# 🛍 フリマアプリ

## 🛠 環境構築
### Dockerビルド
1. git clone git@github.com:ghshzk/furima-app.git
2. docker-compose up -d --build

### Laravel環境構築
1. docker-compose exec php bash
2. composer install
3. cp .env.example .env
4. .envファイルの環境変数を変更
```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
```
5. php artisan key:generate
6. php artisan migrate:fresh --seed
7. php artisan storage:link

## 💻 使用技術
- Laravel 8
- PHP 7.4.9
- MySQL 8.0.26
- Nginx 1.21.1

## 📧 メール認証
メール認証機能に Mailtrap を使用しています。開発環境では以下の手順で設定を行ってください。
1. [Mailtrap](https://mailtrap.io/)に登録・ログイン、サイドバーの Inboxes から My Inbox を開く
2. Integrations で「**laravel 7.x and 8.x**」を選択し、表示されるコードをコピーして、`.env`ファイルの`MAIL`セクションにペースト
3. MAIL_FROM_ADDRESSは任意のメールアドレス、MAIL_FROM_NAMEは任意の名前を入力。
```
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=xxxxxxxxxxxxxx
MAIL_PASSWORD=xxxxxxxxxxxxxx
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="test@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

## 決済画面
決済にはStripeというツールを使用しています。  


## 🗂 テーブル仕様

## 🗺 ER図
![ER図](/furima.drawio.svg)

## 🔑 テストアカウント
### アカウント①
name: test_user2
email: user1@example.com
password: password

### アカウント②
name: test_user2
email: user2@example.com
password: password

## 🌐 URL
- 開発環境：http://localhost/
- phpMyAdmin：http://localhost:8080/