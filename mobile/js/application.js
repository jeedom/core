/***************Fonction d'initialisation*********************/
$(function () {
    MESSAGE_NUMBER = null;
    nbActiveAjaxRequest = 0;
    utid = Date.now();

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
               // window.location.reload();
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
                serverDatetime  = data.result.serverDatetime ;
                user_id = data.result.user_id;
                plugins = data.result.plugins;
                userProfils = data.result.userProfils;
                jeedom.init();
                var include = ['core/js/core.js'];

                if (isset(userProfils) && userProfils != null) {
                    if (isset(userProfils.mobile_theme_color) && userProfils.mobile_theme_color != '') {
                        $('#jQMnDColor').attr('href', '3rdparty/jquery.mobile/css/nativedroid2.color.' + userProfils.mobile_theme_color + '.css');
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
                for(var i in plugins){
                    if(plugins[i].nodejs == 1){
                        include.push('plugins/'+plugins[i].id+'/mobile/js/node.js');
                    }
                }
                $.get("core/php/icon.inc.php", function (data) {
                    $("head").append(data);
                    $.include(include, function () {
                        deviceInfo = getDeviceType();
                        if(getUrlVars('p') != '' && getUrlVars('ajax') != 1){
                           switch (getUrlVars('p')) {
                            case 'view' :
                            page('view', 'Vue',getUrlVars('view_id'));
                            break;
                        }
                    }else{
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
                    }
                    $('#pagecontainer').css('padding-top','64px');
                });
});
}
}
}
});
}

function page(_page, _title, _option, _plugin,_dialog) {
    $.showLoading();
    $('.ui-popup').popup('close');
    if (isset(_title) && (!isset(_dialog) || !_dialog)) {
        $('#pageTitle').empty().append(_title);
    }
    if (_page == 'connection') {
        var page = 'index.php?v=m&ajax=1&p=' + _page;
        $('#page').load(page, function () {
            $('#page').trigger('create');
            $('#pagecontainer').css('padding-top','64px');
            setTimeout(function(){$('#pagecontainer').css('padding-top','64px');; }, 100);
        });
        return;
    }

    jeedom.user.isConnect({
        success: function (result) {
            if (!result) {
                initApplication(true);
                return;
            }
            var page = 'index.php?v=m&ajax=1&p=' + _page;
            if (init(_plugin) != '') {
                page += '&m=' + _plugin;
            }
            if(isset(_dialog) && _dialog){
                $('#popupDialog .content').load(page, function () {
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
                    Waves.init();
                    $("#popupDialog").popup({
                        beforeposition: function () {
                            $(this).css({
                                width: window.innerWidth - 40,
                            });
                        },
                        x: 5,
                        y: 70
                    });
                    $('#popupDialog').popup('open');
                });
}else{
    $('#page').hide().load(page, function () {
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
        Waves.init();
        $('#pagecontainer').css('padding-top','64px');
        $('#page').fadeIn(400);
        setTimeout(function(){$('#pagecontainer').css('padding-top','64px');; }, 100);
    });
}
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
        $('#bottompanel').empty().trigger('create');
        $('#bt_bottompanel').hide();
        $('#bottompanel').panel('close');
    } else {
        $('#bottompanel').empty().append(_content).trigger('create');
        $('#bt_bottompanel').show();
    }
}

function refreshMessageNumber() {
    jeedom.message.number({
        success: function (_number) {
            MESSAGE_NUMBER = _number;
            $('.span_nbMessage').html(_number);
        }
    });
}

function notify(_title, _text) {
    new $.nd2Toast({
        message :  _title+'. '+_text,
        ttl : 3000
    });
}

function setTileSize(_filter) {
    $(_filter).each(function () {
        if (!$(this).hasClass('noResize')) {
            if($(this).hasClass('col2')){
                $(this).width(deviceInfo.bSize * 2 + 4);
            }else{
                $(this).width(deviceInfo.bSize);
            }
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

function positionEqLogic(_id) {}