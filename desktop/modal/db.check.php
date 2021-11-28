<?php
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

if (!isConnect('admin')) {
  throw new Exception('{{401 - Accès non autorisé}}');
}
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
      <th>{{Index}}</th>
      <th>{{SQL}}</th>
      <th>{{Action}}</th>
    </tr>
  </thead>
  <tbody>
    <?php
    foreach ($result as $tname => $tinfo) {
      $sql = '';
      if($tinfo['sql'] != ''){
        $sql = $tinfo['sql'].';';
      }
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
        if($finfo['sql'] != ''){
          $sql .= "\n".$finfo['sql'].';';
        }
        echo '<span class="label label-danger" title="'.$finfo['message'].'">'.$fname.'</span><br/>';
      }
      echo '</td>';
      echo '<td>';
      foreach ($tinfo['indexes'] as $iname => $iinfo) {
        if($iinfo['status'] == 'ok'){
          continue;
        }
        if(isset($iinfo['presql']) && $iinfo['presql'] != ''){
          $sql .= "\n".$iinfo['presql'].';';
        }
        if($iinfo['sql'] != ''){
          $sql .= "\n".$iinfo['sql'].';';
        }
        echo '<span class="label label-danger" title="'.$iinfo['message'].'">'.$iname.'</span><br/>';
      }
      echo '</td>';
      echo '<td>';
      echo str_replace(';;',';',$sql);
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
$('.bt_correctTable').off('click').on('click',function() {
  var el = $(this)
  if (el.data('package') == 'all') {
    var text = '{{Êtes-vous sûr de vouloir corriger toute les tables ?}}'
  } else {
    var text = '{{Êtes-vous sûr de vouloir corriger la table}}' + ' ' + el.data('table') + ' ?'
  }
  bootbox.confirm(text, function(result) {
    if (result) {
      jeedom.dbcorrectTable({
        table : el.data('table'),
        error : function(error) {
          $('#div_dbCheckAlert').showAlert({message: error.message, level: 'danger'})
        },
        success : function() {
          $('#md_modal').dialog({title: "{{Vérification base de données}}"}).load('index.php?v=d&modal=db.check').dialog('open')
        }
      })
    }
  })
})
</script>