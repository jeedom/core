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

class scenario {
    /*     * *************************Attributs****************************** */

    private $id;
    private $name;
    private $isActive = 1;
    private $group = '';
    private $state = 'stop';
    private $lastLaunch = null;
    private $mode;
    private $schedule;
    private $pid;
    private $scenarioElement;
    private $trigger;
    private $log;
    private $timeout = 0;
    private $object_id = null;
    private $isVisible = 1;
    private $hlogs;
    private $display;
    private $description;
    private $configuration;
    private $_internalEvent = 0;
    private static $_templateArray;
    private $_elements = array();
    private $_changeState = false;
    private $_realTrigger = '';

    /*     * ***********************Methode static*************************** */

    /**
     * Renvoit un object scenario
     * @param int  $_id id du scenario voulu
     * @return scenario object scenario
     */
    public static function byId($_id) {
        $values = array(
            'id' => $_id
        );
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '  
                FROM scenario 
                WHERE id=:id';
        return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
    }

    /**
     * Renvoit tous les objects scenario
     * @return [] scenario object scenario
     */
    public static function all($_group = '') {
        if ($_group === '') {
            $sql = 'SELECT ' . DB::buildField(__CLASS__, 's') . ' 
                    FROM scenario s
                    INNER JOIN object ob ON s.object_id=ob.id
                    ORDER BY ob.name,s.group, s.name';
            $result1 = DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
            $sql = 'SELECT ' . DB::buildField(__CLASS__, 's') . '  
                    FROM scenario s
                    WHERE s.object_id IS NULL
                    ORDER BY s.group, s.name';
            $result2 = DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
            return array_merge($result1, $result2);
        } else if ($_group === null) {
            $sql = 'SELECT ' . DB::buildField(__CLASS__, 's') . '  
                    FROM scenario s
                    INNER JOIN object ob ON s.object_id=ob.id
                    WHERE (`group` IS NULL OR `group` = "")
                    ORDER BY ob.name, s.name';
            $result1 = DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
            $sql = 'SELECT ' . DB::buildField(__CLASS__, 's') . '  
                    FROM scenario s
                    WHERE (`group` IS NULL OR `group` = "")
                        AND s.object_id IS NULL
                    ORDER BY  s.name';
            $result2 = DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
            return array_merge($result1, $result2);
        } else {
            $values = array(
                'group' => $_group
            );
            $sql = 'SELECT ' . DB::buildField(__CLASS__, 's') . '  
                    FROM scenario s
                    INNER JOIN object ob ON s.object_id=ob.id
                    WHERE `group`=:group
                    ORDER BY ob.name,s.group, s.name';
            $result1 = DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
            $sql = 'SELECT ' . DB::buildField(__CLASS__, 's') . '  
                    FROM scenario s
                    WHERE `group`=:group
                        AND s.object_id IS NULL
                    ORDER BY s.group, s.name';
            $result2 = DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
            return array_merge($result1, $result2);
        }
    }

    public static function schedule() {
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '  
                FROM scenario
                WHERE `mode` != "provoke"
                    AND isActive=1
                    AND state!="in progress"';
        return DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
    }

    public static function listGroup($_group = null) {
        $values = array();
        $sql = 'SELECT DISTINCT(`group`)
                FROM scenario';
        if ($_group != null) {
            $values['group'] = '%' . $_group . '%';
            $sql .= ' WHERE `group` LIKE :group';
        }
        $sql .= ' ORDER BY `group`';
        return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL);
    }

    public static function byTrigger($_cmd_id) {
        $values = array(
            'cmd_id' => '%#' . $_cmd_id . '#%'
        );
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '  
                    FROM scenario
                    WHERE mode != "schedule"
                    AND `trigger` LIKE :cmd_id';
        return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
    }

    public static function byElement($_element_id) {
        $values = array(
            'element_id' => '%' . $_element_id . '%'
        );
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '  
                    FROM scenario
                    WHERE `scenarioElement` LIKE :element_id';
        return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
    }

    public static function byObjectId($_object_id, $_onlyEnable = true, $_onlyVisible = false) {
        $values = array();
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '  
                FROM scenario';
        if ($_object_id == null) {
            $sql .= ' WHERE object_id IS NULL';
        } else {
            $values['object_id'] = $_object_id;
            $sql .= ' WHERE object_id=:object_id';
        }
        if ($_onlyEnable) {
            $sql .= ' AND isActive = 1';
        }
        if ($_onlyVisible) {
            $sql .= ' AND isVisible = 1';
        }
        return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
    }

    public static function check($_event = null) {
        $message = '';
        if ($_event != null) {
            if (is_object($_event)) {
                $scenarios = self::byTrigger($_event->getId());
                $trigger = '#' . $_event->getId() . '#';
                $message = __('Scenario lance automatiquement sur evenement venant de : ', __FILE__) . $_event->getHumanName();
            } else {
                $scenarios = self::byTrigger($_event);
                $trigger = $_event;
                $message = __('Scenario lance sur evenement : #', __FILE__) . $_event . '#';
            }
        } else {
            $message = __('Scenario lance automatiquement sur programmation', __FILE__);
            $scenarios = scenario::all();
            $dateOk = jeedom::isDateOk();
            $trigger = '#schedule#';
            foreach ($scenarios as $key => &$scenario) {
                if ($scenario->getState() == 'in progress') {
                    if ($scenario->running()) {
                        if ($scenario->getTimeout() > 0 && (strtotime('now') - strtotime($scenario->getLastLaunch())) > $scenario->getTimeout()) {
                            $scenario->setLog(__('Erreur : le scénario est tombé en timeout', __FILE__));
                            try {
                                $scenario->stop();
                            } catch (Exception $e) {
                                $scenario->setLog(__('Erreur : le scénario est tombé en timeout mais il est impossible de l\'arreter : ', __FILE__) . $e->getMessage());
                                $scenario->save();
                            }
                        }
                    } else {
                        $scenario->setLog(__('Erreur : le scénario c\'est incident (toujours marqué en cours mais arreté)', __FILE__));
                        $scenario->setState('error');
                        $scenario->save();
                    }
                }
                if ($dateOk && $scenario->getIsActive() == 1 && $scenario->getState() != 'in progress' && ($scenario->getMode() == 'schedule' || $scenario->getMode() == 'all')) {
                    if (!$scenario->isDue()) {
                        unset($scenarios[$key]);
                    }
                } else {
                    unset($scenarios[$key]);
                }
            }
        }
        if (count($scenarios) == 0) {
            return true;
        }
        foreach ($scenarios as $scenario_) {
            $scenario_->launch(false, $trigger, $message);
        }
        return true;
    }

    public static function doIn($_options) {
        $scenario = self::byId($_options['scenario_id']);
        $scenarioElement = scenarioElement::byId($_options['scenarioElement_id']);
        $scenario->setLog(__('************Lancement sous tâche**************', __FILE__));
        if (!is_object($scenarioElement) || !is_object($scenario)) {
            return;
        }
        if (is_numeric($_options['second']) && $_options['second'] > 0) {
            sleep($_options['second']);
        }
        $scenarioElement->getSubElement('do')->execute($scenario);
        $scenario->setLog(__('************FIN sous tâche**************', __FILE__));
        if (!$scenario->running()) {
            $scenario->setState('stop');
        }
        $scenario->save();
    }

    public static function cleanTable() {
        $ids = array(
            'element' => array(),
            'subelement' => array(),
            'expression' => array(),
        );
        foreach (scenario::all() as $scenario) {
            foreach ($scenario->getElement() as $element) {
                $result = $element->getAllId();
                $ids['element'] = array_merge($ids['element'], $result['element']);
                $ids['subelement'] = array_merge($ids['subelement'], $result['subelement']);
                $ids['expression'] = array_merge($ids['expression'], $result['expression']);
            }
        }

        $sql = 'DELETE FROM scenarioExpression WHERE id NOT IN (-1';
        foreach ($ids['expression'] as $expression_id) {
            $sql .= ',' . $expression_id;
        }
        $sql .= ')';
        DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL);

        $sql = 'DELETE FROM scenarioSubElement WHERE id NOT IN (-1';
        foreach ($ids['subelement'] as $subelement_id) {
            $sql .= ',' . $subelement_id;
        }
        $sql .= ')';
        DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL);

        $sql = 'DELETE FROM scenarioElement WHERE id NOT IN (-1';
        foreach ($ids['element'] as $element_id) {
            $sql .= ',' . $element_id;
        }
        $sql .= ')';
        DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL);
    }

    public static function byObjectNameGroupNameScenarioName($_object_name, $_group_name, $_scenario_name) {
        $values = array(
            'scenario_name' => html_entity_decode($_scenario_name),
        );

        if ($_object_name == __('Aucun', __FILE__)) {
            if ($_group_name == __('Aucun', __FILE__)) {
                $sql = 'SELECT ' . DB::buildField(__CLASS__, 's') . '
                        FROM scenario s
                        WHERE s.name=:scenario_name
                            AND (`group` IS NULL OR `group`=""  OR `group`="Aucun" OR `group`="None")
                            AND s.object_id IS NULL';
            } else {
                $values['group_name'] = $_group_name;
                $sql = 'SELECT ' . DB::buildField(__CLASS__, 's') . '
                        FROM scenario s
                        WHERE s.name=:scenario_name
                            AND s.object_id IS NULL
                            AND `group`=:group_name';
            }
        } else {
            $values['object_name'] = $_object_name;
            if ($_group_name == __('Aucun', __FILE__)) {
                $sql = 'SELECT ' . DB::buildField(__CLASS__, 's') . '
                        FROM scenario s
                        INNER JOIN object ob ON s.object_id=ob.id
                        WHERE s.name=:scenario_name
                            AND ob.name=:object_name
                            AND (`group` IS NULL OR `group`=""  OR `group`="Aucun" OR `group`="None")';
            } else {
                $values['group_name'] = $_group_name;
                $sql = 'SELECT ' . DB::buildField(__CLASS__, 's') . '
                        FROM scenario s
                        INNER JOIN object ob ON s.object_id=ob.id
                        WHERE s.name=:scenario_name
                            AND ob.name=:object_name
                            AND `group`=:group_name';
            }
        }
        return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
    }

    public static function toHumanReadable($_input) {
        if (is_object($_input)) {
            $reflections = array();
            $uuid = spl_object_hash($_input);
            if (!isset($reflections[$uuid])) {
                $reflections[$uuid] = new ReflectionClass($_input);
            }
            $reflection = $reflections[$uuid];
            $properties = $reflection->getProperties();
            foreach ($properties as $property) {
                $property->setAccessible(true);
                $value = $property->getValue($_input);
                $property->setValue($_input, self::toHumanReadable($value));
                $property->setAccessible(false);
            }
            return $_input;
        }
        if (is_array($_input)) {
            foreach ($_input as $key => $value) {
                $_input[$key] = self::toHumanReadable($value);
            }
            return $_input;
        }
        $text = $_input;
        preg_match_all("/#scenario([0-9]*)#/", $text, $matches);
        foreach ($matches[1] as $scenario_id) {
            if (is_numeric($scenario_id)) {
                $scenario = self::byId($scenario_id);
                if (is_object($scenario)) {
                    $text = str_replace('#scenario' . $scenario_id . '#', '#' . $scenario->getHumanName(true) . '#', $text);
                }
            }
        }
        return $text;
    }

    public static function fromHumanReadable($_input) {
        $isJson = false;
        if (is_json($_input)) {
            $isJson = true;
            $_input = json_decode($_input, true);
        }
        if (is_object($_input)) {
            $reflections = array();
            $uuid = spl_object_hash($_input);
            if (!isset($reflections[$uuid])) {
                $reflections[$uuid] = new ReflectionClass($_input);
            }
            $reflection = $reflections[$uuid];
            $properties = $reflection->getProperties();
            foreach ($properties as $property) {
                $property->setAccessible(true);
                $value = $property->getValue($_input);
                $property->setValue($_input, self::fromHumanReadable($value));
                $property->setAccessible(false);
            }
            return $_input;
        }
        if (is_array($_input)) {
            foreach ($_input as $key => $value) {
                $_input[$key] = self::fromHumanReadable($value);
            }
            if ($isJson) {
                return json_encode($_input, JSON_UNESCAPED_UNICODE);
            }
            return $_input;
        }
        $text = $_input;

        preg_match_all("/#\[(.*?)\]\[(.*?)\]\[(.*?)\]#/", $text, $matches);
        if (count($matches) == 4) {
            for ($i = 0; $i < count($matches[0]); $i++) {
                if (isset($matches[1][$i]) && isset($matches[2][$i]) && isset($matches[3][$i])) {
                    $scenario = self::byObjectNameGroupNameScenarioName($matches[1][$i], $matches[2][$i], $matches[3][$i]);
                    if (is_object($scenario)) {
                        $text = str_replace($matches[0][$i], '#scenario' . $scenario->getId() . '#', $text);
                    }
                }
            }
        }

        return $text;
    }

    public static function byUsedCommand($_cmd_id) {
        $scenarios = null;
        $return = self::byTrigger($_cmd_id);
        $expressions = scenarioExpression::searchExpression('#' . $_cmd_id . '#');
        if (is_array($expressions)) {
            foreach ($expressions as $expression) {
                $scenarios[] = $expression->getSubElement()->getElement()->getScenario();
            }
        }
        if (is_array($scenarios)) {
            foreach ($scenarios as $scenario) {
                if (is_object($scenario)) {
                    $find = false;
                    foreach ($return as $existScenario) {
                        if ($scenario->getId() == $existScenario->getId()) {
                            $find = true;
                            break;
                        }
                    }
                    if (!$find) {
                        $return[] = $scenario;
                    }
                }
            }
        }
        return $return;
    }

    /*     * *********************Methode d'instance************************* */

    public function launch($_force = false, $_trigger = '', $_message = '') {
        if ($this->getIsActive() != 1) {
            return;
        }
        if (config::byKey('enableScenario') == 1) {
            if ($this->getConfiguration('launchInForeground', 0) == 1) {
                $this->execute($_trigger, $_message);
            } else {
                $cmd = 'php ' . dirname(__FILE__) . '/../../core/php/jeeScenario.php ';
                $cmd.= ' scenario_id=' . $this->getId();
                $cmd.= ' force=' . $_force;
                $cmd.= ' trigger=' . escapeshellarg($_trigger);
                $cmd.= ' message=' . escapeshellarg($_message);
                $cmd.= ' >> ' . log::getPathToLog('scenario_execution') . ' 2>&1 &';
                exec($cmd);
            }
            return true;
        }
        return false;
    }

    public function execute($_trigger = '', $_message = '') {
        $logs = $this->getHlogs();
        if (trim($this->getLog()) != '') {
            if (is_array($logs)) {
                if (count($logs) > 5) {
                    array_pop($logs);
                }
                array_unshift($logs, $this->getLog());
                $this->setHlogs($logs);
            } else {
                $this->setHlogs(array($this->getLog()));
            }
        }
        if ($this->getIsActive() != 1) {
            $this->setLog(__('Impossible d\'éxecuter le scénario : ', __FILE__) . $this->getHumanName() . ' sur : ' . $_message . ' car il est désactivé');
            $this->setDisplay('icon', '');
            $this->save();
            return;
        }
        $this->setLog('');
        $this->setDisplay('icon', '');
        $this->setLog(__('Début exécution du scénario : ', __FILE__) . $this->getHumanName() . '. ' . $_message);
        $this->setState('in progress');
        $this->setPID(getmypid());
        $this->setLastLaunch(date('Y-m-d H:i:s'));
        $this->save();
        $this->setRealTrigger($_trigger);
        foreach ($this->getElement() as $element) {
            $element->execute($this);
        }
        $this->setState('stop');
        $this->setPID('');
        if ($this->getIsActive() == 1) {
            $scenario = self::byId($this->getId());
            if (is_object($scenario)) {
                $this->setIsActive($scenario->getIsActive());
            }
        }
        $this->save();
        return true;
    }

    public function copy($_name) {
        $scenarioCopy = clone $this;
        $scenarioCopy->setName($_name);
        $scenarioCopy->setId('');
        $scenario_element_list = array();
        foreach ($this->getElement() as $element) {
            $scenario_element_list[] = $element->copy();
        }
        $scenarioCopy->setScenarioElement($scenario_element_list);
        $scenarioCopy->setLog('');
        $scenarioCopy->save();
        return $scenarioCopy;
    }

    public function toHtml($_version) {
        if (!$this->hasRight('r')) {
            return '';
        }
        $_version = jeedom::versionAlias($_version);
        $replace = array(
            '#id#' => $this->getId(),
            '#state#' => $this->getState(),
            '#isActive#' => $this->getIsActive(),
            '#name#' => ($this->getDisplay('name') != '') ? $this->getDisplay('name') : $this->getHumanName(),
            '#icon#' => $this->getIcon(),
            '#lastLaunch#' => $this->getLastLaunch(),
            '#scenarioLink#' => $this->getLinkToConfiguration(),
        );
        if (!isset(self::$_templateArray)) {
            self::$_templateArray = array();
        }
        if (!isset(self::$_templateArray[$_version])) {
            self::$_templateArray[$_version] = getTemplate('core', $_version, 'scenario');
        }
        return template_replace($replace, self::$_templateArray[$_version]);
    }

    public function getIcon($_only_class = false) {
        if ($_only_class) {
            if ($this->getIsActive() == 1) {
                switch ($this->getState()) {
                    case 'in progress':
                        return 'fa fa-spinner fa-spin';
                    case 'error':
                        return 'fa fa-exclamation-triangle';
                    default:
                        if (strpos($this->getDisplay('icon'), '<i') === 0) {
                            return str_replace(array('<i', 'class=', '"', '/>'), '', $this->getDisplay('icon'));
                        }
                        return 'fa fa-check';
                }
            } else {
                return 'fa fa-times';
            }
        } else {
            if ($this->getIsActive() == 1) {
                switch ($this->getState()) {
                    case 'in progress':
                        return '<i class="fa fa-spinner fa-spin"></i>';
                    case 'error':
                        return '<i class="fa fa-exclamation-triangle"></i>';
                    default:
                        if (strpos($this->getDisplay('icon'), '<i') === 0) {
                            return $this->getDisplay('icon');
                        }
                        return '<i class="fa fa-check"></i>';
                }
            } else {
                return '<i class="fa fa-times"></i>';
            }
        }
    }

    public function getLinkToConfiguration() {
        return 'index.php?v=d&p=scenario&id=' . $this->getId();
    }

    public function preSave() {
        if ($this->getTimeout() == '' || !is_numeric($this->getTimeout())) {
            $this->setTimeout(0);
        }
        if ($this->getName() == '') {
            throw new Exception('Le nom du scénario ne peut être vide');
        }
        if (($this->getMode() == 'schedule' || $this->getMode() == 'all') && $this->getSchedule() == '') {
            throw new Exception(__('Le scénario est de type programmé mais la programmation est vide', __FILE__));
        }
    }

    public function save() {
        if ($this->getLastLaunch() == '' && ($this->getMode() == 'schedule' || $this->getMode() == 'all')) {
            $calculateScheduleDate = $this->calculateScheduleDate();
            $this->setLastLaunch($calculateScheduleDate['prevDate']);
        }
        if ($this->getInternalEvent() == 1) {
            $internalEvent = new internalEvent();
            if ($this->getId() == '') {
                $internalEvent->setEvent('create::scenario');
            } else {
                $internalEvent->setEvent('update::scenario');
            }
        }
        DB::save($this);
        if (isset($internalEvent)) {
            $internalEvent->setOptions('id', $this->getId());
            $internalEvent->save();
        }
        if ($this->_changeState) {
            @nodejs::pushUpdate('eventScenario', $this->getId());
        }
    }

    public function refresh() {
        DB::refresh($this);
    }

    public function remove() {
        viewData::removeByTypeLinkId('scenario', $this->getId());
        dataStore::removeByTypeLinkId('scenario', $this->getId());
        $internalEvent = new internalEvent();
        $internalEvent->setEvent('remove::scenario');
        $internalEvent->setOptions('id', $this->getId());
        foreach ($this->getElement() as $element) {
            $element->remove();
        }
        DB::remove($this);
        $internalEvent->save();
    }

    public function removeData($_key, $_private = false) {
        if ($_private) {
            $dataStore = dataStore::byTypeLinkIdKey('scenario', $this->getId(), $_key);
        } else {
            $dataStore = dataStore::byTypeLinkIdKey('scenario', -1, $_key);
        }
        if (is_object($dataStore)) {
            return $dataStore->remove();
        }
        return true;
    }

    public function setData($_key, $_value) {
        $dataStore = new dataStore();
        $dataStore->setType('scenario');
        $dataStore->setKey($_key);
        $dataStore->setValue($_value);
        $dataStore->setLink_id(-1);
        $dataStore->save();
        return true;
    }

    public function getData($_key, $_private = false) {
        if ($_private) {
            $dataStore = dataStore::byTypeLinkIdKey('scenario', $this->getId(), $_key);
        } else {
            $dataStore = dataStore::byTypeLinkIdKey('scenario', -1, $_key);
        }
        if (is_object($dataStore)) {
            return $dataStore->getValue();
        }
        return '';
    }

    public function calculateScheduleDate() {
        $calculatedDate = array('prevDate' => '', 'nextDate' => '');
        if (is_array($this->getSchedule())) {
            $calculatedDate_tmp = array('prevDate' => '', 'nextDate' => '');
            foreach ($this->getSchedule() as $schedule) {
                try {
                    $c = new Cron\CronExpression($schedule, new Cron\FieldFactory);
                    $calculatedDate_tmp['prevDate'] = $c->getPreviousRunDate();
                    $calculatedDate_tmp['nextDate'] = $c->getNextRunDate();
                } catch (Exception $exc) {
                    //echo $exc->getTraceAsString();
                }
                if ($calculatedDate['prevDate'] == '' || $calculatedDate['prevDate'] < $calculatedDate_tmp['prevDate']) {
                    $calculatedDate['prevDate'] = $calculatedDate_tmp['prevDate'];
                }
                if ($calculatedDate['nextDate'] == '' || $calculatedDate['nextDate'] > $calculatedDate_tmp['nextDate']) {
                    $calculatedDate['nextDate'] = $calculatedDate_tmp['nextDate'];
                }
            }
        } else {
            try {
                $c = new Cron\CronExpression($this->getSchedule(), new Cron\FieldFactory);
                $calculatedDate['prevDate'] = $c->getPreviousRunDate();
                $calculatedDate['nextDate'] = $c->getNextRunDate();
            } catch (Exception $exc) {
                //echo $exc->getTraceAsString();
            }
        }
        return $calculatedDate;
    }

    public function isDue() {
        $last = strtotime($this->getLastLaunch());
        $now = time();
        $now = ($now - $now % 60);
        $last = ($last - $last % 60);
        if ($now == $last) {
            return false;
        }

        if (is_array($this->getSchedule())) {
            foreach ($this->getSchedule() as $schedule) {
                try {
                    $c = new Cron\CronExpression($schedule, new Cron\FieldFactory);
                    if ($c->isDue()) {
                        return true;
                    }
                    $lastCheck = new DateTime($this->getLastLaunch());
                    $prev = $c->getPreviousRunDate();
                    $diff = round(abs((strtotime('now') - strtotime($prev)) / 60));
                    if ($lastCheck <= $prev && $diff <= config::byKey('maxCatchAllow') || config::byKey('maxCatchAllow') == -1) {
                        if ($diff > 3) {
                            log::add('scenario', 'error', __('Retard lancement prévu à ', __FILE__) . $prev->format('Y-m-d H:i:s') . __(' dernier lancement à ', __FILE__) . $lastCheck->format('Y-m-d H:i:s') . __('. Retard de : ', __FILE__) . $diff . ' min : ' . $this->getName() . __('. Rattrapage en cours...', __FILE__));
                        }
                        return true;
                    }
                } catch (Exception $exc) {
                    log::add('scenario', 'error', __('Expression cron non valide : ', __FILE__) . $schedule);
                    return false;
                }
            }
        } else {
            try {
                $c = new Cron\CronExpression($this->getSchedule(), new Cron\FieldFactory);
                if ($c->isDue()) {
                    return true;
                }
                $lastCheck = new DateTime($this->getLastLaunch());
                $prev = $c->getPreviousRunDate();
                $diff = round(abs((strtotime('now') - $prev->getTimestamp()) / 60));
                if ($lastCheck < $prev && $diff <= config::byKey('maxCatchAllow') || config::byKey('maxCatchAllow') == -1) {
                    if ($diff > 3) {
                        log::add('scenario', 'error', __('Retard lancement prévu à ', __FILE__) . $prev->format('Y-m-d H:i:s') . __(' dernier lancement à ', __FILE__) . $lastCheck->format('Y-m-d H:i:s') . __('. Retard de : ', __FILE__) . $diff . ' min: ' . $this->getName() . __('. Rattrapage en cours...', __FILE__));
                    }
                    return true;
                }
            } catch (Exception $exc) {
                log::add('scenario', 'error', __('Expression cron non valide : ', __FILE__) . $this->getSchedule());
                return false;
            }
        }
        return false;
    }

    public function running() {
        if ($this->getPID() > 0) {
            return posix_getsid($this->getPID());
        }
        return false;
    }

    public function stop() {
        if ($this->running()) {
            $kill = posix_kill($this->getPID(), 15);
            $retry = 0;
            while (!$kill && $retry < 5) {
                sleep(1);
                $kill = posix_kill($this->getPID(), 9);
                $retry++;
            }
            $retry = 0;
            while ($this->running() && $retry < 5) {
                sleep(1);
                exec('kill -9 ' . $this->getPID());
                $retry++;
            }
            if ($this->running()) {
                throw new Exception(__('Impossible d\'arreter le scénario : ', __FILE__) . $this->getHumanName() . __('. PID : ', __FILE__) . $this->getPID());
            }
        }
        $this->setState('stop');
        $this->save();
        return true;
    }

    public function getElement() {
        if (count($this->_elements) > 0) {
            return $this->_elements;
        }
        $return = array();
        $elements = $this->getScenarioElement();
        if (is_array($elements)) {
            foreach ($this->getScenarioElement() as $element_id) {
                $element = scenarioElement::byId($element_id);
                if (is_object($element)) {
                    $return[] = $element;
                }
            }
            $this->_elements = $return;
            return $return;
        }
        if ($elements != '') {
            $element = scenarioElement::byId($element_id);
            if (is_object($element)) {
                $return[] = $element;
                $this->_elements = $return;
                return $return;
            }
        }
        return array();
    }

    public function export() {
        $return = '';
        $return .= '- Nom du scénario : ' . $this->getName() . "\n";
        if (is_numeric($this->getObject_id())) {
            $return .= '- Objet parent : ' . $this->getObject()->getName() . "\n";
        }
        $return .= '- Mode du scénario : ' . $this->getMode() . "\n";
        $schedules = $this->getSchedule();
        if ($this->getMode() == 'schedule' || $this->getMode() == 'all') {
            if (is_array($schedules)) {
                foreach ($schedules as $schedule) {
                    $return .= '    - Programmation : ' . $schedule . "\n";
                }
            } else {
                if ($schedules != '') {
                    $return .= '    - Programmation : ' . $schedules . "\n";
                }
            }
        }
        if ($this->getMode() == 'provoke' || $this->getMode() == 'all') {
            $triggers = $this->getTrigger();
            if (is_array($triggers)) {
                foreach ($triggers as $trigger) {
                    $return .= '    - Evènement : ' . jeedom::toHumanReadable($trigger) . "\n";
                }
            } else {
                if ($triggers != '') {
                    $return .= '    - Evènement : ' . jeedom::toHumanReadable($triggers) . "\n";
                }
            }
        }
        $return .= "\n";
        $return .= $this->getDescription();
        $return .= "\n\n";
        foreach ($this->getElement() as $element) {
            $exports = explode("\n", $element->export());
            foreach ($exports as $export) {
                $return .= "    " . $export . "\n";
            }
        }
        return $return;
    }

    public function getObject() {
        return object::byId($this->object_id);
    }

    public function getHumanName($_complete = false, $_noGroup = false) {
        $return = '';
        if (is_numeric($this->getObject_id())) {
            $return .= '[' . $this->getObject()->getName() . ']';
        } else {
            if ($_complete) {
                $return .= '[' . __('Aucun', __FILE__) . ']';
            }
        }
        if (!$_noGroup) {
            if ($this->getGroup() != '') {
                $return .= '[' . $this->getGroup() . ']';
            } else {
                if ($_complete) {
                    $return .= '[' . __('Aucun', __FILE__) . ']';
                }
            }
        }
        $return .= '[' . $this->getName() . ']';
        return $return;
    }

    public function hasRight($_right, $_needAdmin = false, $_user = null) {
        if (!is_object($_user)) {
            $_user = $_SESSION['user'];
        }
        if (!is_object($_user)) {
            return false;
        }
        if (!isConnect()) {
            return false;
        }
        if (isConnect('admin')) {
            return true;
        }
        if (config::byKey('jeedom::licence') < 9) {
            return ($_needAdmin) ? false : true;
        }
        if ($_right = 'x') {
            $rights = rights::byuserIdAndEntity($_user->getId(), 'scenario' . $this->getId() . 'action');
        } elseif ($_right = 'w') {
            $rights = rights::byuserIdAndEntity($_user->getId(), 'scenario' . $this->getId() . 'edit');
        } elseif ($_right = 'r') {
            $rights = rights::byuserIdAndEntity($_user->getId(), 'scenario' . $this->getId() . 'view');
        }
        if (!is_object($rights)) {
            return ($_needAdmin) ? false : true;
        }
        return $rights->getRight();
    }

    /*     * **********************Getteur Setteur*************************** */

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getState() {
        return $this->state;
    }

    public function getIsActive() {
        return $this->isActive;
    }

    public function getGroup() {
        return $this->group;
    }

    public function getLastLaunch() {
        return $this->lastLaunch;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setName($name) {
        if ($name != $this->getName()) {
            $this->setInternalEvent(1);
        }
        $this->name = $name;
    }

    public function setIsActive($isActive) {
        if ($isActive != $this->getIsActive()) {
            $this->setInternalEvent(1);
            $this->_changeState = true;
        }
        $this->isActive = $isActive;
    }

    public function setGroup($group) {
        $this->group = $group;
    }

    public function setState($state) {
        $this->_changeState = true;
        $this->state = $state;
    }

    public function setLastLaunch($lastLaunch) {
        $this->lastLaunch = $lastLaunch;
    }

    public function getType() {
        return $this->type;
    }

    public function getMode() {
        return $this->mode;
    }

    public function setMode($mode) {
        $this->mode = $mode;
    }

    public function getSchedule() {
        if (is_json($this->schedule)) {
            return json_decode($this->schedule, true);
        }
        return $this->schedule;
    }

    public function setSchedule($schedule) {
        if (is_array($schedule)) {
            $schedule = json_encode($schedule, JSON_UNESCAPED_UNICODE);
        }
        $this->schedule = $schedule;
    }

    public function getPID() {
        return $this->pid;
    }

    public function setPID($pid) {
        $this->pid = $pid;
    }

    public function getScenarioElement() {
        if (is_json($this->scenarioElement)) {
            return json_decode($this->scenarioElement, true);
        }
        return $this->scenarioElement;
    }

    public function setScenarioElement($scenarioElement) {
        if (is_array($scenarioElement)) {
            $scenarioElement = json_encode($scenarioElement, JSON_UNESCAPED_UNICODE);
        }
        $this->scenarioElement = $scenarioElement;
    }

    public function getTrigger() {
        if (is_json($this->trigger)) {
            return json_decode($this->trigger, true);
        }
        return $this->trigger;
    }

    public function setTrigger($trigger) {
        if (is_array($trigger)) {
            $trigger = json_encode($trigger, JSON_UNESCAPED_UNICODE);
        }
        $this->trigger = cmd::humanReadableToCmd($trigger);
    }

    public function getLog() {
        return $this->log;
    }

    public function setLog($log) {
        if ($log == '') {
            $this->log = '';
        } else {
            $this->log .= '[' . date('Y-m-d H:i:s') . '][SCENARIO] ' . $log . "\n";
        }
    }

    public function getTimeout($_default = '') {
        if ($this->timeout == '' || !is_numeric($this->timeout)) {
            return $_default;
        }
        return $this->timeout;
    }

    public function setTimeout($timeout) {
        $this->timeout = $timeout;
    }

    public function getObject_id() {
        return $this->object_id;
    }

    public function getIsVisible() {
        return $this->isVisible;
    }

    public function setObject_id($object_id = null) {
        $this->object_id = (!is_numeric($object_id)) ? null : $object_id;
    }

    public function setIsVisible($isVisible) {
        $this->isVisible = $isVisible;
    }

    public function getHlogs() {
        if (is_json($this->hlogs)) {
            return json_decode($this->hlogs, true);
        }
        return $this->hlogs;
    }

    public function setHlogs($hlogs) {
        if (is_array($hlogs)) {
            $this->hlogs = json_encode($hlogs);
        } else {
            $this->hlogs = $hlogs;
        }
    }

    public function getInternalEvent() {
        return $this->_internalEvent;
    }

    public function setInternalEvent($_internalEvent) {
        $this->_internalEvent = $_internalEvent;
    }

    public function getDisplay($_key = '', $_default = '') {
        return utils::getJsonAttr($this->display, $_key, $_default);
    }

    public function setDisplay($_key, $_value) {
        $this->display = utils::setJsonAttr($this->display, $_key, $_value);
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getConfiguration($_key = '', $_default = '') {
        return utils::getJsonAttr($this->configuration, $_key, $_default);
    }

    public function setConfiguration($_key, $_value) {
        $this->configuration = utils::setJsonAttr($this->configuration, $_key, $_value);
    }

    function getRealTrigger() {
        return $this->_realTrigger;
    }

    function setRealTrigger($_realTrigger) {
        $this->_realTrigger = $_realTrigger;
    }

}

?>
