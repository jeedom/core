<?php
if (!isConnect('admin')) {
  throw new Exception('{{401 - Accès non autorisé}}');
}
$rootPath = __DIR__ . '/../../data/customTemplates';
if(!file_exists($rootPath)){
  mkdir($rootPath);
}
if(!file_exists($rootPath.'/dashboard')){
  mkdir($rootPath.'/dashboard');
}
if(!file_exists($rootPath.'/mobile')){
  mkdir($rootPath.'/mobile');
}
global $JEEDOM_INTERNAL_CONFIG;
$widgets = array('action' => array(),'info' => array());
foreach ((widgets::all()) as $widget) {
  $widgets[$widget->getType()][] = $widget;
}

function jeedom_displayWidgetGroup($_type, $_widgets) {
  $c = count($_widgets[$_type]);
  $title = ucfirst($_type);
  if ($c > 0) {
    $thisDiv = '<div class="panel panel-default">';
    $thisDiv .= '<div class="panel-heading">';
    $thisDiv .= '<h3 class="panel-title">';
    $thisDiv .= '<a class="accordion-toggle" data-toggle="collapse" data-parent="" aria-expanded="false" href="#widget_'.$_type.'">'.$title.' - ';
    $thisDiv .= $c. ($c > 1 ? ' widgets' : ' widget').'</a>';
    $thisDiv .= '</h3>';
    $thisDiv .= '</div>';
    $thisDiv .= '<div id="widget_'.$_type.'" class="panel-collapse collapse">';
    $thisDiv .= '<div class="panel-body">';
    $thisDiv .= '<div class="widgetsListContainer">';
    foreach ($_widgets[$_type] as $widget) {
      $thisDiv .= '<div class="widgetsDisplayCard cursor" data-widgets_id="' . $widget->getId() . '">';
      if ($widget->getDisplay('icon') != '') {
        $thisDiv .= '<span>'.$widget->getDisplay('icon').'</span>';
      } else {
        $thisDiv .= '<span><i class="fas fa-image"></i></span>';
      }
      $thisDiv .= '<br/>';
      $thisDiv .= '<span class="name"><span class="label label-primary cursor" style="font-size:10px !important;padding: 2px 4px">' . $widget->getType() . '</span> | <span class="label label-info cursor" style="font-size:10px !important;padding: 2px 4px">'.$widget->getSubType() .'</span></span>';
      $thisDiv .= '<span class="name search">' . $widget->getName() . '</span><br/>';
      $thisDiv .= '<span class="hiddenAsCard displayTableRight">'.ucfirst($widget->getSubType()).' | '.ucfirst(str_replace('tmpl', '', $widget->getTemplate()));
      if ($widget->getReplace('#_time_widget_#', 0) == 1) $thisDiv .= ' (time)';
      $thisDiv .= '</span>';
      $thisDiv .= '</div>';
    }
    $thisDiv .= '</div>';
    $thisDiv .= '</div>';
    $thisDiv .= '</div>';
    $thisDiv .= '</div>';

    return $thisDiv;
  }
}
?>

<div class="row row-overflow">
  <div id="div_widgetsList" class="col-xs-12">
    <legend><i class="fas fa-cog"></i>  {{Gestion}}</legend>
    <div class="widgetsListContainer <?php echo (jeedom::getThemeConfig()['theme_displayAsTable'] == 1) ? ' containerAsTable' : ''; ?>">
      <div class="cursor logoPrimary" id="bt_addWidgets">
        <div class="center"><i class="fas fa-plus-circle"></i></div>
        <span class="txtColor">{{Ajouter}}</span>
      </div>
      <div class="cursor logoSecondary" id="bt_mainImportWidgets" style="cursor: default !important;">
        <div class="center" style="cursor: default !important;">
          <span class="btn-file"><i class="fas fa-file-import" style="margin-bottom: 20px;"></i>
            <input type="file" name="file">
          </span>
        </div>
        <span class="txtColor"><span style="cursor: default !important;">{{Importer}}</span></span>
      </div>
      <div class="cursor logoSecondary" id="bt_editCode">
        <div class="center"><i class="far fa-file-code"></i></div>
        <span class="txtColor">{{Code}}</span>
      </div>
      <div class="cursor logoSecondary" id="bt_replaceWidget">
        <div class="center"><i class="fab fa-replyd"></i></div>
        <span class="txtColor">{{Remplacement}}</span>
      </div>
    </div>
    <legend><i class="fas fa-image"></i> {{Mes widgets}} <sub class="itemsNumber"></sub></legend>
    <?php
    if (count($widgets['info']) == 0 && count($widgets['action']) == 0) {
      echo "<br/><br/><br/><div class='center'><span style='color:#767676;font-size:1.2em;font-weight: bold;'>Vous n'avez encore aucun widget. Cliquez sur ajouter pour commencer.</span></div>";
    } else {
      $div = '<div class="input-group" style="margin-bottom:5px;">';
      $div .= '<input class="form-control roundedLeft" placeholder="{{Rechercher | nom | :not(nom}}" id="in_searchWidgets"/>';
      $div .= '<div class="input-group-btn">';
      $div .= '<a id="bt_resetWidgetsSearch" class="btn" style="width:30px"><i class="fas fa-times"></i> </a>';
      $div .= '</div>';
      $div .= '<div class="input-group-btn">';
      $div .= '<a class="btn" id="bt_openAll"><i class="fas fa-folder-open"></i></a>';
      $div .= '</div>';
      $div .= '<div class="input-group-btn">';
      $div .= '<a class="btn" id="bt_closeAll"><i class="fas fa-folder"></i></a>';
      $div .= '<a class="btn roundedRight" id="bt_displayAsTable" data-card=".widgetsDisplayCard" data-container=".widgetsListContainer" data-state="0"><i class="fas fa-grip-lines"></i></a>';
      $div .= '</div>';
      $div .= '</div>';
      $div .= '<div class="panel-group" id="accordionWidgets">';

      $div .= jeedom_displayWidgetGroup('info', $widgets);
      $div .= jeedom_displayWidgetGroup('action', $widgets);

      echo $div.'</div>';
    }
    ?>
  </div>

  <div class="hasfloatingbar col-xs-12 widgets" style="display: none;" id="div_conf">
    <div class="floatingbar">
      <div class="input-group">
        <span class="input-group-btn">
          <a class="btn btn-default btn-sm roundedLeft" id="bt_applyToCmd"><i class="fas fa-arrow-alt-circle-down"></i> <span class="hidden-768">{{Appliquer à}}</span>
          </a><span class="btn btn-info btn-sm btn-file"><i class="fas fa-file-import"></i> <span class="hidden-768">{{Importer}}</span><input  id="bt_importWidgets" type="file" name="file" style="display:inline-block;">
          </span><a class="btn btn-info btn-sm" id="bt_exportWidgets"><i class="fas fa-file-export"></i> <span class="hidden-768">{{Exporter}}</span>
          </a><a class="btn btn-success btn-sm" id="bt_saveWidgets"><i class="fas fa-check-circle"></i> {{Sauvegarder}}
          </a><a class="btn btn-danger btn-sm roundedRight" id="bt_removeWidgets"><i class="fas fa-minus-circle"></i> {{Supprimer}}</a>
        </span>
      </div>
    </div>

    <ul class="nav nav-tabs" role="tablist">
      <li role="presentation"><a class="cursor" aria-controls="home" role="tab" id="bt_returnToThumbnailDisplay"><i class="fas fa-arrow-circle-left"></i></a></li>
      <li role="presentation" class="active"><a href="#widgetstab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer-alt"></i> {{Widgets}}</a></li>
    </ul>

    <div class="tab-content">
      <div role="tabpanel" class="tab-pane active" id="widgetstab">
        <br/>
        <form class="form-horizontal">
          <fieldset>
            <div class="row">
              <div class="col-md-6 col-sm-12">
                <legend><i class="fas fa-wrench"></i> {{Général}}</legend>
                <div class="form-group">
                  <label class="col-lg-4 col-xs-4 control-label">{{Nom du widget}}</label>
                  <div class="col-lg-4 col-xs-5">
                    <input class="form-control widgetsAttr" type="text" data-l1key="id" style="display : none;"/>
                    <input class="form-control widgetsAttr" type="text" data-l1key="name" placeholder="{{Nom du widget}}"/>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-lg-4 col-xs-4 control-label">{{Type}}</label>
                  <div class="col-lg-4 col-xs-5">
                    <select class="form-control widgetsAttr" data-l1key="type">
                      <?php
                      foreach ($JEEDOM_INTERNAL_CONFIG['cmd']['type'] as $key => $value) {
                        echo '<option value="'.$key.'"><a>'.$value['name'].'</option>';
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-lg-4 col-xs-4 control-label">{{Sous-Type}}</label>
                  <div class="col-lg-4 col-xs-5">
                    <?php
                    foreach ($JEEDOM_INTERNAL_CONFIG['cmd']['type'] as $key => $value) {
                      echo '<select class="form-control selectWidgetSubType" data-l1key="subtype" data-type="'.$key.'">';
                      foreach ($value['subtype'] as $skey => $svalue) {
                        echo '<option data-type="'.$key.'" value="'.$skey.'"><a>'.$svalue['name'].'</option>';
                      }
                      echo '</select>';
                    }
                    ?>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-lg-4 col-xs-4 control-label">{{Template}}</label>
                  <div class="col-lg-4 col-xs-5">
                    <?php
                    foreach ((widgets::listTemplate()) as $type => $values) {
                      foreach ($values as $subtype => $namelist) {
                        echo '<select class="form-control selectWidgetTemplate" data-l1key="template" data-type="'.$type.'" data-subtype="'.$subtype.'">';
                        foreach ($namelist as $name) {
                          echo '<option data-type="'.$type.'" data-subtype="'.$subtype.'" value="'.$name.'">'.ucfirst(str_replace('tmpl','',$name)).'</option>';
                        }
                        echo '</select>';
                      }
                    }
                    ?>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-lg-4 col-xs-4 control-label">{{Icône}}</label>
                  <div class="col-lg-2 col-xs-3">
                    <a class="btn btn-default btn-sm" id="bt_chooseIcon"><i class="fas fa-flag"></i> {{Choisir}}</a>
                  </div>
                  <div class="col-lg-2 col-xs-3">
                    <div class="widgetsAttr" data-l1key="display" data-l2key="icon" style="font-size : 1.5em;"></div>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-sm-12">
                <legend><i class="fas fa-search"></i> {{Prévisualisation}}</legend>
                <div id="div_widgetPreview"></div>
              </div>
            </div>
          </fieldset>
        </form>

        <form class="form-horizontal">
          <fieldset>
            <div class="col-sm-12">
              <legend class="type_replace"><i class="fas fa-random"></i> {{Remplacement}}</legend>
              <div id="div_templateReplace" class="type_replace"></div>

              <legend class="type_test"><i class="fas fa-stethoscope"></i> {{Test}}
                <a class="btn btn-xs pull-right" id="bt_widgetsAddTest"><i class="fas fa-plus-circle"></i> {{Ajouter}}</a>
              </legend>

              <div class="type_test">
                <div class="form-group">
                  <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label"></label>
                  <div class="col-sm-3 center">{{Expression}}</div>
                  <div class="col-sm-3 center">{{Résultat thème Light}}</div>
                  <div class="col-sm-3 center">{{Résultat thème Dark}}</div>
                </div>
              </div>

              <div id="div_templateTest" class="type_test"></div>
            </div>
          </fieldset>
        </form>

        <form class="form-horizontal">
          <fieldset>
            <div class="row">
              <div class="col-sm-12">
                <legend><i class="fas fa-link"></i> {{Commandes liées}}</legend>
                <div class="form-group">
                  <div class="col-xs-9" id="div_usedBy"></div>
                </div>
              </div>
            </div>
          </fieldset>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include_file("desktop", "widgets", "js");?>
