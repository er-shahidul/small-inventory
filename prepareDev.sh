sudo rm -rf var/cache/* var/logs/*
bin/console cache:clear
bin/console assets:install --symlink web
bin/console fos:js-routing:dump
chmod 777 var/cache* var/logs* -R
