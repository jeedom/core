<?php
if (!isConnect('admin')) {
  throw new Exception('{{401 - Accès non autorisé}}');
}
require_once __DIR__ . "/../../install/database.php";
$database = json_decode(file_get_contents(__DIR__.'/../../install/database.json'),true);
$result = DB::compareDatabase($database);
?>
<div style="display: none;" id="div_dbCheckAlert"></div>
<a class="btn btn-warning pull-right bt_correctTable" data-table="all"><i class="fas fa-screwdriver"></i> {{Corriger tout}}</a>
<br/><br/>
<table class="table table-condensed">
  <thead>
    <tr>
      <th>{{Table}}</th>
      <th>{{Status}}</th>
      <th>{{Champs}}</th>
      <th>{{SQL}}</th>
      <th>{{Action}}</th>
    </tr>
  </thead>
  <tbody>
    <?php
    foreach ($result as $tname => $tinfo) {
      $sql = $tinfo['sql'];
      echo '<tr>';
      echo '<td>';
      echo $tname;
      echo '</td>';
      if($tinfo['status'] == 'ok'){
        echo '<td class="alert alert-success">OK</td>';
      }else{
        echo '<td class="alert alert-danger">'.$tinfo['message'].'</td>';
      }
      echo '<td>';
      foreach ($tinfo['fields'] as $fname => $finfo) {
        if($finfo['status'] == 'ok'){
          continue;
        }
        $sql .= "\n".$finfo['sql'];
        echo '<span class="label label-danger">'.$fname.'</span><br/>';
      }
      echo '</td>';
      echo '<td>';
      echo $sql;
      echo '</td>';
      echo '<td>';
      if($sql != ''){
        echo '<a class="btn btn-sm btn-warning bt_correctTable" data-table="'.$tname.'"><i class="fas fa-wrench"></i> {{Corriger}}</a>';
      }
      echo '</td>';
      echo '</tr>';
    }
    ?>
  </tbody>
</table>

<script>
$('.bt_correctTable').off('click').on('click',function(){
  var el = $(this);
  bootbox.confirm('{{Etes-vous sûr de vouloir corriger la table }}'+el.data('table')+' ?', function (result) {
    if (result) {
      jeedom.dbcorrectTable({
        table : el.data('table'),
        error : function(error){
          $('#div_dbCheckAlert').showAlert({message: error.message, level: 'danger'});
        },
        success : function(){
          $('#md_modal').dialog({title: "{{Vérification base de données}}"});
          $("#md_modal").load('index.php?v=d&modal=db.check').dialog('open');
        }
      });
    }
  });
});
</script>
