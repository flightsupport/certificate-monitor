FROM node:22 AS frontend-builder
WORKDIR /frontend
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    supervisor libicu-dev unzip zip libzip-dev git \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install -j$(nproc) intl zip

WORKDIR /var/www/html

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY --chown=www-data:www-data . /var/www/html
COPY --from=frontend-builder /frontend/public /var/www/html/public
RUN composer install --no-interaction --no-ansi --no-progress --optimize-autoloader

COPY docker/conf/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

COPY entryfile.sh /entryfile.sh
RUN chmod +x /entryfile.sh

USER www-data
ENTRYPOINT ["/entryfile.sh"]

USER root

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
