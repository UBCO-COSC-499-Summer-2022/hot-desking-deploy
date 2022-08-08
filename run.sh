#!/bin/bash

docker compose down
docker compose up -d --build site
docker compose run --rm composer update
docker compose run --rm npm install
docker compose run --rm npm run prod
# TODO REMOVE FORCE FLAG
docker compose run --rm artisan migrate --force
# clear schedule cahce
docker compose run --rm artisan cache:clear
docker compose run --rm artisan queue:restart
docker compose run --rm schedule:clear-cache
# add a single cron configuration entry to our server that runs the schedule:run command every minute.
* * * * * docker compose run --rm artisan schedule:run >> /dev/null 2>&1
echo Server is LIVE