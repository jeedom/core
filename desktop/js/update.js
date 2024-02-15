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

if (!jeeFrontEnd.update) {
  jeeFrontEnd.update = {
    replaceLogLines: ['OK', '. OK', '.OK', 'OK .', 'OK.'],
    regExLogProgress: /\[PROGRESS\]\[(\d.*)]/gm,
    updtDataTable: null,
    osDataTable: null,
    init: function() {
      window.jeeP = this
      this.hasUpdate = false
      this.progress = -2
      this.alertTimeout = null
      this.osUpdateChecked = 0
      //___log interceptor beautifier___
      this.prevUpdateText = ''
      this.newLogClean = '<pre id="pre_updateInfo_clean" style="display:none;"><i>{{Aucune mise à jour en cours.}}</i></pre>'
      this._UpdateObserver_ = null
      this.printUpdate()
    },
    checkAllUpdate: function() {
      jeedomUtils.hideAlert()
      document.getElementById('progressbarContainer').addClass('hidden')
      jeedom.update.checkAll({
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function() {
          jeeP.printUpdate()
        }
      })
    },
    getJeedomLog: function(_autoUpdate, _log) {
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
            jeeP.getJeedomLog(_autoUpdate, _log)
          }, 1000)
        },
        success: function(data) {
          if (data.state != 'ok') {
            setTimeout(function() {
              jeeP.getJeedomLog(_autoUpdate, _log)
            }, 1000)
            return
          }
          var log = ''
          if (Array.isArray(data.result)) {
            for (var i in data.result.reverse()) {
              log += data.result[i] + "\n"
              //Update end success:
              if (data.result[i].indexOf('[END ' + _log.toUpperCase() + ' SUCCESS]') != -1) {
                jeeP.progress = 100
                jeeP.updateProgressBar()
                jeeP.printUpdate()
                if (jeeP.alertTimeout != null) {
                  clearTimeout(jeeP.alertTimeout)
                }
                _autoUpdate = 0
                document.querySelectorAll('.bt_refreshOsPackageUpdate').removeClass('disabled')
                jeedomUtils.reloadPagePrompt('{{Mise(s) à jour terminée(s) avec succès.}}')
              }
              //update error:
              if (data.result[i].indexOf('[END ' + _log.toUpperCase() + ' ERROR]') != -1) {
                jeeP.progress = -3
                jeeP.updateProgressBar()
                jeeP.printUpdate()
                if (jeeP.alertTimeout != null) {
                  clearTimeout(jeeP.alertTimeout)
                }
                jeedomUtils.showAlert({
                  message: '{{L\'opération a échoué. Veuillez aller sur l\'onglet informations et consulter la log pour savoir pourquoi.}}',
                  level: 'danger'
                })
                _autoUpdate = 0
                document.querySelectorAll('.bt_refreshOsPackageUpdate').removeClass('disabled')
              }
            }
          }
          document.getElementById('pre_' + _log + 'Info').innerHTML = log
          if (init(_autoUpdate, 0) == 1) {
            setTimeout(function() {
              jeeP.getJeedomLog(_autoUpdate, _log)
            }, 1000)
          } else {
            document.getElementById('bt_' + _log + 'Jeedom .fa-refresh')?.unseen()
            document.querySelectorAll('.bt_' + _log + 'Jeedom .fa-refresh')?.unseen()
          }
        }
      })
    },
    printUpdate: function() {
      jeedom.update.get({
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          let tbody = document.querySelector('#table_update tbody')
          tbody.empty()
          if (isset(jeeFrontEnd.update.updtDataTable)) jeeFrontEnd.update.updtDataTable.refresh()

          var tr_updates = []
          for (var i in data) {
            if (!isset(data[i].status)) continue
            if (data[i].type == 'core' || data[i].type == 'plugin') {
              tr_updates.push(jeeP.addUpdate(data[i]))
            }
          }
          for (var tr of tr_updates) {
            tbody.appendChild(tr)
          }
          jeedomUtils.initTooltips(tbody)

          if (isset(jeeFrontEnd.update.updtDataTable)) jeeFrontEnd.update.updtDataTable.refresh()

          jeedomUtils.initDataTables('#coreplugin', false, true)
          jeeFrontEnd.update.updtDataTable = document.querySelector('#table_update')._dataTable

          jeeFrontEnd.update.updtDataTable.on('columns.sort', function(column, direction) {
            let tbody = document.querySelector('#table_update tbody')
            tbody.prepend(tbody.querySelector('tr[data-type="core"]'))
          })
          jeeFrontEnd.update.updtDataTable.columns().sort(0, 'desc')


          if (jeeP.hasUpdate) {
            document.querySelector('li a[data-target="#coreplugin"] i').style.color = 'var(--al-warning-color)'
          } else {
            document.querySelector('li a[data-target="#coreplugin"] i').style.color = 'var(--al-info-color)'
          }

          //create a second <pre> for cleaned text to avoid change event infinite loop:
          if (document.getElementById('pre_updateInfo_clean') == null) {
            document.getElementById('div_log').insertAdjacentHTML('beforeend', jeeP.newLogClean)
            document.getElementById('pre_updateInfo').addClass('hidden')
            jeeP._pre_updateInfo_clean = document.getElementById('pre_updateInfo_clean')
            jeeP._pre_updateInfo_clean.seen()
            jeeP.createUpdateObserver()
            jeedomUtils.setCheckContextMenu()
          }
        }
      })

      jeedom.config.load({
        configuration: {
          "update::lastCheck": 0
        },
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          let span = document.getElementById('span_lastUpdateCheck')
          span.title = '{{Dernière verification des mises à jour}}'
          span.jeeValue(data['update::lastCheck'])
        }
      })
    },
    addUpdate: function(_update) {
      if (init(_update.status) == '') {
        _update.status = 'OK'
      }
      _update.status = _update.status.toUpperCase()
      var labelClass = 'label-success'
      if (_update.status == 'UPDATE') {
        labelClass = 'label-warning'
        if (_update.type == 'core' || _update.type == 'plugin') {
          if (!_update.configuration.hasOwnProperty('doNotUpdate') || _update.configuration.doNotUpdate == '0') jeeP.hasUpdate = true
        }
      }

      var tr = '<tr>'
      tr += '<td style="width:40px"><span class="updateAttr label ' + labelClass + '" data-l1key="status"></span></td>'
      tr += '<td>'
      tr += '<span class="hidden-1280"><span class="updateAttr" data-l1key="source"></span> / <span class="updateAttr" data-l1key="type"></span> : </span>'
      if (_update.name == 'jeedom') {
        tr += '<span class="updateAttr label label-info text-capitalize" data-l1key="name"></span>'
        if (_update.branch) {
          var updClass
          switch (_update.branch.toLowerCase()) {
            case 'alpha':
              updClass = 'label-danger'
              break
            case 'beta':
              updClass = 'label-warning'
              break
            default:
              updClass = 'label-success'
          }
          tr += ' <span class="label ' + updClass + ' hidden-992">' + _update.branch + '</span>'
        }
      }
      else {
        tr += '<span class="label label-info"><span class="updateAttr text-capitalize" data-l1key="plugin" data-l2key="name"></span> (<span class="updateAttr" data-l1key="name"></span>)</span>'
        if (_update.configuration && _update.configuration.version) {
          var updClass
          switch (_update.configuration.version.toLowerCase()) {
            case 'stable':
            case 'master':
              updClass = 'label-success'
              break
            case 'beta':
              updClass = 'label-warning'
              break
            default:
              updClass = 'label-danger'
          }
          if (typeof _update.configuration.user!== 'undefined'){
            tr += ' <span class="label ' + updClass + ' hidden-992">' + _update.configuration.version +' - '+ _update.configuration.user + '</span>'
          } else {
            tr += ' <span class="label ' + updClass + ' hidden-992">' + _update.configuration.version + '</span>'
          }
        }
      }
      tr += '<span class="hidden">' + _update.name + '</span><span class="updateAttr hidden" data-l1key="id"></span>'

      if (_update.localVersion !== null && _update.localVersion.length > 19) _update.localVersion = _update.localVersion.substring(0, 16) + '...'
      if (_update.remoteVersion !== null && _update.remoteVersion.length > 19) _update.remoteVersion = _update.remoteVersion.substring(0, 16) + '...'
      if (_update.updateDate == null) {
        _update.updateDate = 'N/A'
      }

      tr += '</td>'
      tr += '<td style="width:160px;"><span class="label label-primary" data-l1key="localVersion">' + _update.localVersion + '</span></td>'
      tr += '<td style="width:160px;"><span class="label label-primary" data-l1key="remoteVersion">' + _update.remoteVersion + '</span></td>'
      tr += '<td style="width:160px;"><span class="label label-primary" data-l1key="updateDate">' + _update.updateDate + '</span></td>'
      tr += '<td>'
      if (_update.type != 'core') {
        tr += '<i class="fas fa-pencil-ruler" title="{{Ne pas mettre à jour}}"></i> <input id="' + _update.name + '" type="checkbox" class="updateAttr checkContext warning" data-l1key="configuration" data-l2key="doNotUpdate" title="{{Sauvegarder pour conserver les modifications}}">'
        tr += '<label class="cursor fontweightnormal hidden-1280" for="' + _update.name + '"></label>'
      }
      tr += '</td>'
      tr += '<td>'
      if (_update.type != 'core') {
        if (_update.configuration && _update.configuration.version == 'beta') {
          if (isset(_update.plugin) && isset(_update.plugin.changelog_beta) && _update.plugin.changelog_beta != '') {
            tr += '<a class="btn btn-xs cursor" target="_blank" href="' + _update.plugin.changelog_beta + '"><i class="fas fa-book"></i><span class="hidden-1280"> {{Changelog}}</span></a> '
          } else {
            tr += '<a class="btn btn-xs disabled"><i class="fas fa-book"></i><span class="hidden-1280"> {{Changelog}}</span></a> '
          }
        } else {
          if (isset(_update.plugin) && isset(_update.plugin.changelog) && _update.plugin.changelog != '') {
            tr += '<a class="btn btn-xs cursor" target="_blank" href="' + _update.plugin.changelog + '"><i class="fas fa-book"></i><span class="hidden-1280"> {{Changelog}}</span></a> '
          } else {
            tr += '<a class="btn btn-xs disabled"><i class="fas fa-book"></i><span class="hidden-1280"> {{Changelog}}</span></a> '
          }
        }
      } else {
        tr += '<a class="btn btn-xs" id="bt_changelogCore"><i class="fas fa-book"></i><span class="hidden-1280"> {{Changelog}}</span></a> '
      }
      if (_update.type != 'core') {
        if (_update.status == 'UPDATE') {
          if (!_update.configuration.hasOwnProperty('doNotUpdate') || _update.configuration.doNotUpdate == '0') {
            tr += '<a class="btn btn-warning btn-xs update"><i class="fas fa-sync"></i><span class="hidden-1280"> {{Mettre à jour}}</span></a> '
          } else {
            tr += '<a class="btn btn-warning btn-xs update disabled"><i class="fas fa-sync"></i><span class="hidden-1280"> {{Mettre à jour}}</span></a> '
          }
        } else {
          if (!_update.configuration.hasOwnProperty('doNotUpdate') || _update.configuration.doNotUpdate == '0') {
            tr += '<a class="btn btn-warning btn-xs update"><i class="fas fa-sync"></i><span class="hidden-1280"> {{Réinstaller}}</span></a> '
          } else {
            tr += '<a class="btn btn-warning btn-xs update disabled"><i class="fas fa-sync"></i><span class="hidden-1280"> {{Réinstaller}}</span></a> '
          }
        }
      }
      if (_update.type != 'core') {
        tr += '<a class="btn btn-danger btn-xs remove"><i class="far fa-trash-alt"></i><span class="hidden-1280"> {{Supprimer}}</span></a> '
      }
      tr += '<a class="btn btn-info btn-xs checkUpdate"><i class="fas fa-check"></i><span class="hidden-1280"> {{Vérifier}}</span></a>'
      tr += '</td>'
      tr += '</tr>'
      let newRow = document.createElement('tr')
      newRow.innerHTML = tr
      newRow.setAttribute('data-id', init(_update.id))
      newRow.setAttribute('data-logicalId', init(_update.logicalId))
      newRow.setAttribute('data-type', init(_update.type))
      newRow.setJeeValues(_update, '.updateAttr')
      return newRow
    },
    updateProgressBar: function() {
      let progressBar = document.getElementById('div_progressbar')
      if (jeeP.progress == -4) {
        progressBar.removeClass('active progress-bar-info progress-bar-success progress-bar-danger')
          .addClass('progress-bar-warning')
        return
      }
      if (jeeP.progress == -3) {
        progressBar.removeClass('active progress-bar-info progress-bar-success progress-bar-warning')
          .addClass('progress-bar-danger')
        return
      }
      if (jeeP.progress == -2) {
        progressBar.removeClass('active progress-bar-info progress-bar-success progress-bar-danger progress-bar-warning')
        progressBar.style.width = '0'
        progressBar.setAttribute('aria-valuenow', '0')
        progressBar.innerHTML = '0%'
        return
      }
      if (jeeP.progress == -1) {
        progressBar.removeClass('progress-bar-success progress-bar-danger progress-bar-warning')
          .addClass('active progress-bar-info')
        progressBar.style.width = '100%'
        progressBar.setAttribute('aria-valuenow', '100')
        progressBar.innerHTML = 'N/A'
        return
      }
      if (jeeP.progress == 100) {
        progressBar.removeClass('active progress-bar-info progress-bar-danger progress-bar-warning')
          .addClass('progress-bar-success')
        progressBar.style.width = jeeP.progress + '%'
        progressBar.setAttribute('aria-valuenow', jeeP.progress)
        progressBar.innerHTML = jeeP.progress + '%'
        return
      }
      progressBar.removeClass('progress-bar-success progress-bar-danger progress-bar-warning')
        .addClass('active progress-bar-info')
      progressBar.style.width = jeeP.progress + '%'
      progressBar.setAttribute('aria-valuenow', jeeP.progress)
      progressBar.innerHTML = jeeP.progress + '%'
    },
    alertTimeout: function() {
      jeeP.progress = -4
      this.updateProgressBar()
      jeedomUtils.showAlert({
        message: '{{La mise à jour semble être bloquée (pas de changement depuis 10min. Vérifiez le log)}}',
        level: 'warning'
      })
    },
    //listen change in log to update cleaned one:
    createUpdateObserver: function() {
      this._UpdateObserver_ = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
          if (mutation.type == 'childList' && mutation.removedNodes.length >= 1) {
            jeeFrontEnd.update.cleanUpdateLog()
          }
        })
      })

      this.observerConfig = {
        attributes: true,
        childList: true,
        characterData: true,
        subtree: true
      }

      var targetNode = document.getElementById('pre_updateInfo')
      if (targetNode) this._UpdateObserver_.observe(targetNode, this.observerConfig)
    },
    cleanUpdateLog: function() {
      var currentUpdateText = document.getElementById('pre_updateInfo').innerHTML
      if (currentUpdateText == '') return false
      if (this.prevUpdateText == currentUpdateText) return false
      var lines = currentUpdateText.split("\n")
      var l = lines.length

      //update progress bar and clean text!
      var linesRev = lines.slice().reverse()
      for (var i = 0; i < l; i++) {
        var regExpResult = this.regExLogProgress.exec(linesRev[i])
        if (regExpResult !== null) {
          jeeP.progress = regExpResult[1]
          jeeP.updateProgressBar()
          break
        }
      }

      var newLogText = ''
      for (var i = 0; i < l; i++) {
        var line = lines[i]
        if (line == '') continue
        if (line.startsWith('[PROGRESS]')) line = ''

        //check ok at end of line:
        if (line.endsWith('OK')) {
          var matches = line.match(/[. ]{1,}OK/g)
          if (matches) {
            line = line.replace(matches[0], '')
            line += ' | OK'
          } else {
            line = line.replace('OK', ' | OK')
          }
        }

        //remove points ...
        matches = line.match(/[.]{2,}/g)
        if (matches) {
          matches.forEach(function(match) {
            line = line.replace(match, '')
          })
        }
        line = line.trim()

        //check ok on next line, escaping progress inbetween:
        var offset = 1
        if (lines[i + 1].startsWith('[PROGRESS]')) {
          var offset = 2
        }
        var nextLine = lines[i + offset]
        var letters = /^[0-9a-zA-Z]+$/
        if (!nextLine.replace('OK', '').match(letters)) {
          matches = nextLine.match(/[.]{2,}/g)
          if (matches) {
            matches.forEach(function(match) {
              nextLine = nextLine.replace(match, '')
            })
          }
        }
        nextLine = nextLine.trim()
        if (this.replaceLogLines.includes(nextLine)) {
          line += ' | OK'
          lines[i + offset] = ''
        }

        if (line != '') {
          newLogText += line + '\n'
          this._pre_updateInfo_clean.innerHTML = newLogText
          if (document.querySelector('[data-target="#log"]').parentNode.hasClass('active')) {
            document.getElementById('log').parentNode.scrollTop = 1E10
          }
          this.prevUpdateText = currentUpdateText
          if (jeeP.progress == 100) {
            if (this._UpdateObserver_) this._UpdateObserver_.disconnect()
          }
        }
      }
      clearTimeout(jeeP.alertTimeout)
      jeeP.alertTimeout = setTimeout(jeeP.alertTimeout, 60000 * 10)
    },
    //packages updates:
    printOsUpdate: function(_forceRefresh) {
      this.osUpdateChecked = 1
      jeedom.systemGetUpgradablePackage({
        type: 'all',
        forceRefresh: _forceRefresh,
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          document.querySelectorAll('#os .bt_OsPackageUpdate').addClass('disabled')

          var osTable = document.getElementById('table_osUpdate')
          osTable.tBodies[0].empty()

          var tr_updates = []
          for (var type of Object.keys(data)) { //apt pip2 pip3
            var OSPackages = Object.keys(data[type])
            if (OSPackages.length > 0) {
              document.querySelector('#os .bt_OsPackageUpdate[data-type="' + type + '"]').removeClass('disabled')
              for (var OSPackage of OSPackages) {
                tr_updates.push(jeeFrontEnd.update.addOsUpdate(data[type][OSPackage]))
              }
            }

          }

          for (var tr of tr_updates) {
            osTable.tBodies[0].appendChild(tr)
          }

          if (osTable._dataTable) {
            osTable._dataTable.refresh()
          } else {
            jeeFrontEnd.update.osDataTable = new DataTable(osTable, {
              columns: [
                { select: 0, sort: "asc" }
              ],
              paging: false,
              searchable: true
            })
          }
        }
      })
    },
    addOsUpdate: function(_update) {
      var tr = '<tr>'
      tr += '<td>'
      tr += '<span class="osUpdateAttr" data-l1key="type"></span>'
      tr += '</td>'
      tr += '<td>'
      tr += '<span class="osUpdateAttr" data-l1key="name"></span>'
      tr += '</td>'
      tr += '<td style="width:160px;"><span class="label label-primary" data-l1key="localVersion">' + _update.current_version + '</span></td>'
      tr += '<td style="width:160px;"><span class="label label-primary" data-l1key="remoteVersion">' + _update.new_version + '</span></td>'
      tr += '</tr>'
      let newRow = document.createElement('tr')
      newRow.innerHTML = tr
      newRow.setAttribute('data-logicalId', init(_update.name))
      newRow.setAttribute('data-type', init(_update.type))
      newRow.setJeeValues(_update, '.osUpdateAttr')
      return newRow
    },
    //modal update:
    getUpdateModal: function() {
      jeeDialog.dialog({
        id: 'md_update',
        title: "{{Options de mise à jour}}",
        width: window.innerWidth > 600 ? 580 : window.innerWidth,
        height: window.innerHeight > 500 ? 440 : window.innerHeight - 80,
        top: window.innerHeight > 500 ? 120 : 0,
        callback: function() {
          var contentEl = jeeDialog.get('#md_update', 'content')
          if (contentEl.querySelector('#md_specifyUpdate') == null) {
            var newContent = document.getElementById('md_specifyUpdate-template').cloneNode(true)
            newContent.setAttribute('id', 'md_specifyUpdate')
            contentEl.appendChild(newContent)
            newContent.querySelectorAll('[data-title]').forEach(_tooltip => {
              _tooltip.setAttribute('title', _tooltip.getAttribute('data-title'))
              _tooltip.removeAttribute('data-title')
            })
            jeedomUtils.initTooltips(document.getElementById('md_update'))
          }

          contentEl.querySelector('input[data-l1key="force"]').addEventListener('click', function(event) {
            let mdSpec = document.getElementById('md_update')
            let check_backupBefore = mdSpec.querySelector('.updateOption[data-l1key="backup::before"]')
            if (event.target.checked) {
              check_backupBefore.setAttribute('data-state', check_backupBefore.checked)
              check_backupBefore.checked = false
              check_backupBefore.setAttribute('disabled', null)
            } else {
              check_backupBefore.checked = getBool(check_backupBefore.getAttribute('data-state'))
              check_backupBefore.removeAttribute('disabled')
            }
          })

          contentEl.querySelector('.bt_changelogCore').addEventListener('click', function(event) {
            document.getElementById('bt_changelogCore').triggerEvent('click')
          })
        },
        onShown: function() {
          jeeDialog.get('#md_update', 'content').querySelector('#md_specifyUpdate').removeClass('hidden')
        },
        buttons: {
          confirm: {
            label: '{{Mettre à jour}}',
            className: 'success',
            callback: {
              click: function(event) {
                var options = document.getElementById('md_specifyUpdate').getJeeValues('.updateOption')[0]
                jeedomUtils.hideAlert()
                jeeDialog.get('#md_update').hide()
                jeeP.progress = 0
                document.getElementById('progressbarContainer').removeClass('hidden')
                document.querySelector('.bt_refreshOsPackageUpdate').addClass('disabled')
                jeeP.updateProgressBar()
                jeedom.update.doAll({
                  options: options,
                  error: function(error) {
                    jeedomUtils.showAlert({
                      message: error.message,
                      level: 'danger'
                    })
                  },
                  success: function() {
                    jeeP.getJeedomLog(1, 'update')
                  }
                })
              }
            }
          },
          cancel: {
            className: 'hidden'
          }
        },
      })
    }
  }
}

jeeFrontEnd.update.init()

if (jeephp2js.isUpdating == '1') {
  jeedomUtils.hideAlert()
  jeeP.progress = 7
  document.getElementById('progressbarContainer').removeClass('hidden')
  document.querySelector('.bt_refreshOsPackageUpdate').addClass('disabled')
  jeeP.updateProgressBar()
  jeeP.getJeedomLog(1, 'update')
}

/*Events delegations
*/
document.getElementById('div_pageContainer').addEventListener('click', function(event) {
  var _target = null
  if (_target = event.target.closest('#bt_checkAllUpdate')) {
    if (!document.querySelector('a[data-target="#coreplugin"]').hasClass('active')) document.querySelector('a[data-target="#coreplugin"]').click()
    jeeP.checkAllUpdate()
    return
  }

  if (_target = event.target.closest('#bt_updateJeedom')) {
    jeeP.getUpdateModal()
    return
  }

  if (_target = event.target.closest('#bt_changelogCore')) {
    jeedom.getDocumentationUrl({
      page: 'changelog',
      theme: document.body.getAttribute('data-theme'),
      error: function(error) {
        jeedomUtils.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function(url) {
        window.open(url, '_blank')
      }
    })
    return
  }

  if (_target = event.target.closest('#table_update input[data-l2key="doNotUpdate"]')) {
    _target._tippy.show()
    setTimeout(() => { _target._tippy.hide() }, 1500)
    if (_target.checked) {
      _target.closest('tr').querySelector('a.btn.update').addClass('disabled')
    } else {
      _target.closest('tr').querySelector('a.btn.update').removeClass('disabled')
    }
    return
  }

  if (_target = event.target.closest('#table_update .update')) {
    if (_target.hasClass('disabled')) return
    var id = _target.closest('tr').getAttribute('data-id')
    var logicalId = _target.closest('tr').getAttribute('data-logicalid')
    jeeDialog.confirm('{{Êtes-vous sûr de vouloir mettre à jour :}}' + ' ' + logicalId + ' ?', function(result) {
      if (result) {
        jeeP.progress = -1
        document.getElementById('progressbarContainer').removeClass('hidden')
        document.querySelector('.bt_refreshOsPackageUpdate').addClass('disabled')
        jeeP.updateProgressBar()
        jeedomUtils.hideAlert()
        jeedom.update.do({
          id: id,
          error: function(error) {
            jeedomUtils.showAlert({
              message: error.message,
              level: 'danger'
            })
          },
          success: function() {
            jeeP.getJeedomLog(1, 'update')
          }
        })
      }
    })
    return
  }

  if (_target = event.target.closest('#table_update .remove')) {
    var id = _target.closest('tr').getAttribute('data-id')
    var logicalId = _target.closest('tr').getAttribute('data-logicalid')
    jeeDialog.confirm('{{Êtes-vous sûr de vouloir supprimer :}}' + ' ' + logicalId + ' ?', function(result) {
      if (result) {
        jeedomUtils.hideAlert()
        jeedom.update.remove({
          id: id,
          error: function(error) {
            jeedomUtils.showAlert({
              message: error.message,
              level: 'danger'
            })
          },
          success: function() {
            jeeP.printUpdate()
          }
        })
      }
    })
    return
  }

  if (_target = event.target.closest('#table_update .checkUpdate')) {
    var id = _target.closest('tr').getAttribute('data-id')
    jeedomUtils.hideAlert()
    jeedom.update.check({
      id: id,
      error: function(error) {
        jeedomUtils.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function() {
        jeeP.printUpdate()
      }
    })
    return
  }

  if (_target = event.target.closest('#bt_saveUpdate')) {
    jeedom.update.saves({
      updates: document.querySelectorAll('#table_update tbody tr').getJeeValues('.updateAttr'),
      error: function(error) {
        jeedomUtils.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function(data) {
        jeedomUtils.loadPage('index.php?v=d&p=update&saveSuccessFull=1')
      }
    })
    return
  }

  if (_target = event.target.closest('.bt_refreshOsPackageUpdate')) {
    if (jeeP.osUpdateChecked == 0 || _target.getAttribute('data-forceRefresh') == "1") {
      jeeP.printOsUpdate(_target.getAttribute('data-forceRefresh'))
    }
    return
  }

  if (_target = event.target.closest('.bt_OsPackageUpdate')) {
    if (_target.getAttribute('disabled')) {
      return
    }
    let type = _target.getAttribute('data-type')
    jeeDialog.confirm('{{Êtes-vous sûr de vouloir mettre à jour les packages de type}}' + ' : ' + type + ' ? {{Attention cette opération est toujours risquée et peut prendre plusieurs dizaines de minutes}}.', function(result) {
      if (!result) {
        return
      }
      jeedom.systemUpgradablePackage({
        type: type,
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          jeedomUtils.showAlert({
            message: '{{Mise à jour lancée avec succès.}}',
            level: 'success'
          })
          jeeDialog.dialog({
            id: 'jee_modal',
            title: "{{Log de mise à jour}}",
            contentUrl: 'index.php?v=d&modal=log.display&log=packages'
          })
        }
      })
    })
    return
  }
})
