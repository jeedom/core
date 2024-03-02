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

if (!jeeFrontEnd.administration) {
  jeeFrontEnd.administration = {
    configReload: null,
    init: function() {
      this.actionOptions = []
      window.jeeP = this
      domUtils.showLoading()

      this.loadConfig()
      this.printObjectSummary()
      this.printConvertColor()

      jeedomUtils.dateTimePickerInit()
      jeedomUtils.initSpinners()
      jeedomUtils.setCheckContextMenu()
      setTimeout(function() {
        jeedomUtils.initTooltips()
        jeeFrontEnd.modifyWithoutSave = false
      }, 500)
    },
    initSearchLinks: function() {
      document.querySelectorAll('#searchResult a[role="searchTabLink"]').forEach(_search => {
        _search.addEventListener('click', function(event) {
          let tabId = event.target.closest('a').getAttribute('data-target')
          document.getElementById('in_searchConfig').jeeValue('').triggerEvent('keyup')
          document.querySelector('a[data-target="' + tabId + '"]')?.triggerEvent('click')
        })
      })
    },
    //-> summary
    printObjectSummary: function() {
      domUtils.ajax({
        type: "POST",
        url: "core/ajax/config.ajax.php",
        data: {
          action: "getKey",
          key: 'object:summary'
        },
        dataType: 'json',
        global: false,
        error: function(request, status, error) {
          handleAjaxError(request, status, error)
        },
        success: function(data) {
          if (data.state != 'ok') {
            jeedomUtils.showAlert({
              message: data.result,
              level: 'danger'
            })
            return
          }
          var tbody = document.getElementById('table_objectSummary').tBodies[0]
          if (Sortable.get(tbody)) Sortable.get(tbody).destroy()
          tbody.empty()
          for (var i in data.result) {
            if (isset(data.result[i].key) && data.result[i].key == '') continue
            if (!isset(data.result[i].name)) continue
            if (!isset(data.result[i].key)) {
              data.result[i].key = i.toLowerCase().stripAccents().replace(/\_/g, '').replace(/\-/g, '').replace(/\&/g, '').replace(/\s/g, '')
            }
            jeeP.addObjectSummary(data.result[i], false)
          }
          //Set sortable:
          Sortable.create(document.getElementById('table_objectSummary').tBodies[0], {
            delay: 100,
            delayOnTouchOnly: true,
            draggable: 'tr.objectSummary',
            filter: 'a, input, textarea',
            preventOnFilter: false,
            direction: 'vertical',
            removeCloneOnHide: true,
            onEnd: function(event) {
              jeeFrontEnd.modifyWithoutSave = true
            },
          })
          jeeFrontEnd.modifyWithoutSave = false
        }
      })
    },
    addObjectSummary: function(_summary, _change) {
      let tr = '<tr>'
      tr += '<td><input class="objectSummaryAttr form-control input-sm" data-l1key="key" /></td>'

      tr += '<td><input class="objectSummaryAttr form-control input-sm" data-l1key="name" /></td>'

      tr += '<td><select class="form-control objectSummaryAttr input-sm" data-l1key="calcul">'
      tr += '<option value="sum">{{Somme}}</option>'
      tr += '<option value="avg">{{Moyenne}}</option>'
      tr += '<option value="text">{{Texte}}</option>'
      tr += '</select></td>'

      tr += '<td><a class="objectSummaryAction btn btn-sm" data-l1key="chooseIcon"><i class="fas fa-flag"></i><span class="hidden-1280"> {{Icône}}</span></a>'
      tr += '<span class="objectSummaryAttr summIconContainer" data-l1key="icon"></span></td>'

      tr += '<td><a class="objectSummaryAction btn btn-sm" data-l1key="chooseIconNul"><i class="fas fa-flag"></i><span class="hidden-1280"> {{Icône}}</span></a>'
      tr += '<span class="objectSummaryAttr summIconContainer" data-l1key="iconnul"></span></td>'

      tr += '<td><input class="objectSummaryAttr form-control input-sm" data-l1key="unit" /></td>'

      tr += '<td class="center"><input type="checkbox" class="objectSummaryAttr checkContext warning" data-l1key="hidenumber" /></td>'

      tr += '<td class="center"><input type="checkbox" class="objectSummaryAttr checkContext" data-l1key="hidenulnumber" /></td>'

      tr += '<td><select class="objectSummaryAttr input-sm" data-l1key="count">'
      tr += '<option value="">{{Aucun}}</option>'
      tr += '<option value="binary">{{Binaire}}</option>'
      tr += '</select></td>'

      tr += '<td class="center"><input type="checkbox" class="objectSummaryAttr checkContext" data-l1key="allowDisplayZero" /></td>'

      tr += '<td class="center"><input class="objectSummaryAttr form-control input-sm" data-l1key="ignoreIfCmdOlderThan" /></td>'
      tr += ''
      tr += '<td>'
      if (isset(_summary) && isset(_summary.key) && _summary.key != '') {
        tr += '<a class="btn btn-success btn-sm objectSummaryAction" data-l1key="createVirtual"><i class="fas fa-puzzle-piece"></i><span class="hidden-1280"> {{Créer virtuel}}</span></a>'
      }
      tr += '</td>'

      tr += '<td><a class="objectSummaryAction cursor" data-l1key="remove"><i class="fas fa-minus-circle"></i></a></td>'

      tr += '</tr>'

      let newTr = document.createElement('tr')
      newTr.innerHTML = tr
      newTr.addClass('objectSummary')
      if (isset(_summary)) {
        newTr.setJeeValues(_summary, '.objectSummaryAttr')
      }
      if (isset(_summary) && isset(_summary.key) && _summary.key != '') {
        newTr.querySelector('.objectSummaryAttr[data-l1key="key"]').disabled = true
      }
      document.getElementById('table_objectSummary').tBodies[0].appendChild(newTr)
      if (!isset(_change) || _change === true) jeeFrontEnd.modifyWithoutSave = true
    },
    saveObjectSummary: function() {
      var summary = {}
      var temp = document.getElementById('table_objectSummary').tBodies[0].childNodes.getJeeValues('.objectSummaryAttr')
      for (var i in temp) {
        if (temp[i].key == '') {
          temp[i].key = temp[i].name
        }
        temp[i].key = temp[i].key.toLowerCase().stripAccents().replace(/\_/g, '').replace(/\-/g, '').replace(/\&/g, '').replace(/\%/g, '').replace(/\s/g, '').replace(/\./g, '')
        summary[temp[i].key] = temp[i]
      }
      var value = {
        'object:summary': summary
      }
      domUtils.ajax({
        type: "POST",
        url: "core/ajax/config.ajax.php",
        data: {
          action: 'addKey',
          value: JSON.stringify(value)
        },
        dataType: 'json',
        noDisplayError: true,
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          if (data.state != 'ok') {
            jeedomUtils.showAlert({
              message: data.result,
              level: 'danger'
            })
          }
        }
      })
    },
    //-> action on message
    loadActionOnMessage: function() {
      document.querySelectorAll('.bt_addActionOnMessage').forEach(_button => {
        let channel = _button.getAttribute('data-channel')
        document.getElementById('div_actionOnMessage' + channel).empty()
        jeedom.config.load({
          configuration: 'actionOnMessage' + channel,
          error: function(error) {
            jeedomUtils.showAlert({
              message: error.message,
              level: 'danger'
            })
          },
          success: function(data) {
            if (data == '' || typeof data != 'object') return
            jeeP.actionOptions = []
            for (var i in data) {
              jeeP.addActionOnMessage(data[i], channel)
            }
            jeedom.cmd.displayActionsOption({
              params: jeeP.actionOptions,
              async: false,
              error: function(error) {
                jeedomUtils.showAlert({
                  message: error.message,
                  level: 'danger'
                })
              },
              success: function(data) {
                for (var i in data) {
                  document.getElementById(data[i].id).html(data[i].html.html)
                }
                jeedomUtils.taAutosize()
                jeedomUtils.initTooltips()
              }
            })
          }
        })
      })
    },
    addActionOnMessage: function(_action, _channel) {
      if (!isset(_channel)) _channel = ''
      if (!isset(_action)) _action = {}
      if (!isset(_action.options)) _action.options = {}

      let actionOption_id = jeedomUtils.uniqId()
      let div = '<div class="expression actionOnMessage">'
      div += '<input class="expressionAttr" data-l1key="type" style="display : none;" value="action">'
      div += '<div class="form-group ">'
      div += '<label class="col-sm-2 control-label">Action</label>'
      div += '<div class="col-sm-1">'
      div += '<input type="checkbox" class="expressionAttr" data-l1key="options" data-l2key="enable" checked title="{{Décocher pour désactiver l\'action}}" />'
      div += '<input type="checkbox" class="expressionAttr" data-l1key="options" data-l2key="background" title="{{Cocher pour que la commande s\'éxecute en parallèle des autres actions}}" />'
      div += '</div>'
      div += '<div class="col-sm-4">'
      div += '<div class="input-group">'
      div += '<span class="input-group-btn">'
      div += '<a class="btn btn-default bt_removeAction btn-sm roundedLeft"><i class="fas fa-minus-circle"></i></a>'
      div += '</span>'
      div += '<input class="expressionAttr form-control input-sm cmdAction" data-l1key="cmd" />'
      div += '<span class="input-group-btn">'
      div += '<a class="btn btn-default btn-sm listAction" title="{{Sélectionner un mot-clé}}"><i class="fas fa-tasks"></i></a>'
      div += '<a class="btn btn-default btn-sm listCmdAction roundedRight" title="{{Sélectionner la commande}}"><i class="fas fa-list-alt"></i></a>'
      div += '</span>'
      div += '</div>'
      div += '</div>'
      div += '<div class="col-sm-5 actionOptions" id="' + actionOption_id + '"></div>'
      div += '</div>'

      let newDiv = document.createElement('div')
      newDiv.innerHTML = div
      newDiv.setJeeValues(_action, '.expressionAttr')
      document.getElementById('div_actionOnMessage' + _channel).appendChild(newDiv.childNodes[0])

      jeeP.actionOptions.push({
        expression: init(_action.cmd, ''),
        options: _action.options,
        id: actionOption_id
      })

      jeedom.scenario.setAutoComplete({
        parent: document.getElementById('div_actionOnMessage' + _channel),
        type: 'cmd'
      })
    },
    //-> cache
    flushCache: function() {
      jeedom.cache.flush({
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          jeeP.updateCacheStats()
          jeedomUtils.showAlert({
            message: '{{Cache vidé}}',
            level: 'success'
          })
        }
      })
    },
    flushWidgetCache: function() {
      jeedom.cache.flushWidget({
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          jeeP.updateCacheStats()
          jeedomUtils.showAlert({
            message: '{{Cache vidé}}',
            level: 'success'
          })
        }
      })
    },
    cleanCache: function() {
      jeedom.cache.clean({
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          jeeP.updateCacheStats()
          jeedomUtils.showAlert({
            message: '{{Cache nettoyé}}',
            level: 'success'
          })
        }
      })
    },
    updateCacheStats: function() {
      jeedom.cache.stats({
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          document.getElementById('span_cacheObject').innerHTML = data.count
        }
      })
    },
    //-> color convertion
    printConvertColor: function() {
      domUtils.ajax({
        type: "POST",
        url: "core/ajax/config.ajax.php",
        data: {
          action: "getKey",
          key: 'convertColor'
        },
        dataType: 'json',
        global: false,
        error: function(request, status, error) {
          handleAjaxError(request, status, error)
        },
        success: function(data) {
          if (data.state != 'ok') {
            jeedomUtils.showAlert({
              message: data.result,
              level: 'danger'
            })
            return
          }
          document.getElementById('table_convertColor').tBodies[0].empty()
          for (var color in data.result) {
            jeeP.addConvertColor(color, data.result[color], false)
          }
        }
      })
    },
    addConvertColor: function(_color, _html, _change) {
      let tr = '<tr>'
      tr += '<td>'
      tr += '<input class="color form-control input-sm" value="' + init(_color) + '"/>'
      tr += '</td>'
      tr += '<td>'
      tr += '<input type="color" class="html form-control input-sm" value="' + init(_html) + '" />'
      tr += '</td>'
      tr += '<td>'
      tr += '<i class="fas fa-minus-circle removeConvertColor cursor"></i>'
      tr += '</td>'
      tr += '</tr>'
      document.getElementById('table_convertColor').tBodies[0].insertAdjacentHTML('beforeend', tr)
      if (!isset(_change) || _change === true) jeeFrontEnd.modifyWithoutSave = true
    },
    saveConvertColor: function() {
      var value = {}
      var colors = {}
      document.querySelectorAll('#table_convertColor tbody tr').forEach(function(element) {
        colors[element.querySelector('.color').jeeValue()] = element.querySelector('.html').jeeValue()
      })
      value.convertColor = colors
      domUtils.ajax({
        type: "POST",
        url: "core/ajax/config.ajax.php",
        data: {
          action: 'addKey',
          value: JSON.stringify(value)
        },
        dataType: 'json',
        global: false,
        error: function(request, status, error) {
          handleAjaxError(request, status, error)
        },
        success: function(data) {
          if (data.state != 'ok') {
            jeedomUtils.showAlert({
              message: data.result,
              level: 'danger'
            })
            return
          }
          jeeFrontEnd.modifyWithoutSave = false
        }
      })
    },
    //Global:
    loadConfig: function() {
      jeedom.config.load({
        configuration: document.getElementById('config').getJeeValues('.configKey:not(.noSet)')[0],
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          document.getElementById('config').setJeeValues(data, '.configKey')
          //document.querySelector('.configKey[data-l1key="market::allowDNS"]').triggerEvent('change')
          //document.querySelector('.configKey[data-l1key="ldap:enable"]').triggerEvent('change')
          jeeP.loadActionOnMessage()

          if (!jeedom.theme['interface::background::dashboard'].includes('/data/backgrounds/config_dashboard')) document.querySelector('a.bt_removeBackgroundImage[data-page="dashboard"]').addClass('disabled')
          if (!jeedom.theme['interface::background::analysis'].includes('/data/backgrounds/config_analysis')) document.querySelector('a.bt_removeBackgroundImage[data-page="analysis"]').addClass('disabled')
          if (!jeedom.theme['interface::background::tools'].includes('/data/backgrounds/config_tools')) document.querySelector('a.bt_removeBackgroundImage[data-page="tools"]').addClass('disabled')
          jeeFrontEnd.modifyWithoutSave = false

          jeeP.configReload = document.getElementById('config').getJeeValues('.configKey[data-reload="1"]')[0]
        }
      })
    },
    saveConfig: function() {
      jeedomUtils.hideAlert()
      jeeP.saveConvertColor()
      jeeP.saveObjectSummary()
      var config = document.querySelectorAll('#config').getJeeValues('.configKey')[0]
      document.querySelectorAll('.bt_addActionOnMessage').forEach(_bt => {
        let channel = _bt.getAttribute('data-channel')
        config['actionOnMessage' + channel] = JSON.stringify(document.querySelectorAll('#div_actionOnMessage' + channel + ' .actionOnMessage').getJeeValues('.expressionAttr'))
      })

      jeedom.config.save({
        configuration: config,
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function() {
          jeedom.config.load({
            configuration: document.getElementById('config').getJeeValues('.configKey:not(.noSet)')[0],
            error: function(error) {
              jeedomUtils.showAlert({
                message: error.message,
                level: 'danger'
              })
            },
            success: function(data) {
              var reloadPage = false
              try {
                for (var key in jeeP.configReload) {
                  if (jeeP.configReload[key] != data[key]) {
                    reloadPage = true
                    break
                  }
                }
              } catch (error) {
                reloadPage = true
              }
              if (reloadPage) {
                jeeFrontEnd.modifyWithoutSave = false
                var url = 'index.php?v=d&p=administration&saveSuccessFull=1'
                if (window.location.hash != '') {
                  url += window.location.hash
                }
                window.history.pushState({}, document.title, url)
                window.location.reload(true)
              } else {
                document.getElementById('config').setJeeValues(data, '.configKey')
                jeeP.loadActionOnMessage()
                jeeFrontEnd.modifyWithoutSave = false
                setTimeout(function() {
                  jeeFrontEnd.modifyWithoutSave = false
                }, 1000)
                jeedomUtils.showAlert({
                  message: '{{Sauvegarde réussie}}',
                  level: 'success'
                })
                jeeP.configReload = document.getElementById('config').getJeeValues('.configKey[data-reload="1"]')[0]
              }
            }
          })
        }
      })
    },
  }
}

jeeFrontEnd.administration.init()


//searching
document.getElementById('in_searchConfig').addEventListener('keyup', function(event) {
  var search = this.value
  var resultDiv = document.getElementById('searchResult')

  //place back found els with unique span id to place them back to right place. Avoid cloning els to not break saveConfig().
  document.querySelectorAll('span[searchId]').forEach(_span => {
    el = document.querySelector('#searchResult [searchId="' + _span.getAttribute('searchId') + '"]')
    if (el != null) {
      el.removeAttribute('searchId')
      _span.replaceWith(el)
    }
  })

  resultDiv.empty()
  if (search == '') {
    document.querySelectorAll('#config .nav-tabs, #config .tab-content').seen()
    jeedomUtils.dateTimePickerInit()
    jeedomUtils.initTooltips()
    return
  }
  if (search.length < 3) return
  document.querySelectorAll('#config .nav-tabs, #config .tab-content').unseen()

  search = jeedomUtils.normTextLower(search)
  var text, tooltip, tabId, tabName, newTabLink, el, searchId
  var tabsArr = []
  var thisTabLink

  //Search in all labels and their tooltip:
  document.querySelectorAll('.form-group > .control-label').forEach(_label => {
    text = jeedomUtils.normTextLower(_label.textContent)
    tooltip = _label.querySelector('sup i')?.getAttribute('data-title')
    if (tooltip != null) {
      tooltip = jeedomUtils.normTextLower(tooltip)
    } else {
      tooltip = ''
    }
    if (text.includes(search) || (tooltip != '' && tooltip.includes(search))) {
      //get element tab to create link to:
      thisTabLink = false
      newTabLink = false
      tabId = _label.closest('div[role="tabpanel"]')?.getAttribute('id')
      if (tabId == undefined) return
      //Create new tablink ?
      if (!tabsArr.includes(tabId)) {
        tabName = document.querySelector('a[data-target="#' + tabId + '"]').innerHTML
        if (tabName != null) {
          var newTabLink = document.createElement('div')
          newTabLink.innerHTML = '<a role="searchTabLink" data-target="#' + tabId + '">' + tabName + '</a>'
          resultDiv.appendChild(newTabLink)
          tabsArr.push(tabId)
        }
      }
      thisTabLink = resultDiv.querySelector('a[role="searchTabLink"][data-target="#' + tabId + '"]').parentNode
      el = _label.closest('.form-group')
      //Is this form-group not in result yet:
      if (el.getAttribute('searchId') == null) {
        searchId = domUtils.uniqueId('search-')
        el.setAttribute('searchId', searchId)
        var newRefSpan = document.createElement('span')
        newRefSpan.setAttribute('searchId', searchId)
        el.replaceWith(newRefSpan)
        thisTabLink.appendChild(el)
      }
    }
  })
  jeedomUtils.dateTimePickerInit()
  jeeP.initSearchLinks()
  jeedomUtils.initTooltips()
})
document.getElementById('bt_resetConfigSearch').addEventListener('click', function(event) {
  document.getElementById('in_searchConfig').jeeValue('').triggerEvent('keyup')
  jeedomUtils.dateTimePickerInit()
})


/**************************GENERAL***********************************/
/*Events delegations
*/
document.getElementById('generaltab').addEventListener('click', function(event) {
  var _target = null
  if (_target = event.target.closest('#bt_forceSyncHour')) {
    jeedomUtils.hideAlert()
    jeedom.forceSyncHour({
      error: function(error) {
        jeedomUtils.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function(data) {
        jeedomUtils.showAlert({
          message: '{{Commande réalisée avec succès}}',
          level: 'success'
        })
      }
    })
    return
  }

  if (_target = event.target.closest('#bt_resetHour')) {
    domUtils.ajax({
      type: "POST",
      url: "core/ajax/jeedom.ajax.php",
      data: {
        action: "resetHour"
      },
      dataType: 'json',
      global: false,
      error: function(request, status, error) {
        handleAjaxError(request, status, error)
      },
      success: function(data) {
        if (data.state != 'ok') {
          jeedomUtils.showAlert({
            message: data.result,
            level: 'danger'
          })
          return
        }
        jeedomUtils.loadPage('index.php?v=d&p=administration')
      }
    })
    return
  }

  if (_target = event.target.closest('#bt_resetHwKey')) {
    domUtils.ajax({
      type: "POST",
      url: "core/ajax/jeedom.ajax.php",
      data: {
        action: "resetHwKey"
      },
      dataType: 'json',
      global: false,
      error: function(request, status, error) {
        handleAjaxError(request, status, error)
      },
      success: function(data) {
        if (data.state != 'ok') {
          jeedomUtils.showAlert({
            message: data.result,
            level: 'danger'
          })
          return
        }
        jeedomUtils.loadPage('index.php?v=d&p=administration')
      }
    })
    return
  }

  if (_target = event.target.closest('#bt_resetHardwareType')) {
    jeedom.config.save({
      configuration: {
        hardware_name: ''
      },
      error: function(error) {
        jeedomUtils.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function() {
        jeedomUtils.loadPage('index.php?v=d&p=administration')
      }
    })
    return
  }
})


/**************************INTERFACE***********************************/
//Init file upload buttons:
document.querySelectorAll('input.bt_uploadImage').forEach(_button => {
  var dataPage = _button.getAttribute('data-page')
  new jeeFileUploader({
    fileInput: _button,
    url: 'core/ajax/config.ajax.php?action=uploadImage&id=' + dataPage,
    dataType: 'json',
    limitUploadFileSize: 204800, //200Ko
    done: function(event, data) {
      if (data.result.state != 'ok') {
        jeedomUtils.showAlert({
          message: data.result.result,
          level: 'danger'
        })
        return
      }
      document.querySelector('a.bt_removeBackgroundImage[data-page="' + dataPage + '"]').removeClass('disabled')
      jeeP.configReload['imageChanged'] = 1
      jeedomUtils.showAlert({
        message: '{{Image enregistrée et configurée}}',
        level: 'success'
      })
    }
  })
})

/*Events delegations
*/
document.getElementById('interfacetab').addEventListener('click', function(event) {
  var _target = null
  if (_target = event.target.closest('#bt_resetThemeCookie')) {
    setCookie('currentTheme', '', -1)
    jeedomUtils.showAlert({
      message: '{{Cookie de thème supprimé}}',
      level: 'success'
    })
    return
  }

  if (_target = event.target.closest('.bt_removeBackgroundImage')) {
    var dataPage = _target.getAttribute('data-page')
    jeeDialog.confirm('{{Êtes-vous sûr de vouloir supprimer cette image de fond ?}}', function(result) {
      if (result) {
        jeedom.config.removeImage({
          id: dataPage,
          error: function(error) {
            jeedomUtils.showAlert({
              message: error.message,
              level: 'danger'
            })
          },
          success: function() {
            document.querySelector('a.bt_removeBackgroundImage[data-page="' + dataPage + '"]').addClass('disabled')
            jeeP.configReload['imageChanged'] = 1
            jeedomUtils.showAlert({
              message: '{{Image supprimée}}',
              level: 'success'
            })
          },
        })
      }
    })
    return
  }
})


/**************************NETWORK***********************************/
/*Events delegations
*/
document.getElementById('tablist').addEventListener('click', function(event) {
  var _target = null
  if (_target = event.target.closest('#bt_networkTab')) {
    var tableBody = document.getElementById('networkInterfacesTable').tBodies[0]
    if (tableBody.children.length == 0) {
      jeedom.network.getInterfacesInfo({
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(_interfaces) {
          let div = ''
          let options = '<option value="auto">{{Automatique}}</option>'

          for (var i in _interfaces) {
            div += '<tr>'
            div += '<td>' + _interfaces[i].ifname + '</td>'
            div += '<td data-interface="' + _interfaces[i].ifname + '">' + (_interfaces[i].addr_info && _interfaces[i].addr_info[0] ? _interfaces[i].addr_info[0].local : '') + '</td>'
            div += '<td>' + (_interfaces[i].address ? _interfaces[i].address : '') + '</td>'
            div += '</tr>'

            options += '<option value="' + _interfaces[i].ifname + '">' + _interfaces[i].ifname + '</option>'
          }
          tableBody.empty().insertAdjacentHTML('beforeend', div)

          let internalAutoInterface = document.querySelector('.configKey[data-l1key="network::internalAutoInterface"]')
          let value = (internalAutoInterface.jeeValue() != '') ? internalAutoInterface.jeeValue() : 'auto'
          if (internalAutoInterface.tagName.toLowerCase() === 'span') {
            let selectInterface = internalAutoInterface.nextElementSibling
            selectInterface.setAttribute('data-l1key', 'network::internalAutoInterface')
            internalAutoInterface.remove()
            internalAutoInterface = selectInterface
          }
          internalAutoInterface.empty().insertAdjacentHTML('beforeend', options)
          internalAutoInterface.value = value
        }
      })
    }
    return
  }
})



document.getElementById('networktab').addEventListener('click', function(event) {
  var _target = null

  if (_target = event.target.closest('#bt_restartDns')) {
    jeedomUtils.hideAlert()
    jeedom.config.save({
      configuration: document.getElementById('config').getJeeValues('.configKey')[0],
      error: function(error) {
        jeedomUtils.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function() {
        jeedom.network.restartDns({
          error: function(error) {
            jeedomUtils.showAlert({
              message: error.message,
              level: 'danger'
            })
          },
          success: function(data) {
            jeeFrontEnd.modifyWithoutSave = false
            jeedomUtils.loadPage('index.php?v=d&p=administration#networktab')
          }
        })
      }
    })
    return
  }

  if (_target = event.target.closest('#bt_haltDns')) {
    jeedomUtils.hideAlert()
    jeedom.config.save({
      configuration: document.getElementById('config').getJeeValues('.configKey')[0],
      error: function(error) {
        jeedomUtils.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function() {
        jeedom.network.stopDns({
          error: function(error) {
            jeedomUtils.showAlert({
              message: error.message,
              level: 'danger'
            })
          },
          success: function(data) {
            jeeFrontEnd.modifyWithoutSave = false
            jeedomUtils.loadPage('index.php?v=d&p=administration#networktab')
          }
        })
      }
    })
    return
  }
})

document.getElementById('networktab').addEventListener('change', function(event) {
  var _target = null
  if ((_target = event.target.closest('.configKey[data-l1key="market::allowDNS"]')) || (_target = event.target.closest('.configKey[data-l1key="network::disableMangement"]'))) {
    if (document.querySelector('.configKey[data-l1key="market::allowDNS"]').jeeValue() == 1 && document.querySelector('.configKey[data-l1key="network::disableMangement"]').jeeValue() == 0) {
      document.querySelector('.configKey[data-l1key="externalProtocol"]').setAttribute('disabled', true)
      document.querySelector('.configKey[data-l1key="externalAddr"]').jeeValue('').setAttribute('disabled', true)
      document.querySelector('.configKey[data-l1key="externalPort"]').jeeValue('').setAttribute('disabled', true)
      document.querySelector('.configKey[data-l1key="externalComplement"]').jeeValue('').setAttribute('disabled', true)
    } else {
      document.querySelector('.configKey[data-l1key="externalProtocol"]').removeAttribute('disabled')
      document.querySelector('.configKey[data-l1key="externalAddr"]').removeAttribute('disabled')
      document.querySelector('.configKey[data-l1key="externalPort"]').removeAttribute('disabled')
      document.querySelector('.configKey[data-l1key="externalComplement"]').removeAttribute('disabled')
    }
    return
  }

  if ((_target = event.target.closest('.configKey[data-l1key="network::disableInternalAuto"]'))) {
    if (_target.checked) {
      document.querySelector('.configKey[data-l1key="network::internalAutoInterface"]').parentNode.unseen()
      document.querySelector('.configKey[data-l1key="internalAddr"]').removeAttribute('disabled')
    } else {
      document.querySelector('.configKey[data-l1key="network::internalAutoInterface"]').triggerEvent('change').parentNode.seen()
      document.querySelector('.configKey[data-l1key="internalAddr"]').setAttribute('disabled', true)
    }
    return
  }

  if ((_target = event.target.closest('select.configKey[data-l1key="network::internalAutoInterface"]'))) {
    let autoInterface = _target.jeeValue()
    if (document.querySelector('.configKey[data-l1key="network::disableInternalAuto"]').jeeValue() == 0 && autoInterface != '') {
      if (autoInterface != 'auto') {
        document.querySelector('.configKey[data-l1key="internalAddr"]').value = document.querySelector('#networkInterfacesTable td[data-interface="' + autoInterface + '"]').innerText
      } else {
        for (let _interface of document.querySelectorAll('#networkInterfacesTable td[data-interface]')) {
          autoInterface = _interface.getAttribute('data-interface')
          if (autoInterface == 'lo' || autoInterface.startsWith('docker') || autoInterface.startsWith('tun') || autoInterface.startsWith('br')) {
            continue
          }
          let interfaceIP = _interface.innerText
          if (interfaceIP == '' || interfaceIP.startsWith('127.0') || interfaceIP.startsWith('169')) {
            continue
          }
          if (/^((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/.test(interfaceIP)) {
            document.querySelector('.configKey[data-l1key="internalAddr"]').value = interfaceIP
            break
          }
        }
      }
    }
    return
  }
})

/**************************LOGS***********************************/
/*Events delegations
*/
document.getElementById('logtab').addEventListener('click', function(event) {
  var _target = null
  if (_target = event.target.closest('#bt_removeTimelineEvent')) {
    jeedom.timeline.deleteAll({
      error: function(error) {
        jeedomUtils.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function(data) {
        var cmd = (cmd = document.getElementById('timelineEvents')) ? cmd.innerHTML = 0 : null
        jeedomUtils.showAlert({
          message: '{{Evènements de la timeline supprimés avec succès}}',
          level: 'success'
        })
      }
    })
    return
  }

  if (_target = event.target.closest('.bt_addActionOnMessage')) {
    jeeP.addActionOnMessage({}, _target.getAttribute('data-channel'))
    jeeFrontEnd.modifyWithoutSave = true
    return
  }

  if (_target = event.target.closest('.bt_removeAction')) {
    _target.closest('.actionOnMessage').remove()
    jeeFrontEnd.modifyWithoutSave = true
    return
  }

  if (_target = event.target.closest('.listCmdAction')) {
    var el = _target.closest('.actionOnMessage').querySelector('.expressionAttr[data-l1key="cmd"]')
    jeedom.cmd.getSelectModal({
      cmd: {
        type: 'action'
      }
    }, function(result) {
      el.jeeValue(result.human)
      jeedom.cmd.displayActionOption(result.human, '', function(html) {
        el.closest('.actionOnMessage').querySelector('.actionOptions').html(html)
        jeedomUtils.taAutosize()
      })
    })
    return
  }

  if (_target = event.target.closest('.listAction')) {
    var el = _target.closest('.actionOnMessage').querySelector('.expressionAttr[data-l1key="cmd"]')
    jeedom.getSelectActionModal({}, function(result) {
      el.jeeValue(result.human)
      jeedom.cmd.displayActionOption(result.human, '', function(html) {
        el.closest('.actionOnMessage').querySelector('.actionOptions').html(html)
        jeedomUtils.taAutosize()
      })
    })
    return
  }

  if (_target = event.target.closest('.bt_selectAlertCmd')) {
    var type = _target.getAttribute('data-type')
    jeedom.cmd.getSelectModal({
      cmd: {
        type: 'action',
        subType: 'message'
      }
    }, function(result) {
      document.querySelector('.configKey[data-l1key="alert::' + type + 'Cmd"]').insertAtCursor(result.human)
    })
    return
  }

  if (_target = event.target.closest('.bt_selectWarnMeCmd')) {
    jeedom.cmd.getSelectModal({
      cmd: {
        type: 'action',
        subType: 'message'
      }
    }, function(result) {
      document.querySelectorAll('.configKey[data-l1key="interact::warnme::defaultreturncmd"]').jeeValue(result.human)
    })
    return
  }
})

document.getElementById('logtab').addEventListener('change', function(event) {
  var _target = null
  if (_target = event.target.closest('.configKey[data-l1key="log::engine"]')) {
    if (_target.value == '') return
    let element = document.querySelector('.logEngine.' + _target.value)
    if (element != null) element.seen()
    return
  }
})

document.getElementById('logtab').addEventListener('focusout', function(event) {
  var _target = null
  if (_target = event.target.closest('.cmdAction.expressionAttr[data-l1key="cmd"]')) {
    var expression = _target.closest('.actionOnMessage').getJeeValues('.expressionAttr')
    if (expression[0] && expression[0].options) {
      jeedom.cmd.displayActionOption(_target.value, init(expression[0].options), function(html) {
        _target.closest('.actionOnMessage').querySelector('.actionOptions').html(html)
        jeedomUtils.taAutosize()
      })
    }
    return
  }
})


/**************************SUMMARIES***********************************/
/*Events delegations
*/
document.getElementById('summarytab').addEventListener('click', function(event) {
  var _target = null
  if (_target = event.target.closest('#bt_addObjectSummary')) {
    jeeP.addObjectSummary()
    return
  }

  if (_target = event.target.closest('.objectSummaryAction[data-l1key="chooseIcon"]')) {
    var objectSummary = _target.closest('.objectSummary')
    var icon = objectSummary.querySelector('span[data-l1key="icon"] > i')
    if (icon != null) {
      icon = icon.getAttribute('class')
    } else {
      icon = false
    }
    jeedomUtils.chooseIcon(function(_icon) {
      objectSummary.querySelector('span[data-l1key="icon"]').innerHTML = _icon
      jeeFrontEnd.modifyWithoutSave = true
    }, { icon: icon })
    return
  }

  if (_target = event.target.closest('.objectSummaryAction[data-l1key="chooseIconNul"]')) {
    var objectSummary = _target.closest('.objectSummary')
    var icon = objectSummary.querySelector('span[data-l1key="iconnul"] > i')
    if (icon != null) {
      icon = icon.getAttribute('class')
    } else {
      icon = false
    }
    jeedomUtils.chooseIcon(function(_icon) {
      objectSummary.querySelector('span[data-l1key="iconnul"]').innerHTML = _icon
      jeeFrontEnd.modifyWithoutSave = true
    }, { icon: icon })
    return
  }

  if (_target = event.target.closest('.objectSummaryAction[data-l1key="remove"]')) {
    _target.closest('.objectSummary').remove()
    jeeFrontEnd.modifyWithoutSave = true
    return
  }

  if (_target = event.target.closest('.objectSummaryAction[data-l1key="createVirtual"]')) {
    var objectSummary = _target.closest('.objectSummary').querySelector('.objectSummaryAttr[data-l1key="key"]').jeeValue()
    domUtils.ajax({
      type: "POST",
      url: "core/ajax/object.ajax.php",
      data: {
        action: "createSummaryVirtual",
        key: objectSummary
      },
      dataType: 'json',
      error: function(request, status, error) {
        handleAjaxError(request, status, error)
      },
      success: function(data) {
        if (data.state != 'ok') {
          jeedomUtils.showAlert({
            message: data.result,
            level: 'danger'
          })
          return
        }
        jeedomUtils.showAlert({
          message: '{{Création des commandes virtuel réussies}}',
          level: 'success'
        })
      }
    })
    return
  }
})

document.getElementById('summarytab').addEventListener('dblclick', function(event) {
  var _target = null
  if (_target = event.target.closest('.summIconContainer')) {
    _target.closest('.objectSummaryAttr').innerHTML = ''
    return
  }
})

document.getElementById('summarytab').addEventListener('change', function(event) {
  var _target = null
  if (_target = event.target.closest('.objectSummaryAttr')) {
    jeeFrontEnd.modifyWithoutSave = true
    return
  }
})


/**************************EQUIPMENT***********************************/
/*Events delegations
*/
document.getElementById('eqlogictab').addEventListener('click', function(event) {
  var _target = null
  if (_target = event.target.closest('#bt_influxDelete')) {
    jeeDialog.confirm('{{Êtes-vous sûr de vouloir supprimer la base d\'InfluxDB}}', function(result) {
      if (result) {
        jeedom.cmd.dropDatabaseInflux({
          error: function(error) {
            jeedomUtils.showAlert({
              message: error.message,
              level: 'danger'
            })
          },
          success: function(data) {
            jeedomUtils.showAlert({
              message: '{{Action envoyée avec succés}}',
              level: 'success'
            })
          }
        })
      }
    })
    return
  }

  if (_target = event.target.closest('#bt_influxHistory')) {
    jeeDialog.confirm('{{Êtes-vous sûr de vouloir envoyer tout l\'historique de toutes les commandes avec push InfluxDB. Cela sera programmé et effectué en tâche de fond dans une minute et pourra être long selon le nombre de commandes.}}', function(result) {
      if (result) {
        jeedom.cmd.historyInfluxAll({
          error: function(error) {
            jeedomUtils.showAlert({
              message: error.message,
              level: 'danger'
            })
          },
          success: function(data) {
            jeedomUtils.showAlert({
              message: '{{Programmation envoyée avec succés}}',
              level: 'success'
            })
          }
        })
      }
    })
    return
  }
})


/**************************REPORTS************************************/
/***************************LINKS*************************************/
/**************************INTERACT***********************************/
/*Events delegations
*/
document.getElementById('interacttab').addEventListener('click', function(event) {
  var _target = null
  if (_target = event.target.closest('#bt_addColorConvert')) {
    jeeP.addConvertColor()
    return
  }

  if (_target = event.target.closest('.removeConvertColor')) {
    _target.closest('tr').remove()
    return
  }

  if (_target = event.target.closest('.bt_resetColor')) {
    var key = _target.getAttribute('data-l1key')
    jeedom.getConfiguration({
      key: key,
      default: 1,
      error: function(error) {
        jeedomUtils.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function(data) {
        document.querySelectorAll('.configKey[data-l1key="' + key + '"]').jeeValue(data)
      }
    })
    return
  }
})


/**************************SECURITY***********************************/
/*Events delegations
*/
document.getElementById('securitytab').addEventListener('click', function(event) {
  var _target = null
  if (_target = event.target.closest('#bt_testLdapConnection')) {
    jeedom.config.save({
      configuration: document.getElementById('config').getJeeValues('.configKey')[0],
      error: function(error) {
        jeedomUtils.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function() {
        jeeFrontEnd.modifyWithoutSave = false
        domUtils.ajax({
          type: 'POST',
          url: 'core/ajax/user.ajax.php',
          data: {
            action: 'testLdapConnection',
          },
          dataType: 'json',
          error: function(request, status, error) {
            handleAjaxError(request, status, error)
          },
          success: function(data) {
            if (data.state != 'ok') {
              jeedomUtils.showAlert({
                message: '{{Connexion échouée :}} ' + data.result,
                level: 'danger'
              })
              return
            }
            jeedomUtils.showAlert({
              message: '{{Connexion réussie}}',
              level: 'success'
            })
          }
        })
      }
    })
    return
  }

  if (_target = event.target.closest('#bt_removeBanIp')) {
    jeedom.user.removeBanIp({
      error: function(error) {
        jeedomUtils.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function(data) {
        window.location.reload()
      }
    })
    return
  }
})

document.getElementById('securitytab').addEventListener('change', function(event) {
  var _target = null
  if (_target = event.target.closest('.configKey[data-l1key="ldap:enable"]')) {
    if (_target.checked) {
      document.getElementById('div_config_ldap').seen()
    } else {
      document.getElementById('div_config_ldap').unseen()
    }
    return
  }
})


/**************************UPDATES / MARKET***********************************/
/*Events delegations
*/
document.getElementById('updatetab').addEventListener('click', function(event) {
  var _target = null
  if (_target = event.target.closest('.testRepoConnection')) {
    var repo = _target.getAttribute('data-repo')
    jeedom.config.save({
      configuration: document.getElementById('config').getJeeValues('.configKey')[0],
      error: function(error) {
        jeedomUtils.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function() {
        jeedom.config.load({
          configuration: document.querySelectorAll('#config').getJeeValues('.configKey:not(.noSet)')[0],
          error: function(error) {
            jeedomUtils.showAlert({
              message: error.message,
              level: 'danger'
            })
          },
          success: function(data) {
            document.querySelector('#config').setJeeValues(data, '.configKey')
            jeeFrontEnd.modifyWithoutSave = false
            jeedom.repo.test({
              repo: repo,
              error: function(error) {
                jeedomUtils.showAlert({
                  message: error.message,
                  level: 'danger'
                })
              },
              success: function(data) {
                jeedomUtils.showAlert({
                  message: '{{Test réussi}}',
                  level: 'success'
                })
              }
            })
          }
        })
      }
    })
    return
  }

})

document.getElementById('updatetab').addEventListener('change', function(event) {
  var _target = null
  if (_target = event.target.closest('.enableRepository')) {
    if (_target.checked) {
      document.querySelectorAll('.repositoryConfiguration' + _target.getAttribute('data-repo')).seen()
    } else {
      document.querySelectorAll('.repositoryConfiguration' + _target.getAttribute('data-repo')).unseen()
    }
    return
  }
})


/**************************CACHE***********************************/
/*Events delegations
*/
document.getElementById('cachetab').addEventListener('click', function(event) {
  var _target = null
  if (_target = event.target.closest('#bt_cleanCache')) {
    jeedomUtils.hideAlert()
    jeeP.cleanCache()
    return
  }

  if (_target = event.target.closest('#bt_flushCache')) {
    jeedomUtils.hideAlert()
    jeeDialog.confirm('{{Attention ceci est une opération risquée (vidage du cache), Confirmez vous vouloir la faire ?}}', function(result) {
      if (result) {
        jeeP.flushCache()
      }
    })
    return
  }

  if (_target = event.target.closest('#bt_flushWidgetCache')) {
    jeedomUtils.hideAlert()
    jeeP.flushWidgetCache()
    return
  }
})

document.getElementById('cachetab').addEventListener('change', function(event) {
  var _target = null
  if (_target = event.target.closest('.configKey[data-l1key="cache::engine"]')) {
    document.querySelectorAll('.cacheEngine').unseen()
    if (_target.value == '') return
    let element = document.querySelector('.cacheEngine.' + _target.value)
    if (element != null) element.seen()
    return
  }
})


/**************************API***********************************/
/*Events delegations
*/
document.getElementById('apitab').addEventListener('click', function(event) {
  var _target = null
  if (_target = event.target.closest('.bt_regenerate_api')) {
    jeedomUtils.hideAlert()
    jeeDialog.confirm('{{Êtes-vous sûr de vouloir réinitialiser la clé API de}}' + ' ' + _target.getAttribute('data-plugin') + ' ?', function(result) {
      if (result) {
        domUtils.ajax({
          type: "POST",
          url: "core/ajax/config.ajax.php",
          data: {
            action: "genApiKey",
            plugin: _target.getAttribute('data-plugin'),
          },
          dataType: 'json',
          error: function(request, status, error) {
            handleAjaxError(request, status, error)
          },
          success: function(data) {
            if (data.state != 'ok') {
              jeedomUtils.showAlert({
                message: data.result,
                level: 'danger'
              })
              return
            }
            _target.closest('.input-group').querySelectorAll('.span_apikey').jeeValue(data.result)
          }
        })
      }
    })
    return
  }
  if (_target = event.target.closest('.bt_copyPass')) {
    _target.closest('.input-group').querySelector('.span_apikey').select()
    document.execCommand('copy')
    window.getSelection()?.removeAllRanges()
  }
})


/**************************OSDB***********************************/
/*Events delegations
*/
document.getElementById('ostab').addEventListener('click', function(event) {
  var _target = null
  if (_target = event.target.closest('#bt_cleanFileSystemRight')) {
    jeedom.cleanFileSystemRight({
      error: function(error) {
        jeedomUtils.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function(data) {
        jeedomUtils.showAlert({
          message: '{{Rétablissement des droits d\'accès effectué avec succès}}',
          level: 'success'
        })
      }
    })
    return
  }

  if (_target = event.target.closest('#bt_consistency')) {
    jeedom.consistency({
      error: function(error) {
        jeedomUtils.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function(data) {
        jeeDialog.dialog({
          id: 'jee_modal2',
          title: "{{Log consistency}}",
          contentUrl: 'index.php?v=d&modal=log.display&log=consistency'
        })
      }
    })
    return
  }

  if (_target = event.target.closest('#bt_logConsistency')) {
    jeeDialog.dialog({
      id: 'jee_modal2',
      title: "{{Log consistency}}",
      contentUrl: 'index.php?v=d&modal=log.display&log=consistency'
    })
    return
  }

  if (_target = event.target.closest('#bt_checkDatabase')) {
    jeeDialog.dialog({
      id: 'jee_modal',
      title: "{{Vérification base de données}}",
      contentUrl: 'index.php?v=d&modal=db.check'
    })
    return
  }

  if (_target = event.target.closest('#bt_checkPackage')) {
    jeeDialog.dialog({
      id: 'jee_modal',
      title: "{{Vérification des packages}}",
      contentUrl: 'index.php?v=d&modal=package.check'
    })
    return
  }

  if (_target = event.target.closest('#bt_cleanDatabase')) {
    jeedom.cleanDatabase({
      error: function(error) {
        jeedomUtils.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function(data) {
        jeedomUtils.showAlert({
          message: '{{Nettoyage lancé avec succès. Pour suivre l\'avancement merci de regarder le log cleaningdb}}',
          level: 'success'
        })
      }
    })
    return
  }
})


/**************************--GLOBAL--***********************************/
document.getElementById('div_pageContainer').addEventListener('click', function(event) {
  var _target = null
  if (_target = event.target.closest('#bt_saveGeneraleConfig')) {
    jeeP.saveConfig()
    return
  }
})

document.getElementById('div_pageContainer').addEventListener('change', function(event) {
  var _target = null
  if (_target = event.target.closest('.configKey')) {
    if (_target.isVisible()) jeeFrontEnd.modifyWithoutSave = true
    return
  }
})
document.getElementById('div_pageContainer').addEventListener('mousedown', function(event) {
  var _target = null
  if (_target = event.target.closest('.ispin-wrapper')) {
    jeeFrontEnd.modifyWithoutSave = true
    return
  }
})

document.registerEvent('keydown', function(event) {
  if (jeedomUtils.getOpenedModal()) return
  if ((event.ctrlKey || event.metaKey) && event.which == 83) { //s
    event.preventDefault()
    jeeP.saveConfig()
    return
  }
})
