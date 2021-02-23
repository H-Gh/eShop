#!/bin/sh

cd ../var/www 

php artisan eshop:db:migrate --force --seed
php artisan eshop:db:migrate --database=mysql_test --force
./vendor/bin/phpunit tests
php-fpm
