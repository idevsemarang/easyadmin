@php 
$prefix_repeatable = (isset($repeatable))? true : false;
$preffix_method = (isset($method))? $method."_": "";
@endphp
<div class="{{(isset($field['class']))?$field['class']:'form-group'}}">
    <label>{{(isset($field['label']))?$field['label']:'Label '.$key}} 
        @if(isset($field['required']) && $field['required'])
        <small class="text-danger">*</small>
        @endif
    </label> <input type="checkbox" id="cb-all-{{$preffix_method}}{{(isset($field['name']))?$field['name']:'id_'.$key}}">
    <div id="group-checklist-{{$preffix_method}}{{(isset($field['name']))?$field['name']:'id_'.$key}}">
    </div>
</div>


@if (request('from_ajax') && request('from_ajax') == true)
<div class="push-script">
@else
@push('scripts')
@endif
<script>
    $( document ).ready(function() {
        var countCb = 0
        var mId = "{{$preffix_method}}{{(isset($field['name']))?$field['name']:'id_'.$key}}"
        $( "#cb-all-"+mId).on( "change", function() {
            $(".cb-"+mId).prop("checked", $(this).prop('checked'))
            countCb = 0
            if ($(this).prop('checked')) {
                countCb = $(".cb-"+mId).length
            }
        })

        $( document ).on( "ajaxComplete", function() {
            $("#cb-all-"+mId).prop("checked", $(".cb-"+mId+":checked").length == $(".cb-"+mId).length)
            $( ".cb-"+mId).on( "change", function() {
                if ($(this).prop('checked')) {
                    countCb ++
                }else{
                    countCb --
                }
                $("#cb-all-"+mId).prop("checked", countCb == $(".cb-"+mId).length)
            })
        } );
        
    });
</script>
@if (request('from_ajax') && request('from_ajax') == true)
</div>
@else
@endpush
@endif