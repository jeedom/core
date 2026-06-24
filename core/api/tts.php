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
require_once __DIR__ . "/../php/core.inc.php";

if (user::isBan()) {
	header("Status: 404 Not Found");
	header('HTTP/1.0 404 Not Found');
	$_SERVER['REDIRECT_STATUS'] = 404;
	echo "<h1>404 Not Found</h1>";
	echo "The page that you have requested could not be found.";
	die();
}

if (!jeedom::apiAccess(init('apikey'), 'apitts')) {
	echo __('Vous n\'êtes pas autorisé à effectuer cette action', __FILE__);
	die();
}

log::add('tts', 'debug', 'Call tts api : ' . print_r($_GET, true));
$engine = config::byKey('tts::engine', 'core', 'pico');
if (strpos($engine, 'plugin::') !== false) {
	$engine = str_replace('plugin::', '', $engine);
	if (!class_exists($engine) || !method_exists($engine, 'tts')) {
		$engine = 'pico';
	}
}
if (trim($engine) == '') {
	$engine = 'pico';
}
$text = init('text');
if ($text == '') {
	echo __('Aucun texte à dire', __FILE__);
	die();
}
if (substr(init('text'), -1) == '#' && substr(init('text'), 0, 1) == '#' && class_exists('songs')  && class_exists('songs_song')) {
	log::add('tts', 'debug', __('Tag detécté dans le tts et plugin song présent', __FILE__));
	$song = songs_song::byLogicalId(strtolower(str_replace('#', '', init('text'))));
	if (is_object($song) && file_exists($song->getPath())) {
		log::add('tts', 'debug', __('Son trouvé path :', __FILE__) . ' ' . $song->getPath());
		if (init('path') == 1) {
			echo $song->getPath();
		} else {
			header('Content-Type: audio/mpeg');
			header("Content-Transfer-Encoding: binary");
			header("Pragma: no-cache");
			header('Content-length: ' . filesize($song->getPath()));
			readfile($song->getPath());
		}
		die();
	}
}
$text = str_replace(array('[', ']', '#', '{', '}'), '', $text);
$md5 = md5($text);
$tts_dir = jeedom::getTmpFolder('tts');
$filename = $tts_dir . '/' . $md5 . '.mp3';
if (file_exists($filename)) {
	log::add('tts', 'debug', 'Use cache for ' . $filename . ' (' . $text . ')');
	if (init('path') == 1) {
		echo $filename;
		die();
	}
	header('Content-Type: audio/mpeg');
	header("Content-Transfer-Encoding: binary");
	header("Pragma: no-cache");
	header('Content-length: ' . filesize($filename));
	readfile($filename);
	die();
}
log::add('tts', 'debug', 'Generate tts for ' . $filename . ' (' . $text . ') with engine ' . $engine);

try {
	if ($engine == 'espeak') {
		$avconv = 'avconv';
		if (!com_shell::commandExists('avconv')) {
			$avconv = 'ffmpeg';
		}
		$cmd = tts_buildEspeakCmd($text, init('voice', 'fr+f4'), $filename, $avconv);
		log::add('tts', 'debug', $cmd);
		shell_exec($cmd);
	} else if ($engine == 'pico') {
		$avconv = 'avconv';
		if (!com_shell::commandExists('avconv')) {
			$avconv = 'ffmpeg';
		}
		$cmd = tts_buildPicoCmd($text, init('lang', config::byKey('language')), init('volume', '6'), $md5, $filename, $avconv);
		log::add('tts', 'debug', $cmd);
		shell_exec($cmd);
	} else {
		$engine::tts($filename, $text);
	}
} catch (Exception $e) {
	log::add('tts', 'error', $e->getMessage());
}

if (init('path') == 1) {
	echo $filename;
} else {
	header('Content-Type: audio/mpeg');
	header("Content-Transfer-Encoding: binary");
	header("Pragma: no-cache");
	header('Content-length: ' . filesize($filename));
	readfile($filename);
}
try {
	while (getDirectorySize($tts_dir) > (10 * 1024 * 1024)) {
		$older = array('file' => null, 'datetime' => null);
		foreach (ls($tts_dir . '/', '*') as $file) {
			if ($older['datetime'] == null || $older['datetime'] > filemtime($tts_dir . '/' . $file)) {
				$older['file'] = $tts_dir . '/' . $file;
				$older['datetime'] = filemtime($tts_dir . '/' . $file);
			}
		}
		if ($older['file'] == null) {
			log::add('tts', 'error', __('Erreur aucun fichier trouvé à supprimer alors que le répertoire fait :', __FILE__) . ' ' . getDirectorySize($tts_dir));
		}
		unlink($older['file']);
	}
} catch (Exception $e) {
	log::add('tts', 'error', $e->getMessage());
}

/**
 * Normalize and whitelist a TTS locale tag, falling back to fr-FR when invalid.
 *
 * @param string $_lang Locale tag (e.g. "fr_FR", "en-US").
 * @return string Normalized locale tag with a hyphen separator, or "fr-FR" if the input does not match the expected shape.
 */
function tts_sanitizeLang(string $_lang): string {
	$lang = str_replace('_', '-', $_lang);
	if (!preg_match('/^[a-zA-Z]{2}(-[a-zA-Z]{2,3})?$/', $lang)) {
		return 'fr-FR';
	}
	return $lang;
}

/**
 * Build a shell-safe espeak pipeline that renders text to an MP3 file.
 *
 * @param string $_text     Text to synthesize.
 * @param string $_voice    espeak voice identifier.
 * @param string $_filename Destination MP3 path.
 * @param string $_avconv   Transcoder binary name (defaults to "ffmpeg"). Must be a trusted constant, not user input.
 * @return string Full shell command, ready for exec/shell_exec.
 */
function tts_buildEspeakCmd(string $_text, string $_voice, string $_filename, string $_avconv = 'ffmpeg'): string {
	return 'espeak -v' . escapeshellarg($_voice) . ' ' . escapeshellarg($_text)
		. ' --stdout | ' . $_avconv . ' -i - -ar 44100 -ac 2 -ab 192k -f mp3 '
		. escapeshellarg($_filename) . ' > /dev/null 2>&1';
}

/**
 * Build a shell-safe pico2wave + transcode pipeline that renders text to an MP3 file with volume adjustment.
 *
 * @param string $_text     Text to synthesize.
 * @param string $_lang     Locale tag, sanitized through tts_sanitizeLang().
 * @param string $_volume   Volume adjustment in dB, cast through floatval().
 * @param string $_md5      Unique identifier used to name the intermediate WAV file.
 * @param string $_filename Destination MP3 path.
 * @param string $_avconv   Transcoder binary name (defaults to "ffmpeg"). Must be a trusted constant, not user input.
 * @return string Full shell command, ready for exec/shell_exec.
 * @see tts_sanitizeLang()
 */
function tts_buildPicoCmd(string $_text, string $_lang, string $_volume, string $_md5, string $_filename, string $_avconv = 'ffmpeg'): string {
	$lang = tts_sanitizeLang($_lang);
	$volume = '-af "volume=' . floatval($_volume) . 'dB"';
	$cmd = 'pico2wave -l=' . escapeshellarg($lang) . ' -w=' . escapeshellarg($_md5 . '.wav')
		. ' ' . escapeshellarg($_text) . ' > /dev/null 2>&1;';
	$cmd .= $_avconv . ' -i ' . escapeshellarg($_md5 . '.wav') . ' -ar 44100 ' . $volume
		. ' -ac 2 -ab 192k -f mp3 ' . escapeshellarg($_filename)
		. ' > /dev/null 2>&1;rm ' . escapeshellarg($_md5 . '.wav');
	return $cmd;
}