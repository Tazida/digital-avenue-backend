# syntax=docker/dockerfile:1
#
# Production image: Laravel served by FrankenPHP.
# Local development uses Laravel Sail instead (see compose.yaml and README.md).

FROM dunglas/frankenphp:1-php8.5

WORKDIR /app

RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip \
    && rm -rf /var/lib/apt/lists/*

RUN install-php-extensions \
    pdo_pgsql \
    pgsql \
    intl \
    zip \
    gd \
    exif \
    bcmath \
    pcntl \
    opcache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY docker/php/app.ini $PHP_INI_DIR/conf.d/zz-app.ini
COPY docker/php/prod.ini $PHP_INI_DIR/conf.d/zz-prod.ini

# Plain HTTP on port 80; Caddy in front of this container handles HTTPS.
ENV SERVER_NAME=:80
ENV APP_ENV=production

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-interaction

COPY . .
RUN composer dump-autoload --optimize --no-dev \
    && php artisan filament:assets \
    && chown -R www-data:www-data storage bootstrap/cache
