FROM php:7.4.11-fpm-alpine3.12

ARG APP_ENV
ARG DB_HOST
ARG DB_USER
ARG DB_PWD
ARG DB_NAME
ARG DB_PORT
ARG DB_VERSION

COPY ./default.conf /etc/nginx/conf.d/default.conf
COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY ./start.sh /root
COPY .env /root
RUN chmod +x /root/start.sh
RUN apk add nginx git php-pdo
RUN echo "daemon off;" >> /etc/nginx/nginx.conf
WORKDIR /var/www/
RUN rm -rf * ; git clone https://github.com/VGzsysadm/namesend.git
RUN mkdir -p /run/nginx
RUN sed -i "s/APP_ENV=/APP_ENV=$APP_ENV/g" /root/.env
RUN echo "DATABASE_URL=mysql://$DB_USER:$DB_PWD@$DB_HOST:$DB_PORT/$DB_NAME?serverVersion=$DB_VERSION" >> /root/.env
CMD ["/root/start.sh"]