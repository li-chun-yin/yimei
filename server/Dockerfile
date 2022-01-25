FROM ubuntu:20.04
ARG DEBIAN_FRONTEND=noninteractive
ENV TZ=Asia/Shanghai
WORKDIR /srv

RUN apt-get update

RUN apt-get install -yq mysql-server
COPY docker-mysql-init.sql /docker-mysql-init.sql

RUN apt-get install -yq php 
RUN apt-get install -yq php-mysql

COPY . /srv
RUN chmod +x ./docker-entrypoint.sh
ENTRYPOINT ["./docker-entrypoint.sh"]

