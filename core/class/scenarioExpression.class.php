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

class scenarioExpression {
	/*     * *************************Attributs****************************** */

	private $id;
	private $scenarioSubElement_id;
	private $type;
	private $subtype;
	private $expression;
	private $options;
	private $order;
	private $_changed = false;

	/*     * ***********************Méthodes statiques*************************** */

	public static function byId($_id) {
		$values = array(
			'id' => $_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM ' . __CLASS__ . '
		WHERE id=:id';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function all() {
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM ' . __CLASS__;
		return DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function byscenarioSubElementId($_scenarioSubElementId) {
		$values = array(
			'scenarioSubElement_id' => $_scenarioSubElementId,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM ' . __CLASS__ . '
		WHERE scenarioSubElement_id=:scenarioSubElement_id
		ORDER BY `order`';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function searchExpression($_expression, $_options = null, $_and = true) {
		$values = array(
			'expression' => '%' . $_expression . '%',
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM ' . __CLASS__ . '
		WHERE expression LIKE :expression';
		if ($_options !== null) {
			$values['options'] = '%' . $_options . '%';
			if ($_and) {
				$sql .= ' AND options LIKE :options';
			} else {
				$sql .= ' OR options LIKE :options';
			}
		}
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function byElement($_element_id) {
		$values = array(
			'expression' => $_element_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM ' . __CLASS__ . '
		WHERE expression=:expression
		AND `type`= "element"';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function getExpressionOptions($_expression, $_options) {
		$replace = array(
			'#uid#' => 'exp' . mt_rand(),
		);
		$return = array('html' => '');
		$cmd = cmd::byId(str_replace('#', '', cmd::humanReadableToCmd($_expression)));
		if (is_object($cmd)) {
			$return['html'] = trim($cmd->toHtml('scenario', $_options));
			return $return;
		}
		$return['template'] = getTemplate('core', 'scenario', $_expression . '.default');
		$_options = is_json($_options, $_options);
		if (is_array($_options) && count($_options) > 0) {
			foreach ($_options as $key => $value) {
				$replace['#' . $key . '#'] = str_replace('"', '&quot;', $value);
			}
		}
		if (!isset($replace['#id#'])) {
			$replace['#id#'] = rand();
		}
		$return['html'] = template_replace(cmd::cmdToHumanReadable($replace), $return['template']);
		preg_match_all("/#[a-zA-Z_]*#/", $return['template'], $matches);
		foreach ($matches[0] as $value) {
			if (!isset($replace[$value])) {
				$replace[$value] = '';
			}
		}
		$return['html'] = translate::exec(template_replace($replace, $return['html']), 'core/template/scenario/' . $_expression . '.default.html');
		return $return;
	}

	public static function humanAction($_action) {
		$return = '';
		if ($_action['cmd'] == 'scenario') {
			$scenario = scenario::byId($_action['options']['scenario_id']);
			if (!is_object($scenario)) {
				$name = 'scenario ' . $_action['options']['scenario_id'];
			} else {
				$name = $scenario->getName();
			}
			$action = $_action['options']['action'];
			$return .= '<b>'.__('Scénario', __FILE__).'</b> : '.$name.' <i class="fas fa-arrow-right"></i> '.$action;
		} elseif ($_action['cmd'] == 'variable') {
			$name = $_action['options']['name'];
			$value = $_action['options']['value'];
			$return .= '<b>'.__('Variable', __FILE__).'</b> : '.$name.' <i class="fas fa-arrow-right"></i> '.$value;
		} elseif ($_action['cmd'] == 'equipement') {
			$name = eqLogic::toHumanReadable($_action['options']['eqLogic']);
			$action = $_action['options']['action'];
			switch ($_action['options']['action']) {
				case 'activate':
				$action = __('Activation de',__FILE__);
				break;
				case 'deactivate':
				$action = __('Désactivation de',__FILE__);
				break;
				case 'hide':
				$action = __('Masquage de',__FILE__);
				break;
				case 'show':
				$action = __('Affichage de',__FILE__);
				break;
			}
			$return .= $action.' : ' . $name;
		} elseif (is_object(cmd::byId(str_replace('#', '', $_action['cmd'])))) {
			$cmd = cmd::byId(str_replace('#', '', $_action['cmd']));
			$eqLogic = $cmd->getEqLogic();
			$object = $eqLogic->getObject();
			if (is_object($object)) {
				$objectName = $object->getHumanName(true, true); //$object->getDisplay('icon').' '.$object->getName();
			} else {
				$objectName = '<span class="label labelObjectHuman">'.__('Aucun',__FILE__).'</span>';
			}
			$return .= $objectName.' '.$eqLogic->getName().' <i class="fas fa-arrow-right"></i> '.$cmd->getName();
		} elseif ($_action['cmd'] != '') {
			$return .= '<b>'.$_action['cmd'].'</b>';
		}
		return trim($return);
	}

	/*     * ********************Fonctions utilisées dans le calcul des conditions********************************* */
	public static function getDatesFromPeriod($_period = '1 hour') {
		$_period = trim(strtolower($_period));
		if ($_period == 'day') $_period = '1 day';
		$_startTime = date('Y-m-d H:i:s', strtotime('-' . $_period));
		$_endTime = date('Y-m-d H:i:s');

		if ($_period == 'today') {
			$_startTime = date('Y-m-d') . ' 00:00:00';
		} elseif ($_period == 'yesterday') {
			$_startTime = date('Y-m-d', strtotime('-1 day')) . ' 00:00:00';
			$_endTime = date('Y-m-d', strtotime('-1 day')) . ' 23:59:59';
		}
		return array($_startTime, $_endTime);
	}

	public static function randText($_sValue) {
		$_sValue = self::setTags($_sValue);
		$_aValue = explode(";", $_sValue);
		try {
			$result = evaluate($_aValue);
			if (is_string($result)) {
				$result = $_aValue;
			}
		} catch (Exception $e) {
			$result = $_aValue;
		}
		if (is_array($_aValue)) {
			$nbr = mt_rand(0, count($_aValue) - 1);
			return $_aValue[$nbr];
		} else {
			return $_aValue;
		}
	}

	public static function scenario($_scenario) {
		$id = str_replace(array('scenario', '#'), '', trim($_scenario));
		$scenario = scenario::byId($id);
		if (!is_object($scenario)) {
			return -2;
		}
		$state = $scenario->getState();
		if ($scenario->getIsActive() == 0) {
			return -1;
		}
		switch ($state) {
			case 'stop':
			return 0;
			case 'in progress':
			return 1;
		}
		return -3;
	}

	public static function eqEnable($_eqLogic_id) {
		$id = str_replace(array('eqLogic', '#'), '', trim($_eqLogic_id));
		$eqLogic = eqLogic::byId($id);
		if (!is_object($eqLogic)) {
			return -2;
		}
		return $eqLogic->getIsEnable();
	}

	public static function average($_cmd_id, $_period = '1 hour') {
		$args = func_get_args();
		$_period = trim(strtolower($_period));
		if ($_period == 'day') $_period = '1 day';

		if (count($args) > 2 || strpos($_period, '#') !== false || is_numeric($_period)) {
			$values = array();
			foreach ($args as $arg) {
				if (is_numeric($arg)) {
					$values[] = $arg;
				} else {
					$value = cmd::cmdToValue($arg);
					if (is_numeric($value)) {
						$values[] = $value;
					} else {
						try {
							$values[] = evaluate($value);
						} catch (Exception $ex) {

						} catch (Error $ex) {

						}
					}
				}
			}
			return array_sum($values) / count($values);
		} else {
			$cmd = cmd::byId(trim(str_replace('#', '', $_cmd_id)));
			if (!is_object($cmd) || $cmd->getIsHistorized() == 0) {
				return '';
			}

			$dates = self::getDatesFromPeriod($_period);
			$_startTime = $dates[0];
			$_endTime = $dates[1];

			$historyStatistique = $cmd->getStatistique($_startTime, $_endTime);
			if (!isset($historyStatistique['avg']) || $historyStatistique['avg'] == '') {
				return $cmd->execCmd();
			}
			return round($historyStatistique['avg'], 1);
		}
	}

	public static function averageBetween($_cmd_id, $_startDate, $_endDate) {
		$cmd = cmd::byId(trim(str_replace('#', '', $_cmd_id)));
		if (!is_object($cmd) || $cmd->getIsHistorized() == 0) {
			return '';
		}
		$_startTime = date('Y-m-d H:i:s', strtotime(self::setTags($_startDate)));
		$_endTime = date('Y-m-d H:i:s', strtotime(self::setTags($_endDate)));
		$historyStatistique = $cmd->getStatistique($_startTime, $_endTime);
		if (!isset($historyStatistique['avg'])) {
			return '';
		}
		return round($historyStatistique['avg'], 1);
	}

	public static function max($_cmd_id, $_period = '1 hour') {
		$args = func_get_args();
		$_period = trim(strtolower($_period));
		if ($_period == 'day') $_period = '1 day';
		if (count($args) > 2 || strpos($_period, '#') !== false || is_numeric($_period)) {
			$values = array();
			foreach ($args as $arg) {
				if (is_numeric($arg)) {
					$values[] = $arg;
				} else {
					$value = cmd::cmdToValue($arg);
					if (is_numeric($value)) {
						$values[] = $value;
					} else {
						try {
							$values[] = evaluate($value);
						} catch (Exception $ex) {

						} catch (Error $ex) {

						}
					}
				}
			}
			return max($values);
		} else {
			$cmd = cmd::byId(trim(str_replace('#', '', $_cmd_id)));
			if (!is_object($cmd) || $cmd->getIsHistorized() == 0) {
				return '';
			}

			$dates = self::getDatesFromPeriod($_period);
			$_startTime = $dates[0];
			$_endTime = $dates[1];

			$historyStatistique = $cmd->getStatistique($_startTime, $_endTime);
			if (!isset($historyStatistique['max']) || $historyStatistique['max'] == '') {
				return round($cmd->execCmd(), 1);
			}
			return round($historyStatistique['max'], 1);
		}
	}

	public static function color_gradient($_from_color, $_to_color, $_min,$_max,$_value) {
		if(!is_numeric($_value)){
			$value = round(jeedom::evaluateExpression($_value));
		}else{
			$value = round($_value);
		}
		$graduations = $_max - $_min - 1;
		$value -= $_min + 1;
		$startcol = str_replace('#', '', $_from_color);
		$endcol = str_replace('#', '', $_to_color);
		$RedOrigin = hexdec(substr($startcol, 1, 2));
		$GrnOrigin = hexdec(substr($startcol, 3, 2));
		$BluOrigin = hexdec(substr($startcol, 5, 2));
		if ($graduations >= 2) {
			$GradientSizeRed = (hexdec(substr($endcol, 1, 2)) - $RedOrigin) / $graduations;
			$GradientSizeGrn = (hexdec(substr($endcol, 3, 2)) - $GrnOrigin) / $graduations;
			$GradientSizeBlu = (hexdec(substr($endcol, 5, 2)) - $BluOrigin) / $graduations;
			for ($i = 0; $i <= $graduations; $i++) {
				$RetVal[$i] = strtoupper("#" . str_pad(dechex($RedOrigin + ($GradientSizeRed * $i)), 2, '0', STR_PAD_LEFT) .
				str_pad(dechex($GrnOrigin + ($GradientSizeGrn * $i)), 2, '0', STR_PAD_LEFT) .
				str_pad(dechex($BluOrigin + ($GradientSizeBlu * $i)), 2, '0', STR_PAD_LEFT));
			}
		} elseif ($graduations == 1) {
			$RetVal[] = $_from_color;
			$RetVal[] = $_to_color;
		} else {
			$RetVal[] = $_from_color;
		}
		if(isset($RetVal[$value])){
			return $RetVal[$value];
		}
		if($_value <= $_min){
			return $RetVal[0];
		}
		return $RetVal[count($RetVal) - 1];
	}

	public static function maxBetween($_cmd_id, $_startDate, $_endDate) {
		$cmd = cmd::byId(trim(str_replace('#', '', $_cmd_id)));
		if (!is_object($cmd) || $cmd->getIsHistorized() == 0) {
			return '';
		}
		$_startTime = date('Y-m-d H:i:s', strtotime(self::setTags($_startDate)));
		$_endTime = date('Y-m-d H:i:s', strtotime(self::setTags($_endDate)));
		$historyStatistique = $cmd->getStatistique($_startTime, $_endTime);
		if (!isset($historyStatistique['max'])) {
			return '';
		}
		return round($historyStatistique['max'], 1);
	}

	public static function wait($_condition, $_timeout = 7200) {
		$result = false;
		$occurence = 0;
		$limit = 7200;
		$timeout = jeedom::evaluateExpression($_timeout);
		$limit = (is_numeric($timeout)) ? $timeout : 7200;
		while ($result !== true) {
			$result = jeedom::evaluateExpression($_condition);
			if ($occurence > $limit) {
				return 0;
			}
			$occurence++;
			sleep(1);
		}
		return 1;
	}

	public static function min($_cmd_id, $_period = '1 hour') {
		$args = func_get_args();
		$_period = trim(strtolower($_period));
		if ($_period == 'day') $_period = '1 day';
		if (count($args) > 2 || strpos($_period, '#') !== false || is_numeric($_period)) {
			$values = array();
			foreach ($args as $arg) {
				if (is_numeric($arg)) {
					$values[] = $arg;
				} else {
					$value = cmd::cmdToValue($arg);
					if (is_numeric($value)) {
						$values[] = $value;
					} else {
						try {
							$values[] = evaluate($value);
						} catch (Exception $ex) {

						} catch (Error $ex) {

						}
					}
				}
			}
			return min($values);
		} else {
			$cmd = cmd::byId(trim(str_replace('#', '', $_cmd_id)));
			if (!is_object($cmd) || $cmd->getIsHistorized() == 0) {
				return '';
			}

			$dates = self::getDatesFromPeriod($_period);
			$_startTime = $dates[0];
			$_endTime = $dates[1];

			$historyStatistique = $cmd->getStatistique($_startTime, $_endTime);
			if (!isset($historyStatistique['min']) || $historyStatistique['min'] == '') {
				return round($cmd->execCmd(), 1);
			}
			return round($historyStatistique['min'], 1);
		}
	}

	public static function minBetween($_cmd_id, $_startDate, $_endDate) {
		$cmd = cmd::byId(trim(str_replace('#', '', $_cmd_id)));
		if (!is_object($cmd) || $cmd->getIsHistorized() == 0) {
			return '';
		}
		$_startTime = date('Y-m-d H:i:s', strtotime(self::setTags($_startDate)));
		$_endTime = date('Y-m-d H:i:s', strtotime(self::setTags($_endDate)));
		$historyStatistique = $cmd->getStatistique($_startTime, $_endTime);
		if (!isset($historyStatistique['min'])) {
			return '';
		}
		return round($historyStatistique['min'], 1);
	}

	public static function median() {
		$args = func_get_args();
		$values = array();
		foreach ($args as $arg) {
			if (is_numeric($arg)) {
				$values[] = $arg;
			} else {
				$value = cmd::cmdToValue($arg);
				if (is_numeric($value)) {
					$values[] = $value;
				} else {
					try {
						$values[] = evaluate($value);
					} catch (Exception $ex) {

					} catch (Error $ex) {

					}
				}
			}
		}
		if (count($values) < 1) {
			return 0;
		}
		if (count($values) == 1) {
			return $values[0];
		}
		sort($values);
		return $values[round(count($values) / 2) - 1];
	}

	public static function avg() {
		$args = func_get_args();
		$values = array();
		foreach ($args as $arg) {
			if (is_numeric($arg)) {
				$values[] = $arg;
			} else {
				$value = cmd::cmdToValue($arg);
				if (is_numeric($value)) {
					$values[] = $value;
				} else {
					try {
						$values[] = evaluate($value);
					} catch (Exception $ex) {

					} catch (Error $ex) {

					}
				}
			}
		}
		if (count($values) < 1) {
			return 0;
		}
		if (count($values) == 1) {
			return $values[0];
		}
		return array_sum($values)/count($values);
	}

	public static function tendance($_cmd_id, $_period = '1 hour', $_threshold = '') {
		$cmd = cmd::byId(trim(str_replace('#', '', $_cmd_id)));
		if (!is_object($cmd)) {
			return '';
		}
		if ($cmd->getIsHistorized() == 0) {
			return '';
		}

		$dates = self::getDatesFromPeriod($_period);
		$_startTime = $dates[0];
		$_endTime = $dates[1];

		$tendance = $cmd->getTendance($_startTime, $_endTime);
		if ($_threshold != '') {
			$maxThreshold = $_threshold;
			$minThreshold = -$_threshold;
		} else {
			$maxThreshold = config::byKey('historyCalculTendanceThresholddMax');
			$minThreshold = config::byKey('historyCalculTendanceThresholddMin');
		}
		if ($tendance > $maxThreshold) {
			return 1;
		}
		if ($tendance < $minThreshold) {
			return -1;
		}
		return 0;
	}

	public static function lastStateDuration($_cmd_id, $_value = null) {
		return history::lastStateDuration(str_replace('#', '', $_cmd_id), $_value);
	}

	public static function age($_cmd_id = '') {
		$cmd = cmd::byId(str_replace('#', '', cmd::humanReadableToCmd($_cmd_id)));
		if (!is_object($cmd) || $cmd->getType() != 'info') {
			return -1;
		}
		$cmd->execCmd();
		return strtotime('now') - strtotime($cmd->getCollectDate());
	}

	public static function stateChanges($_cmd_id, $_value = null, $_period = '1 hour') {
		if (!is_numeric(str_replace('#', '', $_cmd_id))) {
			$cmd = cmd::byId(str_replace('#', '', cmd::humanReadableToCmd($_cmd_id)));
		} else {
			$cmd = cmd::byId(str_replace('#', '', $_cmd_id));
		}
		if (!is_object($cmd) || $cmd->getIsHistorized() == 0) {
			return '';
		}
		$cmd_id = $cmd->getId();
		$_period = trim(strtolower($_period));
		$args = func_num_args();
		if ($args == 2) {
			if (is_numeric(func_get_arg(1))) {
				$_value = func_get_arg(1);
			} else {
				$_period = func_get_arg(1);
				$_value = null;
			}
		}

		$dates = self::getDatesFromPeriod($_period);
		$_startTime = $dates[0];
		$_endTime = $dates[1];
		return history::stateChanges($cmd_id, $_value, $_startTime, $_endTime);
	}

	public static function stateChangesBetween($_cmd_id, $_value, $_startDate, $_endDate = null) {
		if (!is_numeric(str_replace('#', '', $_cmd_id))) {
			$cmd = cmd::byId(str_replace('#', '', cmd::humanReadableToCmd($_cmd_id)));
		} else { $cmd = cmd::byId(str_replace('#', '', $_cmd_id));}
		if (!is_object($cmd) || $cmd->getIsHistorized() == 0) {
			return '';
		}
		$cmd_id = $cmd->getId();

		$args = func_num_args();
		if ($args == 3) {
			$_startDate = func_get_arg(1);
			$_endDate = func_get_arg(2);
			$_value = null;
		}
		$_startTime = date('Y-m-d H:i:s', strtotime(self::setTags($_startDate)));
		$_endTime = date('Y-m-d H:i:s', strtotime(self::setTags($_endDate)));

		return history::stateChanges($cmd_id, $_value, $_startTime, $_endTime);
	}

	public static function duration($_cmd_id, $_value, $_period = '1 hour') {
		$cmd_id = str_replace('#', '', $_cmd_id);
		if (!is_numeric($cmd_id)) {
			$cmd_id = cmd::byId(str_replace('#', '', cmd::humanReadableToCmd($_cmd_id)));
		}
		$cmd = cmd::byId($cmd_id);
		if (!is_object($cmd) || $cmd->getIsHistorized() == 0) {
			return '';
		}

		$dates = self::getDatesFromPeriod($_period);
		$_startTime = $dates[0];
		$_endTime = $dates[1];

		$_value = str_replace(',', '.', $_value);
		$_decimal = strlen(substr(strrchr($_value, "."), 1));

		$histories = $cmd->getHistory();
		if (count($histories) == 0) {
			return '';
		}
		$duration = 0;
		$lastDuration = strtotime($histories[0]->getDatetime());
		$lastValue = $histories[0]->getValue();
		foreach ($histories as $history) {
			if ($history->getDatetime() >= $_startTime) {
				if ($history->getDatetime() <= $_endTime) {
					if ($lastValue == $_value) {
						$duration = $duration + (strtotime($history->getDatetime()) - $lastDuration);
					}
				} else {
					if ($lastValue == $_value) {
						$duration = $duration + (strtotime($_endTime) - $lastDuration);
					}
					break;
				}
				$lastDuration = strtotime($history->getDatetime());
			} else {
				$lastDuration = strtotime($_startTime);
			}
			$lastValue = round($history->getValue(), $_decimal);
		}
		$endTime = strtotime($_endTime);
		if ($lastValue == $_value && $lastDuration <= $endTime) {
			$duration = $duration + ($endTime - $lastDuration);
		}
		return floor($duration / 60);
	}

	public static function durationBetween($_cmd_id, $_value, $_startDate, $_endDate) {
		if (!is_numeric(str_replace('#', '', $_cmd_id))) {
			$cmd = cmd::byId(str_replace('#', '', cmd::humanReadableToCmd($_cmd_id)));
		} else { $cmd = cmd::byId(str_replace('#', '', $_cmd_id));}
		if (!is_object($cmd) || $cmd->getIsHistorized() == 0) {
			return '';
		}

		$_startTime = date('Y-m-d H:i:s', strtotime(self::setTags($_startDate)));
		$_endTime = date('Y-m-d H:i:s', strtotime(self::setTags($_endDate)));
		$_value = str_replace(',', '.', $_value);
		$_decimal = strlen(substr(strrchr($_value, "."), 1));

		$histories = $cmd->getHistory();

		$duration = 0;
		$lastDuration = strtotime($histories[0]->getDatetime());
		$lastValue = $histories[0]->getValue();

		foreach ($histories as $history) {
			if ($history->getDatetime() >= $_startTime) {
				if ($history->getDatetime() <= $_endTime) {
					if ($lastValue == $_value) {
						$duration = $duration + (strtotime($history->getDatetime()) - $lastDuration);
					}
				} else {
					if ($lastValue == $_value) {
						$duration = $duration + (strtotime($_endTime) - $lastDuration);
					}
					break;
				}
				$lastDuration = strtotime($history->getDatetime());
			} else {
				$lastDuration = strtotime($_startTime);
			}
			$lastValue = round($history->getValue(), $_decimal);
		}
		if ($lastValue == $_value && $lastDuration <= strtotime($_endTime)) {
			$duration = $duration + (strtotime($_endTime) - $lastDuration);
		}
		return floor($duration / 60);
	}

	public static function lastBetween($_cmd_id, $_startDate, $_endDate) {
		$cmd = cmd::byId(trim(str_replace('#', '', $_cmd_id)));
		if (!is_object($cmd) || $cmd->getIsHistorized() == 0) {
			return '';
		}
		$_startTime = date('Y-m-d H:i:s', strtotime(self::setTags($_startDate)));
		$_endTime = date('Y-m-d H:i:s', strtotime(self::setTags($_endDate)));
		$historyStatistique = $cmd->getStatistique($_startTime, $_endTime);
		if(!isset($historyStatistique['last']) || $historyStatistique['last'] === ''){
			return '';
		}
		return round($historyStatistique['last'], 1);
	}

	public static function statistics($_cmd_id, $_calc, $_period = '1 hour') {
		$cmd = cmd::byId(trim(str_replace('#', '', $_cmd_id)));
		if (!is_object($cmd) || $cmd->getIsHistorized() == 0) {
			return '';
		}

		$dates = self::getDatesFromPeriod($_period);
		$_startTime = $dates[0];
		$_endTime = $dates[1];

		$historyStatistique = $cmd->getStatistique($_startTime, $_endTime);
		if (!isset($historyStatistique['min']) || $historyStatistique['min'] == '') {
			return $cmd->execCmd();
		}
		$_calc = str_replace(' ', '', $_calc);
		return $historyStatistique[$_calc];
	}

	public static function statisticsBetween($_cmd_id, $_calc, $_startDate, $_endDate) {
		$cmd = cmd::byId(trim(str_replace('#', '', $_cmd_id)));
		if (!is_object($cmd) || $cmd->getIsHistorized() == 0) {
			return '';
		}
		$_calc = str_replace(' ', '', $_calc);
		$_startTime = date('Y-m-d H:i:s', strtotime(self::setTags($_startDate)));
		$_endTime = date('Y-m-d H:i:s', strtotime(self::setTags($_endDate)));
		$historyStatistique = $cmd->getStatistique(self::setTags($_startTime), self::setTags($_endTime));
		return $historyStatistique[$_calc];
	}

	public static function variable($_name, $_default = '') {
		$_name = trim(trim(trim($_name), '"'));
		$dataStore = dataStore::byTypeLinkIdKey('scenario', -1, trim($_name));
		if (is_object($dataStore)) {
			$value = $dataStore->getValue($_default);
			return $value;
		}
		return $_default;
	}

	public static function stateDuration($_cmd_id, $_value = null) {
		return history::stateDuration(str_replace('#', '', $_cmd_id), $_value);
	}

	public static function lastChangeStateDuration($_cmd_id, $_value) {
		return history::lastChangeStateDuration(str_replace('#', '', $_cmd_id), $_value);
	}

	public static function odd($_value) {
		$_value = intval(evaluate(self::setTags($_value)));
		return ($_value % 2) ? 1 : 0;
	}

	public static function lastScenarioExecution($_scenario_id) {
		$scenario = scenario::byId(str_replace(array('#scenario', '#'), '', $_scenario_id));
		if (!is_object($scenario)) {
			return 0;
		}
		return strtotime('now') - strtotime($scenario->getLastLaunch());
	}

	public static function collectDate($_cmd_id, $_format = 'Y-m-d H:i:s') {
		$cmd = cmd::byId(trim(str_replace('#', '', cmd::humanReadableToCmd('#' . str_replace('#', '', $_cmd_id) . '#'))));
		if (!is_object($cmd)) {
			return -1;
		}
		if ($cmd->getType() != 'info') {
			return -2;
		}
		$cmd->execCmd();
		return date($_format, strtotime($cmd->getCollectDate()));
	}

	public static function valueDate($_cmd_id, $_format = 'Y-m-d H:i:s') {
		$cmd = cmd::byId(trim(str_replace('#', '', cmd::humanReadableToCmd('#' . str_replace('#', '', $_cmd_id) . '#'))));
		if (!is_object($cmd)) {
			return '';
		}
		$cmd->execCmd();
		return date($_format, strtotime($cmd->getValueDate()));
	}

	public static function lastCommunication($_eqLogic_id, $_format = 'Y-m-d H:i:s') {
		$eqLogic = eqLogic::byId(trim(str_replace(array('#','#eqLogic','eqLogic'), '', eqLogic::fromHumanReadable('#' . str_replace('#', '', $_eqLogic_id) . '#'))));
		if (!is_object($eqLogic)) {
			return  -1;
		}
		return date($_format, strtotime($eqLogic->getStatus('lastCommunication', date('Y-m-d H:i:s'))));
	}

	public static function value($_cmd_id) {
		$cmd = cmd::byId(trim(str_replace('#', '', cmd::humanReadableToCmd('#' . str_replace('#', '', $_cmd_id) . '#'))));
		if (!is_object($cmd)) {
			return '';
		}
		return $cmd->execCmd();
	}

	public static function randomColor($_rangeLower, $_rangeHighter) {
		$value = rand($_rangeLower, $_rangeHighter);
		$color_range = 85;
		$color = new stdClass();
		$color->red = $_rangeLower;
		$color->green = $_rangeLower;
		$color->blue = $_rangeLower;
		if ($value < $color_range * 1) {
			$color->red += $color_range - $value;
			$color->green += $value;
		} elseif ($value < $color_range * 2) {
			$color->green += $color_range - $value;
			$color->blue += $value;
		} elseif ($value < $color_range * 3) {
			$color->blue += $color_range - $value;
			$color->red += $value;
		}
		$color->red = ($color->red < 0) ? dechex(0) : dechex(round($color->red));
		$color->blue = ($color->blue < 0) ? dechex(0) : dechex(round($color->blue));
		$color->green = ($color->green < 0) ? dechex(0) : dechex(round($color->green));
		$color->red = (strlen($color->red) == 1) ? '0' . $color->red : $color->red;
		$color->green = (strlen($color->green) == 1) ? '0' . $color->green : $color->green;
		$color->blue = (strlen($color->blue) == 1) ? '0' . $color->blue : $color->blue;
		return '#' . $color->red . $color->green . $color->blue;
	}

	public static function trigger($_name = '', &$_scenario = null) {
		if ($_scenario !== null) {
			if (trim($_name) == '') {
				return $_scenario->getRealTrigger();
			}
			if ($_name == $_scenario->getRealTrigger()) {
				return 1;
			}
		}
		return 0;
	}

	public static function triggerValue(&$_scenario = null) {
		if ($_scenario !== null) {
			$cmd = cmd::byId(str_replace('#', '', $_scenario->getRealTrigger()));
			if (is_object($cmd)) {
				return $cmd->execCmd();
			}
		}
		return false;
	}

	public static function round($_value, $_decimal = 0) {
		$_value = self::setTags($_value);
		try {
			$result = evaluate($_value);
			if (is_string($result)) {
				$result = $_value;
			}
		} catch (Exception $e) {
			$result = $_value;
		}
		return round(floatval(str_replace(',', '.', $result)), $_decimal);
	}

	public static function time_op($_time, $_value = 0 ) {
		$_time = self::setTags($_time);
		$_value = self::setTags($_value);
		$_time = trim($_time);
		$t = explode(":",$_time);
		if(count($t) >= 2) {
			$_time = $t[0].sprintf("%02d",$t[1]);
		}
		if ( ($lg=strlen($_time)) < 4 ) {
			$_time = str_repeat("0",4-$lg).$_time;
		} else {
			$_time = substr($_time,0,4);
		}
		$date = DateTime::createFromFormat('Gi', $_time);
		if ($date === false) {
			return -1;
		}
		if ($_value > 0) {
			$date->add(new DateInterval('PT' . abs($_value) . 'M'));
		} else {
			$date->sub(new DateInterval('PT' . abs($_value) . 'M'));
		}
		return $date->format('Gi');
	}

	public static function time_between($_time, $_start, $_end) {
		$_time = self::setTags($_time);
		$_start = self::setTags($_start);
		$_end = self::setTags($_end);
		if ($_start < $_end) {
			$result = (($_time >= $_start) && ($_time < $_end)) ? 1 : 0;
		} else {
			$result = (($_time >= $_start) || ($_time < $_end)) ? 1 : 0;
		}
		return $result;
	}

	public static function time_diff($_date1, $_date2, $_format = 'd', $_rnd = 2) {
		$d1 = self::setTags($_date1);
		$date1 = new DateTime($d1);
		$d2 = self::setTags($_date2);
		$date2 = new DateTime($d2);
		$duree = $date2->getTimestamp() - $date1->getTimestamp();
		$dureeAbs = abs($duree);
		switch( trim($_format )) {
			case 's': return $dureeAbs; // en secondes
			case 'sf': return $duree;   // en secondes avec signe
			case 'm': return floor($dureeAbs/60); // en minutes
			case 'mf': return round($duree/60,$_rnd); // en minutes décimales avec signe
			case 'h': return floor($dureeAbs/3600); // en heures
			case 'hf': return round($duree/3600,$_rnd); // en heures décimales  avec signe
			case 'dhms':
			$j = floor($dureeAbs/86400); $dureeAbs %= 86400;
			$h = floor($dureeAbs/3600); $dureeAbs %= 3600;
			$m = floor($dureeAbs/60); $dureeAbs %= 60;
			$s = $dureeAbs;
			$ret = '';
			if ($j > 0) $ret .= "${j}j ";
			if ($h > 0) $ret .= "${h}h ";
			if ($m > 0) $ret .= "${m}min ";
			if ($s > 0) $ret .= "${s}s";
			return(trim($ret));
			case 'df': return round($duree/86400,$_rnd); // en jours decimaux avec signe
			default: return floor($dureeAbs/86400); // en jours
		}
	}

	public static function time($_value) {
		$_value = self::setTags($_value);
		try {
			$result = evaluate($_value);
			if (is_string($result)) {
				$result = $_value;
			}
		} catch (Exception $e) {
			$result = $_value;
		}
		if ($result < 0) {
			return -1;
		}
		if (($result % 100) > 59) {
			if (strpos($_value, '-') !== false) {
				$result -= 40;
			} else {
				$result += 40;
			}

		}
		return $result;
	}

	public static function formatTime($_time) {
		$_time = self::setTags($_time);
		if (strlen($_time) > 3) {
			return substr($_time, 0, 2) . 'h' . substr($_time, 2, 2);
		} elseif (strlen($_time) > 2) {
			return substr($_time, 0, 1) . 'h' . substr($_time, 1, 2);
		} elseif (strlen($_time) > 1) {
			return '00h' . substr($_time, 0, 2);
		} else {
			return '00h0' . substr($_time, 0, 1);
		}
	}

	public static function name($_type, $_cmd_id) {
		$cmd = cmd::byId(str_replace('#', '', $_cmd_id));
		if (!is_object($cmd)) {
			$cmd = cmd::byId(trim(str_replace('#', '', cmd::humanReadableToCmd('#' . str_replace('#', '', $_cmd_id) . '#'))));
		}
		if (!is_object($cmd)) {
			return __('Commande non trouvée', __FILE__);
		}
		switch ($_type) {
			case 'cmd':
			return $cmd->getName();
			case 'eqLogic':
			return $cmd->getEqLogic()->getName();
			case 'object':
			$object = $cmd->getEqLogic()->getObject();
			if (!is_object($object)) {
				return __('Aucun', __FILE__);
			}
			return $object->getName();
		}
		return __('Type inconnu', __FILE__);
	}

	public static function getRequestTags($_expression) {
		$return = array();
		preg_match_all("/#([a-zA-Z0-9]*)#/", $_expression, $matches);
		if (count($matches) == 0) {
			return $return;
		}
		$matches = array_unique($matches[0]);
		$replace = array(
			'heure' => 'hour',
			'jour' => 'day',
			'mois' => 'month',
			'annee' => 'year',
			'semaine' => 'week'
		);
		foreach($matches as &$tag) {
			$tag = str_replace(array_keys($replace),$replace,$tag);
			switch ($tag) {
				case '#seconde#':
				$return['#seconde#'] = (int) date('s');
				break;
				case '#hour#':
				$return['#hour#'] = (int) date('G');
				break;
				case '#hour12#':
				$return['#hour12#'] = (int) date('g');
				break;
				case '#minute#':
				$return['#minute#'] = (int) date('i');
				break;
				case '#day#':
				$return['#day#'] = (int) date('d');
				break;
				case '#month#':
				$return['#month#'] = (int) date('m');
				break;
				case '#year#':
				$return['#year#'] = (int) date('Y');
				break;
				case '#time#':
				$return['#time#'] = date('Gi');
				break;
				case '#timestamp#':
				$return['#timestamp#'] = time();
				break;
				case '#seconde#':
				$return['#seconde#'] = (int) date('s');
				break;
				case '#date#':
				$return['#date#'] = date('md');
				break;
				case '#week#':
				$return['#week#'] = date('W');
				break;
				case '#sday#':
				$return['#sday#'] = date_fr(date('l'));
				break;
				case '#smonth#':
				$return['#smonth#'] = date_fr(date('F'));
				break;
				case '#nday#':
				$return['#nday#'] = (int) date('w');
				break;
				case '#jeedom_name#':
				$return['#jeedom_name#'] = config::byKey('name');
				break;
				case '#hostname#':
				$return['#hostname#'] = gethostname();
				break;
				case '#IP#':
				$return['#IP#'] = network::getNetworkAccess('internal', 'ip', '', false);
				break;
				case '#trigger#':
				$return['#trigger#'] = '';
				break;
				case '#trigger_value#':
				$return['#trigger_value#'] = '';
				break;
			}
		}
		$new = array();
		foreach ($return as $key => $value) {
			$new[str_replace($replace,array_keys($replace),$key)] = $value;
		}
		return array_merge($return,$new);
	}

	public static function tag(&$_scenario = null, $_name, $_default = '') {
		if ($_scenario == null) {
			return $_default;
		}
		$tags = $_scenario->getTags();
		if (isset($tags['#' . $_name . '#'])) {
			return $tags['#' . $_name . '#'];
		}
		return $_default;
	}

	public static function setTags($_expression, &$_scenario = null, $_quote = false, $_nbCall = 0) {
		if(config::byKey('expression::autoQuote','core',1) == 0){
			$_quote = false;
		}
		if (file_exists(__DIR__ . '/../../data/php/user.function.class.php')) {
			require_once __DIR__ . '/../../data/php/user.function.class.php';
		}
		if ($_nbCall > 10) {
			return $_expression;
		}
		$replace1 = self::getRequestTags($_expression);
		if ($_scenario !== null && count($_scenario->getTags()) > 0) {
			$replace1 = array_merge($replace1, $_scenario->getTags());
		}

		if (is_object($_scenario)) {
			$cmd = cmd::byId(str_replace('#', '', $_scenario->getRealTrigger()));
			if (is_object($cmd)) {
				$replace1['#trigger#'] = $cmd->getHumanName();
				$replace1['#trigger_value#'] = $cmd->execCmd();
			} else {
				$replace1['#trigger#'] = $_scenario->getRealTrigger();
			}
		}
		if ($_quote) {
			foreach ($replace1 as &$value) {
				if (strpos($value, ' ') !== false || preg_match("/[a-zA-Z]/", $value) || $value === '') {
					$value = '"' . trim($value, '"') . '"';
				}
			}
		}
		$replace2 = array();
		if (!is_string($_expression)) {
			return $_expression;
		}
		preg_match_all("/([a-zA-Z][a-zA-Z_]*?)\((.*?)\)/", $_expression, $matches, PREG_SET_ORDER);
		if (is_array($matches)) {
			foreach ($matches as $match) {
				$function = $match[1];
				$replace_string = $match[0];
				if (substr_count($match[2], '(') != substr_count($match[2], ')')) {
					$pos = strpos($_expression, $match[2]) + strlen($match[2]);
					while (substr_count($match[2], '(') > substr_count($match[2], ')')) {
						$match[2] .= $_expression[$pos];
						$pos++;
						if ($pos > strlen($_expression)) {
							break;
						}
					}
					$arguments = self::setTags($match[2], $_scenario, $_quote, $_nbCall++);
					while ($arguments[0] == '(' && $arguments[strlen($arguments) - 1] == ')') {
						$arguments = substr($arguments, 1, -1);
					}
					$result = str_replace($match[2], $arguments, $_expression);
					while (substr_count($result, '(') > substr_count($result, ')')) {
						$result .= ')';
					}
					$result = self::setTags($result, $_scenario, $_quote, $_nbCall++);
					return cmd::cmdToValue(str_replace(array_keys($replace1), array_values($replace1), $result), $_quote);
				} else {
					$arguments = explode(',', $match[2]);
				}
				if (method_exists(__CLASS__, $function)) {
					if ($function == 'trigger') {
						if (!isset($arguments[0])) {
							$arguments[0] = '';
						}
						$replace2[$replace_string] = self::trigger($arguments[0], $_scenario);
					} elseif ($function == 'triggerValue') {
						$replace2[$replace_string] = self::triggerValue($_scenario);
					} elseif ($function == 'tag') {
						if (!isset($arguments[0])) {
							$arguments[0] = '';
						}
						if (!isset($arguments[1])) {
							$arguments[1] = '';
						}
						$replace2[$replace_string] = self::tag($_scenario, $arguments[0], $arguments[1]);
					} else {
						$replace2[$replace_string] = call_user_func_array(__CLASS__ . "::" . $function, $arguments);
					}
				} else if (class_exists('userFunction') && method_exists('userFunction', $function)) {
					$replace2[$replace_string] = call_user_func_array('userFunction' . "::" . $function, $arguments);
				} else {
					if (function_exists($function)) {
						foreach ($arguments as &$argument) {
							$argument = trim(evaluate(self::setTags($argument, $_scenario, $_quote)));
						}
						$replace2[$replace_string] = call_user_func_array($function, $arguments);
					}
				}
				if ($_quote && isset($replace2[$replace_string]) && (strpos($replace2[$replace_string], ' ') !== false || preg_match("/[a-zA-Z#]/", $replace2[$replace_string]) || $replace2[$replace_string] === '')) {
					$replace2[$replace_string] = '"' . trim($replace2[$replace_string], '"') . '"';
				}
			}
		}
		$return = cmd::cmdToValue(str_replace(array_keys($replace1), array_values($replace1), str_replace(array_keys($replace2), array_values($replace2), $_expression)), $_quote);
		return $return;
	}

	public static function createAndExec($_type, $_cmd, $_options = null) {
		$scenarioExpression = new self();
		$scenarioExpression->setType($_type);
		$scenarioExpression->setExpression($_cmd);
		if (is_array($_options)) {
			foreach ($_options as $key => $value) {
				$scenarioExpression->setOptions($key, $value);
			}
		}
		return $scenarioExpression->execute();
	}

	/*     * *********************Methode d'instance************************* */

	public function checkBackground() {
		if ($this->getOptions('background', 0) == 0) {
			return;
		}
		if (in_array($this->getExpression(), array('wait', 'sleep', 'stop', 'scenario_return'))) {
			$this->setOptions('background', 0);
		}
		return;
	}

	public function execute(&$scenario = null) {
		if ($scenario !== null && !$scenario->getDo()) {
			return;
		}
		if ($this->getOptions('enable', 1) == 0) {
			return;
		}
		$this->checkBackground();
		if ($this->getOptions('background', 0) == 1) {
			$key = 'scenarioElement' . config::genKey(10);
			while (cache::exist($key)) {
				$key = 'scenarioElement' . config::genKey(10);
			}
			cache::set($key, array('scenarioExpression' => $this, 'scenario' => $scenario), 60);
			$cmd = __DIR__ . '/../php/jeeScenarioExpression.php';
			$cmd .= ' key=' . $key;
			$this->setLog($scenario, __('Execution du lancement en arriere plan : ', __FILE__) . $key);
			system::php($cmd . ' >> /dev/null 2>&1 &');
			return;
		}
		$message = '';
		try {
			if ($this->getType() == 'element') {
				$element = scenarioElement::byId($this->getExpression());
				if (is_object($element)) {
					$this->setLog($scenario, __('Exécution d\'un bloc élément : ', __FILE__) . $this->getExpression());
					return $element->execute($scenario);
				}
				return;
			}
			$options = $this->getOptions();
			if (isset($options['enable'])) {
				unset($options['enable']);
			}
			if (is_array($options) && $this->getExpression() != 'wait') {
				foreach ($options as $key => $value) {
					if ($this->getExpression() == 'event' && $key == 'cmd') {
						continue;
					}
					if (is_string($value)) {
						$options[$key] = self::setTags($value, $scenario);
					}
				}
			}
			if ($this->getType() == 'action') {
				if ($this->getExpression() == 'icon') {
					if ($scenario !== null) {
						$options = $this->getOptions();
						$this->setLog($scenario, __('Changement de l\'icone du scénario : ', __FILE__) . $options['icon']);
						$scenario->setDisplay('icon', $options['icon']);
						$scenario->save();
					}
					return;
				} elseif ($this->getExpression() == 'wait') {
					if (!isset($options['condition'])) {
						return;
					}
					$result = false;
					$occurence = 0;
					$limit = 7200;
					if (isset($options['timeout'])) {
						$timeout = jeedom::evaluateExpression($options['timeout']);
						$limit = (is_numeric($timeout)) ? $timeout : 7200;
					}
					while (!$result) {
						$expression = self::setTags($options['condition'], $scenario, true);
						$result = evaluate($expression);
						if ($occurence > $limit) {
							$this->setLog($scenario, __('[Wait] Condition valide par dépassement de temps : ', __FILE__) . $expression . ' => ' . $result);
							return;
						}
						$occurence++;
						sleep(1);
					}
					$this->setLog($scenario, __('[Wait] Condition valide : ', __FILE__) . $expression . ' => ' . $result);
					return;
				} elseif ($this->getExpression() == 'sleep') {
					if (isset($options['duration'])) {
						try {
							$options['duration'] = floatval(evaluate($options['duration']));
						} catch (Exception $e) {

						} catch (Error $e) {

						}
						if (is_numeric($options['duration']) && $options['duration'] > 0) {
							$this->setLog($scenario, __('Pause de ', __FILE__) . $options['duration'] . __(' seconde(s)', __FILE__));
							if ($options['duration'] < 1) {
								usleep($options['duration'] * 1000000);
								return;
							}

							sleep($options['duration']);
							return;
						}
					}
					$this->setLog($scenario, __('Aucune durée trouvée pour l\'action sleep ou la durée n\'est pas valide : ', __FILE__) . $options['duration']);
					return;
				} elseif ($this->getExpression() == 'stop') {
					if ($scenario !== null) {
						$this->setLog($scenario, __('Action stop', __FILE__));
						$scenario->setDo(false);
						return;
					}
					die();
				} elseif ($this->getExpression() == 'log') {
					if ($scenario !== null) {
						$scenario->setLog('Log : ' . $options['message']);
					}
					return;
				} elseif ($this->getExpression() == 'event') {
					$cmd = cmd::byId(trim(str_replace('#', '', $options['cmd'])));
					if (!is_object($cmd)) {
						throw new Exception(__('Commande introuvable : ', __FILE__) . $options['cmd']);
					}
					$cmd->event(jeedom::evaluateExpression($options['value']));
					$this->setLog($scenario, __('Changement de ', __FILE__) . $cmd->getHumanName() . __(' à ', __FILE__) . $options['value']);
					return;
				} elseif ($this->getExpression() == 'message') {
					message::add('scenario', $options['message']);
					$this->setLog($scenario, __('Ajout du message suivant dans le centre de message : ', __FILE__) . $options['message']);
					return;
				} elseif ($this->getExpression() == 'alert') {
					event::add('jeedom::alert', $options);
					$this->setLog($scenario, __('Ajout de l\'alerte : ', __FILE__) . $options['message']);
					return;
				} elseif ($this->getExpression() == 'popup') {
					event::add('jeedom::alertPopup', $options['message']);
					$this->setLog($scenario, __('Affichage du popup : ', __FILE__) . $options['message']);
					return;
				} elseif ($this->getExpression() == 'setColoredIcon') {
					config::save('interface::advance::coloredIcons',$options['state']);
					event::add('jeedom::coloredIcons', $options['state']);
				} elseif ($this->getExpression() == 'equipment' || $this->getExpression() == 'equipement') {
					$eqLogic = eqLogic::byId(str_replace(array('#eqLogic', '#'), '', $this->getOptions('eqLogic')));
					if (!is_object($eqLogic)) {
						throw new Exception(__('Action sur l\'équipement impossible. Equipement introuvable - Vérifiez l\'id : ', __FILE__) . $this->getOptions('eqLogic'));
					}
					switch ($this->getOptions('action')) {
						case 'show':
						$this->setLog($scenario, __('Equipement visible : ', __FILE__) . $eqLogic->getHumanName());
						$eqLogic->setIsVisible(1);
						$eqLogic->save();
						break;
						case 'hide':
						$this->setLog($scenario, __('Equipement masqué : ', __FILE__) . $eqLogic->getHumanName());
						$eqLogic->setIsVisible(0);
						$eqLogic->save();
						break;
						case 'deactivate':
						$this->setLog($scenario, __('Equipement désactivé : ', __FILE__) . $eqLogic->getHumanName());
						$eqLogic->setIsEnable(0);
						$eqLogic->save();
						break;
						case 'activate':
						$this->setLog($scenario, __('Equipement activé : ', __FILE__) . $eqLogic->getHumanName());
						$eqLogic->setIsEnable(1);
						$eqLogic->save();
						break;
					}
					return;
				} elseif ($this->getExpression() == 'gotodesign') {
					$this->setLog($scenario, __('Changement design : ', __FILE__) . $options['plan_id']);
					event::add('jeedom::gotoplan', $options['plan_id']);
					return;
				} elseif ($this->getExpression() == 'scenario') {
					if ($scenario !== null && $this->getOptions('scenario_id') == $scenario->getId()) {
						$actionScenario = &$scenario;
					} else {
						$actionScenario = scenario::byId($this->getOptions('scenario_id'));
					}
					if (!is_object($actionScenario)) {
						throw new Exception(__('Action sur scénario impossible. Scénario introuvable - Vérifiez l\'id : ', __FILE__) . $this->getOptions('scenario_id'));
					}
					switch ($this->getOptions('action')) {
						case 'start':
						if ($this->getOptions('tags') != '' && !is_array($this->getOptions('tags'))) {
							$tags = array();
							$args = arg2array($this->getOptions('tags'));
							foreach ($args as $key => $value) {
								$tags['#' . trim(trim($key), '#') . '#'] = trim(self::setTags(trim($value), $scenario),'"');
							}
							$actionScenario->setTags($tags);
						}
						if (is_array($this->getOptions('tags'))) {
							$actionScenario->setTags($this->getOptions('tags'));
						}
						$this->setLog($scenario, __('Lancement du scénario : ', __FILE__) . $actionScenario->getName() . __(' options : ', __FILE__) . json_encode($actionScenario->getTags()));
						if ($scenario !== null) {
							return $actionScenario->launch('scenario', __('Lancement provoqué par le scénario  : ', __FILE__) . $scenario->getHumanName());
						} else {
							return $actionScenario->launch('other', __('Lancement provoqué', __FILE__));
						}
						break;
						case 'startsync':
						if ($this->getOptions('tags') != '' && !is_array($this->getOptions('tags'))) {
							$tags = array();
							$args = arg2array($this->getOptions('tags'));
							foreach ($args as $key => $value) {
								$tags['#' . trim(trim($key), '#') . '#'] = trim(self::setTags(trim($value), $scenario),'"');
							}
							$actionScenario->setTags($tags);
						}
						if (is_array($this->getOptions('tags'))) {
							$actionScenario->setTags($this->getOptions('tags'));
						}
						$this->setLog($scenario, __('Lancement du scénario : ', __FILE__) . $actionScenario->getName() . __(' options : ', __FILE__) . json_encode($actionScenario->getTags()));
						if ($scenario !== null) {
							return $actionScenario->launch('scenario', __('Lancement provoqué par le scénario  : ', __FILE__) . $scenario->getHumanName(), true);
						} else {
							return $actionScenario->launch('other', __('Lancement provoqué', __FILE__), true);
						}
						break;
						case 'stop':
						$this->setLog($scenario, __('Arrêt forcé du scénario : ', __FILE__) . $actionScenario->getName());
						$actionScenario->stop();
						break;
						case 'deactivate':
						$this->setLog($scenario, __('Désactivation du scénario : ', __FILE__) . $actionScenario->getName());
						$actionScenario->setIsActive(0);
						$actionScenario->save();
						break;
						case 'activate':
						$this->setLog($scenario, __('Activation du scénario : ', __FILE__) . $actionScenario->getName());
						$actionScenario->setLastLaunch(date('Y-m-d H:i:s'));
						$actionScenario->setIsActive(1);
						$actionScenario->save();
						break;
						case 'resetRepeatIfStatus':
						$this->setLog($scenario, __('Remise à zero des status du status des SI du scénario : ', __FILE__) . $actionScenario->getName());
						$actionScenario->resetRepeatIfStatus();
						break;
					}
					return;
				} elseif ($this->getExpression() == 'variable') {
					try {
						$result = evaluate($options['value']);
						if (!is_numeric($result)) {
							$result = $options['value'];
						}
					} catch (Exception $ex) {
						$result = $options['value'];
					} catch (Error $ex) {
						$result = $options['value'];
					}
					$this->setLog($scenario, __('Affectation de la variable ', __FILE__) . $this->getOptions('name') . __(' => ', __FILE__) . $options['value'] . ' = ' . $result);
					$dataStore = new dataStore();
					$dataStore->setKey($this->getOptions('name'));
					$dataStore->setValue($result);
					$dataStore->setType('scenario');
					$dataStore->setLink_id(-1);
					$dataStore->save();
					return;
				} elseif ($this->getExpression() == 'delete_variable') {
					scenario::removeData($options['name']);
					$this->setLog($scenario, __('Suppression de la variable ', __FILE__) . $this->getOptions('name'));
					return;
				} elseif ($this->getExpression() == 'ask') {
					$dataStore = new dataStore();
					$dataStore->setType('scenario');
					$dataStore->setKey($this->getOptions('variable'));
					$dataStore->setValue('');
					$dataStore->setLink_id(-1);
					$dataStore->save();
					$limit = (isset($options['timeout'])) ? $options['timeout'] : 300;
					$options_cmd = array('title' => $options['question'], 'message' => $options['question'], 'answer' => explode(';', $options['answer']), 'timeout' => $limit, 'variable' => $this->getOptions('variable'));

					//Recuperation des tags
					$tags = $scenario->getTags();
					if (isset($tags['#profile#']) === true) {
						//Remplacement du pattern #profile# par le profile utilisateur
						//si la commande contient #profile#
						$this->setOptions('cmd', str_replace('#profile#', $tags['#profile#'], $this->getOptions('cmd')));
					}

					#Recherche de la commandeId avec le bon user
					$cmd = cmd::byId(str_replace('#', '', $this->getOptions('cmd')));

					if (!is_object($cmd)) {
						throw new Exception(__('Commande introuvable - Vérifiez l\'id : ', __FILE__) . $this->getOptions('cmd'));
					}
					$this->setLog($scenario, __('Demande ', __FILE__) . json_encode($options_cmd));
					$cmd->setCache('ask::variable', $this->getOptions('variable'));
					$cmd->setCache('ask::endtime', strtotime('now') + $limit);
					$cmd->execCmd($options_cmd);
					$occurence = 0;
					$value = '';
					while (true) {
						$dataStore = dataStore::byTypeLinkIdKey('scenario', -1, $this->getOptions('variable'));
						if (is_object($dataStore)) {
							$value = $dataStore->getValue();
						}
						if ($value != '') {
							break;
						}
						if ($occurence > $limit) {
							break;
						}
						$occurence++;
						sleep(1);
					}
					if ($value == '') {
						$value = __('Aucune réponse', __FILE__);
						$cmd->setCache('ask::variable', 'none');
						$dataStore = dataStore::byTypeLinkIdKey('scenario', -1, $this->getOptions('variable'));
						$dataStore->setValue($value);
						$dataStore->save();
					}
					$this->setLog($scenario, __('Réponse ', __FILE__) . $value);
					return;
				} elseif ($this->getExpression() == 'jeedom_poweroff') {
					$this->setLog($scenario, __('Lancement de l\'arret de jeedom', __FILE__));
					$scenario->persistLog();
					jeedom::haltSystem();
					return;
				} elseif ($this->getExpression() == 'jeedom_reboot') {
					$this->setLog($scenario, __('Lancement du reboot de jeedom', __FILE__));
					$scenario->persistLog();
					jeedom::rebootSystem();
					return;
				} elseif ($this->getExpression() == 'scenario_return') {
					$this->setLog($scenario, __('Demande de retour d\'information : ', __FILE__) . $options['message']);
					if ($scenario->getReturn() === true) {
						$scenario->setReturn($options['message']);
					} else {
						$scenario->setReturn($scenario->getReturn() . ' ' . $options['message']);
					}
					return;
				} elseif ($this->getExpression() == 'remove_inat') {
					if ($scenario === null) {
						return;
					}
					$this->setLog($scenario, __('Suppression des blocs DANS et A programmés du scénario ', __FILE__));
					$crons = cron::searchClassAndFunction('scenario', 'doIn', '"scenario_id":' . $scenario->getId() . ',');
					if (is_array($crons)) {
						foreach ($crons as $cron) {
							if($cron->getPID() == getmypid()){
								continue;
							}
							$cron->remove();
						}
					}
					return;
				} elseif ($this->getExpression() == 'report') {
					$cmd_parameters = array('files' => null);
					$this->setLog($scenario, __('Génération d\'un rapport de type ', __FILE__) . $options['type']);
					switch ($options['type']) {
						case 'view':
						$view = view::byId($options['view_id']);
						if (!is_object($view)) {
							throw new Exception(__('Vue introuvable - Vérifiez l\'id : ', __FILE__) . $options['view_id']);
						}
						$this->setLog($scenario, __('Génération du rapport ', __FILE__) . $view->getName());
						$cmd_parameters['files'] = array($view->report($options['export_type'], $options));
						$cmd_parameters['title'] = __('[' . config::byKey('name') . '] Rapport ', __FILE__) . $view->getName() . __(' du ', __FILE__) . date('Y-m-d H:i:s');
						$cmd_parameters['message'] = __('Veuillez trouver ci-joint le rapport ', __FILE__) . $view->getName() . __(' généré le ', __FILE__) . date('Y-m-d H:i:s');
						break;
						case 'plan':
						$plan = planHeader::byId($options['plan_id']);
						if (!is_object($plan)) {
							throw new Exception(__('Design introuvable - Vérifiez l\'id : ', __FILE__) . $options['plan_id']);
						}
						$this->setLog($scenario, __('Génération du rapport ', __FILE__) . $plan->getName());
						$cmd_parameters['files'] = array($plan->report($options['export_type'], $options));
						$cmd_parameters['title'] = __('[' . config::byKey('name') . '] Rapport ', __FILE__) . $plan->getName() . __(' du ', __FILE__) . date('Y-m-d H:i:s');
						$cmd_parameters['message'] = __('Veuillez trouver ci-joint le rapport ', __FILE__) . $plan->getName() . __(' généré le ', __FILE__) . date('Y-m-d H:i:s');
						break;
						case 'plugin':
						$plugin = plugin::byId($options['plugin_id']);
						if (!is_object($plugin)) {
							throw new Exception(__('Panel introuvable - Vérifiez l\'id : ', __FILE__) . $options['plugin_id']);
						}
						$this->setLog($scenario, __('Génération du rapport ', __FILE__) . $plugin->getName());
						$cmd_parameters['files'] = array($plugin->report($options['export_type'], $options));
						$cmd_parameters['title'] = __('[' . config::byKey('name') . '] Rapport ', __FILE__) . $plugin->getName() . __(' du ', __FILE__) . date('Y-m-d H:i:s');
						$cmd_parameters['message'] = __('Veuillez trouver ci-joint le rapport ', __FILE__) . $plugin->getName() . __(' généré le ', __FILE__) . date('Y-m-d H:i:s');
						break;
						case 'eqAnalyse':
						$url = network::getNetworkAccess('internal') . '/index.php?v=d&p=eqAnalyse&report=1';
						$this->setLog($scenario, __('Génération du rapport ', __FILE__) . $url);
						$cmd_parameters['files'] = array(report::generate($url,'other','eqAnalyse',$options['export_type'], $options));
						$cmd_parameters['title'] = __('[' . config::byKey('name') . '] Rapport équipement du ', __FILE__) . date('Y-m-d H:i:s');
						$cmd_parameters['message'] = __('Veuillez trouver ci-joint le rapport équipement généré le ', __FILE__) . date('Y-m-d H:i:s');
						break;
						case 'timeline':
						$url = network::getNetworkAccess('internal') . '/index.php?v=d&p=history&report=1&timeline='. $options['timeline'];
						$this->setLog($scenario, __('Génération du rapport timeline ', __FILE__) . $options['timeline']);
						$cmd_parameters['files'] = array(report::generate($url,'other','timeline',$options['export_type'], $options));
						$cmd_parameters['title'] = __('[' . config::byKey('name') . '] Rapport ', __FILE__) . $options['timeline'] . __(' du ', __FILE__) . date('Y-m-d H:i:s');
						$cmd_parameters['message'] = __('Veuillez trouver ci-joint le rapport ', __FILE__) . $options['timeline'] . __(' généré le ', __FILE__) . date('Y-m-d H:i:s');
						break;
						case 'url':
						$url = $options['url'];
						$this->setLog($scenario, __('Génération du rapport ', __FILE__) . $url);
						$cmd_parameters['files'] = array(report::generate($url,'other','url',$options['export_type'], $options));
						$cmd_parameters['title'] = __('[' . config::byKey('name') . '] Rapport url du ', __FILE__) . date('Y-m-d H:i:s');
						$cmd_parameters['message'] = __('Veuillez trouver ci-joint le rapport url généré le ', __FILE__) . date('Y-m-d H:i:s');
						break;
					}
					if ($cmd_parameters['files'] === null) {
						throw new Exception(__('Erreur : Aucun rapport généré', __FILE__));
					}
					if ($this->getOptions('cmd') != '') {
						$cmdArray = explode('&&',$this->getOptions('cmd'));
						foreach ($cmdArray as $cmdname){
							$cmd = cmd::byId(str_replace('#', '', $cmdname));
							if (!is_object($cmd)) {
								throw new Exception(__('Commande introuvable veuillez vérifiez l\'id : ', __FILE__) . $this->getOptions('cmd'));
							}
							$this->setLog($scenario, __('Envoi du rapport généré sur ', __FILE__) . $cmd->getHumanName());
							$cmd->execCmd($cmd_parameters);
						}
					}
				} elseif ($this->getExpression() == 'tag') {
					$tags = $scenario->getTags();
					$options['value'] = self::setTags($options['value'], $scenario);
					try {
						$result = evaluate($options['value']);
						if (!is_numeric($result)) {
							$result = $options['value'];
						}
					} catch (Exception $ex) {
						$result = $options['value'];
					} catch (Error $ex) {
						$result = $options['value'];
					}
					$tags['#' . $options['name'] . '#'] = $result;
					$this->setLog($scenario, __('Mise à jour du tag ', __FILE__) . '#' . $options['name'] . '#' . ' => ' . $result);
					$scenario->setTags($tags);
				} else {
					//check user function:
					if (file_exists(__DIR__ . '/../../data/php/user.function.class.php')) {
						require_once __DIR__ . '/../../data/php/user.function.class.php';
						$stringFunction = strval($this->getExpression());
						$functionName = explode('(', $stringFunction)[0];
						if (class_exists('userFunction') && method_exists('userFunction', $functionName)) {
							$arguments = str_replace([$functionName, '(', ')'], '', $stringFunction);
							if ($arguments != '') {
								$arguments = explode(',', $arguments);
							} else {
								$arguments = [];
							}
							$result = call_user_func_array('userFunction::' . $functionName, $arguments);
							$this->setLog($scenario, 'userFunction: ' . $stringFunction . ' : ' . json_encode($result));
							$scenario->persistLog();
							return;
						}
					}

					$cmd = cmd::byId(str_replace('#', '', $this->getExpression()));
					if (is_object($cmd)) {
						if ($cmd->getSubtype() == 'slider' && isset($options['slider'])) {
							$options['slider'] = evaluate($options['slider']);
						}
						if (is_array($options) && (count($options) > 1 || (isset($options['background']) && $options['background'] == 1))) {
							$this->setLog($scenario, __('Exécution de la commande ', __FILE__) . $cmd->getHumanName() . __(" avec comme option(s) : ", __FILE__) . json_encode($options));
						} else {
							$this->setLog($scenario, __('Exécution de la commande ', __FILE__) . $cmd->getHumanName());
						}
						return $cmd->execCmd($options);
					}
					$this->setLog($scenario, __('[Erreur] Aucune commande trouvée pour ', __FILE__) . $this->getExpression());
					return;
				}
			} elseif ($this->getType() == 'condition') {
				$expression = self::setTags($this->getExpression(), $scenario, true);
				$message = __('Evaluation de la condition : [', __FILE__) . $expression . '] = ';
				$result = evaluate($expression);
				if (is_bool($result)) {
					if ($result) {
						$message .= __('Vrai', __FILE__);
					} else {
						$message .= __('Faux', __FILE__);
					}
				} else {
					$message .= $result;
				}
				$this->setLog($scenario, $message);
				return $result;
			} elseif ($this->getType() == 'code') {
				$this->setLog($scenario, __('Exécution d\'un bloc code', __FILE__));
				return eval($this->getExpression());
			}
		} catch (Exception $e) {
			$this->setLog($scenario, $message . $e->getMessage());
		} catch (Error $e) {
			$this->setLog($scenario, $message . $e->getMessage());
		}
	}

	public function save() {
		$this->checkBackground();
		DB::save($this);
		return true;
	}

	public function remove() {
		DB::remove($this);
	}

	public function getAllId() {
		$return = array(
			'element' => array(),
			'subelement' => array(),
			'expression' => array($this->getId()),
		);
		$result = array(
			'element' => array(),
			'subelement' => array(),
			'expression' => array(),
		);
		if ($this->getType() == 'element') {
			$element = scenarioElement::byId($this->getExpression());
			if (is_object($element)) {
				$result = $element->getAllId();
			}
		}
		$return['element'] = array_merge($return['element'], $result['element']);
		$return['subelement'] = array_merge($return['subelement'], $result['subelement']);
		$return['expression'] = array_merge($return['expression'], $result['expression']);
		return $return;
	}

	public function copy($_scenarioSubElement_id) {
		$expressionCopy = clone $this;
		$expressionCopy->setId('');
		$expressionCopy->setScenarioSubElement_id($_scenarioSubElement_id);
		$expressionCopy->save();
		if ($expressionCopy->getType() == 'element') {
			$element = scenarioElement::byId($expressionCopy->getExpression());
			if (is_object($element)) {
				$expressionCopy->setExpression($element->copy());
				$expressionCopy->save();
			}
		}
		return $expressionCopy->getId();
	}

	public function emptyOptions() {
		$this->options = '';
	}

	public function resetRepeatIfStatus() {
		if ($this->getType() != 'element') {
			return;
		}
		$element = scenarioElement::byId($this->getExpression());
		if (is_object($element)) {
			$element->resetRepeatIfStatus();
		}
	}

	public function export() {
		$return = '';
		if ($this->getType() == 'element') {
			$element = scenarioElement::byId($this->getExpression());
			if (is_object($element)) {
				$exports = explode("\n", $element->export());
				foreach ($exports as $export) {
					$return .= "    " . $export . "\n";
				}
			}
			return rtrim($return);
		}
		$options = $this->getOptions();
		if ($this->getType() == 'action') {
			if ($this->getExpression() == 'icon') {
				return '';
			} elseif ($this->getExpression() == 'sleep') {
				return '(sleep) Pause de  : ' . $options['duration'];
			} elseif ($this->getExpression() == 'stop') {
				return '(stop) Arret du scenario';
			} elseif ($this->getExpression() == 'scenario') {
				$actionScenario = scenario::byId($this->getOptions('scenario_id'));
				if (is_object($actionScenario)) {
					return '(scenario) ' . $this->getOptions('action') . ' de ' . $actionScenario->getHumanName();
				}
			} elseif ($this->getExpression() == 'variable') {
				return '(variable) Affectation de la variable : ' . $this->getOptions('name') . ' à ' . $this->getOptions('value');
			} else {
				$return = jeedom::toHumanReadable($this->getExpression());
				if (is_array($options) && count($options) != 0) {
					$return .= ' - Options : ' . json_encode(jeedom::toHumanReadable($options));
				}
				return $return;
			}
		} elseif ($this->getType() == 'condition') {
			return jeedom::toHumanReadable($this->getExpression());
		}
		if ($this->getType() == 'code') {
			return '(code) ' . $this->getExpression();
		}
	}

	/*     * **********************Getteur Setteur*************************** */

	public function getId() {
		return $this->id;
	}

	public function setId($_id) {
		$this->_changed = utils::attrChanged($this->_changed,$this->id,$_id);
		$this->id = $_id;
		return $this;
	}

	public function getType() {
		return $this->type;
	}

	public function setType($_type) {
		$this->_changed = utils::attrChanged($this->_changed,$this->type,$_type);
		$this->type = $_type;
		return $this;
	}

	public function getScenarioSubElement_id() {
		return $this->scenarioSubElement_id;
	}

	public function getSubElement() {
		return scenarioSubElement::byId($this->getScenarioSubElement_id());
	}

	public function setScenarioSubElement_id($_scenarioSubElement_id) {
		$this->_changed = utils::attrChanged($this->_changed,$this->scenarioSubElement_id,$_scenarioSubElement_id);
		$this->scenarioSubElement_id = $_scenarioSubElement_id;
		return $this;
	}

	public function getSubtype() {
		return $this->subtype;
	}

	public function setSubtype($_subtype) {
		$this->_changed = utils::attrChanged($this->_changed,$this->subtype,$_subtype);
		$this->subtype = $_subtype;
		return $this;
	}

	public function getExpression() {
		return $this->expression;
	}

	public function setExpression($_expression) {
		$_expression = jeedom::fromHumanReadable($_expression);
		$this->_changed = utils::attrChanged($this->_changed,$this->expression,$_expression);
		$this->expression = $_expression;
		return $this;
	}

	public function getOptions($_key = '', $_default = '') {
		return utils::getJsonAttr($this->options, $_key, $_default);
	}

	public function setOptions($_key, $_value) {
		$options = utils::setJsonAttr($this->options, $_key, jeedom::fromHumanReadable($_value));
		$this->_changed = utils::attrChanged($this->_changed,$this->options,$options);
		$this->options = 	$options;
		return $this;
	}

	public function getOrder() {
		return $this->order;
	}

	public function setOrder($_order) {
		$this->_changed = utils::attrChanged($this->_changed,$this->order,$_order);
		$this->order = $_order;
		return $this;
	}

	public function setLog(&$_scenario, $log) {
		if ($_scenario !== null && is_object($_scenario)) {
			$_scenario->setLog($log);
		}
	}

	public function getChanged() {
		return $this->_changed;
	}

	public function setChanged($_changed) {
		$this->_changed = $_changed;
		return $this;
	}

}
