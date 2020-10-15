FROM mysql/mysql-server:8.0

COPY docker/db/templates/debug.cnf /etc/debug.cnf
RUN cat /etc/debug.cnf >> /etc/my.cnf

RUN touch /var/log/mysql_slow.log
RUN touch /var/log/mysql_general.log
RUN chown mysql:mysql /var/log/mysql_slow.log
RUN chown mysql:mysql /var/log/mysql_general.log
