<?php
if (!isConnect('admin')) {
  throw new Exception('{{401 - Accès non autorisé}}');
}

$listPlugins = plugin::listPlugin();
$listObjects = jeeObject::buildTree(null, false);
$listEqlogics = eqLogic::all();
$listCommands = cmd::all();

sendVarToJS([
  'jeephp2js.listPlugins' => utils::o2a($listPlugins),
  'jeephp2js.listObjects' => utils::o2a($listObjects),
  'jeephp2js.listEqlogics' => utils::o2a($listEqlogics),
  'jeephp2js.listCommands' => utils::o2a($listCommands)
]);

?>

<div class="floatingbar">
  <div class="input-group">
      <span class="input-group-btn">
          <a href="index.php?v=d&p=backup" class="btn btn-success btn-sm roundedLeft"><i class="fas fa-save"></i> {{Sauvegarde Système}}
          </a><a class="btn btn-danger btn-sm roundedRight" id="bt_replace"><i class="fas fa-random"></i> {{Remplacer}}</a>
      </span>
  </div>
</div>
<br/><br/>

<div class="row row-overflow">
  <div class="col-xs-12">

    <div class="panel-group" id="accordionFilter">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">
              <a class="accordion-toggle" data-toggle="collapse" data-parent="" aria-expanded="true" href="#panelFilter"><i class="fas fa-filter"></i> {{Filtres}}</a>
            </h3>
          </div>
          <div id="panelFilter" class="panel-collapse collapse in" aria-expanded="true" style="">
            <div class="panel-body">

              <div class="col-lg-1"></div>
              <div class="col-lg-1 col-md-2 col-xs-3">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" title="{{Filtre par objet}}">
                  <i class="fas fa-filter"></i> {{Objets}}&nbsp;&nbsp;&nbsp;<span class="caret"></span>
                </button>
                <ul id="objectFilter" class="dropdown-menu" role="menu">
                  <li>
                    <a id="objectFilterAll"> {{Tous}}</a>
                    <a id="objectFilterNone"> {{Aucun}}</a>
                  </li>
                  <li class="divider"></li>
                  <?php
                    $li = '';
                    $li .= '<li><a><input checked type="checkbox" class="objectFilterKey" data-key=""/>&nbsp;{{Aucun}}</a></li>';
                    foreach ($listObjects as $object) {
                      $decay = $object->getConfiguration('parentNumber');
                      $li .= '<li><a><input checked type="checkbox" class="objectFilterKey" data-key="' . $object->getId() . '"/>&nbsp;' . str_repeat('&nbsp;&nbsp;', $decay) . $object->getName() . '</a></li>';
                    }
                    echo $li;
                  ?>
                </ul>
              </div>
              <div class="col-lg-1 col-md-2 col-xs-3">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" title="{{Filtre par plugin}}">
                  <i class="fas fa-filter"></i> {{Plugins}}&nbsp;&nbsp;&nbsp;<span class="caret"></span>
                </button>
                <ul id="pluginFilter" class="dropdown-menu" role="menu">
                  <li>
                    <a id="pluginFilterAll"> {{Tous}}</a>
                    <a id="pluginFilterNone"> {{Aucun}}</a>
                  </li>
                  <li class="divider"></li>
                  <?php
                    $li = '';
                    foreach ($listPlugins as $plugin) {
                      $li .=  '<li><a><input checked type="checkbox" class="pluginFilterKey" data-key="' . $plugin->getId() . '"/>&nbsp;' . $plugin->getName() . '</a></li>';
                    }
                    echo $li;
                  ?>
                </ul>
              </div>

              <div class="col-lg-1 col-md-2 col-xs-3">
                <a class="btn btn-success" id="bt_applyFilters"><i class="fas fa-filter"></i> {{Filtrer}}</a>
              </div>

              <div class="col-lg-1 col-md-2 col-xs-3">
                <a class="btn btn-info" id="bt_clearReplace"><i class="fas fa-times"></i> {{Reset}}</a>
              </div>

            </div>
        </div>
      </div>
    </div>

    <div class="panel-group" id="accordionOption">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">
              <a class="accordion-toggle" data-toggle="collapse" data-parent="" aria-expanded="true" href="#panelOption"><i class="fas fa-cogs"></i> {{Options}}</a>
            </h3>
          </div>
          <div id="panelOption" class="panel-collapse collapse in" aria-expanded="true" style="">
            <div class="panel-body">

              <div class="form-group col-lg-12">
                <label class="col-lg-4 col-md-6 col-sm-6 col-xs-10 control-label">{{Mode}}
                <sup><i class="fas fa-question-circle" title="{{Remplace l’équipement et ses commandes (scénarios, etc.) ou copie seulement les propriétés souhaitées.}}"></i></sup>
                </label>
                <div class="col-lg-2">
                  <select id="opt_mode" class="form-control">
                    <option value="replace">{{Remplacer}}</option>
                    <option value="copy">{{Copier}}</option>
                  </select>
                </div>
              </div>

              <div class="form-group col-lg-12">
                <label class="col-lg-4 col-md-6 col-sm-6 col-xs-10 control-label">{{Copier la configuration de l'équipement source}}
                <sup><i class="fas fa-question-circle" title="{{Copie les propriétés de l’équipement source (objet parent, catégories, visibilité, positionnement dashboard, designs, vues, etc) sur l’équipement cible.}}"></i></sup>
                </label>
                <div class="col-lg-1">
                  <input id="opt_copyEqProperties" type="checkbox" class="form-control" />
                </div>
              </div>

              <div class="form-group col-lg-12">
                <label class="col-lg-4 col-md-6 col-sm-6 col-xs-10 control-label">{{Cacher l'équipement source}}
                <sup><i class="fas fa-question-circle" title="{{Rend invisible l'équipement source.}}"></i></sup>
                </label>
                <div class="col-lg-1">
                  <input id="opt_hideEqs" type="checkbox" class="form-control" />
                </div>
              </div>

              <div class="form-group col-lg-12">
                <label class="col-lg-4 col-md-6 col-sm-6 col-xs-10 control-label">{{Copier la configuration de la commande source}}
                <sup><i class="fas fa-question-circle" title="{{Copie les propriétés et configurations de la commande source sur la commande cible.}}"></i></sup>
                </label>
                <div class="col-lg-1">
                  <input id="opt_copyCmdProperties" type="checkbox" class="form-control" />
                </div>
              </div>

              <div class="form-group col-lg-12">
                <label class="col-lg-4 col-md-6 col-sm-6 col-xs-10 control-label">{{Supprimer l'historique de la commande cible}}
                <sup><i class="fas fa-question-circle" title="{{Supprime l'historique de la commande cible.}}"></i></sup>
                </label>
                <div class="col-lg-1">
                  <input id="opt_removeHistory" type="checkbox" class="form-control" />
                </div>
              </div>

              <div class="form-group col-lg-12">
                <label class="col-lg-4 col-md-6 col-sm-6 col-xs-10 control-label">{{Copier l'historique de la commande source}}
                <sup><i class="fas fa-question-circle" title="{{Copie l'historique de la commande source sur la commande cible.}}"></i></sup>
                </label>
                <div class="col-lg-1">
                  <input id="opt_copyHistory" type="checkbox" class="form-control" />
                </div>
              </div>

            </div>
        </div>
      </div>
    </div>

    <div class="panel-group">
      <div id="progresscontainer" class="hidden">
        <div id="progressbar" class="progress-bar" style="float:none; height: 25px;"></div>
        <div><i class="fas fa-sync fa-spin"></i><span id="progresslog"></span></div>
      </div>
    </div>

    <div class="panel-group" id="accordionReplace">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">
              <a class="accordion-toggle" data-toggle="collapse" data-parent="" aria-expanded="true" href="#panelReplace"><i class="fas fa-random"></i> {{Remplacements}}</a>
            </h3>
          </div>
          <div id="panelReplace" class="panel-collapse collapse in" aria-expanded="true" style="">
            <div class="panel-body">

              <div class="input-group" style="margin-bottom:5px;display: inline-table;">
                <input class="form-control roundedLeft" placeholder="{{Rechercher}}" id="in_searchByName"/>
                <div class="input-group-btn">
                  <a id="bt_resetSearchName" class="btn roundedRight" style="width:30px;"><i class="fas fa-times"></i></a>
                </div>
              </div>
              <div style="text-align: center;">{{Source}} <i class="far fa-arrow-alt-circle-right"></i> {{Cible}}</div>
              <div id="eqSource" class="row form-group col-lg-12">
              </div>

            </div>
        </div>
      </div>
    </div>

  </div>
</div>

<?php include_file('desktop', 'replace', 'js');?>