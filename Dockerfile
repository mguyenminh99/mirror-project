FROM php:7.2-apache-buster

ARG ADOBE_API_KEY
ARG ADOBE_API_PASS
ARG SEND_GRID_API_ACCOUNT
ARG SEND_GRID_API_KEY
ARG GOOGLE_CLOUD_PROJECT
ARG GOOGLE_CLOUD_REGION
ARG SQL_INSTANCE_NAME

ENV PROJECT_ROOT=/var/www/html
ENV TZ=Asia/Tokyo

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

# apache実行ユーザー作成
RUN groupadd -g 1001 x-shopping-st && useradd -r -m -u 1001 -g 1001 x-shopping-st

ENV APACHE_RUN_USER x-shopping-st
ENV APACHE_RUN_GROUP x-shopping-st

COPY prod/docker/app/apache2/apache2.conf /etc/apache2/apache2.conf
COPY prod/docker/app/php/php.ini /usr/local/etc/php/php.ini
COPY prod/docker/app/php/docker-php-ext-opcache.ini /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini

WORKDIR /etc/apache2/mods-enabled
RUN ln -s ../mods-available/rewrite.load

# マルチステージビルドでcomposerをインストール
COPY --from=composer:1.10 /usr/bin/composer /usr/bin/composer

RUN sed -i 's/	DocumentRoot \/var\/www\/html/	DocumentRoot \/var\/www\/html\/pub/' /etc/apache2/sites-available/000-default.conf && sed -i 's/VirtualHost \*:80/VirtualHost \*:8080/' /etc/apache2/sites-enabled/000-default.conf && sed -i 's/Listen 80/Listen 8080/' /etc/apache2/ports.conf

COPY . $PROJECT_ROOT

RUN chown -R x-shopping-st:x-shopping-st $PROJECT_ROOT

WORKDIR $PROJECT_ROOT

RUN cp -pi ./auth.json.sample ./auth.json && sed -i "s/\"username\": \"<public-key>\"/\"username\": \"$ADOBE_API_KEY\"/" ./auth.json && sed -i "s/\"password\": \"<private-key>\"/\"password\": \"$ADOBE_API_PASS\"/" ./auth.json

USER x-shopping-st

RUN composer update

USER root

WORKDIR $PROJECT_ROOT

RUN find var generated vendor pub/static pub/media app/etc -type f -exec chmod g+w {} + && find var generated vendor pub/static pub/media app/etc -type d -exec chmod g+ws {} + && chmod u+x bin/magento && chmod 777 -R var generated app/etc && chmod 777 -R pub

USER x-shopping-st

CMD ["bash", "/var/www/html/prod/docker/app/start.sh"]