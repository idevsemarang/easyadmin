<div class="{{(isset($field['class']))?$field['class']:'form-group'}}">
    <label>{{(isset($field['label']))?$field['label']:'Label '.$key}}</label>
    <div class="field-bulktable">
        <div class="row">
            <div class="col-md-6">
                <span class="total-data-{{$field['name']}}"></span>
            </div>
            <div class="col-md-3">
                <span class="total-checked-{{$field['name']}} fw-bold">0 Checked</span>
            </div>
            <div class="col-md-3">
                <input type="text" placeholder="search..." class="form-control form-control-sm search-{{$field['name']}}">
            </div>
        </div>
        <table class="table idev-table table-responsive ajx-table-{{$field['name']}}">
            <thead>
                <tr>
                    <th>
                        # <!--input type="checkbox" class="check-all-{{$field['name']}}" value="flagall" -->
                    </th>
                    @foreach($field['table_headers'] as $header)
                    <th style="white-space: nowrap;">{{$header}}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <div class="paginate-{{$field['name']}}"></div>
        <input type="hidden" name="{{$field['name']}}" class="json-{{$field['name']}}" value="[]">
    </div>
</div>

@push('scripts')
<script>
    var ajaxUrl = "{{$field['ajaxUrl']}}"
    var primaryKey = "{{$field['key']}}"
    var stateKey = []
    $(document).ready(function() {
        getTableContent(ajaxUrl)

        // $(".check-all-{{$field['name']}}").change(function(){
        //     $(".check-{{$field['name']}}").prop('checked', $(this).prop('checked'))
        // })

        $(".search-{{$field['name']}}").keyup(delay(function(e) {
            var dInput = this.value;
            if (dInput.length > 3 || dInput.length == 0) {
                getTableContent(ajaxUrl + "?search=" + dInput)
            }
        }, 500))
    });

    function getTableContent(ajaxUrl) {
        $.get(ajaxUrl, function(response) {
            var headers = response.header
            var bodies = response.body
            var mHtml = ""
            var intCurrentData = 0
            $.each(bodies.data, function(index1, body) {
                var setActiveChecked = stateKey.includes(body[primaryKey]) ? "checked" : ""

                mHtml += "<tr>"
                mHtml += "<td><input type='checkbox' class='check-{{$field['name']}}' value='" + body[primaryKey] + "' " + setActiveChecked + "></td>"
                $.each(headers, function(index2, header) {
                    mHtml += "<td>" + body[header] + "</td>"
                })
                mHtml += "</tr>"
                intCurrentData++
            })

            var paginateLink = ""
            $.each(bodies.links, function(index, link) {
                if (link.url != null && link.label != "&laquo; Previous" && link.label != "Next &raquo;") {
                    var linkActive = link.active ? "btn-primary" : "btn-outline-primary"
                    paginateLink += "<button data-url='" + link.url + "' class='btn btn-sm btn-paginate-{{$field['name']}} " + linkActive + "' type='button'>" + link.label + "</button>"
                }
            })

            $(".paginate-{{$field['name']}}").html(paginateLink)
            $(".ajx-table-{{$field['name']}} tbody").html(mHtml)

            $(".btn-paginate-{{$field['name']}}").click(function() {
                getTableContent($(this).data('url'))
            })

            $(".check-{{$field['name']}}").change(function() {
                var intCurrentVal = parseInt($(this).val())
                if ($(this).prop('checked')) {
                    stateKey.push(intCurrentVal)
                } else {
                    stateKey = removeStateKey(stateKey, intCurrentVal)
                }

                $(".json-{{$field['name']}}").val(JSON.stringify(stateKey))
                $(".total-checked-{{$field['name']}}").text(stateKey.length + " Checked")
            })

            $(".total-data-{{$field['name']}}").text("Total : " + intCurrentData + "/" + bodies.total + " Data (s)")

        });
    }

    function removeStateKey(arr, elementToRemove) {
        return arr.filter(function(item) {
            return item !== elementToRemove;
        });
    }
</script>
@endpush