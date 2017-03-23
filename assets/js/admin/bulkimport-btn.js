(function ($) {
    var $bulkimport;
    $(document).ready(function() {
        $bulkimport = $("[data-js-bulkimport-btn]");
        var $bulkimport_text = $("[data-js-bulkimport-textarea]");
        if (!$bulkimport.length || !$bulkimport_text.length) return;

        $bulkimport.click(function(e) {
            var _list = $bulkimport_text.val().split("\n");
            var _html = '';
            for(var i=0;i<_list.length;i++) {
                _html += '<p class="importitem"><input type="text" id="newtap' + i + '" value="' + _list[i] + '" /> ';
                _html += '<input type="text" id="newtap_brewery' + i + '" placeholder="Brewery" /> ';
                _html += '<input type="text" size="3" id="newtap_abv' + i + '" placeholder="ABV" />% ';
                _html += '<input type="button" value="Save" onclick="saveBulkImported(' + i + ', \'' + $bulkimport.data("nonce") + '\');" /></p>';
            }
            _html += '<p class="saveall"><input type="button" value="Save All" onclick="saveBulkImported(0);" /></p>';
            $bulkimport_text.replaceWith($(_html));
        });
    });
})(jQuery);

function saveBulkImported(i, nonce) {
    (function($) {
        $.ajax({
            url: form_handler.ajax_url,
            type: "post",
            data: {
                action: "input_bulkimport",
                ajax_bulkimport: true,
                nonce: nonce,
                tap: $("#newtap" + i).val(),
                brewery: $("#newtap_brewery" + i).val(),
                abv: $("#newtap_abv" + i).val()
            },
            success: function (response) {
                if(response=='0') {
                    console.log("failed");
                } else {
                    var _cnt = 0;
                    var _selected = $("#newtap" + i).closest(".importitem");
                    if($("[data-js-suggest-result]").length) _cnt = $("[data-js-suggest-result]").length;
                    var _tap = '<li data-cnt="' + _cnt + '" onclick="" class="menuitem ui-sortable-handle" data-id="' + response + '" data-js-suggest-result> ' +
                                $("#newtap" + i).val() + ' <span class="meta">' + $("#newtap_brewery" + i).val() + '</span>' +
                                ' <span class="remove" onclick="remSuggested(this);">Remove</span>' +
                                '</li>';
                    $('[data-js-list-sortable]').append(_tap);
                    $('[data-js-input-found]').val($('[data-js-input-found]').val() + response + ';');
                    _selected.remove();
                }
            }
        });
    })(jQuery);
}