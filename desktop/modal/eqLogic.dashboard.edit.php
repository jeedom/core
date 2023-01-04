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
  'jeephp2js.md_eqLogicDashEdit_eqWidgetPossibility' => $eqLogic->widgetPossibility('custom::optionalParameters'),
  'jeephp2js.md_eqLogicDashEdit_allCmdsInfo' => $allCmds
]);

$cmd_widgetDashboard = cmd::availableWidget('dashboard');
$cmd_widgetMobile = cmd::availableWidget('mobile');
?>

<div id="div_displayEqLogicConfigure">
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
                  <input type="number" class="eqLogicAttr form-control input-sm ui-spinner" value="" data-l1key="display" data-l2key="backGraph::height" placeholder="{{Automatique}}">
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
            <table class="table table-bordered table-condensed">
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
                    $echo .= $parameter['name'];
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
            <table class="table table-bordered table-condensed" id="table_widgetParameters">
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
                    $echo .= '<input class="form-control input-sm value" value="' . $value . '">';
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
        }
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
                    <input type="number" min="1" max="20" step="1" class="eqLogicAttr form-control input-sm ui-spinner" data-l1key="display" data-l2key="layout::dashboard::table::nbLine">
                  </div>
                  <label class="col-sm-2 control-label">{{Colonnes}}</label>
                  <div class="col-sm-4">
                    <input type="number" min="1" max="20" step="1" class="eqLogicAttr form-control input-sm ui-spinner" data-l1key="display" data-l2key="layout::dashboard::table::nbColumn">
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
                <table class="table table-bordered table-condensed table-responsive" id="tableCmdLayoutConfiguration">
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
              $display .= '<span class="' . $thisclassAttrib . ' hidden" data-l1key="id"></span>';

              if ($cmd->getType() == 'info') {
                $display .= '<a class="btn btn-default btn-info btn-xs bt_cmdConfig" data-toggle="collapse" data-target="#cmdConfig' . $cmd->getId() . '">' . $cmd->getName() . ' (' . $cmd->getType() . ' | ' . $cmd->getSubType() . ')</a>';
              } else {
                $display .= '<a class="btn btn-default btn-warning btn-xs bt_cmdConfig" data-toggle="collapse" data-target="#cmdConfig' . $cmd->getId() . '">' . $cmd->getName() . ' (' . $cmd->getType() . ' | ' . $cmd->getSubType() . ')</a>';
              }

              $display .= '<div id="cmdConfig' . $cmd->getId() . '" class="collapse" style="margin-top: 8px;">';
              $display .= '<table class="table table-bordered table-condensed">';

              //Editable name:
              $display .= '<tr><td>{{Name}}</td><td colspan="2"><input class="input-sm ' . $thisclassAttrib . '" data-l1key="name" style="width: 100%;"></td>';

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
                  $display .= ' <input class="value" value="' . $value . '" style="width:45%;">';
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
  var modal = $('#md_modal').parents('.ui-dialog.ui-resizable')
  var modal = document.getElementById('div_displayEqLogicConfigure').closest('div.jeeDialogMain')

  //modal title:
  var title = "{{Configuration de la tuile}}"
  title += ' : ' + jeephp2js.md_eqLogicDashEdit_eqInfo.name
  title += ' <span class="cmdName"><em>(' + jeephp2js.md_eqLogicDashEdit_eqInfo.eqType_name + ')</em></span>'
  modal.querySelector('div.jeeDialogTitle > span.title').innerHTML = title

  //display options:
  document.getElementById('div_displayEqLogicConfigure').setJeeValues(jeephp2js.md_eqLogicDashEdit_eqInfo, '.eqLogicAttr')

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

  setTableLayoutSortable()
  jeedomUtils.initTooltips()
  jeedomUtils.initSpinners()

  //layout default or table for cmd order:
  if ($('.sel_layout').val() == 'default') {
    $('input[data-l2key="layout::dashboard::table::nbLine"], input[data-l2key="layout::dashboard::table::nbColumn"]').val(1)
  }

  /* Equipement */
  function setTableLayoutSortable() {
    $('#tableCmdLayoutConfiguration tbody td .cmdLayoutContainer').sortable({
      connectWith: '#tableCmdLayoutConfiguration tbody td .cmdLayoutContainer',
      items: ".cmdLayout"
    })
  }

  function getNewLayoutTd(row, col) {
    var newTd = '<td data-line="' + row + '" data-column="' + col + '">'
    newTd += '<center class="cmdLayoutContainer"></center>'
    newTd += '<input class="eqLogicAttr form-control input-sm" data-l1key="display" data-l2key="layout::dashboard::table::parameters" data-l3key="text::td::' + row + '::' + col + '" placeholder="{{Texte de la cellule}}">'
    newTd += '<input class="eqLogicAttr form-control input-sm" data-l1key="display" data-l2key="layout::dashboard::table::parameters" data-l3key="style::td::' + row + '::' + col + '" placeholder="{{Style CSS ou attribut(s) HTML}}">'
    newTd += '</td>'
    return newTd
  }

  function applyTableLayout() {
    var nbColumn = $('input[data-l2key="layout::dashboard::table::nbColumn"]').val()
    var nbRow = $('input[data-l2key="layout::dashboard::table::nbLine"]').val()

    var tableLayout = $('#tableCmdLayoutConfiguration')
    var tableRowCount = tableLayout.find('tr').length
    var tableColumnCount = tableLayout.find('tr').eq(0).find('td').length

    if (nbColumn != tableColumnCount || nbRow != tableRowCount) {
      //build new table:
      var newTableLayout = '<table class="table table-bordered table-condensed" id="tableCmdLayoutConfiguration">'
      newTableLayout += '<tbody>'

      for (i = 1; i <= nbRow; i++) {
        var newTr = '<tr>'
        for (j = 1; j <= nbColumn; j++) {
          newTd = getNewLayoutTd(i, j)
          newTr += newTd
        }
        newTr += '</tr>'
        newTableLayout += newTr
      }
      newTableLayout += '</tbody>'
      newTableLayout += '</table>'

      newTableLayout = $.parseHTML(newTableLayout)

      //distribute back cmds into new table
      var firstTdLayout = $(newTableLayout).find('tr').eq(0).find('td').eq(0).find('.cmdLayoutContainer')
      var row, col, newTd, text, style
      tableLayout.find('.cmdLayout').each(function() {
        row = $(this).closest('td').data('line')
        col = $(this).closest('td').data('column')
        newTd = $(newTableLayout).find('td[data-line="' + row + '"][data-column="' + col + '"]')
        if (newTd.length) {
          $(this).appendTo(newTd.find('.cmdLayoutContainer'))
        } else {
          $(this).appendTo(firstTdLayout)
        }
      })

      //get back tds texts and styles
      tableLayout.find('td').each(function() {
        row = $(this).data('line')
        col = $(this).data('column')
        text = $(this).find('input[data-l3key="text::td::' + row + '::' + col + '"]').val()
        style = $(this).find('input[data-l3key="style::td::' + row + '::' + col + '"]').val()
        newTd = $(newTableLayout).find('td[data-line="' + row + '"][data-column="' + col + '"]')
        if (newTd.length) {
          $(newTableLayout).find('input[data-l3key="text::td::' + row + '::' + col + '"]').val(text)
          $(newTableLayout).find('input[data-l3key="style::td::' + row + '::' + col + '"]').val(style)
        }
      })

      //replace by new table:
      tableLayout.replaceWith(newTableLayout)
      $('#tableCmdLayoutConfiguration td').css('width', 100 / nbColumn + '%')
      setTableLayoutSortable()
    }
  }

  $('#bt_eqLogicLayoutApply').off().on('click', function() {
    applyTableLayout()
  })

  $('#bt_addTileParameters').off().on('click', function() {
    $('#panel_optParams').find('.collapse').collapse('show')
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
    $('#table_widgetParameters tbody').append(tr)
  })

  $('#table_widgetParameters').on('click', '.removeTileParameter', function() {
    $(this).closest('tr').remove()
  })

  $('.sel_layout').on('change', function() {
    var type = this.getAttribute('data-type')
    document.querySelectorAll('.widget_layout').unseen()
    document.querySelectorAll('.widget_layout.' + this.jeeValue()).seen()
  })

  /* commandes */
  $('#panel_cmds').on('click', '.removeWidgetParameter', function() {
    $(this).closest('tr').remove()
  })

  $('#commands select[data-l1key="template"][data-l2key="dashboard"]').each(function() {
    displayWidgetHelp($(this).val(), $(this).closest('.cmdConfig').attr('data-id'))
  })

  $('#commands select[data-l1key="template"][data-l2key="dashboard"]').off('change').on('change', function() {
    displayWidgetHelp($(this).val(), $(this).closest('.cmdConfig').attr('data-id'))
  })

  function displayWidgetHelp(widgetName, cmdId) {
    jeedom.cmd.getWidgetHelp({
      id: cmdId,
      version: 'dashboard',
      widgetName: widgetName,
      error: function(error) {
        $('#commands div.optionalParamHelp.cmdAttr' + cmdId).empty().text('{{Pas de description des paramètres optionnels sur ce Widget}}')
      },
      success: function(data) {
        $('#commands div.optionalParamHelp.cmdAttr' + cmdId).empty().html(data.html)
      }
    })
  }

  $('.addWidgetParametersCmd').on('click', function() {
    var tr = '<tr class="cmdoptparam text-center">'
    tr += '<td colspan="3">'
    tr += '<input class="key" style="width:45%;">'
    tr += ' <input class="value" style="width:45%;">'
    tr += '<a class="btn btn-danger btn-xs removeWidgetParameter pull-right"><i class="fas fa-trash-alt"></i></a>'
    tr += '</td>'
    tr += '</tr>'
    $(this).closest('.collapse').find('table').append(tr)
  })

  $('.advanceWidgetParameterDefault').off('change').on('change', function() {
    if (this.jeeValue() == 1) {
      this.closest('td').querySelector('.advanceWidgetParameter').unseen()
    } else {
      this.closest('td').querySelector('.advanceWidgetParameter').seen()
    }
  })

  $('.advanceWidgetParameterColorTransparent').off('change').on('change', function() {
    if (this.jeeValue() == 1) {
      this.closest('td').querySelector('.advanceWidgetParameterColor').unseen()
    } else {
      this.closest('td').querySelector('.advanceWidgetParameterColor').seen()
    }
  })

  $("#div_eqLogicCmds").sortable({
    axis: "y",
    cursor: "move",
    items: "div.cmdConfig",
    zIndex: 0,
    tolerance: "pointer",
    forceHelperSize: true,
    forcePlaceholderSize: true,
    placeholder: "sortable-cmd-placeholder",
    start: function(event, ui) {
      ui.placeholder[0].style.setProperty('height', ui.item[0].innerHeight, 'important')
    }
  })

  /* modal */
  $('.accordion-toggle').click(function() {
    $('.collapse').collapse('hide')
  })

  $('.sel_layout').trigger('change')
  function editSaveEqlogic() {
    //get eqLogic:
    var eqLogic = document.getElementById('div_displayEqLogicConfigure').getJeeValues('.eqLogicAttr')[0]
    if (!isset(eqLogic.display)) eqLogic.display = {}
    if (!isset(eqLogic.display.parameters)) eqLogic.display.parameters = {}
    //tile optionnal parameters:
    document.querySelectorAll('#table_widgetParameters tbody tr').forEach(function(element) {
      eqLogic.display.parameters[element.querySelector('.key').jeeValue()] = element.querySelector('.value').jeeValue()
    })

    //get cmds:
    eqLogic.cmd = []
    var cmd, attribClass
    $('#div_eqLogicCmds .cmdConfig').each(function() {
      attribClass = $(this).data('attribclass')
      cmd = this.getJeeValues('.' + attribClass)[0]
      if (!isset(cmd.display)) cmd.display = {}
      if (!isset(cmd.display.parameters)) cmd.display.parameters = {}
      //cmd optionnal parameters:
      this.querySelectorAll('tr.cmdoptparam').forEach(function(element) {
        cmd.display.parameters[element.querySelector('.key').jeeValue()] = element.querySelector('.value').jeeValue()
      })
      eqLogic.cmd.push(cmd);
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
        if ($('.sel_layout').val() != 'default') {
          $('#tableCmdLayoutConfiguration tbody td').find('.cmdLayout').each(function() {
            cmd = {}
            cmd.id = $(this).attr('data-cmd_id')
            cmd.line = $(this).closest('td').attr('data-line')
            cmd.column = $(this).closest('td').attr('data-column')
            cmd.order = order
            cmds.push(cmd)
            order++
          })
        } else {
          $('#div_eqLogicCmds .cmdConfig').each(function() {
            cmd = {}
            cmd.id = $(this).attr('data-id')
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
            $('#md_displayEqLogicConfigure').showAlert({
              message: error.message,
              level: 'danger'
            })
          },
          success: function() {}
        })

        $('body').one('eqLogic::update', function(_event, _options) {
          if (document.body.getAttribute('data-page' == 'dashboard')) {
            setTimeout(function() {
              jeeFrontEnd.dashboard.editWidgetMode(0, false)
              jeeFrontEnd.dashboard.editWidgetMode(1, false)
            }, 250)
          }
          if (document.body.getAttribute('data-page' == 'view')) {
            setTimeout(function() {
              jeeFrontEnd.view.editWidgetMode(0, false)
              jeeFrontEnd.view.editWidgetMode(1, false)
            }, 250)
          }

        })
      }
    })
  }
</script>
