#!/bin/sh

/sbin/setuser www-data php /srv/share/bin/console about

until /sbin/setuser www-data php /srv/share/bin/console -n doctrine:database:create --if-not-exists; do
  >&2 echo "database is unavailable - sleeping"
  sleep 3
done

/sbin/setuser www-data php /srv/share/bin/console -n doctrine:database:drop --force
/sbin/setuser www-data php /srv/share/bin/console -n doctrine:database:create
/sbin/setuser www-data php /srv/share/bin/console -n doctrine:migration:migrate
