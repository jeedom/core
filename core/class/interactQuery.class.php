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

class interactQuery {
	/*     * *************************Attributs****************************** */
	
	private $id;
	private $interactDef_id;
	private $query;
	private $actions;
	private $_changed = false;
	
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
		if ($_interactDef_id !== null) {
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
	
	public static function searchActions($_action) {
		if (!is_array($_action)) {
			$values = array(
				'actions' => '%' . $_action . '%',
			);
			$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
			FROM interactQuery
			WHERE actions LIKE :actions';
		} else {
			$values = array(
				'actions' => '%' . $_action[0] . '%',
			);
			$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
			FROM interactQuery
			WHERE actions LIKE :actions';
			for ($i = 1; $i < count($_action); $i++) {
				$values['actions' . $i] = '%' . $_action[$i] . '%';
				$sql .= ' OR actions LIKE :actions' . $i;
			}
		}
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
			$interactDef = $query->getInteractDef();
			if ($interactDef->getOptions('mustcontain') != '' && !preg_match($interactDef->getOptions('mustcontain'), $_query)) {
				log::add('interact', 'debug', __('Correspondance trouvée : ', __FILE__) . $query->getQuery() . __(' mais ne contient pas : ', __FILE__) . interactDef::sanitizeQuery($interactDef->getOptions('mustcontain')));
				return null;
			}
			log::add('interact', 'debug', 'Je prends : ' . $query->getQuery());
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
			$query = DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
			if (is_object($query)) {
				$interactDef = $query->getInteractDef();
				if ($interactDef->getOptions('mustcontain') != '' && !preg_match($interactDef->getOptions('mustcontain'), $_query)) {
					log::add('interact', 'debug', __('Correspondance trouvée : ', __FILE__) . $query->getQuery() . __(' mais ne contient pas : ', __FILE__) . interactDef::sanitizeQuery($interactDef->getOptions('mustcontain')));
					return null;
				}
				return $queries;
			}
			$queries = self::all();
		}
		$shortest = 999;
		foreach ($queries as $query) {
			$input = interactDef::sanitizeQuery($query->getQuery());
			$tags = interactDef::getTagFromQuery($query->getQuery(), $_query);
			if (count($tags) > 0) {
				foreach ($tags as $value) {
					if ($value == "") {
						continue (2);
					}
				}
				$input = str_replace(array_keys($tags), $tags, $input);
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
		if ($shortest < 0) {
			log::add('interact', 'debug', __('Aucune correspondance trouvée', __FILE__));
			return null;
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
		$interactDef = $closest->getInteractDef();
		if ($interactDef->getOptions('mustcontain') != '' && !preg_match($interactDef->getOptions('mustcontain'), $_query)) {
			log::add('interact', 'debug', __('Correspondance trouvée : ', __FILE__) . $closest->getQuery() . __(' mais ne contient pas : ', __FILE__) . interactDef::sanitizeQuery($interactDef->getOptions('mustcontain')));
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
	
	public static function findInQuery($_type, $_query, $_data = null) {
		$return = array();
		$return['query'] = strtolower(sanitizeAccent($_query));
		$return[$_type] = null;
		$synonyms = self::getQuerySynonym($return['query'], $_type);
		if ($_type == 'object') {
			$objects = jeeObject::all();
		} elseif ($_type == 'eqLogic') {
			if ($_data !== null && is_object($_data['object'])) {
				$objects = $_data['object']->getEqLogic();
			} else {
				$objects = eqLogic::all(true);
			}
		} elseif ($_type == 'cmd') {
			if ($_data !== null && is_object($_data['eqLogic'])) {
				$objects = $_data['eqLogic']->getCmd();
			} elseif ($_data !== null && is_object($_data['object'])) {
				$objects = array();
				foreach ($_data['object']->getEqLogic() as $eqLogic) {
					if ($eqLogic->getIsEnable() == 0) {
						continue;
					}
					foreach ($eqLogic->getCmd() as $cmd) {
						$objects[] = $cmd;
					}
				}
			} else {
				$objects = cmd::all();
			}
		} elseif ($_type == 'summary') {
			foreach (config::byKey('object:summary') as $key => $value) {
				if (count($synonyms) > 0 && in_array(strtolower($value['name']), $synonyms)) {
					$return[$_type] = $value;
					break;
				}
				if (self::autoInteractWordFind($return['query'], $value['name'])) {
					$return[$_type] = $value;
					$return['query'] = str_replace(strtolower(sanitizeAccent($value['name'])), '', $return['query']);
					break;
				}
			}
			if (count($synonyms) > 0) {
				foreach ($synonyms as $value) {
					$return['query'] = str_replace(strtolower(sanitizeAccent($value)), '', $return['query']);
				}
			}
			return $return;
		}
		usort($objects, array("interactQuery", "cmp_objectName"));
		foreach ($objects as $object) {
			if ($object->getConfiguration('interact::auto::disable', 0) == 1) {
				continue;
			}
			if (count($synonyms) > 0 && in_array(strtolower($object->getName()), $synonyms)) {
				$return[$_type] = $object;
				break;
			}
			if (self::autoInteractWordFind($return['query'], $object->getName())) {
				$return[$_type] = $object;
				break;
			}
		}
		if ($_type != 'eqLogic') {
			if (is_object($return[$_type])) {
				$return['query'] = str_replace(strtolower(sanitizeAccent($return[$_type]->getName())), '', $return['query']);
				if (count($synonyms) > 0) {
					foreach ($synonyms as $value) {
						$return['query'] = str_replace(strtolower(sanitizeAccent($value)), '', $return['query']);
					}
				}
			}
		}
		return $return;
	}
	
	public static function cmp_objectName($a, $b) {
		return (strlen($a->getName()) < strlen($b->getName())) ? +1 : -1;
	}
	
	public static function autoInteract($_query, $_parameters = array()) {
		global $JEEDOM_INTERNAL_CONFIG;
		if (!isset($_parameters['identifier'])) {
			$_parameters['identifier'] = '';
		}
		$data = self::findInQuery('object', $_query);
		$data['cmd_parameters'] = array();
		$data = array_merge($data, self::findInQuery('eqLogic', $data['query'], $data));
		$data = array_merge($data, self::findInQuery('cmd', $data['query'], $data));
		if (isset($data['eqLogic']) && is_object($data['eqLogic']) && (!isset($data['cmd']) || !is_object($data['cmd']))) {
			foreach ($data['eqLogic']->getCmd('action') as $cmd) {
				if ($cmd->getSubtype() == 'slider') {
					break;
				}
			}
			if (is_object($cmd)) {
				if (preg_match_all('/' . config::byKey('interact::autoreply::cmd::slider::max') . '/i', $data['query'])) {
					$data['cmd'] = $cmd;
					$data['cmd_parameters']['slider'] = $cmd->getConfiguration('maxValue', 100);
				}
				if (preg_match_all('/' . config::byKey('interact::autoreply::cmd::slider::min') . '/i', $data['query'])) {
					$data['cmd'] = $cmd;
					$data['cmd_parameters']['slider'] = $cmd->getConfiguration('minValue', 0);
				}
			}
		}
		if (!isset($data['cmd']) || !is_object($data['cmd'])) {
			$data = array_merge($data, self::findInQuery('summary', $data['query'], $data));
			log::add('interact', 'debug', print_r($data, true));
			if (!isset($data['summary'])) {
				return '';
			}
			$return = $data['summary']['name'];
			$value = '';
			if (is_object($data['object'])) {
				$return .= ' ' . $data['object']->getName();
				$value = $data['object']->getSummary($data['summary']['key']);
			}
			if (trim($value) === '') {
				$value = jeeObject::getGlobalSummary($data['summary']['key']);
			}
			if (trim($value) === '') {
				return '';
			}
			self::addLastInteract($_query, $_parameters['identifier']);
			return $return . ' ' . $value . ' ' . $data['summary']['unit'];
		}
		self::addLastInteract($data['cmd']->getId(), $_parameters['identifier']);
		if ($data['cmd']->getType() == 'info') {
			return trim($data['cmd']->getHumanName() . ' ' . $data['cmd']->execCmd() . ' ' . $data['cmd']->getUnite());
		} else {
			if ($data['cmd']->getSubtype() == 'slider') {
				preg_match_all('/(\d+)/', strtolower(sanitizeAccent($data['query'])), $matches);
				if (isset($matches[0]) && isset($matches[0][0])) {
					$data['cmd_parameters']['slider'] = $matches[0][0];
				}
			}
			if ($data['cmd']->getSubtype() == 'color') {
				$colors = array_change_key_case(config::byKey('convertColor'));
				foreach ($colors as $name => $value) {
					if (strpos($data['query'], $name) !== false) {
						$data['cmd_parameters']['color'] = $value;
						break;
					}
				}
			}
			$data['cmd']->execCmd($data['cmd_parameters']);
			$return = __('C\'est fait', __FILE__) . ' (';
			$eqLogic = $data['cmd']->getEqLogic();
			if (is_object($eqLogic)) {
				$object = $eqLogic->getObject();
				if (is_object($object)) {
					$return .= $object->getName();
				}
				$return .= ' ' . $data['cmd']->getEqLogic()->getName();
			}
			$return .= ' ' . $data['cmd']->getName();
			if (isset($data['cmd_parameters']['slider'])) {
				$return .= ' => ' . $data['cmd_parameters']['slider'] . '%';
			}
			return $return . ')';
		}
		return '';
	}
	
	public static function autoInteractWordFind($_string, $_word) {
		return preg_match(
			'/( |^)' . preg_quote(strtolower(sanitizeAccent($_word)), '/') . '( |$)/',
			str_replace("'", ' ', strtolower(sanitizeAccent($_string)))
		);
	}
	
	public static function pluginReply($_query, $_parameters = array()) {
		try {
			foreach (plugin::listPlugin(true) as $plugin) {
				if (config::byKey('functionality::interact::enable', $plugin->getId(), 1) == 0) {
					continue;
				}
				if (method_exists($plugin->getId(), 'interact')) {
					$plugin_id = $plugin->getId();
					$reply = $plugin_id::interact($_query, $_parameters);
					if ($reply !== null || is_array($reply)) {
						$reply['reply'] = '[' . $plugin_id . '] ' . $reply['reply'];
						self::addLastInteract($_query, $_parameters['identifier']);
						log::add('interact', 'debug', 'Le plugin ' . $plugin_id . ' a répondu');
						return $reply;
					}
				}
			}
		} catch (Exception $e) {
			return array('reply' => __('Erreur : ', __FILE__) . $e->getMessage());
		}
		return null;
	}
	
	public static function warnMe($_query, $_parameters = array()) {
		global $JEEDOM_INTERNAL_CONFIG;
		$operator = null;
		$operand = null;
		foreach ($JEEDOM_INTERNAL_CONFIG['interact']['test'] as $key => $value) {
			if (strContain(strtolower(sanitizeAccent($_query)), $value)) {
				$operator .= $key;
				break;
			}
		}
		preg_match_all('!\d+!', strtolower(sanitizeAccent($_query)), $matches);
		if (isset($matches[0]) && isset($matches[0][0])) {
			$operand = $matches[0][0];
		}
		if ($operand === null || $operator === null) {
			return null;
		}
		$test = '#value# ' . $operator . ' ' . $operand;
		$options = array('test' => $test);
		if (is_object($_parameters['reply_cmd'])) {
			$options['reply_cmd'] = $_parameters['reply_cmd']->getId();
		}
		$listener = new listener();
		$listener->setClass('interactQuery');
		$listener->setFunction('warnMeExecute');
		$data = self::findInQuery('object', $_query);
		$data = array_merge($data, self::findInQuery('eqLogic', $data['query'], $data));
		$data = array_merge($data, self::findInQuery('cmd', $data['query'], $data));
		if (!isset($data['cmd']) || !is_object($data['cmd'])) {
			return null;
		} else {
			if ($data['cmd']->getType() == 'action') {
				return null;
			}
			$options['type'] = 'cmd';
			$options['cmd_id'] = $data['cmd']->getId();
			$options['name'] = $data['cmd']->getHumanName();
			$listener->addEvent($data['cmd']->getId());
			$listener->setOption($options);
			$listener->save(true);
			return array('reply' => __('C\'est noté : ', __FILE__) . str_replace('#value#', $data['cmd']->getHumanName(), $test));
		}
		return null;
	}
	
	public static function warnMeExecute($_options) {
		$warnMeCmd = (isset($_options['reply_cmd'])) ? $_options['reply_cmd'] : config::byKey('interact::warnme::defaultreturncmd');
		if (!isset($_options['test']) || $_options['test'] == '' || $warnMeCmd == '') {
			listener::byId($_options['listener_id'])->remove();
			return;
		}
		$result = jeedom::evaluateExpression(str_replace('#value#', $_options['value'], $_options['test']));
		if ($result) {
			listener::byId($_options['listener_id'])->remove();
			$cmd = cmd::byId(str_replace('#', '', $warnMeCmd));
			if (!is_object($cmd)) {
				return;
			}
			$cmd->execCmd(array(
				'title' => __('Alerte : ', __FILE__) . str_replace('#value#', $_options['name'], $_options['test']) . __(' valeur : ', __FILE__) . $_options['value'],
				'message' => __('Alerte : ', __FILE__) . str_replace('#value#', $_options['name'], $_options['test']) . __(' valeur : ', __FILE__) . $_options['value'],
			));
		}
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
		if (is_array($startContextual) && count($startContextual) > 0 && config::byKey('interact::contextual::enable') == 1 && isset($words[0]) && in_array(strtolower($words[0]), $startContextual)) {
			$reply = self::contextualReply($_query, $_parameters);
			log::add('interact', 'debug', 'Je cherche interaction contextuel (prioritaire) : ' . print_r($reply, true));
		}
		$startWarnMe = explode(';', config::byKey('interact::warnme::start'));
		if (is_array($startWarnMe) && count($startWarnMe) > 0 && config::byKey('interact::warnme::enable') == 1 && strContain(strtolower(sanitizeAccent($_query)), $startWarnMe)) {
			$reply = self::warnMe($_query, $_parameters);
			log::add('interact', 'debug', 'Je cherche interaction "previens-moi" : ' . print_r($reply, true));
		}
		if (config::byKey('interact::contextual::splitword') != '') {
			$splitWords = explode(';', config::byKey('interact::contextual::splitword'));
			$queries = array();
			foreach ($splitWords as $split) {
				if (in_array($split, $words)) {
					$queries = array_merge($queries, explode(' ' . $split . ' ', $_query));
				}
			}
			if (count($queries) > 1) {
				$reply = self::tryToReply($queries[0], $_parameters);
				if ($reply != '') {
					array_shift($queries);
					foreach ($queries as $query) {
						$tmp = self::contextualReply($query, $_parameters);
						if (is_array($tmp)) {
							foreach ($tmp as $key => $value) {
								if (!isset($reply[$key])) {
									$reply[$key] = $value;
									continue;
								}
								if (is_string($value)) {
									if ($reply[$key] != $value) {
										$reply[$key] .= "\n" . $value;
									}
								}
								if (is_array($value)) {
									$reply[$key] = array_merge($reply[$key], $value);
								}
							}
						} else {
							$reply['reply'] .= "\n" . $tmp;
						}
					}
					return $reply;
				}
			}
		}
		if ($reply == '') {
			$reply = self::pluginReply($_query, $_parameters);
			if ($reply !== null) {
				log::add('interact', 'info', 'J\'ai reçu : ' . $_query . '. Un plugin a répondu : ' . print_r($reply, true));
				return $reply;
			}
			$interactQuery = interactQuery::recognize($_query);
			if (is_object($interactQuery)) {
				$reply = $interactQuery->executeAndReply($_parameters);
				$cmds = $interactQuery->getActions('cmd');
				if (isset($cmds[0]) && isset($cmds[0]['cmd'])) {
					self::addLastInteract(str_replace('#', '', $cmds[0]['cmd']), $_parameters['identifier']);
				}
				log::add('interact', 'info', 'J\'ai reçu : ' . $_query . ". J'ai compris : " . $interactQuery->getQuery() . ". J'ai répondu : " . $reply);
				return array('reply' => ucfirst($reply));
			}
		}
		if ($reply == '' && config::byKey('interact::autoreply::enable') == 1) {
			$reply = self::autoInteract($_query, $_parameters);
			log::add('interact', 'debug', 'Je cherche dans les interactions automatiques, résultat : ' . $reply);
		}
		if ($reply == '' && config::byKey('interact::noResponseIfEmpty', 'core', 0) == 0 && (!isset($_parameters['emptyReply']) || $_parameters['emptyReply'] == 0)) {
			$reply = self::dontUnderstand($_parameters);
			log::add('interact', 'info', 'J\'ai reçu : ' . $_query . ". Je n'ai rien compris. J'ai répondu : " . $reply);
		}
		if (!is_array($reply)) {
			$reply = array('reply' => ucfirst($reply));
		}
		log::add('interact', 'info', 'J\'ai reçu : ' . $_query . ". Je réponds : " . print_r($reply, true));
		if (isset($_parameters['reply_cmd']) && is_object($_parameters['reply_cmd']) && isset($_parameters['force_reply_cmd'])) {
			$_parameters['reply_cmd']->execCmd(array('message' => $reply['reply']));
			return true;
		}
		return $reply;
	}
	
	public static function addLastInteract($_lastCmd, $_identifier = 'unknown') {
		$last = cache::byKey('interact::lastCmd::' . $_identifier);
		if ($last->getValue() == '') {
			cache::set('interact::lastCmd2::' . $_identifier, $last->getValue(), 300);
		}
		cache::set('interact::lastCmd::' . $_identifier, str_replace('#', '', $_lastCmd), 300);
	}
	
	public static function contextualReply($_query, $_parameters = array(), $_lastCmd = null) {
		$return = '';
		if (!isset($_parameters['identifier'])) {
			$_parameters['identifier'] = '';
		}
		if ($_lastCmd === null) {
			$last = cache::byKey('interact::lastCmd::' . $_parameters['identifier']);
			if ($last->getValue() == '') {
				return $return;
			}
			$lastCmd = $last->getValue();
		} else {
			$lastCmd = $_lastCmd;
		}
		$current = array();
		$current['cmd'] = cmd::byId($lastCmd);
		if (is_object($current['cmd'])) {
			$current['eqLogic'] = $current['cmd']->getEqLogic();
			if (!is_object($current['eqLogic'])) {
				return $return;
			}
			$current['object'] = $current['eqLogic']->getObject();
			$humanName = $current['cmd']->getHumanName();
		} else {
			$humanName = strtolower(sanitizeAccent($lastCmd));
			$current = self::findInQuery('object', $humanName);
			$current = array_merge($current, self::findInQuery('summary', $current['query'], $current));
		}
		
		$data = self::findInQuery('object', $_query);
		$data = array_merge($data, self::findInQuery('eqLogic', $data['query'], $data));
		$data = array_merge($data, self::findInQuery('cmd', $data['query'], $data));
		if (isset($data['object']) && is_object($current['object'])) {
			$humanName = self::replaceForContextual($current['object']->getName(), $data['object']->getName(), $humanName);
		}
		if (isset($data['cmd']) && is_object($current['cmd'])) {
			$humanName = self::replaceForContextual($current['cmd']->getName(), $data['cmd']->getName(), $humanName);
		}
		if (isset($data['eqLogic']) && is_object($current['eqLogic'])) {
			$humanName = self::replaceForContextual($current['eqLogic']->getName(), $data['eqLogic']->getName(), $humanName);
		}
		$reply = self::pluginReply($humanName, $_parameters);
		if ($reply !== null) {
			return $reply;
		}
		$return = self::autoInteract(str_replace(array('][', '[', ']'), array(' ', '', ''), $humanName), $_parameters);
		if ($return == '' && $_lastCmd === null) {
			$last = cache::byKey('interact::lastCmd2::' . $_parameters['identifier']);
			if ($last->getValue() != '') {
				$return = self::contextualReply($_query, $_parameters, $last->getValue());
			}
		}
		return $return;
	}
	
	public function replaceForContextual($_replace, $_by, $_in) {
		return str_replace(strtolower(sanitizeAccent($_replace)), strtolower(sanitizeAccent($_by)), str_replace($_replace, $_by, $_in));
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
			throw new Exception(__('InteractDef_id ne peut pas être vide', __FILE__));
		}
		DB::save($this);
		return true;
	}
	
	public function remove() {
		return DB::remove($this);
	}
	
	public function executeAndReply($_parameters) {
		if (isset($_parameters['reply_cmd'])) {
			unset($_parameters['reply_cmd']);
		}
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
		$replace = array('#query#' => $this->getQuery());
		foreach ($_parameters as $key => $value) {
			$replace['#' . $key . '#'] = $value;
		}
		$tags = null;
		if (isset($_parameters['dictation'])) {
			$tags = interactDef::getTagFromQuery($this->getQuery(), $_parameters['dictation']);
			$replace['#dictation#'] = $_parameters['dictation'];
		}
		if (is_array($tags)) {
			$replace = array_merge($replace, $tags);
		}
		$executeDate = null;
		
		if (isset($replace['#duration#'])) {
			$dateConvert = array(
				'heure' => 'hour',
				'mois' => 'month',
				'semaine' => 'week',
				'année' => 'year',
			);
			$replace['#duration#'] = str_replace(array_keys($dateConvert), $dateConvert, $replace['#duration#']);
			$executeDate = strtotime('+' . $replace['#duration#']);
		}
		if (isset($replace['#time#'])) {
			$time = str_replace(array('h'), array(':'), $replace['#time#']);
			if (strlen($time) == 1) {
				$time .= ':00';
			}else if (strlen($time) == 2) {
				$time .= ':00';
			} else if (strlen($time) == 3) {
				$time .= '00';
			}
			$time = str_replace('::',':',$time);
			$executeDate = strtotime($time);
			if ($executeDate < strtotime('now')) {
				$executeDate += 3600 * 24;
			}
		}
		if ($executeDate !== null && !isset($_parameters['execNow'])) {
			if (date('Y', $executeDate) < 2000) {
				return __('Erreur : impossible de calculer la date de programmation', __FILE__);
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
		$colors = array_change_key_case(config::byKey('convertColor'));
		if (is_array($this->getActions('cmd'))) {
			foreach ($this->getActions('cmd') as $action) {
				try {
					$options = array();
					if (isset($action['options'])) {
						$options = $action['options'];
					}
					if ($tags !== null) {
						foreach ($options as &$option) {
							$option = str_replace(array_keys($replace), $replace, $option);
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
					$tags = array();
					if (isset($options['tags'])) {
						$options['tags'] = arg2array($options['tags']);
						foreach ($options['tags'] as $key => $value) {
							$tags['#' . trim(trim($key), '#') . '#'] = scenarioExpression::setTags(trim($value));
						}
					}
					$options['tags'] = array_merge($replace, $tags);
					$return = scenarioExpression::createAndExec('action', $action['cmd'], $options);
					if (trim($return) !== '' && trim($return) !== null) {
						$replace['#valeur#'] .= ' ' . $return;
					}
				} catch (Exception $e) {
					log::add('interact', 'error', __('Erreur lors de l\'exécution de ', __FILE__) . $action['cmd'] . __('. Détails : ', __FILE__) . $e->getMessage());
				} catch (Error $e) {
					log::add('interact', 'error', __('Erreur lors de l\'exécution de ', __FILE__) . $action['cmd'] . __('. Détails : ', __FILE__) . $e->getMessage());
				}
			}
		}
		if ($interactDef->getOptions('waitBeforeReply') != '' && $interactDef->getOptions('waitBeforeReply') != 0 && is_numeric($interactDef->getOptions('waitBeforeReply'))) {
			sleep($interactDef->getOptions('waitBeforeReply'));
		}
		$reply = jeedom::evaluateExpression($reply);
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
		return str_replace(array_keys($replace), $replace, $reply);
	}
	
	public function getInteractDef() {
		return interactDef::byId($this->interactDef_id);
	}
	
	/*     * **********************Getteur Setteur*************************** */
	
	public function getInteractDef_id() {
		return $this->interactDef_id;
	}
	
	public function setInteractDef_id($_interactDef_id) {
		$this->_changed = utils::attrChanged($this->_changed,$this->interactDef_id,$_interactDef_id);
		$this->interactDef_id = $_interactDef_id;
		return $this;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setId($_id) {
		$this->_changed = utils::attrChanged($this->_changed,$this->id,$_id);
		$this->id = $_id;
		return $this;
	}
	
	public function getQuery() {
		return $this->query;
	}
	
	public function setQuery($_query) {
		$this->_changed = utils::attrChanged($this->_changed,$this->query,$_query);
		$this->query = $_query;
		return $this;
	}
	
	public function getActions($_key = '', $_default = '') {
		return utils::getJsonAttr($this->actions, $_key, $_default);
	}
	
	public function setActions($_key, $_value) {
		$actions = utils::setJsonAttr($this->actions, $_key, $_value);
		$this->_changed = utils::attrChanged($this->_changed,$this->actions,$actions);
		$this->actions = $actions;
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
