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
      this.$divConfig = $('#config')
      window.jeeP = this

      //Back to tab:
      var _url = window.location.href
      if (_url.match('#') && _url.split('#')[1] != '' && $('.nav-tabs a[href="#' + _url.split('#')[1] + '"]').html() != undefined) {
        $('.nav-tabs a[href="#' + _url.split('#')[1] + '"]').trigger('click')
      }
    },
    updateTooltips: function() {
      //management of tooltip with search engine. In scenarios, tooltips are specially created with tooltip attribute and copied as title to keep track of it!
      $('[tooltip]:not(.tooltipstered)').each(function() {
        $(this).attr('title', $(this).attr('tooltip'))
      })
      $('[tooltip]:not(.tooltipstered)').tooltipster(jeedomUtils.TOOLTIPSOPTIONS)
    },
    initSearchLinks: function() {
      $('#searchResult a[role="searchTabLink"]').on('click', function() {
        var tabId = $(this).attr('href')
        $('#bt_resetConfigSearch').trigger('click')
        $('ul.nav-primary > li > a[href="' + tabId + '"]').trigger('click')
      })
    },
    //-> summary
    printObjectSummary: function() {
      $.ajax({
        type: "POST",
        url: "core/ajax/config.ajax.php",
        data: {
          action: "getKey",
          key: 'object:summary'
        },
        dataType: 'json',
        error: function(request, status, error) {
          handleAjaxError(request, status, error)
        },
        success: function(data) {
          if (data.state != 'ok') {
            $.fn.showAlert({
              message: data.result,
              level: 'danger'
            })
            return
          }
          $('#table_objectSummary tbody').empty()
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
            jeeP.addObjectSummary(data.result[i])
          }
          jeeFrontEnd.modifyWithoutSave = false
        }
      })
    },
    addObjectSummary: function(_summary) {
      var tr = '<tr class="objectSummary">'
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

      tr += '<td class="center"><input type="checkbox" class="objectSummaryAttr warning" data-l1key="hidenumber" /></td>'

      tr += '<td class="center"><input type="checkbox" class="objectSummaryAttr" data-l1key="hidenulnumber" /></td>'

      tr += '<td><select class="objectSummaryAttr input-sm" data-l1key="count">'
      tr += '<option value="">{{Aucun}}</option>'
      tr += '<option value="binary">{{Binaire}}</option>'
      tr += '</select></td>'

      tr += '<td class="center"><input type="checkbox" class="objectSummaryAttr" data-l1key="allowDisplayZero" /></td>'

      tr += '<td class="center"><input class="objectSummaryAttr form-control input-sm" data-l1key="ignoreIfCmdOlderThan" /></td>'
      tr += ''
      tr += '<td>'
      if (isset(_summary) && isset(_summary.key) && _summary.key != '') {
        tr += '<a class="btn btn-success btn-sm objectSummaryAction" data-l1key="createVirtual"><i class="fas fa-puzzle-piece"></i><span class="hidden-1280"> {{Créer virtuel}}</span></a>'
      }
      tr += '</td>'

      tr += '<td><a class="objectSummaryAction cursor" data-l1key="remove"><i class="fas fa-minus-circle"></i></a></td>'

      tr += '</tr>'
      $('#table_objectSummary tbody').append(tr)
      if (isset(_summary)) {
        $('#table_objectSummary tbody tr').last().setValues(_summary, '.objectSummaryAttr')
      }
      if (isset(_summary) && isset(_summary.key) && _summary.key != '') {
        $('#table_objectSummary tbody tr:last .objectSummaryAttr[data-l1key=key]').attr('disabled', 'disabled')
      }
      jeeFrontEnd.modifyWithoutSave = true
    },
    saveObjectSummary: function() {
      var summary = {}
      var temp = $('#table_objectSummary tbody tr').getValues('.objectSummaryAttr')
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
      $.ajax({
        type: "POST",
        url: "core/ajax/config.ajax.php",
        data: {
          action: 'addKey',
          value: json_encode(value)
        },
        dataType: 'json',
        error: function(request, status, error) {
          handleAjaxError(request, status, error)
        },
        success: function(data) {
          if (data.state != 'ok') {
            $.fn.showAlert({
              message: data.result,
              level: 'danger'
            })
            return
          }
          jeeP.printObjectSummary()
          jeeFrontEnd.modifyWithoutSave = false
        }
      })
    },
    //-> action on message
    loadActionOnMessage: function() {
      $('#div_actionOnMessage').empty()
      jeedom.config.load({
        configuration: 'actionOnMessage',
        error: function(error) {
          $.fn.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          if (data == '' || typeof data != 'object') return
          jeeP.actionOptions = []
          for (var i in data) {
            jeeP.addActionOnMessage(data[i])
          }
          jeedom.cmd.displayActionsOption({
            params: jeeP.actionOptions,
            async: false,
            error: function(error) {
              $.fn.showAlert({
                message: error.message,
                level: 'danger'
              })
            },
            success: function(data) {
              for (var i in data) {
                $('#' + data[i].id).append(data[i].html.html)
              }
              jeedomUtils.taAutosize()
            }
          })
        }
      })
    },
    addActionOnMessage: function(_action) {
      if (!isset(_action)) {
        _action = {}
      }
      if (!isset(_action.options)) {
        _action.options = {}
      }
      var div = '<div class="expression actionOnMessage">'
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
      var actionOption_id = jeedomUtils.uniqId()
      div += '<div class="col-sm-5 actionOptions" id="' + actionOption_id + '"></div>'
      div += '</div>'
      $('#div_actionOnMessage').append(div)
      $('#div_actionOnMessage .actionOnMessage').last().setValues(_action, '.expressionAttr')
      jeeP.actionOptions.push({
        expression: init(_action.cmd, ''),
        options: _action.options,
        id: actionOption_id
      })

      jeedom.scenario.setAutoComplete({
        parent: $('#div_actionOnMessage'),
        type: 'cmd'
      })
    },
    //-> cache
    flushCache: function() {
      jeedom.cache.flush({
        error: function(error) {
          $.fn.showAlert({
            message: data.result,
            level: 'danger'
          })
        },
        success: function(data) {
          jeeP.updateCacheStats()
          $.fn.showAlert({
            message: '{{Cache vidé}}',
            level: 'success'
          })
        }
      })
    },
    flushWidgetCache: function() {
      jeedom.cache.flushWidget({
        error: function(error) {
          $.fn.showAlert({
            message: data.result,
            level: 'danger'
          })
        },
        success: function(data) {
          jeeP.updateCacheStats()
          $.fn.showAlert({
            message: '{{Cache vidé}}',
            level: 'success'
          })
        }
      })
    },
    cleanCache: function() {
      jeedom.cache.clean({
        error: function(error) {
          $.fn.showAlert({
            message: data.result,
            level: 'danger'
          })
        },
        success: function(data) {
          jeeP.updateCacheStats()
          $.fn.showAlert({
            message: '{{Cache nettoyé}}',
            level: 'success'
          })
        }
      })
    },
    updateCacheStats: function() {
      jeedom.cache.stats({
        error: function(error) {
          $.fn.showAlert({
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
      $.ajax({
        type: "POST",
        url: "core/ajax/config.ajax.php",
        data: {
          action: "getKey",
          key: 'convertColor'
        },
        dataType: 'json',
        error: function(request, status, error) {
          handleAjaxError(request, status, error)
        },
        success: function(data) {
          if (data.state != 'ok') {
            $.fn.showAlert({
              message: data.result,
              level: 'danger'
            })
            return
          }
          $('#table_convertColor tbody').empty()
          for (var color in data.result) {
            jeeP.addConvertColor(color, data.result[color])
          }
          jeeFrontEnd.modifyWithoutSave = false
        }
      })
    },
    addConvertColor: function(_color, _html) {
      var tr = '<tr>'
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
      $('#table_convertColor tbody').append(tr)
      jeeFrontEnd.modifyWithoutSave = true
    },
    saveConvertColor: function() {
      var value = {}
      var colors = {}
      $('#table_convertColor tbody tr').each(function() {
        colors[$(this).find('.color').value()] = $(this).find('.html').value()
      })
      value.convertColor = colors
      $.ajax({
        type: "POST",
        url: "core/ajax/config.ajax.php",
        data: {
          action: 'addKey',
          value: json_encode(value)
        },
        dataType: 'json',
        error: function(request, status, error) {
          handleAjaxError(request, status, error)
        },
        success: function(data) {
          if (data.state != 'ok') {
            $.fn.showAlert({
              message: data.result,
              level: 'danger'
            })
            return
          }
          jeeFrontEnd.modifyWithoutSave = false
        }
      })
    },
  }
}

jeeFrontEnd.administration.init()

document.onkeydown = function(event) {
  if (jeedomUtils.getOpenedModal()) return

  if ((event.ctrlKey || event.metaKey) && event.which == 83) { //s
    event.preventDefault()
    $("#bt_saveGeneraleConfig").click()
    return
  }
}

$(function() {
  $.showLoading()
  if (getUrlVars('panel') != false) {
    $('a[href="#' + getUrlVars('panel') + '"]').click()
  }

  jeedomUtils.dateTimePickerInit()
  jeedomUtils.initSpinners()
  jeeP.printConvertColor()
  setTimeout(function() {
    jeeP.updateTooltips()
    jeeFrontEnd.modifyWithoutSave = false
  }, 500)
})

//searching
$('#in_searchConfig').keyup(function() {
  var search = $(this).value()

  //place back found els with random numbered span to place them back to right place. Avoid cloning els for better saving.
  $('span[searchId]').each(function() {
    el = $('#searchResult [searchId="' + $(this).attr('searchId') + '"]')
    el.removeAttr('searchId')
    $(this).replaceWith(el)
  })

  $.clearDivContent('searchResult')
  if (search == '') {
    $('.nav-tabs.nav-primary, .tab-content').show()
    jeedomUtils.dateTimePickerInit()
    jeeP.updateTooltips()
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
    tooltip = $(this).find('sup i').attr('tooltip')
    if (tooltip) {
      tooltip = jeedomUtils.normTextLower(tooltip)
    } else {
      tooltip = ''
    }
    if (text.indexOf(search) >= 0 || tooltip.indexOf(search) >= 0) {
      //get element tab to create link to:
      tabId = $(this).closest('div[role="tabpanel"]').attr('id')
      if (!tabsArr.includes(tabId)) {
        tabName = $('ul.nav-primary a[href="#' + tabId + '"]').html()
        if (tabName != undefined) {
          $('#searchResult').append('<div><a role="searchTabLink" href="#' + tabId + '">' + tabName + '</a></div>')
          tabsArr.push(tabId)
        }
      }
      thisTabLink = $('#searchResult a[role="searchTabLink"][href="#' + tabId + '"]').parent()

      el = $(this).closest('.form-group')
      //Is this form-group not in result yet:
      if (el.attr('searchId') == undefined) {
        searchId = Math.random()
        el.attr('searchId', searchId)
        el.replaceWith('<span searchId=' + searchId + '></span>')
        el.find('.tooltipstered').each(function() {
          $(this).removeClass('tooltipstered')
        })
        thisTabLink.append(el)
      }
    }
  })
  jeedomUtils.dateTimePickerInit()
  jeeP.initSearchLinks()
  jeeP.updateTooltips()
})

$('#bt_resetConfigSearch').on('click', function() {
  $('#in_searchConfig').val('').keyup()
  jeedomUtils.dateTimePickerInit()
})

//load configuration settings
jeedom.config.load({
  configuration: $('#config').getValues('.configKey:not(.noSet)')[0],
  error: function(error) {
    $.fn.showAlert({
      message: error.message,
      level: 'danger'
    })
  },
  success: function(data) {
    $('#config').setValues(data, '.configKey')
    $('.configKey[data-l1key="market::allowDNS"]').trigger('change')
    $('.configKey[data-l1key="ldap:enable"]').trigger('change')
    jeeP.loadActionOnMessage()

    if (jeedom.theme['interface::background::dashboard'] != '/data/backgrounds/config_dashboard.jpg') $('a.bt_removeBackgroundImage[data-page=dashboard]').addClass('disabled')
    if (jeedom.theme['interface::background::analysis'] != '/data/backgrounds/config_analysis.jpg') $('a.bt_removeBackgroundImage[data-page=analysis]').addClass('disabled')
    if (jeedom.theme['interface::background::tools'] != '/data/backgrounds/config_tools.jpg') $('a.bt_removeBackgroundImage[data-page=tools]').addClass('disabled')
    jeeFrontEnd.modifyWithoutSave = false

    jeeP.configReload = $('#config').getValues('.configKey[data-reload="1"]')[0]
  }
})

$("#bt_saveGeneraleConfig").off('click').on('click', function(event) {
  $.hideAlert()
  jeeP.saveConvertColor()
  jeeP.saveObjectSummary()
  var config = $('#config').getValues('.configKey')[0]
  config.actionOnMessage = json_encode($('#div_actionOnMessage .actionOnMessage').getValues('.expressionAttr'))
  jeedom.config.save({
    configuration: config,
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function() {
      jeedom.config.load({
        configuration: $('#config').getValues('.configKey:not(.noSet)')[0],
        error: function(error) {
          $.fn.showAlert({
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
            var url = 'index.php?v=d&p=administration&saveSuccessFull=1'
            if (window.location.hash != '') {
              url += window.location.hash
            }
            window.history.pushState({}, document.title, url)
            window.location.reload(true)
          } else {
            $('#config').setValues(data, '.configKey')
            jeeP.loadActionOnMessage()
            jeeFrontEnd.modifyWithoutSave = false
            setTimeout(function() {
              jeeFrontEnd.modifyWithoutSave = false
            }, 1000)
            $.fn.showAlert({
              message: '{{Sauvegarde réussie}}',
              level: 'success'
            })
            jeeP.configReload = $('#config').getValues('.configKey[data-reload="1"]')[0]
          }
        }
      })
    }
  })
})

jeeP.$divConfig.off('change', '.configKey').on('change', '.configKey:visible', function() {
  jeeFrontEnd.modifyWithoutSave = true
})

/**************************GENERAL***********************************/
$('#bt_forceSyncHour').on('click', function() {
  $.hideAlert()
  jeedom.forceSyncHour({
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(data) {
      $.fn.showAlert({
        message: '{{Commande réalisée avec succès}}',
        level: 'success'
      })
    }
  })
})

$('#bt_resetHour').on('click', function() {
  $.ajax({
    type: "POST",
    url: "core/ajax/jeedom.ajax.php",
    data: {
      action: "resetHour"
    },
    dataType: 'json',
    error: function(request, status, error) {
      handleAjaxError(request, status, error)
    },
    success: function(data) {
      if (data.state != 'ok') {
        $.fn.showAlert({
          message: data.result,
          level: 'danger'
        })
        return
      }
      jeedomUtils.loadPage('index.php?v=d&p=administration')
    }
  })
})

$('#bt_resetHwKey').on('click', function() {
  $.ajax({
    type: "POST",
    url: "core/ajax/jeedom.ajax.php",
    data: {
      action: "resetHwKey"
    },
    dataType: 'json',
    error: function(request, status, error) {
      handleAjaxError(request, status, error)
    },
    success: function(data) {
      if (data.state != 'ok') {
        $.fn.showAlert({
          message: data.result,
          level: 'danger'
        })
        return
      }
      jeedomUtils.loadPage('index.php?v=d&p=administration')
    }
  })
})

$('#bt_resetHardwareType').on('click', function() {
  jeedom.config.save({
    configuration: {
      hardware_name: ''
    },
    error: function(error) {
      $.fn.showAlert({
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
  $.fn.showAlert({
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
        $.fn.showAlert({
          message: data.result.result,
          level: 'danger'
        })
        return
      }
      $('a.bt_removeBackgroundImage[data-page=' + $(this).attr('data-page') + ']').removeClass('disabled')
      jeeP.configReload['imageChanged'] = 1
      $.fn.showAlert({
        message: '{{Image enregistrée et configurée}}',
        level: 'success'
      })
    }
  })
})

jeeP.$divConfig.on({
  'click': function(event) {
    var dataPage = $(this).attr('data-page')
    bootbox.confirm('{{Êtes-vous sûr de vouloir supprimer cette image de fond ?}}', function(result) {
      if (result) {
        jeedom.config.removeImage({
          id: dataPage,
          error: function(error) {
            $.fn.showAlert({
              message: error.message,
              level: 'danger'
            })
          },
          success: function() {
            $('a.bt_removeBackgroundImage[data-page=' + dataPage + ']').addClass('disabled')
            jeeP.configReload['imageChanged'] = 1
            $.fn.showAlert({
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
jeeP.$divConfig.on({
  'change': function(event) {
    setTimeout(function() {
      if ($('.configKey[data-l1key="market::allowDNS"]').value() == 1 && $('.configKey[data-l1key="network::disableMangement"]').value() == 0) {
        $('.configKey[data-l1key=externalProtocol]').attr('disabled', true)
        $('.configKey[data-l1key=externalAddr]').attr('disabled', true).value('')
        $('.configKey[data-l1key=externalPort]').attr('disabled', true).value('')
        $('.configKey[data-l1key=externalComplement]').attr('disabled', true).value('')
      } else {
        $('.configKey[data-l1key=externalProtocol]').attr('disabled', false)
        $('.configKey[data-l1key=externalAddr]').attr('disabled', false)
        $('.configKey[data-l1key=externalPort]').attr('disabled', false)
        $('.configKey[data-l1key=externalComplement]').attr('disabled', false)
      }

      if ($('.configKey[data-l1key="network::disableInternalAuto"]').value() == 1) {
        $('.configKey[data-l1key="network::internalAutoInterface"]').parent().hide()
        $('.configKey[data-l1key="internalAddr"]').attr('disabled', false)
      } else {
        $('.configKey[data-l1key="network::internalAutoInterface"]').trigger('change').parent().show()
        $('.configKey[data-l1key="internalAddr"]').attr('disabled', true)
      }
    }, 100)
  }
}, '.configKey[data-l1key="market::allowDNS"], .configKey[data-l1key="network::disableMangement"], .configKey[data-l1key="network::disableInternalAuto"]')

$('#bt_networkTab').on('click', function() {
  var tableBody = $('#networkInterfacesTable tbody')
  if (tableBody.children().length == 0) {
    jeedom.network.getInterfacesInfo({
      error: function(error) {
        $.fn.showAlert({
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
        tableBody.empty().append(div)

        let internalAutoInterface = document.querySelector('.configKey[data-l1key="network::internalAutoInterface"]')
        let value = (internalAutoInterface.value != '') ? internalAutoInterface.value : 'auto'
        if (internalAutoInterface.tagName.toLowerCase() === 'span') {
          let selectInterface = internalAutoInterface.nextElementSibling
          selectInterface.setAttribute('data-l1key', 'network::internalAutoInterface')
          internalAutoInterface.remove()
          internalAutoInterface = selectInterface
        }
        internalAutoInterface.innerHTML = ''
        internalAutoInterface.insertAdjacentHTML('beforeend', options)
        internalAutoInterface.value = value

        jQuery(internalAutoInterface).on('change', function(event) {
          let autoInterface = this.value
          if ($('.configKey[data-l1key="network::disableInternalAuto"]').value() == 0 && autoInterface != '') {
            if (autoInterface != 'auto') {
              document.querySelector('.configKey[data-l1key="internalAddr"]').value = document.querySelector('#networkInterfacesTable td[data-interface="' + autoInterface + '"]').innerText
            } else {
              for (var _interface of document.querySelectorAll('#networkInterfacesTable td[data-interface]')) {
                let autoInterface = _interface.getAttribute('data-interface')
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
        })
      }
    })
  }
})

$('#bt_restartDns').on('click', function() {
  $.hideAlert()
  jeedom.config.save({
    configuration: $('#config').getValues('.configKey')[0],
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function() {
      jeedom.network.restartDns({
        error: function(error) {
          $.fn.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          jeeFrontEnd.modifyWithoutSave = false
          jeedomUtils.loadPage('index.php?v=d&p=administration&panel=config_network')
        }
      })
    }
  })
})

$('#bt_haltDns').on('click', function() {
  $.hideAlert()
  jeedom.config.save({
    configuration: $('#config').getValues('.configKey')[0],
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function() {
      jeedom.network.stopDns({
        error: function(error) {
          $.fn.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          jeeFrontEnd.modifyWithoutSave = false
          jeedomUtils.loadPage('index.php?v=d&p=administration&panel=config_network')
        }
      })
    }
  })
})

/**************************LOGS***********************************/
$('#bt_removeTimelineEvent').on('click', function() {
  jeedom.timeline.deleteAll({
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(data) {
      $.fn.showAlert({
        message: '{{Evènement de la timeline supprimé avec succès}}',
        level: 'success'
      })
    }
  })
})

jeeP.$divConfig.on({
  'change': function(event) {
    $('.logEngine').hide()
    if ($(this).value() == '') return
    $('.logEngine.' + $(this).value()).show()
  }
}, '.configKey[data-l1key="log::engine"]')

$('#bt_addActionOnMessage').on('click', function() {
  jeeP.addActionOnMessage()
  jeeFrontEnd.modifyWithoutSave = true
})

jeeP.$divConfig.on({
  'click': function(event) {
    $(this).closest('.actionOnMessage').remove()
    jeeFrontEnd.modifyWithoutSave = true
  }
}, '.bt_removeAction')

jeeP.$divConfig.on({
  'focusout': function(event) {
    var expression = $(this).closest('.actionOnMessage').getValues('.expressionAttr')
    var el = $(this)
    if (expression[0] && expression[0].options) {
      jeedom.cmd.displayActionOption($(this).value(), init(expression[0].options), function(html) {
        el.closest('.actionOnMessage').find('.actionOptions').html(html)
        jeedomUtils.taAutosize()
      })
    }
  }
}, '.cmdAction.expressionAttr[data-l1key=cmd]')

jeeP.$divConfig.on({
  'click': function(event) {
    var el = $(this).closest('.actionOnMessage').find('.expressionAttr[data-l1key=cmd]')
    jeedom.cmd.getSelectModal({
      cmd: {
        type: 'action'
      }
    }, function(result) {
      el.value(result.human)
      jeedom.cmd.displayActionOption(el.value(), '', function(html) {
        el.closest('.actionOnMessage').find('.actionOptions').html(html)
        jeedomUtils.taAutosize()
      })
    })
  }
}, '.listCmdAction')

jeeP.$divConfig.on({
  'click': function(event) {
    var el = $(this).closest('.actionOnMessage').find('.expressionAttr[data-l1key=cmd]')
    jeedom.getSelectActionModal({}, function(result) {
      el.value(result.human)
      jeedom.cmd.displayActionOption(el.value(), '', function(html) {
        el.closest('.actionOnMessage').find('.actionOptions').html(html)
        jeedomUtils.taAutosize()
      })
    })
  }
}, '.listAction')

$('.bt_selectAlertCmd').on('click', function() {
  var type = $(this).attr('data-type')
  jeedom.cmd.getSelectModal({
    cmd: {
      type: 'action',
      subType: 'message'
    }
  }, function(result) {
    $('.configKey[data-l1key="alert::' + type + 'Cmd"]').atCaret('insert', result.human)
  })
})

$('.bt_selectWarnMeCmd').on('click', function() {
  jeedom.cmd.getSelectModal({
    cmd: {
      type: 'action',
      subType: 'message'
    }
  }, function(result) {
    $('.configKey[data-l1key="interact::warnme::defaultreturncmd"]').value(result.human)
  })
})

/**************************SUMMARIES***********************************/
$('#bt_addObjectSummary').on('click', function() {
  jeeP.addObjectSummary()
})

jeeP.$divConfig.on({
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

jeeP.$divConfig.on({
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

jeeP.$divConfig.on({
  'click': function(event) {
    $(this).closest('.objectSummary').remove()
    jeeFrontEnd.modifyWithoutSave = true
  }
}, '.objectSummary .objectSummaryAction[data-l1key=remove]')

jeeP.$divConfig.on({
  'click': function(event) {
    var objectSummary = $(this).closest('.objectSummary')
    $.ajax({
      type: "POST",
      url: "core/ajax/object.ajax.php",
      data: {
        action: "createSummaryVirtual",
        key: objectSummary.find('.objectSummaryAttr[data-l1key=key]').value()
      },
      dataType: 'json',
      error: function(request, status, error) {
        handleAjaxError(request, status, error)
      },
      success: function(data) {
        if (data.state != 'ok') {
          $.fn.showAlert({
            message: data.result,
            level: 'danger'
          })
          return
        }
        $.fn.showAlert({
          message: '{{Création des commandes virtuel réussies}}',
          level: 'success'
        })
      }
    })
  }
}, '.objectSummary .objectSummaryAction[data-l1key=createVirtual]')

jeeP.$divConfig.on({
  'dblclick': function(event) {
    $(this).value('')
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

jeeP.$divConfig.on({
  'change': function(event) {
    jeeFrontEnd.modifyWithoutSave = true
  }
}, '.objectSummaryAttr')

/**************************EQUIPMENT***********************************/
$('#bt_influxDelete').off('click').on('click', function() {
  bootbox.confirm('{{Êtes-vous sûr de vouloir supprimer la base d\'InfluxDB}}', function(result) {
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

$('#bt_influxHistory').off('click').on('click', function() {
  bootbox.confirm('{{Êtes-vous sûr de vouloir envoyer tout l\'historique de toutes les commandes avec push InfluxDB. Cela sera programmé et effectué en tâche de fond dans une minute et pourra être long selon le nombre de commandes.}}', function(result) {
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
jeeP.$divConfig.on({
  'change': function(event) {
    if ($(this).value() == 1) {
      $('#div_config_ldap').show()
    } else {
      $('#div_config_ldap').hide()
    }
  }
}, '.configKey[data-l1key="ldap:enable"]')

$("#bt_testLdapConnection").on('click', function(event) {
  jeedom.config.save({
    configuration: $('#config').getValues('.configKey')[0],
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function() {
      jeeFrontEnd.modifyWithoutSave = false
      $.ajax({
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
            $.fn.showAlert({
              message: '{{Connexion échouée :}} ' + data.result,
              level: 'danger'
            })
            return
          }
          $.fn.showAlert({
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
      $.fn.showAlert({
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
jeeP.$divConfig.off('change', '.enableRepository').on('change', '.enableRepository', function() {
  if ($(this).value() == 1) {
    $('.repositoryConfiguration' + $(this).attr('data-repo')).show()
  } else {
    $('.repositoryConfiguration' + $(this).attr('data-repo')).hide()
  }
})

$('.testRepoConnection').on('click', function() {
  var repo = $(this).attr('data-repo')
  jeedom.config.save({
    configuration: $('#config').getValues('.configKey')[0],
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function() {
      jeedom.config.load({
        configuration: $('#config').getValues('.configKey:not(.noSet)')[0],
        error: function(error) {
          $.fn.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          $('#config').setValues(data, '.configKey')
          jeeFrontEnd.modifyWithoutSave = false
          jeedom.repo.test({
            repo: repo,
            error: function(error) {
              $.fn.showAlert({
                message: error.message,
                level: 'danger'
              })
            },
            success: function(data) {
              $.fn.showAlert({
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
jeeP.$divConfig.on({
  'change': function(event) {
    $('.cacheEngine').hide()
    if ($(this).value() == '') return
    $('.cacheEngine.' + $(this).value()).show()
  }
}, '.configKey[data-l1key="cache::engine"]')

$("#bt_cleanCache").on('click', function(event) {
  $.hideAlert()
  jeeP.cleanCache()
})

$("#bt_flushCache").on('click', function(event) {
  $.hideAlert()
  bootbox.confirm('{{Attention ceci est une opération risquée (vidage du cache), Confirmez vous vouloir la faire ?}}', function(result) {
    if (result) {
      jeeP.flushCache()
    }
  })
})

$("#bt_flushWidgetCache").on('click', function(event) {
  $.hideAlert()
  jeeP.flushWidgetCache()
})

/**************************API***********************************/
$(".bt_regenerate_api").on('click', function(event) {
  $.hideAlert()
  var el = $(this)
  bootbox.confirm('{{Êtes-vous sûr de vouloir réinitialiser la clé API de}}' + ' ' + el.attr('data-plugin') + ' ?', function(result) {
    if (result) {
      $.ajax({
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
            $.fn.showAlert({
              message: data.result,
              level: 'danger'
            })
            return
          }
          el.closest('.input-group').find('.span_apikey').value(data.result)
        }
      })
    }
  })
})

/**************************OSDB***********************************/
$('#bt_accessSystemAdministration').on('click', function() {
  $('#md_modal').dialog({
    title: "{{Administration système}}"
  }).load('index.php?v=d&modal=system.action').dialog('open')
})

$('#bt_cleanFileSystemRight').off('click').on('click', function() {
  jeedom.cleanFileSystemRight({
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(data) {
      $.fn.showAlert({
        message: '{{Rétablissement des droits d\'accès effectué avec succès}}',
        level: 'success'
      })
    }
  })
})

$('#bt_consistency').off('click').on('click', function() {
  jeedom.consistency({
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(data) {
      $('#md_modal2').dialog({
        title: "{{Log consistency}}"
      }).load('index.php?v=d&modal=log.display&log=consistency').dialog('open')
    }
  })
})

$('#bt_logConsistency').off('click').on('click', function() {
  $('#md_modal2').dialog({
    title: "{{Log consistency}}"
  }).load('index.php?v=d&modal=log.display&log=consistency').dialog('open')
})

$('#bt_checkDatabase').on('click', function() {
  $('#md_modal').dialog({
    title: "{{Vérification base de données}}"
  }).load('index.php?v=d&modal=db.check').dialog('open')
})

$('#bt_checkPackage').on('click', function() {
  $('#md_modal').dialog({
    title: "{{Vérification des packages}}"
  }).load('index.php?v=d&modal=package.check').dialog('open')
})

$('#bt_cleanDatabase').off('click').on('click', function() {
  jeedom.cleanDatabase({
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(data) {
      $.fn.showAlert({
        message: '{{Nettoyage lancé avec succès. Pour suivre l\'avancement merci de regarder le log cleaningdb}}',
        level: 'success'
      })
    }
  })
})

/********************Convertion************************/
$('#table_convertColor tbody').off('click', '.removeConvertColor').on('click', '.removeConvertColor', function() {
  $(this).closest('tr').remove()
})

//CMD color
$('.bt_resetColor').on('click', function() {
  var el = $(this)
  jeedom.getConfiguration({
    key: $(this).attr('data-l1key'),
    default: 1,
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(data) {
      $('.configKey[data-l1key="' + el.attr('data-l1key') + '"]').value(data)
    }
  })
})
