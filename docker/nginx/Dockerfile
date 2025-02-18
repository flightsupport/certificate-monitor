FROM nginx:alpine

COPY --chown=www-data:www-data . /var/www/html

WORKDIR /var/www/html

COPY docker/conf/nginx.conf /etc/nginx/conf.d/default.conf
