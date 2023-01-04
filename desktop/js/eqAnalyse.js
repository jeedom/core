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

if (!jeeFrontEnd.eqAnalyse) {
  jeeFrontEnd.eqAnalyse = {
    init: function() {
      window.jeeP = this
      this.$batteryContainer = $('div.batteryListContainer')
      this.$alertListContainer = $('div.alertListContainer')
      this.$tableDeadCmd = $('#table_deadCmd')
    },
    getRemoveCmd: function(_id) {
      for (var i in jeephp2js.removeHistory) {
        if (jeephp2js.removeHistory[i].type == 'cmd' && jeephp2js.removeHistory[i].id == _id) return jeephp2js.removeHistory[i]
      }
      return false
    },
    displayDeadCmd: function() {
      jeedom.cmd.getDeadCmd({
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          var tr = ''
          var removed
          for (var i in data) {
            for (var j in data[i].cmd) {
              tr += '<tr>'
              tr += '<td>'
              tr += data[i].name
              tr += '</td>'

              tr += '<td>'
              tr += data[i].cmd[j].detail
              tr += '</td>'

              tr += '<td>'
              tr += data[i].cmd[j].who

              removed = jeeFrontEnd.eqAnalyse.getRemoveCmd(data[i].cmd[j].who.replaceAll('#', ''))
              if (removed) {
                tr += ' <span class="lebel label-sm label-info">' + removed.name + '</span>'
                tr += ' {{Supprimée le}} ' + removed.date
              }

              tr += '</td>'

              tr += '<td>'
              tr += data[i].cmd[j].help
              tr += '</td>'
              tr += '</tr>'
            }
          }
          jeeFrontEnd.eqAnalyse.$tableDeadCmd.find('tbody').empty().append(tr)
          jeeFrontEnd.eqAnalyse.$tableDeadCmd[0].config.widgetOptions.resizable_widths = ['180px', '', '', '180px']
          jeeFrontEnd.eqAnalyse.$tableDeadCmd.trigger('update')
            .trigger('applyWidgets')
            .trigger('resizableReset')
            .trigger('sorton', [
              [
                [0, 0]
              ]
            ])
        }
      })
    },
  }
}

jeeFrontEnd.eqAnalyse.init()

$('.alertListContainer .jeedomAlreadyPosition').removeClass('jeedomAlreadyPosition')

//tabs icons colors:
if ($('div.batteryListContainer div.eqLogic-widget.critical').length) {
  $('a[href="#battery"] > i').addClass('danger')
} else if ($('div.batteryListContainer div.eqLogic-widget.warning').length) {
  $('a[href="#battery"] > i').addClass('warning')
} else {
  $('a[href="#battery"] > i').addClass('success')
}

if ($('div.alertListContainer div.eqLogic-widget').length) {
  $('a[href="#alertEqlogic"] > i').addClass('warning')
}

jeedomUtils.initTableSorter()
window.registerEvent("resize", function eqAnalyse(event) {
  if (document.querySelector('#ul_tabBatteryAlert li.alerts').hasClass('active')) {
    jeedomUtils.positionEqLogic()
  }
})

//update tablesorter on tab click:
$("#tab_actionCmd").off("click").on("click", function() {
  $('#table_Action').trigger('update')
})
$("#tab_alertCmd").off("click").on("click", function() {
  $('#table_Alert').trigger('update')
})
$("#tab_pushCmd").off("click").on("click", function() {
  $('#table_Push').trigger('update')
})
$("#tab_deadCmd").off("click").on("click", function() {
  jeeP.displayDeadCmd()
})

jeeP.$alertListContainer.packery({
  itemSelector: "#alertEqlogic .eqLogic-widget"
})

$('.alerts, .batteries').on('click', function() {
  setTimeout(function() {
    jeedomUtils.positionEqLogic()
    jeeP.$alertListContainer.packery({
      itemSelector: "#alertEqlogic .eqLogic-widget"
    })
  }, 10)
})

$('.cmdAction[data-action=configure]').on('click', function() {
  jeeDialog.dialog({
    id: 'jee_modal2',
    title: '{{Configuration de la commande}}',
    contentUrl: 'index.php?v=d&modal=cmd.configure&cmd_id=' + this.closest('.cmd').getAttribute('data-cmd_id')
  })
})

//searching
jeeP.$eqlogics = jeeP.$batteryContainer.find('.eqLogic-widget')
$('#in_search').off('keyup').on('keyup', function() {
  if (jeeP.$eqlogics.length == 0) {
    return
  }
  var search = this.value
  if (search == '') {
    jeeP.$eqlogics.show()
    return
  }
  search = jeedomUtils.normTextLower(search)
  var not = search.startsWith(":not(")
  if (not) {
    search = search.replace(':not(', '')
  }

  var match, text
  jeeP.$eqlogics.each(function() {
    match = false
    text = jeedomUtils.normTextLower($(this).find('.widget-name').text())
    if (text.includes(search)) match = true

    if (not) match = !match
    if (match) {
      $(this).show()
    } else {
      $(this).hide()
    }
  })
})
$('#bt_resetSearch').on('click', function() {
  $('#in_search').val('').keyup()
})

$('.batteryTime').off('click').on('click', function() {
  $('#md_modal').dialog({
    title: "{{Configuration de l'équipement}}"
  }).load('index.php?v=d&modal=eqLogic.configure&eqLogic_id=' + $(this).closest('.eqLogic').attr('data-eqlogic_id')).dialog('open')
})

$('#bt_massConfigureEqLogic').off('click').on('click', function() {
  $('#md_modal').dialog({
    title: "{{Configuration en masse}}"
  }).load('index.php?v=d&modal=object.massEdit&type=eqLogic&fields=timeout,Alertes%20Communications').dialog('open')
})
