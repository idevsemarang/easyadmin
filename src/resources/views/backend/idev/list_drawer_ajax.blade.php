<span class="current-title">{{$title}}</span>

<div class="row" id="section-list-{{$uri_key}}">
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
                        <small class="count-total-list-{{$uri_key}} float-end mt-2">0 Data</small>
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
                                        <input type="hidden" name="order_state" class="current-order-state-{{$uri_key}}" value="ASC">
                                    </div>
                                </div>
                                @if(isset($filters))
                                @foreach ($filters as $key => $filter)
                                @include('easyadmin::backend.idev.filters.'.$filter['type'])
                                @endforeach
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
                <div class="table-responsive p-0">
                    <table id="table-list-{{$uri_key}}" class="table table-striped">
                        <thead>
                            <tr>
                                @foreach($table_headers as $header)
                                @php
                                $header_name = $header['name'];
                                $header_column = $header['column'];
                                @endphp
                                @if($header['order'])
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{$header_name}}
                                    <button class="btn btn-sm btn-link" onclick="orderBy('list-{{$uri_key}}','{{$header_column}}')"><i class="bi bi-arrow-up"></i></button>
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
                    <div id="paginate-list-{{$uri_key}}" class="mt-4"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="section-preview-{{$uri_key}}" style="display:none;">
    <div class="row mb-2">
        <div class="col-md-12">
            <b>Detail {{$title}}</b>
            <button type="button" class="btn btn-sm btn-outline-danger float-end close-preview">
                <i class="bi bi-x"></i>
            </button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 content-preview"></div>
    </div>
</div>

@push('styles')
<div class="push-style">
    @if(isset($import_styles))
    @foreach($import_styles as $ist)
    <link rel="stylesheet" href="{{$ist['source']}}">
    @endforeach
    @endif
</div>
@endpush

@push('scripts')
<div class="push-script">
    <div class="offcanvas offcanvas-end" tabindex="-1" id="createForm-{{$uri_key}}" aria-labelledby="createForm-{{$uri_key}}">
        <div class="offcanvas-header bg-themes">
            <h5 class="text-white">Create New</h5>
            <button type="button" class="btn-close text-white text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form id="form-create-{{$uri_key}}" action="{{$url_store}}" method="post">
                @csrf
                <div class="row">
                    @php $method = "create"; @endphp
                    @foreach($fields as $key => $field)
                    @include('backend.idev.fields.'.$field['type'])
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

    <div class="fixed-plugin">
        <a class="fixed-plugin-button text-white position-fixed px-3 py-2" data-bs-toggle="offcanvas" data-bs-target="#createForm-{{$uri_key}}">
            <i class="fa fa-plus py-2"> </i>
        </a>
    </div>
    @if(isset($import_scripts))
    @foreach($import_scripts as $isc)
    <script src="{{$isc['source']}}"></script>
    @endforeach
    @endif
    <script>
        function updateFilter() {
            var queryParam = $("#form-filter-list-{{$uri_key}}").serialize();
            var currentHrefPdf = $("#export-pdf").attr('data-base-url')
            var currentHrefExcel = $("#export-excel").attr('data-base-url')

            $("#export-pdf").attr('href', currentHrefPdf + "?" + queryParam)
            $("#export-excel").attr('href', currentHrefExcel + "?" + queryParam)
            idevTable("list-{{$uri_key}}")
        }
    </script>
    @foreach($actionButtonViews as $key => $abv)
    @include($abv)
    @endforeach
</div>