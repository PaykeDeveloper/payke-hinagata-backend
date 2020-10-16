# Laravel環境構築用テンプレ

## SetUp
``` cmd
# docker build
$ docker-compose up --build -d
# phpの依存パッケージインストール
$ docker-compose exec app composer install
# 環境変数ファイルの複製とランダムなアプリケーションkeyを生成
$ cp ./laravel-app/.env.example ./laravel-app/.env
$ docker-compose exec app php artisan key:generate
# laravelのマイグレーションを実行
$ docker-compose exec app php artisan migrate
```
