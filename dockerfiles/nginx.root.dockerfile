FROM nginx:stable-alpine

RUN sed -i "s/user  nginx/user root/g" /etc/nginx/nginx.conf

RUN mkdir -p /var/www/html

RUN chown laravel:laravel /var/www/html

COPY mysite_com.crt /etc/nginx/ssl/mysite.com/
COPY mysite_com.key /etc/nginx/ssl/mysite.com/

RUN apk update \
&& ln -sf ./nginx/ssl/mysite_com /etc/nginx/ssl

ADD ./nginx/default.conf /etc/nginx/conf.d/