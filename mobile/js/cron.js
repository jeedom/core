"use strict"

$('body').attr('data-page', 'cron')

function initCron() {
  var rightPanel = '<ul data-role="listview" class="ui-icon-alt">'
  rightPanel += '<li><a id="bt_refreshCron" href="#"><i class="fas fa-refresh"></i> {{Rafraichir}}</a></li>'
  rightPanel += '<li><a class="ui-bottom-sheet-link ui-btn ui-btn-inline waves-effect waves-button" href="index.php?v=d"><i class="fas fa-desktop"></i> {{Version desktop}}</a></li>'
  rightPanel += '<li><a class="link ui-bottom-sheet-link ui-btn ui-btn-inline waves-effect waves-button" data-page="deamon" data-title="{{Démons}}"><i class="fas fa-bug" ></i> {{Démons}}</a></li>'
  rightPanel += '<li><a class="link ui-bottom-sheet-link ui-btn ui-btn-inline waves-effect waves-button" data-page="health" data-title="{{Santé}}"><i class="icon divers-caduceus3" ></i> {{Santé}}</a></li>'
  rightPanel += '</ul>'
  jeedomUtils.loadPanel(rightPanel)
  getCronState()
}

$('#bt_refreshCron').on('click',function(){
  getCronState()
})

function getCronState() {
  $('#table_cron tbody').empty()
  jeedom.cron.all({
    error: function(error) {
      $.fn.showAlert({message: error.message, level: 'danger'})
    },
    success: function(data) {
     var html = ''
     var crons = data
     for(var i in crons){
       html += '<tr>'
       html += '<td>'
       html += crons[i].class+'::'+crons[i].function
       html += '</td>'
       html += '<td>'
       html += crons[i].state
       html += '</td>'
       html += '<td>'
       html += crons[i].lastRun
       html += '</td>'
       html += '<td>'
       html += '<a class="bt_cronAction ui-btn ui-mini ui-btn-inline ui-btn-raised clr-primary" data-action="start" data-id="'+crons[i].id+'"><i class="fas fa-play"></i></a> '
       html += '<a class="bt_cronAction ui-btn ui-mini ui-btn-inline ui-btn-raised clr-warning" data-action="stop" data-id="'+crons[i].id+'"><i class="fas fa-stop"></i></a> '
       html += '</td>'
       html += '</tr>'
     }
     $('#table_cron tbody').append(html)
   }
 })
}

$('#table_cron tbody').on('click','.bt_cronAction',function(){
  var id = $(this).data('id')
  var action = $(this).data('action')
  jeedom.cron.setState({
    id : id,
    state: action,
    forceRestart : 1,
    error: function (error) {
      $.fn.showAlert({message: error.message, level: 'danger'})
    },
    success: function () {
     getCronState()
   }
 })
})