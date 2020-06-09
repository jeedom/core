<?php
if (!isConnect('admin')) {
  throw new Exception('{{401 - Accès non autorisé}}');
}
?>

<table class="table table-condensed table-bordered">
  <thead>
    <tr>
      <th>{{Nom}}</th>
      <th>{{Temps}}</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $benchmark = jeedom::benchmark();
    foreach ($benchmark as $key => $value) {
      $tr = '<tr>';
      $tr .= '<td>' . $key . '</td>';
      $tr .= '<td>' . $value . '</td>';
      $tr .= '</tr>';
      echo $tr;
    }
    ?>
  </tbody>
</table>