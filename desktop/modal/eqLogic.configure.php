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
sendVarToJS([
  'jeephp2js.md_eqLogicConfigure_Info' => utils::o2a($eqLogic),
  'jeephp2js.md_eqLogicConfigure_InfoSearchString' => urlencode(str_replace('#', '', $eqLogic->getHumanName()))
]);
?>

<div style="display: none;" id="md_displayEqLogicConfigure" data-modalType="md_eqLogicConfigure"></div>
<div class="input-group pull-right" style="display:inline-flex">
  <span class="input-group-btn">
    <a class="btn btn-default roundedLeft btn-sm" id="bt_eqLogicConfigureLogRealTime"><i class="far fa-file"></i> {{Logs}}
    </a><a class="btn btn-default btn-sm" id="bt_eqLogicConfigureGraph"><i class="fas fa-object-group"></i> {{Liens}}
    </a><a class="btn btn-default btn-sm" id="bt_eqLogicConfigureRawObject"><i class="fas fa-info"></i> {{Informations}}
    </a><a class="btn btn-success btn-sm" id="bt_eqLogicConfigureSave"><i class="fas fa-check-circle"></i> {{Sauvegarder}}
    </a><a class="btn btn-danger roundedRight btn-sm" id="bt_eqLogicConfigureRemove"><i class="fas fa-minus-circle"></i> {{Supprimer}}</a>
  </span>
</div>

<div>
  <ul class="nav nav-tabs" role="tablist" id="eqLogicConfigureTab">
    <li role="presentation" class="active"><a href="#eqLogic_information" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-info-circle"></i> {{Informations}}</a></li>
    <?php if ($eqLogic->widgetPossibility('custom')) {
      echo '<li role="presentation"><a href="#eqLogic_display" aria-controls="messages" role="tab" data-toggle="tab"><i class="fas fa-desktop"></i> {{Affichage}}</a></li>';
    }
    if ($eqLogic->widgetPossibility('custom::layout')) {
      echo '<li role="presentation"><a href="#eqLogic_layout" aria-controls="messages" role="tab" data-toggle="tab"><i class="fas fa-th"></i> {{Disposition}}</a></li>';
    }
    ?>
    <li role="presentation"><a href="#eqLogic_alert" aria-controls="messages" role="tab" data-toggle="tab"><i class="fas fa-exclamation-triangle"></i> {{Alertes}}</a></li>
  </ul>

  <div class="tab-content" id="div_displayEqLogicConfigure">
    <div role="tabpanel" class="tab-pane active" id="eqLogic_information">
      <form class="form-horizontal">
        <legend><i class="fas fa-clipboard-list"></i> {{Général}}</legend>

        <div class="form-group">
          <label class="col-sm-2 control-label">{{Nom}}</label>
          <div class="col-sm-3">
            <input type="text" class="eqLogicAttr form-control input-sm" data-l1key="name">
          </div>

          <label class="col-sm-2 control-label">{{ID unique}}</label>
          <div class="col-sm-3">
            <span class="eqLogicAttr label label-sm label-primary" data-l1key="id"></span>
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-2 control-label">{{Options}}</label>
          <div class="col-sm-3">
            <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked>{{Activer}}</label>
            <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked>{{Visible}}</label>
            <?php
            $class = new ReflectionClass($eqLogic->getEqType_name());
            $method_toHtml = $class->getMethod('toHtml');
            if ($method_toHtml->class == $eqLogic->getEqType_name()) {
              echo '<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="display" data-l2key="widgetTmpl" checked>{{Template de widget}}</label>';
            }
            ?>
          </div>

          <label class="col-sm-2 control-label">{{ID logique}}</label>
          <div class="col-sm-3">
            <span class="eqLogicAttr label label-sm label-primary" data-l1key="logicalId"></span>
          </div>
        </div>


        <div class="form-group">
          <label class="col-sm-2 control-label">{{Création - Sauvegarde}}</label>
          <div class="col-sm-3">
            <span class="eqLogicAttr label label-sm label-info" data-l1key="configuration" data-l2key="createtime"></span> -
            <span class="eqLogicAttr label label-sm label-info" data-l1key="configuration" data-l2key="updatetime"></span>
          </div>

          <label class="col-sm-2 control-label">{{Tentative échouée}}</label>
          <div class="col-sm-3">
            <span class="label label-sm label-primary"><?php echo $eqLogic->getStatus('numberTryWithoutSuccess', 0) ?></span>
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-2 control-label">{{Dernière communication}}</label>
          <div class="col-sm-3">
            <span class="label label-sm label-info"><?php echo $eqLogic->getStatus('lastCommunication') ?></span>
          </div>

          <label class="col-sm-2 control-label">{{Tag(s)}}</label>
          <div class="col-sm-3">
            <input class="eqLogicAttr form-control input-sm" data-l1key="tags">
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-2 control-label">{{Commentaire}}</label>
          <div class="col-sm-8">
            <textarea class="form-control eqLogicAttr autogrow" data-l1key="comment"></textarea>
          </div>
        </div>

        <legend><i class="fas fa-list-alt"></i> {{Commandes}}</legend>
        <table class="table table-bordered table-condensed">
          <thead>
            <tr>
              <th class="hidden-xs">{{ID}}</th>
              <th>{{Nom}}</th>
              <th>{{Type}}</th>
              <th>{{Valeur}}</th>
              <th class="text-right">{{Action}}</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $display = '';
            foreach (($eqLogic->getCmd()) as $cmd) {
              $display .= '<tr class="advanceCmdConfigurationCmdConfigure" data-id="' . $cmd->getId() . '">';
              $display .= '<td class="hidden-xs">' . $cmd->getId() . '</td>';
              $display .= '<td>' . $cmd->getHumanName() . '</td>';
              $display .= '<td><span class="label label-sm label-' . (($cmd->getType() == 'action') ? 'warning' : 'primary') . '">' . $cmd->getType() . '</span> <span class="label label-sm label-info">' . $cmd->getSubtype() . '</span></td>';
              $display .= '<td>';
              if ($cmd->getType() == 'info') {
                $value = $cmd->execCmd();
                $title = '{{Date de valeur}} : ' . $cmd->getValueDate() . ' - {{Date de collecte}} : ' .  $cmd->getCollectDate();
                if (strlen($value) > 50) {
                  $title .= '<br>{{Valeur}} : ' . $value;
                  $value = trim(substr($value, 0, 50)) . '...';
                }
                $display .= '<span class="eqLogicConfigure_cmdValue" data-cmd_id="' . $cmd->getid() . '" title=" ' . htmlspecialchars($title) . '">' . $value . ' ' . $cmd->getUnite() . '<span>';
              }
              $display .= '</td>';
              $display .= '<td class="text-right">';
              $display .= '<a class="btn btn-default btn-xs bt_advanceCmdConfigurationOnEqLogicConfiguration" data-id="' . $cmd->getId() . '"><i class="fas fa-cogs"></i></a>';
              $display .= '</td>';
              $display .= '</tr>';
            }
            echo $display;
            ?>
          </tbody>
        </table>
      </form>
    </div>

    <?php if ($eqLogic->widgetPossibility('custom')) {
    ?>
      <div role="tabpanel" class="tab-pane" id="eqLogic_display">
        <?php if ($eqLogic->widgetPossibility('custom::graph')) {
        ?>
          <form class="form-horizontal">
            <legend><i class="fas fa-chart-line"></i> {{Graphique de fond}}</legend>
            <div class="form-group">
              <label class="col-sm-2 control-label">{{Information}}</label>
              <div class="col-sm-3">
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
              <div class="col-sm-3">
                <input type="color" class="eqLogicAttr form-control input-sm" value="#4572A7" data-l1key="display" data-l2key="backGraph::color">
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label">{{Période}}</label>
              <div class="col-sm-3">
                <select class="eqLogicAttr form-control input-sm" data-l1key="display" data-l2key="backGraph::format">
                  <option value="month">{{Mois}}</option>
                  <option value="week">{{Semaine}}</option>
                  <option value="day">{{Jour}}</option>
                  <option value="hour">{{Heure}}</option>
                </select>
              </div>

              <label class="col-sm-2 control-label">{{Hauteur}} <sub>px</sub></label>
              <div class="col-sm-3">
                <input type="number" class="eqLogicAttr form-control input-sm ui-spinner" value="" data-l1key="display" data-l2key="backGraph::height" placeholder="{{Automatique}}">
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label">{{Type}}</label>
              <div class="col-sm-3">
                <select class="eqLogicAttr form-control input-sm" data-l1key="display" data-l2key="backGraph::type">
                  <option value="areaspline">{{Courbe}}</option>
                  <option value="area">{{Escalier}}</option>
                  <option value="column">{{Barres verticales}}</option>
                </select>
              </div>
            </div>
          </form>

        <?php }
        if ($eqLogic->getDisplay('widgetTmpl', 1) == 1 && is_array($eqLogic->widgetPossibility('parameters')) && count($eqLogic->widgetPossibility('parameters')) > 0) { ?>
          <legend><i class="fas fa-pencil-ruler"></i> {{Paramètres du template}}</legend>
          <table class="table table-bordered table-condensed">
            <thead>
              <tr>
                <th></th>
                <?php
                $display = '';
                foreach ((jeedom::getConfiguration('eqLogic:displayType')) as $key => $value) {
                  $display .= '<th style="width:25%">' . $value['name'] . '</th>';
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
                        $echo .= '<input class="eqLogicAttr advanceWidgetParameter"' . $display . '" data-l1key="display" data-l2key="advanceWidgetParameter' . $pKey . $key . '" value="' . $default . '">';
                        break;
                      case 'number':
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
          <legend><i class="fas fa-code"></i> {{Paramètres optionnels}} <a class="btn btn-success btn-xs pull-right" id="bt_addWidgetParameters"><i class="fas fa-plus-circle"></i> {{Ajouter}}</a></legend>
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
                  $echo .= '<a class="btn btn-danger btn-xs removeWidgetParameter"><i class="far fa-trash-alt"></i> {{Supprimer}}</a>';
                  $echo .= '</td>';
                  $echo .= '</tr>';
                }
                echo $echo;
              }
              ?>
            </tbody>
          </table>
        <?php } ?>
      </div>
    <?php } ?>

    <?php if ($eqLogic->widgetPossibility('custom::layout')) {
    ?>
      <div role="tabpanel" class="tab-pane" id="eqLogic_layout">
        <form class="form-horizontal">
          <legend><i class="fas fa-table"></i> {{Mise en forme générale}}</legend>
          <div class="form-group">
            <label class="col-sm-2 control-label">{{Disposition}}</label>
            <div class="col-sm-3">
              <select class="eqLogicAttr form-control input-sm sel_layout" data-l1key="display" data-l2key="layout::dashboard">
                <option value="default">{{Défaut}}</option>
                <option value="table">{{Tableau}}</option>
              </select>
            </div>

            <div class="widget_layout table" style="display: none;">
              <label class="col-sm-2 control-label">{{Centrer dans les cases}}</label>
              <div class="col-sm-3">
                <input type="checkbox" class="eqLogicAttr" data-l1key="display" data-l2key="layout::dashboard::table::parameters" data-l3key="center">
              </div>
            </div>
          </div>

          <div class="widget_layout table" style="display: none;">
            <div class="form-group">
              <label class="col-sm-2 control-label">{{Nombre de lignes}}</label>
              <div class="col-sm-3">
                <input type="number" min="1" max="20" step="1" class="eqLogicAttr form-control input-sm ui-spinner" data-l1key="display" data-l2key="layout::dashboard::table::nbLine">
              </div>
              <label class="col-sm-2 control-label">{{Nombre de colonnes}}</label>
              <div class="col-sm-3">
                <input type="number" min="1" max="20" step="1" class="eqLogicAttr form-control input-sm ui-spinner" data-l1key="display" data-l2key="layout::dashboard::table::nbColumn">
              </div>
              <a class="btn btn-success btn-xs" id="bt_eqLogicLayoutApply"><i class="fas fa-sync-alt"></i></i> {{Appliquer}}</a>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label">{{Style du tableau}} <sub>CSS</sub></label>
              <div class="col-sm-8">
                <textarea class="eqLogicAttr form-control" data-l1key="display" data-l2key="layout::dashboard::table::parameters" data-l3key="styletable"></textarea>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label">{{Style des cellules}} <sub>CSS</sub></label>
              <div class="col-sm-8">
                <textarea class="eqLogicAttr form-control" data-l1key="display" data-l2key="layout::dashboard::table::parameters" data-l3key="styletd"></textarea>
              </div>
            </div>
          </div>
        </form>

        <div class="widget_layout table" style="display: none;">
          <legend><i class="fas fa-th-large"></i> {{Mise en forme détaillée}}</legend>
          <div class="table-responsive">
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
    <?php }
    ?>

    <div role="tabpanel" class="tab-pane" id="eqLogic_alert">
      <form class="form-horizontal">
        <legend><i class="fas fa-satellite-dish"></i> {{Communication}}</legend>
        <div class="form-group">
          <label class="col-sm-2 control-label">{{En alerte}} <sub>{{minutes}}</sub></label>
          <div class="col-sm-3 input-group">
            <span class="input-group-addon input-sm roundedLeft" style="background-color:var(--al-danger-color)!important;"><i class="far fa-clock"></i> {{Danger si}} <i class="fas fa-greater-than-equal"></i></span>
            <input type="number" class="eqLogicAttr form-control input-sm roundedRight" data-l1key="timeout">
          </div>
        </div>

        <legend><i class="fas fa-battery-three-quarters"></i> {{Batterie}}<a class="btn btn-success btn-xs pull-right" id="bt_resetbattery"><i class="fas fa-battery-full"></i> {{Pile(s) neuve(s)}}</a></legend>
        <div class="form-group">
          <label class="col-sm-2 control-label">{{Type de pile}}</label>
          <div class="col-sm-3">
            <input class="eqLogicAttr form-control input-sm" data-l1key="configuration" data-l2key="battery_type"></input>
          </div>
          <label class="col-sm-2 control-label">{{Changement de pile}}</label>
          <div class="col-sm-3">
            <span class="eqLogicAttr label label-sm label-info" data-l1key="configuration" data-l2key="batterytime"></span>
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-2 control-label">{{En alerte}} <sub>%</sub></label>
          <div class="col-sm-8 input-group">
            <span class="input-group-addon input-sm roundedLeft" style="background-color:var(--al-danger-color)!important;"><i class="fas fa-battery-empty"></i> {{Danger si}} <i class="fas fa-less-than-equal"></i></span>
            <input type="number" class="eqLogicAttr form-control input-sm" data-l1key="configuration" data-l2key="battery_danger_threshold">
            <span class="input-group-addon input-sm" style="background-color:var(--al-warning-color)!important;"><i class="fas fa-battery-quarter"></i> {{Attention si}} <i class="fas fa-less-than-equal"></i></span>
            <input type="number" class="eqLogicAttr form-control input-sm roundedRight" data-l1key="configuration" data-l2key="battery_warning_threshold">
          </div>
        </div>
      </form>
    </div>

  </div>
</div>

<script>
  //check if coming from clicking on battery in eqanalyse:
  if (document.body.getAttribute('data-page') == "eqAnalyse") {
    document.querySelector('#eqLogicConfigureTab > li > a[href="#eqLogic_alert"]').click()
  }

  //modal title:
  var title = "{{Configuration de l'équipement}}"
  title += ' : ' + jeephp2js.md_eqLogicConfigure_Info.name
  title += ' <span class="cmdName"><em>(' + jeephp2js.md_eqLogicConfigure_Info.eqType_name + ')</em></span>'
  let titleEl = jeeDialog.get('#eqLogicConfigureTab', 'title')
  if (titleEl != null) {
    titleEl.querySelector('span.title').innerHTML = title
  } else {
    //Deprecated, some plugins may load old ui-dialog modale
    document.getElementById('md_modal').closest('.ui-dialog').querySelector('.ui-dialog-title').innerHTML = title
  }

  //check some values:
  var nbColumn = document.querySelector('input[data-l2key="layout::dashboard::table::nbColumn"]').value
  if (nbColumn == '') document.querySelector('input[data-l2key="layout::dashboard::table::nbColumn"]').value = 1
  var nbLine = document.querySelector('input[data-l2key="layout::dashboard::table::nbLine"]').value
  if (nbLine == '') document.querySelector('input[data-l2key="layout::dashboard::table::nbLine"]').value = 1

  setTableLayoutSortable()
  jeedomUtils.initSpinners()

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

  $('.sel_layout').on('change', function() {
    document.querySelectorAll('.widget_layout').unseen()
    document.querySelectorAll('.widget_layout.' + this.value).seen()
  })

  $('.advanceWidgetParameterDefault').off('change').on('change', function() {
    if (this.jeeValue() == 1) {
      this.closest('td').querySelectorAll('.advanceWidgetParameter').unseen()
    } else {
      this.closest('td').querySelectorAll('.advanceWidgetParameter').seen()
    }
  })

  $('.advanceWidgetParameterColorTransparent').off('change').on('change', function() {
    if (this.jeeValue() == 1) {
      this.closest('td').querySelectorAll('.advanceWidgetParameterColor').unseen()
    } else {
      this.closest('td').querySelectorAll('.advanceWidgetParameterColor').seen()
    }
  })

  document.getElementById('div_displayEqLogicConfigure').setJeeValues(jeephp2js.md_eqLogicConfigure_Info, '.eqLogicAttr')

  $('.bt_advanceCmdConfigurationOnEqLogicConfiguration').off('click').on('click', function() {
    jeeDialog.dialog({
      id: 'jee_modal2',
      title: '{{Configuration de la commande}}',
      contentUrl: 'index.php?v=d&modal=cmd.configure&cmd_id=' + this.getAttribute('data-id')
    })
  })

  $('.advanceCmdConfigurationCmdConfigure').off('dblclick').on('dblclick', function() {
    jeeDialog.dialog({
      id: 'jee_modal2',
      title: '{{Configuration de la commande}}',
      contentUrl: 'index.php?v=d&modal=cmd.configure&cmd_id=' + this.getAttribute('data-id')
    })
  })

  $('#bt_eqLogicConfigureGraph').on('click', function() {
    jeeDialog.dialog({
      id: 'jee_modal2',
      title: "{{Graphique des liens}}",
      contentUrl: 'index.php?v=d&modal=graph.link&filter_type=eqLogic&filter_id=' + jeephp2js.md_eqLogicConfigure_Info.id
    })
  })

  $('#bt_eqLogicConfigureRawObject').off('click').on('click', function() {
    jeeDialog.dialog({
      id: 'jee_modal2',
      title: "{{Informations brutes}}",
      contentUrl: 'index.php?v=d&modal=object.display&class=eqLogic&id=' + jeephp2js.md_eqLogicConfigure_Info.id
    })
  })

  $('#bt_eqLogicConfigureLogRealTime').off('click').on('click', function() {
    jeeDialog.dialog({
      id: 'jee_modal2',
      title: "{{Logs}}",
      contentUrl: 'index.php?v=d&modal=log.display&log=event&search=' + jeephp2js.md_eqLogicConfigure_InfoSearchString
    })
  })

  $('#table_widgetParameters').on('click', '.removeWidgetParameter', function() {
    $(this).closest('tr').remove()
  })

  $('#bt_addWidgetParameters').off().on('click', function() {
    var tr = '<tr>'
    tr += '<td>'
    tr += '<input class="form-control input-sm key">'
    tr += '</td>'
    tr += '<td>'
    tr += '<input class="form-control input-sm value">'
    tr += '</td>'
    tr += '<td class="text-right">'
    tr += '<a class="btn btn-danger btn-xs removeWidgetParameter"><i class="far fa-trash-alt"></i> {{Supprimer}}</a>'
    tr += '</td>'
    tr += '</tr>'
    $('#table_widgetParameters tbody').append(tr)
  })

  $('#bt_eqLogicConfigureSave').on('click', function(event) {
    var eqLogic = document.getElementById('div_displayEqLogicConfigure').getJeeValues('.eqLogicAttr')[0]
    if (!isset(eqLogic.display)) {
      eqLogic.display = {}
    }
    if (!isset(eqLogic.display.parameters)) {
      eqLogic.display.parameters = {}
    }
    $('#table_widgetParameters tbody tr').each(function() {
      eqLogic.display.parameters[this.querySelector('.key').jeeValue()] = this.querySelector('.value').jeeValue()
    })
    jeedom.eqLogic.save({
      eqLogics: [eqLogic],
      type: jeephp2js.md_eqLogicConfigure_Info.eqType_name,
      error: function(error) {
        $('#md_displayEqLogicConfigure').showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function() {
        var cmds = []
        var order = 1
        $('#tableCmdLayoutConfiguration tbody td').find('.cmdLayout').each(function() {
          cmd = {}
          cmd.id = $(this).attr('data-cmd_id')
          cmd.line = $(this).closest('td').attr('data-line')
          cmd.column = $(this).closest('td').attr('data-column')
          cmd.order = order
          cmds.push(cmd)
          order++
        })
        jeedom.cmd.setOrder({
          version: 'dashboard',
          cmds: cmds,
          error: function(error) {
            $('#md_displayEqLogicConfigure').showAlert({
              message: error.message,
              level: 'danger'
            })
          },
          success: function() {
            synchModalToEq()

            if (event.ctrlKey || event.metaKey) {
              setTimeout(function() {
                jeeDialog.get('#md_modal').close()
              }, 500)
            } else {
              var tab = $('#eqLogicConfigureTab > li.active > a').attr('href')
              $('#md_modal').load('index.php?v=d&modal=eqLogic.configure&eqLogic_id=' + eqLogic.id, function() {
                $('#eqLogicConfigureTab > li > a[href="' + tab + '"]').click()
                $('#md_displayEqLogicConfigure').showAlert({
                  message: '{{Sauvegarde réussie}}',
                  level: 'success'
                })
              })
            }
          }
        })
      }
    })
  })

  function synchModalToEq() {
    $('#div_pageContainer input.eqLogicAttr[data-l1key="name"]').val($('#div_displayEqLogicConfigure input.eqLogicAttr[data-l1key="name"').val())
    $('#div_pageContainer input.eqLogicAttr[data-l1key="isEnable"]').prop('checked', $('#div_displayEqLogicConfigure input.eqLogicAttr[data-l1key="isEnable"').prop('checked'))
    $('#div_pageContainer input.eqLogicAttr[data-l1key="isVisible"]').prop('checked', $('#div_displayEqLogicConfigure input.eqLogicAttr[data-l1key="isVisible"').prop('checked'))
  }

  $('#bt_eqLogicConfigureRemove').on('click', function() {
    jeeDialog.confirm('{{Êtes-vous sûr de vouloir supprimer cet équipement ?}}', function(result) {
      if (result) {
        jeedom.eqLogic.remove({
          id: jeephp2js.md_eqLogicConfigure_Info.id,
          type: jeephp2js.md_eqLogicConfigure_Info.eqType_name,
          error: function(error) {
            $('#md_displayEqLogicConfigure').showAlert({
              message: error.message,
              level: 'danger'
            })
          },
          success: function(data) {
            $('#md_displayEqLogicConfigure').showAlert({
              message: '{{Equipement supprimé avec succès}}',
              level: 'success'
            })
          }
        })
      }
    })
  })

  $('#bt_resetbattery').on('click', function() {
    jeeDialog.confirm("{{Confirmer le remplacement des piles ? Cette action enregistrera le dernier de changement de piles à la date d'aujourd'hui.}}", function(result) {
      if (result) {
        var eqLogic = {}
        eqLogic['id'] = jeephp2js.md_eqLogicConfigure_Info.id
        eqLogic['configuration'] = {}
        var today = new Date()
        var dd = today.getDate()
        var mm = today.getMonth() + 1
        var hh = today.getHours()
        var MM = today.getMinutes()
        var ss = today.getSeconds()
        var yyyy = today.getFullYear()
        eqLogic['configuration']['batterytime'] = yyyy + '-' + mm + '-' + dd + ' ' + hh + ':' + MM + ':' + ss
        jeedom.eqLogic.simpleSave({
          eqLogic: eqLogic,
          error: function(error) {
            $('#md_displayEqLogicConfigure').showAlert({
              message: error.message,
              level: 'danger'
            })
          },
          success: function(data) {
            $('#md_displayEqLogicConfigure').showAlert({
              message: '{{Le remplacement des piles a été enregistré}}',
              level: 'success'
            })
            document.querySelectorAll('.eqLogicAttr[data-l1key="configuration"][data-l2key="batterytime"]').jeeValue(yyyy + '-' + mm + '-' + dd + ' ' + hh + ':' + MM + ':' + ss)
          }
        })
      }
    })
  })

  $('.eqLogicConfigure_cmdValue').each(function() {
    jeedom.cmd.addUpdateFunction($(this).attr('data-cmd_id'), function(_options) {
      _options.value = String(_options.value).replace(/<[^>]*>?/gm, '');
      let cmd = $('.eqLogicConfigure_cmdValue[data-cmd_id=' + _options.cmd_id + ']')
      let title = '{{Date de valeur}} : ' + _options.valueDate + ' - {{Date de collecte}} : ' + _options.collectDate;
      if (_options.value.length > 50) {
        title += '<br>{{Valeur}} : ' + _options.value;
        _options.value = _options.value.trim().substring(0, 50) + '...';
      }
      cmd.attr('title', title)
      cmd.empty().append(_options.value + ' ' + _options.unit);
      cmd.css('color', 'var(--logo-primary-color)');
      setTimeout(function() {
        cmd.css('color', '');
      }, 1000);
    });
  })
</script>
