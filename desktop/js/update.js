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

var hasUpdate = false
var progress = -2

printUpdate()

$("#md_specifyUpdate").dialog({
  closeText: '',
  autoOpen: false,
  modal: true,
  width: window.innerWidth > 500 ? 480 : window.innerWidth,
  height: window.innerHeight > 500 ? 460 : window.innerHeight - 30,
  open: function() {
    $("body").css({
      overflow: 'hidden'
    })
    $(this).parent().css({
      'top': window.innerHeight > 500 ? 120 : 0
    })
  },
  beforeClose: function(event, ui) {
    $("body").css({
      overflow: 'inherit'
    })
  }
})

$('#bt_updateJeedom').off('click').on('click', function() {
  $('#md_specifyUpdate').dialog({
    title: "{{Options}}"
  }).dialog('open')
})

$('.updateOption[data-l1key=force]').off('click').on('click', function() {
  if ($(this).value() == 1) {
    $('#md_specifyUpdate .updateOption[data-l1key="backup::before"]').value(0);
    $('#md_specifyUpdate .updateOption[data-l1key="backup::before"]').attr('disabled', 'disabled')
  } else {
    $('#md_specifyUpdate .updateOption[data-l1key="backup::before"]').value(1);
    $('#md_specifyUpdate .updateOption[data-l1key="backup::before"]').attr('disabled', false)
  }
})

$('#bt_doUpdate').off('click').on('click', function() {
  $("#md_specifyUpdate").dialog('close')
  var options = $('#md_specifyUpdate').getValues('.updateOption')[0]
  $.hideAlert()
  progress = 0
  $('.progressbarContainer').removeClass('hidden')
  updateProgressBar()
  jeedom.update.doAll({
    options: options,
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function() {
      getJeedomLog(1, 'update')
    }
  })
})

$('#bt_checkAllUpdate').off('click').on('click', function() {
  if ($('a[href="#log"]').parent().hasClass('active')) $('a[href="#coreplugin"]').trigger('click')
  checkAllUpdate()
})

$('#table_update').on({
  'click': function(event) {
    $(this).tooltipster('open')
    if ($(this).is(':checked')) {
      $(this).closest('tr').find('a.btn.update').addClass('disabled')
    } else {
      $(this).closest('tr').find('a.btn.update').removeClass('disabled')
    }
  }
}, 'input[data-l2key="doNotUpdate"]')

$('#table_update').on({
  'click': function(event) {
    if ($(this).hasClass('disabled')) return
    var id = $(this).closest('tr').attr('data-id')
    var logicalId = $(this).closest('tr').attr('data-logicalid')
    bootbox.confirm('{{Êtes-vous sûr de vouloir mettre à jour :}}' + ' ' + logicalId + ' ?', function(result) {
      if (result) {
        progress = -1;
        $('.progressbarContainer').removeClass('hidden')
        updateProgressBar()
        $.hideAlert()
        jeedom.update.do({
          id: id,
          error: function(error) {
            $.fn.showAlert({
              message: error.message,
              level: 'danger'
            })
          },
          success: function() {
            getJeedomLog(1, 'update')
          }
        })
      }
    })
  }
}, '.update')

$('#table_update').on({
  'click': function(event) {
    var id = $(this).closest('tr').attr('data-id');
    var logicalId = $(this).closest('tr').attr('data-logicalid')
    bootbox.confirm('{{Êtes-vous sûr de vouloir supprimer :}}' + ' ' + logicalId + ' ?', function(result) {
      if (result) {
        $.hideAlert();
        jeedom.update.remove({
          id: id,
          error: function(error) {
            $.fn.showAlert({
              message: error.message,
              level: 'danger'
            })
          },
          success: function() {
            printUpdate()
          }
        })
      }
    })
  }
}, '.remove')

$('#table_update').on({
  'click': function(event) {
    var id = $(this).closest('tr').attr('data-id')
    $.hideAlert()
    jeedom.update.check({
      id: id,
      error: function(error) {
        $.fn.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function() {
        printUpdate()
      }
    })
  }
}, '.checkUpdate')

$('#bt_saveUpdate').on('click', function() {
  jeedom.update.saves({
    updates: $('tbody tr').getValues('.updateAttr'),
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(data) {
      jeedomUtils.loadPage('index.php?v=d&p=update&saveSuccessFull=1')
    }
  })
})

$(function() {
  if (isUpdating == '1') {
    $.hideAlert()
    progress = 7
    $('.progressbarContainer').removeClass('hidden')
    updateProgressBar()
    getJeedomLog(1, 'update')
  }
  $('#md_specifyUpdate').removeClass('hidden')

  $('#table_update').on('sortEnd', function(e, t) {
    $("#table_update tbody").prepend($('tr[data-type="core"]'))
  })
  $('#table_update').trigger('sorton', [
    [
      [0, 1]
    ]
  ])
})

function checkAllUpdate() {
  $.hideAlert()
  $('.progressbarContainer').addClass('hidden')
  jeedom.update.checkAll({
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function() {
      printUpdate()
    }
  })
}

var alertTimeout = null

function getJeedomLog(_autoUpdate, _log) {
  $.ajax({
    type: 'POST',
    url: 'core/ajax/log.ajax.php',
    data: {
      action: 'get',
      log: _log,
    },
    dataType: 'json',
    global: false,
    error: function(request, status, error) {
      setTimeout(function() {
        getJeedomLog(_autoUpdate, _log)
      }, 1000)
    },
    success: function(data) {
      if (data.state != 'ok') {
        setTimeout(function() {
          getJeedomLog(_autoUpdate, _log)
        }, 1000)
        return
      }
      var log = ''
      if ($.isArray(data.result)) {
        for (var i in data.result.reverse()) {
          log += data.result[i] + "\n"
          //Update end success:
          if (data.result[i].indexOf('[END ' + _log.toUpperCase() + ' SUCCESS]') != -1) {
            progress = 100
            updateProgressBar()
            printUpdate()
            if (alertTimeout != null) {
              clearTimeout(alertTimeout)
            }
            _autoUpdate = 0
            jeedomUtils.reloadPagePrompt('{{Mise(s) à jour terminée(s) avec succès.}}')
          }
          //update error:
          if (data.result[i].indexOf('[END ' + _log.toUpperCase() + ' ERROR]') != -1) {
            progress = -3
            updateProgressBar()
            printUpdate()
            if (alertTimeout != null) {
              clearTimeout(alertTimeout)
            }
            $.fn.showAlert({
              message: '{{L\'opération a échoué}}',
              level: 'danger'
            })
            _autoUpdate = 0
          }
        }
      }
      $('#pre_' + _log + 'Info').text(log)
      if (init(_autoUpdate, 0) == 1) {
        setTimeout(function() {
          getJeedomLog(_autoUpdate, _log)
        }, 1000);
      } else {
        $('#bt_' + _log + 'Jeedom .fa-refresh').hide()
        $('.bt_' + _log + 'Jeedom .fa-refresh').hide()
      }
    }
  })
}

function printUpdate() {
  jeedom.update.get({
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(data) {
      var tr_update = []
      for (var i in data) {
        if (!isset(data[i].status)) continue
        if (data[i].type == 'core' || data[i].type == 'plugin') {
          tr_update.push(addUpdate(data[i]))
        }
      }
      $('#table_update tbody').empty().append(tr_update).trigger('update');
      if (hasUpdate) $('li a[href="#coreplugin"] i').style('color', 'var(--al-warning-color)')
    }
  })

  jeedom.config.load({
    configuration: {
      "update::lastCheck": 0
    },
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(data) {
      $('#span_lastUpdateCheck').attr('title', '{{Dernière verification des mises à jour}}').value(data['update::lastCheck'])
    }
  })
}

function addUpdate(_update) {
  if (init(_update.status) == '') {
    _update.status = 'OK'
  }
  _update.status = _update.status.toUpperCase()
  var labelClass = 'label-success'
  if (_update.status == 'UPDATE') {
    labelClass = 'label-warning'
    if (_update.type == 'core' || _update.type == 'plugin') {
      if (!_update.configuration.hasOwnProperty('doNotUpdate') || _update.configuration.doNotUpdate == '0') hasUpdate = true
    }
  }

  var tr = '<tr data-id="' + init(_update.id) + '" data-logicalId="' + init(_update.logicalId) + '" data-type="' + init(_update.type) + '">'
  tr += '<td style="width:40px"><span class="updateAttr label ' + labelClass + '" data-l1key="status"></span></td>'
  tr += '<td>'
  tr += '<span class="updateAttr" data-l1key="source"></span> / <span class="updateAttr" data-l1key="type"></span> : <span class="updateAttr label label-info" data-l1key="name"></span>'
  tr += '<span class="hidden">' + _update.name + '</span><span class="updateAttr hidden" data-l1key="id"></span>'
  if (_update.configuration && _update.configuration.version) {
    var updClass = 'label-warning'
    if (_update.configuration.version.toLowerCase() == 'stable') updClass = 'label-success'
    if (_update.configuration.version.toLowerCase() != 'stable' && _update.configuration.version.toLowerCase() != 'beta') updClass = 'label-danger'
    tr += ' <span class="label ' + updClass + '">' + _update.configuration.version + '</span>'
  }
  if (_update.type == 'core' && _update.branch) {
    tr += ' <span class="label">' + _update.branch + '</span>'
  }

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
    tr += '<input type="checkbox" class="updateAttr" data-l1key="configuration" data-l2key="doNotUpdate" title="{{Sauvegarder pour conserver les modications}}"><span class="hidden-1280">{{Ne pas mettre à jour}}</span>'
  }
  tr += '</td>'
  tr += '<td>'
  if (_update.type != 'core') {
    if (isset(_update.plugin) && isset(_update.plugin.changelog) && _update.plugin.changelog != '') {
      tr += '<a class="btn btn-xs cursor" target="_blank" href="' + _update.plugin.changelog + '"><i class="fas fa-book"></i><span class="hidden-1280"> {{Changelog}}</span></a> '
    }
  } else {
    tr += '<a class="btn btn-xs" id="bt_changelogCore" target="_blank"><i class="fas fa-book"></i><span class="hidden-1280"> {{Changelog}}</span></a> '
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
  var html = $(tr)
  html.setValues(_update, '.updateAttr')
  return html
}

$('body').off('click', '#bt_changelogCore').on('click', '#bt_changelogCore', function() {
  jeedom.getDocumentationUrl({
    page: 'changelog',
    theme: $('body').attr('data-theme'),
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(url) {
      window.open(url, '_blank')
    }
  })
})

function updateProgressBar() {
  if (progress == -4) {
    $('#div_progressbar').removeClass('active progress-bar-info progress-bar-success progress-bar-danger')
      .addClass('progress-bar-warning')
    return
  }
  if (progress == -3) {
    $('#div_progressbar').removeClass('active progress-bar-info progress-bar-success progress-bar-warning')
      .addClass('progress-bar-danger')
    return
  }
  if (progress == -2) {
    $('#div_progressbar').removeClass('active progress-bar-info progress-bar-success progress-bar-danger progress-bar-warning')
      .width(0)
      .attr('aria-valuenow', 0)
      .html('0%')
    return
  }
  if (progress == -1) {
    $('#div_progressbar').removeClass('progress-bar-success progress-bar-danger progress-bar-warning')
      .addClass('active progress-bar-info')
      .width('100%')
      .attr('aria-valuenow', 100)
      .html('N/A');
    return
  }
  if (progress == 100) {
    $('#div_progressbar').removeClass('active progress-bar-info progress-bar-danger progress-bar-warning')
      .addClass('progress-bar-success')
      .width(progress + '%')
      .attr('aria-valuenow', progress)
      .html(progress + '%')
    return;
  }
  $('#div_progressbar').removeClass('progress-bar-success progress-bar-danger progress-bar-warning')
    .addClass('active progress-bar-info')
    .width(progress + '%')
    .attr('aria-valuenow', progress)
    .html(progress + '%')
}

//___log interceptor beautifier___

//listen change in log to update cleaned one:
var prevUpdateText = ''
var replaceLogLines = ['OK', '. OK', '.OK', 'OK .', 'OK.']
var regExLogProgress = /\[PROGRESS\]\[(\d.*)]/gm
var _pre_updateInfo_clean = null
var _UpdateObserver_ = null
$(function() {
  //create a second <pre> for cleaned text to avoid change event infinite loop:
  var newLogClean = '<pre id="pre_updateInfo_clean" style="display:none;"><i>{{Aucune mise à jour en cours.}}</i></pre>'
  $('#pre_updateInfo').after($(newLogClean)).hide()
  _pre_updateInfo_clean = $('#pre_updateInfo_clean')
  _pre_updateInfo_clean.show()
  createUpdateObserver()
})

function createUpdateObserver() {
  var _UpdateObserver_ = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
      if (mutation.type == 'childList' && mutation.removedNodes.length >= 1) {
        cleanUpdateLog()
      }
    })
  })

  var observerConfig = {
    attributes: true,
    childList: true,
    characterData: true,
    subtree: true
  }

  var targetNode = document.getElementById('pre_updateInfo')
  if (targetNode) _UpdateObserver_.observe(targetNode, observerConfig)
}

function cleanUpdateLog() {
  var currentUpdateText = $('#pre_updateInfo').text()
  if (currentUpdateText == '') return false
  if (prevUpdateText == currentUpdateText) return false
  var lines = currentUpdateText.split("\n")
  var l = lines.length

  //update progress bar and clean text!
  var linesRev = lines.slice().reverse()
  for (var i = 0; i < l; i++) {
    var regExpResult = regExLogProgress.exec(linesRev[i])
    if (regExpResult !== null) {
      progress = regExpResult[1]
      updateProgressBar()
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
    if (replaceLogLines.includes(nextLine)) {
      line += ' | OK'
      lines[i + offset] = ''
    }

    if (line != '') {
      newLogText += line + '\n'
      _pre_updateInfo_clean.value(newLogText)
      if ($('[href="#log"]').parent().hasClass('active')) {
        $('#log').scrollTop(1E10)
      }
      prevUpdateText = currentUpdateText
      if (progress == 100) {
        if (_UpdateObserver_) _UpdateObserver_.disconnect()
      }
    }
  }
  clearTimeout(alertTimeout)
  alertTimeout = setTimeout(alertTimeout, 60000 * 10)
}

function alertTimeout() {
  progress = -4
  updateProgressBar()
  $.fn.showAlert({
    message: '{{La mise à jour semble être bloquée (pas de changement depuis 10min. Vérifiez le log)}}',
    level: 'warning'
  })
}