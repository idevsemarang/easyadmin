<?php

namespace database\Seeders;

use Idev\EasyAdmin\app\Models\Role;
// use App\Models\SampleData;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Auth\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->role();
        $this->user();
       // $this->sampleData();
    }

    public function role()
    {
        Role::updateOrCreate(
            [
                'name' => 'admin'
            ],
            [
                'name' => 'admin',
                'access' => '[{"route":"dashboard","access":["list"]},{"route":"role","access":["list","create","show","edit","delete","import-excel-default","export-excel-default","export-pdf-default"]},{"route":"user","access":["list","create","show","edit","delete","import-excel-default","export-excel-default","export-pdf-default"]}]',
            ]
        );

        Role::updateOrCreate(
            [
                'name' => 'customer'
            ],
            [
                'name' => 'customer',
                'access' => '[{"route":"dashboard","access":["list"]}]',
            ]
        );
    }


    

    public function user()
    {
        User::updateOrCreate(
            [
                'email' => 'admin@idev.com',
            ],
            [
                'name' => 'Admin',
                'email' => 'admin@idev.com',
                'password' => bcrypt('qwerty'),
                'role_id' => Role::where('name', 'admin')->first()->id,
            ]
        );

        User::updateOrCreate(
            [
                'email' => 'johny@idev.com',
            ],
            [
                'name' => 'Johny Nur Ahmad',
                'email' => 'johny@idev.com',
                'password' => bcrypt('qwerty'),
                'role_id' => Role::where('name', 'customer')->first()->id,
            ]
        );
    }

    /*
    public function sampleData()
    {
        SampleData::updateOrCreate(
            [
                'name' => 'Augusta Mauricio',
            ],
            [
                'name' => 'Augusta Mauricio',
                'age' => 19,
                'gender' => 'Male',
                'address' => 'Wolkhadr Street number 20',
            ]
        );

        SampleData::updateOrCreate(
            [
                'name' => 'Melivia Adrenaline',
            ],
            [
                'name' => 'Melivia Adrenaline',
                'age' => 21,
                'gender' => 'Female',
                'address' => 'Hawk House 28 Canada',
            ]
        );

        SampleData::updateOrCreate(
            [
                'name' => 'Indigo Venisa',
            ],
            [
                'name' => 'Indigo Venisa',
                'age' => 20,
                'gender' => 'Female',
                'address' => 'Jitruno Street',
            ]
        );
    }
    */
}
