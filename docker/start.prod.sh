#!/usr/bin/env bash
set -e

# shellcheck disable=SC2043
if [[ ! -d "/semart/vendor" ]]; then
    cd /semart && composer update --prefer-dist -vvv
fi

if [[ ! -d "/semart/var" ]]; then
    cd /semart && mkdir var
fi

cd /semart && composer update --prefer-dist -vvv
composer dump-autoload --no-dev --classmap-authoritative
php /semart/bin/console cache:clear --env=prod
php /semart/bin/console assets:install --env=prod
chmod 777 -R var/

chmod 777 -R storage/
chmod 755 -R config/
chmod 755 -R vendor/

/usr/bin/supervisord -n -c /etc/supervisord.conf
