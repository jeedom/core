/***************Fonction d'initialisation*********************/
$(function () {
    MESSAGE_NUMBER = null;
    nbActiveAjaxRequest = 0;

    $.mobile.orientationChangeEnabled = false;

    $(window).on("orientationchange", function (event) {
        deviceInfo = getDeviceType();
    });

    initApplication();

    $('body').delegate('.link', 'click', function () {
        modal(false);
        panel(false);
        page($(this).attr('data-page'), $(this).attr('data-title'), $(this).attr('data-option'), $(this).attr('data-plugin'));
    });

    var webappCache = window.applicationCache;
    webappCache.addEventListener('cached', updateCacheEvent, false);
    webappCache.addEventListener('checking', updateCacheEvent, false);
    webappCache.addEventListener('downloading', updateCacheEvent, false);
    webappCache.addEventListener('error', updateCacheEvent, false);
    webappCache.addEventListener('noupdate', updateCacheEvent, false);
    webappCache.addEventListener('obsolete', updateCacheEvent, false);
    webappCache.addEventListener('progress', updateCacheEvent, false);
    webappCache.addEventListener('updateready', updateCacheEvent, false);
    try{
        webappCache.update();
    }catch (e) {

    }

    function updateCacheEvent(e) {
        if (webappCache.status == 3) {
            $('#div_updateInProgress').show();
        } else if (e.type == 'updateready') {
            webappCache.swapCache();
            window.location.reload();
        }
    }

    $(document).ajaxStart(function () {
        nbActiveAjaxRequest++;
        $.showLoading();
    });
    $(document).ajaxStop(function () {
        nbActiveAjaxRequest--;
        if (nbActiveAjaxRequest <= 0) {
            nbActiveAjaxRequest = 0;
            $.hideLoading();
        }
    });
});

function isset() {
    var a = arguments, b = a.length, d = 0;
    if (0 === b)
        throw Error("Empty isset");
    for (; d !== b; ) {
        if (void 0 === a[d] || null === a[d])
            return!1;
        d++
    }
    return!0
}

function initApplication(_reinit) {
    $.ajax({// fonction permettant de faire de l'ajax
        type: 'POST', // methode de transmission des données au fichier php
        url: 'core/ajax/jeedom.ajax.php', // url du fichier php
        data: {
            action: 'getInfoApplication'
        },
        dataType: 'json',
        error: function (request, status, error) {
            if (confirm('Erreur de communication.Etes-vous connecté à internet? Voulez-vous ressayer ?')) {
                window.location.reload();
            }
        },
        success: function (data) { // si l'appel a bien fonctionné
        if (data.state != 'ok') {
            modal(false);
            panel(false);
            if (data.code == -1234) {
                page('connection', 'Connexion');
                return;
            } else {
                $('#div_alert').showAlert({message: data.result, level: 'danger'});
            }
            return;
        } else {
            if (init(_reinit, false) == false) {
                modal(false);
                panel(false);
                /*************Initialisation environement********************/
                nodeJsKey = data.result.nodeJsKey;
                user_id = data.result.user_id;
                plugins = data.result.plugins;
                userProfils = data.result.userProfils;
                var include = ['core/js/core.js'];

                if (isset(userProfils) && userProfils != null) {
                    if (isset(userProfils.mobile_theme_color) && userProfils.mobile_theme_color != '') {
                        $('#jQMnDColor').attr('href', '3rdparty/jquery.mobile/css/jquery.mobile.nativedroid.color.' + userProfils.mobile_theme_color + '.css');
                    }
                    if (isset(userProfils.mobile_theme) && userProfils.mobile_theme != '') {
                        $('#jQMnDTheme').attr('href', '3rdparty/jquery.mobile/css/jquery.mobile.nativedroid.' + userProfils.mobile_theme + '.css');
                    }
                    if (isset(userProfils.mobile_highcharts_theme) && userProfils.mobile_highcharts_theme != '') {
                        include.push('3rdparty/highstock/themes/' + userProfils.mobile_highcharts_theme + '.js');
                    }
                }
                if (isset(data.result.custom) && data.result.custom != null) {
                    if (isset(data.result.custom.css) && data.result.custom.css) {
                        include.push('mobile/custom/custom.css');
                    }
                    if (isset(data.result.custom.js) && data.result.custom.js) {
                        include.push('mobile/custom/custom.js');
                    }
                }
                $.get("core/php/icon.inc.php", function (data) {
                    $("head").append(data);
                    $.include(include, function () {
                        deviceInfo = getDeviceType();
                        if (isset(userProfils) && userProfils != null && isset(userProfils.homePageMobile) && userProfils.homePageMobile != 'home' && getUrlVars('page') != 'home') {
                            var res = userProfils.homePageMobile.split("::");
                            if (res[0] == 'core') {
                                switch (res[1]) {
                                    case 'dashboard' :
                                    page('equipment', 'Objet', userProfils.defaultMobileObject);
                                    break;
                                    case 'plan' :
                                    window.location.href = 'index.php?v=d&p=plan&plan_id=' + userProfils.defaultMobilePlan;
                                    break;
                                    case 'view' :
                                    page('view', 'Vue', userProfils.defaultMobileView);
                                    break;
                                }
                            } else {
                                page(res[1], 'Plugin', '', res[0]);
                            }
                        } else {
                            page('home', 'Accueil');
                        }
                    });
});
}
}
}
});
}

function page(_page, _title, _option, _plugin) {
    $.showLoading();
    $('.ui-popup').popup('close');
    $('#page').empty();
    if (isset(_title)) {
        $('#pageTitle').empty().append(_title);
    }
    if (_page == 'connection') {
        var page = 'index.php?v=m&p=' + _page;
        $('#page').load(page, function () {
            $('#page').trigger('create');
        });
        return;
    }

    jeedom.user.isConnect({
        success: function (result) {
            if (!result) {
                initApplication(true);
                return;
            }
            var page = 'index.php?v=m&p=' + _page;
            if (init(_plugin) != '') {
                page += '&m=' + _plugin;
            }
            $('#page').load(page, function () {
                $('#page').trigger('create');
                var functionName = '';
                if (init(_plugin) != '') {
                    functionName = 'init' + _plugin.charAt(0).toUpperCase() + _plugin.substring(1).toLowerCase() + _page.charAt(0).toUpperCase() + _page.substring(1).toLowerCase();
                } else {
                    functionName = 'init' + _page.charAt(0).toUpperCase() + _page.substring(1).toLowerCase();
                }
                if ('function' == typeof (window[functionName])) {
                    if (init(_option) != '') {
                        window[functionName](_option);
                    } else {
                        window[functionName]();
                    }
                }
            });
        }
    });
}

function modal(_name) {
    if (_name === false) {
        $('#div_popup').empty();
        $("#div_popup").popup("close");
        $("[data-role=popup]").popup("close");
    } else {
        $('#div_popup').empty();
        $('#div_popup').load(_name, function () {
            $('#div_popup').trigger('create');
            $("#div_popup").popup("open");
        });
    }
}

function panel(_content) {
    if (_content === false) {
        $('#panel_right').empty().trigger('create');
        $('#bt_panel_right').hide();
        $('#panel_right').panel('close');
    } else {
        $('#panel_right').empty().append(_content).trigger('create');
        $('#bt_panel_right').show();
    }
}

function refreshMessageNumber() {
    if (MESSAGE_NUMBER !== null) {
        $('.span_nbMessage').html(MESSAGE_NUMBER);
    } else {
        jeedom.message.number({
            success: function (_number) {
                MESSAGE_NUMBER = _number;
                $('.span_nbMessage').html(_number);
            }
        });
    }
}

function notify(_title, _text) {
    if (_title == '' && _text == '') {
        return true;
    }
    $('#div_alert').html("<center><b>" + _title + "</b></center>" + _text).popup("open", {y: 0});
    setTimeout(function () {
        $('#div_alert').popup("close");
    }, 1000)
}

function setTileSize(_filter) {
    $(_filter).each(function () {
        if (!$(this).hasClass('noResize')) {
            $(this).width(deviceInfo.bSize);
        }
    });
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

function positionEqLogic(_id, _noResize, _class) {
    $('.' + init(_class, 'eqLogic-widget') + ':not(.noResize)').each(function () {
        if (init(_id, '') == '' || $(this).attr('data-eqLogic_id') == _id) {
            var eqLogic = $(this);
            var maxHeight = 0;
            eqLogic.find('.cmd-widget').each(function () {
                if ($(this).height() > maxHeight) {
                    maxHeight = $(this).height();
                }
                var statistiques = $(this).find('.statistiques');
                if (statistiques != undefined) {
                    var left = ($(this).width() - statistiques.width()) / 2;
                    statistiques.css('left', left);
                }
            });
            if (!init(_noResize, false)) {
                //eqLogic.find('.cmd-widget').height(maxHeight);
                var hMarge = (Math.ceil(eqLogic.height() / eqLogic_height_step) - 1) * 6;
                var wMarge = (Math.ceil(eqLogic.width() / eqLogic_width_step) - 1) * 6;
                eqLogic.height((Math.ceil(eqLogic.height() / eqLogic_height_step) * eqLogic_height_step) - 6 + hMarge);
                eqLogic.width((Math.ceil(eqLogic.width() / eqLogic_width_step) * eqLogic_width_step) - 6 + wMarge);
            }

            var verticalAlign = eqLogic.find('.verticalAlign');
            if (count(verticalAlign) > 0 && verticalAlign != undefined) {
                verticalAlign.css('position', 'relative');
                verticalAlign.css('top', ((eqLogic.height() - verticalAlign.height()) / 2) - 20);
                verticalAlign.css('left', (eqLogic.width() - verticalAlign.width()) / 2);
            }
        }
    });
}