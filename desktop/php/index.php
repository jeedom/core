<?php
if (init('rescue', 0) == 1 && !in_array(init('p'), array('custom', 'backup', 'cron', 'connection', 'log', 'database', 'editor', 'system'))) {
	$_GET['p'] = 'system';
}
include_file('core', 'authentification', 'php');
global $JEEDOM_INTERNAL_CONFIG;
$configs = config::byKeys(array('enableCustomCss', 'language', 'jeedom::firstUse', 'widget::step::width', 'widget::step::height', 'widget::margin', 'product_name', 'product_icon', 'product_image'));
if (isConnect()) {
	$homePage = explode('::', $_SESSION['user']->getOptions('homePage', 'core::dashboard'));
	if (count($homePage) == 2) {
		if ($homePage[0] == 'core') {
			$homeLink = 'index.php?v=d&p=' . $homePage[1];
		} else {
			$homeLink = 'index.php?v=d&m=' . $homePage[0] . '&p=' . $homePage[1];
		}
		if ($homePage[1] == 'plan' && $_SESSION['user']->getOptions('defaultPlanFullScreen') == 1) {
			$homeLink .= '&fullscreen=1';
		}
	} else {
		$homeLink = 'index.php?v=d&p=dashboard';
	}
}
if (init('rescue', 0) == 1) {
	$homeLink = 'index.php?v=d&p=system&rescue=1';
}
$title = 'Jeedom';
if (init('p') == '' && isConnect()) {
	redirect($homeLink);
}
$page = '';
if (isConnect() && init('p') != '') {
	$page = init('p');
	$title = ucfirst($page) . ' - ' . $title;
}
$plugin_menu = '';
$panel_menu = '';
if (init('rescue', 0) == 0) {
	$plugins_list = plugin::listPlugin(true, true);
	$eventjs_plugin = array();
	if (count($plugins_list) > 0) {
		foreach ($plugins_list as $category_name => $category) {
			$icon = '';
			if (isset($JEEDOM_INTERNAL_CONFIG['plugin']['category'][$category_name]) && isset($JEEDOM_INTERNAL_CONFIG['plugin']['category'][$category_name]['icon'])) {
				$icon = $JEEDOM_INTERNAL_CONFIG['plugin']['category'][$category_name]['icon'];
			}
			$name = $category_name;
			if (isset($JEEDOM_INTERNAL_CONFIG['plugin']['category'][$category_name]) && isset($JEEDOM_INTERNAL_CONFIG['plugin']['category'][$category_name]['name'])) {
				$name = $JEEDOM_INTERNAL_CONFIG['plugin']['category'][$category_name]['name'];
			}
			
			$plugin_menu .= '<li class="dropdown-submenu"><a data-toggle="dropdown"><i class="fas ' . $icon . '"></i> {{' . $name . '}}</a>';
			$plugin_menu .= '<ul class="dropdown-menu">';
			foreach ($category as $pluginList) {
				if ($pluginList->getId() == init('m')) {
					$plugin = $pluginList;
					$title = $plugin->getName() . ' - Jeedom';
				}
				$plugin_menu .= '<li style="padding-right:10px"><a href="index.php?v=d&m=' . $pluginList->getId() . '&p=' . $pluginList->getIndex() . '"><img class="img-responsive" style="width : 20px;display:inline-block;" src="' . $pluginList->getPathImgIcon() . '" /> ' . $pluginList->getName() . '</a></li>';
				if ($pluginList->getDisplay() != '' && config::byKey('displayDesktopPanel', $pluginList->getId(), 0) != 0) {
					$panel_menu .= '<li style="padding-right:10px"><a href="index.php?v=d&m=' . $pluginList->getId() . '&p=' . $pluginList->getDisplay() . '"><img class="img-responsive" style="width : 20px;display:inline-block;" src="' . $pluginList->getPathImgIcon() . '" /> ' . $pluginList->getName() . '</a></li>';
				}
				if ($pluginList->getEventjs() == 1) {
					$eventjs_plugin[] = $pluginList->getId();
				}
			}
			$plugin_menu .= '</ul>';
			$plugin_menu .= '</li>';
		}
	}
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<title><?php echo $title; ?></title>
	<link rel="shortcut icon" href="<?php echo $configs['product_icon'] ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<script>
	var clientDatetime = new Date();
	var clientServerDiffDatetime = (<?php echo strtotime('now'); ?> * 1000) - clientDatetime.getTime();
	var serverDatetime = <?php echo getmicrotime(); ?>;
	</script>
	<?php
	if (!isConnect()) {
		if (init('rescue', 0) == 0 && is_dir(__DIR__ . '/../../core/themes/' . config::byKey('default_bootstrap_theme') . '/desktop') && file_exists(__DIR__ . '/../../core/themes/' . config::byKey('default_bootstrap_theme') . '/desktop/' . config::byKey('default_bootstrap_theme') . '.css')) {
			include_file('core', config::byKey('default_bootstrap_theme') . '/desktop/' . config::byKey('default_bootstrap_theme'), 'themes.css');
		} else {
			include_file('3rdparty', 'bootstrap/css/bootstrap.min', 'css');
		}
	} else {
		try {
			if (init('rescue', 0) == 0 && is_dir(__DIR__ . '/../../core/themes/' . $_SESSION['user']->getOptions('bootstrap_theme') . '/desktop') && file_exists(__DIR__ . '/../../core/themes/' . $_SESSION['user']->getOptions('bootstrap_theme') . '/desktop/' . $_SESSION['user']->getOptions('bootstrap_theme') . '.css')) {
				include_file('core', $_SESSION['user']->getOptions('bootstrap_theme') . '/desktop/' . $_SESSION['user']->getOptions('bootstrap_theme'), 'themes.css');
			} else if (init('rescue', 0) == 0 && is_dir(__DIR__ . '/../../core/themes/' . config::byKey('default_bootstrap_theme') . '/desktop') && file_exists(__DIR__ . '/../../core/themes/' . config::byKey('default_bootstrap_theme') . '/desktop/' . config::byKey('default_bootstrap_theme') . '.css')) {
				include_file('core', config::byKey('default_bootstrap_theme') . '/desktop/' . config::byKey('default_bootstrap_theme'), 'themes.css');
			} else {
				include_file('3rdparty', 'bootstrap/css/bootstrap.min', 'css');
			}
		} catch (Exception $e) {
			include_file('3rdparty', 'bootstrap/css/bootstrap.min', 'css');
		}
	}
	include_file('core', 'icon.inc', 'php');
	include_file('3rdparty', 'roboto/roboto', 'css');
	include_file('desktop', 'commun', 'css');
	include_file('core', 'core', 'css');
	include_file('3rdparty', 'jquery.toastr/jquery.toastr.min', 'css');
	include_file('3rdparty', 'jquery.ui/jquery-ui-bootstrap/jquery-ui', 'css');
	include_file('3rdparty', 'jquery.utils/jquery.utils', 'css');
	include_file('3rdparty', 'jquery/jquery.min', 'js');
	?>
	<script>
	JEEDOM_PRODUCT_NAME='<?php echo $configs['product_name'] ?>';
	JEEDOM_AJAX_TOKEN='<?php echo ajax::getToken() ?>';
	$.ajaxSetup({
		type: "POST",
		data: {
			jeedom_token: '<?php echo ajax::getToken() ?>'
		}
	})
	</script>
	<?php
	include_file('3rdparty', 'font-noto/font-noto', 'css');
	include_file('3rdparty', 'jquery.utils/jquery.utils', 'js');
	include_file('core', 'core', 'js');
	include_file('3rdparty', 'bootstrap/bootstrap.min', 'js');
	include_file('3rdparty', 'jquery.ui/jquery-ui.min', 'js');
	include_file('3rdparty', 'jquery.ui/jquery.ui.datepicker.fr', 'js');
	include_file('core', 'js.inc', 'php');
	include_file('3rdparty', 'bootbox/bootbox.min', 'js');
	include_file('3rdparty', 'highstock/highstock', 'js');
	include_file('3rdparty', 'highstock/highcharts-more', 'js');
	include_file('3rdparty', 'highstock/modules/solid-gauge', 'js');
	include_file('3rdparty', 'highstock/modules/exporting', 'js');
	include_file('3rdparty', 'highstock/modules/export-data', 'js');
	include_file('desktop', 'utils', 'js');
	include_file('3rdparty', 'jquery.toastr/jquery.toastr.min', 'js');
	include_file('3rdparty', 'jquery.at.caret/jquery.at.caret.min', 'js');
	include_file('3rdparty', 'jwerty/jwerty', 'js');
	include_file('3rdparty', 'jquery.packery/jquery.packery', 'js');
	include_file('3rdparty', 'jquery.lazyload/jquery.lazyload', 'js');
	include_file('3rdparty', 'jquery.sew/jquery.sew', 'css');
	include_file('3rdparty', 'codemirror/lib/codemirror', 'js');
	include_file('3rdparty', 'codemirror/lib/codemirror', 'css');
	include_file('3rdparty', 'codemirror/addon/edit/matchbrackets', 'js');
	include_file('3rdparty', 'codemirror/mode/htmlmixed/htmlmixed', 'js');
	include_file('3rdparty', 'codemirror/mode/clike/clike', 'js');
	include_file('3rdparty', 'codemirror/mode/php/php', 'js');
	include_file('3rdparty', 'codemirror/mode/xml/xml', 'js');
	include_file('3rdparty', 'codemirror/mode/javascript/javascript', 'js');
	include_file('3rdparty', 'codemirror/mode/css/css', 'js');
	include_file('3rdparty', 'jquery.tree/themes/default/style.min', 'css');
	include_file('3rdparty', 'jquery.tree/jstree.min', 'js');
	include_file('3rdparty', 'jquery.fileupload/jquery.ui.widget', 'js');
	include_file('3rdparty', 'jquery.fileupload/jquery.iframe-transport', 'js');
	include_file('3rdparty', 'jquery.fileupload/jquery.fileupload', 'js');
	include_file('3rdparty', 'jquery.tablesorter/theme.bootstrap', 'css');
	include_file('3rdparty', 'jquery.tablesorter/jquery.tablesorter.min', 'js');
	include_file('3rdparty', 'jquery.tablesorter/jquery.tablesorter.widgets.min', 'js');
	include_file('3rdparty', 'jquery.tablesorter/parsers/parser-input-select.min', 'js');
	include_file('3rdparty', 'datetimepicker/jquery.datetimepicker', 'js');
	include_file('3rdparty', 'datetimepicker/jquery.datetimepicker', 'css');
	include_file('3rdparty', 'jquery.cron/jquery.cron.min', 'js');
	include_file('3rdparty', 'jquery.cron/jquery.cron', 'css');
	include_file('3rdparty', 'jquery.contextMenu/jquery.contextMenu.min', 'css');
	include_file('3rdparty', 'jquery.contextMenu/jquery.contextMenu.min', 'js');
	include_file('3rdparty', 'autosize/autosize.min', 'js');
	include_file('3rdparty', 'animate/animate', 'css');
	include_file('3rdparty', 'animate/animate', 'js');
	if (init('rescue', 0) == 0 && $configs['enableCustomCss'] == 1) {
		if (file_exists(__DIR__ . '/../custom/custom.css')) {
			include_file('desktop', '', 'custom.css');
		}
		if (file_exists(__DIR__ . '/../custom/custom.js')) {
			include_file('desktop', '', 'custom.js');
		}
	}
	try {
		if (isConnect()) {
			if (init('rescue', 0) == 0 && is_dir(__DIR__ . '/../../core/themes/' . $_SESSION['user']->getOptions('bootstrap_theme') . '/desktop')) {
				if (file_exists(__DIR__ . '/../../core/themes/' . $_SESSION['user']->getOptions('bootstrap_theme') . '/desktop/' . $_SESSION['user']->getOptions('bootstrap_theme') . '.js')) {
					include_file('core', $_SESSION['user']->getOptions('bootstrap_theme') . '/desktop/' . $_SESSION['user']->getOptions('bootstrap_theme'), 'themes.js');
				}
			}
			if (init('rescue', 0) == 0 && $_SESSION['user']->getOptions('desktop_highcharts_theme') != '') {
				try {
					if (is_dir(__DIR__ . '/../../core/themes/' . $_SESSION['user']->getOptions('bootstrap_theme') . '/desktop')) {
						if (file_exists(__DIR__ . '/../../core/themes/' . $_SESSION['user']->getOptions('bootstrap_theme') . '/desktop/' . $_SESSION['user']->getOptions('bootstrap_theme') . '.js')) {
							include_file('core', $_SESSION['user']->getOptions('bootstrap_theme') . '/desktop/' . $_SESSION['user']->getOptions('bootstrap_theme'), 'themes.js');
						}
					}
				} catch (Exception $e) {
					
				}
				if (init('rescue', 0) == 0 && $_SESSION['user']->getOptions('desktop_highcharts_theme') != '') {
					try {
						include_file('3rdparty', 'highstock/themes/' . $_SESSION['user']->getOptions('desktop_highcharts_theme'), 'js');
					} catch (Exception $e) {
						
					}
				}
			}
		}
	} catch (Exception $e) {
		
	} 	?>
	<script src="3rdparty/snap.svg/snap.svg-min.js"></script>
</head>
<body>
	<div class="backgroundforJeedom" style="position:fixed;"></div>
	<?php
	sendVarToJS('jeedom_langage', $configs['language']);
	if (!isConnect()) {
		include_file('desktop', 'connection', 'php');
	} else {
		sendVarToJS('userProfils', $_SESSION['user']->getOptions());
		sendVarToJS('user_id', $_SESSION['user']->getId());
		sendVarToJS('user_isAdmin', isConnect('admin'));
		sendVarToJS('user_login', $_SESSION['user']->getLogin());
		sendVarToJS('jeedom_firstUse', $configs['jeedom::firstUse']);
		sendVarToJS('widget_width_step', $configs['widget::step::width']);
		sendVarToJS('widget_height_step', $configs['widget::step::height']);
		sendVarToJS('widget_margin', $configs['widget::margin']);
		if (count($eventjs_plugin) > 0) {
			foreach ($eventjs_plugin as $value) {
				try {
					include_file('desktop', 'event', 'js', $value);
				} catch (Exception $e) {
					log::add($value, 'error', 'Event JS file not found');
				}
			}
		}
		?>
		<?php if (init('rescue', 0) == 0) { ?>
			<header class="navbar navbar-fixed-top navbar-default reportModeHidden" style="margin-bottom: 0px !important;">
				<div class="container-fluid">
					<div class="navbar-header">
						<a class="navbar-brand" href="<?php echo $homeLink; ?>">
							<img src="<?php echo $configs['product_image'] ?>" height="30" style="position: relative; top:-5px;"/>
						</a>
						<button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse">
							<span class="sr-only">{{Toggle navigation}}</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<center><span class="visible-xs-inline-block" style="margin-top:20px; font-size:0.8em !important;"><?php echo jeeObject::getGlobalHtmlSummary(); ?></span></center>
					</div>
					<nav class="navbar-collapse collapse">
						<ul class="nav navbar-nav">
							<li class="dropdown cursor">
								<a class="dropdown-toggle" data-toggle="dropdown"><i class="fas fa-home"></i> <span class="hidden-sm hidden-md">{{Accueil}}</span> <b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li class="dropdown-submenu">
										<a data-toggle="dropdown" id="bt_gotoDashboard" href="index.php?v=d&p=dashboard"><i class="fas fa-tachometer-alt"></i> {{Dashboard}}</a>
										<ul class="dropdown-menu scrollable-menu" role="menu" style="height: auto;max-height: 600px; overflow-x: hidden;">
											<?php foreach (jeeObject::buildTree(null, false) as $object_li) {
												echo '<li><a href="index.php?v=d&p=dashboard&object_id=' . $object_li->getId() . '">' . $object_li->getHumanName(true) . '</a></li>';
											} ?>
										</ul>
									</li>
									<li class="dropdown-submenu">
										<a data-toggle="dropdown" id="bt_gotoView"><i class="fas fa-picture-o"></i> {{Vue}}</a>
										<ul class="dropdown-menu scrollable-menu" role="menu" style="height: auto;max-height: 600px; overflow-x: hidden;">
											<?php	foreach (view::all() as $view_menu) {
												echo '<li><a href="index.php?v=d&p=view&view_id=' . $view_menu->getId() . '">' . trim($view_menu->getDisplay('icon')) . ' ' . $view_menu->getName() . '</a></li>';
											} ?>
										</ul>
									</li>
									<li class="dropdown-submenu">
										<a data-toggle="dropdown" id="bt_gotoPlan"><i class="fas fa-paint-brush"></i> {{Design}}</a>
										<ul class="dropdown-menu scrollable-menu" role="menu" style="height: auto;max-height: 600px; overflow-x: hidden;">
											<?php foreach (planHeader::all() as $plan_menu) {
												echo '<li><a href="index.php?v=d&p=plan&plan_id=' . $plan_menu->getId() . '">' . trim($plan_menu->getConfiguration('icon') . ' ' . $plan_menu->getName()) . '</a></li>';
											} ?>
										</ul>
									</li>
									<li class="dropdown-submenu">
										<a data-toggle="dropdown" id="bt_gotoPlan3d"><i class="fas fa-cubes"></i> {{Design 3D}}</a>
										<ul class="dropdown-menu scrollable-menu" role="menu" style="height: auto;max-height: 600px; overflow-x: hidden;">
											<?php foreach (plan3dHeader::all() as $plan3d_menu) {
												echo '<li><a href="index.php?v=d&p=plan3d&plan3d_id=' . $plan3d_menu->getId() . '">' . trim($plan3d_menu->getConfiguration('icon') . ' ' . $plan3d_menu->getName()) . '</a></li>';
											} 	?>
										</ul>
									</li>
									<?php echo $panel_menu; ?>
								</ul>
							</li>
							<li class="dropdown cursor">
								<a data-toggle="dropdown"><i class="fas fa-stethoscope"></i> <span class="hidden-sm hidden-md">{{Analyse}}</span> <b class="caret"></b></a>
								<ul class="dropdown-menu" role="menu">
									<li><a href="index.php?v=d&p=history"><i class="fas fa-bar-chart-o"></i> {{Historique}}</a></li>
									<?php if (isConnect('admin')) {	?>
										<li><a href="index.php?v=d&p=report"><i class="fas fa-newspaper-o"></i> {{Rapport}}</a></li>
									<?php } ?>
									<li class="divider"></li>
									<li><a href="#" id="bt_showEventInRealTime"><i class="fas fa-tachometer-alt"></i> {{Temps réel}}</a></li>
									<?php if (isConnect('admin')) { ?>
										<li><a href="#" id="bt_showNoteManager"><i class="fas fa-sticky-note"></i> {{Note}}</a></li>
										<li><a href="index.php?v=d&p=log"><i class="far fa-file"></i> {{Logs}}</a></li>
										<li><a href="index.php?v=d&p=eqAnalyse"><i class="fas fa-battery-full"></i> {{Equipements}}</a></li>
										<li class="divider"></li>
										<li><a href="index.php?v=d&p=health"><i class="fas fa-medkit"></i> {{Santé}}</a></li>
									<?php } ?>
								</ul>
							</li>
							<?php if (isConnect('admin')) { ?>
								<li class="dropdown cursor">
									<a data-toggle="dropdown"><i class="fas fa-wrench"></i> <span class="hidden-sm hidden-md">{{Outils}}</span> <b class="caret"></b></a>
									<ul class="dropdown-menu" role="menu">
										<li><a href="index.php?v=d&p=object"><i class="fas fa-picture-o"></i> {{Objets}}</a></li>
										<li><a href="index.php?v=d&p=interact"><i class="far fa-comments"></i> {{Interactions}}</a></li>
										<li><a href="index.php?v=d&p=display"><i class="fas fa-th"></i> {{Résumé domotique}}</a></li>
										<li><a href = "index.php?v=d&p=scenario"><i class = "fa fa-cogs"></i> {{Scénarios}}</a></li>
									</ul>
								</li>
								<li class="dropdown cursor">
									<a data-toggle="dropdown"><i class="fas fa-tasks"></i> <span class="hidden-sm hidden-md">{{Plugins}}</span> <b class="caret"></b></a>
									<ul class="dropdown-menu" role="menu">
										<li><a href="index.php?v=d&p=plugin"><i class="fas fa-tags"></i> {{Gestion des plugins}}</a></li>
										<li role="separator" class="divider"></li>
										<?php echo $plugin_menu; ?>
									</ul>
								</li>
							<?php } ?>
						</ul>
						<ul class="nav navbar-nav navbar-right">
							<?php
							$nbMessage = message::nbMessage();
							$displayMessage = ($nbMessage > 0) ? '' : 'display : none;';?>
							<li>
								<a href="#" id="bt_messageModal">
									<span class="badge" id="span_nbMessage" title="{{Nombre de messages}}" style="background-color : #ec971f;<?php echo $displayMessage; ?>">
										<?php echo $nbMessage; ?>
									</span>
								</a>
							</li>
							<?php if (isConnect('admin')) {
								$nbUpdate = update::nbNeedUpdate();
								$displayUpdate = ($nbUpdate > 0) ? '' : 'display : none;';?>
								<li>
									<a href="index.php?v=d&p=update">
										<span class="badge" id="span_nbUpdate"  title="{{Nombre de mises à jour}}" style="background-color : #c9302c;<?php echo $displayUpdate; ?>"><?php echo $nbUpdate; ?></span></a>
									</li>
								<?php } ?>
								<li class="hidden-xs"><a href="#" style="cursor:default;"><?php echo jeeObject::getGlobalHtmlSummary(); ?></a></li>
								<?php if (isConnect('admin')) { ?>
									<li class="dropdown">
										<a class="dropdown-toggle" data-toggle="dropdown" href="#">
											<i class="fas fa-cogs"></i><span class="visible-xs-inline-block">{{Configuration}}</span>
											<span class="caret"></span>
										</a>
										<ul class="dropdown-menu">
											<li><a href="index.php?v=d&p=administration" tabindex="0"><i class="fas fa-wrench"></i> {{Configuration}}</a></li>
											<li><a href="index.php?v=d&p=backup"><i class="fas fa-floppy-o"></i> {{Sauvegardes}}</a></li>
											<li><a href="index.php?v=d&p=update"><i class="fas fa-refresh"></i> {{Centre de mise à jour}}</a></li>
											<?php if(jeedom::getHardwareName() == 'smart'){
												echo '<li><a href="index.php?v=d&p=imageMaj"><i class="fas fa-hdd"></i> {{Restauration Image}}</a></li>';
											} ?>
											<li><a href="index.php?v=d&p=cron"><i class="fas fa-tasks"></i> {{Moteur de tâches}}</a></li>
											<li><a href="index.php?v=d&p=custom"><i class="fas fa-pencil-alt"></i> {{Personnalisation avancée}}</a></li>
											<li><a href="index.php?v=d&p=user"><i class="fas fa-users"></i> {{Utilisateurs}}</a></li>
											<li class="divider"></li>
											<?php	if (jeedom::isCapable('sudo') && isConnect('admin')) {
												echo '<li class="cursor"><a id="bt_rebootSystem" state="0"><i class="fas fa-repeat"></i> {{Redémarrer}}</a></li>';
												echo '<li class="cursor"><a id="bt_haltSystem" state="0"><i class="fas fa-power-off"></i> {{Eteindre}}</a></li>';
											} ?>
										</ul>
									</li>
								<?php } ?>
								<li class="dropdown">
									<a class="dropdown-toggle" data-toggle="dropdown" href="#">
										<i class="fas fa-user"></i> <span class="visible-xs-inline-block">{{Utilisateur}}</span>
										<span class="caret"></span>
									</a>
									<ul class="dropdown-menu">
										<li><a href="index.php?v=d&p=profils"><i class="fas fa-briefcase"></i> {{Profil}} <?php echo $_SESSION['user']->getLogin(); ?></a></li>
										<li><a href="index.php?v=m" class="noOnePageLoad"><i class="fas fa-mobile"></i> {{Version mobile}}</a></li>
										<?php if (isConnect('admin')) { ?>
											<li>
												<?php if (isset($plugin) && is_object($plugin) && $plugin->getIssue() != '') { ?>
													<a target="_blank" href="<?php echo $plugin->getIssue() ?>"><i class="fas fa-exclamation-circle" ></i> {{Rapport de bug}}</a>
												<?php } else {?>
													<a class="bt_reportBug cursor"><i class="fas fa-exclamation-circle" ></i> {{Rapport de bug}}</a>
												<?php } ?>
											</li>
										<?php } ?>
										<li><a href="index.php?v=d&logout=1" class="noOnePageLoad"><i class="fas fa-sign-out-alt"></i> {{Se déconnecter}}</a></li>
										<li><a href="#" id="bt_jeedomAbout"><i class="fas fa-info-circle"></i> {{Version}} v<?php echo jeedom::version(); ?></a></li>
									</ul>
								</li>
								<li class="hidden-xs">
									<a id="bt_getHelpPage" class="cursor" data-plugin="<?php echo init('m'); ?>" data-page="<?php echo init('p'); ?>" title="{{Aide sur la page en cours}}"><i class="fas fa-question-circle" ></i></a>
								</li>
							</ul>
						</nav>
					</div>
				</header>
			<?php } ?>
			<?php if (init('rescue', 0) == 1) {?>
				<header class="navbar navbar-fixed-top navbar-default reportModeHidden" style="margin-bottom: 0px !important;">
					<div class="container-fluid">
						<div class="navbar-header">
							<a class="navbar-brand" href="<?php echo $homeLink; ?>">
								<img src="core/img/logo-jeedom-grand-nom-couleur.svg" height="30" style="position: relative; top:-5px;"/>
							</a>
							<button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse">
								<span class="sr-only">{{Toggle navigation}}</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
						</div>
						<nav class="navbar-collapse collapse">
							<ul class="nav navbar-nav">
								<li><a href="index.php?v=d&p=system&rescue=1"><i class="fas fa-terminal"></i> {{Système}}</a></li>
								<li><a href="index.php?v=d&p=database&rescue=1"><i class="fas fa-database"></i> {{Database}}</a></li>
								<li><a href="index.php?v=d&p=editor&rescue=1"><i class="fas fa-indent"></i> {{Editeur}}</a></li>
								<li><a href="index.php?v=d&p=custom&rescue=1"><i class="fas fa-pen-square"></i> {{Personnalisation}}</a></li>
								<li><a href="index.php?v=d&p=backup&rescue=1"><i class="fas fa-floppy-o"></i> {{Sauvegarde}}</a></li>
								<li><a href="index.php?v=d&p=cron&rescue=1"><i class="fas fa-tasks"></i> {{Moteur de tâches}}</a></li>
								<li><a href="index.php?v=d&p=log&rescue=1"><i class="far fa-file"></i> {{Log}}</a></li>
							</ul>
						</nav>
					</div>
				</header>
			<?php } ?>
			<main class="container-fluid" id="div_mainContainer">
				<div style="display: none;width : 100%" id="div_alert"></div>
				<div id="div_pageContainer">
					<?php
					try {
						if (!jeedom::isStarted()) {
							echo '<div class="alert alert-danger">{{Jeedom est en cours de démarrage, veuillez patienter. La page se rechargera automatiquement une fois le démarrage terminé.}}</div>';
						}
						if (isset($plugin) && is_object($plugin)) {
							include_file('desktop', $page, 'php', $plugin->getId());
						} else {
							include_file('desktop', $page, 'php');
						}
					} catch (Exception $e) {
						ob_end_clean();
						echo '<div class="alert alert-danger div_alert">';
						echo displayException($e);
						echo '</div>';
					} catch (Error $e) {
						ob_end_clean();
						echo '<div class="alert alert-danger div_alert">';
						echo displayException($e);
						echo '</div>';
					}
					?>
				</div>
				<div id="md_modal"></div>
				<div id="md_modal2"></div>
				<div id="md_pageHelp" style="display: none;" title="Aide">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#div_helpWebsite" data-toggle="tab">{{Générale}}</a></li>
						<li><a href="#div_helpSpe" data-toggle="tab">{{Détaillée}}</a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="div_helpWebsite" ></div>
						<div class="tab-pane" id="div_helpSpe" ></div>
					</div>
				</div>
				<div id="md_reportBug" title="{{Ouverture d'un ticket}}"></div>
			</main>
		<?php } 	?>
	</body>
	</html>
	