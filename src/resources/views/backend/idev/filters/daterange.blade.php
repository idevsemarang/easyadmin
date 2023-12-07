@php
$select_id = (isset($filter['name']))?$filter['name']:'id_'.$key;
$select_name = (isset($filter['name']))?$filter['name']:'name_'.$key;
$preffix_method = (isset($method))? $method."_": "";
@endphp
<div class="{{(isset($filter['class']))?$filter['class']:'form-group'}}">
    <small>{{(isset($filter['label']))?$filter['label']:'Label '.$key}}</small>
    <div class="row">
        <div class="col-md-6 pr-1">
            <input id="{{$preffix_method}}{{$select_id}}_start" name="{{$select_name}}_start" type="date" class="form-control">
        </div>
        <div class="col-md-6 pl-1">
            <input id="{{$preffix_method}}{{$select_id}}_end" name="{{$select_name}}_end" type="date" class="form-control">
        </div>
    </div>
</div>

@push('scripts')
<script>
$('#{{$select_id}}_end').on('change', function() {
    updateFilter()        
})
</script>
@endpush
