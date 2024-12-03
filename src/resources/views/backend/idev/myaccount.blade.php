@extends("easyadmin::backend.parent")
@section("content")
@push('mtitle')
{{$title}}
@endpush
<div class="pc-container">
  <div class="pc-content">

    <div class="page-header">
      <div class="page-block">
        <div class="row align-items-center">
          <div class="col-md-12">
            My Account
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-4 col-md-4 col-12">
        <div class="card mb-4">
          <div class="card-body p-3">
            <form id="form-maccount" action="{{url('update-profile')}}" method="post">
              @csrf
              <div class="row">
                @php $method = "create"; @endphp
                @foreach($fields as $key => $field)
                  @include('easyadmin::backend.idev.fields.'.$field['type'])
                @endforeach
              </div>
              <hr>
              <button type="button" id="btn-for-form-maccount" class="btn btn-outline-secondary" onclick="softSubmit('form-maccount','list')">
                    Submit
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
