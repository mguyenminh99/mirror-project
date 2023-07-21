#!/bin/bash

COMPOSE_PROJECT_NAME=`grep ^COMPOSE_PROJECT_NAME .env | cut -d = -f 2`
HOST_NAME=`grep ^HOST_NAME .env | cut -d = -f 2`
MYSQL_ROOT_PASSWORD=`grep ^MYSQL_ROOT_PASSWORD .env | cut -d = -f 2`
MYSQL_DATABASE=`grep ^MYSQL_DATABASE .env | cut -d = -f 2`
MYSQL_USER=`grep ^MYSQL_USER .env | cut -d = -f 2`
MYSQL_PASSWORD=`grep ^MYSQL_PASSWORD .env | cut -d = -f 2`
MYSQL_HOST=`grep ^MYSQL_HOST .env | cut -d = -f 2`
MYSQL_USER=`grep ^MYSQL_USER .env | cut -d = -f 2`
ADMIN_PASS=`grep ^ADMIN_PASS .env | cut -d = -f 2`

ADMIN_FIRSTNAME=xs-admin
ADMIN_LASTNAME=xs-admin
ADMIN_MAIL=xs-admin@x-shopping-st.com
ADMIN_USER=xs-admin

# API認証情報の設定
if [ ! -e "../../auth.json" ]; then

  echo -n Adobe APIのユーザーキーを入力してください:
  read ADOBE_API_KEY

  echo -n Adobe APIのパスワードを入力してください:
  read ADOBE_API_PASS

  cp -pi ../../auth.json.sample ../../auth.json
  sed -i "s/\"username\": \"<public-key>\"/\"username\": \"${ADOBE_API_KEY}\"/" ../../auth.json
  sed -i "s/\"password\": \"<private-key>\"/\"password\": \"${ADOBE_API_PASS}\"/" ../../auth.json
  echo 'auth.jsonファイルをプロジェクトルートに作成しました。'
fi

# sendgridのapiキーが.envに記述されていない場合追記する
grep "SEND_GRID_API_KEY" .env >> /dev/null
if [ $? = 1 ]; then
  echo -n sendgridのAPIキーを入力してください:
  read SEND_GRID_API_KEY
  echo "SEND_GRID_API_KEY=${SEND_GRID_API_KEY}" >> .env
fi

# composerパッケージインストール
docker exec ${COMPOSE_PROJECT_NAME}_apache_php_1 bash -c "composer install"

echo "magentoのインストールを開始します。"
docker exec ${COMPOSE_PROJECT_NAME}_apache_php_1 bash -c "./bin/magento setup:install --base-url=http://${HOST_NAME} --base-url-secure=https://${HOST_NAME} --db-host=${MYSQL_HOST} --db-name=${MYSQL_DATABASE}  --db-user=${MYSQL_USER} --db-password=${MYSQL_PASSWORD} --admin-firstname=${ADMIN_FIRSTNAME} --admin-lastname=${ADMIN_LASTNAME} --admin-email=${ADMIN_MAIL} --admin-user=${ADMIN_USER} --admin-password=${ADMIN_PASS} --language=ja_JP --currency=JPY --timezone=Asia/Tokyo --backend-frontname=x_shopping_st --use-secure=1 --use-secure-admin=1"

docker exec ${COMPOSE_PROJECT_NAME}_mysql_1 mysql -u ${MYSQL_USER} -p${MYSQL_PASSWORD} -e"insert into ${MYSQL_DATABASE}.core_config_data (scope, scope_id, path, value)
values ('default','0','system/full_page_cache/caching_application','2'),
('default','0','system/full_page_cache/varnish/access_list','${HOST_NAME}'),
('default','0','system/full_page_cache/varnish/backend_host','${HOST_NAME}'),
('default','0','system/full_page_cache/varnish/backend_port','80'),
('default','0','system/full_page_cache/varnish/grace_period','300');"

docker exec ${COMPOSE_PROJECT_NAME}_apache_php_1 bash -c "sed -i 's/files/db/g' ./app/etc/env.php"
docker exec ${COMPOSE_PROJECT_NAME}_apache_php_1 bash -c "find var generated vendor pub/static pub/media app/etc -type f -exec chmod g+w {} +"
docker exec ${COMPOSE_PROJECT_NAME}_apache_php_1 bash -c "find var generated vendor pub/static pub/media app/etc -type d -exec chmod g+ws {} +"
docker exec ${COMPOSE_PROJECT_NAME}_apache_php_1 bash -c "chmod u+x bin/magento"
docker exec ${COMPOSE_PROJECT_NAME}_apache_php_1 bash -c "chmod 777 -R var generated app/etc"
docker exec ${COMPOSE_PROJECT_NAME}_apache_php_1 bash -c "chmod 777 -R pub"

echo "以下のレコードを各人PCのhostsファイルに追加してください。"
echo "社内からアクセスする場合 --> 10.0.2.2 ${HOST_NAME}"
echo "社外からアクセスする場合 --> 221.253.81.61 ${HOST_NAME}"
echo "追加が完了したらENTERを押して先に進んでください。"
read RESULT

# インストール後の状態でvarnishのヘルスチェックを行うためコンテナ再起動
docker-compose restart
docker restart reverse_proxy_nginx_1

echo "管理画面 : https://${HOST_NAME}/x_shopping_st"
echo "管理ユーザー : ${ADMIN_USER}"
echo "パスワードは.envファイルのADMIN_PASSに設定されています。キーパスに登録してください。"