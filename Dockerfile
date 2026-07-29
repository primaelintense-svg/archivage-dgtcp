FROM webdevops/php-nginx:8.3-alpine

ENV WEB_DOCUMENT_ROOT=/app/public
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr

COPY . /app

WORKDIR /app

RUN composer install --no-dev --optimize-autoloader --no-interaction \
    && mkdir -p storage/framework/views storage/framework/cache/data storage/framework/sessions storage/logs bootstrap/cache \
    && chown -R application:application storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && chmod +x /app/scripts/00-laravel-deploy.sh \
    && cp /app/scripts/00-laravel-deploy.sh /opt/docker/provision/entrypoint.d/20-laravel-deploy.sh