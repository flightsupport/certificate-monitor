# syntax=docker/dockerfile:1
FROM serversideup/php:8.4-frankenphp AS base

USER root

# Install system dependencies and clean up in one layer
RUN apt-get update && apt-get install -y --no-install-recommends \
htop openssl zip unzip git libonig-dev zlib1g-dev libpng-dev libxml2-dev \
libzip-dev jpegoptim optipng pngquant gifsicle libavif-bin webp \
libfreetype6-dev libjpeg62-turbo-dev libwebp-dev libxpm-dev libmagickwand-dev ghostscript \
default-mysql-client \
&& rm -rf /var/lib/apt/lists/* \
&& printf '[client]\nssl-verify-server-cert=0\n' > /etc/mysql/mariadb.conf.d/99-no-ssl-verify.cnf

# Install PHP extensions
RUN install-php-extensions \
    gd imagick soap exif mbstring intl bcmath

# Harden ImageMagick policy
COPY .infrastructure/ImageMagick/policy.xml /etc/ImageMagick-7/policy.xml

USER www-data

FROM base AS development

FROM node:24-alpine AS frontend

WORKDIR /var/www/html

# Install dependencies first (better layer caching)
COPY package.json package-lock.json* ./
RUN npm ci

# Copy source and build
COPY --chown=www-data:www-data . ./

RUN npm run build

FROM base AS composer-deps

WORKDIR /var/www/html

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY --chown=www-data:www-data composer.json composer.lock ./
COPY --chown=www-data:www-data . ./

# The image is built with dev dependencies to run tests in CI
RUN composer install \
    --optimize-autoloader \
    --prefer-dist \
    --no-progress \
    --no-interaction

FROM base AS production

WORKDIR /var/www/html

COPY --chown=www-data:www-data . /var/www/html
COPY --from=composer-deps --chown=www-data:www-data /var/www/html/vendor ./vendor
COPY --from=frontend --chown=www-data:www-data /var/www/html/public ./public

RUN cp .env.example .env

USER root
COPY entryfile.sh /entryfile.sh
RUN chmod +x /entryfile.sh

USER www-data
ENTRYPOINT ["/entryfile.sh"]
