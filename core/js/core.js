
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
var noBootstrapTooltips = false;
$(function() {
    if (!$.mobile) {
        jeedom.init();
    }
});

if ($.mobile) {
    if (jeedom.nodeJs.state === true) {
        setTimeout(function() {
            $('body').trigger('nodeJsConnect');
        }, 500);
    }
    if (jeedom.nodeJs.state == -1 || socket == null) {
        jeedom.init();
    }
}

function getTemplate(_folder, _version, _filename, _replace) {
    if (_folder == 'core') {
        var path = _folder + '/template/' + _version + '/' + _filename;
    } else {
        var path = 'plugins/' + _folder + '/desktop/template/' + _version + '/' + _filename;
    }
    var template = '';
    $.ajax({// fonction permettant de faire de l'ajax
        type: "POST", // methode de transmission des données au fichier php
        url: path, // url du fichier php
        async: false,
        error: function(request, status, error) {
            handleAjaxError(request, status, error);
        },
        success: function(data) { // si l'appel a bien fonctionné
            if (isset(_replace) && _replace != null) {
                for (i in _replace) {
                    var reg = new RegExp(i, "g");
                    data = data.replace(reg, _replace[i]);
                }
            }
            template = data;
        }
    });
    return template;
}

function handleAjaxError(_request, _status, _error, _div_alert) {
    $.hideLoading();
    var div_alert = init(_div_alert, $('#div_alert'));
    if (_request.status != '0') {
        if (init(_request.responseText, '') != '') {
            div_alert.showAlert({message: _request.responseText, level: 'danger'});
        } else {
            div_alert.showAlert({message: _request.status + ' : ' + _error, level: 'danger'});
        }
    }
}

function init(_value, _default) {
    if (!isset(_default)) {
        _default = '';
    }
    if (!isset(_value)) {
        return _default;
    }
    return _value;
}

function getUrlVars(_key) {
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for (var i = 0; i < hashes.length; i++) {
        hash = hashes[i].split('=');
        vars[hash[0]] = hash[1];
        if (isset(_key) && _key == hash[0]) {
            return hash[1];
        }
    }
    if (isset(_key)) {
        return false;
    }
    return vars;
}

function initTooltips() {
    var noTooltips = noBootstrapTooltips || false;
    if(noTooltips){
        return;
    }
    if ($.mobile) {

    } else {
        $('.tooltips').tooltip({
            animation: true,
            html: true,
            placement: 'bottom'
        });
    }
}



