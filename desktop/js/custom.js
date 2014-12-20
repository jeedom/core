
/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

editorDesktopJS = null;
editorDesktopCSS = null;
editorMobileJS = null;
editorMobileCSS = null;

setTimeout(function () {
    editorDesktopJS = CodeMirror.fromTextArea(document.getElementById("ta_jsDesktopContent"), {
        lineNumbers: true,
        mode: "text/javascript",
        matchBrackets: true,
        viewportMargin: Infinity
    });
    editorDesktopCSS = CodeMirror.fromTextArea(document.getElementById("ta_cssDesktopContent"), {
        lineNumbers: true,
        mode: "text/css",
        matchBrackets: true,
        viewportMargin: Infinity
    });
}, 1);

$('a[data-toggle="tab"][href="#mobile"]').on('shown.bs.tab', function (e) {
    if (editorMobileCSS == null) {
        editorMobileCSS = CodeMirror.fromTextArea(document.getElementById("ta_cssMobileContent"), {
            lineNumbers: true,
            mode: "text/css",
            matchBrackets: true,
            viewportMargin: Infinity
        });
    }
    if (editorMobileJS == null) {
        editorMobileJS = CodeMirror.fromTextArea(document.getElementById("ta_jsMobileContent"), {
            lineNumbers: true,
            mode: "text/javascript",
            matchBrackets: true,
            viewportMargin: Infinity
        });
    }
});

$('.saveCustom').on('click', function () {
    var version = $(this).attr('data-version');
    var type = $(this).attr('data-type');
    var content = '';
    var editor = null;
    if (version == 'desktop' && type == 'js') {
        editor = editorDesktopJS;
    }
    if (version == 'desktop' && type == 'csss') {
        editor = editorDesktopCSS;
    }
    if (version == 'mobile' && type == 'js') {
        editor = editorMobileJS;
    }
    if (version == 'mobile' && type == 'css') {
        editor = editorMobileCSS;
    }
    if (editor != null) {
        content = editor.getValue();
    }
    jeedom.saveCustum({
        version: version,
        type: type,
        content: content,
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
            $('#div_alert').showAlert({message: 'Sauvegarde réussie', level: 'success'});
        }
    });
});

