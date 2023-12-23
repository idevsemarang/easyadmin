## iDev Easy Admin

Admin packages with easy crud, import and export datas 

## Installation
1. Add package to composer.json <br>
    <pre>
    composer require idevsmg/easyadmin:dev-main
    </pre>

2. Add autoload to composer.json
   <pre>
    "autoload": {
        "psr-4": {
            ....
            "Idev\\EasyAdmin\\": "vendor/idevsmg/easyadmin/src/"
        }
    },
   </pre>
 
3. Add provider to config/app.php providers <br>
    <pre>
    'providers' => ServiceProvider::defaultProviders()->merge([
        .....
        Idev\EasyAdmin\EasyAdminServiceProvider::class,
    ])->toArray(),
   </pre>

4. Run installation<br>
    <pre>
    composer dump-autoload

    php artisan vendor:publish --tag=install-idev --force
   </pre>
then you can doing migration and seeder

## CRUD Generator 
You can easily create your crud controller, let's enable command via app/Console/Kernel.php
<pre>
    protected $commands = [
        \Idev\EasyAdmin\app\Console\Commands\ControllerMaker::class,
    ];
</pre>
make sure the table migration has been created
then you just type: 
<pre>
    php artisan idev:controller-maker --slug={your-route} --table={your-table}
</pre>

## Middleware CRUD (Optional)
We also prepare middleware for access control in your crud by adding snippet code below into kernel.php  <br>
    <pre>
    protected $middlewareAliases = [
        .....
        'middlewareByAccess' => \App\Http\Middleware\MiddlewareByAccess::class
    ];
   </pre>
   
And don't forget to implement this middleware into your route
<pre>
   Route::group(['middleware' => ['web', 'auth', 'middlewareByAccess']], function () {
       ......
   });
</pre>

## Sample CRUD  (Optional)
if you want to view our sample crud controller, you can publish sample-crud. <br>
php artisan vendor:publish --tag=sample-crud --force <br>

and set your like this
<pre>
   Route::group(['middleware' => ['web', 'auth']], function () {
       Route::resource('sample-data', SampleDataController::class);
       Route::get('sample-data-api', [SampleDataController::class, 'indexApi'])->name('sample-data.listapi');
       Route::get('sample-data-export-pdf-default', [SampleDataController::class, 'exportPdf'])->name('sample-data.export-pdf-default');
       Route::get('sample-data-export-excel-default', [SampleDataController::class, 'exportExcel'])->name('sample-data.export-excel-default');
       Route::post('sample-data-import-excel-default', [SampleDataController::class, 'importExcel'])->name('sample-data.import-excel-default');
   });
</pre>


