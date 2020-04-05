<?php
if (!isConnect('admin')) {
  throw new Exception('{{401 - Accès non autorisé}}');
}
$result = array();
$result['core'] = system::checkAndInstall(json_decode(file_get_contents(__DIR__.'/../../install/packages.json'),true));
foreach (plugin::listPlugin(true,false,false,true) as $plugin) {
  if(file_exists(__DIR__.'/../../plugins/'.$plugin.'/plugin_info/packages.json')){
    $result[$plugin] = system::checkAndInstall(json_decode(file_get_contents(__DIR__.'/../../plugins/'.$plugin.'/plugin_info/packages.json'),true));
  }
}
$datas = array();
foreach ($result as $key => $packages) {
  foreach ($packages as $package => $info) {
    if(!isset($data[$package])){
      $datas[$package] = $info;
      $datas[$package]['needBy'] = array($key);
    }else{
      if($info['level'] < $datas[$package]['level']){
        $datas[$package]['level'] = $info['level'];
      }
      $datas[$package]['needBy'][] = $key;
    }
  }
}
ksort($datas);
if(count(system::ps('dpkg')) > 0 || count(system::ps('apt')) > 0){
  echo '<div class="alert alert-danger">{{Attention il y a déjà une installation de package en cours.Cliquez sur le bouton rafraichir jusqu\'a ce que ca soit fini}}</div>';
}
?>
<div style="display: none;" id="div_packageCheckAlert"></div>
<div class="input-group pull-right" style="display:inline-flex">
  <span class="input-group-btn">
    <a class="btn btn-default" id="bt_refreshPackage"><i class="fas fa-sync"></i> {{Rafraichir}}</a><a class="btn btn-warning bt_correctPackage" data-package="all"><i class="fas fa-screwdriver"></i> {{Corriger tout}}</a>
  </span>
</div>
<br/><br/>
<table class="table table-condensed">
  <thead>
    <tr>
      <th>{{Package}}</th>
      <th>{{Type}}</th>
      <th>{{Status}}</th>
      <th>{{Voulu par}}</th>
      <th>{{Obligatoire}}</th>
      <th>{{Version}}</th>
      <th>{{Commande}}</th>
      <th>{{Action}}</th>
    </tr>
  </thead>
  <tbody>
    <?php
    foreach ($datas as $package => $info) {
      echo '<tr>';
      echo '<td>';
      echo $info['name'];
      echo '</td>';
      echo '<td>';
      echo $info['type'];
      echo '</td>';
      if($info['status'] == 1){
        echo '<td class="alert alert-success">OK</td>';
      }elseif($info['status'] == 2){
        echo '<td class="alert alert-success">OK ('.$info['alternative_found'].')</td>';
      }else{
        echo '<td class="alert alert-danger">NOK</td>';
      }
      echo '<td>';
      foreach ($info['needBy'] as $value) {
        echo '<span class="label label-primary">'.$value.'</span>';
      }
      echo '</td>';
      echo '<td>';
      if($info['optional'] == 0){
        echo '<span class="label label-warning">{{oui}}</span>';
      }else{
        echo '<span class="label label-info">{{non}}</span>';
      }
      echo '</td>';
      echo '<td>';
      echo $info['version'];
      echo '</td>';
      echo '<td>';
      echo $info['fix'];
      echo '</td>';
      echo '<td>';
      if(!$info['status']){
        echo '<a class="btn btn-sm btn-warning bt_correctPackage" data-package="'.$package.'"><i class="fas fa-wrench"></i> {{Corriger}}</a>';
      }
      echo '</td>';
      echo '</tr>';
    }
    ?>
  </tbody>
</table>

<script>

$('#bt_refreshPackage').off('click').on('click',function(){
  $('#md_modal').dialog({title: "{{Vérification des packages}}"})
  .load('index.php?v=d&modal=package.check').dialog('open');
});

$('.bt_correctPackage').off('click').on('click',function(){
  var el = $(this);
  bootbox.confirm('{{Êtes-vous sûr de vouloir installer le package}} '+el.data('package')+' ?', function (result) {
    if (result) {
      jeedom.systemCorrectPackage({
        package : el.data('package'),
        error : function(error){
          $('#div_packageCheckAlert').showAlert({message: error.message, level: 'danger'});
        },
        success : function(){
          $('#div_packageCheckAlert').showAlert({message:'{{Installation lancée cela peut prendre plusieurs dizaines de minutes.}}', level: 'success'});
          $('#md_modal2').dialog({title: "{{Installation des packages}}"}).load('index.php?v=d&modal=log.display&log=packages').dialog('open')
        }
      });
    }
  });
});
</script>
