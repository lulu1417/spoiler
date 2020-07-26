# Spoiler
An app to prevent food go waste.
## Getting Start
複製範例.env檔內容
```
cp .env.example .env
```
去mysql新增DB
```
mysql> create database 資料庫名稱;
```
修改或新增以下欄位：
```
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

BROADCAST_DRIVER=pusher

//pusher官網新增一個app，在getting start那邊會寫
PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=

MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

//fb在開發工具新增應用程式後能在設定頁面看到
FB_CLIENT_ID=
FB_CLIENT_SECRET=

//domain要記得改
FB_REDIRECT=https://yourDomain.com/api/user/facebook/call-back
FBEndpoint=me?fields=id,name,picture
```
### 需要的套件
```
composer install
```
安裝其它套件需要的php-curl
```
sudo apt-get install php-curl
```

Pusher
```
composer require pusher/pusher-php-server
```
fb OAuth用的套件
```
composer require facebook/graph-sdk
```
儲存圖片
 ```
 composer require intervention/image 
```
建立tables
```
php artisan migrate
```

#### 如果前端要測試pusher的話
npm
```
sudo apt get npm
```
在專案目錄下用npm安裝所需套件
```
npm install
```
