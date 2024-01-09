# laravel notes api

User creating notes, adding files to notes. Work with api can be seen in the attached tests

# Installation
+ To get started, the following steps needs to be taken:
+ Clone the repo.
+ `cd laravel10-notes-api` to the project directory.
+ `cp src/.env.example src/.env` to use env config file
+ Run `docker-compose up -d` to start the containers.
+ Run `docker-compose exec php-fpm bash`
+ Run `composer install`
+ Run `php artisan migrate`
+ Run `php artisan jwt:secret`
+ Run `php artisan test`