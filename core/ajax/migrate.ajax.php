<?php

/** @entrypoint */
/** @ajax */

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Genxeral Public License as published by
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

require_once __DIR__ . '/ajax.handler.inc.php';

ajaxHandle(function ()
{
    ajax::checkAccess('admin');
	if (init('action') == 'usbTry') {
		$usbTry = migrate::usbTry();
		return $usbTry;
	}
	
	if (init('action') == 'backupToUsb') {
		$backupToUsb = migrate::backupToUsb();
		return $backupToUsb;
	}
	
	if (init('action') == 'imageToUsb') {
		$imageToUsb = migrate::imageToUsb();
		return $imageToUsb;
	}
	
	if (init('action') == 'freeSpaceUsb') {
		$freeSpaceUsb = migrate::freeSpaceUsb();
		return $freeSpaceUsb;
	}
	
	if (init('action') == 'getStep') {
		$valueMigrate = config::byKey('stepMigrate');
		return $valueMigrate;
	}
	
	if (init('action') == 'setStep') {
		if(init('stepValues')){
			config::save('stepMigrate', init('stepValues'));
			return init('stepValues');
		}
	}
	if (init('action') == 'renameImage'){
		$renameImage = migrate::renameImage();
		return $renameImage;
	}
	if (init('action') == 'GoBackupInstall'){
		$GoBackupInstall = migrate::GoBackupInstall();
		return $GoBackupInstall;
	}
	if (init('action') == 'finalisation'){
		$finalisation = migrate::finalisation();
		return $finalisation;
	}

	throw new Exception(__('Aucune méthode correspondante à : ', __FILE__) . init('action'));
	/*     * *********Catch exeption*************** */
});
