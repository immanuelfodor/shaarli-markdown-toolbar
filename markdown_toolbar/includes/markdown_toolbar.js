(function ($) {
    $(document).ready(function() {
        $("#lf_description").markdown({
            autofocus: true,
            iconlibrary: "fa", 
            resize: "vertical",
            disabledButtons: ["cmdPreview"],
            language: "en",
            footer: '<small id="desc-counter" class="text-muted">Chars: 0</small>',
            onChange: function(e){
                var content = e.parseContent(),
                    content_length = (content.match(/\n/g)||[]).length + content.length;
                
                $('#desc-counter').html('Chars: ' + content_length);
            }
        });
    });
})(jQuery.noConflict(true));