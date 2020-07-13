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

use PHPUnit\Framework\TestCase;

class cronTest extends TestCase {
	public function testCreate() {
		echo "\n" . __CLASS__ . '::' . __FUNCTION__ . ' : ';
		$cron1 = new cron();
		$cron1->setClass('calendar');
		$cron1->setFunction('pull');
		$cron1->setLastRun(date('Y-m-d H:i:s'));
		$cron1->setSchedule('00 00 * * * 2020');
		$cron1->save();
		
		$cron2 = new cron();
		$cron2->setClass('calendar');
		$cron2->setFunction('pull');
		$cron2->setLastRun(date('Y-m-d H:i:s'));
		$cron2->setSchedule('00 00 * * * 2020');
		$cron2->save();
		
		$this->assertSame($cron1->getId(), $cron2->getId());
		
		$cron1 = cron::byClassAndFunction('calendar', 'pull');
		if (!is_object($cron1)) {
			throw new Exception("Impossible de trouver calend::pull");
		}
		$cron1->remove();
	}
	
	public function testCreateWithOption() {
		echo "\n" . __CLASS__ . '::' . __FUNCTION__ . ' : ';
		$cron1 = cron::byClassAndFunction('calendar', 'pull', array('event_id' => intval(1)));
		if (!is_object($cron1)) {
			$cron1 = new cron();
			$cron1->setClass('calendar');
			$cron1->setFunction('pull');
			$cron1->setOption(array('event_id' => intval(1)));
			$cron1->setLastRun(date('Y-m-d H:i:s'));
		}
		$cron1->setSchedule('00 00 * * * 2020');
		$cron1->save();
		
		$cron2 = cron::byClassAndFunction('calendar', 'pull', array('event_id' => intval(2)));
		if (!is_object($cron2)) {
			$cron2 = new cron();
			$cron2->setClass('calendar');
			$cron2->setFunction('pull');
			$cron2->setOption(array('event_id' => intval(2)));
			$cron2->setLastRun(date('Y-m-d H:i:s'));
		}
		$cron2->setSchedule('00 00 * * * 2020');
		$cron2->save();
		
		$this->assertNotSame($cron1->getId(), $cron2->getId());
		
		$cron3 = cron::byClassAndFunction('calendar', 'pull', array('event_id' => intval(1)));
		if (!is_object($cron3)) {
			$cron3 = new cron();
			$cron3->setClass('calendar');
			$cron3->setFunction('pull');
			$cron3->setOption(array('event_id' => intval(1)));
			$cron3->setLastRun(date('Y-m-d H:i:s'));
		}
		$cron3->setSchedule('00 00 * * * 2020');
		$cron3->save();
		
		$this->assertSame($cron1->getId(), $cron3->getId());
		
		$cron1 = cron::byClassAndFunction('calendar', 'pull', array('event_id' => intval(1)));
		if (!is_object($cron1)) {
			throw new Exception("Impossible de trouver calend::pull (1)");
		}
		$cron1->remove();
		$cron2 = cron::byClassAndFunction('calendar', 'pull', array('event_id' => intval(2)));
		if (!is_object($cron2)) {
			throw new Exception("Impossible de trouver calend::pull (2)");
		}
		$cron2->remove();
	}
}
?>
