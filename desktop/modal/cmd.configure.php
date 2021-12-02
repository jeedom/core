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
$cmd = cmd::byId(init('cmd_id'));
if (!is_object($cmd)) {
  throw new Exception('{{Commande non trouvée}}' . ' : ' . init('cmd_id'));
}
global $JEEDOM_INTERNAL_CONFIG;

$cmdInfo = jeedom::toHumanReadable(utils::o2a($cmd));
$cmdInfo['eqLogicName'] = $cmd->getEqLogic()->getName();
sendVarToJS([
  'cmdInfo' => $cmdInfo,
  'cmdInfoSearchString' => urlencode(str_replace('#', '', $cmd->getHumanName())),
  'cmdInfoString' => $cmd->getHumanName()
]);
$cmd_widgetDashboard = cmd::availableWidget('dashboard');
$cmd_widgetMobile = cmd::availableWidget('mobile');
$configEqDisplayType = jeedom::getConfiguration('eqLogic:displayType');
?>

<div style="display: none;" id="md_displayCmdConfigure"></div>
<div class="input-group pull-right" style="display:inline-flex">
  <span class="input-group-btn">
    <a class="btn btn-default roundedLeft btn-sm" id="bt_cmdConfigureTest"><i class="fas fa-rss"></i> {{Tester}}
    </a><a class="btn btn-default btn-sm" id="bt_cmdConfigureGraph"><i class="fas fa-object-group"></i> {{Liens}}
    </a><a class="btn btn-default btn-sm" id="bt_cmdConfigureRawObject"><i class="fas fa-info"></i> {{Informations}}
    </a><a class="btn btn-success btn-sm roundedRight" id="bt_cmdConfigureSave"><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a>
  </span>
</div>
<div role="tabpanel">
  <ul class="nav nav-tabs" role="tablist" id="cmdConfigureTab">
    <li role="presentation" class="active"><a href="#cmd_information" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-info-circle"></i> {{Informations}}</a></li>
    <li role="presentation"><a href="#cmd_configuration" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-wrench"></i> {{Configuration}}</a></li>
    <?php if ($cmd->getType() == 'info') { ?>
      <li role="presentation"><a href="#cmd_alert" aria-controls="messages" role="tab" data-toggle="tab"><i class="fas fa-exclamation-triangle"></i> {{Alertes}}</a></li>
    <?php }
    ?>
    <?php if ($cmd->widgetPossibility('custom')) {
    ?>
      <li role="presentation"><a href="#cmd_display" aria-controls="messages" role="tab" data-toggle="tab"><i class="fas fa-desktop"></i> {{Affichage}}</a></li>
    <?php }
    ?>
  </ul>

  <div class="tab-content" id="div_displayCmdConfigure" style="overflow-x:hidden">

    <!--Information Tab -->
    <div role="tabpanel" class="tab-pane active" id="cmd_information">
      <br />
      <div class="row">
        <div class="col-sm-6">
          <form class="form-horizontal">
            <fieldset>
              <legend><i class="fas fa-list-alt"></i> {{Général}}</legend>
              <div class="form-group">
                <label class="col-xs-4 control-label">{{ID}}</label>
                <div class="col-xs-8">
                  <span class="cmdAttr label label-primary" data-l1key="id"></span>
                </div>
              </div>
              <div class="form-group">
                <label class="col-xs-4 control-label">{{Logical ID}}</label>
                <div class="col-xs-8">
                  <span class="cmdAttr label label-primary" data-l1key="logicalId"></span>
                </div>
              </div>
              <div class="form-group">
                <label class="col-xs-4 control-label">{{Nom}}</label>
                <div class="col-xs-4">
                  <span class="cmdAttr label label-primary" data-l1key="name"></span>
                </div>
              </div>
              <div class="form-group">
                <label class="col-xs-4 control-label">{{Type}}</label>
                <div class="col-xs-8">
                  <span class="cmdAttr label label-primary" data-l1key="type"></span>
                </div>
              </div>
              <div class="form-group">
                <label class="col-xs-4 control-label">{{Sous-type}}</label>
                <div class="col-xs-8">
                  <span class="cmdAttr label label-primary" data-l1key="subType"></span>
                </div>
              </div>
              <div class="form-group">
                <label class="col-xs-4 control-label">{{Mise à jour par}}</label>
                <div class="col-xs-8">
                  <span class="cmdAttr" data-l1key="value"></span>
                </div>
              </div>
              <?php if ($cmd->getType() == 'action' && $cmd->getSubtype() == 'select') {
              ?>
                <div class="form-group">
                  <label class="col-xs-4 control-label">{{Valeurs possibles}}</label>
                  <div class="col-xs-8">
                    <?php
                    $elements = explode(';', $cmd->getConfiguration('listValue', ''));
                    foreach ($elements as $element) {
                      $coupleArray = explode('|', $element);
                      echo $coupleArray[1] . ' => ' . $coupleArray[0] . '<br/>';
                    }
                    ?>
                  </div>
                </div>
              <?php } ?>
              <?php if ($cmd->getType() == 'info') {
                $cache = $cmd->getCache(array('value', 'collectDate', 'valueDate'));
              ?>
                <div class="form-group">
                  <label class="col-xs-4 control-label">{{Valeur}}</label>
                  <div class="col-xs-8">
                    <span class="label label-primary" style="max-width: 100%;"><?php echo $cache['value'] ?></span>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-xs-4 control-label">{{Date collecte}}</label>
                  <div class="col-xs-8">
                    <span class="label label-primary"><?php echo $cache['collectDate'] ?></span>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-xs-4 control-label">{{Date valeur}}</label>
                  <div class="col-xs-8">
                    <span class="label label-primary"><?php echo $cache['valueDate'] ?></span>
                  </div>
                </div>
              <?php } ?>
            </fieldset>
          </form>
        </div>
        <div class="col-sm-6">
          <form class="form-horizontal">
            <fieldset>
              <br />
              <div class="form-group">
                <label class="col-xs-4 control-label">{{URL directe}}</label>
                <div class="col-xs-8">
                  <?php
                  echo '<a href="' . $cmd->getDirectUrlAccess() . '" target="_blank"><i class="fas fa-external-link-alt"></i> URL</a>';
                  ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-xs-4 control-label">{{Unité}}</label>
                <div class="col-xs-4">
                  <span class="cmdAttr label label-primary" data-l1key="unite"></span>
                </div>
              </div>
              <div class="form-group">
                <label class="col-xs-4 control-label">{{Visible}}</label>
                <div class="col-xs-4">
                  <input type="checkbox" class="cmdAttr" data-l1key="isVisible" />
                </div>
              </div>
              <?php if ($cmd->getType() == 'info' && $cmd->getSubtype() == 'numeric') { ?>
                <div class="form-group">
                  <label class="col-xs-4 control-label">{{Valeur minimum}}
                    <sup><i class="fas fa-question-circle" title="{{défaut}} : 0"></i></sup>
                  </label>
                  <div class="col-xs-2">
                    <input class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="minValue" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-xs-4 control-label">{{Valeur maximum}}
                    <sup><i class="fas fa-question-circle" title="{{défaut}} : 100"></i></sup>
                  </label>
                  <div class="col-xs-2">
                    <input class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="maxValue" />
                  </div>
                </div>
              <?php } ?>
              <div class="form-group">
                <label class="col-xs-4 control-label">{{Suivre dans la timeline}}</label>
                <div class="col-xs-1">
                  <input type="checkbox" class="cmdAttr" data-l1key="configuration" data-l2key="timeline::enable" />
                </div>
                <div class="col-xs-7">
                  <input class="cmdAttr" data-l1key="configuration" data-l2key="timeline::folder" placeholder="{{Dossier}}" style="display:none;">
                </div>
              </div>
              <?php if ($cmd->getType() == 'info') { ?>
                <div class="form-group">
                  <label class="col-xs-4 control-label">{{Envoyer à InfluxDB}}</label>
                  <div class="col-xs-1">
                    <input type="checkbox" class="cmdAttr" data-l1key="configuration" data-l2key="influx::enable" />
                  </div>
                  <div class="col-xs-7"></div>
                </div>
                <div class="form-group selInflux" style="display:none;">
                  <label class="col-xs-4 control-label">{{Nom personnalisé Commande}}</label>
                  <div class="col-xs-8">
                    <input class="cmdAttr" data-l1key="configuration" data-l2key="influx::namecmd" placeholder="{{Facultatif}}">
                  </div>
                </div>
                <div class="form-group selInflux" style="display:none;">
                  <label class="col-xs-4 control-label">{{Nom personnalisé Équipement}}</label>
                  <div class="col-xs-8">
                    <input class="cmdAttr" data-l1key="configuration" data-l2key="influx::nameEq" placeholder="{{Facultatif}}">
                  </div>
                </div>
                <div class="form-group selInflux" style="display:none;">
                  <label class="col-xs-4 control-label">{{Nom personnalisé Valeur}}</label>
                  <div class="col-xs-8">
                    <input class="cmdAttr" data-l1key="configuration" data-l2key="influx::nameVal" placeholder="{{Facultatif}}">
                  </div>
                </div>
                <div class="form-group selInflux" style="display:none;">
                  <label class="col-xs-4 control-label">{{Actions}}</label>
                  <div class="col-xs-8">
                    <a class="btn btn-default btn-sm" id="bt_influxDelete"><i class="fas fa-trash"></i> {{Supprimer}}</a>
                    <a class="btn btn-default btn-sm" id="bt_influxHistory"><i class="fas fas fa-history"></i> {{Envoyer Historique}}</a>
                  </div>
                  <br /><br />
                </div>
              <?php } ?>
              <div class="form-group">
                <label class="col-xs-4 control-label">{{Interdire dans les interactions automatiques}}</label>
                <div class="col-xs-4">
                  <input type="checkbox" class="cmdAttr" data-l1key="configuration" data-l2key="interact::auto::disable" />
                </div>
              </div>
              <div class="iconeGeneric form-group">
                <label class="col-xs-4 control-label">{{Icône}}</label>
                <div class="col-xs-4">
                  <span class="cmdAttr label cursor" data-l1key="display" data-l2key="icon" style="font-size : 1.5em;"></span>
                  <a class="btn btn-default btn-sm" id="bt_cmdConfigureChooseIcon"><i class="fas fa-flag"></i> {{Icône}}</a>
                </div>
              </div>
              <?php if ($cmd->getIsHistorized() == 1) { ?>
                <div class="form-group">
                  <label class="col-xs-4 control-label">{{Historique}}</label>
                  <div class="col-xs-4">
                    <a class="btn btn-default btn-sm" id="bt_cmdConfigureShowHistory"><i class="fas fa-history"></i> {{Voir}}</a>
                  </div>
                </div>
              <?php } ?>
            </fieldset>
          </form>
        </div>
      </div>

      <form class="form-horizontal">
        <fieldset id="fd_cmdUsedBy">
          <legend><i class="fas fa-search"></i> {{Utilisé par}}</legend>
          <div class="input-group pull-right">
            <a class="btn btn-xs btn-warning roundedLeft" id="bt_cmdConfigureReplaceIdByMe"><i class="fas fa-upload" aria-hidden="true"></i> {{Cette commande remplace l'ID}}
            </a><a class="btn btn-xs btn-warning" id="bt_cmdConfigureReplaceByMe"><i class="fas fa-upload" aria-hidden="true"></i> {{Cette commande remplace la commande}}
            </a><a class="btn btn-xs btn-warning roundedRight" id="bt_cmdConfigureReplaceMeBy"><i class="fas fa-download" aria-hidden="true"></i> {{Remplacer cette commande par la commande}}</a>
          </div><br />
          <?php
          $usedBy = $cmd->getUsedBy();
          ?>
          <div class="form-group">
            <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Equipement}}</label>
            <div class="col-lg-10 col-md-9 col-sm-8 col-xs-6">
              <?php
              foreach ($usedBy['eqLogic'] as $usedByEqLogic) {
                if ($usedByEqLogic->getIsEnable() != 1) {
                  echo '<a href="' . $usedByEqLogic->getLinkToConfiguration() . '" class="btn btn-xs btn-info">' . $usedByEqLogic->getHumanName() . '</a><br/>';
                } else {
                  echo '<a href="' . $usedByEqLogic->getLinkToConfiguration() . '" class="btn btn-xs btn-primary">' . $usedByEqLogic->getHumanName() . '</a><br/>';
                }
              }
              ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Commandes}}</label>
            <div class="col-lg-10 col-md-9 col-sm-8 col-xs-6">
              <?php
              foreach ($usedBy['cmd'] as $usedByCmd) {
                echo '<a href="' . $usedByCmd->getEqLogic()->getLinkToConfiguration() . '" class="btn btn-xs btn-primary">' . $usedByCmd->getHumanName() . '</a><br/>';
              }
              ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Scénario}}</label>
            <div class="col-lg-10 col-md-9 col-sm-8 col-xs-6">
              <?php
              foreach ($usedBy['scenario'] as $usedByScenario) {
                if ($usedByScenario->getIsActive() != 1) {
                  echo '<a href="' . $usedByScenario->getLinkToConfiguration() . '&search=' . urlencode($cmd->getHumanName()) . '" class="btn btn-xs btn-info">' . $usedByScenario->getHumanName() . '</a><br/>';
                } else {
                  echo '<a href="' . $usedByScenario->getLinkToConfiguration() . '&search=' . urlencode($cmd->getHumanName()) . '" class="btn btn-xs btn-primary">' . $usedByScenario->getHumanName() . '</a><br/>';
                }
              }
              ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Résumés}}</label>
            <div class="col-lg-10 col-md-9 col-sm-8 col-xs-6">
              <?php
              foreach ($usedBy['object'] as $usedByObject) {
                echo '<a href="index.php?v=d&p=object&id=' . $usedByObject->getId() . '" class="btn btn-xs btn-primary">' . $usedByObject->getHumanName(false, true) . '</a><br/>';
              }
              ?>
            </div>
          </div>

          <?php foreach ($usedBy['plugin'] as $key => $values) { ?>
            <div class="form-group">
              <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Plugin}} <?php echo $key; ?></label>
              <div class="col-lg-10 col-md-9 col-sm-8 col-xs-6">
                <?php
                foreach ($values as $value) {
                  echo '<span class="btn btn-xs btn-info">' . $value->getName() . '</span><br/>';
                }
                ?>
              </div>
            </div>
          <?php } ?>
        </fieldset>
      </form>
    </div>

    <!--Configuration Tab -->
    <div role="tabpanel" class="tab-pane" id="cmd_configuration">
      <br />
      <form class="form-horizontal">
        <fieldset>
          <?php if (($cmd->getType() == 'info' && ($cmd->getSubType() == 'numeric' || $cmd->getSubType() == 'binary')) || ($cmd->getType() == 'action' && ($cmd->getSubType() == 'slider'))) {
          ?>
            <legend><i class="fas fa-table"></i> {{Calcul et arrondi}}</legend>
            <div class="form-group">
              <label class="col-md-3 col-sm-3 control-label">{{Formule de calcul}}
                <sup><i class="fas fa-question-circle" title="#value# = {{valeur de la commande}}"></i></sup>
              </label>
              <div class="col-sm-6">
                <input class="cmdAttr form-control" data-l1key="configuration" data-l2key="calculValueOffset" />
              </div>
            </div>
            <?php if ($cmd->getSubType() == 'numeric') { ?>
              <div class="form-group">
                <label class="col-md-3 col-sm-3 control-label">{{Arrondi}}
                  <sup><i class="fas fa-question-circle" title="{{Nombre de décimales}}"></i></sup>
                </label>
                <div class="col-sm-6">
                  <input class="cmdAttr form-control" data-l1key="configuration" data-l2key="historizeRound" />
                </div>
              </div>
            <?php }
            ?>
          <?php }
          ?>
        </fieldset>
      </form>
      <form class="form-horizontal">
        <fieldset>
          <legend><i class="fas fa-building"></i> {{Type générique}}</legend>
          <div class="form-group">
            <label class="col-md-3 col-sm-3 control-label">{{Valeur}}</label>
            <div class="col-sm-6">
              <select class="cmdAttr form-control" data-l1key="generic_type">
                <?php
                echo $cmd->getGenericTypeSelectOptions();
                ?>
              </select>
            </div>
          </div>
        </fieldset>
      </form>
      <?php if ($cmd->getType() == 'action') { ?>
        <form class="form-horizontal">
          <fieldset>
            <legend><i class="fas fa-exclamation-triangle"></i> {{Restriction de l'action}}</legend>
            <div class="form-group">
              <label class="col-md-3 col-sm-3 control-label">{{Confirmer l'action}}</label>
              <div class="col-sm-6">
                <input type="checkbox" class="cmdAttr" data-l1key="configuration" data-l2key="actionConfirm" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-md-3 col-sm-3 control-label">{{Code d'accès}}</label>
              <div class="col-sm-6">
                <input class="cmdAttr form-control inputPassword" data-l1key="configuration" data-l2key="actionCodeAccess" />
              </div>
            </div>
          </fieldset>
        </form>
      <?php }
      ?>
      <?php if ($cmd->getType() == 'info') {
      ?>
        <form class="form-horizontal">
          <fieldset>
            <legend><i class="fas fa-sign-out-alt"></i> {{Action sur valeur}}</legend>
            <div class="form-group">
              <label class="col-md-3 col-sm-3 col-xs-12 control-label">{{Action sur valeur, si}}</label>
              <div class="col-sm-2 col-xs-3">
                <select class="cmdAttr form-control" data-l1key="configuration" data-l2key="jeedomCheckCmdOperator">
                  <option value="==">{{égal}}</option>
                  <?php if ($cmd->getSubType() == 'numeric') { ?>
                    <option value=">">{{supérieur}}</option>
                    <option value="<">{{inférieur}}</option>
                  <?php }
                  if ($cmd->getSubType() != 'binary') { ?>
                    <option value="!=">{{différent}}</option>
                  <?php } ?>
                </select>
              </div>
              <div class="col-sm-2 col-xs-3">
                <input class="cmdAttr form-control" data-l1key="configuration" data-l2key="jeedomCheckCmdTest" />
              </div>
              <label class="col-sm-2 col-xs-3 control-label">{{pendant plus de}}
                <sup><i class="fas fa-question-circle" title="{{durée en minutes (laisser vide pour une action immédiate)}}"></i></sup>
              </label>
              <div class="col-sm-2 col-xs-3">
                <input type="number" class="cmdAttr form-control" data-l1key="configuration" data-l2key="jeedomCheckCmdTime" />
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-3 col-sm-3 control-label">{{Action}}</label>
              <div class="col-sm-6">
                <a class="btn btn-xs" id="bt_addActionCheckCmd"><i class="fas fa-plus-circle"></i> {{Ajouter}}</a>
              </div>
            </div>
            <div id="div_actionCheckCmd"></div>

            <script type="text/javascript">
              $("#div_actionCheckCmd").sortable({
                axis: "y",
                cursor: "move",
                items: ".actionCheckCmd",
                placeholder: "ui-state-highlight",
                tolerance: "intersect",
                forcePlaceholderSize: true
              });

              $('#bt_addActionCheckCmd').off('click').on('click', function() {
                addActionCmd({}, 'actionCheckCmd', '{{Action}}');
              });
            </script>
          </fieldset>
        </form>
      <?php }
      ?>
      <?php if ($cmd->getType() == 'action') {
      ?>
        <form class="form-horizontal">
          <fieldset>
            <legend><i class="fas fa-sign-out-alt"></i> {{Action avant exécution de la commande}}</legend>
            <div class="form-group">
              <label class="col-md-3 col-sm-3 control-label">{{Action}}</label>
              <div class="col-sm-6">
                <a class="btn btn-xs" id="bt_addActionPreExecCmd"><i class="fas fa-plus-circle"></i> {{Ajouter}}</a>
              </div>
            </div>
            <div id="div_actionPreExecCmd"></div>
            <script type="text/javascript">
              $("#div_actionPreExecCmd").sortable({
                axis: "y",
                cursor: "move",
                items: ".actionPreExecCmd",
                placeholder: "ui-state-highlight",
                tolerance: "intersect",
                forcePlaceholderSize: true
              });
              $('#bt_addActionPreExecCmd').off('click').on('click', function() {
                addActionCmd({}, 'actionPreExecCmd', '{{Action}}');
              });
            </script>
          </fieldset>
        </form>
        <form class="form-horizontal">
          <fieldset>
            <legend><i class="fas fa-sign-out-alt"></i> {{Action après exécution de la commande}}</legend>
            <div class="form-group">
              <label class="col-md-3 col-sm-3 control-label">{{Action}}</label>
              <div class="col-sm-6">
                <a class="btn btn-xs" id="bt_addActionPostExecCmd"><i class="fas fa-plus-circle"></i> {{Ajouter}}</a>
              </div>
            </div>
            <div id="div_actionPostExecCmd"></div>

            <script type="text/javascript">
              $("#div_actionPostExecCmd").sortable({
                axis: "y",
                cursor: "move",
                items: ".actionPostExecCmd",
                placeholder: "ui-state-highlight",
                tolerance: "intersect",
                forcePlaceholderSize: true
              });
              $('#bt_addActionPostExecCmd').off('click').on('click', function() {
                addActionCmd({}, 'actionPostExecCmd', '{{Action}}');
              });
            </script>
          </fieldset>
        </form>
      <?php } ?>

      <?php if ($cmd->getType() == 'info' && $JEEDOM_INTERNAL_CONFIG['cmd']['type']['info']['subtype'][$cmd->getSubType()]['isHistorized']['visible']) {
      ?>
        <form class="form-horizontal">
          <fieldset>
            <legend><i class="far fa-chart-bar"></i> {{Historique}}</legend>
            <div class="form-group">
              <label class="col-md-3 col-sm-3 control-label">{{Historiser}}</label>
              <div class="col-sm-6">
                <input type="checkbox" class="cmdAttr" data-l1key="isHistorized" />
                <?php if ($cmd->getIsHistorized() == 1) { ?>
                  <a class="btn btn-xs btn-warning pull-right" id="bt_cmdConfigureCopyHistory"><i class="fas fa-clone"></i> {{Copier historique de cette commande sur une autre commande}}</a>
                <?php } ?>
              </div>
            </div>
            <?php if ($JEEDOM_INTERNAL_CONFIG['cmd']['type']['info']['subtype'][$cmd->getSubType()]['isHistorized']['canBeSmooth']) { ?>
              <div class="form-group">
                <label class="col-md-3 col-sm-3 control-label">{{Mode de lissage}}</label>
                <div class="col-sm-6">
                  <select class="form-control cmdAttr" data-l1key="configuration" data-l2key="historizeMode">
                    <option value="avg">{{Moyenne}}</option>
                    <option value="min">{{Minimum}}</option>
                    <option value="max">{{Maximum}}</option>
                    <option value="none">{{Aucun}}</option>
                  </select>
                </div>
              </div>
            <?php }
            ?>
            <div class="form-group">
              <label class="col-md-3 col-sm-3 control-label">{{Purger historique}}
                <sup><i class="fas fa-question-circle" title="{{si plus vieux que}}"></i></sup>
              </label>
              <div class="col-sm-6">
                <select class="form-control cmdAttr" data-l1key="configuration" data-l2key="historyPurge">
                  <option value="-1 day">{{1 jour}}</option>
                  <option value="-7 days">{{7 jours}}</option>
                  <option value="-1 month">{{1 mois}}</option>
                  <option value="-3 month">{{3 mois}}</option>
                  <option value="-6 month">{{6 mois}}</option>
                  <option value="-1 year">{{1 an}}</option>
                  <option value="-2 years">{{2 ans}}</option>
                  <option value="-3 years">{{3 ans}}</option>
                  <option value="" selected>{{Jamais}}</option>
                </select>
              </div>
            </div>
          </fieldset>
        </form>
      <?php }
      if ($cmd->getType() == 'info') { ?>
        <form class="form-horizontal">
          <fieldset>
            <legend><i class="fas fa-thermometer-three-quarters"></i> {{Gestion des valeurs}}</legend>
            <div class="form-group">
              <label class="col-md-3 col-sm-3 control-label">{{Valeurs interdites}}
                <sup><i class="fas fa-question-circle" title="{{séparées par un point-virgule}}"></i></sup>
              </label>
              <div class="col-sm-6">
                <input class="cmdAttr form-control" data-l1key="configuration" data-l2key="denyValues" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-md-3 col-sm-3 control-label">{{Valeur retour d'état}}</label>
              <div class="col-sm-6">
                <input class="cmdAttr form-control" data-l1key="configuration" data-l2key="returnStateValue" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-md-3 col-sm-3 control-label">{{Durée avant retour d'état}}
                <sup><i class="fas fa-question-circle" title="{{en minutes}}"></i></sup>
              </label>
              <div class="col-sm-6">
                <input class="cmdAttr form-control" data-l1key="configuration" data-l2key="returnStateTime" />
              </div>
            </div>

            <?php if ($cmd->getSubType() == 'binary') { ?>
            <div class="form-group">
              <label class="col-md-3 col-sm-3 control-label">{{Inverser les valeurs binaires}}
                <sup><i class="fas fa-question-circle" title="{{Contrairement à l'option <b>inverser</b> sur la ligne d'une commande qui n'inverse que lors de l'affichage, cette option inverse la valeur binaire reçue sur la commande (en base)}}"></i></sup>
              </label>
              <div class="col-sm-6">
                <input type="checkbox" class="cmdAttr" data-l1key="configuration" data-l2key="invertBinary" />
              </div>
            </div>
            <?php } ?>
          </fieldset>
        </form>
        <form class="form-horizontal">
          <fieldset>
            <legend><i class="fas fa-plus"></i> {{Autres}}</legend>
            <div class="form-group">
              <label class="col-md-3 col-sm-3 control-label">{{Répéter les valeurs identiques}}</label>
              <div class="col-sm-6">
                <select class="cmdAttr form-control" data-l1key="configuration" data-l2key="repeatEventManagement">
                  <option value="never">{{Non}}</option>
                  <option value="always">{{Oui}}</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-md-3 col-sm-3 control-label">{{Push URL}}</label>
              <div class="col-sm-6">
                <input class="cmdAttr form-control tooltips" data-l1key="configuration" data-l2key="jeedomPushUrl" title="{{URL à appeler lors d'une mise à jour de la valeur de la commande. Vous pouvez utiliser les tags suivants : #value# (valeur de la commande), #cmd_id# (id de la commande) et #cmd_name# (nom de la commande)}}" />
              </div>
            </div>
          </fieldset>
        </form>
      <?php }
      ?>
    </div>
    <?php if ($cmd->getType() == 'info') {
    ?>

    <!--Warning Tab -->
    <div role="tabpanel" class="tab-pane" id="cmd_alert">
      <br />
      <?php
      $form = '';
      foreach ($JEEDOM_INTERNAL_CONFIG['alerts'] as $level => $value) {
        if (!$value['check']) {
          continue;
        }
        $form .= '<form class="form-horizontal">';
        $form .= '<fieldset>';
        $form .= '<legend>';
        if ($value['name'] == 'Warning') {
          $form .= '<i class="fas fa-exclamation"></i>';
        } else if ($value['name'] == 'Danger') {
          $form .= '<i class="fas fa-exclamation-triangle"></i>';
        }
        $form .= '{{Niveau}} ' . $value['name'] . '</legend>';
        $form .= '<div class="form-group">';
        $form .= '<label class="col-md-2 col-sm-3 control-label">{{En}} ' . $value['name'] . ' {{si}} ';
        $form .= '<sup><i class="fas fa-question-circle" title="#value# = {{valeur de la commande}}"></i></sup></label>';
        $form .= '<div class="col-sm-6">';
        $form .= '<input class="cmdAttr form-control" data-l1key="alert" data-l2key="' . $level . 'if" />';
        $form .= '</div>';
        $form .= '</div>';
        $form .= '<div class="form-group">';
        $form .= '<label class="col-md-2 col-sm-3 control-label">{{Pendant plus de}} ';
        $form .= '<sup><i class="fas fa-question-circle" title="{{durée en minutes (laisser vide pour une action immédiate)}}"></i></sup></label>';

        $form .= '<div class="col-sm-6">';
        $form .= '<input type="number" class="cmdAttr form-control" data-l1key="alert" data-l2key="' . $level . 'during" />';
        $form .= '</div>';
        $form .= '</div>';
        $form .= '</fieldset>';
        $form .= '</form>';
      }
      echo $form;
      ?>
    </div>
    <?php }
    ?>

    <!--Display Tab if supported -->
    <?php if ($cmd->widgetPossibility('custom')) {
    ?>
    <div role="tabpanel" class="tab-pane" id="cmd_display">
      <br />
      <legend><i class="fas fa-tint"></i> {{Widget}}</legend>
      <table class="table table-bordered table-condensed">
        <thead>
          <tr>
            <th style="width:30%;"></th>
            <?php
            $display = '';
            foreach ($configEqDisplayType as $key => $value) {
              $display .= '<th>' . $value['name'] . '</th>';
            }
            echo $display;
            ?>
          </tr>
        </thead>
        <tbody>
          <?php if ($cmd->widgetPossibility('custom::widget')) {
          ?>
            <tr>
              <td>{{Widget}}</td>
              <td>
                <?php if ($cmd->widgetPossibility('custom::widget::dashboard')) {
                ?>
                  <select class="form-control cmdAttr" data-l1key="template" data-l2key="dashboard">
                    <?php
                    echo $cmd->getWidgetsSelectOptions('dashboard', $cmd_widgetDashboard);
                    ?>
                  </select>
                <?php }
                ?>
              </td>
              <td>
                <?php if ($cmd->widgetPossibility('custom::widget::mobile')) {
                ?>
                  <select class="form-control cmdAttr" data-l1key="template" data-l2key="mobile">
                    <?php
                    echo $cmd->getWidgetsSelectOptions('mobile', $cmd_widgetMobile);
                    ?>
                  </select>
                <?php }
                ?>
              </td>
              <td style="width: 1px;">
                <a class="btn btn-default btn-xs" id="bt_cmdConfigureSaveOn"><i class="fas fa-arrow-alt-circle-down"></i> {{Appliquer à}}</a>
              </td>
            </tr>
          <?php }
          ?>
          <?php if ($cmd->widgetPossibility('custom::displayName')) {
          ?>
            <tr>
              <td>{{Afficher le nom}}</td>
              <?php
              $display = '';
              foreach ($configEqDisplayType as $key => $value) {
                $display .= '<td>';
                if ($cmd->widgetPossibility('custom::displayName::' . $key)) {
                  $display .= '<input type="checkbox" class="cmdAttr" data-l1key="display" data-l2key="showNameOn' . $key . '" checked />';
                }
                $display .= '</td>';
              }
              echo $display;
              ?>
            </tr>
          <?php }
          ?>
          <?php if ($cmd->widgetPossibility('custom::displayIconAndName')) {
          ?>
            <tr>
              <td>{{Afficher le nom ET l'icône}}</td>
              <?php
              $display = '';
              foreach ($configEqDisplayType as $key => $value) {
                $display .= '<td>';
                if ($cmd->widgetPossibility('custom::displayIconAndName::' . $key)) {
                  $display .= '<input type="checkbox" class="cmdAttr" data-l1key="display" data-l2key="showIconAndName' . $key . '" />';
                }
                $display .= '</td>';
              }
              echo $display;
              ?>
            </tr>
          <?php }
          ?>
          <?php if (config::byKey('displayStatsWidget') == 1 && $cmd->getSubType() != 'string' && $cmd->widgetPossibility('custom::displayStats')) { ?>
            <tr>
              <td>{{Afficher les statistiques}}</td>
              <?php
              $display = '';
              foreach ($configEqDisplayType as $key => $value) {
                $display .= '<td>';
                if ($cmd->widgetPossibility('custom::displayStats::' . $key)) {
                  $display .= '<input type="checkbox" class="cmdAttr" data-l1key="display" data-l2key="showStatsOn' . $key . '" checked />';
                }
                $display .= '</td>';
              }
              echo $display;
              ?>
            </tr>
          <?php
          }
          ?>
          <tr>
            <td>{{Retour à la ligne forcé avant le widget}}</td>
            <td><input type="checkbox" class="cmdAttr" data-l1key="display" data-l2key="forceReturnLineBefore" /></td>
          </tr>
          <tr>
            <td>{{Retour à la ligne forcé après le widget}}</td>
            <td><input type="checkbox" class="cmdAttr" data-l1key="display" data-l2key="forceReturnLineAfter" /></td>
          </tr>
        </tbody>
      </table>

      <?php if ($cmd->widgetPossibility('custom::optionalParameters')) {
      ?>
        <legend><i class="fas fa-pencil-ruler"></i> {{Paramètres optionnels widget}}
          <a class="btn btn-xs pull-right" id="bt_addWidgetParametersCmd" style="position:relative;right:5px;"><i class="fas fa-plus-circle"></i> {{Ajouter}}</a>
        </legend>

        <div id="optionalParamHelp"></div>

        <table class="table table-bordered table-condensed" id="table_widgetParametersCmd">
          <thead class="table table-bordered">
            <tr>
              <th style="width: 20%">Nom</th>
              <th style="width: 80%">Valeur</th>
              <th style="width: 1px">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if ($cmd->getDisplay('parameters') != '') {
              $tr = '';
              foreach (($cmd->getDisplay('parameters')) as $key => $value) {
                $tr .= '<tr>';
                $tr .= '<td>';
                $tr .= '<input class="form-control key" value="' . $key . '" />';
                $tr .= '</td>';
                $tr .= '<td>';
                $tr .= '<input class="form-control value" value="' . $value . '" />';
                $tr .= '</td>';
                $tr .= '<td>';
                $tr .= '<a class="btn btn-danger btn-xs removeWidgetParameter pull-right"><i class="fas fa-times"></i> Supprimer</a>';
                $tr .= '</td>';
                $tr .= '</tr>';
              }
              echo $tr;
            }
            ?>
          </tbody>
        </table>
      <?php
      }
      ?>
    </div>
    <?php }
    ?>
  </div>

  <div id="md_cmdConfigureSelectMultiple" title="{{Sélection multiple de commandes}}"></div>
</div>

<script>
  $(function() {
    if ($('body').attr('data-page') == "widgets") {
      $('a[href="#cmd_display"]').click()
    }

    //modal title:
    var title = '{{Configuration commande}}'
    title += ' : ' + cmdInfo.eqLogicName
    title += ' <span class="cmdName">[' + cmdInfo.name + '] <em>(' + cmdInfo.type + ')</em></span>'
    $('#cmdConfigureTab').parents('.ui-dialog').find('.ui-dialog-title').html(title)
    if ($('#eqLogicConfigureTab').length) {
      $('#cmdConfigureTab').parents('.ui-dialog').css('top', "50px")
    }

    //widgets default if empty:
    var dashWidget = $('select[data-l2key="dashboard"]')
    if (dashWidget.val() == null) dashWidget.val($('select[data-l2key="dashboard"] option:first').val())
    var mobileWidget = $('select[data-l2key="mobile"]')
    if (mobileWidget.val() == null) mobileWidget.val($('select[data-l2key="mobile"] option:first').val())

    //format update linked cmds:
    var spanValues = $('#cmd_information .cmdAttr[data-l1key="value"]')
    var values = spanValues.html()
    spanValues.hide()
    if (values != '') {
      var arrValues = values.split('#')
      var spans = ''
      var span
      arrValues.forEach(function(thisValue) {
        if (thisValue != '' && !thisValue.includes('#')) {
          jeedom.cmd.getHumanCmdName({
            id: thisValue,
            error: function(error) {
              $.fn.showAlert({
                message: error.message,
                level: 'danger'
              })
            },
            success: function(data) {
              var span = '<span class="label label-primary">' + data + '</span><br>'
              spanValues.parent().prepend(span)
            }
          })
        } else {
          if (thisValue != '') {
            span = '<span class="label label-primary">#' + thisValue + '#</span><br>'
            spanValues.parent().prepend(span)
          }
        }
      })
    }

    jeedom.timeline.autocompleteFolder()

    displayWidgetHelp($('#cmd_display select[data-l1key="template"][data-l2key="dashboard"]').val())

    $('#cmd_display select[data-l1key="template"][data-l2key="dashboard"]').off('change').on('change', function() {
      displayWidgetHelp($(this).val())
    })
  })

  function displayWidgetHelp(widgetName) {
    jeedom.cmd.getWidgetHelp({
      id: $('#cmd_information span[data-l1key="id"]').text(),
      version: 'dashboard',
      widgetName: widgetName,
      error: function(error) {
        $('#optionalParamHelp').empty().text('{{Pas de description des paramètres optionnels sur ce Widget.}}')
      },
      success: function(data) {
        $('#optionalParamHelp').empty().html(data.html)
      }
    })
  }

  $('.cmdAttr[data-l2key="timeline::enable"]').off('change').on('change', function() {
    if ($(this).value() == 1) {
      $('.cmdAttr[data-l2key="timeline::folder"]').show()
    } else {
      $('.cmdAttr[data-l2key="timeline::folder"]').hide()
    }
  })

  $('.cmdAttr[data-l2key="influx::enable"]').off('change').on('change', function() {
    if ($(this).value() == 1) {
      $('.selInflux').show()
    } else {
      $('.selInflux').hide()
    }
  })

  $('#cmdConfigureTab').off('click').on('click', function() {
    setTimeout(function() {
      jeedomUtils.taAutosize()
    }, 100)
  })

  $("#md_cmdConfigureSelectMultiple").dialog({
    closeText: '',
    autoOpen: false,
    modal: true,
    height: (jQuery(window).height() - 150),
    width: ((jQuery(window).width() - 150) < 1200) ? (jQuery(window).width() - 50) : 1200,
    position: {
      my: 'center',
      at: 'center',
      of: window
    },
    open: function() {
      $("body").css({
        overflow: 'hidden'
      })
    },
    beforeClose: function(event, ui) {
      $("body").css({
        overflow: 'inherit'
      })
    }
  })

  $('#table_widgetParametersCmd').on({
    'click': function(event) {
      $(this).closest('tr').remove()
    }
  }, '.removeWidgetParameter')

  $('#bt_addWidgetParametersCmd').off().on('click', function() {
    var tr = '<tr>'
    tr += '<td>'
    tr += '<input class="form-control key" />'
    tr += '</td>'
    tr += '<td>'
    tr += '<input class="form-control value" />'
    tr += '</td>'
    tr += '<td>'
    tr += '<a class="btn btn-danger btn-xs removeWidgetParameter pull-right"><i class="fas fa-times"></i> Supprimer</a>'
    tr += '</td>'
    tr += '</tr>'
    $('#table_widgetParametersCmd tbody').append(tr)
  })

  if (cmdInfo.configuration && (!cmdInfo.configuration.repeatEventManagement || cmdInfo.configuration.repeatEventManagement == 'auto')) {
    cmdInfo.configuration.repeatEventManagement = 'never';
  }
  $('#div_displayCmdConfigure').setValues(cmdInfo, '.cmdAttr')

  $('#bt_cmdConfigureRawObject').off('click').on('click', function() {
    $('#md_modal3').dialog({
      title: "{{Informations}}"
    }).load('index.php?v=d&modal=object.display&class=cmd&id=' + cmdInfo.id).dialog('open')
  })

  $('#bt_cmdConfigureGraph').on('click', function() {
    $('#md_modal3').dialog({
      title: "{{Graphique des liens}}"
    }).load('index.php?v=d&modal=graph.link&filter_type=cmd&filter_id=' + cmdInfo.id).dialog('open')
  })

  $('#bt_influxDelete').off('click').on('click', function() {
    bootbox.confirm('{{Êtes-vous sûr de vouloir supprimer toutes les infos de cette commande d\'InfluxDB}}', function(result) {
      if (result) {
        jeedom.cmd.dropInflux({
          cmd_id: cmdInfo.id,
          error: function(error) {
            $('#md_displayCmdConfigure').showAlert({
              message: error.message,
              level: 'danger'
            })
          },
          success: function(data) {
            $('#md_displayCmdConfigure').showAlert({
              message: '{{Action envoyée avec succés}}',
              level: 'success'
            })
          }
        })
      }
    })
  })

  $('#bt_influxHistory').off('click').on('click', function() {
    bootbox.confirm('{{Êtes-vous sûr de vouloir envoyer tout l\'historique de cette commande à InfluxDB. Cela sera programmé et effectué en tâche de fond dans une minute.}}', function(result) {
      if (result) {
        jeedom.cmd.historyInflux({
          cmd_id: cmdInfo.id,
          error: function(error) {
            $('#md_displayCmdConfigure').showAlert({
              message: error.message,
              level: 'danger'
            })
          },
          success: function(data) {
            $('#md_displayCmdConfigure').showAlert({
              message: '{{Programmation envoyée avec succés}}',
              level: 'success'
            })
          }
        })
      }
    })
  })

  $('#bt_cmdConfigureCopyHistory').off('click').on('click', function() {
    jeedom.cmd.getSelectModal({
      cmd: {
        type: 'info',
        subType: cmdInfo.subType
      }
    }, function(result) {
      var target_id = result.cmd.id
      var name = result.human
      bootbox.confirm('{{Êtes-vous sûr de vouloir copier l\'historique de}} <strong>' + cmdInfo.name + '</strong> {{vers}} <strong>' + name + '</strong> ? {{Il est conseillé de vider l\'historique de la commande}} : <strong>' + name + '</strong> {{avant la copie}}', function(result) {
        if (result) {
          jeedom.history.copyHistoryToCmd({
            source_id: cmdInfo.id,
            target_id: target_id,
            error: function(error) {
              $('#md_displayCmdConfigure').showAlert({
                message: error.message,
                level: 'danger'
              })
            },
            success: function(data) {
              $('#md_displayCmdConfigure').showAlert({
                message: '{{Historique copié avec succès}}',
                level: 'success'
              })
            }
          })
        }
      })
    })
  })

  $('#bt_cmdConfigureReplaceMeBy').off('click').on('click', function() {
    jeedom.cmd.getSelectModal({
      cmd: {
        type: cmdInfo.type,
        subType: cmdInfo.subType
      }
    }, function(result) {
      var target_id = result.cmd.id
      var name = result.human
      bootbox.confirm('{{Êtes-vous sûr de vouloir remplacer}} <strong>' + cmdInfoString + '</strong> {{par}} : <br/><strong>' + name + '</strong> ?', function(result) {
        if (result) {
          jeedom.cmd.replaceCmd({
            source_id: cmdInfo.id,
            target_id: target_id,
            error: function(error) {
              $('#md_displayCmdConfigure').showAlert({
                message: error.message,
                level: 'danger'
              })
            },
            success: function(data) {
              $('#md_displayCmdConfigure').showAlert({
                message: '{{Remplacement réalisé avec succès}}',
                level: 'success'
              })
            }
          })
        }
      })
    })
  })

  $('#bt_cmdConfigureReplaceByMe').off('click').on('click', function() {
    jeedom.cmd.getSelectModal({
      cmd: {
        type: cmdInfo.type,
        subType: cmdInfo.subType
      }
    }, function(result) {
      var target_id = result.cmd.id
      var name = result.human
      bootbox.confirm('{{Êtes-vous sûr de vouloir remplacer l\'ID}} <strong>' + name + '</strong> {{par}} : <br/><strong>' + cmdInfoString + '</strong> ?', function(result) {
        if (result) {
          jeedom.cmd.replaceCmd({
            source_id: target_id,
            target_id: cmdInfo.id,
            error: function(error) {
              $('#md_displayCmdConfigure').showAlert({
                message: error.message,
                level: 'danger'
              })
            },
            success: function(data) {
              $('#md_displayCmdConfigure').showAlert({
                message: '{{Remplacement réalisé avec succès}}',
                level: 'success'
              })
            }
          })
        }
      })
    })
  })

  $('#bt_cmdConfigureReplaceIdByMe').off('click').on('click', function() {
    var target_id = prompt("{{ID de commande à remplacer ?}}")
    if (target_id == null) {
      return
    }
    bootbox.confirm('{{Êtes-vous sûr de vouloir remplacer la commande}} id : <strong>' + target_id + '</strong> {{par}} : <br/><strong>' + cmdInfoString + '</strong> ?', function(result) {
      if (result) {
        jeedom.cmd.replaceCmd({
          source_id: target_id,
          target_id: cmdInfo.id,
          error: function(error) {
            $('#md_displayCmdConfigure').showAlert({
              message: error.message,
              level: 'danger'
            })
          },
          success: function(data) {
            $('#md_displayCmdConfigure').showAlert({
              message: '{{Remplacement réalisé avec succès}}',
              level: 'success'
            })
          }
        })
      }
    })
  })


  if (isset(cmdInfo.configuration.actionCheckCmd) && $.isArray(cmdInfo.configuration.actionCheckCmd) && cmdInfo.configuration.actionCheckCmd.length != null) {
    for (var i in cmdInfo.configuration.actionCheckCmd) {
      addActionCmd(cmdInfo.configuration.actionCheckCmd[i], 'actionCheckCmd', '{{Action}}')
    }
  }

  if (isset(cmdInfo.configuration.jeedomPreExecCmd) && $.isArray(cmdInfo.configuration.jeedomPreExecCmd) && cmdInfo.configuration.jeedomPreExecCmd.length != null) {
    for (var i in cmdInfo.configuration.jeedomPreExecCmd) {
      addActionCmd(cmdInfo.configuration.jeedomPreExecCmd[i], 'actionPreExecCmd', '{{Action}}')
    }
  }

  if (isset(cmdInfo.configuration.jeedomPostExecCmd) && $.isArray(cmdInfo.configuration.jeedomPostExecCmd) && cmdInfo.configuration.jeedomPostExecCmd.length != null) {
    for (var i in cmdInfo.configuration.jeedomPostExecCmd) {
      addActionCmd(cmdInfo.configuration.jeedomPostExecCmd[i], 'actionPostExecCmd', '{{Action}}')
    }
  }
  jeedomUtils.taAutosize()

  $('#bt_cmdConfigureSave').on('click', function(event) {
    var cmd = $('#div_displayCmdConfigure').getValues('.cmdAttr')[0]
    if (!isset(cmd.display)) {
      cmd.display = {}
    }
    if (!isset(cmd.display.parameters)) {
      cmd.display.parameters = {}
    }
    $('#table_widgetParametersCmd tbody tr').each(function() {
      cmd.display.parameters[$(this).find('.key').value()] = $(this).find('.value').value()
    })
    var checkCmdParameter = $('#div_jeedomCheckCmdCmdOption').getValues('.expressionAttr')[0]
    if (isset(checkCmdParameter) && isset(checkCmdParameter.options)) {
      cmd.configuration.jeedomCheckCmdCmdActionOption = checkCmdParameter.options
    }
    cmd.configuration.actionCheckCmd = {};
    cmd.configuration.actionCheckCmd = $('#div_actionCheckCmd .actionCheckCmd').getValues('.expressionAttr')
    cmd.configuration.jeedomPreExecCmd = $('#div_actionPreExecCmd .actionPreExecCmd').getValues('.expressionAttr')
    cmd.configuration.jeedomPostExecCmd = $('#div_actionPostExecCmd .actionPostExecCmd').getValues('.expressionAttr')
    jeedom.cmd.save({
      cmd: cmd,
      error: function(error) {
        $('#md_displayCmdConfigure').showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function() {
        modifyWithoutSave = false
        $('#md_displayCmdConfigure').showAlert({
          message: '{{Sauvegarde réussie}}',
          level: 'success'
        })
        synchModalToCmd()
        if (event.ctrlKey) {
          setTimeout(function() {
            $('#md_modal').dialog('close')
          }, 500)
        }
      }
    })
  })

  function synchModalToCmd() {
    var cmdId = $('#div_displayCmdConfigure .cmdAttr[data-l1key="id"]').text()
    var $cmdTr = $('#div_pageContainer tr[data-cmd_id="' + cmdId + '"]')
    if ($cmdTr) {
      $cmdTr.find('input.cmdAttr[data-l1key="isVisible"]').prop('checked', $('#div_displayCmdConfigure input.cmdAttr[data-l1key="isVisible"').prop('checked'))
      $cmdTr.find('.cmdAttr[data-l1key=display][data-l2key=icon]').html($('#div_displayCmdConfigure .cmdAttr[data-l1key=display][data-l2key=icon]').html())
    }
  }

  $('#cmd_configuration').on({
    'click': function(event) {
      var type = $(this).attr('data-type')
      $(this).closest('.' + type).remove()
    }
  }, '.bt_removeAction')

  $('#cmd_configuration').on({
    'click': function(event) {
      var type = $(this).attr('data-type');
      var el = $(this).closest('.' + type).find('.expressionAttr[data-l1key=cmd]')
      jeedom.cmd.getSelectModal({
        cmd: {
          type: 'action'
        }
      }, function(result) {
        el.value(result.human)
        jeedom.cmd.displayActionOption(el.value(), '', function(html) {
          el.closest('.' + type).find('.actionOptions').html(html)
          jeedomUtils.taAutosize()
        })
      })
    }
  }, '.listCmd')

  $('#cmd_configuration').on({
    'click': function(event) {
      var type = $(this).attr('data-type')
      var el = $(this).closest('.' + type).find('.expressionAttr[data-l1key=cmd]')
      jeedom.getSelectActionModal({}, function(result) {
        el.value(result.human)
        jeedom.cmd.displayActionOption(el.value(), '', function(html) {
          el.closest('.' + type).find('.actionOptions').html(html)
          jeedomUtils.taAutosize()
        })
      })
    }
  }, '.listAction')

  $('#cmd_configuration').on({
    'focusout': function(event) {
      var type = $(this).attr('data-type')
      var expression = $(this).closest('.' + type).getValues('.expressionAttr')
      var el = $(this)
      jeedom.cmd.displayActionOption($(this).value(), init(expression[0].options), function(html) {
        el.closest('.' + type).find('.actionOptions').html(html)
        jeedomUtils.taAutosize()
      })
    }
  }, '.cmdAction.expressionAttr[data-l1key=cmd]')

  function addActionCmd(_action, _type, _name) {
    if (!isset(_action)) {
      _action = {}
    }
    if (!isset(_action.options)) {
      _action.options = {}
    }
    var div = '<div class="expression ' + _type + '">'
    div += '<input class="expressionAttr" data-l1key="type" style="display : none;" value="action">'
    div += '<div class="form-group ">'
    div += '<div class="col-sm-1">'
    div += '<input type="checkbox" class="expressionAttr" data-l1key="options" data-l2key="enable" checked title="{{Décocher pour désactiver l\'action}}" />'
    div += '<input type="checkbox" class="expressionAttr" data-l1key="options" data-l2key="background" title="{{Cocher pour que la commande s\'exécute en parallèle des autres actions}}" />'
    div += '</div>'
    div += '<div class="col-sm-4">'
    div += '<div class="input-group">'
    div += '<span class="input-group-btn">'
    div += '<a class="btn btn-default btn-sm bt_removeAction roundedLeft" data-type="' + _type + '"><i class="fas fa-minus-circle"></i></a>'
    div += '</span>'
    div += '<input class="expressionAttr form-control input-sm cmdAction" data-l1key="cmd" data-type="' + _type + '" />'
    div += '<span class="input-group-btn">'
    div += '<a class="btn  btn-default btn-sm listAction" data-type="' + _type + '" title="{{Sélectionner un mot-clé}}"><i class="fa fa-tasks"></i></a>'
    div += '<a class="btn btn-default btn-sm listCmd roundedRight" data-type="' + _type + '"><i class="fas fa-list-alt"></i></a>'
    div += '</span>'
    div += '</div>'
    div += '</div>'
    div += '<div class="col-sm-7 actionOptions">'
    div += jeedom.cmd.displayActionOption(init(_action.cmd, ''), _action.options)
    div += '</div>'
    $('#div_' + _type).append(div)
    $('#div_' + _type + ' .' + _type + '').last().setValues(_action, '.expressionAttr')

    jeedom.scenario.setAutoComplete({
      parent: $('#div_' + _type),
      type: 'cmd'
    })
  }

  $('#bt_cmdConfigureSaveOn').on('click', function() {
    var cmd = $('#div_displayCmdConfigure').getValues('.cmdAttr')[0]
    if (!isset(cmd.display)) {
      cmd.display = {}
    }
    if (!isset(cmd.display.parameters)) {
      cmd.display.parameters = {}
    }
    $('#table_widgetParametersCmd tbody tr').each(function() {
      cmd.display.parameters[$(this).find('.key').value()] = $(this).find('.value').value()
    })
    cmd = {
      display: cmd.display,
      template: cmd.template
    }
    $('#md_cmdConfigureSelectMultiple').dialog({
      title: "{{Appliquer ce widget à}}"
    }).load('index.php?v=d&modal=cmd.selectMultiple&cmd_id=' + cmdInfo.id, function() {
      jeedomUtils.initTableSorter()
      $('#bt_cmdConfigureSelectMultipleAlertToogle').off('click').on('click', function() {
        var state = false
        if ($(this).attr('data-state') == 0) {
          state = true
          $(this).attr('data-state', 1).find('i').removeClass('fa-check-circle-o').addClass('fa-circle-o')
          $('#table_cmdConfigureSelectMultiple tbody tr .selectMultipleApplyCmd:visible').value(1)
        } else {
          state = false
          $(this).attr('data-state', 0).find('i').removeClass('fa-circle-o').addClass('fa-check-circle-o')
          $('#table_cmdConfigureSelectMultiple tbody tr .selectMultipleApplyCmd:visible').value(0)
        }
      });

      $('#bt_cmdConfigureSelectMultipleAlertApply').off().on('click', function() {
        $('#table_cmdConfigureSelectMultiple tbody tr').each(function() {
          if ($(this).find('.selectMultipleApplyCmd').prop('checked')) {
            cmd.id = $(this).attr('data-cmd_id')
            jeedom.cmd.save({
              cmd: cmd,
              error: function(error) {
                $('#md_cmdConfigureSelectMultipleAlert').showAlert({
                  message: error.message,
                  level: 'danger'
                })
              },
              success: function() {}
            })
          }
        })
        $('#md_cmdConfigureSelectMultipleAlert').showAlert({
          message: "{{Modification(s) appliquée(s) avec succès}}",
          level: 'success'
        })
      })
    }).dialog('open')
  })

  $('#bt_cmdConfigureChooseIcon').on('click', function() {
    var iconeGeneric = $(this).closest('.iconeGeneric')
    jeedomUtils.chooseIcon(function(_icon) {
      iconeGeneric.find('.cmdAttr[data-l1key=display][data-l2key=icon]').empty().append(_icon)
    })
  })

  $('#cmd_information').on({
    'click': function(event) {
      $(this).empty()
    }
  }, '.cmdAttr[data-l1key=display][data-l2key=icon]')

  $('#bt_cmdConfigureLogRealTime').off('click').on('click', function() {
    $('#md_modal3').dialog({
      title: "{{Logs}}"
    }).load('index.php?v=d&modal=log.display&log=event&search=' + cmdInfoSearchString).dialog('open')
  })

  $('#bt_cmdConfigureShowHistory').off('click').on('click', function() {
    $('#md_modal3').dialog({
      title: "Historique"
    }).load('index.php?v=d&modal=cmd.history&id=' + cmdInfo.id).dialog('open')
  })

  $('#bt_cmdConfigureTest').off('click').on('click', function() {
    jeedom.cmd.test({
      id: cmdInfo.id,
      alert: '#md_displayCmdConfigure'
    })
  })
</script>