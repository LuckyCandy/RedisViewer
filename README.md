## 基于Laravel、Vue and Vue-Router 开发redis便捷查询应用
![20210804102025](https://user-images.githubusercontent.com/16129954/129856598-251099f8-d07b-4cdc-a10f-5c7448141efd.jpg)

### Server Requirements
* PHP >= 7.2.5
* BCMath PHP Extension
* Ctype PHP Extension
* Fileinfo PHP extension
* JSON PHP Extension
* Mbstring PHP Extension
* OpenSSL PHP Extension
* PDO PHP Extension
* Tokenizer PHP Extension
* XML PHP Extension

## Install
|步骤|操作|说明|
|:----:|----|----|
|1|```composer install```|Install backend(php) dependencies
|2|```cp .env.example .env```|Create default environment configuration
|3|```config/database.php;config/cache.php```|Setup your database configuration, or cache configuration
|4|```php artisan key:generate```|Setup the application key
|5|```(c)npm install```|Install frontend(vue...) dependencies
|6|```php artisan migrate```|Create system table
|7|```npm run dev;npm run prod```|Package for dev(Test) or prod(Online)
|8|```php artisan db:seed```|Create system-admin user:admin@gaea.com,pass:123456

