CREATE USER ':user' IDENTIFIED BY ':password';
GRANT SELECT ON :database.* TO ':user'@'%';
