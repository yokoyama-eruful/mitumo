# mitumo

### composer install 方法（Docker 利用の場合）

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
