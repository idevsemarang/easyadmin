@php
$prefix_repeatable = (isset($repeatable))? true : false;
$preffix_method = (isset($method))? $method."_": "";
$radioFormId = (isset($field['name']))?$field['name']:'id_'.$key;
$radioFormId = $preffix_method . $radioFormId;
@endphp
<div class="{{(isset($field['class']))?$field['class']:'form-group'}}">
    <label>{{(isset($field['label']))?$field['label']:'Label '.$key}}
        @if(isset($field['required']) && $field['required'])
        <small class="text-danger">*</small>
        @endif
    </label>
    <div id="{{$radioFormId}}" class="p-2">
        @foreach($field['options'] as $key => $opt)
        <input type="radio" id="{{$radioFormId}}_{{$key}}" name="{{$field['name']}}" value="{{$opt['value']}}" @if($opt['value'] == $field['value']) selected @endif> {{$opt['text']}} <br>
        @endforeach
    </div>
</div>
