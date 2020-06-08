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

class interactDef {
	/*     * *************************Attributs****************************** */
	
	private $id;
	private $name;
	private $filtres;
	private $query;
	private $reply;
	private $person;
	private $options;
	private $enable;
	private $group;
	private $actions;
	private $display;
	private $_changed = false;
	
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
			ORDER BY name,query';
		} else if ($_group === null) {
			$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
			FROM interactDef
			WHERE (`group` IS NULL OR `group` = "")
			ORDER BY name,query';
		} else {
			$values['group'] = $_group;
			$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
			FROM interactDef
			WHERE `group`=:group
			ORDER BY name,query';
		}
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}
	
	public static function listGroup($_group = null) {
		$values = array();
		$sql = 'SELECT DISTINCT(`group`)
		FROM interactDef';
		if ($_group !== null) {
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
		$_def = self::sanitizeQuery(trim($_def));
		$_query = self::sanitizeQuery(trim($_query));
		$options = array();
		$regexp = preg_quote(strtolower($_def));
		preg_match_all("/#(.*?)#/", $_def, $tags);
		if (count($tags[1]) > 0) {
			foreach ($tags[1] as $match) {
				$regexp = str_replace(preg_quote('#' . $match . '#'), '(.*?)', $regexp);
			}
			preg_match_all("/" . $regexp . "$/", strtolower($_query), $matches, PREG_SET_ORDER);
			if (isset($matches[0])) {
				$countTags = count($tags[1]);
				for ($i = 0; $i < $countTags; $i++) {
					if (isset($matches[0][$i + 1])) {
						$options['#' . $tags[1][$i] . '#'] = $matches[0][$i + 1];
					}
				}
			}
		}
		foreach ($tags[1] as $match) {
			if (!isset($options['#' . $match . '#'])) {
				$options['#' . $match . '#'] = '';
			}
		}
		return $options;
	}
	
	public static function sanitizeQuery($_query) {
		$_query = str_replace(array("\'"), array("'"), $_query);
		$_query = preg_replace('/\s+/', ' ', $_query);
		$_query = ucfirst(strtolower($_query));
		$_query = strtolower(sanitizeAccent($_query));
		return $_query;
	}
	
	public static function deadCmd() {
		$return = array();
		foreach (interactDef::all() as $interact) {
			//var_dump($interact->getActions('cmd'));
			foreach ($interact->getActions('cmd') as $cmd) {
				$json = json_encode($cmd);
				preg_match_all("/#([0-9]*)#/", $json, $matches);
				foreach ($matches[1] as $cmd_id) {
					if (is_numeric($cmd_id)) {
						if (!cmd::byId(str_replace('#', '', $cmd_id))) {
							$return[] = array('detail' => 'Interaction : ' . $interact->getHumanName(), 'help' => 'Action', 'who' => '#' . $cmd_id . '#');
						}
					}
				}
				if (is_string($interact->getReply()) && $interact->getReply() != '') {
					preg_match_all("/#([0-9]*)#/", $interact->getReply(), $matches);
					foreach ($matches[1] as $cmd_id) {
						if (is_numeric($cmd_id)) {
							if (!cmd::byId(str_replace('#', '', $cmd_id))) {
								$return[] = array('detail' => 'Interaction : ' . $interact->getHumanName(), 'help' => 'Réponse', 'who' => '#' . $cmd_id . '#');
							}
						}
					}
				}
			}
		}
		return $return;
	}
	
	public static function cleanInteract() {
		$list_id = array();
		foreach (self::all() as $interactDef) {
			$list_id[$interactDef->getId()] = $interactDef->getId();
		}
		if (count($list_id) > 0) {
			$sql = 'DELETE FROM interactQuery WHERE interactDef_id NOT IN (' . implode(',', $list_id) . ')';
			return DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
		}
	}
	
	public static function searchByUse($_search) {
		$return = array();
		if (!is_array($_search)) {
			$values = array(
				'search' => '%' . $_search . '%',
			);
			$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
			FROM interactDef
			WHERE actions LIKE :search
			OR reply LIKE :search';
		} else {
			$values = array(
				'search' => '%' . $_search[0] . '%',
			);
			$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
			FROM interactDef
			WHERE actions LIKE :search
			OR reply LIKE :search';
			for ($i = 1; $i < count($_search); $i++) {
				$values['search' . $i] = '%' . $_search[$i] . '%';
				$sql .= ' OR actions LIKE :search' . $i . '
				OR reply LIKE :search' . $i;
			}
		}
		$interactDefs = DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
		$interactQueries = interactQuery::searchActions($_search);
		foreach ($interactQueries as $interactQuery) {
			$interactDefs[] = $interactQuery->getInteractDef();
		}
		foreach ($interactDefs as $interactDef) {
			if (!isset($return[$interactDef->getId()])) {
				$return[$interactDef->getId()] = $interactDef;
			}
		}
		return $return;
	}
	
	/*     * *********************Méthodes d'instance************************* */
	
	public function checkQuery($_query) {
		if ($this->getOptions('allowSyntaxCheck', 1) == 1) {
			$exclude_regexp = "/l'(z|r|t|p|q|s|d|f|g|j|k|l|m|w|x|c|v|b|n|y| )|( |^)la (a|e|u|i|o)|( |^)le (a|e|u|i|o)|( |^)du (a|e|u|i|o)/i";
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
				'le maison',
				'du maison',
				'le buanderie',
				'du buanderie',
				'la bureau',
				'de salon',
				'de maison',
				'de chambre',
				'de cuisine',
				'de espace',
				'de salle de bain',
				'(dans|quelqu\'un) entr(é|e)e',
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
	
	public function preInsert() {
		if ($this->getReply() == '') {
			$this->setReply('#valeur#');
		}
		$this->setEnable(1);
	}
	
	public function preSave() {
		if ($this->getOptions('allowSyntaxCheck') === '') {
			$this->setOptions('allowSyntaxCheck', 1);
		}
		if ($this->getFiltres('eqLogic_id') == '') {
			$this->setFiltres('eqLogic_id', 'all');
		}
	}
	
	public function save() {
		if ($this->getQuery() == '') {
			throw new Exception(__('La commande (demande) ne peut pas être vide', __FILE__));
		}
		DB::save($this);
		return true;
	}
	
	public function postSave() {
		$queries = $this->generateQueryVariant();
		interactQuery::removeByInteractDefId($this->getId());
		if ($this->getEnable()) {
			DB::beginTransaction();
			foreach ($queries as $query) {
				$query['query'] = self::sanitizeQuery($query['query']);
				if (trim($query['query']) == '') {
					continue;
				}
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
		}
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
		$visible_filter = $this->getFiltres('visible');
		$category_filter = $this->getFiltres('category');
		foreach ($inputs as $input) {
			preg_match_all("/#(.*?)#/", $input, $matches);
			$matches = $matches[1];
			if (in_array('commande', $matches) || (in_array('objet', $matches) || in_array('equipement', $matches))) {
				foreach (jeeObject::all() as $object) {
					if (isset($object_filter[$object->getId()]) && $object_filter[$object->getId()] == 0) {
						continue;
					}
					if (isset($visible_filter['object']) && $visible_filter['object'] == 1 && $object->getIsVisible() != 1) {
						continue;
					}
					foreach ($object->getEqLogic() as $eqLogic) {
						if ($this->getFiltres('eqLogic_id', 'all') != 'all' && $eqLogic->getId() != $this->getFiltres('eqLogic_id')) {
							continue;
						}
						if (isset($plugin_filter[$eqLogic->getEqType_name()]) && $plugin_filter[$eqLogic->getEqType_name()] == 0) {
							continue;
						}
						if (isset($visible_filter['eqLogic']) && $visible_filter['eqLogic'] == 1 && $eqLogic->getIsVisible() != 1) {
							continue;
						}
						
						$category_ok = true;
						if (is_array($category_filter)) {
							$category_ok = false;
							foreach ($category_filter as $category => $value) {
								if ($value == 1) {
									if ($eqLogic->getCategory($category) == 1) {
										$category_ok = true;
										break;
									}
									if ($category == 'noCategory' && $eqLogic->getPrimaryCategory() == '') {
										$category_ok = true;
										break;
									}
								}
							}
						}
						if (!$category_ok) {
							continue;
						}
						foreach ($eqLogic->getCmd() as $cmd) {
							if (isset($visible_filter['cmd']) && $visible_filter['cmd'] == 1 && $cmd->getIsVisible() != 1) {
								continue;
							}
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
			$synonymes = array();
			foreach (explode('|', $this->getOptions('synonymes')) as $value) {
				$values = explode('=', $value);
				if (count($values) != 2) {
					continue;
				}
				$synonymes[self::sanitizeQuery($values[0])] = explode(',', self::sanitizeQuery($values[1]));
			}
			$result = array();
			foreach ($return as $query) {
				$results = self::generateSynonymeVariante(self::sanitizeQuery($query['query']), $synonymes);
				if (count($results) == 0) {
					continue;
				}
				foreach ($results as $result) {
					$query_info = $query;
					$query_info['query'] = $result;
					$return[$result] = $query_info;
				}
			}
		}
		return $return;
	}
	
	public static function generateSynonymeVariante($_text, $_synonymes, $_deep = 0) {
		$return = array();
		if (count($_synonymes) == 0) {
			return $return;
		}
		if ($_deep > 10) {
			return $return;
		}
		$_deep++;
		foreach ($_synonymes as $replace => $values) {
			foreach ($values as $value) {
				$result = @preg_replace('/\b' . $replace . '\b/iu', $value, $_text);
				if ($result != $_text) {
					$synonymes = $_synonymes;
					unset($synonymes[$replace]);
					$return = array_merge($return, self::generateSynonymeVariante($result, $synonymes, $_deep));
					$return[] = $result;
				}
			}
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
	
	public function getLinkData(&$_data = array('node' => array(), 'link' => array()), $_level = 0, $_drill = 3) {
		if (isset($_data['node']['interactDef' . $this->getId()])) {
			return;
		}
		$_level++;
		if ($_level > $_drill) {
			return $_data;
		}
		$icon = findCodeIcon('fa-comments-o');
		$_data['node']['interactDef' . $this->getId()] = array(
			'id' => 'interactDef' . $this->getId(),
			'type' => __('Intéraction',__FILE__),
			'name' => substr($this->getHumanName(), 0, 20),
			'icon' => $icon['icon'],
			'fontfamily' => $icon['fontfamily'],
			'fontsize' => '1.5em',
			'fontweight' => ($_level == 1) ? 'bold' : 'normal',
			'texty' => -14,
			'textx' => 0,
			'title' => $this->getHumanName(),
			'url' => 'index.php?v=d&p=interact&id=' . $this->getId(),
		);
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
	
	public function getQuery() {
		return $this->query;
	}
	
	public function setQuery($_query) {
		$this->_changed = utils::attrChanged($this->_changed,$this->query,$_query);
		$this->query = $_query;
		return $this;
	}
	
	public function getReply() {
		return $this->reply;
	}
	
	public function setReply($_reply) {
		$this->_changed = utils::attrChanged($this->_changed,$this->reply,$_reply);
		$this->reply = $_reply;
		return $this;
	}
	
	public function getPerson() {
		return $this->person;
	}
	
	public function setPerson($_person) {
		$this->_changed = utils::attrChanged($this->_changed,$this->person,$_person);
		$this->person = $_person;
		return $this;
	}
	
	public function getOptions($_key = '', $_default = '') {
		return utils::getJsonAttr($this->options, $_key, $_default);
	}
	
	public function setOptions($_key, $_value) {
		$options = utils::setJsonAttr($this->options, $_key, $_value);
		$this->_changed = utils::attrChanged($this->_changed,$this->options,$options);
		$this->options = $options;
		return $this;
	}
	
	public function getFiltres($_key = '', $_default = '') {
		return utils::getJsonAttr($this->filtres, $_key, $_default);
	}
	
	public function setFiltres($_key, $_value) {
		$filtres = utils::setJsonAttr($this->filtres, $_key, $_value);
		$this->_changed = utils::attrChanged($this->_changed,$this->filtres,$filtres);
		$this->filtres = $filtres;
		return $this;
	}
	
	public function getEnable() {
		return $this->enable;
	}
	
	public function setEnable($_enable) {
		$this->_changed = utils::attrChanged($this->_changed,$this->enable,$_enable);
		$this->enable = $_enable;
		return $this;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function setName($_name) {
		$this->_changed = utils::attrChanged($this->_changed,$this->name,$_name);
		$this->name = $_name;
		return $this;
	}
	
	public function getGroup() {
		return $this->group;
	}
	
	public function setGroup($_group) {
		$this->_changed = utils::attrChanged($this->_changed,$this->group,$_group);
		$this->group = $_group;
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
	
	public function getDisplay($_key = '', $_default = '') {
		return utils::getJsonAttr($this->display, $_key, $_default);
	}
	
	public function setDisplay($_key, $_value) {
		$display = utils::setJsonAttr($this->display, $_key, $_value);
		$this->_changed = utils::attrChanged($this->_changed,$this->display,$display);
		$this->display = $display;
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
