  <?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
?>
 <style>
   .bs-sidenav .list-group-item{
    padding : 2px 2px 2px 2px;
  }
</style>
<div id="div_rowSystemCommand" class="row">
 <div class="col-lg-2 col-md-3 col-sm-4" style="overflow-y:auto;overflow-x:hidden;">
  <div class="bs-sidebar">
   <ul class="nav nav-list bs-sidenav list-group" id='ul_listSqlHistory'></ul>

   <ul class="nav nav-list bs-sidenav list-group" id='ul_listSqlRequest'>
    <li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
    <li class="cursor list-group-item list-group-item-success"><a class="bt_dbCommand" data-command="SHOW TABLES;">Tables</a></li>
  </ul>
</div>
</div>
<div class="col-lg-10 col-md-9 col-sm-8" style="border-left: solid 1px #EEE; padding-left: 25px;overflow-y:hidden;overflow-x:hidden;">

  <h3 id="h3_executeCommand">{{Cliquez sur une commande à droite ou tapez une commande personnalisée ci-dessous}}</h3>
  <input id="in_specificCommand" class="form-control" style="width:90%;display:inline-block;" /> <a id="bt_validateSpecifiCommand" class="btn btn-warning" style="position:relative;top:-2px;"><i class="fa fa-check"></i> {{OK}}</a>
  <div id="div_commandResult" style="overflow: auto;"></div>
</div>
</div>

<script>
  function dbGenerateTableFromResponse(_response){
    var result = '<table class="table table-condensed table-bordered">';
    result += '<thead>';
    result += '<tr>';
    for(var i in _response[0]){
      result += '<th>';
      result += i;
      result += '</th>';
    }
    result += '</tr>';
    result += '</thead>';
    result += '<tbody>';
    for(var i in _response){
     result += '<tr>';
     for(var j in _response[i]){
       result += '<td>';
       result += _response[i][j];
       result += '</td>';
     }
     result += '</tr>';
   }
   result += '</tbody>';
   result += '</table>';
   return result;
 }


 var hWindow = $('#div_rowSystemCommand').parent().outerHeight() - 30;
 $('#div_rowSystemCommand > div').height(hWindow);
 $('#div_commandResult').height(hWindow - 120);

 $('.bt_dbCommand').off('click').on('click',function(){
  var command = $(this).attr('data-command');
  $('#div_commandResult').empty();
  if($(this).parent().hasClass('list-group-item-danger')){
   bootbox.confirm('{{Etes-vous sûr de vouloir éxécuter cette commande : }}<strong>'+command+'</strong> ? {{Celle-ci est classé en dangereuse}}', function (result) {
    if (result) {
     jeedom.ssh({
      command : command,
      success : function(log){
       $('#h3_executeCommand').empty().append('{{Commande : }}'+command);
       $('#div_commandResult').append(dbGenerateTableFromResponse(log));
     }
   })
   }
 });
 }else{
   jeedom.db({
    command : command,
    success : function(log){
     $('#h3_executeCommand').empty().append('{{Commande : }}'+command);
     $('#div_commandResult').append(dbGenerateTableFromResponse(log));
   }
 })
 }
});

 $('#ul_listSqlHistory').off('click','.bt_dbCommand').on('click','.bt_dbCommand',function(){
  var command = $(this).attr('data-command');
  $('#div_commandResult').empty();
  jeedom.db({
    command : command,
    success : function(log){
     $('#h3_executeCommand').empty().append('{{Commande : }}'+command);
     $('#div_commandResult').append(dbGenerateTableFromResponse(log));
   }
 })
});

 $('#bt_validateSpecifiCommand').off('click').on('click',function(){
  var command = $('#in_specificCommand').value();
  $('#div_commandResult').empty();
  jeedom.db({
    command : command,
    success : function(log){
      $('#h3_executeCommand').empty().append('{{Commande : }}'+command);
      $('#div_commandResult').append(dbGenerateTableFromResponse(log));
      $('#ul_listSqlHistory').prepend('<li class="cursor list-group-item list-group-item-success"><a class="bt_dbCommand" data-command="'+command+'">'+command+'</a></li>');
      var kids = $('#ul_listSqlHistory').children();
      if (kids.length >= 10) {
        kids.last().remove();
      }


    }
  })
});

 $('#in_specificCommand').keypress(function(e) {
  if(e.which == 13) {
   var command = $('#in_specificCommand').value();
   $('#div_commandResult').empty();
   jeedom.db({
    command : command,
    success : function(log){
      $('#h3_executeCommand').empty().append('{{Commande : }}'+command);
      $('#div_commandResult').append(dbGenerateTableFromResponse(log));
      $('#ul_listSqlHistory').prepend('<li class="cursor list-group-item list-group-item-success"><a class="bt_dbCommand" data-command="'+command+'">'+command+'</a></li>');
      var kids = $('#ul_listSqlHistory').children();
      if (kids.length >= 10) {
        kids.last().remove();
      }
    }
  })
 }
});
</script>
