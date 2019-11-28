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

$(function(){
  $('.objectPreview .objectSummaryParent[data-summary="temperature"]').each(function() {
    $(this).find('i').remove()
    $(this).appendTo($(this).closest('.objectPreview').find('.topPreview'))
  })
})

$('.settings').off('click').on('click', function (event) {
  var url = '/index.php?v=d&p=object&id='+$(this).closest('.objectPreview').attr('data-object_id')
  if (event.ctrlKey) {
    window.open(url).focus()
  } else {
    loadPage(url)
  }
  return false
})

$('.objectPreview').off('click').on('click', function (event) {
  if (event.target !== this) return
  var url = '/index.php?v=d&p=dashboard&object_id='+$(this).attr('data-object_id')+'&childs=0'
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
    if (target !== this) {
      if ($(event.target).hasClass('fa-cog')) {
        var url = '/index.php?v=d&p=object&id='+$(this).closest('.objectPreview').attr('data-object_id')
        window.open(url).focus()
      } else {
        target = $(event.target).closest('.objectSummaryParent')
        var url = 'index.php?v=d&p=dashboard&summary='+target.data('summary')+'&object_id='+$(this).data('object_id')+'&childs=0'
        window.open(url).focus()
      }
    } else {
      event.preventDefault()
      var id = $(this).attr('data-object_id')
      $('.objectPreview[data-object_id="'+id+'"]').trigger(jQuery.Event('click', {ctrlKey: true}))
    }
  }
})