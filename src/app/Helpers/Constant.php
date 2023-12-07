<?php

namespace Idev\EasyAdmin\app\Helpers;

use Idev\EasyAdmin\app\Models\Role;
use Illuminate\Support\Facades\Auth;

class Constant
{

  public function permissions()
  {
    $arrParent = [
      'list_access' => []
    ];
    
    $kjs = Role::where('id', Auth::user()->role_id)->first();

    if ($kjs) {
      $accessJson = json_decode($kjs->access, true);
      $arrAccess = [];
      foreach ($accessJson as $key => $aj) {
        foreach ($aj['access'] as $key => $access) {
          $arrAccess[] = $aj['route'] . "." . $access;
        }
      }
      $arrParent = [
        'list_access' => $arrAccess
      ];
    }

    return $arrParent;
  }


  public function permissionByMenu($menu){
    $kjs = Role::where('id', Auth::user()->role_id)->first();

    $arrAccess = [];
    
    if ($kjs) {
      $accessJson = json_decode($kjs->access, true);
      $colAccess = collect($accessJson)->where('route', $menu)->first();
      if ($colAccess) {
        $arrAccess = $colAccess['access'];
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
