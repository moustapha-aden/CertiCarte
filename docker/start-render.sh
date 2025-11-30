#!/bin/sh
set -e

echo "🚀 Starting Laravel application on Render..."

# Attendre que la base de données soit prête (avec timeout)
echo "⏳ Waiting for database..."
timeout=60
count=0
while ! php -r "
try {
    \$pdo = new PDO('mysql:host='.getenv('DB_HOST').';port='.getenv('DB_PORT').';dbname='.getenv('DB_DATABASE'), 
                    getenv('DB_USERNAME'), 
                    getenv('DB_PASSWORD'));
    \$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    \$pdo->query('SELECT 1');
    exit(0);
} catch (Exception \$e) {
    exit(1);
}
" 2>/dev/null; do
    if [ $count -ge $timeout ]; then
        echo "⚠️  Database connection timeout, continuing anyway..."
        break
    fi
    echo "Database unavailable, retrying... ($count/$timeout)"
    sleep 2
    count=$((count+2))
done

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

# Démarrer PHP-FPM en arrière-plan
echo "🚀 Starting PHP-FPM..."
php-fpm -D

# Remplacer PORT dans la config Nginx avec la variable d'environnement
PORT=${PORT:-10000}
echo "Setting Nginx port to $PORT"
# Remplacer le port dans la config Nginx
sed -i "s/listen 10000;/listen $PORT;/g" /etc/nginx/http.d/default.conf
sed -i "s/listen \[::\]:10000;/listen [::]:$PORT;/g" /etc/nginx/http.d/default.conf

# Démarrer Nginx au premier plan
echo "✅ Starting Nginx on port $PORT..."
exec nginx -g "daemon off;"

