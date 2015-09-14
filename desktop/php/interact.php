<?php
if (!hasRight('interactview', true)) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$interacts = array();
$interacts[-1] = interactDef::all(null);
$interactListGroup = interactDef::listGroup();
if (is_array($interactListGroup)) {
	foreach ($interactListGroup as $group) {
		$interacts[$group['group']] = interactDef::all($group['group']);
	}
}
?>

<div style="position : fixed;height:100%;width:15px;top:50px;left:0px;z-index:998;background-color:#f6f6f6;" class="div_smallSideBar" id="bt_displayInteractList"><i class="fa fa-arrow-circle-o-right" style="color : #b6b6b6;"></i></div>

<div class="row row-overflow">
    <div class="col-lg-2 col-md-3 col-sm-4" id="div_listInteract" style="z-index:999">
        <div class="bs-sidebar">

            <a id="bt_addInteract" class="btn btn-default" style="width : 100%;margin-top : 5px;margin-bottom: 5px;"><i class="fa fa-plus-circle"></i> {{Ajouter interaction}}</a>
            <div class="row">
                <div class="col-xs-6">
                    <a id="bt_regenerateInteract" class="btn btn-warning" style="width : 100%;margin-top : 5px;margin-bottom: 5px;text-shadow : none;"><i class="fa fa-refresh"></i> {{Regénérer}}</a>
                </div>
                <div class="col-xs-6">
                    <a id="bt_testInteract" class="btn btn-primary" style="width : 100%;margin-top : 5px;margin-bottom: 5px;text-shadow : none;"><i class="fa fa-comment-o"></i> {{Tester}}</a>
                </div>
            </div>

            <input id='in_treeSearch' class='form-control' placeholder="{{Rechercher}}" />
            <div id="div_tree">
                <ul id="ul_interact" >
                    <li data-jstree='{"opened":true}'>
                        <a>Aucune</a>
                        <ul>
                            <?php
foreach ($interacts[-1] as $interact) {
	echo '<li data-jstree=\'{"opened":true,"icon":""}\'>';
	echo ' <a class="li_interact" id="interact' . $interact->getId() . '" data-interact_id="' . $interact->getId() . '" >' . $interact->getHumanName() . '</a>';
	echo '</li>';
}
?>
                       </ul>
                       <?php
foreach ($interactListGroup as $group) {
	if ($group['group'] != '') {
		echo '<li data-jstree=\'{"opened":true}\'>';
		echo '<a>' . $group['group'] . '</a>';
		echo '<ul>';
		foreach ($interacts[$group['group']] as $interact) {
			echo '<li data-jstree=\'{"opened":true,"icon":""}\'>';
			echo ' <a class="li_interact" id="interact' . $interact->getId() . '" data-interact_id="' . $interact->getId() . '" >' . $interact->getHumanName() . '</a>';
			echo '</li>';
		}
		echo '</ul>';
		echo '</li>';
	}
}
?>
                 </ul>
             </div>

         </div>
     </div>

     <div id="interactThumbnailDisplay" style="border-left: solid 1px #EEE; padding-left: 25px;">
       <div class="interactListContainer">
           <legend>{{Gestion}}</legend>
           <div class="cursor" id="bt_addInteract2" style="background-color : #ffffff; height : 100px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 140px;margin-left : 10px;" >
             <center>
                <i class="fa fa-plus-circle" style="font-size : 4em;color:#94ca02;"></i>
            </center>
            <span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#94ca02"><center>{{Ajouter}}</center></span>
        </div>
        <div class="cursor" id="bt_regenerateInteract2" style="background-color : #ffffff; height : 100px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 140px;margin-left : 10px;" >
         <center>
             <i class="fa fa-refresh" style="font-size : 4em;color:#f0ad4e;"></i>
         </center>
         <span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#f0ad4e"><center>{{Regénérer}}</center></span>
     </div>
     <div class="cursor" id="bt_testInteract2" style="background-color : #ffffff; height : 100px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 140px;margin-left : 10px;" >
         <center>
            <i class="fa fa-comment-o" style="font-size : 4em;color:#337ab7;"></i>
        </center>
        <span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#337ab7"><center>{{Tester}}</center></span>
    </div>
</div>

<legend>{{Mes interactions}}</legend>
<?php
echo '<legend>Aucun</legend>';
echo '<div class="interactListContainer">';
foreach ($interacts[-1] as $interact) {
	echo '<div class="interactDisplayCard cursor" data-interact_id="' . $interact->getId() . '" style="background-color : #ffffff; height : 140px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >';
	echo "<center>";
	echo '<i class="fa fa-bullhorn" style="font-size : 4em;color:#767676;"></i>';
	echo "</center>";
	echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>' . $interact->getHumanName() . '</center></span>';
	echo '</div>';
}
echo '</div>';

foreach ($interactListGroup as $group) {
	if ($group['group'] != '') {
		echo '<legend>' . $group['group'] . '</legend>';
		echo '<div class="interactListContainer">';
		foreach ($interacts[$group['group']] as $interact) {
			echo '<div class="interactDisplayCard cursor" data-interact_id="' . $interact->getId() . '" style="background-color : #ffffff; height : 140px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >';
			echo "<center>";
			echo '<i class="fa fa-bullhorn" style="font-size : 4em;color:#767676;"></i>';
			echo "</center>";
			echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>' . $interact->getHumanName() . '</center></span>';
			echo '</div>';
		}
		echo '</div>';
	}
}
?>
</div>



<div class="interact" style="display: none;margin-left:20px;" id="div_conf">
    <div class="row">
        <div class="col-sm-6">
            <form class="form-horizontal">
                <fieldset>
                    <legend><i class="fa fa-arrow-circle-left cursor" id="bt_interactThumbnailDisplay"></i>
                        {{Général}}
                        <a class="btn btn-default btn-xs pull-right" id="bt_duplicate"><i class="fa fa-files-o"></i> {{Dupliquer}}</a>
                    </legend>
                    <div class="form-group">
                        <label class="col-sm-3 col-xs-3 control-label">{{Nom}}</label>
                        <div class="col-sm-9 col-xs-9">
                            <input class="form-control interactAttr" type="text" data-l1key="name" placeholder=""/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 col-xs-3 control-label">{{Groupe}}</label>
                        <div class="col-sm-9 col-xs-9">
                            <input class="form-control interactAttr" type="text" data-l1key="group" placeholder=""/>
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
                        <div class="col-sm-8 col-xs-8">
                            <textarea class="form-control interactAttr" type="text" data-l1key="reply" placeholder=""></textarea>
                        </div>
                        <div class="col-sm-1">
                            <a class="btn btn-default cursor listEquipementInfoReply input-sm"><i class="fa fa-list-alt "></i></a>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 col-xs-3 control-label">{{Conversion binaire}}</label>
                        <div class="col-sm-9 col-xs-9">
                            <input class="form-control tooltips interactAttr" type="text" data-l1key="options" data-l2key="convertBinary" placeholder="" title="{{Convertir les commandes binaires}}"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 col-xs-3 control-label">{{Utilisateurs autorisés}}</label>
                        <div class="col-sm-9 col-xs-9">
                            <input class="form-control tooltips interactAttr" type="text" data-l1key="person" placeholder="" title="{{Liste des utilisateurs (login) séparés par un |}}"/>
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
                            <a class="btn btn-default displayInteracQuery"><i class="fa fa-eye"></i> {{Voir}}</a>
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
                            <select class="interactAttr form-control" data-l1key="link_type">';
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
            <a class="btn btn-danger" id="bt_removeInteract"><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>
            <a class="btn btn-success" id="bt_saveInteract"><i class="fa fa-check-circle"></i> {{Enregistrer}}</a>
        </div>
    </fieldset>
</form>
</div>
</div>

<?php include_file('desktop', 'interact', 'js');?>
