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
      document.querySelectorAll('.alertListContainer .jeedomAlreadyPosition').removeClass('jeedomAlreadyPosition')
      //tabs icons colors:
      if (document.querySelectorAll('div.batteryListContainer div.eqLogic-widget.critical').length) {
        document.querySelector('a[data-target="#battery"] > i').addClass('danger')
      } else if (document.querySelectorAll('div.batteryListContainer div.eqLogic-widget.warning').length) {
        document.querySelector('a[data-target="#battery"] > i').addClass('warning')
      } else {
        document.querySelector('a[data-target="#battery"] > i').addClass('success')
      }

      if (document.querySelectorAll('div.alertListContainer div.eqLogic-widget').length) {
        document.querySelector('a[data-target="#alertEqlogic"] > i').addClass('warning')
      }

      jeedomUtils.initDataTables('body', false, true)
      new Packery(document.querySelector('div.alertListContainer'), {
        itemSelector: "#alertEqlogic .eqLogic-widget",
        isLayoutInstant: true,
        transitionDuration: 0,
      }).layout()
      this.eqlogicsEls = document.querySelectorAll('div.batteryListContainer > div.eqLogic-widget')
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
          let tableDeadCmd = document.getElementById('table_deadCmd')
          tableDeadCmd.tBodies[0].empty().insertAdjacentHTML('beforeend', tr)
          if (tableDeadCmd._dataTable) tableDeadCmd._dataTable.refresh()
        }
      })
    },
  }
}

jeeFrontEnd.eqAnalyse.init()

//searching
document.getElementById('in_search')?.addEventListener('keyup', function(event) {
  if (jeeP.eqlogicsEls.length == 0) {
    return
  }
  var search = event.target.closest('#in_search').value
  if (search == '') {
    jeeP.eqlogicsEls.seen()
    return
  }
  search = jeedomUtils.normTextLower(search)
  var not = search.startsWith(":not(")
  if (not) {
    search = search.replace(':not(', '')
  }

  var match, text
  jeeP.eqlogicsEls.forEach(_el => {
    match = false
    text = jeedomUtils.normTextLower(_el.querySelector('.widget-name').textContent)
    if (text.includes(search)) match = true

    if (not) match = !match
    if (match) {
      _el.seen()
    } else {
      _el.unseen()
    }
  })
})
document.getElementById('bt_resetSearch')?.addEventListener('click', function(event) {
  document.getElementById('in_search').jeeValue('').triggerEvent('keyup')
})


//Register events on top of page container:
window.registerEvent("resize", function eqAnalyse(event) {
  if (document.getElementById('tab_alerts').hasClass('active')) {
    jeedomUtils.positionEqLogic()
  }
})


//Manage events outside parents delegations:
document.getElementById('bt_massConfigureEqLogic')?.addEventListener('click', function(event) {
  var field = "{{Alertes Communications}}"
  jeeDialog.dialog({
    id: 'jee_modal',
    title: "{{Configuration en masse}}",
    contentUrl: 'index.php?v=d&modal=object.massEdit&type=eqLogic&fields=timeout,' + field
  })
})


/*Events delegations
*/
document.getElementById('div_pageContainer').addEventListener('click', function(event) {
  var _target = null
  if (_target = event.target.closest('#tab_alerts')) {
    setTimeout(function() {
      jeedomUtils.positionEqLogic()
      Packery.data(document.querySelector('div.alertListContainer')).layout()
    }, 10)
    return
  }
  if (_target = event.target.closest('#tab_actionCmd')) {
    document.getElementById('table_Action').triggerEvent('update')
    return
  }
  if (_target = event.target.closest('#tab_alertCmd')) {
    document.getElementById('table_Alert').triggerEvent('update')
    return
  }
  if (_target = event.target.closest('#tab_pushCmd')) {
    document.getElementById('table_Push').triggerEvent('update')
    return
  }
  if (_target = event.target.closest('#tab_deadCmd')) {
    jeeP.displayDeadCmd()
    return
  }

  if (_target = event.target.closest('.cmdAction[data-action="configure"]')) {
    jeeDialog.dialog({
      id: 'jee_modal',
      title: '{{Configuration de la commande}}',
      contentUrl: 'index.php?v=d&modal=cmd.configure&cmd_id=' + _target.getAttribute('data-cmd_id')
    })
    return
  }

  if (_target = event.target.closest('.batteryTime')) {
    jeeDialog.dialog({
      id: 'jee_modal',
      title: "{{Configuration de l'équipement}}",
      contentUrl: 'index.php?v=d&modal=eqLogic.configure&eqLogic_id=' + _target.closest('div.eqLogic').getAttribute('data-eqlogic_id')
    })
    return
  }
})
