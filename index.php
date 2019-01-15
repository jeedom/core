<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */
try {
	if (!file_exists(__DIR__ . '/core/config/common.config.php')) {
		header("location: install/setup.php");
	}

	if (!isset($_GET['v'])) {
		$useragent = (isset($_SERVER["HTTP_USER_AGENT"])) ? $_SERVER["HTTP_USER_AGENT"] : 'none';
		$getParams = (stristr($useragent, "Android") || strpos($useragent, "iPod") || strpos($useragent, "iPhone") || strpos($useragent, "Mobile") || strpos($useragent, "WebOS") || strpos($useragent, "mobile") || strpos($useragent, "hp-tablet"))
		? 'm' : 'd';
		foreach ($_GET AS $var => $value) {
			$getParams .= '&' . $var . '=' . $value;
		}
		$url = 'index.php?v=' . trim($getParams, '&');
		if (headers_sent()) {
			echo '<script type="text/javascript">';
			echo "window.location.href='$url';";
			echo '</script>';
		} else {
			exit(header('Location: ' . $url));
		}
		die();
	}
	require_once __DIR__ . "/core/php/core.inc.php";
	if (isset($_GET['v']) && $_GET['v'] == 'd') {
	    $translator = \Jeedom\Core\Translator\TranslatorFactory::build();
		if (isset($_GET['modal'])) {
			try {
				include_file('core', 'authentification', 'php');
				include_file('desktop', init('modal'), 'modal', init('plugin'));
			} catch (Exception $e) {
				ob_end_clean();
				echo '<div class="alert alert-danger div_alert">';
				echo $translator->exec(displayException($e), 'desktop/' . init('p') . '.php');
				echo '</div>';
			} catch (Error $e) {
				ob_end_clean();
				echo '<div class="alert alert-danger div_alert">';
				echo $translator->exec(displayException($e), 'desktop/' . init('p') . '.php');
				echo '</div>';
			}
		} elseif (isset($_GET['configure'])) {
			include_file('core', 'authentification', 'php');
			include_file('plugin_info', 'configuration', 'configuration', init('plugin'));
		} elseif (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
			try {
				$title = 'Jeedom';
				if (init('m') != '') {
					try {
						$plugin = plugin::byId(init('m'));
						if (is_object($plugin)) {
							$title = $plugin->getName() . ' - Jeedom';
						}
					} catch (Exception $e) {

					} catch (Error $e) {

					}
				} else if (init('p') != '') {
					$title = ucfirst(init('p')) . ' - ' . config::byKey('product_name');
				}
				echo '<script>';
				echo 'document.title = "' . $title . '"';
				echo '</script>';
				include_file('core', 'authentification', 'php');
				include_file('desktop', init('p'), 'php', init('m'));
			} catch (Exception $e) {
				ob_end_clean();
				echo '<div class="alert alert-danger div_alert">';
				echo $translator->exec(displayException($e), 'desktop/' . init('p') . '.php');
				echo '</div>';
			} catch (Error $e) {
				ob_end_clean();
				echo '<div class="alert alert-danger div_alert">';
				echo $translator->exec(displayException($e), 'desktop/' . init('p') . '.php');
				echo '</div>';
			}
		} else {
			include_file('desktop', 'index', 'php');
		}
	} elseif (isset($_GET['v']) && $_GET['v'] == 'm') {
		$_fn = 'index';
		$_type = 'html';
		$_plugin = '';
		if (isset($_GET['modal'])) {
			$_fn = init('modal');
			$_type = 'modalhtml';
			$_plugin = init('plugin');
		} elseif (isset($_GET['p']) && isset($_GET['ajax'])) {
			$_fn = $_GET['p'];
			$_plugin = isset($_GET['m']) ? $_GET['m'] : $_plugin;
		}
		include_file('mobile', $_fn, $_type, $_plugin);
	} else {
		echo "Erreur : veuillez contacter l'administrateur";
	}
} catch (Exception $e) {
	echo $e->getMessage();
}
