<div class="modal fade" tabindex="-1" role="dialog" id="modalDelete">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi</h5>
            </div>
            <div class="modal-body">
                <p>Are you sure, want to delete this data? <span id='attrDelete'></span></p>
                <form id="form-delete-{{$uri_key}}" action="#" method="post">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <button type="button" 
                        class="btn btn-sm btn-outline-primary"                             
                        onclick="softSubmit('form-delete-{{$uri_key}}', 'list-{{$uri_key}}')">Yes</button>
                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-dismiss="modal">No</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function setDelete(id) {
        var uriKey = "{{$uri_key}}"
        var uriDelete = "{{url($uri_key)}}/"+id
        $("#form-delete-" + uriKey).attr('action', uriDelete)
    }
</script>