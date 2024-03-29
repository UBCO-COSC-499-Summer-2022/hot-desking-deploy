FROM php:8-fpm-alpine

RUN set -ex \
    && apk --no-cache add \
    postgresql-dev

RUN docker-php-ext-install pdo pdo_pgsql

COPY crontab /etc/crontabs/root

CMD [ "crond", "-f" ]