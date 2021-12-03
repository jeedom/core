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

var actionOptions = []
var $divConfig = $('#config')

document.onkeydown = function(event) {
  if (jeedomUtils.getOpenedModal()) return

  if ((event.ctrlKey || event.metaKey) && event.which == 83) { //s
    event.preventDefault()
    $("#bt_saveGeneraleConfig").click()
    return
  }
}

//select tab:
var _url = window.location.href
if (_url.match('#') && _url.split('#')[1] != '' && $('.nav-tabs a[href="#' + _url.split('#')[1] + '"]').html() != undefined) {
  $('.nav-tabs a[href="#' + _url.split('#')[1] + '"]').trigger('click')
}

$(function() {
  $.showLoading()
  if (getUrlVars('panel') != false) {
    $('a[href="#' + getUrlVars('panel') + '"]').click()
  }

  initPickers()
  printConvertColor()
  setTimeout(function() {
    updateTooltips()
    modifyWithoutSave = false
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
    initPickers()
    updateTooltips()
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
  initPickers()
  initSearchLinks()
  updateTooltips()
})

function initSearchLinks() {
  $('#searchResult a[role="searchTabLink"]').on('click', function() {
    var tabId = $(this).attr('href')
    $('#bt_resetConfigSearch').trigger('click')
    $('ul.nav-primary > li > a[href="' + tabId + '"]').trigger('click')
  })
}

function updateTooltips() {
  //management of tooltip with search engine. In scenarios, tooltips are specially created with tooltip attribute and copied as title to keep track of it!
  $('[tooltip]:not(.tooltipstered)').each(function() {
    $(this).attr('title', $(this).attr('tooltip'))
  })
  $('[tooltip]:not(.tooltipstered)').tooltipster(jeedomUtils.TOOLTIPSOPTIONS)
}
$('#bt_resetConfigSearch').on('click', function() {
  $('#in_searchConfig').val('').keyup()
  initPickers()
})

//DateTimePickers and Spinners
function initPickers() {
  $('input.isdatepicker').datetimepicker('destroy')
  $('.xdsoft_datetimepicker').remove()
  $('input.isdatepicker').datetimepicker({
    datepicker: false,
    format: 'H:i',
    step: 10
  })
  $('input[type="number"]').spinner({
    icons: {
      down: "ui-icon-triangle-1-s",
      up: "ui-icon-triangle-1-n"
    }
  })
}

var _configReload_
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
    loadActionOnMessage()

    if (jeedom.theme['interface::background::dashboard'] != '/data/backgrounds/config_dashboard.jpg') $('a.bt_removeBackgroundImage[data-page=dashboard]').addClass('disabled')
    if (jeedom.theme['interface::background::analysis'] != '/data/backgrounds/config_analysis.jpg') $('a.bt_removeBackgroundImage[data-page=analysis]').addClass('disabled')
    if (jeedom.theme['interface::background::tools'] != '/data/backgrounds/config_tools.jpg') $('a.bt_removeBackgroundImage[data-page=tools]').addClass('disabled')
    modifyWithoutSave = false

    _configReload_ = $('#config').getValues('.configKey[data-reload="1"]')[0]
  }
})

$("#bt_saveGeneraleConfig").off('click').on('click', function(event) {
  $.hideAlert()
  saveConvertColor()
  saveObjectSummary()
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
            for (var key in _configReload_) {
              if (_configReload_[key] != data[key]) {
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
            loadActionOnMessage()
            modifyWithoutSave = false
            setTimeout(function() {
              modifyWithoutSave = false
            }, 1000)
            $.fn.showAlert({
              message: '{{Sauvegarde réussie}}',
              level: 'success'
            })
            _configReload_ = $('#config').getValues('.configKey[data-reload="1"]')[0]
          }
        }
      })
    }
  })
})

$divConfig.off('change', '.configKey').on('change', '.configKey:visible', function() {
  modifyWithoutSave = true
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
      _configReload_['imageChanged'] = 1
      $.fn.showAlert({
        message: '{{Image enregistrée et configurée}}',
        level: 'success'
      })
    }
  })
})

$divConfig.on({
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
            _configReload_['imageChanged'] = 1
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
$divConfig.on({
  'change': function(event) {
    setTimeout(function() {
      if ($('.configKey[data-l1key="market::allowDNS"]').value() == 1 && $('.configKey[data-l1key="network::disableMangement"]').value() == 0) {
        $('.configKey[data-l1key=externalProtocol]').attr('disabled', true)
        $('.configKey[data-l1key=externalAddr]').attr('disabled', true).value('')
        $('.configKey[data-l1key=externalPort]').attr('disabled', true).value('')
      } else {
        $('.configKey[data-l1key=externalProtocol]').attr('disabled', false)
        $('.configKey[data-l1key=externalAddr]').attr('disabled', false)
        $('.configKey[data-l1key=externalPort]').attr('disabled', false)
      }
    }, 100)
  }
}, '.configKey[data-l1key="market::allowDNS"], .configKey[data-l1key="network::disableMangement"]')

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
          modifyWithoutSave = false
          jeedomUtils.loadPage('index.php?v=d&p=administration&panel=config_network')
        }
      })
    }
  })
})

$('#bt_haltDns').on('click', function() {
  $.hideAlert();
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
          modifyWithoutSave = false
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

$divConfig.on({
  'change': function(event) {
    $('.logEngine').hide()
    if ($(this).value() == '') return
    $('.logEngine.' + $(this).value()).show()
  }
}, '.configKey[data-l1key="log::engine"]')

$('#bt_addActionOnMessage').on('click', function() {
  addActionOnMessage()
})

function loadActionOnMessage() {
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
      actionOptions = []
      for (var i in data) {
        addActionOnMessage(data[i])
      }
      jeedom.cmd.displayActionsOption({
        params: actionOptions,
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
}

function addActionOnMessage(_action) {
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
  actionOptions.push({
    expression: init(_action.cmd, ''),
    options: _action.options,
    id: actionOption_id
  })

  jeedom.scenario.setAutoComplete({
    parent: $('#div_actionOnMessage'),
    type: 'cmd'
  })
}

$divConfig.on({
  'click': function(event) {
    $(this).closest('.actionOnMessage').remove()
  }
}, '.bt_removeAction')

$divConfig.on({
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

$divConfig.on({
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

$divConfig.on({
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
  addObjectSummary()
})

$divConfig.on({
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
    modifyWithoutSave = true
  }
}, '.objectSummary .objectSummaryAction[data-l1key=chooseIcon]')

$divConfig.on({
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
    modifyWithoutSave = true
  }
}, '.objectSummary .objectSummaryAction[data-l1key=chooseIconNul]')

$divConfig.on({
  'click': function(event) {
    $(this).closest('.objectSummary').remove()
    modifyWithoutSave = true
  }
}, '.objectSummary .objectSummaryAction[data-l1key=remove]')

$divConfig.on({
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

$divConfig.on({
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

printObjectSummary()

$divConfig.on({
  'change': function(event) {
    modifyWithoutSave = true
  }
}, '.objectSummaryAttr')

function printObjectSummary() {
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
        addObjectSummary(data.result[i])
      }
      modifyWithoutSave = false
    }
  })
}

function addObjectSummary(_summary) {
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
  modifyWithoutSave = true
}

function saveObjectSummary() {
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
      printObjectSummary()
      modifyWithoutSave = false
    }
  })
}

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
  addConvertColor()
})

/**************************SECURITY***********************************/
$divConfig.on({
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
      modifyWithoutSave = false
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
$divConfig.off('change', '.enableRepository').on('change', '.enableRepository', function() {
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
          modifyWithoutSave = false
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
$divConfig.on({
  'change': function(event) {
    $('.cacheEngine').hide()
    if ($(this).value() == '') return
    $('.cacheEngine.' + $(this).value()).show()
  }
}, '.configKey[data-l1key="cache::engine"]')

$("#bt_cleanCache").on('click', function(event) {
  $.hideAlert()
  cleanCache()
})

$("#bt_flushCache").on('click', function(event) {
  $.hideAlert()
  bootbox.confirm('{{Attention ceci est une opération risquée (vidage du cache), Confirmez vous vouloir la faire ?}}', function(result) {
    if (result) {
      flushCache()
    }
  })
})

$("#bt_flushWidgetCache").on('click', function(event) {
  $.hideAlert()
  flushWidgetCache()
})

function flushCache() {
  jeedom.cache.flush({
    error: function(error) {
      $.fn.showAlert({
        message: data.result,
        level: 'danger'
      })
    },
    success: function(data) {
      updateCacheStats()
      $.fn.showAlert({
        message: '{{Cache vidé}}',
        level: 'success'
      })
    }
  })
}

function flushWidgetCache() {
  jeedom.cache.flushWidget({
    error: function(error) {
      $.fn.showAlert({
        message: data.result,
        level: 'danger'
      })
    },
    success: function(data) {
      updateCacheStats()
      $.fn.showAlert({
        message: '{{Cache vidé}}',
        level: 'success'
      })
    }
  })
}

function cleanCache() {
  jeedom.cache.clean({
    error: function(error) {
      $.fn.showAlert({
        message: data.result,
        level: 'danger'
      })
    },
    success: function(data) {
      updateCacheStats()
      $.fn.showAlert({
        message: '{{Cache nettoyé}}',
        level: 'success'
      })
    }
  })
}

function updateCacheStats() {
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
}

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
function printConvertColor() {
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
        addConvertColor(color, data.result[color])
      }
      modifyWithoutSave = false
    }
  })
}

function addConvertColor(_color, _html) {
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
  modifyWithoutSave = true
}

$('#table_convertColor tbody').off('click', '.removeConvertColor').on('click', '.removeConvertColor', function() {
  $(this).closest('tr').remove()
})

function saveConvertColor() {
  var value = {}
  var colors = {}
  $('#table_convertColor tbody tr').each(function() {
    colors[$(this).find('.color').value()] = $(this).find('.html').value()
  });
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
      modifyWithoutSave = false
    }
  })
}

/*CMD color*/
$('.bt_resetColor').on('click', function() {
  var el = $(this);
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