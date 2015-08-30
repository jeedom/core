<?php
if (!hasRight('viewedit')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
?>

<style>
    .viewZone{
        padding: 9.5px;
        margin: 0 0px 5px;
        font-size: 13px;
        line-height: 20px;
        background-color: #f5f5f5;
        border: 1px solid #ccc;
        border: 1px solid rgba(0,0,0,0.15);
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
    }
</style>

<div class="row row-overflow">
    <div class="col-lg-2 col-md-3 col-sm-4">
        <div class="bs-sidebar">
            <ul id="ul_view" class="nav nav-list bs-sidenav">
                <a id="bt_addView" class="btn btn-default" style="width : 100%;margin-top : 5px;margin-bottom: 5px;"><i class="fa fa-plus-circle"></i> {{Créer une vue}}</a>
                <li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
                <?php
foreach (view::all() as $view) {
	echo '<li class="cursor li_view" data-view_id="' . $view->getId() . '"><a>' . $view->getName() . '</a></li>';
}
?>
           </ul>
       </div>
   </div>

   <div class="col-lg-10 col-md-9 col-sm-8" style="display: none;" id="div_view">
    <legend style="height: 35px;">
        <a class="btn btn-default btn-xs" id="bt_editView"><i class="fa fa-pencil"></i> {{Renommer}}</a>
        <a class="btn btn-default btn-xs" id="bt_chooseIcon"><i class="fa fa-flag"></i> {{Icone}}</a>
        <span class="viewAttr cursor" data-l1key="display" data-l2key="icon"></span>
        <a class="btn btn-danger btn-xs pull-right" id="bt_removeView"><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>
        <a class="btn btn-success btn-xs pull-right" id="bt_saveView"><i class="fa fa-check-circle"></i> {{Enregistrer}}</a>
        <a class="btn btn-primary btn-xs pull-right" id="bt_viewResult"><i class="fa fa fa-eye"></i> {{Voir le résultat}}</a>
        <a class="btn btn-default btn-xs pull-right" id="bt_addviewZone"><i class="fa fa-plus-circle"></i> {{Ajouter une zone}}</a>
    </legend>

    <div id="div_viewZones" style="margin-top: 10px;"></div>
</div>

</div>

<div class="modal fade" id="md_addEditviewZone">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">{{Ajouter/Editer viewZone}}</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger div_alert" style="display: none;" id="div_addEditviewZoneError"></div>
                <input id="in_addEditviewZoneEmplacement"  style="display : none;" />
                <form class="form-horizontal" onsubmit="return false;">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">{{Nom}}</label>
                        <div class="col-sm-5">
                            <input id="in_addEditviewZoneName" class="form-control" placeholder="{{Nom}}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">{{Type}}</label>
                        <div class="col-sm-5">
                            <select class="form-control" id="sel_addEditviewZoneType">
                                <option value="widget">{{Widget}}</option>
                                <option value="graph">{{Graphique}}</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <a class="btn btn-danger" data-dismiss="modal">{{Annuler}}</a>
                <a class="btn btn-success" id="bt_addEditviewZoneSave"><i class="fa fa-save"></i> {{Enregistrer}}</a>
            </div>
        </div>
    </div>
</div>

<?php include_file('desktop', 'view_edit', 'js');?>