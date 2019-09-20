$('body').attr('data-page', 'home')
$('#searchContainer').hide()

function initHome() {
  refreshMessageNumber()
  $bottomPanelOtherActions = $('#bottompanel_otherActionList')
  $bottomPanelOtherActions.empty()
  $bottomPanelOtherActions.append('<a class="ui-bottom-sheet-link ui-btn ui-btn-inline waves-effect waves-button" href="index.php?v=d"><i class="fas fa-desktop"></i> {{Version desktop}}</a>')
  $bottomPanelOtherActions.append('<a class="link ui-bottom-sheet-link ui-btn ui-btn-inline waves-effect waves-button" data-page="deamon" data-title="{{Démons}}"><i class="fas fa-bug" ></i> {{Démons}}</a>')
  $bottomPanelOtherActions.append('<a class="link ui-bottom-sheet-link ui-btn ui-btn-inline waves-effect waves-button" data-page="cron" data-title="{{Crons}}"><i class="fas fa-cogs" ></i> {{Crons}}</a>')
  $bottomPanelOtherActions.append('<a class="link ui-bottom-sheet-link ui-btn ui-btn-inline waves-effect waves-button" data-page="health" data-title="{{Santé}}"><i class="icon divers-caduceus3" ></i> {{Santé}}</a>')
  $bottomPanelOtherActions.append('<a class="link ui-bottom-sheet-link ui-btn ui-btn-inline waves-effect waves-button" data-page="eqAnalyse" data-title="{{Analyse équipement}}"><i class="fas fa-battery-full" ></i> {{Analyse équipement}}</a>')
  $bottomPanelOtherActions.append('<a class="link ui-bottom-sheet-link ui-btn ui-btn-inline waves-effect waves-button" data-page="log" data-title="{{Logs}}"><i class="far fa-file" ></i> {{Logs}}</a>')
  if(jeedom.theme.mobile_theme_color != jeedom.theme.mobile_theme_color_night){
    $bottomPanelOtherActions.append('<a id="bt_switchTheme" class="ui-bottom-sheet-link ui-btn ui-btn-inline waves-effect waves-button"><i class="fas fa-sync-alt"></i> {{Basculer le thème}}</a>')
  }
  $bottomPanelOtherActions.append('<a class="link ui-bottom-sheet-link ui-btn ui-btn-inline waves-effect waves-button" data-page="about" data-title="{{A propos}}"><i class="fas fa-info-circle"></i> {{A propos}}</a>')
  $bottomPanelOtherActions.append('<a id="bt_forceReload" class="ui-bottom-sheet-link ui-btn ui-btn-inline waves-effect waves-button"><i class="fas fa-retweet"></i> {{Forcer mise à jour}}</a>')
  $bottomPanelOtherActions.append('<a href="#" id="bt_logout" class="ui-bottom-sheet-link ui-btn ui-btn-inline waves-effect waves-button"><i class="fas fa-sign-out-alt"></i> {{Déconnexion}}</a>')

  jeedom.object.all({
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'})
    },
    success: function (objects) {
      var li = ''
      var summaries = []
      li += '<a href="#" class="link ui-bottom-sheet-link ui-btn ui-btn-inline waves-effect waves-button" data-page="equipment" data-title="<i class=\'fas fa-globe\'></i> {{Tout}}" data-option="all"><span><i class="fas fa-globe"></i> {{Tout}}</a>'
      for (var i in objects) {
        if (objects[i].isVisible == 1) {
          var icon = ''
          if (isset(objects[i].display) && isset(objects[i].display.icon)) {
            icon = objects[i].display.icon
          }
          li += '<a href="#" class="link ui-bottom-sheet-link ui-btn ui-btn-inline waves-effect waves-button" data-page="equipment" data-title="' + icon.replace(/\"/g, "\'") + ' ' + objects[i].name.replace(/\"/g, "\'") + '" data-option="' + objects[i].id + '"><span>' + icon + '</span> ' + objects[i].name + '<span class="summaryMenu"><span class="objectSummary'+objects[i].id+'" data-version="mobile"></span></span></a>'
          summaries.push({object_id : objects[i].id})
        }
      }
      $('#bottompanel_objectList').empty().append(li)
      jeedom.object.summaryUpdate(summaries)
    }
  })

  jeedom.view.all({
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'})
    },
    success: function (views) {
      var li = ''
      for (var i in views) {
        var icon = ''
        if (isset(views[i].display) && isset(views[i].display.icon)) {
          icon = views[i].display.icon
        }
        li += '<a href="#" class="link ui-bottom-sheet-link ui-btn ui-btn-inline waves-effect waves-button" data-page="view" data-title="'+ icon.replace(/\"/g, "\'") + ' ' + views[i].name + '" data-option="' + views[i].id + '">'+ icon + ' ' + views[i].name + '</a>'
      }
      $('#bottompanel_viewList').empty().append(li)
    }
  })

  jeedom.plan.allHeader({
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'})
    },
    success: function (planHeader) {
      var li = ''
      for (var i in planHeader) {
        var icon = ''
        if (isset(planHeader[i].configuration) && isset(planHeader[i].configuration.icon)) {
          icon = planHeader[i].configuration.icon
        }
        li += '<a class="ui-bottom-sheet-link ui-btn ui-btn-inline waves-effect waves-button" href="index.php?v=d&p=plan&plan_id=' + planHeader[i].id + '" data-ajax="false">' +icon+' '+ planHeader[i].name + '</a>'
      }
      $('#bottompanel_planList').empty().append(li)
    }
  })

  jeedom.plan3d.allHeader({
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'})
    },
    success: function (plan3dHeader) {
      var li = ''
      for (var i in plan3dHeader) {
        var icon = ''
        if (isset(plan3dHeader[i].configuration) && isset(plan3dHeader[i].configuration.icon)) {
          icon = plan3dHeader[i].configuration.icon
        }
        li += '<a class="ui-bottom-sheet-link ui-btn ui-btn-inline waves-effect waves-button" href="index.php?v=d&p=plan3d&plan3d_id=' + plan3dHeader[i].id + '&fullscreen=1" data-ajax="false">' +icon+' '+ plan3dHeader[i].name + '</a>'
      }
      $('#bottompanel_plan3dList').empty().append(li)
    }
  })


  if (plugins.length > 0) {
    var li = ''
    for (var i in plugins) {
      if(plugins[i].mobile == ''){
        continue
      }
      if(plugins[i].displayMobilePanel == 0){
        continue
      }
      li += '<a href="#" class="link ui-bottom-sheet-link ui-btn ui-btn-inline waves-effect waves-button" data-page="' + plugins[i].mobile + '" data-plugin="' + plugins[i].id + '" data-title="' + plugins[i].name + '">'
      li += '<img src="plugins/'+plugins[i].id +'/plugin_info/'+plugins[i].id +'_icon.png" onerror=\'this.style.display = "none"\' /> '
      li +=  plugins[i].name
      li +=  '</a>'
    }
    if(li != ''){
      $('#bottompanel_pluginList').empty().append(li)
    }else{
      $('#bt_listPlugin').hide()
    }
  } else {
    $('#bt_listPlugin').hide()
  }

  $('#bt_logout').off('click').on('click', function () {
    $.ajax({
      type: "POST",
      url: "core/ajax/user.ajax.php",
      data: {
        action: "logout",
      },
      dataType: 'json',
      error: function (request, status, error) {
        handleAjaxError(request, status, error, $('#div_alert'))
      },
      success: function (data) {
        if (data.state != 'ok') {
          $('#div_alert').showAlert({message: data.result, level: 'danger'})
          return
        }
        initApplication()
      }
    })
  })

  $('#bt_forceReload').off('click').on('click', function () {
    window.location.reload(true)
  })

  jeedom.version({success : function(version) {
    $('#homeVersion').html(version)
  }
})

jeedom.config.load({
  configuration: 'name',
  success: function (data) {
    $('#jeedomName').html(data+'<br>WebApp Jeedom<br>')
  }
})

setTimeout(function() {
  $('#pagecontainer').css('padding-top','64px') }
  , 100)
}
