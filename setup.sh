#!/bin/bash

cp docker-compose.root.yml docker-compose.yml
ls
cd src/
cp .env.production .env
ls
cd ..
ls
docker compose down
docker compose build --no-cache
docker compose down
docker compose up -d --build site
docker compose run --rm composer update
docker compose run --rm npm install
docker compose run --rm npm run prod
# TODO REMOVE FORCE FLAG
docker compose run --rm artisan migrate --force
docker compose run --rm artisan db:seed --class=DBSeeder --force
docker compose up -d --build cron