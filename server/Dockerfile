FROM ubuntu:20.04
ARG DEBIAN_FRONTEND=noninteractive
ENV TZ=
WORKDIR /srv

RUN apt-get update

RUN apt-get install -yq mysql-server

RUN apt-get install -yq php php-mysql php-curl

RUN apt-get install -yq nginx 

COPY . /srv

RUN chmod +x ./z_docker/docker-entrypoint.sh

EXPOSE 80 443 3306 33060

ENTRYPOINT ["./z_docker/docker-entrypoint.sh"]

