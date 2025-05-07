FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    supervisor libicu-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install -j$(nproc) intl

COPY --chown=www-data:www-data . /var/www/html
COPY docker/conf/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

COPY entryfile.sh /entryfile.sh
RUN chmod +x /entryfile.sh

USER www-data
ENTRYPOINT ["/entryfile.sh"]

USER root

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
