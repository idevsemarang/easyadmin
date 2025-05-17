<div class="offcanvas offcanvas-end" data-bs-scroll="true" data-bs-backdrop="true" tabindex="-1"
    id="drawerEdit">
    <div class="offcanvas-header bg-secondary p-4">
        <h5 class="text-white m-0">Edit</h5>
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
    idevSetEdit(id, "{{$uri_key}}")
}
</script>