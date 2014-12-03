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

class jeedom {
    /*     * *************************Attributs****************************** */

    private static $jeedomConfiguration;

    /*     * ***********************Methode static*************************** */

    public static function stop() {
        try {
            echo "Desactivation de toutes les tâches";
            config::save('enableCron', 0);
            foreach (cron::all() as $cron) {
                if ($cron->running()) {
                    $cron->halt();
                    echo '.';
                }
            }
            echo " OK\n";
        } catch (Exception $e) {
            if (!isset($_GET['mode']) || $_GET['mode'] != 'force') {
                throw $e;
            } else {
                echo "\n***ERREUR*** " . $e->getMessage();
            }
        }
        /*         * **********Arret des crons********************* */

        try {
            if (cron::jeeCronRun()) {
                echo "Arret du cron master ";
                $pid = cron::getPidFile();
                $kill = posix_kill($pid, 15);
                if (!$kill) {
                    $kill = posix_kill($pid, 9);
                    if (!$kill) {
                        throw new Exception('Impossible d\'arrêter le cron master : ' . $pid);
                    }
                }
                echo " OK\n";
            }
        } catch (Exception $e) {
            if (!isset($_GET['mode']) || $_GET['mode'] != 'force') {
                throw $e;
            } else {
                echo '***ERREUR*** ' . $e->getMessage();
            }
        }


        /*         * *********Arrêt des scénarios**************** */
        try {
            echo "Désactivation de tous les scénarios";
            config::save('enableScenario', 0);
            foreach (scenario::all() as $scenario) {
                $scenario->stop();
                echo '.';
            }
            echo " OK\n";
        } catch (Exception $e) {
            if (!isset($_GET['mode']) || $_GET['mode'] != 'force') {
                throw $e;
            } else {
                echo '***ERREUR*** ' . $e->getMessage();
            }
        }
    }

    public static function start() {
        try {
            /*             * *********Réactivation des scénarios**************** */
            echo "Réactivation des scénarios : ";
            config::save('enableScenario', 1);
            echo "OK\n";
            /*             * *********Réactivation des tâches**************** */
            echo "Réactivation des tâches : ";
            config::save('enableCron', 1);
            echo "OK\n";
        } catch (Exception $e) {
            if (!isset($_GET['mode']) || $_GET['mode'] != 'force') {
                throw $e;
            } else {
                echo '***ERREUR*** ' . $e->getMessage();
            }
        }
    }

    public static function sick() {
        $cmd = 'php ' . dirname(__FILE__) . '/../../sick.php';
        $cmd.= ' >> ' . log::getPathToLog('sick') . ' 2>&1';
        shell_exec($cmd);
    }

    public static function getUsbMapping($_name = '') {
        $cache = cache::byKey('jeedom::usbMapping');
        if (!is_json($cache->getValue()) || $_name == '') {
            $usbMapping = array();
            foreach (ls('/dev/', 'ttyUSB*') as $usb) {
                $vendor = '';
                $model = '';
                foreach (explode("\n", shell_exec('/sbin/udevadm info --name=/dev/' . $usb . ' --query=all')) as $line) {
                    if (strpos($line, 'E: ID_MODEL=') !== false) {
                        $model = trim(str_replace(array('E: ID_MODEL=', '"'), '', $line));
                    }
                    if (strpos($line, 'E: ID_VENDOR=') !== false) {
                        $vendor = trim(str_replace(array('E: ID_VENDOR=', '"'), '', $line));
                    }
                }
                if ($vendor == '' && $model == '') {
                    $usbMapping['/dev/' . $usb] = '/dev/' . $usb;
                } else {
                    $name = trim($vendor . ' ' . $model);
                    $number = 2;
                    while (isset($usbMapping[$name])) {
                        $name = trim($vendor . ' ' . $model . ' ' . $number);
                        $number++;
                    }
                    $usbMapping[$name] = '/dev/' . $usb;
                }
            }
            cache::set('jeedom::usbMapping', json_encode($usbMapping), 0);
        } else {
            $usbMapping = json_decode($cache->getValue(), true);
        }
        if ($_name != '') {
            if (isset($usbMapping[$_name])) {
                return $usbMapping[$_name];
            }
            $usbMapping = array();
            foreach (ls('/dev/', 'ttyUSB*') as $usb) {
                $vendor = '';
                $model = '';
                foreach (explode("\n", shell_exec('/sbin/udevadm info --name=/dev/' . $usb . ' --query=all')) as $line) {
                    if (strpos($line, 'E: ID_MODEL=') !== false) {
                        $model = trim(str_replace(array('E: ID_MODEL=', '"'), '', $line));
                    }
                    if (strpos($line, 'E: ID_VENDOR=') !== false) {
                        $vendor = trim(str_replace(array('E: ID_VENDOR=', '"'), '', $line));
                    }
                }
                if ($vendor == '' && $model == '') {
                    $usbMapping['/dev/' . $usb] = '/dev/' . $usb;
                } else {
                    $name = trim($vendor . ' ' . $model);
                    $number = 2;
                    while (isset($usbMapping[$name])) {
                        $name = trim($vendor . ' ' . $model . ' ' . $number);
                        $number++;
                    }
                    $usbMapping[$name] = '/dev/' . $usb;
                }
            }
            cache::set('jeedom::usbMapping', json_encode($usbMapping), 0);
            if (isset($usbMapping[$_name])) {
                return $usbMapping[$_name];
            }
            if (file_exists($_name)) {
                return $_name;
            }
            return '';
        }
        return $usbMapping;
    }

    public static function persist() {
        
    }

    public static function backup($_background = false) {
        if ($_background) {
            log::clear('backup');
            $cmd = 'php ' . dirname(__FILE__) . '/../../install/backup.php';
            $cmd.= ' >> ' . log::getPathToLog('backup') . ' 2>&1 &';
            exec($cmd);
        } else {
            require_once dirname(__FILE__) . '/../../install/backup.php';
        }
    }

    public static function listBackup() {
        if (substr(config::byKey('backup::path'), 0, 1) != '/') {
            $backup_dir = dirname(__FILE__) . '/../../' . config::byKey('backup::path');
        } else {
            $backup_dir = config::byKey('backup::path');
        }
        $backups = ls($backup_dir, '*.tar.gz', false, array('files', 'quiet', 'datetime_asc'));
        $return = array();
        foreach ($backups as $backup) {
            $return[$backup_dir . '/' . $backup] = $backup;
        }
        return $return;
    }

    public static function removeBackup($_backup) {
        if (file_exists($_backup)) {
            unlink($_backup);
        } else {
            throw new Exception('Impossible de trouver le fichier : ' . $_backup);
        }
    }

    public static function restore($_backup = '', $_background = false) {
        if ($_background) {
            log::clear('restore');
            $cmd = 'php ' . dirname(__FILE__) . '/../../install/restore.php backup=' . $_backup;
            $cmd.= ' >> ' . log::getPathToLog('restore') . ' 2>&1 &';
            exec($cmd);
        } else {
            global $BACKUP_FILE;
            $BACKUP_FILE = $_backup;
            require_once dirname(__FILE__) . '/../../install/restore.php';
        }
    }

    public static function update($_mode = '', $_level = -1) {
        log::clear('update');
        $cmd = 'php ' . dirname(__FILE__) . '/../../install/install.php mode=' . $_mode . ' level=' . $_level;
        $cmd.= ' >> ' . log::getPathToLog('update') . ' 2>&1 &';
        exec($cmd);
    }

    public static function getConfiguration($_key, $_default = false) {
        if (!is_array(self::$jeedomConfiguration)) {
            self::$jeedomConfiguration = array();
        }
        if (!$_default && isset(self::$jeedomConfiguration[$_key])) {
            return self::$jeedomConfiguration[$_key];
        }
        $keys = explode(':', $_key);
        global $JEEDOM_INTERNAL_CONFIG;
        $result = $JEEDOM_INTERNAL_CONFIG;
        foreach ($keys as $key) {
            if (isset($result[$key])) {
                $result = $result[$key];
            }
        }
        if ($_default) {
            return $result;
        }
        self::$jeedomConfiguration[$_key] = self::checkValueInconfiguration($_key, $result);
        return self::$jeedomConfiguration[$_key];
    }

    private static function checkValueInconfiguration($_key, $_value) {
        if (!is_array(self::$jeedomConfiguration)) {
            self::$jeedomConfiguration = array();
        }
        if (isset(self::$jeedomConfiguration[$_key])) {
            return self::$jeedomConfiguration[$_key];
        }
        if (is_array($_value)) {
            foreach ($_value as $key => $value) {
                $_value[$key] = self::checkValueInconfiguration($_key . ':' . $key, $value);
            }
            self::$jeedomConfiguration[$_key] = $_value;
            return $_value;
        } else {
            $config = config::byKey($_key);
            return ($config == '') ? $_value : $config;
        }
    }

    public static function whatDoYouKnow($_object = null) {
        $result = array();
        if (is_object($_object)) {
            $objects = array($_object);
        } else {
            $objects = object::all();
        }
        foreach ($objects as $object) {
            foreach ($object->getEqLogic() as $eqLogic) {
                if ($eqLogic->getIsEnable() == 1) {
                    foreach ($eqLogic->getCmd() as $cmd) {
                        if ($cmd->getIsVisible() == 1 && $cmd->getType() == 'info') {
                            try {
                                $value = $cmd->execCmd();
                                if (!isset($result[$object->getId()])) {
                                    $result[$object->getId()] = array();
                                    $result[$object->getId()]['name'] = $object->getName();
                                    $result[$object->getId()]['eqLogic'] = array();
                                }
                                if (!isset($result[$object->getId()]['eqLogic'][$eqLogic->getId()])) {
                                    $result[$object->getId()]['eqLogic'][$eqLogic->getId()] = array();
                                    $result[$object->getId()]['eqLogic'][$eqLogic->getId()]['name'] = $eqLogic->getName();
                                    $result[$object->getId()]['eqLogic'][$eqLogic->getId()]['cmd'] = array();
                                }

                                $result[$object->getId()]['eqLogic'][$eqLogic->getId()]['cmd'][$cmd->getId()] = array();
                                $result[$object->getId()]['eqLogic'][$eqLogic->getId()]['cmd'][$cmd->getId()]['name'] = $cmd->getName();
                                $result[$object->getId()]['eqLogic'][$eqLogic->getId()]['cmd'][$cmd->getId()]['unite'] = $cmd->getUnite();
                                $result[$object->getId()]['eqLogic'][$eqLogic->getId()]['cmd'][$cmd->getId()]['value'] = $value;
                            } catch (Exception $exc) {
                                
                            }
                        }
                    }
                }
            }
        }
        return $result;
    }

    public static function needUpdate($_refresh = false) {
        $return = array();
        $return['currentVersion'] = market::getJeedomCurrentVersion($_refresh);
        $return['version'] = getVersion('jeedom');
        if (version_compare($return['currentVersion'], $return['version'], '>')) {
            $return['needUpdate'] = true;
        } else {
            $return['needUpdate'] = false;
        }
        return $return;
    }

    public static function isStarted() {
        $sql = "SELECT `value` FROM `start` WHERE `key`='start'";
        $result = DB::Prepare($sql, array());
        if (count($result) > 0 && $result['value'] == 'ok') {
            return true;
        }
        return false;
    }

    public static function isDateOk() {
        $cache = cache::byKey('jeedom::lastDate');
        $lastDate = strtotime($cache->getValue());
        if ($lastDate == '' || $lastDate === false) {
            cache::set('jeedom::lastDate', date('Y-m-d H:00:00'), 0);
            message::removeAll('core', 'dateCheckFailed');
            return true;
        }
        if ($lastDate == strtotime(date('Y-m-d H:00:00'))) {
            message::removeAll('core', 'dateCheckFailed');
            return true;
        }
        if (($lastDate + 2592000) > strtotime(date('Y-m-d H:00:00')) && ($lastDate - 7200) < strtotime(date('Y-m-d H:00:00'))) {
            cache::set('jeedom::lastDate', date('Y-m-d H:00:00'), 0);
            message::removeAll('core', 'dateCheckFailed');
            return true;
        }
        $ntptime = strtotime(getNtpTime());
        if ($ntptime !== false && ($ntptime + 7200) > strtotime('now') && ($ntptime - 7200) < strtotime('now')) {
            cache::set('jeedom::lastDate', date('Y-m-d H:00:00'), 0);
            message::removeAll('core', 'dateCheckFailed');
            return true;
        }
        log::add('core', 'error', __('La date système (', __FILE__) . date('Y-m-d H:00:00') . __(') est antérieure ou trop loin de la dernière date (', __FILE__) . date('Y-m-d H:i:s', $lastDate) . __(')enregistrer. Toutes les exécutions des scénarios sont interrompues jusqu\'à correction.', __FILE__), 'dateCheckFailed');
        return false;
    }

    public static function event($_event) {
        scenario::check($_event);
    }

    public static function cron() {
        if (!self::isStarted()) {
            $cache = cache::byKey('jeedom::usbMapping');
            $cache->remove();
            jeedom::start();
            plugin::start();
            internalEvent::start();
            self::doUPnP();
            DB::Prepare("REPLACE INTO `start` (`key` ,`value`) VALUES ('start',  'ok')", array());
            self::event('start');
            log::add('core', 'info', 'Démarrage de Jeedom OK');
        }
        plugin::cron();
        interactDef::cron();
        eqLogic::checkAlive();
        connection::cron();
        try {
            $c = new Cron\CronExpression(config::byKey('log::chunck'), new Cron\FieldFactory);
            if ($c->isDue()) {
                log::chunk();
            }
        } catch (Exception $e) {
            log::add('log', 'error', $e->getMessage());
        }
        try {
            $c = new Cron\CronExpression(config::byKey('update::check'), new Cron\FieldFactory);
            if ($c->isDue()) {
                if (config::byKey('update::auto') == 1) {
                    jeedom::update();
                } else {
                    update::checkAllUpdate();
                    $nbUpdate = update::nbNeedUpdate();
                    if ($nbUpdate > 0) {
                        message::add('update', 'De nouvelles mises à jour sont disponibles (' . $nbUpdate . ')', '', 'newUpdate');
                    }
                }
                config::save('update::check', rand(10, 59) . ' 06 * * *');
            }
        } catch (Exception $e) {
            //log::add('update', 'error', '[' . config::byKey('update::check') . ']' . $e->getMessage());
        }
        try {
            $c = new Cron\CronExpression(config::byKey('backup::cron'), new Cron\FieldFactory);
            if ($c->isDue()) {
                jeedom::backup();
            }
        } catch (Exception $e) {
            
        }
        try {
            $c = new Cron\CronExpression('50 23 * * *', new Cron\FieldFactory);
            if ($c->isDue()) {
                scenario::cleanTable();
            }
        } catch (Exception $e) {
            log::add('scenario', 'error', $e->getMessage());
        }
        try {
            $c = new Cron\CronExpression('00 * * * *', new Cron\FieldFactory);
            if ($c->isDue()) {
                self::isDateOk();
            }
        } catch (Exception $e) {
            log::add('scenario', 'error', $e->getMessage());
        }
        try {
            $c = new Cron\CronExpression(config::byKey('jeeNetwork::pull'), new Cron\FieldFactory);
            if ($c->isDue()) {
                jeeNetwork::pull();
            }
        } catch (Exception $e) {
            log::add('jeeNetwork', 'error', '[' . config::byKey('jeeNetwork::pull') . ']' . $e->getMessage());
        }
        if (config::byKey('market::allowDNS') == 1 && config::byKey('jeeNetwork::mode') == 'master' && config::byKey('jeedom::licence') >= 5) {
            try {
                $c = new Cron\CronExpression('*/10 * * * *', new Cron\FieldFactory);
                if ($c->isDue()) {
                    market::updateIp();
                }
            } catch (Exception $e) {
                log::add('market', 'error', '[' . config::byKey('jeeNetwork::mode') . ']' . $e->getMessage());
            }
        }
    }

    public static function checkOngoingThread($_cmd) {
        return shell_exec('ps ax | grep "' . $_cmd . '$" | grep -v "grep" | wc -l');
    }

    public static function retrievePidThread($_cmd) {
        return shell_exec('ps ax | grep "' . $_cmd . '$" | grep -v "grep" | awk \'{print $1}\'');
    }

    public static function getHardwareKey() {
        $cache = cache::byKey('jeedom::hwkey');
        if ($cache->getValue(0) == 0) {
            $rdkey = config::byKey('jeedom::rdkey');
            if ($rdkey == '') {
                $rdkey = config::genKey();
                config::save('jeedom::rdkey', $rdkey);
            }
            $key = shell_exec("/sbin/ifconfig eth0 | grep -o -E '([[:xdigit:]]{1,2}:){5}[[:xdigit:]]{1,2}'");
            $hwkey = sha1($key . $rdkey);
            cache::set('jeedom::hwkey', $hwkey, 86400);
            return $hwkey;
        }
        return $cache->getValue();
    }

    public static function isRestrictionOk() {
        $isRestrictionOk = cache::byKey('isRestrictionOk');
        if ($isRestrictionOk->getValue(-1) != -1) {
            return $isRestrictionOk->getValue(0);
        }
        $register_datetime = config::save('register::datetime', date('Y-m-d H:i:s'));
        $restrict_hw = shell_exec("dmesg | grep HummingBoard | wc -l");
        if ($restrict_hw == 1 && config::byKey('jeedom::licence') < 1) {
            if (($register_datetime + 604800) > strtotime('now')) {
                $result = $register_datetime + 604800 - strtotime('now');
                log::add(__('hardware', 'error', 'Attention vous utilisez Jeedom sur un matériel soumis à une licence, veuillez enregistrer votre compte market et/ou acheter une licence, il vous reste ', __FILE__) . convertDuration($result), 'restrictHardwareTime');
                cache::set('isRestrictionOk', $result, 86400);
                return $result;
            }
            cache::set('isRestrictionOk', 0, 86400);

            return 0;
        }
        cache::set('isRestrictionOk', 1, 86400);
        return 1;
    }

    public static function versionAlias($_version) {
        $alias = array(
            'mview' => 'mobile',
            'dview' => 'dashboard',
        );
        return (isset($alias[$_version])) ? $alias[$_version] : $_version;
    }

    public static function toHumanReadable($_input) {
        return scenario::toHumanReadable(eqLogic::toHumanReadable(cmd::cmdToHumanReadable($_input)));
    }

    public static function fromHumanReadable($_input) {
        return scenario::fromHumanReadable(eqLogic::fromHumanReadable(cmd::humanReadableToCmd($_input)));
    }

    public static function evaluateExpression($_input) {
        try {
            $_input = scenarioExpression::setTags($_input);
            $test = new evaluate();
            $result = $test->Evaluer($_input);
            if (is_bool($result) || is_numeric($result)) {
                return $result;
            }
            return $_input;
        } catch (Exception $exc) {
            return $_input;
        }
    }

    public static function haltSystem() {
        exec('sudo halt');
    }

    public static function rebootSystem() {
        exec('sudo reboot');
    }

    public static function portForwarding($_internalIp, $_internalPort, $_externalPort, $_protocol = 'TCP') {
        $fp = popen("which upnpc", "r");
        $result = fgets($fp, 255);
        $exists = !empty($result);
        pclose($fp);
        if (!$exists) {
            throw new Exception(__('Impossible de trouver : upnpc. Veuillez l\'installer en ssh en faisant : "sudo apt-get install -y miniupnpc"', __FILE__));
        }
        shell_exec('upnpc -d ' . $_externalPort . ' ' . $_protocol);
        $result = exec('upnpc -a ' . $_internalIp . ' ' . $_internalPort . ' ' . $_externalPort . ' ' . $_protocol);
        if (strpos($result, 'is redirected to internal') === false) {
            throw new Exception(__('Echec de la redirection de port : ', __FILE__) . $result);
        }
    }

    public static function doUPnP() {
        if (config::byKey('internalAddr') == '') {
            config::save('internalAddr', exec("/sbin/ifconfig eth0 | grep 'inet adr:' | cut -d: -f2 | awk '{ print $1}'"));
        }
        if (config::byKey('allowupnpn') == 1) {
            try {
                self::portForwarding(getIpFromString(config::byKey('internalAddr')), 80, config::byKey('externalPort', 80));
            } catch (Exception $e) {
                log::add('core', 'error', $e->getMessage());
            }
        }
    }

    public function checkFilesystem() {
        $result = exec('dmesg | grep "I/O error" | wc -l');
        if ($result != 0) {
            log::add('core', 'error', __('Erreur : corruption sur le filesystem detecter (I/O error sur dmseg)', __FILE__));
            return false;
        }
        return true;
    }

    /*     * ****************************SQL BUDDY*************************** */

    public static function getCurrentSqlBuddyFolder() {
        $dir = dirname(__FILE__) . '/../../';
        $ls = ls($dir, 'sqlbuddy*');
        if (count($ls) != 1) {
            return '';
        }
        return $ls[0];
    }

    public static function renameSqlBuddyFolder() {
        $folder = self::getCurrentSqlBuddyFolder();
        if ($folder != '') {
            rename(dirname(__FILE__) . '/../../' . $folder, dirname(__FILE__) . '/../../sqlbuddy' . config::genKey());
        }
    }

    /*     * *********************Methode d'instance************************* */

    /*     * **********************Getteur Setteur*************************** */
}

?>
