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

if (!jeeFrontEnd.cron) {
  jeeFrontEnd.cron = {
    init: function() {
      window.jeeP = this
      this.$tableCron = $('#table_cron')
      this.printCron()
      this.printListener()
      jeedomUtils.initTableSorter(false)
      this.$tableCron[0].config.widgetOptions.resizable_widths = ['50px', '65px', '52px', '100px', '80px', '', '', '', '115px', '148px', '120px', '60px', '90px']
      this.$tableCron.trigger('applyWidgets')
        .trigger('resizableReset')
        .trigger('sorton', [
          [
            [0, 0]
          ]
        ])

      this.getDeamonState()
    },
    //-> Cron
    printCron: function() {
      jeedom.cron.all({
        success: function(data) {
          domUtils.showLoading()
          var table = document.getElementById('table_cron').querySelector('tbody')
          table.empty()
          for (var i in data) {
            let newRow = document.createElement("tr")
            newRow.innerHTML = jeeP.addCron(data[i])
            newRow.setJeeValues(data[i], '.cronAttr')
            table.appendChild(newRow)
          }
          jeeP.$tableCron.trigger("update")
          jeeFrontEnd.modifyWithoutSave = false
          setTimeout(function() {
            jeeFrontEnd.modifyWithoutSave = false
          }, 1000)
          domUtils.hideLoading()
        }
      })
    },
    addCron: function(_cron) {
      jeedomUtils.hideAlert()
      var disabled = ''
      if (init(_cron.deamon) == 1) {
        disabled = 'disabled'
      }
      var tr = '<tr>'
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
      return tr
    },
    //-> Listeners
    printListener: function() {
      jeedom.listener.all({
        success: function(data) {
          domUtils.showLoading()
          var table = document.getElementById('table_listener').querySelector('tbody')
          table.empty()
          for (var i in data) {
            let newRow = document.createElement("tr")
            newRow.innerHTML = jeeP.addListener(data[i])
            newRow.setJeeValues(data[i], '.listenerAttr')
            table.appendChild(newRow)
          }
          jeeFrontEnd.modifyWithoutSave = false
          domUtils.hideLoading()
        }
      })
    },
    addListener: function(_listener) {
      jeedomUtils.hideAlert()
      var disabled = ''
      var tr = '<tr>'
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
      return tr
    },
    //-> Daemons
    getDeamonState: function() {
      $('#table_deamon tbody').empty()
      jeedom.plugin.all({
        activateOnly: true,
        error: function(error) {
          jeedomUtils.showAlert({
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
                jeedomUtils.showAlert({
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
                document.getElementById('table_deamon tbody').html(html, true)
              }
            })
          }
        }
      })
    },
  }
}

jeeFrontEnd.cron.init()

document.registerEvent('keydown', function(event) {
  if (jeedomUtils.getOpenedModal()) return
  if ((event.ctrlKey || event.metaKey) && event.which == 83) { //s
    event.preventDefault()
    document.getElementById("bt_save").click()
  }
})

$("#bt_refreshCron").on('click', function() {
  jeeP.printCron()
  jeeP.printListener()
})

$("#bt_addCron").on('click', function() {
  var table = document.getElementById('table_cron').querySelector('tbody')
  let newRow = document.createElement("tr")
  newRow.innerHTML = jeeP.addCron({})
  newRow.setJeeValues({}, '.cronAttr')
  table.appendChild(newRow)
  jeeP.$tableCron.trigger("update")
  jeeFrontEnd.modifyWithoutSave = true
})

$("#bt_save").on('click', function() {
  jeedom.cron.save({
    crons: document.querySelectorAll('#table_cron tbody tr').getJeeValues('.cronAttr'),
    error: function(error) {
      jeedomUtils.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function() {
      jeeP.printCron()
    }
  })
})

$("#bt_changeCronState").on('click', function() {
  var el = event.target.closest('#bt_changeCronState')
  jeedom.config.save({
    configuration: {
      enableCron: el.getAttribute('data-state')
    },
    error: function(error) {
      jeedomUtils.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function() {
      if (el.getAttribute('data-state') == '1') {
        el.removeClass('btn-success').addClass('btn-danger').setAttribute('data-state', '0')
        el.innerHTML = '<i class="fas fa-times"></i> {{Désactiver le système cron}}'
      } else {
        el.removeClass('btn-danger').addClass('btn-success').setAttribute('data-state', '1')
        el.innerHTML = '<i class="fas fa-check"></i> {{Activer le système cron}}</a>'
      }
    }
  })
})

jeeP.$tableCron.on({
  'click': function(event) {
    event.target.closest('tr').remove()
  }
}, '.remove')

jeeP.$tableCron.on({
  'click': function(event) {
    jeedom.cron.setState({
      state: 'stop',
      id: event.target.closest('tr').querySelector('span[data-l1key="id"]').innerHTML,
      error: function(error) {
        jeedomUtils.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function() {
        jeeP.printCron()
      }
    })
  }
}, '.stop')

jeeP.$tableCron.on({
  'click': function(event) {
    jeedom.cron.setState({
      state: 'start',
      id: event.target.closest('tr').querySelector('span[data-l1key="id"]').innerHTML,
      error: function(error) {
        jeedomUtils.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function() {
        jeeP.printCron()
      }
    })
  }
}, '.start')

jeeP.$tableCron.on({
  'click': function(event) {
    jeeDialog.dialog({
      id: 'jee_modal',
      title: "{{Détails du cron}}",
      contentUrl: 'index.php?v=d&modal=object.display&class=cron&id=' + event.target.closest('tr').querySelector('span[data-l1key="id"]').innerHTML
    })
  }
}, '.display')

jeeP.$tableCron.on({
  'change': function(event) {
    if (event.target.jeeValue() == 1) {
      event.target.closest('tr').querySelector('.cronAttr[data-l1key=deamonSleepTime]').seen()
    } else {
      event.target.closest('tr').querySelector('.cronAttr[data-l1key=deamonSleepTime]').unseen()
    }
  }
}, '.cronAttr[data-l1key=deamon]')

$("#table_listener").on({
  'click': function(event) {
    jeeDialog.dialog({
      id: 'jee_modal',
      title: "{{Détails du listener}}",
      contentUrl: 'index.php?v=d&modal=object.display&class=listener&id=' + event.target.closest('tr').querySelector('span[data-l1key="id"]').innerHTML
    })
  }
}, '.display')

$('#div_pageContainer').off('change', '.cronAttr').on('change', '.cronAttr:visible', function(event) {
  jeeFrontEnd.modifyWithoutSave = true
})

$('#table_listener').off('click', '.removeListener').on('click', '.removeListener', function(event) {
  var tr = event.target.closest('tr')
  jeedom.listener.remove({
    id: tr.getAttribute('id'),
    success: function() {
      tr.remove()
    }
  })
})

$('#bt_refreshDeamon').on('click', function(event) {
  jeeP.getDeamonState()
})

$('#table_deamon tbody').on('click', '.bt_deamonAction', function(event) {
  var plugin = event.target.getAttribute('data-plugin')
  var action = event.target.getAttribute('data-action')
  if (action == 'start') {
    jeedom.plugin.deamonStart({
      id: plugin,
      forceRestart: 1,
      error: function(error) {
        jeedomUtils.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function() {
        jeeP.getDeamonState()
      }
    })
  } else if (action == 'stop') {
    jeedom.plugin.deamonStop({
      id: plugin,
      error: function(error) {
        jeedomUtils.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function() {
        jeeP.getDeamonState()
      }
    })
  } else if (action == 'enableAuto') {
    jeedom.plugin.deamonChangeAutoMode({
      id: plugin,
      mode: 1,
      error: function(error) {
        jeedomUtils.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function() {
        jeeP.getDeamonState()
      }
    })
  } else if (action == 'disableAuto') {
    jeedom.plugin.deamonChangeAutoMode({
      id: plugin,
      mode: 0,
      error: function(error) {
        jeedomUtils.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function() {
        jeeP.getDeamonState()
      }
    })
  }
})

//Register events on top of page container:

//Manage events outside parents delegations:

//Specials

/*Events delegations
*/