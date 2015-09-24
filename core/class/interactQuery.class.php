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
	private $link_type;
	private $link_id;
	private $enable = 1;

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

	public static function byInteractDefId($_interactDef_id, $_enable = false) {
		$values = array(
			'interactDef_id' => $_interactDef_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
    FROM interactQuery
    WHERE interactDef_id=:interactDef_id';
		if ($_enable) {
			$sql .= ' AND enable=1';
		}
		$sql .= ' ORDER BY `query`';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function byTypeAndLinkId($_type, $_link_id) {
		$values = array(
			'type' => $_type,
			'link_id' => $_link_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
    FROM interactQuery
    WHERE link_type=:type
    AND link_id=:link_id';
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

		$values = array(
			'query' => $_query,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
    FROM interactQuery
    WHERE enable = 1 AND
    LOWER(query)=LOWER(:query)';
		$query = DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
		if (is_object($query)) {
			log::add('interact', 'debug', 'Je prend : ' . $query->getQuery());
			return $query;
		}

		$sql = 'SELECT ' . DB::buildField(__CLASS__) . ', MATCH query AGAINST (:query IN NATURAL LANGUAGE MODE) as score
    FROM interactQuery
    WHERE enable = 1
    GROUP BY id
    HAVING score > 1';
		$queries = DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
		if (count($queries) == 0) {
			$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
        FROM interactQuery
        WHERE enable = 1
        AND query=:query';
			$queries = DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
			if (is_object($queries)) {
				return $queries;
			}
			$queries = self::all();
		}
		log::add('interact', 'debug', 'Result : ' . print_r($queries, true));
		$caracteres = array(
			'À' => 'a', 'Á' => 'a', 'Â' => 'a', 'Ä' => 'a', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ä' => 'a', '@' => 'a',
			'È' => 'e', 'É' => 'e', 'Ê' => 'e', 'Ë' => 'e', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', '€' => 'e',
			'Ì' => 'i', 'Í' => 'i', 'Î' => 'i', 'Ï' => 'i', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
			'Ò' => 'o', 'Ó' => 'o', 'Ô' => 'o', 'Ö' => 'o', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'ö' => 'o',
			'Ù' => 'u', 'Ú' => 'u', 'Û' => 'u', 'Ü' => 'u', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'µ' => 'u',
			'Œ' => 'oe', 'œ' => 'oe',
			'$' => 's');
		$shortest = 999;
		$closest = null;
		$_query = strtolower(preg_replace('#[^A-Za-z0-9 \n\.\'=\*:]+#', '', strtr($_query, $caracteres)));
		foreach ($queries as $query) {
			$input = strtolower(preg_replace('#[^A-Za-z0-9 \n\.\'=\*:]+#', '', strtr($query->getQuery(), $caracteres)));
			preg_match_all("/#(.*?)#/", $input, $matches);
			foreach ($matches[1] as $match) {
				$input = str_replace('#' . $match . '#', '', $input);
			}
			$lev = levenshtein($input, $_query);
			log::add('interact', 'debug', 'Je compare : ' . $_query . ' avec ' . $input . ' => ' . $lev);
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
		if (str_word_count($_query) == 1 && $shortest > 1) {
			log::add('interact', 'debug', 'Correspondance trop éloigné (limite à 1 du à la presence d\'un seul mots) : ' . $shortest);
			return null;
		}
		if (config::byKey('interact::confidence') > 0 && $shortest > config::byKey('interact::confidence')) {
			log::add('interact', 'debug', 'Correspondance trop éloigné : ' . $shortest);
			return null;
		}
		return $closest;
	}

	public static function whatDoYouKnow($_object = null) {
		$results = jeedom::whatDoYouKnow($_object);
		$reply = '';
		foreach ($results as $object) {
			$reply .= __('*** Je sais que pour ', __FILE__) . $object['name'] . " : \n";
			foreach ($object['eqLogic'] as $eqLogic) {
				foreach ($eqLogic['cmd'] as $cmd) {
					$reply .= $eqLogic['name'] . ' ' . $cmd['name'] . ' = ' . $cmd['value'] . ' ' . $cmd['unite'] . "\n";
				}
			}
		}
		return $reply;
	}

	public static function tryToReply($_query, $_parameters = array()) {
		$_parameters['dictation'] = $_query;
		if (isset($_parameters['profile'])) {
			$_parameters['profile'] = strtolower($_parameters['profile']);
		}
		$reply = '';
		$interactQuery = interactQuery::recognize($_query);
		if (is_object($interactQuery)) {
			$reply = $interactQuery->executeAndReply($_parameters);
		}
		if (trim($reply) == '' && config::byKey('interact::noResponseIfEmpty', 'core', 0) == 0 && (!isset($_parameters['emptyReply']) || $_parameters['emptyReply'] == 0)) {
			$reply = self::dontUnderstand($_parameters);
		}
		if (is_object($interactQuery)) {
			log::add('interact', 'debug', 'J\'ai reçu : ' . $_query . "\nJ'ai compris : " . $interactQuery->getQuery() . "\nJ'ai répondu : " . $reply);
		} else {
			log::add('interact', 'debug', 'J\'ai reçu : ' . $_query . "\nJe n'ai rien compris\nJ'ai répondu : " . $reply);
		}
		return ucfirst($reply);
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
		$options = interactDef::getTagFromQuery($this->getQuery(), $_parameters['dictation']);
		if (isset($_parameters['profile']) && trim($interactDef->getPerson()) != '') {
			$person = strtolower($interactDef->getPerson());
			$person = explode('|', $person);
			if (!in_array($_parameters['profile'], $person)) {
				return __('Vous n\'êtes pas autorisé à exécuter cette action', __FILE__);
			}
		}
		if ($this->getLink_type() == 'whatDoYouKnow') {
			$object = object::byId($this->getLink_id());
			if (is_object($object)) {
				$reply = self::whatDoYouKnow($object);
				if (trim($reply) == '') {
					return __('Je ne sais rien sur ', __FILE__) . $object->getName();
				}
				return $reply;
			}
			return self::whatDoYouKnow();
		}
		if ($this->getLink_type() == 'scenario') {
			$scenario = scenario::byId($this->getLink_id());
			if (!is_object($scenario)) {
				return __('Impossible de trouver le scénario correspondant', __FILE__);
			}
			log::add('interact', 'debug', 'Execution du scénario : ' . $scenario->getHumanName() . ' => ' . $interactDef->getOptions('scenario_action'));
			$interactDef = $this->getInteractDef();
			if (!is_object($interactDef)) {
				return __('Impossible de trouver la définition de l\'interaction', __FILE__);
			}
			$reply = $interactDef->selectReply();
			if (trim($reply) == '') {
				$reply = self::replyOk();
			}
			$replace = array();
			$replace['#profile#'] = isset($_parameters['profile']) ? $_parameters['profile'] : '';
			$reply = scenarioExpression::setTags(str_replace(array_keys($replace), $replace, $reply));
			switch ($interactDef->getOptions('scenario_action')) {
				case 'start':
					$tags = array(
						'#query#' => $this->getQuery(),
						'#profile#' => $replace['#profile#'],
					);
					foreach ($options as $key => $value) {
						$tags['#' . $key . '#'] = $value;
					}
					$scenario->setTags($tags);
					$return = $scenario->launch(false, 'interact', __('Scénario exécuté sur interaction (S.A.R.A.H, SMS...)', __FILE__), 1);
					if (is_string($return) && $return != '') {
						$return = str_replace(array_keys($replace), $replace, $return);
						return $return;
					}
					return $reply;
				case 'stop':
					$scenario->stop();
					return $reply;
				case 'activate':
					$scenario->setIsActive(1);
					$scenario->save();
					return $reply;
				case 'deactivate':
					$scenario->setIsActive(0);
					$scenario->save();
					return $reply;
				default:
					return __('Aucune action n\'est définie dans l\'interaction sur le scénario : ', __FILE__) . $scenario->getHumanName();
			}
		}

		$reply = $interactDef->selectReply();
		$synonymes = array();
		if ($interactDef->getOptions('synonymes') != '') {
			foreach (explode('|', $interactDef->getOptions('synonymes')) as $value) {
				$values = explode('=', $value);
				$synonymes[strtolower($values[0])] = explode(',', $values[1]);
			}
		}
		$replace = array();
		$replace['#profile#'] = isset($_parameters['profile']) ? $_parameters['profile'] : '';

		if ($this->getLink_type() == 'cmd') {
			foreach (explode('&&', $this->getLink_id()) as $cmd_id) {
				$cmd = cmd::byId($cmd_id);
				if (!is_object($cmd)) {
					continue;
				}
				$replace['#commande#'] = $cmd->getName();
				if (isset($synonymes[strtolower($cmd->getName())])) {
					$replace['#commande#'] = $synonymes[strtolower($cmd->getName())][rand(0, count($synonymes[strtolower($cmd->getName())]) - 1)];
				}
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

				$replace['#unite#'] = $cmd->getUnite();
				if ($cmd->getType() == 'action') {
					if ($cmd->getSubType() == 'color') {
						$colors = config::byKey('convertColor');
						if (isset($colors[strtolower($options['color'])])) {
							$options['color'] = $colors[strtolower($options['color'])];
						}
					}
					try {
						log::add('interact', 'debug', 'Execution de la commande : ' . $cmd->getHumanName() . ' => ' . print_r($options, true));
						if ($cmd->execCmd($options) === false) {
							return __('Impossible d\'exécuter la commande', __FILE__);
						}
					} catch (Exception $exc) {
						return $exc->getMessage();
					}
					if ($options != null) {
						foreach ($options as $key => $value) {
							$replace['#' . $key . '#'] = $value;
						}
					}
				}
				if ($cmd->getType() == 'info') {
					$value = $cmd->execCmd();
					if ($value === null) {
						return __('Impossible de récupérer la valeur de la commande', __FILE__);
					} else {
						$replace['#valeur#'] = $value;
						if ($cmd->getSubType() == 'binary' && $interactDef->getOptions('convertBinary') != '') {
							$convertBinary = $interactDef->getOptions('convertBinary');
							$convertBinary = explode('|', $convertBinary);
							$replace['#valeur#'] = $convertBinary[$replace['#valeur#']];
						}
					}
				}
			}
		}
		$result = scenarioExpression::setTags(str_replace(array_keys($replace), $replace, $reply));
		if ($interactDef->getOptions('convertBinary') != '') {
			$convertBinary = $interactDef->getOptions('convertBinary');
			$convertBinary = explode('|', $convertBinary);
			$result = str_replace(' 1', $convertBinary[1], $result);
			$result = str_replace(' 0', $convertBinary[0], $result);
		}
		return $result;
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
	}

	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function getQuery() {
		return $this->query;
	}

	public function setQuery($query) {
		$this->query = $query;
	}

	public function getLink_type() {
		return $this->link_type;
	}

	public function setLink_type($link_type) {
		$this->link_type = $link_type;
	}

	public function getLink_id() {
		return $this->link_id;
	}

	public function setLink_id($link_id) {
		$this->link_id = $link_id;
	}

	public function getEnable() {
		return $this->enable;
	}

	public function setEnable($enable) {
		$this->enable = $enable;
	}

}

?>
