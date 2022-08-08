#!/bin/bash

docker compose down
docker compose up -d --build site
docker compose run --rm composer update
docker compose run --rm npm install
docker compose run --rm npm run prod
docker compose run --rm artisan migrate --force
# add a single cron configuration entry to our server that runs the schedule:run command every minute.
cd .. 
* * * * * cd test-pull5/ && php artisan schedule:run >> /dev/null 2>&1
echo Server is LIVE