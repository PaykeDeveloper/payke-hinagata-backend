# Laravel環境構築用テンプレ

## SetUp
``` cmd
# docker build
$ docker-compose up --build -d
# phpの依存パッケージインストール
$ docker-compose exex app composer install
# laravelのマイグレーションを実行
$ docker-compose exex app php artisan migrate
```
