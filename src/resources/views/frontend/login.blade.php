@extends("easyadmin::frontend.parent")
@push('mtitle')
{{$title}}
@endpush
@section("contentfrontend")
<div class="auth-main">
    <div class="auth-wrapper v3">
        <div class="auth-form">
            <div class="card my-5">
                <div class="card-body">

                    <a href="#" class="d-flex justify-content-center">
                        <img src="{{ config('idev.app_logo', asset('easyadmin/idev/img/logo-idev.png')) }}">
                    </a>
                    <div class="row">
                        <div class="d-flex justify-content-center">
                            <div class="auth-header">
                                <!-- <h2 class="text-dark my-2"><b>Please Login</b></h2> -->
                            </div>
                        </div>
                    </div>
                    
                    <form id="form-login" action="{{url('login')}}" method="post">
                        @csrf
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="create_email" name="email" placeholder="Email address / Username" />
                            <label for="floatingInput">Email address / Username</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="create_password" name="password" placeholder="Password" />
                            <label for="floatingInput">Password</label>
                        </div>
                        
                        <div class="d-grid mt-4">
                            <button type="button" class="btn btn-primary-idev" id="btn-for-form-login" onclick="submitAfterValid('form-login')">Sign In</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@push('fescripts')
<script>
    var input = document.getElementById("create_password");

    input.addEventListener("keypress", function(event) {
        if (event.key === "Enter") {
            event.preventDefault();
            submitAfterValid('form-login')
        }
    });
</script>
@endpush
@endsection
