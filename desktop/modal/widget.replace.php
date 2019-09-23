<?php
if (!isConnect('admin')) {
  throw new Exception('{{401 - Accès non autorisé}}');
}
global $JEEDOM_INTERNAL_CONFIG;
$select = array('dashboard' => '','mobile'=>'');
foreach (cmd::availableWidget('dashboard') as $type => $value) {
  if(!isset($JEEDOM_INTERNAL_CONFIG['cmd']['type'][$type])){
    continue;
  }
  foreach ($value as $subtype => $value2) {
    if($subtype == '' || !isset($JEEDOM_INTERNAL_CONFIG['cmd']['type'][$type]['subtype'][$subtype])){
      continue;
    }
    foreach ($value2 as $name => $widget) {
      if($name == ''){
        continue;
      }
      $select['dashboard'] .= '<option data-type="'.$type.'"  data-subtype="'.$subtype.'" value="'.$widget['location'].'::'.$widget['name'].'">'.$type.' - '.$subtype.' - '.$widget['name'].'</option>';
    }
  }
}
foreach (cmd::availableWidget('mobile') as $type => $value) {
  if(!isset($JEEDOM_INTERNAL_CONFIG['cmd']['type'][$type])){
    continue;
  }
  foreach ($value as $subtype => $value2) {
    if($subtype == '' || !isset($JEEDOM_INTERNAL_CONFIG['cmd']['type'][$type]['subtype'][$subtype])){
      continue;
    }
    foreach ($value2 as $name => $widget) {
      if($name == ''){
        continue;
      }
      $select['mobile'] .= '<option data-type="'.$type.'"  data-subtype="'.$subtype.'" value="'.$widget['location'].'::'.$widget['name'].'">'.$type.' - '.$subtype.' - '.$widget['name'].'</option>';
    }
  }
}
?>
<div id="form_widgetReplace">
  <div style="display: none;" id="md_widgetReplaceAlert"></div>
  <legend><i class="fas fa-desktop"></i> {{Dashboard}}</legend>
  <form class="form-horizontal">
    <fieldset>
      <div class="form-group">
        <label class="col-lg-3 control-label">{{Je veux remplacer}}</label>
        <div class="col-lg-8">
          <select class="form-control widgetReplaceAttrdashboard" data-l1key="replace">
            <?php echo $select['dashboard']; ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label">{{Par}}</label>
        <div class="col-lg-8">
          <select class="form-control widgetReplaceAttrdashboard" data-l1key="by">
            <?php echo $select['dashboard']; ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label"></label>
        <div class="col-lg-8">
          <a class="btn btn-success bt_replaceWidget" data-version="dashboard"><i class="fas fa-check"></i> {{Remplacer}}</a>
        </div>
      </div>
    </fieldset>
  </form>
  <legend><i class="fas fa-tablet-alt"></i> {{Mobile}}</legend>
  <form class="form-horizontal">
    <fieldset>
      <div class="form-group">
        <label class="col-lg-3 control-label">{{Je veux remplacer}}</label>
        <div class="col-lg-8">
          <select class="form-control widgetReplaceAttrmobile" data-l1key="replace">
            <?php echo $select['mobile']; ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label">{{Par}}</label>
        <div class="col-lg-8">
          <select class="form-control widgetReplaceAttrmobile" data-l1key="by">
            <?php echo $select['mobile']; ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label"></label>
        <div class="col-lg-8">
          <a class="btn btn-success bt_replaceWidget" data-version="mobile"><i class="fas fa-check"></i> {{Remplacer}}</a>
        </div>
      </div>
    </fieldset>
  </form>
</div>
<script>
$('.bt_replaceWidget').off('click').on('click',function(){
  var version = $(this).attr('data-version');
  var opt1 = $('.widgetReplaceAttr'+version+'[data-l1key=replace] option:selected');
  var opt2 = $('.widgetReplaceAttr'+version+'[data-l1key=by] option:selected');
  if(opt1.attr('data-type') != opt2.attr('data-type')){
    $('#md_widgetReplaceAlert').showAlert({message: '{{Le type de la commande à replacer doit etre le meme que le type de la commande remplacante}}', level: 'danger'});
    return;
  }
  if(opt1.attr('data-subtype') != opt2.attr('data-subtype')){
    $('#md_widgetReplaceAlert').showAlert({message: '{{Le sous-type de la commande à replacer doit etre le meme que le sous-type de la commande remplacante}}', level: 'danger'});
    return;
  }
  var info = $('#form_widgetReplace').getValues('.widgetReplaceAttr'+version)[0];
  jeedom.widgets.replacement({
    version : version,
    replace : info.replace,
    by : info.by,
    error: function (error) {
      $('#md_widgetReplaceAlert').showAlert({message: error.message, level: 'danger'});
    },
    success : function(data){
      $('#md_widgetReplaceAlert').showAlert({message: '{{Remplacement réalisé avec succès. Nombre de widget remplacé :}} '+data, level: 'success'});
    }
  })
});
</script>
