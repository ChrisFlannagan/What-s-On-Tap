(function ($) {
    $(document).ready(function() {
        /* if no tabs components on page then return */
        var $sortable = $("[data-js-list-sortable]");
        if (!$sortable.length) return;
        
        $sortable.sortable({
            update: function( e, ui ) {
                $('[data-js-input-found]').val("");
                $('[data-js-suggest-result]').each(function() {
                    $('[data-js-input-found]').val($('[data-js-input-found]').val() + $(this).data("id") + ';');
                });
            }
        });
    });
})(jQuery);