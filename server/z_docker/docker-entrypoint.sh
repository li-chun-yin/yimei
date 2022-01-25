#!/bin/bash

usermod -d /var/lib/mysql mysql
service mysql start
mysql < /srv/z_docker/docker-mysql-init.sql

rm /etc/nginx/sites-enabled/*
ln -s /srv/z_docker/nginx.conf /etc/nginx/sites-enabled/
service nginx start

./vendor/bin/doctrine orm:schema-tool:create
./bin/console video-sync &
php -d post_max_size=4G -d upload_max_filesize=4G -S 0.0.0.0:80 -t public