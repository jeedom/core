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

class scenarioExpression {
	/*     * *************************Attributs****************************** */

	private $id;
	private $scenarioSubElement_id;
	private $type;
	private $subtype;
	private $expression;
	private $options;
	private $order;

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
		if ($_options != null) {
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
		$replace = array();
		$return = array('html' => '');
		$cmd = cmd::byId(str_replace('#', '', cmd::humanReadableToCmd($_expression)));
		if (is_object($cmd)) {
			$return['html'] = trim($cmd->toHtml('scenario', $_options));
		} else {
			try {
				$return['html'] = getTemplate('core', 'scenario', $_expression . '.default');
				if (is_json($_options)) {
					$_options = json_decode($_options, true);
				}
				if (is_array($_options) && count($_options) > 0) {
					foreach ($_options as $key => $value) {
						$replace['#' . $key . '#'] = $value;
					}
				}

				if (!isset($replace['#id#'])) {
					$replace['#id#'] = rand();
				}
				$tags = array('#title#', '#message#', '#slider#', '#color#', '#duration#');
				foreach ($tags as $tag) {
					if (!isset($replace[$tag])) {
						$replace[$tag] = '';
					}
				}
				$return['html'] = template_replace(cmd::cmdToHumanReadable($replace), $return['html']);
			} catch (Exception $e) {

			} catch (Error $e) {

			}
		}
		$replace = array(
			'#uid#' => 'exp' . mt_rand(),
			'#action#' => '',
			'#scenario_id#' => '',
			'#plan_id#' => '',
		);
		$return['html'] = translate::exec(template_replace($replace, $return['html']), 'core/template/scenario/' . $_expression . '.default');
		return $return;
	}

	/*     * ********************Fonctions utilisées dans le calcul des conditions********************************* */

	public static function rand($_min, $_max) {
		return rand($_min, $_max);
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
			if (str_word_count($_period) == 1 && is_numeric(trim($_period)[0])) {
				$startHist = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' -' . $_period));
			} else {
				$startHist = date('Y-m-d H:i:s', strtotime($_period));
				if ($startHist == date('Y-m-d H:i:s', strtotime(0))) {
					return '';
				}
			}
			$historyStatistique = $cmd->getStatistique($startHist, date('Y-m-d H:i:s'));
			if ($historyStatistique['avg'] == '') {
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
		$_startDate = date('Y-m-d H:i:s', strtotime(self::setTags($_startDate)));
		$_endDate = date('Y-m-d H:i:s', strtotime(self::setTags($_endDate)));
		$historyStatistique = $cmd->getStatistique($_startDate, $_endDate);
		return round($historyStatistique['avg'], 1);
	}

	public static function max($_cmd_id, $_period = '1 hour') {
		$args = func_get_args();
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
			if (str_word_count($_period) == 1 && is_numeric(trim($_period)[0])) {
				$startHist = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' -' . $_period));
			} else {
				$startHist = date('Y-m-d H:i:s', strtotime($_period));
				if ($startHist == date('Y-m-d H:i:s', strtotime(0))) {
					return '';
				}
			}
			$historyStatistique = $cmd->getStatistique($startHist, date('Y-m-d H:i:s'));
			if ($historyStatistique['max'] == '') {
				return $cmd->execCmd();
			}
			return round($historyStatistique['max'], 1);
		}
	}

	public static function maxBetween($_cmd_id, $_startDate, $_endDate) {
		$cmd = cmd::byId(trim(str_replace('#', '', $_cmd_id)));
		if (!is_object($cmd) || $cmd->getIsHistorized() == 0) {
			return '';
		}
		$_startDate = date('Y-m-d H:i:s', strtotime(self::setTags($_startDate)));
		$_endDate = date('Y-m-d H:i:s', strtotime(self::setTags($_endDate)));
		$historyStatistique = $cmd->getStatistique($_startDate, $_endDate);
		$historyStatistique = $cmd->getStatistique(self::setTags($_startDate), self::setTags($_endDate));
		return round($historyStatistique['max'], 1);
	}

	public static function wait($_condition, $_timeout = 7200) {
		$result = false;
		$occurence = 0;
		$limit = (is_numeric($_timeout)) ? $_timeout : 7200;
		while ($result === false) {
			$expression = self::setTags($_condition);
			$result = evaluate($expression);
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
			if (str_word_count($_period) == 1 && is_numeric(trim($_period)[0])) {
				$startHist = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' -' . $_period));
			} else {
				$startHist = date('Y-m-d H:i:s', strtotime($_period));
				if ($startHist == date('Y-m-d H:i:s', strtotime(0))) {
					return '';
				}
			}
			$historyStatistique = $cmd->getStatistique($startHist, date('Y-m-d H:i:s'));
			if ($historyStatistique['min'] == '') {
				return $cmd->execCmd();
			}
			return round($historyStatistique['min'], 1);
		}
	}

	public static function minBetween($_cmd_id, $_startDate, $_endDate) {
		$cmd = cmd::byId(trim(str_replace('#', '', $_cmd_id)));
		if (!is_object($cmd) || $cmd->getIsHistorized() == 0) {
			return '';
		}
		$_startDate = date('Y-m-d H:i:s', strtotime(self::setTags($_startDate)));
		$_endDate = date('Y-m-d H:i:s', strtotime(self::setTags($_endDate)));
		$historyStatistique = $cmd->getStatistique($_startDate, $_endDate);
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

	public static function tendance($_cmd_id, $_period = '1 hour', $_threshold = '') {
		$cmd = cmd::byId(trim(str_replace('#', '', $_cmd_id)));
		if (!is_object($cmd)) {
			return '';
		}
		if ($cmd->getIsHistorized() == 0) {
			return '';
		}
		$endTime = date('Y-m-d H:i:s');
		if (str_word_count($_period) == 1 && is_numeric(trim($_period)[0])) {
			$startTime = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' -' . $_period));
		} else {
			$startTime = date('Y-m-d H:i:s', strtotime($_period));
			if ($startTime == date('Y-m-d H:i:s', strtotime(0))) {
				return '';
			}
		}
		$tendance = $cmd->getTendance($startTime, $endTime);
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

	public static function stateChanges($_cmd_id, $_value = null, $_period = '1 hour') {
		if (!is_numeric(str_replace('#', '', $_cmd_id))) {
			$cmd = cmd::byId(str_replace('#', '', cmd::humanReadableToCmd($_cmd_id)));
		} else { $cmd = cmd::byId(str_replace('#', '', $_cmd_id));}
		if (!is_object($cmd) || $cmd->getIsHistorized() == 0) {
			return '';
		}
		$cmd_id = $cmd->getId();

		$args = func_num_args();
		if ($args == 2) {
			if (is_numeric(func_get_arg(1))) {
				$_value = func_get_arg(1);
			} else {
				$_period = func_get_arg(1);
				$_value = null;
			}
		}

		if (str_word_count($_period) == 1 && is_numeric(trim($_period)[0])) {
			$startHist = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' -' . $_period));
		} else {
			$startHist = date('Y-m-d H:i:s', strtotime($_period));
			if ($startHist == date('Y-m-d H:i:s', strtotime(0))) {
				return '';
			}
		}
		return history::stateChanges($cmd_id, $_value, $startHist, date('Y-m-d H:i:s'));
	}

	public static function stateChangesBetween($_cmd_id, $_value = null, $_startDate, $_endDate) {
		if (!is_numeric(str_replace('#', '', $_cmd_id))) {
			$cmd = cmd::byId(str_replace('#', '', cmd::humanReadableToCmd($_cmd_id)));
		} else { $cmd = cmd::byId(str_replace('#', '', $_cmd_id));}
		if (!is_object($cmd) || $cmd->getIsHistorized() == 0) {
			return '';
		}
		$cmd_id = $cmd->getId();

		$args = func_num_args();
		if ($args == 3) {
			$_endDate = func_get_arg(2);
			$_startDate = func_get_arg(1);
			$_value = null;
		}
		$_startDate = date('Y-m-d H:i:s', strtotime(self::setTags($_startDate)));
		$_endDate = date('Y-m-d H:i:s', strtotime(self::setTags($_endDate)));
		return history::stateChanges($cmd_id, $_value, $_startDate, $_endDate);
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

		if (str_word_count($_period) == 1 && is_numeric(trim($_period)[0])) {
			$_startDate = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' -' . $_period));
		} else {
			$_startDate = date('Y-m-d H:i:s', strtotime($_period));
			if ($_startDate == date('Y-m-d H:i:s', strtotime(0))) {
				return '';
			}
		}
		$_endDate = date('Y-m-d H:i:s');
		$_value = str_replace(',', '.', $_value);
		$_decimal = strlen(substr(strrchr($_value, "."), 1));

		$histories = $cmd->getHistory();

		$duration = 0;
		$lastDuration = strtotime($histories[0]->getDatetime());
		$lastValue = $histories[0]->getValue();

		foreach ($histories as $history) {
			if ($history->getDatetime() >= $_startDate) {
				if ($history->getDatetime() <= $_endDate) {
					if ($lastValue == $_value) {
						$duration = $duration + (strtotime($history->getDatetime()) - $lastDuration);
					}
				} else {
					if ($lastValue == $_value) {
						$duration = $duration + (strtotime($_endDate) - $lastDuration);
					}
					break;
				}
				$lastDuration = strtotime($history->getDatetime());
			} else {
				$lastDuration = strtotime($_startDate);
			}
			$lastValue = round($history->getValue(), $_decimal);
		}
		if ($lastValue == $_value AND $lastDuration <= strtotime($_endDate)) {
			$duration = $duration + (strtotime($_endDate) - $lastDuration);
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
		$cmd_id = $cmd->getId();

		$_startDate = date('Y-m-d H:i:s', strtotime(self::setTags($_startDate)));
		$_endDate = date('Y-m-d H:i:s', strtotime(self::setTags($_endDate)));
		$_value = str_replace(',', '.', $_value);
		$_decimal = strlen(substr(strrchr($_value, "."), 1));

		$histories = $cmd->getHistory();

		$duration = 0;
		$lastDuration = strtotime($histories[0]->getDatetime());
		$lastValue = $histories[0]->getValue();

		foreach ($histories as $history) {
			if ($history->getDatetime() >= $_startDate) {
				if ($history->getDatetime() <= $_endDate) {
					if ($lastValue == $_value) {
						$duration = $duration + (strtotime($history->getDatetime()) - $lastDuration);
					}
				} else {
					if ($lastValue == $_value) {
						$duration = $duration + (strtotime($_endDate) - $lastDuration);
					}
					break;
				}
				$lastDuration = strtotime($history->getDatetime());
			} else {
				$lastDuration = strtotime($_startDate);
			}
			$lastValue = round($history->getValue(), $_decimal);
		}
		if ($lastValue == $_value AND $lastDuration <= strtotime($_endDate)) {
			$duration = $duration + (strtotime($_endDate) - $lastDuration);
		}
		return floor($duration / 60);
	}

	public static function lastBetween($_cmd_id, $_startDate, $_endDate) {
		$cmd = cmd::byId(trim(str_replace('#', '', $_cmd_id)));
		if (!is_object($cmd) || $cmd->getIsHistorized() == 0) {
			return '';
		}
		$_startDate = date('Y-m-d H:i:s', strtotime(self::setTags($_startDate)));
		$_endDate = date('Y-m-d H:i:s', strtotime(self::setTags($_endDate)));
		$historyStatistique = $cmd->getStatistique($_startDate, $_endDate);
		return round($historyStatistique['last'], 1);
	}

	public static function statistics($_cmd_id, $_calc, $_period = '1 hour') {
		$args = func_get_args();
		$cmd = cmd::byId(trim(str_replace('#', '', $_cmd_id)));
		if (!is_object($cmd) || $cmd->getIsHistorized() == 0) {
			return '';
		}
		if (str_word_count($_period) == 1 && is_numeric(trim($_period)[0])) {
			$startHist = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' -' . $_period));
		} else {
			$startHist = date('Y-m-d H:i:s', strtotime($_period));
			if ($startHist == date('Y-m-d H:i:s', strtotime(0))) {
				return '';
			}
		}
		$_calc = str_replace(' ', '', $_calc);
		$historyStatistique = $cmd->getStatistique($startHist, date('Y-m-d H:i:s'));
		if ($historyStatistique['min'] == '') {
			return $cmd->execCmd();
		}
		return $historyStatistique[$_calc];
	}

	public static function statisticsBetween($_cmd_id, $_calc, $_startDate, $_endDate) {
		$cmd = cmd::byId(trim(str_replace('#', '', $_cmd_id)));
		if (!is_object($cmd) || $cmd->getIsHistorized() == 0) {
			return '';
		}
		$_calc = str_replace(' ', '', $_calc);
		$_startDate = date('Y-m-d H:i:s', strtotime(self::setTags($_startDate)));
		$_endDate = date('Y-m-d H:i:s', strtotime(self::setTags($_endDate)));
		$historyStatistique = $cmd->getStatistique(self::setTags($_startDate), self::setTags($_endDate));
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

	public static function collectDate($_cmd, $_format = 'Y-m-d H:i:s') {
		$cmd = cmd::byId(trim(str_replace('#', '', $_cmd)));
		if (!is_object($cmd)) {
			return -1;
		}
		if ($cmd->getType() != 'info') {
			return -2;
		}
		$cmd->execCmd();
		return date($_format, strtotime($cmd->getCollectDate()));
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
		} else if ($value < $color_range * 2) {
			$color->green += $color_range - $value;
			$color->blue += $value;
		} else if ($value < $color_range * 3) {
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
		if ($_scenario != null) {
			if (trim($_name) == '') {
				return $_scenario->getRealTrigger();
			}
			if ($_name == $_scenario->getRealTrigger()) {
				return 1;
			}
		}
		return 0;
	}

	public static function round($_value, $_decimal = 0) {
		$_value = self::setTags($_value);
		try {
			$result = evaluate($_value);
			if (is_string($result)) {
				//Alors la valeur n'est pas un calcul
				$result = $_value;
			}
		} catch (Exception $e) {
			$result = $_value;
		}
		if ($_decimal == 0) {
			return ceil(floatval(str_replace(',', '.', $result)));
		} else {
			return round(floatval(str_replace(',', '.', $result)), $_decimal);
		}
	}

	public static function time_op($_time, $_value) {
		$_time = self::setTags($_time);
		$_value = self::setTags($_value);
		$_time = ltrim($_time, 0);
		switch (strlen($_time)) {
			case 1:
				$date = DateTime::createFromFormat('Gi', '000' . intval(trim($_time)));
				break;
			case 2:
				$date = DateTime::createFromFormat('Gi', '00' . intval(trim($_time)));
				break;
			case 3:
				$date = DateTime::createFromFormat('Gi', '0' . intval(trim($_time)));
				break;
			default:
				$date = DateTime::createFromFormat('Gi', intval(trim($_time)));
				break;
		}
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

	public static function time($_value) {
		$_value = self::setTags($_value);
		try {
			$result = evaluate($_value);
			if (is_string($result)) {
				//Alors la valeur n'est pas un calcul
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
		} else if (strlen($_time) > 2) {
			return substr($_time, 0, 1) . 'h' . substr($_time, 1, 2);
		} else if (strlen($_time) > 1) {
			return '00h' . substr($_time, 0, 2);
		} else {
			return '00h0' . substr($_time, 0, 1);
		}
	}

	public static function setTags($_expression, &$_scenario = null, $_quote = false, $_nbCall = 0) {
		if ($_nbCall > 10) {
			return $_expression;
		}
		$replace1 = array(
			'#seconde#' => (int) date('s'),
			'#heure#' => (int) date('G'),
			'#minute#' => (int) date('i'),
			'#jour#' => (int) date('d'),
			'#mois#' => (int) date('m'),
			'#annee#' => (int) date('Y'),
			'#time#' => date('Gi'),
			'#timestamp#' => time(),
			'#seconde#' => (int) date('s'),
			'#date#' => date('md'),
			'#semaine#' => date('W'),
			'#sjour#' => '"' . date_fr(date('l')) . '"',
			'#smois#' => '"' . date_fr(date('F')) . '"',
			'#njour#' => (int) date('w'),
			'#hostname#' => '"' . gethostname() . '"',
			'#jeedom_name#' => '"' . config::byKey('name') . '"',
			'#IP#' => '"' . network::getNetworkAccess('internal', 'ip') . '"',
			'#trigger#' => '',
		);

		if (is_object($_scenario)) {
			$cmd = cmd::byId(str_replace('#', '', $_scenario->getRealTrigger()));
			if (is_object($cmd)) {
				$replace1['#trigger#'] = $cmd->getHumanName();
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
		if (is_string($_expression)) {
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
						} else {
							$replace2[$replace_string] = call_user_func_array(__CLASS__ . "::" . $function, $arguments);

						}
					} else {
						if (function_exists($function)) {
							foreach ($arguments as &$argument) {
								$argument = evaluate(self::setTags($argument, $_scenario, $_quote));
							}
							$replace2[$replace_string] = call_user_func_array($function, $arguments);
						}
					}
					if ($_quote && (strpos($replace2[$replace_string], ' ') !== false || preg_match("/[a-zA-Z#]/", $replace2[$replace_string]) || $replace2[$replace_string] === '')) {
						$replace2[$replace_string] = '"' . trim($replace2[$replace_string], '"') . '"';
					}
				}
			}
		} else {
			log::add('scenario', 'debug', print_r($_expression, true));
		}
		return cmd::cmdToValue(str_replace(array_keys($replace1), array_values($replace1), str_replace(array_keys($replace2), array_values($replace2), $_expression)), $_quote);

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

	public function execute(&$scenario = null) {
		if ($this->getOptions('enable', 1) == 0) {
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
					if (is_string($value)) {
						$options[$key] = str_replace('"', '', self::setTags($value, $scenario));
					}
				}
			}
			if ($this->getType() == 'action') {
				if ($this->getExpression() == 'icon') {
					if ($scenario != null) {
						$options = $this->getOptions();
						$this->setLog($scenario, __('Changement de l\'icone du scénario : ', __FILE__) . $options['icon']);
						$scenario->setDisplay('icon', $options['icon']);
						$scenario->save();
					}
					return;
				} else if ($this->getExpression() == 'wait') {
					if (!isset($options['condition'])) {
						return;
					}
					$result = false;
					$occurence = 0;
					$limit = (isset($options['timeout']) && is_numeric($options['timeout'])) ? $options['timeout'] : 7200;
					while ($result !== true) {
						$expression = self::setTags($options['condition'], $scenario);
						$result = evaluate($expression);
						if ($occurence > $limit) {
							$this->setLog($scenario, __('[Wait] Condition valide par dépassement de temps', __FILE__));
							return;
						}
						$occurence++;
						sleep(1);
					}
					$this->setLog($scenario, __('[Wait] Condition valide : ', __FILE__) . $expression);
					return;
				} else if ($this->getExpression() == 'sleep') {
					if (isset($options['duration'])) {
						try {
							$options['duration'] = evaluate($options['duration']);
						} catch (Exception $e) {

						} catch (Error $e) {

						}
						if (is_numeric($options['duration']) && $options['duration'] > 0) {
							$this->setLog($scenario, __('Pause de ', __FILE__) . $options['duration'] . __(' seconde(s)', __FILE__));
							if ($options['duration'] < 1) {
								return usleep($options['duration'] * 1000);
							} else {
								return sleep($options['duration']);
							}
						}
					}
					$this->setLog($scenario, __('Aucune durée trouvée pour l\'action sleep ou la durée n\'est pas valide : ', __FILE__) . $options['duration']);
					return;
				} else if ($this->getExpression() == 'stop') {
					if ($scenario != null) {
						$scenario2 = scenario::byId($scenario->getId());
						if ($scenario->getIsActive() != $scenario2->getIsActive()) {
							$scenario->setIsActive($scenario2->getIsActive());
						}
						$this->setLog($scenario, __('Arret du scénario', __FILE__));
						$scenario->setState('stop');
						$scenario->setPID('');
						$scenario->persistLog();
						$scenario->save();
					}
					die();
				} else if ($this->getExpression() == 'log') {
					if ($scenario != null) {
						$scenario->setLog('Log : ' . $options['message']);
					}
					return;
				} else if ($this->getExpression() == 'message') {
					message::add('scenario', $options['message']);
					$this->setLog($scenario, __('Ajout du message suivant dans le centre de message : ', __FILE__) . $options['message']);
					return;
				} else if ($this->getExpression() == 'equipement') {
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
				} else if ($this->getExpression() == 'gotodesign') {
					$this->setLog($scenario, __('Changement design : ', __FILE__) . $options['plan_id']);
					event::add('jeedom::gotoplan', $options['plan_id']);
					return;
				} else if ($this->getExpression() == 'scenario') {
					if ($scenario != null && $this->getOptions('scenario_id') == $scenario->getId()) {
						$actionScenario = &$scenario;
					} else {
						$actionScenario = scenario::byId($this->getOptions('scenario_id'));
					}
					if (!is_object($actionScenario)) {
						throw new Exception(__('Action sur scénario impossible. Scénario introuvable - Vérifiez l\'id : ', __FILE__) . $this->getOptions('scenario_id'));
					}
					switch ($this->getOptions('action')) {
						case 'start':
							$this->setLog($scenario, __('Lancement du scénario : ', __FILE__) . $actionScenario->getName());
							if ($scenario != null) {
								return $actionScenario->launch(__('Lancement provoqué par le scénario  : ', __FILE__) . $scenario->getHumanName());
							} else {
								return $actionScenario->launch(__('Lancement provoqué', __FILE__));
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
							$actionScenario->setIsActive(1);
							$actionScenario->save();
							break;
					}
					return;
				} else if ($this->getExpression() == 'variable') {
					$options['value'] = self::setTags($options['value']);
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
				} else if ($this->getExpression() == 'ask') {
					$dataStore = new dataStore();
					$dataStore->setType('scenario');
					$dataStore->setKey($this->getOptions('variable'));
					$dataStore->setValue('');
					$dataStore->setLink_id(-1);
					$dataStore->save();
					$limit = (isset($options['timeout'])) ? $options['timeout'] : 300;
					$options_cmd = array('title' => '', 'message' => $options['question'], 'answer' => explode(';', $options['answer']), 'timeout' => $limit, 'variable' => $this->getOptions('variable'));
					$cmd = cmd::byId(str_replace('#', '', $this->getOptions('cmd')));
					if (!is_object($cmd)) {
						throw new Exception(__('Commande introuvable - Vérifiez l\'id : ', __FILE__) . $this->getOptions('cmd'));
					}
					$this->setLog($scenario, __('Demande ', __FILE__) . print_r($options_cmd, true));
					$cmd->setConfiguration('storeVariable', $this->getOptions('variable'));
					$cmd->save();
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
						$dataStore = dataStore::byTypeLinkIdKey('scenario', -1, $this->getOptions('variable'));
						$dataStore->setValue($value);
						$dataStore->save();
					}
					$this->setLog($scenario, __('Réponse ', __FILE__) . $value);
					return;
				} else {
					$cmd = cmd::byId(str_replace('#', '', $this->getExpression()));
					if (is_object($cmd)) {
						if ($cmd->getSubtype() == 'slider' && isset($options['slider'])) {
							$options['slider'] = evaluate($options['slider']);
						}
						if (is_array($options) && count($options) != 0) {
							$this->setLog($scenario, __('Exécution de la commande ', __FILE__) . $cmd->getHumanName() . __(" avec comme option(s) : \n", __FILE__) . print_r($options, true));
						} else {
							$this->setLog($scenario, __('Exécution de la commande ', __FILE__) . $cmd->getHumanName());
						}
						return $cmd->execCmd($options);
					}
					$this->setLog($scenario, __('[Erreur] Aucune commande trouvée pour ', __FILE__) . $this->getExpression());
					return;
				}
			} else if ($this->getType() == 'condition') {
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
			} else if ($this->getType() == 'code') {
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
		DB::save($this);
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
			} else if ($this->getExpression() == 'sleep') {
				return '(sleep) Pause de  : ' . $options['duration'];
			} else if ($this->getExpression() == 'stop') {
				return '(stop) Arret du scenario';
			} else if ($this->getExpression() == 'scenario') {
				$actionScenario = scenario::byId($this->getOptions('scenario_id'));
				if (is_object($actionScenario)) {
					return '(scenario) ' . $this->getOptions('action') . ' de ' . $actionScenario->getHumanName();
				}
			} else if ($this->getExpression() == 'variable') {
				return '(variable) Affectation de la variable : ' . $this->getOptions('name') . ' à ' . $this->getOptions('value');
			} else {
				$return = jeedom::toHumanReadable($this->getExpression());
				if (is_array($options) && count($options) != 0) {
					$return .= ' - Options : ' . print_r(jeedom::toHumanReadable($options), true);
				}
				return $return;
			}
		} else if ($this->getType() == 'condition') {
			return jeedom::toHumanReadable($this->getExpression());
		}
		if ($this->getType() == 'code') {

		}
	}

/*     * **********************Getteur Setteur*************************** */

	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function getType() {
		return $this->type;
	}

	public function setType($type) {
		$this->type = $type;
	}

	public function getScenarioSubElement_id() {
		return $this->scenarioSubElement_id;
	}

	public function getSubElement() {
		return scenarioSubElement::byId($this->getScenarioSubElement_id());
	}

	public function setScenarioSubElement_id($scenarioSubElement_id) {
		$this->scenarioSubElement_id = $scenarioSubElement_id;
	}

	public function getSubtype() {
		return $this->subtype;
	}

	public function setSubtype($subtype) {
		$this->subtype = $subtype;
	}

	public function getExpression() {
		return $this->expression;
	}

	public function setExpression($expression) {
		$this->expression = jeedom::fromHumanReadable($expression);
	}

	public function getOptions($_key = '', $_default = '') {
		return utils::getJsonAttr($this->options, $_key, $_default);
	}

	public function setOptions($_key, $_value) {
		$this->options = utils::setJsonAttr($this->options, $_key, jeedom::fromHumanReadable($_value));
	}

	public function getOrder() {
		return $this->order;
	}

	public function setOrder($order) {
		$this->order = $order;
	}

	public function setLog(&$_scenario, $log) {
		if ($_scenario != null && is_object($_scenario)) {
			$_scenario->setLog($log);
		}
	}

}

?>
