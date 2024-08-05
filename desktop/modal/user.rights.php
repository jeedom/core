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
$user = user::byId(init('id'));

if (!is_object($user)) {
  throw new Exception(__('Impossible de trouver l\'utilisateur :', __FILE__) . ' ' . init('id'));
}
sendVarToJs('jeephp2js.md_userRights_rights', utils::o2a($user));
?>

<div id="md_userRights" data-modalType="md_userRights">
  <a class="btn btn-success pull-right" id="bt_usersRightsSave"><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a>
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#tab_eqLogic" aria-controls="tab_eqLogic" role="tab" data-toggle="tab"><i class="fas fa-th"></i> {{Equipements}}</a></li>
    <li role="presentation"><a href="#tab_scenario" aria-controls="tab_scenario" role="tab" data-toggle="tab"><i class="fas fa-cogs"></i> {{Scénarios}}</a></li>
    <li role="presentation"><a href="#tab_object" aria-controls="tab_object" role="tab" data-toggle="tab"><i class="far fa-object-group"></i> {{Objets}}</a></li>
    <li role="presentation"><a href="#tab_view" aria-controls="tab_view" role="tab" data-toggle="tab"><i class="far fa-image"></i> {{Vues}}</a></li>
    <li role="presentation"><a href="#tab_plan" aria-controls="tab_plan" role="tab" data-toggle="tab"><i class="fas fa-paint-brush"></i> {{Design}}</a></li>
    <li role="presentation"><a href="#tab_plan3d" aria-controls="tab_plan3d" role="tab" data-toggle="tab"><i class="fas fa-cubes"></i> {{Design 3d}}</a></li>
    <li role="presentation"><a href="#tab_menu" aria-controls="tab_menu" role="tab" data-toggle="tab"><i class="fas fa-bars"></i> {{Menu}}</a></li>
  </ul>
  <div class="tab-content" id="div_tabUserRights">
    <span class="userAttr" data-l1key="id" style="display:none;"></span>

    <div role="tabpanel" class="tab-pane active" id="tab_eqLogic">
      <div class="pull-right" style="width: 100%;text-align: right;">
        {{Appliquer aux éléments visibles}}:
        <select id="eqSelectSet" class="input-sm" style="width: 25%;">
          <option value="n">{{Aucun}}</option>
          <option value="r">{{Visualisation}}</option>
          <option value="rx">{{Visualisation et exécution}}</option>
        </select>
      </div>
      <table class='table table-condensed dataTable'>
        <thead>
          <tr>
            <th>{{Equipement}}</th>
            <th data-type="select-text" style="width:250px;">{{Droits}}</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $html = '';
          foreach ((eqLogic::all()) as $eqLogic) {
            $html .= '<tr>';
            $html .= '<td>' . $eqLogic->getHumanName(true) . '</td>';
            $html .= '<td>';
            $html .= '<select class="form-control userAttr input-sm" data-l1key="rights" data-l2key="eqLogic' . $eqLogic->getId() . '">';
            $html .= '<option value="n">{{Aucun}}</option>';
            $html .= '<option value="r">{{Visualisation}}</option>';
            $html .= '<option value="rx">{{Visualisation et exécution}}</option>';
            $html .= '</select>';
            $html .= '</td>';
            $html .= '</tr>';
          }
          echo $html;
          ?>
        </tbody>
      </table>
    </div>

    <div role="tabpanel" class="tab-pane" id="tab_scenario">
      <div class="pull-right" style="width: 100%;text-align: right;">
        {{Appliquer aux éléments visibles}}:
        <select id="scSelectSet" class="input-sm" style="width: 25%;">
          <option value="n">{{Aucun}}</option>
          <option value="r">{{Visualisation}}</option>
          <option value="rx">{{Visualisation et exécution}}</option>
        </select>
      </div>
      <table class='table table-condensed dataTable'>
        <thead>
          <tr>
            <th>{{Scénario}}</th>
            <th data-type="select-text" style="width:250px;">{{Droits}}</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $html = '';
          foreach ((scenario::allOrderedByGroupObjectName()) as $scenario) {
            $html .= '<tr>';
            $html .= '<td>' . $scenario->getHumanName(true, false, true) . '</td>';
            $html .= '<td>';
            $html .= '<select class="form-control userAttr input-sm" data-l1key="rights" data-l2key="scenario' . $scenario->getId() . '">';
            $html .= '<option value="n">{{Aucun}}</option>';
            $html .= '<option value="r">{{Visualisation}}</option>';
            $html .= '<option value="rx">{{Visualisation et exécution}}</option>';
            $html .= '</select>';
            $html .= '</td>';
            $html .= '</tr>';
          }
          echo $html;
          ?>
        </tbody>
      </table>
    </div>

    <div role="tabpanel" class="tab-pane" id="tab_object">
      <div class="pull-right" style="width: 100%;text-align: right;">
        {{Appliquer aux éléments visibles}}:
        <select id="objSelectSet" class="input-sm" style="width: 25%;">
          <option value="n">{{Aucun}}</option>
          <option value="r">{{Visualisation}}</option>
        </select>
      </div>
      <table class='table table-condensed dataTable'>
        <thead>
          <tr>
            <th>{{Objets}}</th>
            <th data-type="select-text" style="width:250px;">{{Droits}}</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $html = '';
          foreach ((jeeObject::all()) as $object) {
            $html .= '<tr>';
            $html .= '<td>' . $object->getHumanName(true, false, true) . '</td>';
            $html .= '<td>';
            $html .= '<select class="form-control userAttr input-sm" data-l1key="rights" data-l2key="jeeObject' . $object->getId() . '">';
            $html .= '<option value="n">{{Aucun}}</option>';
            $html .= '<option value="r">{{Visualisation}}</option>';
            $html .= '</select>';
            $html .= '</td>';
            $html .= '</tr>';
          }
          echo $html;
          ?>
        </tbody>
      </table>
    </div>

    <div role="tabpanel" class="tab-pane" id="tab_view">
      <div class="pull-right" style="width: 100%;text-align: right;">
        {{Appliquer aux éléments visibles}}:
        <select id="viewSelectSet" class="input-sm" style="width: 25%;">
          <option value="n">{{Aucun}}</option>
          <option value="r">{{Visualisation}}</option>
        </select>
      </div>
      <table class='table table-condensed dataTable'>
        <thead>
          <tr>
            <th>{{Vues}}</th>
            <th data-type="select-text" style="width:250px;">{{Droits}}</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $html = '';
          foreach ((view::all()) as $view) {
            $html .= '<tr>';
            $html .= '<td>' . $view->getName() . '</td>';
            $html .= '<td>';
            $html .= '<select class="form-control userAttr input-sm" data-l1key="rights" data-l2key="view' . $view->getId() . '">';
            $html .= '<option value="n">{{Aucun}}</option>';
            $html .= '<option value="r">{{Visualisation}}</option>';
            $html .= '</select>';
            $html .= '</td>';
            $html .= '</tr>';
          }
          echo $html;
          ?>
        </tbody>
      </table>
    </div>

    <div role="tabpanel" class="tab-pane" id="tab_plan">
      <div class="pull-right" style="width: 100%;text-align: right;">
        {{Appliquer aux éléments visibles}}:
        <select id="planSelectSet" class="input-sm" style="width: 25%;">
          <option value="n">{{Aucun}}</option>
          <option value="r">{{Visualisation}}</option>
        </select>
      </div>
      <table class='table table-condensed dataTable'>
        <thead>
          <tr>
            <th>{{Design}}</th>
            <th data-type="select-text" style="width:250px;">{{Droits}}</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $html = '';
          foreach ((planHeader::all()) as $plan) {
            $html .= '<tr>';
            $html .= '<td>' . $plan->getName() . '</td>';
            $html .= '<td>';
            $html .= '<select class="form-control userAttr input-sm" data-l1key="rights" data-l2key="plan' . $plan->getId() . '">';
            $html .= '<option value="n">{{Aucun}}</option>';
            $html .= '<option value="r">{{Visualisation}}</option>';
            $html .= '</select>';
            $html .= '</td>';
            $html .= '</tr>';
          }
          echo $html;
          ?>
        </tbody>
      </table>
    </div>

    <div role="tabpanel" class="tab-pane" id="tab_plan3d">
      <div class="pull-right" style="width: 100%;text-align: right;">
        {{Appliquer aux éléments visibles}}:
        <select id="plan3dSelectSet" class="input-sm" style="width: 25%;">
          <option value="n">{{Aucun}}</option>
          <option value="r">{{Visualisation}}</option>
        </select>
      </div>
      <table class='table table-condensed dataTable'>
        <thead>
          <tr>
            <th>{{Design}}</th>
            <th data-type="select-text" style="width:250px;">{{Droits}}</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $html = '';
          foreach ((plan3dHeader::all()) as $plan3d) {
            $html .= '<tr>';
            $html .= '<td>' . $plan3d->getName() . '</td>';
            $html .= '<td>';
            $html .= '<select class="form-control userAttr input-sm" data-l1key="rights" data-l2key="plan3d' . $plan3d->getId() . '">';
            $html .= '<option value="n">{{Aucun}}</option>';
            $html .= '<option value="r">{{Visualisation}}</option>';
            $html .= '</select>';
            $html .= '</td>';
            $html .= '</tr>';
          }
          echo $html;
          ?>
        </tbody>
      </table>
    </div>

    <div role="tabpanel" class="tab-pane" id="tab_menu">
      <table class='table table-condensed dataTable'>
        <thead>
          <tr>
            <th>{{Menu}}</th>
            <th data-type="select-text" style="width:250px;">{{Droits}}</th>
          </tr>
        </thead>
        <tbody>
        <?php
          $menus = array(
            'overview' => __('Synthèse',__FILE__),
            'dashboard' => __('Dashboard',__FILE__),
            'view' => __('Vue',__FILE__),
            'plan' => __('Design',__FILE__),
            'plan3d' => __('Design 3D',__FILE__),
            'analyze' => __('[Globale] Analyse',__FILE__),
            'timeline' => __('Timeline',__FILE__),
            'history' => __('Historique',__FILE__),
            'settings' => __('[Globale] Réglages',__FILE__),
            'profils' => __('Préférences',__FILE__),
            'mobile' => __('Version mobile',__FILE__),
          );
          $html = '';
          foreach ($menus as $key => $value) {
            $html .= '<tr>';
            $html .= '<td>' . $value . '</td>';
            $html .= '<td>';
            $html .= '<select class="form-control userAttr input-sm" data-l1key="rights" data-l2key="menu::' . $key . '">';
            $html .= '<option value="n">{{Masqué}}</option>';
            $html .= '<option value="r" selected>{{Visible}}</option>';
            $html .= '</select>';
            $html .= '</td>';
            $html .= '</tr>';
          }
          echo $html;
          ?>
        </tbody>
      </table>
    </div>

  </div>
</div>

<script>
if (!jeeFrontEnd.md_userRights) {
  jeeFrontEnd.md_userRights = {
    init: function() {
      document.getElementById('div_tabUserRights').setJeeValues(jeephp2js.md_userRights_rights, '.userAttr')
      jeedomUtils.initDataTables('#md_userRights', false, true)
    },
    save: function() {
      jeedom.user.save({
        users: document.getElementById('div_tabUserRights').getJeeValues('.userAttr'),
        error: function(error) {
          jeedomUtils.showAlert({
            attachTo: jeeDialog.get('#md_userRights', 'dialog'),
            message: error.message,
            level: 'danger'
          })
        },
        success: function() {
          jeedomUtils.showAlert({
            attachTo: jeeDialog.get('#md_userRights', 'dialog'),
            message: '{{Sauvegarde effectuée}}',
            level: 'success'
          })
          modifyWithoutSave = false
        }
      })
    },
  }
}
(function() {// Self Isolation!
  var jeeM = jeeFrontEnd.md_userRights
  jeeM.init()

  //Manage events outside parents delegations:
  document.querySelector('#md_userRights #bt_usersRightsSave').addEventListener('click', function(event) {
    jeeFrontEnd.md_userRights.save()
  })

  /*Events delegations
  */
  document.getElementById('md_userRights').addEventListener('change', function(event) {
    var _target = null
    if (_target = event.target.closest('#eqSelectSet')) {
      var value = _target.value
      document.getElementById('tab_eqLogic').querySelectorAll('tbody tr:not(.filtered) select').forEach(_select => {
        _select.value = value
      })
      return
    }

    if (_target = event.target.closest('#scSelectSet')) {
      var value = _target.value
      document.getElementById('tab_scenario').querySelectorAll('tbody tr:not(.filtered) select').forEach(_select => {
        _select.value = value
      })
      return
    }

    if (_target = event.target.closest('#objSelectSet')) {
      var value = _target.value
      document.getElementById('tab_object').querySelectorAll('tbody tr:not(.filtered) select').forEach(_select => {
        _select.value = value
      })
      return
    }

    if (_target = event.target.closest('#viewSelectSet')) {
      var value = _target.value
      document.getElementById('tab_view').querySelectorAll('tbody tr:not(.filtered) select').forEach(_select => {
        _select.value = value
      })
      return
    }

    if (_target = event.target.closest('#planSelectSet')) {
      var value = _target.value
      document.getElementById('tab_plan').querySelectorAll('tbody tr:not(.filtered) select').forEach(_select => {
        _select.value = value
      })
      return
    }

    if (_target = event.target.closest('#plan3dSelectSet')) {
      var value = _target.value
      document.getElementById('tab_plan3d').querySelectorAll('tbody tr:not(.filtered) select').forEach(_select => {
        _select.value = value
      })
      return
    }
  })

})()
</script>