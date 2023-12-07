<?php
namespace Idev\EasyAdmin\app\Helpers;

class Validation
{

    public function modify($validator,$rules, $preffixMethod = "create_")
    {
        $message_errors = [];
            $obj_validators = $validator->errors();
            foreach(array_keys($rules) as $key => $field){
                if ($obj_validators->has($field)) {
                    $message_errors[] = ['id' => $preffixMethod.$field , 'message'=> $obj_validators->first($field)];
                }
            }
        return $message_errors;
    }
}