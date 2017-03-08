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

class interactQuery {
	/*     * *************************Attributs****************************** */

	private $id;
	private $interactDef_id;
	private $query;
	private $actions;

	private static $_globalConfiguration;

	/*     * ***********************Méthodes statiques*************************** */

	public static function byId($_id) {
		$values = array(
			'id' => $_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM interactQuery
		WHERE id=:id';

		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function byQuery($_query, $_interactDef_id = null) {
		$values = array(
			'query' => $_query,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM interactQuery
		WHERE query=:query';
		if ($_interactDef_id != null) {
			$values['interactDef_id'] = $_interactDef_id;
			$sql .= ' AND interactDef_id=:interactDef_id';
		}
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function byInteractDefId($_interactDef_id) {
		$values = array(
			'interactDef_id' => $_interactDef_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM interactQuery
		WHERE interactDef_id=:interactDef_id
		ORDER BY `query`';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function all() {
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM interactQuery
		ORDER BY id';
		return DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function removeByInteractDefId($_interactDef_id) {
		$values = array(
			'interactDef_id' => $_interactDef_id,
		);
		$sql = 'DELETE FROM interactQuery
		WHERE interactDef_id=:interactDef_id';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function recognize($_query) {
		$_query = interactDef::sanitizeQuery($_query);
		if (trim($_query) == '') {
			return null;
		}
		$values = array(
			'query' => $_query,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM interactQuery
		WHERE LOWER(query)=LOWER(:query)';
		$query = DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
		if (is_object($query)) {
			log::add('interact', 'debug', 'Je prend : ' . $query->getQuery());
			return $query;
		}

		$sql = 'SELECT ' . DB::buildField(__CLASS__) . ', MATCH query AGAINST (:query IN NATURAL LANGUAGE MODE) as score
		FROM interactQuery
		GROUP BY id
		HAVING score > 1';
		$queries = DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
		if (count($queries) == 0) {
			$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
			FROM interactQuery
			WHERE query=:query';
			$queries = DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
			if (is_object($queries)) {
				return $queries;
			}
			$queries = self::all();
		}
		$shortest = 999;
		foreach ($queries as $query) {
			$input = interactDef::sanitizeQuery($query->getQuery());
			preg_match_all("/#(.*?)#/", $input, $matches);
			foreach ($matches[1] as $match) {
				$input = str_replace('#' . $match . '#', '', $input);
			}
			$lev = levenshtein($input, $_query);
			log::add('interact', 'debug', 'Je compare : ' . $_query . ' avec ' . $input . ' => ' . $lev);
			if (trim($_query) == trim($input)) {
				$shortest = 0;
				$closest = $query;
				break;
			}
			if ($lev == 0) {
				$shortest = 0;
				$closest = $query;
				break;
			}
			if ($lev <= $shortest || $shortest < 0) {
				$closest = $query;
				$shortest = $lev;
			}
		}
		$weigh = array(1 => config::byKey('interact::weigh1'), 2 => config::byKey('interact::weigh2'), 3 => config::byKey('interact::weigh3'), 4 => config::byKey('interact::weigh4'));
		foreach (str_word_count($_query, 1) as $word) {
			if (isset($weigh[strlen($word)])) {
				$shortest += $weigh[strlen($word)];
			}
		}
		if (str_word_count($_query) == 1 && config::byKey('interact::confidence1') > 0 && $shortest > config::byKey('interact::confidence1')) {
			log::add('interact', 'debug', __('Correspondance trop éloigné : ', __FILE__) . $shortest);
			return null;
		} else if (str_word_count($_query) == 2 && config::byKey('interact::confidence2') > 0 && $shortest > config::byKey('interact::confidence2')) {
			log::add('interact', 'debug', __('Correspondance trop éloigné : ', __FILE__) . $shortest);
			return null;
		} else if (str_word_count($_query) == 3 && config::byKey('interact::confidence3') > 0 && $shortest > config::byKey('interact::confidence3')) {
			log::add('interact', 'debug', __('Correspondance trop éloigné : ', __FILE__) . $shortest);
			return null;
		} else if (str_word_count($_query) > 3 && config::byKey('interact::confidence') > 0 && $shortest > config::byKey('interact::confidence')) {
			log::add('interact', 'debug', __('Correspondance trop éloigné : ', __FILE__) . $shortest);
			return null;
		}
		if (!is_object($closest)) {
			log::add('interact', 'debug', __('Aucune phrase trouvée', __FILE__));
			return null;
		}
		log::add('interact', 'debug', __('J\'ai une correspondance  : ', __FILE__) . $closest->getQuery() . __(' avec ', __FILE__) . $shortest);
		return $closest;
	}

	public static function getQuerySynonym($_query, $_for) {
		$return = array();
		$base_synonyms = explode(';', config::byKey('interact::autoreply::' . $_for . '::synonym'));
		if (count($base_synonyms) == 0) {
			return $return;
		}
		foreach ($base_synonyms as $synonyms) {
			if (trim($synonyms) == '') {
				continue;
			}
			$synonyms = explode('|', $synonyms);
			foreach ($synonyms as $synonym) {
				if (self::autoInteractWordFind($_query, $synonym)) {
					$return = array_merge($return, $synonyms);
				}
			}
		}
		return $return;
	}

	public static function autoInteract($_query, $_parameters = array()) {
		if (!isset($_parameters['identifier'])) {
			$_parameters['identifier'] = '';
		}
		$query = strtolower(sanitizeAccent($_query));
		$data = array('object_name' => array(), 'object' => null, 'eqLogic_name' => array(), 'eqLogic' => null, 'cmd_name' => array(), 'cmd' => null, 'parameters' => array());

		$data['object_name'] = self::getQuerySynonym($query, 'object');

		foreach (object::all() as $object) {
			if (count($data['object_name']) > 0 && in_array(strtolower($object->getName()), $data['object_name'])) {
				$data['object'] = $object;
				break;
			}
			if (self::autoInteractWordFind($query, $object->getName())) {
				$data['object'] = $object;
				break;
			}
		}
		if (!is_object($data['object'])) {
			return '';
		}
		$query = str_replace(strtolower($data['object']->getName()), '', $query);

		$data['eqLogic_name'] = self::getQuerySynonym($query, 'eqLogic');

		foreach ($data['object']->getEqLogic() as $eqLogic) {
			if (count($data['eqLogic_name']) > 0 && in_array(strtolower($eqLogic->getName()), $data['eqLogic_name'])) {
				$data['eqLogic'] = $eqLogic;
				break;
			}
			if (self::autoInteractWordFind($query, $eqLogic->getName())) {
				$data['eqLogic'] = $eqLogic;
				break;
			}
		}

		if (is_object($data['eqLogic'])) {
			$query = str_replace(strtolower($data['eqLogic']->getName()), '', $query);
		}

		$data['cmd_name'] = self::getQuerySynonym($query, 'cmd');

		if (is_object($data['eqLogic'])) {
			foreach ($data['eqLogic']->getCmd() as $cmd) {
				if (count($data['cmd_name']) > 0 && in_array(strtolower($cmd->getName()), $data['cmd_name'])) {
					$data['cmd'] = $cmd;
					break;
				}
				if (self::autoInteractWordFind($query, $cmd->getName())) {
					$data['cmd'] = $cmd;
					break;
				}
			}
		} else {
			foreach ($data['cmd_name'] as $name) {
				foreach ($data['object']->getEqLogic() as $eqLogic) {
					foreach ($eqLogic->getCmd() as $cmd) {
						if (count($data['cmd_name']) > 0 && in_array(strtolower($cmd->getName()), $data['cmd_name'])) {
							$data['cmd'] = $cmd;
							break (3);
						}
						if (self::autoInteractWordFind($query, $cmd->getName())) {
							$data['cmd'] = $cmd;
							break (3);
						}
					}
				}
			}
		}

		if (!is_object($data['cmd'])) {
			return;
		}
		cache::set('interact::lastCmd::' . $_parameters['identifier'], $data['cmd']->getId(), 300);
		if ($data['cmd']->getType() == 'info') {
			return trim($data['cmd']->execCmd() . ' ' . $data['cmd']->getUnite());
		} else {
			$data['cmd']->execCmd();
			$return = __('J\'ai executé ', __FILE__) . $data['cmd']->getName();
			$eqLogic = $data['cmd']->getEqLogic();
			if (is_object($eqLogic)) {
				$return .= __(' de ', __FILE__) . $data['cmd']->getEqLogic()->getName();
				$object = $eqLogic->getObject();
				if (is_object($object)) {
					$return .= __(' dans ', __FILE__) . $object->getName();
				}
			}
			return $return;
		}
		return '';
	}

	public static function autoInteractWordFind($_string, $_word) {
		$string = strtolower(sanitizeAccent($_string));
		$word = strtolower(sanitizeAccent($_word));
		return preg_match('/( |^)' . preg_quote($word, '/') . '( |$)/', $_string);
	}

	public static function tryToReply($_query, $_parameters = array()) {
		if (trim($_query) == '') {
			return array('reply' => '');
		}
		$_parameters['identifier'] = '';
		if (isset($_parameters['plugin'])) {
			$_parameters['identifier'] = $_parameters['plugin'];
		} else {
			$_parameters['identifier'] = 'unknown';
		}
		if (isset($_parameters['profile'])) {
			$_parameters['identifier'] .= '::' . $_parameters['profile'];
		}
		$_parameters['dictation'] = $_query;
		if (isset($_parameters['profile'])) {
			$_parameters['profile'] = strtolower($_parameters['profile']);
		}
		$reply = '';
		$words = str_word_count($_query, 1);
		$startContextual = explode(';', config::byKey('interact::contextual::startpriority'));
		if (is_array($startContextual) && count($startContextual) > 0 && config::byKey('interact::contextual::enable') == 1 && isset($words[0]) && in_array($words[0], $startContextual)) {
			$reply = self::contextualReply($_query, $_parameters);
		}
		if ($reply == '') {
			$interactQuery = interactQuery::recognize($_query);
			if (is_object($interactQuery)) {
				$reply = $interactQuery->executeAndReply($_parameters);
				$cmds = $interactQuery->getActions('cmd');
				if (isset($cmds[0]) && isset($cmds[0]['cmd'])) {
					cache::set('interact::lastCmd::' . $_parameters['identifier'], str_replace('#', '', $cmds[0]['cmd']), 300);
				}
				log::add('interact', 'debug', 'J\'ai reçu : ' . $_query . "\nJ'ai compris : " . $interactQuery->getQuery() . "\nJ'ai répondu : " . $reply);
				return array('reply' => ucfirst($reply));
			}
		}
		if ($reply == '') {
			foreach (plugin::listPlugin(true) as $plugin) {
				if (method_exists($plugin->getId(), 'interact')) {
					$plugin_id = $plugin->getId();
					$reply = $plugin_id::interact($_query, $_parameters);
					if ($reply != null || is_array($reply)) {
						$reply['reply'] = '[' . $plugin_id . '] ' . $reply['reply'];
						return $reply;
					}
				}
			}
		}
		if ($reply == '' && config::byKey('interact::autoreply::enable') == 1) {
			$reply = self::autoInteract($_query, $_parameters);
		}
		if ($reply == '' && config::byKey('interact::contextual::enable') == 1) {
			$reply = self::contextualReply($_query, $_parameters);
		}
		if ($reply == '' && config::byKey('interact::noResponseIfEmpty', 'core', 0) == 0 && (!isset($_parameters['emptyReply']) || $_parameters['emptyReply'] == 0)) {
			$reply = self::dontUnderstand($_parameters);
			log::add('interact', 'debug', 'J\'ai reçu : ' . $_query . "\nJe n'ai rien compris\nJ'ai répondu : " . $reply);
		}
		return array('reply' => ucfirst($reply));
	}

	public static function contextualReply($_query, $_parameters = array()) {
		if (!isset($_parameters['identifier'])) {
			$_parameters['identifier'] = '';
		}
		$last = cache::byKey('interact::lastCmd::' . $_parameters['identifier']);
		if ($last->getValue() == '') {
			return '';
		}
		$lastCmd = $last->getValue();
		$current = array();
		$current['cmd'] = cmd::byId($lastCmd);
		if (!is_object($current['cmd'])) {
			return '';
		}
		$current['eqLogic'] = $current['cmd']->getEqLogic();
		if (!is_object($current['eqLogic'])) {
			return '';
		}
		$current['object'] = $current['eqLogic']->getObject();
		$humanName = $current['cmd']->getHumanName();
		$data = array();
		$findReplace = false;
		$query = strtolower(sanitizeAccent($_query));

		$data['object_name'] = self::getQuerySynonym($query, 'object');
		foreach (object::all() as $object) {
			if (count($data['object_name']) > 0 && in_array(strtolower($object->getName()), $data['object_name'])) {
				$data['object'] = $object;
				break;
			}
			if (self::autoInteractWordFind($query, $object->getName())) {
				$data['object'] = $object;
				break;
			}
		}

		if (isset($data['object']) && is_object($current['object'])) {
			$humanName = str_replace($current['object']->getName(), $data['object']->getName(), $humanName);
			$query = str_replace(strtolower($data['object']->getName()), '', $query);
		}

		$data['cmd_name'] = self::getQuerySynonym($query, 'cmd');
		foreach (cmd::all() as $cmd) {
			if (count($data['cmd_name']) > 0 && in_array(strtolower($cmd->getName()), $data['cmd_name'])) {
				$data['cmd'] = $cmd;
				break;
			}
			if (self::autoInteractWordFind($query, $cmd->getName())) {
				$data['cmd'] = $cmd;
				break;
			}
		}
		if (isset($data['cmd']) && is_object($current['cmd'])) {
			$humanName = str_replace($current['cmd']->getName(), $data['cmd']->getName(), $humanName);
			$query = str_replace(strtolower($data['cmd']->getName()), '', $query);
		}

		$data['eqLogic_name'] = self::getQuerySynonym($query, 'eqLogic');
		foreach (eqLogic::all() as $eqLogic) {
			if (count($data['eqLogic_name']) > 0 && in_array(strtolower($eqLogic->getName()), $data['eqLogic_name'])) {
				$data['eqLogic'] = $eqLogic;
				break;
			}
			if (self::autoInteractWordFind($query, $eqLogic->getName())) {
				$data['eqLogic'] = $eqLogic;
				break;
			}
		}
		if (isset($data['eqLogic']) && is_object($current['eqLogic'])) {
			$humanName = str_replace($current['eqLogic']->getName(), $data['eqLogic']->getName(), $humanName);
			$query = str_replace(strtolower($data['eqLogic']->getName()), '', $query);
		}
		return self::autoInteract(str_replace(array('][', '[', ']'), array(' ', '', ''), $humanName), $_parameters);
	}

	public static function brainReply($_query, $_parameters) {
		global $PROFILE;
		$PROFILE = '';
		if (isset($_parameters['profile'])) {
			$PROFILE = $_parameters['profile'];
		}
		include_file('core', 'bot', 'config');
		global $BRAINREPLY;
		$shortest = 999;
		foreach ($BRAINREPLY as $word => $response) {
			$lev = levenshtein(strtolower($_query), strtolower($word));
			if ($lev == 0) {
				$closest = $word;
				$shortest = 0;
				break;
			}
			if ($lev <= $shortest || $shortest < 0) {
				$closest = $word;
				$shortest = $lev;
			}
		}
		if (isset($closest) && is_array($BRAINREPLY[$closest])) {
			$random = rand(0, count($BRAINREPLY[$closest]) - 1);
			return $BRAINREPLY[$closest][$random];
		}
		return '';
	}

	public static function dontUnderstand($_parameters) {
		$notUnderstood = array(
			__('Désolé je n\'ai pas compris', __FILE__),
			__('Désolé je n\'ai pas compris la demande', __FILE__),
			__('Désolé je ne comprends pas la demande', __FILE__),
			__('Je ne comprends pas', __FILE__),
		);
		if (isset($_parameters['profile'])) {
			$notUnderstood[] = __('Désolé ', __FILE__) . $_parameters['profile'] . __(' je n\'ai pas compris', __FILE__);
			$notUnderstood[] = __('Désolé ', __FILE__) . $_parameters['profile'] . __(' je n\'ai pas compris ta demande', __FILE__);
		}
		$random = rand(0, count($notUnderstood) - 1);
		return $notUnderstood[$random];
	}

	public static function replyOk() {
		$reply = array(
			__('C\'est fait', __FILE__),
			__('Ok', __FILE__),
			__('Voila, c\'est fait', __FILE__),
			__('Bien compris', __FILE__),
		);
		$random = rand(0, count($reply) - 1);
		return $reply[$random];
	}

	public static function doIn($_params) {
		$interactQuery = self::byId($_params['interactQuery_id']);
		if (!is_object($interactQuery)) {
			return;
		}
		$_params['execNow'] = 1;
		$interactQuery->executeAndReply($_params);
	}

	/*     * *********************Méthodes d'instance************************* */

	public function save() {
		if ($this->getQuery() == '') {
			throw new Exception(__('La commande vocale ne peut pas être vide', __FILE__));
		}
		if ($this->getInteractDef_id() == '') {
			throw new Exception(__('SarahDef_id ne peut pas être vide', __FILE__));
		}
		return DB::save($this);
	}

	public function remove() {
		return DB::remove($this);
	}

	public function executeAndReply($_parameters) {
		$interactDef = interactDef::byId($this->getInteractDef_id());
		if (!is_object($interactDef)) {
			return __('Inconsistance de la base de données', __FILE__);
		}
		if (isset($_parameters['profile']) && trim($interactDef->getPerson()) != '') {
			$person = strtolower($interactDef->getPerson());
			$person = explode('|', $person);
			if (!in_array($_parameters['profile'], $person)) {
				return __('Vous n\'êtes pas autorisé à exécuter cette action', __FILE__);
			}
		}
		$reply = $interactDef->selectReply();
		$replace = array();
		$tags_replace = array('#query#' => $this->getQuery());
		foreach ($_parameters as $key => $value) {
			$tags_replace['#' . $key . '#'] = $value;
		}
		$tags = null;
		if (isset($_parameters['dictation'])) {
			$tags = interactDef::getTagFromQuery($this->getQuery(), $_parameters['dictation']);
			$tags_replace['#dictation#'] = $_parameters['dictation'];
		}
		if (is_array($tags)) {
			foreach ($tags as $key => $value) {
				$tags_replace['#' . $key . '#'] = $value;
				$replace['#' . $key . '#'] = $value;
			}
		}
		$executeDate = null;
		$dateConvert = array(
			'heure' => 'hour',
			'mois' => 'month',
			'semaine' => 'week',
			'année' => 'year',
		);
		if (isset($tags_replace['#duration#'])) {
			$tags_replace['#duration#'] = str_replace(array_keys($dateConvert), $dateConvert, $tags_replace['#duration#']);
			$executeDate = strtotime('+' . $tags_replace['#duration#']);
		}
		if (isset($tags_replace['#time#'])) {
			$time = str_replace(array('h'), array(':'), $tags_replace['#time#']);
			if (strlen($time) == 2) {
				$time .= ':00';
			} else if (strlen($time) == 3) {
				$time .= '00';
			}
			$executeDate = strtotime($time);
			if ($executeDate < strtotime('now')) {
				$executeDate += 3600;
			}
		}
		if ($executeDate !== null && !isset($_parameters['execNow'])) {
			if (date('Y', $executeDate) < 2000) {
				return __('Erreur impossible de calculer la date de programmation', __FILE__);
			}
			if ($executeDate < (strtotime('now') + 60)) {
				$executeDate = strtotime('now') + 60;
			}
			$crons = cron::searchClassAndFunction('interactQuery', 'doIn', '"interactQuery_id":' . $this->getId());
			if (is_array($crons)) {
				foreach ($crons as $cron) {
					if ($cron->getState() != 'run') {
						$cron->remove();
					}
				}
			}
			$cron = new cron();
			$cron->setClass('interactQuery');
			$cron->setFunction('doIn');
			$cron->setOption(array_merge(array('interactQuery_id' => intval($this->getId())), $_parameters));
			$cron->setLastRun(date('Y-m-d H:i:s'));
			$cron->setOnce(1);
			$cron->setSchedule(cron::convertDateToCron($executeDate));
			$cron->save();
			$replace['#valeur#'] = date('Y-m-d H:i:s', $executeDate);
			$result = scenarioExpression::setTags(str_replace(array_keys($replace), $replace, $reply));
			return $result;
		}
		$replace['#valeur#'] = '';
		$colors = config::byKey('convertColor');
		if (is_array($this->getActions('cmd'))) {
			foreach ($this->getActions('cmd') as $action) {
				try {
					$options = array();
					if (isset($action['options'])) {
						$options = $action['options'];
					}
					if ($tags != null) {
						foreach ($options as &$option) {
							$option = str_replace(array_keys($tags_replace), $tags_replace, $option);
						}
						if (isset($options['color']) && isset($colors[strtolower($options['color'])])) {
							$options['color'] = $colors[strtolower($options['color'])];
						}
					}
					$cmd = cmd::byId(str_replace('#', '', $action['cmd']));
					if (is_object($cmd)) {
						$replace['#unite#'] = $cmd->getUnite();
						$replace['#commande#'] = $cmd->getName();
						$replace['#objet#'] = '';
						$replace['#equipement#'] = '';
						$eqLogic = $cmd->getEqLogic();
						if (is_object($eqLogic)) {
							$replace['#equipement#'] = $eqLogic->getName();
							$object = $eqLogic->getObject();
							if (is_object($object)) {
								$replace['#objet#'] = $object->getName();
							}
						}
					}
					$options['tags'] = $tags_replace;
					$return = scenarioExpression::createAndExec('action', $action['cmd'], $options);
					if (trim($return) !== '' && trim($return) !== null) {
						$replace['#valeur#'] .= ' ' . $return;
					}

				} catch (Exception $e) {
					log::add('interact', 'error', __('Erreur lors de l\'éxecution de ', __FILE__) . $action['cmd'] . __('. Détails : ', __FILE__) . $e->getMessage());
				} catch (Error $e) {
					log::add('interact', 'error', __('Erreur lors de l\'éxecution de ', __FILE__) . $action['cmd'] . __('. Détails : ', __FILE__) . $e->getMessage());
				}
			}
		}
		$replace['#valeur#'] = trim($replace['#valeur#']);
		$replace['#profile#'] = isset($_parameters['profile']) ? $_parameters['profile'] : '';
		if ($interactDef->getOptions('convertBinary') != '') {
			$convertBinary = explode('|', $interactDef->getOptions('convertBinary'));
			if (is_array($convertBinary) && count($convertBinary) == 2) {
				$replace['1'] = $convertBinary[1];
				$replace['0'] = $convertBinary[0];
			}
		}
		foreach ($replace as $key => $value) {
			if (is_array($value)) {
				unset($replace[$key]);
			}
		}
		if ($replace['#valeur#'] == '') {
			$replace['#valeur#'] = __('aucune valeur', __FILE__);
		}
		$replace['"'] = '';
		return str_replace(array_keys($replace), $replace, scenarioExpression::setTags($reply));
	}

	public function getInteractDef() {
		return interactDef::byId($this->interactDef_id);
	}

	/*     * **********************Getteur Setteur*************************** */

	public function getInteractDef_id() {
		return $this->interactDef_id;
	}

	public function setInteractDef_id($interactDef_id) {
		$this->interactDef_id = $interactDef_id;
		return $this;
	}

	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
		return $this;
	}

	public function getQuery() {
		return $this->query;
	}

	public function setQuery($query) {
		$this->query = $query;
		return $this;
	}

	public function getActions($_key = '', $_default = '') {
		return utils::getJsonAttr($this->actions, $_key, $_default);
	}

	public function setActions($_key, $_value) {
		$this->actions = utils::setJsonAttr($this->actions, $_key, $_value);
		return $this;
	}

}
