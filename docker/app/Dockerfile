FROM php:8.4-fpm AS app_builder

WORKDIR /var/www/html

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

RUN apt-get update && apt-get install -y \
    libzip-dev libicu-dev unzip sqlite3 libsqlite3-dev npm \
    && docker-php-ext-install intl pdo pdo_sqlite zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY --chown=www-data:www-data . /var/www/html

RUN chown www-data:www-data /var/www

USER www-data

RUN composer install --no-dev --optimize-autoloader
RUN npm i && npm run build


FROM php:8.4-fpm

COPY --from=app_builder /var/www/html /var/www/html

COPY entryfile.sh /entryfile.sh
RUN chmod +x /entryfile.sh

USER www-data

ENTRYPOINT ["/entryfile.sh"]

CMD ["php-fpm"]
