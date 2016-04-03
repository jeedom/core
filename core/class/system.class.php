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

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../core/php/core.inc.php';

class system {
	public static function fuserk($_port, $_protocol = 'tcp') {
		if (file_exists($_port)) {
			exec('fuser -k ' . $_port . ' > /dev/null 2>&1;sudo fuser -k ' . $_port . ' > /dev/null 2>&1');
		} else {
			exec('fuser -k ' . $_port . '/' . $_protocol . ' > /dev/null 2>&1;sudo fuser -k ' . $_port . '/' . $_protocol . ' > /dev/null 2>&1');
		}
	}

	public static function ps($_find, $_without = null) {
		$return = array();
		$cmd = '(ps ax || ps w) | grep -ie "' . $_find . '" | grep -v "grep"';
		if ($_without != null) {
			if (!is_array($_without)) {
				$_without = array($_without);
			}
			foreach ($_without as $value) {
				$cmd .= ' | grep -v "' . $value . '"';
			}
		}
		$results = explode("\n", trim(shell_exec($cmd)));
		if (!is_array($results) || count($results) == 0) {
			return $return;
		}
		$order = array('pid', 'tty', 'stat', 'time', 'command');
		foreach ($results as $result) {
			if (trim($result) == '') {
				continue;
			}
			$explodes = explode(" ", $result);
			$info = array();
			$i = 0;
			foreach ($explodes as $value) {
				if (trim($value) == '') {
					continue;
				}
				if (isset($order[$i])) {
					$info[$order[$i]] = trim($value);
				} else {
					$info[end($order)] = $info[end($order)] . ' ' . trim($value);

				}
				$i++;
			}
			$return[] = $info;
		}
		return $return;
	}

	public static function kill($_find = '') {
		if (trim($_find) == '') {
			return;
		}
		if (is_numeric($_find)) {
			$kill = posix_kill($_find, 15);
			if ($kill) {
				return true;
			}
			usleep(100);
			$kill = posix_kill($_find, 9);
			if ($kill) {
				return true;
			}
			usleep(100);
			$cmd = 'kill -9 ' . $_find;
			$cmd .= '; sudo kill -9 ' . $_find;
			exec($cmd);
			return;
		}
		$cmd = "(ps ax || ps w) | grep -ie '" . $_find . "' | grep -v grep | awk '{print $1}' | xargs kill -9 > /dev/null 2>&1";
		$cmd .= "; (ps ax || ps w) | grep -ie '" . $_find . "' | grep -v grep | awk '{print $1}' | xargs sudo kill -9 > /dev/null 2>&1";
		exec($cmd);
	}

	public static function php($arguments) {
		return exec('php ' . $arguments);
	}
}
?>