# syntax=docker/dockerfile:1.7
#
# MegaShop — Laravel 12 / PHP 8.3
#
# Targets:
#   app  -> PHP-FPM runtime (default)
#   web  -> Nginx serving /var/www/html/public
#
# Usage: docker compose build
#

############################
# app — PHP-FPM runtime
############################
FROM php:8.3-fpm-bookworm AS app

ARG INSTALL_DEV=true

ENV COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_NO_INTERACTION=1 \
    COMPOSER_MEMORY_LIMIT=-1

RUN apt-get update && apt-get install -y --no-install-recommends \
        ca-certificates \
        curl \
        git \
        unzip \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libwebp-dev \
        libzip-dev \
        libicu-dev \
        libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j"$(nproc)" \
        bcmath \
        exif \
        gd \
        intl \
        mbstring \
        opcache \
        pcntl \
        pdo_mysql \
        zip \
    && rm -rf /var/lib/apt/lists/* /tmp/pear

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY docker/php/php.ini        /usr/local/etc/php/conf.d/zz-app.ini
COPY docker/php/opcache.ini    /usr/local/etc/php/conf.d/zz-opcache.ini
COPY docker/php/fpm-pool.conf  /usr/local/etc/php-fpm.d/zz-app.conf

WORKDIR /var/www/html

# Composer layer is cached as long as composer.json/lock do not change.
COPY composer.json composer.lock ./

RUN if [ "$INSTALL_DEV" = "false" ]; then \
        composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-progress; \
    else \
        composer install --no-scripts --no-autoloader --prefer-dist --no-progress; \
    fi

COPY . .

# dump-autoload runs `artisan package:discover` (post-autoload-dump script).
RUN if [ "$INSTALL_DEV" = "false" ]; then \
        composer dump-autoload --no-dev --optimize; \
    else \
        composer dump-autoload --optimize; \
    fi \
    && mkdir -p \
        bootstrap/cache \
        storage/app/public \
        storage/debugbar \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
    && ln -sfn ../storage/app/public public/storage \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R ug+rwX storage bootstrap/cache

COPY docker/entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

EXPOSE 9000

ENTRYPOINT ["/usr/local/bin/docker-entrypoint"]
CMD ["php-fpm", "-F"]

############################
# web — Nginx
############################
FROM nginx:1.27-alpine AS web

RUN rm -f /etc/nginx/conf.d/default.conf

COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Only static assets are needed here; uploads come from the shared storage volume.
COPY public /var/www/html/public
RUN mkdir -p /var/www/html/storage/app/public \
    && ln -sfn ../storage/app/public /var/www/html/public/storage

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
