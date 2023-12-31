<?php

namespace Idev\EasyAdmin\app\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ControllerMaker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'idev:controller-maker {--slug=} {--connection=} {--table=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate an iDev controller';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $slug = $this->option('slug');
        $connection = $this->option('connection') ?? env('DB_CONNECTION');
        $table = $this->option('table');

        $columns = DB::connection($connection)->getSchemaBuilder()->getColumnListing($table);

        $strLoopHeaders = "";
        $strLoopHeadersExcel = "";
        $firstHeaderExcel = "";
        $fillable = [];
        foreach ($columns as $key => $col) {
            $label = str_replace("_", " ", ucwords($col)); 

            if(!in_array($col, ['id', 'created_at', 'updated_at', 'deleted_at']))
            {
                $strLoopHeaders .= "['name' => '".$label."', 'column' => '".$col."', 'order' => true],\n";
                $strLoopHeadersExcel .= "['name' => '".$label."', 'column' => '".$col."'],\n";
    
                if($key == 1){
                    $firstHeaderExcel = $col;
                }
                $fillable[] = $col;
            }
        }

        $firstCaps = str_replace("-", "", ucwords($slug)); 
        $spaceBetween = str_replace("-", " ", ucwords($slug)); 
        $lowerLetter = strtolower($slug);

        $destinationPath = app_path('Http/Controllers/' . $firstCaps."Controller.php");
        $sourcePath = __DIR__.'/sample-controller.idev';

        copy($sourcePath, $destinationPath);

        $contents = file_get_contents($destinationPath);

        $newContents = Str::replace("SampleData", $firstCaps, $contents);
        $newContents = Str::replace("Sample Data", $spaceBetween, $newContents);
        $newContents = Str::replace("sample-data", $lowerLetter, $newContents);
        $newContents = Str::replace("LOOPTABLEHEADERS", $strLoopHeaders, $newContents);
        $newContents = Str::replace("LOOPEXCELHEADERS", $strLoopHeadersExcel, $newContents);
        $newContents = Str::replace("FIRSTHEADERSEXCEL", $firstHeaderExcel, $newContents);

        if (file_put_contents($destinationPath, $newContents) !== false) {
            $this->info($firstCaps.'Controller created successfully!');
        }

        // Model section
        $destinationPath = app_path('Models/' . $firstCaps.".php");
        $sourcePath = __DIR__.'/sample-model.idev';

        copy($sourcePath, $destinationPath);

        $contents = file_get_contents($destinationPath);

        $newContents = Str::replace("SampleData", $firstCaps, $contents);
        $newContents = Str::replace("CONTENTFILLABLE", json_encode($fillable), $newContents);
        $newContents = Str::replace("TABLENAME", $table, $newContents);

        if (file_put_contents($destinationPath, $newContents) !== false) {
            $this->info('Model '.$firstCaps.' created successfully!');
        }

        $strRoutes = "Route::resource('".$lowerLetter."', ".$firstCaps."Controller::class);\n";
        $strRoutes .= "Route::get('".$lowerLetter."-api', [".$firstCaps."Controller::class, 'indexApi'])->name('".$lowerLetter.".listapi');\n";
        $strRoutes .= "Route::get('".$lowerLetter."-export-pdf-default', [".$firstCaps."Controller::class, 'exportPdf'])->name('".$lowerLetter.".export-pdf-default');\n";
        $strRoutes .= "Route::get('".$lowerLetter."-export-excel-default', [".$firstCaps."Controller::class, 'exportExcel'])->name('".$lowerLetter.".export-excel-default');\n";
        $strRoutes .= "Route::post('".$lowerLetter."-import-excel-default', [".$firstCaps."Controller::class, 'importExcel'])->name('".$lowerLetter.".import-excel-default');\n";

        $this->info("Please copy this code into your route \n".$strRoutes);

        $strSidebar = "\n
          [
            'name' => '".$firstCaps."',
            'icon' => 'ti ti-menu',
            'key' => '".$lowerLetter."',
            'base_key' => '".$lowerLetter."',
            'visibility' => true,
            'ajax_load' => false,
            'childrens' => []
          ],\n";
        $this->info("And copy this snippet code into your app/Helpers/Sidebar.php \n".$strSidebar);
    }
}
