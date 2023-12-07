@php
$counting = 0;
$arrKey = [];
$da = $detail->toArray();
@endphp
@foreach ($da as $key => $d)
@if(str_contains($key, "btn_") == false)
@php
$counting++;
$arrKey[] = $key;
@endphp
@endif
@endforeach
<div class="card">
    <div class='card-body'>
        <div class="row">

            @if($counting > 6)
            @php $max = round($counting/2); @endphp
            <div class="col-md-6">
                @for($i = 0; $i < $max; $i++) <p>
                    <small>{{ str_replace("_", " ", $arrKey[$i])  }} </small><br>
                    @if ($arrKey[$i] == 'view_image')
                    <img src="{{ $da[$arrKey[$i]] }}" class='img-thumbnail img-responsive' width='120px' alt="">
                    @else
                    <b>{{ $da[$arrKey[$i]] ?? "-" }}</b>
                    @endif
                    </p>
                    @endfor
            </div>
            <div class="col-md-6">
                @for($j = $max; $j < $counting; $j++) <p>
                    <small>{{ str_replace("_", " ", $arrKey[$j])  }} </small><br>
                    @if ($arrKey[$j] == 'view_image')
                    <img src="{{ $da[$arrKey[$j]] }}" class='img-thumbnail img-responsive' width='120px' alt="">
                    @else
                    <b>{{ $da[$arrKey[$j]] ?? "-" }}</b>
                    @endif
                    </p>
                    @endfor
            </div>
            @else
            <div class="col-md-6">
                @for($i = 0; $i < $counting; $i++) <p>
                    <small>{{ str_replace("_", " ", $arrKey[$i])  }} </small><br>
                    @if ($arrKey[$i] == 'view_image')
                    <img src="{{ $da[$arrKey[$i]] }}" class='img-thumbnail img-responsive' width='120px' alt="">
                    @else
                    <b>{{ $da[$arrKey[$i]] ?? "-" }}</b>
                    @endif
                    </p>
                    @endfor
            </div>
            @endif

        </div>
    </div>
</div>