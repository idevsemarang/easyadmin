@php 
$preffix_method = (isset($method))? $method."_": "";
@endphp
<div class="{{(isset($field['class']))?$field['class']:'form-group'}}">
    <label>{{(isset($field['label']))?$field['label']:'Label '.$key}}</label>
    <input type="password" id="{{$preffix_method}}{{(isset($field['name']))?$field['name']:'id_'.$key}}" name="{{(isset($field['name']))?$field['name']:'name_'.$key}}" value="{{(isset($field['value']))?$field['value']:''}}" class="form-control idev-form" autocomplete="">
</div>