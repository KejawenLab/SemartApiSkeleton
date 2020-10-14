#!/usr/bin/env bash
set -e

# shellcheck disable=SC2043
for name in NGINX_WEBROOT
do
    eval value=\$$name
    # shellcheck disable=SC2154
    sed -i "s|\${${name}}|${value}|g" /etc/nginx/conf.d/default.conf
done

if [[ ! -d "/semart/vendor" ]]; then
    cd /semart && composer update --prefer-dist -vvv
fi

if [[ ! -d "/semart/var" ]]; then
    cd /semart && mkdir var
fi

if [[ "prod" == "${APP_ENV}" ]]; then
    composer dump-autoload --no-dev --classmap-authoritative
    php /semart/bin/console cache:clear --env=prod
fi

if [[ "dev" == "${APP_ENV}" ]]; then
    composer dump-autoload
    php /semart/bin/console cache:clear --env=dev
fi

chmod 777 -R var/
chmod 777 -R storage/
chmod 755 -R config/
chmod 755 -R vendor/

/usr/bin/supervisord -n -c /etc/supervisord.conf
