(function ($) {

    var getContentLength = function(e) {
            var content = e.parseContent(),
                content_length = (content.match(/\n/g)||[]).length + content.length;
            
            return content_length;
        },
        displayContentLength = function(e) {
            var content_length = getContentLength(e);
            $("#desc-counter").html("Chars: " + content_length);
        };

    $(document).ready(function() {
        $("#lf_description").markdown({
            autofocus: true,
            iconlibrary: "fa", 
            resize: "vertical",
            disabledButtons: ["cmdPreview"],
            hiddenButtons: ["cmdPreview"],
            language: "en",
            footer: '<small id="desc-counter" class="text-muted">Chars: 0</small>',
            onShow: function(e) {
                $(".md-editor").css("box-shadow", "none").css("-webkit-box-shadow", "none");
                $("#lf_description").css("padding", "8px");
            },
            onFocus: function(e) {
                displayContentLength(e);
            },
            onChange: function(e){
                displayContentLength(e);
            }
        });
    });

})(jQuery.noConflict(true));