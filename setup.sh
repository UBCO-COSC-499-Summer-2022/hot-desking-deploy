#!/bin/bash

cd src/
docker-compose up -d --build site
docker-compose run --rm composer update
docker-compose run --rm npm install
docker-compose run --rm npm run prod
docker-compose run --rm artisan migrate:refresh
docker-compose run --rm artisan db:seed --class=UserSeeder
docker-compose run --rm artisan db:seed --class=ResourceSeeder
