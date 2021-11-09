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

$('.li_type').on('click', function() {
  $('.li_type').removeClass('active')
  $(this).addClass('active')
  $('.reportType').hide()
  $('.reportType.' + $(this).attr('data-type')).show()
})

$('.li_reportType').on('click', function() {
  $('.li_reportType').removeClass('active')
  $(this).addClass('active')
  getReportList($(this).attr('data-type'), $(this).attr('data-id'))
})

function getReportList(_type, _id) {
  jeedom.report.list({
    type: _type,
    id: _id,
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(data) {
      $('#ul_report .li_report').remove()
      var ul = '';
      for (var i in data) {
        ul += '<li class="cursor li_report" data-type=' + _type + ' data-id=' + _id + ' data-report="' + i + '"><a>' + i + '</a></li>'
      }
      $('#ul_report').append(ul)
    }
  })
}

$('#ul_report').on('click', '.li_report', function() {
  $('.li_report').removeClass('active')
  $(this).addClass('active')
  getReport($(this).attr('data-type'), $(this).attr('data-id'), $(this).attr('data-report'))
})

function getReport(_type, _id, _report) {
  jeedom.report.get({
    type: _type,
    id: _id,
    report: _report,
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(data) {
      $('#div_reportForm').setValues(data, '.reportAttr')
      $('#div_reportForm').show()
      $('#div_imgreport').empty()
      var type = $('#div_reportForm .reportAttr[data-l1key=type]').value()
      var id = $('#div_reportForm .reportAttr[data-l1key=id]').value()
      var filename = $('#div_reportForm .reportAttr[data-l1key=filename]').value()
      var extension = $('#div_reportForm .reportAttr[data-l1key=extension]').value()
      if (extension != 'pdf') {
        $('#div_imgreport').append('<img class="img-responsive" src="core/php/downloadFile.php?pathfile=data/report/' + type + '/' + id + '/' + filename + '.' + extension + '" />')
      } else {
        $('#div_imgreport').append('{{Aucun aperçu possible en pdf}}')
      }
      $('#currentReport').html('<i class="fas fa-clipboard-check"></i> ' + filename + ' (' + type + ')')
    }
  })
}


$('#bt_download').on('click', function() {
  var type = $('#div_reportForm .reportAttr[data-l1key=type]').value()
  var id = $('#div_reportForm .reportAttr[data-l1key=id]').value()
  var filename = $('#div_reportForm .reportAttr[data-l1key=filename]').value()
  var extension = $('#div_reportForm .reportAttr[data-l1key=extension]').value()
  window.open('core/php/downloadFile.php?pathfile=data/report/' + type + '/' + id + '/' + filename + '.' + extension, "_blank", null)
});

$('#bt_remove').on('click', function() {
  var report = $('#div_reportForm .reportAttr[data-l1key=filename]').value() + '.' + $('#div_reportForm .reportAttr[data-l1key=extension]').value()
  jeedom.report.remove({
    type: $('#div_reportForm .reportAttr[data-l1key=type]').value(),
    id: $('#div_reportForm .reportAttr[data-l1key=id]').value(),
    report: report,
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(data) {
      $('#div_reportForm').hide()
      $('.li_report[data-report="' + report + '"]').remove()
      $('.li_reportType.active .number').text($('.li_reportType.active .number').text() - 1)
    }
  })
})

$('#bt_removeAll').on('click', function() {
  jeedom.report.removeAll({
    type: $('#div_reportForm .reportAttr[data-l1key=type]').value(),
    id: $('#div_reportForm .reportAttr[data-l1key=id]').value(),
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(data) {
      $('#div_reportForm').hide()
      $('.li_report').remove()
      $('.li_reportType.active .number').text('0')
    }
  })
})