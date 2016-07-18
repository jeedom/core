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
require_once dirname(__FILE__) . "/../php/core.inc.php";

if (!jeedom::apiAccess(init('apikey'))) {
	echo 'Clef API non valide, vous n\'etes pas autorisé à effectuer cette action';
	die();
}

$engine = init('engine', 'pico');
$text = init('text');
if ($text == '') {
	echo __('Aucun text à dire', __FILE__);
	die();
}

$md5 = md5($text);
$filename = '/tmp/' . $md5 . '.mp3';
switch ($engine) {
	case 'espeak':
		$voice = init('voice', 'fr+f4');
		shell_exec('sudo espeak -v' . $voice . ' "' . $text . '" --stdout | avconv -i - -ar 44100 -ac 2 -ab 192k -f mp3 ' . $filename . ' > /dev/null 2>&1');
		break;
	case 'pico':
		$volume = '-af "volume=' . init('volume', '6') . 'dB"';
		$lang = init('lang', 'fr-FR');
		shell_exec('sudo pico2wave -l=' . $lang . ' -w=' . $md5 . '.wav "' . $text . '" > /dev/null 2>&1;sudo avconv -i ' . $md5 . '.wav -ar 44100 ' . $volume . ' -ac 2 -ab 192k -f mp3 ' . $filename . ' > /dev/null 2>&1;sudo rm ' . $md5 . '.wav');
		break;
	default:
		echo __('Moteur de voix inconnue : ', __FILE__) . $engine;
		die();
		break;
}

header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename=' . $md5 . '.mp3');
readfile($filename);
shell_exec('sudo rm ' . $filename . ' > /dev/null 2>&1');
?>