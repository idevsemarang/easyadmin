@php
$select_id = (isset($filter['name']))?$filter['name']:'id_'.$key;
$select_name = (isset($filter['name']))?$filter['name']:'name_'.$key;
$preffix_method = (isset($method))? $method."_": "";
$fName = isset($filter['label'])?$filter['label']:'Label '.$key;
$startVal = "";
$endVal = "";
if (isset($filter['selected_value'])) {
    if (isset($filter['selected_value']['start'])) {
        $startVal = $filter['selected_value']['start'];
    }
    if (isset($filter['selected_value']['end'])) {
        $endVal = $filter['selected_value']['end'];
    }
}
$startValue = isset($filter['label'])?$filter['label']:'Label '.$key;
@endphp
<div class="{{(isset($filter['class']))?$filter['class']:'form-group'}}">
    <div class="row">
        <div class="col-md-6 pr-1">
            <small>{{$fName}} Start</small>
            <input id="{{$preffix_method}}{{$select_id}}_monthstart" name="{{$select_name}}_start" type="month" class="form-control" value="{{$startVal}}">
        </div>
        <div class="col-md-6 pl-1">
            <small>{{$fName}} End</small>
            <input id="{{$preffix_method}}{{$select_id}}_monthend" name="{{$select_name}}_end" type="month" class="form-control" value="{{$endVal}}">
        </div>
    </div>
</div>

@push('scripts')
<script>
$('#{{$select_id}}_monthend').on('change', function() {
    updateFilter()        
})
</script>
@endpush
