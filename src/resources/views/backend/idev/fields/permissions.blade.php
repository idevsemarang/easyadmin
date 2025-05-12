<div class="{{(isset($field['class']))?$field['class']:'form-group'}}">
    <label>
        <input type="checkbox" class="check-all" name="check_all" > {{(isset($field['label']))?$field['label']:'Label '.$key}}
    </label>
    @php $keyA = 0; @endphp
    @foreach((new App\Helpers\Sidebar())->generate() as $key1 => $permission)
        <div class="row">
            <div class="col-md-12">
                <span>{{$permission['name']}}</span>
                <input type="hidden" name="keys[{{$keyA}}]" value="{{$permission['key']}}">
            </div>
        </div>
        <div class="row mb-2"> 
        @foreach($permission['access'] as $key2 => $access)
            <div class="col-md-3">
                <input 
                    type="checkbox" 
                    name="access[{{$keyA}}][{{$key2}}]" 
                    value="{{$access}}" 
                    class="check-unit"
                    @if(in_array($access,(new App\Helpers\Constant())->permissionDb($permission['key'], $field['value'])))
                    checked
                    @endif
                > {{$access}}
            </div>
        @endforeach
        </div>

        @foreach($permission['childrens'] as $key3 => $children)
        @php $keyA++; @endphp
        <div class="row">
            <div class="col-md-12">
                <span>{{$children['name']}}</span>
                <input type="hidden" name="keys[{{$keyA}}]" value="{{$children['key']}}">
            </div>
        </div>
        <div class="row mb-2"> 
            @foreach($children['access'] as $key4 => $access)
            <div class="col-md-3">
                <input 
                    type="checkbox" 
                    name="access[{{$keyA}}][{{$key4}}]" 
                    value="{{$access}}" 
                    class="check-unit"
                    @if(in_array($access,(new App\Helpers\Constant())->permissionDb($children['key'], $field['value'])))
                    checked
                    @endif
                > {{$access}}
            </div>
            @endforeach
        </div>
        @endforeach

        @php $keyA++; @endphp

    @endforeach

   
</div>

@push('scripts')
<script>
checkCount()

$('.check-all').change(function ($e){
    $(".check-unit").prop('checked', $(this).prop('checked'))
})

$('.check-unit').change(function ($e){
    checkCount()
})

function checkCount() {
    var countChecked = $('.check-unit:checked').length;
    var countCheckbox = $('.check-unit').length;  
    if(countChecked == countCheckbox){
        $(".check-all").prop('checked', true)
    }  else{
        $(".check-all").prop('checked', false)
    }
}
</script>

@endpush