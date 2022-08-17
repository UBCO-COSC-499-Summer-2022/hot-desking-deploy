FROM nginx:stable-alpine

RUN sed -i "s/user  nginx/user root/g" /etc/nginx/nginx.conf

RUN mkdir -p /var/www/html

ADD ./nginx/default.conf /etc/nginx/conf.d/