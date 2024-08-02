<!DOCTYPE html>
<html lang="en">

<head>
    <title>@stack("mtitle")</title>
    <meta charset="utf-8" />
    <meta name="base_url" content="{{url('')}}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Berry is made using Bootstrap 5 design framework. Download the free admin template & use it for your project." />
    <meta name="keywords" content="Berry, Dashboard UI Kit, Bootstrap 5, Admin Template, Admin Dashboard, CRM, CMS, Bootstrap Admin Template" />
    <meta name="author" content="CodedThemes" />
	<link rel="icon" href=" {{ config('idev.app_favicon', asset('easyadmin/idev/img/favicon.png')) }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" id="main-font-link" />

    <link rel="stylesheet" href="{{ asset('easyadmin/theme/default/fonts/tabler-icons.min.css')}}" />

    <link rel="stylesheet" href="{{ asset('easyadmin/theme/default/fonts/material.css')}}" />

    <link rel="stylesheet" href="{{ asset('easyadmin/theme/'.config('idev.theme','default').'/css/style.css')}}" id="main-style-link" />
    <link rel="stylesheet" href="{{ asset('easyadmin/theme/'.config('idev.theme','default').'/css/style-preset.css')}}" id="preset-style-link" />
    <link href="{{ asset('easyadmin/idev/styles.css')}}" rel="stylesheet" />
    @stack("festyles")
</head>


<body>

    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>

    @yield("contentfrontend")

    <script src="{{ asset('easyadmin/theme/default/js/plugins/jquery-3.6.3.min.js')}}"></script>
    <script src="{{ asset('easyadmin/theme/default/js/plugins/popper.min.js')}}"></script>
    <script src="{{ asset('easyadmin/theme/default/js/plugins/simplebar.min.js')}}"></script>
    <script src="{{ asset('easyadmin/theme/default/js/plugins/bootstrap.min.js')}}"></script>
    <script src="{{ asset('easyadmin/theme/default/js/config.js')}}"></script>
    <script src="{{ asset('easyadmin/theme/default/js/pcoded.js')}}"></script>
    <script src="{{ asset('easyadmin/idev/scripts.js')}}"></script>
    @stack("fescripts")
</body>

</html>