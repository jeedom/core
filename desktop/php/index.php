<?php
include_file('core', 'authentification', 'php');
global $JEEDOM_INTERNAL_CONFIG;
$configs = config::byKeys(array('enableCustomCss', 'language', 'jeedom::firstUse'));
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
$title = 'Jeedom';
if (init('p') == '' && isConnect()) {
	redirect($homeLink);
}
$page = '';
if (isConnect() && init('p') != '') {
	$page = init('p');
	$title = ucfirst($page) . ' - ' . $title;
}
$plugins_list = plugin::listPlugin(true, true);
$plugin_menu = '';
$panel_menu = '';
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

		$plugin_menu .= '<li class="dropdown-submenu"><a data-toggle="dropdown"><i class="fa ' . $icon . '"></i> {{' . $name . '}}</a>';
		$plugin_menu .= '<ul class="dropdown-menu">';
		foreach ($category as $pluginList) {
			if ($pluginList->getId() == init('m')) {
				$plugin = $pluginList;
				$title = ucfirst($plugin->getName()) . ' - Jeedom';
			}
			$plugin_menu .= '<li style="padding-right:10px"><a href="index.php?v=d&m=' . $pluginList->getId() . '&p=' . $pluginList->getIndex() . '"><img class="img-responsive" style="width : 20px;display:inline-block;" src="' . $pluginList->getPathImgIcon() . '" /> ' . $pluginList->getName() . '</a></li>';
			if ($pluginList->getDisplay() != '' && config::bykey('displayDesktopPanel', $pluginList->getId(), 0) != 0) {
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

?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<title><?php echo $title; ?></title>
	<link rel="shortcut icon" href="core/img/logo-jeedom-sans-nom-couleur-25x25.png">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<script>
		var clientDatetime = new Date();
		var clientServerDiffDatetime = (<?php echo strtotime('now'); ?> * 1000) - clientDatetime.getTime();
		var serverDatetime = <?php echo getmicrotime(); ?>;
		var io = null;
	</script>
	<?php
if (!isConnect() || $_SESSION['user']->getOptions('bootstrap_theme') == '') {
	include_file('3rdparty', 'bootstrap/css/bootstrap.min', 'css');
} else {
	try {
		if (is_dir(dirname(__FILE__) . '/../../core/themes/' . $_SESSION['user']->getOptions('bootstrap_theme') . '/desktop') && file_exists(dirname(__FILE__) . '/../../core/themes/' . $_SESSION['user']->getOptions('bootstrap_theme') . '/desktop/' . $_SESSION['user']->getOptions('bootstrap_theme') . '.css')) {
			include_file('core', $_SESSION['user']->getOptions('bootstrap_theme') . '/desktop/' . $_SESSION['user']->getOptions('bootstrap_theme'), 'themes.css');
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
include_file('3rdparty', 'datetimepicker/jquery.datetimepicker', 'js');
include_file('3rdparty', 'datetimepicker/jquery.datetimepicker', 'css');
include_file('3rdparty', 'jquery.cron/jquery.cron.min', 'js');
include_file('3rdparty', 'jquery.cron/jquery.cron', 'css');
include_file('3rdparty', 'jquery.contextMenu/jquery.contextMenu.min', 'css');
include_file('3rdparty', 'jquery.contextMenu/jquery.contextMenu.min', 'js');
include_file('3rdparty', 'autosize/autosize.min', 'js');
if ($configs['enableCustomCss'] == 1) {
	if (file_exists(dirname(__FILE__) . '/../custom/custom.css')) {
		include_file('desktop', '', 'custom.css');
	}
	if (file_exists(dirname(__FILE__) . '/../custom/custom.js')) {
		include_file('desktop', '', 'custom.js');
	}
}
try {
	if (isConnect()) {
		if (is_dir(dirname(__FILE__) . '/../../core/themes/' . $_SESSION['user']->getOptions('bootstrap_theme') . '/desktop')) {
			if (file_exists(dirname(__FILE__) . '/../../core/themes/' . $_SESSION['user']->getOptions('bootstrap_theme') . '/desktop/' . $_SESSION['user']->getOptions('bootstrap_theme') . '.js')) {
				include_file('core', $_SESSION['user']->getOptions('bootstrap_theme') . '/desktop/' . $_SESSION['user']->getOptions('bootstrap_theme'), 'themes.js');
			}
		}
		if ($_SESSION['user']->getOptions('desktop_highcharts_theme') != '') {
			try {
				if (is_dir(dirname(__FILE__) . '/../../core/themes/' . $_SESSION['user']->getOptions('bootstrap_theme') . '/desktop')) {
					if (file_exists(dirname(__FILE__) . '/../../core/themes/' . $_SESSION['user']->getOptions('bootstrap_theme') . '/desktop/' . $_SESSION['user']->getOptions('bootstrap_theme') . '.js')) {
						include_file('core', $_SESSION['user']->getOptions('bootstrap_theme') . '/desktop/' . $_SESSION['user']->getOptions('bootstrap_theme'), 'themes.js');
					}
				}
			} catch (Exception $e) {

			}
			if ($_SESSION['user']->getOptions('desktop_highcharts_theme') != '') {
				try {
					include_file('3rdparty', 'highstock/themes/' . $_SESSION['user']->getOptions('desktop_highcharts_theme'), 'js');
				} catch (Exception $e) {

				}
			}
		}
	}
} catch (Exception $e) {

}
?>
	<script src="3rdparty/snap.svg/snap.svg-min.js"></script>
</head>
<body>
	<?php
sendVarToJS('jeedom_langage', $configs['language']);
if (!isConnect()) {
	include_file('desktop', 'connection', 'php');
} else {
	sendVarToJS('userProfils', $_SESSION['user']->getOptions());
	sendVarToJS('user_id', $_SESSION['user']->getId());
	sendVarToJS('user_login', $_SESSION['user']->getLogin());
	sendVarToJS('jeedom_firstUse', $configs['jeedom::firstUse']);
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
						<li class="dropdown cursor">
							<a class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-home"></i> <span class="hidden-xs hidden-sm hidden-md">{{Accueil}}</span> <b class="caret"></b></a>
							<ul class="dropdown-menu">

								<li class="dropdown-submenu">
									<a data-toggle="dropdown" id="bt_gotoDashboard" href="index.php?v=d&p=dashboard"><i class="fa fa-dashboard"></i> {{Dashboard}}</a>
									<ul class="dropdown-menu">
										<?php
foreach (object::buildTree(null, false) as $object_li) {
		echo '<li><a href="index.php?v=d&p=dashboard&object_id=' . $object_li->getId() . '">' . $object_li->getHumanName(true) . '</a></li>';
	}
	?>
									</ul>
								</li>
								<li class="dropdown-submenu">
									<a data-toggle="dropdown" id="bt_gotoView"><i class="fa fa-picture-o"></i> {{Vue}}</a>
									<ul class="dropdown-menu">
										<?php
foreach (view::all() as $view_menu) {
		echo '<li><a href="index.php?v=d&p=view&view_id=' . $view_menu->getId() . '">' . trim($view_menu->getDisplay('icon')) . ' ' . $view_menu->getName() . '</a></li>';
	}
	?>
									</ul>
								</li>
								<li class="dropdown-submenu">
									<a data-toggle="dropdown" id="bt_gotoPlan"><i class="fa fa-paint-brush"></i> {{Design}}</a>
									<ul class="dropdown-menu">
										<?php
foreach (planHeader::all() as $plan_menu) {
		echo '<li><a href="index.php?v=d&p=plan&plan_id=' . $plan_menu->getId() . '">' . trim($plan_menu->getConfiguration('icon') . ' ' . $plan_menu->getName()) . '</a></li>';
	}
	?>
									</ul>
								</li>
								<?php
echo $panel_menu;
	?>
							</ul>
						</li>
						<li class="dropdown cursor">
							<a data-toggle="dropdown"><i class="fa fa-stethoscope"></i> <span class="hidden-xs hidden-sm hidden-md">{{Analyse}}</span> <b class="caret"></b></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="index.php?v=d&p=history"><i class="fa fa-bar-chart-o"></i> {{Historique}}</a></li>
								<?php
if (isConnect('admin')) {
		?>
									<li><a href="index.php?v=d&p=report"><i class="fa fa-newspaper-o"></i> {{Rapport}}</a></li>
									<?php
}
	?>
								<li class="divider"></li>
								<li><a href="#" id="bt_showEventInRealTime"><i class="fa fa-tachometer"></i> {{Temps réel}}</a></li>
								<?php
if (isConnect('admin')) {
		?>
									<li><a href="index.php?v=d&p=log"><i class="fa fa-file-o"></i> {{Logs}}</a></li>
									<li><a href="index.php?v=d&p=eqAnalyse"><i class="fa fa-battery-full"></i> {{Equipements}}</a></li>
									<li class="divider"></li>
									<li><a href="index.php?v=d&p=health"><i class="fa fa-medkit"></i> {{Santé}}</a></li>
									<?php
}
	?>
							</ul>
						</li>
						<?php
if (isConnect('admin')) {
		?>
							<li class="dropdown cursor">
								<a data-toggle="dropdown"><i class="fa fa-wrench"></i> <span class="hidden-xs hidden-sm hidden-md">{{Outils}}</span> <b class="caret"></b></a>
								<ul class="dropdown-menu" role="menu">
									<li><a href="index.php?v=d&p=object"><i class="fa fa-picture-o"></i> {{Objets}}</a></li>
									<li><a href="index.php?v=d&p=interact"><i class="fa fa-comments-o"></i> {{Interactions}}</a></li>
									<li><a href="index.php?v=d&p=display"><i class="fa fa-th"></i> {{Résumé domotique}}</a></li>
									<li><a href = "index.php?v=d&p=scenario"><i class = "fa fa-cogs"></i> {{Scénarios}}</a></li>
								</ul>
							</li>
							<li class="dropdown cursor">
								<a data-toggle="dropdown"><i class="fa fa-tasks"></i> <span class="hidden-xs hidden-sm hidden-md">{{Plugins}}</span> <b class="caret"></b></a>
								<ul class="dropdown-menu" role="menu">
									<li><a href="index.php?v=d&p=plugin"><i class="fa fa-tags"></i> {{Gestion des plugins}}</a></li>
									<li role="separator" class="divider"></li>
									<?php
echo $plugin_menu;
		?>
								</ul>
							</li>
							<?php
}
	?>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<li>
							<a href="#" style="cursor:default;">
								<?php
echo object::getGlobalHtmlSummary();
	?>
							</a>
						</li>
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
						<?php $nbUpdate = update::nbNeedUpdate();
	$displayUpdate = ($nbUpdate > 0) ? '' : 'display : none;';?>
						<li>
							<a href="index.php?v=d&p=update">
								<span class="badge" id="span_nbUpdate"  title="{{Nombre de mises à jour}}" style="background-color : #c9302c;<?php echo $displayUpdate; ?>"><?php echo $nbUpdate; ?></span></a></li>
								<?php if (isConnect('admin')) {
		?>
									<li class="dropdown">
										<a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-cogs"></i><span class="caret"></span></a>
										<ul class="dropdown-menu">
											<li><a href="index.php?v=d&p=administration" tabindex="0"><i class="fa fa-wrench"></i> {{Configuration}}</a></li>
											<li><a href="index.php?v=d&p=backup"><i class="fa fa-floppy-o"></i> {{Sauvegardes}}</a></li>
											<li><a href="index.php?v=d&p=update"><i class="fa fa-refresh"></i> {{Centre de mise à jour}}</a></li>
											<li><a href="index.php?v=d&p=cron"><i class="fa fa-tasks"></i> {{Moteur de tâches}}</a></li>
											<li><a href="index.php?v=d&p=custom"><i class="fa fa-pencil-square-o"></i> {{Personnalisation avancée}}</a></li>
											<li role="separator" class="divider"></li>
											<li><a href="index.php?v=d&p=user"><i class="fa fa-users"></i> {{Utilisateurs}}</a></li>
										</ul>
									</li>
									<?php }
	?>
									<li class="dropdown">
										<a class="dropdown-toggle" data-toggle="dropdown" href="#">
											<i class="fa fa-user"></i>
											<span class="caret"></span>
										</a>
										<ul class="dropdown-menu">
											<li><a href="index.php?v=d&p=profils"><i class="fa fa-briefcase"></i> {{Profil}} <?php echo $_SESSION['user']->getLogin(); ?></a></li>
											<li class="divider"></li>
											<li><a href="index.php?v=m" class="noOnePageLoad"><i class="fa fa-mobile"></i> {{Version mobile}}</a></li>
											<li class="divider"></li>
											<li><a href="#" id="bt_jeedomAbout"><i class="fa fa-info-circle"></i> {{Version}} v<?php echo jeedom::version(); ?></a></li>
											<?php	if (jeedom::isCapable('sudo')) {
		echo '<li class="divider"></li>';
		echo '<li class="cursor"><a id="bt_rebootSystem" state="0"><i class="fa fa-repeat"></i> {{Redémarrer}}</a></li>';
		echo '<li class="cursor"><a id="bt_haltSystem" state="0"><i class="fa fa-power-off"></i> {{Eteindre}}</a></li>';

	}
	?>
											<li class="divider"></li>
											<li><a href="index.php?v=d&logout=1" class="noOnePageLoad"><i class="fa fa-sign-out"></i> {{Se déconnecter}}</a></li>
										</ul>
									</li>
									<li>
										<a id="bt_getHelpPage" class="cursor" data-plugin="<?php echo init('m'); ?>" data-page="<?php echo init('p'); ?>" title="{{Aide sur la page en cours}}"><i class="fa fa-question-circle" ></i></a>
									</li>
									<?php if (isConnect('admin')) {
		?>
										<li>
											<?php if (isset($plugin) && is_object($plugin) && $plugin->getIssue() != '') {
			?>
												<a target="_blank" href="<?php echo $plugin->getIssue() ?>" title="{{Envoyer un rapport de bug}}">
													<i class="fa fa-exclamation-circle" ></i>
												</a>
												<?php } else {?>
												<a class="bt_reportBug cursor" title="{{Envoyer un rapport de bug}}">
													<i class="fa fa-exclamation-circle" ></i>
												</a>
												<?php }?>
											</li>
											<?php }
	?>
											<li>
												<a href="#" style="cursor:default;">
													<span id="horloge"><?php echo date('H:i:s'); ?></span>
												</a>
											</li>
										</ul>
									</nav>
								</div>
							</header>
							<main class="container-fluid" id="div_mainContainer">
								<div style="display: none;width : 100%" id="div_alert"></div>
								<div id="div_pageContainer">
									<?php
try {
		if (!jeedom::isStarted()) {
			echo '<div class="alert alert-danger">{{Jeedom est en cours de démarrage veuillez patienter. La page se rechargera automatiquement une fois le démarrage terminé}}</div>';
		}
		if (isset($plugin) && is_object($plugin)) {
			include_file('desktop', $page, 'php', $plugin->getId());
		} else {
			include_file('desktop', $page, 'php');
		}
	} catch (Exception $e) {
		ob_end_clean();
		echo '<div class="alert alert-danger div_alert">';
		echo displayExeption($e);
		echo '</div>';
	} catch (Error $e) {
		ob_end_clean();
		echo '<div class="alert alert-danger div_alert">';
		echo displayExeption($e);
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
							<?php
}
?>
					</body>
					</html>
