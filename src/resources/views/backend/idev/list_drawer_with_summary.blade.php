@extends("easyadmin::backend.parent")
@section("content")
@push('mtitle')
{{$title}}
@endpush
<div class="pc-container" id="section-list-{{$uri_key}}">
    <div class="pc-content">
        <div class="page-header">
            <div class="page-block">
                @if(isset($headerLayout))
                    @include('backend.idev.parts.'.$headerLayout.'')
                @else
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <span class="count-total-list-{{$uri_key}} float-end mt-2">0 Data</span>
                        </div>
                        @if (in_array('create', $permissions))
                        <a class="btn btn-secondary float-end text-white mx-1" data-bs-toggle="offcanvas" data-bs-target="#createForm-{{$uri_key}}">
                            Create
                        </a>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8 col-md-6">
                                @foreach ($more_actions as $ma)
                                @if (isset($ma['key']) && in_array($ma['key'], $permissions))
                                {!! $ma['html_button'] !!}
                                @endif
                                @endforeach
                            </div>
                            <div class="col-4 col-md-6">
                            </div>
                            <div class="col-md-12">
                                <form id="form-filter-list-{{$uri_key}}" action="{{$uri_list_api}}" method="get">
                                    <div class="row my-3">
                                        <div class="col-md-2">
                                            <small for="">Search</small>
                                            <div class="form-group">
                                                <input class="form-control search-list-{{$uri_key}}" name="search" placeholder="Type for search...">
                                                <input type="hidden" name="route_name" class="route-name-{{$uri_key}}" value="{{$uri_key}}">
                                                <input type="hidden" name="page" class="current-paginate-{{$uri_key}}">
                                                <input type="hidden" name="order" class="current-order-{{$uri_key}}">
                                                <input type="hidden" name="manydatas" class="current-manydatas-{{$uri_key}}" value="10">
                                                <input type="hidden" name="order_state" class="current-order-state-{{$uri_key}}" value="ASC">
                                            </div>
                                        </div>
                                        @if(isset($filters))
                                        @foreach ($filters as $key => $filter)
                                            @if (View::exists('backend.idev.filters.'.$filter['type']))
                                                @include('backend.idev.filters.'.$filter['type'])
                                            @else
                                                @include('easyadmin::backend.idev.filters.'.$filter['type'])
                                            @endif
                                        @endforeach
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>
                        <h5>Summary</h5>
                        <div class="summary-section mb-4"></div>
                        <h5>Report Table</h5>
                        <div class="table-responsive p-0">
                            <table id="table-list-{{$uri_key}}" class="table table-hover">
                                <thead>
                                    <tr>
                                        @foreach($table_headers as $header)
                                        @php
                                        $header_name = $header['name'];
                                        $header_column = $header['column'];
                                        @endphp
                                        @if($header['order'])
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="white-space: nowrap;">{{$header_name}}
                                            <button class="btn btn-sm btn-link" onclick="orderBy('list-{{$uri_key}}','{{$header_column}}')"><i class="ti ti-arrow-up"></i></button>
                                        </th>
                                        @else
                                        <th style="white-space: nowrap;">{{$header_name}}
                                        </th>
                                        @endif
                                        @endforeach
                                        <th class="col-action"></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                            <div class="row">
                                <div class="col-md-1 col-lg-1 col-4">
                                    <select class="form-control form-control-sm" id="manydatas-show-{{$uri_key}}">
                                        @foreach(['10', '20', '50', '100', 'All'] as $key => $showData)
                                        <option value="{{$showData}}">{{$showData}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-11">
                                    <div id="paginate-list-{{$uri_key}}"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="pc-container" id="section-preview-{{$uri_key}}" style="display:none;">
    <div class="pc-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                           <b>Detail {{$title}}</b> 
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger float-end close-preview">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-12 content-preview"></div>
        </div>
    </div>
</div>

@push('styles')
@if(isset($import_styles))
@foreach($import_styles as $ist)
<link rel="stylesheet" href="{{$ist['source']}}">
@endforeach
@endif
@endpush

@push('scripts')
@if(isset($import_scripts))
@foreach($import_scripts as $isc)
<script src="{{$isc['source']}}"></script>
@endforeach
@endif

@if (in_array('create', $permissions))
<div class="offcanvas offcanvas-end @if(isset($drawerExtraClass)) {{ $drawerExtraClass }} @endif" tabindex="-1" id="createForm-{{$uri_key}}" aria-labelledby="createForm-{{$uri_key}}">
    <div class="offcanvas-header border-bottom bg-secondary p-4">
        <h5 class="text-white m-0">Create New</h5>
        <button type="button" class="btn-close text-white text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form id="form-create-{{$uri_key}}" action="{{$url_store}}" method="post">
            @csrf
            <div class="row">
                @php $method = "create"; @endphp
                @foreach($fields as $key => $field)
                @if (View::exists('backend.idev.fields.'.$field['type']))
                    @include('backend.idev.fields.'.$field['type'])
                @else
                    @include('easyadmin::backend.idev.fields.'.$field['type'])
                @endif
                @endforeach
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group my-2">
                        <button id="btn-for-form-create-{{$uri_key}}" type="button" class="btn btn-outline-secondary" onclick="softSubmit('form-create-{{$uri_key}}', 'list-{{$uri_key}}')">Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endif
<script>
    const periodicReportUrl = "{{ $summaryUrl }}"
    
    $(document).ready(function() {
        if ($(".idev-actionbutton").children().length == 0) {
            $("#dropdownMoreTopButton").remove()
            $(".idev-actionbutton").remove()
        }
        idevTable("list-{{$uri_key}}")
        $('form input').on('keypress', function(e) {
            return e.which !== 13;
        });

        showDetailPage(periodicReportUrl, ".summary-section")
    })
    $(".search-list-{{$uri_key}}").keyup(delay(function(e) {
        var dInput = this.value;
        if (dInput.length > 3 || dInput.length == 0) {
            $(".current-paginate-{{$uri_key}}").val(1)
            $(".search-list-{{$uri_key}}").val(dInput)
            updateFilter()
        }
    }, 500))


    $("#manydatas-show-{{$uri_key}}").change(function(){
        $(".current-manydatas-{{$uri_key}}").val($(this).val())
        idevTable("list-{{$uri_key}}")
    });

    function updateFilter() {
        var queryParam = $("#form-filter-list-{{$uri_key}}").serialize();
        var currentHrefPdf = $("#export-pdf").attr('data-base-url')
        var currentHrefExcel = $("#export-excel").attr('data-base-url')

        $("#export-pdf").attr('href', currentHrefPdf + "?" + queryParam)
        $("#export-excel").attr('href', currentHrefExcel + "?" + queryParam)
        idevTable("list-{{$uri_key}}")
        showDetailPage(periodicReportUrl+ "?" + queryParam, ".summary-section", 69)
    }

</script>
@foreach($actionButtonViews as $key => $abv)
@include($abv)
@endforeach
@endpush
@endsection