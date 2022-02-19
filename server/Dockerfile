FROM ubuntu:20.04
ARG DEBIAN_FRONTEND=noninteractive
ENV TZ=
WORKDIR /srv

RUN apt-get update

RUN apt-get install -yq git zip

RUN apt-get install -yq mysql-server

RUN apt-get install -yq php php-mysql php-curl

RUN apt-get install -yq nginx 

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"; \
	php -r "if (hash_file('sha384', 'composer-setup.php') === '906a84df04cea2aa72f40b5f787e49f22d4c2f19492ac310e8cba5b96ac8b64115ac402c8cd292b8a03482574915d1a8') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"; \
	php composer-setup.php; \
	php -r "unlink('composer-setup.php');"; \
	mv composer.phar /usr/local/bin/composer

COPY ./docker-entrypoint.sh /docker-entrypoint.sh

RUN chmod +x /docker-entrypoint.sh

EXPOSE 80 443 3306 33060

ENTRYPOINT ["/docker-entrypoint.sh"]

