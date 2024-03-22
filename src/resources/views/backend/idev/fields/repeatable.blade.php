@php 
$prefix_repeatable = (isset($repeatable))? true : false;
$preffix_method = (isset($method))? $method."_": "";
@endphp
<div class="{{(isset($field['class']))?$field['class']:'form-group'}}">
    <label>{{(isset($field['label']))?$field['label']:'Label '.$key}}</label>
    <div class="{{$preffix_method}}repeatable-sections">
        @php 
        $field_count = 0;
        $enable_action = $field['enable_action'];
        $row_count = sizeof($field['html_fields']);
        @endphp
       
        <div id="{{$preffix_method}}repeatable-0" class="row field-sections">

            @foreach($field['html_fields'] as $key2 => $child_fields)
            @php
            $field = $child_fields;
            $repeatable = true;
            $field['name'] = $field['name']."[]"
            @endphp
            @include("easyadmin::backend.idev.fields.".$field['type'])
            @endforeach

            @if($enable_action)
            <div class="col-md-1 remove-section">
                <button type='button' class='btn btn-sm btn-circle btn-danger my-4 text-white' onclick='remove(0)'>
                    <i class='ti ti-minus' data-toggle='tooltip' data-placement='top' title='Remove'> </i>
                </button>
            </div>
            @endif
        </div>
    </div>
    
    @if($enable_action)
    <div class="row">
        <div class="col-md-4">
            <button type="button" class="btn btn-sm btn-secondary my-2 text-white" onclick="add()">
                <i class="fa fa-plus" data-toggle="tooltip" data-placement="top" title="Add"> </i> 1 ITEM
            </button>
        </div>
    </div>
    @endif
</div>
@push('styles')
    <style>
        .btn-circle{
            border-radius: 50%;
        }
    </style>
@endpush
@push('scripts')
<script>

    function setRepeatableID(index){
        var epochMilliseconds = Date.now();
        var epochSeconds = Math.floor(epochMilliseconds / 1000);

        $('.field-sections:last()').attr('id', '{{$preffix_method}}repeatable-'+epochSeconds)
        // for (var i = 0; i < fieldCount; i++) {
        //     var newID = 'field-'+arrAlpabhet[i]+'-'+index
        //     $('.field-sections:last() .field-repeatable:eq('+i+')').attr('id', newID)
        //     if ($('#'+newID).hasClass("support-live-select2")) {
        //         $('.field-sections:last() div:eq('+i+') span').remove()
        //         $('#'+newID).removeClass("select2-hidden-accessible")
        //         $('#'+newID).removeAttr("data-select2-id")
        //         $('#'+newID).removeAttr("tabindex")
        //         $('#'+newID).removeAttr("aria-hidden")
        //         $('#'+newID+' option').removeAttr("data-select2-id")
        //         $('#repeatable-'+index).removeAttr("data-select2-id")
        //         $('#'+newID).select2();
        //     }
        // }
    }


    function add() {
        var epochMilliseconds = Date.now();
        var epochSeconds = Math.floor(epochMilliseconds / 1000);

        $('.field-sections:last()').attr('id', '{{$preffix_method}}repeatable-'+epochSeconds)

        $('.{{$preffix_method}}repeatable-sections .row:last()').clone().appendTo($('.{{$preffix_method}}repeatable-sections'))
        // setRepeatableID(numButton)

        var htmlRemove = "<button type='button' class='btn btn-sm btn-circle btn-danger my-4 text-white' onclick='remove("+epochSeconds+")'>"
        htmlRemove += "<i class='ti ti-minus' data-toggle='tooltip' data-placement='top' title='Remove'> </i>"
        htmlRemove += "</button>"
        $('#{{$preffix_method}}repeatable-'+epochSeconds+' .remove-section').html(htmlRemove)
    }

    
    function remove(index) {
        $("#{{$preffix_method}}repeatable-"+index).remove();
    }
</script>
@endpush