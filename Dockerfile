
# Use the official PHP image.
# https://hub.docker.com/_/php
FROM php:8.0-apache


ARG DB_HOST
ARG DB_NAME
ARG DB_USER
ARG DB_PASS

ENV DB_HOST=$DB_HOST \
    DB_NAME=$DB_NAME \
    DB_USER=$DB_USER \
    DB_PASS=$DB_PASS 
