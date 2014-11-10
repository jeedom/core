<?php
if (!hasRight('objectview',true)) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
sendVarToJS('select_id', init('id', '-1'));
?>

<div class="row row-overflow">
    <div class="col-lg-2">
        <div class="bs-sidebar">
            <ul id="ul_object" class="nav nav-list bs-sidenav">
                <a id="bt_addObject" class="btn btn-default" style="width : 100%;margin-top : 5px;margin-bottom: 5px;"><i class="fa fa-plus-circle"></i> {{Ajouter objet}}</a>
                <li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
                <?php
                $allObject = object::buildTree(null, false);
                foreach ($allObject as $object) {
                    $margin = 15 * $object->parentNumber();
                    echo '<li class="cursor li_object bt_sortable" data-object_id="' . $object->getId() . '">'
                    . '<i class="fa fa-arrows-v pull-left cursor"></i>'
                    . '<span class="pull-left">' . $object->getDisplay('icon') . '</span>'
                    . '<a style="position:relative;left:' . $margin . 'px;">' . $object->getName() . '</a>'
                    . '</li>';
                }
                ?>
            </ul>
        </div>
    </div>
    <div class="col-lg-10 object" style="display: none;" id="div_conf">
        <form class="form-horizontal">
            <fieldset>
                <legend>{{Général}}</legend>
                <div class="form-group">
                    <label class="col-lg-2 control-label">{{Nom de l'objet}}</label>
                    <div class="col-lg-3">
                        <input class="form-control objectAttr" type="text" data-l1key="id" style="display : none;"/>
                        <input class="form-control objectAttr" type="text" data-l1key="name" placeholder="Nom de l'objet"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">{{Père}}</label>
                    <div class="col-lg-3">
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
                    <label class="col-lg-2 control-label">{{Visible}}</label>
                    <div class="col-lg-1">
                        <input class="objectAttr" type="checkbox"  data-l1key="isVisible" checked/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">{{Icône}}</label>
                    <div class="col-lg-2">
                        <div class="objectAttr" data-l1key="display" data-l2key="icon" ></div>
                    </div>
                    <div class="col-lg-2">
                        <a class="btn btn-default btn-sm" id="bt_chooseIcon"><i class="fa fa-flag"></i> {{Choisir une icône}}</a>
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

<?php include_file("desktop", "object", "js"); ?>