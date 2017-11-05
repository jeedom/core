function initAlert() {
  var rightPanel = '<ul data-role="listview" class="ui-icon-alt">';
  rightPanel += '<li><a id="bt_refreshAlert" href="#"><i class="fa fa-refresh"></i> {{Rafraichir}}</a></li>';
  rightPanel += '<li><a class="ui-bottom-sheet-link ui-btn ui-btn-inline waves-effect waves-button" href="index.php?v=d"><i class="fa fa-desktop"></i> {{Version desktop}}</a></li>';
  rightPanel += '<li><a class="link ui-bottom-sheet-link ui-btn ui-btn-inline waves-effect waves-button" data-page="cron" data-title="{{Crons}}"><i class="fa fa-cogs" ></i> {{Crons}}</a></li>';
  rightPanel += '<li><a class="link ui-bottom-sheet-link ui-btn ui-btn-inline waves-effect waves-button" data-page="health" data-title="{{Santé}}"><i class="icon divers-caduceus3" ></i> {{Santé}}</a></li>';
  rightPanel += '</ul>';
  panel(rightPanel);
  getAlert();

  $('#bt_refreshAlert').on('click',function(){
    getAlert();
  });

  function getAlert(){
     $.ajax({
        type: 'POST',
        url: 'core/ajax/eqLogic.ajax.php',
        data: {
            action: 'getAlert',
            version: 'mview'
        },
        dataType: 'json',
        error: function (request, status, error) {
            handleAjaxError(request, status, error);
        },
        success: function (data) {
            if (data.state != 'ok') {
                $('#div_alert').showAlert({message: data.result, level: 'danger'});
                return;
            }
            $('#div_displayAlert').empty();
            for (var i in data.result) {
                $('#div_displayAlert').append(data.result[i]).trigger('create');
            }
            setTileSize('.eqLogic');
        }
    });
  }
}