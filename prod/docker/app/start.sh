#!/bin/bash

PROJECT_ID=$GOOGLE_CLOUD_PROJECT
REGION=$GOOGLE_CLOUD_REGION
INSTANCE_NAME=$SQL_INSTANCE_NAME

INSTANCE_CONNECTION_NAME="$PROJECT_ID:$REGION:$INSTANCE_NAME"

/var/www/html/prod/docker/app/cloud-sql-proxy $INSTANCE_CONNECTION_NAME &

php /var/www/html/prod/docker/app/setup/init-script.php &

exec apache2-foreground