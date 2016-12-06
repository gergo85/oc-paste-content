(function($) {
    $.oc.richEditorButtons.splice(0, 0, 'paste');

    $.FroalaEditor.RegisterCommand('paste', {
        title: _translate('paste'),
        type: 'dropdown',
        icon: '<i class="icon-clipboard"></i>',

        html: function() {
            if ($.oc.paste) {
                var html = '<ul class="fr-dropdown-list">';
                var icon, title = '';

                $.each($.oc.paste, function(i, paste) {
                    if (paste.type == 1) {
                        icon = 'file-text';
                    }
                    else {
                        icon = 'file-code-o';
                    }

                    if (paste.name != '') {
                        title = paste.name;
                    }
                    else {
                        title = '{{' + paste.code + '}}';
                    }

                    html += '<li><a class="fr-command" data-cmd="paste" data-param1="' + paste.code + '" title="' + title + '"><i class="icon-' + icon + '"></i> &nbsp;' + title + '</a></li>';
                });

                return html + '</ul>';
            }
            else {
                return '<div style="padding:10px;">' + _translate('no_data') + '</div>';
            }
        },

        undo: true,
        focus: true,
        refreshAfterCallback: true,

        callback: function(cmd, val) {
            var html = '';

            if ($.oc.paste) {
                $.each($.oc.paste, function(i, paste) {
                    if (paste.code == val) {
                        html = '{{' + paste.code + '}}';
                    }
                });
            }

            this.html.insert(html);
        }
    });
})(jQuery);

function _translate(code) {
    var lang = $('html').attr('lang');

    var text_en = {
        paste: 'Paste',
        no_data: 'No pasted contents are defined.'
    };

    var text_hu = {
        paste: 'Beillesztés',
        no_data: 'Nincs beilleszthető tartalom.'
    };

    if (lang == 'hu') {
        return text_hu[code];
    }
    return text_en[code];
}
