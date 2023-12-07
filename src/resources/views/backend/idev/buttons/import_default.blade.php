<div class="modal fade" tabindex="-1" role="dialog" id="modalImportDefault">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmation</h5>
            </div>
            <div class="modal-body">
                <p>Please import data based on this template : 
                    <a href="{{$templateImportExcel}}">template.xlsx</a>
                </p>
                <form id='form-import' class="m-t-20" action="{{url($uri_key.'-import-excel-default')}}" method="post" >
                    {{ csrf_field() }}
                    <div class="my-2">
                        <label for="">Drop Your File</label>
                        <input type="file" name="excel_file" class="form-control">
                    </div>
                    <hr>
                    <button type="button" id="btn-for-form-import" class="btn btn-sm btn-outline-primary" onclick="softSubmit('form-import', 'list-{{$uri_key}}')">Submit</button>
                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-dismiss="modal">Close</button>
                </form>
            </div>
        </div>
    </div>
</div>
