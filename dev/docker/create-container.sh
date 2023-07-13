#!/bin/bash

COMPOSE_PROJECT_NAME=`whoami`
STG_USER='stg-user'

if [ $COMPOSE_PROJECT_NAME = $STG_USER ]; then

  HOST_NAME="stg.x-shopping-st.com"
  VARNISH_HOST_NAME=stg-varnish.x-shopping-st.com

else

  HOST_NAME=dev-${COMPOSE_PROJECT_NAME}.x-shopping-st.com
  VARNISH_HOST_NAME=dev-${COMPOSE_PROJECT_NAME}-varnish.x-shopping-st.com

fi

docker ps -a | grep ${COMPOSE_PROJECT_NAME}_
if [ $? = 0 ]; then
    echo "${COMPOSE_PROJECT_NAME}のコンテナはすでに存在します。\nコンテナを起動する場合は docker-compose up -d {サービス名} で起動してください。"
    exit 1
fi

MYSQL_ROOT_PASSWORD="root"
MYSQL_DATABASE="mp01"
MYSQL_USER="mpuser"
MYSQL_PASSWORD="mppass"
MYSQL_HOST="dev-${COMPOSE_PROJECT_NAME}-mysql.x-shopping-st.com"

TZ=Asia/Tokyo

USER_ID=$(id -u)
GROUP_ID=$(id -g)

# .envファイルの生成
#if [[ ! -e "./.env" ]]; then
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
" > .env
#fi

# リバースプロキシが存在しない場合作成する
docker ps -a | grep reverse_proxy_nginx_1
if [ ! $? = 0 ]; then
  docker-compose -p reverse_proxy -f docker-compose-reverse-proxy.yml up -d
fi

# DBビルド・起動後にその他のサービスを起動する
docker-compose up -d
