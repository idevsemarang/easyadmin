<?php

namespace Idev\EasyAdmin\app\Helpers;

use Idev\EasyAdmin\app\Models\Role;
use Illuminate\Support\Facades\Auth;

class Constant
{

  private $alias = [
    'list' => ['index', 'listapi'],
    'show' => ['show'],
    'create' => ['create', 'store'],
    'edit' => ['edit', 'update'],
    'delete' => ['delete','destroy'],
    'export-pdf-default' => ['export-pdf-default'],
    'export-excel-default' => ['export-excel-default'],
    'import-excel-default' => ['import-excel-default'],
  ];

  public function permissions()
  {
    $arrParent = [
      'list_access' => []
    ];
    $arrAccess = [];
    $kjs = Auth::user()->role->access;

    if ($kjs) {
      $accessJson = json_decode($kjs, true);

      foreach ($accessJson as $key => $aj) {
        foreach ($aj['access'] as $key => $access) {
          if (array_key_exists($access, $this->alias)) {
            foreach ($this->alias[$access] as $key => $accJson) {
              $arrAccess[] = $aj['route'] . "." . $accJson;
            }
          }
        }
      }

      $arrParent = [
        'list_access' => $arrAccess
      ];
    }

    return $arrParent;
  }


  public function permissionByMenu($menu)
  {

    $kjs = Auth::user()->role->access;
    $arrAccess = [];

    if ($kjs) {
      $accessJson = json_decode($kjs, true);

      $colAccess = collect($accessJson)->where('route', $menu)->first();
      if ($colAccess) {
        foreach ($colAccess['access'] as $key => $access) {
          if (array_key_exists($access, $this->alias)) {
            foreach ($this->alias[$access] as $key => $accJson) {
              $arrAccess[] = $accJson;
            }
          }
        }
      }
    }
    return $arrAccess;
  }


  public function validationMobile($validator, $rules)
  {
    $message_errors = [];
    $obj_validators     = $validator->errors();
    foreach (array_keys($rules) as $key => $field) {
      if ($obj_validators->has($field)) {
        $message_errors[] = $obj_validators->first($field);
      }
    }
    return $message_errors;
  }

}
