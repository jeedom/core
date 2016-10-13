function initHealth() {
  var rightPanel = '<ul data-role="listview" class="ui-icon-alt">';
  rightPanel += '<li><a id="bt_refreshCron" href="#"><i class="fa fa-refresh"></i> {{Rafraichir}}</a></li>';
  rightPanel += '</ul>';
  panel(rightPanel);
  getHealth();

  $('#bt_refreshCron').on('click',function(){
    getHealth();
  });

  function getHealth(){
    $('#table_health tbody').empty();
    jeedom.health({
      error: function (error) {
        $('#div_alert').showAlert({message: error.message, level: 'danger'});
      },
      success: function (data) {
        var html = '';
        for(var i in data){
         html += '<tr>';
         html += '<td>';
         html += data[i].name;
         html += '</td>';
          html += '<td>';
         if(data[i].state){
          html += '<td style="background-color:rgba(148, 202, 2, 0.3);color:rgba(255,255,255,0.85)">';
        }else{
          html += '<td style="background-color:rgb(244,67,54);color:rgba(255,255,255,0.85)">';
        }
        html += data[i].result;
         html += '<a>';
        html += '</td>';
        html += '<td>';
        html += data[i].comment;
        html += '</td>';
        html += '</tr>';
      }
      $('#table_health tbody').append(html);
    }
  });
  }


}