<div class="{{(isset($field['class']))?$field['class']:'form-group'}}">
    <label>{{(isset($field['label']))?$field['label']:'Label '.$key}}</label>
    <table class="table idev-table table-responsive">
        <thead>
            <tr>
                @foreach($field['table_headers'] as $header)
                @php
                    $header_column = $header['name'];
                @endphp
                <th style="white-space: nowrap;">{{$header_column}} <button class="btn btn-sm btn-link" @if($header['order']) onclick="orderBy('list','{{$header_column}}')" @endif><i class="fa fa-sort"></i></button></th>
                @endforeach
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($field['body_datas'] as $key => $bd)
            <tr>
                @foreach($field['table_headers'] as $key2 => $hd)
                @if($key2 == 0)
                <td>
                    <input 
                        type="checkbox" 
                        name="{{$field['name']}}[]" 
                        value="{{$bd->id}}"
                        @if(in_array($bd->id, $field['value']))
                        checked
                        @endif
                    >
                </td>
                @else
                <td>{!! $bd->{$hd['column']} !!}</td>
                @endif
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</div>