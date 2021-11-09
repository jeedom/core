"use strict"

$('body').attr('data-page', 'health')

function initHealth() {
  var rightPanel = '<ul data-role="listview" class="ui-icon-alt">'
  rightPanel += '<li><a id="bt_refreshCron" href="#"><i class="fas fa-refresh"></i> {{Rafraichir}}</a></li>'
  rightPanel += '<li><a class="ui-bottom-sheet-link ui-btn ui-btn-inline waves-effect waves-button" href="index.php?v=d"><i class="fas fa-desktop"></i> {{Version desktop}}</a></li>'
  rightPanel += '<li><a class="link ui-bottom-sheet-link ui-btn ui-btn-inline waves-effect waves-button" data-page="deamon" data-title="{{Démons}}"><i class="fas fa-bug" ></i> {{Démons}}</a></li>'
  rightPanel += '<li><a class="link ui-bottom-sheet-link ui-btn ui-btn-inline waves-effect waves-button" data-page="cron" data-title="{{Crons}}"><i class="fas fa-cogs" ></i> {{Crons}}</a></li>'
  rightPanel += '</ul>'
  jeedomUtils.loadPanel(rightPanel)
  getHealth()
}

$('#bt_refreshCron').on('click',function(){
  getHealth()
})

function getHealth(){
  $('#table_health tbody').empty()
  jeedom.health({
    error: function(error) {
      $.fn.showAlert({message: error.message, level: 'danger'})
    },
    success: function(data) {
      var html = ''
      for (var i in data) {
        html += '<tr>'
        html += '<td>'
        html += data[i].name
        html += '</td>'
        if(data[i].state){
          html += '<td class="alert alert-success">'
        }else{
          html += '<td class="alert alert-danger">'
        }
        html += data[i].result
        html += '</td>'
        html += '</tr>'
      }
      $('#table_health tbody').append(html)
    }
  })
}