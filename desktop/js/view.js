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

$(function() {
  setTimeout(function() {
    $('input', 'textarea', 'select').click(function() {
      $(this).focus()
    })
  }, 750)

  jeedomUI.isEditing = false
  jeedomUI.setEqSignals()
  jeedomUI.setHistoryModalHandler()
})

var modifyWithoutSave = false

$('#div_pageContainer').on('click', '.bt_gotoViewZone', function() {
  var ptop = $('.div_displayViewContainer').scrollTop() + $('.lg_viewZone[data-zone_id=' + $(this).attr('data-zone_id') + ']').offset().top - 60
  $('.div_displayViewContainer').animate({
    scrollTop: ptop
  }, 500)
})

function fullScreen(_mode) {
  if (_mode) {
    $('header').hide()
    $('footer').hide()
    $('#div_mainContainer').css('margin-top', '-50px')
    $('#backgroundforJeedom').css({
      'margin-top': '-50px',
      'height': '100%'
    })
    $('#wrap').css('margin-bottom', '0px')
    $('.div_displayView').height($('html').height() - 5)
    $('.div_displayViewContainer').height($('html').height() - 5)
    $('.bt_hideFullScreen').hide()
  } else {
    $('header').show()
    $('footer').show()
    $('#div_mainContainer').css('margin-top', '0px')
    $('#backgroundforJeedom').css({
      'margin-top': '0px',
      'height': 'calc(100% - 50px)'
    })
    $('#wrap').css('margin-bottom', '15px')
    $('.div_displayView').height($('body').height())
    $('.div_displayViewContainer').height($('body').height())
    $('.bt_hideFullScreen').show()
  }
}

$('#bt_editViewWidgetOrder').off('click').on('click', function() {
  if ($(this).attr('data-mode') == 1) {
    $('#md_modal').dialog('close')
    $.hideAlert()
    $(this).attr('data-mode', 0)
    $('.counterReorderJeedom').remove()
    editWidgetMode(0)
  } else {
    $.fn.showAlert({
      message: "{{Vous êtes en mode édition. Vous pouvez déplacer les tuiles, les redimensionner,  et éditer les commandes (ordre, widget) avec le bouton à droite du titre.}}",
      level: 'info'
    })
    $(this).attr('data-mode', 1)
    editWidgetMode(1)
  }
})

if (view_id != '') {
  jeedom.view.toHtml({
    id: view_id,
    version: 'dashboard',
    useCache: true,
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(html) {
      if (isset(html.raw) && isset(html.raw.img) && html.raw.img != '') {
        jeedomUtils.setBackgroundImage(html.raw.img)
      } else {
        jeedomUtils.setBackgroundImage('')
      }

      try {
        var summary = ''
        for (var i in html.raw.viewZone) {
          summary += '<li style="padding:0px 0px"><a style="padding:2px 20px" class="cursor bt_gotoViewZone" data-zone_id="' + html.raw.viewZone[i].id + '">' + html.raw.viewZone[i].name + '</a></li>'
        }
        $('#ul_viewSummary').empty().append(summary)
      } catch (err) {
        console.log(err)
      }

      try {
        $('.div_displayView').last().empty().html(html.html)
      } catch (err) {
        console.log(err)
      }

      setTimeout(function() {
        jeedomUtils.initReportMode()
        jeedomUtils.positionEqLogic()
        $('.eqLogicZone').disableSelection()
        $('input', 'textarea', 'select').click(function() {
          $(this).focus()
        })

        $('.eqLogicZone').each(function() {
          var container = $(this).packery()
          var itemElems = container.find('.eqLogic-widget, .scenario-widget').draggable()
          container.packery('bindUIDraggableEvents', itemElems)

          //set vieworder for editMode:
          $(itemElems).each(function(i, itemElem) {
            $(itemElem).attr('data-vieworder', i + 1)
          })
          container.on('dragItemPositioned', function() {
            jeedomUI.orderItems(container, 'data-vieworder')
          })

          itemElems.draggable('disable')
        })

        if (isset(html.raw) && isset(html.raw.configuration) && isset(html.raw.configuration.displayObjectName) && html.raw.configuration.displayObjectName == 1) {
          $('.eqLogic-widget, .scenario-widget').addClass('displayObjectName')
        }
        if (getUrlVars('fullscreen') == 1) {
          fullScreen(true)
        }
      }, 10)

      //draw graphs:
      $('.chartToDraw').each(function() {
        $(this).find('.viewZoneData').each(function() {
          var cmdId = $(this).attr('data-cmdid')
          var el = $(this).attr('data-el')
          var daterange = $(this).attr('data-daterange')

          var _params = {
            cmd_id: cmdId,
            el: el,
            dateRange: daterange,
            success: function() {
              $('.chartToDraw >.viewZoneData[data-cmdid="'+cmdId+'"]').remove()
            }
          }
          var option = $(this).attr('data-option')
          option = json_decode(option.replaceAll("'", '"'))
          _params.option = option
          jeedom.history.drawChart(_params)
        })
      })
    }
  })
}

$('.bt_displayView').on('click', function() {
  if ($(this).attr('data-display') == 1) {
    $(this).closest('.row').find('.div_displayViewList').hide()
    $(this).closest('.row').find('.div_displayViewContainer').removeClass('col-lg-8 col-lg-10 col-lg-12 col-lg-8 col-lg-10 col-lg-12 col-md-8 col-md-10 col-md-12 col-sm-8 col-sm-10 col-sm-12').addClass('col-lg-12 col-md-12 col-sm-12')
    $('.eqLogicZone').each(function() {
      $(this).packery()
    });
    $(this).attr('data-display', 0)
  } else {
    $(this).closest('.row').find('.div_displayViewList').show();
    $(this).closest('.row').find('.div_displayViewContainer').removeClass('col-lg-8 col-lg-10 col-lg-12 col-lg-8 col-lg-10 col-lg-12 col-md-8 col-md-10 col-md-12 col-sm-8 col-sm-10 col-sm-12').addClass('col-lg-10 col-md-9 col-sm-8')
    $('.eqLogicZone').packery()
    $(this).attr('data-display', 1)
  }
})

$('#div_pageContainer').on({
  'click': function(event) {
    var eqId = $(this).closest('.eqLogic-widget').attr('data-eqlogic_id')
    $('#md_modal').dialog({
      title: "{{Configuration Affichage}}"
    }).load('index.php?v=d&modal=eqLogic.dashboard.edit&eqLogic_id=' + eqId).dialog('open')
  }
}, '.editOptions')

function editWidgetMode(_mode, _save) {
  if (!isset(_mode)) {
    if ($('#bt_editViewWidgetOrder').attr('data-mode') != undefined && $('#bt_editViewWidgetOrder').attr('data-mode') == 1) {
      editWidgetMode(0, false)
      editWidgetMode(1, false)
    }
    return
  }
  var divEquipements = $('.div_displayView')
  if (_mode == 0 || _mode == '0') {
    modifyWithoutSave = false
    jeedomUI.isEditing = false
    jeedom.cmd.disableExecute = false

    divEquipements.find('.editingMode.allowResize').resizable('destroy')
    divEquipements.find('.editingMode').draggable('disable').removeClass('editingMode', '').removeAttr('data-editId')
    divEquipements.find('.cmd.editOptions').remove()

    if (!isset(_save) || _save) {
      jeedomUI.saveWidgetDisplay({
        view: 1
      })
    }
  } else {
    jeedomUI.isEditing = true
    jeedom.cmd.disableExecute = true
    $('.eqLogic-widget, .scenario-widget').addClass('editingMode')

    //show orders:
    var value
    $('.jeedomAlreadyPosition.ui-draggable').each(function() {
      value = $(this).attr('data-vieworder')
      if ($(this).find(".counterReorderJeedom").length) {
        $(this).find(".counterReorderJeedom").text(value)
      } else {
        $(this).prepend('<span class="counterReorderJeedom pull-left">' + value + '</span>')
      }
    })

    //set unique id whatever we have:
    divEquipements.find('.eqLogic-widget, .scenario-widget').each(function(index) {
      $(this).addClass('editingMode')
        .attr('data-editId', index)
        .append('<span class="cmd editOptions cursor"></span>')
    })

    //set draggables:
    divEquipements.find('.editingMode').draggable({
      disabled: false,
      distance: 10,
      start: function(event, ui) {
        modifyWithoutSave = true
        jeedomUI.draggingId = $(this).attr('data-editId')
        jeedomUI.orders = {}
        $(this).parent().find('.ui-draggable').each(function(i, itemElem) {
          jeedomUI.orders[jeedomUI.draggingId] = parseInt($(this).attr('data-vieworder'))
        })
      }
    })
    //set resizables:
    $('.eqLogicZone .eqLogic-widget.allowResize').resizable({
      grid: [2, 2],
      start: function(event, ui) {
        modifyWithoutSave = true
      },
      resize: function(event, ui) {
        jeedomUtils.positionEqLogic(ui.element.attr('data-eqlogic_id'), false)
        ui.element.closest('.eqLogicZone').packery()
      },
      stop: function(event, ui) {
        jeedomUtils.positionEqLogic(ui.element.attr('data-eqlogic_id'), false)
        ui.element.closest('.eqLogicZone').packery()
      }
    })
    $('.eqLogicZone .scenario-widget.allowResize').resizable({
      grid: [2, 2],
      start: function(event, ui) {
        modifyWithoutSave = true
      },
      resize: function(event, ui) {
        jeedomUtils.positionEqLogic(ui.element.attr('data-scenario_id'), false, true)
        ui.element.closest('.eqLogicZone').packery()
      },
      stop: function(event, ui) {
        jeedomUtils.positionEqLogic(ui.element.attr('data-scenario_id'), false, true)
        ui.element.closest('.eqLogicZone').packery()
      }
    })
  }
}