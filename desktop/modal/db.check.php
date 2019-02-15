<?php
if (!isConnect('admin')) {
  throw new Exception('{{401 - Accès non autorisé}}');
}
require_once __DIR__ . "/../../install/database.php";
$database = json_decode(file_get_contents(__DIR__.'/../../install/database.json'),true);
$result = DB::compareDatabase($database);
?>
<table class="table table-condensed">
  <thead>
    <tr>
      <th>{{Table}}</th>
      <th>{{Status}}</th>
      <th>{{Champs}}</th>
      <th>{{SQL}}</th>
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
      echo '</tr>';
    }
    
    
    ?>
  </tbody>
</table>
