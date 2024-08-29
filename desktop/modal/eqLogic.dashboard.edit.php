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
  throw new Exception('401 - {{Accès non autorisé}}');
}
$eqLogic = eqLogic::byId(init('eqLogic_id'));
if (!is_object($eqLogic)) {
  throw new Exception('{{Equipement non trouvé}}' . ' : ' . init('eqLogic_id'));
}
//cmds setters:
$cmds = $eqLogic->getCmd();
$allCmds = [];
foreach ($cmds as $cmd) {
  $allCmds[$cmd->getId()] = jeedom::toHumanReadable(utils::o2a($cmd));
  $allCmds[$cmd->getId()]['widgetPossibilityDashboard'] = $cmd->widgetPossibility('custom::widget::dashboard');
  $allCmds[$cmd->getId()]['widgetPossibilityMobile'] = $cmd->widgetPossibility('custom::widget::mobile');
}
//eqLogic setter:
sendVarToJS([
  'jeephp2js.md_eqLogicDashEdit_eqInfo' => utils::o2a($eqLogic),
  'jeephp2js.md_eqLogicDashEdit_customOptParams' => $eqLogic->widgetPossibility('custom::optionalParameters'),
  'jeephp2js.md_eqLogicDashEdit_customLayout' => $eqLogic->widgetPossibility('custom::layout'),
  'jeephp2js.md_eqLogicDashEdit_allCmdsInfo' => $allCmds
]);

$cmd_widgetDashboard = cmd::availableWidget('dashboard');
$cmd_widgetMobile = cmd::availableWidget('mobile');
?>

<div id="md_eqlogicDashEdit" data-modalType="md_eqlogicDashEdit">
  <!-- Global parameters -->
  <span class="eqLogicAttr hidden" data-l1key="id"></span>
  <span class="eqLogicAttr hidden" data-l1key="name"></span>

  <!-- Display -->
  <?php if ($eqLogic->widgetPossibility('custom')) {
  ?>
    <div id="panel_optParams" class="panel panel-default">
      <h3 class="panel-title">
        <a class="accordion-toggle" data-toggle="collapse" data-parent="" aria-expanded="false" href="#optParams"><i class="fas fa-desktop"></i> {{Affichage}}</a>
        <sup class="pull-right"><i class="fas fa-question-circle" title="{{Graphique de fond de tuile, paramètres de widget et optionnels.}}"></i></sup>
      </h3>
      <div id="optParams" class="panel-collapse collapse">
        <div class="panel-body">
          <?php if ($eqLogic->widgetPossibility('custom::graph')) {
          ?>
            <form class="form-horizontal">
              <label><i class="fas fa-chart-line"></i> {{Graphique de fond}}</label>
              <div class="form-group">
                <label class="col-sm-2 control-label">{{Information}}</label>
                <div class="col-sm-4">
                  <select class="eqLogicAttr form-control input-sm sel_backGraphInfo" data-l1key="display" data-l2key="backGraph::info">
                    <option value="0">{{Aucune}}</option>
                    <?php
                    $options = '';
                    foreach (($eqLogic->getCmd('info')) as $cmd) {
                      if ($cmd->getIsHistorized()) {
                        $options .= '<option value="' . $cmd->getId() . '">' . $cmd->getName() . '</option>';
                      }
                    }
                    echo $options;
                    ?>
                  </select>
                </div>

                <label class="col-sm-2 control-label">{{Couleur}}</label>
                <div class="col-sm-4">
                  <input type="color" class="eqLogicAttr form-control input-sm" value="#4572A7" data-l1key="display" data-l2key="backGraph::color">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label">{{Période}}</label>
                <div class="col-sm-4">
                  <select class="eqLogicAttr form-control input-sm" data-l1key="display" data-l2key="backGraph::format">
                    <option value="month">{{Mois}}</option>
                    <option value="week">{{Semaine}}</option>
                    <option value="day">{{Jour}}</option>
                    <option value="hour">{{Heure}}</option>
                  </select>
                </div>

                <label class="col-sm-2 control-label">{{Hauteur}}</label>
                <div class="col-sm-4">
                  <input type="number" class="eqLogicAttr form-control input-sm ispin" min="0" value="" data-l1key="display" data-l2key="backGraph::height" placeholder="{{Automatique}}">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label">{{Type}}</label>
                <div class="col-sm-4">
                  <select class="eqLogicAttr form-control input-sm" data-l1key="display" data-l2key="backGraph::type">
                    <option value="areaspline">{{Courbe}}</option>
                    <option value="area">{{Escalier}}</option>
                    <option value="column">{{Barres verticales}}</option>
                  </select>
                </div>
              </div>
            </form>
            <br>

          <?php }
          if ($eqLogic->getDisplay('widgetTmpl', 1) == 1 && is_array($eqLogic->widgetPossibility('parameters')) && count($eqLogic->widgetPossibility('parameters')) > 0) {
          ?>
            <label><i class="fas fa-pencil-ruler"></i> {{Paramètres du template}}</label>
            <table class="table table-condensed">
              <thead>
                <tr>
                  <th></th>
                  <?php
                  $display = '';
                  foreach ((jeedom::getConfiguration('eqLogic:displayType')) as $key => $value) {
                    $display .= '<th width="33%">' . $value['name'] . '</th>';
                  }
                  echo $display;
                  ?>
                </tr>
              </thead>
              <tbody>
                <?php
                if (is_array($eqLogic->widgetPossibility('parameters'))) {
                  $echo = '';
                  foreach (($eqLogic->widgetPossibility('parameters')) as $pKey => $parameter) {
                    $echo .= '<tr>';
                    $echo .= '<td>';
                    if (is_array($parameter['name']) && isset($parameter['name'][translate::getLanguage()])) $echo .= $parameter['name'][translate::getLanguage()];
                    else $echo .= $parameter['name'];
                    $echo .= '</td>';
                    foreach ((jeedom::getConfiguration('eqLogic:displayType')) as $key => $value) {
                      $echo .= '<td>';
                      if (!isset($parameter['allow_displayType'])) {
                        continue;
                      }
                      if (!isset($parameter['type'])) {
                        continue;
                      }
                      if (is_array($parameter['allow_displayType']) && !in_array($key, $parameter['allow_displayType'])) {
                        continue;
                      }
                      if ($parameter['allow_displayType'] === false) {
                        continue;
                      }
                      $default = '';
                      $display = '';
                      if (isset($parameter['default'])) {
                        $display = ' style="display:none;"';
                        $default = $parameter['default'];
                        $echo .= '<label>{{Défaut}} <input type="checkbox" class="eqLogicAttr advanceWidgetParameterDefault" data-l1key="display" data-l2key="advanceWidgetParameter' . $pKey . $key . '-default" checked></label> ';
                      }
                      switch ($parameter['type']) {
                        case 'color':
                          if ($parameter['allow_transparent']) {
                            $echo .= '<span class="advanceWidgetParameter"' . $display . '>';
                            $echo .= '<label>{{Transparent}} <input type="checkbox" class="eqLogicAttr advanceWidgetParameterColorTransparent" data-l1key="display" data-l2key="advanceWidgetParameter' . $pKey . $key . '-transparent"></label>';
                            $echo .= ' <input type="color" class="eqLogicAttr advanceWidgetParameterColor" data-l1key="display" data-l2key="advanceWidgetParameter' . $pKey . $key . '" value="' . $default . '">';
                            $echo .= '</span>';
                          } else {
                            $echo .= '<input type="color" class="eqLogicAttr advanceWidgetParameter"' . $display . '" data-l1key="display" data-l2key="advanceWidgetParameter' . $pKey . $key . '" value="' . $default . '">';
                          }
                          break;
                        case 'input':
                          $display = ($display != '') ? str_replace(';"', ';width:50%;"', $display) : '';
                          $echo .= '<input class="eqLogicAttr advanceWidgetParameter"' . $display . '" data-l1key="display" data-l2key="advanceWidgetParameter' . $pKey . $key . '" value="' . $default . '">';
                          break;
                        case 'number':
                          $display = ($display != '') ? str_replace(';"', ';width:50%;"', $display) : '';
                          $echo .= '<input type="number" class="eqLogicAttr advanceWidgetParameter"' . $display . '" data-l1key="display" data-l2key="advanceWidgetParameter' . $pKey . $key . '" value="' . $default . '">';
                          break;
                      }
                      $echo .= '</td>';
                    }
                    $echo .= '</tr>';
                  }
                  echo $echo;
                }
                ?>
              </tbody>
            </table>
          <?php }
          if ($eqLogic->widgetPossibility('custom::optionalParameters')) {
          ?>
            <label><i class="fas fa-code"></i> {{Paramètres optionnels}}</label>
            <a class="btn btn-success btn-xs pull-right" id="bt_addTileParameters"><i class="fas fa-plus-circle"></i> {{Ajouter}}</a>
            <table class="table table-condensed" id="table_widgetParameters">
              <thead>
                <tr>
                  <th>{{Nom}}</th>
                  <th>{{Valeur}}</th>
                  <th class="text-right">{{Action}}</th>
                </tr>
              </thead>
              <tbody>
                <?php
                if ($eqLogic->getDisplay('parameters') != '') {
                  $echo = '';
                  foreach (($eqLogic->getDisplay('parameters')) as $key => $value) {
                    $echo .= '<tr>';
                    $echo .= '<td>';
                    $echo .= '<input class="form-control input-sm key" value="' . $key . '">';
                    $echo .= '</td>';
                    $echo .= '<td>';
                    $echo .= '<input class="form-control input-sm value" value="' . htmlspecialchars($value, ENT_QUOTES) . '">';
                    $echo .= '</td>';
                    $echo .= '<td class="text-right">';
                    $echo .= '<a class="btn btn-danger btn-xs removeTileParameter"><i class="far fa-trash-alt"></i></a>';
                    $echo .= '</td>';
                    $echo .= '</tr>';
                  }
                  echo $echo;
                }
                ?>
              </tbody>
            </table>
          <?php }
          ?>
        </div>
      </div>
    </div>

    <!-- Layout -->
    <?php if ($eqLogic->widgetPossibility('custom::layout')) {
    ?>
      <div id="panel_layout" class="panel panel-default">
        <h3 class="panel-title">
          <a class="accordion-toggle" data-toggle="collapse" data-parent="" aria-expanded="false" href="#layout"><i class="fas fa-th"></i> {{Disposition}}</a>
          <sup class="pull-right"><i class="fas fa-question-circle" title="{{Mise en forme de la tuile par défaut ou en mode tableau.}}"></i></sup>
        </h3>
        <div id="layout" class="panel-collapse collapse">
          <div class="panel-body">
            <form class="form-horizontal">
              <label><i class="fas fa-table"></i> {{Mise en forme générale}}</label>
              <div class="form-group">
                <label class="col-sm-2 control-label">{{Disposition}}</label>
                <div class="col-sm-4">
                  <select class="eqLogicAttr form-control input-sm sel_layout" data-l1key="display" data-l2key="layout::dashboard">
                    <option value="default">{{Défaut}}</option>
                    <option value="table">{{Tableau}}</option>
                  </select>
                </div>

                <div class="widget_layout table" style="display: none;">
                  <label class="col-sm-2 control-label">{{Centrer}}</label>
                  <div class="col-sm-4">
                    <input type="checkbox" class="eqLogicAttr" data-l1key="display" data-l2key="layout::dashboard::table::parameters" data-l3key="center">
                  </div>
                </div>
              </div>

              <div class="widget_layout table" style="display: none;">
                <div class="form-group">
                  <label class="col-sm-2 control-label">{{Lignes}}</label>
                  <div class="col-sm-4">
                    <input type="number" min="1" max="20" step="1" class="eqLogicAttr form-control input-sm ispin" data-l1key="display" data-l2key="layout::dashboard::table::nbLine">
                  </div>
                  <label class="col-sm-2 control-label">{{Colonnes}}</label>
                  <div class="col-sm-4">
                    <input type="number" min="1" max="20" step="1" class="eqLogicAttr form-control input-sm ispin" data-l1key="display" data-l2key="layout::dashboard::table::nbColumn">
                    <a class="btn btn-success btn-xs" id="bt_eqLogicLayoutApply"><i class="fas fa-sync-alt"></i></i> {{Appliquer}}</a>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label">{{Style tableau}}</label>
                  <div class="col-sm-10">
                    <textarea class="eqLogicAttr form-control" data-l1key="display" data-l2key="layout::dashboard::table::parameters" data-l3key="styletable"></textarea>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label">{{Style cellules}}</label>
                  <div class="col-sm-10">
                    <textarea class="eqLogicAttr form-control" data-l1key="display" data-l2key="layout::dashboard::table::parameters" data-l3key="styletd"></textarea>
                  </div>
                </div>
              </div>
            </form>

            <div class="widget_layout table" style="display: none;">
              <label><i class="fas fa-th-large"></i> {{Mise en forme détaillée}}</label>
              <div class="table-responsive">
                <table class="table table-condensed table-responsive" id="tableCmdLayoutConfiguration">
                  <tbody>
                    <?php
                    $table = array();
                    foreach (($eqLogic->getCmd(null, null, true)) as $cmd) {
                      $line = $eqLogic->getDisplay('layout::dashboard::table::cmd::' . $cmd->getId() . '::line', 1);
                      $column = $eqLogic->getDisplay('layout::dashboard::table::cmd::' . $cmd->getId() . '::column', 1);
                      if (!isset($table[$line])) {
                        $table[$line] = array();
                      }
                      if (!isset($table[$line][$column])) {
                        $table[$line][$column] = array();
                      }
                      $table[$line][$column][] = $cmd;
                    }
                    $getDisplayDasboardNbLine = $eqLogic->getDisplay('layout::dashboard::table::nbLine', 1);
                    $getDisplayDasboardNbColumn = $eqLogic->getDisplay('layout::dashboard::table::nbColumn', 1);
                    for ($i = 1; $i <= $getDisplayDasboardNbLine; $i++) {
                      $tr = '<tr>';
                      for ($j = 1; $j <= $getDisplayDasboardNbColumn; $j++) {
                        $tr .= '<td data-line="' . $i . '" data-column="' . $j . '">';
                        $string_cmd = '<div class="cmdLayoutContainer text-center" style="min-height:30px;">';
                        if (isset($table[$i][$j]) && count($table[$i][$j]) > 0) {
                          foreach ($table[$i][$j] as $cmd) {
                            $string_cmd .= '<span class="label label-default cmdLayout cursor" data-cmd_id="' . $cmd->getId() . '" style="margin:2px;">' . $cmd->getName() . '</span>';
                          }
                        }
                        $tr .= $string_cmd . '</div>';
                        $tr .= '<input class="eqLogicAttr form-control input-sm" data-l1key="display" data-l2key="layout::dashboard::table::parameters" data-l3key="text::td::' . $i . '::' . $j . '" placeholder="{{Texte de la cellule}}">';
                        $tr .= '<input class="eqLogicAttr form-control input-sm" data-l1key="display" data-l2key="layout::dashboard::table::parameters" data-l3key="style::td::' . $i . '::' . $j . '" placeholder="{{Style CSS ou attribut(s) HTML}}">';

                        $tr .= '</td>';
                      }
                      $tr .= '</tr>';
                      echo $tr;
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php }
  }
  ?>

  <!-- Commands -->
  <div id="panel_cmds" class="panel panel-default">
    <h3 class="panel-title">
      <a class="accordion-toggle" data-toggle="collapse" data-parent="" aria-expanded="false" href="#commands"><i class="fas fa-list-alt"></i> {{Commandes}}</a>
      <sup class="pull-right"><i class="fas fa-question-circle" title="{{Paramètres d'affichage des commandes.<br>En disposition standard, l'ordre des commandes est modifiable par glisser-déposer.}}"></i></sup>
    </h3>
    <div id="commands" class="panel-collapse collapse">
      <div class="panel-body">
        <div id="div_eqLogicCmds">
          <?php
          $display = '';
          foreach (($eqLogic->getCmd()) as $cmd) {
            $thisclassAttrib = 'cmdAttr' . $cmd->getId();
            $display .= '<div class="cmdConfig" style="padding: 2px;" data-attribclass="' . $thisclassAttrib . '" data-id="' . $cmd->getId() . '">';
            $display .= '<i class="fas fa-sort bt_sortable pull-right" style="margin-top: 5px; cursor: move !important;"></i>';
            $display .= '<span class="' . $thisclassAttrib . ' hidden" data-l1key="id"></span>';

            if ($cmd->getType() == 'info') {
              $display .= '<a class="btn btn-default btn-info btn-xs bt_cmdConfig" data-toggle="collapse" data-target="#cmdConfig' . $cmd->getId() . '">' . $cmd->getName() . ' (' . $cmd->getType() . ' | ' . $cmd->getSubType() . ')</a>';
            } else {
              $display .= '<a class="btn btn-default btn-warning btn-xs bt_cmdConfig" data-toggle="collapse" data-target="#cmdConfig' . $cmd->getId() . '">' . $cmd->getName() . ' (' . $cmd->getType() . ' | ' . $cmd->getSubType() . ')</a>';
            }

            $display .= '<div id="cmdConfig' . $cmd->getId() . '" class="collapse" style="margin-top: 8px;">';
            $display .= '<table class="table table-condensed">';

            //Editable name:
            $display .= '<tr><td>{{Nom}}</td><td colspan="2"><input class="input-sm ' . $thisclassAttrib . '" data-l1key="name" style="width: 100%;"></td>';

            //visible and td widths:
            $display .= '<tr><td>{{Visible}}<input type="checkbox" class="' . $thisclassAttrib . '" data-l1key="isVisible" style="float: right"></td>';

            foreach ((jeedom::getConfiguration('eqLogic:displayType')) as $key => $value) {
              $display .= '<td width="28%"><strong>' . $value['name'] . '</strong></td>';
            }

            //generic type:
            $display .= '<tr><td>{{Type générique}}</td>';
            $display .= '<td colspan="2"><select class="input-sm ' . $thisclassAttrib . '" data-l1key="generic_type">';
            $display .= $cmd->getGenericTypeSelectOptions();
            $display .= '</select></td></tr>';

            $display .= '<tr><td>{{Widget}}</td>';
            //select dashboard widget
            $display .= '<td class="widgetPossibilityDashboard" style="display: none;"><select class="input-sm ' . $thisclassAttrib . '" data-l1key="template" data-l2key="dashboard">';
            $display .= $cmd->getWidgetsSelectOptions('dashboard', $cmd_widgetDashboard);
            $display .= '</select></td>';

            //select mobile widget:
            $display .= '<td class="widgetPossibilityMobile" style="display: none;"><select class="input-sm ' . $thisclassAttrib . '" data-l1key="template" data-l2key="mobile">';
            $display .= $cmd->getWidgetsSelectOptions('mobile', $cmd_widgetMobile);
            $display .= '</select></td>';
            $display .= '</tr>';

            $display .= '<tr><td>{{Afficher le nom}}</td>';
            $display .= '<td><input type="checkbox" class="' . $thisclassAttrib . '" data-l1key="display" data-l2key="showNameOndashboard" checked></td>';
            $display .= '<td><input type="checkbox" class="' . $thisclassAttrib . '" data-l1key="display" data-l2key="showNameOnmobile" checked></td>';
            $display .= '</tr>';

            $display .= "<tr><td>{{Afficher le nom ET l'icône}}</td>";
            $display .= '<td><input type="checkbox" class="' . $thisclassAttrib . '" data-l1key="display" data-l2key="showIconAndNamedashboard"></td>';
            $display .= '<td><input type="checkbox" class="' . $thisclassAttrib . '" data-l1key="display" data-l2key="showIconAndNamemobile"></td>';
            $display .= '</tr>';

            if ($cmd->getType() == 'info' && $cmd->getSubType() != 'string') {
              $display .= '<tr><td>{{Afficher les statistiques}}</td>';
              $display .= '<td><input type="checkbox" class="' . $thisclassAttrib . '" data-l1key="display" data-l2key="showStatsOndashboard" checked></td>';
              $display .= '<td><input type="checkbox" class="' . $thisclassAttrib . '" data-l1key="display" data-l2key="showStatsOnmobile" checked></td>';
              $display .= '</tr>';
            }


            $display .= '<tr><td>{{Retour à la ligne avant}}</td>';
            $display .= '<td><input type="checkbox" class="' . $thisclassAttrib . '" data-l1key="display" data-l2key="forceReturnLineBefore" ></td></tr>';

            $display .= '<tr><td>{{Retour à la ligne après}}</td>';
            $display .= '<td><input type="checkbox" class="' . $thisclassAttrib . '" data-l1key="display" data-l2key="forceReturnLineAfter" ></td></tr>';

            $display .= '<tr><td>{{Paramètres optionnels}}</td>';
            $display .= '<td colspan="2"><a class="btn btn-xs btn-success addWidgetParametersCmd pull-right"><i class="fas fa-plus-circle"></i> {{Ajouter}}</a></td></tr>';

            $display .= '<tr><td colspan="3"><div class="optionalParamHelp ' . $thisclassAttrib . '"></div></td></tr>';

            if ($cmd->getDisplay('parameters') != '') {
              foreach (($cmd->getDisplay('parameters')) as $key => $value) {
                $display .= '<tr class="cmdoptparam text-center">';
                $display .= '<td colspan="3">';
                $display .= '<input class="key" value="' . $key . '" style="width:45%;">';
                $display .= ' <input class="value" value="' . htmlspecialchars($value, ENT_QUOTES) . '" style="width:45%;">';
                $display .= '<a class="btn btn-danger btn-xs pull-right removeWidgetParameter"><i class="fas fa-trash-alt"></i></a>';
                $display .= '</td>';
                $display .= '</tr>';
              }
            }

            $display .= '</table>';

            $display .= '</div>';
            $display .= '</div>';
          }
          echo $display;
          ?>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  if (!jeeFrontEnd.md_eqlogicDashEdit) {
    jeeFrontEnd.md_eqlogicDashEdit = {
      init: function(_cmdIds) {
        //display options:
        document.getElementById('md_eqlogicDashEdit').setJeeValues(jeephp2js.md_eqLogicDashEdit_eqInfo, '.eqLogicAttr')

        this.setModal()
      },
      postInit: function() {
        //layout default or table for cmd order:
        if (jeephp2js.md_eqLogicDashEdit_customLayout != '') {
          if (document.querySelector('select[data-l2key="layout::dashboard"]')?.value == 'default') {
            document.querySelector('input[data-l2key="layout::dashboard::table::nbLine"]').value = '1'
            document.querySelector('input[data-l2key="layout::dashboard::table::nbColumn"]').value = '1'
          }
          document.querySelector('select[data-l2key="layout::dashboard"]').triggerEvent('change')
        }

        document.querySelectorAll('#commands select[data-l1key="template"][data-l2key="dashboard"]').forEach(_tmplt => {
          jeeFrontEnd.md_eqlogicDashEdit.displayWidgetHelp(_tmplt.value, _tmplt.closest('.cmdConfig').getAttribute('data-id'))
        })

        this.setTableLayoutSortable()
        this.setCmdsSortable()
        jeedomUtils.initTooltips()
        jeedomUtils.initSpinners()
      },
      setModal: function() {
        var modal = document.getElementById('md_eqlogicDashEdit').closest('div.jeeDialogMain')
        if (window.innerWidth < 700) modal.style.width = '80vw'
        if (window.innerHeight < 700) modal.style.height = '60vh'

        //modal title:
        var title = "{{Configuration de la tuile}}"
        title += ' : ' + jeephp2js.md_eqLogicDashEdit_eqInfo.name
        title += ' <span class="cmdName"><em>(' + jeephp2js.md_eqLogicDashEdit_eqInfo.eqType_name + ')</em></span>'
        modal.querySelector('div.jeeDialogTitle > span.title').innerHTML = title

        var panelCmds = document.getElementById('panel_cmds')
        var id, cmdInfo, dashWidget, mobileWidget
        panelCmds.querySelectorAll('.cmdConfig').forEach(function(element) {
          var id = element.dataset.id
          var cmdInfo = jeephp2js.md_eqLogicDashEdit_allCmdsInfo[id]
          if (cmdInfo.widgetPossibilityDashboard == true) element.querySelector('.widgetPossibilityDashboard').seen()
          if (cmdInfo.widgetPossibilityMobile == true) element.querySelector('.widgetPossibilityMobile').seen()
          panelCmds.setJeeValues(cmdInfo, '.cmdAttr' + id)

          //widgets default if empty:
          var dashWidget = element.querySelector('select[data-l2key="dashboard"]')
          if (dashWidget.value == '') dashWidget.selectedIndex = 0
          var mobileWidget = element.querySelector('select[data-l2key="mobile"]')
          if (mobileWidget.value == '') mobileWidget.selectedIndex = 0
        })
      },
      displayWidgetHelp: function(_widgetName, _cmdId) {
        jeedom.cmd.getWidgetHelp({
          id: _cmdId,
          version: 'dashboard',
          widgetName: _widgetName,
          error: function(error) {
            document.querySelector('#md_eqlogicDashEdit #commands div.optionalParamHelp.cmdAttr' + _cmdId).empty().textContent = '{{Pas de description des paramètres optionnels sur ce Widget}}'
          },
          success: function(data) {
            document.querySelector('#md_eqlogicDashEdit #commands div.optionalParamHelp.cmdAttr' + _cmdId).empty().innerHTML = data.html
          }
        })
      },
      setCmdsSortable: function() {
        Sortable.create(document.getElementById('div_eqLogicCmds'), {
          delay: 100,
          delayOnTouchOnly: true,
          draggable: 'div.cmdConfig',
          filter: 'a, input, textarea, table',
          preventOnFilter: false,
          direction: 'vertical',
          removeCloneOnHide: true,
          chosenClass: 'dragSelected',
        })
      },
      setTableLayoutSortable: function() {
        if (jeephp2js.md_eqLogicDashEdit_customLayout == '') return
        let containers = document.querySelectorAll('#md_eqlogicDashEdit #tableCmdLayoutConfiguration tbody td .cmdLayoutContainer')
        containers.forEach(_container => {
          new Sortable(_container, {
            delay: 100,
            delayOnTouchOnly: true,
            group: 'cmdLayoutContainer',
            draggable: '.cmdLayout',
            filter: 'a, input, textarea',
            preventOnFilter: false,
          })
        })
      },
      getNewLayoutTd: function(row, col) {
        if (jeephp2js.md_eqLogicDashEdit_customLayout == '') return
        var newTd = '<td data-line="' + row + '" data-column="' + col + '">'
        newTd += '<center class="cmdLayoutContainer"></center>'
        newTd += '<input class="eqLogicAttr form-control input-sm" data-l1key="display" data-l2key="layout::dashboard::table::parameters" data-l3key="text::td::' + row + '::' + col + '" placeholder="{{Texte de la cellule}}">'
        newTd += '<input class="eqLogicAttr form-control input-sm" data-l1key="display" data-l2key="layout::dashboard::table::parameters" data-l3key="style::td::' + row + '::' + col + '" placeholder="{{Style CSS ou attribut(s) HTML}}">'
        newTd += '</td>'
        return newTd
      },
      applyTableLayout: function() {
        if (jeephp2js.md_eqLogicDashEdit_customLayout == '') return
        var nbColumn = document.querySelector('#md_eqlogicDashEdit input[data-l2key="layout::dashboard::table::nbColumn"]').value
        var nbRow = document.querySelector('#md_eqlogicDashEdit input[data-l2key="layout::dashboard::table::nbLine"]').value

        var tableLayout = document.getElementById('tableCmdLayoutConfiguration')
        var tableRowCount = tableLayout.querySelectorAll('tr').length
        var tableColumnCount = tableLayout.querySelector('tr').querySelectorAll('td').length

        if (nbColumn != tableColumnCount || nbRow != tableRowCount) {
          //build new table:
          var newTableLayout = document.createElement('table')
          newTableLayout.addClass('table table-condensed')
          newTableLayout.setAttribute('id', 'tableCmdLayoutConfiguration')
          newTableLayout.appendChild(document.createElement('tbody'))

          for (i = 1; i <= nbRow; i++) {
            var newTr = document.createElement('tr')
            for (j = 1; j <= nbColumn; j++) {
              newTd = jeeFrontEnd.md_eqlogicDashEdit.getNewLayoutTd(i, j)
              newTr.insertAdjacentHTML('beforeend', newTd)
            }
            newTableLayout.tBodies[0].appendChild(newTr)
          }

          //distribute back cmds into new table
          var firstTdLayout = newTableLayout.querySelector('tr').querySelector('td > .cmdLayoutContainer')
          var row, col, newTd, text, style
          tableLayout.querySelectorAll('.cmdLayout').forEach(_cLay => {
            row = _cLay.closest('td').getAttribute('data-line')
            col = _cLay.closest('td').getAttribute('data-column')
            newTd = newTableLayout.querySelector('td[data-line="' + row + '"][data-column="' + col + '"]')
            if (newTd) {
              newTd.querySelector('.cmdLayoutContainer').appendChild(_cLay)
            } else {
              firstTdLayout.appendChild(_cLay)
            }
          })

          //get back tds texts and styles
          tableLayout.querySelectorAll('td').forEach(_td => {
            row = _td.getAttribute('data-line')
            col = _td.getAttribute('data-column')
            text = _td.querySelector('input[data-l3key="text::td::' + row + '::' + col + '"]').value
            style = _td.querySelector('input[data-l3key="style::td::' + row + '::' + col + '"]').value
            newTd = newTableLayout.querySelector('td[data-line="' + row + '"][data-column="' + col + '"]')
            if (newTd) {
              newTableLayout.querySelector('input[data-l3key="text::td::' + row + '::' + col + '"]').value = text
              newTableLayout.querySelector('input[data-l3key="style::td::' + row + '::' + col + '"]').value = style
            }
          })

          //replace by new table:
          tableLayout.replaceWith(newTableLayout)
          document.querySelectorAll('#tableCmdLayoutConfiguration td').forEach(td => {
            td.style.width = 100 / nbColumn + '%'
          })
          jeeFrontEnd.md_eqlogicDashEdit.setTableLayoutSortable()
        }
      },
      eqlogicSave: function() {
        //get eqLogic:
        var eqLogic = document.getElementById('md_eqlogicDashEdit').getJeeValues('.eqLogicAttr')[0]
        if (!isset(eqLogic.display)) eqLogic.display = {}
        if (!isset(eqLogic.display.parameters)) eqLogic.display.parameters = {}
        //tile optionnal parameters:
        document.querySelectorAll('#table_widgetParameters tbody tr').forEach(_tr => {
          eqLogic.display.parameters[_tr.querySelector('.key').jeeValue()] = _tr.querySelector('.value').jeeValue()
        })

        //get cmds:
        eqLogic.cmd = []
        var cmd, attribClass
        document.querySelectorAll('#div_eqLogicCmds .cmdConfig').forEach(_cmdCf => {
          attribClass = _cmdCf.dataset.attribclass
          cmd = _cmdCf.getJeeValues('.' + attribClass)[0]
          if (!isset(cmd.display)) cmd.display = {}
          if (!isset(cmd.display.parameters)) cmd.display.parameters = {}
          //cmd optionnal parameters:
          _cmdCf.querySelectorAll('tr.cmdoptparam').forEach(_opt => {
            cmd.display.parameters[_opt.querySelector('.key').jeeValue()] = _opt.querySelector('.value').jeeValue()
          })
          eqLogic.cmd.push(cmd)
        })

        jeedom.eqLogic.save({
          eqLogics: [eqLogic],
          type: jeephp2js.md_eqLogicDashEdit_eqInfo.eqType_name,
          error: function(error) {
            jeedomUtils.showAlert({
              message: error.message,
              level: 'danger'
            })
          },
          success: function() {
            //save commands order, dependings on default/table setting:
            var cmds = []
            var order = 1
            if (jeephp2js.md_eqLogicDashEdit_customLayout != '' && document.querySelector('select[data-l2key="layout::dashboard"]').value != 'default') {
              document.querySelectorAll('#tableCmdLayoutConfiguration tbody td .cmdLayout').forEach(_lay => {
                cmd = {}
                cmd.id = _lay.getAttribute('data-cmd_id')
                cmd.line = _lay.closest('td').getAttribute('data-line')
                cmd.column = _lay.closest('td').getAttribute('data-column')
                cmd.order = order
                cmds.push(cmd)
                order++
              })
            } else {
              document.querySelectorAll('#div_eqLogicCmds .cmdConfig').forEach(_cmd => {
                cmd = {}
                cmd.id = _cmd.getAttribute('data-id')
                cmd.order = order
                cmds.push(cmd)
                order++
              })
            }

            for (cmd of cmds) {
              delete jeedom.cmd.update[cmd.id]
            }

            jeedom.cmd.setOrder({
              version: 'dashboard',
              cmds: cmds,
              error: function(error) {
                jeedomUtils.showAlert({
                  message: error.message,
                  level: 'danger'
                })
              },
              success: function() {}
            })

            //saving will update the eqLogic tile, set event once:
            document.body.addEventListener('eqLogic::update', function(_event, _options) {
              // console.log('eqLogic::update', _event, _options)
              let found = false;
              if (_event.detail) {
                for (let i in _event.detail) {
                  if (_event.detail[i].eqLogic_id && eqLogic.id == _event.detail[i].eqLogic_id) {
                    found = true
                  }
                }
              }
              if (!found) {
                return;
              }
              document.body.removeEventListener('eqLogic::update', arguments.callee);
              if (document.body.getAttribute('data-page') == 'dashboard') {
                setTimeout(function() {
                  jeeFrontEnd.dashboard.editWidgetMode(0, false)
                  jeeFrontEnd.dashboard.editWidgetMode(1, false)
                }, 50)
              }
              if (document.body.getAttribute('data-page') == 'view') {
                setTimeout(function() {
                  jeeFrontEnd.view.editWidgetMode(0, false)
                  jeeFrontEnd.view.editWidgetMode(1, false)
                }, 50)
              }
            })
          }
        })
      },
    }
  }

  (function() { // Self Isolation!
    var jeeM = jeeFrontEnd.md_eqlogicDashEdit
    jeeM.init()

    /*Events delegations
     */
    document.getElementById('md_eqlogicDashEdit')?.addEventListener('click', function(event) {
      var _target = null
      if (_target = event.target.closest('#bt_eqLogicLayoutApply')) {
        jeeFrontEnd.md_eqlogicDashEdit.applyTableLayout()
        return
      }

      if (_target = event.target.closest('#bt_addTileParameters')) {
        document.getElementById('optParams').addClass('in')
        var tr = '<tr>'
        tr += '<td>'
        tr += '<input class="form-control input-sm key">'
        tr += '</td>'
        tr += '<td>'
        tr += '<input class="form-control input-sm value">'
        tr += '</td>'
        tr += '<td class="text-right">'
        tr += '<a class="btn btn-danger btn-xs removeTileParameter"><i class="far fa-trash-alt"></i></a>'
        tr += '</td>'
        tr += '</tr>'
        document.getElementById('table_widgetParameters').tBodies[0].insertAdjacentHTML('beforeend', tr)
        return
      }

      if (_target = event.target.closest('.removeTileParameter')) {
        _target.closest('tr').remove()
        return
      }

      if (_target = event.target.closest('.removeWidgetParameter')) {
        _target.closest('tr').remove()
        return
      }

      if (_target = event.target.closest('.addWidgetParametersCmd')) {
        var tr = '<tr class="cmdoptparam text-center">'
        tr += '<td colspan="3">'
        tr += '<input class="key" style="width:45%;">'
        tr += ' <input class="value" style="width:45%;">'
        tr += '<a class="btn btn-danger btn-xs removeWidgetParameter pull-right"><i class="fas fa-trash-alt"></i></a>'
        tr += '</td>'
        tr += '</tr>'
        _target.closest('.collapse').querySelector('table').insertAdjacentHTML('beforeend', tr)
        return
      }

      if (_target = event.target.closest('a.accordion-toggle')) {
        document.getElementById('md_eqlogicDashEdit').querySelectorAll('div.panel-collapse').removeClass('in')
        return
      }
    })

    document.getElementById('md_eqlogicDashEdit')?.addEventListener('change', function(event) {
      var _target = null
      if (_target = event.target.closest('.sel_layout')) {
        document.querySelectorAll('.widget_layout').unseen()
        document.querySelectorAll('.widget_layout.' + _target.jeeValue()).seen()
        return
      }

      if (_target = event.target.closest('select[data-l1key="template"][data-l2key="dashboard"]')) {
        jeeFrontEnd.md_eqlogicDashEdit.displayWidgetHelp(_target.value, _target.closest('.cmdConfig').getAttribute('data-id'))
        return
      }

      if (_target = event.target.closest('.advanceWidgetParameterDefault')) {
        if (_target.jeeValue() == 1) {
          _target.closest('td').querySelectorAll('.advanceWidgetParameter').unseen()
        } else {
          _target.closest('td').querySelectorAll('.advanceWidgetParameter').seen()
        }
        return
      }

      if (_target = event.target.closest('.advanceWidgetParameterColorTransparent')) {
        if (_target.jeeValue() == 1) {
          _target.closest('td').querySelectorAll('.advanceWidgetParameterColor').unseen()
        } else {
          _target.closest('td').querySelectorAll('.advanceWidgetParameterColor').seen()
        }
        return
      }
    })

    jeeM.postInit()
  })()
</script>
