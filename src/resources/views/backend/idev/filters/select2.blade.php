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
        class="form-control support-live-select2 @if($prefix_repeatable) filter-repeatable @endif">
        @foreach($filter['options'] as $key => $opt)
        <option value="{{$opt['value']}}">{{$opt['text']}}</option>
        @endforeach
    </select>
</div>

@push('scripts')
<script>
$('#{{$select_id}}').on('change', function() {
    updateFilter()        
})
</script>
@endpush
