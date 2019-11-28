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
    <a class="btn btn-default roundedLeft btn-sm" id="bt_cmdConfigureTest"><i class="fas fa-rss"></i> {{Tester}}
    </a><a class="btn btn-default btn-sm" id="bt_cmdConfigureGraph"><i class="fas fa-object-group"></i> {{Liens}}
    </a><a class="btn btn-default btn-sm" id="bt_cmdConfigureRawObject"><i class="fas fa-info"></i> {{Informations}}
    </a><a class="btn btn-default btn-sm" id="bt_cmdConfigureSaveOn"><i class="fas fa-plus-circle"></i> {{Appliquer à}}
    </a><a class="btn btn-success btn-sm roundedRight" id="bt_cmdConfigureSave"><i class="far fa-check-circle"></i> {{Sauvegarder}}</a>
  </span>
</div>
<div role="tabpanel">
  <ul class="nav nav-tabs" role="tablist" id="cmdConfigureTab">
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
  </ul>

  <div class="tab-content" id="div_displayCmdConfigure" style="overflow-x:hidden">
    <div role="tabpanel" class="tab-pane active" id="cmd_information">
      <br/>
      <div class="row">
        <div class="col-sm-6" >
          <form class="form-horizontal">
            <fieldset>
              <legend><i class="fas fa-list-alt"></i> {{Général}}</legend>
              <div class="form-group">
                <label class="col-xs-4 control-label">{{ID}}</label>
                <div class="col-xs-6">
                  <span class="cmdAttr label label-primary" data-l1key="id"></span>
                </div>
              </div>
              <div class="form-group">
                <label class="col-xs-4 control-label">{{Logical ID}}</label>
                <div class="col-xs-6">
                  <span class="cmdAttr label label-primary" data-l1key="logicalId"></span>
                </div>
              </div>
              <div class="form-group">
                <label class="col-xs-4 control-label">{{Nom}}</label>
                <div class="col-xs-6">
                  <span class="cmdAttr label label-primary" data-l1key="name"></span>
                </div>
              </div>
              <div class="form-group">
                <label class="col-xs-4 control-label">{{Type}}</label>
                <div class="col-xs-6">
                  <span class="cmdAttr label label-primary" data-l1key="type"></span>
                </div>
              </div>
              <div class="form-group">
                <label class="col-xs-4 control-label">{{Sous-type}}</label>
                <div class="col-xs-6">
                  <span class="cmdAttr label label-primary" data-l1key="subType"></span>
                </div>
              </div>
              <div class="form-group">
                <label class="col-xs-4 control-label">{{Commande déclenchant une mise à jour}}</label>
                <div class="col-xs-6">
                  <span class="cmdAttr" data-l1key="value"></span>
                </div>
              </div>
              <?php if ($cmd->getType() == 'action' && $cmd->getSubtype() == 'select') {
                ?>
                <div class="form-group">
                  <label class="col-xs-6 control-label">{{Valeurs possibles}}</label>
                  <div class="col-xs-6">
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
                    <span class="label label-primary"><?php echo $cache['value'] ?></span>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-xs-4 control-label">{{Date collecte}}</label>
                  <div class="col-xs-4">
                    <span class="label label-primary"><?php echo $cache['collectDate'] ?></span>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-xs-4 control-label">{{Date valeur}}</label>
                  <div class="col-xs-4">
                    <span class="label label-primary"><?php echo $cache['valueDate'] ?></span>
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
                  <span class="cmdAttr label label-primary" data-l1key="unite"></span>
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
                <div class="col-xs-1">
                  <input type="checkbox" class="cmdAttr" data-l1key="configuration" data-l2key="timeline::enable" />
                </div>
                <div class="col-xs-3">
                  <input class="cmdAttr" data-l1key="configuration" data-l2key="timeline::folder" placeholder="{{Dossier}}" style="display:none;">
                </div>
              </div>
              <div class="form-group">
                <label class="col-xs-4 control-label">{{Interdire dans les interactions automatique}}</label>
                <div class="col-xs-4">
                  <input type="checkbox" class="cmdAttr" data-l1key="configuration" data-l2key="interact::auto::disable" />
                </div>
              </div>
              <div class="iconeGeneric form-group">
                <label class="col-xs-4 control-label">{{Icône}}</label>
                <div class="col-xs-4">
                  <span class="cmdAttr label cursor" data-l1key="display" data-l2key="icon" style="font-size : 1.5em;" ></span>
                  <a class="btn btn-default btn-sm" id="bt_cmdConfigureChooseIcon"><i class="fas fa-flag"></i> {{Icône}}</a>
                </div>
              </div>
              <?php if($cmd->getIsHistorized() == 1){ ?>
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
          </div><br/>
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
                  echo '<a href="' . $usedByScenario->getLinkToConfiguration() . '" class="btn btn-xs btn-info">' . $usedByScenario->getHumanName() . '</a><br/>';
                } else {
                  echo '<a href="' . $usedByScenario->getLinkToConfiguration() . '" class="btn btn-xs btn-primary">' . $usedByScenario->getHumanName() . '</a><br/>';
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
                  }
                  $info['key'] = $key;
                  if (!isset($groups[$info['family']])) {
                    $groups[$info['family']][0] = $info;
                  } else {
                    array_push($groups[$info['family']], $info);
                  }
                }
                ksort($groups);
                $optgroup = '';
                foreach ($groups as $group) {
                  usort($group, function ($a, $b) {
                    return strcmp($a['name'], $b['name']);
                  });
                  foreach ($group as $key => $info) {
                    if ($key == 0) {
                      $optgroup .= '<optgroup label="{{' . $info['family'] . '}}">';
                    }
                    $name = $info['name'];
                    if (isset($info['noapp']) && $info['noapp']) {
                      $name .= ' (Non géré par Application Mobile)';
                    }
                    $optgroup .= '<option value="' . $info['key'] . '">' . $name . '</option>';
                  }
                  $optgroup .= '</optgroup>';
                }
                if ($optgroup != '') echo $optgroup;
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
              <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                <input name = "DummyPassword" type="password" autocomplete="new-password" style="display:none;">
                <input type="password" autocomplete="new-password" class="cmdAttr form-control" data-l1key="configuration" data-l2key="actionCodeAccess"/>
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
            <legend><i class="far fa-chart-bar"></i> {{Historique}}</legend>
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
                  <option value="-2 years">{{2 ans}}</option>
                  <option value="-3 years">{{3 ans}}</option>
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
    <?php if ($cmd->getType() == 'info') {
      ?>
      <div role="tabpanel" class="tab-pane" id="cmd_alert">
        <br/>
        <?php
        $form = '';
        foreach ($JEEDOM_INTERNAL_CONFIG['alerts'] as $level => $value) {
          if (!$value['check']) {
            continue;
          }
          $form .= '<form class="form-horizontal">';
          $form .= '<fieldset>';
          $form .= '<legend>';
          if($value['name'] == 'Warning'){
            $form .= '<i class="fas fa-exclamation"></i>';
          }elseif ($value['name'] == 'Danger') {
            $form .= '<i class="fas fa-exclamation-triangle"></i>';
          }
          $form .= '{{Niveau}} ' . $value['name'] . '</legend>';
          $form .= '<div class="form-group">';
          $form .= '<label class="col-lg-3 col-md-3 col-sm-4 col-xs-6 control-label">{{En}} ' . $value['name'] . ' {{si (#value# pour la valeur)}}</label>';
          $form .= '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">';
          $form .= '<input class="cmdAttr form-control" data-l1key="alert" data-l2key="' . $level . 'if" />';
          $form .= '</div>';
          $form .= '</div>';
          $form .= '<div class="form-group">';
          $form .= '<label class="col-lg-3 col-md-3 col-sm-4 col-xs-6 control-label">{{Pendant plus de (en min, laisser vide pour immédiat)}}</label>';
          $form .= '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">';
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

    <?php if ($cmd->widgetPossibility('custom')) {
      ?>
      <div role="tabpanel" class="tab-pane" id="cmd_display">
        <br/>
        <legend><i class="fas fa-tint"></i> {{Widget}}</legend>
        <table class="table table-bordered table-condensed">
          <thead>
            <tr>
              <th style="width:200px;"></th>
              <?php
              $display = '';
              foreach (jeedom::getConfiguration('eqLogic:displayType') as $key => $value) {
                $display .= '<th>{{' . $value['name'] . '}}</th>';
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
                      <option value="default">Défaut</option>
                      <?php

                      if (is_array($cmd_widgetDashboard[$cmd->getType()]) && is_array($cmd_widgetDashboard[$cmd->getType()][$cmd->getSubType()]) && count($cmd_widgetDashboard[$cmd->getType()][$cmd->getSubType()]) > 0) {
                        $types = array();
                        foreach ($cmd_widgetDashboard[$cmd->getType()][$cmd->getSubType()] as $key => $info) {
                          if (isset($info['type'])) {
                            $info['key'] = $key;
                            if (!isset($types[$info['type']])) {
                              $types[$info['type']][0] = $info;
                            } else {
                              array_push($types[$info['type']], $info);
                            }
                          }
                        }
                        ksort($types);
                        $display = '';
                        foreach ($types as $type) {
                          usort($type, function ($a, $b) {
                            return strcmp($a['name'], $b['name']);
                          });
                          foreach ($type as $key => $widget) {
                            if ($widget['name'] == 'default') {
                              continue;
                            }
                            if ($key == 0) {
                              $display .= '<optgroup label="{{' . ucfirst($widget['type']) . '}}">';
                            }
                            if(isset($widget['location']) && $widget['location'] != 'core' && $widget['location'] != 'custom'){
                              $display .= '<option value="'.$widget['location'].'::' . $widget['name'].'">' . ucfirst($widget['location']).'/'.ucfirst($widget['name']) . '</option>';
                            }else{
                              $display .= '<option value="'.$widget['location'].'::' . $widget['name'].'">' . ucfirst($widget['name']) . '</option>';
                            }
                          }
                          $display .= '</optgroup>';
                        }
                        echo $display;
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
                      <option value="default">Défaut</option>';
                      <?php
                      if (is_array($cmd_widgetMobile[$cmd->getType()]) && is_array($cmd_widgetMobile[$cmd->getType()][$cmd->getSubType()]) && count($cmd_widgetMobile[$cmd->getType()][$cmd->getSubType()]) > 0) {
                        $types = array();
                        foreach ($cmd_widgetMobile[$cmd->getType()][$cmd->getSubType()] as $key => $info) {
                          if (isset($info['type'])) {
                            $info['key'] = $key;
                            if (!isset($types[$info['type']])) {
                              $types[$info['type']][0] = $info;
                            } else {
                              array_push($types[$info['type']], $info);
                            }
                          }
                        }
                        ksort($types);
                        $display = '';
                        foreach ($types as $type) {
                          usort($type, function ($a, $b) {
                            return strcmp($a['name'], $b['name']);
                          });

                          foreach ($type as $key => $widget) {
                            if ($widget['name'] == 'default') {
                              continue;
                            }
                            if ($key == 0) {
                              $display .= '<optgroup label="{{' . ucfirst($widget['type']) . '}}">';
                            }
                            if(isset($widget['location']) && $widget['location'] != 'core' && $widget['location'] != 'custom'){
                              $display .= '<option value="'.$widget['location'].'::' . $widget['name'].'">' . ucfirst($widget['location']).'/'.ucfirst($widget['name']) . '</option>';
                            }else{
                              $display .= '<option value="'.$widget['location'].'::' . $widget['name'].'">' . ucfirst($widget['name']) . '</option>';
                            }
                          }
                          $display .= '</optgroup>';
                        }
                        echo $display;
                      }
                      ?>
                    </select>
                  <?php }
                  ?>
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
                foreach (jeedom::getConfiguration('eqLogic:displayType') as $key => $value) {
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
                foreach (jeedom::getConfiguration('eqLogic:displayType') as $key => $value) {
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
                foreach (jeedom::getConfiguration('eqLogic:displayType') as $key => $value) {
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
          <legend><i class="fas fa-pencil-ruler"></i> {{Paramètres optionnels widget}}
            <a class="btn btn-success btn-xs pull-right" id="bt_addWidgetParametersCmd" style="position:relative;right:5px;"><i class="fas fa-plus-circle"></i> Ajouter</a>
          </legend>
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
                $tr = '';
                foreach ($cmd->getDisplay('parameters') as $key => $value) {
                  $tr .= '<tr>';
                  $tr .= '<td>';
                  $tr .= '<input class="form-control key" value="' . $key . '" />';
                  $tr .= '</td>';
                  $tr .= '<td>';
                  $tr .= '<input class="form-control value" value="' . $value . '" />';
                  $tr .= '</td>';
                  $tr .= '<td>';
                  $tr .= '<a class="btn btn-danger btn-xs removeWidgetParameter"><i class="fas fa-times"></i> Supprimer</a>';
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
  if ($('body').attr('data-page')=="widgets") {
    $('a[href="#cmd_display"]').click()
  }
  //widgets default if empty:
  var dashWidget = $('select[data-l2key="dashboard"]')
  if (dashWidget.val()==null) dashWidget.val($('select[data-l2key="dashboard"] option:first').val())
  var mobileWidget = $('select[data-l2key="mobile"]')
  if (mobileWidget.val()==null) mobileWidget.val($('select[data-l2key="mobile"] option:first').val())

  //format update linked cmds:
  var spanValues = $('#cmd_information .cmdAttr[data-l1key="value"]')
  var values = spanValues.html()
  spanValues.hide()
  if (values != '') {
    var arrValues = values.split('#')
    var spans = ''
    arrValues.forEach(function(thisValue) {
      if (thisValue != '') {
        spans += '<span class="label label-primary">#' + thisValue + '#</span><br>'
      }
    })
    spanValues.parent().prepend(spans)
  }
  jeedom.timeline.autocompleteFolder()
})

$('.cmdAttr[data-l2key="timeline::enable"]').off('change').on('change',function(){
  if($(this).value() == 1){
    $('.cmdAttr[data-l2key="timeline::folder"]').show();
  }else{
    $('.cmdAttr[data-l2key="timeline::folder"]').hide();
  }
});

$('#cmdConfigureTab').off('click').on('click',function(){
  setTimeout(function(){ taAutosize(); }, 100);
})
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
  $('#md_modal3').dialog({title: "{{Informations}}"});
  $("#md_modal3").load('index.php?v=d&modal=object.display&class=cmd&id='+cmdInfo.id).dialog('open');
});
$('#bt_cmdConfigureGraph').on('click', function () {
  $('#md_modal3').dialog({title: "{{Graphique des liens}}"});
  $("#md_modal3").load('index.php?v=d&modal=graph.link&filter_type=cmd&filter_id='+cmdInfo.id).dialog('open');
});

$('#bt_cmdConfigureCopyHistory').off('click').on('click',function(){
  jeedom.cmd.getSelectModal({cmd: {type: 'info', subType: cmdInfo.subType}}, function (result) {
    var target_id = result.cmd.id
    var name = result.human
    bootbox.confirm('{{Êtes-vous sûr de vouloir copier l\'historique de}} <strong>'+cmdInfo.name+'</strong> {{vers}} <strong>'+name+'</strong> ? {{Il est conseillé de vider l\'historique de la commande}} : <strong>'+name+'</strong> {{ avant la copie}}', function (result) {
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
    bootbox.confirm('{{Êtes-vous sûr de vouloir copier l\'historique de}} <strong>'+cmdInfo.name+'</strong> {{vers}} <strong>'+name+'</strong> ? {{Il est conseillé de vider l\'historique de la commande}} : <strong>'+name+'</strong> {{ avant la copie}}', function (result) {
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
    bootbox.confirm('{{Êtes-vous sûr de vouloir remplacer}} <strong>'+cmdInfo.name+'</strong> {{par}} <strong>'+name+'</strong> ?', function (result) {
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
    bootbox.confirm('{{Êtes-vous sûr de vouloir remplacer l\'ID}} <strong>'+name+'</strong> {{par}} <strong>'+cmdInfo.name+'</strong> ?', function (result) {
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
  bootbox.confirm('{{Êtes-vous sûr de vouloir remplacer}} <strong>'+target_id+'</strong> {{par}} <strong>'+cmdInfo.name+'</strong> ?', function (result) {
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
taAutosize();
$('#bt_cmdConfigureSave').on('click', function (event) {
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
  jeedom.cmd.save({
    cmd: cmd,
    error: function (error) {
      $('#md_displayCmdConfigure').showAlert({message: error.message, level: 'danger'});
    },
    success: function () {
      modifyWithoutSave = false;
      $('#md_displayCmdConfigure').showAlert({message: '{{Sauvegarde réussie}}', level: 'success'});
      if (event.ctrlKey) {
        setTimeout(function() { $('#md_modal').dialog('close') }, 500);
      }
    }
  })
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

$("body").off('click',".listAction").on('click',".listAction",  function () {
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
  $('#div_' + _type + ' .' + _type + '').last().setValues(_action, '.expressionAttr');
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
    $('#bt_cmdConfigureSelectMultipleAlertToogle').off('click').on('click', function () {
      var state = false;
      if ($(this).attr('data-state') == 0) {
        state = true;
        $(this).attr('data-state', 1)
        .find('i').removeClass('fa-check-circle-o').addClass('fa-circle-o');
        $('#table_cmdConfigureSelectMultiple tbody tr .selectMultipleApplyCmd:visible').value(1);
      } else {
        state = false;
        $(this).attr('data-state', 0)
        .find('i').removeClass('fa-circle-o').addClass('fa-check-circle-o');
        $('#table_cmdConfigureSelectMultiple tbody tr .selectMultipleApplyCmd:visible').value(0);
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
    $('tr[data-cmd_id="' +  cmdInfo.id + '"] .cmdAttr[data-l1key=display][data-l2key=icon]').empty().append(_icon);
  });
});

$('body').undelegate('.cmdAttr[data-l1key=display][data-l2key=icon]', 'click').delegate('.cmdAttr[data-l1key=display][data-l2key=icon]', 'click', function () {
  $(this).empty();
  $('tr[data-cmd_id="' +  cmdInfo.id + '"] .cmdAttr[data-l1key=display][data-l2key=icon]').empty();
});

$('#bt_cmdConfigureLogRealTime').off('click').on('click', function () {
  $('#md_modal3').dialog({title: "{{Logs}}"});
  $('#md_modal3').load('index.php?v=d&modal=log.display&log=event&search=' + cmdInfoSearchString).dialog('open');
});

$('#bt_cmdConfigureShowHistory').on( 'click',function () {
  $('#md_modal3').dialog({title: "Historique"});
  $("#md_modal3").load('index.php?v=d&modal=cmd.history&id=' + cmdInfo.id).dialog('open');
});

$('#bt_cmdConfigureTest').on('click',function(){
  jeedom.cmd.test({id: cmdInfo.id, alert : '#md_displayCmdConfigure'});
});
</script>
