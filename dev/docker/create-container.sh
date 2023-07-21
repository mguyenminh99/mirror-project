#!/bin/bash

COMPOSE_PROJECT_NAME=`whoami`
STG_USER='stg-user'

if [ $COMPOSE_PROJECT_NAME = $STG_USER ]; then
  HOST_NAME="stg.x-shopping-st.com"
  VARNISH_HOST_NAME=stg-varnish.x-shopping-st.com
  MYSQL_HOST="stg-mysql.x-shopping-st.com"
else
  HOST_NAME=dev-${COMPOSE_PROJECT_NAME}.x-shopping-st.com
  VARNISH_HOST_NAME=dev-${COMPOSE_PROJECT_NAME}-varnish.x-shopping-st.com
  MYSQL_HOST="dev-${COMPOSE_PROJECT_NAME}-mysql.x-shopping-st.com"
fi

MYSQL_DATABASE="mp01"
MYSQL_USER="mpuser"
TZ=Asia/Tokyo
USER_ID=$(id -u)
GROUP_ID=$(id -g)

MYSQL_ROOT_PASSWORD=$(cat /dev/urandom | tr -dc 'a-zA-Z0-9' | fold -w 16 | head -n 1)
MYSQL_PASSWORD=$(cat /dev/urandom | tr -dc 'a-zA-Z0-9' | fold -w 16 | head -n 1)
ADMIN_PASS=$(cat /dev/urandom | tr -dc 'a-zA-Z0-9' | fold -w 16 | head -n 1)

# .envファイルの生成
echo "\
APP_MODE=development
HOST_NAME=${HOST_NAME}
VARNISH_HOST_NAME=${VARNISH_HOST_NAME}
MYSQL_ROOT_PASSWORD=${MYSQL_ROOT_PASSWORD}
MYSQL_DATABASE=${MYSQL_DATABASE}
MYSQL_USER=${MYSQL_USER}
MYSQL_PASSWORD=${MYSQL_PASSWORD}
MYSQL_HOST="${MYSQL_HOST}"
TZ=${TZ}
UID=${USER_ID}
GID=${GROUP_ID}
COMPOSE_PROJECT_NAME=${COMPOSE_PROJECT_NAME}
ADMIN_PASS=${ADMIN_PASS}
" > .env

if ! docker network ls --format '{{.Name}}' | grep -q "^x_shopping_st$"; then
  docker network create x_shopping_st
fi

# リバースプロキシが存在しない場合作成する
docker-compose -p reverse_proxy -f docker-compose-reverse-proxy.yml up -d

# DBビルド・起動後にその他のサービスを起動する
docker-compose up -d
