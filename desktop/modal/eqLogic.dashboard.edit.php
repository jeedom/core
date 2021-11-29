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
$eqLogic = eqLogic::byId(init('eqLogic_id'));
if (!is_object($eqLogic)) {
  throw new Exception('{{EqLogic non trouvé :}}' . ' ' . init('eqLogic_id'));
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
  'eqLogicInfo' => utils::o2a($eqLogic),
  'eqLogicWidgetPossibility' => $eqLogic->widgetPossibility('custom::optionalParameters'),
  'allCmdsInfo' => $allCmds
]);

$cmd_widgetDashboard = cmd::availableWidget('dashboard');
$cmd_widgetMobile = cmd::availableWidget('mobile');
?>

<div style="display: none;" id="md_displayEqLogicConfigure"></div>
<div id="div_displayEqLogicConfigure">
  <div style="margin: 0 -13px; overflow: hidden;">
    <!-- Global parameters -->
    <span class="eqLogicAttr hidden" data-l1key="id"></span>
    <span class="eqLogicAttr hidden" data-l1key="eqType_name"></span>
    <form class="form-horizontal">
      <fieldset>
        <div class="form-group">
          <label class="col-sm-2 control-label">{{Nom}}</label>
          <div class="col-sm-4">
            <input type="text" class=" eqLogicAttr form-control" data-l1key="name">
          </div>
          <label class="col-sm-2 control-label">{{Visible}}</label>
          <div class="col-sm-1">
            <input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>
          </div>
        </div>
      </fieldset>
    </form>
    <br/>

    <!-- Display -->
    <div id="panel_optParams" class="panel panel-default" style="display:none">
      <div class="panel-heading">
        <h3 class="panel-title">
          <a class="accordion-toggle" data-toggle="collapse" data-parent="" aria-expanded="false" href="#optParams">{{Affichage}}</a>
        </h3>
      </div>
      <div id="optParams" class="panel-collapse collapse">
        <div class="panel-body">
        <form class="form-horizontal">
          <label><i class="fas fa-chart-line"></i> {{Graphique de fond}}</label>
          <fieldset>
            <div class="form-group">
              <label class="col-sm-2 control-label">{{Information}}</label>
              <div class="col-sm-4">
                <select class="eqLogicAttr form-control sel_backGraphInfo" data-l1key="display" data-l2key="backGraph::info">
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

              <label class="col-sm-2 control-label">{{Format}}</label>
              <div class="col-sm-4">
                <select class="eqLogicAttr form-control" data-l1key="display" data-l2key="backGraph::format">
                  <option value="month">{{Mois}}</option>
                  <option value="week">{{Semaine}}</option>
                  <option value="day">{{Jour}}</option>
                  <option value="hour">{{Heure}}</option>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label">{{Type}}</label>
              <div class="col-sm-4">
                <select class="eqLogicAttr form-control" data-l1key="display" data-l2key="backGraph::type">
                  <option value="areaspline">{{Courbe}}</option>
                  <option value="area">{{Escalier}}</option>
                  <option value="column">{{Barre}}</option>
                </select>
              </div>

              <label class="col-sm-2 control-label">{{Couleur}}</label>
              <div class="col-sm-4">
                <input type="color" class="eqLogicAttr html form-control input-sm" value="#4572A7" data-l1key="display" data-l2key="backGraph::color"/>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-8 control-label">{{Hauteur fixe (px)}}</label>
              <div class="col-sm-4">
                <input class="eqLogicAttr html form-control input-sm" value="" data-l1key="display" data-l2key="backGraph::height"/>
              </div>
            </div>
          </fieldset>
        </form>
        <br/>

        <span>
          <label><i class="fas fa-edit"></i></i> {{Paramètres optionnels sur la tuile}}</label>
          <a class="btn btn-success btn-xs pull-right" id="bt_addTileParameters"><i class="fas fa-plus-circle"></i> {{Ajouter}}</a>
        </span>
        <table class="table table-bordered table-condensed" id="table_widgetParameters">
          <thead>
            <tr>
              <th>{{Nom}}</th>
              <th>{{Valeur}}</th>
              <th>{{Action}}</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if ($eqLogic->getDisplay('parameters') != '') {
              $echo = '';
              foreach (($eqLogic->getDisplay('parameters')) as $key => $value) {
                $echo .= '<tr>';
                $echo .= '<td>';
                $echo .= '<input class="form-control key" value="' . $key . '" />';
                $echo .= '</td>';
                $echo .= '<td>';
                $echo .= '<input class="form-control value" value="' . $value . '" />';
                $echo .= '</td>';
                $echo .= '<td>';
                $echo .= '<a class="btn btn-danger btn-xs removeTileParameter"><i class="fas fa-times"></i> {{Supprimer}}</a>';
                $echo .= '</td>';
                $echo .= '</tr>';
              }
              echo $echo;
            }
            ?>
          </tbody>
        </table>
        </div>
      </div>
    </div>

    <!-- Layout -->
    <div id="panel_layout" class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">
          <a class="accordion-toggle" data-toggle="collapse" data-parent="" aria-expanded="false" href="#layout">{{Disposition}}</a>
          <sup class="pull-right"><i class="fas fa-question-circle" title="{{Disposition des widgets en mode Standard ou Tableau.<br>Edition du tableau et de la disposition des commandes.}}"></i></sup>
        </h3>
      </div>
      <div id="layout" class="panel-collapse collapse">
        <div class="panel-body">
          <form class="form-horizontal">
            <fieldset>
              <div class="form-group">
                <label class="col-sm-2 control-label">{{Disposition}}</label>
                <div class="col-sm-4">
                  <select class="eqLogicAttr form-control sel_layout" data-l1key="display" data-l2key="layout::dashboard">
                    <option value="default">{{Défaut}}</option>
                    <option value="table">{{Tableau}}</option>
                  </select>
                </div>
              </div>
              <div class="widget_layout table" style="display: none;">
                <div class="form-group">
                  <label class="col-sm-2 control-label">{{Lignes}}</label>
                  <div class="col-sm-2">
                    <input type="number" min="1" max="20" step="1" class="eqLogicAttr form-control input-sm" data-l1key="display" data-l2key="layout::dashboard::table::nbLine" />
                  </div>
                  <label class="col-sm-2 control-label">{{Colonnes}}</label>
                  <div class="col-sm-2">
                    <input type="number" min="1" max="20" step="1" class="eqLogicAttr form-control input-sm" data-l1key="display" data-l2key="layout::dashboard::table::nbColumn" />
                  </div>
                  <a class="btn btn-success btn-sm" id="bt_eqLogicLayoutApply"><i class="fas fa-hammer"></i></i> {{Appliquer}}</a>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 control-label">{{Centrer dans les cases}}</label>
                  <div class="col-sm-2">
                    <input type="checkbox" class="eqLogicAttr" data-l1key="display" data-l2key="layout::dashboard::table::parameters" data-l3key="center" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 control-label">{{Style général des cases (CSS)}}</label>
                  <div class="col-sm-8">
                    <input class="eqLogicAttr form-control" data-l1key="display" data-l2key="layout::dashboard::table::parameters" data-l3key="styletd" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 control-label">{{Style du tableau (CSS)}}</label>
                  <div class="col-sm-8">
                    <input class="eqLogicAttr form-control" data-l1key="display" data-l2key="layout::dashboard::table::parameters" data-l3key="styletable" />
                  </div>
                </div>
              </div>
            </fieldset>
          </form>
          <div class="widget_layout table" style="display: none;">
            <table class="table table-bordered table-condensed" id="tableCmdLayoutConfiguration">
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
                    $string_cmd = '<center class="cmdLayoutContainer" style="min-height:30px;">';
                    if (isset($table[$i][$j]) && count($table[$i][$j]) > 0) {
                      foreach ($table[$i][$j] as $cmd) {
                        $string_cmd .= '<span class="label label-default cmdLayout cursor" data-cmd_id="' . $cmd->getId() . '" style="margin:2px;">' . $cmd->getName() . '</span>';
                      }
                    }
                    $tr .= $string_cmd . '</center>';
                    $tr .= '<input class="eqLogicAttr form-control input-sm" data-l1key="display" data-l2key="layout::dashboard::table::parameters" data-l3key="text::td::' . $i . '::' . $j . '" placeholder="{{Texte de la case}}" style="margin-top:3px;"/>';
                    $tr .= '<input class="eqLogicAttr form-control input-sm" data-l1key="display" data-l2key="layout::dashboard::table::parameters" data-l3key="style::td::' . $i . '::' . $j . '" placeholder="{{Style de la case (CSS)}}" style="margin-top:3px;"/>';

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

    <!-- Commands -->
    <div id="panel_cmds" class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">
          <a class="accordion-toggle" data-toggle="collapse" data-parent="" aria-expanded="false" href="#commands">{{Commandes}}</a>
          <sup class="pull-right"><i class="fas fa-question-circle" title="{{Paramètres d'affichage de chaque commande.<br>Pour l'affichage en disposition standard, l'ordre des commandes est modifiable par glisser-déposer.}}"></i></sup>
        </h3>
      </div>
      <div id="commands" class="panel-collapse collapse">
        <div class="panel-body">
          <div id="div_eqLogicCmds">
            <?php
            $display = '';
            foreach (($eqLogic->getCmd()) as $cmd) {
              $thisclassAttrib = 'cmdAttr'.$cmd->getId();
              $display .= '<div class="cmdConfig" style="padding: 2px;" data-attribclass="'.$thisclassAttrib.'" data-id="' . $cmd->getId() . '">';
              $display .= '<span class="'.$thisclassAttrib.' hidden" data-l1key="id"></span>';
              $display .= '<span class="'.$thisclassAttrib.' hidden" data-l1key="name"></span>';

              if ($cmd->getType() == 'info') {
                $display .= '<a class="btn btn-default btn-info btn-xs cursor bt_cmdConfig" data-toggle="collapse" data-target="#cmdConfig' . $cmd->getId() . '">'.$cmd->getName().' ('.$cmd->getType().' | '.$cmd->getSubType().')</a>';
              } else {
                $display .= '<a class="btn btn-default btn-warning btn-xs cursor bt_cmdConfig" data-toggle="collapse" data-target="#cmdConfig' . $cmd->getId() . '">'.$cmd->getName().' ('.$cmd->getType().' | '.$cmd->getSubType().')</a>';
              }

              $display .= '<div id="cmdConfig' . $cmd->getId() . '" class="collapse" style="margin-top: 8px;">';
              $display .= '<table class="table table-bordered table-condensed">';

              //visible and td widths:
              $display .= '<tr><td style="width:40%">{{Visible}}<input type="checkbox" class="'.$thisclassAttrib.'" data-l1key="isVisible" style="float: right"/></td>';
              $display .= '<td style="width:30%"><i class="fas fa-desktop"></i></td>';
              $display .= '<td style="width:30%"><i class="fas fa-tablet-alt"></i></td></tr>';

              //generic type:
              $display .= '<tr><td>{{Type générique}}</td>';
              $display .= '<td colspan="2"><select class="input-sm '.$thisclassAttrib.'" data-l1key="generic_type">';
              $display .= $cmd->getGenericTypeSelectOptions();
              $display .= '</select></td></tr>';

              $display .= '<tr><td>{{Widget}}</td>';
              //select dashboard widget
              $display .= '<td class="widgetPossibilityDashboard" style="display: none;"><select class="input-sm '.$thisclassAttrib.'" data-l1key="template" data-l2key="dashboard">';
              $display .= $cmd->getWidgetsSelectOptions('dashboard', $cmd_widgetDashboard);
              $display .= '</select></td>';

              //select mobile widget:
              $display .= '<td class="widgetPossibilityMobile" style="display: none;"><select class="input-sm '.$thisclassAttrib.'" data-l1key="template" data-l2key="mobile">';
              $display .= $cmd->getWidgetsSelectOptions('mobile', $cmd_widgetMobile);
              $display .= '</select></td>';
              $display .= '</tr>';

              $display .= '<tr><td>{{Afficher le nom}}</td>';
              $display .= '<td><input type="checkbox" class="'.$thisclassAttrib.'" data-l1key="display" data-l2key="showNameOndashboard" checked></td>';
              $display .= '<td><input type="checkbox" class="'.$thisclassAttrib.'" data-l1key="display" data-l2key="showNameOnmobile" checked></td>';
              $display .= '</tr>';

              $display .= '<tr><td>{{Afficher le nom ET l\'icône}}</td>';
              $display .= '<td><input type="checkbox" class="'.$thisclassAttrib.'" data-l1key="display" data-l2key="showIconAndNamedashboard"></td>';
              $display .= '<td><input type="checkbox" class="'.$thisclassAttrib.'" data-l1key="display" data-l2key="showIconAndNamemobile"></td>';
              $display .= '</tr>';

              $display .= '<tr><td>{{Afficher les statistiques}}</td>';
              $display .= '<td><input type="checkbox" class="'.$thisclassAttrib.'" data-l1key="display" data-l2key="showStatsOndashboard" checked></td>';
              $display .= '<td><input type="checkbox" class="'.$thisclassAttrib.'" data-l1key="display" data-l2key="showStatsOnmobile" checked></td>';
              $display .= '</tr>';

              $display .= '<tr><td>{{Retour à la ligne avant le widget}}</td>';
              $display .= '<td><input type="checkbox" class="'.$thisclassAttrib.'" data-l1key="display" data-l2key="forceReturnLineBefore" /></td></tr>';

              $display .= '<tr><td>{{Retour à la ligne après le widget}}</td>';
              $display .= '<td><input type="checkbox" class="'.$thisclassAttrib.'" data-l1key="display" data-l2key="forceReturnLineAfter" /></td></tr>';

              $display .= '<tr><td>{{Paramètres optionnels sur le widget:}}</td>';
              $display .= '<td colspan="2"><a class="btn btn-xs addWidgetParametersCmd pull-right" style="position:relative;right:5px;"><i class="fas fa-plus-circle"></i> {{Ajouter}}</a></td></tr>';

              $display .= '<tr><td colspan="3"><div class="optionalParamHelp '.$thisclassAttrib.'"></div></td></tr>';

              if ($cmd->getDisplay('parameters') != '') {
                foreach (($cmd->getDisplay('parameters')) as $key => $value) {
                  $display .= '<tr class="cmdoptparam">';
                  $display .= '<td>';
                  $display .= '<input class="input-sm key" value="' . $key . '" style="width: 100%;"/>';
                  $display .= '</td>';
                  $display .= '<td colspan="2">';
                  $display .= '<input class="input-sm value" value="' . $value . '" style="width: calc(100% - 30px);"/>';
                  $display .= '<a class="btn btn-danger btn-xs removeWidgetParameter"><i class="fas fa-times"></i></a>';
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
</div>
</div>

<script>
$('.ui-widget-overlay').hide()
var modal

$(function() {
  modal = $('#md_modal').parents('.ui-dialog.ui-resizable')

  //add save buttons:
  var button = {
    "Save": {
      text: "{{Sauvegarder}}",
      click: editSaveEqlogic
    }
  }
  $('#md_modal').dialog('option', 'buttons', button)

  //remove dialog buttons and bindings before closing:
  $('#md_modal').on('dialogbeforeclose', function(event, ui) {
    modal.find('.ui-dialog-buttonpane').remove()
    modal.find('.ui-draggable-handle').off('mouseup')
    $(this).off('dialogbeforeclose')
  })

  setModal()

  //display options:
  if (eqLogicWidgetPossibility == 1) $('#panel_optParams').show()
  $('#div_displayEqLogicConfigure').setValues(eqLogicInfo, '.eqLogicAttr')

  var $panelCmds = $('#panel_cmds')
  $panelCmds.find('.cmdConfig').each(function() {
    var id = $(this).data('id')
    var cmdInfo = allCmdsInfo[id]
    if (cmdInfo.widgetPossibilityDashboard == true) $(this).find('.widgetPossibilityDashboard').show()
    if (cmdInfo.widgetPossibilityMobile == true) $(this).find('.widgetPossibilityMobile').show()
    $panelCmds.setValues(cmdInfo, '.cmdAttr'+id)

    //widgets default if empty:
    var dashWidget = $(this).find('select[data-l2key="dashboard"]')
    if (dashWidget.val()==null) dashWidget.val($(this).find('select[data-l2key="dashboard"] option:first').val())
    var mobileWidget = $(this).find('select[data-l2key="mobile"]')
    if (mobileWidget.val()==null) mobileWidget.val($(this).find('select[data-l2key="mobile"] option:first').val())
  })

  setTableLayoutSortable()
  jeedomUtils.initTooltips()
  initPickers()

  //layout default or table for cmd order:
  if ($('.sel_layout').val() == 'default') {
    $('input[data-l2key="layout::dashboard::table::nbLine"], input[data-l2key="layout::dashboard::table::nbColumn"]').val(1)
  }
})

function setModal() {
  //check previous size/pos:
  var datas = modal.data()
  if (datas && datas.width && datas.height && datas.top && datas.left) {
    modal.width(datas.width).height(datas.height).css('top', datas.top).css('left', datas.left)
    $('#md_modal').height(datas.height-100)
  } else if ($(window).width() > 600) {
    width = 550
    height = 700
    modal.width(width).height(height)
    modal.position({
      my: "right",
      at: "right-50",
      of: window
    })
    $('#md_modal').height(height-100)
  }

  //store size/pos:
  modal.find('.ui-draggable-handle').on('mouseup', function(event) {
    modal.data( {'width':modal.width(), 'height':modal.height(), 'top':modal.css('top'), 'left':modal.css('left')} )
  })
}

/* Equipement */
function initPickers() {
  $('input[type="number"]').spinner({
    icons: { down: "ui-icon-triangle-1-s", up: "ui-icon-triangle-1-n" }
  })
}

function setTableLayoutSortable() {
  $('#tableCmdLayoutConfiguration tbody td .cmdLayoutContainer').sortable({
    connectWith: '#tableCmdLayoutConfiguration tbody td .cmdLayoutContainer',
    items: ".cmdLayout"
  })
}

function getNewLayoutTd(row, col) {
  var newTd = '<td data-line="' + row + '" data-column="' + col + '">'
  newTd += '<center class="cmdLayoutContainer"></center>'
  newTd += '<input class="eqLogicAttr form-control input-sm" data-l1key="display" data-l2key="layout::dashboard::table::parameters" data-l3key="text::td::' + row + '::' + col + '" placeholder="{{Texte de la case}}"/>'
  newTd += '<input class="eqLogicAttr form-control input-sm" data-l1key="display" data-l2key="layout::dashboard::table::parameters" data-l3key="style::td::' + row + '::' + col + '" placeholder="{{Style de la case (CSS)}}"/>'
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
      newTd = $(newTableLayout).find('td[data-line="'+row+'"][data-column="'+col+'"]')
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
      text = $(this).find('input[data-l3key="text::td::'+row+'::'+col+'"]').val()
      style = $(this).find('input[data-l3key="style::td::'+row+'::'+col+'"]').val()
      newTd = $(newTableLayout).find('td[data-line="'+row+'"][data-column="'+col+'"]')
      if (newTd.length) {
        $(newTableLayout).find('input[data-l3key="text::td::'+row+'::'+col+'"]').val(text)
        $(newTableLayout).find('input[data-l3key="style::td::'+row+'::'+col+'"]').val(style)
      }
    })

    //replace by new table:
    tableLayout.replaceWith(newTableLayout)
    $('#tableCmdLayoutConfiguration td').css('width', 100/nbColumn + '%')
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
  tr += '<input class="form-control key" />'
  tr += '</td>'
  tr += '<td>'
  tr += '<input class="form-control value" />'
  tr += '</td>'
  tr += '<td>'
  tr += '<a class="btn btn-danger btn-xs removeTileParameter"><i class="fas fa-times"></i> {{Supprimer}}</a>'
  tr += '</td>'
  tr += '</tr>'
  $('#table_widgetParameters tbody').append(tr)
})

$('#table_widgetParameters').on('click', '.removeTileParameter', function() {
  $(this).closest('tr').remove()
})

$('.sel_layout').on('change',function() {
  var type = $(this).attr('data-type')
  $('.widget_layout').hide()
  $('.widget_layout.'+$(this).value()).show()
})

/* commandes */
$('#panel_cmds').on('click', '.removeWidgetParameter', function() {
  $(this).closest('tr').remove()
})

$(function() {
  $('#commands select[data-l1key="template"][data-l2key="dashboard"]').each(function() {
    displayWidgetHelp($(this).val(), $(this).closest('.cmdConfig').attr('data-id'))
  })

  $('#commands select[data-l1key="template"][data-l2key="dashboard"]').off('change').on('change',function() {
    displayWidgetHelp($(this).val(), $(this).closest('.cmdConfig').attr('data-id'))
  })
})

function displayWidgetHelp(widgetName, cmdId) {
  jeedom.cmd.getWidgetHelp({
    id: cmdId,
    version: 'dashboard',
    widgetName: widgetName,
    error: function(error) {
      $('#commands div.optionalParamHelp.cmdAttr'+cmdId).empty().text('{{Pas de description des paramètres optionnels sur ce Widget.}}')
    },
    success: function(data) {
      $('#commands div.optionalParamHelp.cmdAttr'+cmdId).empty().html(data.html)
    }
  })
}

$('.addWidgetParametersCmd').on('click', function() {
  var tr = '<tr class="cmdoptparam">'
  tr += '<td>'
  tr += '<input class="input-sm key" style="width: 100%;"/>'
  tr += '</td>'
  tr += '<td colspan="2">'
  tr += '<input class="input-sm value" style="width: calc(100% - 30px);"/>'
  tr += '<a class="btn btn-danger btn-xs removeWidgetParameter"><i class="fas fa-times"></i></a>'
  tr += '</td>'
  tr += '</tr>'
  $(this).closest('.collapse').find('table').append(tr)
})

$("#div_eqLogicCmds").sortable({
  axis: "y",
  cursor: "move",
  items: ".cmdConfig",
  handle: ".bt_cmdConfig",
  placeholder: "ui-state-highlight",
  tolerance: "intersect",
  forcePlaceholderSize: true
})

/* modal */
$('.accordion-toggle').click(function() {
  $('.collapse').collapse('hide')
})

function editSaveEqlogic() {
  //get eqLogic:
  var eqLogic = $('#div_displayEqLogicConfigure').getValues('.eqLogicAttr')[0]
  if (!isset(eqLogic.display)) eqLogic.display = {}
  if (!isset(eqLogic.display.parameters)) eqLogic.display.parameters = {}
  //tile optionnal parameters:
  $('#table_widgetParameters tbody tr').each(function() {
    eqLogic.display.parameters[$(this).find('.key').value()] = $(this).find('.value').value()
  })

  //get cmds:
  eqLogic.cmd = []
  var cmd, attribClass
  $('#div_eqLogicCmds .cmdConfig').each(function() {
    attribClass = $(this).data('attribclass')
    cmd = $(this).getValues('.'+attribClass)[0]
    if (!isset(cmd.display)) cmd.display = {}
    if (!isset(cmd.display.parameters)) cmd.display.parameters = {}
    //cmd optionnal parameters:
    $(this).find('tr.cmdoptparam').each(function() {
      cmd.display.parameters[$(this).find('.key').value()] = $(this).find('.value').value()
    })
    eqLogic.cmd.push(cmd);
  })

  jeedom.eqLogic.save({
    eqLogics: [eqLogic],
    type: eqLogic.eqType_name,
    error: function(error) {
      $('#md_displayEqLogicConfigure').showAlert({message: error.message, level: 'danger'})
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

      jeedom.cmd.setOrder({
        version : 'dashboard',
        cmds: cmds,
        error: function(error) {
          $('#md_displayEqLogicConfigure').showAlert({message: error.message, level: 'danger'})
        },
        success : function(){}
      })
    }
  })
}
</script>
