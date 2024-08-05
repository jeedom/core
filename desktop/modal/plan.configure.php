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
$plan = plan::byId(init('id'));
if (!is_object($plan)) {
  throw new Exception('{{Impossible de trouver le design}}');
}
$link = $plan->getLink();
sendVarToJS('jeephp2js.md_planConfigure_Id', $plan->getId());
?>

<div id="md_planConfigure" data-modalType="md_planConfigure">
  <form class="form-horizontal">
    <fieldset id="fd_planConfigure">
      <legend>{{Général}}
        <a class='btn btn-success btn-xs pull-right cursor' style="color: white;" id='bt_saveConfigurePlan'><i class="fas fa-check"></i> {{Sauvegarder}}</a>
      </legend>
      <input type="text" class="planAttr form-control" data-l1key="id" style="display: none;" />
      <input type="text" class="planAttr form-control" data-l1key="link_type" style="display: none;" />
      <div class="form-group link_type link_eqLogic link_cmd link_scenario link_graph link_text link_view link_plan link_image link_zone link_summary">
        <label class="col-lg-4 control-label">{{Profondeur}}</label>
        <div class="col-lg-2">
          <select class="form-control planAttr" data-l1key="css" data-l2key="z-index">
            <option value="999">{{Niveau 0}}</option>
            <option value="1000" selected>{{Niveau 1}}</option>
            <option value="1001">{{Niveau 2}}</option>
            <option value="1002">{{Niveau 3}}</option>
          </select>
        </div>
      </div>
      <div class="form-group link_type link_eqLogic link_cmd link_scenario link_graph link_text link_view link_plan link_image link_zone link_summary">
        <label class="col-lg-4 control-label">{{Position X}}<sub>px</sub></label>
        <div class="col-lg-2">
          <input type="text" class="planAttr form-control" data-l1key="position" data-l2key="left" placeholder="0" />
        </div>
        <label class="col-lg-2 control-label">{{Position Y}}<sub>px</sub></label>
        <div class="col-lg-2">
          <input type="text" class="planAttr form-control" data-l1key="position" data-l2key="top" placeholder="0" />
        </div>
      </div>
      <div class="form-group link_type link_eqLogic link_scenario link_graph link_text link_view link_plan link_image link_zone link_summary">
        <label class="col-lg-4 control-label">{{Largeur}}<sub>px</sub></label>
        <div class="col-lg-2">
          <input type="text" class="planAttr form-control" data-l1key="display" data-l2key="width" placeholder="100" />
        </div>
        <label class="col-lg-2 control-label">{{Hauteur}}<sub>px</sub></label>
        <div class="col-lg-2">
          <input type="text" class="planAttr form-control" data-l1key="display" data-l2key="height" placeholder="100" />
        </div>
      </div>
      <div class="form-group link_type link_eqLogic link_cmd link_scenario">
        <label class="col-lg-4 control-label">{{Taille du widget}}
          <sup><i class="fas fa-question-circle" title="{{Facteur de zoom. Ex : Réduire de moitié : 0.5, Doubler : 2}}"></i></sup>
        </label>
        <div class="col-lg-2">
          <input type="text" class="planAttr form-control" data-l1key="css" data-l2key="zoom" placeholder="1.2" />
          <sup class="danger"><i class="fas fa-exclamation-circle" title="{{Attention : cette option crée des problèmes de placement sur les bords du design et désactive la grille aimantée pour l'équipement en question}}"></i></sup>
        </div>
      </div>
      <legend>{{Spécifique}}</legend>
      <div class="form-group link_type link_image">
        <label class="col-lg-4 control-label">{{Afficher}}</label>
        <div class="col-lg-3">
          <select class="form-control planAttr" data-l1key="configuration" data-l2key="display_mode">
            <option value="image">{{Image}}</option>
            <option value="camera">{{Caméra}}</option>
          </select>
        </div>
      </div>
      <div class="form-group link_type link_image display_mode display_mode_image">
        <label class="col-lg-4 control-label">{{Image}}</label>
        <div class="col-lg-8">
          <span class="btn btn-default btn-file">
            <i class="fas fa-cloud-upload-alt"></i> {{Envoyer}}<input id="bt_uploadImagePlan" type="file" name="file" style="display: inline-block;">
          </span>
        </div>
      </div>
      <div class="form-group link_type link_image display_mode display_mode_image">
        <div class="col-lg-4"></div>
        <div class="col-lg-4 planImg">
          <img src="core/img/no_image.gif" width="240px" height="auto"/>
        </div>
      </div>
      <div class="form-group link_type link_image display_mode display_mode_camera" style="display:none;">
        <label class="col-lg-4 control-label">{{Caméra}}</label>
        <div class="col-lg-3">
          <div class="input-group">
            <input type="text" class="planAttr form-control roundedLeft" data-l1key="configuration" data-l2key="camera" />
            <span class="input-group-btn">
              <a class="btn btn-default roundedRight" id="bt_planConfigureSelectCamera"><i class="fas fa-list-alt"></i></a>
            </span>
          </div>
        </div>
      </div>
      <div class="form-group link_type link_image display_mode display_mode_camera" style="display:none;">
        <label class="col-lg-4 control-label">{{Autoriser la fenêtre de zoom}}</label>
        <div class="col-lg-2">
          <input type="checkbox" class="planAttr" data-l1key="display" data-l2key="allowZoom">
        </div>
      </div>
      <div class="form-group link_type link_graph">
        <label class="col-lg-4 control-label">{{Période}}</label>
        <div class="col-lg-2">
          <select class="planAttr form-control" data-l1key="display" data-l2key="dateRange">
            <option value="30 min">{{30min}}</option>
            <option value="1 hour">{{Heure}}</option>
            <option value="1 day">{{Jour}}</option>
            <option value="7 days" selected>{{Semaine}}</option>
            <option value="1 month">{{Mois}}</option>
            <option value="1 year">{{Année}}</option>
            <option value="all">{{Tous}}</option>
          </select>
        </div>
      </div>
      <div class="form-group link_type link_graph">
        <label class="col-lg-4 control-label">{{Echelles axes Y indépendantes}}</label>
        <div class="col-lg-2">
          <input type="checkbox" checked class="planAttr" data-l1key="display" data-l2key="yAxisScaling">
        </div>
      </div>
      <div class="form-group link_type link_graph">
        <label class="col-lg-4 control-label">{{Grouper axes Y par unité}}</label>
        <div class="col-lg-2">
          <input type="checkbox" checked class="planAttr" data-l1key="display" data-l2key="yAxisByUnit">
        </div>
      </div>
      <div class="form-group link_type link_graph">
        <label class="col-lg-4 control-label">{{Afficher la légende}}</label>
        <div class="col-lg-2">
          <input type="checkbox" checked class="planAttr" data-l1key="display" data-l2key="showLegend">
        </div>
      </div>
      <div class="form-group link_type link_graph">
        <label class="col-lg-4 control-label">{{Afficher le navigateur}}</label>
        <div class="col-lg-2">
          <input type="checkbox" checked class="planAttr" data-l1key="display" data-l2key="showNavigator">
        </div>
      </div>
      <div class="form-group link_type link_graph">
        <label class="col-lg-4 control-label">{{Afficher le sélecteur de période}}</label>
        <div class="col-lg-2">
          <input type="checkbox" class="planAttr" checked data-l1key="display" data-l2key="showTimeSelector">
        </div>
      </div>
      <div class="form-group link_type link_graph">
        <label class="col-lg-4 control-label">{{Afficher la barre de défilement}}</label>
        <div class="col-lg-2">
          <input type="checkbox" class="planAttr" checked data-l1key="display" data-l2key="showScrollbar">
        </div>
      </div>
      <div class="form-group link_type link_graph">
        <label class="col-lg-4 control-label">{{Fond transparent}}</label>
        <div class="col-lg-2">
          <input type="checkbox" class="planAttr" checked data-l1key="display" data-l2key="transparentBackground">
        </div>
      </div>
      <div class="form-group link_type link_plan link_view">
        <label class="col-lg-4 control-label">{{Nom}}</label>
        <div class="col-lg-2">
          <input class="planAttr form-control" data-l1key="display" data-l2key="name" />
        </div>
      </div>
      <div class="form-group link_type link_summary">
        <label class="col-lg-4 control-label">{{Lien}}</label>
        <div class="col-lg-2">
          <select class="form-control planAttr" data-l1key="link_id">
            <option value="-1">{{Aucun}}</option>
            <option value="0">{{Général}}</option>
            <?php
            foreach ((jeeObject::all()) as $object) {
              echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
            }
            ?>
          </select>
        </div>
      </div>
      <div class="form-group link_type link_view">
        <label class="col-lg-4 control-label">{{Lien}}</label>
        <div class="col-lg-2">
          <select class="form-control planAttr" data-l1key="link_id">
            <?php
            foreach ((view::all()) as $view) {
              echo '<option value="' . $view->getId() . '">' . $view->getName() . '</option>';
            }
            ?>
          </select>
        </div>
      </div>
      <div class="form-group link_type link_plan">
        <label class="col-lg-4 control-label">{{Lien}}</label>
        <div class="col-lg-2">
          <select class="form-control planAttr" data-l1key="link_id">
            <?php
            foreach ((planHeader::all()) as $planHeader) {
              echo '<option value="' . $planHeader->getId() . '">' . $planHeader->getName() . '</option>';
            }
            ?>
          </select>
        </div>
      </div>
      <div class="form-group link_type link_plan link_view link_text">
        <label class="col-lg-4 control-label">{{Icône}}</label>
        <div class="col-lg-2">
          <a class="btn btn-default btn-sm" id="bt_chooseIcon"><i class="fas fa-flag"></i> {{Choisir une icône}}</a>
        </div>
        <div class="col-lg-2">
          <div class="planAttr" data-l1key="display" data-l2key="icon"></div>
        </div>
      </div>
      <div class="form-group link_type link_eqLogic">
        <label class="col-lg-4 control-label">{{Afficher le nom de l'objet}}</label>
        <div class="col-lg-2">
          <input type="checkbox" class="planAttr" data-l1key="display" data-l2key="showObjectName" />
        </div>
      </div>
      <div class="form-group link_type link_eqLogic link_cmd">
        <label class="col-lg-4 control-label">{{Masquer le nom}}</label>
        <div class="col-lg-2">
          <input type="checkbox" class="planAttr" data-l1key="display" data-l2key="hideName" />
        </div>
      </div>
      <div id="deferredEvents" class="form-group link_type link_plan link_view link_text link_summary link_eqLogic link_cmd">
        <label class="col-lg-4 control-label">{{Couleur de fond}}</label>
        <div class="col-lg-2">
          <input type="color" class="planAttr form-control" data-l1key="css" data-l2key="background-color" />
        </div>
        <label class="col-lg-1 control-label">{{Transparent}}</label>
        <div class="col-lg-1">
          <input type="checkbox" class="planAttr" data-l1key="display" data-l2key="background-transparent" />
        </div>
        <label class="col-lg-1 control-label">{{Défaut}}</label>
        <div class="col-lg-1">
          <input type="checkbox" class="planAttr" data-l1key="display" data-l2key="background-defaut" checked />
        </div>
      </div>
      <div class="form-group link_type link_plan link_view link_text link_summary link_eqLogic link_cmd">
        <label class="col-lg-4 control-label">{{Couleur du texte}}</label>
        <div class="col-lg-2">
          <input type="color" class="planAttr form-control" data-l1key="css" data-l2key="color" />
        </div>
        <label class="col-lg-1 control-label">{{Défaut}}</label>
        <div class="col-lg-1">
          <input type="checkbox" class="planAttr" data-l1key="display" data-l2key="color-defaut" checked />
        </div>
      </div>
      <div class="form-group link_type link_plan link_view link_text link_summary link_eqLogic link_cmd">
        <label class="col-lg-4 control-label">{{Arrondis}} <sub>px</sub></label>
        <div class="col-lg-2">
          <input class="form-control planAttr" data-l1key="css" data-l2key="border-radius" placeholder="10px" />
        </div>
      </div>
      <div class="form-group link_type link_plan link_view link_text link_graph link_summary link_eqLogic link_cmd">
        <label class="col-lg-4 control-label">{{Bordure}} <sub>css</sub>
          <sup><i class="fas fa-question-circle tooltips" title="{{Code css. Ex: 1px solid black}}"></i></sup>
        </label>
        <div class="col-lg-2">
          <input class="form-control planAttr" data-l1key="css" data-l2key="border" placeholder="1px solid black" />
        </div>
      </div>
      <div class="form-group link_type link_plan link_view link_text link_summary link_eqLogic link_cmd">
        <label class="col-lg-4 control-label">{{Opacité}}
          <sup><i class="fas fa-question-circle tooltips" title="{{Valeur entre 0 et 1. Une couleur de fond doit être définie.}}"></i></sup>
        </label>
        <div class="col-lg-2">
          <input type="number" min="0" max="1" class="form-control planAttr" data-l1key="css" data-l2key="opacity" placeholder="0,75" />
        </div>
      </div>
      <div class="form-group link_type link_plan link_view link_text link_summary link_eqLogic link_cmd">
        <label class="col-lg-4 control-label">{{CSS personnalisé}} <sub>css</sub></label>
        <div class="col-lg-8">
          <input class="planAttr form-control" data-l1key="display" data-l2key="css" placeholder="font-weight: bold;" />
          <sup class="danger"><i class="fas fa-exclamation-circle" title="{{Attention : peut être source de problèmes.}}"></i></sup>
        </div>
      </div>
      <div class="form-group link_type link_plan link_view link_text link_summary link_eqLogic link_cmd">
        <label class="col-lg-4 control-label">{{Appliquer le css personnalisé sur}} <sub>selecteur css</sub></label>
        <div class="col-lg-2">
          <input class="planAttr form-control" data-l1key="display" data-l2key="cssApplyOn" placeholder="*" />
        </div>
      </div>
      <div class="link_eqLogic">
        <?php
        if ($plan->getLink_type() == 'eqLogic') {
          $eQs = '';
          $eQs .= '<legend>{{Commandes}}</legend>';
          $eQs .= '<table class="table  table-condensed">';
          $eQs .= '<thead>';
          $eQs .= '<tr>';
          $eQs .= '<th>';
          $eQs .= '{{Commande}}';
          $eQs .= '</th>';
          $eQs .= '<th>';
          $eQs .= '{{Masquer le nom}}';
          $eQs .= '</th>';
          $eQs .= '<th>';
          $eQs .= '{{Masquer}}';
          $eQs .= '</th>';
          $eQs .= '<th>';
          $eQs .= '{{Fond transparent}}';
          $eQs .= '</th>';
          $eQs .= '</tr>';
          $eQs .= '</thead>';
          $eQs .= '<tbody>';
          foreach (($link->getCmd()) as $cmd) {
            $eQs .= '<tr>';
            $eQs .= '<td>';
            $eQs .= $cmd->getHumanName();
            $eQs .= '</td>';
            $eQs .= '<td>';
            $eQs .= '<input type="checkbox" class="planAttr checkContext" data-l1key="display" data-l2key="cmdHideName" data-l3key="' . $cmd->getId() . '" />';
            $eQs .= '</td>';
            $eQs .= '<td>';
            $eQs .= '<input type="checkbox" class="planAttr checkContext" data-l1key="display" data-l2key="cmdHide" data-l3key="' . $cmd->getId() . '" />';
            $eQs .= '</td>';
            $eQs .= '<td>';
            $eQs .= '<input type="checkbox" class="planAttr checkContext" data-l1key="display" data-l2key="cmdTransparentBackground" data-l3key="' . $cmd->getId() . '" />';
            $eQs .= '</td>';
            $eQs .= '</tr>';
          }
          $eQs .= '</tbody>';
          $eQs .= '</table>';
          echo $eQs;
        }
        ?>
      </div>
      <div class="form-group link_type link_plan link_view link_text link_summary">
        <label class="col-lg-4 control-label">{{Taille de la police}} <sub>%</sub></label>
        <div class="col-lg-2">
          <input class="planAttr form-control" data-l1key="css" data-l2key="font-size" />
        </div>
      </div>
      <div class="form-group link_type link_plan link_view link_text">
        <label class="col-lg-4 control-label">{{Alignement du texte}}</label>
        <div class="col-lg-2">
          <select class="planAttr form-control" data-l1key="css" data-l2key="text-align">
            <option value="initial">{{Par defaut}}</option>
            <option value="left">{{Gauche}}</option>
            <option value="right">{{Droite}}</option>
            <option value="center">{{Centré}}</option>
          </select>
        </div>
      </div>
      <div class="form-group link_type link_plan link_view link_text link_summary">
        <label class="col-lg-4 control-label">{{Gras}}</label>
        <div class="col-lg-2">
          <select class="planAttr form-control" data-l1key="css" data-l2key="font-weight">
            <option value="normal">{{Normal}}</option>
            <option value="bold">{{Gras}}</option>
          </select>
        </div>
      </div>
      <div class="form-group link_type link_text">
        <label class="col-lg-4 control-label">{{Texte}}</label>
        <div class="col-lg-8">
          <textarea class="planAttr form-control" data-l1key="display" data-l2key="text" rows="10">Texte à insérer ici</textarea>
        </div>
      </div>
      <div class="link_type link_zone">
        <div class="form-group">
          <label class="col-lg-4 control-label">{{Type de zone}}</label>
          <div class="col-lg-2">
            <select class="planAttr form-control" data-l1key="configuration" data-l2key="zone_mode">
              <option value="simple">{{Macro simple}}</option>
              <option value="binary">{{Macro binaire}}</option>
              <option value="widget">{{Widget au survol}}</option>
            </select>
          </div>
        </div>

        <div class="zone_mode zone_simple">
          <legend>{{Action}}<a class="btn btn-success pull-right btn-xs bt_planConfigurationAction" data-type="other"><i class="fas fa-plus"></i></a></legend>
          <div id="div_planConfigureActionother"></div>
        </div>

        <div class="zone_mode zone_widget" style="display:none;">
          <div class="form-group">
            <label class="col-lg-4 control-label">{{Equipement}}</label>
            <div class="col-lg-3">
              <div class="input-group">
                <input type="text" class="planAttr form-control roundedLeft" data-l1key="configuration" data-l2key="eqLogic" />
                <span class="input-group-btn">
                  <a class="btn btn-default roundedRight" id="bt_planConfigureAddEqLogic"><i class="fas fa-list-alt"></i></a>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-4 control-label">{{Afficher au survol}}</label>
            <div class="col-lg-2">
              <input type="checkbox" checked class="planAttr" data-l1key="configuration" data-l2key="showOnFly">
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-4 control-label">{{Afficher sur un clic}}</label>
            <div class="col-lg-2">
              <input type="checkbox" checked class="planAttr" data-l1key="configuration" data-l2key="showOnClic">
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-4 control-label">{{Position}}</label>
            <div class="col-lg-2">
              <select class="planAttr form-control" data-l1key="configuration" data-l2key="position">
                <option value="">{{Défaut}}</option>
                <option value="top:0px;">{{Haut}}</option>
                <option value="left:0px;">{{Gauche}}</option>
                <option value="bottom:0px;">{{Bas}}</option>
                <option value="right:0px;">{{Droite}}</option>
                <option value="top:0px;left:0px;">{{Haut Gauche}}</option>
                <option value="top:0px;right:0px;">{{Haut Droite}}</option>
                <option value="bottom:0px;left:0px">{{Bas Gauche}}</option>
                <option value="bottom:0px;right:0px">{{Bas Droite}}</option>
              </select>
            </div>
          </div>
        </div>
        <div class="zone_mode zone_binary" style="display: none;">
          <div class="form-group">
            <label class="col-lg-4 control-label">{{Information binaire}}</label>
            <div class="col-lg-3">
              <input type="text" class="planAttr form-control" data-l1key="configuration" data-l2key="binary_info" />
            </div>
            <div class="col-lg-3">
              <a class="btn btn-default" id="bt_planConfigureSelectBinary"><i class="fas fa-list-alt"></i></a>
            </div>
          </div>
          <legend>{{Action on}}<a class="btn btn-success pull-right btn-xs bt_planConfigurationAction" data-type="on"><i class="fas fa-plus"></i></a></legend>
          <div id="div_planConfigureActionon"></div>

          <legend>{{Action off}}<a class="btn btn-success pull-right btn-xs bt_planConfigurationAction" data-type="off"><i class="fas fa-plus"></i></a></legend>
          <div id="div_planConfigureActionoff"></div>
        </div>
      </div>
    </fieldset>
  </form>
</div>

<script>
if (!jeeFrontEnd.md_planConfigure) {
  jeeFrontEnd.md_planConfigure = {
    plan_configure_plan: null,
    editor: [],
    init: function() {
    },
    postInit: function() {
      //load and set settings (call before any change event set):
      if (isset(jeephp2js.md_planConfigure_Id) && jeephp2js.md_planConfigure_Id != '') {
        jeedom.plan.byId({
          id: jeephp2js.md_planConfigure_Id,
          error: function(error) {
            jeedomUtils.showAlert({
              attachTo: jeeDialog.get('#md_planConfigure', 'dialog'),
              message: error.message,
              level: 'danger'
            })
          },
          success: function(plan) {
            jeeFrontEnd.md_planConfigure.plan_configure_plan = plan
            document.querySelectorAll('.link_type:not(.link_' + plan.plan.link_type + ')').remove()
            document.getElementById('fd_planConfigure').setJeeValues(plan.plan, '.planAttr')
            if (isset(plan.plan.configuration.action_on)) {
              for (var i in plan.plan.configuration.action_on) {
                jeeFrontEnd.md_planConfigure.addActionPlanConfigure(plan.plan.configuration.action_on[i], 'on')
              }
            }
            if (isset(plan.plan.configuration.action_off)) {
              for (var i in plan.plan.configuration.action_off) {
                jeeFrontEnd.md_planConfigure.addActionPlanConfigure(plan.plan.configuration.action_off[i], 'off')
              }
            }
            if (isset(plan.plan.configuration.action_other)) {
              for (var i in plan.plan.configuration.action_other) {
                jeeFrontEnd.md_planConfigure.addActionPlanConfigure(plan.plan.configuration.action_other[i], 'other')
              }
            }
            if (plan.plan.link_type == 'image' && plan.plan.display.path != undefined) {
              document.querySelector('#fd_planConfigure .planImg img').seen().setAttribute('src', plan.plan.display.path)
            }
            if (plan.plan.link_type == 'text') {
              var code = document.querySelector('.planAttr[data-l1key="display"][data-l2key="text"]')
              if (code.getAttribute('id') == undefined) {
                code.uniqueId()
                var id = code.getAttribute('id')
                setTimeout(function() {
                  jeeFrontEnd.md_planConfigure.editor[id] = CodeMirror.fromTextArea(document.getElementById(id), {
                    lineNumbers: true,
                    mode: 'htmlmixed',
                    matchBrackets: true
                  })
                }, 1)
              }
            }
          }
        })
      }

      this.setPlanUI_Events()
      this.setFileUpload()
      jeedomUtils.setCheckContextMenu()
    },
    setFileUpload: function() {
      new jeeFileUploader({
        fileInput: document.getElementById('bt_uploadImagePlan'),
        replaceFileInput: false,
        url: 'core/ajax/plan.ajax.php?action=uploadImagePlan&id=' + jeephp2js.md_planConfigure_Id,
        dataType: 'json',
        done: function(e, data) {
          if (data.result.state != 'ok') {
            jeedomUtils.showAlert({
              attachTo: jeeDialog.get('#md_planConfigure', 'dialog'),
              message: data.result.result,
              level: 'danger'
            })
            return
          }
          if (isset(data.result.result.filepath)) {
            var filePath = data.result.result.filepath
            filePath = '/data/plan/' + filePath.split('/data/plan/')[1]
            document.querySelector('.planImg img').seen().setAttribute('src', filePath)
          } else {
            document.querySelector('.planImg img').unseen()
          }
        }
      })
    },
    addActionPlanConfigure: function(_action, _type) {
      if (!isset(_action)) {
        _action = {}
      }
      if (!isset(_action.options)) {
        _action.options = {}
      }
      var div = '<div class="expression ' + _type + '">'
      div += '<div class="form-group ">'
      div += '<label class="col-sm-1 control-label">{{Action}}</label>'
      div += '<div class="col-sm-4">'
      div += '<div class="input-group">'
      div += '<span class="input-group-btn">'
      div += '<a class="btn btn-default bt_removeAction btn-sm roundedLeft" data-type="' + _type + '"><i class="fas fa-minus-circle"></i></a>'
      div += '</span>'
      div += '<input class="expressionAttr form-control input-sm cmdAction" data-l1key="cmd" data-type="' + _type + '" />'
      div += '<span class="input-group-btn">'
      div += '<a class="btn btn-default btn-sm bt_selectOtherActionExpression" data-type="' + _type + '" title="{{Sélectionner un mot-clé}}"><i class="fas fa-tasks"></i></a>'
      div += '<a class="btn btn-default btn-sm listCmdAction roundedRight" data-type="' + _type + '"><i class="fas fa-list-alt"></i></a>'
      div += '</span>'
      div += '</div>'
      div += '</div>'
      div += '<div class="col-sm-7 actionOptions">'
      div += jeedom.cmd.displayActionOption(init(_action.cmd, ''), _action.options)
      div += '</div>'
      div += '</div>'

      let newDiv = document.createElement('div')
      newDiv.html(div)
      newDiv.setJeeValues(_action, '.expressionAttr')
      document.querySelector('#div_planConfigureAction' + _type).appendChild(newDiv)
      newDiv.replaceWith(...newDiv.childNodes)
      jeedomUtils.taAutosize()
    },
    setPlanUI_Events: function() {
      document.getElementById('deferredEvents').addEventListener('change', function(event) {
        var _target = null
        //background : not default if transparent:
        if (_target = event.target.closest('.planAttr[data-l1key="display"][data-l2key="background-transparent"]')) {
          if (_target.jeeValue() == 1) {
            document.querySelector('.planAttr[data-l1key="display"][data-l2key="background-defaut"]').checked = false
          }
          return
        }
        //background: not default/transparent if colored:
        if (_target = event.target.closest('.planAttr[data-l1key="css"][data-l2key="background-color"]')) {
          if (_target.jeeValue() != '#000000') {
            document.querySelector('.planAttr[data-l1key="display"][data-l2key="background-defaut"]').checked = false
            document.querySelector('.planAttr[data-l1key="display"][data-l2key="background-transparent"]').checked = false
          }
          return
        }
        //background: not transparent if default:
        if (_target = event.target.closest('.planAttr[data-l1key="display"][data-l2key="background-defaut"]')) {
          if (_target.jeeValue() == 1) {
            document.querySelector('.planAttr[data-l1key="display"][data-l2key="background-transparent"]').checked = false
          }
          return
        }
        //text: not default if colored:
        if (_target = event.target.closest('.planAttr[data-l1key="display"][data-l2key="background-defaut"]')) {
          if (_target.jeeValue() != '#000000') {
            document.querySelector('.planAttr[data-l1key="display"][data-l2key="color-defaut"]').checked = false
          }
          return
        }
      })
    },
    save: function() {
      var plans = document.getElementById('fd_planConfigure').getJeeValues('.planAttr')
      if (plans[0].link_type == 'text') {
        var id = document.querySelector('.planAttr[data-l1key=display][data-l2key=text]').getAttribute('id')
        if (id != undefined && isset(jeeFrontEnd.md_planConfigure.editor[id])) {
          plans[0].display.text = jeeFrontEnd.md_planConfigure.editor[id].getValue()
        }
      }
      if (!isset(plans[0].configuration)) {
        plans[0].configuration = {}
      }
      plans[0].configuration.action_on = document.getElementById('div_planConfigureActionon')?.querySelectorAll('.on').getJeeValues('.expressionAttr')
      plans[0].configuration.action_off = document.getElementById('div_planConfigureActionoff')?.querySelectorAll('.off').getJeeValues('.expressionAttr')
      plans[0].configuration.action_other = document.getElementById('div_planConfigureActionother')?.querySelectorAll('.other').getJeeValues('.expressionAttr')
      jeedom.plan.save({
        plans: plans,
        error: function(error) {
          jeedomUtils.showAlert({
            attachTo: jeeDialog.get('#md_planConfigure', 'dialog'),
            message: error.message,
            level: 'danger'
          })
        },
        success: function() {
          jeedomUtils.showAlert({
            attachTo: jeeDialog.get('#md_planConfigure', 'dialog'),
            message: '{{Design sauvegardé}}',
            level: 'success'
          })
          jeedom.plan.byId({
            id: plans[0].id,
            error: function(error) {
              jeedomUtils.showAlert({
                attachTo: jeeDialog.get('#md_planConfigure', 'dialog'),
                message: error.message,
                level: 'danger'
              })
            },
            success: function(plan) {
              if (jeeFrontEnd.md_planConfigure.plan_configure_plan.plan.link_type == 'summary' && jeeFrontEnd.md_planConfigure.plan_configure_plan !== null && jeeFrontEnd.md_planConfigure.plan_configure_plan.plan.link_id) {
                document.querySelector('.div_displayObject .summary-widget[data-summary_id="' + jeeFrontEnd.md_planConfigure.plan_configure_plan.plan.link_id + '"]').remove()
              }
              jeeFrontEnd.plan.displayObject(plan.plan, plan.html, false)
            }
          })
        }
      })
    },
  }
}
(function() {// Self Isolation!
  var jeeM = jeeFrontEnd.md_planConfigure
  jeeM.init()

  /*Events delegations
  */
  document.getElementById('md_planConfigure').addEventListener('click', function(event) {
    var _target = null
    if (_target = event.target.closest('.bt_planConfigurationAction')) {
      jeeFrontEnd.md_planConfigure.addActionPlanConfigure({}, _target.getAttribute('data-type'))
      return
    }

    if (_target = event.target.closest('.bt_removeAction')) {
      _target.closest('.' + _target.getAttribute('data-type')).remove()
      return
    }

    if (_target = event.target.closest('.listCmdAction')) {
      var type = _target.getAttribute('data-type')
      var el = _target.closest('.' + type).querySelector('.expressionAttr[data-l1key="cmd"]')
      jeedom.cmd.getSelectModal({
        cmd: {
          type: 'action'
        }
      }, function(result) {
        el.jeeValue(result.human)
        jeedom.cmd.displayActionOption(result.human, '', function(html) {
          el.closest('.' + type).querySelector('.actionOptions').html(html)
          jeedomUtils.taAutosize()
        })
      })
      return
    }

    if (_target = event.target.closest('.bt_selectOtherActionExpression')) {
      var expression = _target.closest('.expression')
      jeedom.getSelectActionModal({
        scenario: true
      }, function(result) {
        expression.querySelector('.expressionAttr[data-l1key="cmd"]').jeeValue(result.human)
        jeedom.cmd.displayActionOption(result.human, '', function(html) {
          expression.querySelector('.actionOptions').html(html)
          jeedomUtils.taAutosize()
        })
      })
      return
    }

    if (_target = event.target.closest('#bt_planConfigureAddEqLogic')) {
      jeedom.eqLogic.getSelectModal({}, function(result) {
        _target.parentNode.parentNode.querySelector('.planAttr[data-l1key="configuration"][data-l2key="eqLogic"]').jeeValue(result.human)
      })
      return
    }

    if (_target = event.target.closest('#bt_planConfigureSelectCamera')) {
      jeedom.eqLogic.getSelectModal({
        eqLogic: {
          eqType_name: 'camera'
        }
      }, function(result) {
        _target.parentNode.parentNode.querySelector('.planAttr[data-l1key="configuration"][data-l2key="camera"]').jeeValue(result.human)
      })
      return
    }

    if (_target = event.target.closest('#bt_planConfigureSelectBinary')) {
      jeedom.cmd.getSelectModal({
        cmd: {
          type: 'info'
        }
      }, function(result) {
        _target.parentNode.parentNode.querySelector('.planAttr[data-l1key="configuration"][data-l2key="binary_info"]').jeeValue(result.human)
      })
      return
    }

    if (_target = event.target.closest('#bt_chooseIcon')) {
      jeedomUtils.chooseIcon(function(_icon) {
        document.querySelector('.planAttr[data-l1key="display"][data-l2key="icon"]').innerHTML = _icon
      })
      return
    }

    if (_target = event.target.closest('#bt_saveConfigurePlan')) {
      var check = document.querySelector('input[data-l2key="font-size"]')
      if (check && check.value != '' && !check.value.endsWith('%')) {
        check.value = check.value + '%'
      }
      jeeFrontEnd.md_planConfigure.save()
      return
    }
  })

  document.getElementById('md_planConfigure').addEventListener('focusout', function(event) {
    var _target = null
    if (_target = event.target.closest('.expressionAttr[data-l1key="cmd"]')) {
      var type = _target.getAttribute('data-type')
      jeedom.cmd.displayActionOption(_target.jeeValue(), '', function(html) {
        _target.closest('.' + type).querySelector('.actionOptions').html(html)
        jeedomUtils.taAutosize()
      })
      return
    }
  })

  document.getElementById('md_planConfigure').addEventListener('change', function(event) {
    var _target = null
    if (_target = event.target.closest('.planAttr[data-l1key="configuration"][data-l2key="zone_mode"]')) {
      document.querySelectorAll('.zone_mode').unseen()
      document.querySelectorAll('.zone_mode.zone_' + _target.jeeValue()).seen()
      return
    }

    if (_target = event.target.closest('.planAttr[data-l1key="configuration"][data-l2key="display_mode"]')) {
      document.querySelectorAll('.display_mode').unseen()
      document.querySelectorAll('.display_mode.display_mode_' + _target.jeeValue()).seen()
      return
    }
  })

  jeeM.postInit()
})()
</script>