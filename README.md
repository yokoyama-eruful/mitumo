# mitumo

### composer install 方法（Docker 利用の場合）

git clone後に実行してください

```
docker run --rm \
-u "$(id -u):$(id -g)" \
-v $(pwd):/var/www/html \
-w /var/www/html \
laravelsail/php81-composer:latest \
composer install --ignore-platform-reqs
```

## 保険の計算式・料率テーブル

　Documentフォルダ内の別途ファイルに記載

## ツール一覧

### 静的解析の実行　larastan

```
./vendor/bin/phpstan analyse
```

### コードのスタイルをチェックする Laravel pint

```
./vendor/bin/pint
```

### コード品質を自動でチェックする　（あくまで目安） php insights

```
php artisan insights
```

## Unitテスト

```
php artisan test --testsuite=Unit
```

ディレクト内のテストを実行する例（ディレクト名　tests/Unit/Domains/ComprehensivePremium/　）

```
php artisan test tests/Unit/Domains/ComprehensivePremium/
```

ファイルを指定するテストを実行する例　（ファイル名　tests/Unit/Domains/ComprehensivePremium/FactoryTest.php）

```
php artisan test tests/Unit/Domains/ComprehensivePremium/FactoryTest.php
```
