@php
$prefix_repeatable = (isset($repeatable))? true : false;
$ajaxUrl = $field['ajax_url'];
$select2Ajax_name = (isset($field['name']))?$field['name']:'name_'.$key;
$preffix_method = (isset($method))? $method."_": "";
$select2AjaxFormid = (isset($field['name']))?$field['name']:'id_'.$key;
$select2AjaxFormid = $preffix_method . $select2AjaxFormid;

@endphp
<div class="{{(isset($field['class']))?$field['class']:'form-group'}}">
    <label>{{(isset($field['label']))?$field['label']:'Label '.$key}}
        @if(isset($field['required']) && $field['required'])
        <small class="text-danger">*</small>
        @endif
    </label>
    <select 
        id="{{$select2AjaxFormid}}" 
        name="{{$select2Ajax_name}}" 
        class="form-control @if($prefix_repeatable) field-repeatable @endif"></select>
</div>

<script>
    
    $("#{{$select2AjaxFormid}}").select2({
        dropdownParent: $('.offcanvas'),
        ajax: {
            url: "{{$ajaxUrl}}",
            dataType: 'json',
            data: function (params) {
                return {
                    search: $.trim(params.term)
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });
</script>


