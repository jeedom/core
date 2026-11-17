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
 * Lecture de l'avancement des scripts install/ (marqueurs « [PROGRESS][n] » écrits par
 * consoleProgress) dans leur journal.
 *
 * Ce fichier ne charge pas core.inc.php : le suivi de restauration l'utilise avant le
 * bootstrap du Core, pendant que la base est remplacée (voir restoreTracking::FILES).
 */
final class progressLog {
	/** Taille de la fin de journal lue (octets). */
	public const TAIL = 16384;

	/** Fin d'un journal ouvert, ou '' en cas d'échec de lecture. */
	public static function tail($_handle): string {
		$stat = fstat($_handle);
		if ($stat === false) {
			return '';
		}
		fseek($_handle, max(0, (int) $stat['size'] - self::TAIL));
		$value = stream_get_contents($_handle);
		return $value === false ? '' : $value;
	}

	/** Fin du journal $_path, ou '' s'il est illisible. */
	public static function tailOf(string $_path): string {
		$handle = @fopen($_path, 'rb');
		if ($handle === false) {
			return '';
		}
		try {
			return self::tail($handle);
		} finally {
			fclose($handle);
		}
	}

	/** Dernier pourcentage annoncé dans $_text (borné à 100), ou null s'il n'y en a pas. */
	public static function lastPercent(string $_text): ?int {
		if (!preg_match_all('/\[PROGRESS\]\[(\d{1,3})\]/', $_text, $matches)) {
			return null;
		}
		return min(100, (int) end($matches[1]));
	}
}
