#!/bin/sh
cd /var/www/namesend && composer install
mv /root/.env /var/www/namesend/.env && mv /root/doctrine.yaml /var/www/namesend/config/packages/doctrine.yaml && composer dump-env prod
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
chmod -R 777 /var/www/namesend/var/cache/prod
chown -R nginx. /var/www/namesend/var/cache/prod

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
done

