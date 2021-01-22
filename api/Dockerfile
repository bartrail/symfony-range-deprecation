FROM provolution/php:7.4 as build

ARG APP_ENV=prod
ARG COMPOSER_ALLOW_SUPERUSER=1
# for cache clear
ARG DATABASE_URL=mysql://root@mysql:3306/app?charset=utf8mb4&serverVersion=5.7
ARG SENTRY_DSN=''

WORKDIR /var/build
COPY . .

# install packages + run post-install scripts like `cache:warmup`
RUN composer install --optimize-autoloader --no-dev

#####

FROM phusion/baseimage:bionic-1.0.0

ARG DEBIAN_FRONTEND=noninteractive

# set environments
RUN echo prod > /etc/container_environment/APP_ENV
ENV APP_ENV=prod

# install common tools
RUN apt-get update && \
    apt-get install --no-install-recommends -y \
        software-properties-common \
        openssh-client \
        make \
        && \
    rm -r /var/lib/apt/lists/*

# install php
RUN LC_ALL=C.UTF-8 add-apt-repository -y ppa:ondrej/php && \
    apt-get update && \
    apt-get install -y --no-install-recommends \
        php7.4 \
        php7.4-fpm \
        php7.4-cli \
        php7.4-intl \
        php7.4-pdo \
        php7.4-zip \
        php7.4-xml \
        php7.4-mbstring \
        php7.4-json \
        php7.4-curl \
        php7.4-pdo \
        php7.4-mysql \
        php7.4-amqp \
        && \
    rm -r /var/lib/apt/lists/*

# install & setup nginx
RUN apt-get update && \
    apt-get install --no-install-recommends -y \
        nginx \
        && \
    rm -r /var/lib/apt/lists/*

# setup php-fpm
RUN mkdir /etc/service/php-fpm
COPY docker/php-fpm.sh /etc/service/php-fpm/run
RUN chmod +x /etc/service/php-fpm/run

RUN mkdir -p /run/php
COPY docker/fpm-www.conf /etc/php/7.4/fpm/pool.d/www.conf

# setup nginx
RUN mkdir /etc/service/nginx
COPY docker/nginx.sh /etc/service/nginx/run
RUN chmod +x /etc/service/nginx/run

COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx.vhost /etc/nginx/vhosts.d/app.conf

# setup boot script
RUN mkdir -p /etc/my_init.d
COPY docker/boot.sh /etc/my_init.d/boot.sh
RUN chmod +x /etc/my_init.d/boot.sh

# copy project
COPY --from=build /var/build /srv/share

# create jwt keys
RUN mkdir -p /srv/share/config/jwt \
 && openssl genrsa -passout pass:1234 -out /srv/share/config/jwt/private.pem -aes256 4096 \
 && openssl rsa -passin pass:1234 -pubout -in /srv/share/config/jwt/private.pem -out /srv/share/config/jwt/public.pem

# set rights
RUN chmod -R u+rwX,go+rX,go-w /srv/share
RUN chown -R www-data:www-data /srv/share/var

EXPOSE 80

CMD ["/sbin/my_init"]
