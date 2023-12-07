<div class="dropdown my-2">
    <button class="btn btn-outline-danger dropdown-toggle  float-end" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
        Hi {{ Auth::user()->name }}
    </button>
    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
        <li><a class="dropdown-item" href="#">Profile</a></li>
        <li><a class="dropdown-item" href="{{route('logout')}}">Logout</a></li>
    </ul>
</div>