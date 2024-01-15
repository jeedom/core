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

if (!jeeFrontEnd.backup) {
  jeeFrontEnd.backup = {
    init: function() {
      window.jeeP = this
    },
    postInit: function() {
      this.updateListBackup()
      for (var i in jeephp2js.repoList) {
        this.updateRepoListBackup(jeephp2js.repoList[i])
      }

      jeedom.config.load({
        configuration: document.getElementById('backup').getJeeValues('.configKey')[0],
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          document.getElementById('backup').setJeeValues(data, '.configKey')
          jeeFrontEnd.modifyWithoutSave = false
        }
      })


      new jeeFileUploader({
        fileInput: document.getElementById('bt_uploadBackup'),
        dataType: 'json',
        replaceFileInput: false,
        done: function(e, data) {
          if (data.result.state != 'ok') {
            jeedomUtils.showAlert({
              message: data.result.result,
              level: 'danger'
            })
            return
          }
          jeeFrontEnd.backup.updateListBackup()
          jeedomUtils.showAlert({
            message: '{{Fichier(s) ajouté(s) avec succès}}',
            level: 'success'
          })
        }
      })

    },
    getJeedomLog: function(_autoUpdate, _log) {
      if (document.body.getAttribute('data-page') != 'backup') {
        setTimeout(function() {
          jeeFrontEnd.backup.getJeedomLog(_autoUpdate, _log)
        }, 1000)
        return
      }
      domUtils.ajax({
        type: 'POST',
        url: 'core/ajax/log.ajax.php',
        data: {
          // Warning get is slow, prefer getDelta in ajax or use jeedom.log.autoUpdateDelta js class
          action: 'get',
          log: _log,
        },
        dataType: 'json',
        global: false,
        error: function(request, status, error) {
          setTimeout(function() {
            jeeFrontEnd.backup.getJeedomLog(_autoUpdate, _log)
          }, 1000)
        },
        success: function(data) {
          if (data.state != 'ok') {
            setTimeout(function() {
              jeeFrontEnd.backup.getJeedomLog(_autoUpdate, _log)
            }, 1000)
            return
          }
          var log = ''
          if (Array.isArray(data.result)) {
            for (var i in data.result.reverse()) {
              log += data.result[i] + "\n"
              if (data.result[i].indexOf('[END ' + _log.toUpperCase() + ' SUCCESS]') != -1) {
                jeedomUtils.showAlert({
                  message: '{{L\'opération est réussie}}',
                  level: 'success'
                })
                if (_log == 'restore') {
                  jeedom.user.refresh()
                }
                document.querySelector('.bt_restoreRepoBackup .fa-sync').unseen()
                _autoUpdate = 0
              }
              if (data.result[i].indexOf('[END ' + _log.toUpperCase() + ' ERROR]') != -1) {
                jeedomUtils.showAlert({
                  message: '{{L\'opération a échoué}}',
                  level: 'danger'
                })
                if (_log == 'restore') {
                  jeedom.user.refresh()
                }
                document.querySelector('.bt_restoreRepoBackup .fa-sync').unseen()
                _autoUpdate = 0
              }
            }
          }
          document.getElementById('pre_backupInfo').innerHTML = log
          if (init(_autoUpdate, 0) == 1) {
            setTimeout(function() {
              jeeFrontEnd.backup.getJeedomLog(_autoUpdate, _log)
            }, 1000)
          } else {
            document.querySelector('#bt_' + _log + 'Jeedom .fa-sync')?.unseen()
            document.querySelectorAll('.bt_' + _log + 'Jeedom .fa-sync').unseen()
            jeeFrontEnd.backup.updateListBackup()
            for (var i in jeephp2js.repoList) {
              jeeFrontEnd.backup.updateRepoListBackup(jeephp2js.repoList[i])
            }
          }
        }
      })
    },
    updateListBackup: function() {
      jeedom.backup.list({
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          var options = ''
          for (var i in data) {
            options += '<option value="' + i + '">' + data[i] + '</option>'
          }
          document.getElementById('sel_restoreBackup').innerHTML = options
        }
      })
    },
    updateRepoListBackup: function(_repo) {
      jeedom.repo.backupList({
        repo: _repo,
        global: false,
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          var options = ''
          if (data.length > 0) {
            for (var i in data) {
              options += '<option value="' + data[i] + '">' + data[i] + '</option>'
            }
          } else {
            document.querySelector('.bt_restoreRepoBackup[data-repo="' + _repo + '"]').addClass('disabled')
          }
          document.querySelector('.sel_restoreCloudBackup[data-repo="' + _repo + '"]').innerHTML = options
        }
      })
    },
    saveBackup: function() {
      jeedomUtils.hideAlert()
      jeedom.config.save({
        configuration: document.getElementById('backup').getJeeValues('.configKey')[0],
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function() {
          jeedom.config.load({
            configuration: document.getElementById('backup').getJeeValues('.configKey')[0],
            plugin: 'core',
            error: function(error) {
              jeedomUtils.showAlert({
                message: error.message,
                level: 'danger'
              })
            },
            success: function(data) {
              document.getElementById('backup').setJeeValues(data, '.configKey')
              jeeFrontEnd.modifyWithoutSave = false
              jeedomUtils.showAlert({
                message: '{{Sauvegarde réussie}}',
                level: 'success'
              })
            }
          })
        }
      })
    },
  }
}

jeeFrontEnd.backup.init()

jeeFrontEnd.backup.postInit()

document.registerEvent('keydown', function(event) {
  if (jeedomUtils.getOpenedModal()) return
  if ((event.ctrlKey || event.metaKey) && event.which == 83) { //s
    event.preventDefault()
    jeeFrontEnd.backup.saveBackup()
  }
})

//Manage events outside parents delegations:
document.getElementById('bt_saveBackup')?.addEventListener('click', function(event) {
  jeeFrontEnd.backup.saveBackup()
})

document.getElementById('bt_restoreJeedom')?.addEventListener('click', function(event) {
  var _target = event.target
  var msg = '{{Êtes-vous sûr de vouloir restaurer}} ' + JEEDOM_PRODUCT_NAME + ' {{avec la sauvegarde}} :<br><b>' + document.getElementById('sel_restoreBackup').value + ' </b> ?'
  msg += '<br> <span class="warning">{{IMPORTANT la restauration d\'un backup est une opération risquée et n\'est à utiliser qu\'en dernier recours}}'
  msg += '<br>{{Une fois lancée cette opération ne peut être annulée.}}</span>'
  jeeDialog.confirm({
    title:  '<span class="warning">{{Restauration de }} ' + JEEDOM_PRODUCT_NAME + '.</span>',
    message: msg
    },
    function(result) {
      if (result) {
        jeedomUtils.hideAlert()
        _target.querySelector('.fa-sync').seen()
        jeedom.backup.restoreLocal({
          backup: document.getElementById('sel_restoreBackup').value,
          error: function(error) {
            jeedomUtils.showAlert({
              message: error.message,
              level: 'danger'
            })
          },
          success: function() {
            jeeFrontEnd.backup.getJeedomLog(1, 'restore')
          }
        })
      }
    }
  )
})

document.getElementById('bt_removeBackup')?.addEventListener('click', function(event) {
  jeeDialog.confirm('{{Êtes-vous sûr de vouloir supprimer la sauvegarde}} :<br><b>' + document.getElementById('sel_restoreBackup').value + '</b> ?', function(result) {
    if (result) {
      jeedom.backup.remove({
        backup: document.getElementById('sel_restoreBackup').value,
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function() {
          jeeFrontEnd.backup.updateListBackup()
          jeedomUtils.showAlert({
            message: '{{Sauvegarde supprimée avec succès}}',
            level: 'success'
          })
        }
      })
    }
  })
})

document.getElementById('bt_downloadBackup')?.addEventListener('click', function(event) {
  window.open('core/php/downloadFile.php?pathfile=' + document.getElementById('sel_restoreBackup').value, "_blank", null)
})

/*Events delegations
*/
document.getElementById('div_pageContainer').addEventListener('click', function(event) {
  var _target = null
  if (_target = event.target.closest('.bt_backupJeedom')) {
    jeeDialog.confirm('{{Êtes-vous sûr de vouloir faire une sauvegarde de}} ' + JEEDOM_PRODUCT_NAME + ' {{? Une fois lancée cette opération ne peut être annulée}}', function(result) {
      if (result) {
        jeedomUtils.hideAlert()
        _target.querySelector('.fa-sync').seen()
        jeedom.backup.backup({
          error: function(error) {
            jeedomUtils.showAlert({
              message: error.message,
              level: 'danger'
            })
          },
          success: function() {
            jeeFrontEnd.backup.getJeedomLog(1, 'backup')
          }
        })
      }
    })
    return
  }

  if (_target = event.target.closest('.bt_uploadCloudBackup')) {
    jeeDialog.confirm('{{Êtes-vous sûr de vouloir envoyer une sauvegarde de}} ' + JEEDOM_PRODUCT_NAME + ' {{sur le cloud ? Une fois lancée cette opération ne peut être annulée}}', function(result) {
      if (result) {
        _target.querySelector('.fa-sync').seen()
        jeedom.backup.uploadCloud({
          backup: document.getElementById('sel_restoreBackup').value,
          error: function(error) {
            jeedomUtils.showAlert({
              message: error.message,
              level: 'danger'
            })
          },
          success: function() {
            jeeFrontEnd.backup.getJeedomLog(1, 'backupCloud')
          }
        })
      }
    })
    return
  }

  if (_target = event.target.closest('.bt_restoreRepoBackup')) {
    jeeDialog.confirm('{{Êtes-vous sûr de vouloir rapatrier la sauvegarde cloud}} :<br><b>' + _target.closest('.repo').querySelector('.sel_restoreCloudBackup').value + '</b> ?', function(result) {
      if (result) {
        _target.querySelector('.fa-sync').seen()
        jeedom.backup.restoreCloud({
          backup: _target.closest('.repo').querySelector('.sel_restoreCloudBackup').value,
          repo: _target.getAttribute('data-repo'),
          error: function(error) {
            jeedomUtils.showAlert({
              message: error.message,
              level: 'danger'
            })
          },
          success: function() {
            jeeFrontEnd.backup.updateListBackup()
            jeedomUtils.showAlert({
              message: '{{Sauvegarde rapatrier avec succès}}',
              level: 'success'
            })
          }
        })
      }
    })
    return
  }
})

document.getElementById('div_pageContainer').addEventListener('change', function(event) {
  if (event.target.matches('.configKey')) {
    jeeFrontEnd.modifyWithoutSave = true
  }
})
