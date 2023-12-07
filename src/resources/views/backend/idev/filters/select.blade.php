@php
$prefix_repeatable = (isset($repeatable))? true : false;
$select_id = (isset($filter['name']))?$filter['name']:'id_'.$key;
$select_name = (isset($filter['name']))?$filter['name']:'name_'.$key;
$preffix_method = (isset($method))? $method."_": "";
@endphp
<div class="{{(isset($filter['class']))?$filter['class']:'form-group'}}">
    <small>{{(isset($filter['label']))?$filter['label']:'Label '.$key}}</small>
    <select 
        id="{{$preffix_method}}{{$select_id}}" 
        name="{{$select_name}}" 
        class="form-control @if($prefix_repeatable) filter-repeatable @endif">
        @foreach($filter['options'] as $key => $opt)
        <option value="{{$opt['value']}}" @if(isset($filter['selected_value']) && $opt['value'] == $filter['selected_value']) selected @endif>{{$opt['text']}}</option>
        @endforeach
    </select>
</div>

@if (request('from_ajax') && request('from_ajax') == true)
<div class="push-script">
@else
@push('scripts')
@endif
<script>
$('#{{$select_id}}').on('change', function() {
    updateFilter()        
})
</script>
@if (request('from_ajax') && request('from_ajax') == true)
<div class="push-script">
@else
@endpush
@endif
