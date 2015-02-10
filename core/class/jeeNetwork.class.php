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

class jeeNetwork {
    /*     * *************************Attributs****************************** */

    private $id;
    private $ip;
    private $apikey;
    private $plugin;
    private $configuration;
    private $name;
    private $status;

    /*     * ***********************Méthodes statiques*************************** */

    public static function all() {
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
        FROM jeeNetwork';
        return DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
    }

    public static function byId($_id) {
        $values = array(
            'id' => $_id
            );
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
        FROM jeeNetwork
        WHERE id=:id';
        return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
    }

    public static function byPlugin($_plugin) {
        $values = array(
            'plugin' => '%' . $_plugin . '%'
            );
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
        FROM jeeNetwork
        WHERE plugin LIKE :plugin';
        return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
    }

    public static function changeMode($_mode) {
        if (config::byKey('jeedom::licence') < 5) {
            throw new Exception(__('Votre licence ne vous autorise pas à utiliser le mode esclave',__FILE__));
        }
        switch ($_mode) {
            case 'master':
            if (config::byKey('jeeNetwork::mode') != 'master') {
                $cron = new cron();
                $cron->setClass('history');
                $cron->setFunction('historize');
                $cron->setSchedule('*/5 * * * * *');
                $cron->setTimeout(5);
                $cron->save();
                $cron = new cron();
                $cron->setClass('scenario');
                $cron->setFunction('check');
                $cron->setSchedule('* * * * * *');
                $cron->setTimeout(5);
                $cron->save();
                $cron = new cron();
                $cron->setClass('cmd');
                $cron->setFunction('collect');
                $cron->setSchedule('*/5 * * * * *');
                $cron->setTimeout(5);
                $cron->save();
                $cron = new cron();
                $cron->setClass('history');
                $cron->setFunction('archive');
                $cron->setSchedule('00 * * * * *');
                $cron->setTimeout(20);
                $cron->save();
                config::save('jeeNetwork::mode', 'master');
            }
            break;
            case 'slave':
            if (config::byKey('jeeNetwork::mode') != 'slave') {
                foreach (eqLogic::all() as $eqLogic) {
                    $eqLogic->remove();
                }
                foreach (object::all() as $object) {
                    $object->remove();
                }
                foreach (update::all() as $update) {
                    switch ($update->getType()) {
                        case 'core':
                        break;
                        case 'plugin':
                        try {
                           $plugin = plugin::byId($update->getLogicalId());
                           if (is_object($plugin) && $plugin->getAllowRemote() != 1) {
                            $update->deleteObjet();
                        } 
                    } catch (Exception $e) {

                    }
                    break;
                    default :
                    $update->deleteObjet();
                    break;
                }
            }
            foreach (view::all() as $view) {
                $view->remove();
            }
            foreach (plan::all() as $plan) {
                $plan->remove();
            }
            foreach (scenario::all() as $scenario) {
                $scenario->remove();
            }
            foreach (listener::all() as $listener) {
                $listener->remove();
            }
            $cron = cron::byClassAndFunction('history', 'historize');
            if (is_object($cron)) {
                $cron->remove();
            }
            $cron = cron::byClassAndFunction('scenario', 'check');
            if (is_object($cron)) {
                $cron->remove();
            }
            $cron = cron::byClassAndFunction('cmd', 'collect');
            if (is_object($cron)) {
                $cron->remove();
            }
            $cron = cron::byClassAndFunction('history', 'archive');
            if (is_object($cron)) {
                $cron->remove();
            }
            $user = new user();
            $user->setLogin('jeedom_master');
            $user->setPassword(config::genKey(255));
            $user->setRights('admin', 1);
            $user->save();
            config::save('jeeNetwork::mode', 'slave');
        }
        break;
    }
}

public static function pull() {
    foreach (self::all() as $jeeNetwork) {
        if ($jeeNetwork->getStatus() == 'ok') {
            try {
                $jeeNetwork->save();
            } catch (Exception $e) {
                log::add('jeeNetwork', 'error', $e->getMessage());
            }
        } else {
            try {
                $jeeNetwork->save();
            } catch (Exception $e) {

            }
        }
    }
}

public static function testMaster() {
    if (config::byKey('jeeNetwork::mode') != 'slave') {
        throw new Exception(__('Seul un esclave peut envoyer un backup au maître', __FILE__));
    }
    $jsonrpc = self::getJsonRpcMaster();
    if ($jsonrpc->sendRequest('ping')) {
        if ($jsonrpc->getResult() != 'pong') {
            throw new Exception(__('Erreur réponse du maître != pong : ', __FILE__) . $jsonrpc->getResult());
        }
    } else {
        if (strpos(config::byKey('jeeNetwork::master::ip'), '/jeedom') === false) {
            config::save('jeeNetwork::master::ip', config::byKey('jeeNetwork::master::ip') . '/jeedom');
            $jsonrpc = self::getJsonRpcMaster();
            if ($jsonrpc->sendRequest('ping')) {
                if ($jsonrpc->getResult() != 'pong') {
                    throw new Exception(__('Erreur reponse du maitre != pong : ', __FILE__) . $jsonrpc->getResult());
                }
            } else {
                throw new Exception($jsonrpc->getError());
            }
        } else {
            throw new Exception($jsonrpc->getError());
        }
    }
}

public static function sendBackup($_path) {
    if (config::byKey('jeeNetwork::mode') != 'slave') {
        throw new Exception(__('Seul un esclave peut envoyer un backup au maître', __FILE__));
    }
    $jsonrpc = self::getJsonRpcMaster();
    $file = array(
        'file' => '@' . realpath($_path)
        );
    if (!$jsonrpc->sendRequest('jeeNetwork::receivedBackup', array(), 3600, $file)) {
        throw new Exception($jsonrpc->getError());
    }
}

public static function getJsonRpcMaster() {
    if (config::byKey('jeeNetwork::master::ip') == '') {
        throw new Exception(__('Aucune adresse IP renseignée pour le maître ', __FILE__));
    }
    return new jsonrpcClient(config::byKey('jeeNetwork::master::ip') . '/core/api/jeeApi.php', config::byKey('jeeNetwork::master::apikey'), array('slave_id' => config::byKey('jeeNetwork::slave::id')));
}

/*     * *********************Methode d'instance************************* */

public function preUpdate() {
    if ($this->getIp() == '') {
        throw new Exception('L\'adresse IP ne peut pas être vide');
    }
    if ($this->getApikey() == '') {
        throw new Exception('La clef API ne peut pas être vide');
    }
    try {
        $this->handshake();
    } catch (Exception $e) {
        $old_ip = $this->getIp();
        if (strpos($this->getIp(), '/jeedom') === false) {
            try {
                $this->setIp($this->getIp() . '/jeedom');
                $this->handshake();
            } catch (Exception $e) {
                $this->setIp($old_ip);
                DB::save($this, true);
                throw $e;
            }
        } else {
            DB::save($this, true);
            throw $e;
        }
    }
}

public function save() {
    return DB::save($this);
}

public function remove() {
    return DB::remove($this);
}

public function handshake() {
    $jsonrpc = $this->getJsonRpc();
    $params = array(
        'apikey_master' => config::byKey('api'),
        'address' => config::byKey('internalProtocol').config::byKey('internalAddr'). ':'.config::byKey('internalPort','core',80).config::byKey('internalComplement'),
        'slave_ip' => $this->getRealIp(),
        'slave_id' => $this->getId(),
        );
    if ($jsonrpc->sendRequest('jeeNetwork::handshake', $params, 60)) {
        $result = $jsonrpc->getResult();
        $this->setStatus('ok');
        $this->setPlugin($result['plugin']);
        $this->setConfiguration('nbUpdate', $result['nbUpdate']);
        $this->setConfiguration('version', $result['version']);
        $this->setConfiguration('auiKey', $result['auiKey']);
        $this->setConfiguration('lastCommunication', date('Y-m-d H:i:s'));
        if ($this->getConfiguration('nbMessage') != $result['nbMessage'] && $result['nbMessage'] > 0) {
            log::add('jeeNetwork', 'error', __('Le jeedom esclave : ', __FILE__) . $this->getName() . __(' a de nouveaux messages : ', __FILE__) . $result['nbMessage']);
        }
        $this->setConfiguration('nbMessage', $result['nbMessage']);
    } else {
        $this->setStatus('error');
        throw new Exception($jsonrpc->getError(), $jsonrpc->getErrorCode());
    }
}

public function sendRawRequest($_method,$_params = array()){
   $jsonrpc = $this->getJsonRpc();
   if (!$jsonrpc->sendRequest($_method, $_params)) {
    throw new Exception($jsonrpc->getError(), $jsonrpc->getErrorCode());
}
return $jsonrpc->getResult();
}

public function reload() {
    if ($this->getStatus() == 'error') {
        return '';
    }
    $jsonrpc = $this->getJsonRpc();
    if (!$jsonrpc->sendRequest('jeeNetwork::reload', array())) {
        throw new Exception($jsonrpc->getError(), $jsonrpc->getErrorCode());
    }
}

public function halt() {
    if ($this->getStatus() == 'error') {
        return '';
    }
    $jsonrpc = $this->getJsonRpc();
    if (!$jsonrpc->sendRequest('jeeNetwork::halt', array())) {
        throw new Exception($jsonrpc->getError(), $jsonrpc->getErrorCode());
    }
}

public function reboot() {
    if ($this->getStatus() == 'error') {
        return '';
    }
    $jsonrpc = $this->getJsonRpc();
    if (!$jsonrpc->sendRequest('jeeNetwork::reboot', array())) {
        throw new Exception($jsonrpc->getError(), $jsonrpc->getErrorCode());
    }
}

public function update() {
    if ($this->getStatus() == 'error') {
        return '';
    }
    $jsonrpc = $this->getJsonRpc();
    if (!$jsonrpc->sendRequest('jeeNetwork::update', array())) {
        throw new Exception($jsonrpc->getError(), $jsonrpc->getErrorCode());
    }
}

public function checkUpdate() {
    if ($this->getStatus() == 'error') {
        return '';
    }
    $jsonrpc = $this->getJsonRpc();
    if ($jsonrpc->sendRequest('jeeNetwork::checkUpdate', array())) {
        $this->save();
    } else {
        throw new Exception($jsonrpc->getError(), $jsonrpc->getErrorCode());
    }
}

public function installPlugin($_plugin_id, $_version = 'stable') {
    $jsonrpc = $this->getJsonRpc();
    $params = array(
        'plugin_id' => $_plugin_id,
        'version' => $_version,
        );
    if (!$jsonrpc->sendRequest('jeeNetwork::installPlugin', $params)) {
        throw new Exception($jsonrpc->getError(), $jsonrpc->getErrorCode());
    }
}

public function getLog($_log = 'core', $_begin, $_nbLines) {
    if ($this->getStatus() == 'error') {
        return '';
    }
    $jsonrpc = $this->getJsonRpc();
    $params = array(
        'log' => $_log,
        'start' => $_begin,
        'nbLine' => $_nbLines,
        );
    if ($jsonrpc->sendRequest('log::get', $params)) {
        return $jsonrpc->getResult();
    } else {
        throw new Exception($jsonrpc->getError(), $jsonrpc->getErrorCode());
    }
}

public function emptyLog($_log = 'core') {
    if ($this->getStatus() == 'error') {
        return '';
    }
    $jsonrpc = $this->getJsonRpc();
    $params = array(
        'log' => $_log,
        );
    if (!$jsonrpc->sendRequest('log::empty', $params)) {
        throw new Exception($jsonrpc->getError(), $jsonrpc->getErrorCode());
    }
}

public function removeLog($_log = 'core') {
    if ($this->getStatus() == 'error') {
        return '';
    }
    $jsonrpc = $this->getJsonRpc();
    $params = array(
        'log' => $_log,
        );
    if (!$jsonrpc->sendRequest('log::remove', $params)) {
        throw new Exception($jsonrpc->getError(), $jsonrpc->getErrorCode());
    }
}

public function getListLog() {
    if ($this->getStatus() == 'error') {
        return '';
    }
    $jsonrpc = $this->getJsonRpc();
    if ($jsonrpc->sendRequest('log::list', array())) {
        return $jsonrpc->getResult();
    } else {
        throw new Exception($jsonrpc->getError(), $jsonrpc->getErrorCode());
    }
}

public function getMessage() {
    if ($this->getStatus() == 'error') {
        return '';
    }
    $jsonrpc = $this->getJsonRpc();
    if ($jsonrpc->sendRequest('message::all', array())) {
        return $jsonrpc->getResult();
    } else {
        throw new Exception($jsonrpc->getError(), $jsonrpc->getErrorCode());
    }
}

public function removeAllMessage() {
    if ($this->getStatus() == 'error') {
        return '';
    }
    $jsonrpc = $this->getJsonRpc();
    if (!$jsonrpc->sendRequest('message::removeAll', array())) {
        throw new Exception($jsonrpc->getError(), $jsonrpc->getErrorCode());
    }
    return true;
}

public function restoreLocalBackup($_backup) {
    if (!file_exists($_backup)) {
        throw new Exception(__('Backup non trouvé : ', __FILE__) . $_backup);
    }
    $jsonrpc = $this->getJsonRpc();
    $file = array(
        'file' => '@' . realpath($_backup)
        );
    if (!$jsonrpc->sendRequest('jeeNetwork::restoreBackup', array(), 3600, $file)) {
        throw new Exception($jsonrpc->getError());
    }
}

public function getJsonRpc() {
    if ($this->getIp() == '') {
        throw new Exception(__('Aucune adresse IP renseignée pour : ', __FILE__) . $this->getName());
    }
    return new jsonrpcClient($this->getIp() . '/core/api/jeeApi.php', $this->getApikey());
}

public function getRealIp() {
    return getIpFromString($this->getIp());
}

/*     * **********************Getteur Setteur*************************** */

public function getId() {
    return $this->id;
}

public function getIp() {
    return $this->ip;
}

public function getApikey() {
    return $this->apikey;
}

public function setId($id) {
    $this->id = $id;
}

public function setIp($ip) {
    $this->ip = $ip;
}

public function setApikey($apikey) {
    $this->apikey = $apikey;
}

public function getPlugin() {
    return json_decode($this->plugin, true);
}

public function setPlugin($plugins) {
    $this->plugin = json_encode($plugins, JSON_UNESCAPED_UNICODE);
}

public function getConfiguration($_key = '', $_default = '') {
    return utils::getJsonAttr($this->configuration, $_key, $_default);
}

public function setConfiguration($_key, $_value) {
    $this->configuration = utils::setJsonAttr($this->configuration, $_key, $_value);
}

public function getName() {
    return $this->name;
}

public function setName($name) {
    $this->name = $name;
}

function getStatus() {
    return $this->status;
}

function setStatus($status) {
    $this->status = $status;
}

}
