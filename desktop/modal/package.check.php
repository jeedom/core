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
    <a id="bt_refreshPackage" class="btn btn-sm btn-default"><i class="fas fa-sync"></i> {{Rafraichir}}
    </a><a class="btn btn-sm btn-warning bt_correctPackage" data-package="all"><i class="fas fa-screwdriver"></i> {{Corriger tout}}</a>
  </span>
</div>
<br/><br/>
<table id="table_packages" class="table table-condensed">
  <thead>
    <tr>
      <th>{{Package}}</th>
      <th>{{Type}}</th>
      <th>{{Status}}</th>
      <th>{{Obligatoire}}</th>
      <th>{{Voulu par}}</th>
      <th>{{Version}}</th>
      <th>{{Remarque}}</th>
      <th>{{Commande}}</th>
      <th>{{Action}}</th>
    </tr>
  </thead>
  <tbody>
    <?php
    foreach ($datas as $package => $info) {
      $_echo = '';
      $_echo .= '<tr>';
      $_echo .= '<td>';
      $_echo .= $info['name'];
      $_echo .= '</td>';
      $_echo .= '<td>';
      $_echo .= $info['type'];
      $_echo .= '</td>';

      if($info['status'] == 1){
        $_echo .= '<td class="alert-success">OK</td>';
      }elseif($info['status'] == 2){
        $_echo .= '<td class="alert-success">OK ('.$info['alternative_found'].')</td>';
      }else{
        if($info['needUpdate']){
          $_echo .= '<td class="alert-warning">{{Mise à jour}}</td>';
        }else{
          $_echo .= '<td class="alert-danger">NOK</td>';
        }
      }

      $_echo .= '<td>';
      if($info['optional'] == 0){
        $_echo .= '<span class="label label-warning">{{oui}}</span>';
      }else{
        $_echo .= '<span class="label label-info">{{non}}</span>';
      }
      $_echo .= '</td>';

      $_echo .= '<td>';
      foreach ($info['needBy'] as $value) {
        $_echo .= '<span class="label label-primary">'.$value.'</span>';
      }
      $_echo .= '</td>';

      $_echo .= '<td>';
      $_echo .= $info['version'];
      if($info['needUpdate']){
        $_echo .= '/'.$info['needVersion'];
      }
      $_echo .= '</td>';
      
      $_echo .= '<td>';
      $_echo .= $info['remark'];
      $_echo .= '</td>';

      $_echo .= '<td>';
      $_echo .= $info['fix'];
      $_echo .= '</td>';
      $_echo .= '<td>';
      if(!$info['status']){
        $_echo .= '<a class="btn btn-xs btn-warning bt_correctPackage" data-package="'.$package.'"><i class="fas fa-wrench"></i> {{Corriger}}</a>';
      }
      $_echo .= '</td>';
      $_echo .= '</tr>';
      echo $_echo;
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
  if(el.data('package') == 'all'){
    var text = '{{Êtes-vous sûr de vouloir installer tous les packages non optionnel ?}}';
  }else{
    var text = '{{Êtes-vous sûr de vouloir installer le package}} '+el.data('package')+' ?';
  }
  bootbox.confirm(text, function (result) {
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
