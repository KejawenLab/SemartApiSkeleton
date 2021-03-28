#!/usr/bin/env bash
set -e

# shellcheck disable=SC2043
if [[ ! -d "/semart/var" ]]; then
    cd /semart && mkdir var
fi

cd /semart && composer update --prefer-dist -vvv
if [[ "prod" == "${APP_ENV}" ]]; then
    composer dump-autoload  --optimize --classmap-authoritative
    php /semart/bin/console cache:clear --env=prod
else
    composer dump-autoload --optimize
    php /semart/bin/console cache:clear --env=dev
    chmod 777 -R var/
fi

chmod 777 -R var/
chmod 777 -R storage/
chmod 755 -R config/
chmod 755 -R vendor/

if [[ "prod" == "${APP_ENV}" ]]; then
    /usr/bin/supervisord -n -c /etc/supervisord.prod.conf
else
    /usr/bin/supervisord -n -c /etc/supervisord.dev.conf
fi

