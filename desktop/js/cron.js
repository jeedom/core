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

"use strict"

var $tableCron = $('#table_cron')
printCron()
printListener()
jeedomUtils.initTableSorter(false)
$tableCron[0].config.widgetOptions.resizable_widths = ['50px', '65px', '52px', '100px', '80px', '', '', '', '115px', '148px', '120px', '60px', '90px']
$tableCron.trigger('applyWidgets')
  .trigger('resizableReset')
  .trigger('sorton', [
    [
      [0, 0]
    ]
  ])

document.onkeydown = function(event) {
  if (jeedomUtils.getOpenedModal()) return

  if ((event.ctrlKey || event.metaKey) && event.which == 83) { //s
    event.preventDefault()
    $("#bt_save").click()
  }
}

$("#bt_refreshCron").on('click', function() {
  printCron()
  printListener()
})

$("#bt_addCron").on('click', function() {
  $('#table_cron tbody').prepend(addCron({}))
})

$("#bt_save").on('click', function() {
  jeedom.cron.save({
    crons: $('#table_cron tbody tr').getValues('.cronAttr'),
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function() {
      printCron()
    }
  })
})

$("#bt_changeCronState").on('click', function() {
  var el = $(this)
  jeedom.config.save({
    configuration: {
      enableCron: el.attr('data-state')
    },
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function() {
      if (el.attr('data-state') == 1) {
        el.removeClass('btn-success').addClass('btn-danger').attr('data-state', 0)
        el.empty().html('<i class="fas fa-times"></i> {{Désactiver le système cron}}')
      } else {
        el.removeClass('btn-danger').addClass('btn-success').attr('data-state', 1)
        el.empty().html('<i class="fas fa-check"></i> {{Activer le système cron}}</a>')
      }
    }
  })
})

$tableCron.on({
  'click': function(event) {
    $(this).closest('tr').remove()
  }
}, '.remove')

$tableCron.on({
  'click': function(event) {
    jeedom.cron.setState({
      state: 'stop',
      id: $(this).closest('tr').attr('id'),
      error: function(error) {
        $.fn.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: printCron
    })
  }
}, '.stop')

$tableCron.on({
  'click': function(event) {
    jeedom.cron.setState({
      state: 'start',
      id: $(this).closest('tr').attr('id'),
      error: function(error) {
        $.fn.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: printCron
    })
  }
}, '.start')

$tableCron.on({
  'click': function(event) {
    $('#md_modal').dialog({
      title: "{{Détails du cron}}"
    }).load('index.php?v=d&modal=object.display&class=cron&id=' + $(this).closest('tr').attr('id')).dialog('open')
  }
}, '.display')

$tableCron.on({
  'change': function(event) {
    if ($(this).value() == 1) {
      $(this).closest('tr').find('.cronAttr[data-l1key=deamonSleepTime]').show()
    } else {
      $(this).closest('tr').find('.cronAttr[data-l1key=deamonSleepTime]').hide()
    }
  }
}, '.cronAttr[data-l1key=deamon]')

$("#table_listener").on({
  'click': function(event) {
    $('#md_modal').dialog({
      title: "{{Détails du listener}}"
    }).load('index.php?v=d&modal=object.display&class=listener&id=' + $(this).closest('tr').attr('id')).dialog('open')
  }
}, '.display')

$('#div_pageContainer').off('change', '.cronAttr').on('change', '.cronAttr:visible', function() {
  modifyWithoutSave = true
})

/***********************CRONS*****************************/
function printCron() {
  $.showLoading()
  jeedom.cron.all({
    success: function(data) {
      $.showLoading()
      $tableCron.find('tbody').empty()
      var tr = []
      for (var i in data) {
        tr.push(addCron(data[i]))
      }
      $('#table_cron tbody').append(tr)

      $tableCron.trigger("update")

      modifyWithoutSave = false
      setTimeout(function() {
        modifyWithoutSave = false
      }, 1000)
      $.hideLoading()
    }
  })
}

function addCron(_cron) {
  $.hideAlert()
  var disabled = ''
  if (init(_cron.deamon) == 1) {
    disabled = 'disabled'
  }
  var tr = '<tr id="' + init(_cron.id) + '">'
  tr += '<td><span class="cronAttr label label-info" data-l1key="id"></span></td>'
  tr += '<td class="center">'
  tr += '<input type="checkbox"class="cronAttr" data-l1key="enable" checked ' + disabled + '/>'
  tr += '</td>'
  tr += '<td>'
  tr += init(_cron.pid)
  tr += '</td>'
  tr += '<td class="center">'
  tr += '<input type="checkbox" class="cronAttr" data-l1key="deamon" ' + disabled + ' /></span> '
  tr += '<input class="cronAttr input-xs" data-l1key="deamonSleepTime" style="width : 50px;" />'
  tr += '</td>'
  tr += '<td class="center">'
  if (init(_cron.deamon) == 0) {
    tr += '<input type="checkbox" class="cronAttr" data-l1key="once" /></span> '
  }
  tr += '</td>'
  tr += '<td><input class="form-control cronAttr input-sm" data-l1key="class" ' + disabled + ' /></td>'
  tr += '<td><input class="form-control cronAttr input-sm" data-l1key="function" ' + disabled + ' /></td>'
  tr += '<td><input class="form-control cronAttr input-sm" data-l1key="schedule" ' + disabled + ' /></td>'
  tr += '<td>'
  if (init(_cron.deamon) == 0) {
    tr += '<input class="form-control cronAttr input-sm" data-l1key="timeout" />'
  }
  tr += '</td>'
  tr += '<td>'
  tr += init(_cron.lastRun)
  tr += '</td>'
  tr += '<td>'
  tr += init(_cron.runtime, '0') + 's'
  tr += '</td>'
  tr += '<td>'
  var label = 'label label-info'
  var state = init(_cron.state)
  if (init(_cron.state) == 'run') {
    label = 'label label-success'
    state = '{{En cours}}'
  }
  if (init(_cron.state) == 'stop') {
    label = 'label label-danger'
    state = '{{Arrêté}}'
  }
  if (init(_cron.state) == 'starting') {
    label = 'label label-warning'
    state = '{{Démarrage}}'
  }
  if (init(_cron.state) == 'stoping') {
    label = 'label label-warning'
    state = '{{Arrêt}}'
  }
  tr += '<span class="' + label + '">' + state + '</span>'
  tr += '</td>'

  tr += '<td>'
  if (init(_cron.id) != '') {
    tr += '<a class="btn btn-xs display" title="{{Détails de cette tâche}}"><i class="fas fa-file"></i></a> '
  }
  if (init(_cron.deamon) == 0) {
    if (init(_cron.state) == 'run') {
      tr += ' <a class="btn btn-danger btn-xs stop" title="{{Arrêter cette tâche}}"><i class="fas fa-stop"></i></a>'
    }
    if (init(_cron.state) != '' && init(_cron.state) != 'starting' && init(_cron.state) != 'run' && init(_cron.state) != 'stoping') {
      tr += ' <a class="btn btn-xs btn-success start" title="{{Démarrer cette tâche}}"><i class="fas fa-play"></i></a>'
    }
  }
  tr += ' <a class="btn btn-danger btn-xs" title="{{Supprimer cette tâche}}"><i class="icon maison-poubelle remove"></i></a>'
  tr += '</td>'
  tr += '</tr>'
  var result = $(tr)
  result.setValues(_cron, '.cronAttr')
  return result
}

/***********************LISTENERS*****************************/
function printListener() {
  $.showLoading()
  jeedom.listener.all({
    success: function(data) {
      $.showLoading()
      $('#table_listener tbody').empty()
      var tr = []
      for (var i in data) {
        tr.push(addListener(data[i]))
      }
      $('#table_listener tbody').append(tr)
      modifyWithoutSave = false
      $.hideLoading()
    }
  })
}

function addListener(_listener) {
  $.hideAlert()
  var disabled = ''
  var tr = '<tr id="' + init(_listener.id) + '">'
  tr += '<td class="option"><span class="listenerAttr" data-l1key="id"></span></td>'
  tr += '<td>'
  if (init(_listener.id) != '') {
    tr += '<a class="btn btn-xs display"><i class="fas fa-file"></i></a> '
  }
  tr += '</td>'
  tr += '<td><textarea class="form-control listenerAttr input-sm" data-l1key="event_str" disabled ></textarea></td>'
  tr += '<td><input class="form-control listenerAttr input-sm" data-l1key="class" disabled /></td>'
  tr += '<td><input class="form-control listenerAttr input-sm" data-l1key="function" disabled /></td>'
  tr += '<td><a class="btn btn-danger btn-xs removeListener pull-right" title="{{Supprimer cette tâche}}"><i class="icon maison-poubelle"></i></a></td>'
  tr += '</tr>'
  var result = $(tr)
  result.setValues(_listener, '.listenerAttr')
  return result
}

$('#table_listener').off('click', '.removeListener').on('click', '.removeListener', function() {
  var tr = $(this).closest('tr')
  jeedom.listener.remove({
    id: tr.attr('id'),
    success: function() {
      tr.remove()
    }
  })
})

/***********************DEAMONS*****************************/
getDeamonState()

$('#bt_refreshDeamon').on('click', function() {
  getDeamonState()
})

function getDeamonState() {
  $('#table_deamon tbody').empty()
  jeedom.plugin.all({
    activateOnly: true,
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(plugins) {
      for (var i in plugins) {
        if (plugins[i].hasOwnDeamon == 0) continue

        jeedom.plugin.getDeamonInfo({
          id: plugins[i].id,
          async: false,
          error: function(error) {
            $.fn.showAlert({
              message: error.message,
              level: 'danger'
            })
          },
          success: function(deamonInfo) {
            var html = '<tr>'
            html += '<td>'
            html += deamonInfo.plugin.name
            html += '</td>'
            html += '<td>'
            if (deamonInfo.state == 'ok') {
              html += '<span class="label label-success">OK</span>'
            } else {
              html += '<span class="label label-danger">' + deamonInfo.state.toUpperCase() + '</span>'
            }
            html += '</td>'
            html += '<td>'
            html += deamonInfo.last_launch
            html += '</td>'
            html += '<td>'
            html += '<a class="bt_deamonAction btn btn-xs btn-success" data-action="start" title="{{Démarrer ou re-démarrer}}" data-plugin="' + deamonInfo.plugin.id + '"><i class="fas fa-play"></i></a> '
            if (deamonInfo.auto == 0) {
              html += '<a class="bt_deamonAction btn btn-xs btn-danger" data-action="stop" title="{{Arrêter}}" data-plugin="' + deamonInfo.plugin.id + '"><i class="fas fa-stop"></i></a> '
              html += '<a class="bt_deamonAction btn btn-xs btn-warning" data-action="enableAuto" title="{{Activer la gestion automatique du démon}}" data-plugin="' + deamonInfo.plugin.id + '"><i class="fas fa-magic"></i></a> '
            } else {
              html += '<a class="bt_deamonAction btn btn-xs btn-warning" data-action="disableAuto" title="{{Désactiver la gestion automatique du démon}}" data-plugin="' + deamonInfo.plugin.id + '"><i class="fas fa-times"></i></a> '
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

$('#table_deamon tbody').on('click', '.bt_deamonAction', function() {
  var plugin = $(this).data('plugin')
  var action = $(this).data('action')
  if (action == 'start') {
    jeedom.plugin.deamonStart({
      id: plugin,
      forceRestart: 1,
      error: function(error) {
        $.fn.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function() {
        getDeamonState()
      }
    })
  } else if (action == 'stop') {
    jeedom.plugin.deamonStop({
      id: plugin,
      error: function(error) {
        $.fn.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function() {
        getDeamonState()
      }
    })
  } else if (action == 'enableAuto') {
    jeedom.plugin.deamonChangeAutoMode({
      id: plugin,
      mode: 1,
      error: function(error) {
        $.fn.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function() {
        getDeamonState()
      }
    })
  } else if (action == 'disableAuto') {
    jeedom.plugin.deamonChangeAutoMode({
      id: plugin,
      mode: 0,
      error: function(error) {
        $.fn.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function() {
        getDeamonState()
      }
    })
  }
})