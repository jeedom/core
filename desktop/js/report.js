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

$('.li_type').on('click', function(event) {
  var currentType = document.querySelector('.li_type.active').getAttribute('data-type')
  var newType = this.getAttribute('data-type')
  document.querySelectorAll('.li_type').removeClass('active')
  this.addClass('active')
  document.querySelectorAll('.reportType').unseen()
  document.querySelector('.reportType.' + newType).seen()
  if (newType != currentType) {
    document.querySelectorAll('#ul_report .li_report').remove()
    document.getElementById('div_reportForm').unseen()
  }
})

$('.li_reportType').on('click', function(event) {
  document.querySelectorAll('.li_reportType').removeClass('active')
  this.addClass('active')
  getReportList(this.getAttribute('data-type'), this.getAttribute('data-id'))
})

function getReportList(_type, _id) {
  jeedom.report.list({
    type: _type,
    id: _id,
    error: function(error) {
      jeedomUtils.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(data) {
      document.querySelectorAll('#ul_report .li_report').remove()
      var ul = '';
      for (var i in data) {
        ul += '<li class="cursor li_report" data-type=' + _type + ' data-id=' + _id + ' data-report="' + i + '"><a>' + i + '</a></li>'
      }
      document.getElementById('ul_report').insertAdjacentHTML('beforeend', ul)
    }
  })
}

$('#ul_report').on('click', '.li_report', function(event) {
  document.querySelectorAll('.li_report').removeClass('active')
  this.addClass('active')
  getReport(this.getAttribute('data-type'), this.getAttribute('data-id'), this.getAttribute('data-report'))
})

function getReport(_type, _id, _report) {
  jeedom.report.get({
    type: _type,
    id: _id,
    report: _report,
    error: function(error) {
      jeedomUtils.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(data) {
      document.getElementById('div_reportForm').setJeeValues(data, '.reportAttr')
      document.getElementById('div_reportForm').seen()
      document.getElementById('div_imgreport')?.empty()
      var type = document.querySelector('#div_reportForm .reportAttr[data-l1key=type]').jeeValue()
      var id = document.querySelector('#div_reportForm .reportAttr[data-l1key=id]').jeeValue()
      var filename = document.querySelector('#div_reportForm .reportAttr[data-l1key=filename]').jeeValue()
      var extension =document.querySelector('#div_reportForm .reportAttr[data-l1key=extension]').jeeValue()
      if (extension != 'pdf') {
        $('#div_imgreport').append('<img class="img-responsive" src="core/php/downloadFile.php?pathfile=data/report/' + type + '/' + id + '/' + filename + '.' + extension + '" />')
      }
      else {
        $('#div_imgreport').append('<object data="core/php/downloadFile.php?pathfile=data/report/' + type+'/'+id+'/'+filename+'.'+extension+'" type="application/pdf" style="width:90%;height:800px;">{{Le fichier PDF ne peut pas être visualisé dans ce navigateur, veuillez le télécharger.}}</object>')
      }
      $('#currentReport').html('<i class="fas fa-clipboard-check"></i> ' + filename + ' (' + type + ')')
    }
  })
}


$('#bt_download').on('click', function(event) {
  var type = document.querySelector('#div_reportForm .reportAttr[data-l1key=type]').jeeValue()
  var id = document.querySelector('#div_reportForm .reportAttr[data-l1key=id]').jeeValue()
  var filename = document.querySelector('#div_reportForm .reportAttr[data-l1key=filename]').jeeValue()
  var extension = document.querySelector('#div_reportForm .reportAttr[data-l1key=extension]').jeeValue()
  window.open('core/php/downloadFile.php?pathfile=data/report/' + type + '/' + id + '/' + filename + '.' + extension, "_blank", null)
})

$('#bt_remove').on('click', function(event) {
  var filename = document.querySelector('#div_reportForm .reportAttr[data-l1key=filename]').jeeValue()
  var extension = document.querySelector('#div_reportForm .reportAttr[data-l1key=extension]').jeeValue()
  var report = filename + '.' + extension
  jeedom.report.remove({
    type: document.querySelector('#div_reportForm .reportAttr[data-l1key=type]').jeeValue(),
    id: document.querySelector('#div_reportForm .reportAttr[data-l1key=id]').jeeValue(),
    report: report,
    error: function(error) {
      jeedomUtils.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(data) {
      document.getElementById('div_reportForm').unseen()
      document.querySelector('.li_report[data-report="' + report + '"]').remove()
      document.querySelector('.li_reportType.active .number').text = document.querySelector('.li_reportType.active .number').text - 1
    }
  })
})

$('#bt_removeAll').on('click', function(event) {
  jeedom.report.removeAll({
    type: document.querySelector('#div_reportForm .reportAttr[data-l1key=type]').jeeValue(),
    id: document.querySelector('#div_reportForm .reportAttr[data-l1key=id]').jeeValue(),
    error: function(error) {
      jeedomUtils.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(data) {
      document.getElementById('div_reportForm').unseen()
      document.querySelectorAll('.li_report').remove()
      document.querySelector('.li_reportType.active .number').text = '0'
    }
  })
})

//Register events on top of page container:

//Manage events outside parents delegations:

//Specials

/*Events delegations
*/