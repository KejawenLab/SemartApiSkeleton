#!/usr/bin/env bash
set -e

# shellcheck disable=SC2043
if [[ ! -d "/semart/var" ]]; then
    cd /semart && mkdir var
fi

if [[ ! -d "/var/log/supervisor" ]]; then
    mkdir -p /var/log/supervisor
fi

if [[ ! -d "/semart/vendor/composer" ]]; then
    cd /semart && composer update --prefer-dist -vvv
fi

if [[ "prod" == "${APP_ENV}" ]]; then
    cp "${PHP_INI_DIR}/php.ini-production" "${PHP_INI_DIR}/php.ini"
    cp "/etc/opcache.ini" "${PHP_INI_DIR}/conf.d/opcache.ini"
    composer dump-autoload --classmap-authoritative
    php /semart/bin/console cache:clear --env=prod
else
    cp "${PHP_INI_DIR}/php.ini-development" "${PHP_INI_DIR}/php.ini"
    composer dump-autoload
    php /semart/bin/console cache:clear --env=dev
    chmod 777 -R var/
fi

chmod 777 -R var/
chmod 777 -R storage/
chmod 755 -R config/
chmod 755 -R vendor/
chmod 755 -R public/

/usr/bin/supervisord -n -c /etc/supervisord.conf

