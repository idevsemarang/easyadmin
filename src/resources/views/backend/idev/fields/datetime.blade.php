@php 
$prefix_repeatable = (isset($repeatable))? true : false;
$preffix_method = (isset($method))? $method."_": "";
@endphp
<div class="{{(isset($field['class']))?$field['class']:'form-group'}}">
    <label>{{(isset($field['label']))?$field['label']:'Label '.$key}} 
        @if(isset($field['required']) && $field['required'])
        <small class="text-danger">*</small>
        @endif
    </label>
    <input 
        type="datetime-local" 
        id="{{$preffix_method}}{{(isset($field['name']))?$field['name']:'id_'.$key}}" 
        name="{{(isset($field['name']))?$field['name']:'name_'.$key}}" 
        value="{{(isset($field['value']))?$field['value']:''}}" 
        class="form-control idev-form @if($prefix_repeatable) field-repeatable @endif">
</div>