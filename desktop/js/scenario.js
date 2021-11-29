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

var $divScenario = $('#div_editScenario')

//searching
$('#in_searchScenario').keyup(function() {
  var search = $(this).value()
  if (search == '') {
    $('.panel-collapse.in').closest('.panel').find('.accordion-toggle').click()
    $('.scenarioDisplayCard').show()
    return
  }
  search = jeedomUtils.normTextLower(search)
  var not = search.startsWith(":not(")
  if (not) {
    search = search.replace(':not(', '')
  }
  $('.panel-collapse').attr('data-show', 0)
  $('.scenarioDisplayCard').hide()
  var match, text
  $('.scenarioDisplayCard .name').each(function() {
    match = false
    text = jeedomUtils.normTextLower($(this).text())
    if (text.includes(search)) {
      match = true
    }

    if (not) match = !match
    if (match) {
      $(this).closest('.scenarioDisplayCard').show()
      $(this).closest('.panel-collapse').attr('data-show', 1)
    }

  })
  $('.panel-collapse[data-show=1]').collapse('show')
  $('.panel-collapse[data-show=0]').collapse('hide')
})
$('#bt_openAll').off('click').on('click', function() {
  $(".accordion-toggle[aria-expanded='false']").each(function() {
    $(this).click()
  })
})
$('#bt_closeAll').off('click').on('click', function() {
  $(".accordion-toggle[aria-expanded='true']").each(function() {
    $(this).click()
  })
})
$('#bt_resetScenarioSearch').on('click', function() {
  $('#in_searchScenario').val('').keyup()
})

$('.bt_ViewLog').off('click').on('click', function(event) {
  event.stopPropagation()
  var id = $(this).closest('.scenarioDisplayCard').attr('data-scenario_id')
  $('#md_modal2').dialog({
    title: "{{Log d'exécution du scénario}}"
  }).load('index.php?v=d&modal=scenario.log.execution&scenario_id=' + id).dialog('open')
})

//inside searching
$('#in_searchInsideScenario').keyup(function() {
  var search = $(this).value()
  $('#div_scenarioElement .insideSearch').removeClass('insideSearch')
  $('#div_scenarioElement div.CodeMirror.CodeMirror-wrap').each(function() {
    $(this).get(0).CodeMirror.setCursor(0)
  })

  if (search == '' || search.length < 3) {
    $('i.fa-eye-slash').each(function() {
      $(this).parents('.element').first().addClass('elementCollapse')
    })
    return
  }
  search = jeedomUtils.normTextLower(search)

  //search code blocks:
  var cmEditor, code, cursor
  $('#div_scenarioElement div.elementCODE').each(function() {
    try {
      cmEditor = $(this).find('div.CodeMirror.CodeMirror-wrap').get(0).CodeMirror
      code = jeedomUtils.normTextLower(cmEditor.getValue())
      if (code.indexOf(search) >= 0) {
        $(this).removeClass('elementCollapse')
        cursor = cmEditor.getSearchCursor(search, CodeMirror.Pos(cmEditor.firstLine(), 0), {
          caseFold: true,
          multiline: true
        })
        if (cursor.find(false)) {
          cmEditor.setSelection(cursor.from(), cursor.to())
        }
      } else {
        $(this).addClass('elementCollapse')
        cmEditor.setCursor(0)
      }
    } catch {}
  })
  //search in expressions:
  var text
  $('#div_scenarioElement div.element:not(.elementCODE) .expressionAttr').each(function() {
    text = jeedomUtils.normTextLower($(this).val())
    if (text.indexOf(search) >= 0) {
      $(this).addClass('insideSearch')
      $(this).parents('.element').removeClass('elementCollapse')
    }
  })
})
$('#bt_resetInsideScenarioSearch').on('click', function() {
  var btn = $(this)
  if (btn.hasClass('disabled')) return
  var searchField = $('#in_searchInsideScenario')
  if (btn.data('state') == '0') {
    //show search:
    searchField.show()
    btn.find('i').removeClass('fa-search').addClass('fa-times')
    btn.data('state', '1')
    //open code blocks for later search:
    $('#div_scenarioElement div.elementCODE.elementCollapse').each(function() {
      $(this).removeClass('elementCollapse')
      $(this).find('textarea[data-l1key="expression"]').show()
    })
    setEditors()
    $('textarea[data-l1key="expression"]').hide()
    searchField.focus()
  } else {
    if (searchField.val() == '') {
      $('i.fa-eye-slash').each(function() {
        $(this).parents('.element').first().addClass('elementCollapse')
      })
      btn.find('i').removeClass('fa-times').addClass('fa-search')
      searchField.hide()
      btn.data('state', '0')
    } else {
      searchField.val('').keyup()
    }
  }
})

//contextMenu
$(function() {
  try {
    $.contextMenu('destroy', $('.nav.nav-tabs'))

    jeedom.scenario.allOrderedByGroupObjectName({
      asGroup: 1,
      error: function(error) {
        $.fn.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function(scenarioGroupedList) {
        if (scenarioGroupedList.length == 0) return

        var contextmenuitems = {}
        var uniqId = 0
        var items, scName, scId
        for (var group in scenarioGroupedList) {
          items = {}
          for (var i in scenarioGroupedList[group]) {
            scName = scenarioGroupedList[group][i].humanName.replace('[' + group + ']', '')
            scId = scenarioGroupedList[group][i].id
            items[uniqId] = {
              'name': scName,
              'id': scId
            }
            uniqId++
          }
          contextmenuitems[group] = {
            'name': group,
            'items': items
          }
        }

        if (Object.entries(contextmenuitems).length > 0 && contextmenuitems.constructor === Object) {
          $.contextMenu({
            selector: '.nav.nav-tabs li',
            autoHide: true,
            zIndex: 9999,
            className: 'scenario-context-menu',
            callback: function(key, options, event) {
              if (event.ctrlKey || event.metaKey || event.originalEvent.which == 2) {
                var url = 'index.php?v=d&p=scenario&id=' + options.commands[key].id
                if (window.location.hash != '') {
                  url += window.location.hash
                }
                window.open(url).focus()
              } else {
                printScenario(options.commands[key].id)
              }
            },
            items: contextmenuitems
          })
        }
      }
    })
  } catch (err) {}
})

/* ---------Scenario Management UI---------- */
var tab = null
$("#bt_addScenario").off('click').on('click', function(event) {
  bootbox.prompt("{{Nom du scénario}} ?", function(result) {
    if (result !== null) {
      jeedom.scenario.save({
        scenario: {
          name: result
        },
        error: function(error) {
          $.fn.showAlert({
            message: error.message,
            level: 'danger'
          });
        },
        success: function(data) {
          var vars = getUrlVars();
          var url = 'index.php?';
          for (var i in vars) {
            if (i != 'id' && i != 'saveSuccessFull' && i != 'removeSuccessFull') {
              url += i + '=' + vars[i].replace('#', '') + '&'
            }
          }
          url += 'id=' + data.id + '&saveSuccessFull=1'
          if (tab !== null) {
            url += tab
          }
          modifyWithoutSave = false
          resetUndo()
          jeedomUtils.loadPage(url)
        }
      })
    }
  })
})
$("#bt_changeAllScenarioState").off('click').on('click', function() {
  var el = $(this)
  jeedom.config.save({
    configuration: {
      enableScenario: el.attr('data-state')
    },
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function() {
      jeedomUtils.loadPage('index.php?v=d&p=scenario')
    }
  })
})

$(".bt_clearAllLogs").on('click', function(event) {
  bootbox.confirm("{{Êtes-vous sûr de vouloir supprimer tous les logs des scénarios ?}}", function(result) {
    if (result) {
      jeedom.scenario.clearAllLogs({
        error: function(error) {
          $('#div_alertError').showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          $('#div_alertError').showAlert({
            message: "{{Logs des scénarios supprimés.}}",
            level: 'success'
          })
        }
      })
    }
  })
})


$('.bt_showScenarioSummary').off('click').on('click', function() {
  $('#md_modal').dialog({
    title: "{{Vue d'ensemble des scénarios}}"
  }).load('index.php?v=d&modal=scenario.summary').dialog('open')
})

$('#bt_generalTab').on('click', function() {
  $('#bt_resetInsideScenarioSearch').addClass('disabled')
  $('#in_searchInsideScenario').prop("disabled", true)
})

$('#bt_scenarioTab').on('click', function() {
  $('#bt_resetInsideScenarioSearch').removeClass('disabled')
  $('#in_searchInsideScenario').prop("disabled", false)
  setTimeout(function() {
    setEditors()
    jeedomUtils.taAutosize()
    updateElseToggle()
  }, 50)
})

$('.scenarioAttr[data-l2key="timeline::enable"]').off('change').on('change', function() {
  if ($(this).value() == 1) {
    $('.scenarioAttr[data-l2key="timeline::folder"]').show()
  } else {
    $('.scenarioAttr[data-l2key="timeline::folder"]').hide()
  }
})

$('.scenarioDisplayCard').off('click').on('click', function(event) {
  if (event.ctrlKey || event.metaKey) {
    var url = '/index.php?v=d&p=scenario&id=' + $(this).attr('data-scenario_id')
    window.open(url).focus()
  } else {
    $('#scenarioThumbnailDisplay').hide()
    printScenario($(this).attr('data-scenario_id'))
  }
})
$('.scenarioDisplayCard').off('mouseup').on('mouseup', function(event) {
  if (event.which == 2) {
    event.preventDefault()
    var id = $(this).attr('data-scenario_id')
    $('.scenarioDisplayCard[data-scenario_id="' + id + '"]').trigger(jQuery.Event('click', {
      ctrlKey: true
    }))
  }
})

$('#bt_scenarioThumbnailDisplay').off('click').on('click', function() {
  setTimeout(function() {
    $('.nav li.active').removeClass('active')
    $('a[href="#' + $('.tab-pane.active').attr('id') + '"]').closest('li').addClass('active')
  }, 500)
  if (jeedomUtils.checkPageModified()) return

  $('#div_editScenario').hide()
  $('#scenarioThumbnailDisplay').show()
  jeedomUtils.addOrUpdateUrl('id', null, '{{Scénario}} - ' + JEEDOM_PRODUCT_NAME)
})


/* ---------Scenario UI---------- */
document.onkeydown = function(event) {
  if (jeedomUtils.getOpenedModal()) return

  if ((event.ctrlKey || event.metaKey) && event.which == 83) { //s
    event.preventDefault()
    if ($('#bt_saveScenario').is(':visible')) {
      saveScenario()
      return
    }
  }

  if ((event.ctrlKey || event.metaKey) && event.which == 76) { //l
    event.preventDefault()
    $('#md_modal').dialog({
      title: "{{Log d'exécution du scénario}}"
    }).load('index.php?v=d&modal=scenario.log.execution&scenario_id=' + $('.scenarioAttr[data-l1key=id]').value()).dialog('open')
    return
  }

  if ((event.ctrlKey || event.metaKey) && event.shiftKey && event.which == 90) { //z
    event.preventDefault()
    undo()
    PREV_FOCUS = null
    return
  }

  if ((event.ctrlKey || event.metaKey) && event.shiftKey && event.which == 89) { //y
    event.preventDefault()
    redo()
    PREV_FOCUS = null
    return
  }
}

var PREV_FOCUS = null
$('#div_scenarioElement').on('focus', ':input', function() {
  PREV_FOCUS = $(this)
})

$(function() {
  jeedom.timeline.autocompleteFolder()
  $('sub.itemsNumber').html('(' + $('.scenarioDisplayCard').length + ')')
  if (initSearch != 0) {
    setTimeout(function() {
      $('#bt_scenarioTab').trigger('click')
      $('#bt_resetInsideScenarioSearch').trigger('click')
      setTimeout(function() {
        $('#in_searchInsideScenario').val(initSearch).keyup().blur().focus()
      }, 500)
    }, 200)
  }

  //trigger:
  setTimeout(function() {
    checkNoMode()
  }, 250)
})

function checkNoMode() {
  if ($('div.scheduleDisplay .schedule').length || $('div.provokeDisplay .trigger').length) {
    $('#emptyModeWarning').hide()
  } else {
    $('#emptyModeWarning').show()
  }
}

$('.scenario_link').off('click', '.scenario_link').on('click', '.scenario_link', function(event) {
  $.hideAlert()
  if (event.ctrlKey || event.metaKey) {
    var url = '/index.php?v=d&p=scenario&id=' + $(this).attr('data-scenario_id')
    window.open(url).focus()
  } else {
    $('#scenarioThumbnailDisplay').hide()
    printScenario($(this).attr('data-scenario_id'))
  }
})
$('.scenario_link').off('mouseup', '.scenario_link').on('mouseup', '.scenario_link', function(event) {
  if (event.which == 2) {
    event.preventDefault()
    var id = $(this).attr('data-scenario_id')
    $('.scenario_link[data-scenario_id="' + id + '"]').trigger(jQuery.Event('click', {
      ctrlKey: true
    }))
  }
})

$('#bt_chooseIcon').on('click', function() {
  var _icon = false
  if ($('div[data-l2key="icon"] > i').length) {
    _icon = $('div[data-l2key="icon"] > i').attr('class')
    _icon = '.' + _icon.replace(' ', '.')
  }
  jeedomUtils.chooseIcon(function(_icon) {
    $('.scenarioAttr[data-l1key=display][data-l2key=icon]').empty().append(_icon)
  }, {
    icon: _icon
  })
  modifyWithoutSave = true
})

$('.scenarioAttr[data-l1key=display][data-l2key=icon]').on('dblclick', function() {
  $('.scenarioAttr[data-l1key=display][data-l2key=icon]').value('')
})

$('.scenarioAttr[data-l1key=group]').autocomplete({
  source: function(request, response, url) {
    $.ajax({
      type: 'POST',
      url: 'core/ajax/scenario.ajax.php',
      data: {
        action: 'autoCompleteGroup',
        term: request.term
      },
      dataType: 'json',
      global: false,
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
        response(data.result)
      }
    })
  },
  minLength: 1,
})

$('.scenarioAttr[data-l1key=mode]').off('change').on('change', function() {
  $('#bt_addSchedule').removeClass('roundedRight')
  $('#bt_addTrigger').removeClass('roundedRight')
  if ($(this).value() == 'schedule' || $(this).value() == 'all') {
    $('.scheduleDisplay').show()
    $('#bt_addSchedule').show()
  } else {
    $('.scheduleDisplay').hide()
    $('#bt_addSchedule').hide()
    $('#bt_addTrigger').addClass('roundedRight')
  }
  if ($(this).value() == 'provoke' || $(this).value() == 'all') {
    $('.provokeDisplay').show()
    $('#bt_addTrigger').show()
  } else {
    $('.provokeDisplay').hide()
    $('#bt_addTrigger').hide()
    $('#bt_addSchedule').addClass('roundedRight')
  }
  if ($(this).value() == 'all') {
    $('#bt_addSchedule').addClass('roundedRight')
  }
})

$('#bt_addTrigger').off('click').on('click', function() {
  addTrigger('')
  checkNoMode()
})

$('#bt_addSchedule').off('click').on('click', function() {
  addSchedule('')
  checkNoMode()
})

$divScenario.on('click', '.bt_removeTrigger', function(event) {
  $(this).closest('.trigger').remove()
  checkNoMode()
})

$divScenario.on('click', '.bt_removeSchedule', function(event) {
  $(this).closest('.schedule').remove()
  checkNoMode()
})

$divScenario.on('click', '.bt_selectTrigger', function(event) {
  var el = $(this)
  jeedom.cmd.getSelectModal({
    cmd: {
      type: 'info'
    }
  }, function(result) {
    el.closest('.trigger').find('.scenarioAttr[data-l1key=trigger]').value(result.human)
  })
})

$divScenario.on('click', '.bt_selectDataStoreTrigger', function(event) {
  var el = $(this);
  jeedom.dataStore.getSelectModal({
    cmd: {
      type: 'info'
    }
  }, function(result) {
    el.closest('.trigger').find('.scenarioAttr[data-l1key=trigger]').value(result.human)
  })
})

$divScenario.on('click', '.bt_selectGenericTrigger', function(event) {
  var el = $(this);
  jeedom.config.getGenericTypeModal({
    type: 'info',
    object: true
  }, function(result) {
    el.closest('.trigger').find('.scenarioAttr[data-l1key=trigger]').value('#' + result.human + '#')
  })
})


//Scenario bar:
var SC_CLIPBOARD = null

$divScenario.on('click', '.bt_addScenarioElement', function(event) {
  if (!window.location.href.includes('#scenariotab')) $('#bt_scenarioTab').trigger('click')
  var expression = false
  var insertAfter = false
  var elementDiv = $(this).closest('.element')

  //is scenario empty:
  if ($('#div_scenarioElement').children('.element').length == 0) {
    elementDiv = $('#div_scenarioElement')
    $('#div_scenarioElement .span_noScenarioElement').remove()
  } else {
    //had focus ?
    if (PREV_FOCUS != null && $(PREV_FOCUS).closest('div.element').html() != undefined) {
      insertAfter = true
      elementDiv = $(PREV_FOCUS).closest('div.element')
      if (elementDiv.parent().attr('id') != 'div_scenarioElement') {
        elementDiv = elementDiv.parents('.expression').eq(0)
        expression = true
      }
    } else {
      elementDiv = $('#div_scenarioElement')
    }
  }

  $('#md_addElement').modal('show')
  $("#bt_addElementSave").off('click').on('click', function(event) {
    setUndoStack()
    if (expression) {
      var newEL = $(addExpression({
        type: 'element',
        element: {
          type: $("#in_addElementType").value()
        }
      }))
    } else {
      var newEL = $(addElement({
        type: $("#in_addElementType").value()
      }))
    }
    if (insertAfter) {
      elementDiv.after(newEL.addClass('disableElement'))
    } else {
      elementDiv.append(newEL.addClass('disableElement'))
    }

    setEditors()
    updateSortable()
    updateElseToggle()
    $('#md_addElement').modal('hide')
    modifyWithoutSave = true
    updateTooltips()
    jeedom.scenario.setAutoComplete()
    setTimeout(function() {
      newEL.removeClass('disableElement')
    }, 600)
  })
})
$('#in_addElementType').off('change').on('change', function() {
  $('.addElementTypeDescription').hide()
  $('.addElementTypeDescription.' + $(this).value()).show()
})

$('#bt_logScenario').off('click').on('click', function() {
  $('#md_modal').dialog({
    title: "{{Log d'exécution du scénario}}"
  }).load('index.php?v=d&modal=scenario.log.execution&scenario_id=' + $('.scenarioAttr[data-l1key=id]').value()).dialog('open')
})

$("#bt_copyScenario").off('click').on('click', function() {
  bootbox.prompt("{{Nom du scénario}} ?", function(result) {
    if (result !== null) {
      jeedom.scenario.copy({
        id: $('.scenarioAttr[data-l1key=id]').value(),
        name: result,
        error: function(error) {
          $.fn.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          jeedomUtils.loadPage('index.php?v=d&p=scenario&id=' + data.id)
        }
      })
    }
  })
})

$('#bt_graphScenario').off('click').on('click', function() {
  $('#md_modal').dialog({
    title: "{{Graphique de lien(s)}}"
  }).load('index.php?v=d&modal=graph.link&filter_type=scenario&filter_id=' + $('.scenarioAttr[data-l1key=id]').value()).dialog('open')
})

$('#bt_editJsonScenario').on('click', function() {
  $('#md_modal').dialog({
    title: "{{Edition texte scénarios}}"
  }).load('index.php?v=d&modal=scenario.jsonEdit&id=' + $('.scenarioAttr[data-l1key=id]').value()).dialog('open')
})

$('#bt_exportScenario').off('click').on('click', function() {
  $('#md_modal').dialog({
    title: "{{Export du scénario}}"
  }).load('index.php?v=d&modal=scenario.export&scenario_id=' + $('.scenarioAttr[data-l1key=id]').value()).dialog('open')
})

$('#bt_templateScenario').off('click').on('click', function() {
  $('#md_modal').dialog({
    title: "{{Template de scénario}}"
  }).load('index.php?v=d&modal=scenario.template&scenario_id=' + $('.scenarioAttr[data-l1key=id]').value()).dialog('open')
})

$("#bt_runScenario").off('click').on('click', function(event) {
  $.hideAlert()
  var scenario_id = $('.scenarioAttr[data-l1key=id]').value()
  var logmode = $('button[data-l2key="logmode"]').attr('value')
  if (event.ctrlKey || event.metaKey) {
    saveScenario(function() {
      jeedom.scenario.changeState({
        id: scenario_id,
        state: 'start',
        error: function(error) {
          $.fn.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function() {
          $.fn.showAlert({
            message: '{{Lancement du scénario réussi}}',
            level: 'success'
          })
          if (logmode != 'none') {
            $('#md_modal').dialog({
                title: "{{Log d'exécution du scénario}}"
              })
              .load('index.php?v=d&modal=scenario.log.execution&scenario_id=' + scenario_id)
              .dialog('open')
          }
        }
      })
    })
  } else {
    jeedom.scenario.changeState({
      id: $('.scenarioAttr[data-l1key=id]').value(),
      state: 'start',
      error: function(error) {
        $.fn.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function() {
        $.fn.showAlert({
          message: '{{Lancement du scénario réussi}}',
          level: 'success'
        })
      }
    })
  }
})

$("#bt_stopScenario").off('click').on('click', function() {
  jeedom.scenario.changeState({
    id: $('.scenarioAttr[data-l1key=id]').value(),
    state: 'stop',
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function() {
      $.fn.showAlert({
        message: '{{Arrêt du scénario réussi}}',
        level: 'success'
      })
    }
  })
})

$("#bt_saveScenario").off('click').on('click', function(event) {
  saveScenario()
  SC_CLIPBOARD = null
})

$("#bt_delScenario").off('click').on('click', function(event) {
  $.hideAlert()
  bootbox.confirm('{{Êtes-vous sûr de vouloir supprimer le scénario}} <span style="font-weight: bold ;">' + $('.scenarioAttr[data-l1key=name]').value() + '</span> ?', function(result) {
    if (result) {
      jeedom.scenario.remove({
        id: $('.scenarioAttr[data-l1key=id]').value(),
        error: function(error) {
          $.fn.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function() {
          modifyWithoutSave = false
          resetUndo()
          jeedomUtils.loadPage('index.php?v=d&p=scenario')
        }
      })
    }
  })
})


/*******************Element***********************/
$divScenario.on('change', '.subElementAttr[data-l1key=options][data-l2key=enable]', function() {
  var checkbox = $(this)
  var element = checkbox.closest('.element')
  if (checkbox.value() == 1) {
    element.removeClass('disableElement')
  } else {
    element.addClass('disableElement')
  }
  var subElement = checkbox.closest('.element').find('.subElement:not(.noSortable)')
  if (checkbox.value() == 1) {
    subElement.find('.expressions').removeClass('disableSubElement')
  } else {
    subElement.find('.expressions').addClass('disableSubElement')
  }
})

$divScenario.on('change', '.expressionAttr[data-l1key=options][data-l2key=enable]', function() {
  var checkbox = $(this)
  var element = checkbox.closest('.expression')
  if (checkbox.value() == 1) {
    element.removeClass('disableSubElement')
  } else {
    element.addClass('disableSubElement')
  }
})

$divScenario.on('click', '.bt_removeElement', function(event) {
  var button = $(this)
  if (event.ctrlKey || event.metaKey) {
    if (button.closest('.expression').length != 0) {
      setUndoStack()
      button.closest('.expression').remove()
    } else {
      setUndoStack()
      button.closest('.element').remove()
    }
  } else {
    bootbox.confirm("{{Êtes-vous sûr de vouloir supprimer ce bloc ?}}", function(result) {
      if (result) {
        if (button.closest('.expression').length != 0) {
          setUndoStack()
          button.closest('.expression').remove()
        } else {
          setUndoStack()
          button.closest('.element').remove()
        }
      }
    })
  }
  modifyWithoutSave = true
  PREV_FOCUS = null
})

$divScenario.on('click', '.bt_addAction', function(event) {
  setUndoStack()
  $(this).closest('.subElement').children('.expressions').append(addExpression({
    type: 'action'
  }))
  jeedom.scenario.setAutoComplete()
  updateSortable()
  updateTooltips()
})

$divScenario.on('click', '.bt_showElse', function(event) {
  if ($(this).children('i').hasClass('fa-sort-down')) {
    $(this).children('i').removeClass('fa-sort-down').addClass('fa-sort-up')
    $(this).closest('.element').children('.subElementELSE').show()
  } else {
    if ($(this).closest('.element').children('.subElementELSE').children('.expressions').children('.expression').length > 0) {
      $.fn.showAlert({
        message: "{{Le bloc Sinon ne peut être masqué s'il contient des éléments.}}",
        level: 'danger'
      })
      return
    }
    $(this).children('i').removeClass('fa-sort-up').addClass('fa-sort-down')
    $(this).closest('.element').children('.subElementELSE').hide()
  }
})

$divScenario.on('click', '.bt_collapse', function(event) {
  var changeThis = $(this)
  if (event.ctrlKey || event.metaKey) changeThis = $('.element').find('.bt_collapse')
  if ($(this).children('i').hasClass('fa-eye')) {
    // -> Collapse!
    changeThis.children('i').removeClass('fa-eye').addClass('fa-eye-slash')
    changeThis.closest('.element').addClass('elementCollapse')
    changeThis.attr('value', 1)
    changeThis.attr('title', "{{Afficher ce bloc.<br>Ctrl+click: tous.}}")
    //update action, comment and code blocPreview:
    var txt, _el, id
    changeThis.closest('.element').find('.blocPreview').each(function() {
      txt = '<i>Unfound</i>'
      _el = $(this).closest('.element')
      if (_el.hasClass('elementACTION')) {
        txt = _el.find('.expressions .expression').first().find('input.form-control').first().val()
        if (!txt) txt = _el.find('.expression textarea').val()
      } else if (_el.hasClass('elementCODE')) {
        id = _el.find('.expressionAttr[data-l1key=expression]').attr('id')
        if (isset(_EDITORS[id])) txt = _EDITORS[id].getValue()
      } else {
        //comment
        txt = _el.find('.expression textarea').val().HTMLFormat()
        if (typeof txt === 'object') {
          txt = JSON.stringify(expression.expression)
        }
        txt = '<b>' + txt.split('\n')[0] + '</b>' + txt.replace(txt.split('\n')[0], '')
        if (!txt) txt = _el.find('.expression input.form-control').val()
      }
      if (txt) $(this).html(txt.substring(0, 200))
    })
    updateTooltips()
  } else {
    // -> Uncollapse!
    changeThis.children('i').addClass('fa-eye').removeClass('fa-eye-slash')
    changeThis.closest('.element').removeClass('elementCollapse')
    changeThis.attr('value', 0)
    changeThis.attr('title', "{{Masquer ce bloc.<br>Ctrl+click: tous.}}")
    setEditors()
    updateTooltips()
  }
})

$divScenario.on('click', '.bt_removeExpression', function(event) {
  setUndoStack()
  $(this).closest('.expression').remove()
  updateSortable()
})

$('body').on('click', '.modal-body .bt_selectCmdFromModal', function(event) {
  var modal = $(this).closest('.bootbox.modal')
  modal.hide()
  jeedom.cmd.getSelectModal({
    cmd: {
      type: 'info'
    },
    returnCancel: 1
  }, function(result) {
    modal.show()
    if (isset(result.human)) modal.find('input[data-l1key="operande"]').val(result.human)
  })
})

function getSelectCmdExpressionMessage(subType, cmdHumanName) {
  if (!['numeric', 'string', 'binary'].includes(subType)) return '{{Aucun choix possible}}'

  var message = '<div class="row">'
  message += '<div class="col-md-12">'
  message += '<form class="form-horizontal" onsubmit="return false;">'
  message += '<div class="form-group">'
  message += '<label class="col-xs-5 control-label" >' + cmdHumanName + ' {{est}}</label>'

  if (subType == 'numeric') {
    message += '<div class="col-xs-3">'
    message += '  <select class="conditionAttr form-control" data-l1key="operator">'
    message += '    <option value="==">{{égal}}</option>'
    message += '    <option value=">">{{supérieur}}</option>'
    message += '    <option value="<">{{inférieur}}</option>'
    message += '    <option value="!=">{{différent}}</option>'
    message += '  </select>'
    message += '</div>'
    message += '<div class="col-xs-4">'
    message += '  <input class="conditionAttr form-control radio-inline" data-l1key="operande" style="width: calc(100% - 45px);" />'
    message += '  <button type="button" class="btn btn-default cursor bt_selectCmdFromModal"><i class="fas fa-list-alt"></i></button>'
    message += '</div>'
    message += '</div>'
  }

  if (subType == 'string') {
    message += '<div class="col-xs-2">'
    message += '  <select class="conditionAttr form-control" data-l1key="operator">'
    message += '    <option value="==">{{égale}}</option>'
    message += '    <option value="matches">{{contient}}</option>'
    message += '    <option value="!=">{{différent}}</option>'
    message += '  </select>'
    message += '</div>'
    message += '<div class="col-xs-4">'
    message += '  <input class="conditionAttr form-control radio-inline" data-l1key="operande" style="width: calc(100% - 45px);" />'
    message += '  <button type="button" class="btn btn-default cursor bt_selectCmdFromModal"><i class="fas fa-list-alt"></i></button>'
    message += '</div>'
    message += '</div>'
  }

  if (subType == 'binary') {
    message += '<div class="col-xs-7">'
    message += '<input class="conditionAttr" data-l1key="operator" value="==" style="display : none;" />'
    message += '  <select class="conditionAttr form-control" data-l1key="operande">'
    message += '    <option value="1">{{Ouvert}}</option>'
    message += '    <option value="0">{{Fermé}}</option>'
    message += '    <option value="1">{{Allumé}}</option>'
    message += '    <option value="0">{{Eteint}}</option>'
    message += '    <option value="1">{{Déclenché}}</option>'
    message += '    <option value="0">{{Au repos}}</option>'
    message += '  </select>'
    message += '</div>'
    message += '</div>'
  }

  message += '<div class="form-group">'
  message += '<label class="col-xs-5 control-label" >{{Ensuite}}</label>'
  message += '<div class="col-xs-3">'
  message += '  <select class="conditionAttr form-control" data-l1key="next">'
  message += '    <option value="">{{rien}}</option>'
  message += '    <option value="ET">{{et}}</option>'
  message += '    <option value="OU">{{ou}}</option>'
  message += '  </select>'
  message += '</div>'
  message += '</div>'
  message += '</div></div>'
  message += '</form></div></div>'
  return message
}

$divScenario.on('click', '.bt_selectCmdExpression', function(event) {
  var el = $(this)
  var expression = $(this).closest('.expression')
  var type = 'info'
  if (expression.find('.expressionAttr[data-l1key=type]').value() == 'action') {
    type = 'action'
  }

  jeedom.cmd.getSelectModal({
    cmd: {
      type: type
    }
  }, function(result) {
    if (expression.find('.expressionAttr[data-l1key=type]').value() == 'action') {
      setUndoStack()
      expression.find('.expressionAttr[data-l1key=expression]').value(result.human)
      jeedom.cmd.displayActionOption(expression.find('.expressionAttr[data-l1key=expression]').value(), '', function(html) {
        expression.find('.expressionOptions').html(html)
        jeedomUtils.taAutosize()
        updateTooltips()
      })
    }

    if (expression.find('.expressionAttr[data-l1key=type]').value() == 'condition') {
      var condType = el.closest('.subElement').get(0)
      if (!$(condType).hasClass('subElementIF') && !$(condType).hasClass('subElementFOR')) {
        expression.find('.expressionAttr[data-l1key=expression]').atCaret('insert', result.human)
        return
      }

      var message = getSelectCmdExpressionMessage(result.cmd.subType, result.human)
      bootbox.dialog({
        title: "{{Ajout d'une nouvelle condition}}",
        message: message,
        size: 'large',
        buttons: {
          "{{Ne rien mettre}}": {
            className: "btn-default",
            callback: function() {
              expression.find('.expressionAttr[data-l1key=expression]').atCaret('insert', result.human)
            }
          },
          success: {
            label: "{{Valider}}",
            className: "btn-primary",
            callback: function() {
              setUndoStack()
              modifyWithoutSave = true
              var condition = result.human
              condition += ' ' + $('.conditionAttr[data-l1key=operator]').value()
              if (result.cmd.subType == 'string') {
                if ($('.conditionAttr[data-l1key=operator]').value() == 'matches') {
                  condition += ' "/' + $('.conditionAttr[data-l1key=operande]').value() + '/"'
                } else {
                  condition += " '" + $('.conditionAttr[data-l1key=operande]').value() + "'"
                }
              } else {
                condition += ' ' + $('.conditionAttr[data-l1key=operande]').value()
              }
              condition += ' ' + $('.conditionAttr[data-l1key=next]').value() + ' '
              expression.find('.expressionAttr[data-l1key=expression]').atCaret('insert', condition)
              if ($('.conditionAttr[data-l1key=next]').value() != '') {
                el.click()
              }
            }
          },
        }
      })
    }
  })
})

$divScenario.on('click', '.bt_selectOtherActionExpression', function(event) {
  var expression = $(this).closest('.expression')
  jeedom.getSelectActionModal({
    scenario: true
  }, function(result) {
    setUndoStack()
    expression.find('.expressionAttr[data-l1key=expression]').value(result.human);
    jeedom.cmd.displayActionOption(expression.find('.expressionAttr[data-l1key=expression]').value(), '', function(html) {
      expression.find('.expressionOptions').html(html)
      jeedomUtils.taAutosize()
    })
  })
})

$divScenario.on('click', '.bt_selectScenarioExpression', function(event) {
  var expression = $(this).closest('.expression')
  jeedom.scenario.getSelectModal({}, function(result) {
    if (expression.find('.expressionAttr[data-l1key=type]').value() == 'action') {
      expression.find('.expressionAttr[data-l1key=expression]').value(result.human)
    }
    if (expression.find('.expressionAttr[data-l1key=type]').value() == 'condition') {
      expression.find('.expressionAttr[data-l1key=expression]').atCaret('insert', result.human)
    }
  })
})

$divScenario.on('click', '.bt_selectGenericExpression', function(event) {
  var expression = $(this).closest('.expression')
  jeedom.config.getGenericTypeModal({type: 'info', object: true}, function(result) {
    expression.find('.expressionAttr[data-l1key=expression]').atCaret('insert', result.human)
  })
})

$divScenario.on('click', '.bt_selectEqLogicExpression', function(event) {
  var expression = $(this).closest('.expression')
  jeedom.eqLogic.getSelectModal({}, function(result) {
    if (expression.find('.expressionAttr[data-l1key=type]').value() == 'action') {
      expression.find('.expressionAttr[data-l1key=expression]').value(result.human)
    }
    if (expression.find('.expressionAttr[data-l1key=type]').value() == 'condition') {
      expression.find('.expressionAttr[data-l1key=expression]').atCaret('insert', result.human)
    }
  })
})

$divScenario.on('focusout', '.expression .expressionAttr[data-l1key=expression]', function(event) {
  var el = $(this)
  if (el.closest('.expression').find('.expressionAttr[data-l1key=type]').value() == 'action') {
    var expression = el.closest('.expression').getValues('.expressionAttr')
    jeedom.cmd.displayActionOption(el.value(), init(expression[0].options), function(html) {
      el.closest('.expression').find('.expressionOptions').html(html)
      jeedomUtils.taAutosize()
      updateTooltips()
    })
  }
})

//COPY - PASTE
function removeObjectProp(obj, propToDelete) {
  for (var property in obj) {
    if (obj.hasOwnProperty(property)) {
      if (typeof obj[property] == 'object') {
        removeObjectProp(obj[property], propToDelete)
      } else {
        if (property === propToDelete) {
          delete obj[property]
        }
      }
    }
  }
}
$divScenario.on('click', '.bt_copyElement', function(event) {
  var clickedBloc = $(this).closest('.element')
  if (!clickedBloc.parent('#div_scenarioElement').length) {
    SC_CLIPBOARD = clickedBloc.parent().parent()
  } else {
    SC_CLIPBOARD = clickedBloc
  }
  SC_CLIPBOARD = getElement(clickedBloc)
  //delete all id in properties to make it unique:
  removeObjectProp(SC_CLIPBOARD, 'id')
  localStorage.removeItem('jeedomScCopy')
  localStorage.setItem('jeedomScCopy', JSON.stringify(SC_CLIPBOARD))

  if (event.ctrlKey || event.metaKey) {
    setUndoStack()
    clickedBloc.remove()
  }
  modifyWithoutSave = true
})

$divScenario.on('click', '.bt_pasteElement', function(event) {
  var clickedBloc = $(this).closest('.element')
  if (localStorage.getItem('jeedomScCopy')) {
    SC_CLIPBOARD = JSON.parse(localStorage.getItem('jeedomScCopy'))
  }

  setUndoStack()

  actionOptions = []
  var pastedElement = addElement(SC_CLIPBOARD)
  pastedElement = $(pastedElement)

  //Are we pasting inside an expresion:
  if (clickedBloc.parent('#div_scenarioElement').length) {
    //get the element if copied from an expression:
    if (pastedElement.hasClass('expression')) pastedElement = pastedElement.find('.element')
    pastedElement.insertAfter(clickedBloc)
  } else {
    //make it an expression if not yet:
    if (pastedElement.hasClass('expression')) {
      pastedElement.insertAfter(clickedBloc.parent().parent())
    } else {
      var newDiv = '<div class="expression sortable col-xs-12">'
      newDiv += '<input class="expressionAttr" data-l1key="type" style="display: none;" value="element">'
      newDiv += '<div class="col-xs-12" id="insertHere">'
      newDiv += '</div>'
      newDiv += '</div>'
      $(newDiv).insertAfter(clickedBloc.parent().parent())
      pastedElement.appendTo('#insertHere')
    }
  }

  //Synch collapsed elements:
  pastedElement.find('i.fa-eye-slash').each(function() {
    $(this).parents('.element').first().addClass('elementCollapse')
  })
  //Synch disabled elements:
  pastedElement.find('input[data-l1key="options"][data-l2key="enable"]:not(:checked)').each(function() {
    $(this).trigger('change')
  })

  updateElseToggle()

  if (event.ctrlKey || event.metaKey) {
    clickedBloc.remove()
  }

  setScenarioActionsOptions()
  updateSortable()
  updateTooltips()
  jeedom.scenario.setAutoComplete()
  setEditors()
  modifyWithoutSave = true
})

$divScenario.on('mouseenter', '.bt_sortable', function() {
  var expressions = $(this).closest('.expressions')
  $("#div_scenarioElement").sortable({
    cursor: "move",
    grid: [5, 15],
    items: ".sortable",
    appendTo: $("#div_scenarioElement"),
    zIndex: 0,
    opacity: 0.5,
    forcePlaceholderSize: true,
    placeholder: "sortable-placeholder",
    start: function(event, ui) {
      $('.dropdown.open').removeClass('open')
      if (expressions.find('.sortable').length < 3) {
        expressions.find('.sortable.empty').show()
      }
    },
    change: function(event, ui) {
      if (ui.placeholder.next().length == 0) {
        ui.placeholder.addClass('sortable-placeholderLast')
      } else {
        ui.placeholder.removeClass('sortable-placeholderLast')
      }

      var getClass = true
      if (ui.placeholder.parent().hasClass('subElement')) {
        getClass = false
      }
      if (ui.helper.hasClass('expressionACTION') && ui.placeholder.parent().attr('id') == 'div_scenarioElement') {
        getClass = false
      }
      var thisSub = ui.placeholder.parents('.expressions').parents('.subElement')
      if (thisSub.hasClass('subElementCOMMENT') || thisSub.hasClass('subElementCODE')) {
        getClass = false
      }

      if (getClass) {
        ui.placeholder.addClass('sortable-placeholder')
      } else {
        ui.placeholder.removeClass('sortable-placeholder')
      }
    },
    update: function(event, ui) {
      if (ui.item.closest('.subElement').hasClass('subElementCOMMENT')) {
        $("#div_scenarioElement").sortable('cancel')
      }
      if (ui.item.findAtDepth('.element', 2).length == 1 && ui.item.parent().attr('id') == 'div_scenarioElement') {
        ui.item.replaceWith(ui.item.findAtDepth('.element', 2))
      }

      if (ui.item.hasClass('element') && ui.item.parent().attr('id') != 'div_scenarioElement') {
        ui.item.find('.expressionAttr,.subElementAttr,.elementAttr').each(function() {
          var value = $(this).value()
          if (value != undefined && value != '') {
            $(this).attr('data-tmp-value', value)
          }
        })
        var el = $(addExpression({
          type: 'element',
          element: {
            html: ui.item.wrapAll("<div/>").parent().html()
          }
        }))
        var value
        el.find('.expressionAttr,.subElementAttr,.elementAttr').each(function() {
          value = $(this).attr('data-tmp-value')
          if (value != undefined && value != '') {
            $(this).value(value)
          }
          $(this).removeAttr('data-tmp-value')
        })
        ui.item.parent().replaceWith(el)
      }

      if (ui.item.hasClass('expression') && ui.item.parent().attr('id') == 'div_scenarioElement') {
        $("#div_scenarioElement").sortable("cancel")
      }
      if (ui.item.closest('.subElement').hasClass('noSortable')) {
        $("#div_scenarioElement").sortable("cancel")
      }

      updateTooltips()
      updateSortable()
    },
    stop: function(event, ui) {
      $("#div_scenarioElement").sortable("disable")
      ui.item.attr('style', '')
      modifyWithoutSave = true
    }
  })
  $("#div_scenarioElement").sortable("enable")
})

$divScenario.on('mousedown', '.bt_sortable', function() {
  setUndoStack()
})

$divScenario.on('mouseout', '.bt_sortable', function() {
  $("#div_scenarioElement").sortable("disable")
})

$divScenario.on('click', '.subElementAttr[data-l1key=options][data-l2key=allowRepeatCondition]', function() {
  if ($(this).attr('value') == 0) {
    $(this).attr('value', 1).html('<span><i class="fas fa-ban text-danger"></i></span>')
  } else {
    $(this).attr('value', 0).html('<span><i class="fas fa-sync"></span>')
  }
})

/**************** Initialisation **********************/
$divScenario.on('change', '.scenarioAttr:visible', function() {
  modifyWithoutSave = true
})

$divScenario.on('change', '.expressionAttr:visible', function() {
  modifyWithoutSave = true
})

$divScenario.on('change', '.elementAttr:visible', function() {
  modifyWithoutSave = true
})

$divScenario.on('change', '.subElementAttr:visible', function() {
  modifyWithoutSave = true
})

if (is_numeric(getUrlVars('id'))) {
  if ($('.scenarioDisplayCard[data-scenario_id=' + getUrlVars('id') + ']').length != 0) {
    $('.scenarioDisplayCard[data-scenario_id=' + getUrlVars('id') + ']').click()
  }
}

function updateSortable() {
  $('.element').removeClass('sortable');
  $('#div_scenarioElement > .element').addClass('sortable')
  $('.subElement .expressions').each(function() {
    if ($(this).children('.sortable:not(.empty)').length > 0) {
      $(this).children('.sortable.empty').hide()
    } else {
      $(this).children('.sortable.empty').show()
    }
  })
}

function updateElseToggle() {
  $('.subElementELSE').each(function() {
    if (!$(this).closest('.element').children('.subElementTHEN').find('.bt_showElse:first i').hasClass('fa-sort-down')) {
      if ($(this).children('.expressions').children('.expression').length == 0) {
        $(this).closest('.element').children('.subElementTHEN').find('.bt_showElse').first().trigger('click')
      }
    }
  })
}

function updateElementCollpase() {
  $('.bt_collapse').each(function() {
    if ($(this).value() == 0) {
      $(this).closest('.element').removeClass('elementCollapse')
    } else {
      $(this).closest('.element').addClass('elementCollapse')
    }
  })
}


var actionOptions = []

function setScenarioActionsOptions() {
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
      $.showLoading()
      for (var i in data) {
        $('#' + data[i].id).append(data[i].html.html)
      }
      $.hideLoading()
      jeedomUtils.taAutosize()
    }
  })
}

function printScenario(_id) {
  $.hideAlert()
  $.showLoading()
  $('#emptyModeWarning').hide()
  jeedom.scenario.update[_id] = function(_options) {
    if (_options.scenario_id = !$divScenario.getValues('.scenarioAttr')[0]['id']) {
      return
    }
    switch (_options.state) {
      case 'error':
        $('#bt_stopScenario').hide()
        $('#span_ongoing').text('{{Erreur}}')
        $('#span_ongoing').removeClass('label-info label-danger label-success label-info').addClass('label-warning')
        break
      case 'on':
        $('#bt_stopScenario').show()
        $('#span_ongoing').text('{{Actif}}')
        $('#span_ongoing').removeClass('label-info label-danger label-warning label-info').addClass('label-success')
        break
      case 'starting':
        $('#bt_stopScenario').show()
        $('#span_ongoing').text('{{Démarrage}}')
        $('#span_ongoing').removeClass('label-success label-danger label-warning label-info').addClass('label-warning')
        break
      case 'in progress':
        $('#bt_stopScenario').show()
        $('#span_ongoing').text('{{En cours}}')
        $('#span_ongoing').removeClass('label-success label-danger label-warning label-info').addClass('label-info')
        break
      case 'stop':
        $('#bt_stopScenario').hide()
        $('#span_ongoing').text('{{Arrêté}}')
        $('#span_ongoing').removeClass('label-info label-success label-warning label-info').addClass('label-danger')
        break
    }
  }
  jeedom.scenario.get({
    id: _id,
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      });
    },
    success: function(data) {
      $('.scenarioAttr').value('')
      $('.scenarioAttr[data-l1key=object_id] option').first().attr('selected', true)
      $('.scenarioAttr[data-l1key=object_id]').val('')
      $divScenario.setValues(data, '.scenarioAttr')
      data.lastLaunch = (data.lastLaunch == null) ? '{{Jamais}}' : data.lastLaunch
      $('#span_lastLaunch').text(data.lastLaunch)

      $.clearDivContent('div_scenarioElement')
      $('.provokeMode').empty()
      $('.scheduleMode').empty()
      $('.scenarioAttr[data-l1key=mode]').trigger('change')
      for (var i in data.schedules) {
        $('#div_schedules').schedule.display(data.schedules[i])
      }

      jeedom.scenario.update[_id](data)
      if (data.isActive != 1) {
        $('#in_ongoing').removeClass('label-danger').removeClass('label-success').text('{{Inactif}}')
      }

      if ($.isArray(data.trigger)) {
        for (var i in data.trigger) {
          if (data.trigger[i] != '' && data.trigger[i] != null) {
            addTrigger(data.trigger[i])
          }
        }
      } else {
        if (data.trigger != '' && data.trigger != null) {
          addTrigger(data.trigger)
        }
      }

      if ($.isArray(data.schedule)) {
        for (var i in data.schedule) {
          if (data.schedule[i] != '' && data.schedule[i] != null) {
            addSchedule(data.schedule[i])
          }
        }
      } else {
        if (data.schedule != '' && data.schedule != null) {
          addSchedule(data.schedule)
        }
      }
      $('.scenario_link').empty()
      var html = ''
      if (data.scenario_link.scenario) {
        for (var i in data.scenario_link.scenario) {
          if (data.scenario_link.scenario[i].isActive == 1) {
            html += '<span class="label label-success cursor scenario_link" data-scenario_id="' + i + '">' + data.scenario_link.scenario[i].name + '</span><br/>'
          } else {
            html += '<span class="label label-danger cursor scenario_link" data-scenario_id="' + i + '">' + data.scenario_link.scenario[i].name + '</span><br/>'
          }
        }
      }
      $('.scenario_link').append(html)
      if (data.elements.length == 0) {
        $('#div_scenarioElement').append('<center class="span_noScenarioElement"><span>{{Pour constituer votre scénario, veuillez ajouter des blocs}}.</span></center>')
      }
      actionOptions = []
      var elements = ''
      for (var i in data.elements) {
        elements += addElement(data.elements[i])
      }
      $('#div_scenarioElement').append(elements)
      $('.subElementAttr[data-l1key=options][data-l2key=enable]').trigger('change')
      $('.expressionAttr[data-l1key=options][data-l2key=enable]').trigger('change')
      setScenarioActionsOptions()
      $('#div_editScenario').show()
      updateSortable()
      jeedom.scenario.setAutoComplete()
      updateElementCollpase()
      updateElseToggle()
      jeedomUtils.taAutosize()
      var title = ''
      if (data.name) title = data.name + ' - Jeedom'
      var hash = window.location.hash
      jeedomUtils.addOrUpdateUrl('id', data.id, title)
      if (hash == '') {
        $('.nav-tabs a[href="#generaltab"]').click()
      } else {
        window.location.hash = hash
      }
      setTimeout(function() {
        setEditors()
      }, 100)
      modifyWithoutSave = false
      resetUndo()
      setTimeout(function() {
        modifyWithoutSave = false
      }, 1000)
      setTimeout(function() {
        checkNoMode()
        updateTooltips()
      }, 500)

      jeedom.scenario.setAutoComplete()
      $('#humanNameTag').html(data.humanNameTag)
    }
  })
}

function saveScenario(_callback) {
  $.hideAlert()
  var scenario = $divScenario.getValues('.scenarioAttr')[0]
  if (typeof scenario.trigger == 'undefined') {
    scenario.trigger = ''
  }
  if (typeof scenario.schedule == 'undefined') {
    scenario.schedule = ''
  }
  var elements = []
  $('#div_scenarioElement').children('.element').each(function() {
    elements.push(getElement($(this)))
  })
  scenario.elements = elements
  jeedom.scenario.save({
    scenario: scenario,
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(data) {
      modifyWithoutSave = false
      resetUndo()
      var url = 'index.php?v=d&p=scenario&id=' + data.id + '&saveSuccessFull=1'
      if (window.location.hash != '') {
        url += window.location.hash
      }
      jeedomUtils.loadPage(url)
      if (typeof _callback == 'function') {
        _callback()
      }
    }
  })
}

function addTrigger(_trigger) {
  var div = '<div class="form-group trigger">'
  div += '<label class="col-xs-3 control-label">{{Evénement}}</label>'
  div += '<div class="col-xs-9">'
  div += '<div class="input-group">'
  div += '<input class="scenarioAttr input-sm form-control roundedLeft" data-l1key="trigger" value="' + _trigger.replace(/"/g, '&quot;') + '" >'
  div += '<span class="input-group-btn">'
  div += '<a class="btn btn-default btn-sm cursor bt_selectTrigger" tooltip="{{Choisir une commande}}"><i class="fas fa-list-alt"></i></a>'
  div += '<a class="btn btn-default btn-sm cursor bt_selectGenericTrigger" tooltip="{{Choisir un Type Générique}}"><i class="fas fa-puzzle-piece"></i></a>'
  div += '<a class="btn btn-default btn-sm cursor bt_selectDataStoreTrigger" tooltip="{{Choisir une variable}}"><i class="fas fa-calculator"></i></a>'
  div += '<a class="btn btn-default btn-sm cursor bt_removeTrigger roundedRight"><i class="fas fa-minus-circle"></i></a>'
  div += '</span>'
  div += '</div>'
  div += '</div>'
  div += '</div>'
  $('.provokeMode').append(div)
}

function addSchedule(_schedule) {
  var div = '<div class="form-group schedule">'
  div += '<label class="col-xs-3 control-label">{{Programmation}}</label>'
  div += '<div class="col-xs-9">'
  div += '<div class="input-group">'
  div += '<input class="scenarioAttr input-sm form-control roundedLeft" data-l1key="schedule" value="' + _schedule.replace(/"/g, '&quot;') + '">'
  div += '<span class="input-group-btn">'
  div += '<a class="btn btn-default btn-sm cursor jeeHelper" data-helper="cron"><i class="fas fa-question-circle"></i></a>'
  div += '<a class="btn btn-default btn-sm cursor bt_removeSchedule roundedRight"><i class="fas fa-minus-circle"></i></a>'
  div += '</span>'
  div += '</div>'
  div += '</div>'
  div += '</div>'
  $('.scheduleMode').append(div)
}

function addExpression(_expression) {
  if (!isset(_expression) || !isset(_expression.type) || _expression.type == '') {
    return ''
  }
  var sortable = 'sortable'
  if (_expression.type == 'condition' || _expression.type == 'code') {
    sortable = 'noSortable'
  }

  var retour = '<div class="expression ' + sortable + ' col-xs-12" >'
  if (_expression.type == 'action') {
    retour = '<div class="expression expressionACTION ' + sortable + ' col-xs-12" >'
  }
  retour += '<input class="expressionAttr" data-l1key="id" style="display : none;" value="' + init(_expression.id) + '"/>'
  retour += '<input class="expressionAttr" data-l1key="scenarioSubElement_id" style="display : none;" value="' + init(_expression.scenarioSubElement_id) + '"/>'
  retour += '<input class="expressionAttr" data-l1key="type" style="display : none;" value="' + init(_expression.type) + '"/>'
  switch (_expression.type) {
    case 'condition':
      if (isset(_expression.expression)) {
        _expression.expression = _expression.expression.replace(/"/g, '&quot;')
      }
      retour += '<div class="input-group input-group-sm" >'
      retour += '<input class="expressionAttr form-control roundedLeft" data-l1key="expression" value="' + init(_expression.expression) + '" />'
      retour += '<span class="input-group-btn">'
      retour += '<button type="button" class="btn btn-default cursor bt_selectCmdExpression" tooltip="{{Rechercher une commande}}"><i class="fas fa-list-alt"></i></button>'
      retour += '<button type="button" class="btn btn-default cursor bt_selectGenericExpression" tooltip="{{Rechercher un type générique}}"><i class="fas fa-puzzle-piece"></i></button>'
      retour += '<button type="button" class="btn btn-default cursor bt_selectScenarioExpression" tooltip="{{Rechercher un scénario}}"><i class="fas fa-history"></i></button>'
      retour += '<button type="button" class="btn btn-default cursor bt_selectEqLogicExpression roundedRight"  tooltip="{{Rechercher un équipement}}"><i class="fas fa-cube"></i></button>'
      retour += '</span>'
      retour += '</div>'
      break
    case 'element':
      retour += '<div class="col-xs-12" >';
      if (isset(_expression.element) && isset(_expression.element.html)) {
        retour += _expression.element.html
      } else {
        var element = addElement(_expression.element, true)
        if ($.trim(element) == '') {
          return ''
        }
        retour += element
      }
      retour += '</div>'
      break
    case 'action':
      retour += '<div class="col-xs-1" >'
      retour += '<i class="fas fa-arrows-alt-v cursor bt_sortable" ></i>'
      if (!isset(_expression.options) || !isset(_expression.options.enable) || _expression.options.enable == 1) {
        retour += '<input type="checkbox" class="expressionAttr" data-l1key="options" data-l2key="enable" checked  tooltip="{{Décocher pour désactiver l\'action}}"/>'
      } else {
        retour += '<input type="checkbox" class="expressionAttr" data-l1key="options" data-l2key="enable"  tooltip="{{Décocher pour désactiver l\'action}}"/>'
      }
      if (!isset(_expression.options) || !isset(_expression.options.background) || _expression.options.background == 0) {
        retour += '<input type="checkbox" class="expressionAttr" data-l1key="options" data-l2key="background"  tooltip="{{Cocher pour que la commande s\'exécute en parallèle des autres actions}}"/>'
      } else {
        retour += '<input type="checkbox" class="expressionAttr" data-l1key="options" data-l2key="background" checked  tooltip="{{Cocher pour que la commande s\'exécute en parallèle des autres actions}}"/>'
      }
      var expression_txt = init(_expression.expression)
      if (typeof expression_txt != 'string') {
        expression_txt = json_encode(expression_txt)
      }
      retour += '</div>'
      retour += '<div class="col-xs-4" ><div class="input-group input-group-sm">'
      retour += '<span class="input-group-btn">'
      retour += '<button class="btn btn-default bt_removeExpression roundedLeft" type="button" tooltip="{{Supprimer l\'action}}"><i class="fas fa-minus-circle"></i></button>'
      retour += '</span>'
      retour += '<input class="expressionAttr form-control" data-l1key="expression" value="' + expression_txt.replace(/"/g, '&quot;') + '"/>'
      retour += '<span class="input-group-btn">'
      retour += '<button class="btn btn-default bt_selectOtherActionExpression" type="button" tooltip="{{Sélectionner un mot-clé}}"><i class="fas fa-tasks"></i></button>'
      retour += '<button class="btn btn-default bt_selectCmdExpression roundedRight" type="button" tooltip="{{Sélectionner la commande}}"><i class="fas fa-list-alt"></i></button>'
      retour += '</span>'
      retour += '</div></div>'
      var actionOption_id = jeedomUtils.uniqId()
      retour += '<div class="col-xs-7 expressionOptions"  id="' + actionOption_id + '">'
      retour += '</div>'
      actionOptions.push({
        expression: init(_expression.expression, ''),
        options: _expression.options,
        id: actionOption_id
      })
      break
    case 'code':
      retour += '<div>'
      retour += '<textarea class="expressionAttr form-control" data-l1key="expression">' + init(_expression.expression) + '</textarea>'
      retour += '</div>';
      break;
    case 'comment':
      retour += '<textarea class="expressionAttr form-control" data-l1key="expression">' + init(_expression.expression) + '</textarea>'
      break
  }
  retour += '</div>'
  return retour
}

function addSubElement(_subElement) {
  if (!isset(_subElement.type) || _subElement.type == '') {
    return ''
  }
  if (!isset(_subElement.options)) {
    _subElement.options = {}
  }
  var noSortable = ''
  if (_subElement.type == 'if' || _subElement.type == 'for' || _subElement.type == 'code') {
    noSortable = 'noSortable'
  }

  var blocClass = ''
  switch (_subElement.type) {
    case 'if':
      blocClass = 'subElementIF'
      break
    case 'then':
      blocClass = 'subElementTHEN'
      break
    case 'else':
      blocClass = 'subElementELSE'
      break
    case 'for':
      blocClass = 'subElementFOR'
      break
    case 'in':
      blocClass = 'subElementIN'
      break
    case 'at':
      blocClass = 'subElementAT'
      break
    case 'do':
      blocClass = 'subElementDO'
      break
    case 'code':
      blocClass = 'subElementCODE'
      break
    case 'comment':
      blocClass = 'subElementCOMMENT'
      break
    case 'action':
      blocClass = 'subElementACTION'
      break
  }
  var retour = '<div class="subElement ' + blocClass + ' ' + noSortable + '">'
  retour += '<input class="subElementAttr" data-l1key="id" style="display : none;" value="' + init(_subElement.id) + '"/>'
  retour += '<input class="subElementAttr" data-l1key="scenarioElement_id" style="display : none;" value="' + init(_subElement.scenarioElement_id) + '"/>'
  retour += '<input class="subElementAttr" data-l1key="type" style="display : none;" value="' + init(_subElement.type) + '"/>'

  switch (_subElement.type) {
    case 'if':
      retour += '<input class="subElementAttr" data-l1key="subtype" style="display : none;" value="condition"/>'
      retour += '<div>'
      retour += '<i class="bt_sortable fas fa-arrows-alt-v pull-left cursor" ></i>'
      if (!isset(_subElement.options) || !isset(_subElement.options.collapse) || _subElement.options.collapse == 0) {
        retour += '<a class="bt_collapse cursor subElementAttr" tooltip="{{Masquer ce bloc.<br>Ctrl+click: tous.}}" data-l1key="options" data-l2key="collapse" value="0"><i class="far fa-eye"></i></a>'
      } else {
        retour += '<a class="bt_collapse cursor subElementAttr" tooltip="{{Afficher ce bloc.<br>Ctrl+click: tous.}}" data-l1key="options" data-l2key="collapse" value="1"><i class="far fa-eye-slash"></i></a>'
      }
      if (!isset(_subElement.options) || !isset(_subElement.options.enable) || _subElement.options.enable == 1) {
        retour += '<input type="checkbox" class="subElementAttr" data-l1key="options" data-l2key="enable" checked tooltip="{{Décocher pour désactiver l\'élément}}" />'
      } else {
        retour += '<input type="checkbox" class="subElementAttr" data-l1key="options" data-l2key="enable" tooltip="{{Décocher pour désactiver l\'élément}}" />'
      }
      retour += '</div>'
      retour += '<div><legend >{{SI}}</legend></div>'

      retour += '<div >'
      if (!isset(_subElement.options) || !isset(_subElement.options.allowRepeatCondition) || _subElement.options.allowRepeatCondition == 0) {
        retour += '<a class="bt_repeat cursor subElementAttr" tooltip="{{Autoriser ou non la répétition des actions si l\'évaluation de la condition est la même que la précédente}}" data-l1key="options" data-l2key="allowRepeatCondition" value="0"><span><i class="fas fa-sync"></i></span></a>'
      } else {
        retour += '<a class="bt_repeat cursor subElementAttr" tooltip="{{Autoriser ou non la répétition des actions si l\'évaluation de la condition est la même que la précédente}}" data-l1key="options" data-l2key="allowRepeatCondition" value="1"><span><i class="fas fa-ban text-danger"></i></span></a>'
      }
      retour += '</div>'

      retour += '<div class="expressions" >'
      var expression = {
        type: 'condition'
      }
      if (isset(_subElement.expressions) && isset(_subElement.expressions[0])) {
        expression = _subElement.expressions[0]
      }
      retour += addExpression(expression);
      retour += '  </div>'
      retour = addElButtons(retour)
      break

    case 'then':
      retour += '<input class="subElementAttr" data-l1key="subtype" style="display : none;" value="action"/>'
      retour += '<div class="subElementFields">'
      retour += '<legend >{{ALORS}}</legend>'
      retour += getAddButton(true)
      retour += '</div>'
      retour += '<div class="expressions">'
      retour += '<div class="sortable empty" ></div>'
      if (isset(_subElement.expressions)) {
        for (var k in _subElement.expressions) {
          retour += addExpression(_subElement.expressions[k])
        }
      }
      retour += '</div>'
      break

    case 'else':
      retour += '<input class="subElementAttr subElementElse" data-l1key="subtype" style="display : none;" value="action"/>'
      retour += '<div class="subElementFields">'
      retour += '<legend >{{SINON}}</legend>'
      retour += getAddButton()
      retour += '</div>'
      retour += '<div class="expressions">'
      retour += '<div class="sortable empty" ></div>'
      if (isset(_subElement.expressions)) {
        for (var k in _subElement.expressions) {
          retour += addExpression(_subElement.expressions[k])
        }
      }
      retour += '</div>'
      break

    case 'for':
      retour += '<input class="subElementAttr" data-l1key="subtype" style="display : none;" value="condition"/>'
      retour += '<div>'
      retour += '<i class="bt_sortable fas fa-arrows-alt-v pull-left cursor"></i>'
      if (!isset(_subElement.options) || !isset(_subElement.options.collapse) || _subElement.options.collapse == 0) {
        retour += '<a class="bt_collapse cursor subElementAttr" tooltip="{{Masquer ce bloc.<br>Ctrl+click: tous.}}" data-l1key="options" data-l2key="collapse" value="0"><i class="far fa-eye"></i></a>'
      } else {
        retour += '<a class="bt_collapse cursor subElementAttr" tooltip="{{Afficher ce bloc.<br>Ctrl+click: tous.}}" data-l1key="options" data-l2key="collapse" value="1"><i class="far fa-eye-slash"></i></a>'
      }
      if (!isset(_subElement.options) || !isset(_subElement.options.enable) || _subElement.options.enable == 1) {
        retour += '<input type="checkbox" class="subElementAttr" data-l1key="options" data-l2key="enable" checked tooltip="{{Décocher pour désactiver l\'élément}}" />'
      } else {
        retour += '<input type="checkbox" class="subElementAttr" data-l1key="options" data-l2key="enable" tooltip="{{Décocher pour désactiver l\'élément}}" />'
      }
      retour += '</div>'
      retour += '<div>'
      retour += '<legend >{{DE 1 A}}</legend>'
      retour += '</div>'
      retour += '<div class="expressions" >'
      var expression = {
        type: 'condition'
      }
      if (isset(_subElement.expressions) && isset(_subElement.expressions[0])) {
        expression = _subElement.expressions[0]
      }
      retour += addExpression(expression)
      retour += '</div>'
      retour = addElButtons(retour)
      break

    case 'in':
      retour += '<input class="subElementAttr" data-l1key="subtype" style="display : none;" value="condition"/>'
      retour += '<div>'
      retour += '<i class="bt_sortable fas fa-arrows-alt-v pull-left cursor"></i>'
      if (!isset(_subElement.options) || !isset(_subElement.options.collapse) || _subElement.options.collapse == 0) {
        retour += '<a class="bt_collapse cursor subElementAttr" tooltip="{{Masquer ce bloc.<br>Ctrl+click: tous.}}" data-l1key="options" data-l2key="collapse" value="0"><i class="far fa-eye"></i></a>'
      } else {
        retour += '<a class="bt_collapse cursor subElementAttr" tooltip="{{Afficher ce bloc.<br>Ctrl+click: tous.}}" data-l1key="options" data-l2key="collapse" value="1"><i class="far fa-eye-slash"></i></a>'
      }
      if (!isset(_subElement.options) || !isset(_subElement.options.enable) || _subElement.options.enable == 1) {
        retour += '<input type="checkbox" class="subElementAttr" data-l1key="options" data-l2key="enable" checked tooltip="{{Décocher pour désactiver l\'élément}}" />'
      } else {
        retour += '<input type="checkbox" class="subElementAttr" data-l1key="options" data-l2key="enable" tooltip="{{Décocher pour désactiver l\'élément}}" />'
      }
      retour += '</div>'
      retour += '<div>'
      retour += '<legend tooltip="{{Action DANS x minutes}}">{{DANS}}</legend>'
      retour += '</div>'
      retour += '<div class="expressions" >'
      var expression = {
        type: 'condition'
      }
      if (isset(_subElement.expressions) && isset(_subElement.expressions[0])) {
        expression = _subElement.expressions[0]
      }
      retour += addExpression(expression)
      retour += '</div>'
      retour = addElButtons(retour)
      break

    case 'at':
      retour += '<input class="subElementAttr" data-l1key="subtype" style="display : none;" value="condition"/>'
      retour += '<div>'
      retour += '<i class="bt_sortable fas fa-arrows-alt-v pull-left cursor"></i>'
      if (!isset(_subElement.options) || !isset(_subElement.options.collapse) || _subElement.options.collapse == 0) {
        retour += '<a class="bt_collapse cursor subElementAttr" tooltip="{{Masquer ce bloc.<br>Ctrl+click: tous.}}" data-l1key="options" data-l2key="collapse" value="0"><i class="far fa-eye"></i></a>'
      } else {
        retour += '<a class="bt_collapse cursor subElementAttr" tooltip="{{Afficher ce bloc.<br>Ctrl+click: tous.}}" data-l1key="options" data-l2key="collapse" value="1"><i class="far fa-eye-slash"></i></a>'
      }
      if (!isset(_subElement.options) || !isset(_subElement.options.enable) || _subElement.options.enable == 1) {
        retour += '<input type="checkbox" class="subElementAttr" data-l1key="options" data-l2key="enable" checked tooltip="{{Décocher pour désactiver l\'élément}}" />'
      } else {
        retour += '<input type="checkbox" class="subElementAttr" data-l1key="options" data-l2key="enable" tooltip="{{Décocher pour désactiver l\'élément}}" />'
      }
      retour += '</div>'
      retour += '<div>'
      retour += '<legend >{{A}}</legend><span>(Hmm)</span>'
      retour += '</div>'
      retour += '<div class="expressions" >'
      var expression = {
        type: 'condition'
      }
      if (isset(_subElement.expressions) && isset(_subElement.expressions[0])) {
        expression = _subElement.expressions[0]
      }
      retour += addExpression(expression)
      retour += '</div>'
      retour = addElButtons(retour)
      break

    case 'do':
      retour += '<input class="subElementAttr" data-l1key="subtype" style="display : none;" value="action"/>'
      retour += '<div class="subElementFields">'
      retour += '<legend >{{FAIRE}}</legend>'
      retour += getAddButton()
      retour += '</div>'
      retour += '<div class="expressions">'
      retour += '<div class="sortable empty" ></div>'
      if (isset(_subElement.expressions)) {
        for (var k in _subElement.expressions) {
          retour += addExpression(_subElement.expressions[k])
        }
      }
      retour += '</div>'
      break

    case 'code':
      retour += '<input class="subElementAttr" data-l1key="subtype" style="display : none;" value="action"/>'
      retour += '<div>'
      retour += '<i class="bt_sortable fas fa-arrows-alt-v pull-left cursor"></i>'
      if (!isset(_subElement.options) || !isset(_subElement.options.collapse) || _subElement.options.collapse == 0) {
        retour += '<a class="bt_collapse cursor subElementAttr" tooltip="{{Masquer ce bloc.<br>Ctrl+click: tous.}}" data-l1key="options" data-l2key="collapse" value="0"><i class="far fa-eye"></i></a>'
      } else {
        retour += '<a class="bt_collapse cursor subElementAttr" tooltip="{{Afficher ce bloc.<br>Ctrl+click: tous.}}" data-l1key="options" data-l2key="collapse" value="1"><i class="far fa-eye-slash"></i></a>'
      }
      if (!isset(_subElement.options) || !isset(_subElement.options.enable) || _subElement.options.enable == 1) {
        retour += '<input type="checkbox" class="subElementAttr" data-l1key="options" data-l2key="enable" checked tooltip="{{Décocher pour désactiver l\'élément}}" />'
      } else {
        retour += '<input type="checkbox" class="subElementAttr" data-l1key="options" data-l2key="enable" tooltip="{{Décocher pour désactiver l\'élément}}" />'
      }
      retour += '</div>'
      retour += '<div>'
      retour += '<legend >{{CODE}}</legend>'
      var expression = {
        type: 'code'
      }
      if (isset(_subElement.expressions) && isset(_subElement.expressions[0]) && typeof _subElement.expressions[0].expression == "string") {
        expression = _subElement.expressions[0]
        retour += '<div class="blocPreview">' + expression.expression.substring(0, 200).HTMLFormat() + '</div>'
      } else {
        retour += '<div class="blocPreview"></div>'
      }
      retour += '</div>'
      retour += '<div class="expressions">'
      retour += addExpression(expression)
      retour += '</div>'
      retour = addElButtons(retour)
      break

    case 'comment':
      retour += '<input class="subElementAttr" data-l1key="subtype" style="display : none;" value="comment"/>'
      retour += '<div>'
      retour += '<i class="bt_sortable fas fa-arrows-alt-v pull-left cursor"></i>'
      if (!isset(_subElement.options) || !isset(_subElement.options.collapse) || _subElement.options.collapse == 0) {
        retour += '<a class="bt_collapse cursor subElementAttr" tooltip="{{Masquer ce bloc.<br>Ctrl+click: tous.}}" data-l1key="options" data-l2key="collapse" value="0"><i class="far fa-eye"></i></a>'
      } else {
        retour += '<a class="bt_collapse cursor subElementAttr" tooltip="{{Afficher ce bloc.<br>Ctrl+click: tous.}}" data-l1key="options" data-l2key="collapse" value="1"><i class="far fa-eye-slash"></i></a>'
      }
      retour += '</div>'
      retour += '<div>'
      retour += '<legend >{{COMMENTAIRE}}</legend>'
      var expression = {
        type: 'comment'
      }
      if (isset(_subElement.expressions) && isset(_subElement.expressions[0])) {
        expression = _subElement.expressions[0]
        if (typeof expression.expression === 'object') {
          expression.expression = JSON.stringify(expression.expression, null, 2)
        }
        var txt = expression.expression.substring(0, 200).HTMLFormat()
        txt = '<b>' + txt.split('\n')[0] + '</b>' + txt.replace(txt.split('\n')[0], '')
        retour += '<div class="blocPreview">' + txt + '</div>'
      } else {
        retour += '<div class="blocPreview"></div>'
      }
      retour += '</div>'
      retour += '<div class="expressions">'
      retour += addExpression(expression)
      retour += '</div>'
      retour = addElButtons(retour)
      break

    case 'action':
      retour += '<input class="subElementAttr" data-l1key="subtype" style="display : none;" value="action"/>'
      retour += '<div>'
      retour += '<i class="bt_sortable fas fa-arrows-alt-v pull-left cursor"></i>'
      if (!isset(_subElement.options) || !isset(_subElement.options.collapse) || _subElement.options.collapse == 0) {
        retour += '<a class="bt_collapse cursor subElementAttr" tooltip="{{Masquer ce bloc.<br>Ctrl+click: tous.}}" data-l1key="options" data-l2key="collapse" value="0"><i class="far fa-eye"></i></a>'
      } else {
        retour += '<a class="bt_collapse cursor subElementAttr" tooltip="{{Afficher ce bloc.<br>Ctrl+click: tous.}}" data-l1key="options" data-l2key="collapse" value="1"><i class="far fa-eye-slash"></i></a>'
      }
      if (!isset(_subElement.options) || !isset(_subElement.options.enable) || _subElement.options.enable == 1) {
        retour += '<input type="checkbox" class="subElementAttr" data-l1key="options" data-l2key="enable" checked tooltip="{{Décocher pour désactiver l\'élément}}" />'
      } else {
        retour += '<input type="checkbox" class="subElementAttr" data-l1key="options" data-l2key="enable" tooltip="{{Décocher pour désactiver l\'élément}}" />'
      }
      retour += '<legend class="legendHidden">{{ACTION}}</legend>'
      if (isset(_subElement.expressions) && isset(_subElement.expressions[0])) {
        expression = _subElement.expressions[0]
        if (expression.type == 'element' && isset(expression.element.subElements) && isset(expression.element.subElements[0]) && isset(expression.element.subElements[0].expressions) && isset(expression.element.subElements[0].expressions[0])) {
          retour += '<div class="blocPreview">' + expression.element.subElements[0].expressions[0].expression.substring(0, 200).HTMLFormat() + '</div>'
        } else {
          retour += '<div class="blocPreview">' + _subElement.expressions[0].expression.substring(0, 200).HTMLFormat() + '</div>'
        }
      } else {
        retour += '<div class="blocPreview"></div>'
      }
      retour += '</div>'
      retour += '<div class="subElementFields">'
      retour += '<legend >{{ACTION}}</legend><br/>'
      retour += getAddButton()
      retour += '</div>'
      retour += '<div class="expressions">'
      retour += '<div class="sortable empty" ></div>'
      if (isset(_subElement.expressions)) {
        for (var k in _subElement.expressions) {
          retour += addExpression(_subElement.expressions[k])
        }
      }
      retour += '</div>'
      retour = addElButtons(retour)
      break
  }
  retour += '</div>'
  return retour
}

function addElButtons(_retour) {
  _retour += '  <div><i class="fas fa-minus-circle pull-right cursor bt_removeElement" tooltip="{{Supprimer ce bloc.<br>Ctrl+Click: Supprimer sans confirmation.}}"></i></div>'
  _retour += '  <div><i class="fas fa-copy pull-right cursor bt_copyElement" tooltip="{{Copier ce bloc.<br>Ctrl+Click: Couper ce bloc.}}"></i></div>'
  _retour += '  <div><i class="fas fa-paste pull-right cursor bt_pasteElement" tooltip="{{Coller un bloc après celui-ci.<br>Ctrl+Click: remplacer ce bloc par le bloc copié.}}"></i></div>'
  return _retour
}

function addElement(_element) {
  if (!isset(_element)) {
    return
  }
  if (!isset(_element.type) || _element.type == '') {
    return ''
  }

  var elementClass = ''
  switch (_element.type) {
    case 'if':
      elementClass = 'elementIF'
      break
    case 'for':
      elementClass = 'elementFOR'
      break
    case 'in':
      elementClass = 'elementIN'
      break
    case 'at':
      elementClass = 'elementAT'
      break
    case 'code':
      elementClass = 'elementCODE'
      break
    case 'comment':
      elementClass = 'elementCOM'
      break
    case 'action':
      elementClass = 'elementACTION'
  }

  var div = '<div class="element ' + elementClass + '">'

  div += '<input class="elementAttr" data-l1key="id" style="display : none;" value="' + init(_element.id) + '"/>'
  div += '<input class="elementAttr" data-l1key="type" style="display : none;" value="' + init(_element.type) + '"/>'
  switch (_element.type) {
    case 'if':
      if (isset(_element.subElements) && isset(_element.subElements)) {
        for (var j in _element.subElements) {
          div += addSubElement(_element.subElements[j])
        }
      } else {
        div += addSubElement({
          type: 'if'
        })
        div += addSubElement({
          type: 'then'
        })
        div += addSubElement({
          type: 'else'
        })
      }
      break
    case 'for':
      if (isset(_element.subElements) && isset(_element.subElements)) {
        for (var j in _element.subElements) {
          div += addSubElement(_element.subElements[j])
        }
      } else {
        div += addSubElement({
          type: 'for'
        })
        div += addSubElement({
          type: 'do'
        })
      }
      break;
    case 'in':
      if (isset(_element.subElements) && isset(_element.subElements)) {
        for (var j in _element.subElements) {
          div += addSubElement(_element.subElements[j])
        }
      } else {
        div += addSubElement({
          type: 'in'
        })
        div += addSubElement({
          type: 'do'
        })
      }
      break
    case 'at':
      if (isset(_element.subElements) && isset(_element.subElements)) {
        for (var j in _element.subElements) {
          div += addSubElement(_element.subElements[j])
        }
      } else {
        div += addSubElement({
          type: 'at'
        })
        div += addSubElement({
          type: 'do'
        })
      }
      break
    case 'code':
      if (isset(_element.subElements) && isset(_element.subElements)) {
        for (var j in _element.subElements) {
          div += addSubElement(_element.subElements[j])
        }
      } else {
        div += addSubElement({
          type: 'code'
        })
      }
      break
    case 'comment':
      if (isset(_element.subElements) && isset(_element.subElements)) {
        for (var j in _element.subElements) {
          div += addSubElement(_element.subElements[j])
        }
      } else {
        div += addSubElement({
          type: 'comment'
        })
      }
      break
    case 'action':
      if (isset(_element.subElements) && isset(_element.subElements)) {
        for (var j in _element.subElements) {
          div += addSubElement(_element.subElements[j])
        }
      } else {
        div += addSubElement({
          type: 'action'
        })
      }
      break
  }
  div += '</div>'
  return div
}

function getElement(_element) {
  var element = _element.getValues('.elementAttr', 1)
  if (element.length == 0) {
    return
  }
  element = element[0]
  element.subElements = []

  var subElement, expression_dom, expression, id
  _element.findAtDepth('.subElement', 2).each(function() {
    subElement = $(this).getValues('.subElementAttr', 2)[0]
    subElement.expressions = []
    expression_dom = $(this).children('.expressions')
    if (expression_dom.length == 0) {
      expression_dom = $(this).children('legend').findAtDepth('.expressions', 2)
    }
    expression_dom.children('.expression').each(function() {
      expression = $(this).getValues('.expressionAttr', 3)[0]
      if (expression.type == 'element') {
        expression.element = getElement($(this).findAtDepth('.element', 2))
      }
      if (subElement.type == 'code') {
        id = $(this).find('.expressionAttr[data-l1key=expression]').attr('id')
        if (id != undefined && isset(_EDITORS[id])) {
          expression.expression = _EDITORS[id].getValue()
        }
      }
      subElement.expressions.push(expression)

    });
    element.subElements.push(subElement)
  });
  return element
}

function updateTooltips() {
  //in scenarios, for faster undo/redo, tooltips are specially created with tooltip attribute and copied as title to keep track of it!
  $('[tooltip]:not(.tooltipstered)').each(function() {
    $(this).attr('title', $(this).attr('tooltip'))
  })
  $('[tooltip]:not(.tooltipstered)').tooltipster(jeedomUtils.TOOLTIPSOPTIONS)
}

function getAddButton(_caret) {
  if (!isset(_caret)) _caret = false
  var retour = ''
  if (_caret) {
    retour += '<div class="input-group">'
    retour += '<button class="bt_showElse btn btn-xs btn-default roundedLeft" type="button" data-toggle="dropdown" tooltip="{{Afficher/masquer le bloc Sinon}}" aria-haspopup="true" aria-expanded="true">'
    retour += '<i class="fas fa-sort-up"></i>'
    retour += '</button>'
    retour += '<span class="input-group-btn">'
  }
  retour += '<div class="dropdown">'
  if (_caret) {
    retour += '<button class="btn btn-default dropdown-toggle roundedRight" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">'
  } else {
    retour += '<button class="btn btn-xs btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">'
  }
  retour += '<i class="fas fa-plus-circle"></i> {{Ajouter}}'
  retour += '<span class="caret"></span>'
  retour += '</button>'

  retour += '<ul class="dropdown-menu">'
  retour += '<li><a class="bt_addAction">{{Action}}</a></li>'
  retour += '<li><a class="fromSubElement" data-type="if">{{Bloc Si/Alors/Sinon}}</a></li>'
  retour += '<li><a class="fromSubElement" data-type="action">{{Bloc Action}}</a></li>'
  retour += '<li><a class="fromSubElement" data-type="for">{{Bloc Boucle}}</a></li>'
  retour += '<li><a class="fromSubElement" data-type="in">{{Bloc Dans}}</a></li>'
  retour += '<li><a class="fromSubElement" data-type="at">{{Bloc A}}</a></li>'
  retour += '<li><a class="fromSubElement" data-type="code">{{Bloc Code}}</a></li>'
  retour += '<li><a class="fromSubElement" data-type="comment">{{Bloc Commentaire}}</a></li>'
  retour += '</ul>'

  retour += '</div>'
  if (_caret) {
    retour += '</span>'
    retour += '</div>'
  }
  return retour
}

$divScenario.on('shown.bs.dropdown', '.dropdown', function() {
  if ( $(this).offset().top > window.innerHeight - 220) {
    $(this).addClass('dropup')
  }
})
.on('hidden.bs.dropdown', '.dropdown', function() {
  $(this).removeClass("dropup")
})

$divScenario.on('click', '.fromSubElement', function(event) {
  var elementType = $(this).attr('data-type')
  setUndoStack()

  var elementDiv = $(this).closest('.subElement').find('.expressions').eq(0)
  var newEL = $(addExpression({
    type: 'element',
    element: {
      type: elementType
    }
  }))
  elementDiv.append(newEL.addClass('disableElement'))

  setEditors()
  updateSortable()
  updateElseToggle()
  modifyWithoutSave = true
  updateTooltips()
  jeedom.scenario.setAutoComplete()
  setTimeout(function() {
    newEL.removeClass('disableElement')
  }, 600)
})


//UNDO Management
var _undoStack_ = []
var _undoState_ = -1
var _firstState_ = 0
var _undoLimit_ = 12
var _redo_ = 0
var bt_undo = $('#bt_undo')
var bt_redo = $('#bt_redo')

bt_undo.off('click').on('click', function() {
  if (!jeedomUtils.getOpenedModal()) {
    undo()
    PREV_FOCUS = null
  }
})
bt_redo.off('click').on('click', function() {
  if (!jeedomUtils.getOpenedModal()) {
    redo()
    PREV_FOCUS = null
  }
})

function reloadStack(loadStack) {
  $('#div_scenarioElement').empty()
  actionOptions = []
  var elements = ''
  for (var i in loadStack) {
    elements += addElement(loadStack[i])
  }

  $('#div_scenarioElement').append(elements)

  //Synch collapsed elements:
  $('i.fa-eye-slash').each(function() {
    $(this).parents('.element').first().addClass('elementCollapse')
  })

  updateElseToggle()
  setScenarioActionsOptions()
}

function setUndoStack(state = 0) {
  syncEditors()
  bt_undo.removeClass('disabled')
  bt_redo.addClass('disabled')
  var newStack = []
  $('#div_scenarioElement').children('.element').each(function() {
    newStack.push(getElement($(this)))
  })

  if (newStack == $(_undoStack_[state - 1])) return
  if (state == 0) {
    state = _undoState_ = _undoStack_.length
    _redo_ = 0
  }
  _undoStack_[state] = newStack
  //limit stack:
  if (state >= _firstState_ + _undoLimit_) {
    _firstState_ += 1
    _undoStack_[_firstState_ - 1] = 0
  }
}

function undo() {
  if (_undoState_ < _firstState_) {
    return
  }
  try {
    var loadState = _undoState_
    if (_redo_ == 0) setUndoStack(_undoState_ + 1)
    reloadStack(_undoStack_[loadState])
    _undoState_ -= 1

    if (_undoState_ < _firstState_) bt_undo.addClass('disabled')
    bt_redo.removeClass('disabled')
  } catch (error) {
    console.log('undo ERROR:', error)
  }
  updateTooltips()
  jeedom.scenario.setAutoComplete()
  resetEditors()
}

function redo() {
  _redo_ = 1
  if (_undoState_ < _firstState_ - 1 || _undoState_ + 2 >= _undoStack_.length) {
    return
  }
  bt_undo.removeClass('disabled')
  try {
    var loadState = _undoState_ + 2
    reloadStack(_undoStack_[loadState])
    _undoState_ += 1

    if (_undoState_ < _firstState_ - 1 || _undoState_ + 2 >= _undoStack_.length) bt_redo.addClass('disabled')
  } catch (error) {
    console.log('redo ERROR:', error)
  }
  updateTooltips()
  jeedom.scenario.setAutoComplete()
  resetEditors()
}

function resetUndo() {
  _undoStack_ = []
  _undoState_ = -1
  _firstState_ = 0
  _undoLimit_ = 10
  bt_undo.addClass('disabled')
  bt_redo.addClass('disabled')
}

//Code Editors:
var _EDITORS = []

function setEditors() {
  var expression, code
  $('.expressionAttr[data-l1key=type][value=code]').each(function() {
    expression = $(this).closest('.expression')
    code = expression.find('.expressionAttr[data-l1key=expression]')
    $(this).find('.blocPreview').html(code.val())
    if (code.attr('id') == undefined && code.is(':visible')) {
      code.uniqueId()
      var id = code.attr('id')
      setTimeout(function() {
        _EDITORS[id] = CodeMirror.fromTextArea(document.getElementById(id), {
          lineNumbers: true,
          lineWrapping: true,
          mode: 'text/x-php',
          matchBrackets: true,
          viewportMargin: Infinity,
          foldGutter: true,
          gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"]
        })
        _EDITORS[id].setOption("extraKeys", {
          "Ctrl-Y": cm => CodeMirror.commands.foldAll(cm),
          "Ctrl-I": cm => CodeMirror.commands.unfoldAll(cm)
        })
      }, 1)
    }
  })
}

function resetEditors() {
  _EDITORS = []
  var expression, code
  $('.expressionAttr[data-l1key=type][value=code]').each(function() {
    expression = $(this).closest('.expression')
    code = expression.find('.expressionAttr[data-l1key=expression]')
    code.removeAttr('id').show()
    expression.find('.CodeMirror.CodeMirror-wrap').remove()
  })
  setEditors()
}

function syncEditors() {
  var expression, code, id
  $('.expressionAttr[data-l1key=type][value=code]').each(function() {
    expression = $(this).closest('.expression')
    code = expression.find('.expressionAttr[data-l1key=expression]')
    id = code.attr('id')
    if (isset(_EDITORS[id])) code.html(_EDITORS[id].getValue())
  })
}