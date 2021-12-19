#!/bin/sh

composer update

php artisan storage:link

# php artisan migrate:fresh

php artisan db:seed

php artisan passport:install

php artisan serve --host=0.0.0.0 --port=8181
