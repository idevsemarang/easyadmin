<script>
    function setShowPreview(id) {
        var urlPreview = "{{route($uri_key.'.show', 'myid')}}"
        urlPreview = urlPreview.replace("myid", id)
        $(".content-preview").html("Processing...")

        $("#section-list-{{$uri_key}}").hide()
        $("#section-preview-{{$uri_key}}").fadeIn('slow')
        $(".text-subtitle").text("Detail {{$title}}")

        $(".close-preview").click(function() {
            $("#section-preview-{{$uri_key}}").hide()
            $("#section-list-{{$uri_key}}").fadeIn('slow')
            $(".text-subtitle").text("List {{$title}}")
        });
        $.ajax({
            url: urlPreview,
            type: "GET",
            success: function (response) {
                $(".content-preview").html(response)
            }
        });
    }
</script>