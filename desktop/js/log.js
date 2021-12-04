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

var $btGlobalLogStopStart = $('#bt_globalLogStopStart')

var $rawLogCheck = $('#brutlogcheck')
$rawLogCheck.on('click').on('click', function() {
  $rawLogCheck.attr('autoswitch', 0)

  var scroll = $('#pre_globallog').scrollTop()
  jeedom.log.autoupdate({
    log: $('li.li_log.active').attr('data-log'),
    display: $('#pre_globallog'),
    search: $('#in_searchGlobalLog'),
    control: $btGlobalLogStopStart,
    once: 1
  })
  $('#pre_globallog').scrollTop(scroll)
})

$(".li_log").on('click', function() {
  $.clearDivContent('pre_globallog')
  $(".li_log").removeClass('active')
  $(this).addClass('active')
  $btGlobalLogStopStart.removeClass('btn-success')
    .addClass('btn-warning')
    .html('<i class="fas fa-pause"></i><span class="hidden-768"> {{Pause}}</span>')
    .attr('data-state', 1)
  jeedom.log.autoupdate({
    log: $(this).attr('data-log'),
    display: $('#pre_globallog'),
    search: $('#in_searchGlobalLog'),
    control: $btGlobalLogStopStart,
  })
})


//searching
$('#in_searchLogFilter').keyup(function() {
  var search = $(this).value()
  if (search == '') {
    $('#ul_object .li_log').show()
    return
  }
  var not = search.startsWith(":not(")
  if (not) {
    search = search.replace(':not(', '')
  }
  search = jeedomUtils.normTextLower(search)
  $('#ul_object .li_log').hide()
  var match, text
  $('#ul_object .li_log').each(function() {
    match = false
    text = jeedomUtils.normTextLower($(this).text())
    if (text.includes(search)) {
      match = true
    }

    if (not) match = !match
    if (match) {
      $(this).show()
    }

  })
})
$('#bt_resetLogFilterSearch').on('click', function() {
  $('#in_searchLogFilter').val('').keyup()
})

$('#bt_resetGlobalLogSearch').on('click', function() {
  $('#in_searchGlobalLog').val('').keyup()
})

$('#bt_downloadLog').click(function() {
  window.open('core/php/downloadFile.php?pathfile=log/' + $('.li_log.active').attr('data-log'), "_blank", null)
})

$("#bt_clearLog").on('click', function(event) {
  jeedom.log.clear({
    log: $('.li_log.active').attr('data-log'),
    success: function(data) {
      $('.li_log.active a').html('<i class="fa fa-check"></i> ' + $('.li_log.active').attr('data-log'))
      $('.li_log.active i').removeClass().addClass('fas fa-check')
      if ($btGlobalLogStopStart.attr('data-state') == 0) {
        $btGlobalLogStopStart.click()
      }
    }
  })
})

$("#bt_clearAllLog").on('click', function(event) {
  bootbox.confirm("{{Êtes-vous sûr de vouloir vider tous les logs ?}}", function(result) {
    if (result) {
      jeedom.log.clearAll({
        error: function(error) {
          $('#div_alertError').showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          jeedomUtils.loadPage('index.php?v=d&p=log')
        }
      })
    }
  })
})

$("#bt_removeLog").on('click', function(event) {
  jeedom.log.remove({
    log: $('.li_log.active').attr('data-log'),
    success: function(data) {
      jeedomUtils.loadPage('index.php?v=d&p=log');
    }
  })
})

$("#bt_removeAllLog").on('click', function(event) {
  bootbox.confirm("{{Êtes-vous sûr de vouloir supprimer tous les logs ?}}", function(result) {
    if (result) {
      jeedom.log.removeAll({
        error: function(error) {
          $('#div_alertError').showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          jeedomUtils.loadPage('index.php?v=d&p=log')
        }
      })
    }
  })
})

//autoclick first log:
$(function() {
  var logfile = getUrlVars('logfile')
  if ($('#div_displayLogList .li_log[data-log="' + logfile + '"]').length) {
    $('#div_displayLogList .li_log[data-log="' + logfile + '"]').trigger('click')
  } else {
    $('#div_displayLogList .li_log').first().trigger('click')
  }
})