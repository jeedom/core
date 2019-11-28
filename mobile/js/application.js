/***************Fonction d'initialisation*********************/
var PAGE_HISTORY = [];
var PANEL_SCROLL= 0
$(document).ajaxStart(function () {
  nbActiveAjaxRequest++
  $.showLoading()
});

$(document).ajaxStop(function () {
  nbActiveAjaxRequest--
  if (nbActiveAjaxRequest <= 0) {
    nbActiveAjaxRequest = 0
    $.hideLoading()
  }
})

if ('serviceWorker' in navigator) {
  window.addEventListener('load', function() {
    navigator.serviceWorker.register('sw.js').then(function(registration) {
      console.log('ServiceWorker registration successful with scope: ', registration.scope)
    }, function(err) {
      console.log('ServiceWorker registration failed: ', err)
    })
  })
}

$(function() {
  MESSAGE_NUMBER = null
  BACKGROUND_IMG = ''
  nbActiveAjaxRequest = 0
  utid = Date.now();
  
  $(window).on('orientationchange', function(event) {
    //wait to get new width:
    window.setTimeout(function() {
      $('body').trigger('orientationChanged', [event.orientation])
    }, 200)
  })
  
  initApplication()
  
  $('body').delegate('.link', 'click', function () {
    modal(false)
    panel(false)
    page($(this).attr('data-page'), $(this).attr('data-title'), $(this).attr('data-option'), $(this).attr('data-plugin'))
  });
  
  $('body').on('click','.objectSummaryParent',function() {
    modal(false)
    panel(false)
    page('equipment', '{{Résumé}}', $(this).data('object_id')+':'+$(this).data('summary'))
  })
  
  $('body').on('click','.cmd[data-type=info],.cmd .history[data-type=info]',function(event) {
    var mainOpt = $('#bottompanel_mainoption')
    mainOpt.empty()
    mainOpt.append('<a class="link ui-bottom-sheet-link ui-btn ui-btn-inline waves-effect waves-button" data-page="history" data-title="{{Historique}}" data-option="'+$(this).data('cmd_id')+'"><i class="fas fa-chart-bar"></i> {{Historique}}</a>')
    mainOpt.append('<a class="ui-bottom-sheet-link ui-btn ui-btn-inline waves-effect waves-button" id="bt_warnmeCmd" data-cmd_id="'+$(this).data('cmd_id')+'"><i class="fas fa-bell"></i> {{Préviens moi}}</a>')
    
    mainOpt.panel('open')
    $(document).scrollTop(PANEL_SCROLL)
    
  });
  
  $('body').on('click','#bt_warnmeCmd',function() {
    page('warnme','{{Me prévenir si}}',{cmd_id : $(this).data('cmd_id')},null,true)
  });
  
  $('body').on('click','#bt_switchTheme',function() {
    $('body').attr('data-theme',userProfils.mobile_theme_color_night)
    var theme = 'core/themes/'+userProfils.mobile_theme_color_night+'/mobile/' + userProfils.mobile_theme_color_night + '.css'
    if ($('#jQMnDColor').attr('href') == theme) {
      $('body').attr('data-theme',userProfils.mobile_theme_color)
      theme = 'core/themes/'+userProfils.mobile_theme_color+'/mobile/' + userProfils.mobile_theme_color + '.css'
    }
    $('#jQMnDColor').attr('href', theme).attr('data-nochange',1)
    setBackgroundImage(BACKGROUND_IMG)
    triggerThemechange()
  });
  
  var webappCache = window.applicationCache
  
  function updateCacheEvent(e) {
    if (webappCache.status == 3) {
      $('#div_updateInProgress').html('<p>Mise à jour de l\'application en cours<br/><span id="span_updateAdvancement">0</span>%</p>')
      $('#div_updateInProgress').show()
    } else if (e.type == 'updateready') {
      window.location.reload()
    }
    if (e.type == 'progress') {
      var progress = Math.round((e.loaded/e.total)*100 * 100) / 100
      $('#span_updateAdvancement').text(progress)
    }
    if (e.type == 'error') {
      $('#div_updateInProgress').html('<p>{{Erreur lors de la mise à jour}}<br/>{{Nouvelle tentative dans 5s}}</p>')
      setTimeout(function(){ webappCache.update(); }, 5000)
    }
  }
  if (webappCache != undefined) {
    webappCache.addEventListener('cached', updateCacheEvent, false)
    webappCache.addEventListener('checking', updateCacheEvent, false)
    webappCache.addEventListener('downloading', updateCacheEvent, false)
    webappCache.addEventListener('error', updateCacheEvent, false)
    webappCache.addEventListener('noupdate', updateCacheEvent, false)
    webappCache.addEventListener('obsolete', updateCacheEvent, false)
    webappCache.addEventListener('progress', updateCacheEvent, false)
    webappCache.addEventListener('updateready', updateCacheEvent, false)
    try {
      webappCache.update()
    } catch(e) {
      
    }
  }
})

function setBackgroundImage(_path) {
  if(typeof jeedom.theme == 'undefined' || typeof jeedom.theme.showBackgroundImg  == 'undefined' || jeedom.theme.showBackgroundImg == 0) {
    return
  }
  $backForJeedom = $('.backgroundforJeedom')
  $backForJeedom.css({
    'background-image':'',
    'background-position':'',
    'background-repeat':'no-repeat'
  })
  BACKGROUND_IMG = _path
  if (_path === null) {
    document.body.style.setProperty('--dashBkg-url','url("")')
    $backForJeedom.css('background-image','url("") !important')
  } else if (_path === '') {
    var mode = 'light'
    if ($('body').attr('data-theme') == 'core2019_Dark') {
      mode = 'dark'
    }
    _path = 'core/img/background/jeedom_abstract_01_'+mode+'.jpg'
    if (['administration','profils'].indexOf($('body').attr('data-page')) != -1) {
      _path = 'core/img/background/jeedom_abstract_03_'+mode+'.jpg'
    }
    if (['display','eqAnalyse','log','history','report','health'].indexOf($('body').attr('data-page')) != -1) {
      _path = 'core/img/background/jeedom_abstract_02_'+mode+'.jpg'
    }
    $backForJeedom.css('background-image','url("'+_path+'") !important')
    document.body.style.setProperty('--dashBkg-url','url("../../../../'+_path+'")')
  }else{
    $backForJeedom.css('background-image','url("'+_path+'") !important')
    document.body.style.setProperty('--dashBkg-url','url("../../../../'+_path+'")')
  }
}

function triggerThemechange() {
  //set jeedom logo:
  var currentTheme = $('body').attr('data-theme')
  if (currentTheme.endsWith('Dark')) {
    $('#homeLogoImg').attr('src', jeedom.theme.logo_mobile_dark)
  } else {
    $('#homeLogoImg').attr('src', jeedom.theme.logo_mobile_light)
  }
  //trigger event for widgets:
  if ( $('body').attr('data-page') && ['equipment', 'view'].includes($('body').attr('data-page')) ) {
    if (currentTheme.endsWith('Light')) {
      $('body').trigger('changeThemeEvent', ['Light'])
    } else {
      $('body').trigger('changeThemeEvent', ['Dark'])
    }
  }
}

function changeThemeAuto(_ambiantLight){
  if (typeof jeedom.theme == 'undefined') {
    return
  }
  if (typeof jeedom.theme.mobile_theme_color_night == 'undefined' || typeof jeedom.theme.mobile_theme_color == 'undefined') {
    return
  }
  if (jeedom.theme.mobile_theme_color == jeedom.theme.mobile_theme_color_night) {
    return
  }
  if (jeedom.theme.mobile_theme_useAmbientLight == "1" && 'AmbientLightSensor' in window) {
    const sensor = new AmbientLightSensor()
    sensor.onreading = () => {
      if ($('#jQMnDColor').attr('data-nochange') == 1) {
        return
      }
      if (sensor.illuminance < 200 && sensor.illuminance > 50) {
        return
      }
      var theme = jeedom.theme.mobile_theme_color
      var themeCss = 'core/themes/'+jeedom.theme.mobile_theme_color+'/mobile/' + jeedom.theme.mobile_theme_color + '.css'
      if (sensor.illuminance < 50) {
        theme = jeedom.theme.mobile_theme_color_night
        themeCss = 'core/themes/'+jeedom.theme.mobile_theme_color_night+'/mobile/' + jeedom.theme.mobile_theme_color_night + '.css'
      }
      if ($('#jQMnDColor').attr('href') != themeCss) {
        setTimeout(function() {
          if (sensor.illuminance < 100 && sensor.illuminance > 50) {
            return
          }
          var theme = jeedom.theme.mobile_theme_color
          var themeCss = 'core/themes/'+jeedom.theme.mobile_theme_color+'/mobile/' + jeedom.theme.mobile_theme_color + '.css'
          if (sensor.illuminance < 50) {
            theme = jeedom.theme.mobile_theme_color_night
            themeCss = 'core/themes/'+jeedom.theme.mobile_theme_color_night+'/mobile/' + jeedom.theme.mobile_theme_color_night + '.css'
          }
          if ($('#jQMnDColor').attr('href') != themeCss) {
            $('body').attr('data-theme',theme)
            $('#jQMnDColor').attr('href', themeCss)
            setBackgroundImage(BACKGROUND_IMG)
            triggerThemechange()
          }
        }, 500)
      }
    }
    sensor.start()
  } else if (jeedom.theme.theme_changeAccordingTime == "1") {
    setInterval(function () {
      if ($('#jQMnDColor').attr('data-nochange') == 1) {
        return
      }
      var theme = jeedom.theme.mobile_theme_color_night
      var themeCss = 'core/themes/'+jeedom.theme.mobile_theme_color_night+'/mobile/' + jeedom.theme.mobile_theme_color_night + '.css'
      var currentTime = parseInt((new Date()).getHours()*100+ (new Date()).getMinutes())
      if (parseInt(jeedom.theme.theme_start_day_hour.replace(':','')) <  currentTime && parseInt(jeedom.theme.theme_end_day_hour.replace(':','')) >  currentTime) {
        theme = jeedom.theme.mobile_theme_color
        themeCss = 'core/themes/'+jeedom.theme.mobile_theme_color+'/mobile/' + jeedom.theme.mobile_theme_color + '.css'
      }
      if ($('#jQMnDColor').attr('href') != themeCss) {
        $('body').attr('data-theme',theme)
        $('#jQMnDColor').attr('href', themeCss)
        setBackgroundImage(BACKGROUND_IMG)
        triggerThemechange()
      }
    }, 60000)
  }
}

function insertHeader(rel, href, size = null, media = null) {
  var link = document.createElement('link')
  link.rel = rel
  link.href = href
  if (size != null) {
    link.size = size
  }
  if (media != null) {
    link.media = media
  }
  document.head.appendChild(link)
}

function isset() {
  var a = arguments, b = a.length, d = 0
  if (0 === b)
  throw Error("Empty isset")
  for (; d !== b; ) {
    if (void 0 === a[d] || null === a[d])
    return!1
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
      confirm('Erreur de communication. Etes-vous connecté à Internet ? Voulez-vous réessayer ?')
    },
    success: function (data) {
      jeedom.theme= data.result
      insertHeader("apple-touch-icon",jeedom.theme.product_icon, "128x128")
      insertHeader("apple-touch-startup-image",jeedom.theme.product_icon, "256x256")
      insertHeader("apple-touch-icon-precomposed",jeedom.theme.product_icon, "256x256")
      insertHeader("shortcut icon",jeedom.theme.product_icon, "128x128")
      insertHeader("icon",jeedom.theme.product_icon, "128x128")
      insertHeader("apple-touch-startup-image",jeedom.theme.product_icon, null, "(device-width: 320px)")
      insertHeader("apple-touch-startup-image",jeedom.theme.product_icon, null, "(device-width: 320px) and (-webkit-device-pixel-ratio: 2)")
      insertHeader("apple-touch-startup-image",jeedom.theme.product_icon, null, "(device-width: 768px) and (orientation: portrait)")
      insertHeader("apple-touch-startup-image",jeedom.theme.product_icon, null, "(device-width: 768px) and (orientation: landscape)")
      insertHeader("apple-touch-startup-image",jeedom.theme.product_icon, null, "(device-width: 1536px) and (orientation: portrait) and (-webkit-device-pixel-ratio: 2)")
      insertHeader("apple-touch-startup-image",jeedom.theme.product_icon, null, "(device-width: 1536px)  and (orientation: landscape) and (-webkit-device-pixel-ratio: 2)")
      if (data.state != 'ok' || (isset(data.result.connected) && data.result.connected == false)) {
        modal(false)
        panel(false)
        page('connection', 'Connexion')
        return;
      }
      if (init(_reinit, false) == false) {
        document.title = data.result.product_name
        $('#favicon').attr("href", data.result.product_icon)
        $.ajaxSetup({
          type: "POST",
          data: {
            jeedom_token: data.result.jeedom_token
          }
        })
        modal(false)
        panel(false)
        /*************Initialisation environement********************/
        serverDatetime  = data.result.serverDatetime
        var clientDatetime = new Date()
        clientServerDiffDatetime = serverDatetime*1000 - clientDatetime.getTime()
        user_id = data.result.user_id
        plugins = data.result.plugins
        userProfils = data.result.userProfils
        jeedom.init()
        var include = ['core/js/core.js']
        if (typeof jeedom.theme != 'undefined' && typeof jeedom.theme.css != 'undefined' && Object.keys(jeedom.theme.css).length > 0) {
          for(var i in jeedom.theme.css) {
            document.body.style.setProperty(i,jeedom.theme.css[i])
          }
        }
        if (typeof jeedom.theme.mobile_theme_useAmbientLight == undefined) {
          jeedom.theme.mobile_theme_useAmbientLight = "0"
        }
        if (typeof jeedom.theme.theme_changeAccordingTime == undefined) {
          jeedom.theme.theme_changeAccordingTime = "0"
        }
        $('body').attr('data-coloredIcons',0)
        if (typeof jeedom.theme['interface::advance::coloredIcons'] != 'undefined' && jeedom.theme['interface::advance::coloredIcons'] == '1') {
          $('body').attr('data-coloredIcons',1)
        }
        $('body').attr('data-theme',jeedom.theme.current_mobile_theme)
        $('#jQMnDColor').attr('href', 'core/themes/'+jeedom.theme.current_mobile_theme+'/mobile/' + jeedom.theme.current_mobile_theme + '.css')
        changeThemeAuto()
        if (isset(jeedom.theme.current_mobile_theme) && jeedom.theme.current_mobile_theme != '') {
          include.push( 'core/themes/'+jeedom.theme.current_mobile_theme+'/mobile/' + jeedom.theme.current_mobile_theme + '.js')
        }
        
        var widget_shadow = true
        var useAdvance = 0
        if (typeof jeedom.theme != 'undefined') {
          if (typeof jeedom.theme['interface::advance::enable'] != 'undefined') {
            useAdvance = parseInt(jeedom.theme['interface::advance::enable'])
          }
          if (typeof jeedom.theme['widget::shadow'] != 'undefined' && useAdvance == 1 && jeedom.theme['widget::shadow'] == '1') {
            widget_shadow = false
          }
        }
        if (widget_shadow) {
          themePath = 'core/themes/' + jeedom.theme['mobile_theme_color'] + '/mobile'
          include.push( 'core/themes/'+jeedom.theme.current_mobile_theme+'/mobile/' + 'shadows.css')
        }
        if (isset(data.result.custom) && data.result.custom != null) {
          if (isset(data.result.custom.css) && data.result.custom.css) {
            include.push('mobile/custom/custom.css')
          }
          if (isset(data.result.custom.js) && data.result.custom.js) {
            include.push('mobile/custom/custom.js')
          }
        }
        triggerThemechange()
        for(var i in plugins){
          if (plugins[i].eventjs == 1) {
            include.push('plugins/'+plugins[i].id+'/mobile/js/event.js')
          }
        }
        
        $.get("core/php/icon.inc.php", function (data) {
          $("head").append(data)
          $.include(include, function () {
            deviceInfo = getDeviceType()
            jeedom.object.summaryUpdate([{object_id:'global'}])
            if (getUrlVars('p') == 'view') {
              page('view', 'Vue',getUrlVars('view_id'));
            } else if (isset(userProfils) && userProfils != null && isset(userProfils.homePageMobile) && userProfils.homePageMobile != 'home' && getUrlVars('p') != 'home') {
              var res = userProfils.homePageMobile.split("::")
              if (res[0] == 'core') {
                switch (res[1]) {
                  case 'dashboard' :
                  page('equipment', userProfils.defaultMobileObjectName, userProfils.defaultMobileObject)
                  break
                  case 'plan' :
                  window.location.href = 'index.php?v=d&p=plan&plan_id=' + userProfils.defaultMobilePlan
                  break
                  case 'view' :
                  page('view', userProfils.defaultMobileViewName, userProfils.defaultMobileView)
                  break
                }
              } else {
                page(res[1], 'Plugin', '', res[0])
              }
            } else {
              page('home', 'Accueil')
            }
            $('#pagecontainer').css('padding-top','64px')
          })
        })
      }
    }
  })
}

$(window).on("navigate", function (event, data) {
  if($('.ui-popup-container:not(.ui-popup-hidden)').length > 0){
    return;
  }
  event.preventDefault();
  event.stopPropagation();
  var direction = data.state.direction;
  PAGE_HISTORY.pop()
  var history_page = PAGE_HISTORY.pop();
  if(!history_page || !history_page.page){
    return;
  }
  if(!history_page.option){
    page(history_page.page,history_page.title);
  }else if(!history_page.plugin){
    page(history_page.page,history_page.title,history_page.option);
  }else {
    page(history_page.page,history_page.title,history_page.option,history_page.plugin);
  }
  if(history_page.scroll){
    setTimeout(function(){$(document).scrollTop(history_page.scroll)}, 1000);
  }
});

function page(_page, _title, _option, _plugin,_dialog) {
  if(PAGE_HISTORY[PAGE_HISTORY.length - 1]){
    PAGE_HISTORY[PAGE_HISTORY.length - 1].scroll = $(document).scrollTop();
  }
  if (!isset(_dialog) || !_dialog) {
    PAGE_HISTORY.push({page : _page,title : _title,option : _option, plugin : _plugin});
  }
  $('#searchContainer').hide()
  setBackgroundImage('')
  $.showLoading()
  try {
    $('#bottompanel').panel('close')
    $('#bottompanel_mainoption').panel('close')
    $('.ui-popup').popup('close')
  } catch (e) {
    
  }
  if (isset(_title)) {
    if (!isset(_dialog) || !_dialog) {
      $('#pageTitle').empty().append(_title)
    } else {
      $('#popupDialog .nd-title').text(_title)
    }
  }
  if (_page == 'connection') {
    var page = 'index.php?v=m&ajax=1&p=' + _page
    $('#page').load(page, function () {
      $('body').attr('data-page', 'connection')
      $('#page').trigger('create')
      $('#pagecontainer').css('padding-top','64px')
      setTimeout(function(){$('#pagecontainer').css('padding-top','64px');}, 100)
    });
    return
  }
  var page = 'index.php?v=m&ajax=1'
  if (isset(_dialog) && _dialog) {
    page += '&modal='+_page
  } else {
    page += '&p=' + _page
  }
  if (init(_plugin) != '') {
    page += '&m=' + _plugin
  }
  if (isset(_dialog) && _dialog) {
    $('#popupDialog .content').load(page, function () {
      var functionName = ''
      if (init(_plugin) != '') {
        functionName = 'init' + _plugin.charAt(0).toUpperCase() + _plugin.substring(1).toLowerCase() + _page.charAt(0).toUpperCase() + _page.substring(1).toLowerCase()
      } else {
        functionName = 'init' + _page.charAt(0).toUpperCase() + _page.substring(1).toLowerCase()
      }
      if ('function' == typeof (window[functionName])) {
        if (init(_option) != '') {
          window[functionName](_option)
        } else {
          window[functionName]()
        }
      }
      Waves.init()
      $("#popupDialog").popup({
        beforeposition: function () {
          $(this).css({
            width: window.innerWidth - 40,
          })
        },
        x: 5,
        y: 70
      })
      $('#popupDialog').trigger('create').popup('open')
    })
  } else {
    $('#page').hide().load(page, function () {
      $('body').attr('data-page', _page)
      $('#page').trigger('create')
      window.history.pushState('','','index.php?v=m&p=home')
      var functionName = ''
      if (init(_plugin) != '') {
        functionName = 'init' + _plugin.charAt(0).toUpperCase() + _plugin.substring(1).toLowerCase() + _page.charAt(0).toUpperCase() + _page.substring(1).toLowerCase()
      } else {
        functionName = 'init' + _page.charAt(0).toUpperCase() + _page.substring(1).toLowerCase()
      }
      if ('function' == typeof (window[functionName])) {
        if (init(_option) != '') {
          window[functionName](_option)
        } else {
          window[functionName]()
        }
      }
      Waves.init()
      $('#pagecontainer').css('padding-top','64px')
      $('#page').fadeIn(400)
      setTimeout(function(){$('#pagecontainer').css('padding-top','64px'); }, 100)
    })
  }
}

function modal(_name) {
  try{
    if (_name === false) {
      $('#div_popup').empty().popup("close")
      $("[data-role=popup]").popup("close")
    } else {
      $('#div_popup').empty().load(_name, function () {
        $('#div_popup').trigger('create').popup("open")
      });
    }
  } catch(e) {}
}

function panel(_content) {
  try{
    if (_content === false) {
      $('#bottompanel').empty().trigger('create')
      $('#bt_bottompanel').hide()
      $('#bottompanel').panel('close')
    } else {
      $('#bottompanel').empty().append(_content).trigger('create')
      $('#bt_bottompanel').show()
    }
  } catch(e) {}
}

$(document).on('panelbeforeopen', function(event) {
  PANEL_SCROLL = $(document).scrollTop()
  event.stopImmediatePropagation()
})

$(document).on('panelopen', function(event) {
  $(document).scrollTop(PANEL_SCROLL);
})

$(document).on('panelbeforeclose', function(event) {
  PANEL_SCROLL = $(document).scrollTop()
})

$(document).on('panelclose', function(event) {
  $(document).scrollTop(PANEL_SCROLL);
})

function refreshMessageNumber() {
  jeedom.message.number({
    success: function (_number) {
      MESSAGE_NUMBER = _number
      $('.span_nbMessage').html(_number)
    }
  })
}

function notify(_title, _text) {
  new $.nd2Toast({
    message :  _title+'. '+_text,
    ttl : 3000
  })
}

function setTileSize(_filter) {
  if (typeof jeedom.theme['widget::margin'] == 'undefined') {
    jeedom.theme['widget::margin'] = 4
  }
  var bsize = deviceInfo.bSize
  $(_filter).each(function () {
    $(this).css({'margin':'0px', 'padding':'0px'})
    if ($(this).hasClass('col2')) {
      $(this).width(bsize * 2)
    } else {
      $(this).width(bsize - jeedom.theme['widget::margin'])
    }
    $(this).css('margin',jeedom.theme['widget::margin']+'px')
  });
}

function init(_value, _default) {
  if (!isset(_default)) {
    _default = ''
  }
  if (!isset(_value)) {
    return _default
  }
  return _value
}

function normTextLower(_text) {
  return _text.normalize('NFD').replace(/[\u0300-\u036f]/g, "").toLowerCase()
}

function refreshUpdateNumber() {}
function positionEqLogic(_id) {}
