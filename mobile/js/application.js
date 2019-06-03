/***************Fonction d'initialisation*********************/

var JEEDOM_DATA;

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

$(function () {
  MESSAGE_NUMBER = null;
  nbActiveAjaxRequest = 0;
  utid = Date.now();
  
  $.mobile.orientationChangeEnabled = false;
  
  $(window).on("resize", function (event) {
    deviceInfo = getDeviceType();
  });
  
  initApplication();
  
  $('body').delegate('.link', 'click', function () {
    modal(false);
    panel(false);
    page($(this).attr('data-page'), $(this).attr('data-title'), $(this).attr('data-option'), $(this).attr('data-plugin'));
  });
  
  
  $('body').on('click','.objectSummaryParent',function(){
    modal(false);
    panel(false);
    page('equipment', '{{Résumé}}', $(this).data('object_id')+':'+$(this).data('summary'));
  });
  
  $('body').on('taphold','.cmd[data-type=info]',function(){
    $('#bottompanel_mainoption').empty();
    $('#bottompanel_mainoption').append('<a class="link ui-bottom-sheet-link ui-btn ui-btn-inline waves-effect waves-button" data-page="history" data-title="{{Historique}}" data-option="'+$(this).data('cmd_id')+'"><i class="fas fa-bar-chart"></i> {{Historique}}</a>');
    $('#bottompanel_mainoption').append('<a class="ui-bottom-sheet-link ui-btn ui-btn-inline waves-effect waves-button" id="bt_warnmeCmd" data-cmd_id="'+$(this).data('cmd_id')+'"><i class="fas fa-bell"></i> {{Préviens moi}}</a>');
    $('#bottompanel_mainoption').panel('open');
  });
  
  $('body').on('click','#bt_warnmeCmd',function(){
    page('warnme','{{Me prévenir si}}',{cmd_id : $(this).data('cmd_id')},null,true);
  });
  
  var webappCache = window.applicationCache;
  
  
  function updateCacheEvent(e) {
    if (webappCache.status == 3) {
      $('#div_updateInProgress').html('<p>Mise à jour de l\'application en cours<br/><span id="span_updateAdvancement">0</span>%</p>');
      $('#div_updateInProgress').show();
    } else if (e.type == 'updateready') {
      webappCache.swapCache();
      window.location.reload();
    }
    if (e.type == 'progress') {
      var progress = Math.round((e.loaded/e.total)*100 * 100) / 100
      $('#span_updateAdvancement').text(progress);
    }
    if (e.type == 'error') {
      $('#div_updateInProgress').html('<p>{{Erreur lors de la mise à jour}}<br/>{{Nouvelle tentative dans 5s}}</p>');
      setTimeout(function(){ webappCache.update(); }, 5000);
    }
  }
  
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
});


function setBackgroundImage(_path){
  if(typeof userProfils == 'undefined' || typeof userProfils.hideBackgroundImg  == 'undefined' || userProfils.hideBackgroundImg == 1){
    return;
  }
  $('.backgroundforJeedom').css('background-image','');
  $('.backgroundforJeedom').css('background-position','');
  $('.backgroundforJeedom').css('background-repeat','no-repeat');
  if(_path != ''){
    $('.backgroundforJeedom').css('background-image','url("'+_path+'") !important');
    document.body.style.setProperty('--dashBkg-url','url("../../../../'+_path+'")');
  }else{
    document.body.style.setProperty('--dashBkg-url','url("")');
    $('.backgroundforJeedom').css('background-image','url("") !important');
  }
}

function insertHeader(rel, href, size = null, media = null){
  var link = document.createElement('link');
  link.rel = rel;
  link.href = href;
  if(size != null){
    link.size = size;
  }
  if(media != null){
    link.media = media;
  }
  document.head.appendChild(link);
}

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
  $.ajax({
    type: 'POST',
    url: 'core/ajax/jeedom.ajax.php',
    data: {
      action: 'getInfoApplication'
    },
    dataType: 'json',
    error: function (request, status, error) {
      confirm('Erreur de communication. Etes-vous connecté à Internet ? Voulez-vous réessayer ?');
    },
    success: function (data) {
      JEEDOM_DATA= data.result;
      insertHeader("apple-touch-icon",JEEDOM_DATA.product_icon, "128x128");
      insertHeader("apple-touch-startup-image",JEEDOM_DATA.product_icon, "256x256");
      insertHeader("apple-touch-icon-precomposed",JEEDOM_DATA.product_icon, "256x256");
      insertHeader("shortcut icon",JEEDOM_DATA.product_icon, "128x128");
      insertHeader("apple-touch-startup-image",JEEDOM_DATA.product_icon, null, "(device-width: 320px)");
      insertHeader("apple-touch-startup-image",JEEDOM_DATA.product_icon, null, "(device-width: 320px) and (-webkit-device-pixel-ratio: 2)");
      insertHeader("apple-touch-startup-image",JEEDOM_DATA.product_icon, null, "(device-width: 768px) and (orientation: portrait)");
      insertHeader("apple-touch-startup-image",JEEDOM_DATA.product_icon, null, "(device-width: 768px) and (orientation: landscape)");
      insertHeader("apple-touch-startup-image",JEEDOM_DATA.product_icon, null, "(device-width: 1536px) and (orientation: portrait) and (-webkit-device-pixel-ratio: 2)");
      insertHeader("apple-touch-startup-image",JEEDOM_DATA.product_icon, null, "(device-width: 1536px)  and (orientation: landscape) and (-webkit-device-pixel-ratio: 2)");
      if (data.state != 'ok' || (isset(data.result.connected) && data.result.connected == false)) {
        modal(false);
        panel(false);
        page('connection', 'Connexion');
        return;
      }
      if (init(_reinit, false) == false) {
        document.title = data.result.product_name;
        $('#favicon').attr("href", data.result.product_icon);
        $.ajaxSetup({
          type: "POST",
          data: {
            jeedom_token: data.result.jeedom_token
          }
        })
        modal(false);
        panel(false);
        /*************Initialisation environement********************/
        serverDatetime  = data.result.serverDatetime ;
        var clientDatetime = new Date();
        clientServerDiffDatetime = serverDatetime*1000 - clientDatetime.getTime();
        user_id = data.result.user_id;
        plugins = data.result.plugins;
        userProfils = data.result.userProfils;
        widget_margin =  data.result.widget_margin;
        jeedom.init();
        var include = ['core/js/core.js'];
        
        if (isset(userProfils) && userProfils != null) {
          if (isset(userProfils.mobile_theme_color) && userProfils.mobile_theme_color != '') {
            $('#jQMnDColor').attr('href', 'core/themes/'+userProfils.mobile_theme_color+'/mobile/' + userProfils.mobile_theme_color + '.css');
            include.push( 'core/themes/'+userProfils.mobile_theme_color+'/mobile/' + userProfils.mobile_theme_color + '.js');
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
          if(plugins[i].eventjs == 1){
            include.push('plugins/'+plugins[i].id+'/mobile/js/event.js');
          }
        }
        
        $.get("core/php/icon.inc.php", function (data) {
          $("head").append(data);
          $.include(include, function () {
            deviceInfo = getDeviceType();
            jeedom.object.summaryUpdate([{object_id:'global'}])
            if(getUrlVars('p') == 'view'){
              page('view', 'Vue',getUrlVars('view_id'));
            }else if (isset(userProfils) && userProfils != null && isset(userProfils.homePageMobile) && userProfils.homePageMobile != 'home' && getUrlVars('p') != 'home') {
              var res = userProfils.homePageMobile.split("::");
              if (res[0] == 'core') {
                switch (res[1]) {
                  case 'dashboard' :
                  page('equipment', userProfils.defaultMobileObjectName, userProfils.defaultMobileObject);
                  break;
                  case 'plan' :
                  window.location.href = 'index.php?v=d&p=plan&plan_id=' + userProfils.defaultMobilePlan;
                  break;
                  case 'view' :
                  page('view', userProfils.defaultMobileViewName, userProfils.defaultMobileView);
                  break;
                }
              } else {
                page(res[1], 'Plugin', '', res[0]);
              }
            } else {
              page('home', 'Accueil');
            }
            $('#pagecontainer').css('padding-top','64px');
          });
        });
      }
    }
  });
}

function page(_page, _title, _option, _plugin,_dialog) {
  setBackgroundImage('');
  $.showLoading();
  try {
    $('#bottompanel').panel('close');
    $('#bottompanel_mainoption').panel('close');
    $('.ui-popup').popup('close');
  } catch (e) {
    
  }
  if (isset(_title)) {
    if(!isset(_dialog) || !_dialog){
      $('#pageTitle').empty().append(_title);
    }else{
      $('#popupDialog .nd-title').text(_title);
    }
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
  var page = 'index.php?v=m&ajax=1'
  if(isset(_dialog) && _dialog){
    page += '&modal='+_page;
  }else{
    page += '&p=' + _page;
  }
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
      $('#popupDialog').trigger('create');
      $('#popupDialog').popup('open');
    });
  }else{
    $('#page').hide().load(page, function () {
      window.history.pushState('','','index.php?v=m&p=' +_page);
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

function modal(_name) {
  try{
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
  } catch (e) {
    
  }
}

function panel(_content) {
  try{
    if (_content === false) {
      $('#bottompanel').empty().trigger('create');
      $('#bt_bottompanel').hide();
      $('#bottompanel').panel('close');
    } else {
      $('#bottompanel').empty().append(_content).trigger('create');
      $('#bt_bottompanel').show();
    }
  } catch (e) {
    
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

function refreshUpdateNumber() {
  
}

function notify(_title, _text) {
  new $.nd2Toast({
    message :  _title+'. '+_text,
    ttl : 3000
  });
}

function setTileSize(_filter) {
  if(typeof widget_margin == 'undefined'){
    widget_margin = 4;
  }
  $(_filter).each(function () {
    $(this).css('margin','0px').css('padding','0px');
    if($(this).hasClass('col2')){
      $(this).width(deviceInfo.bSize * 2);
    }else{
      $(this).width(deviceInfo.bSize-widget_margin);
    }
    $(this).css('margin',widget_margin+'px');
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
