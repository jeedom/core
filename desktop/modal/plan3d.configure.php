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
if(init('id') != ''){
  $plan3d = plan3d::byId(init('id'));
}else{
  $plan3d = plan3d::byName3dHeaderId(init('name'), init('plan3dHeader_id'));
}
if (!is_object($plan3d)) {
  $plan3d = new plan3d();
  $plan3d->setName(init('name'));
  $plan3d->setPlan3dHeader_id(init('plan3dHeader_id'));
  $plan3d->save();
}
$link = $plan3d->getLink();
sendVarToJS('id', $plan3d->getId());
?>

<div id="div_alertPlan3dConfigure"></div>
<form class="form-horizontal">
  <fieldset id="fd_plan3dConfigure">
    <legend>{{Général}}
      <a class='btn btn-success btn-xs pull-right cursor' style="color: white;" id='bt_saveConfigurePlan3d'><i class="fas fa-check"></i> {{Sauvegarder}}</a>
      <a class='btn btn-danger btn-xs pull-right cursor' style="color: white;" id='bt_removeConfigurePlan3d'><i class="fas fa-times"></i> {{Supprimer}}</a>
    </legend>
    <input type="text"  class="plan3dAttr form-control" data-l1key="id" style="display: none;"/>
    <div class="form-group">
      <label class="col-lg-4 control-label">{{Nom}}</label>
      <div class="col-lg-3">
        <input type="text" class="plan3dAttr form-control" data-l1key="name" disabled="disabled" />
      </div>
    </div>
    <div class="form-group">
      <label class="col-lg-4 control-label">{{Type de lien}}</label>
      <div class="col-lg-3">
        <select class="plan3dAttr form-control" data-l1key="link_type" >
          <option value="eqLogic">{{Equipement}}</option>
        </select>
      </div>
    </div>
    <div class="form-group">
      <label class="col-lg-4 control-label">{{Lien}}</label>
      <div class="col-lg-3">
        <input type="text" class="plan3dAttr form-control" data-l1key="link_id" />
      </div>
      <div class="col-lg-2">
        <a class="btn btn-default" id="bt_selEqLogic" title="{{Rechercher d\'un équipement}}"><i class="fas fa-list-alt"></i></a>
      </div>
    </div>
    <div class="form-group">
      <label class="col-lg-4 control-label">{{Spécificité}}</label>
      <div class="col-lg-3">
        <select class="plan3dAttr form-control" data-l1key="configuration" data-l2key="3d::widget">
          <option value="">{{Aucun}}</option>
          <option value="light">{{Lumière}}</option>
          <option value="text">{{Texte}}</option>
          <option value="door">{{Porte/Fenêtre}}</option>
          <option value="conditionalColor">{{Couleur conditionnel}}</option>
          <option value="conditionalShow">{{Affichage conditionnel}}</option>
        </select>
      </div>
    </div>
    <div class="form-group specificity specificity_light">
      <label class="col-lg-4 control-label">{{Statut}}</label>
      <div class="col-lg-3">
        <input type="text" class="plan3dAttr form-control" data-l1key="configuration" data-l2key="cmd::state"/>
      </div>
      <div class="col-lg-2">
        <a class="btn btn-default" id="bt_selCmd" title="{{Rechercher d\'une commande}}"><i class="fas fa-list-alt"></i></a>
      </div>
    </div>
    <!---*********************************LIGHT************************************** -->
    <div class="form-group specificity specificity_light">
      <label class="col-lg-4 control-label">{{Puissance}}</label>
      <div class="col-lg-3">
        <select class="plan3dAttr form-control" data-l1key="configuration" data-l2key="3d::widget::light::power">
          <option value="0.5">20 lm (4W)</option>
          <option value="3">180 lm (25W)</option>
          <option value="6" selected>400 lm (40W)</option>
          <option value="10">800 lm (60W)</option>
          <option value="15">1700 lm (100W)</option>
          <option value="45">3500 lm (300W)</option>
          <option value="150">110000 lm (1000W)</option>
        </select>
      </div>
    </div>

    <!---*********************************TEXT************************************** -->
    <div class="form-group specificity specificity_text">
      <label class="col-lg-4 control-label">{{Texte}}</label>
      <div class="col-lg-7">
        <textarea type="text" class="plan3dAttr form-control" data-l1key="configuration" data-l2key="3d::widget::text::text"></textarea>
      </div>
      <div class="col-lg-1">
        <a class="btn btn-default" id="bt_addTextCommand" title="{{Rechercher d\'une commande}}"><i class="fas fa-list-alt"></i></a>
      </div>
    </div>
    <div class="form-group specificity specificity_text">
      <label class="col-lg-4 control-label">{{Taille du texte}}</label>
      <div class="col-lg-3">
        <input type="number" class="plan3dAttr form-control" data-l1key="configuration" data-l2key="3d::widget::text::fontsize"/>
      </div>
    </div>
    <div class="form-group specificity specificity_text">
      <label class="col-lg-4 control-label">{{Couleur du texte}}</label>
      <div class="col-lg-3">
        <input type="color" class="plan3dAttr form-control" data-l1key="configuration" data-l2key="3d::widget::text::textcolor"/>
      </div>
      <label class="col-lg-2 control-label">{{Transparence du texte}}</label>
      <div class="col-lg-1">
        <input type="number" class="plan3dAttr form-control" data-l1key="configuration" data-l2key="3d::widget::text::texttransparency"/>
      </div>
    </div>
    <div class="form-group specificity specificity_text">
      <label class="col-lg-4 control-label">{{Couleur de fond}}</label>
      <div class="col-lg-3">
        <input type="color" class="plan3dAttr form-control" data-l1key="configuration" data-l2key="3d::widget::text::backgroundcolor"/>
      </div>
      <label class="col-lg-2 control-label">{{Transparence fond}}</label>
      <div class="col-lg-1">
        <input type="number" class="plan3dAttr form-control" data-l1key="configuration" data-l2key="3d::widget::text::backgroundtransparency"/>
      </div>
    </div>
    <div class="form-group specificity specificity_text">
      <label class="col-lg-4 control-label">{{Couleur de la bordure}}</label>
      <div class="col-lg-3">
        <input type="color" class="plan3dAttr form-control" data-l1key="configuration" data-l2key="3d::widget::text::bordercolor"/>
      </div>
      <label class="col-lg-2 control-label">{{Transparence bordure}}</label>
      <div class="col-lg-1">
        <input type="number" class="plan3dAttr form-control" data-l1key="configuration" data-l2key="3d::widget::text::bordertransparency"/>
      </div>
    </div>
    <div class="form-group specificity specificity_text">
      <label class="col-lg-4 control-label">{{Espacement au dessus de l'objet}}</label>
      <div class="col-lg-3">
        <input type="number" class="plan3dAttr form-control" data-l1key="configuration" data-l2key="3d::widget::text::space::z"/>
      </div>
    </div>

    <!---*********************************DOOR************************************** -->
    <ul class="nav nav-tabs  specificity specificity_door" role="tablist">
      <li role="presentation" class="active"><a href="#tab_door_window" aria-controls="tab_door_window" role="tab" data-toggle="tab">{{Fênetre/Porte}}</a></li>
      <li role="presentation"><a href="#tab_door_shutter" aria-controls="tab_door_shutter" role="tab" data-toggle="tab">{{Volet}}</a></li>
    </ul>

    <div class="tab-content  specificity specificity_door">
      <div role="tabpanel" class="tab-pane active" id="tab_door_window">
        <br/>
        <div class="form-group specificity specificity_door">
          <label class="col-lg-4 control-label">{{Etat}}</label>
          <div class="col-lg-3">
            <input type="text" class="plan3dAttr form-control" data-l1key="configuration" data-l2key="3d::widget::door::window"/>
          </div>
          <div class="col-lg-2">
            <a class="btn btn-default" id="bt_selWindow" title="{{Rechercher d\'une commande}}"><i class="fas fa-list-alt"></i></a>
          </div>
        </div>
        <legend>{{Rotation}}</legend>
        <div class="form-group specificity specificity_door">
          <label class="col-lg-4 control-label">{{Activer}}</label>
          <div class="col-lg-3">
            <input type="checkbox" class="plan3dAttr" data-l1key="configuration" data-l2key="3d::widget::door::rotate"/>
          </div>
        </div>
        <div class="form-group specificity specificity_door">
          <label class="col-lg-4 control-label">{{Ouverture}}</label>
          <div class="col-lg-2">
            <select class="plan3dAttr form-control rotate" data-l1key="configuration" data-l2key="3d::widget::door::rotate::0" >
              <option value="left">{{Gauche}}</option>
              <option value="right">{{Droite}}</option>
            </select>
          </div>
          <div class="col-lg-2">
            <select class="plan3dAttr form-control rotate" data-l1key="configuration" data-l2key="3d::widget::door::rotate::1" >
              <option value="front">{{Devant}}</option>
              <option value="behind">{{Derriere}}</option>
            </select>
          </div>
          <div class="col-lg-2">
            <select class="plan3dAttr form-control rotate" data-l1key="configuration" data-l2key="3d::widget::door::rotate::way" >
              <option value="1">{{Normal}}</option>
              <option value="3">{{Inversé}}</option>
            </select>
          </div>
        </div>

        <legend>{{Translation}}</legend>
        <div class="form-group specificity specificity_door">
          <label class="col-lg-4 control-label">{{Activer}}</label>
          <div class="col-lg-3">
            <input type="checkbox" class="plan3dAttr" data-l1key="configuration" data-l2key="3d::widget::door::translate"/>
          </div>
        </div>
        <div class="form-group specificity specificity_door">
          <label class="col-lg-4 control-label">{{Sens}}</label>
          <div class="col-lg-2">
            <select class="plan3dAttr form-control translate" data-l1key="configuration" data-l2key="3d::widget::door::translate::way" >
              <option value="left">{{Gauche}}</option>
              <option value="right">{{Droite}}</option>
              <option value="up">{{Haut}}</option>
              <option value="down">{{Bas}}</option>
            </select>
          </div>
          <label class="col-lg-3 control-label">{{Répeter}}</label>
          <div class="col-lg-2">
            <input type="text" class="plan3dAttr form-control translate" data-l1key="configuration" data-l2key="3d::widget::door::translate::repeat"/>
          </div>
        </div>

        <legend>{{Masquer quand la porte/fenêtre est ouverte}}</legend>
        <div class="form-group specificity specificity_door">
          <label class="col-lg-4 control-label">{{Activer}}</label>
          <div class="col-lg-3">
            <input type="checkbox" class="plan3dAttr" data-l1key="configuration" data-l2key="3d::widget::door::hide"/>
          </div>
        </div>

        <legend>{{Couleur}}</legend>
        <div class="form-group specificity specificity_door">
          <label class="col-lg-4 control-label">{{Couleur ouverte}}</label>
          <div class="col-lg-1">
            <input type="checkbox" class="plan3dAttr" data-l1key="configuration" data-l2key="3d::widget::door::windowopen::enableColor"/>
          </div>
          <div class="col-lg-3">
            <input type="color" class="plan3dAttr form-control" data-l1key="configuration" data-l2key="3d::widget::door::windowopen"/>
          </div>
        </div>
        <div class="form-group specificity specificity_door">
          <label class="col-lg-4 control-label">{{Couleur fermée}}</label>
          <div class="col-lg-1">
            <input type="checkbox" class="plan3dAttr" data-l1key="configuration" data-l2key="3d::widget::door::windowclose::enableColor"/>
          </div>
          <div class="col-lg-3">
            <input type="color" class="plan3dAttr form-control" data-l1key="configuration" data-l2key="3d::widget::door::windowclose"/>
          </div>
        </div>
      </div>
      <div role="tabpanel" class="tab-pane" id="tab_door_shutter">
        <br/>
        <div class="form-group specificity specificity_door">
          <label class="col-lg-4 control-label">{{Etat}}</label>
          <div class="col-lg-3">
            <input type="text" class="plan3dAttr form-control" data-l1key="configuration" data-l2key="3d::widget::door::shutter"/>
          </div>
          <div class="col-lg-2">
            <a class="btn btn-default" id="bt_selShutter" title="{{Rechercher d\'une commande}}"><i class="fas fa-list-alt"></i></a>
          </div>
        </div>
        <legend>{{Masquer quand le volet est ouvert}}</legend>
        <div class="form-group specificity specificity_door">
          <label class="col-lg-4 control-label">{{Activer}}</label>
          <div class="col-lg-3">
            <input type="checkbox" class="plan3dAttr" data-l1key="configuration" data-l2key="3d::widget::shutter::hide"/>
          </div>
        </div>
        <legend>{{Couleur}}</legend>
        <div class="form-group specificity specificity_door">
          <label class="col-lg-4 control-label">{{Couleur fermé}}</label>
          <div class="col-lg-1">
            <input type="checkbox" class="plan3dAttr" data-l1key="configuration" data-l2key="3d::widget::door::shutterclose::enableColor"/>
          </div>
          <div class="col-lg-3">
            <input type="color" class="plan3dAttr form-control" data-l1key="configuration" data-l2key="3d::widget::door::shutterclose"/>
          </div>
        </div>
      </div>
    </div>

    <script>
    $('.plan3dAttr[data-l1key=configuration][data-l2key="3d::widget::door::rotate"]').on('change', function() {
      $('.specificity.specificity_door .rotate').attr('disabled',false);
      if($(this).value() != 1){
        $('.specificity.specificity_door .rotate').attr('disabled','disabled');
      }
    });
    $('.plan3dAttr[data-l1key=configuration][data-l2key="3d::widget::door::translate"]').on('change', function() {
      $('.specificity.specificity_door .translate').attr('disabled',false);
      if($(this).value() != 1){
        $('.specificity.specificity_door .translate').attr('disabled','disabled');
      }
    });
    </script>

    <!---*********************************conditionalColor************************************** -->
    <div class="specificity specificity_conditionalColor">
      <legend>{{Condition}} <a class="btn btn-xs btn-success pull-right" id="bt_addCondition"><i class="fas fa-plus"></i> {{Ajouter}}</a></legend>
      <div id="div_conditionColor"></div>
    </div>
    <script>
    $('#bt_addCondition').on('click', function() {
      addConditionalColor({})
    });

    $('#fd_plan3dConfigure').off('click','.bt_removeConditionalColor').on('click','.bt_removeConditionalColor',  function(event) {
      $(this).closest('.conditionalColor').remove();
    });

    $('#fd_plan3dConfigure').off('click','.listCmdInfoConditionalColor').on('click','.listCmdInfoConditionalColor',  function(event) {
      var el = $(this).closest('.conditionalColor').find('.conditionalColorAttr[data-l1key=cmd]');
      jeedom.cmd.getSelectModal({cmd:{type:'info'}}, function(result) {
        el.atCaret('insert',result.human);
      });
    });

    function addConditionalColor(_conditionalColor) {
      if (!isset(_conditionalColor)) {
        _conditionalColor = {};
      }
      var div = '<div class="conditionalColor">';
      div += '<div class="form-group">';
      div += '<label class="col-sm-1 control-label">{{Condition}}</label>';
      div += '<div class="col-sm-9">';
      div += '<div class="input-group">';
      div += '<span class="input-group-btn">';
      div += '<a class="btn btn-default bt_removeConditionalColor btn-sm roundedLeft"><i class="fas fa-minus-circle"></i></a>';
      div += '</span>';
      div += '<input class="conditionalColorAttr form-control input-sm" data-l1key="cmd" />';
      div += '<span class="input-group-btn">';
      div += '<a class="btn btn-sm listCmdInfoConditionalColor btn-default roundedRight"><i class="fas fa-list-alt"></i></a>';
      div += '</span>';
      div += '</div>';
      div += '</div>';
      div += '<label class="col-sm-1 control-label">{{Couleur}}</label>';
      div += '<div class="col-sm-1">';
      div += '<input type="color" class="conditionalColorAttr form-control input-sm" data-l1key="color" />';
      div += '</div>';
      div += '</div>';
      $('#div_conditionColor').append(div);
      $('#div_conditionColor .conditionalColor').last().setValues(_conditionalColor, '.conditionalColorAttr');
    }

    $("#div_conditionColor").sortable({axis: "y", cursor: "move", items: ".conditionalColor", placeholder: "ui-state-highlight", tolerance: "intersect", forcePlaceholderSize: true});
    </script>


    <!---*********************************conditionalShow************************************** -->
    <div class="specificity specificity_conditionalShow">
      <legend>{{Condition}} <a class="btn btn-xs btn-success pull-right" id="bt_addConditionShow"><i class="fas fa-plus"></i> {{Ajouter}}</a></legend>
      <div id="div_conditionShow"></div>
    </div>
    <script>
    $('#bt_addConditionShow').on('click', function() {
      addConditionalShow({})
    });

    $('#fd_plan3dConfigure').off('click','.bt_removeConditionalShow').on('click','.bt_removeConditionalShow',  function(event) {
      $(this).closest('.conditionalShow').remove();
    });

    $('#fd_plan3dConfigure').off('click','.listCmdInfoConditionalShow').on('click','.listCmdInfoConditionalShow',  function(event) {
      var el = $(this).closest('.conditionalShow').find('.conditionalShowAttr[data-l1key=cmd]');
      jeedom.cmd.getSelectModal({cmd:{type:'info'}}, function(result) {
        el.atCaret('insert',result.human);
      });
    });

    function addConditionalShow(_conditionalShow) {
      if (!isset(_conditionalShow)) {
        _conditionalShow = {};
      }
      var div = '<div class="conditionalShow">';
      div += '<div class="form-group">';
      div += '<label class="col-sm-1 control-label">{{Masqué si}}</label>';
      div += '<div class="col-sm-9">';
      div += '<div class="input-group">';
      div += '<span class="input-group-btn">';
      div += '<a class="btn btn-default bt_removeConditionalShow btn-sm roundedLeft"><i class="fas fa-minus-circle"></i></a>';
      div += '</span>';
      div += '<input class="conditionalShowAttr form-control input-sm" data-l1key="cmd" />';
      div += '<span class="input-group-btn">';
      div += '<a class="btn btn-sm listCmdInfoConditionalShow btn-default roundedRight"><i class="fas fa-list-alt"></i></a>';
      div += '</span>';
      div += '</div>';
      div += '</div>';
      div += '</div>';
      $('#div_conditionShow').append(div);
      $('#div_conditionShow .conditionalShow').last().setValues(_conditionalShow, '.conditionalShowAttr');
    }

    $("#div_conditionColor").sortable({axis: "y", cursor: "move", items: ".conditionalColor", placeholder: "ui-state-highlight", tolerance: "intersect", forcePlaceholderSize: true});
    </script>
  </fieldset>
</form>

<script>
$('.plan3dAttr[data-l1key=configuration][data-l2key="3d::widget"]').on('change', function() {
  $('.specificity').hide()
  $('.specificity.specificity_'+$(this).value()).show()
})

$('.plan3dAttr[data-l1key=configuration][data-l2key="3d::widget"]').trigger('change')

$('#fd_plan3dConfigure').off('click','#bt_selEqLogic').on('click','#bt_selEqLogic',  function(event) {
  jeedom.eqLogic.getSelectModal({}, function(result) {
    $('.plan3dAttr[data-l1key=link_id]').value(result.human)
  })
})

$('#fd_plan3dConfigure').off('click','#bt_selCmd').on('click','#bt_selCmd',  function(event) {
  jeedom.cmd.getSelectModal({cmd:{type:'info'}}, function(result) {
    $('.plan3dAttr[data-l1key=configuration][data-l2key="cmd::state"]').value(result.human)
  })
})

$('#fd_plan3dConfigure').off('click','#bt_selWindow').on('click','#bt_selWindow',  function(event) {
  jeedom.cmd.getSelectModal({cmd:{type:'info'}}, function(result) {
    $('.plan3dAttr[data-l1key=configuration][data-l2key="3d::widget::door::window"]').value(result.human)
  })
})

$('#fd_plan3dConfigure').off('click','#bt_selShutter').on('click','#bt_selShutter',  function(event) {
  jeedom.cmd.getSelectModal({cmd:{type:'info'}}, function(result) {
    $('.plan3dAttr[data-l1key=configuration][data-l2key="3d::widget::door::shutter"]').value(result.human)
  })
})

$('#fd_plan3dConfigure').off('click','#bt_addTextCommand').on('click','#bt_addTextCommand',  function(event) {
  jeedom.cmd.getSelectModal({cmd:{type:'info'}}, function(result) {
    $('.plan3dAttr[data-l1key=configuration][data-l2key="3d::widget::text::text"]').atCaret('insert',result.human)
  })
})

$('#bt_saveConfigurePlan3d').on('click', function() {
  var plan3ds = $('#fd_plan3dConfigure').getValues('.plan3dAttr')
  if (!isset(plan3ds[0].configuration)) {
    plan3ds[0].configuration = {}
  }
  plan3ds[0].configuration['3d::widget::conditionalColor::condition'] = $('#div_conditionColor .conditionalColor').getValues('.conditionalColorAttr')
  plan3ds[0].configuration['3d::widget::conditionalShow::condition'] = $('#div_conditionShow .conditionalShow').getValues('.conditionalShowAttr')
  jeedom.plan3d.save({
    plan3ds: plan3ds,
    error: function(error) {
      $('#div_alertPlan3dConfigure').showAlert({message: error.message, level: 'danger'})
    },
    success: function() {
      $('#fd_plan3dConfigure').closest("div.ui-dialog-content").dialog("close")
      if (typeof refresh3dObject == 'function') {
        refresh3dObject()
      }
    }
  })
})

$('#bt_removeConfigurePlan3d').on('click', function() {
  var plan3ds = $('#fd_plan3dConfigure').getValues('.plan3dAttr')
  if (!isset(plan3ds[0].configuration)) {
    plan3ds[0].configuration = {}
  }
  jeedom.plan3d.remove({
    id: plan3ds[0].id,
    error: function(error) {
      $('#div_alertPlan3dConfigure').showAlert({message: error.message, level: 'danger'})
    },
    success: function() {
      $('#fd_plan3dConfigure').closest("div.ui-dialog-content").dialog("close")
      if (typeof refresh3dObject == 'function') {
        refresh3dObject()
      }
    }
  })
})

if (isset(id) && id != '') {
  $.ajax({
    type: "POST",
    url: "core/ajax/plan3d.ajax.php",
    data: {
      action: "get",
      id: id
    },
    dataType: 'json',
    error: function(request, status, error) {
      handleAjaxError(request, status, error, $('#div_alertPlan3dConfigure'))
    },
    success: function(data) {
      if (data.state != 'ok') {
        $('#div_alertPlan3dConfigure').showAlert({message: data.result, level: 'danger'})
        return
      }
      $('#fd_plan3dConfigure').setValues(data.result, '.plan3dAttr')
      if (isset(data.result.configuration) && isset(data.result.configuration['3d::widget::conditionalColor::condition'])) {
        for (var i in data.result.configuration['3d::widget::conditionalColor::condition']) {
          addConditionalColor(data.result.configuration['3d::widget::conditionalColor::condition'][i])
        }
      }
      if (isset(data.result.configuration) && isset(data.result.configuration['3d::widget::conditionalShow::condition'])) {
        for (var i in data.result.configuration['3d::widget::conditionalShow::condition']) {
          addConditionalShow(data.result.configuration['3d::widget::conditionalShow::condition'][i])
        }
      }
    }
  })
}
</script>