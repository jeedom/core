<?php
if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
$object = object::byId(init('object_id'));
if (!is_object($object)) {
    throw new Exception('Objet non trouvé : ' . init('object_id'));
}
sendVarToJS('objectInfo', utils::o2a($object));
?>
<div id='div_displayObjectConfigure'>
    <div class="row">
        <form class="form-horizontal">
            <fieldset>
                <div class="form-group">
                    <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{ID}}</label>
                    <div class="col-sm-4">
                        <span class="objectAttr label label-primary" data-l1key="id"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Nom}}</label>
                    <div class="col-sm-4">
                        <span class="objectAttr label label-primary" data-l1key="name"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Visible}}</label>
                    <div class="col-sm-4">
                        <span class="objectAttr label label-primary" data-l1key="isVisible"></span>
                    </div>
                </div>

            </fieldset>
        </form>
    </div>
</div>
<script>
    $('#div_displayObjectConfigure').setValues(objectInfo, '.objectAttr');
</script>

