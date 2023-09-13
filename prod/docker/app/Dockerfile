FROM ubuntu:18.04

ENV TZ=Asia/Tokyo

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone && \
    apt-get update && apt-get install -y php7.2 \
                                         php7.2-bcmath \
                                         php7.2-soap \
                                         php7.2-xsl \
                                         php7.2-curl \
                                         php7.2-xml \
                                         php7.2-gd \
                                         php7.2-intl \
                                         php7.2-mbstring \
                                         php7.2-mysql \
                                         php7.2-soap \
                                         php7.2-zip \
                                         supervisor \
                                         curl

COPY ./supervisord/conf.d/supervisord_include.conf /etc/supervisor/conf.d/supervisord_include.conf

# マルチステージビルドでcomposerをインストール
COPY --from=composer:1.10 /usr/bin/composer /usr/bin/composer

RUN curl -s https://packagecloud.io/install/repositories/varnishcache/varnish63/script.deb.sh | bash && \
    DEBIAN_FRONTEND=noninteractive apt-get -y install varnish

COPY ./varnish/default.vcl /etc/varnish/default.vcl

# apache実行ユーザー、supervisord管理ユーザー作成
RUN groupadd -g 1001 x-shopping-st && useradd -r -m -u 1001 -g 1001 x-shopping-st
ENV APACHE_RUN_USER x-shopping-st
ENV APACHE_RUN_GROUP x-shopping-st

COPY ./apache2/apache2.conf ./php/php.ini /etc/apache2/
COPY ./php/opcache.ini /etc/php/7.2/mods-available/opcache.ini

WORKDIR /etc/apache2/mods-enabled
RUN ln -s ../mods-available/rewrite.load && \
    sed -i s/"Listen 80"/"Listen 8080"/ /etc/apache2/ports.conf && \
    sed -i s/"<VirtualHost \*:80>"/"<VirtualHost \*:8080>"/ /etc/apache2/sites-available/000-default.conf && \
    sed -i 's/	DocumentRoot \/var\/www\/html/	DocumentRoot \/var\/www\/html\/pub/' /etc/apache2/sites-available/000-default.conf && \
    chown -R x-shopping-st:x-shopping-st /var/log/apache2 && \
    chown -R x-shopping-st:x-shopping-st /var/run/apache2 && \
    chown -R varnish:varnish /var/lib/varnish

WORKDIR /var/www/html

CMD ["/usr/bin/supervisord"]
