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
