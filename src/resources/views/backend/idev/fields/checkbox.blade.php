@php 
$prefix_repeatable = (isset($repeatable))? true : false;
$preffix_method = (isset($method))? $method."_": "";
$checkBoxFormId = (isset($field['name']))?$field['name']:'id_'.$key;
$checkBoxFormId = $preffix_method . $checkBoxFormId;

@endphp
<div class="{{(isset($field['class']))?$field['class']:'form-group'}}">
    <label>
        <input 
            type="checkbox" 
            id="{{$checkBoxFormId}}" 
            name="{{(isset($field['name']))?$field['name']:'name_'.$key}}" 
            value="{{(isset($field['value']))?$field['value']:''}}" 
            class="idev-form @if($prefix_repeatable) field-repeatable @endif">

        {{(isset($field['label']))?$field['label']:'Label '.$key}} 
        @if(isset($field['required']) && $field['required'])
        <small class="text-danger">*</small>
        @endif
    </label>
    
</div>