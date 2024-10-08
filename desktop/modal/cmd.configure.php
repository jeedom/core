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
$cmdInfo['eqLogicHumanName'] = $cmd->getEqLogic()->getHumanName();
$cmdInfo['cache'] = $cmd->getCache();
sendVarToJS([
  'jeephp2js.md_cmdConfigure_cmdInfo' => $cmdInfo,
  'jeephp2js.md_cmdConfigure_cmdInfoSearchString' => urlencode(str_replace('#', '', $cmd->getHumanName())),
  'jeephp2js.md_cmdConfigure_cmdInfoString' => $cmd->getHumanName()
]);
$cmd_widgetDashboard = cmd::availableWidget('dashboard');
$cmd_widgetMobile = cmd::availableWidget('mobile');
$configEqDisplayType = jeedom::getConfiguration('eqLogic:displayType');
?>

<div id="md_displayCmdConfigure" data-modalType="md_displayCmdConfigure">
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
                  <div class="col-xs-8">
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
                  <label class="col-xs-4 control-label">{{Commande mise à jour}}</label>
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
                <div class="form-group">
                  <label class="col-xs-4 control-label">{{Dernière exécution par}}</label>
                  <div class="col-xs-8">
                    <span class="cmdAttr label label-primary" data-l1key="cache" data-l2key="lastExecutionUser"></span>
                  </div>
                </div>
                <?php if ($cmd->getType() == 'info') {
                  $value = $cmd->execCmd();
                ?>
                  <div class="form-group">
                    <label class="col-xs-4 control-label">{{Etat}}</label>
                    <div class="col-xs-8">
                      <?php
                      if ($value !== '') {
                      ?>
                        <span class="label label-primary" style="max-width: 100%;display: inline-table !important;line-height: initial !important;"><?php echo '<span class="cmdConfigure_cmdValue" data-cmd_id="' . $cmd->getid() . '" title="{{Date de collecte}} : ' .  $cmd->getCollectDate() . '">' . $value . ' ' . $cmd->getUnite() . ' {{le}} ' . $cmd->getValueDate() . '<span>'; ?></span>
                      <?php } else { ?>
                        <span class="label label-primary" style="max-width: 100%;display: inline-table !important;line-height: initial !important;"><?php echo '<span class="cmdConfigure_cmdValue" data-cmd_id="' . $cmd->getid() . '">{{Inconnu}}<span>'; ?></span>
                      <?php } ?>
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
                <div class="displayIconParent form-group">
                  <label class="col-xs-4 control-label">{{Icône}}</label>
                  <div class="col-xs-4">
                    <span class="cmdAttr label cursor" data-l1key="display" data-l2key="icon" style="font-size : 1.5em!important;"></span>
                    <a class="btn btn-default btn-sm" id="bt_cmdConfigureChooseIcon"><i class="fas fa-icons"></i> {{Icône}}</a>
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
              <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Equipement(s)}}</label>
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
              <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Commande(s)}}</label>
              <div class="col-lg-10 col-md-9 col-sm-8 col-xs-6">
                <?php
                foreach ($usedBy['cmd'] as $usedByCmd) {
                  echo '<a href="' . $usedByCmd->getEqLogic()->getLinkToConfiguration() . '" class="btn btn-xs btn-primary">' . $usedByCmd->getHumanName() . '</a><br/>';
                }
                ?>
              </div>
            </div>
            <div class="form-group">
              <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Scénario(s)}}</label>
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
              <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Résumé(s)}}</label>
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
                <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Plugin(s)}} <?php echo $key; ?></label>
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
            <?php if (($cmd->getType() == 'info') || ($cmd->getType() == 'action' && ($cmd->getSubType() == 'slider'))) {
            ?>
              <legend><i class="fas fa-table"></i> {{Calcul et arrondi}}</legend>
              <div class="form-group">
                <label class="col-md-3 col-sm-3 control-label">{{Formule de calcul}}
                  <sup><i class="fas fa-question-circle" title="#value# = {{valeur de la commande}}"></i></sup>
                </label>
                <div class="col-sm-6">
                  <div class="input-group">
                    <input class="cmdAttr form-control roundedLeft" data-l1key="configuration" data-l2key="calculValueOffset" />
                    <span class="input-group-btn">
                      <a class="btn btn-default roundedRight cursor tooltips" id="bt_searchInfoCmdCalculValue" title="{{Rechercher une commande}}"><i class="fas fa-list-alt"></i></a>
                    </span>
                  </div>
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
          <form class="form-horizontal">
            <legend><i class="fas fa-exclamation-triangle"></i> {{Ne pas exécuter la commande si l'équipement est déjà dans le bon état (alpha)}}</legend>
            <div class="form-group">
              <label class="col-md-3 col-sm-3 control-label">{{Confirmer l'action}}</label>
              <div class="col-sm-6">
                <select class="cmdAttr form-control" data-l1key="configuration" data-l2key="alreadyInState">
                  <option value="">{{Défaut}}</option>
                  <option value="allow">{{Oui}}</option>
                  <option value="deny">{{Non}}</option>
                </select>
              </div>
            </div>
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
              <div class="form-group">
                <label class="col-md-3 col-sm-3 control-label">{{Limiter à une valeur toute les}}
                <sup><i class="fas fa-question-circle" title="{{Limite le nombre de valeur historisé par la commande en temps réel (avant le lissage de la nuit). Attention un mode de lissage doit absolument être défini.}}"></i></sup>
                </label>
                <div class="col-sm-6">
                  <select class="form-control cmdAttr" data-l1key="configuration" data-l2key="history::smooth">
                    <option value="">{{Default}}</option>
                    <option value="-1">{{Aucun}}</option>
                    <option value="60">{{1 min}}</option>
                    <option value="300">{{5 min}}</option>
                    <option value="600">{{10 min}}</option>
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
                    <option value="never" selected>{{Jamais}}</option>
                    <option value="" selected>{{Défaut}}</option>
                  </select>
                </div>
              </div>
              <?php if ($cmd->getIsHistorized() == 1 && $JEEDOM_INTERNAL_CONFIG['cmd']['type']['info']['subtype'][$cmd->getSubType()]['isHistorized']['canBeSmooth']) { ?>
              <div class="form-group">
                <label class="col-md-3 col-sm-3 control-label">{{Paramètres d'affichage}}</label>
                <div class="col-sm-2">
                  <select class="form-control cmdAttr" data-l1key="display" data-l2key="groupingType">
                    <option value="">{{Aucun groupement}}</option>
                    <option value="sum::hour">{{Somme par heure}}</option>
                    <option value="average::hour">{{Moyenne par heure}}</option>
                    <option value="low::hour">{{Minimum par heure}}</option>
                    <option value="high::hour">{{Maximum par heure}}</option>
                    <option value="sum::day">{{Somme par jour}}</option>
                    <option value="average::day">{{Moyenne par jour}}</option>
                    <option value="low::day">{{Minimum par jour}}</option>
                    <option value="high::day">{{Maximum par jour}}</option>
                    <option value="sum::week">{{Somme par semaine}}</option>
                    <option value="average::week">{{Moyenne par semaine}}</option>
                    <option value="low::week">{{Minimum par semaine}}</option>
                    <option value="high::week">{{Maximum par semaine}}</option>
                    <option value="sum::month">{{Somme par mois}}</option>
                    <option value="average::month">{{Moyenne par mois}}</option>
                    <option value="low::month">{{Minimum par mois}}</option>
                    <option value="high::month">{{Maximum par mois}}</option>
                    <option value="sum::year">{{Somme par année}}</option>
                    <option value="average::year">{{Moyenne par année}}</option>
                    <option value="low::year">{{Minimum par année}}</option>
                    <option value="high::year">{{Maximum par année}}</option>
                  </select>
                </div>
                <div class="col-sm-2">
                  <select class="form-control cmdAttr" data-l1key="display" data-l2key="graphType">
                    <option value="line">{{Ligne}}</option>
                    <option value="area">{{Aire}}</option>
                    <option value="column">{{Barre}}</option>
                  </select>
                </div>
                <div class="col-sm-2">
                  {{Variation}}&nbsp;<input type="checkbox" class="cmdAttr" data-l1key="display" data-l2key="graphDerive" />
                  </div>
                <div class="col-sm-2">
                  {{Escalier}}&nbsp;<input type="checkbox" class="cmdAttr" data-l1key="display" data-l2key="graphStep" />
                </div>
              </div>
              <?php } ?>
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
          <form class="form-horizontal">
            <fieldset>
              <legend><i class="fas fa-plus"></i> {{Autres}}</legend>
              <div class="form-group">
                <label class="col-md-2 col-sm-3 control-label">{{M'alerter au retour à la normale}}</label>
                <div class="col-sm-6">
                  <input type="checkbox" class="cmdAttr form-control tooltips" data-l1key="configuration" data-l2key="alert::messageReturnBack" />
                </div>
              </div>
            </fieldset>
          </form>
        </div>
      <?php }
      ?>

      <!--Display Tab if supported -->
      <?php if ($cmd->widgetPossibility('custom')) {
      ?>
        <div role="tabpanel" class="tab-pane" id="cmd_display">
          <br />
          <legend><i class="fas fa-tint"></i> {{Widget}}</legend>
          <table class="table table-condensed">
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
                    <a class="btn btn-default btn-xs" id="bt_cmdConfigureApplyTo"><i class="fas fa-arrow-alt-circle-down"></i> {{Appliquer à}}</a>
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
                  <td>{{Afficher le nom et l'icône}}</td>
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
              <?php if (config::byKey('displayStatsWidget') == 1 && $cmd->getType() == 'info' && $cmd->getSubType() != 'string' && $cmd->widgetPossibility('custom::displayStats')) { ?>
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

            <table class="table table-condensed" id="table_widgetParametersCmd">
              <thead class="table">
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
                    $tr .= '<input class="form-control value" value="' . htmlspecialchars($value, ENT_QUOTES) . '" />';
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
  </div>
</div>

<script>
  if (!jeeFrontEnd.md_displayCmdConfigure) {
    jeeFrontEnd.md_displayCmdConfigure = {
      init: function(_cmdIds) {
        if (document.body.getAttribute('data-page') == "widgets") {
          document.querySelector('a[href="#cmd_display"]').click()
        }
        if (jeephp2js.md_cmdConfigure_cmdInfo.type == 'info') {
          document.getElementById('bt_cmdConfigureTest').remove()
          document.getElementById('bt_cmdConfigureGraph').remove()
          document.getElementById('bt_cmdConfigureRawObject').classList.add("roundedLeft")
        }
        this.setModal()

        document.getElementById('div_displayCmdConfigure').setJeeValues(jeephp2js.md_cmdConfigure_cmdInfo, '.cmdAttr')

        //format update linked cmds:
        //id1#id2#id3
        var spanValues = document.querySelector('#cmd_information .cmdAttr[data-l1key="value"]')
        var cmdIds = spanValues.textContent
        spanValues.unseen()
        if (cmdIds.length > 0) {
          var arrValues = cmdIds.split('#')
          var spans = ''
          var span
          arrValues.forEach(thisValue => {
            if (thisValue != '' && !thisValue.includes('#')) {
              jeedom.cmd.getHumanCmdName({
                id: thisValue,
                error: function(error) {
                  jeedomUtils.showAlert({
                    attachTo: jeeDialog.get('#md_displayCmdConfigure', 'dialog'),
                    message: error.message,
                    level: 'danger'
                  })
                },
                success: function(data) {
                  var span = '<span class="label label-primary">' + data + '</span><br>'
                  spanValues.parentNode.insertAdjacentHTML('beforeend', span)
                }
              })
            } else {
              if (thisValue != '') {
                span = '<span class="label label-primary">#' + thisValue + '#</span><br>'
                spanValues.parentNode.insertAdjacentHTML('beforeend', span)
              }
            }
          })
        }

        //Set cmd values badges
        document.querySelectorAll('#cmd_information .cmdConfigure_cmdValue').forEach(_cmd => {
          jeedom.cmd.addUpdateFunction(_cmd.getAttribute('data-cmd_id'), function(_options) {
            let cmd = document.querySelector('.cmdConfigure_cmdValue[data-cmd_id="' + _options.cmd_id + '"]')
            if (cmd === null) {
              return;
            }
            cmd.setAttribute('title', '{{Date de collecte}} : ' + _options.collectDate)
            cmd.empty().innerHTML = _options.display_value + ' ' + _options.unit + ' {{le}} ' + _options.valueDate
            jeedomUtils.initTooltips()
          })
        })

        var cmdTemplate = document.querySelector('#cmd_display select[data-l1key="template"][data-l2key="dashboard"]')
        if (cmdTemplate != null) {
          jeeFrontEnd.md_displayCmdConfigure.displayWidgetHelp(cmdTemplate.value)
        }
      },
      postInit: function() {
        this.setInputsDefault()
        this.setActions()
        this.setSortables()
        jeedom.timeline.autocompleteFolder()
      },
      setModal: function() {
        //modal title:
        var title = '{{Configuration commande}}'
        title += ' : ' + jeephp2js.md_cmdConfigure_cmdInfo.eqLogicHumanName
        var emClass = jeephp2js.md_cmdConfigure_cmdInfo.type == 'info' ? 'info' : 'warning'
        title += '<span class="cmdName">[' + jeephp2js.md_cmdConfigure_cmdInfo.name + '] <em class="' + emClass + '">(' + jeephp2js.md_cmdConfigure_cmdInfo.type + ')</em></span>'
        var titleEl = jeeDialog.get('#md_displayCmdConfigure', 'title')
        if (titleEl != null) {
          titleEl.querySelector('span.title').innerHTML = title
        } else {
          //Deprecated, some plugins may load old ui-dialog modale
          document.getElementById('div_displayCmdConfigure').closest('.ui-dialog').querySelector('.ui-dialog-title').innerHTML = title
        }

        jeeDialog.get('#cmdConfigureTab', 'title').querySelector('span.title').innerHTML = title
      },
      setInputsDefault: function() {
        let content = jeeDialog.get('#md_displayCmdConfigure', 'content')
        content.querySelectorAll('select').forEach(_select => {
          if (_select.selectedIndex == -1) {
            let defaultOpt = Array.from(_select.options).filter(o => o.value == '')
            if (defaultOpt.length > 0) {
              _select.value = ''
            } else {
              _select.selectedIndex = 0
            }
            _select.triggerEvent('change')
          }
        })
      },
      setSortables: function() {
        let containers = document.querySelectorAll('#md_displayCmdConfigure #div_actionCheckCmd, #md_displayCmdConfigure #div_actionPreExecCmd, #md_displayCmdConfigure #div_actionPostExecCmd')
        containers.forEach(_container => {
          new Sortable(_container, {
            delay: 100,
            delayOnTouchOnly: true,
            group: 'cmdLayoutContainer',
            draggable: '.actionCheckCmd, .actionPreExecCmd, .actionPostExecCmd',
            filter: 'a, input, textarea',
            preventOnFilter: false,
            direction: 'vertical',
            onEnd: function(event) {
              if (event.to != event.from) {
                //Set right class for save:
                event.item.removeClass('actionCheckCmd actionPreExecCmd actionPostExecCmd')
                var toId = event.to.getAttribute('id')
                if (toId == 'div_actionCheckCmd') {
                  event.item.addClass('actionCheckCmd')
                }
                if (toId == 'div_actionPreExecCmd') {
                  event.item.addClass('actionPreExecCmd')
                }
                if (toId == 'div_actionPostExecCmd') {
                  event.item.addClass('actionPostExecCmd')
                }
              }
            },
          })
        })
      },
      displayWidgetHelp: function(_widgetName) {
        if (document.getElementById('optionalParamHelp')) {
          jeedom.cmd.getWidgetHelp({
            id: document.querySelector('#cmd_information span[data-l1key="id"]').textContent,
            version: 'dashboard',
            widgetName: _widgetName,
            error: function(error) {
              document.getElementById('optionalParamHelp').empty().textContent = '{{Pas de description des paramètres optionnels sur ce Widget.}}'
            },
            success: function(data) {
              document.getElementById('optionalParamHelp').empty().innerHTML = data.html
            }
          })
        }
      },
      synchModalToCmd: function() {
        var cmdId = document.querySelector('#div_displayCmdConfigure .cmdAttr[data-l1key="id"]').textContent
        var cmdTr = document.querySelector('#div_pageContainer tr[data-cmd_id="' + cmdId + '"]')
        if (cmdTr) {
          if (cmdTr.querySelector('input.cmdAttr[data-l1key="isVisible"]')) {
            cmdTr.querySelector('input.cmdAttr[data-l1key="isVisible"]').checked = document.querySelector('#div_displayCmdConfigure input.cmdAttr[data-l1key="isVisible"').checked
          }
          if (cmdTr.querySelector('.cmdAttr[data-l1key="display"][data-l2key="icon"]')) {
            cmdTr.querySelector('.cmdAttr[data-l1key="display"][data-l2key="icon"]').innerHTML = document.querySelector('#div_displayCmdConfigure .cmdAttr[data-l1key="display"][data-l2key="icon"]').innerHTML
          }
        }
      },
      syncModalToScenario: function() {
        if (getUrlVars('p') != 'scenario') return
        jeeFrontEnd.scenario.updateDefinedActions(true)
      },
      setActions: function() {
        var config = jeephp2js.md_cmdConfigure_cmdInfo.configuration
        if (isset(config.actionCheckCmd) && Array.isArray(config.actionCheckCmd) && config.actionCheckCmd.length != null) {
          for (var i in config.actionCheckCmd) {
            this.addActionCmd(config.actionCheckCmd[i], 'actionCheckCmd', '{{Action}}')
          }
        }

        if (isset(config.jeedomPreExecCmd) && Array.isArray(config.jeedomPreExecCmd) && config.jeedomPreExecCmd.length != null) {
          for (var i in config.jeedomPreExecCmd) {
            this.addActionCmd(config.jeedomPreExecCmd[i], 'actionPreExecCmd', '{{Action}}')
          }
        }

        if (isset(config.jeedomPostExecCmd) && Array.isArray(config.jeedomPostExecCmd) && config.jeedomPostExecCmd.length != null) {
          for (var i in config.jeedomPostExecCmd) {
            this.addActionCmd(config.jeedomPostExecCmd[i], 'actionPostExecCmd', '{{Action}}')
          }
        }
        jeedomUtils.taAutosize()
      },
      addActionCmd: function(_action, _type, _name) {
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
        let newDiv = document.createElement('div')
        newDiv.html(div)
        newDiv.setJeeValues(_action, '.expressionAttr')
        document.querySelector('#div_' + _type).appendChild(newDiv)
        newDiv.replaceWith(...newDiv.childNodes)

        jeedom.scenario.setAutoComplete({
          parent: document.getElementById('div_' + _type),
          type: 'cmd'
        })
      },
      applySelectMultiple: function() {
        var cmd = document.getElementById('div_displayCmdConfigure').getJeeValues('.cmdAttr')[0]
        if (!isset(cmd.display)) cmd.display = {}
        if (!isset(cmd.display.parameters)) cmd.display.parameters = {}

        document.querySelector('#cmd_display #table_widgetParametersCmd')?.tBodies[0].childNodes.forEach(_tr => {
          if (_tr.nodeType != 3) {
            cmd.display.parameters[_tr.querySelector('.key').jeeValue()] = _tr.querySelector('.value').jeeValue()
          }
        })
        cmd = {
          display: cmd.display,
          template: cmd.template
        }
        jeeDialog.dialog({
          id: 'md_cmdConfigureSelectMultiple',
          title: "{{Sélection multiple de commandes}}",
          width: (window.innerWidth - 150) < 1200 ? (window.innerWidth - 50) : 1200,
          height: (window.innerHeight - 150),
          zIndex: 1030,
          contentUrl: 'index.php?v=d&modal=cmd.selectMultiple&cmd_id=' + jeephp2js.md_cmdConfigure_cmdInfo.id,
          callback: function() {
            document.getElementById('bt_cmdSelectMultipleApply').addEventListener('click', function(event) {
              document.getElementById('table_cmdConfigureSelectMultiple').tBodies[0].querySelectorAll('tr').forEach(_tr => {
                if (_tr.querySelector('.selectMultipleApplyCmd').checked) {
                  cmd.id = _tr.getAttribute('data-cmd_id')
                  jeedom.cmd.save({
                    cmd: cmd,
                    error: function(error) {
                      jeedomUtils.showAlert({
                        attachTo: jeeDialog.get('#md_cmdConfigureSelectMultiple', 'dialog'),
                        message: error.message,
                        level: 'danger'
                      })
                    },
                    success: function() {}
                  })
                }
              })
              jeedomUtils.showAlert({
                attachTo: jeeDialog.get('#md_cmdConfigureSelectMultiple', 'dialog'),
                message: "{{Modification(s) appliquée(s) avec succès}}",
                level: 'success'
              })
            })
          },
        })
      },
      cmdSave: function(event) {
        //Get cmd options:
        var cmd = document.getElementById('div_displayCmdConfigure').getJeeValues('.cmdAttr')[0]
        if (!isset(cmd.display)) cmd.display = {}
        if (!isset(cmd.display.parameters)) cmd.display.parameters = {}

        if (document.querySelector('#cmd_display #table_widgetParametersCmd')) { 
          document.querySelector('#cmd_display #table_widgetParametersCmd')?.tBodies[0].childNodes.forEach(_tr => {
            if (_tr.nodeType != 3) {
              cmd.display.parameters[_tr.querySelector('.key').jeeValue()] = _tr.querySelector('.value').jeeValue()
            }
          })
        } else delete cmd.display.parameters;

        try {
          var checkCmdParameter = document.getElementById('div_jeedomCheckCmdCmdOption').getJeeValues('.expressionAttr')[0]
        } catch (e) {}
        if (isset(checkCmdParameter) && isset(checkCmdParameter.options)) {
          cmd.configuration.jeedomCheckCmdCmdActionOption = checkCmdParameter.options
        }

        //Get pre/post exec actions:
        cmd.configuration.actionCheckCmd = {}
        cmd.configuration.actionCheckCmd = document.querySelectorAll('#div_actionCheckCmd .actionCheckCmd').getJeeValues('.expressionAttr')
        cmd.configuration.jeedomPreExecCmd = document.querySelectorAll('#div_actionPreExecCmd .actionPreExecCmd').getJeeValues('.expressionAttr')
        cmd.configuration.jeedomPostExecCmd = document.querySelectorAll('#div_actionPostExecCmd .actionPostExecCmd').getJeeValues('.expressionAttr')
        jeedom.cmd.save({
          cmd: cmd,
          error: function(error) {
            jeedomUtils.showAlert({
              attachTo: jeeDialog.get('#md_displayCmdConfigure', 'dialog'),
              message: error.message,
              level: 'danger'
            })
          },
          success: function() {
            modifyWithoutSave = false
            jeedomUtils.showAlert({
              attachTo: event.ctrlKey ? null : jeeDialog.get('#md_displayCmdConfigure', 'content'),
              message: '{{Sauvegarde réussie}}',
              level: 'success'
            })
            jeeFrontEnd.md_displayCmdConfigure.synchModalToCmd()
            jeeFrontEnd.md_displayCmdConfigure.syncModalToScenario()
            if (event.ctrlKey) {
              setTimeout(function() {
                jeeDialog.get('#jee_modal').close()
              }, 200)
            }
          }
        })
      },
    }
  }

  (function() { // Self Isolation!
    var jeeM = jeeFrontEnd.md_displayCmdConfigure
    
    //Manage events outside parents delegations:
    document.getElementById('bt_cmdConfigureTest')?.addEventListener('click', function(event) {
      jeedom.cmd.test({
        id: jeephp2js.md_cmdConfigure_cmdInfo.id,
        alert: '#md_displayCmdConfigure'
      })
    })

    document.getElementById('bt_cmdConfigureGraph')?.addEventListener('click', function(event) {
      jeeDialog.dialog({
        id: 'jee_modal3',
        title: "{{Graphique des liens}}",
        contentUrl: 'index.php?v=d&modal=graph.link&filter_type=cmd&filter_id=' + jeephp2js.md_cmdConfigure_cmdInfo.id
      })
    })

    document.getElementById('bt_cmdConfigureRawObject')?.addEventListener('click', function(event) {
      jeeDialog.dialog({
        id: 'jee_modal3',
        title: "{{Informations}}",
        contentUrl: 'index.php?v=d&modal=object.display&class=cmd&id=' + jeephp2js.md_cmdConfigure_cmdInfo.id
      })
    })

    document.getElementById('bt_cmdConfigureSave')?.addEventListener('click', function(event) {
      jeeFrontEnd.md_displayCmdConfigure.cmdSave(event)
    })

    document.getElementById('cmdConfigureTab')?.addEventListener('click', function(event) {
      setTimeout(function() {
        jeedomUtils.taAutosize()
      }, 100)
    })

    document.getElementById('cmd_configuration')?.addEventListener('click', function(event) {
      var _target = null
      if (_target = event.target.closest('#bt_cmdConfigureCopyHistory')) {
        jeedom.cmd.getSelectModal({
          cmd: {
            type: 'info',
            subType: jeephp2js.md_cmdConfigure_cmdInfo.subType
          }
        }, function(result) {
          var target_id = result.cmd.id
          var name = result.human
          jeeDialog.confirm('{{Êtes-vous sûr de vouloir copier l\'historique de}} <strong>' + jeephp2js.md_cmdConfigure_cmdInfo.name + '</strong> {{vers}} <strong>' + name + '</strong> ? {{Il est conseillé de vider l\'historique de la commande}} : <strong>' + name + '</strong> {{avant la copie}}', function(result) {
            if (result) {
              jeedom.history.copyHistoryToCmd({
                source_id: jeephp2js.md_cmdConfigure_cmdInfo.id,
                target_id: target_id,
                error: function(error) {
                  jeedomUtils.showAlert({
                    attachTo: jeeDialog.get('#div_displayCmdConfigure', 'dialog'),
                    message: error.message,
                    level: 'danger'
                  })
                },
                success: function(data) {
                  jeedomUtils.showAlert({
                    attachTo: jeeDialog.get('#div_displayCmdConfigure', 'dialog'),
                    message: '{{Historique copié avec succès}}',
                    level: 'success'
                  })
                }
              })
            }
          })
        })
        return
      }
    });

    /*Events delegations
     */
    //cmd information tab
    document.getElementById('cmd_information')?.addEventListener('click', function(event) {
      var _target = null
      if (_target = event.target.closest('#bt_cmdConfigureChooseIcon')) {
        let displayIconParent = _target.closest('.displayIconParent')
        let icon = displayIconParent.querySelector('[data-l2key="icon"] > i')
        let params = {}
        if (icon) params.icon = icon.attributes.class.value
        jeedomUtils.chooseIcon(function(_icon) {
          displayIconParent.querySelector('.cmdAttr[data-l1key="display"][data-l2key="icon"]').empty().innerHTML = _icon
        }, params)
        return
      }

      if (_target = event.target.closest('#bt_influxDelete')) {
        jeeDialog.confirm('{{Êtes-vous sûr de vouloir supprimer toutes les infos de cette commande d\'InfluxDB}}', function(result) {
          if (result) {
            jeedom.cmd.dropInflux({
              cmd_id: jeephp2js.md_cmdConfigure_cmdInfo.id,
              error: function(error) {
                jeedomUtils.showAlert({
                  attachTo: jeeDialog.get('#div_displayCmdConfigure', 'dialog'),
                  message: error.message,
                  level: 'danger'
                })
              },
              success: function(data) {
                jeedomUtils.showAlert({
                  attachTo: jeeDialog.get('#div_displayCmdConfigure', 'dialog'),
                  message: '{{Action envoyée avec succés}}',
                  level: 'success'
                })
              }
            })
          }
        })
        return
      }

      if (_target = event.target.closest('#bt_influxHistory')) {
        jeeDialog.confirm('{{Êtes-vous sûr de vouloir envoyer tout l\'historique de cette commande à InfluxDB. Cela sera programmé et effectué en tâche de fond dans une minute.}}', function(result) {
          if (result) {
            jeedom.cmd.historyInflux({
              cmd_id: jeephp2js.md_cmdConfigure_cmdInfo.id,
              error: function(error) {
                jeedomUtils.showAlert({
                  attachTo: jeeDialog.get('#div_displayCmdConfigure', 'dialog'),
                  message: error.message,
                  level: 'danger'
                })
              },
              success: function(data) {
                jeedomUtils.showAlert({
                  attachTo: jeeDialog.get('#div_displayCmdConfigure', 'dialog'),
                  message: '{{Programmation envoyée avec succés}}',
                  level: 'success'
                })
              }
            })
          }
        })
        return
      }

      if (_target = event.target.closest('#bt_cmdConfigureReplaceMeBy')) {
        jeedom.cmd.getSelectModal({
          cmd: {
            type: jeephp2js.md_cmdConfigure_cmdInfo.type,
            subType: jeephp2js.md_cmdConfigure_cmdInfo.subType
          }
        }, function(result) {
          var target_id = result.cmd.id
          var name = result.human
          jeeDialog.confirm('{{Êtes-vous sûr de vouloir remplacer}} <strong>' + jeephp2js.md_cmdConfigure_cmdInfoString + '</strong> {{par}} : <br/><strong>' + name + '</strong> ?', function(result) {
            if (result) {
              jeedom.cmd.replaceCmd({
                source_id: jeephp2js.md_cmdConfigure_cmdInfo.id,
                target_id: target_id,
                error: function(error) {
                  jeedomUtils.showAlert({
                    attachTo: jeeDialog.get('#div_displayCmdConfigure', 'dialog'),
                    message: error.message,
                    level: 'danger'
                  })
                },
                success: function(data) {
                  jeedomUtils.showAlert({
                    attachTo: jeeDialog.get('#div_displayCmdConfigure', 'dialog'),
                    message: '{{Remplacement réalisé avec succès}}',
                    level: 'success'
                  })
                }
              })
            }
          })
        })
        return
      }

      if (_target = event.target.closest('#bt_cmdConfigureReplaceByMe')) {
        jeedom.cmd.getSelectModal({
          cmd: {
            type: jeephp2js.md_cmdConfigure_cmdInfo.type,
            subType: jeephp2js.md_cmdConfigure_cmdInfo.subType
          }
        }, function(result) {
          var target_id = result.cmd.id
          var name = result.human
          jeeDialog.confirm('{{Êtes-vous sûr de vouloir remplacer l\'ID}} <strong>' + name + '</strong> {{par}} : <br/><strong>' + jeephp2js.md_cmdConfigure_cmdInfoString + '</strong> ?', function(result) {
            if (result) {
              jeedom.cmd.replaceCmd({
                source_id: target_id,
                target_id: jeephp2js.md_cmdConfigure_cmdInfo.id,
                error: function(error) {
                  jeedomUtils.showAlert({
                    attachTo: jeeDialog.get('#div_displayCmdConfigure', 'dialog'),
                    message: error.message,
                    level: 'danger'
                  })
                },
                success: function(data) {
                  jeedomUtils.showAlert({
                    attachTo: jeeDialog.get('#div_displayCmdConfigure', 'dialog'),
                    message: '{{Remplacement réalisé avec succès}}',
                    level: 'success'
                  })
                }
              })
            }
          })
        })
        return
      }

      if (_target = event.target.closest('#bt_cmdConfigureReplaceIdByMe')) {
        var target_id = prompt("{{ID de commande à remplacer ?}}")
        if (target_id == null) return
        jeeDialog.confirm('{{Êtes-vous sûr de vouloir remplacer la commande}} id : <strong>' + target_id + '</strong> {{par}} : <br/><strong>' + jeephp2js.md_cmdConfigure_cmdInfoString + '</strong> ?', function(result) {
          if (result) {
            jeedom.cmd.replaceCmd({
              source_id: target_id,
              target_id: jeephp2js.md_cmdConfigure_cmdInfo.id,
              error: function(error) {
                jeedomUtils.showAlert({
                  attachTo: jeeDialog.get('#div_displayCmdConfigure', 'dialog'),
                  message: error.message,
                  level: 'danger'
                })
              },
              success: function(data) {
                jeedomUtils.showAlert({
                  attachTo: jeeDialog.get('#div_displayCmdConfigure', 'dialog'),
                  message: '{{Remplacement réalisé avec succès}}',
                  level: 'success'
                })
              }
            })
          }
        })
        return
      }

      if (_target = event.target.closest('#bt_cmdConfigureShowHistory')) {
        jeeDialog.dialog({
          id: 'md_cmdHistory',
          title: "{{Historique}}",
          contentUrl: 'index.php?v=d&modal=cmd.history&id=' + jeephp2js.md_cmdConfigure_cmdInfo.id
        })
        return
      }
    })

    document.getElementById('cmd_information')?.addEventListener('dblclick', function(event) {
      var _target = null
      if (_target = event.target.closest('.cmdAttr[data-l1key="display"][data-l2key="icon"]')) {
        _target.innerHTML = ''
        return
      }
    })

    document.getElementById('cmd_information')?.addEventListener('change', function(event) {
      var _target = null
      if (_target = event.target.closest('.cmdAttr[data-l2key="timeline::enable"]')) {
        if (_target.jeeValue() == 1) {
          document.querySelectorAll('.cmdAttr[data-l2key="timeline::folder"]').seen()
        } else {
          document.querySelectorAll('.cmdAttr[data-l2key="timeline::folder"]').unseen()
        }
        return
      }

      if (_target = event.target.closest('.cmdAttr[data-l2key="influx::enable"]')) {
        if (_target.jeeValue() == 1) {
          document.querySelectorAll('.selInflux').seen()
        } else {
          document.querySelectorAll('.selInflux').unseen()
        }
        return
      }
    })


    //cmd configuration tab
    document.getElementById('cmd_configuration')?.addEventListener('click', function(event) {
      var _target = null

      if (_target = event.target.closest('#bt_searchInfoCmdCalculValue')) {
        jeedom.cmd.getSelectModal({cmd: {type: 'info'}}, function(result) {
          document.querySelectorAll('.cmdAttr[data-l1key=configuration][data-l2key=calculValueOffset]')[0].insertAtCursor(result.human)
        })
      }

      if (_target = event.target.closest('.bt_removeAction')) {
        _target.closest('.' + _target.getAttribute('data-type')).remove()
        return
      }

      if (_target = event.target.closest('#bt_addActionCheckCmd')) {
        jeeFrontEnd.md_displayCmdConfigure.addActionCmd({}, 'actionCheckCmd', '{{Action}}')
        return
      }

      if (_target = event.target.closest('#bt_addActionPreExecCmd')) {
        jeeFrontEnd.md_displayCmdConfigure.addActionCmd({}, 'actionPreExecCmd', '{{Action}}')
        return
      }

      if (_target = event.target.closest('#bt_addActionPostExecCmd')) {
        jeeFrontEnd.md_displayCmdConfigure.addActionCmd({}, 'actionPostExecCmd', '{{Action}}')
        return
      }

      if (_target = event.target.closest('.listCmd')) {
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

      if (_target = event.target.closest('.listAction')) {
        var type = _target.getAttribute('data-type')
        var el = _target.closest('.' + type).querySelector('.expressionAttr[data-l1key="cmd"]')
        jeedom.getSelectActionModal({}, function(result) {
          el.jeeValue(result.human)
          jeedom.cmd.displayActionOption(result.human, '', function(html) {
            el.closest('.' + type).querySelector('.actionOptions').html(html)
            jeedomUtils.taAutosize()
          })
        })
        return
      }
    })

    document.getElementById('cmd_configuration')?.addEventListener('focusout', function(event) {
      var _target = null
      if (_target = event.target.closest('.cmdAction.expressionAttr[data-l1key="cmd"]')) {
        var type = _target.getAttribute('data-type')
        var expression = _target.closest('.' + type).getJeeValues('.expressionAttr')
        var el = this
        jeedom.cmd.displayActionOption(_target.jeeValue(), init(expression[0].options), function(html) {
          _target.closest('.' + type).querySelector('.actionOptions').html(html)
          jeedomUtils.taAutosize()
        })
        return
      }
    })

    //cmd alert tab

    //cmd display tab
    document.getElementById('cmd_display')?.addEventListener('click', function(event) {
      var _target = null

      if (_target = event.target.closest('#bt_addWidgetParametersCmd')) {
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
        document.getElementById('table_widgetParametersCmd').tBodies[0].insertAdjacentHTML('beforeend', tr)
        return
      }

      if (_target = event.target.closest('.removeWidgetParameter')) {
        _target.closest('tr').remove()
        return
      }

      if (_target = event.target.closest('#bt_cmdConfigureApplyTo')) {
        jeeFrontEnd.md_displayCmdConfigure.applySelectMultiple()
        return
      }
    })

    document.getElementById('cmd_display')?.addEventListener('change', function(event) {
      var _target = null
      if (_target = event.target.closest('select[data-l1key="template"][data-l2key="dashboard"]')) {
        jeeFrontEnd.md_displayCmdConfigure.displayWidgetHelp(_target.value)
        return
      }
    })

    jeeM.init()

    jeeM.postInit()
  })()
</script>
