"use strict"

$('body').attr('data-page', 'log')

var $rawLogCheck = $('#brutlogcheck')

function initLog(_log) {
  $('#bt_eraseLogSearch').insertAfter($('#in_globalLogSearch'))
  $('#pre_globallog').empty()
  jeedom.log.list({
    error: function(error) {
      $.fn.showAlert({message: error.message, level: 'danger'})
    },
    success : function(_data) {
      var li = ' <ul data-role="listview" data-inset="false">'
      for (var i in _data) {
        li += '<li><a href="#" class="link" data-page="log" data-title="<i class=\'far fa-file\'></i> '+_data[i]+'" data-option="'+_data[i]+'"><span><i class=\'fa fa-file\'></i> '+_data[i]+'</a></li>'
      }
      li += '</ul>'
      jeedomUtils.loadPanel(li)
    }
  })

  if (isset(_log)) {
    setTimeout(function() {
      if (_log == "-1") {
        _log = $('#bottompanel .ui-listview li.ui-first-child > a > span').text().trim()
      }

      jeedom.log.autoupdate({
        log : _log,
        display : $('#pre_globallog'),
        search : $('#in_globalLogSearch'),
        control : $('#bt_globalLogStopStart'),
      })
    }, 250)

    $("#bt_clearLog").off('click').on('click', function(event) {
      jeedom.log.clear({
        log : _log,
        error: function(error) {
          $.fn.showAlert({message: error.message, level: 'danger'})
        },
        success: function(data) {
          if($('#bt_globalLogStopStart').attr('data-state') == 0){
            $('#bt_globalLogStopStart').click()
          }
        }
      })
    })

    $("#bt_removeLog").off('click').on('click', function(event) {
      jeedom.log.remove({
        log : _log,
        error: function(error) {
          $.fn.showAlert({message: error.message, level: 'danger'})
        },
        success: function(data) {
          initLog()
        }
      })
    })
  }

  $('#bt_eraseLogSearch').off('click').on('click',function() {
    $('#in_globalLogSearch').val('').keyup()
  })

  $("#bt_removeAllLog").off('click').on('click', function(event) {
    var result = confirm("{{Êtes-vous sûr de vouloir supprimer tous les logs ?}}")
    if (result) {
      jeedom.log.removeAll({
        error: function(error) {
          $.fn.showAlert({message: error.message, level: 'danger'})
        },
        success: function(data) {
          initLog()
        }
      })
    }
  })
}