<?php
if (!hasRight('interactview', true)) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
?>

<div class="row row-overflow">
    <div class="col-lg-2 col-md-3 col-sm-4">
        <div class="bs-sidebar">
            <ul id="ul_interact" class="nav nav-list bs-sidenav">
                <a id="bt_addInteract" class="btn btn-default" style="width : 100%;margin-top : 5px;margin-bottom: 5px;"><i class="fa fa-plus-circle"></i> {{Ajouter interaction}}</a>
                <li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
                <?php
                $allObject = object::buildTree();
                foreach (interactDef::all() as $interact) {
                    if ($interact->getName() != '') {
                        echo '<li class="li_interact cursor" data-interact_id="' . $interact->getId() . '"><a>' . $interact->getName() . '</a></li>';
                    } else {
                        echo '<li class="li_interact cursor" data-interact_id="' . $interact->getId() . '"><a>' . $interact->getQuery() . '</a></li>';
                    }
                }
                ?>
            </ul>
        </div>
    </div>
    <div class="col-lg-10 col-md-9 col-sm-8 interact" style="display: none;" id="div_conf">
        <div class="row">
            <div class="col-sm-6">
                <form class="form-horizontal">
                    <fieldset>
                        <legend>
                            {{Général}}
                            <a class="btn btn-default btn-xs pull-right" id="bt_duplicate">{{Dupliquer}}</a>
                        </legend>
                        <div class="form-group">
                            <label class="col-sm-3 col-xs-3 control-label">{{Nom}}</label>
                            <div class="col-sm-9 col-xs-9">
                                <input class="form-control interactAttr" type="text" data-l1key="name" placeholder=""/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 col-xs-3 control-label">{{Demande}}</label>
                            <div class="col-sm-9 col-xs-9">
                                <input class="form-control interactAttr" type="text" data-l1key="id" style="display : none;"/>
                                <input class="form-control interactAttr" type="text" data-l1key="query" placeholder=""/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 col-xs-3 control-label">{{Synonyme}}</label>
                            <div class="col-sm-9 col-xs-9">
                                <input class="form-control interactAttr tooltips" type="text" data-l1key="options" data-l2key="synonymes" placeholder="" title="{{Remplace les mots par leur synonymes lors de la génération des commandes}}"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 col-xs-3 control-label">{{Réponse}}</label>
                            <div class="col-sm-9 col-xs-9">
                                <input class="form-control interactAttr" type="text" data-l1key="reply" placeholder=""/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 col-xs-3 control-label">{{Conversion binaire}}</label>
                            <div class="col-sm-9 col-xs-9">
                                <input class="form-control tooltips interactAttr" type="text" data-l1key="options" data-l2key="convertBinary" placeholder="" title="{{Convertir les commandes binaire}}"/>
                            </div>
                        </div>
                         <div class="form-group">
                            <label class="col-sm-3 col-xs-3 control-label">{{Utilisateurs autorisés}}</label>
                            <div class="col-sm-9 col-xs-9">
                                <input class="form-control tooltips interactAttr" type="text" data-l1key="person" placeholder="" title="{{Liste des utilisateur (login) separé par un |}}"/>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="col-sm-6">
                <form class="form-horizontal">
                    <fieldset>
                        <legend>{{Phrases générées}}</legend>
                        <div class="form-group">
                            <label class="col-sm-4 col-xs-7 control-label">{{Phrases générées}}</label>
                            <div class="col-sm-8 col-xs-4">
                                <a class="btn btn-default displayInteracQuery">{{Voir}}</a>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 col-xs-7 control-label">{{Nombre de phrases générées}}</label>
                            <div class="col-sm-8 col-xs-2">
                                <span class="label label-success interactAttr" data-l1key="nbInteractQuery"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 col-xs-7 control-label">{{Nombre de phrases actives}}</label>
                            <div class="col-sm-8 col-xs-2">
                                <span class="label label-success interactAttr" data-l1key="nbEnableInteractQuery"></span>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>


        <div class="row">
            <div class="col-sm-6">
                <form class="form-horizontal">
                    <fieldset>
                        <legend>{{Action}}</legend>
                        <div class="form-group">
                            <label class="col-sm-3 col-xs-6 control-label">{{Type d'action}}</label>
                            <div class="col-sm-9 col-xs-6">
                                <select class="interactAttr form-control input-sm" data-l1key="link_type">';
                                    <option value="cmd">{{Commande}}</option>
                                    <option value="whatDoYouKnow">{{Que sais tu ?}}</option>
                                    <option value="scenario">{{Scénario}}</option>
                                    <option value="none">{{Aucun}}</option>
                                </select>
                            </div>
                        </div>
                        <div id="linkOption"></div>

                    </fieldset>
                </form>
            </div>

            <div class="col-sm-6">
                <form class="form-horizontal" id="div_filtre">
                    <fieldset>
                        <legend>{{Filtres}}</legend>

                        <div class="form-group">
                            <label class="col-sm-6 control-label">{{Limiter aux commandes de type}}</label>
                            <div class="col-sm-4">
                                <select class="interactAttr form-control" data-l1key="filtres" data-l2key="cmd_type">
                                    <?php
                                    foreach (jeedom::getConfiguration('cmd:type') as $id => $type) {
                                        echo '<option value="' . $id . '">' . $type['name'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">{{Limiter aux commandes ayant pour sous-type}}</label>
                            <div class="col-sm-4">
                                <select class="interactAttr form-control" data-l1key="filtres" data-l2key="subtype">
                                    <option value="all">{{Tous}}</option>
                                    <?php
                                    foreach (jeedom::getConfiguration('cmd:type') as $type) {
                                        foreach ($type['subtype'] as $id => $subtype) {
                                            echo '<option value="' . $id . '">' . $subtype['name'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">{{Limiter aux commandes ayant pour unité}}</label>
                            <div class="col-sm-4">
                                <select class='interactAttr form-control' data-l1key='filtres' data-l2key='cmd_unite'>
                                    <option value="all">{{Tous}}</option>
                                    <?php
                                    foreach (cmd::allUnite() as $unite) {
                                        echo '<option value="' . $unite['unite'] . '" >' . $unite['unite'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">{{Limiter aux commandes appartenant à l'objet}}</label>
                            <div class="col-sm-4">
                                <select class='interactAttr form-control' data-l1key='filtres' data-l2key='object_id' >
                                    <option value="all">{{Tous}}</option>
                                    <?php
                                    foreach (object::all() as $object) {
                                        echo '<option value="' . $object->getId() . '" >' . $object->getName() . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">{{Limiter à l'équipement}}</label>
                            <div class="col-sm-4">
                                <select class='interactAttr form-control' data-l1key='filtres' data-l2key='eqLogic_id' >
                                    <option value="all">{{Tous}}</option>
                                    <?php
                                    foreach (eqLogic::all() as $eqLogic) {
                                        echo '<option value="' . $eqLogic->getId() . '" >' . $eqLogic->getHumanName() . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">{{Limiter au plugin}}</label>
                            <div class="col-sm-4">
                                <select class='interactAttr form-control' data-l1key='filtres' data-l2key='plugin'>
                                    <option value="all">{{Tous}}</option>
                                    <?php
                                    foreach (eqLogic::allType() as $type) {
                                        echo '<option value="' . $type['type'] . '" >' . $type['type'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-6 control-label">{{Limiter à la catégorie}}</label>
                            <div class="col-sm-4">
                                <select class='interactAttr form-control' data-l1key='filtres' data-l2key='eqLogic_category'>
                                    <option value="all">{{Toutes}}</option>
                                    <?php
                                    foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
                                        echo '<option value="' . $key . '">{{' . $value['name'] . '}}</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                    </fieldset>
                </form>
            </div>
        </div>
        <form class="form-horizontal">
            <fieldset>
                <div class="form-actions">
                    <a class="btn btn-success" id="bt_saveInteract"><i class="fa fa-check-circle"></i> {{Enregistrer}}</a>
                    <a class="btn btn-danger" id="bt_removeInteract"><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>
                </div>

            </fieldset>
        </form>
    </div>
</div>

<?php include_file('desktop', 'interact', 'js'); ?>
