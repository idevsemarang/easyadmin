<?php

namespace App\Helpers;

use Idev\EasyAdmin\app\Helpers\Constant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class Sidebar
{

  public function generate()
  {
    $permission = (new Constant())->permissions();
    
    $menus = $this->menus();

    $arrMenu = [];
    foreach ($menus as $key => $menu) {
      // $menu['visibility'] = in_array($menu['key'].".index", $permission['list_access']);
      $menu['url'] = (Route::has($menu['key'].".index")) ? route($menu['key'].".index") : "#";
      $menu['base_key'] = $menu['key'];
      $menu['key'] = $menu['key'].".index";

      $arrMenu[] = $menu;
    }
    return $arrMenu;
  }


  public function menus(){
    $role = Auth::user()->role->name;
    return
      [
        [
          'name' => 'Dashboard',
          'icon' => 'ti ti-dashboard',
          'key' => 'dashboard',
          'base_key' => 'dashboard',
          'visibility' => true,
          'ajax_load' => false,
          'childrens' => []
        ],
        [
          'name' => 'Role',
          'icon' => 'ti ti-key',
          'key' => 'role',
          'base_key' => 'role',
          'visibility' => in_array($role, ['admin']),
          'ajax_load' => false,
          'childrens' => []
        ],
        [
          'name' => 'User',
          'icon' => 'ti ti-users',
          'key' => 'user',
          'base_key' => 'user',
          'visibility' => in_array($role, ['admin']),
          'ajax_load' => false,
          'childrens' => []
        ],
      ];
  }


  public function defaultAllAccess($exclude = []) {
    return ['index', 'create','store', 'show', 'edit', 'update', 'destroy', 'listapi', 'import-excel-default', 'export-excel-default', 'export-excel-custom', 'export-pdf-default'];
  }

}
