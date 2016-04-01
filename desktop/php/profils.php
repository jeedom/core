<?php
if (!isConnect()) {
	throw new Exception('{{Error 401 Unauthorized');
}

$notifyTheme = array(
	'info' => '{{Bleu}}',
	'error' => '{{Rouge}}',
	'success' => '{{Vert}}',
	'warning' => '{{Jaune}}',
);

$homePage = array(
	'core::dashboard' => '{{Dashboard}}',
	'core::view' => '{{Vue}}',
	'core::plan' => '{{Design}}',
);
foreach (plugin::listPlugin() as $pluginList) {
	if ($pluginList->isActive() == 1 && $pluginList->getDisplay() != '') {
		$homePage[$pluginList->getId() . '::' . $pluginList->getDisplay()] = $pluginList->getName();
	}
}
?>
<legend><i class="fa fa-user"></i>  {{Profil}}</legend>

<div class="panel-group" id="accordionConfiguration">
  <input style="display: none;" class="userAttr form-control" data-l1key="id" />
  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">
        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionConfiguration" href="#config_themes">
          <i class="fa fa-tint"></i>  {{Thèmes}}
        </a>
      </h3>
    </div>
    <div id="config_themes" class="panel-collapse collapse in">
      <div class="panel-body">
      <div class="pull-right img-responsive" id="div_imgThemeDesktop" style="height: 450px;"></div>
        <form class="form-horizontal">
          <fieldset>
            <div class="form-group">
              <label class="col-sm-3 control-label">{{Desktop}}</label>
              <div class="col-sm-2">
                <select class="userAttr form-control" data-l1key="options" data-l2key="bootstrap_theme">
                  <option value="">Défaut</option>
                  <?php

foreach (ls(dirname(__FILE__) . '/../../core/themes') as $dir) {
	if (is_dir(dirname(__FILE__) . '/../../core/themes/' . $dir . '/desktop')) {
		echo '<option value="' . trim($dir, '/') . '">' . ucfirst(trim($dir, '/')) . '</option>';
	}
}
?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">{{Mobile couleur}}</label>
              <div class="col-sm-2">
                <select class="userAttr form-control" data-l1key="options" data-l2key="mobile_theme_color">
                  <option value="amber">Ambre</option>
                  <option value="blue">Bleu</option>
                  <option value="blue-grey">Bleu-gris</option>
                  <option value="brown">Marron</option>
                  <option value="cyan">Cyan</option>
                  <option value="deep-orange">Orange foncé</option>
                  <option value="deep-purple">Violet foncé</option>
                  <option value="green">Vert</option>
                  <option value="grey">Gris</option>
                  <option value="indigo">Indigo</option>
                  <option value="light-blue">Bleu clair</option>
                  <option value="light-green">Vert clair</option>
                  <option value="lime">Citron</option>
                  <option value="orange">Orange</option>
                  <option value="pink">Rose</option>
                  <option value="purple">Violet</option>
                  <option value="red">Rouge</option>
                  <option value="teal">Bleu-vert foncé</option>
                  <option value="yellow">Jaune</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">{{Graphique Desktop}}</label>
              <div class="col-sm-2">
                <select class="userAttr form-control" data-l1key="options" data-l2key="desktop_highcharts_theme">
                  <option value="">Défaut</option>
                  <option value="dark-blue">Dark-blue</option>
                  <option value="dark-green">Dark-green</option>
                  <option value="dark-unica">Dark-unica</option>
                  <option value="gray">Gray</option>
                  <option value="grid-light">Grid-light</option>
                  <option value="grid">Grid</option>
                  <option value="sand-signika">Sand-signika</option>
                  <option value="skies">Skies</option>
                </select>
              </div>
            </div>
             <div class="form-group">
              <label class="col-sm-3 control-label">{{Graphique mobile}}</label>
              <div class="col-sm-2">
                <select class="userAttr form-control" data-l1key="options" data-l2key="mobile_highcharts_theme">
                  <option value="">Défaut</option>
                  <option value="dark-blue">Dark-blue</option>
                  <option value="dark-green">Dark-green</option>
                  <option value="dark-unica">Dark-unica</option>
                  <option value="gray">Gray</option>
                  <option value="grid-light">Grid-light</option>
                  <option value="grid">Grid</option>
                  <option value="sand-signika">Sand-signika</option>
                  <option value="skies">Skies</option>
                </select>
              </div>
            </div>
          </fieldset>
        </form>
      </div>
    </div>
  </div>

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">
        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionConfiguration" href="#config_interface">
          <i class="fa fa-laptop"></i>  {{Interface}}
        </a>
      </h3>
    </div>
    <div id="config_interface" class="panel-collapse collapse">
      <div class="panel-body">
        <form class="form-horizontal">
          <fieldset>
            <legend><i class="fa fa-home"></i>  {{Général}}</legend>
            <div class="form-group">
              <label class="col-sm-3 control-label">{{Masquer les menus automatiquement}}</label>
              <div class="col-sm-1">
                <input type="checkbox" class="userAttr bootstrapSwitch" data-on-color="danger" data-off-color="success" data-off-text="Oui" data-on-text="Non" data-l1key="options" data-l2key="doNotAutoHideMenu"/>
              </div>
            </div>


            <legend><i class="fa fa-file-o"></i>  {{Page par défaut}}</legend>
            <div class="form-group">
              <label class="col-sm-3 control-label">{{Desktop}}</label>
              <div class="col-sm-2">
                <select class="userAttr form-control" data-l1key="options" data-l2key="homePage">
                  <?php
foreach ($homePage as $key => $value) {
	echo "<option value='$key'>$value</option>";
}
?>
               </select>
             </div>
             <label class="col-sm-1 control-label">{{Mobile}}</label>
             <div class="col-sm-2">
              <select class="userAttr form-control" data-l1key="options" data-l2key="homePageMobile">
                <option value="home">{{Accueil}}</option>
                <?php
foreach ($homePage as $key => $value) {
	echo "<option value='$key'>$value</option>";
}
?>
             </select>
           </div>
         </div>
         <legend><i class="fa fa-columns"></i>  {{Objet par défaut sur le dashboard}}</legend>
         <div class="form-group">
          <label class="col-sm-3 control-label">{{Desktop}}</label>
          <div class="col-sm-2">
            <select class="userAttr form-control" data-l1key="options" data-l2key="defaultDashboardObject">
            	<option value='all'>{{Tout}}</option>
              <?php
foreach (object::all() as $object) {
	echo "<option value='" . $object->getId() . "'>" . $object->getName() . "</option>";
}
?>
           </select>
         </div>
         <label class="col-sm-1 control-label">{{Mobile}}</label>
         <div class="col-sm-2">
          <select class="userAttr form-control" data-l1key="options" data-l2key="defaultMobileObject">
            <option value='all'>{{Tout}}</option>
            <?php
foreach (object::all() as $object) {
	echo "<option value='" . $object->getId() . "'>" . $object->getName() . "</option>";
}
?>
         </select>
       </div>
     </div>
     <legend><i class="fa fa-eye"></i>  {{Vue par défaut}}</legend>
     <div class="form-group">
      <label class="col-sm-3 control-label">{{Desktop}}</label>
      <div class="col-sm-2">
        <select class="userAttr form-control" data-l1key="options" data-l2key="defaultDesktopView">
          <?php
foreach (view::all() as $view) {
	echo "<option value='" . $view->getId() . "'>" . $view->getName() . "</option>";
}
?>
       </select>
     </div>
     <label class="col-sm-1 control-label">{{Mobile}}</label>
     <div class="col-sm-2">
      <select class="userAttr form-control" data-l1key="options" data-l2key="defaultMobileView">
        <?php
foreach (view::all() as $view) {
	echo "<option value='" . $view->getId() . "'>" . $view->getName() . "</option>";
}
?>
     </select>
   </div>
 </div>
 <legend><i class="fa fa-paint-brush"></i>  {{Design par défaut}}</legend>
 <div class="form-group">
  <label class="col-sm-3 control-label">{{Desktop}}</label>
  <div class="col-sm-2">
    <select class="userAttr form-control" data-l1key="options" data-l2key="defaultDashboardPlan">
      <?php
foreach (planHeader::all() as $plan) {
	echo "<option value='" . $plan->getId() . "'>" . $plan->getName() . "</option>";
}
?>
   </select>
 </div>
 <label class="col-sm-1 control-label">{{Mobile}}</label>
 <div class="col-sm-2">
  <select class="userAttr form-control" data-l1key="options" data-l2key="defaultMobilePlan">
    <?php
foreach (planHeader::all() as $plan) {
	echo "<option value='" . $plan->getId() . "'>" . $plan->getName() . "</option>";
}
?>
 </select>
</div>
</div>
<div class="form-group">
  <label class="col-sm-3 control-label">{{Plein écran}}</label>
  <div class="col-sm-1">
    <input type="checkbox" class="userAttr bootstrapSwitch" data-l1key="options" data-l2key="defaultPlanFullScreen" />
  </div>
</div>

<legend><i class="fa fa-tachometer"></i>  {{Dashboard}}</legend>
<div class="form-group">
  <label class="col-sm-3 control-label">{{Déplier le panneau des scénarios}}</label>
  <div class="col-sm-1">
    <input type="checkbox" class="userAttr bootstrapSwitch" data-l1key="options" data-l2key="displayScenarioByDefault"/>
  </div>
</div>
<div class="form-group">
  <label class="col-sm-3 control-label">{{Déplier le panneau des objets}}</label>
  <div class="col-sm-1">
    <input type="checkbox" class="userAttr bootstrapSwitch" data-l1key="options" data-l2key="displayObjetByDefault"/>
  </div>
</div>
<legend><i class="fa fa-picture-o"></i>  {{Vue}}</legend>
<div class="form-group">
  <label class="col-sm-3 control-label">{{Déplier le panneau des vues}}</label>
  <div class="col-sm-1">
    <input type="checkbox" class="userAttr bootstrapSwitch" data-l1key="options" data-l2key="displayViewByDefault"/>
  </div>
</div>
</fieldset>
</form>
</div>
</div>
</div>

<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">
      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionConfiguration" href="#config_notification">
        <i class="fa fa-download"></i>  {{Notifications}}
      </a>
    </h3>
  </div>
  <div id="config_notification" class="panel-collapse collapse">
    <div class="panel-body">
      <form class="form-horizontal">
        <fieldset>
          <div class="form-group">
            <label class="col-sm-3 control-label">{{Notifier des évènements}}</label>
            <div class="col-sm-3">
              <select class="userAttr form-control" data-l1key="options" data-l2key="notifyEvent">
                <?php
foreach ($notifyTheme as $key => $value) {
	echo "<option value='$key'>$value</option>";
}
?>
             </select>
           </div>
         </div>
         <div class="form-group">
          <label class="col-sm-3 control-label">{{Notifier nouveau message}}</label>
          <div class="col-sm-3">
            <select class="userAttr form-control" data-l1key="options" data-l2key="notifyNewMessage">
              <?php
foreach ($notifyTheme as $key => $value) {
	echo "<option value='$key'>$value</option>";
}
?>
           </select>
         </div>
       </div>
     </fieldset>
   </form>
 </div>
</div>
</div>

<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">
      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionConfiguration" href="#config_other">
        <i class="icon securite-key1"></i>  {{Sécurité}}
      </a>
    </h3>
  </div>
  <div id="config_other" class="panel-collapse collapse">
    <div class="panel-body">
      <form class="form-horizontal">
        <fieldset>
          <?php if (config::byKey('sso:allowRemoteUser') != 1) {
	?>
           <div class="form-group">
            <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Authentification en 2 étapes}}</label>
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
             <a class="btn btn-default" id="bt_configureTwoFactorAuthentification"><i class="fa fa-cogs"></i> {{Configurer}}</a>
           </div>
           <?php
if ($_SESSION['user']->getOptions('twoFactorAuthentification', 0) == 1) {
		?>
    <label class="col-lg-1 col-md-2 col-sm-2 col-xs-2 control-label">{{Actif}}</label>
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
             <input type="checkbox" class="userAttr form-control bootstrapSwitch" data-l1key="options" data-l2key="twoFactorAuthentification" />
           </div>
           <?php }
	?>
         </div>

         <div class="form-group">
          <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Mot de passe}}</label>
          <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
            <input type="password" class="userAttr form-control" data-l1key="password" />
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Retapez le mot de passe}}</label>
          <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
            <input type="password" class="form-control" id="in_passwordCheck" />
          </div>
        </div>
        <?php }
?>
        <div class="form-group expertModeVisible">
          <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Hash de l'utilisateur}}</label>
          <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
            <textarea class="userAttr" data-l1key="hash" style="width:100%;" rows="5" disabled></textarea>
          </div>
          <div class="col-lg-2 col-md-3 col-sm-3">
            <a class="btn btn-default form-control" id="bt_genUserKeyAPI"><i class="fa fa-refresh"></i> {{Regénérer}}</a>
          </div>
        </div>

      </fieldset>
    </form>
  </div>
</div>
</div>

<br/>
<div class="form-actions">
  <a class="btn btn-success" id="bt_saveProfils"><i class="fa fa-check-circle icon-white"></i> {{Sauvegarder}}</a>
</div>
</div>
<?php include_file("desktop", "profils", "js");?>
