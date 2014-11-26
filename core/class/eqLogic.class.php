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

class eqLogic {
    /*     * *************************Attributs****************************** */

    protected $id;
    protected $name;
    protected $logicalId = '';
    protected $object_id = null;
    protected $eqType_name;
    protected $eqReal_id = null;
    protected $isVisible = 0;
    protected $isEnable = 0;
    protected $configuration;
    protected $specificCapatibilities;
    protected $timeout;
    protected $category;
    protected $display;
    protected $_internalEvent = 0;
    protected $_debug = false;
    protected $_object = null;
    private static $_templateArray = array();

    /*     * ***********************Methode static*************************** */

    public static function byId($_id) {
        $values = array(
            'id' => $_id
        );
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
                FROM eqLogic
                WHERE id=:id';
        return self::cast(DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__));
    }

    private static function cast($_inputs) {
        if (is_object($_inputs)) {
            if (class_exists($_inputs->getEqType_name())) {
                return cast($_inputs, $_inputs->getEqType_name());
            }
        }
        if (is_array($_inputs)) {
            $return = array();
            foreach ($_inputs as $input) {
                $return[] = self::cast($input);
            }
            return $return;
        }
        return $_inputs;
    }

    public static function all() {
        $sql = 'SELECT ' . DB::buildField(__CLASS__, 'el') . '
                FROM eqLogic el
                 LEFT JOIN object ob ON el.object_id=ob.id
                 ORDER BY ob.name,el.name';
        return self::cast(DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__));
    }

    public static function byTimeout($_timeout = 0) {
        $values = array(
            'timeout' => $_timeout
        );
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
                FROM eqLogic
                WHERE timeout>:timeout';
        return self::cast(DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__));
    }

    public static function byEqRealId($_eqReal_id) {
        $values = array(
            'eqReal_id' => $_eqReal_id
        );
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
                FROM eqLogic
                WHERE eqReal_id=:eqReal_id';
        return self::cast(DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__));
    }

    public static function byObjectId($_object_id, $_onlyEnable = true, $_onlyVisible = false, $_eqType_name = null, $_logicalId = null) {
        $values = array();
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
                FROM eqLogic';
        if ($_object_id == null) {
            $sql .= ' WHERE object_id IS NULL';
        } else {
            $values['object_id'] = $_object_id;
            $sql .= ' WHERE object_id=:object_id';
        }
        if ($_onlyEnable) {
            $sql .= ' AND isEnable = 1';
        }
        if ($_onlyVisible) {
            $sql .= ' AND isVisible = 1';
        }
        if ($_eqType_name != null) {
            $values['eqType_name'] = $_eqType_name;
            $sql .= ' AND eqType_name=:eqType_name';
        }
        if ($_logicalId != null) {
            $values['logicalId'] = $_logicalId;
            $sql .= ' AND logicalId=:logicalId';
        }
        $sql .= ' ORDER BY category DESC';
        return self::cast(DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__));
    }

    public static function byLogicalId($_logicalId, $_eqType_name) {
        $values = array(
            'logicalId' => $_logicalId,
            'eqType_name' => $_eqType_name
        );
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
                FROM eqLogic
                WHERE logicalId=:logicalId
                    AND eqType_name=:eqType_name';
        return self::cast(DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__));
    }

    public static function byType($_eqType_name) {
        $values = array(
            'eqType_name' => $_eqType_name
        );
        $sql = 'SELECT ' . DB::buildField(__CLASS__, 'el') . '
                FROM eqLogic el
                LEFT JOIN object ob ON el.object_id=ob.id
                WHERE eqType_name=:eqType_name
                ORDER BY ob.name,el.name';
        return self::cast(DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__));
    }

    public static function byCategorie($_category) {
        $values = array(
            'category' => '%"' . $_category . '":1%',
            'category2' => '%"' . $_category . '":"1"%'
        );

        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
                FROM eqLogic
                WHERE category LIKE :category
                    OR category LIKE :category2
                ORDER BY name';
        return self::cast(DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__));
    }

    public static function byTypeAndSearhConfiguration($_eqType_name, $_configuration) {
        $values = array(
            'eqType_name' => $_eqType_name,
            'configuration' => '%' . $_configuration . '%'
        );
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
                FROM eqLogic
                WHERE eqType_name=:eqType_name
                    AND configuration LIKE :configuration
                ORDER BY name';
        return self::cast(DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__));
    }

    public static function searchConfiguration($_configuration, $_type = null) {
        $values = array(
            'configuration' => '%' . $_configuration . '%'
        );
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
                FROM eqLogic
                WHERE configuration LIKE :configuration';
        if ($_type != null) {
            $values['eqType_name'] = $_type;
            $sql .= ' AND eqType_name=:eqType_name ';
        }
        $sql .= ' ORDER BY name';
        return self::cast(DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__));
    }

    public static function listByTypeAndCmdType($_eqType_name, $_typeCmd, $subTypeCmd = '') {
        if ($subTypeCmd == '') {
            $values = array(
                'eqType_name' => $_eqType_name,
                'typeCmd' => $_typeCmd
            );
            $sql = 'SELECT DISTINCT(el.id),el.name
                    FROM eqLogic el
                        INNER JOIN cmd c ON c.eqLogic_id=el.id
                    WHERE eqType_name=:eqType_name
                        AND c.type=:typeCmd
                    ORDER BY name';
            return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL);
        } else {
            $values = array(
                'eqType_name' => $_eqType_name,
                'typeCmd' => $_typeCmd,
                'subTypeCmd' => $subTypeCmd
            );
            $sql = 'SELECT DISTINCT(el.id),el.name
                    FROM eqLogic el
                        INNER JOIN cmd c ON c.eqLogic_id=el.id
                    WHERE eqType_name=:eqType_name
                        AND c.type=:typeCmd
                        AND c.subType=:subTypeCmd
                    ORDER BY name';
            return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL);
        }
    }

    public static function listByObjectAndCmdType($_object_id, $_typeCmd, $subTypeCmd = '') {
        $values = array();
        $sql = 'SELECT DISTINCT(el.id),el.name
                FROM eqLogic el
                    INNER JOIN cmd c ON c.eqLogic_id=el.id
                WHERE ';
        if ($_object_id == null) {
            $sql .= ' object_id IS NULL ';
        } elseif ($_object_id != '') {
            $values['object_id'] = $_object_id;
            $sql .= ' object_id=:object_id ';
        }
        if ($subTypeCmd != '') {
            $values['subTypeCmd'] = $subTypeCmd;
            $sql .= ' AND c.subType=:subTypeCmd ';
        }
        if ($_typeCmd != '' && $_typeCmd != 'all') {
            $values['type'] = $_typeCmd;
            $sql .= ' AND c.type=:type ';
        }
        $sql .= ' ORDER BY name ';
        return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL);
    }

    public static function allType() {
        $sql = 'SELECT distinct(eqType_name) as type
                FROM eqLogic';
        return DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL);
    }

    public static function checkAlive() {
        foreach (eqLogic::byTimeout() as $eqLogic) {
            if ($eqLogic->getIsEnable() == 1) {
                $sendReport = false;
                $cmds = $eqLogic->getCmd();
                foreach ($cmds as $cmd) {
                    if ($cmd->getEventOnly() == 1) {
                        $sendReport = true;
                    }
                }
                $logicalId = 'noMessage' . $eqLogic->getId();
                if ($sendReport) {
                    $noReponseTimeLimit = $eqLogic->getTimeout();
                    if (count(message::byPluginLogicalId('core', $logicalId)) == 0) {
                        if ($eqLogic->getStatus('lastCommunication', date('Y-m-d H:i:s')) < date('Y-m-d H:i:s', strtotime('-' . $noReponseTimeLimit . ' minutes' . date('Y-m-d H:i:s')))) {
                            $message = __('Attention', __FILE__) . ' <a href="' . $eqLogic->getLinkToConfiguration() . '">' . $eqLogic->getHumanName();
                            $message .= '</a>' . __(' n\'a pas envoyé de message depuis plus de ', __FILE__) . $noReponseTimeLimit . __(' min (vérifier les piles)', __FILE__);
                            message::add('core', $message, '', $logicalId);
                            foreach ($cmds as $cmd) {
                                if ($cmd->getEventOnly() == 1) {
                                    $cmd->event('error::timeout');
                                }
                            }
                        }
                    } else {
                        if ($eqLogic->getStatus('lastCommunication', date('Y-m-d H:i:s')) > date('Y-m-d H:i:s', strtotime('-' . $noReponseTimeLimit . ' minutes' . date('Y-m-d H:i:s')))) {
                            foreach (message::byPluginLogicalId('core', $logicalId) as $message) {
                                $message->remove();
                            }
                        }
                    }
                }
            }
        }
    }

    public static function byObjectNameEqLogicName($_object_name, $_eqLogic_name) {
        if ($_object_name == __('Aucun', __FILE__)) {
            $values = array(
                'eqLogic_name' => $_eqLogic_name,
            );
            $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
                    FROM eqLogic
                    WHERE name=:eqLogic_name
                        AND object_id IS NULL';
        } else {
            $values = array(
                'eqLogic_name' => $_eqLogic_name,
                'object_name' => $_object_name,
            );
            $sql = 'SELECT ' . DB::buildField(__CLASS__, 'el') . '
                    FROM eqLogic el
                        INNER JOIN object ob ON el.object_id=ob.id
                    WHERE el.name=:eqLogic_name
                        AND ob.name=:object_name';
        }
        return self::cast(DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__));
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
        preg_match_all("/#eqLogic([0-9]*)#/", $text, $matches);
        foreach ($matches[1] as $eqLogic_id) {
            if (is_numeric($eqLogic_id)) {
                $eqLogic = self::byId($eqLogic_id);
                if (is_object($eqLogic)) {
                    $text = str_replace('#eqLogic' . $eqLogic_id . '#', '#' . $eqLogic->getHumanName() . '#', $text);
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

        preg_match_all("/#\[(.*?)\]\[(.*?)\]#/", $text, $matches);
        if (count($matches) == 3) {
            for ($i = 0; $i < count($matches[0]); $i++) {
                if (isset($matches[1][$i]) && isset($matches[2][$i])) {
                    $eqLogic = self::byObjectNameEqLogicName($matches[1][$i], $matches[2][$i]);
                    if (is_object($eqLogic)) {
                        $text = str_replace($matches[0][$i], '#eqLogic' . $eqLogic->getId() . '#', $text);
                    }
                }
            }
        }

        return $text;
    }

    /*     * *********************Methode d'instance************************* */

    public function copy($_name) {
        $eqLogicCopy = clone $this;
        $eqLogicCopy->setName($_name);
        $eqLogicCopy->setId('');
        $eqLogicCopy->save();
        foreach ($this->getCmd() as $cmd) {
            $cmdCopy = clone $cmd;
            $cmdCopy->setId('');
            $cmdCopy->setEqLogic_id($eqLogicCopy->getId());
            $cmdCopy->save();
        }
        return $eqLogicCopy;
    }

    public function getTableName() {
        return 'eqLogic';
    }

    public function hasOnlyEventOnlyCmd() {
        $values = array(
            'eqLogic_id' => $this->getId(),
        );
        $sql = 'SELECT count(*)
                FROM cmd
                WHERE eqLogic_id=:eqLogic_id
                    AND eventOnly!=1
                    AND type="info"
                    AND isVisible=1
                    AND isHistorized=0
                    AND (`value` = 0 OR `value` IS NULL OR `value`="")';
        $result = DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW);
        if ($result['count(*)'] > 0) {
            return false;
        }
        return true;
    }

    public function toHtml($_version = 'dashboard') {
        if ($_version == '') {
            throw new Exception(__('La version demandé ne peut être vide (mobile, dashboard ou scenario)', __FILE__));
        }
        $info = '';
        $version = jeedom::versionAlias($_version);
        $vcolor = 'cmdColor';
        if ($version == 'mobile') {
            $vcolor = 'mcmdColor';
        }
        if ($this->getPrimaryCategory() == '') {
            $cmdColor = '';
        } else {
            $cmdColor = jeedom::getConfiguration('eqLogic:category:' . $this->getPrimaryCategory() . ':' . $vcolor);
        }
        if ($this->getIsEnable()) {
            foreach ($this->getCmd(null, null, true) as $cmd) {
                $info.=$cmd->toHtml($_version, '', $cmdColor);
            }
        }
        $replace = array(
            '#id#' => $this->getId(),
            '#name#' => $this->getName(),
            '#eqLink#' => $this->getLinkToConfiguration(),
            '#category#' => $this->getPrimaryCategory(),
            '#background_color#' => $this->getBackgroundColor($version),
            '#info#' => $info,
            '#style#' => '',
        );
        if ($_version == 'dview' || $_version == 'mview') {
            $object = $this->getObject();
            $replace['#object_name#'] = (is_object($object)) ? '(' . $object->getName() . ')' : '';
        } else {
            $replace['#object_name#'] = '';
        }
        if (($_version == 'dview' || $_version == 'mview') && $this->getDisplay('doNotShowNameOnView') == 1) {
            $replace['#name#'] = '';
            $replace['#object_name#'] = (is_object($object)) ? $object->getName() : '';
        }
        if (($_version == 'mobile' || $_version == 'dashboard') && $this->getDisplay('doNotShowNameOnDashboard') == 1) {
            $replace['#name#'] = '<br/>';
            $replace['#object_name#'] = (is_object($object)) ? $object->getName() : '';
        }
        $parameters = $this->getDisplay('parameters');
        if (is_array($parameters)) {
            foreach ($parameters as $key => $value) {
                $replace['#' . $key . '#'] = $value;
            }
        }

        if (!isset(self::$_templateArray[$version])) {
            self::$_templateArray[$version] = getTemplate('core', $version, 'eqLogic');
        }
        return template_replace($replace, self::$_templateArray[$version]);
    }

    public function getShowOnChild() {
        return false;
    }

    public function remove() {
        foreach ($this->getCmd() as $cmd) {
            $cmd->remove();
        }
        viewData::removeByTypeLinkId('eqLogic', $this->getId());
        dataStore::removeByTypeLinkId('eqLogic', $this->getId());
        $internalEvent = new internalEvent();
        $internalEvent->setEvent('remove::eqLogic');
        $internalEvent->setOptions('id', $this->getId());
        DB::remove($this);
        $internalEvent->save();
    }

    public function save() {
        if ($this->getName() == '') {
            throw new Exception(__('Le nom de l\'équipement ne peut être vide', __FILE__));
        }
        if ($this->getInternalEvent() == 1) {
            $internalEvent = new internalEvent();
            if ($this->getId() == '') {
                $internalEvent->setEvent('create::eqLogic');
            } else {
                $internalEvent->setEvent('update::eqLogic');
            }
        }
        DB::save($this);
        if (isset($internalEvent)) {
            $internalEvent->setOptions('id', $this->getId());
            $internalEvent->save();
        }
        return true;
    }

    public function refresh() {
        DB::refresh($this);
    }

    public function getLinkToConfiguration() {
        return 'index.php?v=d&p=' . $this->getEqType_name() . '&m=' . $this->getEqType_name() . '&id=' . $this->getId();
    }

    public function collectInProgress() {
        $values = array(
            'eqLogic_id' => $this->getId()
        );
        $sql = 'SELECT count(*)
                FROM cmd
                WHERE eqLogic_id=:eqLogic_id
                    AND collect=1
                    AND eventOnly=0';
        $results = DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW);
        if ($results['count(*)'] > 0) {
            return true;
        }
        return false;
    }

    public function getHumanName($_tag = false) {
        $name = '';
        $objet = $this->getObject();
        if (is_object($objet)) {
            if ($_tag) {
                $name .= '<span class="label label-primary" style="text-shadow : none;">' . $objet->getName() . '</span>';
            } else {
                $name .= '[' . $objet->getName() . ']';
            }
        } else {
            if ($_tag) {
                $name .= '<span class="label labe-default">' . __('Aucun', __FILE__) . '</span>';
            } else {
                $name .= '[' . __('Aucun', __FILE__) . ']';
            }
        }
        if ($_tag) {
            $name .= ' ' . $this->getName();
        } else {
            $name .= '[' . $this->getName() . ']';
        }
        return $name;
    }

    public function getBackgroundColor($_version = 'dashboard') {
        $vcolor = 'color';
        $default = '#19bc9c';
        if ($_version == 'mobile') {
            $vcolor = 'mcolor';
            $default = '#19bc9c';
        }
        $category = $this->getPrimaryCategory();
        if ($category != '') {
            return jeedom::getConfiguration('eqLogic:category:' . $category . ':' . $vcolor);
        }
        return $default;
    }

    public function getPrimaryCategory() {
        if ($this->getCategory('security', 0) == 1) {
            return 'security';
        }
        if ($this->getCategory('heating', 0) == 1) {
            return 'heating';
        }
        if ($this->getCategory('light', 0) == 1) {
            return 'light';
        }
        if ($this->getCategory('automatism', 0) == 1) {
            return 'automatism';
        }
        if ($this->getCategory('energy', 0) == 1) {
            return 'energy';
        }
        return '';
    }

    public function displayDebug($_message) {
        if ($this->getDebug()) {
            echo $_message . "\n";
        }
    }

    public function batteryStatus($_pourcent) {
        foreach (message::byPluginLogicalId($this->getEqType_name(), 'lowBattery' . $this->getId()) as $message) {
            $message->remove();
        }
        foreach (message::byPluginLogicalId($this->getEqType_name(), 'noBattery' . $this->getId()) as $message) {
            $message->remove();
        }
        if ($_pourcent > 0 && $_pourcent <= 20) {
            $logicalId = 'lowBattery' . $this->getId();
            if (count(message::byPluginLogicalId($this->getEqType_name(), $logicalId)) == 0) {
                $message = 'Le module ' . $this->getEqType_name() . ' ';
                $message .= $this->getHumanName() . ' à moins de ' . $_pourcent . '% de batterie';
                message::add($this->getEqType_name(), $message, '', $logicalId);
            }
        }
        if ($_pourcent <= 0) {
            $logicalId = 'noBattery' . $this->getId();
            $message = __('Le module ', __FILE__) . $this->getEqType_name() . ' ';
            $message .= $this->getHumanName() . __(' a été désactivé car il n\'a plus de batterie (', __FILE__) . $_pourcent . ' %)';
            message::add($this->getEqType_name(), $message, '', $logicalId);
        }
    }

    public function refreshWidget() {
        nodejs::pushUpdate('eventEqLogic', $this->getId());
    }

    public function hasRight($_right, $_needAdmin = false, $_user = null) {
        if (!isConnect()) {
            return false;
        }
        if (isConnect('admin')) {
            return true;
        }
        if (config::byKey('jeedom::licence') < 9) {
            return ($_needAdmin) ? false : true;
        }
        if (!is_object($_user)) {
            $_user = $_SESSION['user'];
        }
        if (!is_object($_user)) {
            return false;
        }
        if ($_right = 'x') {
            $rights = rights::byuserIdAndEntity($_user->getId(), 'eqLogic' . $this->getId() . 'action');
        } elseif ($_right = 'w') {
            $rights = rights::byuserIdAndEntity($_user->getId(), 'eqLogic' . $this->getId() . 'edit');
        } elseif ($_right = 'r') {
            $rights = rights::byuserIdAndEntity($_user->getId(), 'eqLogic' . $this->getId() . 'view');
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

    public function getLogicalId() {
        return $this->logicalId;
    }

    public function getObject_id() {
        return $this->object_id;
    }

    public function getObject() {
        if ($this->_object == null) {
            $this->_object = object::byId($this->object_id);
        }
        return $this->_object;
    }

    public function getEqType_name() {
        return $this->eqType_name;
    }

    public function getIsVisible() {
        return $this->isVisible;
    }

    public function getIsEnable() {
        return $this->isEnable;
    }

    public function getCmd($_type = null, $_logicalId = null, $_visible = null) {
        if ($_logicalId != null) {
            return cmd::byEqLogicIdAndLogicalId($this->id, $_logicalId);
        }
        return cmd::byEqLogicId($this->id, $_type, $_visible);
    }

    public function getEqReal_id() {
        return $this->eqReal_id;
    }

    public function getEqReal() {
        return eqReal::byId($this->eqReal_id);
    }

    public function setId($id) {
        if ($id != $this->getId()) {
            $this->setInternalEvent(1);
        }
        $this->id = $id;
    }

    public function setName($name) {
        $name = str_replace(array('&', '#', ']', '[', '%', "'"), '', $name);
        if ($name != $this->getName()) {
            $this->setInternalEvent(1);
        }
        $this->name = $name;
    }

    public function setLogicalId($logicalId) {
        if ($logicalId != $this->getLogicalId()) {
            $this->setInternalEvent(1);
        }
        $this->logicalId = $logicalId;
    }

    public function setObject_id($object_id = null) {
        if ($object_id != $this->getObject_id()) {
            $this->setInternalEvent(1);
        }
        $this->object_id = (!is_numeric($object_id)) ? null : $object_id;
    }

    public function setEqType_name($eqType_name) {
        if ($eqType_name != $this->getEqType_name()) {
            $this->setInternalEvent(1);
        }
        $this->eqType_name = $eqType_name;
    }

    public function setEqReal_id($eqReal_id) {
        if ($eqReal_id != $this->getEqReal_id()) {
            $this->setInternalEvent(1);
        }
        $this->eqReal_id = $eqReal_id;
    }

    public function setIsVisible($isVisible) {
        if ($isVisible != $this->getIsVisible()) {
            $this->setInternalEvent(1);
        }
        $this->isVisible = $isVisible;
    }

    public function setIsEnable($_isEnable) {
        if ($_isEnable != $this->getIsEnable()) {
            $this->setInternalEvent(1);
        }
        $this->isEnable = $_isEnable;
    }

    public function getConfiguration($_key = '', $_default = '') {
        return utils::getJsonAttr($this->configuration, $_key, $_default);
    }

    public function setConfiguration($_key, $_value) {
        $this->configuration = utils::setJsonAttr($this->configuration, $_key, $_value);
    }

    public function getStatus($_key = '', $_default = '') {
        $status = cache::byKey('core::eqLogic' . $this->getId() . '::' . $_key);
        return $status->getValue($_default);
    }

    public function setStatus($_key, $_value) {
        return cache::set('core::eqLogic' . $this->getId() . '::' . $_key, $_value, 0);
    }

    public function getSpecificCapatibilities($_key = '', $_default = '') {
        return utils::getJsonAttr($this->specificCapatibilities, $_key, $_default);
    }

    public function setSpecificCapatibilities($_key, $_value) {
        $this->specificCapatibilities = utils::setJsonAttr($this->specificCapatibilities, $_key, $_value);
    }

    public function getDisplay($_key = '', $_default = '') {
        return utils::getJsonAttr($this->display, $_key, $_default);
    }

    public function setDisplay($_key, $_value) {
        $this->display = utils::setJsonAttr($this->display, $_key, $_value);
    }

    public function getInternalEvent() {
        return $this->_internalEvent;
    }

    public function setInternalEvent($_internalEvent) {
        $this->_internalEvent = $_internalEvent;
    }

    public function getTimeout() {
        return $this->timeout;
    }

    public function setTimeout($timeout) {
        $this->timeout = $timeout;
    }

    public function getCategory($_key = '', $_default = '') {
        if ($_key == 'other' && strpos($this->category, "1") === false) {
            return 1;
        }
        return utils::getJsonAttr($this->category, $_key, $_default);
    }

    public function setCategory($_key, $_value) {
        $this->category = utils::setJsonAttr($this->category, $_key, $_value);
    }

    public function getDebug() {
        return $this->_debug;
    }

    public function setDebug($_debug) {
        if ($_debug) {
            echo "Mode debug activé\n";
        }
        $this->_debug = $_debug;
    }

}

?>
