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
            Hi, <b>{{ Auth::user()->name }} </b> You are logged in as <i>{{ Auth::user()->role->name }}</i> 
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-12">
        <div class="card mb-4">
          <div class="card-body p-3">
            <h3> Welcome to iDev Admin</h3>
            <p>An easy way to make admin page with crud, import and export. Feel free to override available method</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection