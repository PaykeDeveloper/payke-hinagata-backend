FROM nginx:1.19

RUN apt-get update -qq && \
    apt-get install -y openssl

ENV NGINX_ROOT /etc/nginx

WORKDIR $NGINX_ROOT/certs
RUN openssl genrsa -out server.key 4096 &&\
    openssl req -new -batch -key server.key -out server.csr &&\
    openssl x509 -req -days 3650 -in server.csr -signkey server.key -out server.crt

ADD ./etc/nginx/conf.d/default.conf $NGINX_ROOT/conf.d/default.conf
