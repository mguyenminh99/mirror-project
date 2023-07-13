FROM php:7.2-apache-buster

ARG APP_MODE
ARG UID
ARG GID

RUN apt-get update

RUN apt-get install -y mariadb-client \
                       libnice-dev \
                       libpng-dev \
                       libjpeg-dev \
                       libwebp-dev \
                       libfreetype6-dev \
                       libxslt-dev \
                       libzip-dev \
                       libxml2 \
                       cron \
                       unzip

RUN docker-php-ext-install bcmath \
                           mysqli \
                           pdo_mysql \
                           soap \
                           zip \
                           intl \
                           xsl \
                           opcache \
                           sockets

RUN docker-php-ext-configure gd  --with-freetype-dir=/usr/include/ \
                                 --with-jpeg-dir=/usr/include/ \
                                 --with-png-dir=/usr/include/ \
                                 --with-webp-dir=/usr/include/

RUN docker-php-ext-install gd

#install xdebug
RUN if [ "$APP_MODE" = 'development' ]; then \
  pecl install xdebug-2.6.0 && \
  docker-php-ext-enable xdebug; \
fi;

# apache実行ユーザー作成
RUN groupadd -g $GID x-shopping-st && useradd -r -m -u $UID -g $GID x-shopping-st

ENV APACHE_RUN_USER x-shopping-st
ENV APACHE_RUN_GROUP x-shopping-st

COPY dev/docker/apache-php/apache2/apache2.conf /etc/apache2/apache2.conf
COPY dev/docker/apache-php/php/php.ini /usr/local/etc/php/php.ini
COPY dev/docker/apache-php/php/docker-php-ext-opcache.ini /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini

WORKDIR /etc/apache2/mods-enabled
RUN ln -s ../mods-available/rewrite.load

# マルチステージビルドでcomposerをインストール
COPY --from=composer:1.10 /usr/bin/composer /usr/bin/composer

RUN sed -i 's/	DocumentRoot \/var\/www\/html/	DocumentRoot \/var\/www\/html\/pub/' /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

USER x-shopping-st