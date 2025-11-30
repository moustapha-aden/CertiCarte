#!/bin/sh
# Ne pas utiliser set -e ici car on veut gérer les erreurs manuellement
set +e

echo "🚀 Starting Laravel application on Render..."

# Afficher les variables d'environnement de la base de données (sans le mot de passe)
echo "📋 Database configuration:"
echo "   DB_HOST: ${DB_HOST:-not set}"
echo "   DB_PORT: ${DB_PORT:-not set}"
echo "   DB_DATABASE: ${DB_DATABASE:-not set}"
echo "   DB_USERNAME: ${DB_USERNAME:-not set}"
echo "   DB_CONNECTION: ${DB_CONNECTION:-not set}"

# CRITIQUE : Démarrer Nginx rapidement pour que Render détecte le port
# Les opérations de base de données peuvent être faites après

# Démarrer PHP-FPM en arrière-plan IMMÉDIATEMENT
echo "🚀 Starting PHP-FPM..."
php-fpm -D || {
    echo "❌ Failed to start PHP-FPM"
    exit 1
}

# Configurer Nginx IMMÉDIATEMENT
PORT=${PORT:-10000}
echo "🔧 Configuring Nginx to listen on port $PORT"

# Créer la config Nginx
cat > /etc/nginx/http.d/default.conf <<EOF
server {
    listen $PORT;
    listen [::]:$PORT;
    server_name _;
    root /var/www/html/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    # Gestion des fichiers statiques
    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    # Cache pour les assets Vite
    location /build {
        expires 1y;
        add_header Cache-Control "public, immutable";
        access_log off;
    }

    # Cache pour les images
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        access_log off;
    }

    # Refuser l'accès aux fichiers cachés
    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    # Refuser l'accès aux fichiers sensibles
    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Traitement PHP
    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;

        # Augmenter les limites pour les imports Excel
        fastcgi_read_timeout 300;
        client_max_body_size 50M;
    }

    # Gestion des erreurs
    error_page 404 /index.php;

    # Logs
    access_log /dev/stdout;
    error_log /dev/stderr warn;
}
EOF

# Vérifier la configuration Nginx
echo "🔍 Testing Nginx configuration..."
nginx -t || {
    echo "❌ Nginx configuration test failed"
    exit 1
}

# Faire les opérations de base de données en arrière-plan (non bloquant)
(
    echo "⏳ Waiting for database to be ready (non-blocking)..."
    timeout=30
    count=0
    db_ready=0

    while [ $count -lt $timeout ]; do
        if php -r "
        try {
            \$host = getenv('DB_HOST') ?: 'localhost';
            \$port = getenv('DB_PORT') ?: '3306';
            \$database = getenv('DB_DATABASE') ?: '';
            \$username = getenv('DB_USERNAME') ?: 'root';
            \$password = getenv('DB_PASSWORD') ?: '';

            if (empty(\$host) || empty(\$database)) {
                exit(1);
            }

            \$dsn = 'mysql:host=' . \$host . ';port=' . \$port;
            if (!empty(\$database)) {
                \$dsn .= ';dbname=' . \$database;
            }

            \$pdo = new PDO(\$dsn, \$username, \$password);
            \$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            \$pdo->query('SELECT 1');
            exit(0);
        } catch (Exception \$e) {
            exit(1);
        }
        " 2>/dev/null; then
            db_ready=1
            break
        fi

        sleep 2
        count=$((count+2))
    done

    if [ $db_ready -eq 1 ]; then
        echo "✅ Database is ready!"

        # Générer la clé si nécessaire
        if [ -z "$(grep APP_KEY=.env 2>/dev/null | cut -d '=' -f2)" ] || [ "$(grep APP_KEY=.env 2>/dev/null | cut -d '=' -f2)" = "" ]; then
            echo "🔑 Generating application key..."
            php artisan key:generate --force || true
        fi

        # Exécuter les migrations
        echo "🗄️  Running migrations..."
        php artisan migrate --force || true

        # Créer le lien storage si nécessaire
        if [ ! -L "public/storage" ]; then
            echo "🔗 Creating storage link..."
            php artisan storage:link || true
        fi
    else
        echo "⚠️  Database connection timeout, but Nginx is running"
        echo "⚠️  The application will work but database features may not be available"
    fi
) &

# Démarrer Nginx au PREMIER PLAN (critique pour que Render détecte le port)
echo "✅ Starting Nginx on port $PORT (foreground)..."
echo "🌐 Application should be accessible on port $PORT"
exec nginx -g "daemon off;"
