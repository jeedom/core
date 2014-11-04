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

class internalEvent {
    /*     * *************************Attributs****************************** */

    private $datetime;
    private $event;
    private $options = '';

    /*     * ***********************Methode static*************************** */

    public static function all() {
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
                FROM internalEvent
                ORDER BY `datetime` DESC, `event` DESC';
        return DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
    }

    public static function cleanEvent($_maxAge = '02:00:00') {
        $values = array(
            'maxAge' => $_maxAge
        );
        $sql = 'DELETE FROM internalEvent
                WHERE TIMEDIFF(NOW(),`datetime`)>:maxAge';
        return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW);
    }

    public static function byDatetime($_startDate, $_endDate = null) {
        $values = array(
            'startDate' => $_startDate
        );
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
                FROM internalEvent
                WHERE `datetime` > :startDate ';
        if ($_endDate != null) {
            $values['endDate'] = $_endDate;
            $sql .= ' AND `datetime` < :endDate ';
        }
        return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
    }

    public static function byEventAndOptions($_event, $_options, $_last = false) {
        if (is_array($_options) || is_object($_options)) {
            $_options = json_encode($_options, JSON_UNESCAPED_UNICODE);
        }
        $values = array(
            'event' => $_event,
            'options' => '%' . $_options . '%'
        );
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
                FROM internalEvent
                WHERE event=:event
                    AND `options` LIKE :options';
        if ($_last) {
            $sql .= ' ORDER BY `datetime`
                      LIMIT 1';
            return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
        } else {
            return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
        }
    }

    public static function byEvent($_event, $_last = false) {
        $values = array(
            'event' => $_event
        );
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
                FROM internalEvent
                WHERE event=:event';
        if ($_last) {
            $sql .= ' ORDER BY `datetime`
                      LIMIT 1';
            return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
        } else {
            return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
        }
    }

    public static function getNewInternalEvent($_plugin) {
        if ($_plugin == '') {
            throw new Exception(__('Le nom du plugin ne peut être vide', __FILE__));
        }
        $now = strtotime(date('Y-m-d H:i:s', strtotime('-1 second', strtotime('now'))));
        self::cleanEvent();
        $key = $_plugin . '::lastRetrievalInternalEvent';
        $cache = cache::byKey($key);
        $lastDatetime = $cache->getValue(0);
        cache::set($key, $now, 0);
        return self::byDatetime(date('Y-m-d H:i:s', $lastDatetime));
    }

    public static function start() {
        $caches = cache::search('::lastRetrievalInternalEvent');
        if (count($caches) > 0) {
            foreach ($caches as $cache) {
                $cache->remove();
            }
        }
    }

    /*     * *********************Methode d'instance************************* */

    public function save() {
        $options = $this->getOptions();
        if (is_array($options) || is_object($options)) {
            $options = json_encode($options, JSON_UNESCAPED_UNICODE);
        }
        $values = array(
            'options' => $options,
            'event' => $this->getEvent(),
        );
        $sql = 'DELETE FROM internalEvent
                WHERE `options` = :options
                       AND `event` = :event';
        DB::Prepare($sql, $values);
        $values['datetime'] = $this->getDatetime();
        $sql = 'REPLACE INTO internalEvent
                SET `datetime` = :datetime,
                    `event` = :event,
                    `options` = :options';
        DB::Prepare($sql, $values);
        self::cleanEvent();
    }

    public function remove() {
        DB::remove($this);
    }

    /*     * **********************Getteur Setteur*************************** */

    public function getDatetime() {
        if ($this->datetime == '') {
            $this->datetime = date('Y-m-d H:i:s');
        }
        return $this->datetime;
    }

    public function setDatetime($datetime) {
        $this->datetime = $datetime;
    }

    public function getEvent() {
        return $this->event;
    }

    public function setEvent($event) {
        $this->event = $event;
    }

    public function getOptions($_key = '', $_default = '') {
        return utils::getJsonAttr($this->options, $_key, $_default);
    }

    public function setOptions($_key, $_value) {
        $this->options = utils::setJsonAttr($this->options, $_key, $_value);
    }

}

?>
