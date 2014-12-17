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

class plugin {
    /*     * *************************Attributs****************************** */

    private $id;
    private $name;
    private $description;
    private $licence;
    private $installation;
    private $author;
    private $require;
    private $version;
    private $category;
    private $filepath;
    private $icon;
    private $index;
    private $display;
    private $mobile;
    private $allowRemote;
    private $include = array();
    private static $_cache = array();

    /*     * ***********************Méthodes statiques*************************** */

    public static function byId($_id) {
        if (isset(self::$_cache[$_id])) {
            return self::$_cache[$_id];
        }
        if (!file_exists($_id)) {
            $_id = self::getPathById($_id);
            if (isset(self::$_cache[$_id])) {
                return self::$_cache[$_id];
            }
            if (!file_exists($_id)) {
                throw new Exception('Plugin introuvable : ' . $_id);
            }
        }

        $plugin_xml = @simplexml_load_file($_id);
        if (!is_object($plugin_xml)) {
            throw new Exception('Plugin introuvable : ' . $_id);
        }
        $plugin = new plugin();
        $plugin->id = (string) $plugin_xml->id;
        $plugin->name = (string) $plugin_xml->name;
        $plugin->description = (string) $plugin_xml->description;
        $plugin->icon = (string) $plugin_xml->icon;
        $plugin->licence = (string) $plugin_xml->licence;
        $plugin->author = (string) $plugin_xml->author;
        $plugin->require = (string) $plugin_xml->require;
        $plugin->version = (string) $plugin_xml->version;
        $plugin->installation = (string) $plugin_xml->installation;
        $plugin->category = (string) $plugin_xml->category;
        $plugin->allowRemote = 0;
        if (isset($plugin_xml->allowRemote)) {
            $plugin->allowRemote = $plugin_xml->allowRemote;
        }
        $plugin->filepath = $_id;
        $plugin->index = (isset($plugin_xml->index)) ? (string) $plugin_xml->index : $plugin_xml->id;
        $plugin->display = (isset($plugin_xml->display)) ? (string) $plugin_xml->display : '';

        $plugin->mobile = '';
        if (file_exists(dirname(__FILE__) . '/../../plugins/' . $plugin_xml->id . '/mobile')) {
            $plugin->mobile = (isset($plugin_xml->mobile)) ? (string) $plugin_xml->mobile : $plugin_xml->id;
        }
        if (isset($plugin_xml->include)) {
            $plugin->include = array(
                'file' => (string) $plugin_xml->include,
                'type' => (string) $plugin_xml->include['type']
            );
        } else {
            $plugin->include = array(
                'file' => $plugin_xml->id,
                'type' => 'class'
            );
        }

        self::$_cache[$_id] = $plugin;
        self::$_cache[$plugin->id] = $plugin;
        return $plugin;
    }

    public static function getPathById($_id) {
        return dirname(__FILE__) . '/../../plugins/' . $_id . '/plugin_info/info.xml';
    }

    public function getPathToConfigurationById() {
        if (file_exists(dirname(__FILE__) . '/../../plugins/' . $this->id . '/plugin_info/configuration.php')) {
            return 'plugins/' . $this->id . '/plugin_info/configuration.php';
        } else {
            return '';
        }
    }

    public static function listPlugin($_activateOnly = false, $_orderByCaterogy = false) {
        $listPlugin = array();
        if ($_activateOnly) {
            $sql = "SELECT plugin
                    FROM config
                    WHERE `key`='active'
                    AND `value`='1'";
            $results = DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL);
            foreach ($results as $result) {
                try {
                    $listPlugin[] = plugin::byId($result['plugin']);
                } catch (Exception $e) {
                    log::add('plugin', 'error', $e->getMessage(), 'pluginNotFound::' . $result['plugin']);
                }
            }
        } else {
            $rootPluginPath = dirname(__FILE__) . '/../../plugins';
            foreach (ls($rootPluginPath, '*') as $dirPlugin) {
                if (is_dir($rootPluginPath . '/' . $dirPlugin)) {
                    $pathInfoPlugin = $rootPluginPath . '/' . $dirPlugin . '/plugin_info/info.xml';
                    if (file_exists($pathInfoPlugin)) {
                        try {
                            $listPlugin[] = plugin::byId($pathInfoPlugin);
                        } catch (Exception $e) {
                            log::add('plugin', 'error', $e->getMessage(), 'pluginNotFound::' . $pathInfoPlugin);
                        }
                    }
                }
            }
        }
        if ($_orderByCaterogy) {
            $return = array();
            if (count($listPlugin) > 0) {
                foreach ($listPlugin as $plugin) {
                    $category = $plugin->getCategory();
                    if ($category == '') {
                        $category = __('Autre', __FILE__);
                    }
                    if (!isset($return[$category])) {
                        $return[$category] = array();
                    }
                    $return[$category][] = $plugin;
                }
                foreach ($return as &$category) {
                    usort($category, 'plugin::orderPlugin');
                }
                ksort($return);
            }
            return $return;
        } else {
            if (isset($listPlugin) && is_array($listPlugin) && count($listPlugin) > 0) {
                usort($listPlugin, 'plugin::orderPlugin');
                return $listPlugin;
            } else {
                return array();
            }
        }
    }

    public static function orderPlugin($a, $b) {
        $al = strtolower($a->name);
        $bl = strtolower($b->name);
        if ($al == $bl) {
            return 0;
        }
        return ($al > $bl) ? +1 : -1;
    }

    public static function cron() {
        foreach (self::listPlugin(true) as $plugin) {
            if (method_exists($plugin->getId(), 'cron')) {
                $plugin->launch('cron');
            }
        }
    }

    public static function cronDaily() {
        foreach (self::listPlugin(true) as $plugin) {
            if (method_exists($plugin->getId(), 'cronDaily')) {
                $plugin->launch('cronDaily');
            }
        }
    }

    public static function cronHourly() {
        foreach (self::listPlugin(true) as $plugin) {
            if (method_exists($plugin->getId(), 'cronHourly')) {
                $plugin->launch('cronHourly');
            }
        }
    }

    public static function start() {
        foreach (self::listPlugin(true) as $plugin) {
            if (method_exists($plugin->getId(), 'start')) {
                $plugin->launch('start');
            }
        }
    }

    /*     * *********************Méthodes d'instance************************* */

    public function isActive() {
        return config::byKey('active', $this->id);
    }

    public function setIsEnable($_state) {
        if (version_compare(getVersion('jeedom'), $this->getRequire()) == -1 && $_state == 1) {
            throw new Exception('Votre version de jeedom n\'est pas assez récente pour activer ce plugin');
        }
        $alreadyActive = config::byKey('active', $this->getId(), 0);
        if ($_state == 1) {
            if (config::byKey('jeeNetwork::mode') != 'master' && $this->getAllowRemote() != 1) {
                throw new Exception('Vous ne pouvez pas activer ce plugin sur un Jeedom configuré en esclave');
            }
            market::checkPayment($this->getId());
            config::save('active', $_state, $this->getId());
        }
        if ($_state == 0) {
            foreach (eqLogic::byType($this->getId()) as $eqLogic) {
                $eqLogic->setIsEnable(0);
                $eqLogic->setIsVisible(0);
                $eqLogic->save();
            }
        }
        if ($alreadyActive == 0 && $_state == 1) {
            foreach (eqLogic::byType($this->getId()) as $eqLogic) {
                $eqLogic->setIsEnable(1);
                $eqLogic->setIsVisible(1);
                $eqLogic->save();
            }
        }
        try {
            log::add('plugin', 'debug', 'Recherche de ' . dirname(__FILE__) . '/../../plugins/' . $this->getId() . '/plugin_info/install.php');
            if (file_exists(dirname(__FILE__) . '/../../plugins/' . $this->getId() . '/plugin_info/install.php')) {
                log::add('plugin', 'debug', 'Fichier d\'installation trouvé pour  : ' . $this->getId());
                require dirname(__FILE__) . '/../../plugins/' . $this->getId() . '/plugin_info/install.php';
                ob_start();
                if ($_state == 1) {
                    if ($alreadyActive == 1) {
                        $function = $this->getId() . '_update';
                        if (function_exists($this->getId() . '_update')) {
                            $function();
                        }
                    } else {
                        $function = $this->getId() . '_install';
                        if (function_exists($function)) {
                            $function();
                        }
                    }
                } else {
                    if ($alreadyActive == 1) {
                        $function = $this->getId() . '_remove';
                        if (function_exists($this->getId() . '_remove')) {
                            $function();
                        }
                    }
                }
                $out = ob_get_clean();
                if (trim($out) != '') {
                    log::add($this->getId(), 'info', "Installation/remove/update result : " . $out);
                }
            }
        } catch (Exception $e) {
            config::save('active', $alreadyActive, $this->getId());
            log::add('plugin', 'error', $e->getMessage());
            throw $e;
        }
        if ($_state == 0) {
            config::save('active', $_state, $this->getId());
        }
        if ($alreadyActive == 0) {
            $this->start();
        }
        return true;
    }

    public function status() {
        return market::getInfo($this->getId());
    }

    public function launch($_function) {
        if ($_function == '') {
            throw new Exception('La fonction à lancer ne peut être vide');
        }
        if (!class_exists($this->getId()) || !method_exists($this->getId(), $_function)) {
            throw new Exception('Il n\'existe aucune méthode : ' . $this->getId() . '::' . $_function . '()');
        }
        $cmd = 'php ' . dirname(__FILE__) . '/../../core/php/jeePlugin.php ';
        $cmd.= ' plugin_id=' . $this->getId();
        $cmd.= ' function=' . $_function;
        if (jeedom::checkOngoingThread($cmd) > 0) {
            return true;
        }
        exec($cmd . ' >> /dev/null 2>&1 &');
        return true;
    }

    public function getTranslation($_language) {
        $dir = dirname(__FILE__) . '/../../plugins/' . $this->getId() . '/core/i18n';
        if (!file_exists($dir)) {
            mkdir($dir, 0775, true);
        }
        if (file_exists($dir . '/' . $_language . '.json')) {
            $return = file_get_contents($dir . '/' . $_language . '.json');
            if (is_json($return)) {
                return json_decode($return, true);
            } else {
                return array();
            }
        }
        return array();
    }

    public function saveTranslation($_language, $_translation) {
        $dir = dirname(__FILE__) . '/../../plugins/' . $this->getId() . '/core/i18n';
        if (!file_exists($dir)) {
            mkdir($dir, 0775, true);
        }
        file_put_contents($dir . '/' . $_language . '.json', json_encode($_translation, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    /*     * **********************Getteur Setteur*************************** */

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getDescription() {
        return nl2br($this->description);
    }

    public function getAuthor() {
        return $this->author;
    }

    public function getRequire() {
        return $this->require;
    }

    public function getVersion() {
        return $this->version;
    }

    public function getCategory() {
        return $this->category;
    }

    public function getLicence() {
        return $this->licence;
    }

    public function getFilepath() {
        return $this->filepath;
    }

    public function getInstallation() {
        return nl2br($this->installation);
    }

    public function getIcon() {
        return $this->icon;
    }

    public function getIndex() {
        return $this->index;
    }

    public function getInclude() {
        return $this->include;
    }

    public function getDisplay() {
        return $this->display;
    }

    public function setDisplay($display) {
        $this->display = $display;
    }

    public function getMobile() {
        return $this->mobile;
    }

    public function setMobile($mobile) {
        $this->mobile = $mobile;
    }

    function getAllowRemote() {
        return $this->allowRemote;
    }

    function setAllowRemote($allowRemote) {
        $this->allowRemote = $allowRemote;
    }

}

?>
