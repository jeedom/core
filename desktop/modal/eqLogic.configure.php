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

<div id="md_eqLogicConfigure" data-modalType="md_eqLogicConfigure">
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
      <li role="presentation"><a href="#eqLogic_specialAttributesPlugin" aria-controls="messages" role="tab" data-toggle="tab"><i class="fas fa-cogs"></i> {{Informations complémentaires}}</a></li>
    </ul>

    <div class="tab-content" id="div_displayEqLogicConfigure">
      <div role="tabpanel" class="tab-pane active" id="eqLogic_information">
        <form class="form-horizontal">
          
		  <div class="row">
        	<div class="col-sm-6">
        	  <legend><i class="fas fa-clipboard-list"></i> {{Général}}</legend>
              <div class="form-group">
                <label class="col-sm-4 control-label">{{Nom}}</label>
                <div class="col-sm-8">
                  <input type="text" class="eqLogicAttr form-control input-sm" data-l1key="name">
                </div>
            </div>
             <div class="form-group">
                <label class="col-sm-4 control-label">{{ID unique}}</label>
                <div class="col-sm-8">
                  <span class="eqLogicAttr label label-sm label-primary" data-l1key="id"></span>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-4 control-label">{{Options}}</label>
                <div class="col-sm-8">
                  <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked>{{Activer}}</label>
                  <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked>{{Visible}}</label>
                  <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="hideOnMain">{{Masquer sur la vue principale}}</label>
                  <?php
                  $class = new ReflectionClass($eqLogic->getEqType_name());
                  $method_toHtml = $class->getMethod('toHtml');
                  if ($method_toHtml->class == $eqLogic->getEqType_name()) {
                    echo '<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="display" data-l2key="widgetTmpl" checked>{{Template de widget}}</label>';
                  }
                  ?>
                </div>
             </div>
             <div class="form-group">
                <label class="col-sm-4 control-label">{{ID logique}}</label>
                <div class="col-sm-8">
                  <span class="eqLogicAttr label label-sm label-primary" data-l1key="logicalId"></span>
                </div>
              </div>


              <div class="form-group">
                <label class="col-sm-4 control-label">{{Création - Sauvegarde}}</label>
                <div class="col-sm-8">
                  <span class="eqLogicAttr label label-sm label-info" data-l1key="configuration" data-l2key="createtime"></span> -
                  <span class="eqLogicAttr label label-sm label-info" data-l1key="configuration" data-l2key="updatetime"></span>
                </div>
             </div>
             <div class="form-group">
                <label class="col-sm-4 control-label">{{Tentative échouée}}</label>
                <div class="col-sm-8">
                  <span class="label label-sm label-primary"><?php echo $eqLogic->getStatus('numberTryWithoutSuccess', 0) ?></span>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-4 control-label">{{Dernière communication}}</label>
                <div class="col-sm-8">
                  <span class="label label-sm label-info"><?php echo $eqLogic->getStatus('lastCommunication') ?></span>
                </div>
            </div>
             <div class="form-group">
                <label class="col-sm-4 control-label">{{Tag(s)}}</label>
                <div class="col-sm-8">
                  <input class="eqLogicAttr form-control input-sm" data-l1key="tags">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-4 control-label">{{Commentaire}}</label>
                <div class="col-sm-8">
                  <textarea class="form-control eqLogicAttr autogrow" data-l1key="comment"></textarea>
                </div>
              </div>
            </div>
            <div class="col-sm-6">
                    <legend>{{Image}}</legend>
                    <div class="form-group">
                    	<div class="col-sm-7 col-sm-offset-3">
                    		<span class="btn btn-default btn-file">
                    			<i class="fas fa-cloud-upload-alt"></i> {{Envoyer}}<input id="bt_uploadImageEqLogic" type="file" name="file" accept="image/*">
                    		</span>
                    		<a class="btn btn-danger" id="bt_removeEqLogicImage"><i class="fas fa-trash"></i> {{Enlever l'image}}</a>
                   		</div>
					</div>
					<div class="form-group">
						<div class="col-sm-7 col-sm-offset-3 eqLogicImg">
							<img class="img-responsive" src="<?php echo $eqLogic->getImage(); ?>" width="240px" style="min-height : 50px" />
                        </div>
					</div>
                    
            </div>	        
          </div>
          <legend><i class="fas fa-list-alt"></i> {{Commandes}}</legend>
          <table class="table table-condensed">
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
                  $value = htmlspecialchars($cmd->execCmd());
                  $title = '{{Date de valeur}} : ' . $cmd->getValueDate() . '<br>{{Date de collecte}} : ' .  $cmd->getCollectDate();
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
                  <input type="number" class="eqLogicAttr form-control input-sm ispin" min="0" value="" data-l1key="display" data-l2key="backGraph::height" placeholder="{{Automatique}}">
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
            <table class="table table-condensed">
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
                    if (is_array($parameter['name']) && isset($parameter['name'][translate::getLanguage()])) $echo .= $parameter['name'][translate::getLanguage()];
                    else $echo .= $parameter['name'];
                    $echo .= '</td>';
                    foreach ((jeedom::getConfiguration('eqLogic:displayType')) as $key => $value) {
                      $echo .= '<td><div class="form-group">';
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
                          $echo .= '<input type="number" class="eqLogicAttr advanceWidgetParameter form-control input-sm ispin"' . $display . '" data-l1key="display" data-l2key="advanceWidgetParameter' . $pKey . $key . '" min="0" max="999999" value="' . $default . '">';
                          break;
                      }
                      $echo .= '</div></td>';
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

      <?php if ($eqLogic->widgetPossibility('custom::layout')) { ?>
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
                  <input type="number" min="1" max="20" step="1" class="eqLogicAttr form-control input-sm ispin" data-l1key="display" data-l2key="layout::dashboard::table::nbLine">
                </div>
                <label class="col-sm-2 control-label">{{Nombre de colonnes}}</label>
                <div class="col-sm-3">
                  <input type="number" min="1" max="20" step="1" class="eqLogicAttr form-control input-sm ispin" data-l1key="display" data-l2key="layout::dashboard::table::nbColumn">
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
              <table class="table table-condensed" id="tableCmdLayoutConfiguration">
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

          <legend><i class="fas fa-battery-three-quarters"></i> {{Batterie}}<a class="btn btn-success btn-xs pull-right eqLogicHideNoBattery" id="bt_resetbattery"><i class="fas fa-battery-full"></i> {{Pile(s) neuve(s)}}</a></legend>
          <div class="form-group">
            <label class="col-sm-2 control-label">{{Cet équipement n'a pas de batterie}}</label>
            <div class="col-sm-3">
              <input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="battery::disable"></input>
            </div>
          </div>

          <div class="form-group eqLogicHideNoBattery">
            <label class="col-sm-2 control-label">{{Type de pile}}</label>
            <div class="col-sm-3">
              <input class="eqLogicAttr form-control input-sm" data-l1key="configuration" data-l2key="battery_type"></input>
            </div>
            <label class="col-sm-2 control-label">{{Changement de pile}}</label>
            <div class="col-sm-3">
              <span class="eqLogicAttr label label-sm label-info" data-l1key="configuration" data-l2key="batterytime"></span>
            </div>
          </div>

          <div class="form-group eqLogicHideNoBattery">
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

      <div role="tabpanel" class="tab-pane" id="eqLogic_specialAttributesPlugin">
        <form class="form-horizontal">
        <br/>
          <div class="alert alert-info">{{Vous pouvez trouver ici toute informations complementaires demandées par un plugin sur les équipements Jeedom}}</div>
          <?php
            try {
              $plugins = plugin::listPlugin(true);
              foreach ($plugins as $plugin) {
                $specialAttributes = $plugin->getSpecialAttributes();
                if (!isset($specialAttributes['eqLogic']) || !is_array($specialAttributes['eqLogic']) || count($specialAttributes['eqLogic']) == 0) {
                  continue;
                }
                $spAttr = '<legend><i class="fas fa-users-cog"></i> {{Informations complémentaires demandées par}} ' . $plugin->getName() . '</legend>';
                foreach ($specialAttributes['eqLogic'] as $key => $config) {
                  $spAttr .= '<div class="form-group">';
                  $spAttr .= '<label class="col-sm-3 control-label">' . $config['name'][translate::getLanguage()] . '</label>';
                  $spAttr .= '<div class="col-sm-7">';
                  switch ($config['type']) {
                    case 'input':
                      $spAttr .= '<input class="form-control eqLogicAttr" data-l1key="configuration" data-l2key="plugin::' . $plugin->getId() . '::' . $key . '"/>';
                      break;
                    case 'checkbox':
                        $spAttr .= '<input type="checkbox" class="form-control eqLogicAttr" data-l1key="configuration" data-l2key="plugin::' . $plugin->getId() . '::' . $key . '"/>';
                        break;
                    case 'number':
                      $spAttr .= '<input type="number" class="form-control eqLogicAttr" data-l1key="configuration" data-l2key="plugin::' . $plugin->getId() . '::' . $key . '" min="' . (isset($config['min']) ? $config['min'] : '') . '" max="' . (isset($config['max']) ? $config['max'] : '') . '" />';
                      break;
                    case 'select':
                      $spAttr .= '<select class="form-control eqLogicAttr" data-l1key="configuration" data-l2key="plugin::' . $plugin->getId() . '::' . $key . '">';
                      foreach ($config['values'] as $value) {
                        $spAttr .= '<option value="' . $value['value'] . '">' . $value['name'] . '</option>';
                      }
                      $spAttr .= '</select>';
                      break;
                  }
                  $spAttr .= '</div>';
                  $spAttr .= '</div>';
                }
                echo $spAttr;
              }
            } catch (\Exception $e) {
            }
            ?>
          </form>
      </div>


    </div>
  </div>
</div>

<script>
  if (!jeeFrontEnd.md_eqLogicConfigure) {
    jeeFrontEnd.md_eqLogicConfigure = {
      init: function(_cmdIds) {
        if (document.body.getAttribute('data-page') == "eqAnalyse") {
          document.querySelector('#eqLogicConfigureTab > li > a[href="#eqLogic_alert"]').click()
        }
        this.setModal()

        //Set values:
        document.getElementById('div_displayEqLogicConfigure').setJeeValues(jeephp2js.md_eqLogicConfigure_Info, '.eqLogicAttr')
        //check some values:
        if (document.querySelector('select[data-l2key="layout::dashboard"]') != null) { //Can be unavailable!
          var nbColumn = document.querySelector('input[data-l2key="layout::dashboard::table::nbColumn"]').value
          if (nbColumn == '') document.querySelector('input[data-l2key="layout::dashboard::table::nbColumn"]').value = 1
          var nbLine = document.querySelector('input[data-l2key="layout::dashboard::table::nbLine"]').value
          if (nbLine == '') document.querySelector('input[data-l2key="layout::dashboard::table::nbLine"]').value = 1
        }
      },
      postInit: function() {
        document.querySelector('select[data-l2key="layout::dashboard"]')?.triggerEvent('change')
        document.querySelector('.eqLogicAttr[data-l1key="configuration"][data-l2key="battery::disable"]')?.triggerEvent('click')
        document.querySelectorAll('#md_eqLogicConfigure .advanceWidgetParameterDefault').forEach(_default => {
          _default?.triggerEvent('change')
        })
        document.querySelectorAll('#md_eqLogicConfigure .advanceWidgetParameterColorTransparent').forEach(_transparent => {
          _transparent?.triggerEvent('change')
        })
          
       try {
        	jeeFrontEnd.md_eqLogicConfigure.bckUploader.destroy()
       } catch (error) {}
        jeeFrontEnd.md_eqLogicConfigure.bckUploader = new jeeFileUploader({
          fileInput: document.getElementById('bt_uploadImageEqLogic'),
          replaceFileInput: false,
          url: 'core/ajax/eqLogic.ajax.php?action=uploadImage&id=' + jeephp2js.md_eqLogicConfigure_Info.id,
          dataType: 'json',
          done: function(e, data) {
            if (data.result.state != 'ok') {
              jeedomUtils.showAlert({
                message: data.result.result,
                level: 'danger'
                })
                return
              }
              if (isset(data.result.result.filepath)) {
                document.querySelector('#md_eqLogicConfigure .eqLogicImg').seen().querySelector('img').src = data.result.result.filepath
              } else {
              	document.querySelector('#md_eqLogicConfigure .eqLogicImg').unseen()
              }
              jeedomUtils.showAlert({
                message: '{{Image ajoutée avec succès}}',
                level: 'success'
                })
              }
        })

        //Dynamic values:
        document.querySelectorAll('#md_eqLogicConfigure .eqLogicConfigure_cmdValue').forEach(_cmd => {
          jeedom.cmd.addUpdateFunction(_cmd.getAttribute('data-cmd_id'), function(_options) {
            _options.value = String(_options.value).replace(/<[^>]*>?/gm, '')
            let cmd = document.querySelector('#md_eqLogicConfigure .eqLogicConfigure_cmdValue[data-cmd_id="' + _options.cmd_id + '"]')
            if (cmd === null) {
              return
            }
            let title = '{{Date de valeur}} : ' + _options.valueDate + '<br>{{Date de collecte}} : ' + _options.collectDate
            if (_options.value.length > 50) {
              title += '<br>{{Valeur}} : ' + _options.value
              _options.value = _options.value.trim().substring(0, 50) + '...'
            }
            cmd.setAttribute('title', title)
            cmd.empty().innerHTML = _options.value + ' ' + _options.unit
            cmd.style.color = 'var(--logo-primary-color)'
            setTimeout(function() {
              cmd.style.color = null
            }, 1000)
          })
        })

        jeeFrontEnd.md_eqLogicConfigure.setTableLayoutSortable()
        jeedomUtils.initSpinners()
      },
      setModal: function() {
        //modal title:
        var title = "{{Configuration de l'équipement}}"
        title += ' : ' + jeephp2js.md_eqLogicConfigure_Info.name
        title += ' <span class="cmdName"><em>(' + jeephp2js.md_eqLogicConfigure_Info.eqType_name + ')</em></span>'
        var titleEl = jeeDialog.get('#eqLogicConfigureTab', 'title')
        if (titleEl != null) {
          titleEl.querySelector('span.title').innerHTML = title
        } else {
          //Deprecated, some plugins may load old ui-dialog modale
          document.getElementById('div_displayEqLogicConfigure').closest('.ui-dialog').querySelector('.ui-dialog-title').innerHTML = title
        }
      },
      synchModalToEq: function() {
        if(document.querySelector('#div_pageContainer input.eqLogicAttr[data-l1key="name"]')){
          document.querySelector('#div_pageContainer input.eqLogicAttr[data-l1key="name"]').value = document.querySelector('#eqLogic_information input.eqLogicAttr[data-l1key="name"').value
        }
        if(document.querySelector('#div_pageContainer input.eqLogicAttr[data-l1key="isEnable"]')){
          document.querySelector('#div_pageContainer input.eqLogicAttr[data-l1key="isEnable"]').checked = document.querySelector('#eqLogic_information input.eqLogicAttr[data-l1key="isEnable"').checked
        }
        if(document.querySelector('#div_pageContainer input.eqLogicAttr[data-l1key="isVisible"]')){
          document.querySelector('#div_pageContainer input.eqLogicAttr[data-l1key="isVisible"]').checked = document.querySelector('#eqLogic_information input.eqLogicAttr[data-l1key="isVisible"').checked
        }
      },
      setTableLayoutSortable: function() {
        let containers = document.querySelectorAll('#md_eqLogicConfigure #tableCmdLayoutConfiguration tbody td .cmdLayoutContainer')
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
        var newTd = '<td data-line="' + row + '" data-column="' + col + '">'
        newTd += '<center class="cmdLayoutContainer"></center>'
        newTd += '<input class="eqLogicAttr form-control input-sm" data-l1key="display" data-l2key="layout::dashboard::table::parameters" data-l3key="text::td::' + row + '::' + col + '" placeholder="{{Texte de la cellule}}">'
        newTd += '<input class="eqLogicAttr form-control input-sm" data-l1key="display" data-l2key="layout::dashboard::table::parameters" data-l3key="style::td::' + row + '::' + col + '" placeholder="{{Style CSS ou attribut(s) HTML}}">'
        newTd += '</td>'
        return newTd
      },
      applyTableLayout: function() {
        var nbColumn = document.querySelector('#md_eqLogicConfigure input[data-l2key="layout::dashboard::table::nbColumn"]').value
        var nbRow = document.querySelector('#md_eqLogicConfigure input[data-l2key="layout::dashboard::table::nbLine"]').value

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
              newTd = jeeFrontEnd.md_eqLogicConfigure.getNewLayoutTd(i, j)
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
          jeeFrontEnd.md_eqLogicConfigure.setTableLayoutSortable()
        }
      },
      eqlogicSave: function(event) {
        var eqLogic = document.getElementById('div_displayEqLogicConfigure').getJeeValues('.eqLogicAttr')[0]
        if (!isset(eqLogic.display)) eqLogic.display = {}
        if (!isset(eqLogic.display.parameters)) eqLogic.display.parameters = {}

        document.querySelectorAll('#table_widgetParameters tbody tr').forEach(_tr => {
          eqLogic.display.parameters[_tr.querySelector('.key').jeeValue()] = _tr.querySelector('.value').jeeValue()
        })

        jeedom.eqLogic.save({
          eqLogics: [eqLogic],
          type: jeephp2js.md_eqLogicConfigure_Info.eqType_name,
          error: function(error) {
            jeedomUtils.showAlert({
              attachTo: jeeDialog.get('#md_eqLogicConfigure', 'dialog'),
              message: error.message,
              level: 'danger'
            })
          },
          success: function() {
            var cmds = []
            var order = 1
            document.querySelectorAll('#tableCmdLayoutConfiguration tbody td .cmdLayout').forEach(_lay => {
              cmd = {}
              cmd.id = _lay.getAttribute('data-cmd_id')
              cmd.line = _lay.closest('td').getAttribute('data-line')
              cmd.column = _lay.closest('td').getAttribute('data-column')
              cmd.order = order
              cmds.push(cmd)
              order++
            })

            for (cmd of cmds) {
              delete jeedom.cmd.update[cmd.id]
            }

            jeedom.cmd.setOrder({
              version: 'dashboard',
              cmds: cmds,
              error: function(error) {
                jeedomUtils.showAlert({
                  attachTo: jeeDialog.get('#md_eqLogicConfigure', 'dialog'),
                  message: error.message,
                  level: 'danger'
                })
              },
              success: function() {
                jeeFrontEnd.md_eqLogicConfigure.synchModalToEq()
                if (event.ctrlKey || event.metaKey) {
                  setTimeout(function() {
                    jeeDialog.get('#md_modal').close()
                  }, 500)
                } else {
                  var tab = document.querySelector('#eqLogicConfigureTab > li.active > a').getAttribute('href')
                  jeeDialog.dialog({
                    id: 'jee_modal',
                    contentUrl: 'index.php?v=d&modal=eqLogic.configure&eqLogic_id=' + eqLogic.id,
                    callback: function() {
                      document.querySelector('#eqLogicConfigureTab > li > a[href="' + tab + '"]')?.click()
                      jeedomUtils.showAlert({
                        attachTo: jeeDialog.get('#md_eqLogicConfigure', 'dialog'),
                        message: '{{Sauvegarde réussie}}',
                        level: 'success'
                      })
                    }
                  })
                }
              }
            })
          }
        })
      },
    }
  }

  (function() { // Self Isolation!
    var jeeM = jeeFrontEnd.md_eqLogicConfigure
    jeeM.init()

    //Manage events outside parents delegations:
    document.getElementById('bt_eqLogicConfigureLogRealTime')?.addEventListener('click', function(event) {
      jeeDialog.dialog({
        id: 'jee_modal2',
        title: "{{Logs}}",
        contentUrl: 'index.php?v=d&modal=log.display&log=event&search=' + jeephp2js.md_eqLogicConfigure_InfoSearchString
      })
    })
    document.getElementById('bt_eqLogicConfigureGraph')?.addEventListener('click', function(event) {
      jeeDialog.dialog({
        id: 'jee_modal2',
        title: "{{Graphique des liens}}",
        contentUrl: 'index.php?v=d&modal=graph.link&filter_type=eqLogic&filter_id=' + jeephp2js.md_eqLogicConfigure_Info.id
      })
    })
    document.getElementById('bt_eqLogicConfigureRawObject')?.addEventListener('click', function(event) {
      jeeDialog.dialog({
        id: 'jee_modal2',
        title: "{{Informations brutes}}",
        contentUrl: 'index.php?v=d&modal=object.display&class=eqLogic&id=' + jeephp2js.md_eqLogicConfigure_Info.id
      })
    })
    document.getElementById('bt_eqLogicConfigureSave')?.addEventListener('click', function(event) {
      jeeFrontEnd.md_eqLogicConfigure.eqlogicSave(event)
    })
    document.getElementById('bt_eqLogicConfigureRemove')?.addEventListener('click', function(event) {
      jeeDialog.confirm('{{Êtes-vous sûr de vouloir supprimer cet équipement ?}}', function(result) {
        if (result) {
          jeedom.eqLogic.remove({
            id: jeephp2js.md_eqLogicConfigure_Info.id,
            type: jeephp2js.md_eqLogicConfigure_Info.eqType_name,
            error: function(error) {
              jeedomUtils.showAlert({
                attachTo: jeeDialog.get('#md_eqLogicConfigure', 'dialog'),
                message: error.message,
                level: 'danger'
              })
            },
            success: function(data) {
              jeedomUtils.showAlert({
                attachTo: jeeDialog.get('#md_eqLogicConfigure', 'dialog'),
                message: '{{Equipement supprimé avec succès}}',
                level: 'success'
              })
            }
          })
        }
      })
    })

    /*Events delegations
     */
    //eqLogic information tab
    document.getElementById('eqLogic_information')?.addEventListener('click', function(event) {
      var _target = null
      if (_target = event.target.closest('.bt_advanceCmdConfigurationOnEqLogicConfiguration')) {
        jeeDialog.dialog({
          id: 'jee_modal2',
          title: '{{Configuration de la commande}}',
          contentUrl: 'index.php?v=d&modal=cmd.configure&cmd_id=' + _target.getAttribute('data-id')
        })
        return
      }
      
      if (_target = event.target.closest('#bt_removeEqLogicImage')) {
         jeeDialog.confirm('{{Êtes-vous sûr de vouloir enlever l\'image cet équipement ?}}', function(result) {
         if (result) {
            jeedom.eqLogic.removeImage({
              id: jeephp2js.md_eqLogicConfigure_Info.id,
              error: function(error) {
                jeedomUtils.showAlert({
                  message: error.message,
                  level: 'danger'
                })
              },
              success: function(data) {
                if (isset(data.filepath)) {
                  document.querySelector('#md_eqLogicConfigure .eqLogicImg').seen().querySelector('img').src = data.filepath
                } else {
                  document.querySelector('#md_eqLogicConfigure .eqLogicImg').unseen()
                }
                jeedomUtils.showAlert({
                  message: '{{Image enlevée}}',
                  level: 'success'
                })
              },
            })
          }
        })
        return
      }
      
    })

    document.getElementById('eqLogic_information')?.addEventListener('dblclick', function(event) {
      var _target = null
      if (_target = event.target.closest('.advanceCmdConfigurationCmdConfigure')) {
        jeeDialog.dialog({
          id: 'jee_modal2',
          title: '{{Configuration de la commande}}',
          contentUrl: 'index.php?v=d&modal=cmd.configure&cmd_id=' + _target.getAttribute('data-id')
        })
        return
      }
    })

    //eqLogic display tab
    document.getElementById('eqLogic_display')?.addEventListener('click', function(event) {
      var _target = null
      if (_target = event.target.closest('#bt_addWidgetParameters')) {
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
        document.getElementById('table_widgetParameters').tBodies[0].insertAdjacentHTML('beforeend', tr)
        return
      }

      if (_target = event.target.closest('.removeWidgetParameter')) {
        _target.closest('tr').remove()
        return
      }
    })

    document.getElementById('eqLogic_display')?.addEventListener('change', function(event) {
      var _target = null
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

    //eqLogic layout tab
    document.getElementById('eqLogic_layout')?.addEventListener('click', function(event) {
      var _target = null
      if (_target = event.target.closest('#bt_eqLogicLayoutApply')) {
        jeeFrontEnd.md_eqLogicConfigure.applyTableLayout()
        return
      }
    })

    document.getElementById('eqLogic_layout')?.addEventListener('change', function(event) {
      var _target = null
      if (_target = event.target.closest('.sel_layout')) {
        document.querySelectorAll('.widget_layout').unseen()
        document.querySelectorAll('.widget_layout.' + _target.value).seen()
        return
      }
    })

    //eqLogic alert tab
    document.getElementById('eqLogic_alert')?.addEventListener('click', function(event) {
      var _target = null

      if (_target = event.target.closest('.eqLogicAttr[data-l1key="configuration"][data-l2key="battery::disable"]')) {
        if(document.querySelector('.eqLogicAttr[data-l1key="configuration"][data-l2key="battery::disable"]').jeeValue() == 1){
          document.querySelectorAll('.eqLogicHideNoBattery').unseen();
        }else{
          document.querySelectorAll('.eqLogicHideNoBattery').seen();
        }
      }

      if (_target = event.target.closest('#bt_resetbattery')) {
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
                jeedomUtils.showAlert({
                  attachTo: jeeDialog.get('#md_eqLogicConfigure', 'dialog'),
                  message: error.message,
                  level: 'danger'
                })
              },
              success: function(data) {
                jeedomUtils.showAlert({
                  attachTo: jeeDialog.get('#md_eqLogicConfigure', 'dialog'),
                  message: '{{Le remplacement des piles a été enregistré}}',
                  level: 'success'
                })
                document.querySelectorAll('.eqLogicAttr[data-l1key="configuration"][data-l2key="batterytime"]').jeeValue(yyyy + '-' + mm + '-' + dd + ' ' + hh + ':' + MM + ':' + ss)
              }
            })
          }
        })
        return
      }
    })

    jeeM.postInit()
  })()
</script>
