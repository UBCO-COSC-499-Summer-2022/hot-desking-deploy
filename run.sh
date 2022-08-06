#!/bin/bash

docker compose down
docker compose up -d --build site
docker compose run --rm composer update
docker compose run --rm npm install
docker compose run --rm npm run prod
# TODO REMOVE FORCE FLAG
docker compose run --rm artisan migrate --force
echo Server is LIVE