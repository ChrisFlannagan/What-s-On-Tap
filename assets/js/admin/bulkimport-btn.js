(function ($) {
    var $bulkimport = $("[data-js-bulkimport-btn]");
    var $bulkimport_text = $("[data-js-bulkimport-textarea]");
    if (!$bulkimport.length || !$bulkimport_text.length) return;

    $bulkimport.click(function(e) {
        var _list = $bulkimport_text.val().split("\n");
        var _html = '';
        for(var i=0;i<_list.length;i++) {
            _html += '<p><input type="text" name="newtap' + i + '" value="' + _list[i] + '" /> ';
            _html += '<input type="text" name="newtap_brewery' + i + '" placeholder="Brewery" /> ';
            _html += '<input type="text" size="3" name="newtap_abv' + i + '" placeholder="ABV" />% </p>';
        }
        $bulkimport_text.replaceWith($(_html));
    });
})(jQuery);