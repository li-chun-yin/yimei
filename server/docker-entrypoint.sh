#!/bin/bash

usermod -d /var/lib/mysql mysql
service mysql start
mysql < /docker-mysql-init.sql

./vendor/bin/doctrine orm:schema-tool:create
./bin/console video-sync &
php -d post_max_size=4G -d upload_max_filesize=4G -S 0.0.0.0:8621 -t public