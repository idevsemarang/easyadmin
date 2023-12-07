## iDev Easy Admin

Admin packages with easy crud, import and export table 

## Installation
1. Add package to composer.json <br>
composer require idev/easyadmin

2. Add autoload to composer.json
   <pre>
    "autoload": {
        "psr-4": {
            ....
            "Idev\\EasyAdmin\\": "vendor/idev/easyadmin/src/"
        }
    },
   </pre>
 
3. Add provider to config/app.php <br>
Idev\EasyAdmin\EasyAdminServiceProvider::class

4. run composer update

Now, publish some method :<br>
php artisan vendor:publish --tag=public --force  <br>
php artisan vendor:publish --tag=sidebar --force  <br>
php artisan vendor:publish --tag=migrate-and-seed --force  <br>
php artisan vendor:publish --tag=sample-crud --force (optional)  <br>

and set middleware by access to your kernel.php  <br>
'middlewareByAccess' => \Idev\EasyAdmin\app\Http\Middleware\MiddlewareByAccess::class,


