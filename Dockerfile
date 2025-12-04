FROM webdevops/php-nginx:8.2

WORKDIR /app

# Copy application code
COPY . /app

# Install PHP dependencies for production
RUN composer install --no-dev --optimize-autoloader

# Ensure correct permissions for Laravel writable directories
RUN chown -R application:application /app/storage /app/bootstrap/cache \
    && chmod -R 775 /app/storage /app/bootstrap/cache

# Nginx / PHP-FPM configuration
ENV WEB_DOCUMENT_ROOT=/app/public

# The container will listen on this port (configure the same PORT on Railway)
EXPOSE 8080


