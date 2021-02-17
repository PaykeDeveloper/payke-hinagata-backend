FROM mysql/mysql-server:8.0

ARG MYSQL_DATABASE
ARG MYSQL_REPLICA_USER
ARG MYSQL_REPLICA_PASSWORD

COPY ./docker-entrypoint-initdb.d/ /docker-entrypoint-initdb.d/

RUN sed -i 's/:database/'"${MYSQL_DATABASE}"'/g' /docker-entrypoint-initdb.d/init.sql
RUN sed -i 's/:user/'"${MYSQL_REPLICA_USER}"'/g' /docker-entrypoint-initdb.d/init.sql
RUN sed -i 's/:password/'"${MYSQL_REPLICA_PASSWORD}"'/g' /docker-entrypoint-initdb.d/init.sql

COPY ./tmp/my.cnf /tmp/my.cnf
RUN cat /tmp/my.cnf >> /etc/my.cnf

RUN touch /var/log/mysql_slow.log
RUN touch /var/log/mysql_general.log
RUN chown mysql:mysql /var/log/mysql_slow.log
RUN chown mysql:mysql /var/log/mysql_general.log
