"use strict"

$('body').attr('data-page', 'scenariolog')

function initScenariolog(_scenarioId) {
  $('#bt_eraseLogSearch').insertAfter($('#in_globalLogSearch'))
  $('#pre_scenariolog').empty()
  if (isset(_scenarioId)) {
    setTimeout(function() {
      jeedom.log.autoupdate({
        log : 'scenarioLog/scenario'+_scenarioId+'.log',
        display : $('#pre_scenariolog'),
        search : $('#in_globalLogSearch'),
        control : $('#bt_globalLogStopStart'),
      })
    }, 250)

    $("#bt_clearLog").off('click').on('click', function(event) {
      jeedom.scenario.emptyLog({
        id : _scenarioId,
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
  }

  $('#bt_eraseLogSearch').off('click').on('click',function() {
    $('#in_globalLogSearch').val('').keyup()
  })
}