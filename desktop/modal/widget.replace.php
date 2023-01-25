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
global $JEEDOM_INTERNAL_CONFIG;
$select = array('dashboard' => '','mobile'=>'');
$seld = array();
foreach ((cmd::availableWidget('dashboard')) as $type => $value) {
  if (!isset($JEEDOM_INTERNAL_CONFIG['cmd']['type'][$type])) {
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
      $seld[] = '<option data-type="'.$type.'"  data-subtype="'.$subtype.'" value="'.$widget['location'].'::'.$widget['name'].'">'.$type.' - '.$subtype.' - '.$widget['location'].'::'.$widget['name'].'</option>';
    }
  }
}
sort($seld);
$select['dashboard'] = implode('',$seld);
$selm = array();
foreach ((cmd::availableWidget('mobile')) as $type => $value) {
  if (!isset($JEEDOM_INTERNAL_CONFIG['cmd']['type'][$type])) {
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
      $selm[] = '<option data-type="'.$type.'"  data-subtype="'.$subtype.'" value="'.$widget['location'].'::'.$widget['name'].'">'.$type.' - '.$subtype.' - '.$widget['location'].'::'.$widget['name'].'</option>';
    }
  }
}
sort($selm);
$select['mobile'] = implode('',$selm);
?>

<div id="md_widgetReplace" data-modalType="md_widgetReplace">
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
/*Events delegations
*/
document.getElementById('md_widgetReplace').addEventListener('click', function(event) {
  var _target = null
  if (_target = event.target.closest('.bt_replaceWidget')) {
    var version = _target.getAttribute('data-version')
    var opt1 = document.querySelector('#md_widgetReplace .widgetReplaceAttr' + version + '[data-l1key="replace"]').selectedOptions[0]
    var opt2 = document.querySelector('#md_widgetReplace .widgetReplaceAttr' + version + '[data-l1key="by"]').selectedOptions[0]

    if (opt1.getAttribute('data-type') != opt2.getAttribute('data-type')) {
      jeedomUtils.showAlert({message: '{{Le type de la commande à replacer doit etre le meme que le type de la commande remplacante}}', level: 'danger'})
      return
    }
    if (opt1.getAttribute('data-subtype') != opt2.getAttribute('data-subtype')) {
      jeedomUtils.showAlert({message: '{{Le sous-type de la commande à replacer doit etre le meme que le sous-type de la commande remplacante}}', level: 'danger'})
      return
    }
    var info = document.getElementById('md_widgetReplace').getJeeValues('.widgetReplaceAttr' + version)[0]

    jeedom.widgets.replacement({
      version : version,
      replace : info.replace,
      by : info.by,
      error: function(error) {
        jeedomUtils.showAlert({message: error.message, level: 'danger'})
      },
      success : function(data) {
        jeedomUtils.showAlert({message: '{{Remplacement réalisé avec succès. Nombre de widget remplacé :}} '+data, level: 'success'})
      }
    })
    return
  }
})

</script>