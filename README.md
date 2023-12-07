## iDev Easy Admin

Admin packages with easy crud, import and export table 

## Installation
1. Add package to composer.json
composer require idev/easyadmin

2. Add autoload to composer.json
 "autoload": {
        "psr-4": {
            ....
            "Idev\\EasyAdmin\\": "vendor/idev/easyadmin/src/"
        }
    },

1. Add provider to config/app.php
Idev\EasyAdmin\EasyAdminServiceProvider::class


3. run composer update

Now, publish some method :
php artisan vendor:publish --tag=public --force
php artisan vendor:publish --tag=sidebar --force
php artisan vendor:publish --tag=migrate-and-seed --force
php artisan vendor:publish --tag=sample-crud --force (optional)

and set middleware by access to your kernel.php
'middlewareByAccess' => \Idev\EasyAdmin\app\Http\Middleware\MiddlewareByAccess::class,


