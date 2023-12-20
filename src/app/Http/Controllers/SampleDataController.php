<?php

namespace App\Http\Controllers;

use App\Models\SampleData;
use Idev\EasyAdmin\app\Http\Controllers\DefaultController;

class SampleDataController extends DefaultController
{
    protected $modelClass = SampleData::class;
    protected $title;
    protected $generalUri;
    protected $tableHeaders;
    protected $actionButtons;
    protected $arrPermissions;
    protected $importExcelConfig;

    public function __construct()
    {
        $this->title = 'Sample Data';
        $this->generalUri = 'sample-data';
        $this->arrPermissions = [];
        $this->actionButtons = ['btn_edit', 'btn_show', 'btn_destroy'];

        $this->tableHeaders = [
            ['name' => 'No', 'column' => '#', 'order' => true],
            ['name' => 'Name', 'column' => 'name', 'order' => true],
            ['name' => 'Age', 'column' => 'age', 'order' => true],
            ['name' => 'Gender', 'column' => 'gender', 'order' => true],
            ['name' => 'Address', 'column' => 'address', 'order' => true],
            ['name' => 'Created at', 'column' => 'created_at', 'order' => true],
            ['name' => 'Updated at', 'column' => 'updated_at', 'order' => true],
        ];


        $this->importExcelConfig = [ 
            'primaryKeys' => ['name'],
            'headers' => [
                ['name' => 'Name', 'column' => 'name'],
                ['name' => 'Age', 'column' => 'age'],
                ['name' => 'Gender', 'column' => 'gender'],
                ['name' => 'Address', 'column' => 'address']
            ]
        ];
    }

}
