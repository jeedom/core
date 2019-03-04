<?php
if (!isConnect('admin')) {
  throw new Exception('{{401 - Accès non autorisé}}');
}
$cmd = cmd::byId(init('cmd_id'));
if (!is_object($cmd)) {
  throw new Exception('Commande non trouvé : ' . init('cmd_id'));
}
global $JEEDOM_INTERNAL_CONFIG;
sendVarToJS('cmdInfo', jeedom::toHumanReadable(utils::o2a($cmd)));
sendVarToJS('cmdInfoSearchString', urlencode(str_replace('#', '', $cmd->getHumanName())));
$cmd_widgetDashboard = cmd::availableWidget('dashboard');
$cmd_widgetMobile = cmd::availableWidget('mobile');
?>
<div style="display: none;" id="md_displayCmdConfigure"></div>
<div class="input-group pull-right" style="display:inline-flex">
  <span class="input-group-btn">
    <a class="btn btn-default roundedLeft btn-sm" id="bt_cmdConfigureTest"><i class="fas fa-rss"></i> {{Tester}}</a><a class="btn btn-default btn-sm" id="bt_cmdConfigureGraph"><i class="fas fa-object-group"></i> {{Liens}}</a><a class="btn btn-default btn-sm" id="bt_cmdConfigureRawObject"><i class="fas fa-info"></i> {{Informations}}</a><a class="btn btn-default btn-sm" id="bt_cmdConfigureSaveOn"><i class="fas fa-plus-circle"></i> {{Appliquer à}}</a><a class="btn btn-success btn-sm roundedRight" id="bt_cmdConfigureSave"><i class="far fa-check-circle"></i> {{Enregistrer}}</a>
  </span>
</div>
<div role="tabpanel">
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#cmd_information" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-info-circle"></i> {{Informations}}</a></li>
    <li role="presentation"><a href="#cmd_configuration" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-wrench"></i> {{Configuration}}</a></li>
    <?php if ($cmd->getType() == 'info') {?>
      <li role="presentation"><a href="#cmd_alert" aria-controls="messages" role="tab" data-toggle="tab"><i class="fas fa-exclamation-triangle"></i> {{Alertes}}</a></li>
    <?php }
    ?>
    <?php if ($cmd->widgetPossibility('custom')) {
      ?>
      <li role="presentation"><a href="#cmd_display" aria-controls="messages" role="tab" data-toggle="tab"><i class="fas fa-desktop"></i> {{Affichage}}</a></li>
    <?php }
    ?>
    <?php if ($cmd->widgetPossibility('custom::htmlCode')) {
      ?>
      <li role="presentation"><a href="#cmd_html" aria-controls="messages" role="tab" data-toggle="tab"><i class="fas fa-code-fork"></i> {{Code}}</a></li>
    <?php }
    ?>
  </ul>
  <div class="tab-content" id="div_displayCmdConfigure">
    <div role="tabpanel" class="tab-pane active" id="cmd_information">
      <br/>
      <legend><i class="fas fa-list-alt"></i> {{Général}}</legend>
      <div class="row">
        <div class="col-sm-6" >
          <form class="form-horizontal">
            <fieldset>
              <div class="form-group">
                <label class="col-xs-4 control-label">{{ID}}</label>
                <div class="col-xs-4">
                  <span class="cmdAttr label label-primary" data-l1key="id" style="font-size : 1em;"></span>
                </div>
              </div>
              <div class="form-group">
                <label class="col-xs-4 control-label">{{Logical ID}}</label>
                <div class="col-xs-4">
                  <span class="cmdAttr label label-primary" data-l1key="logicalId" style="font-size : 1em;"></span>
                </div>
              </div>
              <div class="form-group">
                <label class="col-xs-4 control-label">{{Nom}}</label>
                <div class="col-xs-4">
                  <span class="cmdAttr label label-primary" data-l1key="name" style="font-size : 1em;"></span>
                </div>
              </div>
              <div class="form-group">
                <label class="col-xs-4 control-label">{{Type}}</label>
                <div class="col-xs-4">
                  <span class="cmdAttr label label-primary" data-l1key="type" style="font-size : 1em;"></span>
                </div>
              </div>
              <div class="form-group">
                <label class="col-xs-4 control-label">{{Sous-type}}</label>
                <div class="col-xs-4">
                  <span class="cmdAttr label label-primary" data-l1key="subType" style="font-size : 1em;"></span>
                </div>
              </div>
              <div class="form-group">
                <label class="col-xs-4 control-label">{{Commande déclenchant une mise à jour}}</label>
                <div class="col-xs-4">
                  <span class="cmdAttr label label-primary" data-l1key="value" style="font-size : 1em;"></span>
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
              <?php }?>
              <?php if ($cmd->getType() == 'info') {
                $cache = $cmd->getCache(array('value', 'collectDate', 'valueDate'));
                ?>
                <div class="form-group">
                  <label class="col-xs-4 control-label">{{Valeur}}</label>
                  <div class="col-xs-4">
                    <span class="label label-primary" style="font-size : 1em;"><?php echo $cache['value'] ?></span>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-xs-4 control-label">{{Date collecte}}</label>
                  <div class="col-xs-4">
                    <span class="label label-primary" style="font-size : 1em;"><?php echo $cache['collectDate'] ?></span>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-xs-4 control-label">{{Date valeur}}</label>
                  <div class="col-xs-4">
                    <span class="label label-primary" style="font-size : 1em;"><?php echo $cache['valueDate'] ?></span>
                  </div>
                </div>
              <?php }?>
            </fieldset>
          </form>
        </div>
        <div class="col-sm-6" >
          <form class="form-horizontal">
            <fieldset>
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
                  <span class="cmdAttr label label-primary" data-l1key="unite" style="font-size : 1em;"></span>
                </div>
              </div>
              <div class="form-group">
                <label class="col-xs-4 control-label">{{Visible}}</label>
                <div class="col-xs-4">
                  <input type="checkbox" class="cmdAttr" data-l1key="isVisible" />
                </div>
              </div>
              <?php if ($cmd->getType() == 'info' && $cmd->getSubtype() == 'numeric') {?>
                <div class="form-group">
                  <label class="col-xs-4 control-label">{{Valeur minimum (défaut : 0)}}</label>
                  <div class="col-xs-2">
                    <input class="cmdAttr form-control" data-l1key="configuration" data-l2key="minValue" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-xs-4 control-label">{{Valeur maximum (défaut : 100)}}</label>
                  <div class="col-xs-2">
                    <input class="cmdAttr form-control" data-l1key="configuration" data-l2key="maxValue" />
                  </div>
                </div>
              <?php }?>
              <div class="form-group">
                <label class="col-xs-4 control-label">{{Suivre dans la timeline}}</label>
                <div class="col-xs-4">
                  <input type="checkbox" class="cmdAttr" data-l1key="configuration" data-l2key="timeline::enable" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-xs-4 control-label">{{Interdire dans les interactions automatique}}</label>
                <div class="col-xs-4">
                  <input type="checkbox" class="cmdAttr" data-l1key="configuration" data-l2key="interact::auto::disable" />
                </div>
              </div>
              <div class="iconeGeneric">
                <label class="col-xs-4 control-label">{{Icône}}</label>
                <div class="col-xs-4">
                  <span class="cmdAttr label label-info cursor" data-l1key="display" data-l2key="icon" style="font-size : 1.5em;" ></span>
                  <a class="btn btn-default btn-sm" id="bt_cmdConfigureChooseIcon"><i class="fas fa-flag"></i> {{Icône}}</a>
                </div>
              </div>
            </fieldset>
          </form>
        </div>
      </div>
      
      <legend><i class="fas fa-search"></i> {{Utilisé par}}
        <a class="btn btn-xs btn-warning pull-right" id="bt_cmdConfigureReplaceMeBy"><i class="fas fa-download" aria-hidden="true"></i> {{Remplacer cette commande par la commande}}</a>
        <a class="btn btn-xs btn-warning pull-right" id="bt_cmdConfigureReplaceByMe"><i class="fas fa-upload" aria-hidden="true"></i> {{Cette commande remplace la commande}}</a>
        <a class="btn btn-xs btn-warning pull-right" id="bt_cmdConfigureReplaceIdByMe"><i class="fas fa-upload" aria-hidden="true"></i> {{Cette commande remplace l'ID}}</a>
      </legend>
      <form class="form-horizontal">
        <fieldset id="fd_cmdUsedBy">
          <?php
          $usedBy = $cmd->getUsedBy();
          ?>
          <div class="form-group">
            <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Equipement}}</label>
            <div class="col-lg-10 col-md-9 col-sm-8 col-xs-6">
              <?php
              foreach ($usedBy['eqLogic'] as $usedByEqLogic) {
                if ($usedByEqLogic->getIsEnable() != 1) {
                  echo '<span class="label label-default cursor"><a href="' . $usedByEqLogic->getLinkToConfiguration() . '" style="color : white;">' . $usedByEqLogic->getHumanName() . '</a></span><br/>';
                } else {
                  echo '<span class="label label-primary cursor"><a href="' . $usedByEqLogic->getLinkToConfiguration() . '" style="color : white;">' . $usedByEqLogic->getHumanName() . '</a></span><br/>';
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
                echo '<span class="label label-primary cursor"><a href="' . $usedByCmd->getEqLogic()->getLinkToConfiguration() . '" style="color : white;">' . $usedByCmd->getHumanName() . '</a></span><br/>';
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
                  echo '<span class="label label-default cursor"><a href="' . $usedByScenario->getLinkToConfiguration() . '" style="color : white;">' . $usedByScenario->getHumanName() . '</a></span><br/>';
                } else {
                  echo '<span class="label label-primary cursor"><a href="' . $usedByScenario->getLinkToConfiguration() . '" style="color : white;">' . $usedByScenario->getHumanName() . '</a></span><br/>';
                }
              }
              ?>
            </div>
          </div>
        </fieldset>
      </form>
    </div>
    <div role="tabpanel" class="tab-pane" id="cmd_configuration">
      <br/>
      <form class="form-horizontal">
        <fieldset>
          
          <?php if ($cmd->getType() == 'info' && ($cmd->getSubType() == 'numeric' || $cmd->getSubType() == 'binary')) {
            ?>
            <legend><i class="fas fa-table"></i> {{Calcul et arrondi}}</legend>
            <div class="form-group">
              <label class="col-lg-3 col-md-3 col-sm-4 col-xs-6 control-label">{{Formule de calcul (#value# pour la valeur)}}</label>
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <input class="cmdAttr form-control" data-l1key="configuration" data-l2key="calculValueOffset" />
              </div>
            </div>
            <?php if ($cmd->getSubType() == 'numeric') {?>
              <div class="form-group">
                <label class="col-lg-3 col-md-3 col-sm-3 col-xs-6 control-label">{{Arrondi (chiffre après la virgule)}}</label>
                <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
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
            <label class="col-lg-3 col-md-3 col-sm-3 col-xs-6 control-label">{{Valeur}}</label>
            <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
              <select class="cmdAttr form-control" data-l1key="generic_type">
                <option value="">{{Aucun}}</option>
                <?php
                $groups = array();
                foreach (jeedom::getConfiguration('cmd::generic_type') as $key => $info) {
                  if (strtolower($cmd->getType()) != strtolower($info['type'])) {
                    continue;
                  } elseif (isset($info['ignore']) && $info['ignore']) {
                    continue;
                  }
                  $info['key'] = $key;
                  if (!isset($groups[$info['family']])) {
                    $groups[$info['family']][0] = $info;
                  } else {
                    array_push($groups[$info['family']], $info);
                  }
                }
                ksort($groups);
                foreach ($groups as $group) {
                  usort($group, function ($a, $b) {
                    return strcmp($a['name'], $b['name']);
                  });
                  foreach ($group as $key => $info) {
                    if ($key == 0) {
                      echo '<optgroup label="{{' . $info['family'] . '}}">';
                    }
                    echo '<option value="' . $info['key'] . '">' . $info['name'] . '</option>';
                  }
                  echo '</optgroup>';
                }
                ?>
              </select>
            </div>
          </div>
        </fieldset>
      </form>
      <?php if ($cmd->getType() == 'action') {?>
        
        <form class="form-horizontal">
          <fieldset>
            <legend><i class="fas fa-exclamation-triangle"></i> {{Restriction de l'action}}</legend>
            <div class="form-group">
              <label class="col-lg-3 col-md-3 col-sm-4 col-xs-6 control-label">{{Confirmer l'action}}</label>
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <input type="checkbox" class="cmdAttr" data-l1key="configuration" data-l2key="actionConfirm" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-lg-3 col-md-3 col-sm-4 col-xs-6 control-label">{{Code d'accès}}</label>
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <input type="password" class="cmdAttr form-control" data-l1key="configuration" data-l2key="actionCodeAccess" autocomplete="off" />
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
            <legend><i class="fas fa-sign-out-alt"></i> {{Action sur la valeur}}</legend>
            <div class="form-group">
              <label class="col-lg-3 col-md-3 col-sm-4 col-xs-6 control-label">{{Action sur valeur, si}}</label>
              <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                <select class="cmdAttr form-control" data-l1key="configuration" data-l2key="jeedomCheckCmdOperator" >
                  <option value="==">{{égal}}</option>
                  <option value=">">{{supérieur}}</option>
                  <option value="<">{{inférieur}}</option>
                  <option value="!=">{{différent}}</option>
                </select>
              </div>
              <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                <input class="cmdAttr form-control" data-l1key="configuration" data-l2key="jeedomCheckCmdTest" />
              </div>
              <label class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label">{{plus de (min)}}</label>
              <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                <input type="number" class="cmdAttr form-control" data-l1key="configuration" data-l2key="jeedomCheckCmdTime" />
              </div>
            </div>
            
            <div class="form-group">
              <label class="col-lg-3 col-md-3 col-sm-4 col-xs-6 control-label">{{Action}}</label>
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <a class="btn btn-success" id="bt_addActionCheckCmd"><i class="fas fa-plus-circle"></i> {{Ajouter}}</a>
              </div>
            </div>
            <div id="div_actionCheckCmd"></div>
            
            <script type="text/javascript">
            $("#div_actionCheckCmd").sortable({axis: "y", cursor: "move", items: ".actionCheckCmd", placeholder: "ui-state-highlight", tolerance: "intersect", forcePlaceholderSize: true});
            
            $('#bt_addActionCheckCmd').off('click').on('click',function(){
              addActionCmd({}, 'actionCheckCmd','{{Action}}');
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
              <label class="col-lg-3 col-md-3 col-sm-4 col-xs-6 control-label">{{Action}}</label>
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <a class="btn btn-success" id="bt_addActionPreExecCmd"><i class="fas fa-plus-circle"></i> {{Ajouter}}</a>
              </div>
            </div>
            <div id="div_actionPreExecCmd"></div>
            <script type="text/javascript">
            $("#div_actionPreExecCmd").sortable({axis: "y", cursor: "move", items: ".actionPreExecCmd", placeholder: "ui-state-highlight", tolerance: "intersect", forcePlaceholderSize: true});
            $('#bt_addActionPreExecCmd').off('click').on('click',function(){
              addActionCmd({}, 'actionPreExecCmd','{{Action}}');
            });
            </script>
          </fieldset>
        </form>
        <form class="form-horizontal">
          <fieldset>
            <legend><i class="fas fa-sign-out-alt"></i> {{Action après exécution de la commande}}</legend>
            <div class="form-group">
              <label class="col-lg-3 col-md-3 col-sm-4 col-xs-6 control-label">{{Action}}</label>
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <a class="btn btn-success" id="bt_addActionPostExecCmd"><i class="fas fa-plus-circle"></i> {{Ajouter}}</a>
              </div>
            </div>
            <div id="div_actionPostExecCmd"></div>
            
            <script type="text/javascript">
            $("#div_actionPostExecCmd").sortable({axis: "y", cursor: "move", items: ".actionPostExecCmd", placeholder: "ui-state-highlight", tolerance: "intersect", forcePlaceholderSize: true});
            $('#bt_addActionPostExecCmd').off('click').on('click',function(){
              addActionCmd({}, 'actionPostExecCmd','{{Action}}');
            });
            </script>
          </fieldset>
        </form>
      <?php }?>
      
      <?php if ($cmd->getType() == 'info' && $JEEDOM_INTERNAL_CONFIG['cmd']['type']['info']['subtype'][$cmd->getSubType()]['isHistorized']['visible']) {
        ?>
        <form class="form-horizontal">
          <fieldset>
            <legend><i class="fas fa-bar-chart-o"></i> {{Historique}}</legend>
            <div class="form-group">
              <label class="col-lg-3 col-md-3 col-sm-3 col-xs-6 control-label">{{Historiser}}</label>
              <div class="col-xs-1">
                <input type="checkbox" class="cmdAttr" data-l1key="isHistorized" />
              </div>
            </div>
            <?php if ($JEEDOM_INTERNAL_CONFIG['cmd']['type']['info']['subtype'][$cmd->getSubType()]['isHistorized']['canBeSmooth']) {?>
              <div class="form-group">
                <label class="col-lg-3 col-md-3 col-sm-3 col-xs-6 control-label">{{Mode de lissage}}</label>
                <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
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
              <label class="col-lg-3 col-md-3 col-sm-3 col-xs-6 control-label">{{Purger l'historique si plus vieux que }}</label>
              <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                <select class="form-control cmdAttr" data-l1key="configuration" data-l2key="historyPurge">
                  <option value="">{{Jamais}}</option>
                  <option value="-1 day">{{1 jour}}</option>
                  <option value="-7 days">{{7 jours}}</option>
                  <option value="-1 month">{{1 mois}}</option>
                  <option value="-3 month">{{3 mois}}</option>
                  <option value="-6 month">{{6 mois}}</option>
                  <option value="-1 year">{{1 an}}</option>
                </select>
              </div>
            </div>
            <?php if ($cmd->getIsHistorized() == 1) {?>
              <div class="form-group">
                <label class="col-lg-3 col-md-3 col-sm-3 col-xs-6 control-label">{{Copie des données historisées}}</label>
                <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                  <a class="btn btn-warning" id="bt_cmdConfigureCopyHistory"><i class="fas fa-clone"></i> {{Copier l'historique de cette commande sur une autre commande}}</a>
                </div>
              </div>
            <?php }
            ?>
          </fieldset>
        </form>
      <?php }
      ?>
      <?php if ($cmd->getType() == 'info') {?>
        <form class="form-horizontal">
          <fieldset>
            <legend><i class="fas fa-thermometer-three-quarters"></i> {{Gestion des valeurs}}</legend>
            <div class="form-group">
              <label class="col-lg-3 col-md-3 col-sm-3 col-xs-6 control-label">{{Valeurs interdites (séparées par ";")}}</label>
              <div class="col-xs-3">
                <input class="cmdAttr form-control" data-l1key="configuration" data-l2key="denyValues" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-lg-3 col-md-3 col-sm-3 col-xs-6 control-label">{{Valeur retour d'état}}</label>
              <div class="col-xs-3">
                <input class="cmdAttr form-control" data-l1key="configuration" data-l2key="returnStateValue" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-lg-3 col-md-3 col-sm-3 col-xs-6 control-label">{{Durée avant retour d'état (min)}}</label>
              <div class="col-xs-3">
                <input class="cmdAttr form-control" data-l1key="configuration" data-l2key="returnStateTime" />
              </div>
            </div>
          </fieldset>
        </form>
        <form class="form-horizontal">
          <fieldset>
            <legend><i class="fas fa-plus"></i> {{Autres}}</legend>
            <div class="form-group">
              <label class="col-lg-3 col-md-3 col-sm-3 col-xs-6 control-label">{{Gestion de la répétition des valeurs}}</label>
              <div class="col-xs-3">
                <select class="cmdAttr form-control" data-l1key="configuration" data-l2key="repeatEventManagement" >
                  <option value="auto">{{Automatique}}</option>
                  <option value="always">{{Toujours répéter}}</option>
                  <option value="never">{{Jamais répéter}}</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-lg-3 col-md-3 col-sm-4 col-xs-6 control-label">{{Push URL}}</label>
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <input class="cmdAttr form-control tooltips" data-l1key="configuration" data-l2key="jeedomPushUrl" title="{{Mettez ici l'URL à appeler lors d'une mise à jour de la valeur de la commande. Vous pouvez utiliser les tags suivants : #value# (valeur de la commande), #cmd_id# (id de la commande) et #cmd_name# (nom de la commande)}}"/>
              </div>
            </div>
          </fieldset>
        </form>
      <?php }
      ?>
    </div>
    <?php if ($cmd->widgetPossibility('custom::htmlCode')) {
      $html = array();
      foreach (array('dashboard', 'mobile', 'dview', 'mview', 'dplan') as $value) {
        if ($cmd->getHtml($value) == '') {
          $html[$value] = str_replace('textarea>','textarea$>',$cmd->getWidgetTemplateCode($value));
        }else{
          $html[$value] = str_replace('textarea>','textarea$>',$cmd->getHtml($value));
        }
      }
      ?>
      <div role="tabpanel" class="tab-pane" id="cmd_html">
        <br/>
        <a class="btn btn-warning btn-sm pull-right" id="bt_reinitHtmlCode" style="position:relative;top:-3px;"><i class="fas fa-times"></i> {{Réinitialiser la personnalisation}}</a>
        <div class="form-group">
          <label class="col-lg-3 col-md-3 col-sm-3 col-xs-6 control-label">{{Activer la personnalisation du widget}}</label>
          <div class="col-xs-2">
            <input type="checkbox" class="cmdAttr" data-l1key="html" data-l2key="enable" />
          </div>
        </div>
        <br/>
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingOne">
              <h4 class="panel-title">
                <a role="button" id="bt_codeDashboard" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                  {{Dashboard}}
                </a>
              </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
              <div class="panel-body">
                <textarea class="cmdAttr" id="ta_codeDashboard" data-l1key="html" data-l2key="dashboard" style="width: 100%;height: 350px"><?php echo $html['dashboard']; ?></textarea>
              </div>
            </div>
          </div>
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingFour">
              <h4 class="panel-title">
                <a class="collapsed" id="bt_codeDview" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                  {{Vue dashboard}}
                </a>
              </h4>
            </div>
            <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
              <div class="panel-body">
                <textarea class="cmdAttr" id="ta_codeDview" data-l1key="html" data-l2key="dview" style="width: 100%;height: 350px"><?php echo $html['dview']; ?></textarea>
              </div>
            </div>
          </div>
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingThree">
              <h4 class="panel-title">
                <a class="collapsed" id="bt_codeDplan" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                  {{Design}}
                </a>
              </h4>
            </div>
            <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
              <div class="panel-body">
                <textarea class="cmdAttr" id="ta_codeDplan" data-l1key="html" data-l2key="dplan" style="width: 100%;height: 350px"><?php echo $html['dplan']; ?></textarea>
              </div>
            </div>
          </div>
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingTwo">
              <h4 class="panel-title">
                <a class="collapsed" id="bt_codeMobile" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                  {{Mobile}}
                </a>
              </h4>
            </div>
            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
              <div class="panel-body">
                <textarea class="cmdAttr" id="ta_codeMobile" data-l1key="html" data-l2key="mobile" style="width: 100%;height: 350px"><?php echo $html['mobile']; ?></textarea>
              </div>
            </div>
          </div>
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingFive">
              <h4 class="panel-title">
                <a class="collapsed" id="bt_codeMview" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                  {{Vue mobile}}
                </a>
              </h4>
            </div>
            <div id="collapseFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
              <div class="panel-body">
                <textarea class="cmdAttr" id="ta_codeMview" data-l1key="html" data-l2key="mview" style="width: 100%;height: 350px"><?php echo $html['mview']; ?></textarea>
              </div>
            </div>
          </div>
          
        </div>
      </div>
    <?php }
    ?>
    <?php if ($cmd->getType() == 'info') {
      ?>
      <div role="tabpanel" class="tab-pane" id="cmd_alert">
        <br/>
        <?php
        foreach ($JEEDOM_INTERNAL_CONFIG['alerts'] as $level => $value) {
          if (!$value['check']) {
            continue;
          }
          echo '<form class="form-horizontal">';
          echo '<fieldset>';
          echo '<legend><i class="' . $value['name'] . '"></i> {{Niveau}} ' . $value['name'] . '</legend>';
          echo '<div class="form-group">';
          echo '<label class="col-lg-3 col-md-3 col-sm-4 col-xs-6 control-label">{{En}} ' . $value['name'] . ' {{si (#value# pour la valeur)}}</label>';
          echo '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">';
          echo '<input class="cmdAttr form-control" data-l1key="alert" data-l2key="' . $level . 'if" />';
          echo '</div>';
          echo '</div>';
          echo '<div class="form-group">';
          echo '<label class="col-lg-3 col-md-3 col-sm-4 col-xs-6 control-label">{{Pendant plus de (en min, laisser vide pour immédiat)}}</label>';
          echo '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">';
          echo '<input type="number" class="cmdAttr form-control" data-l1key="alert" data-l2key="' . $level . 'during" />';
          echo '</div>';
          echo '</div>';
          echo '</fieldset>';
          echo '</form>';
        }
        ?>
      </div>
    <?php }
    ?>
    
    <?php if ($cmd->widgetPossibility('custom')) {
      ?>
      <div role="tabpanel" class="tab-pane" id="cmd_display">
        <br/>
        <legend><i class="fas fa-tint"></i> {{Widget}}</legend>
        <table class="table table-bordered table-condensed">
          <thead>
            <tr>
              <th></th>
              <?php
              foreach (jeedom::getConfiguration('eqLogic:displayType') as $key => $value) {
                echo '<th>{{' . $value['name'] . '}}</th>';
              }
              ?>
            </tr>
          </thead>
          <tbody>
            <?php if ($cmd->widgetPossibility('custom::widget')) {
              ?>
              <tr>
                <td>{{Widget}}</td>
                <td colspan="3">
                  <?php if ($cmd->widgetPossibility('custom::widget::dashboard')) {
                    ?>
                    <select class="form-control cmdAttr" data-l1key="template" data-l2key="dashboard">
                      <option value="default">defaut (core)</option>';
                      <?php
                      if (is_array($cmd_widgetDashboard[$cmd->getType()]) && is_array($cmd_widgetDashboard[$cmd->getType()][$cmd->getSubType()]) && count($cmd_widgetDashboard[$cmd->getType()][$cmd->getSubType()]) > 0) {
                        foreach ($cmd_widgetDashboard[$cmd->getType()][$cmd->getSubType()] as $widget) {
                          if ($widget['name'] == 'default') {
                            continue;
                          }
                          echo '<option value="' . $widget['name'] . '">' . $widget['name'] . ' (' . $widget['location'] . ')</option>';
                        }
                      }
                      ?>
                    </select>
                  <?php }
                  ?>
                </td>
                <td>
                  <?php if ($cmd->widgetPossibility('custom::widget::mobile')) {
                    ?>
                    <select class="form-control cmdAttr" data-l1key="template" data-l2key="mobile">
                      <option value="default">defaut (core)</option>';
                      <?php
                      if (is_array($cmd_widgetMobile[$cmd->getType()]) && is_array($cmd_widgetMobile[$cmd->getType()][$cmd->getSubType()]) && count($cmd_widgetMobile[$cmd->getType()][$cmd->getSubType()]) > 0) {
                        foreach ($cmd_widgetMobile[$cmd->getType()][$cmd->getSubType()] as $widget) {
                          if ($widget['name'] == 'default') {
                            continue;
                          }
                          echo '<option value="' . $widget['name'] . '">' . $widget['name'] . ' (' . $widget['location'] . ')</option>';
                        }
                      }
                      ?>
                    </select>
                  <?php }
                  ?>
                </td>
              </tr>
            <?php }
            ?>
            <?php if ($cmd->widgetPossibility('custom::visibility')) {
              ?>
              <tr>
                <td>{{Visible}}</td>
                <?php
                foreach (jeedom::getConfiguration('eqLogic:displayType') as $key => $value) {
                  echo '<td>';
                  if ($cmd->widgetPossibility('custom::visibility::' . $key)) {
                    echo '<input type="checkbox" class="cmdAttr" data-l1key="display" data-l2key="showOn' . $key . '" checked />';
                  }
                  echo '</td>';
                }
                ?>
              </tr>
            <?php }
            ?>
            <?php if ($cmd->widgetPossibility('custom::displayName')) {
              ?>
              <tr>
                <td>{{Afficher le nom}}</td>
                <?php
                foreach (jeedom::getConfiguration('eqLogic:displayType') as $key => $value) {
                  echo '<td>';
                  if ($cmd->widgetPossibility('custom::displayName::' . $key)) {
                    echo '<input type="checkbox" class="cmdAttr" data-l1key="display" data-l2key="showNameOn' . $key . '" checked />';
                  }
                  echo '</td>';
                }
                ?>
              </tr>
            <?php }
            ?>
            <?php if ($cmd->widgetPossibility('custom::displayIconAndName')) {
              ?>
              <tr>
                <td>{{Afficher le nom ET l'icône}}</td>
                <?php
                foreach (jeedom::getConfiguration('eqLogic:displayType') as $key => $value) {
                  echo '<td>';
                  if ($cmd->widgetPossibility('custom::displayIconAndName::' . $key)) {
                    echo '<input type="checkbox" class="cmdAttr" data-l1key="display" data-l2key="showIconAndName' . $key . '" />';
                  }
                  echo '</td>';
                }
                ?>
              </tr>
            <?php }
            ?>
            <?php if (config::byKey('displayStatsWidget') == 1 && $cmd->getSubType() != 'string' && $cmd->widgetPossibility('custom::displayStats')) {
              
              ?>
              <tr>
                <td>{{Afficher les statistiques}}</td>
                <?php
                foreach (jeedom::getConfiguration('eqLogic:displayType') as $key => $value) {
                  echo '<td>';
                  if ($cmd->widgetPossibility('custom::displayStats::' . $key)) {
                    echo '<input type="checkbox" class="cmdAttr" data-l1key="display" data-l2key="showStatsOn' . $key . '" checked />';
                  }
                  echo '</td>';
                }
                ?>
              </tr>
              <?php
            }
            ?>
          </tbody>
        </table>
        
        <div class="form-group">
          <label class="col-lg-3 col-md-3 col-sm-4 col-xs-6 control-label">{{Retour à la ligne forcé avant le widget}}</label>
          <div class="col-xs-1">
            <input type="checkbox" class="cmdAttr" data-l1key="display" data-l2key="forceReturnLineBefore" />
          </div>
          <label class="col-xs-2 control-label">{{après le widget}}</label>
          <div class="col-xs-1">
            <input type="checkbox" class="cmdAttr" data-l1key="display" data-l2key="forceReturnLineAfter" />
          </div>
        </div>
        
        <br/><br/>
        <?php if ($cmd->widgetPossibility('custom::optionalParameters')) {
          ?>
          <legend><i class="fas fa-pencil-alt-square-o"></i> {{Paramètres optionnels widget}} <a class="btn btn-success btn-xs pull-right" id="bt_addWidgetParametersCmd"><i class="fas fa-plus-circle"></i> Ajouter</a></legend>
          <table class="table table-bordered table-condensed" id="table_widgetParametersCmd">
            <thead class="table table-bordered">
              <tr>
                <th>Nom</th>
                <th>Valeur</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if ($cmd->getDisplay('parameters') != '') {
                foreach ($cmd->getDisplay('parameters') as $key => $value) {
                  echo '<tr>';
                  echo '<td>';
                  echo '<input class="form-control key" value="' . $key . '" />';
                  echo '</td>';
                  echo '<td>';
                  echo '<input class="form-control value" value="' . $value . '" />';
                  echo '</td>';
                  echo '<td>';
                  echo '<a class="btn btn-danger btn-xs removeWidgetParameter"><i class="fas fa-times"></i> Supprimer</a>';
                  echo '</td>';
                  echo '</tr>';
                }
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
  
  
  <script>
  $('#ta_codeDashboard').value($('#ta_codeDashboard').value().replace(/textarea\$\>/gi, 'textarea>'));
  $('#ta_codeDview').value($('#ta_codeDview').value().replace(/textarea\$\>/gi, 'textarea>'));
  $('#ta_codeDplan').value($('#ta_codeDplan').value().replace(/textarea\$\>/gi, 'textarea>'));
  $('#ta_codeMobile').value($('#ta_codeMobile').value().replace(/textarea\$\>/gi, 'textarea>'));
  $('#ta_codeMview').value($('#ta_codeMview').value().replace(/textarea\$\>/gi, 'textarea>'));
  $("#md_cmdConfigureSelectMultiple").dialog({
    closeText: '',
    autoOpen: false,
    modal: true,
    height: (jQuery(window).height() - 150),
    width: ((jQuery(window).width() - 150) < 1200) ? (jQuery(window).width() - 50) : 1200,
    position: {my: 'center', at: 'center', of: window},
    open: function () {
      $("body").css({overflow: 'hidden'});
    },
    beforeClose: function (event, ui) {
      $("body").css({overflow: 'inherit'});
    }
  });
  
  $('#table_widgetParametersCmd').delegate('.removeWidgetParameter', 'click', function () {
    $(this).closest('tr').remove();
  });
  
  $('#bt_addWidgetParametersCmd').off().on('click', function () {
    var tr = '<tr>';
    tr += '<td>';
    tr += '<input class="form-control key" />';
    tr += '</td>';
    tr += '<td>';
    tr += '<input class="form-control value" />';
    tr += '</td>';
    tr += '<td>';
    tr += '<a class="btn btn-danger btn-xs removeWidgetParameter pull-right"><i class="fas fa-times"></i> Supprimer</a>';
    tr += '</td>';
    tr += '</tr>';
    $('#table_widgetParametersCmd tbody').append(tr);
  });
  
  $('#div_displayCmdConfigure').setValues(cmdInfo, '.cmdAttr');
  $('#bt_cmdConfigureRawObject').off('click').on('click',function(){
    $('#md_modal2').dialog({title: "{{Informations}}"});
    $("#md_modal2").load('index.php?v=d&modal=object.display&class=cmd&id='+cmdInfo.id).dialog('open');
  });
  $('#bt_cmdConfigureGraph').on('click', function () {
    $('#md_modal2').dialog({title: "{{Graphique des liens}}"});
    $("#md_modal2").load('index.php?v=d&modal=graph.link&filter_type=cmd&filter_id='+cmdInfo.id).dialog('open');
  });
  
  $('#bt_cmdConfigureCopyHistory').off('click').on('click',function(){
    jeedom.cmd.getSelectModal({cmd: {type: 'info', subType: cmdInfo.subType}}, function (result) {
      var target_id = result.cmd.id
      var name = result.human
      bootbox.confirm('{{Etes-vous sûr de vouloir copier l\'historique de}} <strong>'+cmdInfo.name+'</strong> {{vers}} <strong>'+name+'</strong> ? {{Il est conseillé de vider l\'historique de la commande}} : <strong>'+name+'</strong> {{ avant la copie}}', function (result) {
        if (result) {
          jeedom.history.copyHistoryToCmd({
            source_id : cmdInfo.id,
            target_id : target_id,
            error: function (error) {
              $('#md_displayCmdConfigure').showAlert({message: error.message, level: 'danger'});
            },
            success: function (data) {
              $('#md_displayCmdConfigure').showAlert({message: '{{Historique copié avec succès}}', level: 'success'});
            }
          });
        }
      });
    });
  });
  
  $('#bt_cmdConfigureCopyHistory').off('click').on('click',function(){
    jeedom.cmd.getSelectModal({cmd: {type: 'info', subType: cmdInfo.subType}}, function (result) {
      var target_id = result.cmd.id
      var name = result.human
      bootbox.confirm('{{Etes-vous sûr de vouloir copier l\'historique de}} <strong>'+cmdInfo.name+'</strong> {{vers}} <strong>'+name+'</strong> ? {{Il est conseillé de vider l\'historique de la commande}} : <strong>'+name+'</strong> {{ avant la copie}}', function (result) {
        if (result) {
          jeedom.history.copyHistoryToCmd({
            source_id : cmdInfo.id,
            target_id : target_id,
            error: function (error) {
              $('#md_displayCmdConfigure').showAlert({message: error.message, level: 'danger'});
            },
            success: function (data) {
              $('#md_displayCmdConfigure').showAlert({message: '{{Historique copié avec succès}}', level: 'success'});
            }
          });
        }
      });
    });
  });
  
  
  $('#bt_cmdConfigureReplaceMeBy').off('click').on('click',function(){
    jeedom.cmd.getSelectModal({cmd: {type: cmdInfo.type, subType: cmdInfo.subType}}, function (result) {
      var target_id = result.cmd.id
      var name = result.human
      bootbox.confirm('{{Etes-vous sûr de vouloir remplacer}} <strong>'+cmdInfo.name+'</strong> {{par}} <strong>'+name+'</strong> ?', function (result) {
        if (result) {
          jeedom.cmd.replaceCmd({
            source_id : cmdInfo.id,
            target_id : target_id,
            error: function (error) {
              $('#md_displayCmdConfigure').showAlert({message: error.message, level: 'danger'});
            },
            success: function (data) {
              $('#md_displayCmdConfigure').showAlert({message: '{{Remplacement réalisé avec succès}}', level: 'success'});
            }
          });
        }
      });
    });
  });
  
  $('#bt_cmdConfigureReplaceByMe').off('click').on('click',function(){
    jeedom.cmd.getSelectModal({cmd: {type: cmdInfo.type, subType: cmdInfo.subType}}, function (result) {
      var target_id = result.cmd.id
      var name = result.human
      bootbox.confirm('{{Etes-vous sûr de vouloir remplacer l\'ID}} <strong>'+name+'</strong> {{par}} <strong>'+cmdInfo.name+'</strong> ?', function (result) {
        if (result) {
          jeedom.cmd.replaceCmd({
            source_id : target_id,
            target_id : cmdInfo.id,
            error: function (error) {
              $('#md_displayCmdConfigure').showAlert({message: error.message, level: 'danger'});
            },
            success: function (data) {
              $('#md_displayCmdConfigure').showAlert({message: '{{Remplacement réalisé avec succès}}', level: 'success'});
            }
          });
        }
      });
    });
  });
  
  
  $('#bt_cmdConfigureReplaceIdByMe').off('click').on('click',function(){
    var target_id = prompt("{{ID de commande à remplacer ?}}");
    if(target_id == null){
      return;
    }
    bootbox.confirm('{{Etes-vous sûr de vouloir remplacer}} <strong>'+target_id+'</strong> {{par}} <strong>'+cmdInfo.name+'</strong> ?', function (result) {
      if (result) {
        jeedom.cmd.replaceCmd({
          source_id : target_id,
          target_id : cmdInfo.id,
          error: function (error) {
            $('#md_displayCmdConfigure').showAlert({message: error.message, level: 'danger'});
          },
          success: function (data) {
            $('#md_displayCmdConfigure').showAlert({message: '{{Remplacement réalisé avec succès}}', level: 'success'});
          }
        });
      }
    });
  });
  
  
  if(isset(cmdInfo.configuration.actionCheckCmd) && $.isArray(cmdInfo.configuration.actionCheckCmd) && cmdInfo.configuration.actionCheckCmd.length != null){
    for(var i in cmdInfo.configuration.actionCheckCmd){
      addActionCmd(cmdInfo.configuration.actionCheckCmd[i], 'actionCheckCmd','{{Action}}');
    }
  }
  
  if(isset(cmdInfo.configuration.jeedomPreExecCmd) && $.isArray(cmdInfo.configuration.jeedomPreExecCmd) && cmdInfo.configuration.jeedomPreExecCmd.length != null){
    for(var i in cmdInfo.configuration.jeedomPreExecCmd){
      addActionCmd(cmdInfo.configuration.jeedomPreExecCmd[i], 'actionPreExecCmd','{{Action}}');
    }
  }
  
  if(isset(cmdInfo.configuration.jeedomPostExecCmd) && $.isArray(cmdInfo.configuration.jeedomPostExecCmd) && cmdInfo.configuration.jeedomPostExecCmd.length != null){
    for(var i in cmdInfo.configuration.jeedomPostExecCmd){
      addActionCmd(cmdInfo.configuration.jeedomPostExecCmd[i], 'actionPostExecCmd','{{Action}}');
    }
  }
  
  editorCodeDview = null;
  editorCodeDplan = null;
  editorCodeMobile = null;
  editorCodeMview = null;
  editorCodeDashboard = null;
  
  $('#bt_codeDashboard').one('click',function(){
    setTimeout(function () {
      editorCodeDashboard = CodeMirror.fromTextArea(document.getElementById("ta_codeDashboard"), {
        lineNumbers: true,
        mode: "text/javascript",
        matchBrackets: true,
        viewportMargin: Infinity
      });
    }, 1);
  });
  
  $('#bt_codeDview').one('click',function(){
    setTimeout(function () {
      editorCodeDview = CodeMirror.fromTextArea(document.getElementById("ta_codeDview"), {
        lineNumbers: true,
        mode: "text/javascript",
        matchBrackets: true,
        viewportMargin: Infinity
      });
    }, 1);
  });
  
  $('#bt_codeDplan').one('click',function(){
    setTimeout(function () {
      editorCodeDplan = CodeMirror.fromTextArea(document.getElementById("ta_codeDplan"), {
        lineNumbers: true,
        mode: "text/javascript",
        matchBrackets: true,
        viewportMargin: Infinity
      });
    }, 1);
  });
  
  $('#bt_codeMobile').one('click',function(){
    setTimeout(function () {
      editorCodeMobile = CodeMirror.fromTextArea(document.getElementById("ta_codeMobile"), {
        lineNumbers: true,
        mode: "text/javascript",
        matchBrackets: true,
        viewportMargin: Infinity
      });
    }, 1);
  });
  
  $('#bt_codeMview').one('click',function(){
    setTimeout(function () {
      editorCodeMview = CodeMirror.fromTextArea(document.getElementById("ta_codeMview"), {
        lineNumbers: true,
        mode: "text/javascript",
        matchBrackets: true,
        viewportMargin: Infinity
      });
    }, 1);
  });
  
  $('#bt_reinitHtmlCode').on('click',function(){
    $('#ta_codeDashboard').value('');
    $('#ta_codeDview').value('');
    $('#ta_codeDplan').value('');
    $('#ta_codeMobile').value('');
    $('#ta_codeMview').value('');
    if(editorCodeDashboard != null){
      editorCodeDashboard.setValue('');
    }
    if(editorCodeDview != null){
      editorCodeDview.setValue('');
    }
    if(editorCodeDplan != null){
      editorCodeDplan.setValue('');
    }
    if(editorCodeMobile != null){
      editorCodeMobile.setValue('');
    }
    if(editorCodeMview != null){
      editorCodeMview.setValue('');
    }
    $('#md_displayCmdConfigure').showAlert({message: '{{Opération effectuée avec succès, n\'oubliez pas de sauvegarder}}', level: 'success'});
  });
  
  
  $('#bt_cmdConfigureSave').on('click', function () {
    var cmd = $('#div_displayCmdConfigure').getValues('.cmdAttr')[0];
    if (!isset(cmd.display)) {
      cmd.display = {};
    }
    if (!isset(cmd.display.parameters)) {
      cmd.display.parameters = {};
    }
    $('#table_widgetParametersCmd tbody tr').each(function () {
      cmd.display.parameters[$(this).find('.key').value()] = $(this).find('.value').value();
    });
    var checkCmdParameter = $('#div_jeedomCheckCmdCmdOption').getValues('.expressionAttr')[0];
    if (isset(checkCmdParameter) && isset(checkCmdParameter.options)) {
      cmd.configuration.jeedomCheckCmdCmdActionOption = checkCmdParameter.options;
    }
    cmd.configuration.actionCheckCmd = {};
    cmd.configuration.actionCheckCmd = $('#div_actionCheckCmd .actionCheckCmd').getValues('.expressionAttr');
    
    cmd.configuration.jeedomPreExecCmd = $('#div_actionPreExecCmd .actionPreExecCmd').getValues('.expressionAttr');
    
    cmd.configuration.jeedomPostExecCmd = $('#div_actionPostExecCmd .actionPostExecCmd').getValues('.expressionAttr');
    
    if(editorCodeDashboard != null){
      cmd.html.dashboard = editorCodeDashboard.getValue();
    }
    if(editorCodeDview != null){
      cmd.html.dview = editorCodeDview.getValue();
    }
    if(editorCodeDplan != null){
      cmd.html.dplan = editorCodeDplan.getValue();
    }
    if(editorCodeMobile != null){
      cmd.html.mobile = editorCodeMobile.getValue();
    }
    if(editorCodeMview != null){
      cmd.html.mview = editorCodeMview.getValue();
    }
    jeedom.cmd.save({
      cmd: cmd,
      error: function (error) {
        $('#md_displayCmdConfigure').showAlert({message: error.message, level: 'danger'});
      },
      success: function () {
        $('#md_displayCmdConfigure').showAlert({message: '{{Enregistrement réussi}}', level: 'success'});
      }
    });
  });
  
  
  $("body").undelegate('.bt_removeAction', 'click').delegate('.bt_removeAction', 'click', function () {
    var type = $(this).attr('data-type');
    $(this).closest('.' + type).remove();
  });
  
  $("body").undelegate(".listCmd", 'click').delegate(".listCmd", 'click', function () {
    var type = $(this).attr('data-type');
    var el = $(this).closest('.' + type).find('.expressionAttr[data-l1key=cmd]');
    jeedom.cmd.getSelectModal({cmd : {type :'action'}}, function (result) {
      el.value(result.human);
      jeedom.cmd.displayActionOption(el.value(), '', function (html) {
        el.closest('.' + type).find('.actionOptions').html(html);
        taAutosize();
      });
    });
  });
  
  $("body").undelegate(".listAction", 'click').delegate(".listAction", 'click', function () {
    var type = $(this).attr('data-type');
    var el = $(this).closest('.' + type).find('.expressionAttr[data-l1key=cmd]');
    jeedom.getSelectActionModal({}, function (result) {
      el.value(result.human);
      jeedom.cmd.displayActionOption(el.value(), '', function (html) {
        el.closest('.' + type).find('.actionOptions').html(html);
        taAutosize();
      });
    });
  });
  
  $('body').undelegate(".cmdAction.expressionAttr[data-l1key=cmd]", 'focusout').delegate('.cmdAction.expressionAttr[data-l1key=cmd]', 'focusout', function (event) {
    var type = $(this).attr('data-type')
    var expression = $(this).closest('.' + type).getValues('.expressionAttr');
    var el = $(this);
    jeedom.cmd.displayActionOption($(this).value(), init(expression[0].options), function (html) {
      el.closest('.' + type).find('.actionOptions').html(html);
      taAutosize();
    })
  });
  
  function addActionCmd(_action, _type, _name) {
    if (!isset(_action)) {
      _action = {};
    }
    if (!isset(_action.options)) {
      _action.options = {};
    }
    var div = '<div class="' + _type + '">';
    div += '<div class="form-group ">';
    div += '<div class="col-sm-1">';
    div += '<input type="checkbox" class="expressionAttr" data-l1key="options" data-l2key="enable" checked title="{{Décocher pour désactiver l\'action}}" />';
    div += '<input type="checkbox" class="expressionAttr" data-l1key="options" data-l2key="background" title="{{Cocher pour que la commande s\'exécute en parallèle des autres actions}}" />';
    div += '</div>';
    div += '<div class="col-sm-4">';
    div += '<div class="input-group">';
    div += '<span class="input-group-btn">';
    div += '<a class="btn btn-default btn-sm bt_removeAction roundedLeft" data-type="' + _type + '"><i class="fas fa-minus-circle"></i></a>';
    div += '</span>';
    div += '<input class="expressionAttr form-control input-sm cmdAction" data-l1key="cmd" data-type="' + _type + '" />';
    div += '<span class="input-group-btn">';
    div += '<a class="btn  btn-default btn-sm listAction" data-type="' + _type + '" title="{{Sélectionner un mot-clé}}"><i class="fa fa-tasks"></i></a>';
    div += '<a class="btn btn-default btn-sm listCmd roundedRight" data-type="' + _type + '"><i class="fas fa-list-alt"></i></a>';
    div += '</span>';
    div += '</div>';
    div += '</div>';
    div += '<div class="col-sm-7 actionOptions">';
    div += jeedom.cmd.displayActionOption(init(_action.cmd, ''), _action.options);
    div += '</div>';
    $('#div_' + _type).append(div);
    $('#div_' + _type + ' .' + _type + ':last').setValues(_action, '.expressionAttr');
    taAutosize();
  }
  
  $('#bt_cmdConfigureSaveOn').on('click',function(){
    var cmd = $('#div_displayCmdConfigure').getValues('.cmdAttr')[0];
    if (!isset(cmd.display)) {
      cmd.display = {};
    }
    if (!isset(cmd.display.parameters)) {
      cmd.display.parameters = {};
    }
    $('#table_widgetParametersCmd tbody tr').each(function () {
      cmd.display.parameters[$(this).find('.key').value()] = $(this).find('.value').value();
    });
    cmd = {display : cmd.display,template : cmd.template };
    $('#md_cmdConfigureSelectMultiple').load('index.php?v=d&modal=cmd.selectMultiple&cmd_id='+cmdInfo.id, function() {
      initTableSorter();
      $('#bt_cmdConfigureSelectMultipleAlertToogle').off().on('click', function () {
        var state = false;
        if ($(this).attr('data-state') == 0) {
          state = true;
          $(this).attr('data-state', 1);
          $(this).find('i').removeClass('fa-check-circle-o').addClass('fa-circle-o');
          $('#table_cmdConfigureSelectMultiple tbody tr .selectMultipleApplyCmd').value(1);
        } else {
          state = false;
          $(this).attr('data-state', 0);
          $(this).find('i').removeClass('fa-circle-o').addClass('fa-check-circle-o');
          $('#table_cmdConfigureSelectMultiple tbody tr .selectMultipleApplyCmd').value(0);
        }
      });
      
      $('#bt_cmdConfigureSelectMultipleAlertApply').off().on('click', function () {
        $('#table_cmdConfigureSelectMultiple tbody tr').each(function () {
          if ($(this).find('.selectMultipleApplyCmd').prop('checked')) {
            cmd.id = $(this).attr('data-cmd_id');
            jeedom.cmd.save({
              cmd: cmd,
              error: function (error) {
                $('#md_cmdConfigureSelectMultipleAlert').showAlert({message: error.message, level: 'danger'});
              },
              success: function () {
                
              }
            });
          }
        });
        $('#md_cmdConfigureSelectMultipleAlert').showAlert({message: "{{Modification(s) appliquée(s) avec succès}}", level: 'success'});
      });
    }).dialog('open');
  });
  $('#bt_cmdConfigureChooseIcon').on('click', function () {
    var iconeGeneric = $(this).closest('.iconeGeneric');
    chooseIcon(function (_icon) {
      iconeGeneric.find('.cmdAttr[data-l1key=display][data-l2key=icon]').empty().append(_icon);
    });
  });
  
  $('body').undelegate('.cmdAttr[data-l1key=display][data-l2key=icon]', 'click').delegate('.cmdAttr[data-l1key=display][data-l2key=icon]', 'click', function () {
    $(this).empty();
  });
  
  $('#bt_cmdConfigureLogRealTime').off('click').on('click', function () {
    $('#md_modal2').dialog({title: "{{Logs}}"});
    $('#md_modal2').load('index.php?v=d&modal=log.display&log=event&search=' + cmdInfoSearchString).dialog('open');
  });
  
  $('#bt_cmdConfigureTest').on('click',function(){
    jeedom.cmd.test({id: cmdInfo.id});
  });
</script>
