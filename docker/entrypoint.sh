#!/bin/sh
set -e

echo "🚀 Starting Laravel application setup..."

# Attendre que la base de données soit prête
echo "⏳ Waiting for database to be ready..."
until php -r "
try {
    \$pdo = new PDO('mysql:host=${DB_HOST:-db};port=${DB_PORT:-3306};dbname=${DB_DATABASE:-certicarte}', '${DB_USERNAME:-certicarte_user}', '${DB_PASSWORD:-certicarte_password}');
    \$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    \$pdo->query('SELECT 1');
    echo 'Database is ready';
    exit(0);
} catch (PDOException \$e) {
    exit(1);
}
" 2>/dev/null; do
    echo "Database is unavailable - sleeping"
    sleep 2
done

echo "✅ Database is ready!"

# Installer les dépendances si nécessaire
if [ ! -d "vendor" ]; then
    echo "📦 Installing Composer dependencies..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# Vérifier si le fichier .env existe
if [ ! -f ".env" ]; then
    echo "📝 Creating .env file from .env.example..."
    if [ -f ".env.example" ]; then
        cp .env.example .env
    else
        echo "⚠️  Warning: .env.example not found, creating basic .env file"
        cat > .env <<EOF
APP_NAME=CertiCarte
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=${DB_HOST:-db}
DB_PORT=${DB_PORT:-3306}
DB_DATABASE=${DB_DATABASE:-certicarte}
DB_USERNAME=${DB_USERNAME:-certicarte_user}
DB_PASSWORD=${DB_PASSWORD:-certicarte_password}

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=database

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null

FILESYSTEM_DISK=local
EOF
    fi
fi

# Générer la clé d'application si nécessaire
if [ -z "$(grep APP_KEY=.env 2>/dev/null | cut -d '=' -f2)" ] || [ "$(grep APP_KEY=.env 2>/dev/null | cut -d '=' -f2)" = "" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
fi

# Vérifier et créer le lien symbolique storage
if [ ! -L "public/storage" ]; then
    echo "🔗 Creating storage symbolic link..."
    php artisan storage:link || true
fi

# Exécuter les migrations
echo "🗄️  Running database migrations..."
php artisan migrate --force || true

# Optimiser l'application pour la production
if [ "${APP_ENV:-production}" = "production" ]; then
    echo "⚡ Optimizing application for production..."
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
fi

# Fixer les permissions
echo "🔒 Setting correct permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache || true
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache || true

echo "✅ Application setup completed!"

# Exécuter la commande passée en argument ou PHP-FPM par défaut
exec "$@"

