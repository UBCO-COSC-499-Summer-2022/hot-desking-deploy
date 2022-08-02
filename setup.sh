#!/bin/bash

cp docker-compose.example.yml docker-compose.yml
ls
cd src/
cp .env.example .env
ls
cd ..
ls
docker up -d --build site
docker run --rm composer update
docker run --rm npm install
docker run --rm npm run dev
docker run --rm artisan migrate:refresh
docker run --rm artisan db:seed --class=ResourceSeeder
docker run --rm artisan db:seed --class=UserSeeder
