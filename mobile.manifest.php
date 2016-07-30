<?php
header('Content-type: text/cache-manifest');
require_once dirname(__FILE__) . "/core/php/core.inc.php";

$js_file = array(
	'3rdparty/highstock/highcharts-more.js',
	'3rdparty/highstock/highstock.js',
	'3rdparty/highstock/themes/dark-blue.js',
	'3rdparty/highstock/themes/dark-green.js',
	'3rdparty/highstock/themes/dark-unica.js',
	'3rdparty/highstock/themes/gray.js',
	'3rdparty/highstock/themes/grid-light.js',
	'3rdparty/highstock/themes/grid.js',
	'3rdparty/highstock/themes/sand-signika.js',
	'3rdparty/highstock/themes/skies.js',
	'3rdparty/jquery/jquery.min.js',
	'3rdparty/jquery.mobile/jquery.mobile.min.js',
	'3rdparty/jquery.mobile/nativedroid2.js',
	'3rdparty/wow/wow.min.js',
	'3rdparty/waves/waves.min.js',
	'3rdparty/jquery.utils/jquery.utils.js',
	'3rdparty/jquery.ui/jquery-ui.min.js',
	'core/js/cmd.class.js',
	'core/js/private.class.js',
	'core/js/core.js',
	'core/js/eqLogic.class.js',
	'core/js/user.class.js',
	'core/js/history.class.js',
	'core/js/config.class.js',
	'core/js/jeedom.class.js',
	'core/js/object.class.js',
	'core/js/plugin.class.js',
	'core/js/view.class.js',
	'core/js/message.class.js',
	'core/js/scenario.class.js',
	'core/js/plan.class.js',
	'3rdparty/jquery.packery/jquery.packery.js',
);
if (file_exists(dirname(__FILE__) . '/mobile/custom/custom.js')) {
	$js_file[] = 'mobile/custom/custom.js';
}

$other_file = array(
	'core/php/icon.inc.php',
	'3rdparty/jquery.mobile/css/font-awesome.min.css',
	'3rdparty/jquery.mobile/jquery.mobile.min.css',
	'3rdparty/jquery.mobile/css/nativedroid2.css',
	'3rdparty/jquery.mobile/css/fonts.css',
	'3rdparty/jquery.mobile/css/flexboxgrid.min.css',
	'3rdparty/jquery.mobile/css/material-design-iconic-font.min.css',
	'3rdparty/waves/waves.min.css',
	'core/css/core.css',
	'3rdparty/jquery.utils/jquery.utils.css',
	'mobile/css/commun.css',
	'3rdparty/font-awesome/fonts/fontawesome-webfont.woff2?v=4.4.0',
	'3rdparty/font-awesome/css/font-awesome.min.css',
	'3rdparty/jquery.mobile/images/ajax-loader.gif',
	'core/img/logo-jeedom-petit-nom-couleur-128x128.png',
	'core/img/logo-jeedom-sans-nom-couleur-25x25.png',
	'3rdparty/jquery.mobile/css/fonts/fontawesome-webfont.woff2?v=4.3.0',
	'3rdparty/roboto/Roboto-Black.ttf',
	'3rdparty/roboto/Roboto-BlackItalic.ttf',
	'3rdparty/roboto/Roboto-Bold.ttf',
	'3rdparty/roboto/Roboto-BoldItalic.ttf',
	'3rdparty/roboto/Roboto-Light.ttf',
	'3rdparty/roboto/Roboto-LightItalic.ttf',
	'3rdparty/roboto/Roboto-Medium.ttf',
	'3rdparty/roboto/Roboto-MediumItalic.ttf',
	'3rdparty/roboto/Roboto-Regular.ttf',
	'3rdparty/roboto/Roboto-Thin.ttf',
	'3rdparty/roboto/Roboto-ThinItalic.ttf',
	'3rdparty/roboto/roboto.css',
	'3rdparty/jquery.mobile/css/fonts/roboto/Roboto-Medium-webfont.woff',

);
if (file_exists(dirname(__FILE__) . '/mobile/custom/custom.css')) {
	$other_file[] = 'mobile/custom/custom.css';
}

$root_dir = dirname(__FILE__) . '/core/css/icon/';
foreach (ls($root_dir, '*') as $dir) {
	if (is_dir($root_dir . $dir) && file_exists($root_dir . $dir . '/style.css')) {
		$other_file[] = 'core/css/icon/' . $dir . 'style.css';
		foreach (ls($root_dir . $dir . '/fonts', '*') as $font) {
			$other_file[] = 'core/css/icon/' . $dir . 'fonts/' . $font;
		}
	}
}

foreach (ls(dirname(__FILE__) . '/core/themes') as $dir) {
	if (is_dir(dirname(__FILE__) . '/core/themes/' . $dir . '/mobile')) {
		$other_file[] = 'core/themes/' . $dir . 'mobile/' . trim($dir, '/') . '.css';
		if (file_exists(dirname(__FILE__) . '/core/themes/' . $dir . 'mobile/' . trim($dir, '/') . '.js')) {
			$other_file[] = 'core/themes/' . $dir . 'mobile/' . trim($dir, '/') . '.js';
		}
	}
}
?>
CACHE MANIFEST

CACHE:
<?php
echo '#LANG : ' . translate::getLanguage();
foreach (plugin::listPlugin(true) as $plugin) {
	foreach (ls(dirname(__FILE__) . '/plugins/' . $plugin->getId() . '/core/template/mobile', '*') as $file) {
		if (is_dir(dirname(__FILE__) . '/plugins/' . $plugin->getId() . '/core/template/mobile/' . $file)) {
			foreach (ls(dirname(__FILE__) . '/plugins/' . $plugin->getId() . '/core/template/mobile/' . $file, '*') as $file2) {
				if (is_dir(dirname(__FILE__) . '/plugins/' . $plugin->getId() . '/core/template/mobile/' . $file . $file2)) {
					foreach (ls(dirname(__FILE__) . '/plugins/' . $plugin->getId() . '/core/template/mobile/' . $file . $file2, '*') as $file3) {
						if (strpos($file3, '.js') !== false) {
							$js_file[] = 'plugins/' . $plugin->getId() . '/core/template/mobile/' . $file . $file2 . $file3;
						} elseif (strpos($file3, '.css') !== false || strpos($file3, '.png') !== false || strpos($file3, '.jpg') !== false || strpos($file3, '.ttf') !== false || strpos($file3, '.woff') !== false) {
							$other_file[] = 'plugins/' . $plugin->getId() . '/core/template/mobile/' . $file . $file2 . $file3;
						}
					}
				} else if (strpos($file2, '.js') !== false) {
					$js_file[] = 'plugins/' . $plugin->getId() . '/core/template/mobile/' . $file . $file2;
				} elseif (strpos($file2, '.css') !== false || strpos($file2, '.png') !== false || strpos($file2, '.jpg') !== false || strpos($file2, '.ttf') !== false || strpos($file2, '.woff') !== false) {
					$other_file[] = 'plugins/' . $plugin->getId() . '/core/template/mobile/' . $file . $file2;
				}
			}
		} elseif (strpos($file, '.js') !== false) {
			$js_file[] = 'plugins/' . $plugin->getId() . '/core/template/mobile/' . $file;
		} elseif (strpos($file, '.css') !== false || strpos($file, '.png') !== false || strpos($file, '.jpg') !== false || strpos($file, '.ttf') !== false || strpos($file, '.woff') !== false) {
			$other_file[] = 'plugins/' . $plugin->getId() . '/core/template/mobile/' . $file;
		}
	}
	if ($plugin->getEventjs() == 1 && file_exists(dirname(__FILE__) . '/plugins/' . $plugin->getId() . '/mobile/js/event.js')) {
		$js_file[] = 'plugins/' . $plugin->getId() . '/mobile/js/event.js';
	}
	if ($plugin->getMobile() != '') {
		if (file_exists(dirname(__FILE__) . '/plugins/' . $plugin->getId() . '/doc/images/' . $plugin->getId() . '_icon.png')) {
			$other_file[] = 'plugins/' . $plugin->getId() . '/doc/images/' . $plugin->getId() . '_icon.png';
		}
		if (method_exists($plugin->getId(), 'mobileManifest')) {
			$plugin_id = $plugin->getId();
			try {
				$plugin_id::mobileManifest();
			} catch (Exception $e) {
				log::add($plugin_id, 'error', __('Erreur sur la fonction mobileManifest du plugin : ', __FILE__) . $e->getMessage());
			}
		}
		foreach (ls(dirname(__FILE__) . '/plugins/' . $plugin->getId() . '/mobile/js', '*.js') as $file) {
			echo "\n";
			if (file_exists(dirname(__FILE__) . '/plugins/' . $plugin->getId() . '/mobile/js/' . $file)) {
				echo '#' . md5_file(dirname(__FILE__) . '/plugins/' . $plugin->getId() . '/mobile/js/' . $file);
				echo "\n";
			}
			echo 'core/php/getJS.php?file=plugins/' . $plugin->getId() . '/mobile/js/' . $file . "\n";
		}
		foreach (ls(dirname(__FILE__) . '/plugins/' . $plugin->getId() . '/mobile/html', '*.html') as $file) {
			echo "\n";
			if (file_exists(dirname(__FILE__) . '/plugins/' . $plugin->getId() . '/mobile/html/' . $file)) {
				echo '#' . md5_file(dirname(__FILE__) . '/plugins/' . $plugin->getId() . '/mobile/html/' . $file);
				echo "\n";
			}
			echo 'index.php?v=m&ajax=1&p=' . substr($file, 0, -5) . '&m=' . $plugin->getId() . "\n";
		}
	}
}

foreach ($js_file as $file) {
	echo "\n";
	if (file_exists(dirname(__FILE__) . '/' . $file)) {
		echo '#' . md5_file(dirname(__FILE__) . '/' . $file);
		echo "\n";
	}
	echo 'core/php/getJS.php?file=' . $file;
	echo "\n";
	echo 'core/php/getJS.php?file=' . $file . '&md5=' . md5_file(dirname(__FILE__) . '/' . $file);
	echo "\n";
}
foreach ($other_file as $file) {
	echo "\n";
	if (file_exists(dirname(__FILE__) . '/' . $file)) {
		echo '#' . md5_file(dirname(__FILE__) . '/' . $file);
		echo "\n";
	}
	echo $file;
	echo "\n";
}
foreach (ls('mobile/js', '*.js') as $file) {
	echo "\n";
	if (file_exists(dirname(__FILE__) . '/mobile/js/' . $file)) {
		echo '#' . md5_file(dirname(__FILE__) . '/mobile/js/' . $file);
		echo "\n";
	}
	echo 'core/php/getResource.php?file=mobile/js/' . $file;
	echo "\n";
}
foreach (ls('mobile/html', '*.html') as $file) {
	echo "\n";
	if (file_exists(dirname(__FILE__) . '/mobile/html/' . $file)) {
		echo '#' . md5_file(dirname(__FILE__) . '/mobile/html/' . $file);
		echo "\n";
	}
	echo 'index.php?v=m&ajax=1&p=' . substr($file, 0, -5);
	echo "\n";
}

?>

NETWORK:
*

FALLBACK:
/ mobile/html/fallback.html
