FROM richarvey/nginx-php-fpm:3.1.6
COPY . .

# Config de l'image
ENV SKIP_COMPOSER=0
ENV WEBROOT=/var/www/html/public
ENV PHP_ERRORS_STDERR=1
ENV RUN_SCRIPTS=1
ENV REAL_IP_HEADER=1

# Config Laravel (production)
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr

RUN chmod +x scripts/00-laravel-deploy.sh
CMD ["/start.sh"]