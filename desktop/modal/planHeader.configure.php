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

$planHeader = planHeader::byId(init('planHeader_id'));
if (!is_object($planHeader)) {
  throw new Exception('Impossible de trouver le plan');
}
sendVarToJS([
  'id' => $planHeader->getId(),
  'planHeader' => utils::o2a($planHeader)
]);
?>

<div id="div_alertPlanHeaderConfigure"></div>
<ul class="nav nav-tabs" role="tablist">
  <li role="presentation" class="active"><a href="#main" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-cog"></i> {{Général}}</a></li>
  <li role="presentation"><a href="#components" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-cubes"></i> {{Composants}}</a></li>
  <a class='btn btn-success btn-sm pull-right cursor' id='bt_saveConfigurePlanHeader'><i class="fas fa-check"></i> {{Sauvegarder}}</a>
</ul>
<div class="tab-content">
  <div role="tabpanel" class="tab-pane active" id="main">
    <div id="div_planHeaderConfigure">
      <form class="form-horizontal">
        <fieldset>
          <legend><i class="fas fa-cog"></i> {{Général}}</legend>
          <input type="text"  class="planHeaderAttr form-control" data-l1key="id" style="display: none;"/>
          <div class="form-group">
            <label class="col-lg-4 control-label">{{Nom}}</label>
            <div class="col-lg-2">
              <input class="planHeaderAttr form-control" data-l1key="name" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-4 control-label">{{Position}}</label>
            <div class="col-lg-2">
              <input type="number" class="planHeaderAttr form-control" data-l1key="order" min="0" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-4 control-label">{{Fond transparent}}</label>
            <div class="col-lg-2">
              <input type="checkbox" class="planHeaderAttr" data-l1key="configuration" data-l2key="backgroundTransparent" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-4 control-label">{{Couleur de fond}}</label>
            <div class="col-lg-2">
              <input type="color" class="planHeaderAttr form-control" data-l1key="configuration" data-l2key="backgroundColor" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-4 control-label">{{Code d'accès}}</label>
            <div class="col-lg-2">
              <input class="planHeaderAttr form-control inputPassword" data-l1key="configuration" data-l2key="accessCode" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-4 control-label">{{Icône}}</label>
            <div class="col-lg-1">
              <a class="btn btn-default btn-sm" id="bt_chooseIcon"><i class="fas fa-flag"></i> {{Choisir}}</a>
            </div>
            <div class="col-lg-2">
              <div class="planHeaderAttr" data-l1key="configuration" data-l2key="icon" ></div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-4 control-label">{{Image}}</label>
            <div class="col-lg-8">
              <span class="btn btn-default btn-file">
                <i class="fas fa-cloud-upload-alt"></i> {{Envoyer}}<input  id="bt_uploadImage" type="file" name="file" style="display: inline-block;">
              </span>
              <a class="btn btn-danger" id="bt_removeBackgroundImage"><i class="fas fa-trash"></i> {{Supprimer l'image}}</a>
            </div>
          </div>
        </fieldset>
      </form>
      <form class="form-horizontal">
        <fieldset>
          <legend><i class="icon techno-fleches"></i> {{Tailles}}</legend>
          <div class="form-group">
            <label class="col-lg-4 control-label">{{Taille (LxH)}}</label>
            <div class="col-lg-4">
              <input class="form-control input-sm planHeaderAttr" data-l1key='configuration' data-l2key="desktopSizeX" style="width: 80px;display: inline-block;"/>
              x
              <input class="form-control input-sm planHeaderAttr" data-l1key='configuration' data-l2key='desktopSizeY' style="width: 80px;display: inline-block;"/>
            </div>
          </div>
        </fieldset>
      </form>
    </div>
  </div>
  <div role="tabpanel" class="tab-pane" id="components">
    <form class="form-horizontal">
      <fieldset>
        <table class="table table-condensed table-bordered">
          <thead>
            <tr>
              <th>{{ID}}</th>
              <th>{{Type}}</th>
              <th>{{ID du lien}}</th>
              <th>{{Lien}}</th>
              <th>{{Action}}</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $tr = '';
            foreach (($planHeader->getPlan()) as $plan) {
              $tr .= '<tr  class="plan" data-id="'.$plan->getId().'">';
              $tr .= '<td>';
              $tr .= $plan->getId();
              $tr .= '</td>';
              $tr .= '<td>';
              $tr .= $plan->getLink_type();
              $tr .= '</td>';
              $tr .= '<td>';
              $tr .= $plan->getLink_id();
              $tr .= '</td>';
              $tr .= '<td>';
              if (in_array($plan->getLink_type(),array('text','summary','graph','plan','view','zone'))) {
                $tr .= '<span class="label label-default">N/A</span>';
              } else {
                $link = $plan-> getLink();
                if(is_object($link)){
                  $tr .= $link->getHumanName();
                }else{
                  $tr .= '<span class="label label-danger">{{Lien mort ou absent}}</span>';
                }
              }
              $tr .= '</td>';
              $tr .= '<td>';
              $tr .= '<a class="btn btn-danger btn-xs bt_removePlanComposant pull-right"><i class="fas fa-trash"></i> {{Supprimer}}</a> ';
              if (is_object($link)) {
                $tr .= '<a class="btn btn-default btn-xs bt_configurePlanComposant pull-right"><i class="fas fa-cog"></i> {{Configuration}}</a>';
              }
              $tr .= '</td>';
              $tr .= '</tr>';
            }
            echo $tr;
            ?>
          </tbody>
        </table>
      </fieldset>
    </form>
  </div>
</div>

<script>
$('.bt_removePlanComposant').off('click').on('click', function() {
  var tr = $(this).closest('tr');
  jeedom.plan.remove({
    id : tr.attr('data-id'),
    error: function(error) {
      $('#div_alertPlanHeaderConfigure').showAlert({message: error.message, level: 'danger'})
    },
    success: function() {
      $('#div_alertPlanHeaderConfigure').showAlert({message: '{{Composant supprimée}}', level: 'success'})
      tr.remove()
    }
  })
})

$('.bt_configurePlanComposant').off('click').on('click', function() {
  var tr = $(this).closest('tr')
  $('#md_modal2').dialog({title: "{{Configuration du composant}}"}).load('index.php?v=d&modal=plan.configure&id='+tr.attr('data-id')).dialog('open')
})

$('.planHeaderAttr[data-l1key=configuration][data-l2key=icon]').on('dblclick', function() {
  $('.planHeaderAttr[data-l1key=configuration][data-l2key=icon]').value('')
})

$('#bt_chooseIcon').on('click', function() {
  jeedomUtils.chooseIcon(function(_icon) {
    $('.planHeaderAttr[data-l1key=configuration][data-l2key=icon]').empty().append(_icon)
  })
})

$('#bt_uploadImage').fileupload({
  replaceFileInput: false,
  url: 'core/ajax/plan.ajax.php?action=uploadImage&id=' + planHeader_id,
  dataType: 'json',
  done: function(e, data) {
    if (data.result.state != 'ok') {
      $('#div_alertPlanHeaderConfigure').showAlert({message: data.result.result, level: 'danger'})
      return
    }
    jeedomUtils.loadPage('index.php?v=d&p=plan&plan_id='+planHeader_id)
  }
})

$('#bt_removeBackgroundImage').on('click', function() {
  jeedom.plan.removeImageHeader({
    planHeader_id: planHeader_id,
    error: function(error) {
      $('#div_alertPlanHeaderConfigure').showAlert({message: error.message, level: 'danger'});
    },
    success: function() {
      $('#div_alertPlanHeaderConfigure').showAlert({message: '{{Image supprimée}}', level: 'success'});
    },
  });
});

$('#bt_saveConfigurePlanHeader').on('click', function() {
  jeedom.plan.saveHeader({
    planHeader: $('#div_planHeaderConfigure').getValues('.planHeaderAttr')[0],
    error: function(error) {
      $('#div_alertPlanHeaderConfigure').showAlert({message: error.message, level: 'danger'})
    },
    success: function() {
      $('#div_alertPlanHeaderConfigure').showAlert({message: '{{Design sauvegardé}}', level: 'success'})
      $('#div_pageContainer').data('editOption.state', false)
      jeedomUtils.loadPage('index.php?v=d&p=plan&plan_id='+planHeader_id)
    }
  })
})

if (isset(id) && id != '') {
  $('#div_planHeaderConfigure').setValues(planHeader, '.planHeaderAttr')
}
</script>
