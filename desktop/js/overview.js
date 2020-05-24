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
  loadPage('index.php?v=d&p=dashboard&summary='+$(this).data('summary')+'&object_id='+$(this).data('object_id')+'&childs=0')
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