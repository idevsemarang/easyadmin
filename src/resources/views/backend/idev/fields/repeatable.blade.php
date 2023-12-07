<div class="{{(isset($field['class']))?$field['class']:'form-group'}}">
    <label>{{(isset($field['label']))?$field['label']:'Label '.$key}}</label>
    <div class="repeatable-sections">
        @php 
        $field_count = 0;
        $enable_action = $field['enable_action'];
        $row_count = sizeof($field['html_fields']);
        @endphp
        @foreach($field['html_fields'] as $key2 => $child_fields)
        @php 
        $field_count = sizeof($child_fields)
        @endphp
        <div id="repeatable-{{$key2}}" class="row field-sections">
            @foreach($child_fields as $key3 => $cf)
            @php 
                $field = $cf; 
                $repeatable = true; 
            @endphp
            @include('backend.standard.fields.'.$field['type'])
            @endforeach
            @if($enable_action)
            <div class="col-md-1 remove-section">
                @if($key2 > 0)
                <button type='button' class='btn btn-sm btn-circle btn-danger my-4 text-white' onclick='remove({{$key2}})'>
                    <i class='fa fa-minus' data-toggle='tooltip' data-placement='top' title='Remove'> </i>
                </button>
                @endif
            </div>
            @endif
        </div>
        @endforeach
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

@push('scripts')
<script>
    var fieldCount = "{{$field_count}}"
    var totalRow = "{{$row_count}}"
    var arrAlpabhet = []
    for (var i = "a".charCodeAt(0); i <= "z".charCodeAt(0); i++) {
        arrAlpabhet.push(String.fromCharCode(i));
    }
    for (var i = 0; i < totalRow; i++) {
        setRepeatableID(i)
        console.log(i);
    }

    function setRepeatableID(index){
        $('.field-sections:last()').attr('id', 'repeatable-'+index)
        for (var i = 0; i < fieldCount; i++) {
            var newID = 'field-'+arrAlpabhet[i]+'-'+index
            $('.field-sections:last() .field-repeatable:eq('+i+')').attr('id', newID)
            if ($('#'+newID).hasClass("support-live-select2")) {
                $('.field-sections:last() div:eq('+i+') span').remove()
                $('#'+newID).removeClass("select2-hidden-accessible")
                $('#'+newID).removeAttr("data-select2-id")
                $('#'+newID).removeAttr("tabindex")
                $('#'+newID).removeAttr("aria-hidden")
                $('#'+newID+' option').removeAttr("data-select2-id")
                $('#repeatable-'+index).removeAttr("data-select2-id")
                $('#'+newID).select2();
            }
        }
    }


    function add() {
        totalRow++
        var numButton = totalRow - 1
        $('.repeatable-sections .row:last()').clone().appendTo($('.repeatable-sections'))
        setRepeatableID(numButton)

        var htmlRemove = "<button type='button' class='btn btn-sm btn-circle btn-danger my-4 text-white' onclick='remove("+numButton+")'>"
        htmlRemove += "<i class='fa fa-minus' data-toggle='tooltip' data-placement='top' title='Remove'> </i>"
        htmlRemove += "</button>"
        $('#repeatable-'+numButton+' .remove-section').html(htmlRemove)
    }

    
    function remove(index) {
        // totalRow--
        if (index > 0) {
            $("#repeatable-"+index).remove();
        }
    }
</script>
@endpush