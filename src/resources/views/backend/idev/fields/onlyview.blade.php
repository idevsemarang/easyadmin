@php
$preffix_method = (isset($method))? $method."_": "";
@endphp
<div class="{{(isset($field['class']))?$field['class']:'form-group'}}">
    <label>{{(isset($field['label']))?$field['label']:'Label '.$key}}</label>
    @if(array_key_exists('options', $field))
    @foreach($field['options'] as $key => $opt)
        @if($opt['value'] == $field['value'])
        <div class="form-control"> <span id="{{$preffix_method}}{{(isset($field['name']))?$field['name']:'id_'.$key}}">{{$opt['text']}}</span> </div>
        @endif
    @endforeach
    @else
        <div class="form-control"> <span id="{{$preffix_method}}{{(isset($field['name']))?$field['name']:'id_'.$key}}">{{(isset($field['value']))?$field['value']:''}}</span> </div>
    @endif
</div>
