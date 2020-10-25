FROM php:7.4.11-fpm-alpine3.12

ARG APP_ENV
ARG DB_HOST
ARG DB_USER
ARG DB_PWD
ARG DB_NAME
ARG DB_PORT
ARG DB_VERSION
ARG DB_DRIVER

COPY ./default.conf /etc/nginx/conf.d/default.conf
COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY ./start.sh /root
COPY .env /root
COPY doctrine.yaml /root
RUN chmod +x /root/start.sh
RUN apk add nginx git
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable pdo_mysql
RUN echo "daemon off;" >> /etc/nginx/nginx.conf
WORKDIR /var/www/
RUN rm -rf * ; git clone https://github.com/VGzsysadm/namesend.git
RUN mkdir -p /run/nginx
RUN sed -i "s/APP_ENV=/APP_ENV=$APP_ENV/g" /root/.env
RUN sed -i "s/dbname: DB_NAME/dbname: $DB_NAME/g" /root/doctrine.yaml
RUN sed -i "s/host: DB_HOST/host: $DB_HOST/g" /root/doctrine.yaml
RUN sed -i "s/port: DB_PORT/port: $DB_PORT/g" /root/doctrine.yaml
RUN sed -i "s/user: DB_USER/user: $DB_USER/g" /root/doctrine.yaml
RUN sed -i "s/password: DB_PWD/password: $DB_PWD/g" /root/doctrine.yaml
RUN sed -i "s/driver: DB_DRIVER/driver: $DB_DRIVER/g" /root/doctrine.yaml
RUN echo "DATABASE_URL=mysql://$DB_USER:$DB_PWD@$DB_HOST:$DB_PORT/$DB_NAME?serverVersion=$DB_VERSION" >> /root/.env
CMD ["/root/start.sh"]