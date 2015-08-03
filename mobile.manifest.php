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
	//'3rdparty/responsivevoices/responsivevoices.js',
);
if (file_exists(dirname(__FILE__) . '/mobile/custom/custom.js')) {
	$js_file[] = 'mobile/custom/custom.js';
}

$other_file = array(
	'core/php/icon.inc.php',
	'3rdparty/jquery.mobile/css/font-awesome.min.css',
	'3rdparty/jquery.mobile/jquery.mobile.min.css',
	'3rdparty/jquery.mobile/css/jquery.mobile.nativedroid.css',
	'3rdparty/jquery.mobile/css/jquery.mobile.nativedroid.dark.css',
	'3rdparty/jquery.mobile/css/jquery.mobile.nativedroid.light.css',
	'3rdparty/jquery.mobile/css/jquery.mobile.nativedroid.color.green.css',
	'3rdparty/jquery.mobile/css/jquery.mobile.nativedroid.color.yellow.css',
	'3rdparty/jquery.mobile/css/jquery.mobile.nativedroid.color.purple.css',
	'3rdparty/jquery.mobile/css/jquery.mobile.nativedroid.color.blue.css',
	'3rdparty/jquery.mobile/css/jquery.mobile.nativedroid.color.red.css',
	'3rdparty/jquery.mobile/css/fonts.css',
	'core/css/core.css',
	'3rdparty/jquery.utils/jquery.utils.css',
	'mobile/css/commun.css',
	'3rdparty/jquery.mobile/css/fonts/fontawesome-webfont.woff?v=4.1.0',
	'3rdparty/jquery.mobile/images/ajax-loader.gif',
	'3rdparty/jquery.mobile/css/fonts/roboto/Roboto-Thin-webfont.woff',
	'3rdparty/jquery.mobile/css/fonts/roboto/Roboto-Light-webfont.woff',
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
?>
CACHE MANIFEST

CACHE:
/socket.io/socket.io.js?1.2.1
<?php
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
	echo 'core/php/getJS.php?file=mobile/js/' . $file;
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

foreach (plugin::listPlugin(true) as $plugin) {
	if ($plugin->getMobile() != '') {
		foreach (ls('plugins/' . $plugin->getId() . '/mobile/js', '*.js') as $file) {
			echo "\n";
			if (file_exists(dirname(__FILE__) . '/plugins/' . $plugin->getId() . '/mobile/js/' . $file)) {
				echo '#' . md5_file(dirname(__FILE__) . '/plugins/' . $plugin->getId() . '/mobile/js/' . $file);
				echo "\n";
			}
			echo 'core/php/getJS.php?file=plugins/' . $plugin->getId() . '/mobile/js/' . $file . "\n";
		}
		foreach (ls('plugins/' . $plugin->getId() . '/mobile/html', '*.html') as $file) {
			echo "\n";
			if (file_exists(dirname(__FILE__) . '/plugins/' . $plugin->getId() . '/mobile/html/' . $file)) {
				echo '#' . md5_file(dirname(__FILE__) . '/plugins/' . $plugin->getId() . '/mobile/html/' . $file);
				echo "\n";
			}
			echo 'index.php?v=m&m=' . $plugin->getId() . '&p=' . substr($file, 0, -5) . "\n";
		}
	}
}
?>

NETWORK:
*

FALLBACK:
/ mobile/html/fallback.html
