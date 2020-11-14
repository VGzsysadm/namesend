#!/bin/sh
until nc -z -v -w30 mysql-db 3306
do
  echo "Waiting for database connection..."
  sleep 5
done
cd /var/www/namesend && composer install
mv /root/.env /var/www/namesend/.env && mv /root/doctrine.yaml /var/www/namesend/config/packages/doctrine.yaml
sed -i "s/APP_ENV=/APP_ENV=prod/g" .env
composer dump-env prod
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
chmod -R 777 /var/www/namesend/var/cache/prod
openssl genrsa -out /var/www/namesend/config/keys/private.key 2048
openssl rsa -in /var/www/namesend/config/keys/private.key -outform PEM -pubout -out /var/www/namesend/config/keys/public.pub
chmod 755 /var/www/namesend/config/keys/private.key
chmod 755 /var/www/namesend/config/keys/public.pub
ln -sf /proc/1/fd/1 /var/log/nginx/namesend_error.log
ln -sf /proc/1/fd/1 /var/log/nginx/namesend_access.log

php-fpm -D
status=$?
if [ $status -ne 0 ]; then
  echo "Failed to start my_first_process: $status"
  exit $status
fi

nginx
status=$?
if [ $status -ne 0 ]; then
  echo "Failed to start my_second_process: $status"
  exit $status
fi

while sleep 60; do
  ps aux |grep php-fpm |grep -q -v grep
  PROCESS_1_STATUS=$?
  ps aux |grep nginx |grep -q -v grep
  PROCESS_2_STATUS=$?

  if [ $PROCESS_1_STATUS -ne 0 -o $PROCESS_2_STATUS -ne 0 ]; then
    echo "One of the processes has already exited."
    exit 1
  fi

