(function ($) {

    var getContentLength = function(e) {
            var content = e.parseContent(),
                content_length = (content.match(/\n/g)||[]).length + content.length;
            
            return content_length;
        },
        displayContentLength = function(e) {
            var content_length = getContentLength(e);
            $('#desc-counter').html('Chars: ' + content_length);
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
            onFocus: function(e) {
                displayContentLength(e);
            },
            onChange: function(e){
                displayContentLength(e);
            }
        });
    });

})(jQuery.noConflict(true));