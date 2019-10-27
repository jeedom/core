<?php
if (!isConnect('admin')) {
  throw new Exception('{{401 - Accès non autorisé}}');
}
global $JEEDOM_INTERNAL_CONFIG;
sendVarToJS('sel_plugin_id', init('id', '-1'));
$plugins_list = plugin::listPlugin(false, true);
?>
<div id='div_alertPluginConfiguration'></div>

<div class="row row-overflow">
  <div class="col-xs-12" id="div_resumePluginList">
    <legend><i class="fas fa-cog"></i> {{Gestion}}</legend>
    <div class="pluginListContainer">
      <div class="cursor success" id="bt_addPluginFromOtherSource">
        <center><i class="fas fa-plus"></i></center>
        <span class="txtColor"><center>{{Plugins}}</center></span>
      </div>
      <?php
      $div = '';
      foreach (update::listRepo() as $key => $value) {
        if (!$value['enable']) {
          continue;
        }
        if (!isset($value['scope']['hasStore']) || !$value['scope']['hasStore']) {
          continue;
        }
        $div .= '<div class="cursor displayStore success" data-repo="' . $key . '">';
        $div .= '<center><i class="fas fa-shopping-cart"></i></center>';
        $div .= '<span class="txtColor"><center>' . $value['name'] . '</center></span>';
        $div .= '</div>';
        if (!isset($value['scope']['pullInstall']) || !$value['scope']['pullInstall']) {
          continue;
        }
        $div .= '<div class="cursor pullInstall success" data-repo="' . $key . '">';
        $div .= '<center><i class="fas fa-sync"></i></center>';
        $div .= '<span class="txtColor"><center>{{Synchroniser}} ' . $value['name'] . '</center></span>';
        $div .= '</div>';
      }
      echo $div;
      ?>
    </div>
    <legend><i class="fas fa-list-alt"></i> {{Mes plugins}}</legend>
    <div class="input-group" style="margin-bottom:5px;">
      <input class="form-control roundedLeft" placeholder="{{Rechercher}}" id="in_searchPlugin"/>
      <div class="input-group-btn">
        <a id="bt_resetPluginSearch" class="btn roundedRight" style="width:30px"><i class="fas fa-times"></i> </a>
      </div>
    </div>
    <div class="panel">
      <div class="panel-body">
        <div class="pluginListContainer">
          <?php
          foreach (plugin::listPlugin() as $plugin) {
            $opacity = ($plugin->isActive()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
            $div = '<div class="pluginDisplayCard cursor" data-pluginPath="' . $plugin->getFilepath() . '" data-plugin_id="' . $plugin->getId() . '" style="'.$opacity.'">';
            $div .= '<center>';
            $div .= '<img class="img-responsive" src="' . $plugin->getPathImgIcon() . '" />';
            $div .= '</center>';
            $lbl_version = false;
            $update = $plugin->getUpdate();
            if (is_object($update)) {
              $version = $update->getConfiguration('version');
              if ($version && $version != 'stable') $lbl_version = true;
            }
            if ($lbl_version) {
              $div .= '<span class="name"><sub style="font-size:22px" class="warning">&#8226</sub>' . $plugin->getName() . '</span>';
            } else {
              $div .= '<span class="name">' . $plugin->getName() . '</span>';
            }
            $div .= '</div>';
            echo $div;
          }
          ?>
        </div>
      </div>
    </div>
    
  </div>
  <div class="col-xs-12" id="div_confPlugin" style="display:none;">
    <legend>
      <i class="fas fa-arrow-circle-left cursor" id="bt_returnToThumbnailDisplay"></i>
      <span id="span_plugin_name"></span> (<span id="span_plugin_id"></span>) - <span id="span_plugin_install_version"></span>
      <div class="input-group pull-right" style="display:inline-flex">
        <span class="input-group-btn" id="span_right_button"></span>
      </div>
    </legend>
    
    <div class="row">
      <div class="col-md-6 col-sm-12">
        <div class="panel panel-default" id="div_state">
          <div class="panel-heading"><h3 class="panel-title"><i class="fas fa-circle-notch"></i> {{Etat}}</h3></div>
          <div class="panel-body">
            <div id="div_plugin_toggleState"></div>
            <form class="form-horizontal">
              <fieldset>
                <div class="form-group">
                  <label class="col-sm-2 control-label">{{Version}}</label>
                  <div class="col-sm-4">
                    <span id="span_plugin_install_date"></span>
                  </div>
                  <label class="col-sm-2 control-label">{{Version minimum Jeedom}}</label>
                  <div class="col-sm-4">
                    <span id="span_plugin_require"></span>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">{{License}}</label>
                  <div class="col-sm-4">
                    <span id="span_plugin_license"></span>
                  </div>
                </div>
              </fieldset>
            </form>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-sm-12">
        <div class="panel panel-primary" id="div_configLog">
          <div class="panel-heading">
            <h3 class="panel-title"><i class="far fa-file"></i> {{Logs et surveillance}}
              <a class="btn btn-success btn-xs pull-right" id="bt_savePluginLogConfig"><i class="far fa-check-circle icon-white"></i> {{Sauvegarder}}</a>
            </h3>
          </div>
          <div class="panel-body">
            <form class="form-horizontal">
              <fieldset>
                <div id="div_plugin_log"></div>
              </fieldset>
            </form>
            <div class="form-actions"></div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="row">
      <div class="col-md-6 col-sm-12">
        <div class="panel panel-success">
          <div class="panel-heading"><h3 class="panel-title"><i class="fas fa-certificate"></i> {{Dépendances}}</h3></div>
          <div class="panel-body">
            <div id="div_plugin_dependancy"></div>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-sm-12">
        <div class="panel panel-success">
          <div class="panel-heading"><h3 class="panel-title"><i class="fas fa-university"></i> {{Démon}}</h3></div>
          <div class="panel-body">
            <div id="div_plugin_deamon"></div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="panel panel-primary">
      <div class="panel-heading"><h3 class="panel-title"><i class="fas fa-map"></i> {{Installation}}</h3></div>
      <div class="panel-body">
        <span id="span_plugin_installation"></span>
      </div>
    </div>
    
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fas fa-cogs"></i> {{Configuration}}
          <a class="btn btn-success btn-xs pull-right" id="bt_savePluginConfig"><i class="far fa-check-circle icon-white"></i> {{Sauvegarder}}</a>
        </h3>
      </div>
      <div class="panel-body">
        <div id="div_plugin_configuration"></div>
        <div class="form-actions"></div>
      </div>
    </div>
    
    <div class="row">
      <div class="col-md-6 col-sm-12">
        <div class="panel panel-primary" id="div_functionalityPanel">
          <div class="panel-heading">
            <h3 class="panel-title"><i class="fas fa-satellite"></i> {{Fonctionnalités}}
              <a class="btn btn-success btn-xs pull-right" id="bt_savePluginFunctionalityConfig"><i class="far fa-check-circle icon-white"></i> {{Sauvegarder}}</a>
            </h3>
          </div>
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
          <div class="panel-heading">
            <h3 class="panel-title"><i class="fas fa-chalkboard"></i> {{Panel}}
              <a class="btn btn-success btn-xs pull-right" id="bt_savePluginPanelConfig"><i class="far fa-check-circle icon-white"></i> {{Sauvegarder}}</a>
            </h3>
          </div>
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
