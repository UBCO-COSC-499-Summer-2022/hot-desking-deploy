#!/bin/bash

cp docker-compose.mac.yml docker-compose.yml
ls
cd src/
cp .env.example .env
ls
cd ..
ls
docker compose down
docker compose up -d --build site
docker compose run --rm composer update
docker compose run --rm npm install
docker compose run --rm npm run dev
echo DONE
# docker compose run --rm artisan migrate:refresh
# docker compose run --rm artisan db:seed --class=ResourceSeeder
# docker compose run --rm artisan db:seed --class=UserSeeder
