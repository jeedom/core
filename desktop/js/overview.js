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

var _SummaryObserver_ = null
var $summaryContainer = null
var modal = null
var modalContent = null

//infos/actions tile signals:
$('body').off()
  .on('mouseenter','div.eqLogic-widget .cmd-widget[data-type="action"][data-subtype!="select"]',function (event) {
    $(this).closest('.eqLogic-widget').addClass('eqSignalAction')
  })
  .on('mouseleave','div.eqLogic-widget .cmd-widget[data-type="action"][data-subtype!="select"]',function (event) {
    $(this).closest('.eqLogic-widget').removeClass('eqSignalAction')
  })
  .on('mouseenter','div.eqLogic-widget .cmd-widget.history[data-type="info"]',function (event) {
    $(this).closest('.eqLogic-widget').addClass('eqSignalInfo')
  })
  .on('mouseleave','div.eqLogic-widget .cmd-widget.history[data-type="info"]',function (event) {
    $(this).closest('.eqLogic-widget').removeClass('eqSignalInfo')
  })
  .on('mouseenter','div.eqLogic-widget .cmd-widget[data-type="action"] .timeCmd',function (event) {
    $(this).closest('.eqLogic-widget').removeClass('eqSignalAction').addClass('eqSignalInfo')
  })
  .on('mouseleave','div.eqLogic-widget .cmd-widget[data-type="action"] .timeCmd',function (event) {
    $(this).closest('.eqLogic-widget').removeClass('eqSignalInfo').addClass('eqSignalAction')
  })


$(function() {
  //move to top summary:
  $('.objectPreview').each(function() {
    var parent = $(this).find('.topPreview')
    $(this).find('.objectSummaryParent[data-summary="temperature"], .objectSummaryParent[data-summary="motion"], .objectSummaryParent[data-summary="security"], .objectSummaryParent[data-summary="humidity"]').each(function() {
      $(this).detach().appendTo(parent)
    })
    $(this).find('.resume').find('.objectSummaryParent').eq(-7).after("<br />")
  })

  colorizeSummary()
  checkResumeEmpty()
  $('.resume').show();
  createSummaryObserver()
})

function checkResumeEmpty() {
  $('.objectPreview ').each(function() {
    if (!$(this).find('.objectSummaryParent').length) {
     var button = '<span class="bt_config"><i class="fas fa-cogs"></i></span>'
     $(this).find('.bt_config').remove()
     $(this).find('.topPreview').append(button)
    }
  })
}

function colorizeSummary() {
  $('.objectPreview .objectSummarysecurity, .objectPreview .objectSummarymotion').each(function() {
    var value = $(this).html()
    if (value == 0) {
      $(this).closest('.objectSummaryParent').addClass('success')
    } else {
      $(this).closest('.objectSummaryParent').addClass('danger')
    }
  })
}

function createSummaryObserver() {
  var _SummaryObserver_ = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
      if ( mutation.type == 'childList' && mutation.target.className == 'resume') {
        updateSummary(mutation.addedNodes[0].className)
      }
    })
  })

  var observerConfig = {
    attributes: true,
    childList: true,
    characterData: true,
    subtree: true
  }

  var targetNode = document.getElementById('objectOverviewContainer')
  _SummaryObserver_.observe(targetNode, observerConfig)
}

function updateSummary(_className) {
  var parent = $('.'+_className).closest('.objectPreview')
  parent.find('.topPreview').find('.objectSummaryParent').remove()
  parent.find('.resume').find('.objectSummaryParent[data-summary="temperature"], .objectSummaryParent[data-summary="motion"], .objectSummaryParent[data-summary="security"], .objectSummaryParent[data-summary="humidity"]').each(function() {
      $(this).detach().appendTo(parent.find('.topPreview'))
    })
  parent.find('.resume').find('.objectSummaryParent').eq(-7).after("<br />")
  colorizeSummary()
  checkResumeEmpty()
}


//buttons:
$('#div_pageContainer').delegate('.objectPreview .bt_config', 'click', function () {
  var objectId = $(this).closest('.objectPreview').data('object_id')
  var url = 'index.php?v=d&p=object&id='+objectId+'#summarytab'
  loadPage(url)
})

$('#objectOverviewContainer .objectSummaryParent').off('click').on('click', function (event) {
  event.stopPropagation()
  event.preventDefault()
  var objectId = $(this).closest('.objectPreview').attr('data-object_id')
  var summaryType = $(this).attr('data-summary')
  getSummaryHtml(objectId, summaryType)
})

//Tile click or center-click
$('.objectPreview').off('click').on('click', function (event) {
  if (event.target !== this && !$(event.target).hasClass('bottomPreview')) return
  var url = 'index.php?v=d&p=dashboard&object_id='+$(this).attr('data-object_id')+'&childs=0'
  if (event.ctrlKey) {
    window.open(url).focus()
  } else {
    loadPage(url)
  }
  return false
})
$('.objectPreview').off('mouseup').on('mouseup', function (event) {
  if( event.which == 2 ) {
    var target = event.target
    if ($(target).hasClass('topPreview') || $(target).hasClass('name')) return
    if (target !== this && !$(target).hasClass('bottomPreview')) {
      target = $(target).closest('.objectSummaryParent')
      var url = 'index.php?v=d&p=dashboard&summary='+target.data('summary')+'&object_id='+$(this).data('object_id')+'&childs=0'
      window.open(url).focus()
    } else {
      event.preventDefault()
      var id = $(this).attr('data-object_id')
      $('.objectPreview[data-object_id="'+id+'"]').trigger(jQuery.Event('click', {ctrlKey: true}))
    }
  }
})

//Tile name click or center-click
$('.objectPreview .name').off('click').on('click', function (event) {
  var url = 'index.php?v=d&p=dashboard&object_id='+$(this).closest('.objectPreview').attr('data-object_id')
  if (event.ctrlKey) {
    window.open(url).focus()
  } else {
    loadPage(url)
  }
  return false
})
$('.objectPreview .name').off('mouseup').on('mouseup', function (event) {
  if( event.which == 2 ) {
    event.preventDefault()
    var id = $(this).closest('.objectPreview').attr('data-object_id')
    $('.objectPreview[data-object_id="'+id+'"] .name').trigger(jQuery.Event('click', {ctrlKey: true}))
  }
})


$("#md_overviewSummary").dialog({
  closeText: '',
  autoOpen: false,
  modal: true,
  width: 480,
  height: 400,
  open: function () {
    modal.find('.ui-dialog-titlebar-close').appendTo(modal)
  }
}).siblings('.ui-dialog-titlebar').css('opacity', 0)

$(function() {
  $summaryContainer = $('#summaryEqlogics')
  $summaryContainer.packery()
  modal = $summaryContainer.parents('.ui-dialog.ui-resizable')
  modalContent = modal.find('.ui-dialog-content.ui-widget-content')
  modal.resize(function() {
    $summaryContainer.packery()
  })


  modalContent.off()
  modalContent.off('click').on('click', function (event) {
    if (!$(event.target).parents('.eqLogic-widget').length) {
      $("#md_overviewSummary").dialog('close')
    }
  })

  //history in summary modal:
  modalContent.delegate('.eqLogic-widget .history', 'click', function () {
    event.stopImmediatePropagation()
    event.stopPropagation()
    if (event.ctrlKey) {
      var cmdIds = []
      $(this).closest('.eqLogic.eqLogic-widget').find('.history[data-cmd_id]').each(function () {
        cmdIds.push($(this).data('cmd_id'))
      })
      cmdIds = cmdIds.join('-')
    } else {
      var cmdIds = $(this).closest('.history[data-cmd_id]').data('cmd_id')
    }
    $('#md_modal2').dialog({title: "{{Historique}}"}).load('index.php?v=d&modal=cmd.history&id=' + cmdIds).dialog('open')
  })
})

function getSummaryHtml(_object_id, _summary) {
  jeedom.object.toHtml({
    id: _object_id,
    version: 'dashboard',
    category : null,
    summary : _summary,
    tag : null,
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'})
    },
    success: function (html) {
      $summaryContainer.empty().packery('destroy')
      $summaryContainer.append(html)
      $('#md_overviewSummary').dialog({title: "{{Résumé}}"}).dialog('open')

      //adapt modal size:
      var brwSize = {
        width: window.innerWidth || document.body.clientWidth,
        height: window.innerHeight || document.body.clientHeight
      }
      var fullWidth = 0
      var fullHeight = 0
      var thisWidth = 0
      var thisHeight = 0
      $('.eqLogic-widget').each(function( index ) {
        thisWidth = $(this).outerWidth(true)
        thisHeight = $(this).outerHeight(true)
        if (fullHeight == 0 || fullHeight < thisHeight + 5) fullHeight = thisHeight + 5
        if ( (fullWidth + thisWidth + 150) < brwSize.width ) {
          fullWidth += thisWidth + 5
        } else {
          fullHeight += thisHeight + 5
        }
      })
      fullWidth += 5
      fullHeight += 5
      modal.width(fullWidth + 26).height(fullHeight + 50)
      modalContent.width(fullWidth).height(fullHeight)
      $summaryContainer.packery({gutter: 10})
    }
  })
}