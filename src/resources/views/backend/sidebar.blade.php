
<div class="navbar-content">
    <ul class="pc-navbar">
        @foreach ((new App\Helpers\Sidebar())->generate() as $key => $menu)
        @if(isset($menu['visibility']) && $menu['visibility'])
        <li class="pc-item sidebar-{{$menu['base_key']}} @if(sizeof($menu['childrens']) > 0) pc-hasmenu @endif">
            <a class="pc-link 
                @if(Route::has($menu['key'])) @if(strpos(URL::current(), route($menu['key'])) !==false ) active @endif @endif
                @if(sizeof($menu['childrens']) > 0)
                sidebar-link
                @endif" @if(sizeof($menu['childrens'])> 0)
                href="#"
                @else
                href="@if(Route::has($menu['key'])) {{route($menu['key'])}} @endif"
                @endif

                @if(isset($menu['ajax_load']) && $menu['ajax_load'] == true)
                onclick="loadPage(event,'{{ $menu['base_key'] }}','@if(Route::has($menu['key'])) {{route($menu['key'])}} @endif')"
                @endif
                >
                <span class="pc-micon"><i class="{{$menu['icon']}}"></i></span>
                <span class="pc-mtext">
                    {{$menu['name']}}
                </span>
                @if(sizeof($menu['childrens']) > 0)
                <span class="pc-arrow">
                    <i class="ti ti-chevron-right"></i>
                </span>
                @endif
            </a>

            @if(sizeof($menu['childrens']) > 0)
            <ul class="pc-submenu">
            @foreach($menu['childrens'] as $key => $cm)
                @if(isset($cm['visibility']) && $cm['visibility'])
                <li class="pc-item">
                    <a href="@if(Route::has($cm['key'])) {{route($cm['key'])}} @endif" class="pc-link">{{$cm['name']}}</a>
                </li>
                @endif
            @endforeach
            </ul>
            @endif

        </li>
        @endif
        @endforeach
    </ul>  
</div>


@push('scripts')
<script>
    var uriKey = ''

    function loadPage(e, uriKey, url) {
        e.preventDefault()
        var url = url.replaceAll(" ", "")

        $(".page-from-ajax").css("filter", 'blur(2px)')
        $(".nav-link").removeClass("active")
        $(".sidebar-" + uriKey+" .nav-link").addClass("active")
        changeUrl(uriKey)
        $.get(url, {
            from_ajax: true
        }, function(response) {
            $(".page-from-ajax").html(response)

            $(".current-title-ajax").text($(".current-title").text())
            $(".current-title").remove()
            $(".page-from-ajax").css("filter", 'blur(0px)')
            updateContent(uriKey)
        });
        uriKey = uriKey
    }

    function updateContent(uriKey) {
        var addHtmlScript = ""
        $('.push-script').each(function(i, obj) {
            addHtmlScript += $(this).html()
        });
        $('.push-script-ajax').html(addHtmlScript);
        $('.push-script').remove();

        var addHtmlStyle = ""
        $('.push-style').each(function(i, obj) {
            addHtmlStyle += $(this).html()
        });
        $('.push-style-ajax').html(addHtmlStyle);
        $('.push-style').remove();

        if ($(".idev-actionbutton").children().length == 0) {
            $("#dropdownMoreTopButton").remove()
            $(".idev-actionbutton").remove()
        }
        idevTable("list-" + uriKey)
        $('form input').on('keypress', function(e) {
            return e.which !== 13;
        });
        $(".search-list-" + uriKey).keyup(delay(function(e) {
            var dInput = this.value;
            if (dInput.length > 3 || dInput.length == 0) {
                $(".current-paginate-" + uriKey).val(1)
                $(".search-list-" + uriKey).val(dInput)
                updateFilter()
            }
        }, 500))
    }

    function changeUrl(url) {
        var new_url = url;
        window.history.pushState("data", "Title", new_url);
        document.title = url;
    }

</script>
@endpush