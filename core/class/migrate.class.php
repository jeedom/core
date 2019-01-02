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

class migrate {

	public static function usbTry(){
		$minSize = 7900; //En megaOct.
		$iSD = 0;
		$iSDn = 0;
		$usbSD = null;
		$statut = null;
		$space = null;
		
		foreach (ls('/dev/', 'sd*') as $usb) {
			if(strlen($usb) == 3){
				$iSD++;
			}
			if(strlen($usb) > 3){
				$iSDn++;
				$usbSD = $usb;
			}
		}
		if($iSD == 0){
			$statut = 'sdaNull';
		}elseif($iSD > 1){
			$statut = 'sdaSup';
		}elseif($iSD == 1){
			if($iSDn == 0){
				$statut = 'sdaNumNull';
			}elseif($iSDn > 1){
				$statut = 'sdaNumSup';
			}else{
				exec('sudo mount -t vfat /dev/'.$usb.' /media/usb2');
				if((disk_free_space('/media/usb2')/1024)/1024 > $minSize){
					$statut = 'ok';
				}else{
					$statut = 'space';
					$space = (disk_free_space('/media/usb2')/1024)/1024;
				}
			}
		}
		return array('statut' => $statut, 'space' => $space, 'minSpace' => $minSize);
	}

}