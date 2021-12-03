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
sendVarToJs('user_rights', utils::o2a($user));
?>

<div style="display: none;" id="div_userRightAlert"></div>
<a class="btn btn-success pull-right" id="bt_usersRightsSave"><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a>
<ul class="nav nav-tabs" role="tablist">
  <li role="presentation" class="active"><a href="#tab_eqLogic" aria-controls="tab_eqLogic" role="tab" data-toggle="tab"><i class="fas fa-th"></i> {{Equipements}}</a></li>
  <li role="presentation"><a href="#tab_scenario" aria-controls="tab_scenario" role="tab" data-toggle="tab"><i class="fas fa-cogs"></i> {{Scénarios}}</a></li>
  <li role="presentation"><a href="#tab_object" aria-controls="tab_object" role="tab" data-toggle="tab"><i class="far fa-object-group"></i> {{Objets}}</a></li>
  <li role="presentation"><a href="#tab_view" aria-controls="tab_view" role="tab" data-toggle="tab"><i class="far fa-image"></i> {{Vues}}</a></li>
  <li role="presentation"><a href="#tab_plan" aria-controls="tab_plan" role="tab" data-toggle="tab"><i class="fas fa-paint-brush"></i> {{Design}}</a></li>
  <li role="presentation"><a href="#tab_plan3d" aria-controls="tab_plan3d" role="tab" data-toggle="tab"><i class="fas fa-cubes"></i> {{Design 3d}}</a></li>
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
    <table class='table table-condensed table-bordered tablesorter'>
      <thead>
        <tr>
          <th>{{Equipement}}</th>
          <th data-sorter="select-text">{{Droits}}</th>
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
    <table class='table table-condensed table-bordered tablesorter'>
      <thead>
        <tr>
          <th>{{Scénario}}</th>
          <th data-sorter="select-text">{{Droits}}</th>
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
    <table class='table table-condensed table-bordered tablesorter'>
      <thead>
        <tr>
          <th>{{Objets}}</th>
          <th data-sorter="select-text">{{Droits}}</th>
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
      <select id="objSelectSet" class="input-sm" style="width: 25%;">
        <option value="n">{{Aucun}}</option>
        <option value="r">{{Visualisation}}</option>
      </select>
    </div>
    <table class='table table-condensed table-bordered tablesorter'>
      <thead>
        <tr>
          <th>{{Vues}}</th>
          <th data-sorter="select-text">{{Droits}}</th>
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
      <select id="objSelectSet" class="input-sm" style="width: 25%;">
        <option value="n">{{Aucun}}</option>
        <option value="r">{{Visualisation}}</option>
      </select>
    </div>
    <table class='table table-condensed table-bordered tablesorter'>
      <thead>
        <tr>
          <th>{{Design}}</th>
          <th data-sorter="select-text">{{Droits}}</th>
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
      <select id="objSelectSet" class="input-sm" style="width: 25%;">
        <option value="n">{{Aucun}}</option>
        <option value="r">{{Visualisation}}</option>
      </select>
    </div>
    <table class='table table-condensed table-bordered tablesorter'>
      <thead>
        <tr>
          <th>{{Design}}</th>
          <th data-sorter="select-text">{{Droits}}</th>
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

</div>

<script>
  $('#div_tabUserRights').setValues(user_rights, '.userAttr')
  jeedomUtils.initTableSorter()

  $("#bt_usersRightsSave").on('click', function(event) {
    jeedom.user.save({
      users: $('#div_tabUserRights').getValues('.userAttr'),
      error: function(error) {
        $('#div_userRightAlert').showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function() {
        $('#div_userRightAlert').showAlert({
          message: '{{Sauvegarde effectuée}}',
          level: 'success'
        })
        modifyWithoutSave = false
      }
    })
  })

  $('#tab_eqLogic').on({
    'change': function(event) {
      var value = $(this).val()
      $('#tab_eqLogic').find('tbody tr:not(.filtered) select').each(function() {
        $(this).val(value)
      })
    }
  }, '#eqSelectSet')

  $('#tab_scenario').on({
    'change': function(event) {
      var value = $(this).val()
      $('#tab_scenario').find('tbody tr:not(.filtered) select').each(function() {
        $(this).val(value)
      })
    }
  }, '#scSelectSet')

  $('#tab_object').on({
    'change': function(event) {
      var value = $(this).val()
      $('#tab_object').find('tbody tr:not(.filtered) select').each(function() {
        $(this).val(value)
      })
    }
  }, '#objSelectSet')
</script>