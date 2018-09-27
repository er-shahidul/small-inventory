bin/console cache:clear --env=prod
bin/console assets:install --env=prod --symlink web
bin/console assetic:dump --env=prod
bin/console fos:js-routing:dump
chmod 777 app/cache* app/logs* -R
