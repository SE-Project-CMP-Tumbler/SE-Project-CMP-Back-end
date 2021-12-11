#!/bin/sh

composer update

php artisan storage:link

php artisan migrate --seed

php artisan passport:install

php artisan serve --host=0.0.0.0 --port=8181
