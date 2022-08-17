#!/bin/bash

cp docker-compose.root.yml docker-compose.yml
ls
cd src/
cp .env.production .env
ls
cd ..
ls
docker compose down
docker compose up -d --build site
docker compose run --rm composer update
docker compose run --rm npm install
docker compose run --rm npm run prod
docker compose run --rm artisan migrate --force
docker compose run --rm artisan cache:clear
docker compose run --rm artisan config:clear
docker compose run --rm artisan config:cache
docker compose up -d --build cron