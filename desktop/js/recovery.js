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

if (!jeeFrontEnd.recovery) {
  jeeFrontEnd.recovery = {
    step: Node,
    details: Node,
    progress: Node,
    buttons: NodeList,
    inProgress: null,
    mode: null,
    usb: null,
    init: function() {
      this.step = document.getElementById('recovery-step')
      this.details = document.getElementById('recovery-details')
      this.progress = document.getElementById('recovery-progress')
      this.buttons = document.getElementById('recovery-buttons').querySelectorAll('a.btn')
      window.jeeP = this
    },
    start: function(_mode) {
      jeeP.mode = _mode
      jeeP.monitorProgress()
      jeeP.displayButtons('cancel')

      jeedom.recovery.start({
        global: false,
        mode: _mode,
        success: function(_success) {
          jeeP.monitorProgress('stop')
          jeeP.mode = null

          if (_success) {
            jeeP.displayButtons('reboot', 'halt')
            jeeP.progress.removeClass('active')
          } else {
            jeeP.displayButtons('refresh')
          }
        }
      })
    },
    cancel: function() {
      bootbox.confirm('<div class="text-center alert alert-danger"><i class="fas fa-exclamation-triangle"></i> {{Annuler la procédure de restauration système en cours ?}}</div>', function(ok) {
        if (ok) {
          jeeP.monitorProgress('stop')

          jeeP.updateProgress({
            details: "{{Procédure annulée à la demande de l'utilisateur}}",
            progress: -1
          })

          if (jeeP.mode) {
            jeedom.recovery.cancel({
              async: false
            })
            setTimeout(() => {
              jeeP.getProgress()
            }, 800)
          }

          jeeP.displayButtons('refresh')
        }
      })
    },
    getProgress: function() {
      jeedom.recovery.getProgress({
        async: false,
        success: function(_data) {
          if (_data) {
            jeeP.updateProgress(JSON.parse(_data))
          }
        }
      })
    },
    monitorProgress: function(_state = 'start') {
      if (_state === 'start') {
        jeeP.inProgress = setInterval(function() {
          jeeP.getProgress()
        }, 500)
      } else if (_state === 'stop') {
        if (jeeP.inProgress) {
          clearInterval(jeeP.inProgress)
          jeeP.inProgress = null
        }
      }
    },
    updateProgress: function(_data) {
      if (isset(_data.step)) {
        jeeP.step.innerText = _data.step
      }
      if (isset(_data.details)) {
        jeeP.details.innerText = _data.details
      }
      if (isset(_data.progress)) {
        if (!jeeP.progress.isVisible()) {
          jeeP.progress.parentNode.seen()
        }

        if (_data.progress < 0) {
          jeeP.progress.classList = 'progress-bar ' + (!jeeP.inProgress ? 'progress-bar-warning' : 'progress-bar-danger')
          jeeP.progress.style.width = '100%'
          jeeP.progress.setAttribute('aria-valuenow', 100)
          jeeP.progress.innerText = (!jeeP.inProgress) ? "{{Annulation}}" : '{{Erreur}}'
          jeeP.displayButtons()
        } else if (_data.progress >= 100) {
          jeeP.progress.classList = 'progress-bar progress-bar-striped progress-bar-animated progress-bar-success active'
          jeeP.progress.style.width = '100%'
          jeeP.progress.setAttribute('aria-valuenow', 100)
          jeeP.progress.innerText = '100%'
          jeeP.displayButtons()
        } else {
          jeeP.progress.classList = 'progress-bar progress-bar-striped progress-bar-animated progress-bar-info active'
          jeeP.progress.style.width = _data.progress + '%'
          jeeP.progress.setAttribute('aria-valuenow', _data.progress)
          jeeP.progress.innerText = _data.progress + '%'
          if (_data.progress >= 98) {
            jeeP.displayButtons()
          }
        }
      }
    },
    displayButtons: function(..._buttons) {
      jeeP.buttons.unseen()
      _buttons.forEach(_button => {
        document.getElementById('bt_' + _button).seen()
      })
    },
    usbConnected: function() {
      jeedom.recovery.usbConnected({
        async: false,
        success: function(_data) {
          jeeP.usb = _data
        }
      })
    },
    usbDetection: function() {
      return new Promise(function(resolve, reject) {
        if (jeeP.usbConnected()) {
          return resolve(true)
        }

        let i = 1
        jeeP.updateProgress({
          step: '{{Détection de la clé USB}}',
          details: "{{Veuillez insérer une clé USB dans le premier port en haut à droite}}",
          progress: i
        })

        jeeP.displayButtons('cancel')
        jeeP.inProgress = setInterval(function() {
          if (jeeP.usbConnected()) {
            jeeP.monitorProgress('stop')
            return resolve(true)
          }

          if (i == 100) {
            jeeP.updateProgress({
              details: '{{Abandon, clé USB non détectée}}',
              progress: -1
            })
            jeeP.monitorProgress('stop')
            jeeP.displayButtons('refresh')
            return reject(new Error('{{Clé USB non détectée}}'))
          }

          i += 1
          jeeP.updateProgress({
            progress: i
          })
        }, 5000)
      })
    }
  }
}

jeeFrontEnd.recovery.init()

/**************************BUTTONS***********************************/
// /*Events delegations
// */
document.getElementById('recovery-buttons').addEventListener('click', function(_event) {
  var _target = null
  if (_target = _event.target.closest('#bt_auto')) {
    jeeP.start('auto')
    return
  }

  if (_target = _event.target.closest('#bt_usb')) {
    jeeP.usbDetection().then(() => {
      jeeP.start('usb')
    }).catch((_error) => {
    })
    return
  }

  if (_target = _event.target.closest('#bt_cancel')) {
    jeeP.cancel()
    return
  }

  if (_target = _event.target.closest('#bt_reboot')) {
    jeedomUtils.loadPage('index.php?v=d&p=reboot')
    return
  }

  if (_target = _event.target.closest('#bt_halt')) {
    jeedomUtils.loadPage('index.php?v=d&p=shutdown')
    return
  }

  if (_target = _event.target.closest('#bt_refresh')) {
    window.location.reload()
    return
  }
})
