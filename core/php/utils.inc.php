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

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

function include_file($_folder, $_fn, $_type, $_plugin = '') {
	$type = '';
	if ($_folder == '3rdparty') {
		$_folder = $_folder;
		$_fn = $_fn . '.' . $_type;
		$path = dirname(__FILE__) . "/../../$_folder/$_fn";
		if ($_type == 'css') {
			$type = 'css';
		} else if ($_type == 'js') {
			$type = 'js';
		} else {
			$type = 'php';
		}
	} else {
		if ($_type == 'class') {
			$_folder .= '/class';
			$_fn = $_fn . '.class.php';
			$type = 'php';
		}
		if ($_type == 'com') {
			$_folder .= '/com';
			$_fn = $_fn . '.com.php';
			$type = 'php';
		}
		if ($_type == 'config') {
			$_folder .= '/config';
			$_fn = $_fn . '.config.php';
			$type = 'php';
		}
		if ($_type == 'modal') {
			$_folder = $_folder . '/modal';
			$_fn = $_fn . '.php';
			$type = 'php';
		}
		if ($_type == 'php') {
			$_folder = $_folder . '/php';
			$_fn = $_fn . '.php';
			$type = 'php';
		}
		if ($_type == 'css') {
			$_folder = $_folder . '/css';
			$_fn = $_fn . '.css';
			$type = 'css';
		}
		if ($_type == 'js') {
			$_folder = $_folder . '/js';
			$_fn = $_fn . '.js';
			$type = 'js';
		}
		if ($_type == 'class.js') {
			$_folder = $_folder . '/js';
			$_fn = $_fn . '.class.js';
			$type = 'js';
		}
		if ($_type == 'custom.js') {
			$_folder = $_folder . '/custom';
			$_fn = $_fn . 'custom.js';
			$type = 'js';
		}
		if ($_type == 'custom.css') {
			$_folder = $_folder . '/custom';
			$_fn = $_fn . 'custom.css';
			$type = 'css';
		}
		if ($_type == 'api') {
			$_folder .= '/api';
			$_fn = $_fn . '.api.php';
			$type = 'php';
		}
		if ($_type == 'html') {
			$_folder .= '/html';
			$_fn = $_fn . '.html';
			$type = 'php';
		}
		if ($_type == 'configuration') {
			$_folder .= '';
			$_fn = $_fn . '.php';
			$type = 'php';
		}
	}
	if ($_plugin != '') {
		$_folder = 'plugins/' . $_plugin . '/' . $_folder;
	}
	$path = dirname(__FILE__) . "/../../$_folder/$_fn";
	if (file_exists($path)) {
		if ($type == 'php') {
			ob_start();
			require_once $path;
			echo translate::exec(ob_get_clean(), "$_folder/$_fn");
		} else if ($type == 'css') {
			echo "<link href=\"$_folder/$_fn?md5=" . md5_file($path) . "\" rel=\"stylesheet\" />";
		} else if ($type == 'js') {
			echo "<script type=\"text/javascript\" src=\"core/php/getJS.php?file=$_folder/$_fn&md5=" . md5_file($path) . "&lang=" . translate::getLanguage() . "\"></script>";
		}
	} else {
		throw new Exception("File not found : $_fn", 35486);
	}
}

function getTemplate($_folder, $_version, $_filename, $_plugin = '') {
	if ($_plugin == '') {
		$path = dirname(__FILE__) . '/../../' . $_folder . '/template/' . $_version . '/' . $_filename . '.html';
	} else {
		$path = dirname(__FILE__) . '/../../plugins/' . $_plugin . '/core/template/' . $_version . '/' . $_filename . '.html';
	}
	if (file_exists($path)) {
		return file_get_contents($path);
	}
	return '';
}

function template_replace($_array, $_subject) {
	return str_replace(array_keys($_array), array_values($_array), $_subject);
}

function init($_name, $_default = '') {
	if (isset($_GET[$_name])) {
		$cache[$_name] = $_GET[$_name];
		return $_GET[$_name];
	}
	if (isset($_POST[$_name])) {
		$cache[$_name] = $_POST[$_name];
		return $_POST[$_name];
	}
	if (isset($_REQUEST[$_name])) {
		$cache[$_name] = $_REQUEST[$_name];
		return $_REQUEST[$_name];
	}
	return $_default;
}

function sendVarToJS($_varName, $_value) {
	if (is_array($_value)) {
		echo '<script>';
		echo 'var ' . $_varName . ' = jQuery.parseJSON("' . addslashes(json_encode($_value, JSON_UNESCAPED_UNICODE)) . '");';
		echo '</script>';
	} else {
		echo '<script>';
		echo 'var ' . $_varName . ' = "' . $_value . '";';
		echo '</script>';
	}
}

function resizeImage($contents, $width, $height) {
// Cacul des nouvelles dimensions
	$width_orig = imagesx($contents);
	$height_orig = imagesy($contents);
	$ratio_orig = $width_orig / $height_orig;
	if ($width / $height > $ratio_orig) {
		$dest_width = ceil($height * $ratio_orig);
		$dest_height = $height;
	} else {
		$dest_height = ceil($width / $ratio_orig);
		$dest_width = $width;
	}

	$dest_image = imagecreatetruecolor($width, $height);
	$wh = imagecolorallocate($dest_image, 0xFF, 0xFF, 0xFF);
	imagefill($dest_image, 0, 0, $wh);

	$milieu_dest_x = $width / 2;
	$milieu_dest_y = $height / 2;
	$milieu_source_x = $dest_width / 2;
	$milieu_source_y = $dest_height / 2;
	$offcet_x = $milieu_dest_x - $milieu_source_x;
	$offcet_y = $milieu_dest_y - $milieu_source_y;
	if ($dest_image && $contents) {
		if (!imagecopyresampled($dest_image, $contents, $offcet_x, $offcet_y, 0, 0, $dest_width, $dest_height, $width_orig, $height_orig)) {
			error_log("Error image copy resampled");
			return false;
		}
	}
// start buffering
	ob_start();
	imagejpeg($dest_image);
	$contents = ob_get_contents();
	ob_end_clean();
	return $contents;
}

function getmicrotime() {
	list($usec, $sec) = explode(" ", microtime());
	return ((float) $usec + (float) $sec);
}

function redirect($_url, $_forceType = null) {
	switch ($_forceType) {
		case 'JS':
			echo '<script type="text/javascript">';
			echo "window.location.href='$_url';";
			echo '</script>';
			break;
		case 'PHP':
			exit(header("Location: $_url"));
			break;
		default:
			if (headers_sent() || isset($_GET['ajax'])) {
				echo '<script type="text/javascript">';
				echo "window.location.href='$_url';";
				echo '</script>';
			} else {
				exit(header("Location: $_url"));
			}
			break;
	}
	return;
}

function convertDuration($time) {
	if ($time >= 86400) {
		$jour = floor($time / 86400);
		$reste = $time % 86400;
		$heure = floor($reste / 3600);
		$reste = $reste % 3600;
		$minute = floor($reste / 60);
		$seconde = $reste % 60;
		$result = $jour . 'j ' . $heure . 'h ' . $minute . 'min ' . $seconde . 's';
	} elseif ($time < 86400 AND $time >= 3600) {
		$heure = floor($time / 3600);
		$reste = $time % 3600;
		$minute = floor($reste / 60);
		$seconde = $reste % 60;
		$result = $heure . 'h ' . $minute . 'min ' . $seconde . ' s';
	} elseif ($time < 3600 AND $time >= 60) {
		$minute = floor($time / 60);
		$seconde = $time % 60;
		$result = $minute . 'min ' . $seconde . 's';
	} elseif ($time < 60) {
		$result = $time . 's';
	}
	return $result;
}

function getClientIp() {
	if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		return $_SERVER['HTTP_X_FORWARDED_FOR'];
	} elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
		return $_SERVER['HTTP_CLIENT_IP'];
	} elseif (isset($_SERVER['REMOTE_ADDR'])) {
		return $_SERVER['REMOTE_ADDR'];
	}
	return '';
}

function mySqlIsHere() {
	require_once dirname(__FILE__) . '/../class/DB.class.php';
	return is_object(DB::getConnection());
}

function displayExeption($e) {
	$message = '<span id="span_errorMessage">' . $e->getMessage() . '</span>';
	if (DEBUG) {
		$message .= '<a class="pull-right bt_errorShowTrace cursor">Show traces</a>';
		$message .= '<br/><pre class="pre_errorTrace" style="display : none;">' . print_r($e->getTrace(), true) . '</pre>';
	}
	return $message;
}

function is_json($_string) {
	return ((is_string($_string) && (is_object(json_decode($_string)) || is_array(json_decode($_string))))) ? true : false;
}

function cleanPath($path) {
	$out = array();
	foreach (explode('/', $path) as $i => $fold) {
		if ($fold == '' || $fold == '.') {
			continue;
		}

		if ($fold == '..' && $i > 0 && end($out) != '..') {
			array_pop($out);
		} else {
			$out[] = $fold;
		}

	}
	return ($path{0} == '/' ? '/' : '') . join('/', $out);
}

function getRootPath() {
	return cleanPath(dirname(__FILE__) . '/../../');
}

function hadFileRight($_allowPath, $_path) {
	$path = cleanPath($_path);
	foreach ($_allowPath as $right) {
		if (strpos($right, '/') !== false || strpos($right, '\\') !== false) {
			if (strpos($right, '/') !== 0 || strpos($right, '\\') !== 0) {
				$right = getRootPath() . '/' . $right;
			}
			if (dirname($path) == $right || $path == $right) {
				return true;
			}
		} else {
			if (basename(dirname($path)) == $right || basename($path) == $right) {
				return true;
			}
		}
	}
	return false;
}

function getVersion($_name) {
	return jeedom::version();
}

function ls($folder = "", $pattern = "*", $recursivly = false, $options = array('files', 'folders')) {
	if ($folder) {
		$current_folder = realpath('.');
		if (in_array('quiet', $options)) {
			// If quiet is on, we will suppress the 'no such folder' error
			if (!file_exists($folder)) {
				return array();
			}

		}
		if (!is_dir($folder) || !chdir($folder)) {
			return array();
		}

	}
	$get_files = in_array('files', $options);
	$get_folders = in_array('folders', $options);
	$both = array();
	$folders = array();
	// Get the all files and folders in the given directory.
	if ($get_files) {
		$both = array();
		foreach (glob($pattern, GLOB_BRACE + GLOB_MARK) as $file) {
			if (!is_dir($folder . '/' . $file)) {
				$both[] = $file;
			}
		}
	}
	if ($recursivly or $get_folders) {
		$folders = glob("*", GLOB_ONLYDIR + GLOB_MARK);
	}

	//If a pattern is specified, make sure even the folders match that pattern.
	$matching_folders = array();
	if ($pattern !== '*') {
		$matching_folders = glob($pattern, GLOB_ONLYDIR + GLOB_MARK);
	}

	//Get just the files by removing the folders from the list of all files.
	$all = array_values(array_diff($both, $folders));
	if ($recursivly or $get_folders) {
		foreach ($folders as $this_folder) {
			if ($get_folders) {
				//If a pattern is specified, make sure even the folders match that pattern.
				if ($pattern !== '*') {
					if (in_array($this_folder, $matching_folders)) {
						array_push($all, $this_folder);
					}

				} else {
					array_push($all, $this_folder);
				}

			}

			if ($recursivly) {
				// Continue calling this function for all the folders
				$deep_items = ls($pattern, $this_folder, $recursivly, $options); # :RECURSION:
				foreach ($deep_items as $item) {
					array_push($all, $this_folder . $item);
				}
			}
		}
	}

	if ($folder && is_dir($current_folder)) {
		chdir($current_folder);
	}

	if (in_array('datetime_asc', $options)) {
		global $current_dir;
		$current_dir = $folder;
		usort($all, function ($a, $b) {
			return filemtime($GLOBALS['current_dir'] . '/' . $a) < filemtime($GLOBALS['current_dir'] . '/' . $b);
		});
	}
	if (in_array('datetime_desc', $options)) {
		global $current_dir;
		$current_dir = $folder;
		usort($all, function ($a, $b) {
			return filemtime($GLOBALS['current_dir'] . '/' . $a) > filemtime($GLOBALS['current_dir'] . '/' . $b);
		});
	}

	return $all;
}

function removeCR($_string) {
	$_string = str_replace("\n", '', $_string);
	$_string = str_replace("\r\n", '', $_string);
	$_string = str_replace("\r", '', $_string);
	$_string = str_replace("\n\r", '', $_string);
	return trim($_string);
}

function rcopy($src, $dst, $_emptyDest = true, $_exclude = array(), $_noError = false) {
	if (!file_exists($src)) {
		return true;
	}
	if ($_emptyDest) {
		rrmdir($dst);
	}
	if (is_dir($src)) {
		if (!file_exists($dst)) {
			mkdir($dst);
		}
		$files = scandir($src);
		foreach ($files as $file) {
			if ($file != "." && $file != ".." && !in_array($file, $_exclude) && !in_array(realpath($src . '/' . $file), $_exclude)) {
				if (!rcopy($src . '/' . $file, $dst . '/' . $file, $_emptyDest, $_exclude, $_noError) && !$_noError) {
					return false;
				}
			}
		}
	} else {
		if (!in_array(basename($src), $_exclude) && !in_array(realpath($src), $_exclude)) {
			if (!$_noError) {
				return copy($src, $dst);
			} else {
				@copy($src, $dst);
				return true;
			}

		}
	}
	return true;
}

// removes files and non-empty directories
function rrmdir($dir) {
	if (is_dir($dir)) {
		$files = scandir($dir);
		foreach ($files as $file) {
			if ($file != "." && $file != "..") {
				rrmdir("$dir/$file");
			}
		}
		if (!rmdir($dir)) {
			return false;
		}
	} else if (file_exists($dir)) {
		return unlink($dir);
	}
	return true;
}

function date_fr($date_en) {
	if (config::byKey('language', 'core', 'fr_FR') == 'en_US') {
		return $date_en;
	}
	$texte_long_en = array(
		"Monday", "Tuesday", "Wednesday", "Thursday",
		"Friday", "Saturday", "Sunday", "January",
		"February", "March", "April", "May",
		"June", "July", "August", "September",
		"October", "November", "December",
	);
	$texte_short_en = array(
		"Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun",
		"Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul",
		"Aug", "Sep", "Oct", "Nov", "Dec",
	);

	switch (config::byKey('language', 'core', 'fr_FR')) {
		case 'fr_FR':
			$texte_long = array(
				"Lundi", "Mardi", "Mercredi", "Jeudi",
				"Vendredi", "Samedi", "Dimanche", "Janvier",
				"Février", "Mars", "Avril", "Mai",
				"Juin", "Juillet", "Août", "Septembre",
				"Octobre", "Novembre", "Décembre",
			);
			$texte_short = array(
				"Lun", "Mar", "Mer", "Jeu", "Ven", "Sam", "Dim",
				"Jan", "Fev", "Mar", "Avr", "Mai", "Jui",
				"Jui", "Aou;", "Sep", "Oct", "Nov", "Dec",
			);
			break;
		case 'de_DE':
			$texte_long = array(
				"Montag", "Dienstag", "Mittwoch", "Donnerstag",
				"Freitag", "Samstag", "Sonntag", "Januar",
				"Februar", "März", "April", "May",
				"Juni", "July", "August", "September",
				"October", "November", "December",
			);

			$texte_short = array(
				"Mon", "Die", "Mit", "Thu", "Don", "Sam", "Son",
				"Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul",
				"Aug", "Sep", "Oct", "Nov", "Dec",
			);
			break;
		default:
			return $date_en;
			break;
	}
	return str_replace($texte_short_en, $texte_short, str_replace($texte_long_en, $texte_long, $date_en));
}

function convertDayEnToFr($_day) {
	switch (config::byKey('language', 'core', 'fr_FR')) {
		case 'fr_FR':
			if ($_day == 'Monday' || $_day == 'Mon') {
				return 'Lundi';
			}
			if ($_day == 'monday' || $_day == 'mon') {
				return 'lundi';
			}

			if ($_day == 'Tuesday' || $_day == 'Tue') {
				return 'Mardi';
			}
			if ($_day == 'tuesday' || $_day == 'tue') {
				return 'mardi';
			}

			if ($_day == 'Wednesday' || $_day == 'Wed') {
				return 'Mercredi';
			}
			if ($_day == 'wednesday' || $_day == 'wed') {
				return 'mercredi';
			}

			if ($_day == 'Thursday' || $_day == 'Thu') {
				return 'Jeudi';
			}
			if ($_day == 'thursday' || $_day == 'thu') {
				return 'Jeudi';
			}

			if ($_day == 'Friday' || $_day == 'Fri') {
				return 'Vendredi';
			}
			if ($_day == 'friday' || $_day == 'fri') {
				return 'vendredi';
			}

			if ($_day == 'Saturday' || $_day == 'Sat') {
				return 'Samedi';
			}
			if ($_day == 'saturday' || $_day == 'sat') {
				return 'samedi';
			}

			if ($_day == 'Sunday' || $_day == 'Sun') {
				return 'Dimanche';
			}
			if ($_day == 'sunday' || $_day == 'sun') {
				return 'dimanche';
			}
		case 'de_DE':
			if ($_day == 'Monday' || $_day == 'Mon') {
				return 'Montag';
			}
			if ($_day == 'monday' || $_day == 'mon') {
				return 'montag';
			}
			if ($_day == 'Tuesday' || $_day == 'Tue') {
				return 'Donnerstag';
			}
			if ($_day == 'tuesday' || $_day == 'tue') {
				return 'donnerstag';
			}
			if ($_day == 'Wednesday' || $_day == 'Wed') {
				return 'Mittwoch';
			}
			if ($_day == 'wednesday' || $_day == 'wed') {
				return 'mittwoch';
			}
			if ($_day == 'Thursday' || $_day == 'Thu') {
				return 'Donnerstag';
			}
			if ($_day == 'thursday' || $_day == 'thu') {
				return 'Donnerstag';
			}
			if ($_day == 'Friday' || $_day == 'Fri') {
				return 'Freitag';
			}
			if ($_day == 'friday' || $_day == 'fri') {
				return 'freitag';
			}
			if ($_day == 'Saturday' || $_day == 'Sat') {
				return 'Samstag';
			}
			if ($_day == 'saturday' || $_day == 'sat') {
				return 'samstag';
			}
			if ($_day == 'Sunday' || $_day == 'Sun') {
				return 'Sonntag';
			}
			if ($_day == 'sunday' || $_day == 'sun') {
				return 'Sonntag';
			}
	}

	return $_day;
}

function create_zip($source_arr, $destination) {
	if (is_string($source_arr)) {
		$source_arr = array($source_arr);
	}
	// convert it to array

	if (!extension_loaded('zip')) {
		throw new Exception('Extension php ZIP non chargée');
	}

	$zip = new ZipArchive();
	if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
		throw new Exception('Impossible de creer l\'archive ZIP dans le dossier de destination : ' . $destination);
	}

	foreach ($source_arr as $source) {
		if (!file_exists($source)) {
			continue;
		}

		$source = str_replace('\\', '/', realpath($source));

		if (is_dir($source) === true) {
			$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

			foreach ($files as $file) {
				if (strpos($file, $source) === false) {
					continue;
				}
				if ($file == $source . '/..') {
					continue;
				}
				if ($file == $source . '/.') {
					continue;
				}
				$file = str_replace('\\', '/', realpath($file));
				if (is_dir($file) === true) {
					$zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
				} else if (is_file($file) === true) {
					$zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
				}
			}
		} else if (is_file($source) === true) {
			$zip->addFromString(basename($source), file_get_contents($source));
		}
	}

	return $zip->close();
}

function br2nl($string) {
	return preg_replace('/\<br(\s*)?\/?\>/i', "\n", $string);
}

function calculPath($_path) {
	if (strpos($_path, '/') !== 0) {
		return dirname(__FILE__) . '/../../' . $_path;
	}
	return $_path;
}

function getDirectorySize($path) {
	$totalsize = 0;
	$totalcount = 0;
	$dircount = 0;
	if ($handle = opendir($path)) {
		while (false !== ($file = readdir($handle))) {
			$nextpath = $path . '/' . $file;
			if ($file != '.' && $file != '..' && !is_link($nextpath)) {
				if (is_dir($nextpath)) {
					$dircount++;
					$result = getDirectorySize($nextpath);
					$totalsize += $result['size'];
					$totalcount += $result['count'];
					$dircount += $result['dircount'];
				} elseif (is_file($nextpath)) {
					$totalsize += filesize($nextpath);
					$totalcount++;
				}
			}
		}
		closedir($handle);
	}
	return $totalsize;
}

function sizeFormat($size) {
	$mod = 1024;
	$units = explode(' ', 'B KB MB GB TB PB');
	for ($i = 0; $size > $mod; $i++) {
		$size /= $mod;
	}
	return round($size, 2) . ' ' . $units[$i];
}

function netMatch($network, $ip) {
	$network = trim($network);
	$orig_network = $network;
	$ip = trim($ip);
	if ($ip == $network) {
		return TRUE;
	}
	$network = str_replace(' ', '', $network);
	if (strpos($network, '*') !== FALSE) {
		if (strpos($network, '/') !== FALSE) {
			$asParts = explode('/', $network);
			$network = @$asParts[0];
		}
		$nCount = substr_count($network, '*');
		$network = str_replace('*', '0', $network);
		if ($nCount == 1) {
			$network .= '/24';
		} else if ($nCount == 2) {
			$network .= '/16';
		} else if ($nCount == 3) {
			$network .= '/8';
		} else if ($nCount > 3) {
			return TRUE; // if *.*.*.*, then all, so matched
		}
	}

	$d = strpos($network, '-');
	if ($d === FALSE) {
		if (strpos($network, '/') === false) {
			if ($ip == $network) {
				return true;
			}
			return false;
		}
		$ip_arr = explode('/', $network);
		if (!preg_match("@\d*\.\d*\.\d*\.\d*@", $ip_arr[0], $matches)) {
			$ip_arr[0] .= ".0"; // Alternate form 194.1.4/24
		}
		$network_long = ip2long($ip_arr[0]);
		$x = ip2long($ip_arr[1]);
		$mask = long2ip($x) == $ip_arr[1] ? $x : (0xffffffff << (32 - $ip_arr[1]));
		$ip_long = ip2long($ip);
		return ($ip_long & $mask) == ($network_long & $mask);
	} else {

		$from = trim(ip2long(substr($network, 0, $d)));
		$to = trim(ip2long(substr($network, $d + 1)));
		$ip = ip2long($ip);
		return ($ip >= $from and $ip <= $to);
	}
	return false;
}

function getNtpTime() {
	$time_servers = array(
		'ntp2.emn.fr',
		'time-a.timefreq.bldrdoc.gov',
		'utcnist.colorado.edu',
		'time.nist.gov',
		'ntp.pads.ufrj.br',
	);
	$time_adjustment = 0;
	foreach ($time_servers as $time_server) {
		$fp = fsockopen($time_server, 37, $errno, $errstr, 1);
		if ($fp) {
			$data = NULL;
			while (!feof($fp)) {
				$data .= fgets($fp, 128);
			}
			fclose($fp);
			if (strlen($data) == 4) {
				$NTPtime = ord($data{0}) * pow(256, 3) + ord($data{1}) * pow(256, 2) + ord($data{2}) * 256 + ord($data{3});
				$TimeFrom1990 = $NTPtime - 2840140800;
				$TimeNow = $TimeFrom1990 + 631152000;
				return date("m/d/Y H:i:s", $TimeNow + $time_adjustment);
			}
		}
	}
	return false;
}

function cast($sourceObject, $destination) {
	if (is_string($destination)) {
		$destination = new $destination();
	}
	$sourceReflection = new ReflectionObject($sourceObject);
	$destinationReflection = new ReflectionObject($destination);
	$sourceProperties = $sourceReflection->getProperties();
	foreach ($sourceProperties as $sourceProperty) {
		$sourceProperty->setAccessible(true);
		$name = $sourceProperty->getName();
		$value = $sourceProperty->getValue($sourceObject);
		if ($destinationReflection->hasProperty($name)) {
			$propDest = $destinationReflection->getProperty($name);
			$propDest->setAccessible(true);
			$propDest->setValue($destination, $value);
		} else {
			$destination->$name = $value;
		}
	}
	return $destination;
}

function getIpFromString($_string) {
	$result = parse_url($_string);
	if (isset($result['host'])) {
		$_string = $result['host'];
	} else {
		$_string = str_replace(array('https://', 'http://'), '', $_string);
		if (strpos($_string, '/') !== false) {
			$_string = substr($_string, 0, strpos($_string, '/'));
		}
		if (strpos($_string, ':') !== false) {
			$_string = substr($_string, 0, strpos($_string, ':'));
		}
	}
	if (!filter_var($_string, FILTER_VALIDATE_IP)) {
		$_string = gethostbyname($_string);
	}
	return $_string;
}

function evaluate($_string) {
	if (!isset($GLOBALS['ExpressionLanguage'])) {
		$GLOBALS['ExpressionLanguage'] = new ExpressionLanguage();
	}
	try {
		$expr = str_replace(array(' et ', ' ET ', ' AND ', ' and ', ' ou ', ' OR ', ' or ', ' OU '), array(' && ', ' && ', ' && ', ' && ', ' || ', ' || ', ' || ', ' || '), $_string);
		$expr = str_replace('==', '=', $expr);
		$expr = str_replace('=', '==', $expr);
		$expr = str_replace('<==', '<=', $expr);
		$expr = str_replace('>==', '>=', $expr);
		$expr = str_replace('!==', '!=', $expr);
		$expr = str_replace('!===', '!==', $expr);
		$expr = str_replace('====', '===', $expr);
		return $GLOBALS['ExpressionLanguage']->evaluate($expr);
	} catch (Exception $e) {
		log::add('expression', 'debug', '[Parser 1] Expression : ' . $_string . ' tranformé en ' . $expr . ' => ' . $e->getMessage());
	}
	return $_string;
}

function secureXSS($_string) {
	return str_replace('&amp;', '&', htmlspecialchars(strip_tags($_string), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
}

function minify($buffer) {
	$search = array(
		'/\>[^\S ]+/s', // strip whitespaces after tags, except space
		'/[^\S ]+\</s', // strip whitespaces before tags, except space
		'/(\s)+/s', // shorten multiple whitespace sequences
	);
	$replace = array(
		'>',
		'<',
		'\\1',
	);
	return preg_replace($search, $replace, $buffer);
}

function removeAccent($_message) {
	$caracteres = array(
		'À' => 'a', 'Á' => 'a', 'Â' => 'a', 'Ä' => 'a', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ä' => 'a', '@' => 'a',
		'È' => 'e', 'É' => 'e', 'Ê' => 'e', 'Ë' => 'e', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', '€' => 'e',
		'Ì' => 'i', 'Í' => 'i', 'Î' => 'i', 'Ï' => 'i', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
		'Ò' => 'o', 'Ó' => 'o', 'Ô' => 'o', 'Ö' => 'o', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'ö' => 'o',
		'Ù' => 'u', 'Ú' => 'u', 'Û' => 'u', 'Ü' => 'u', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'µ' => 'u',
		'Œ' => 'oe', 'œ' => 'oe',
		'$' => 's');
	return preg_replace('#[^A-Za-z0-9 \n\.\'=\*:]+#', '', strtr($_message, $caracteres));
}