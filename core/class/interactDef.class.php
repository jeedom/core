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

class interactDef {
	/*     * *************************Attributs****************************** */

	private $id;
	private $name;
	private $position;
	private $filtres;
	private $query;
	private $reply;
	private $person;
	private $options;
	private $enable;
	private $group;
	private $actions;

	/*     * ***********************Méthodes statiques*************************** */

	public static function byId($_id) {
		$values = array(
			'id' => $_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
        FROM interactDef
        WHERE id=:id';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function all($_group = '') {
		$values = array();
		if ($_group === '') {
			$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
        FROM interactDef
        ORDER BY position';
		} else if ($_group === null) {
			$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
        FROM interactDef
        WHERE (`group` IS NULL OR `group` = "")
        ORDER BY position';
		} else {
			$values['group'] = $_group;
			$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
        FROM interactDef
        WHERE `group`=:group
        ORDER BY position';
		}
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function listGroup($_group = null) {
		$values = array();
		$sql = 'SELECT DISTINCT(`group`)
        FROM interactDef';
		if ($_group != null) {
			$values['group'] = '%' . $_group . '%';
			$sql .= ' WHERE `group` LIKE :group';
		}
		$sql .= ' ORDER BY `group`';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL);
	}

	public static function generateTextVariant($_text) {
		$return = array();
		preg_match_all("/(\[.*?\])/", $_text, $words);
		if (count($words[1]) == 0) {
			$return[] = $_text;
		} else {
			$math = $words[1][0];
			$words = str_replace('[', '', $math);
			$words = str_replace(']', '', $words);
			$words = explode('|', $words);
			$textBefore = substr($_text, 0, strpos($_text, $math));
			foreach (self::generateTextVariant(substr($_text, strpos($_text, $math) + strlen($math))) as $remainsText) {
				foreach ($words as $word) {
					$return[] = $textBefore . $word . $remainsText;
				}
			}
		}
		return $return;
	}

	public static function searchByQuery($_query) {
		$values = array(
			'query' => '%' . $_query . '%',
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
        FROM interactDef
        WHERE query LIKE :query';

		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function regenerateInteract() {
		foreach (self::all() as $interactDef) {
			$interactDef->save();
		}
	}

	public static function getTagFromQuery($_def, $_query) {
		$_def = trim($_def);
		$_query = trim($_query);
		$options = array();
		$regexp = preg_quote(strtolower($_def));
		preg_match_all("/#(.*?)#/", $_def, $tags);
		if (count($tags[1]) > 0) {
			foreach ($tags[1] as $match) {
				$regexp = str_replace('#' . $match . '#', '(.*?)', $regexp);
			}
			preg_match_all("/" . $regexp . "$/", strtolower($_query), $matches, PREG_SET_ORDER);
			if (isset($matches[0])) {
				for ($i = 0; $i < count($tags[1]); $i++) {
					if (isset($matches[0][$i + 1])) {
						$options[$tags[1][$i]] = $matches[0][$i + 1];
					}
				}
			}
		}
		foreach ($tags[1] as $match) {
			if (!isset($options[$match])) {
				$options[$match] = '""';
			}
		}
		return $options;
	}

	public static function sanitizeQuery($_query) {
		$_query = str_replace(array("\'"), array("'"), $_query);
		$_query = preg_replace('/\s+/', ' ', $_query);
		$_query = ucfirst(strtolower($_query));
		return $_query;
	}

	public static function cleanInteract() {
		$list_id = array();
		foreach (self::all() as $interactDef) {
			$list_id[$interactDef->getId()] = $interactDef->getId();
		}
		$sql = 'DELETE FROM interactQuery WHERE interactDef_id NOT IN (' . implode(',', $list_id) . ')';
		return DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
	}

	/*     * *********************Méthodes d'instance************************* */

	public function checkQuery($_query) {
		if ($this->getOptions('allowSyntaxCheck', 1) == 1) {
			$exclude_regexp = "/l'(z|r|t|p|q|s|d|f|g|j|k|l|m|w|x|c|v|b|n)|( |^)la (a|e|y|u|i|o)|( |^)le (a|e|y|u|i|o)|( |^)du (a|e|y|u|i|o)/i";
			if (preg_match($exclude_regexp, $_query)) {
				return false;
			}
			$disallow = array(
				'le salle',
				'le chambre',
				'la dressing',
				'la salon',
				'le cuisine',
				'la jours',
				'la total',
				'(le|la) dehors',
				'la balcon',
				'du chambre',
				'du salle',
				'du cuisine',
				'la homecinema',
				'la led',
				'le led',
				'la pc',
				'la sol',
				'la conseil',
				'(la|les) lave\-vaisselle',
				'(la|les) lave\-linge',
				'la sonos',
				'(la|le) humidité',
				'la genre',
				'la résumé',
				'le bouton',
				'la status',
				'la volume',
				'le piste',
				'le consommation',
				'le position',
				'le puissance',
				'le luminosité',
				'le température',
				'(la|les) micro\-onde',
				'la mirroir',
				'la lapin',
				'la greenmomit',
				'le prise',
				'le frigo',
				'le (petite | )lumière',
				'la boutton',
				'la sommeil',
				'la temps',
				'la poids',
				'(la|les) heartbeat',
				'(la|le) heure',
				'la nombre',
				'la coût',
				'la titre',
				'la type',
				'la demain',
				'la pas',
				'la démarré',
				'la relai',
				'(la|le) vacance',
				'la coucher',
				'la lever',
				'la kodi',
				'la frigo',
				'la citronier',
				'la basilique',
				'la plante',
				'la mouvement',
				'la mode',
				'la statut',
				'la dns',
				'la thym',
				'lumière cuisine',
				'lumière salon',
				'lumière chambre',
				'lumière salle de bain',
				'la thumbnail',
				'la bouton',
				'la co',
				'la co2',
				'la répéter',
				'(fait-il|combien) chambre',
				'(fait-il|combien) salon',
				'(fait-il|combien) cuisine',
				'(fait-il|combien) salle',
				'(fait-il|combien) entrée',
				'(fait-il|combien) balcon',
				'(fait-il|combien) appartement',
				'dans le balcon',
				'le calorie',
				'le chansons',
				'le charge',
				'le demain',
				'le démarré',
				'le direction',
				'le distance',
				'le masse',
				'le mémoire',
				'le pr(é|e)sence',
				'le répéter',
				'le taille',
				'le fumée',
				'le pression',
				'le vitesse',
				'le condition',
				'les pc',
				'la tetris',
				'le bougies',
				'le myfox',
				'les homecinema',
				'les kodi',
				'les appartement',
			);
			if (preg_match('/( |^)' . implode('( |$)|( |^)', $disallow) . '( |$)/i', $_query)) {
				return false;
			}
		}
		if ($this->getOptions('exclude_regexp') != '' && preg_match($this->getOptions('exclude_regexp'), $_query)) {
			return false;
		}
		if (config::byKey('interact::regexpExcludGlobal') != '' && preg_match(config::byKey('interact::regexpExcludGlobal'), $_query)) {
			return false;
		}
		return true;
	}

	public function selectReply() {
		$replies = self::generateTextVariant($this->getReply());
		$random = rand(0, count($replies) - 1);
		return $replies[$random];
	}

	public function preSave() {
		if ($this->getOptions('allowSyntaxCheck') === '') {
			$this->setOptions('allowSyntaxCheck', 1);
		}
	}

	public function save() {
		if ($this->getQuery() == '') {
			throw new Exception(__('La commande (demande) ne peut pas être vide', __FILE__));
		}
		return DB::save($this);
	}

	public function postSave() {
		$queries = $this->generateQueryVariant();
		interactQuery::removeByInteractDefId($this->getId());
		DB::beginTransaction();
		foreach ($queries as $query) {
			$query['query'] = self::sanitizeQuery($query['query']);
			if (!$this->checkQuery($query['query'])) {
				continue;
			}
			$interactQuery = new interactQuery();
			$interactQuery->setInteractDef_id($this->getId());
			$interactQuery->setQuery($query['query']);
			$interactQuery->setActions('cmd', $query['cmd']);
			$interactQuery->save();
		}
		DB::commit();
		self::cleanInteract();
	}

	public function remove() {
		DB::remove($this);
	}

	public function preRemove() {
		interactQuery::removeByInteractDefId($this->getId());
	}

	public function postRemove() {
		self::cleanInteract();
	}

	public function generateQueryVariant() {
		$inputs = self::generateTextVariant($this->getQuery());
		$return = array();
		$object_filter = $this->getFiltres('object');
		$type_filter = $this->getFiltres('type');
		$subtype_filter = $this->getFiltres('subtype');
		$unite_filter = $this->getFiltres('unite');
		$plugin_filter = $this->getFiltres('plugin');
		$category_filter = $this->getFiltres('category');
		$eqLogic_category_filter = $this->getFiltres('eqLogic_category');
		foreach ($inputs as $input) {
			preg_match_all("/#(.*?)#/", $input, $matches);
			$matches = $matches[1];
			if (in_array('commande', $matches) && (in_array('objet', $matches) || in_array('equipement', $matches))) {
				foreach (object::all() as $object) {
					if (isset($object_filter[$object->getId()]) && $object_filter[$object->getId()] == 0) {
						continue;
					}
					foreach ($object->getEqLogic() as $eqLogic) {
						if ($this->getFiltres('eqLogic_id', 'all') != 'all' && $eqLogic->getId() != $this->getFiltres('eqLogic_id')) {
							continue;
						}
						if (isset($plugin_filter[$eqLogic->getEqType_name()]) && $plugin_filter[$eqLogic->getEqType_name()] == 0) {
							continue;
						}
						$eq_caterogy = $eqLogic->getCategory();
						$category_ok = true;
						if (is_array($category_filter)) {
							$category_ok = false;
							foreach ($category_filter as $category => $value) {
								if ($value == 1 && $eqLogic->getCategory($category) == 1) {
									$category_ok = true;
									break;
								}
							}
						}
						if (!$category_ok) {
							continue;
						}
						foreach ($eqLogic->getCmd() as $cmd) {
							if (isset($subtype_filter[$cmd->getSubType()]) && $subtype_filter[$cmd->getSubType()] == 0) {
								continue;
							}
							if (isset($type_filter[$cmd->getType()]) && $type_filter[$cmd->getType()] == 0) {
								continue;
							}
							if ($cmd->getUnite() == '') {
								if (isset($unite_filter['none']) && $unite_filter['none'] == 0) {
									continue;
								}
							} else {
								if (isset($unite_filter[$cmd->getUnite()]) && $unite_filter[$cmd->getUnite()] == 0) {
									continue;
								}
							}

							$replace = array(
								'#objet#' => strtolower($object->getName()),
								'#commande#' => strtolower($cmd->getName()),
								'#equipement#' => strtolower($eqLogic->getName()),
							);
							$options = array();
							if ($cmd->getType() == 'action') {
								if ($cmd->getSubtype() == 'color') {
									$options['color'] = '#color#';
								}
								if ($cmd->getSubtype() == 'slider') {
									$options['slider'] = '#slider#';
								}
								if ($cmd->getSubtype() == 'message') {
									$options['message'] = '#message#';
									$options['title'] = '#title#';
								}
							}
							$query = str_replace(array_keys($replace), $replace, $input);
							$return[$query] = array(
								'query' => $query,
								'cmd' => array(array('cmd' => '#' . $cmd->getId() . '#', 'options' => $options)),

							);
						}
					}
				}
			}
		}

		if (count($return) == 0) {
			foreach ($inputs as $input) {
				$return[] = array(
					'query' => $input,
					'cmd' => $this->getActions('cmd'),
				);
			}
		}
		if ($this->getOptions('synonymes') != '') {
			$queries = $return;
			$synonymes = array();
			foreach (explode('|', $this->getOptions('synonymes')) as $value) {
				$values = explode('=', $value);
				$synonymes[strtolower($values[0])] = explode(',', $values[1]);
			}
			$return = array();
			foreach ($queries as $query) {
				foreach (self::generateSynonymeVariante($query['query'], $synonymes) as $synonyme) {
					$query_info = $query;
					$query_info['query'] = $synonyme;
					$return[$synonyme] = $query_info;
				}
			}
		}
		return $return;
	}

	public static function generateSynonymeVariante($_text, $_synonymes) {
		$return = array();
		if (count($_synonymes) > 0) {
			foreach ($_synonymes as $replace => $values) {
				if (stripos($_text, $replace) !== false &&
					(substr($_text, stripos($_text, $replace) - 1, 1) == ' ' || stripos($_text, $replace) - 1 < 0) &&
					(substr($_text, stripos($_text, $replace) + strlen($replace), 1) == ' ' || stripos($_text, $replace) + strlen($replace) + 1 > strlen($_text))) {
					$start = stripos($_text, $replace);
					foreach (self::generateSynonymeVariante(substr($_text, $start + strlen($replace)), $_synonymes) as $endSentence) {
						foreach ($values as $value) {
							$return[] = substr($_text, 0, $start) . $value . $endSentence;
						}
					}
				} else {
					$return[] = $_text;
				}
			}
		} else {
			$return[] = $_text;
		}
		return $return;
	}

	public function getLinkToConfiguration() {
		return 'index.php?v=d&p=interact&id=' . $this->getId();
	}

	public function getHumanName() {
		if ($this->getName() != '') {
			return $this->getName();
		}
		return $this->getQuery();
	}

/*     * **********************Getteur Setteur*************************** */

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

	public function getReply() {
		return $this->reply;
	}

	public function setReply($reply) {
		$this->reply = $reply;
	}

	public function getPerson() {
		return $this->person;
	}

	public function setPerson($person) {
		$this->person = $person;
	}

	public function getOptions($_key = '', $_default = '') {
		return utils::getJsonAttr($this->options, $_key, $_default);
	}

	public function setOptions($_key, $_value) {
		$this->options = utils::setJsonAttr($this->options, $_key, $_value);
	}

	public function getFiltres($_key = '', $_default = '') {
		return utils::getJsonAttr($this->filtres, $_key, $_default);
	}

	public function setFiltres($_key, $_value) {
		$this->filtres = utils::setJsonAttr($this->filtres, $_key, $_value);
	}

	public function getPosition() {
		return $this->position;
	}

	public function setPosition($position) {
		$this->position = $position;
	}

	public function getEnable() {
		return $this->enable;
	}

	public function setEnable($enable) {
		$this->enable = $enable;
	}

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function getGroup() {
		return $this->group;
	}

	public function setGroup($group) {
		$this->group = $group;
	}

	public function getActions($_key = '', $_default = '') {
		return utils::getJsonAttr($this->actions, $_key, $_default);
	}

	public function setActions($_key, $_value) {
		$this->actions = utils::setJsonAttr($this->actions, $_key, $_value);
	}

}

?>
