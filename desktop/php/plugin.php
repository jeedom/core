<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
global $JEEDOM_INTERNAL_CONFIG;
sendVarToJS('sel_plugin_id', init('id', '-1'));
$plugins_list = plugin::listPlugin(false, true);
?>
<div id='div_alertPluginConfiguration'></div>
<div style="position : fixed;height:100%;width:15px;top:50px;left:0px;z-index:998;background-color:#f6f6f6;" class="div_smallSideBar" id="bt_displayPluginList"><i class="fa fa-arrow-circle-o-right" style="color : #b6b6b6;"></i></div>

<div class="row row-overflow">
  <div class="col-md-3 col-sm-4" id="sd_pluginList" style="z-index:999;display:none">
    <div class="bs-sidebar">
      <ul id="ul_plugin" class="nav nav-list bs-sidenav">
       <a class="btn btn-default" id="bt_addPluginFromOtherSource" style="width:100%"><i class="fa fa-plus"></i> {{Ajout depuis une autre source}}</a>
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
		$opacity = ($plugin->isActive()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
		echo '<li class="cursor li_plugin" data-pluginPath="' . $plugin->getFilepath() . '" data-plugin_id="' . $plugin->getId() . '" style="' . $opacity . '"><a>';
		echo '<img class="img-responsive" style="width : 20px;display:inline-block;" src="' . $plugin->getPathImgIcon() . '" /> ';
		echo $plugin->getName();
		echo '</a></li>';
	}
}
?>
    </ul>
  </div>
</div>

<div class="col-md-9 col-sm-8" id="div_resumePluginList" style="border-left: solid 1px #EEE; padding-left: 25px;">
 <legend><i class="fa fa-list-alt"></i>  {{Mes plugins}}</legend>
 <div class="pluginListContainer">
   <?php
foreach (update::listRepo() as $key => $value) {
	if (!$value['enable']) {
		continue;
	}
	if (!isset($value['scope']['hasStore']) || !$value['scope']['hasStore']) {
		continue;
	}
	echo '<div class="cursor displayStore" data-repo="' . $key . '" style="background-color : #ffffff; height : 140px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
    <center>
      <i class="fa fa-shopping-cart" style="font-size : 6em;color:#94ca02;margin-top:20px;"></i>
    </center>
    <span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#94ca02"><center>' . $value['name'] . '</center></span>
  </div>';
}
foreach (plugin::listPlugin() as $plugin) {
	$opacity = ($plugin->isActive()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
	echo '<div class="pluginDisplayCard cursor" data-pluginPath="' . $plugin->getFilepath() . '" data-plugin_id="' . $plugin->getId() . '" style="background-color : #ffffff; height : 140px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;' . $opacity . '" >';
	echo "<center>";
	echo '<img class="img-responsive" style="width : 120px;" src="' . $plugin->getPathImgIcon() . '" />';
	echo "</center>";
	echo '</div>';
}
?>
</div>
</div>
<div class="col-md-9 col-sm-8" id="div_confPlugin" style="border-left: solid 1px #EEE; padding-left: 25px;display: none;">
 <legend>
   <i class="fa fa-arrow-circle-left cursor" id="bt_returnToThumbnailDisplay"></i>
   <span id="span_plugin_name" ></span> (<span id="span_plugin_id"></span>) - <span id="span_plugin_install_version"></span>
   <span id="span_plugin_market" class="pull-right"></span>
   <span id="span_plugin_delete" class="pull-right"></span>
   <span id="span_plugin_doc" class="pull-right"></span>
 </legend>

 <div class="row">
  <div class="col-md-6 col-sm-12">
    <div class="panel panel-default">
     <div class="panel-heading"><h3 class="panel-title"><i class="fa fa-circle-o-notch"></i> {{Etat}}</h3></div>
     <div class="panel-body">
      <div id="div_plugin_toggleState"></div>
      <form class="form-horizontal">
        <fieldset>
          <div class="form-group">
            <label class="col-sm-2 control-label">{{Version}}</label>
            <div class="col-sm-4">
              <span id="span_plugin_install_date" style="position:relative;top:9px;"></span>
            </div>
            <label class="col-sm-2 control-label">{{Version Jeedom}}</label>
            <div class="col-sm-4">
              <span id="span_plugin_require" style="position:relative;top:9px;"></span>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">{{Auteur}}</label>
            <div class="col-sm-4">
              <span id="span_plugin_author" style="position:relative;top:9px;"></span>
            </div>
            <label class="col-sm-2 control-label">{{Licence}}</label>
            <div class="col-sm-4">
              <span id="span_plugin_licence" style="position:relative;top:9px;"></span>
            </div>
          </div>
        </fieldset>
      </form>
    </div>
  </div>
</div>
<div class="col-md-6 col-sm-12">
  <div class="panel panel-primary" id="div_configLog">
    <div class="panel-heading"><h3 class="panel-title"><i class="fa fa-file-o"></i> {{Log}}
      <a class="btn btn-success btn-xs pull-right" id="bt_savePluginLogConfig"><i class="fa fa-check-circle icon-white"></i> {{Sauvegarder}}</a>
    </h3></div>
    <div class="panel-body">
      <form class="form-horizontal">
        <fieldset>
          <div id="div_plugin_log"></div>
        </fieldset>
      </form>
      <div class="form-actions">

      </div>
    </div>
  </div>
</div>
</div>

<div class="row">
 <div class="col-md-6 col-sm-12">
  <div class="panel panel-success">
    <div class="panel-heading"><h3 class="panel-title"><i class="fa fa-certificate"></i> {{Dépendances}}</h3></div>
    <div class="panel-body">
     <div id="div_plugin_dependancy"></div>
   </div>
 </div>
</div>
<div class="col-md-6 col-sm-12">
  <div class="panel panel-success">
    <div class="panel-heading"><h3 class="panel-title"><i class="fa fa-university"></i> {{Démon}}</h3></div>
    <div class="panel-body">
     <div id="div_plugin_deamon"></div>
   </div>
 </div>
</div>
</div>

<div class="panel panel-primary">
  <div class="panel-heading"><h3 class="panel-title"><i class="fa fa-map"></i> {{Installation}}</h3></div>
  <div class="panel-body">
   <span id="span_plugin_installation"></span>
 </div>
</div>

<div class="panel panel-primary">
  <div class="panel-heading"><h3 class="panel-title"><i class="fa fa-cogs"></i> {{Configuration}}
    <a class="btn btn-success btn-xs pull-right" id="bt_savePluginConfig"><i class="fa fa-check-circle icon-white"></i> {{Sauvegarder}}</a>
  </h3></div>
  <div class="panel-body">
    <div id="div_plugin_configuration"></div>

    <div class="form-actions">

    </div>
  </div>
</div>

<div class="row">
<div class="col-md-6 col-sm-12">
  <div class="panel panel-primary" id="div_functionalityPanel">
    <div class="panel-heading"><h3 class="panel-title"><i class="fa fa-picture-o"></i> {{Fonctionnalités}}
      <a class="btn btn-success btn-xs pull-right" id="bt_savePluginFunctionalityConfig"><i class="fa fa-check-circle icon-white"></i> {{Sauvegarder}}</a>
    </h3></div>
    <div class="panel-body">
      <form class="form-horizontal">
        <fieldset>
         <div id="div_plugin_functionality"></div>
       </fieldset>
     </form>
   </div>
 </div>
</div>
 <div class="col-md-6 col-sm-12">
  <div class="panel panel-primary" id="div_configPanel">
    <div class="panel-heading"><h3 class="panel-title"><i class="fa fa-picture-o"></i> {{Panel}}
      <a class="btn btn-success btn-xs pull-right" id="bt_savePluginPanelConfig"><i class="fa fa-check-circle icon-white"></i> {{Sauvegarder}}</a>
    </h3></div>
    <div class="panel-body">
      <form class="form-horizontal">
        <fieldset>
         <div id="div_plugin_panel"></div>
       </fieldset>
     </form>
   </div>
 </div>
</div>
</div>

</div>
</div>

<?php include_file("desktop", "plugin", "js");?>
