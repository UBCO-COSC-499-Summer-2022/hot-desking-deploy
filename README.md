# hot-desking-deploy

[![Build Status](https://droneci.ok.ubc.ca/api/badges/UBCO-COSC-499-Summer-2022/hot-desking-deploy/status.svg)](https://droneci.ok.ubc.ca/UBCO-COSC-499-Summer-2022/hot-desking-deploy)


## Setting up Locally:
1. Ensure that you have Docker set up on your computer (Docker setup link: https://docs.docker.com/get-started/ ).
    1. If you are on Windows we recommend that you download the Ubuntu 20.04 distribution from the Microsoft Store
1. Download the repository
1. In terminal change directories into the project folder
1. Depending on your OS run one of the commands listed below:
    1. (**Mac/Linux**):  ```cp docker-compose.mac.yml docker-compose.yml```
    1. (**Windows**): ```cp docker-compose.example.yml docker-compose.yml``` **OR** ```cp docker-compose.root.yml docker-compose.yml```
1. **Side note**: Inside the newly created docker-compose.yml file ensure that the Docker service MailHog is not commented out. (This is used for testing emails that are sent out)
1. Change directories into the /src folder, then run ```cp .env.example .env```
1. From the /src folder run the following commands:
    1. * ```docker compose up -d --build site```
    1. * ```docker compose run --rm composer update```
    1. * ```docker compose run --rm npm install```
    1. * ```docker compose run --rm npm run dev```
    1. * ```docker compose run --rm artisan migrate```
    1. * ```docker compose run --rm artisan db:seed --class=DBSeeder```
1. The site should now be running at: http://localhost
1. To access Mailhog use the URL: http://localhost:8025
Starting and stopping the project after it has been built:
* (**Start**): ```docker compose up -d```
* (**Stop**): ```docker compose down```
