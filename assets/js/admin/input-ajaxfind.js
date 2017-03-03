(function ($) {
    /* if no tabs components on page then return */
    var $ajaxfinder = $("[data-js-input-ajaxfinder]");
    if (!$ajaxfinder.length) return;

    $ajaxfinder.keyup(function(e) {
        var _finder = $(e.target);
        if(_finder.val().length > 3) {
            var _data = $(_finder).data("find");
            console.log("Data: " + _data[0]);
            $.ajax({
                url: form_handler.ajax_url,
                type: "post",
                data: {
                    action: "input_ajaxfinder",
                    ajax_finder: true,
                    nonce: _data[0],
                    find: _data[1],
                    field: _data[2],
                    meta: _data[3],
                    val: _finder.val()
                },
                success: function (response) {

                }
            });
        }
    });
})(jQuery);