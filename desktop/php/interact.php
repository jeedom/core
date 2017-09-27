<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$interacts = array();
$totalInteract = interactDef::all();
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
  <div class="col-xs-2" id="div_listInteract" style="z-index:999">
    <div class="bs-sidebar nav nav-list bs-sidenav" >
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
          <?php if (count($interacts[-1]) > 0) {
	?>
           <li data-jstree='{"opened":true}'>
            <?php
echo '<a>Aucune - ' . count($interacts[-1]) . ' interaction(s)</a>';
	echo '<ul>';
	foreach ($interacts[-1] as $interact) {
		echo '<li data-jstree=\'{"opened":true,"icon":""}\'>';
		echo ' <a class="li_interact" id="interact' . $interact->getId() . '" data-interact_id="' . $interact->getId() . '" title="{{Interaction ID :}} ' . $interact->getId() . '">' . $interact->getHumanName(false, true) . '</a>';
		echo '</li>';
	}
	?>
          </ul>
          <?php
}
foreach ($interactListGroup as $group) {
	if ($group['group'] != '') {
		echo '<li data-jstree=\'{"opened":true}\'>';
		echo '<a>' . $group['group'] . ' - ' . count($interacts[$group['group']]) . ' interaction(s)</a>';
		echo '<ul>';
		foreach ($interacts[$group['group']] as $interact) {
			echo '<li data-jstree=\'{"opened":true,"icon":""}\'>';
			echo ' <a class="li_interact" id="interact' . $interact->getId() . '" data-interact_id="' . $interact->getId() . '" title="{{Interaction ID :}} ' . $interact->getId() . '">' . $interact->getHumanName(false, true) . '</a>';
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
<div id="interactThumbnailDisplay" class="col-xs-10" style="border-left: solid 1px #EEE; padding-left: 25px;">
 <div class="interactListContainer">
   <legend><i class="fa fa-cog"></i>  {{Gestion}}</legend>
   <div class="cursor" id="bt_addInteract2" style="background-color : #ffffff; height : 120px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 140px;margin-left : 10px;" >
     <center>
      <i class="fa fa-plus-circle" style="font-size : 6em;color:#94ca02;"></i>
    </center>
    <span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#94ca02"><center>{{Ajouter}}</center></span>
  </div>
  <div class="cursor" id="bt_regenerateInteract2" style="background-color : #ffffff; height : 120px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 140px;margin-left : 10px;" >
   <center>
     <i class="fa fa-refresh" style="font-size : 6em;color:#f0ad4e;"></i>
   </center>
   <span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#f0ad4e"><center>{{Regénérer}}</center></span>
 </div>
 <div class="cursor" id="bt_testInteract2" style="background-color : #ffffff; height : 120px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 140px;margin-left : 10px;" >
   <center>
    <i class="fa fa-comment-o" style="font-size : 6em;color:#337ab7;"></i>
  </center>
  <span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#337ab7"><center>{{Tester}}</center></span>
</div>
</div>

<legend><i class="fa fa-comments-o"></i>  {{Mes interactions}}</legend>
<?php
if (count($totalInteract) == 0) {
	echo "<br/><br/><br/><center><span style='color:#767676;font-size:1.2em;font-weight: bold;'>Vous n'avez encore aucune interaction. Cliquez sur ajouter une interaction pour commencer</span></center>";
} else {
	echo "<center><span style='color:#767676;font-size:1.2em;font-weight: bold;'>Vous avez " . count($totalInteract) . " interaction(s) dans " . count($interactListGroup) . " groupes</span></center>";
	if (count($interacts[-1]) > 0) {
		echo '<div class="panel-group" id="accordionInteract">';
		echo '<div class="panel panel-default">';
		echo '<div class="panel-heading">';
		echo '<h3 class="panel-title">';
		echo '<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionInteract" href="#config_aucun" style="text-decoration:none;">Aucun - ' . count($interacts[-1]) . ' interaction(s)</a>';
		echo '</h3>';
		echo '</div>';
		echo '<div id="config_aucun" class="panel-collapse collapse">';
		echo '<div class="panel-body">';
		echo '<div class="interactListContainer">';
		foreach ($interacts[-1] as $interact) {
			echo '<div class="interactDisplayCard cursor" data-interact_id="' . $interact->getId() . '" style="text-align: center; background-color : #ffffff; min-height : 140px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >';
			echo '<img src="core/img/interaction.png" height="90" width="85" />';
			echo "<br>";
			echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;">' . $interact->getHumanName(true, true, true, true) . '</span>';
			echo '</div>';
		}
		echo '</div>';
		echo '</div>';
		echo '</div>';
		echo '</div>';
	}
	$i = 0;
	foreach ($interactListGroup as $group) {
		if ($group['group'] != '') {
			echo '<div class="panel panel-default">';
			echo '<div class="panel-heading">';
			echo '<h3 class="panel-title">';
			echo '<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionInteract" href="#config_' . $i . '" style="text-decoration:none;">' . $group['group'] . ' - ' . count($interacts[$group['group']]) . ' interaction(s)</a>';
			echo '</h3>';
			echo '</div>';
			echo '<div id="config_' . $i . '" class="panel-collapse collapse">';
			echo '<div class="panel-body">';
			echo '<div class="interactListContainer">';
			foreach ($interacts[$group['group']] as $interact) {
				echo '<div class="interactDisplayCard cursor" data-interact_id="' . $interact->getId() . '" style="text-align: center; background-color : #ffffff; min-height : 140px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >';
				echo '<img src="core/img/interaction.png" height="90" width="85" />';
				echo "<br>";
				echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;">' . $interact->getHumanName(true, true, true, true) . '</span>';
				echo '</div>';
			}
			echo '</div>';
			echo '</div>';
			echo '</div>';
			echo '</div>';
		}
		$i += 1;
	}
	echo '</div>';
}
?>
</div>

<div class="interact col-xs-10" style="display: none;" id="div_conf">
  <a class="btn btn-default pull-right" id="bt_duplicate"><i class="fa fa-files-o"></i> {{Dupliquer}}</a>
  <a class="btn btn-danger pull-right" id="bt_removeInteract"><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>
  <a class="btn btn-success pull-right" id="bt_saveInteract"><i class="fa fa-check-circle"></i> {{Enregistrer}}</a>
  <a class="btn btn-default displayInteracQuery pull-right"><i class="fa fa-eye"></i> {{Phrase(s)}} <span class="label label-success interactAttr" data-l1key="nbInteractQuery"></span></a>

  <ul class="nav nav-tabs" role="tablist">
   <li role="presentation"><a class="cursor" aria-controls="home" role="tab" id="bt_interactThumbnailDisplay"><i class="fa fa-arrow-circle-left"></i></a></li>
   <li role="presentation" class="active"><a href="#generaltab" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-tachometer"></i> {{Général}}</a></li>
   <li role="presentation"><a href="#filtertab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-filter"></i> {{Filtres}}</a></li>
   <li role="presentation"><a href="#actiontab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-cogs"></i> {{Actions}}</a></li>
 </ul>
</ul>

<div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
  <div role="tabpanel" class="tab-pane active" id="generaltab">
    <form class="form-horizontal">
      <br/>
      <fieldset>
        <div class="form-group">
          <label class="col-sm-2 col-xs-2 control-label">{{Nom}}</label>
          <div class="col-sm-9 col-xs-9">
            <input class="form-control interactAttr" type="text" data-l1key="name" placeholder=""/>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 col-xs-2 control-label">{{Groupe}}</label>
          <div class="col-sm-9 col-xs-9">
            <input class="form-control interactAttr" type="text" data-l1key="group" placeholder=""/>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 col-xs-2 control-label">{{Demande}}</label>
          <div class="col-sm-9 col-xs-9">
            <input class="form-control interactAttr" type="text" data-l1key="id" style="display : none;"/>
            <input class="form-control interactAttr" type="text" data-l1key="query" placeholder=""/>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 col-xs-2 control-label">{{Synonyme}}</label>
          <div class="col-sm-9 col-xs-9">
            <input class="form-control interactAttr" type="text" data-l1key="options" data-l2key="synonymes" placeholder="" title="{{Remplace les mots par leur synonymes lors de la génération des commandes}}"/>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 col-xs-2 control-label">{{Réponse}}</label>
          <div class="col-sm-8 col-xs-8">
            <textarea class="form-control interactAttr ta_autosize" type="text" data-l1key="reply" placeholder=""></textarea>
          </div>
          <div class="col-sm-1">
            <a class="btn btn-default cursor listEquipementInfoReply input-sm"><i class="fa fa-list-alt "></i></a>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 col-xs-2 control-label">{{Conversion binaire}}</label>
          <div class="col-sm-9 col-xs-9">
            <input class="form-control interactAttr" type="text" data-l1key="options" data-l2key="convertBinary" placeholder="" title="{{Convertir les commandes binaires}}"/>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 col-xs-2 control-label">{{Utilisateurs autorisés}}</label>
          <div class="col-sm-9 col-xs-9">
            <input class="form-control interactAttr" type="text" data-l1key="person" placeholder="" title="{{Liste des utilisateurs (login) séparés par un |}}"/>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 col-xs-2 control-label">{{Regexp d'exclusion}}</label>
          <div class="col-sm-9 col-xs-9">
            <input class="form-control interactAttr" type="text" data-l1key="options" data-l2key="exclude_regexp" placeholder="" />
          </div>
        </div>
        <div class="form-group">
          <label class="col-xs-2 control-label">{{Autoriser Jeedom à supprimer les demandes syntaxiquement incorrectes}}</label>
          <div class="col-xs-3">
            <input type="checkbox" class="interactAttr" data-l1key="options" data-l2key="allowSyntaxCheck">
          </div>
        </div>
      </fieldset>
    </form>
  </div>
  <div role="tabpanel" class="tab-pane" id="filtertab">
    <br/>
    <form class="form-horizontal" id="div_filtre">
      <fieldset>
        <div class="form-group">
          <label class="col-sm-3 control-label">{{Limiter aux commandes de type}}</label>
          <div class="col-sm-9">
            <?php
foreach (jeedom::getConfiguration('cmd:type') as $id => $type) {
	echo '<label style="margin-right:25px;"><input class="interactAttr" type="checkbox" data-l1key="filtres" data-l2key="type" data-l3key="' . $id . '" checked="true" />' . $type['name'] . '</label> ';
}
?>
         </div>
       </div>
       <div class="form-group">
        <label class="col-sm-3 control-label">{{Limiter aux commandes ayant pour sous-type}}</label>
        <div class="col-sm-9">
          <?php
foreach (jeedom::getConfiguration('cmd:type') as $type) {
	foreach ($type['subtype'] as $id => $subtype) {
		echo '<label style="margin-right:25px;"><input class="interactAttr" type="checkbox" data-l1key="filtres" data-l2key="subtype" data-l3key="' . $id . '" checked="true" />' . $subtype['name'] . '</label> ';
	}
}
?>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-3 control-label">{{Limiter aux commandes ayant pour unité}}</label>
      <div class="col-sm-9">
        <label style="margin-right:25px;"><input class="interactAttr" type="checkbox" data-l1key="filtres" data-l2key="unite" data-l3key="none" checked="true" />{{Sans unité}}</label>
        <?php
foreach (cmd::allUnite() as $unite) {
	if (trim($unite['unite']) == '') {
		continue;
	}
	echo '<label style="margin-right:25px;"><input class="interactAttr" type="checkbox" data-l1key="filtres" data-l2key="unite" data-l3key="' . $unite['unite'] . '" checked="true" />' . $unite['unite'] . '</label> ';
}
?>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label">{{Limiter aux commandes appartenant à l'objet}}</label>
    <div class="col-sm-9">
      <?php
foreach (object::all() as $object) {
	echo '<label style="margin-right:25px;"><input class="interactAttr" type="checkbox" data-l1key="filtres" data-l2key="object" data-l3key="' . $object->getId() . '" checked="true" />' . $object->getName() . '</label> ';
}
?>
   </div>
 </div>
 <div class="form-group">
  <label class="col-sm-3 control-label">{{Limiter aux plugins}}</label>
  <div class="col-sm-9">
    <?php
foreach (eqLogic::allType() as $type) {
	echo '<label style="margin-right:25px;"><input class="interactAttr" type="checkbox" data-l1key="filtres" data-l2key="plugin" data-l3key="' . $type['type'] . '" checked="true" />' . $type['type'] . '</label> ';
}
?>
 </div>
</div>

<div class="form-group">
  <label class="col-sm-3 control-label">{{Limiter aux categories}}</label>
  <div class="col-sm-9">
    <?php
echo '<label style="margin-right:25px;"><input class="interactAttr" type="checkbox" data-l1key="filtres" data-l2key="category" data-l3key="noCategory" checked="true" />{{Sans catégorie}}</label> ';
foreach (jeedom::getConfiguration('eqLogic:category') as $id => $category) {
	echo '<label style="margin-right:25px;"><input class="interactAttr" type="checkbox" data-l1key="filtres" data-l2key="category" data-l3key="' . $id . '" checked="true" />' . $category['name'] . '</label> ';
}
?>
 </div>
</div>
<div class="form-group">
  <label class="col-sm-3 control-label">{{Limiter aux visibles}}</label>
  <div class="col-sm-9">
   <?php
foreach (array('object' => 'Objets', 'eqlogic' => 'Equipements', 'cmd' => 'Commandes') as $id => $name) {
	echo '<label style="margin-right:25px;"><input class="interactAttr" type="checkbox" data-l1key="filtres" data-l2key="visible" data-l3key="' . $id . '" />' . $name . '</label> ';
}
?>
 </div>
</div>
<div class="form-group">
  <label class="col-sm-3 control-label">{{Limiter à l'équipement}}</label>
  <div class="col-sm-6">
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
</fieldset>
</form>
</div>

<div role="tabpanel" class="tab-pane" id="actiontab">
  <a class="btn btn-success btn-sm pull-right" id="bt_addAction" style="margin-top:5px;"><i class="fa fa-plus-circle"></i> {{Ajouter}}</a>
  <br/><br/>
  <form class="form-horizontal">
    <fieldset>

     <div id="div_action"></div>
   </fieldset>
 </form>
</div>
</div>

<?php include_file('desktop', 'interact', 'js');?>
