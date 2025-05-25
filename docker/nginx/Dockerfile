FROM node:22 AS frontend-builder
WORKDIR /frontend
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

FROM nginx:alpine
COPY --from=frontend-builder --chown=www-data:www-data /frontend/public /var/www/html/public
WORKDIR /var/www/html
COPY docker/conf/nginx.conf /etc/nginx/conf.d/default.conf
