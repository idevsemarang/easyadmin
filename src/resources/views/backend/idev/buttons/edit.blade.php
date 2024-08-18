<div class="offcanvas offcanvas-end" data-bs-scroll="true" data-bs-backdrop="true" tabindex="-1"
    id="drawerEdit">
    <div class="offcanvas-header bg-secondary">
        <h5 class="text-white">Edit</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form id="form-edit-{{$uri_key}}" action="#" method="post">
            @csrf
            {{ method_field('PUT') }}
            <div class="row content-fields">
                @php $method = "edit"; @endphp
                @foreach($edit_fields as $key => $field)
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
                        <button id="btn-for-form-edit-{{$uri_key}}" type="button" class="btn btn-outline-secondary"
                            onclick="softSubmit('form-edit-{{$uri_key}}', 'list-{{$uri_key}}')">Submit</button>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function setEdit(id) {
    var uriKey = "{{$uri_key}}"
    var uriEdit = "{{url($uri_key)}}/"+id+"/edit"
    var uriUpdate = "{{url($uri_key)}}/"+id+""
    $('.idev-form').val(null).trigger('change')
    // $('.select2-hidden-accessible').html("")
    $('.form-checkbox').prop("checked", false)
    $("#form-edit-" + uriKey).attr('action', uriUpdate)

    $.get(uriEdit, function(response) {
        var fields = response.fields

        $.each(fields, function(key, field) {
            
           
            if (field.type == "onlyview") {
                $('#edit_' + field.name).text(field.value)
                if (field.options) {
                    $.each(field.options, function(index, option) {
                        if (option.value == field.value) {
                            $('#edit_' + field.name).text(option.text)
                        }
                    })
                }
            }
            else if (field.type == "image") {
                $('.thumb_'+field.name).attr('src', field.value)
            }
            else if (field.type == "select2_ajax_multiple") {
                var selected = [];
                var initials = [];
                $.each(field.value, function(index, option) {
                    initials.push({
                        id: option.value,
                        text: option.text
                    })
                    selected.push(option.value)
                });
                $('#edit_' + field.name).select2({
                    data: initials,
                    ajax: {
                        url: field.ajax_url,
                        cache: true,
                        delay: 250,
                        data: function(params) {
                            var query = {
                                search: params.term,
                                type: 'public'
                            }
                            return query;
                        },
                    },
                });
                $('#edit_' + field.name).val(selected).trigger('change')
                $('.s2-' + field.name + ' select').val(selected).trigger('change')
            }
            else if (field.type == "group_checklist") {
                var htmlCl = ""
                $.each(field.checklists, function(index, cl) {
                    var labelName = cl.label.replaceAll("-", " ")

                    htmlCl += "<p class='mt-2 mb-0'>"+labelName.toUpperCase()+"</p>"
                    htmlCl += "<div class='row'>"
                    $.each(cl.checkbox, function(index, cb) {
                        var isChecked = cb.enable ? "checked" : ""
                        var accessName = cb.name.replaceAll("-", " ")
                        htmlCl += "<div class='col-md-6'>"
                        htmlCl += "<input type='checkbox' class='cb-edit_"+field.name+"' name='"+field.name+"["+cl.label+"][]' value='"+cb.name+"' "+isChecked+"> <small> "+accessName+"</small>"
                        htmlCl += "</div>"
                    });
                    htmlCl += "</div>"
                });
                $('#group-checklist-edit_'+field.name).html(htmlCl)
            }else if(field.type == "repeatable"){
                var jsonValues = JSON.parse(field.value)
                var cloneElement = $('.edit_repeatable-sections .row:last()').clone();
                var arrClone = [cloneElement]

                $('.edit_repeatable-sections').html("")
                $.each(jsonValues, function(index, jv) {

                    $('.edit_repeatable-sections').html(arrClone)

                    $('.edit_repeatable-sections .row:last()').attr('id', 'edit_repeatable-'+index)
                    // $('.edit_repeatable-sections').append(cloneElement);
                    arrClone.push(cloneElement.clone())
                    $.each(field.html_fields, function(index2, hf) {
                        $("#edit_repeatable-"+index+" #edit_" + hf.name).val(jv[hf.name])
                    })
                });
            }
            else{
                $("#edit_" + field.name).val(field.value)
                $('#edit_' + field.name).trigger('change')
            }
        })
    })
}
</script>
