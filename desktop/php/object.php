<?php
if (!hasRight('objectview', true)) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
sendVarToJS('select_id', init('id', '-1'));
?>

<div style="position : fixed;height:100%;width:15px;top:50px;left:0px;z-index:998;background-color:#f6f6f6;" class="div_smallSideBar" id="bt_displayObject"><i class="fa fa-arrow-circle-o-right" style="color : #b6b6b6;"></i></div>

<div class="row row-overflow">
    <div class="col-md-2 col-sm-3" id="sd_objectList" style="z-index:999">
        <div class="bs-sidebar">
            <ul id="ul_object" class="nav nav-list bs-sidenav">
                <a id="bt_addObject" class="btn btn-default" style="width : 100%;margin-top : 5px;margin-bottom: 5px;"><i class="fa fa-plus-circle"></i> {{Ajouter objet}}</a>
                <li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
                <?php
$allObject = object::buildTree(null, false);
foreach ($allObject as $object) {
	$margin = 15 * $object->parentNumber();
	echo '<li class="cursor li_object bt_sortable" data-object_id="' . $object->getId() . '">';
	echo '<i class="fa fa-arrows-v pull-left cursor"></i>';
	echo '<a style="position:relative;left:' . $margin . 'px;">';
	echo $object->getHumanName(true, true);
	echo '</a>';
	echo '</li>';
}
?>
           </ul>
       </div>
   </div>

   <div class="col-lg-10 col-md-10 col-sm-9" id="div_resumeObjectList" style="border-left: solid 1px #EEE; padding-left: 25px;">
       <legend><i class="fa fa-picture-o"></i>  {{Mes objets}}</legend>
       <div class="objectListContainer">
           <div class="cursor" id="bt_addObject2" style="background-color : #ffffff; height : 140px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
             <center>
                <i class="fa fa-plus-circle" style="font-size : 6em;color:#94ca02;"></i>
            </center>
            <span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#94ca02"><center>Ajouter</center></span>
        </div>
        <?php
foreach ($allObject as $object) {
	echo '<div class="objectDisplayCard cursor" data-object_id="' . $object->getId() . '" style="background-color : #ffffff; height : 140px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >';
	echo "<center>";
	echo str_replace('></i>', ' style="font-size : 6em;color:#767676;"></i>', $object->getDisplay('icon', '<i class="fa fa-lemon-o"></i>'));
	echo "</center>";
	echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>' . $object->getName() . '</center></span>';
	echo '</div>';
}
?>
   </div>
</div>

<div class="col-md-10 col-sm-9 object" style="display: none;" id="div_conf">
    <form class="form-horizontal">
        <fieldset>
        <legend><i class="fa fa-arrow-circle-left cursor" id="bt_returnToThumbnailDisplay"></i> {{Général}}</legend>
            <div class="form-group">
                <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Nom de l'objet}}</label>
                <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                    <input class="form-control objectAttr" type="text" data-l1key="id" style="display : none;"/>
                    <input class="form-control objectAttr" type="text" data-l1key="name" placeholder="Nom de l'objet"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Père}}</label>
                <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                    <select class="form-control objectAttr" data-l1key="father_id">
                        <option value="">{{Aucun}}</option>
                        <?php
foreach ($allObject as $object) {
	echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
}
?>
                   </select>
               </div>
           </div>
           <div class="form-group">
            <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label"></label>
            <div class="col-sm-1">
                <input class="objectAttr bootstrapSwitch" type="checkbox" data-label-text="{{Visible}}" data-l1key="isVisible" checked/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Icône}}</label>
            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
                <div class="objectAttr" data-l1key="display" data-l2key="icon" style="font-size : 1.5em;"></div>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
                <a class="btn btn-default btn-sm" id="bt_chooseIcon"><i class="fa fa-flag"></i> {{Choisir}}</a>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Couleur du tag}}</label>
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                <input type="color" class="objectAttr form-control" data-l1key="display" data-l2key="tagColor" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Couleur du texte du tag}}</label>
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                <input type="color" class="objectAttr form-control" data-l1key="display" data-l2key="tagTextColor" />
            </div>
        </div>
    </fieldset>
</form>
<hr/>
<form class="form-horizontal">
    <fieldset>
        <div class="form-actions">
            <a class="btn btn-danger" id="bt_removeObject"><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>
            <a class="btn btn-success" id="bt_saveObject"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
        </div>

    </fieldset>
</form>
</div>
</div>

<?php include_file("desktop", "object", "js");?>