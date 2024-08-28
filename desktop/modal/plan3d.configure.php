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
if (init('id') != '') {
  $plan3d = plan3d::byId(init('id'));
} else {
  $plan3d = plan3d::byName3dHeaderId(init('name'), init('plan3dHeader_id'));
}
if (!is_object($plan3d)) {
  $plan3d = new plan3d();
  $plan3d->setName(init('name'));
  $plan3d->setPlan3dHeader_id(init('plan3dHeader_id'));
  $plan3d->save();
}
sendVarToJS('jeephp2js.md_plan3dConfigure_Id', $plan3d->getId());
?>

<form class="form-horizontal">
  <div id="md_plan3dConfigure">
    <div class="input-group pull-right" style="display:inline-flex;">
      <span class="input-group-btn">
        <a class='btn btn-success btn-sm roundedLeft bt_saveConfigurePlan3d'><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a>
        <a class='btn btn-danger btn-sm roundedRight bt_removeConfigurePlan3d'><i class="fas fa-minus-circle"></i> {{Supprimer}}</a>
      </span>
    </div>
  <legend>{{Général}}</legend>
    <!-- ******************************* Général ************************************ -->
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
        <div class="input-group">
          <input type="text" class="plan3dAttr form-control roundedLeft" data-l1key="link_id">
          <span class="input-group-btn">
            <a class="btn btn-default bt_selEqLogic roundedRight" tooltip="{{Rechercher un équipement}}">
              <i class="fas fa-list-alt"></i>
            </a>
          </span>
        </div>
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
    <!---******************************** LIGHT ************************************* -->
    <div class="form-group specificity specificity_light">
      <label class="col-lg-4 control-label">{{Statut}}</label>
      <div class="col-lg-3">
        <div class="input-group">
          <input type="text" class="plan3dAttr form-control roundedLeft" data-l1key="configuration" data-l2key="cmd::state"/>
          <span class="input-group-btn">
            <a class="btn btn-default bt_selCmd roundedRight" tooltip="{{Rechercher une commande}}">
              <i class="fas fa-list-alt"></i>
            </a>
          </span>
        </div>
      </div>
    </div>
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
    <!---******************************** TEXT ************************************* -->
    <div class="form-group specificity specificity_text">
      <div class="form-group">
        <label class="col-lg-4 control-label">{{Texte}}</label>
        <div class="col-sm-3">
          <div class="input-group">
            <textarea type="text" class="plan3dAttr form-control roundedLeft" data-l1key="configuration" data-l2key="3d::widget::text::text" style="height: 32px;"></textarea>
            <!--<input type="text" class="plan3dAttr form-control roundedLeft" data-l1key="configuration" data-l2key="3d::widget::door::shutter">-->
              <span class="input-group-btn">
                <a class="btn btn-default bt_addTextCommand roundedRight" tooltip="{{Rechercher une commande}}">
                  <i class="fas fa-list-alt"></i>
                </a>
             </span>
           </div>
         </div>
      </div>
    </div>
    <div class="form-group specificity specificity_text">
      <label class="col-lg-4 control-label">{{Taille du texte}}</label>
      <div class="col-lg-1">
        <input type="number" class="plan3dAttr form-control" data-l1key="configuration" data-l2key="3d::widget::text::fontsize"/>
      </div>
    </div>
    <div class="form-group specificity specificity_text">
      <label class="col-lg-4 control-label">{{Espacement au dessus de l'objet}}</label>
      <div class="col-lg-1">
        <input type="number" class="plan3dAttr form-control" data-l1key="configuration" data-l2key="3d::widget::text::space::z"/>
      </div>
    </div>
    <div class="form-group specificity specificity_text">
      <label class="col-lg-4 control-label">{{Couleur du texte}}</label>
      <div class="col-lg-1">
        <input type="color" class="plan3dAttr form-control" data-l1key="configuration" data-l2key="3d::widget::text::textcolor"/>
      </div>
      <label class="col-lg-1 control-label">{{Transparence}}</label>
      <div class="col-lg-1">
        <input type="number" class="plan3dAttr form-control" data-l1key="configuration" data-l2key="3d::widget::text::texttransparency"/>
      </div>
    </div>
    <div class="form-group specificity specificity_text">
      <label class="col-lg-4 control-label">{{Couleur de fond}}</label>
      <div class="col-lg-1">
        <input type="color" class="plan3dAttr form-control" data-l1key="configuration" data-l2key="3d::widget::text::backgroundcolor"/>
      </div>
      <label class="col-lg-1 control-label">{{Transparence}}</label>
      <div class="col-lg-1">
        <input type="number" class="plan3dAttr form-control" data-l1key="configuration" data-l2key="3d::widget::text::backgroundtransparency"/>
      </div>
    </div>
    <div class="form-group specificity specificity_text">
      <label class="col-lg-4 control-label">{{Couleur de la bordure}}</label>
      <div class="col-lg-1">
        <input type="color" class="plan3dAttr form-control" data-l1key="configuration" data-l2key="3d::widget::text::bordercolor"/>
      </div>
      <label class="col-lg-1 control-label">{{Transparence}}</label>
      <div class="col-lg-1">
        <input type="number" class="plan3dAttr form-control" data-l1key="configuration" data-l2key="3d::widget::text::bordertransparency"/>
      </div>
    </div>
    <!---******************************** DOOR ************************************* -->
    <ul class="nav nav-tabs  specificity specificity_door" role="tablist">
      <li role="presentation" class="active" style="border-radius: var(--border-radius) 0 0 0 !important;"><a href="#tab_door_window" aria-controls="tab_door_window" role="tab" data-toggle="tab" style="border-radius: var(--border-radius) 0 0 0 !important;">{{Fênetre/Porte}}</a></li>
      <li role="presentation" style="border-radius: 0 var(--border-radius) 0 0 !important;"><a href="#tab_door_shutter" aria-controls="tab_door_shutter" role="tab" data-toggle="tab" style="border-radius: 0 var(--border-radius) 0 0 !important;">{{Volet}}</a></li>
    </ul>
    <div class="tab-content  specificity specificity_door">
      <div role="tabpanel" class="tab-pane active" id="tab_door_window">
        <br/>
        <div class="form-group specificity specificity_door">
          <div class="form-group">
            <label class="col-lg-4 control-label">{{Etat}}</label>
            <div class="col-lg-3">
              <div class="input-group">
                <input type="text" class="plan3dAttr form-control roundedLeft" data-l1key="configuration" data-l2key="3d::widget::door::window">
                <span class="input-group-btn">
                  <a class="btn btn-default bt_selWindow roundedRight" tooltip="{{Rechercher une commande}}">
                    <i class="fas fa-list-alt"></i>
                  </a>
                </span>
              </div>
            </div>
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
          <label class="col-lg-1 control-label">{{Répeter}}</label>
          <div class="col-lg-2">
            <input type="number" min=0 class="plan3dAttr form-control translate" data-l1key="configuration" data-l2key="3d::widget::door::translate::repeat"/>
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
          <div class="col-lg-1">
            <input type="color" class="plan3dAttr form-control" data-l1key="configuration" data-l2key="3d::widget::door::windowopen"/>
          </div>
        </div>
        <div class="form-group specificity specificity_door">
          <label class="col-lg-4 control-label">{{Couleur fermée}}</label>
          <div class="col-lg-1">
            <input type="checkbox" class="plan3dAttr" data-l1key="configuration" data-l2key="3d::widget::door::windowclose::enableColor"/>
          </div>
          <div class="col-lg-1">
            <input type="color" class="plan3dAttr form-control" data-l1key="configuration" data-l2key="3d::widget::door::windowclose"/>
          </div>
        </div>       
      </div>
      <div role="tabpanel" class="tab-pane" id="tab_door_shutter">
        <br/>
        <div class="form-group specificity specificity_door">
          <div class="form-group">
            <label class="col-lg-4 control-label">{{Etat}}</label>
              <div class="col-lg-3">
                <div class="input-group">
                  <input type="text" class="plan3dAttr form-control roundedLeft" data-l1key="configuration" data-l2key="3d::widget::door::shutter">
                  <span class="input-group-btn">
                    <a class="btn btn-default bt_selShutter roundedRight" tooltip="{{Rechercher une commande}}">
                      <i class="fas fa-list-alt"></i>
                    </a>
                 </span>
               </div>
             </div>
          </div>
        </div>
        <legend>{{Masquer quand le volet est ouvert}}</legend>
        <div class="form-group specificity specificity_door">
          <label class="col-lg-4 control-label">{{Activer}}</label>
          <div class="col-lg-1">
            <input type="checkbox" class="plan3dAttr" data-l1key="configuration" data-l2key="3d::widget::shutter::hide"/>
          </div>
        </div>
        <legend>{{Couleur}}</legend>
        <div class="form-group specificity specificity_door">
          <label class="col-lg-4 control-label">{{Activer}}</label>
          <div class="col-lg-1">
            <input type="checkbox" class="plan3dAttr" data-l1key="configuration" data-l2key="3d::widget::door::shutterclose::enableColor"/>
          </div>
          <label class="col-lg-1 control-label">{{Couleur fermé}}</label>
          <div class="col-lg-1">
            <input type="color" class="plan3dAttr form-control" data-l1key="configuration" data-l2key="3d::widget::door::shutterclose"/>
          </div>
        </div>
      </div>
    </div>
    <!---******************************** conditionalColor ************************************* -->
    <div class="specificity specificity_conditionalColor">
      <legend>{{Condition}} <a class="btn btn-xs btn-success pull-right" id="bt_addCondition"><i class="fas fa-plus"></i> {{Ajouter}}</a></legend>
      <div id="div_conditionColor"></div>
    </div>
    <!---******************************** conditionalShow ************************************* -->
    <div class="specificity specificity_conditionalShow">
      <legend>{{Condition}} <a class="btn btn-xs btn-success pull-right" id="bt_addConditionShow"><i class="fas fa-plus"></i> {{Ajouter}}</a></legend>
      <div id="div_conditionShow"></div>
    </div>
  </div>
</form>

<script>
if (!jeeFrontEnd.md_plan3dConfigure) {
  jeeFrontEnd.md_plan3dConfigure = {
    plan3d_configure_plan3d: null,
    init: function(_cmdIds) {
      if (isset(jeephp2js.md_plan3dConfigure_Id) && jeephp2js.md_plan3dConfigure_Id != '') {
        jeedom.plan3d.byId({
          id: jeephp2js.md_plan3dConfigure_Id,
          async: false,
          error: function(error) {
            jeedomUtils.showAlert({
              attachTo: jeeDialog.get('#md_plan3dConfigure', 'dialog'),
              message: error.message,
              level: 'danger'
            })
          },
          success: function(plan3d) {
            document.getElementById('md_plan3dConfigure').setJeeValues(plan3d, '.plan3dAttr')
            if (isset(plan3d.configuration) && isset(plan3d.configuration['3d::widget::conditionalColor::condition'])) {
              for (var i in plan3d.configuration['3d::widget::conditionalColor::condition']) {
                jeeFrontEnd.md_plan3dConfigure.addConditionalColor(plan3d.configuration['3d::widget::conditionalColor::condition'][i])
              }
            }
            if (isset(plan3d.configuration) && isset(plan3d.configuration['3d::widget::conditionalShow::condition'])) {
              for (var i in plan3d.configuration['3d::widget::conditionalShow::condition']) {
                jeeFrontEnd.md_plan3dConfigure.addConditionalShow(plan3d.configuration['3d::widget::conditionalShow::condition'][i])
              }
            }
          }
        })
      }
    },
    postInit: function() {
      this.setConditionColorSortable()
      this.setConditionShowSortable()
      this.setPlan3dUI_Events()
    },
    setPlan3dUI_Events: function() {
      document.getElementById('md_plan3dConfigure').addEventListener('change', function(event) {
        var _target = null
        if (_target = event.target.closest('.plan3dAttr[data-l1key=configuration][data-l2key="3d::widget"]')) {
          document.querySelectorAll('.specificity').unseen()
          document.querySelectorAll('.specificity.specificity_' + event.target.jeeValue()).seen()
        }
        if (_target = event.target.closest('.plan3dAttr[data-l1key=configuration][data-l2key="3d::widget::door::rotate"]')) {
          document.querySelectorAll('.specificity.specificity_door .rotate').forEach(_select => {
            _select.removeAttribute('disabled')
          })
          if (_target.jeeValue() != 1) {
            document.querySelectorAll('.specificity.specificity_door .rotate').forEach(_select => {
              _select.setAttribute('disabled', true)
            })
          }
        }
        if (_target = event.target.closest('.plan3dAttr[data-l1key=configuration][data-l2key="3d::widget::door::translate"]')) {
          document.querySelectorAll('.specificity.specificity_door .translate').forEach(_select => {
            _select.removeAttribute('disabled')
          })
          if (_target.jeeValue() != 1) {
            document.querySelectorAll('.specificity.specificity_door .translate').forEach(_select => {
              _select.setAttribute('disabled', true)
            })
          }
        }
      })
      document.querySelector('.plan3dAttr[data-l1key=configuration][data-l2key="3d::widget"]').triggerEvent('change')
      document.querySelector('.plan3dAttr[data-l1key=configuration][data-l2key="3d::widget::door::rotate"]').triggerEvent('change')
      document.querySelector('.plan3dAttr[data-l1key=configuration][data-l2key="3d::widget::door::translate"]').triggerEvent('change')
    },
    setConditionColorSortable: function() {
      Sortable.create(document.getElementById('div_conditionColor'), {
        delay: 100,
        delayOnTouchOnly: true,
        draggable: '.conditionalColor',
        filter: 'a, input, textarea',
        preventOnFilter: false,
        direction: 'vertical',
        removeCloneOnHide: true,
        chosenClass: 'dragSelected',
      })
    },
    setConditionShowSortable: function() {
      Sortable.create(document.getElementById('div_conditionShow'), {
        delay: 100,
        delayOnTouchOnly: true,
        draggable: '.conditionalShow',
        filter: 'a, input, textarea',
        preventOnFilter: false,
        direction: 'vertical',
        removeCloneOnHide: true,
        chosenClass: 'dragSelected',
      })
    },
    addConditionalShow: function(_conditionalShow) {
      if (!isset(_conditionalShow)) {
        _conditionalShow = {};
      }
      var div = '<div class="conditionalShow">';
      div += '<div class="form-group">';
      div += '<label class="col-sm-1 control-label">{{Masquer si}}</label>';
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
      let newDiv = document.createElement('div')
      newDiv.html(div)
      newDiv.setJeeValues(_conditionalShow, '.conditionalShowAttr')
      document.querySelector('#div_conditionShow').appendChild(newDiv)
      newDiv.replaceWith(...newDiv.childNodes)
    },
    addConditionalColor: function(_conditionalColor) {
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
      let newDiv = document.createElement('div')
      newDiv.html(div)
      newDiv.setJeeValues(_conditionalColor, '.conditionalColorAttr')
      document.querySelector('#div_conditionColor').appendChild(newDiv)
      newDiv.replaceWith(...newDiv.childNodes)
    },
    saveConfigurePlan3d: function() {
      var plan3ds = document.getElementById('md_plan3dConfigure').getJeeValues('.plan3dAttr')
      if (!isset(plan3ds[0].configuration)) {
        plan3ds[0].configuration = {}
      }
      plan3ds[0].configuration['3d::widget::conditionalColor::condition'] = document.querySelectorAll('#div_conditionColor .conditionalColor').getJeeValues('.conditionalColorAttr')
      plan3ds[0].configuration['3d::widget::conditionalShow::condition'] = document.querySelectorAll('#div_conditionShow .conditionalShow').getJeeValues('.conditionalShowAttr')
      jeedom.plan3d.save({
        plan3ds: plan3ds,
        error: function(error) {
          jeedomUtils.showAlert({
            attachTo: jeeDialog.get('#md_plan3dConfigure', 'dialog'),
            message: error.message,
            level: 'danger'
          })
        },
        success: function() {
          jeedomUtils.showAlert({
            attachTo: jeeDialog.get('#md_plan3dConfigure', 'dialog'),
            message: '{{Objet sauvegardé}}',
            level: 'success'
          })
        }
      })
    },
    removeConfigurePlan3d: function() {
      var plan3ds = document.getElementById('md_plan3dConfigure').getJeeValues('.plan3dAttr')
      if (!isset(plan3ds[0].configuration)) {
        plan3ds[0].configuration = {}
      }
      jeedom.plan3d.remove({
        id: plan3ds[0].id,
        error: function(error) {
          jeedomUtils.showAlert({
            attachTo: jeeDialog.get('#md_plan3dConfigure', 'dialog'),
            message: error.message,
            level: 'danger'
          })
        },
        success: function() {
          //document.getElementById('jeeDialogBackdrop').triggerEvent('click')
          jeedomUtils.showAlert({
            message: "{{Configuration de l'objet supprimée}}",
            level: 'success'
          })
        }
      })
    }
  }
}


(function() { // Self Isolation!
  var jeeM = jeeFrontEnd.md_plan3dConfigure
  jeeM.init()

  /*Events delegations
  */
  document.getElementById('md_plan3dConfigure').addEventListener('click', function(event) {
    var _target = null
    if (_target = event.target.closest('.bt_selEqLogic')) {
      jeedom.eqLogic.getSelectModal({}, function(result) {
        document.querySelector('.plan3dAttr[data-l1key="link_id"]').jeeValue(result.human)
      })
    }

    if (_target = event.target.closest('.bt_selCmd')) {
      jeedom.cmd.getSelectModal({cmd:{type:'info'}}, function(result) {
        document.querySelector('.plan3dAttr[data-l1key="configuration"][data-l2key="cmd::state"]').jeeValue(result.human)
      })
    }

    if (_target = event.target.closest('.bt_saveConfigurePlan3d')) {
      jeeFrontEnd.md_plan3dConfigure.saveConfigurePlan3d()
      return
    }

    if (_target = event.target.closest('.bt_removeConfigurePlan3d')) {
      jeeDialog.confirm('{{Êtes-vous sûr de vouloir supprimer cette configutation ?}}', function(result) {
        if (result) {
          jeeFrontEnd.md_plan3dConfigure.removeConfigurePlan3d()
        }
      })
      return
    }

    // Window / Door / Shutter
    if (_target = event.target.closest('.bt_selWindow')) {
      jeedom.cmd.getSelectModal({cmd:{type:'info'}}, function(result) {
        document.querySelector('.plan3dAttr[data-l1key="configuration"][data-l2key="3d::widget::door::window"]').jeeValue(result.human)
      })
    }

    if (_target = event.target.closest('.bt_selShutter')) {
      jeedom.cmd.getSelectModal({cmd:{type:'info'}}, function(result) {
        document.querySelector('.plan3dAttr[data-l1key="configuration"][data-l2key="3d::widget::door::shutter"]').jeeValue(result.human)
      })
    }

    // Text
    if (_target = event.target.closest('.bt_addTextCommand')) {
      jeedom.cmd.getSelectModal({cmd:{type:'info'}}, function(result) {
        document.querySelector('.plan3dAttr[data-l1key="configuration"][data-l2key="3d::widget::text::text"]').jeeValue(result.human)
      })
    }

    // conditionalColor
    if (_target = event.target.closest('#bt_addCondition')) {
      jeeFrontEnd.md_plan3dConfigure.addConditionalColor({})
      return
    }

    if (_target = event.target.closest('.bt_removeConditionalColor')) {
      _target.closest('.conditionalColor').remove()
      return
    }

    if (_target = event.target.closest('.listCmdInfoConditionalColor')) {
      jeedom.cmd.getSelectModal({cmd: {type: 'info'}}, function(result) {
        _target.closest('.conditionalColor').querySelector('.conditionalColorAttr[data-l1key="cmd"]').insertAtCursor(result.human)
      })
      return
    }

    // conditionalShow
    if (_target = event.target.closest('#bt_addConditionShow')) {
      jeeFrontEnd.md_plan3dConfigure.addConditionalShow({})
      return
    }

    if (_target = event.target.closest('.bt_removeConditionalShow')) {
      _target.closest('.conditionalShow').remove()
      return
    }

    if (_target = event.target.closest('.listCmdInfoConditionalShow')) {
      jeedom.cmd.getSelectModal({cmd: {type: 'info'}}, function(result) {
        _target.closest('.conditionalShow').querySelector('.conditionalShowAttr[data-l1key="cmd"]').insertAtCursor(result.human)
      })
      return
    }
  })

  jeeM.postInit()

})()
</script>
