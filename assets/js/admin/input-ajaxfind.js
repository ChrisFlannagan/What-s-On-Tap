(function ($) {
    /* if no tabs components on page then return */
    var $ajaxfinder = $("[data-js-input-ajaxfinder]");
    if (!$ajaxfinder.length) return;

    $ajaxfinder.keydown(function(e) {
        if(e.which === 13) {
            e.preventDefault();
        }
    });

    $ajaxfinder.keyup(function(e) {
        e.preventDefault();
        e.stopPropagation();
        var _inp = String.fromCharCode(e.keyCode);
        var _finder = $(e.target);

        if(_finder.val().length > 1 && /[a-zA-Z0-9-_ ]/.test(_inp)) {
            var _data = $(_finder).data("find");
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
                    var _res = JSON.parse(response);
                    var _list = '<div class="' + form_handler.js_namespace + '" data-js-list-suggest><ul class="list-suggest">';
                    for(var i=0;i<_res.length;i++) {
                        var _i = _res[i];
                        var _class = 'result';
                        if (i == 0) {
                            _class += ' -selected';
                        }
                        _list += '<li data-cnt="' + i + '" onclick="addSuggested(this);" class="' + _class + '" data-id="' + _i.id + '" data-js-suggest-result> ' +
                            _i.title + ' <span class="meta">' + _i.meta + '</span> <span class="remove" onclick="remSuggested(this);">Remove</span></li>';
                    }
                    _list += '</ul></div>';

                    // If the list exists, remove it and add a new one
                    var _view = $("[data-js-list-suggest]");
                    if(_view.length) {
                        _view.remove();
                    }
                    $("body").append(_list);
                    _view = $("[data-js-list-suggest]");

                    var _pos = _finder.offset();
                    _view.css({
                        "left": _pos.left + "px",
                        "top": _pos.top + _finder.outerHeight() + "px",
                        "width": _finder.outerWidth() + "px",
                        "position": "absolute"});
                }
            });
        }

        var _view = $("[data-js-list-suggest]");
        {
            var _cur = _view.find(".-selected");
            var _all = _view.find("[data-js-suggest-result]");
            var _cnt = _all.length;
            var _curcnt = Number(_cur.data("cnt"));
            if (e.which == 38 && _curcnt != 0) { // up key pressed
                _cur.removeClass("-selected");
                _view.find(".result[data-cnt='" + (Number(_curcnt-1)) + "']").addClass("-selected");
            } else if (e.which == 40 && _curcnt < (_cnt-1)) { // down key pressed
                _cur.removeClass("-selected");
                console.log(Number(_curcnt+1));
                _view.find(".result[data-cnt='" + (Number(_curcnt+1)) + "']").addClass("-selected");
            } else if (e.which == 13) { // enter key pressed
                addSuggested(_cur);
                return false;
            }
        }
    });
})(jQuery);

function addSuggested(li) {
    jQuery('[data-js-list-sortable]').append(li);
    jQuery('[data-js-list-suggest]').remove();
    jQuery('[data-js-input-ajaxfinder]').val('');
    jQuery('[data-js-input-found]').val(jQuery('[data-js-input-found]').val() + jQuery(li).data("id") + ';');
    jQuery('[data-js-suggest-result]').removeClass("result");
    jQuery('[data-js-suggest-result]').removeClass("-selected");
    jQuery('[data-js-suggest-result]').addClass("menuitem");
    jQuery('[data-js-suggest-result]').attr('onclick','');
}

function remSuggested(btn) {
    var update = '';
    jQuery(btn).parent().remove();
    jQuery('[data-js-input-found]').val('');
    jQuery('[data-js-list-sortable] li').each(function() {
        update += jQuery(this).data("id") + ";";
    });
    jQuery('[data-js-input-found]').val(update);
}