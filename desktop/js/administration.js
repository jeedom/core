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

      jeedomUtils.dateTimePickerInit()
      jeedomUtils.initSpinners()
      jeedomUtils.setCheckContextMenu()
      jeeP.printConvertColor()
      setTimeout(function() {
        jeedomUtils.initTooltips()
        jeeFrontEnd.modifyWithoutSave = false
      }, 500)
    },
    initSearchLinks: function() {
      document.querySelectorAll('#searchResult a[role="searchTabLink"]').addEventListener('click', function() {
        let tabId = event.target.getAttribute('href')
        let input = document.getElementById('in_searchConfig')
        input.value = ''
        input.triggerEvent('keyup')
        document.querySelector('ul.nav-primary > li > a[href="' + tabId + '"]').triggerEvent('click')
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
          document.getElementById('table_objectSummary').tBodies[0].empty()
          for (var i in data.result) {
            if (isset(data.result[i].key) && data.result[i].key == '') {
              continue
            }
            if (!isset(data.result[i].name)) {
              continue
            }
            if (!isset(data.result[i].key)) {
              data.result[i].key = i.toLowerCase().stripAccents().replace(/\_/g, '').replace(/\-/g, '').replace(/\&/g, '').replace(/\s/g, '')
            }
            jeeP.addObjectSummary(data.result[i], false)
          }
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
      tr += '<span class="objectSummaryAttr" data-l1key="icon"></span></td>'

      tr += '<td><a class="objectSummaryAction btn btn-sm" data-l1key="chooseIconNul"><i class="fas fa-flag"></i><span class="hidden-1280"> {{Icône}}</span></a>'
      tr += '<span class="objectSummaryAttr" data-l1key="iconnul"></span></td>'

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
              jeeP.addActionOnMessage(data[i],channel)
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
              }
            })
          }
        })
      });
    },
    addActionOnMessage: function(_action,_channel) {
      if (!isset(_channel)) _channel = ''
      if (!isset(_action)) _action = {}
      if (!isset(_action.options)) _action.options = {}

      let actionOption_id = jeedomUtils.uniqId()
      let div = '<div class="expression actionOnMessage">'
      div += '<input class="expressionAttr" data-l1key="type" style="display : none;" value="action">'
      div += '<div class="form-group ">'
      div += '<label class="col-sm-2 control-label">Action</label>'
      div += '<div class="col-sm-1">'
      div += '<input type="checkbox" class="expressionAttr" data-l1key="options" data-l2key="enable" checked title="{{Décocher pour desactiver l\'action}}" />'
      div += '<input type="checkbox" class="expressionAttr" data-l1key="options" data-l2key="background" title="{{Cocher pour que la commande s\'éxecute en parrallele des autres actions}}" />'
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
      document.getElementById('div_actionOnMessage'+_channel).appendChild(newDiv.childNodes[0])

      jeeP.actionOptions.push({
        expression: init(_action.cmd, ''),
        options: _action.options,
        id: actionOption_id
      })

      jeedom.scenario.setAutoComplete({
        parent: document.getElementById('div_actionOnMessage'+_channel),
        type: 'cmd'
      })
    },
    //-> cache
    flushCache: function() {
      jeedom.cache.flush({
        error: function(error) {
          jeedomUtils.showAlert({
            message: data.result,
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
            message: data.result,
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
            message: data.result,
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
            message: data.result,
            level: 'danger'
          })
        },
        success: function(data) {
          $('#span_cacheObject').html(data.count)
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
      document.querySelectorAll('#table_convertColor tbody tr').forEach(function (element) {
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
        document.querySelector('.configKey[data-l1key="market::allowDNS"]').triggerEvent('change')
        document.querySelector('.configKey[data-l1key="ldap:enable"]').triggerEvent('change')
        jeeP.loadActionOnMessage()

        if (jeedom.theme['interface::background::dashboard'] != '/data/backgrounds/config_dashboard.jpg') document.querySelector('a.bt_removeBackgroundImage[data-page="dashboard"]').addClass('disabled')
        if (jeedom.theme['interface::background::analysis'] != '/data/backgrounds/config_analysis.jpg') document.querySelector('a.bt_removeBackgroundImage[data-page="analysis"]').addClass('disabled')
        if (jeedom.theme['interface::background::tools'] != '/data/backgrounds/config_tools.jpg') document.querySelector('a.bt_removeBackgroundImage[data-page="tools"]').addClass('disabled')
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
            configuration: document.querySelectorAll('#config').getJeeValues('.configKey:not(.noSet)')[0],
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
                document.querySelector('#config').setJeeValues(data, '.configKey')
                jeeP.loadActionOnMessage()
                jeeFrontEnd.modifyWithoutSave = false
                setTimeout(function() {
                  jeeFrontEnd.modifyWithoutSave = false
                }, 1000)
                jeedomUtils.showAlert({
                  message: '{{Sauvegarde réussie}}',
                  level: 'success'
                })
                jeeP.configReload = document.querySelectorAll('#config').getJeeValues('.configKey[data-reload="1"]')[0]
              }
            }
          })
        }
      })
    },
  }
}

jeeFrontEnd.administration.init()

document.registerEvent('keydown', function(event) {
  if (jeedomUtils.getOpenedModal()) return
  if ((event.ctrlKey || event.metaKey) && event.which == 83) { //s
    event.preventDefault()
    jeeP.saveConfig()
    return
  }
})

//searching
$('#in_searchConfig').keyup(function(event) {
  var search = this.value

  //place back found els with random numbered span to place them back to right place. Avoid cloning els for better saving.
  $('span[searchId]').each(function() {
    el = $('#searchResult [searchId="' + $(this).attr('searchId') + '"]')
    el.removeAttr('searchId')
    $(this).replaceWith(el)
  })

  document.getElementById('searchResult').empty()
  if (search == '') {
    $('.nav-tabs.nav-primary, .tab-content').show()
    jeedomUtils.dateTimePickerInit()
    jeedomUtils.initTooltips()
    return
  }
  if (search.length < 3) return
  $('.nav-tabs.nav-primary, .tab-content').hide()

  search = jeedomUtils.normTextLower(search)
  var text, tooltip, tabId, tabName, el, searchId
  var tabsArr = []
  var thisTabLink
  $('.form-group > .control-label').each(function() {
    thisTabLink = false
    text = jeedomUtils.normTextLower($(this).text())
    tooltip = $(this).find('sup i').attr('data-title')
    if (tooltip != undefined) {
      tooltip = jeedomUtils.normTextLower(tooltip)
    } else {
      tooltip = ''
    }
    if (text.includes(search) || (tooltip != '' && tooltip.includes(search))) {
      //get element tab to create link to:
      tabId = $(this).closest('div[role="tabpanel"]').attr('id')
      if (!tabsArr.includes(tabId)) {
        tabName = $('ul.nav-primary a[data-target="#' + tabId + '"]').html()
        if (tabName != undefined) {
          $('#searchResult').append('<div><a role="searchTabLink" data-target="#' + tabId + '">' + tabName + '</a></div>')
          tabsArr.push(tabId)
        }
      }
      thisTabLink = $('#searchResult a[role="searchTabLink"][data-target="#' + tabId + '"]').parent()

      el = $(this).closest('.form-group')
      //Is this form-group not in result yet:
      if (el.attr('searchId') == undefined) {
        searchId = Math.random()
        el.attr('searchId', searchId)
        el.replaceWith('<span searchId=' + searchId + '></span>')
        thisTabLink.append(el)
      }
    }
  })
  jeedomUtils.dateTimePickerInit()
  jeeP.initSearchLinks()
  jeedomUtils.initTooltips()
})

$('#bt_resetConfigSearch').on('click', function(event) {
  let input = document.getElementById('in_searchConfig')
  input.value = ''
  input.triggerEvent('keyup')
  jeedomUtils.dateTimePickerInit()
})

$("#bt_saveGeneraleConfig").off('click').on('click', function(event) {
  jeeP.saveConfig()
})

$('#config').on('change', '.configKey:visible', function(event) {
  jeeFrontEnd.modifyWithoutSave = true
})

/**************************GENERAL***********************************/
$('#bt_forceSyncHour').on('click', function(event) {
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
})

$('#bt_resetHour').on('click', function(event) {
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
})

$('#bt_resetHwKey').on('click', function(event) {
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
})

$('#bt_resetHardwareType').on('click', function(event) {
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
})

/**************************INTERFACE***********************************/
$("#bt_resetThemeCookie").on('click', function(event) {
  setCookie('currentTheme', '', -1)
  jeedomUtils.showAlert({
    message: '{{Cookie de thème supprimé}}',
    level: 'success'
  })
})

$('.bt_uploadImage').each(function() {
  $(this).fileupload({
    replaceFileInput: false,
    url: 'core/ajax/config.ajax.php?action=uploadImage&id=' + $(this).attr('data-page'),
    dataType: 'json',
    done: function(e, data) {
      if (data.result.state != 'ok') {
        jeedomUtils.showAlert({
          message: data.result.result,
          level: 'danger'
        })
        return
      }
      $('a.bt_removeBackgroundImage[data-page=' + $(this).attr('data-page') + ']').removeClass('disabled')
      jeeP.configReload['imageChanged'] = 1
      jeedomUtils.showAlert({
        message: '{{Image enregistrée et configurée}}',
        level: 'success'
      })
    }
  })
})

$('#config').on({
  'click': function(event) {
    var dataPage = $(this).attr('data-page')
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
            $('a.bt_removeBackgroundImage[data-page=' + dataPage + ']').addClass('disabled')
            jeeP.configReload['imageChanged'] = 1
            jeedomUtils.showAlert({
              message: '{{Image supprimée}}',
              level: 'success'
            })
          },
        })
      }
    })
  }
}, '.bt_removeBackgroundImage')

/**************************NETWORK***********************************/
$('#config').on({
  'change': function(event) {
    let externalAddr = document.querySelector('.configKey[data-l1key="externalAddr"]')
    let externalPort = document.querySelector('.configKey[data-l1key="externalPort"]')
    let externalComplement = document.querySelector('.configKey[data-l1key="externalComplement"]')
    setTimeout(function() {
      if (document.querySelector('.configKey[data-l1key="market::allowDNS"]')?.jeeValue() == 1 && document.querySelector('.configKey[data-l1key="network::disableMangement"]')?.jeeValue() == 0) {
        document.querySelector('.configKey[data-l1key="externalProtocol"]').setAttribute('disabled', '')
        externalAddr.setAttribute('disabled', '')
        externalAddr.jeeValue('')
        externalPort.setAttribute('disabled', '')
        externalPort.jeeValue('')
        externalComplement.setAttribute('disabled', '')
        externalComplement.jeeValue('')
      } else {
        externalAddr.removeAttribute('disabled')
        externalPort.removeAttribute('disabled')
        externalComplement.removeAttribute('disabled')
      }
    }, 100)
  }
}, '.configKey[data-l1key="market::allowDNS"], .configKey[data-l1key="network::disableMangement"]')

$('#bt_networkTab').on('click', function(event) {
  var tableBody = $('#networkInterfacesTable tbody')
  if (tableBody.children().length == 0) {
    jeedom.network.getInterfacesInfo({
      error: function(error) {
        jeedomUtils.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function(_interfaces) {
        var div = ''
        for (var i in _interfaces) {
          div += '<tr>'
          div += '<td>' + _interfaces[i].ifname + '</td>'
          div += '<td>' + (_interfaces[i].addr_info && _interfaces[i].addr_info[0] ? _interfaces[i].addr_info[0].local : '') + '</td>'
          div += '<td>' + (_interfaces[i].address ? _interfaces[i].address : '') + '</td>'
          div += '</tr>'
        }
        tableBody.empty().append(div)
      }
    })
  }
})

$('#bt_restartDns').on('click', function(event) {
  jeedomUtils.hideAlert()
  jeedom.config.save({
    configuration: document.querySelectorAll('#config').getJeeValues('.configKey')[0],
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
})

$('#bt_haltDns').on('click', function(event) {
  jeedomUtils.hideAlert();
  jeedom.config.save({
    configuration: document.querySelectorAll('#config').getJeeValues('.configKey')[0],
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
})

/**************************LOGS***********************************/
$('#bt_removeTimelineEvent').on('click', function(event) {
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
})

$('#config').on({
  'change': function(event) {
    document.querySelectorAll('.logEngine').unseen()
    if (this.value == '') return
    let element = document.querySelector('.logEngine.' + this.value)
    if (element !== null) element.seen()
  }
}, '.configKey[data-l1key="log::engine"]')

$('.bt_addActionOnMessage').on('click', function() {
  jeeP.addActionOnMessage({},$(this).attr('data-channel'))
  jeeFrontEnd.modifyWithoutSave = true
})

$('#config').on({
  'click': function(event) {
    $(this).closest('.actionOnMessage').remove()
    jeeFrontEnd.modifyWithoutSave = true
  }
}, '.bt_removeAction')

$('#config').on({
  'focusout': function(event) {
    var expression = this.closest('.actionOnMessage').getJeeValues('.expressionAttr')
    var el = this
    if (expression[0] && expression[0].options) {
      jeedom.cmd.displayActionOption(this.value, init(expression[0].options), function(html) {
        el.closest('.actionOnMessage').querySelector('.actionOptions').html(html)
        jeedomUtils.taAutosize()
      })
    }
  }
}, '.cmdAction.expressionAttr[data-l1key=cmd]')

$('#config').on({
  'click': function(event) {
    var el = this.closest('.actionOnMessage').querySelector('.expressionAttr[data-l1key=cmd]')
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
  }
}, '.listCmdAction')

$('#config').on({
  'click': function(event) {
    var el = this.closest('.actionOnMessage').querySelector('.expressionAttr[data-l1key=cmd]')
    jeedom.getSelectActionModal({}, function(result) {
      el.jeeValue(result.human)
      jeedom.cmd.displayActionOption(result.human, '', function(html) {
        el.closest('.actionOnMessage').querySelector('.actionOptions').html(html)
        jeedomUtils.taAutosize()
      })
    })
  }
}, '.listAction')

$('.bt_selectAlertCmd').on('click', function(event) {
  var type = $(this).attr('data-type')
  jeedom.cmd.getSelectModal({
    cmd: {
      type: 'action',
      subType: 'message'
    }
  }, function(result) {
    document.querySelector('.configKey[data-l1key="alert::' + type + 'Cmd"]').insertAtCursor(result.human)
  })
})

$('.bt_selectWarnMeCmd').on('click', function(event) {
  jeedom.cmd.getSelectModal({
    cmd: {
      type: 'action',
      subType: 'message'
    }
  }, function(result) {
    document.querySelectorAll('.configKey[data-l1key="interact::warnme::defaultreturncmd"]').jeeValue(result.human)
  })
})

/**************************SUMMARIES***********************************/
$('#bt_addObjectSummary').on('click', function() {
  jeeP.addObjectSummary()
})

$('#config').on({
  'click': function(event) {
    var objectSummary = $(this).closest('.objectSummary')
    var _icon = false
    var icon = false
    var color = false
    if ($(this).parent().find('.objectSummaryAttr > i').length) {
      var color = ''
      var class_icon = $(this).parent().find('.objectSummaryAttr > i').attr('class')
      class_icon = class_icon.replace(' ', '.').split(' ')
      var icon = '.' + class_icon[0]
      if (class_icon[1]) {
        color = class_icon[1]
      }
    }
    jeedomUtils.chooseIcon(function(_icon) {
      objectSummary.find('.objectSummaryAttr[data-l1key=icon]').empty().append(_icon)
    }, {
      icon: icon,
      color: color
    })
    jeeFrontEnd.modifyWithoutSave = true
  }
}, '.objectSummary .objectSummaryAction[data-l1key=chooseIcon]')

$('#config').on({
  'click': function(event) {
    var objectSummary = $(this).closest('.objectSummary')
    var _icon = false
    var icon = false
    var color = false
    if ($(this).parent().find('.objectSummaryAttr > i').length) {
      var color = ''
      var class_icon = $(this).parent().find('.objectSummaryAttr > i').attr('class')
      class_icon = class_icon.replace(' ', '.').split(' ')
      var icon = '.' + class_icon[0]
      if (class_icon[1]) {
        color = class_icon[1]
      }
    }
    jeedomUtils.chooseIcon(function(_icon) {
      objectSummary.find('.objectSummaryAttr[data-l1key=iconnul]').empty().append(_icon)
    }, {
      icon: icon,
      color: color
    })
    jeeFrontEnd.modifyWithoutSave = true
  }
}, '.objectSummary .objectSummaryAction[data-l1key=chooseIconNul]')

$('#config').on({
  'click': function(event) {
    $(this).closest('.objectSummary').remove()
    jeeFrontEnd.modifyWithoutSave = true
  }
}, '.objectSummary .objectSummaryAction[data-l1key=remove]')

$('#config').on({
  'click': function(event) {
    var objectSummary = this.closest('.objectSummary').querySelectorAll('.objectSummaryAttr[data-l1key=key]').jeeValue()
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
  }
}, '.objectSummary .objectSummaryAction[data-l1key=createVirtual]')

$('#config').on({
  'dblclick': function(event) {
    this.innerHTML = ''
  }
}, '.objectSummary .objectSummaryAttr[data-l1key=icon], .objectSummary .objectSummaryAttr[data-l1key=iconnul]')

$("#table_objectSummary").sortable({
  axis: "y",
  cursor: "move",
  items: ".objectSummary",
  placeholder: "ui-state-highlight",
  tolerance: "intersect",
  forcePlaceholderSize: true
})

jeeP.printObjectSummary()

$('#config').on({
  'change': function(event) {
    jeeFrontEnd.modifyWithoutSave = true
  }
}, '.objectSummaryAttr')

/**************************EQUIPMENT***********************************/
$('#bt_influxDelete').off('click').on('click', function(event) {
  jeeDialog.confirm('{{Êtes-vous sûr de vouloir supprimer la base d\'InfluxDB}}', function(result) {
    if (result) {
      jeedom.cmd.dropDatabaseInflux({
        error: function(error) {
          $('#md_displayCmdConfigure').showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          $('#md_displayCmdConfigure').showAlert({
            message: '{{Action envoyée avec succés}}',
            level: 'success'
          })
        }
      })
    }
  })
})

$('#bt_influxHistory').off('click').on('click', function(event) {
  jeeDialog.confirm('{{Êtes-vous sûr de vouloir envoyer tout l\'historique de toutes les commandes avec push InfluxDB. Cela sera programmé et effectué en tâche de fond dans une minute et pourra être long selon le nombre de commandes.}}', function(result) {
    if (result) {
      jeedom.cmd.historyInfluxAll({
        error: function(error) {
          $('#md_displayCmdConfigure').showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          $('#md_displayCmdConfigure').showAlert({
            message: '{{Programmation envoyée avec succés}}',
            level: 'success'
          })
        }
      })
    }
  })
})

/**************************INTERACT***********************************/
$('#bt_addColorConvert').on('click', function() {
  jeeP.addConvertColor()
})

/**************************SECURITY***********************************/
$('#config').on({
  'change': function(event) {
    if (this.checked) {
      document.getElementById('div_config_ldap').seen()
    } else {
      document.getElementById('div_config_ldap').unseen()
    }
  }
}, '.configKey[data-l1key="ldap:enable"]')

$("#bt_testLdapConnection").on('click', function(event) {
  jeedom.config.save({
    configuration: document.querySelectorAll('#config').getJeeValues('.configKey')[0],
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
  return false
})

$('#bt_removeBanIp').on('click', function() {
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
})

/**************************UPDATES / MARKET***********************************/
$('#config').off('change', '.enableRepository').on('change', '.enableRepository', function(event) {
  if (this.checked) {
    document.querySelectorAll('.repositoryConfiguration' + this.getAttribute('data-repo')).seen()
  } else {
    document.querySelectorAll('.repositoryConfiguration' + this.getAttribute('data-repo')).unseen()
  }
})

$('.testRepoConnection').on('click', function(event) {
  var repo = $(this).attr('data-repo')
  jeedom.config.save({
    configuration: document.querySelectorAll('#config').getJeeValues('.configKey')[0],
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
})

/**************************CACHE***********************************/
$('#config').on({
  'change': function(event) {
    document.querySelectorAll('.cacheEngine').unseen()
    if (this.value == '') return
    let element = document.querySelector('.cacheEngine.' + this.value)
    if (element !== null) element.seen()
  }
}, '.configKey[data-l1key="cache::engine"]')

$("#bt_cleanCache").on('click', function(event) {
  jeedomUtils.hideAlert()
  jeeP.cleanCache()
})

$("#bt_flushCache").on('click', function(event) {
  jeedomUtils.hideAlert()
  jeeDialog.confirm('{{Attention ceci est une opération risquée (vidage du cache), Confirmez vous vouloir la faire ?}}', function(result) {
    if (result) {
      jeeP.flushCache()
    }
  })
})

$("#bt_flushWidgetCache").on('click', function(event) {
  jeedomUtils.hideAlert()
  jeeP.flushWidgetCache()
})

/**************************API***********************************/
$(".bt_regenerate_api").on('click', function(event) {
  jeedomUtils.hideAlert()
  var el = this
  jeeDialog.confirm('{{Êtes-vous sûr de vouloir réinitialiser la clé API de}}' + ' ' + el.attr('data-plugin') + ' ?', function(result) {
    if (result) {
      domUtils.ajax({
        type: "POST",
        url: "core/ajax/config.ajax.php",
        data: {
          action: "genApiKey",
          plugin: el.attr('data-plugin'),
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
          el.closest('.input-group').querySelectorAll('.span_apikey').jeeValue(data.result)
        }
      })
    }
  })
})

/**************************OSDB***********************************/
$('#bt_cleanFileSystemRight').off('click').on('click', function(event) {
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
})

$('#bt_consistency').off('click').on('click', function(event) {
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
})

$('#bt_logConsistency').off('click').on('click', function(event) {
  jeeDialog.dialog({
    id: 'jee_modal2',
    title: "{{Log consistency}}",
    contentUrl: 'index.php?v=d&modal=log.display&log=consistency'
  })
})

$('#bt_checkDatabase').on('click', function(event) {
  jeeDialog.dialog({
    id: 'jee_modal',
    title: "{{Vérification base de données}}",
    contentUrl: 'index.php?v=d&modal=db.check'
  })
})

$('#bt_checkPackage').on('click', function(event) {
  jeeDialog.dialog({
    id: 'jee_modal',
    title: "{{Vérification des packages}}",
    contentUrl: 'index.php?v=d&modal=package.check'
  })
})

$('#bt_cleanDatabase').off('click').on('click', function(event) {
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
})

/********************Convertion************************/
$('#table_convertColor tbody').off('click', '.removeConvertColor').on('click', '.removeConvertColor', function(event) {
  $(this).closest('tr').remove()
})

//CMD color
$('.bt_resetColor').on('click', function(event) {
  var el = this
  jeedom.getConfiguration({
    key: el.getAttribute('data-l1key'),
    default: 1,
    error: function(error) {
      jeedomUtils.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(data) {
      document.querySelectorAll('.configKey[data-l1key="' + el.getAttribute('data-l1key') + '"]').jeeValue(data)
    }
  })
})

jeeFrontEnd.modifyWithoutSave = false