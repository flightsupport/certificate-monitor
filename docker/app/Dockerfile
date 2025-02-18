FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    supervisor \
    && rm -rf /var/lib/apt/lists/*

COPY --chown=www-data:www-data . /var/www/html
COPY docker/conf/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

COPY entryfile.sh /entryfile.sh
RUN chmod +x /entryfile.sh

USER www-data
ENTRYPOINT ["/entryfile.sh"]

USER root

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
