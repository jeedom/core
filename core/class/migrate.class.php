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
		//log::remove('migrate');
		$minSize = 7900; //En megaOct.
		$mediaLink = '/media/migrate';
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
              	exec('sudo umount '.$mediaLink);
              	exec('sudo mkdir '.$mediaLink);
              	exec('sudo mkdir '.$mediaLink.'/Backup');
				exec('sudo mount -t vfat /dev/'.$usb.' '.$mediaLink);
				if((migrate::freeSpaceUsb()/1024) > $minSize){
					$statut = 'ok';
				}else{
					$statut = 'space';
					$space = migrate::freeSpaceUsb()/1024;
				}
			}
		}
		return array('statut' => $statut, 'space' => $space, 'minSpace' => $minSize);
	}
	
	public static function backupToUsb() { 
		$mediaLink = '/media/migrate/Backup';
		log::remove('migrate');
	    $backups = jeedom::listBackup();
	    foreach ($backups as $backup) {
		    	$lienBackup = $backup;
	    }
	    if (substr(config::byKey('backup::path'), 0, 1) != '/') {
			$backup_dir = dirname(__FILE__) . '/../../' . config::byKey('backup::path');
		} else {
			$backup_dir = config::byKey('backup::path');
		}
		$tailleBackup = filesize($backup_dir.'/'.$lienBackup);
		exec('sudo rsync --progress '.$backup_dir.'/'.$lienBackup.' '.$mediaLink.'/'.$lienBackup. ' >'.log::getPathToLog('migrate').' 2>&1');
		$tailleBackupFin = filesize($mediaLink.'/'.$lienBackup);
		if($tailleBackup == $tailleBackupFin){
			return 'ok';
		}else{
			return 'nok';
		}
	} 
	
	public static function imageToUsb() { 
		$mediaLink = '/media/migrate';
		log::remove('migrate');
		$jsonrpc = repo_market::getJsonRpc();
		if (!$jsonrpc->sendRequest('box::smart_image_url')) {
	    	throw new Exception($jsonrpc->getErrorMessage());
		}
		$urlArray = $jsonrpc->getResult();
		$url = $urlArray['url'];
		$size = $urlArray['size'];
		exec('sudo pkill -9 wget');
		exec('sudo wget --no-check-certificate --progress=dot --dot=mega '.$url.' -a '.log::getPathToLog('migrate').' -O '.$mediaLink.'/backupJeedomDownload.tar.gz >> ' . log::getPathToLog('migrate').' 2&>1');
		$sizeFile = filesize($mediaLink.'/backupJeedomDownload.tar.gz');
		if($sizeFile == $size){
			return 'ok';
		}else{
			return 'nok';
		}
	}
	
	public static function renameImage(){
		$mediaLink = '/media/migrate';
		exec('sudo mv '.$mediaLink.'/backupJeedomDownload.tar.gz '.$mediaLink.'/backupJeedom.tar.gz');
		log::remove('migrate');
		return 'ok';
	}
	
	public static function finalisation(){
		$mediaLink = '/media/migrate';
		$mediaLinkBackup = $mediaLink.'/Backup';
		if (substr(config::byKey('backup::path'), 0, 1) != '/') {
			$backup_dir = dirname(__FILE__) . '/../../' . config::byKey('backup::path');
		} else {
			$backup_dir = config::byKey('backup::path');
		}
		log::remove('migrate');
		exec('sudo rsync --progress '.$mediaLinkBackup.'/* '.$backup_dir.' >'.log::getPathToLog('migrate').' 2>&1');
		$backups = jeedom::listBackup();
	    foreach ($backups as $backup) {
		    	$lienBackup = $backup;
	    }
	    jeedom::restore($lienBackup);
		return 'ok';
	}
	
	public static function freeSpaceUsb(){
		$mediaLink = '/media/migrate';
		return disk_free_space($mediaLink)/1024;
	}

}