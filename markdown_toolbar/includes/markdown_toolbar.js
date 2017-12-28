(function ($) {
    $(document).ready(function() {
        console.log($.fn.jquery); // TODO: remove
        $("#lf_description").markdown({iconlibrary:"fa"});
    });
})(jQuery.noConflict(true));