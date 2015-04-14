<?php
if (!hasRight('pluginview', true)) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
global $JEEDOM_INTERNAL_CONFIG;
sendVarToJS('select_id', init('id', '-1'));
$plugins_list = plugin::listPlugin(false, true);
?>

<div style="position : fixed;height:100%;width:30px;top:90px;left:0px;z-index:9998" id="bt_displayPluginList"></div>

<div class="row row-overflow">
    <div class="col-md-3 col-sm-4" id="sd_pluginList" style="z-index:9999">
        <div class="bs-sidebar">
            <ul id="ul_plugin" class="nav nav-list bs-sidenav">
                <center>
                    <a class="btn btn-default btn-sm tooltips" id="bt_displayMarket" style="display: inline-block;"><i class="fa fa-shopping-cart"></i> {{Market}}</a>
                    <input class="expertModeVisible" id="bt_uploadPlugin" type="file" name="file" data-url="core/ajax/plugin.ajax.php?action=pluginupload" style="display : inline-block;">
                </center>
                <li class="filter" style="margin-bottom: 5px;margin-top: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
                <?php
foreach ($plugins_list as $category_name => $category) {
	$icon = '';
	if (isset($JEEDOM_INTERNAL_CONFIG['plugin']['category'][$category_name]) && isset($JEEDOM_INTERNAL_CONFIG['plugin']['category'][$category_name]['icon'])) {
		$icon = $JEEDOM_INTERNAL_CONFIG['plugin']['category'][$category_name]['icon'];
	}
	$name = $category_name;
	if (isset($JEEDOM_INTERNAL_CONFIG['plugin']['category'][$category_name]) && isset($JEEDOM_INTERNAL_CONFIG['plugin']['category'][$category_name]['name'])) {
		$name = $JEEDOM_INTERNAL_CONFIG['plugin']['category'][$category_name]['name'];
	}
	echo '<li><i class="fa ' . $icon . '"></i> ' . $name . '</li>';

	foreach ($category as $plugin) {
		echo '<li class="cursor li_plugin" data-pluginPath="' . $plugin->getFilepath() . '" data-plugin_id="' . $plugin->getId() . '"><a>';
		echo '<i class="' . $plugin->getIcon() . '"></i> ' . $plugin->getName();
		if ($plugin->isActive() == 1) {
			echo '<span class="pull-right"><i class="fa fa-check"></i></span> ';
		} else {
			echo '<span class="pull-right"><i class="fa fa-times"></i></span> ';
		}
		echo '</a></li>';
	}
}
?>
         </ul>
     </div>
 </div>

 <div class="col-md-9 col-sm-8" id="div_resumePluginList" style="border-left: solid 1px #EEE; padding-left: 25px;">
 <legend>{{Mes plugins}}</legend>
    <div class="pluginListContainer">
    <div class="cursor" id="bt_displayMarket2" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
       <center>
        <i class="fa fa-shopping-cart" style="font-size : 4em;color:#94ca02;"></i>
    </center>
    <span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#94ca02"><center>{{Accéder au Market}}</center></span>
</div>

<?php
foreach ($plugins_list as $category_name => $category) {
	foreach ($category as $plugin) {
		echo '<div class="pluginDisplayCard cursor" data-pluginPath="' . $plugin->getFilepath() . '" data-plugin_id="' . $plugin->getId() . '" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >';
		echo "<center>";
		echo '<i class="' . $plugin->getIcon() . '" style="font-size : 4em;color:#767676;"></i>';
		echo "</center>";
		echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>' . $plugin->getName() . '</center></span>';
		echo '</div>';
	}
}
?>
</div>


</div>
<div class="col-md-9 col-sm-8" id="div_confPlugin" style="border-left: solid 1px #EEE; padding-left: 25px;display: none;">

    <div>
        <?php
if (config::byKey('market::showPromotion') == 1) {
	echo market::getPromo();
}
?>
   </div>
   <legend><i class="fa fa-arrow-circle-left cursor" id="bt_returnToThumbnailDisplay"></i>
    <span id="span_plugin_name" ></span> (<span id="span_plugin_id"></span>) - <span id="span_plugin_install_version"></span>
</legend>
<div>
    <center>
        <span style='font-weight: bold;'>{{N'oubliez pas d'activer le plugin pour pouvoir vous servir de celui-ci}}</span><br/>
        <span id="span_plugin_toggleState"></span><br/><br/>
        <span id="span_plugin_market"></span>
        <span id="span_plugin_delete"></span>
        <span id="span_plugin_doc"></span>
    </center>
    <br/>
</div>

<div class="alert alert-info">
    <h5 style="display: inline-block;font-weight: bold;">{{Installation}} : </h5> <span id="span_plugin_installation"></span>
</div>
<div class="alert alert-warning">
    <legend>{{Configuration}}</legend>
    <div id="div_plugin_configuration"></div>

    <div class="form-actions">
        <a class="btn btn-success" id="bt_savePluginConfig"><i class="fa fa-check-circle icon-white" style="position:relative;left:-5px;top:1px"></i>{{Sauvegarder}}</a>
    </div>
</div>
<div class="alert alert-success">
    <h5 style="display: inline-block;font-weight: bold;">{{Description}} : </h5> <span id="span_plugin_description"></span><br/>
    <h5 style="display: inline-block;font-weight: bold;">{{Version plugin}} : </h5> <span id="span_plugin_version"></span> -
    <h5 style="display: inline-block;font-weight: bold;">{{Version Jeedom requise}} : </h5> <span id="span_plugin_require"></span><br/>
    <h5 style="display: inline-block;font-weight: bold;">{{Auteur}} : </h5> <span id="span_plugin_author"></span> -
    <h5 style="display: inline-block;font-weight: bold;">{{Licence}} : </h5> <span id="span_plugin_licence"></span>
</div>

</div>
</div>

<?php include_file("desktop", "plugin", "js");?>
