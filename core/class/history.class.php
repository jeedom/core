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
require_once __DIR__ . '/../../core/php/core.inc.php';

class history {
	/*     * *************************Attributs****************************** */

	protected $cmd_id;
	protected $value;
	protected $datetime;
	protected $_changed = false;
	protected $_tableName = 'history';

	/*     * ***********************Methode static*************************** */

	public static function removeHistoryInFutur(){
		$sql = 'DELETE FROM history 
		WHERE `datetime` > :datetime';
		DB::Prepare($sql, array('datetime' => date('Y-m-d H:i:s')), DB::FETCH_TYPE_ROW);
		$sql = 'DELETE FROM historyArch 
		WHERE `datetime` > :datetime';
		DB::Prepare($sql, array('datetime' => date('Y-m-d H:i:s')), DB::FETCH_TYPE_ROW);
	  }

	public static function checkCurrentValueAndHistory() {
		$sql = 'SELECT DISTINCT(cmd_id)
        FROM history';
		$cmd_ids = DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL);
		foreach ($cmd_ids as $cmd_id) {
			$cmd = cmd::byId($cmd_id['cmd_id']);
			if (!is_object($cmd) || $cmd->getType() != 'info') {
				continue;
			}
			$values = array(
				'cmd_id' => $cmd->getId(),
			);
			$sql = 'SELECT ' . DB::buildField(__CLASS__);
			$sql .= ' FROM history
            WHERE cmd_id=:cmd_id ';
			$sql .= ' ORDER BY `datetime` DESC LIMIT 1';
			$history = DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, 'history');
			$cmd->execCmd();
			if (strtotime($cmd->getCollectDate()) < strtotime($history->getDatetime())) {
				$cmd->setCache('collectDate', $history->getDatetime());
				$cmd->setCache(array(
					'value' => $history->getValue(),
					'collectDate' => $history->getDatetime()
				));
			}
		}
	}

	public static function exportToCSV($histories) {
		if (!is_array($histories)) {
			$histories = array($histories);
		}
		$return = '"' . __('Commande', __FILE__) . '";"' . '"' . __('Date', __FILE__) . '";"' . __('Valeur', __FILE__) . '"' . "\n";
		foreach ($histories as $history) {
			$return .=  '"' . $history->getCmd()->getHumanName() . '";"' . '"' . $history->getDatetime() . '";"' . $history->getValue() . '"' . "\n";
		}
		return $return;
	}

	public static function copyHistoryToCmd($_source_id, $_target_id) {
		$source_cmd = cmd::byId(str_replace('#', '', $_source_id));
		if (!is_object($source_cmd)) {
			throw new Exception(__('La commande source n\'existe pas :', __FILE__) . ' ' . $_source_id);
		}
		if ($source_cmd->getIsHistorized() != 1) {
			throw new Exception(__('La commande source n\'est pas historisée', __FILE__));
		}
		if ($source_cmd->getType() != 'info') {
			throw new Exception(__('La commande source n\'est pas de type info', __FILE__));
		}
		$target_cmd = cmd::byId(str_replace('#', '', $_target_id));
		if (!is_object($target_cmd)) {
			throw new Exception(__('La commande cible n\'existe pas :', __FILE__) . ' ' . $_target_id);
		}
		if ($target_cmd->getType() != 'info') {
			throw new Exception(__('La commande cible n\'est pas de type info', __FILE__));
		}
		if ($target_cmd->getSubType() != $source_cmd->getSubType()) {
			throw new Exception(__('Le sous-type de la commande cible n\'est pas le même que celui de la commande source', __FILE__));
		}
		if ($target_cmd->getIsHistorized() != 1) {
			$target_cmd->setIsHistorized(1);
			$target_cmd->save();
		}
		$values = array(
			'source_id' => $source_cmd->getId(),
		);
		$sql = 'REPLACE INTO `history` (`cmd_id`,`datetime`,`value`)
        SELECT ' . $target_cmd->getId() . ',`datetime`,`value` FROM `history` WHERE cmd_id=:source_id';
		DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW);

		$sql = 'REPLACE INTO `historyArch` (`cmd_id`,`datetime`,`value`)
        SELECT ' . $target_cmd->getId() . ',`datetime`,`value` FROM `historyArch` WHERE cmd_id=:source_id';
		DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW);
	}

	public static function byCmdIdDatetime($_cmd_id, $_startTime, $_endTime = null, $_oldValue = null) {
		if ($_endTime == null) {
			$_endTime = $_startTime;
		}
		$values = array(
			'cmd_id' => $_cmd_id,
			'startTime' => $_startTime,
			'endTime' => $_endTime,
		);
		if ($_oldValue != null) {
			$values['oldValue'] = $_oldValue;
		}
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
        FROM history
        WHERE cmd_id=:cmd_id
        AND `datetime`>=:startTime
        AND `datetime`<=:endTime';
		if ($_oldValue != null) {
			$sql .= ' AND `value`=:oldValue';
		}
		$result = DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
		if (!is_object($result)) {
			$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
            FROM historyArch
            WHERE cmd_id=:cmd_id
            AND `datetime`>=:startTime
            AND `datetime`<=:endTime';
			if ($_oldValue != null) {
				$sql .= ' AND `value`=:oldValue';
			}
			$result = DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, 'historyArch');
			if (is_object($result)) {
				$result->setTableName('historyArch');
			}
		} else {
			$result->setTableName('history');
		}
		return $result;
	}

	/**
	 * Value of a command on a given date
	 */
	public static function byCmdIdAtDatetime($_cmd_id, $_time, $_previous = true) {
		$operator = '<=';
		$direction = 'DESC';
		$tables = [__CLASS__, 'historyArch'];
		if (!$_previous) {
			$operator = '>=';
			$direction = 'ASC';
			$tables = ['historyArch', __CLASS__];
		}
		$values = array(
			'cmd_id' => $_cmd_id,
			'time' => $_time,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
        FROM ' . $tables[0] . '
        WHERE cmd_id=:cmd_id
        AND `datetime`' . $operator . ':time
        ORDER BY `datetime` ' . $direction . ' LIMIT 1';
		$result = DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, $tables[0]);
		if (!is_object($result)) {
			$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
          FROM ' . $tables[1] . '
          WHERE cmd_id=:cmd_id
          AND `datetime`' . $operator . ':time
        	ORDER BY `datetime` ' . $direction . ' LIMIT 1';
			$result = DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, $tables[1]);
			if (is_object($result)) {
				$result->setTableName($tables[1]);
			}
		} else {
			$result->setTableName($tables[0]);
		}
		return $result;
	}
        
        public static function byCmdIdAtDatetimeFromCalcul($_strcalcul, $_time, $_previous = true){
		$cmd_histories = array();
                preg_match_all("/#([0-9]*)#/", $_strcalcul, $matches);
                if (count($matches[1]) > 0) {
                    foreach ($matches[1] as $cmd_id) {
                        if (is_numeric($cmd_id)) {
                            $cmd = cmd::byId($cmd_id);
                            $value = 0;
                            if (is_object($cmd) && $cmd->getIsHistorized() == 1 && !$cmd->getConfiguration('isHistorizedCalc', 0)) {
                                $result = history::byCmdIdAtDatetime($cmd_id, $_time, $_previous);
                                if($result)
                                    $value = $result->getValue();
                            }
                            elseif(is_object($cmd)){
                                $value = history::byCmdIdAtDatetimeFromCalcul(jeedom::fromHumanReadable($cmd->getConfiguration('calcul')), $_time, $_previous);
                            }
                            $cmd_histories['#' . $cmd_id . '#'] = $value;
                        }
                    }
                }
                $calcul = template_replace($cmd_histories, $_strcalcul);
                return floatval(jeedom::evaluateExpression($calcul));
        }

	/**
	 * Archive data from history into historyArch
	 */
	public static function archive() {
		global $JEEDOM_INTERNAL_CONFIG;
		$sql = 'DELETE FROM history WHERE `value` IS NULL';
		DB::Prepare($sql, array());
		$sql = 'DELETE FROM historyArch WHERE `value` IS NULL';
		DB::Prepare($sql, array());
		$sql = 'DELETE FROM history WHERE `datetime` <= "2000-01-01 01:00:00" OR  `datetime` >= "2026-01-01 01:00:00"';
		DB::Prepare($sql, array());
		$sql = 'DELETE FROM historyArch WHERE `datetime` <= "2000-01-01 01:00:00" OR  `datetime` >= "2026-01-01 01:00:00"';
		DB::Prepare($sql, array());
		$sql = 'DELETE FROM history WHERE `value` IS NULL';
		DB::Prepare($sql, array());
		$sql = 'DELETE FROM historyArch WHERE `value` IS NULL';
		DB::Prepare($sql, array());
		if (config::byKey('historyArchivePackage') < 1) {
			config::save('historyArchivePackage', 1);
		}
		if (config::byKey('historyArchiveTime') < 2) {
			config::save('historyArchiveTime', 2);
		}
		if (config::byKey('historyArchivePackage') >= config::byKey('historyArchiveTime')) {
			config::save('historyArchivePackage', config::byKey('historyArchiveTime') - 1);
		}
		if (config::byKey('historyArchivePackage') >= config::byKey('historyArchiveTime')) {
			config::save('historyArchivePackage', config::byKey('historyArchiveTime') - 1);
		}
		$archiveDatetime = (config::byKey('historyArchiveTime') < 1) ? date('Y-m-d H:i:s', strtotime('- 1 hours')) : date('Y-m-d H:i:s', strtotime('- ' . config::byKey('historyArchiveTime', 'core', 1) . ' hours'));
		if (config::byKey('historyArchivePackage') < 1) {
			$archivePackage = '00:' . config::byKey('historyArchivePackage') * 60 . ':00';
		} else {
			$archivePackage = config::byKey('historyArchivePackage') . ':00:00';
			if (strlen($archivePackage) < 8) {
				$archivePackage = '0' . $archivePackage;
			}
		}
		if ($archiveDatetime === false) {
			$archiveDatetime = date('Y-m-d H:i:s', strtotime('- 1 hours'));
		}
		$sql = '(SELECT DISTINCT(cmd_id) FROM history) UNION (SELECT DISTINCT(cmd_id) FROM historyArch)';
		$list_sensors = DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL);
		foreach ($list_sensors as $sensors) {
			try {
				$cmd = cmd::byId($sensors['cmd_id']);
				if (!is_object($cmd) || $cmd->getType() != 'info' || $cmd->getIsHistorized() != 1) {
					$values = array(
						'cmd_id' => $sensors['cmd_id']
					);
					$sql = 'DELETE FROM history WHERE cmd_id=:cmd_id';
					DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW);
					$sql = 'DELETE FROM historyArch WHERE cmd_id=:cmd_id';
					DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW);
					continue;
				}
				$purgeTime = false;
				if($cmd->getConfiguration('historyPurge', '') != 'never'){
					if ($cmd->getConfiguration('historyPurge', '') != '') {
						$purgeTime = date('Y-m-d H:i:s', strtotime($cmd->getConfiguration('historyPurge', '')));
					} else if (config::byKey('historyPurge') != '' && config::byKey('historyPurge') != 'never') {
						$purgeTime = date('Y-m-d H:i:s', strtotime(config::byKey('historyPurge')));
					}
				}
				if ($purgeTime !== false) {
					$values = array(
						'cmd_id' => $cmd->getId(),
						'datetime' => $purgeTime,
					);
					$sql = 'DELETE FROM historyArch WHERE cmd_id=:cmd_id AND `datetime` < :datetime';
					DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW);
					$sql = 'DELETE FROM history WHERE cmd_id=:cmd_id AND `datetime` < :datetime';
					DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW);
				}
				if (!$JEEDOM_INTERNAL_CONFIG['cmd']['type']['info']['subtype'][$cmd->getSubType()]['isHistorized']['canBeSmooth'] || $cmd->getConfiguration('historizeMode', 'avg') == 'none') {
					$values = array(
						'cmd_id' => $cmd->getId(),
						'archiveTime' => $archiveDatetime
					);
					$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
                    FROM history
                    WHERE `datetime` <= :archiveTime
                    AND cmd_id=:cmd_id
                    AND `value` IS NOT NULL
                    ORDER BY `datetime` ASC';
					$history = DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
					$countHistory = count($history);
					if ($countHistory > 0) {
						if ($cmd->getConfiguration('historizeMode', 'avg') == 'none') {
							for ($i = 0; $i < $countHistory; $i++) {
								$history[$i]->setTableName('historyArch');
								$history[$i]->save();
							}
						} else {
							if ($countHistory > 1) {
								for ($i = 1; $i < $countHistory; $i++) {
									if ($history[$i]->getValue() != $history[$i - 1]->getValue()) {
										$history[$i]->setTableName('historyArch');
										$history[$i]->save();
									}
								}
							}
							$history[0]->setTableName('historyArch');
							$history[0]->save();
						}
					}
					$sql = 'DELETE FROM history
                    WHERE `datetime` <= :archiveTime
                    AND cmd_id=:cmd_id';
					DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW);
					continue;
				}
				$mode = $cmd->getConfiguration('historizeMode', 'avg');
				$values = array(
					'cmd_id' => $sensors['cmd_id'],
					'archivePackage' => config::byKey('historyArchivePackage') * 3600,
					'archiveTime' => $archiveDatetime
				);
				$round = (is_numeric($cmd->getConfiguration('historizeRound'))) ? $cmd->getConfiguration('historizeRound') : 2;
				$sql = 'REPLACE INTO historyArch(cmd_id,`datetime`,value) SELECT cmd_id,MIN(`datetime`),' . $mode . '(CAST(value AS DECIMAL(12,' . $round . '))) as value
                FROM history
                WHERE `datetime` <= :archiveTime
                AND cmd_id=:cmd_id
                AND `value` IS NOT NULL
                GROUP BY UNIX_TIMESTAMP(`datetime`) DIV :archivePackage';
				try {
					DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW);
				} catch (Exception $e) {
					log::add('history', 'error', __('Erreur l\'archivage des historiques :', __FILE__) . ' ' . json_encode($values) . '  => ' . log::exception($e));
					continue;
				}
				$values = array('cmd_id' => $sensors['cmd_id'], 'archiveTime' => $archiveDatetime);
				$sql = 'DELETE FROM history
                WHERE `datetime` <= :archiveTime
                AND cmd_id=:cmd_id';
				DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW);
			} catch (\Exception $e) {
			}
		}
	}

	public static function all($_cmd_id, $_startTime = null, $_endTime = null, $_groupingType = null, $_addFirstPreviousValue = false) {
		$delta=false;
		$values = array(
			'cmd_id' => $_cmd_id,
		);
		if ($_startTime !== null) {
			$values['startTime'] = $_startTime;
		}
		if ($_endTime !== null) {
			$values['endTime'] = $_endTime;
		}
		$sql='';
		if ($_groupingType == null || strpos($_groupingType, '::') === false) {
			$sql .= 'SELECT ' . DB::buildField(__CLASS__);
		} else {
			$goupingTypeDelta = explode('||', $_groupingType);
			if (count($goupingTypeDelta)>1){
				$_groupingType=$goupingTypeDelta[0];
				$delta=true;
				if ($goupingTypeDelta[1]== 'delta'){
					$sql .= 'SELECT `cmd_id`,`datetime` as datetime, CAST(value - COALESCE(LAG(value) OVER (ORDER BY `datetime` ),0) AS DECIMAL(12,2)) as value FROM (';
				}
			}
			$goupingType = explode('::', $_groupingType);
			$function = 'AVG';
			if ($goupingType[0] == 'high' || $goupingType[0] == 'max') {
				$function = 'MAX';
			} else  if ($goupingType[0] == 'low' || $goupingType[0] == 'min') {
				$function = 'MIN';
			} else  if ($goupingType[0] == 'sum') {
				$function = 'SUM';
			}
			if ($goupingType[1] == 'hour') {
				$sql .= 'SELECT `cmd_id`,DATE_FORMAT(`datetime`,\'%Y-%m-%d %H:00:00\') as `datetime`,' . $function . '(CAST(value AS DECIMAL(12,2))) as value';
			} else if ($goupingType[1] == 'minute') {
				$sql .= 'SELECT `cmd_id`,DATE_FORMAT(`datetime`,\'%Y-%m-%d %H:%i:00\') as `datetime`,' . $function . '(CAST(value AS DECIMAL(12,2))) as value';
			} else {
				$sql .= 'SELECT `cmd_id`,DATE(`datetime`) as `datetime`,' . $function . '(CAST(value AS DECIMAL(12,2))) as value';
			}
		}
		$sql .= ' FROM (';
		if ($_groupingType != null && strpos($_groupingType, '::') !== false && count($goupingType)>2) {
			$functionFinest = 'AVG';
			if ($goupingType[2] == 'high' || $goupingType[2] == 'max') {
				$functionFinest = 'MAX';
			} else  if ($goupingType[2] == 'low' || $goupingType[2] == 'min') {
				$functionFinest = 'MIN';
			} else  if ($goupingType[2] == 'sum') {
				$functionFinest = 'SUM';
			}
			if ($goupingType[3] == 'hour') {
				$sql .= 'SELECT `cmd_id`,DATE_FORMAT(`datetime`,\'%Y-%m-%d %H:00:00\') as `datetime`,' . $functionFinest . '(CAST(value AS DECIMAL(12,2))) as value';
			} else if ($goupingType[3] == 'minute') {
				$sql .= 'SELECT `cmd_id`,DATE_FORMAT(`datetime`,\'%Y-%m-%d %H:%i:00\') as `datetime`,' . $functionFinest . '(CAST(value AS DECIMAL(12,2))) as value';
			} else {
				$sql .= 'SELECT `cmd_id`,DATE(`datetime`) as `datetime`,' . $functionFinest . '(CAST(value AS DECIMAL(12,2))) as value';
			}
			$sql .= ' FROM (';
		}
		$sql .= ' (SELECT * from history WHERE value is not null AND cmd_id=:cmd_id ';
		if ($_startTime !== null) {
			$sql .= ' AND datetime>=:startTime';
		}
		if ($_endTime !== null) {
			$sql .= ' AND datetime<=:endTime';
		}
		$sql .= ') ';
		$sql .= ' UNION ALL ';
		$sql .= ' (SELECT * from historyArch WHERE value is not null AND cmd_id=:cmd_id ';
		if ($_startTime !== null) {
			$sql .= ' AND `datetime`>=:startTime';
		}
		if ($_endTime !== null) {
			$sql .= ' AND `datetime`<=:endTime';
		}
		$sql .= ') ';
		$sql .= ')a ';
		if ($_groupingType != null && strpos($_groupingType, '::') !== false && count($goupingType)>2) {
			if ($goupingType[3] == 'week') {
				$sql .= ' GROUP BY CONCAT(YEAR(`datetime`), \'/\', WEEK(`datetime`,7))';
			} else if ($goupingType[3] == 'hour') {
				$sql .= ' GROUP BY CONCAT(DATE(`datetime`), \'/\', HOUR(`datetime`))';
			} else if ($goupingType[3] == 'month') {
				$sql .= ' GROUP BY CONCAT(YEAR(`datetime`), \'/\', MONTH(`datetime`))';
			} else if ($goupingType[3] == 'minute') {
				$sql .= ' GROUP BY CONCAT(DATE(`datetime`), \'/\', HOUR(`datetime`), \'/\', MINUTE(`datetime`))';
			} else {
				$time = 'DATE';
				if ($goupingType[3] == 'year') {
					$time = 'YEAR';
				}
				$sql .= ' GROUP BY ' . $time . '(DATE_SUB(`datetime`, INTERVAL 1 SECOND))';
			}
			$sql .= ')b ';
		}
		if ($_groupingType != null && strpos($_groupingType, '::') !== false) {
			if ($goupingType[1] == 'week') {
				$sql .= ' GROUP BY CONCAT(YEAR(`datetime`), \'/\', WEEK(`datetime`,7))';
			} else if ($goupingType[1] == 'hour') {
				$sql .= ' GROUP BY CONCAT(DATE(`datetime`), \'/\', HOUR(`datetime`))';
			} else if ($goupingType[1] == 'month') {
				$sql .= ' GROUP BY CONCAT(YEAR(`datetime`), \'/\', MONTH(`datetime`))';
			} else if ($goupingType[1] == 'minute') {
				$sql .= ' GROUP BY CONCAT(DATE(`datetime`), \'/\', HOUR(`datetime`), \'/\', MINUTE(`datetime`))';
			} else {
				$time = 'DATE';
				if ($goupingType[1] == 'year') {
					$time = 'YEAR';
				}
				$sql .= ' GROUP BY ' . $time . '(DATE_SUB(`datetime`, INTERVAL 1 SECOND))';
			}
		}
		$sql .= ' ORDER BY `datetime` ASC';
		if ($delta) {
			$sql .= ')c ';
		}
		$return = DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
		if ($_addFirstPreviousValue && count($return) > 0 && ($_groupingType == null || strpos($_groupingType, '::') === false)) {
			$values = array(
				'cmd_id' => $_cmd_id,
				'startTime' => $_startTime
			);
			$sql = 'SELECT ' . DB::buildField(__CLASS__);
			$sql .= ' FROM (';
			$sql .= ' (SELECT * from history WHERE value is not null AND cmd_id=:cmd_id AND `datetime`<=:startTime';
			$sql .= ' ORDER BY datetime DESC LIMIT 1';
			$sql .= ') ';
			$sql .= ' UNION ALL ';
			$sql .= ' (SELECT * from historyArch WHERE value is not null AND cmd_id=:cmd_id AND `datetime`<=:startTime';
			$sql .= ' ORDER BY datetime DESC LIMIT 1';
			$sql .= ') ';
			$sql .= ')a ';
			$sql .= ' ORDER BY datetime DESC LIMIT 1';
			$row = DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
			if ($row != null) {
				$row->setDatetime(date('Y-m-d H:i:s', strtotime($_startTime) - 2));
				array_unshift($return, $row);
			}
		}
		return $return;
	}

	public static function getOldestValue($_cmd_id, $_limit = 1) {
		$values = array(
			'cmd_id' => $_cmd_id
		);
		$_limit = intval($_limit);
		if ($_limit > 0 && $_limit < 100) {
			$sql = 'SELECT ' . DB::buildField(__CLASS__);
			$sql .= ' FROM historyArch WHERE cmd_id=:cmd_id ORDER BY `datetime` ASC LIMIT ' . $_limit;
			$result = DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, 'historyArch');
			return array_merge($result);
		}
		return array();
	}

	public static function removes($_cmd_id, $_startTime = null, $_endTime = null) {
		$values = array(
			'cmd_id' => $_cmd_id,
		);
		if ($_startTime !== null) {
			$values['startTime'] = $_startTime;
		}
		if ($_endTime !== null) {
			$values['endTime'] = $_endTime;
		}
		$sql_tmpl = 'DELETE FROM #table#
        WHERE cmd_id=:cmd_id ';
		if ($_startTime !== null) {
			$sql_tmpl .= ' AND datetime>=:startTime';
		}
		if ($_endTime !== null) {
			$sql_tmpl .= ' AND datetime<=:endTime';
		}
		DB::Prepare(str_replace('#table#', 'history', $sql_tmpl), $values, DB::FETCH_TYPE_ROW);
		DB::Prepare(str_replace('#table#', 'historyArch', $sql_tmpl), $values, DB::FETCH_TYPE_ROW);
		return true;
	}

	public static function getPlurality($_cmd_id, $_startTime = null, $_endTime = null, $_period = 'day', $_offset = 0) {
		$values = array(
			'cmd_id' => $_cmd_id,
		);
		if ($_startTime !== null) {
			$values['startTime'] = $_startTime;
		}
		if ($_endTime !== null) {
			$values['endTime'] = $_endTime;
		}
		switch ($_period) {
			case 'day':
				if ($_offset == 0) {
					$select = 'SELECT cmd_id,MAX(CAST(value AS DECIMAL(12,2))) as `value`,DATE_FORMAT(`datetime`,"%Y-%m-%d 00:00:00") as `datetime`';
				} elseif ($_offset > 0) {
					$select = 'SELECT cmd_id,MAX(CAST(value AS DECIMAL(12,2))) as `value`,DATE_FORMAT(DATE_ADD(`datetime`, INTERVAL ' . $_offset . ' HOUR),"%Y-%m-%d 00:00:00") as `datetime`';
				} else {
					$select = 'SELECT cmd_id,MAX(CAST(value AS DECIMAL(12,2))) as `value`,DATE_FORMAT(DATE_SUB(`datetime`, INTERVAL ' . abs($_offset) . ' HOUR),"%Y-%m-%d 00:00:00") as `datetime`';
				}
				break;
			case 'month':
				$select = 'SELECT cmd_id,MAX(CAST(value AS DECIMAL(12,2))) as `value`,DATE_FORMAT(`datetime`,"%Y-%m-01 00:00:00") as `datetime`';
				break;
			case 'year':
				$select = 'SELECT cmd_id,MAX(CAST(value AS DECIMAL(12,2))) as `value`,DATE_FORMAT(`datetime`,"%Y-01-01 00:00:00") as `datetime`';
				break;
			default:
				$select = 'SELECT ' . DB::buildField(__CLASS__);
				break;
		}
		switch ($_period) {
			case 'day':
				if ($_offset == 0) {
					$groupBy = ' GROUP BY date(`datetime`)';
				} elseif ($_offset > 0) {
					$groupBy = ' GROUP BY date(DATE_ADD(`datetime`, INTERVAL ' . $_offset . ' HOUR))';
				} else {
					$groupBy = ' GROUP BY date(DATE_SUB(`datetime`, INTERVAL ' . abs($_offset) . ' HOUR))';
				}
				break;
			case 'month':
				$groupBy = ' GROUP BY month(DATE_ADD(`datetime`, INTERVAL ' . $_offset . ' DAY))';
				break;
			case 'year':
				$groupBy = ' GROUP BY YEAR(DATE_ADD(`datetime`, INTERVAL ' . $_offset . ' MONTH))';
				break;
			default:
				$groupBy = '';
				break;
		}
		$sql = $select . '
        FROM (
        ' . $select . '
        FROM history
        WHERE cmd_id=:cmd_id ';
		if ($_startTime !== null) {
			$sql .= ' AND datetime>=:startTime';
		}
		if ($_endTime !== null) {
			$sql .= ' AND datetime<=:endTime';
		}
		$sql .= $groupBy;
		$sql .= ' UNION ALL
        ' . $select . '
        FROM historyArch
        WHERE cmd_id=:cmd_id';
		if ($_startTime !== null) {
			$sql .= ' AND `datetime`>=:startTime';
		}
		if ($_endTime !== null) {
			$sql .= ' AND `datetime`<=:endTime';
		}
		$sql .= $groupBy;
		$sql .= ' ) as dt ';
		$sql .= $groupBy;
		$sql .= ' ORDER BY `datetime` ASC ';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function getTemporalAvg($_cmd_id, $_startTime, $_endTime) {
		$histories = self::all($_cmd_id, $_startTime, $_endTime);
		if (is_object($lastHistory = self::byCmdIdAtDatetime($_cmd_id, $_startTime))) {
			$histories = array_merge(array($lastHistory), $histories);
		}

		if (count($histories) == 0) {
			return -1;
		}
		$cValue = $histories[0]->getValue();
		if (count($histories) == 1) {
			return $cValue;
		}
		$cTime = strtotime($_startTime);
		$sum = 0;
		foreach ($histories as $history) {
			if (strtotime($history->getDatetime()) < strtotime($_startTime)) {
				continue;
			}
			$sum += $cValue * (strtotime($history->getDatetime()) - $cTime);
			$cValue = $history->getValue();
			$cTime = strtotime($history->getDatetime());
		}
		if (strtotime($_endTime) > $cTime) {
			$sum += $cValue * (strtotime($_endTime) - $cTime);
		}
		return $sum / (strtotime($_endTime) - strtotime($_startTime));
	}

	public static function getStatistique($_cmd_id, $_startTime, $_endTime) {
		$values = array(
			'cmd_id' => $_cmd_id,
			'startTime' => $_startTime,
			'endTime' => $_endTime,
		);
		$sql = 'SELECT AVG(CAST(value AS DECIMAL(12,2))) as avg, MIN(CAST(value AS DECIMAL(12,2))) as min, MAX(CAST(value AS DECIMAL(12,2))) as max, SUM(CAST(value AS DECIMAL(12,2))) as sum, COUNT(CAST(value AS DECIMAL(12,2))) as count, STD(CAST(value AS DECIMAL(12,2))) as std, VARIANCE(CAST(value AS DECIMAL(12,2))) as variance
        FROM (
        SELECT *
        FROM history
        WHERE cmd_id=:cmd_id
        AND `datetime`>=:startTime
        AND `datetime`<=:endTime
        UNION ALL
        SELECT *
        FROM historyArch
        WHERE cmd_id=:cmd_id
        AND `datetime`>=:startTime
        AND `datetime`<=:endTime
        ) as dt';

		$result = DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW);
		if (!is_array($result)) {
			$result = array();
		}
		$values = array(
			'cmd_id' => $_cmd_id,
			'startTime' => $_startTime,
			'endTime' => $_endTime,
		);
		$sql = 'SELECT value as `last`
        FROM (
        SELECT *
        FROM history
        WHERE cmd_id=:cmd_id
        AND `datetime`>=:startTime
        AND `datetime`<=:endTime
        UNION ALL
        SELECT *
        FROM historyArch
        WHERE cmd_id=:cmd_id
        AND `datetime`>=:startTime
        AND `datetime`<=:endTime
        ) as dt
        ORDER BY `datetime` DESC
        LIMIT 1';

		$result2 = DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW);
		if (!is_array($result2)) {
			$result2 = array();
		}
		$return = array_merge($result, $result2);
		foreach ($return as $key => &$value) {
			if ($value === '') {
				$value = 0;
			}
		}
		return $return;
	}

	public static function getTendance($_cmd_id, $_startTime, $_endTime) {
		$values = array();
		foreach (self::all($_cmd_id, $_startTime, $_endTime) as $history) {
			$values[] = floatval($history->getValue());
		}
		if (count($values) == 0) {
			$x_mean = 0;
		} else {
			$x_mean = array_sum(array_keys($values)) / count($values);
		}
		if (count($values) == 0) {
			$y_mean = 0;
		} else {
			$y_mean = array_sum($values) / count($values);
		}
		$base = 0.0;
		$divisor = 0.0;
		foreach ($values as $i => $value) {
			$base += ($i - $x_mean) * ($value - $y_mean);
			$divisor += ($i - $x_mean) * ($i - $x_mean);
		}
		if ($divisor == 0) {
			return 0;
		}
		return ($base / $divisor);
	}

	public static function stateDuration($_cmd_id, $_value = null) {
		$cmd = cmd::byId($_cmd_id);
		if (!is_object($cmd)) {
			throw new Exception(__('Commande introuvable :', __FILE__) . ' ' . $_cmd_id);
		}
		if ($cmd->getIsHistorized() != 1) {
			return -2;
		}
		$histories = array_reverse(history::all($_cmd_id));
		$c = count($histories);
		if ($c == 0) {
			return -1;
		}
		$currentValue = $histories[0]->getValue();
		for ($i = 0; $i < $c - 1; $i++) {
			$nextValue = $histories[$i + 1]->getValue();
			if ($currentValue != $nextValue) {
				break;
			}
		}
		$dateTo = date('Y-m-d H:i:s');
		$duration = strtotime($dateTo) - strtotime($histories[$i]->getDatetime());
		return $duration;
	}

	public static function lastStateDuration($_cmd_id, $_value = null) {
		$cmd = cmd::byId($_cmd_id);
		if (!is_object($cmd)) {
			throw new Exception(__('Commande introuvable :', __FILE__) . ' ' . $_cmd_id);
		}
		if ($cmd->getIsHistorized() != 1) {
			return -2;
		}
		$_value = str_replace(',', '.', $_value);
		$_value = trim($_value);
		$_decimal = strlen(substr(strrchr($_value, '.'), 1));
		$histories = array_reverse(history::all($_cmd_id));
		$c = count($histories);
		if ($c == 0) {
			return -1;
		}
		$currentValue = $histories[0]->getValue();
		if (is_numeric($_value)) {
			$currentValue = round($currentValue, $_decimal);
		}
		$duration = 0;
		$dateTo = date('Y-m-d H:i:s');
		if ($_value === null || $_value == $currentValue) {
			$_value = $currentValue;
			$duration = strtotime($dateTo) - strtotime($histories[0]->getDatetime());
		}
		$started = 0;
		for ($i = 0; $i < $c; $i++) {
			$history = $histories[$i];
			$value = $history->getValue();
			if (is_numeric($_value)) {
				$value = round($value, $_decimal);
			}
			$date = $history->getDatetime();
			//same state as current:
			if ($_value == $currentValue && $_value != $value) {
				return $duration;
			}
			//different state as current:
			if ($_value != $currentValue && $i > 0) {
				$prevValue = $histories[$i - 1]->getValue();
				if (is_numeric($_value)) {
					$prevValue = round($prevValue, $_decimal);
				}
				if ($_value == $value && $_value != $prevValue) {
					$started = 1;
					$duration = 0;
				}
				if ($_value != $value && $started == 1) {
					return $duration;
				}
			}
			if ($i > 0) {
				$duration += strtotime($histories[$i - 1]->getDatetime()) - strtotime($date);
			}
		}
		return -1;
	}

	public static function lastChangeStateDuration($_cmd_id, $_value) {
		$cmd = cmd::byId($_cmd_id);
		if (!is_object($cmd)) {
			throw new Exception(__('Commande introuvable :', __FILE__) . ' ' . $_cmd_id);
		}
		if ($cmd->getIsHistorized() != 1) {
			return -2;
		}
		$_value = str_replace(',', '.', $_value);
		$_value = trim($_value);
		$_decimal = strlen(substr(strrchr($_value, '.'), 1));
		$histories = array_reverse(history::all($_cmd_id));
		$c = count($histories);
		if ($c == 0) {
			return -1;
		}
		$currentValue = $histories[0]->getValue();
		if (is_numeric($_value)) {
			$currentValue = round($currentValue, $_decimal);
		}
		$dateTo = date('Y-m-d H:i:s');
		$duration = strtotime($dateTo) - strtotime($histories[0]->getDatetime());
		if ($_value === null) {
			$_value = $currentValue;
		}
		for ($i = 0; $i < $c - 1; $i++) {
			$history = $histories[$i];
			$value = $history->getValue();
			if (is_numeric($_value)) {
				$value = round($value, $_decimal);
			}
			$date = $history->getDatetime();
			//same state as current:
			if ($_value == $currentValue) {
				$nextValue = $histories[$i + 1]->getValue();
				if (is_numeric($_value)) {
					$nextValue = round($nextValue, $_decimal);
				}
				if ($_value != $nextValue && isset($histories[$i - 1])) {
					$duration += strtotime($histories[$i - 1]->getDatetime()) - strtotime($date);
					return $duration;
				}
				if ($_value != $nextValue) {
					return $duration;
				}
			}
			//different state as current:
			if ($_value != $currentValue && $i > 0) {
				$nextValue = $histories[$i + 1]->getValue();
				if (is_numeric($_value)) {
					$nextValue = round($nextValue, $_decimal);
				}
				if ($_value == $value && $_value != $nextValue && isset($histories[$i - 1])) {
					$duration += strtotime($histories[$i - 1]->getDatetime()) - strtotime($date);
					return $duration;
				}
			}
			if ($i > 0) {
				$duration += strtotime($histories[$i - 1]->getDatetime()) - strtotime($date);
			}
		}
		return -1;
	}

	public static function stateChanges($_cmd_id, $_value = null, $_startTime = null, $_endTime = null) {
		$cmd = cmd::byId($_cmd_id);
		if (!is_object($cmd)) {
			throw new Exception(__('Commande introuvable :', __FILE__) . ' ' . $_cmd_id);
		}
		if ($_value === null) {
			$_value = $cmd->execCmd();
		}
		$_dateTime = '';

		if ($_startTime !== null) {
			$_dateTime = ' AND `datetime`>="' . $_startTime . '"';
		}

		if ($_endTime === null) {
			$_dateTime .= ' AND `datetime`<="' . date('Y-m-d H:i:s') . '"';
		} else {
			$_dateTime .= ' AND `datetime`<="' . $_endTime . '"';
		}

		if ($cmd->getSubType() != 'string') {
			$_value = str_replace(',', '.', $_value);
			$_decimal = strlen(substr(strrchr($_value, "."), 1));
			$_condition = ' ROUND(CAST(value AS DECIMAL(12,2)),' . $_decimal . ') = ' . $_value;
		} else {
			$_condition = ' value = ' . $_value;
		}

		$values = array('cmd_id' => $_cmd_id,);
		$sql = 'SELECT count(*) as changes
        FROM (SELECT t1.*
            FROM (
                SELECT *
                FROM history
                WHERE cmd_id=:cmd_id' . $_dateTime . '
                UNION ALL
                SELECT *
                FROM historyArch
                WHERE cmd_id=:cmd_id' . $_dateTime . '
            ) as t1
            WHERE cmd_id=:cmd_id' . $_dateTime . '
        ) as t1
        where ' . $_condition . '';

		$result = DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW);
		return $result['changes'];
	}

	public static function emptyHistory($_cmd_id, $_date = '') {
		$values = array(
			'cmd_id' => $_cmd_id,
		);
		if ($_date != '') {
			$values['date'] = $_date;
		}
		$sql = 'DELETE FROM history WHERE cmd_id=:cmd_id';
		if ($_date != '') {
			$sql .= ' AND `datetime` <= :date';
		}
		DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW);
		$sql = 'DELETE FROM historyArch WHERE cmd_id=:cmd_id';
		if ($_date != '') {
			$sql .= ' AND `datetime` <= :date';
		}
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW);
	}

	public static function getHistoryFromCalcul($_strcalcul, $_dateStart = null, $_dateEnd = null, $_noCalcul = false, $_groupingType = null) {
		$now = strtotime('now');
		$value = array();
		$cmd_histories = array();
		preg_match_all("/#([0-9]*)#/", $_strcalcul, $matches);
		if (count($matches[1]) > 0) {
			foreach ($matches[1] as $cmd_id) {
				if (is_numeric($cmd_id)) {
					$cmd = cmd::byId($cmd_id);
					if (is_object($cmd) && $cmd->getIsHistorized() == 1) {
						$prevDatetime = null;
						$prevValue = 0;
						$histories_cmd = $cmd->getHistory($_dateStart, $_dateEnd, $_groupingType);
						$histories_cmd_count = count($histories_cmd);
						for ($i = 0; $i < $histories_cmd_count; $i++) {
							if (!isset($cmd_histories[$histories_cmd[$i]->getDatetime()])) {
								$cmd_histories[$histories_cmd[$i]->getDatetime()] = array();
							}
							$cmd_histories[$histories_cmd[$i]->getDatetime()]['#' . $cmd_id . '#'] = $histories_cmd[$i]->getValue();
						}
					}
				}
			}
			ksort($cmd_histories);
			$prevData = null;
			foreach ($cmd_histories as $datetime => &$cmd_history) {
				if (count($matches[1]) != count($cmd_history)) {
					if ($prevData == null) {
						$prevData = $cmd_history;
						continue;
					}
					foreach ($matches[1] as $cmd_id) {
						if (!isset($cmd_history['#' . $cmd_id . '#']) && isset($prevData['#' . $cmd_id . '#'])) {
							$cmd_history['#' . $cmd_id . '#'] = $prevData['#' . $cmd_id . '#'];
						}
					}
				}
				$prevData = $cmd_history;
				if (count($matches[1]) != count($cmd_history)) {
					continue;
				}
				$datetime = floatval(strtotime($datetime . ' UTC'));
				$calcul = template_replace($cmd_history, $_strcalcul);
				if ($_noCalcul) {
					$value[$datetime] = $calcul;
					continue;
				}
				try {
					$result = floatval(jeedom::evaluateExpression($calcul));
					$value[$datetime] = $result;
				} catch (Exception $e) {
				} catch (Error $e) {
				}
			}
		} else {
			$value = $_strcalcul;
		}
		if (is_array($value)) {
			ksort($value);
		}
		return $value;
	}

	/*     * *********************Methode d'instance************************* */

	public function save($_cmd = null, $_direct = false) {
		if ($this->getValue() === null) {
			return;
		}
		global $JEEDOM_INTERNAL_CONFIG;
		if ($_cmd === null) {
			$cmd = $this->getCmd();
			if (!is_object($cmd)) {
				self::emptyHistory($this->getCmd_id());
				return;
			}
		} else {
			$cmd = $_cmd;
		}
		if ($this->getDatetime() == '') {
			$this->setDatetime(date('Y-m-d H:i:s'));
		}
		if ($cmd->getConfiguration('historizeRound') !== '' && is_numeric($cmd->getConfiguration('historizeRound')) && $cmd->getConfiguration('historizeRound') >= 0 && $this->getValue() !== null) {
			$this->setValue(round($this->getValue(), $cmd->getConfiguration('historizeRound')));
		}
		if ( $JEEDOM_INTERNAL_CONFIG['cmd']['type']['info']['subtype'][$cmd->getSubType()]['isHistorized']['canBeSmooth'] 
		    && $cmd->getConfiguration('history::smooth', config::byKey('history::smooth','core',0)) > 0 
			&& $cmd->getConfiguration('historizeMode', 'none') != 'none' 
			&& $this->getValue() !== null ) {
			if ($this->getTableName() == 'history') {
				$time = strtotime($this->getDatetime());
				$time -= $time % $cmd->getConfiguration('history::smooth', config::byKey('history::smooth','core',0));
				if ($this->getValue() == 0) {
					$this->setDatetime(date('Y-m-d H:i:00', $time + $cmd->getConfiguration('history::smooth', config::byKey('history::smooth','core',0))));
					$values = array(
						'cmd_id' => $this->getCmd_id(),
						'datetime' => date('Y-m-d H:i:00', strtotime($this->getDatetime())),
						'value' => $this->getValue(),
					);
					$sql = 'REPLACE INTO history
                    SET cmd_id=:cmd_id,
                    `datetime`=:datetime,
                    value=:value';
					DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW);
					return;
				}
				$this->setDatetime(date('Y-m-d H:i:00', $time));
				$values = array(
					'cmd_id' => $this->getCmd_id(),
					'datetime' => $this->getDatetime(),
				);
				$sql = 'SELECT `value`
                FROM history
                WHERE cmd_id=:cmd_id
                AND `datetime`=:datetime';
				$result = DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW);
				$round = (is_numeric($cmd->getConfiguration('historizeRound'))) ? $cmd->getConfiguration('historizeRound') : 2;
				if ($result !== false) {
					switch ($cmd->getConfiguration('historizeMode', 'avg')) {
						case 'avg':
							$this->setValue(round(($result['value'] + $this->getValue()) / 2, $round));
							break;
						case 'min':
							$this->setValue(round(min($result['value'], $this->getValue()), $round));
							break;
						case 'max':
							$this->setValue(round(max($result['value'], $this->getValue()), $round));
							break;
					}
					if ($result['value'] === $this->getValue()) {
						return;
					}
				}
			}
		}
		$values = array(
			'cmd_id' => $this->getCmd_id(),
			'datetime' => $this->getDatetime(),
			'value' => $this->getValue(),
		);
		if ($values['value'] === '') {
			$values['value'] = null;
		}
		$sql = 'REPLACE INTO ' . $this->getTableName() . '
        SET cmd_id=:cmd_id,
        `datetime`=:datetime,
        value=:value';
		DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW);
	}

	public function remove() {
		DB::remove($this);
	}

	/*     * **********************Getteur Setteur*************************** */

	public function getCmd_id() {
		return $this->cmd_id;
	}

	public function getCmd() {
		return cmd::byId($this->cmd_id);
	}

	public function getValue() {
		return $this->value;
	}

	public function getDatetime() {
		return $this->datetime;
	}

	public function getTableName() {
		return $this->_tableName;
	}

	public function setTableName($_tableName) {
		$this->_tableName = $_tableName;
		return $this;
	}

	public function setCmd_id($_cmd_id) {
		$this->_changed = utils::attrChanged($this->_changed, $this->cmd_id, $_cmd_id);
		$this->cmd_id = $_cmd_id;
		return $this;
	}

	public function setValue($_value) {
		$this->_changed = utils::attrChanged($this->_changed, $this->value, $_value);
		$this->value = $_value;
		return $this;
	}

	public function setDatetime($_datetime) {
		$this->_changed = utils::attrChanged($this->_changed, $this->datetime, $_datetime);
		$this->datetime = $_datetime;
		return $this;
	}

	public function getChanged() {
		return $this->_changed;
	}

	public function setChanged($_changed) {
		$this->_changed = $_changed;
		return $this;
	}
}
