FROM us-central1-docker.pkg.dev/cohesive-slate-409107/minh-artifact/base_image:latest

RUN php -f $PROJECT_ROOT/bin/magento setup:install --base-url=http://$HOST_NAME --base-url-secure=https://$HOST_NAME --db-host=$DB_HOST --db-name=$DB_NAME  --db-user=$DB_USER --db-password=$DB_PASS --admin-firstname=$ADMIN_FIRSTNAME --admin-lastname=$ADMIN_LASTNAME --admin-email=$ADMIN_MAIL --admin-user=$ADMIN_USER --admin-password=$ADMIN_PASS --language=ja_JP --currency=JPY --timezone=Asia/Tokyo --backend-frontname=$ADMIN_PAGE_SLUG --use-secure=1 --use-secure-admin=1
RUN cp $PROJECT_ROOT/app/etc/env.php.tpl $PROJECT_ROOT/app/etc/env.php

CMD ["bash", "/var/www/html/prod/docker/app/start.sh"]
