@php 
$prefix_repeatable = (isset($repeatable))? true : false;
$preffix_method = (isset($method))? $method."_": "";
$accept = "*";
if (isset($field['accept'])) {
    $accept = $field['accept'];
}
@endphp
<div class="{{(isset($field['class']))?$field['class']:'form-group'}}">
    <label>{{(isset($field['label']))?$field['label']:'Label '.$key}}</label>
    <br>
  
    <input type="file" accept="{{$accept}}" id="{{$preffix_method}}{{(isset($field['name']))?$field['name']:'id_'.$key}}" name="{{(isset($field['name']))?$field['name']:'name_'.$key}}" class="form-control idev-validation" placeholder="Upload File">
    @if(isset($field['value']) && $field['value'] != "")
    <a href="{{$field['value']}}" class="fiu_{{(isset($field['name']))?$field['name']:'id_'.$key}}"> <i class="ti ti-link"></i>Link To File</a>
    <br>
    @endif
</div>