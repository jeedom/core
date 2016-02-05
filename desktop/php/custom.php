<?php
if (!hasRight('customview', true)) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
?>
<div class="alert alert-warning">{{Attention tout ce que vous écrivez ici est global et inclus sur toutes les pages, la moindre erreur peut rendre votre jeedom non fonctionel}}</div>
<div role="tabpanel">
    <ul class="nav nav-tabs" role="tablist">
        <li class="active"><a href="#desktop" aria-controls="home" role="tab" data-toggle="tab">Desktop</a></li>
        <li><a href="#mobile" aria-controls="profile" role="tab" data-toggle="tab">Mobile</a></li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="desktop">
            <div class="row row-overflow">
                <div class="col-xs-6">
                    <legend>Javascript
                        <a class="btn btn-success pull-right btn-xs saveCustom" data-version="desktop" data-type="js" style="margin-top: 5px;"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
                    </legend>
                    <textarea class='form-control' id='ta_jsDesktopContent' style='height: 500px;'><?php
if (file_exists(dirname(__FILE__) . '/../custom/custom.js')) {
	echo trim(file_get_contents(dirname(__FILE__) . '/../custom/custom.js'));
}
?></textarea>
                </div>
                <div class="col-xs-6">
                    <legend>CSS
                        <a class="btn btn-success pull-right btn-xs saveCustom" data-version="desktop" data-type="css" style="margin-top: 5px;"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
                    </legend>
                    <textarea class='form-control' id='ta_cssDesktopContent' style='height: 500px;'><?php
if (file_exists(dirname(__FILE__) . '/../custom/custom.css')) {
	echo trim(file_get_contents(dirname(__FILE__) . '/../custom/custom.css'));
}
?></textarea>
                </div>
            </div>

        </div>
        <div role="tabpanel" class="tab-pane" id="mobile">
            <div class="row row-overflow">
                <div class="col-xs-6">
                    <legend>Javascript
                        <a class="btn btn-success pull-right btn-xs saveCustom" data-version="mobile" data-type="js" style="margin-top: 5px;"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
                    </legend>
                    <textarea class='form-control' id='ta_jsMobileContent' style='height: 500px;'><?php
if (file_exists(dirname(__FILE__) . '/../../mobile/custom/custom.js')) {
	echo trim(file_get_contents(dirname(__FILE__) . '/../../mobile/custom/custom.js'));
}
?></textarea>
                </div>
                <div class="col-xs-6">
                    <legend>CSS
                        <a class="btn btn-success pull-right btn-xs saveCustom" data-version="mobile" data-type="css" style="margin-top: 5px;"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
                    </legend>
                    <textarea class='form-control' id='ta_cssMobileContent' style='height: 500px;'><?php
if (file_exists(dirname(__FILE__) . '/../../mobile/custom/custom.css')) {
	echo trim(file_get_contents(dirname(__FILE__) . '/../../mobile/custom/custom.css'));
}
?></textarea>
                </div>
            </div>
        </div>
    </div>

</div>

<?php include_file('desktop', 'custom', 'js');?>