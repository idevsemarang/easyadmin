@php
$prefix_repeatable = (isset($repeatable))? true : false;
$select_id = (isset($field['name']))?$field['name']:'id_'.$key;
$select_name = (isset($field['name']))?$field['name']:'name_'.$key;
$preffix_method = (isset($method))? $method."_": "";
@endphp
<div class="{{(isset($field['class']))?$field['class']:'form-group'}}">
    <label>{{(isset($field['label']))?$field['label']:'Label '.$key}}
        @if(isset($field['required']) && $field['required'])
        <small class="text-danger">*</small>
        @endif
    </label>
    <select 
        id="{{$preffix_method}}{{$select_id}}" 
        name="{{$select_name}}" 
        class="form-control idev-form support-live-select2 @if($prefix_repeatable) field-repeatable @endif">
        @foreach($field['options'] as $key => $opt)
        <option value="{{$opt['value']}}" 
        @if($opt['value'] == $field['value'] || $opt['value'] == request($select_name)) selected @endif
        >{{$opt['text']}}</option>
        @endforeach
    </select>
</div>

@if(isset($field['filter']))
@push('scripts')
<script>
    var currentUrl = "{{url()->current()}}"

$('#{{$select_id}}').on('change', function() {
    if (currentUrl.includes("?")) {
        currentUrl += "&{{$select_name}}="+$(this).val()
    }else{
        currentUrl += "?{{$select_name}}="+$(this).val()
    }
    window.location.replace(currentUrl);
})
</script>
@endpush
@endif