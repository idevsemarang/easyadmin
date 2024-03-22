@php 
$preffix_method = (isset($method))? $method."_": "";
@endphp
<div class="{{(isset($field['class']))?$field['class']:'form-group'}}">
    <label>{{(isset($field['label']))?$field['label']:'Label '.$key}}
        @if(isset($field['required']) && $field['required'])
        <small class="text-danger">*</small>
        @endif
    </label>
    <select id="{{$preffix_method}}{{(isset($field['name']))?str_replace("[]","",$field['name']):'id_'.$key}}" name="{{(isset($field['name']))?$field['name']:'name_'.$key}}" class="form-control idev-form">
        @foreach($field['options'] as $key => $opt)
        <option value="{{$opt['value']}}" @if($opt['value'] == $field['value']) selected @endif>{{$opt['text']}}</option>
        @endforeach
    </select>
</div>