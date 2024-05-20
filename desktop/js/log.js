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

if (!jeeFrontEnd.log) {
  jeeFrontEnd.log = {
    init: function() {
      window.jeeP = this
      this.btGlobalLogStopStart = document.getElementById('bt_globalLogStopStart')
      this.logListButtons = document.querySelectorAll('#ul_object .li_log')

      //autoclick first log:
      var logfile = getUrlVars('logfile')
      var log = document.querySelector('#div_displayLogList .li_log[data-log="' + logfile + '"]')
      if (log != null) {
        log.click()
      } else {
        document.querySelector('#div_displayLogList .li_log')?.click()
      }
    },
  }
}

//searching
document.getElementById('in_searchLogFilter')?.addEventListener('keyup', function(event) {
  var search = event.target.value
  if (search == '') {
    jeeP.logListButtons.seen()
    return
  }
  var not = search.startsWith(":not(")
  if (not) {
    search = search.replace(':not(', '')
  }
  search = jeedomUtils.normTextLower(search)
  jeeP.logListButtons.unseen()
  var match, text
  jeeP.logListButtons.forEach(_bt => {
    match = false
    text = jeedomUtils.normTextLower(_bt.textContent)
    if (text.includes(search)) {
      match = true
    }
    if (not) match = !match
    if (match) {
      _bt.seen()
    }
  })
})

/*Events delegations
*/
//div_pageContainer events delegation:
document.getElementById('div_pageContainer').addEventListener('click', function(event) {
  var _target = null

  // "Raw log" button clicked
  if (_target = event.target.closest('#brutlogcheck')) {
    // Empty log view
    document.getElementById('pre_globallog').empty()
    // Display Pause button
    jeedom.log.updateBtn(jeeP.btGlobalLogStopStart)
    // Activate log auto update
    jeedom.log.autoUpdateDelta({
      log: document.querySelector('li.li_log.active')?.getAttribute('data-log'),
      display: document.getElementById('pre_globallog'),
      search: document.getElementById('in_searchGlobalLog'),
      control: jeeP.btGlobalLogStopStart
    })
    return
  }

  // Log selected in the list
  if (_target = event.target.closest('.li_log')) {
    // Empty log view
    document.getElementById('pre_globallog').empty()
    // Activate the corresponding item in the list
    document.querySelectorAll('.li_log').removeClass('active')
    _target.addClass('active')
    // Display Pause button
    jeedom.log.updateBtn(jeeP.btGlobalLogStopStart)
    // Activate log auto update
    jeedom.log.autoUpdateDelta({
      log: _target.getAttribute('data-log'),
      display: document.getElementById('pre_globallog'),
      search: document.getElementById('in_searchGlobalLog'),
      control: jeeP.btGlobalLogStopStart
    })
    return
  }

  // Log download button clicked
  if (_target = event.target.closest('#bt_downloadLog')) {
    window.open('core/php/downloadFile.php?pathfile=log/' + document.querySelector('.li_log.active').getAttribute('data-log'), "_blank", null)
    return
  }

  if (_target = event.target.closest('#bt_clearLog')) {
    jeedom.log.clear({
      log: document.querySelector('.li_log.active').getAttribute('data-log'),
      success: function(data) {
        document.querySelector('li.li_log.active a').innerHTML = '<i class="fas fa-check"></i> ' + document.querySelector('.li_log.active').getAttribute('data-log')
        document.querySelector('li.li_log.active').click()
      }
    })
    return
  }

  if (_target = event.target.closest('#bt_clearAllLog')) {
    jeeDialog.confirm("{{Êtes-vous sûr de vouloir vider tous les logs ?}}", function(result) {
      if (result) {
        jeedom.log.clearAll({
          error: function(error) {
            jeedomUtils.showAlert({
              message: error.message,
              level: 'danger'
            })
          },
          success: function(data) {
            jeedomUtils.loadPage('index.php?v=d&p=log')
          }
        })
      }
    })
    return
  }

  if (_target = event.target.closest('#bt_removeLog')) {
    jeedom.log.remove({
      log: document.querySelector('.li_log.active').getAttribute('data-log'),
      success: function(data) {
        jeedomUtils.loadPage('index.php?v=d&p=log');
      }
    })
    return
  }

  if (_target = event.target.closest('#bt_removeAllLog')) {
    jeeDialog.confirm("{{Êtes-vous sûr de vouloir supprimer tous les logs ?}}", function(result) {
      if (result) {
        jeedom.log.removeAll({
          error: function(error) {
            jeedomUtils.showAlert({
              message: error.message,
              level: 'danger'
            })
          },
          success: function(data) {
            jeedomUtils.loadPage('index.php?v=d&p=log')
          }
        })
      }
    })
    return
  }

  if (_target = event.target.closest('#bt_resetLogFilterSearch')) {
    document.getElementById('in_searchLogFilter').jeeValue('').triggerEvent('keyup')
    return
  }

  if (_target = event.target.closest('#bt_resetGlobalLogSearch')) {
    document.getElementById('in_searchGlobalLog').jeeValue('').triggerEvent('keyup')
    return
  }
})

jeeFrontEnd.log.init()
