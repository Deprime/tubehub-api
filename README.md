# TubeHub api

TubeHub is a video hosting and streaming platform.

## Requirements

* php > 8
* composer
* MySQL || Postgres

## Under the hood

* laravel 9
* ffmpeg

## Installation

* clone repository
* navigate to cloned repo directory
* run ```composer install```
* make a copy of **.env.example** and name it **.env**
* change DB connection settings:
  * DB_CONNECTION=mysql
  * DB_HOST=127.0.0.1
  * DB_PORT=3306
  * DB_DATABASE='db_name'
  * DB_USERNAME='db_user'
  * DB_PASSWORD='db_password'
* run ```php artisan migrate```
* run ```php db:seed --class UserSeeder```
