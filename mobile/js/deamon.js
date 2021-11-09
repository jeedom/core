"use strict"

$('body').attr('data-page', 'cron')

function initDeamon() {
  var rightPanel = '<ul data-role="listview" class="ui-icon-alt">'
  rightPanel += '<li><a id="bt_refreshDeamon" href="#"><i class="fas fa-refresh"></i> {{Rafraichir}}</a></li>'
  rightPanel += '<li><a class="ui-bottom-sheet-link ui-btn ui-btn-inline waves-effect waves-button" href="index.php?v=d"><i class="fas fa-desktop"></i> {{Version desktop}}</a></li>'
  rightPanel += '<li><a class="link ui-bottom-sheet-link ui-btn ui-btn-inline waves-effect waves-button" data-page="cron" data-title="{{Crons}}"><i class="fas fa-cogs" ></i> {{Crons}}</a></li>'
  rightPanel += '<li><a class="link ui-bottom-sheet-link ui-btn ui-btn-inline waves-effect waves-button" data-page="health" data-title="{{Santé}}"><i class="icon divers-caduceus3" ></i> {{Santé}}</a></li>'
  rightPanel += '</ul>'
  jeedomUtils.loadPanel(rightPanel)
  getDeamonState()
}

$('#bt_refreshDeamon').on('click',function(){
  getDeamonState()
})

function getDeamonState(){
  $('#table_deamon tbody').empty()
  jeedom.plugin.all({
    error: function (error) {
      $.fn.showAlert({message: error.message, level: 'danger'})
    },
    success: function (plugins) {
      for (var i in plugins) {
       if (plugins[i].hasOwnDeamon == 0) {
        continue
      }
      jeedom.plugin.getDeamonInfo({
        id : plugins[i].id,
        async:false,
        error: function (error) {
          $.fn.showAlert({message: error.message, level: 'danger'})
        },
        success: function (deamonInfo) {
          var html = '<tr>'
          html += '<td>'
          html += deamonInfo.plugin.name
          html += '</td>'
          html += '<td>'
          html += deamonInfo.state
          html += '</td>'
          html += '<td>'
          html += deamonInfo.last_launch
          html += '</td>'
          html += '<td>'
          html += '<a class="bt_deamonAction ui-btn ui-mini ui-btn-inline ui-btn-raised clr-primary" data-action="start" data-plugin="'+deamonInfo.plugin.id+'"><i class="fas fa-play"></i></a> '
          if (deamonInfo.auto == 0) {
            html += '<a class="bt_deamonAction ui-btn ui-mini ui-btn-inline ui-btn-raised clr-warning" data-action="stop" data-plugin="'+deamonInfo.plugin.id+'"><i class="fas fa-stop"></i></a> '
            html += '<a class="bt_deamonAction ui-btn ui-mini ui-btn-inline ui-btn-raised clr-primary" data-action="enableAuto" data-plugin="'+deamonInfo.plugin.id+'"><i class="fas fa-magic"></i></a> '
          } else {
            html += '<a class="bt_deamonAction ui-btn ui-mini ui-btn-inline ui-btn-raised clr-warning" data-action="disableAuto" data-plugin="'+deamonInfo.plugin.id+'"><i class="fas fa-times"></i></a> '
          }
          html += '</td>'
          html += '</tr>'
          $('#table_deamon tbody').append(html)
        }
      })
    }
  }
})
}

$('#table_deamon tbody').on('click','.bt_deamonAction',function(){
  var plugin = $(this).data('plugin')
  var action = $(this).data('action')
  if (action == 'start') {
    jeedom.plugin.deamonStart({
      id : plugin,
      forceRestart : 1,
      error: function (error) {
        $.fn.showAlert({message: error.message, level: 'danger'})
      },
      success: function () {
       getDeamonState()
     }
   })
  } else if (action == 'stop') {
    jeedom.plugin.deamonStop({
      id : plugin,
      error: function (error) {
        $.fn.showAlert({message: error.message, level: 'danger'})
      },
      success: function () {
       getDeamonState()
     }
   })
  } else if (action == 'enableAuto') {
    jeedom.plugin.deamonChangeAutoMode({
      id : plugin,
      mode:1,
      error: function (error) {
        $.fn.showAlert({message: error.message, level: 'danger'})
      },
      success: function () {
       getDeamonState()
     }
   })
  } else if (action == 'disableAuto') {
    jeedom.plugin.deamonChangeAutoMode({
      id : plugin,
      mode:0,
      error: function (error) {
        $.fn.showAlert({message: error.message, level: 'danger'})
      },
      success: function () {
       getDeamonState()
     }
   })
  }
})