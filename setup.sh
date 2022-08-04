#!/bin/bash

chown -R 1001:1001 ../hot-desking-test5

cp docker-compose.server.yml docker-compose.yml
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
docker compose run --rm artisan migrate
docker compose down
echo DONE

# docker compose run --rm artisan db:seed --class=ResourceSeeder
# docker compose run --rm artisan db:seed --class=UserSeeder
