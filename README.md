<p align="center"><img src="https://res.cloudinary.com/dtfbvvkyp/image/upload/v1566331377/laravel-logolockup-cmyk-red.svg" width="400"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## Server Requirements
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
1. install backend(php) dependencies  
```composer install```
2. create default environment configuration  
```cp .env.example .env```
3. set your database  
4. set the application key  
```php artisan key:generate```
5. install frontend(vue...) dependencies  
```npm install```
6. create table  
```php artisan migrate```
7. package  
```npm run dev```
8. run serve (Serve the application on the PHP development server)
```php artisan serve```

***PS: If you want to have a try with registered user, run "php artisan db:seed", then you can login in with email:admin@gaea.com and password:123456***


