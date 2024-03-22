<?php
if (init('rescue', 0) == 1 && !in_array(init('p'), array('custom', 'backup', 'cron', 'connection', 'log', 'database', 'editor', 'system'))) {
	$_GET['p'] = 'system';
}
include_file('core', 'authentification', 'php');
global $JEEDOM_INTERNAL_CONFIG;
global $jeedom_theme;
$jeedom_theme = jeedom::getThemeConfig();
$configs = array_merge($jeedom_theme, config::byKeys(array('language', 'jeedom::firstUse', 'debugFrontEnd', 'debugFrontEndVerbose', 'scenario::disableAutocomplete')));
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
		} else	if ($homePage[1] == 'plan3d' && $_SESSION['user']->getOptions('defaultPlanFullScreen3d') == 1) {
			$homeLink .= '&fullscreen=1';
		}
	} else {
		$homeLink = 'index.php?v=d&p=dashboard';
	}
}
if (init('rescue', 0) == 1) {
	$homeLink = 'index.php?v=d&p=system&rescue=1';
}
$title = config::byKey('product_name');
if (init('p') == '' && isConnect()) {
	redirect($homeLink);
}
$page = 'dashboard';
if (isConnect() && init('p') != '') {
	$page = secureXSS(init('p'));
	$title = ucfirst($page) . ' - ' . $title;
}
$plugin_menu = '';
$panel_menu = '';
if (init('rescue', 0) == 0) {
	$plugins_list = plugin::listPlugin(true, true);
	$eventjs_plugin = array();
	$panelMenuArray = array();
	$categories = array();
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
			$plugins = array();
			foreach ($category as $pluginList) {
				array_push($plugins, array($pluginList->getName(), $pluginList));
			}
			sort($plugins);
			array_push($categories, array($name, $icon, $plugins));
		}
		sort($categories);
		foreach ($categories as $cat) {
			$name = $cat[0];
			$icon = $cat[1];
			$plugin_menu .= '<li><a class="submenu"><i class="fas ' . $icon . '"></i> ' . $name;
			$plugin_menu .= '<label class="drop-icon" for="drop-' . $name . '"><i class="fas fa-chevron-down fa-2x"></i></label>';
			$plugin_menu .= '</a>';
			$plugin_menu .= '<input type="checkbox" id="drop-' . $name . '">';
			$plugin_menu .= '<ul>';
			$plugins = $cat[2];
			foreach ($plugins as $pluginAr) {
				$pluginObj = $pluginAr[1];
				if ($pluginObj->getId() == init('m')) {
					$plugin = $pluginObj;
					$title = $pluginObj->getName() . ' - ' . config::byKey('product_name');
				}
				$plugin_menu .= '<li><a href="index.php?v=d&m=' . $pluginObj->getId() . '&p=' . $pluginObj->getIndex() . '"><img class="img-responsive" src="' . $pluginObj->getPathImgIcon() . '" /> ' . $pluginObj->getName() . '</a></li>';
				if ($pluginObj->getDisplay() != '' && config::byKey('displayDesktopPanel', $pluginObj->getId(), 0) != 0) {
					$panelLi = '<li><a href="index.php?v=d&m=' . $pluginObj->getId() . '&p=' . $pluginObj->getDisplay() . '"><img class="img-responsive" src="' . $pluginObj->getPathImgIcon() . '" /> ' . $pluginObj->getName() . '</a></li>';
					$panelMenuArray[] = array('name' => $pluginObj->getName(), 'menu' => $panelLi);
				}
				if ($pluginObj->getEventjs() == 1) {
					$eventjs_plugin[] = $pluginObj->getId();
				}
			}
			$plugin_menu .= '</ul>';
			$plugin_menu .= '</li>';
		}
	}
	array_multisort($panelMenuArray);
	foreach ($panelMenuArray as $item) {
		$panel_menu .= $item['menu'];
	}
}

$allUserViews = [];
foreach ((view::all()) as $view_menu) {
	if ($view_menu->hasRight('r')) array_push($allUserViews, $view_menu);
}
$allPlanHeaderViews = [];
foreach ((planHeader::all()) as $plan_menu) {
	if ($plan_menu->hasRight('r')) array_push($allPlanHeaderViews, $plan_menu);
}
$allPlan3dHeaderViews = [];
foreach ((plan3dHeader::all()) as $plan3d_menu) {
	if ($plan3d_menu->hasRight('r')) array_push($allPlan3dHeaderViews, $plan3d_menu);
}

global $homeLogoSrc;
function setTheme() {
	global $jeedom_theme, $homeLogoSrc;
	$homeLogoSrc = config::byKey('logo_light');
	$dataNoChange = false;
	$themeCss = '<link id="jeedom_theme_currentcss" href="core/themes/core2019_Light/desktop/core2019_Light.css?md5=' . md5(__DIR__ . '/../../core/themes/core2019_Light/desktop/core2019_Light.css') . '" rel="stylesheet">';
	$themeJs = 'core2019_Light/desktop/core2019_Light';

	$themeDefinition = $jeedom_theme['current_desktop_theme'];
	if (isset($_COOKIE['currentTheme'])) {
		if ($_COOKIE['currentTheme'] == 'alternate') {
			$themeDefinition = $jeedom_theme['jeedom_theme_alternate'];
			$dataNoChange = true;
		} else {
			$themeDefinition = $jeedom_theme['jeedom_theme_main'];
			$dataNoChange = true;
		}
	}
	if (init('rescue', 0) == 0) {
		if (is_dir(__DIR__ . '/../../core/themes/' . $themeDefinition . '/desktop') && file_exists(__DIR__ . '/../../core/themes/' . $themeDefinition . '/desktop/' . $themeDefinition . '.css')) {
			$themeCss = '<link id="jeedom_theme_currentcss" href="core/themes/' . $themeDefinition . '/desktop/' . $themeDefinition . '.css?md5=' . md5(__DIR__ . '/../../core/themes/' . $themeDefinition . '/desktop/' . $themeDefinition . '.css') . '" rel="stylesheet">';
			if ($dataNoChange) $themeCss = str_replace('rel="stylesheet"', 'rel="stylesheet" data-nochange="1"', $themeCss);
		}
	}
	$jeedom_theme['currentTheme'] = $themeDefinition;
	if (substr($themeDefinition, -5) == '_Dark') {
		$homeLogoSrc = config::byKey('logo_dark');
	}
	echo $themeCss;
	if (!isset($jeedom_theme['interface::advance::enable']) || !isset($jeedom_theme['widget::shadow']) || $jeedom_theme['interface::advance::enable'] == 0 || $jeedom_theme['widget::shadow'] == 0) {
		$shdPath = __DIR__ . '/../../core/themes/' . $themeDefinition . '/desktop/shadows.css';
		if (file_exists($shdPath)) {
			echo '<link id="shadows_theme_css" href="core/themes/' . $themeDefinition . '/desktop/shadows.css" rel="stylesheet">';
		}
	}
}

$loadJquery = true;
if (config::byKey('core::jqueryless') == 1) $loadJquery = false;
?>

<!DOCTYPE html>
<html lang="en-US" translate="no" class="notranslate">

<head>
	<meta charset="utf-8">
	<meta name="google" content="notranslate" />
	<title><?php echo $title; ?></title>
	<link rel="shortcut icon" href="<?php echo $configs['product_icon'] ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">

	<?php
	include_file('coreDOM', 'dom.utils', 'js');
	include_file('coreDOM', 'dom.ui', 'js');
	include_file('core', 'icon.inc', 'php');
	include_file('3rdparty', 'roboto/roboto', 'css');
	include_file('3rdparty', 'camingocode/camingocode', 'css');
	include_file('3rdparty', 'text-security/text-security-disc', 'css');

	if ($loadJquery) include_file('3rdparty', 'jquery/jquery.min', 'js');
	if ($loadJquery) include_file('3rdparty', 'jquery.utils/jquery.utils', 'js');

	include_file('core', 'jeedom', 'class.js');
	?>
	<script>
		jeeFrontEnd.jeedomVersion = '<?php echo jeedom::version() ?>';
		jeeFrontEnd.coreBranch = '<?php echo config::byKey('core::branch') ?>';
		jeeFrontEnd.hardware_name = '<?php echo config::byKey('hardware_name') ?>';
		jeeFrontEnd.clientDatetime = new Date();
		jeeFrontEnd.clientServerDiffDatetime = (<?php echo microtime(TRUE); ?> * 1000) - jeeFrontEnd.clientDatetime.getTime();
		jeeFrontEnd.serverTZoffsetMin = <?php echo getTZoffsetMin() ?>;
		jeeFrontEnd.serverDatetime = <?php echo getmicrotime(); ?>;
		JEEDOM_PRODUCT_NAME = '<?php echo $configs['product_name'] ?>';
		JEEDOM_AJAX_TOKEN = '';
	</script>
	<?php
	include_file('core', 'core', 'js');
	include_file('core', 'js.inc', 'php');

	include_file('desktop', 'bootstrap', 'css');
	if ($loadJquery) {
		include_file('3rdparty', 'bootstrap/bootstrap.min', 'js');

		//ui-dialog replaced by jeeDialog, still sortable and resizable
		include_file('3rdparty', 'jquery.ui/jquery-ui.min', 'js');
		include_file('3rdparty', 'jquery.ui/jquery-ui-bootstrap/jquery-ui', 'css');
		include_file('3rdparty', 'jquery.ui-touch-punch/jquery.ui.touch-punch.min', 'js');

		//Deprecated 4.4, keep for plugins
		include_file('3rdparty', 'bootbox/bootbox.min', 'js');
		include_file('3rdparty', 'jquery.fileupload/jquery.ui.widget', 'js');
		include_file('3rdparty', 'jquery.fileupload/jquery.iframe-transport', 'js');
		include_file('3rdparty', 'jquery.fileupload/jquery.fileupload', 'js');
		include_file('3rdparty', 'jquery.contextMenu/jquery.contextMenu.min', 'css');
		include_file('3rdparty', 'jquery.contextMenu/jquery.contextMenu.min', 'js');
		include_file('3rdparty', 'jquery.at.caret/jquery.at.caret.min', 'js');

		//Table column sorter:
		include_file('3rdparty', 'jquery.tablesorter/theme.bootstrap.min', 'css');
		include_file('3rdparty', 'jquery.tablesorter/jquery.tablesorter.min', 'js');
		include_file('3rdparty', 'jquery.tablesorter/jquery.tablesorter.widgets.min', 'js');
		include_file('3rdparty', 'jquery.tablesorter/parsers/parser-input-select.min', 'js');

		//Cron helper:
		//include_file('3rdparty', 'jquery.cron/jquery.cron.min', 'js');
		//include_file('3rdparty', 'jquery.cron/jquery.cron', 'css');

		//Deprecated Core css
		include_file('desktop', 'deprecated.main', 'css');
	}

	//jQuery less libs:
	include_file('3rdparty', 'packery/packery.pkgd', 'js');
	include_file('3rdparty', 'packery/draggabilly.pkgd', 'js');

	include_file('3rdparty', 'codemirror/lib/codemirror', 'js');
	include_file('3rdparty', 'codemirror/lib/codemirror', 'css');
	include_file('3rdparty', 'codemirror/addon/edit/matchbrackets', 'js');
	include_file('3rdparty', 'codemirror/mode/htmlmixed/htmlmixed', 'js');
	include_file('3rdparty', 'codemirror/mode/clike/clike', 'js');
	include_file('3rdparty', 'codemirror/mode/php/php', 'js');
	include_file('3rdparty', 'codemirror/mode/xml/xml', 'js');
	include_file('3rdparty', 'codemirror/mode/javascript/javascript', 'js');
	include_file('3rdparty', 'codemirror/mode/css/css', 'js');
	include_file('3rdparty', 'codemirror/mode/python/python', 'js');

	include_file('3rdparty', 'highstock/highstock', 'js');
	include_file('3rdparty', 'highstock/highcharts-more', 'js');
	//include_file('3rdparty', 'highstock/modules/solid-gauge', 'js');
	include_file('3rdparty', 'highstock/modules/exporting', 'js');
	//include_file('3rdparty', 'highstock/modules/offline-exporting', 'js');
	//include_file('3rdparty', 'highstock/modules/export-data', 'js');

	include_file('3rdparty', 'nouislider/nouislider', 'js');
	include_file('3rdparty', 'nouislider/nouislider', 'css');
	include_file('3rdparty', 'autosize/autosize.min', 'js');
	include_file('3rdparty', 'moment/moment-with-locales.min', 'js');

	//New $less libs
	include_file('coreDOM', 'jeeCron/jeeCron', 'js');
	include_file('coreDOM', 'Vanilla-DataTables/Vanilla-DataTables', 'css');
	include_file('coreDOM', 'Vanilla-DataTables/Vanilla-DataTables', 'js');
	include_file('3rdparty', 'isPin/ispin', 'css');
	include_file('3rdparty', 'isPin/ispin', 'js');
	include_file('3rdparty', 'sortable/sortable', 'js');
	include_file('3rdparty', 'popper/popper.min', 'js');
	include_file('3rdparty', 'tippy/tippy.min', 'js');
	include_file('3rdparty', 'flatpickr/flatpickr.min', 'css');
	include_file('3rdparty', 'flatpickr/flatpickr.dark', 'css');
	include_file('3rdparty', 'flatpickr/flatpickr.min', 'js');
	include_file('3rdparty', 'flatpickr/l10n/fr', 'js');
	include_file('3rdparty', 'flatpickr/l10n/es', 'js');

	//set theme before loading utils:
	include_file('desktop', 'coreWidgets', 'css');
	include_file('desktop', 'desktop.main', 'css');
	setTheme();
	sendVarToJS([
		'jeeFrontEnd.language' => $configs['language'],
		'jeeFrontEnd.scenario_autocomplete' => $configs['scenario::disableAutocomplete'],
		'jeedom.theme' => $jeedom_theme
	]);
	include_file('desktop/common', 'utils', 'js');

	if (init('report') == 1) {
		include_file('desktop', 'report', 'css');
	}
	if (init('rescue', 0) == 0 && $configs['enableCustomCss'] == 1) {
		if (file_exists(__DIR__ . '/../custom/custom.css')) {
			include_file('desktop', '', 'custom.css');
		}
		if (file_exists(__DIR__ . '/../custom/custom.js')) {
			include_file('desktop', '', 'custom.js');
		}
	}
	?>
	<script src="3rdparty/snap.svg/snap.svg-min.js"></script>
</head>

<body data-uimode="desktop">
	<div id="backgroundforJeedom">
		<div id="top"></div>
		<div id="bottom"></div>
	</div>
	<div id="div_jeedomLoading" style="display:none;">
		<div class="loadingBack"></div>
		<div class="loadingSpinner"></div>
	</div>
	<?php
	if (!isConnect()) {
		include_file('desktop', 'connection', 'php');
	} else {
		sendVarToJS([
			'jeeFrontEnd.userProfils' => $_SESSION['user']->getOptions(),
			'user_id' => $_SESSION['user']->getId(),
			'user_isAdmin' => isConnect('admin'),
			'user_login' => $_SESSION['user']->getLogin(),
			'jeeFrontEnd.jeedom_firstUse' => $configs['jeedom::firstUse']
		]);
		if (isset($eventjs_plugin) && count($eventjs_plugin) > 0) {
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
			<header id="jeedomMenuBar" class="navbar navbar-fixed-top navbar-default reportModeHidden shadowed">
				<div class="container-fluid">
					<div class="navbar-header">
						<a class="navbar-brand" href="<?php echo $homeLink; ?>"><img id="homeLogoImg" src="<?php echo $homeLogoSrc; ?>" onclick="domUtils.showLoading()" height="30px"></a>
						<button id="mainMenuHamburgerToggle" class="navbar-toggle cursor" type="button" data-toggle="collapse" data-target=".navbar-collapse">
							<span class="sr-only">{{Toggle navigation}}</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
					</div>
					<nav class="navbar-collapse collapse">
						<ul class="nav navbar-nav">

							<li class="cursor">
								<a>
									<i class="fas fa-home"></i> <span class="hidden-sm hidden-md">{{Accueil}}</span> <b class="caret"></b>
									<label class="drop-icon" for="drop-home"><i class="fas fa-chevron-down fa-2x"></i></label>
								</a>
								<input type="checkbox" id="drop-home">
								<ul>

									<li><a href="index.php?v=d&p=overview"><i class="fab fa-hubspot"></i> {{Synthèse}}</a></li>

									<li>
										<a id="bt_gotoDashboard" class="submenu">
											<i class="fas fa-tachometer-alt"></i> {{Dashboard}}
											<label class="drop-icon" for="drop-dashboard"><i class="fas fa-chevron-down fa-2x"></i></label>
										</a>
										<input type="checkbox" id="drop-dashboard">
										<ul>
											<?php
											$echo = '';
											foreach ((jeeObject::buildTree(null, false)) as $object_li) {
												$echo .= '<li><a href="index.php?v=d&p=dashboard&object_id=' . $object_li->getId() . '">' . str_repeat('&nbsp;&nbsp;', $object_li->getConfiguration('parentNumber')) . $object_li->getHumanName(true) . '</a></li>';
											}
											echo $echo;
											?>
										</ul>
									</li>

									<li>
										<a id="bt_gotoView" class="<?php if (count($allUserViews) > 0) echo 'submenu'; ?>">
											<i class="far fa-image"></i> {{Vue}}
											<?php if (count($allUserViews) > 0) echo '<label class="drop-icon" for="drop-view"><i class="fas fa-chevron-down fa-2x"></i></label>'; ?>
										</a>
										<input type="checkbox" id="drop-view">
										<?php
										$echo = '';
										foreach ($allUserViews as $view_menu) {
											$echo .= '<li><a href="index.php?v=d&p=view&view_id=' . $view_menu->getId() . '">' . trim($view_menu->getDisplay('icon', '<i class="far fa-image"></i>')) . ' ' . $view_menu->getName() . '</a></li>';
										}
										if ($echo != '') {
											echo '<ul>' . $echo . '</ul>';
										}
										?>
									</li>

									<li>
										<a id="bt_gotoPlan" class="<?php if (count($allPlanHeaderViews) > 0) echo 'submenu'; ?>">
											<i class="fas fa-paint-brush"></i> {{Design}}
											<?php if (count($allPlanHeaderViews) > 0) echo '<label class="drop-icon" for="drop-design"><i class="fas fa-chevron-down fa-2x"></i></label>'; ?>
										</a>
										<input type="checkbox" id="drop-design">
										<?php
										$echo = '';
										foreach ($allPlanHeaderViews as $plan_menu) {
											$echo .= '<li><a href="index.php?v=d&p=plan&plan_id=' . $plan_menu->getId() . '">' . trim($plan_menu->getConfiguration('icon', '<i class="fas fa-paint-brush"></i>') . ' ' . $plan_menu->getName()) . '</a></li>';
										}
										if ($echo != '') {
											echo '<ul>' . $echo . '</ul>';
										}
										?>
									</li>

									<li>
										<a id="bt_gotoPlan3d" class="<?php if (count($allPlan3dHeaderViews) > 0) echo 'submenu'; ?>">
											<i class="fas fa-cubes"></i> {{Design 3D}}
											<?php if (count($allPlan3dHeaderViews) > 0) echo '<label class="drop-icon" for="drop-design3d"><i class="fas fa-chevron-down fa-2x"></i></label>'; ?>
										</a>
										<input type="checkbox" id="drop-design3d">
										<?php
										$echo = '';
										foreach ($allPlan3dHeaderViews as $plan3d_menu) {
											$echo .= '<li><a href="index.php?v=d&p=plan3d&plan3d_id=' . $plan3d_menu->getId() . '">' . trim($plan3d_menu->getConfiguration('icon') . ' ' . $plan3d_menu->getName()) . '</a></li>';
										}
										if ($echo != '') {
											echo '<ul>' . $echo . '</ul>';
										}
										?>
									</li>
									<?php echo $panel_menu; ?>
								</ul>
							</li>

							<li class="cursor">
								<a>
									<i class="fas fa-stethoscope"></i> <span class="hidden-sm hidden-md">{{Analyse}}</span> <b class="caret"></b>
									<label class="drop-icon" for="drop-analysis"><i class="fas fa-chevron-down fa-2x"></i></label>
								</a>
								<input type="checkbox" id="drop-analysis">
								<ul>
									<?php if (isConnect('admin')) { ?>
										<li><a href="index.php?v=d&p=log"><i class="far fa-file"></i> {{Logs}}</a></li>
										<li><a id="bt_showEventInRealTime"><i class="fas fa-tachometer-alt"></i> {{Temps réel}}</a></li>
										<li><a href="index.php?v=d&p=eqAnalyse"><i class="fas fa-battery-full"></i> {{Equipements}}</a></li>
										<li><a href="index.php?v=d&p=display"><i class="fas fa-th"></i> {{Résumé domotique}}</a></li>
									<?php } ?>
									<li class="divider"></li>
									<li><a href="index.php?v=d&p=timeline"><i class="far fa-clock"></i> {{Timeline}}</a></li>
									<li><a href="index.php?v=d&p=history"><i class="fas fa-chart-line"></i> {{Historique}}</a></li>
									<?php if (isConnect('admin')) { ?>
										<li><a href="index.php?v=d&p=report"><i class="far fa-newspaper"></i> {{Rapport}}</a></li>
										<li class="divider"></li>
										<li><a href="index.php?v=d&p=health"><i class="fas fa-medkit"></i> {{Santé}}</a></li>
									<?php } ?>
								</ul>
							</li>

							<?php if (isConnect('admin')) { ?>
								<li class="cursor">
									<a>
										<i class="fas fa-wrench"></i> <span class="hidden-sm hidden-md">{{Outils}}</span> <b class="caret"></b>
										<label class="drop-icon" for="drop-tools"><i class="fas fa-chevron-down fa-2x"></i></label>
									</a>
									<input type="checkbox" id="drop-tools">
									<ul>
										<li><a href="index.php?v=d&p=object"><i class="far fa-object-group"></i> {{Objets}}</a></li>
										<li><a href="index.php?v=d&p=scenario"><i class="fas fa-cogs"></i> {{Scénarios}}</a></li>
										<li><a href="index.php?v=d&p=interact"><i class="far fa-comments"></i> {{Interactions}}</a></li>
										<li><a href="index.php?v=d&p=widgets"><i class="fas fa-camera-retro"></i> {{Widgets}}</a></li>
										<li><a href="index.php?v=d&p=types"><i class="fas fa-puzzle-piece"></i> {{Types d'équipement}}</a></li>
										<li role="separator" class="divider"></li>
										<li><a id="bt_showNoteManager"><i class="fas fa-sticky-note"></i> {{Notes}}</a></li>
										<li><a id="bt_showExpressionTesting"><i class="fas fa-check"></i> {{Testeur d'expressions}}</a></li>
										<li><a id="bt_showDatastoreVariable"><i class="fas fa-eye"></i> {{Variables}}</a></li>
										<li><a id="bt_showSearching"><i class="fas fa-search"></i> {{Recherche}}</a></li>
										<li><a href="index.php?v=d&p=replace"><i class="fas fa-random warning"></i> {{Remplacer}}</a></li>
									</ul>
								</li>
							<?php } ?>

							<?php if (isConnect('admin')) { ?>
								<li class="cursor">
									<a>
										<i class="fas fa-tasks"></i> <span class="hidden-sm hidden-md">{{Plugins}}</span> <b class="caret"></b>
										<label class="drop-icon" for="drop-plugins"><i class="fas fa-chevron-down fa-2x"></i></label>
									</a>
									<input type="checkbox" id="drop-plugins">
									<ul>
										<li><a href="index.php?v=d&p=plugin"><i class="fas fa-tags"></i> {{Gestion des plugins}}</a></li>
										<li role="separator" class="divider"></li>
										<?php echo $plugin_menu; ?>
									</ul>
								</li>
							<?php } ?>

							<li class="cursor">
								<a>
									<i class="fas fa-cog"></i> <span class="hidden-sm hidden-md">{{Réglages}}</span> <b class="caret"></b>
									<label class="drop-icon" for="drop-settings"><i class="fas fa-chevron-down fa-2x"></i></label>
								</a>
								<input type="checkbox" id="drop-settings">
								<ul>
									<?php if (isConnect('admin')) { ?>
										<li>
											<a class="submenu">
												<i class="fas fa-cog"></i> {{Système}}
												<label class="drop-icon" for="drop-system"><i class="fas fa-chevron-down fa-2x"></i></label>
											</a>
											<input type="checkbox" id="drop-system">
											<ul>
												<li><a href="index.php?v=d&p=administration" tabindex="0"><i class="fas fa-wrench"></i> {{Configuration}}</a></li>
												<li><a href="index.php?v=d&p=backup"><i class="fas fa-save"></i> {{Sauvegardes}}</a></li>
												<li><a href="index.php?v=d&p=update"><i class="fas fa-sync-alt"></i> {{Centre de mise à jour}}</a></li>
												<li><a href="index.php?v=d&p=user"><i class="fas fa-users"></i> {{Utilisateurs}}</a></li>
												<li class="divider"></li>
												<li><a href="index.php?v=d&p=cron"><i class="fas fa-tasks warning"></i> {{Moteur de tâches}}</a></li>
												<li><a href="index.php?v=d&p=editor&type=custom"><i class="fas fa-pencil-alt warning"></i> {{Personnalisation avancée}}</a></li>
												<?php if (isConnect('admin')) {
													echo '<li class="cursor"><a href="index.php?v=d&p=editor"><i class="fas fa-folder-open warning"></i> {{Editeur de fichiers}}</a></li>';
												} ?>
												<li class="divider"></li>
												<?php if (jeedom::isCapable('sudo') && isConnect('admin')) {
													echo '<li class="cursor"><a href="index.php?v=d&p=reboot"><i class="fas fa-redo"></i> {{Redémarrer}}</a></li>';
													echo '<li class="cursor"><a href="index.php?v=d&p=shutdown"><i class="fas fa-power-off"></i> {{Eteindre}}</a></li>';
												} ?>
											</ul>
										</li>
									<?php } ?>
									<li><a href="index.php?v=d&p=profils"><i class="fas fa-briefcase"></i> {{Préférences}}</a></li>
									<li class="divider"></li>
									<?php if ($jeedom_theme['jeedom_theme_main'] != $jeedom_theme['jeedom_theme_alternate']) { ?>
										<li><a id="bt_switchTheme"><i class="fas fa-adjust"></i> {{Thème alternatif}}</a></li>
									<?php } ?>
									<li><a href="index.php?v=m" class="noOnePageLoad"><i class="fas fa-mobile"></i> {{Version mobile}}</a></li>
									<li class="divider"></li>
									<?php $mbState = config::byKey('mbState'); ?>
									<?php if (isConnect('admin')) {
										if ($mbState == 0) { ?>
											<li>
												<?php if (isset($plugin) && is_object($plugin) && $plugin->getIssue() != '') { ?>
													<a target="_blank" href="<?php echo $plugin->getIssue() ?>"><i class="fas fa-exclamation-circle"></i> {{Rapport de bug}}</a>
												<?php } else { ?>
													<a class="bt_reportBug"><i class="fas fa-exclamation-circle"></i> {{Demande de support}}</a>
												<?php } ?>
											</li>
									<?php }
									} ?>
									<li><a href="index.php?v=d&logout=1" class="noOnePageLoad"><i class="fas fa-sign-out-alt"></i> {{Se déconnecter}}</a></li>
									<li class="nocursor"><a class="disabled"><i class="fas fa-user"></i> <?php echo $_SESSION['user']->getLogin(); ?></a></li>
									<?php
									if ($mbState == 0) { ?>
										<li><a id="bt_jeedomAbout"><i class="fas fa-info-circle"></i> {{Version}} <?php echo jeedom::version(); ?></a></li>
									<?php } else { ?>
										<li><a><i class="fas fa-info-circle"></i> {{Version}} <?php echo jeedom::version(); ?></a></li>
									<?php } ?>
								</ul>
							</li>
						</ul>
						<ul class="nav navbar-nav navbar-right">
							<?php
							$nbMessage = message::nbMessage();
							$displayMessage = ($nbMessage > 0) ? '' : 'display : none;'; ?>
							<li>
								<a id="bt_messageModal">
									<span class="badge btn btn-warning" id="span_nbMessage" title="{{Nombre de messages}}" style="<?php echo $displayMessage; ?>">
										<?php echo $nbMessage; ?>
									</span>
								</a>
							</li>
							<li>
								<a id="bt_jsErrorModal" style="display:none;">
									<i class="fas fa-exclamation-triangle" title="{{Erreur Javascript}}"></i>
								</a>
							</li>
							<?php if (isConnect('admin')) {
								$nbUpdate = update::nbNeedUpdate();
								$displayUpdate = ($nbUpdate > 0) ? '' : 'display : none;'; ?>
								<li>
									<a href="index.php?v=d&p=update" id="bt_nbUpdateNavbar">
										<span class="badge btn btn-danger" id="span_nbUpdate" title="{{Nombre de mises à jour}}" style="<?php echo $displayUpdate; ?>"><?php echo $nbUpdate; ?></span></a>
								</li>
							<?php } ?>
							<li class="hidden-sm navTime">
								<a href="index.php?v=d&p=timeline">
									<span id="horloge"><?php echo date('H:i:s'); ?></span>
								</a>
								<a id="configName">
									<span class="cmdName"><?php echo config::byKey('name'); ?></span>
								</a>
							</li>
							<?php
							$mbState = config::byKey('mbState');
							if ($mbState == 0) {
								if (config::byKey('doc::base_url', 'core') != '') { ?>
									<li class="hidden-sm">
										<a id="bt_getHelpPage" class="cursor" data-plugin="<?php echo init('m'); ?>" data-page="<?php echo init('p'); ?>" title="{{Aide sur la page en cours}}"><i class="fas fa-question-circle"></i></a>
									</li>
							<?php }
							} ?>
						</ul>
					</nav>
					<div id="summaryGlobalMain"><?php echo jeeObject::getGlobalHtmlSummary(); ?></div>
				</div>
			</header>
		<?php } ?>
		<?php if (init('rescue', 0) == 1) { ?>
			<header id="jeedomMenuBar" class="navbar navbar-fixed-top navbar-default reportModeHidden">
				<div class="container-fluid">
					<div class="navbar-header">
						<a class="navbar-brand" href="<?php echo $homeLink; ?>">
							<img src="core/img/logo-jeedom-grand-nom-couleur.svg" height="30" style="position: relative; top:-5px;" />
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
							<li><a href="index.php?v=d&p=editor&type=custom&rescue=1"><i class="fas fa-pen-square"></i> {{Personnalisation}}</a></li>
							<li><a href="index.php?v=d&p=backup&rescue=1"><i class="far fa-save"></i> {{Sauvegarde}}</a></li>
							<li><a href="index.php?v=d&p=cron&rescue=1"><i class="fas fa-tasks"></i> {{Moteur de tâches}}</a></li>
							<li><a href="index.php?v=d&p=log&rescue=1"><i class="far fa-file"></i> {{Log}}</a></li>
							<li><a class="disabled">Jeedom v<?php echo jeedom::version(); ?></a></li>
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
						echo '<div class="alert alert-danger">' . config::byKey('product_name') . ' {{est en cours de démarrage, veuillez attendre 5min et rafraîchir la page.}}</div>';
					}
					if (isset($plugin) && is_object($plugin)) {
						include_file('desktop', $page, 'php', $plugin->getId());
					} else {
						include_file('desktop', $page, 'php');
					}
				} catch (Exception $e) {
					if ($_GET['m'] == 'mobile' && $_GET['p'] == 'panelMenuCustom') {
						echo "Aucun menu custom";
					} else {
						ob_end_clean();
						echo '<div class="alert alert-danger div_alert">';
						echo displayException($e);
						echo '</div>';
					}
				} catch (Error $e) {
					ob_end_clean();
					echo '<div class="alert alert-danger div_alert">';
					echo displayException($e);
					echo '</div>';
				}
				?>
			</div>
		</main>
	<?php
	}
	?>
	<?php if (init('report') == 1 && init('delay', -1) != -1 && is_numeric(init('delay'))) { ?>
		<iframe src='/core/php/sleep.php?delay=<?php echo init('delay') ?>' width=0 height=0></iframe>
	<?php } ?>

	<div id="md_modal"></div>
	<div id="md_modal2"></div>
	<div id="md_modal3"></div>

</body>

</html>
