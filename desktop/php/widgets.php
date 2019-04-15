<?php
if (!isConnect('admin')) {
  throw new Exception('{{401 - Accès non autorisé}}');
}
global $JEEDOM_INTERNAL_CONFIG;
?>
<div class="row row-overflow">
  <div id="div_widgetsList" class="col-xs-12">
    <legend><i class="fas fa-cog"></i>  {{Gestion}}</legend>
    <div class="widgetsListContainer">
      <div class="cursor success" id="bt_addWidgets">
        <center>
          <i class="fas fa-plus-circle"></i>
        </center>
        <span><center>{{Ajouter}}</center></span>
      </div>
    </div>
    
    <legend><i class="fas fa-image"></i> {{Mes widgets}}</legend>
    <div class="input-group" style="margin-bottom:5px;">
      <input class="form-control roundedLeft" placeholder="{{Rechercher}}" id="in_searchWidgets"/>
      <div class="input-group-btn">
        <a id="bt_resetWidgetsSearch" class="btn roundedRight" style="width:30px"><i class="fas fa-times"></i> </a>
      </div>
    </div>
    <div class="widgetsListContainer">
      <?php
      foreach (widgets::all() as $widgets) {
        echo '<div class="widgetsDisplayCard cursor" data-widgets_id="' . $widgets->getId() . '">';
        if($widgets->getDisplay('icon') != ''){
          echo '<span>'.$widgets->getDisplay('icon').'</span>';
        }else{
          echo '<span><i class="fas fa-image"></i></span>';
        }
        echo '<br/>';
        echo '<span class="name"><span class="label label-primary" style="font-size:10px !important;padding: 2px 4px">' . $widgets->getType() . '</span> / <span class="label label-info" style="font-size:10px !important;padding: 2px 4px">'.$widgets->getSubType() .'</span></span>'; 
        echo '<span class="name">' . $widgets->getName() . '</span><br/>';
        echo '</div>';
      }
      ?>
    </div>
  </div>
  
  <div class="col-xs-12 widgets" style="display: none;" id="div_conf">
    <div class="input-group pull-right" style="display:inline-flex">
      <span class="input-group-btn">
        <a class="btn btn-success btn-sm roundedLeft" id="bt_saveWidgets"><i class="far fa-check-circle"></i> {{Sauvegarder}}</a><a class="btn btn-danger btn-sm roundedRight" id="bt_removeWidgets"><i class="fas fa-minus-circle"></i> {{Supprimer}}</a>
      </span>
    </div>
    <ul class="nav nav-tabs" role="tablist">
      <li role="presentation"><a class="cursor" aria-controls="home" role="tab" id="bt_returnToThumbnailDisplay"><i class="fas fa-arrow-circle-left"></i></a></li>
      <li role="presentation" class="active"><a href="#widgetstab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer-alt"></i> {{Widgets}}</a></li>
    </ul>
    <div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
      <div role="tabpanel" class="tab-pane active" id="widgetstab">
        <br/>
        <form class="form-horizontal">
          <fieldset>
            <div class="row">
              <div class="col-sm-6">
                <legend>{{Général}}</legend>
                <div class="form-group">
                  <label class="col-lg-4 col-xs-6 control-label">{{Nom du widgets}}</label>
                  <div class="col-lg-6 col-xs-6">
                    <input class="form-control widgetsAttr" type="text" data-l1key="id" style="display : none;"/>
                    <input class="form-control widgetsAttr" type="text" data-l1key="name" placeholder="Nom du widget"/>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-lg-4 col-xs-6 control-label">{{Type}}</label>
                  <div class="col-lg-6 col-xs-6">
                    <select class="form-control widgetsAttr" data-l1key="type">
                      <?php
                      foreach ($JEEDOM_INTERNAL_CONFIG['cmd']['type'] as $key => $value) {
                        echo '<option value="'.$key.'"><a>{{'.$value['name'].'}}</option>';
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-lg-4 col-xs-6 control-label">{{Sous-Type}}</label>
                  <div class="col-lg-6 col-xs-6">
                    <select class="form-control widgetsAttr" data-l1key="subtype">
                      <option value="" data-default="1"><a></option>
                        <?php
                        foreach ($JEEDOM_INTERNAL_CONFIG['cmd']['type'] as $key => $value) {
                          foreach ($value['subtype'] as $skey => $svalue) {
                            echo '<option data-type="'.$key.'" value="'.$skey.'"><a>{{'.$svalue['name'].'}}</option>';
                          }
                        }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-lg-4 col-xs-6 control-label">{{Template}}</label>
                    <div class="col-lg-6 col-xs-6">
                      <select class="form-control widgetsAttr" data-l1key="template">
                        <option value="" data-default="1"><a></option>
                          <?php
                          foreach (widgets::listTemplate() as $type => $values) {
                            foreach ($values as $subtype => $namelist) {
                              foreach ($namelist as $name) {
                                echo '<option data-type="'.$type.'" data-subtype="'.$subtype.'" value="'.$name.'">'.ucfirst(str_replace('tmpl','',$name)).'</option>';
                              }
                            }
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-lg-4 col-xs-6 control-label">{{Icône}}</label>
                      <div class="col-lg-2 col-xs-3">
                        <a class="btn btn-default btn-sm" id="bt_chooseIcon"><i class="fas fa-flag"></i> {{Choisir}}</a>
                      </div>
                      <div class="col-lg-2 col-xs-3">
                        <div class="widgetsAttr" data-l1key="display" data-l2key="icon" style="font-size : 1.5em;"></div>
                      </div>
                    </div>
                  </div>
                  <div class="col-xs-6">
                    <legend>{{Informations}}</legend>
                    <div class="form-group">
                      <label class="col-xs-3 control-label">{{Utilisé par}}</label>
                      <div class="col-xs-9" id="div_usedBy"></div>
                    </div>
                  </div>
                </div>
              </fieldset>
            </form>
            <form class="form-horizontal">
              <fieldset>
                <legend class="type_replace">{{Remplacement}}</legend>
                <div id="div_templateReplace" class="type_replace"></div>
                <legend class="type_test">{{Test}}
                  <a class="btn btn-xs pull-right" id="bt_widgetsAddTest"><i class="fas fa-plus-circle"></i> {{Ajouter}}</a>
                </legend>
                <div id="div_templateTest" class="type_test"></div>
              </fieldset>
            </form>
          </div>
        </div>
      </div>
      
      <?php include_file("desktop", "widgets", "js");?>
      