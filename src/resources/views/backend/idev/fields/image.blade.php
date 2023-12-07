@php 
$prefix_repeatable = (isset($repeatable))? true : false;
$preffix_method = (isset($method))? $method."_": "";
@endphp
<div class="{{(isset($field['class']))?$field['class']:'form-group'}}">
    <label>{{(isset($field['label']))?$field['label']:'Label '.$key}}</label>
    <br>
    @if(isset($field['value']))
    <img src="{{$field['value']}}" class="img-thumbnail img-fluid thumb_{{(isset($field['name']))?$field['name']:'id_'.$key}}" alt="">
    <br>
    @endif
    <input type="file" accept="image/png, image/gif, image/jpeg" id="{{$preffix_method}}{{(isset($field['name']))?$field['name']:'id_'.$key}}" name="{{(isset($field['name']))?$field['name']:'name_'.$key}}" class="form-control idev-validation" placeholder="Image">
</div>