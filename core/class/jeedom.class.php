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
global $JEEDOM_INTERNAL_CONFIG;
class jeedom {
	/*     * *************************Attributs****************************** */

	private static $jeedomConfiguration;
	private static $cache = array();

	/*     * ***********************Methode static*************************** */

	public static function minify() {
		$folders = array('/../../desktop/js', '/../../core/js', '/../../mobile/js');
		foreach ($folders as $folder) {
			foreach (ls(__DIR__ . $folder, '*.jeemin.js') as $file) {
				unlink(__DIR__ . $folder . '/' . $file);
			}
			foreach (ls(__DIR__ . $folder, '*.js') as $file) {
				$path = __DIR__ . $folder . '/' . $file;
				$md5 = md5_file($path);
				$path_min =	__DIR__ . $folder . '/' . $md5 . '.' . translate::getLanguage() . '.jeemin.js';
				$tmp = '/tmp/jeedom/' . $file;
				file_put_contents($tmp, translate::exec(file_get_contents($path), $folder . $file, true));
				exec('python -m jsmin ' . $tmp . ' > ' . $path_min);
				unlink($tmp);
			}
		}
	}

	public static function getThemeConfig() {
		$key = array(
			'jeedom_theme_main',
			'jeedom_theme_alternate',
			'product_name',
			'product_icon',
			'product_icon_apple',
			'product_image',
			'product_synthese_image',
			'product_interface_image',
			'mbState',
			'enableCustomCss',
			'mobile_theme_color',
			'mobile_theme_color_night',
			'theme_start_day_hour',
			'theme_end_day_hour',
			'theme_changeAccordingTime',
			'mobile_theme_useAmbientLight',
			'showBackgroundImg',
			'widget::step::width',
			'widget::step::height',
			'widget::margin',
			'widget::shadow',
			'interface::advance::enable',
			'interface::advance::coloredIcons',
			'interface::advance::coloredcats',
			'logo_light',
			'logo_dark',
			'logo_mobile_light',
			'logo_mobile_dark',
			'css::objectBackgroundBlur',
			'theme_displayAsTable',
			'interface::toast::position',
			'interface::toast::duration',
			'interface::background::dashboard',
			'interface::background::analysis',
			'interface::background::tools',
			'interface::background::opacitylight',
			'interface::background::opacitydark',
			'interface::mobile::onecolumn',
			'css::background-opacity',
			'css::border-radius'
		);

		$return = config::byKeys($key);
		$return['current_desktop_theme'] = $return['jeedom_theme_main'];
		$return['current_mobile_theme'] = $return['mobile_theme_color'];
		if ($return['theme_changeAccordingTime'] == 1 && (date('Gi') < intval(str_replace(':', '', $return['theme_start_day_hour'])) || date('Gi') > intval(str_replace(':', '', $return['theme_end_day_hour'])))) {
			$return['current_desktop_theme'] = $return['jeedom_theme_alternate'];
			$return['current_mobile_theme'] = $return['mobile_theme_color_night'];
		}

		$css_convert = array();
		$return['css'] = array();
		if ($return['interface::advance::enable'] == 1) {
			$css_convert['css::background-opacity'] = '--opacity';
			$css_convert['css::border-radius'] = '--border-radius';
		}
		$css_convert['css::objectBackgroundBlur'] = '--objectBackgroundBlur';

		$css = config::byKeys(array_keys($css_convert));
		foreach ($css as $key => $value) {
			if ($value == '') {
				continue;
			}
			if (isset($css_convert[$key])) {
				$return['css'][$css_convert[$key]] = $value;
			}
		}
		if (count($return['css']) > 0) {
			foreach ($return['css'] as $key => &$value) {
				switch ($key) {
					case '--border-radius':
						if ($value == '') {
							$value = 0;
						} else if ($value > 1) {
							$value = 1;
						}
						$value .= 'rem';
						break;
					case '--objectBackgroundBlur':
						if ($value == '') {
							$value = 0;
						}
						$value .= 'px';
						break;
				}
			}
		}
		return $return;
	}

	public static function addRemoveHistory($_data) {
		try {
			$remove_history = array();
			if (file_exists(__DIR__ . '/../../data/remove_history.json')) {
				$remove_history = json_decode(file_get_contents(__DIR__ . '/../../data/remove_history.json'), true);
			}
			$remove_history[] = $_data;
			$remove_history = array_slice($remove_history, -200, 200);
			file_put_contents(__DIR__ . '/../../data/remove_history.json', json_encode($remove_history));
		} catch (Exception $e) {
		}
	}

	public static function deadCmd() {
		global $JEEDOM_INTERNAL_CONFIG;
		$return = array();
		$cmd = config::byKey('interact::warnme::defaultreturncmd', 'core', '');
		if ($cmd != '') {
			if (!cmd::byId(str_replace('#', '', $cmd))) {
				$return[] = array('detail' => __('Administration', __FILE__), 'help' => __('Commande retour interactions', __FILE__), 'who' => $cmd);
			}
		}
		foreach ($JEEDOM_INTERNAL_CONFIG['alerts'] as $level => $value) {
			$cmds = config::byKey('alert::' . $level . 'Cmd', 'core', '');
			preg_match_all("/#([0-9]*)#/", $cmds, $matches);
			foreach ($matches[1] as $cmd_id) {
				if (!cmd::byId($cmd_id)) {
					$return[] = array('detail' => __('Administration', __FILE__), 'help' => __('Commande sur', __FILE__) . ' ' . $value['name'], 'who' => '#' . $cmd_id . '#');
				}
			}
		}
		return $return;
	}

	public static function health() {
		$return = array();
		// Hardware
		$return[] = array(
			'name' => __('Matériel', __FILE__),
			'state' => true,
			'result' => ucfirst(jeedom::getHardwareName()),
			'comment' => __('Type de matériel utilisé', __FILE__),
			'key' => 'hardware'
		);

		// Updates
		$nbNeedUpdate = update::nbNeedUpdate();
		$updateState = true;
		$coreNeedUpdate = false;
		if ($nbNeedUpdate > 0) {
			$coreUpdate = update::byTypeAndLogicalId('core', 'jeedom');
			$coreNeedUpdate = is_object($coreUpdate) && $coreUpdate->getStatus() == 'update';
			if ($coreNeedUpdate) {
				$updateState = false;
			} else {
				$updateState = 2;
			}
		}
		$return[] = array(
			'name' => __('Mises à jour', __FILE__),
			'state' => $updateState,
			'result' => ($updateState === true) ? 'OK' : $nbNeedUpdate,
			'comment' => ($updateState === true) ? __('Aucune mise à jour en attente', __FILE__) : __('Nombre de mises à jour en attente', __FILE__),
			'key' => 'uptodate'
		);

		// Start date
		$product_name = config::byKey('product_name');
		$isStarted = self::isStarted();
		$return[] = array(
			'name' => __('Démarrage', __FILE__),
			'state' => $isStarted,
			'result' => ($isStarted) ? file_get_contents(self::getTmpFolder() . '/started') : 'NOK',
			'comment' => ($isStarted) ? sprintf(__('Date de démarrage de %s', __FILE__), $product_name) : sprintf(__('%s est en cours de démarrage, patienter quelques minutes puis rafraîchir la page', __FILE__), $product_name),
			'key' => 'isStarted'
		);

		// Crons
		$cronState = config::byKey('enableCron', 'core', 0, true) == 1;
		$return[] = array(
			'name' => __('Moteur de tâches', __FILE__),
			'state' => $cronState,
			'result' => ($cronState) ? 'OK' : 'NOK',
			'comment' => ($cronState) ? __('Le moteur de tâches est actif', __FILE__) : __('Activer le système cron (Réglages > Système > Moteur de tâches)', __FILE__),
			'key' => 'cron::enable'
		);

		// System date
		$dateState = self::isDateOk();
		$lastKnowDate = cache::byKey('hour')->getValue(0);
		if ($lastKnowDate == 0) {
			$dateState = 2;
			$lastKnowDate = '-';
			$dateComment = __("Aucune heure enregistrée, patienter 1 heure maximum", __FILE__);
		} else {
			$lastKnowDate = gmdate('Y-m-d H:i:s', $lastKnowDate);
			$dateComment = ($dateState) ? __('Date actuelle (dernière heure enregistrée)', __FILE__) : __('Remettre à zéro la dernière date connue (Réglages > Système > Configuration, onglet Général)', __FILE__);
		}
		$return[] = array(
			'name' => __('Date système', __FILE__),
			'state' => $dateState,
			'result' => date('Y-m-d H:i:s') . ' (' . $lastKnowDate . ')',
			'comment' => $dateComment,
			'key' => 'hour'
		);

		// Scenarios
		$scenarioState = config::byKey('enableScenario', 'core', 0, true) == 1;
		$scenarioComment = __('Le moteur de scénarios est actif', __FILE__);
		if (!$scenarioState) {
			$nbScenario = DB::Prepare('SELECT COUNT(*) as nb FROM scenario', array(), DB::FETCH_TYPE_ROW)['nb'];
			if ($nbScenario > 0) {
				$scenarioComment = __('Activer le moteur de scénarios (Outils > Scénarios)', __FILE__);
			} else {
				$scenarioState = 2;
				$scenarioComment = __('Le moteur de scénarios est désactivé sans scénarios existants (Outils > Scénarios)', __FILE__);
			}
		}
		$return[] = array(
			'name' => __('Moteur de scénarios', __FILE__),
			'state' => $scenarioState,
			'result' => ($scenarioState === true) ? 'OK' : 'NOK',
			'comment' => $scenarioComment,
			'key' => 'scenario::enable'
		);

		// Core version
		$coreVersionState = ($coreNeedUpdate) ? 2 : true;
		if (config::byKey('mbState') == 0) {
			$coreVersionName = __('Version core Jeedom', __FILE__);
			$coreVersionComment = __('Version du core Jeedom', __FILE__);
		} else {
			$coreVersionName = __('Version core', __FILE__);
			$coreVersionComment = __('Version du core', __FILE__);
		}
		$return[] = array(
			'name' => $coreVersionName,
			'state' => $coreVersionState,
			'result' => self::version(),
			'comment' => ($coreVersionState === true) ? $coreVersionComment : __('Une mise à jour du core est disponible', __FILE__),
			'key' => 'jeedom::version'
		);

		// Sudo rights
		$sudoState = self::isCapable('sudo', true);
		$return[] = array(
			'name' => __('Droits sudo', __FILE__),
			'state' => $sudoState ?: 2,
			'result' => ($sudoState) ? 'OK' : 'NOK',
			'comment' => ($sudoState) ? __("Droits d'administration des dossiers et fichiers", __FILE__) : __('Ajouter "www-data ALL=(ALL) NOPASSWD: ALL" à la fin du fichier sudoers', __FILE__),
			'key' => 'sudo::right'
		);

		// OS version
		$distrib = system::getDistrib();
		$osVersion = system::getOsVersion();
		$osState = true;
		if ($distrib != 'debian') {
			$osState = false;
		} else {
			$majorVersion = intval($osVersion);
			if ($majorVersion > 0) {
				if ($majorVersion < config::byKey('os::min') || $majorVersion > config::byKey('os::max')) {
					$osState = false;
				}
			} else if (strpos($osVersion, 'bookworm') === false && strpos($osVersion, 'trixie') === false) {
				$osState = false;
			}
		}
		$return[] = array(
			'name' => __('Version système', __FILE__),
			'state' => $osState,
			'result' => ucfirst($distrib) . ' ' . $osVersion,
			'comment' => ($osState) ? __("Version du système", __FILE__) : __("Ce système n'est pas officiellement supporté (voir la documentation sur la compatibilité logicielle)", __FILE__),
			'key' => 'os::version'
		);

		// Cache persist
		$cacheState = cache::isPersistOk();
		$return[] = array(
			'name' => __('Persistance du cache', __FILE__),
			'state' => $cacheState,
			'result' => ($cacheState) ? 'OK' : 'NOK',
			'comment' => ($cacheState) ? __('Persistance du cache après redémarrage', __FILE__) : __('Certaines informations peuvent être perdues après redémarrage, attendre 30 minutes maximum ou démarrer la tâche cache::persist manuellement (Réglages > Système > Moteur de tâches)', __FILE__),
			'key' => 'cache::persist'
		);

		// Kernel version
		$hostname = php_uname('n');
		$release = php_uname('r');
		$machine = php_uname('m');
		$buildDate = preg_replace(
			array('/^#.*(\d{4}-\d{2}-\d{2}[\dTZ:]*)\)?.*$/', '/^#\d+ SMP\S*\s*/'),
			array('$1', ''),
			php_uname('v')
		);
		$kernelState = false;
		if (in_array($machine, array('x86_64', 'aarch64'))) {
			$kernelState = version_compare(strtok($release, '-'), '4.4', '>=') ? true : 2;
		} elseif ($machine == 'armv7l') {
			$kernelState = 2;
		}
		$return[] = array(
			'name' => __('Version noyau', __FILE__),
			'state' => $kernelState,
			'result' => $hostname . ' ' . $release . ' ' . $machine . ' (' . $buildDate . ')',
			'comment' => ($kernelState === true) ? __('Version du noyau Linux', __FILE__) : __("Ce noyau Linux n'est pas officiellement supporté (voir la documentation sur la compatibilité matérielle)", __FILE__),
			'key' => 'kernel::version'
		);

		// Apache private tmp
		$apacheTmpState = true;
		if (jeedom::getHardwareName() != 'docker') {
			$apacheTmpState = trim(shell_exec('systemctl show apache2 | grep  PrivateTmp | grep yes | wc -l')) == 0;
		}
		$return[] = array(
			'name' => __('Dossier temporaire Apache', __FILE__),
			'state' => $apacheTmpState,
			'result' => ($apacheTmpState) ? 'OK' : 'NOK',
			'comment' => ($apacheTmpState) ? __('Accès au dossier temporaire Apache', __FILE__) : __('Désactiver Apache PrivateTmp', __FILE__),
			'key' => 'apache2::privateTmp'
		);

		// Database version
		$dbVersion = DB::Prepare('select version()', array(), DB::FETCH_TYPE_ROW);
		$return[] = array(
			'name' => __('Version base de données', __FILE__),
			'state' => true,
			'result' => $dbVersion['version()'],
			'comment' => __('Version de la base de données', __FILE__),
			'key' => 'database::version'
		);

		// Network internal
		$internalState = network::test('internal');
		$return[] = array(
			'name' => __('Configuration accès interne', __FILE__),
			'state' => $internalState,
			'result' => ($internalState) ? 'OK' : 'NOK',
			'comment' => ($internalState) ? __("Configuration de l'accès réseau interne", __FILE__) : __("Configurer l'accès réseau interne (Réglages > Système > Configuration, onglet Réseaux)", __FILE__),
			'key' => 'network::internal'
		);

		// PHP version
		$phpVersion = phpversion();
		$phpState = true;
		if (version_compare($phpVersion, '7.4', '<')) {
			$phpState = false;
		} else if (version_compare($phpVersion, '8', '<')) {
			$phpState = 2;
		}
		$return[] = array(
			'name' => __('Version PHP', __FILE__),
			'state' => $phpState,
			'result' => $phpVersion,
			'comment' => ($phpState === true) ? __('Version de PHP', __FILE__) : __("Cette version de PHP n'est pas officiellement supportée (voir la documentation sur la compatibilité logicielle)", __FILE__),
			'key' => 'php::version'
		);

		// Network external
		$externalState = network::test('external');
		$externalComment = __("Configuration de l'accès réseau externe", __FILE__);
		if (!$externalState) {
			if (config::byKey('market::allowDNS') == 1 || config::byKey('externalAddr', 'core', '') != '') {
				$externalComment = __("Vérifier la configuration de l'accès réseau externe (Réglages > Système > Configuration, onglet Réseaux)", __FILE__);
			} else {
				$externalState = 2;
				$externalComment = __("Configurer l'accès réseau externe (Réglages > Système > Configuration, onglet Réseaux)", __FILE__);
			}
		}
		$return[] = array(
			'name' => __('Configuration accès externe', __FILE__),
			'state' => $externalState,
			'result' => ($externalState === true) ? 'OK' : 'NOK',
			'comment' => $externalComment,
			'key' => 'network::external'
		);

		// Python version
		$pythonState = false;
		if (shell_exec('which python3') != '') {
			$pythonState = true;
			$pythonVersion = trim(shell_exec("python3 --version | sed 's/^Python//'"));
		} else if (shell_exec('which python') != '') {
			$pythonVersion = trim(shell_exec("python --version | sed 's/^Python//'"));
		}
		$return[] = array(
			'name' => __('Version Python', __FILE__),
			'state' => $pythonState,
			'result' => $pythonVersion ?? 'NOK',
			'comment' => ($pythonState) ? __('Version de Python', __FILE__) : __('Python 3 est nécessaire pour certains plugins, vérifier les packages système (Réglages > Système > Configuration, onglet OS/DB)', __FILE__),
			'key' => 'python::version'
		);

		// Sufficient memory
		$killedProcesses = trim(shell_exec('sudo dmesg | grep oom-killer | grep -v deprecated | wc -l'));
		$killedState = $killedProcesses == 0;
		$return[] = array(
			'name' => __('Mémoire suffisante', __FILE__),
			'state' => $killedState,
			'result' => ($killedState) ? 'OK' : $killedProcesses,
			'comment' => ($killedState) ? __("Mémoire suffisante pour l'ensemble des processus", __FILE__) : __('Le noyau a dû arrêter des processus par manque de mémoire, revoir les plugins/scénarios si le problème persiste après redémarrage', __FILE__),
			'key' => 'memory::killed'
		);

		// Node.js version
		$nodeVersion = trim(shell_exec("node --version | tr -d 'v'"));
		$installScript = file_get_contents(__DIR__ . '/../../resources/install_nodejs.sh');
		preg_match("/minVer='([^']+)'/", $installScript, $nodeMinVer);
		preg_match("/installVer='([^']+)'/", $installScript, $nodeInstallVer);
		$nodeState = true;
		$nodeComment = __('Version de Node.js', __FILE__);
		if (version_compare($nodeVersion, $nodeMinVer[1], '<')) {
			$nodeState = false;
			$nodeComment = sprintf(__('Node.js %s est nécessaire pour certains plugins, lancer la vérification générale (Réglages > Système > Configuration, onglet OS/DB)', __FILE__), $nodeInstallVer[1]);
		} else if (version_compare(intval($nodeVersion), $nodeInstallVer[1], '>')) {
			$nodeState = 2;
			$nodeComment = __('Cette version de Node.js est plus récente que celle attendue', __FILE__) . ' (' . $nodeInstallVer[1] . ')';
		}
		$return[] = array(
			'name' => __('Version Node.js', __FILE__),
			'state' => $nodeState,
			'result' => $nodeVersion,
			'comment' => $nodeComment,
			'key' => 'node::version'
		);

		// I/O errors
		$ioCount = trim(shell_exec('sudo dmesg | grep "CRC error" | grep "mmcblk" | grep "card status" | wc -l'));
		if (!is_numeric($ioCount)) {
			$ioCount = 0;
		}
		$ioCount2 = trim(shell_exec('sudo dmesg | grep "I/O error" | wc -l'));
		if (is_numeric($ioCount2)) {
			$ioCount += $ioCount2;
		}
		$ioState = $ioCount == 0;
		$return[] = array(
			'name' => __('Erreurs disque', __FILE__),
			'state' => ($ioState),
			'result' => ($ioState) ? 'OK' : $ioCount,
			'comment' => ($ioState) ? __("Erreurs d'écriture sur le support de stockage", __FILE__) : __("Des erreurs d'écriture ont été détectées sur le support de stockage, possiblement un problème matériel (SD/eMMC, disque, câblage) ou d'alimentation", __FILE__),
			'key' => 'io_error'
		);

		// System load
		$systemLoads = sys_getloadavg();
		$nproc = trim(shell_exec('nproc'));
		$loadState = true;
		if ($systemLoads[2] > $nproc) {
			$loadState = false;
		} else if ($systemLoads[1] > $nproc) {
			$loadState = 2;
		}
		$return[] = array(
			'name' => __('Charge système', __FILE__),
			'state' => $loadState,
			'result' => round($systemLoads[0], 2) . ' - ' . round($systemLoads[1], 2) . ' - ' . round($systemLoads[2], 2),
			'comment' => __('Charge du système (1 minute - 5 minutes - 15 minutes)', __FILE__),
			'key' => 'load'
		);

		// Apache processes
		$return[] = array(
			'name' => __('Processus Apache', __FILE__),
			'state' => true,
			'result' => count(system::ps('apache2')),
			'comment' => __("Nombre de processus Apache en cours d'exécution", __FILE__),
			'key' => 'apache'
		);

		// Available memory
		$systemMemInfo = getSystemMemInfo();
		$availableMem = cmd::autoValueArray($systemMemInfo['MemAvailable'] * 1024, 1, 'io');
		$totalMem = cmd::autoValueArray($systemMemInfo['MemTotal'] * 1024, 1, 'io');
		$percentMem = round(($systemMemInfo['MemAvailable'] / $systemMemInfo['MemTotal']) * 100);
		$memState = true;
		if ($percentMem < 15) {
			$memState = false;
		} else if ($percentMem < 20) {
			$memState = 2;
		}
		$return[] = array(
			'name' => __('Mémoire disponible', __FILE__),
			'state' => $memState,
			'result' => $percentMem . '% (' . $availableMem[0] . $availableMem[1] . '/' . $totalMem[0] . $totalMem[1] . ')',
			'comment' => __("Pourcentage de mémoire disponible (libre/total)", __FILE__),
			'key' => 'memory::free'
		);

		// Database connections
		$nb_active_connection = DB::Prepare('show status where `variable_name` = \'Threads_connected\'', array(), DB::FETCH_TYPE_ROW);
		$max_used_connection = DB::Prepare('SHOW STATUS WHERE `variable_name` = \'Max_used_connections\';', array(), DB::FETCH_TYPE_ROW);
		$allow_connection = DB::Prepare('SHOW VARIABLES LIKE \'max_connections\'', array(), DB::FETCH_TYPE_ROW);
		$dbConnState = true;
		if ($max_used_connection['Value'] >= ($allow_connection['Value'] * 0.9)) {
			$dbConnState = false;
		} else if ($max_used_connection['Value'] >= ($allow_connection['Value'] * 0.7)) {
			$dbConnState = 2;
		}
		$return[] = array(
			'name' => __('Connexions base de données', __FILE__),
			'state' => $dbConnState,
			'result' => $nb_active_connection['Value'] . '/' . $max_used_connection['Value'] . '/' . $allow_connection['Value'],
			'comment' => ($dbConnState === true) ? __('Nombre de connexions à la base de données (actives/maximales/autorisées)', __FILE__) : __('Le nombre maximal de connexions à la base de données approche la limite autorisée (actives/maximales/autorisées)', __FILE__),
			'key' => 'database::connexion'
		);

		// Available swap
		$swapState = false;
		$swapResult = 'NOK';
		if ($systemMemInfo['SwapTotal'] > 0) {
			$availableSwap = cmd::autoValueArray($systemMemInfo['SwapFree'] * 1024, 1, 'io');
			$totalSwap = cmd::autoValueArray($systemMemInfo['SwapTotal'] * 1024, 1, 'io');
			$percentSwap = round(($systemMemInfo['SwapFree'] / $systemMemInfo['SwapTotal']) * 100);
			$swapResult = $percentSwap . '% (' . $availableSwap[0] . $availableSwap[1] . '/' . $totalSwap[0] . $totalSwap[1] . ')';
			$swapState = true;
			if ($percentSwap < 15) {
				$swapState = false;
			} else if ($percentSwap < 20) {
				$swapState = 2;
			}
		}
		$return[] = array(
			'name' => __('Swap disponible', __FILE__),
			'state' => $swapState,
			'result' => $swapResult,
			'comment' => __("Pourcentage de swap disponible (libre/total)", __FILE__),
			'key' => 'swap'
		);

		// Swappiness
		$swappiness = trim(shell_exec('sudo cat /proc/sys/vm/swappiness'));
		$swappinessState = 2;
		if ($swappiness <= 20 || $systemMemInfo['MemTotal'] >= (1024 * 1024)) {
			$swappinessState = true;
		}
		$return[] = array(
			'name' => __('Swappiness', __FILE__),
			'state' => $swappinessState,
			'result' => $swappiness . '%',
			'comment' => ($swappinessState === true) ? __('Pourcentage de mémoire utilisée avant recours au swap', __FILE__) : __('Pour des performances optimales le swappiness ne doit pas dépasser 20% avec 1Go ou moins de mémoire', __FILE__),
			'key' => 'swapiness'
		);

		// Available space
		$freeDisk = disk_free_space('/');
		$totalDisk = disk_total_space('/');
		$availableSpace = cmd::autoValueArray($freeDisk, 1, 'io');
		$totalSpace = cmd::autoValueArray($totalDisk, 1, 'io');
		$percentSpace = round(($freeDisk / $totalDisk) * 100);
		$return[] = array(
			'name' => __('Espace disque disponible', __FILE__),
			'state' => $percentSpace > 10,
			'result' => $percentSpace . '% (' . $availableSpace[0] . $availableSpace[1] . '/' . $totalSpace[0] . $totalSpace[1] . ')',
			'comment' => __("Pourcentage d'espace disque disponible (libre/total)", __FILE__),
			'key' => 'space::root'
		);

		// Available tmp space
		$tmpSpace = self::checkSpaceLeft(self::getTmpFolder());
		$tmpSpaceState = $tmpSpace > 10;
		$return[] = array(
			'name' => __('Espace temporaire disponible', __FILE__),
			'state' => $tmpSpaceState,
			'result' => $tmpSpace . '%',
			'comment' => ($tmpSpaceState) ? __("Pourcentage d'espace temporaire disponible", __FILE__) : __('Espace temporaire faible, désactiver les plugins un à un pour identifier le responsable si le problème persiste après redémarrage', __FILE__),
			'key' => 'space::tmp'
		);

		// Database size
		$dbSize = DB::Prepare('SELECT SUM(data_length + index_length) as size FROM information_schema.tables WHERE table_schema = \'jeedom\' GROUP BY table_schema;', array(), DB::FETCH_TYPE_ROW);
		$dbSize = cmd::autoValueArray($dbSize['size'], 2, 'io');
		$return[] = array(
			'name' => __('Taille base de données', __FILE__),
			'state' => true,
			'result' => $dbSize[0] . $dbSize[1],
			'comment' => __('Taille de la base de données', __FILE__),
			'key' => 'database::size'
		);

		return $return;
	}

	public static function sick() {
		$cmd = __DIR__ . '/../../sick.php';
		$cmd .= ' >> ' . log::getPathToLog('sick') . ' 2>&1';
		system::php($cmd);
	}

	public static function getApiKey(string $_plugin = 'core', string $_mode = 'enable') {
		if ($_plugin == 'core') {
			if (config::byKey('api') == '') {
				config::save('api', config::genKey());
				config::save('api::api::mode', $_mode, 'core');
			}
			return config::byKey('api');
		}
		if ($_plugin == 'apipro') {
			if (config::byKey('apipro') == '') {
				config::save('apipro', config::genKey());
				config::save('api::apipro::mode', $_mode, 'core');
			}
			return config::byKey('apipro');
		}
		if ($_plugin == 'apitts') {
			if (config::byKey('apitts') == '') {
				config::save('apitts', config::genKey());
				config::save('api::apitts::mode', $_mode, 'core');
			}
			return config::byKey('apitts');
		}
		if ($_plugin == 'apimarket') {
			if (config::byKey('apimarket') == '') {
				config::save('apimarket', config::genKey());
				config::save('api::apimarket::mode', $_mode, 'core');
			}
			return config::byKey('apimarket');
		}
		if (config::byKey('api', $_plugin) == '') {
			if (!plugin::isInstalled($_plugin)) {
				return '';
			}
			config::save('api', config::genKey(), $_plugin);
			config::save('api::' . $_plugin . '::mode', $_mode, 'core');
		}
		if (config::byKey('api::' . $_plugin . '::mode', 'core', 'enable') == 'disable' && $_mode != 'disable') {
			config::save('api::' . $_plugin . '::mode', $_mode, 'core');
		}
		return config::byKey('api', $_plugin);
	}

	public static function apiModeResult(string $_mode = 'enable') {
		if ($_mode == 'localhost' && jeedom::getHardwareName() == 'docker') {
			$_mode = 'whiteip';
		}
		switch ($_mode) {
			case 'disable':
				return false;
			case 'whiteip':
				$ip = getClientIp();
				$find = false;
				$whiteIps = explode(';', config::byKey('security::whiteips'));
				if (config::byKey('security::whiteips') != '' && count($whiteIps) > 0) {
					foreach ($whiteIps as $whiteip) {
						if (netMatch($whiteip, $ip)) {
							$find = true;
						}
					}
					if (!$find) {
						return false;
					}
				}
				break;
			case 'localhost':
				if (getClientIp() != '127.0.0.1') {
					return false;
				}
				break;
		}
		return true;
	}

	public static function apiAccess(string $_apikey = '', string $_plugin = 'core') {
		if (trim($_apikey) == '' || strlen($_apikey) < 16) {
			return false;
		}
		$user = user::byHash($_apikey);
		if (is_object($user)) {
			if ($user->getEnable() == 0 || !self::apiModeResult($user->getOptions('api::mode', 'enable'))) {
				return false;
			}
			if ($user->getOptions('localOnly', 0) == 1 && !self::apiModeResult('whiteip')) {
				return false;
			}
			global $_USER_GLOBAL;
			$_USER_GLOBAL = $user;
			log::add('connection', 'info', __('Connexion par API de l\'utilisateur :', __FILE__) . ' ' . $user->getLogin());
			return true;
		}
		if (!self::apiModeResult(config::byKey('api::' . $_plugin . '::mode', 'core', 'enable'))) {
			return false;
		}
		$apikey = self::getApiKey($_plugin);
		if (trim($apikey) != '' && $apikey === $_apikey) {
			/** @var bool $_RESTRICTED */
			global $_RESTRICTED;
			$_RESTRICTED = config::byKey('api::' . $_plugin . '::restricted', 'core', false);
			return true;
		}
		return false;
	}

	public static function isOk() {
		if (!self::isStarted()) {
			return false;
		}
		if (!self::isDateOk()) {
			return false;
		}
		if (config::byKey('enableScenario') == 0 && count(scenario::all()) > 0) {
			return false;
		}
		if (!self::isCapable('sudo')) {
			return false;
		}
		if (config::byKey('enableCron', 'core', 1, true) == 0) {
			return false;
		}
		return true;
	}

	/*************************************************USB********************************************************/
	public static function getUsbDetails(string $_usb = '', array $_usbMapping = []): array {
		$vendor = '';
		$model = '';
		$serial_by_id = '';
		foreach (explode("\n", shell_exec('udevadm info --name=/dev/' . $_usb . ' --query=all')) as $line) {
			if (strpos($line, 'E: ID_USB_MODEL=') !== false) {
				$model = trim(str_replace(array('E: ID_USB_MODEL=', '"'), '', $line));
			}
			if (strpos($line, 'E: ID_MODEL=') !== false) {
				$model = trim(str_replace(array('E: ID_MODEL=', '"'), '', $line));
			}
			if (strpos($line, 'E: ID_USB_VENDOR=') !== false) {
				$vendor = trim(str_replace(array('E: ID_USB_VENDOR=', '"'), '', $line));
			}
			if (strpos($line, 'E: ID_VENDOR=') !== false) {
				$vendor = trim(str_replace(array('E: ID_VENDOR=', '"'), '', $line));
			}
			if (strpos($line, 'E: DEVLINKS=') !== false) {
				$serial_by_ids = explode(' ', trim(str_replace(array('E: DEVLINKS=', '"'), '', $line)));
				foreach ($serial_by_ids as $serial_links) {
					if (strpos($serial_links, '/serial/by-id') !== false) {
						$serial_by_id = trim($serial_links);
						break;
					}
				}
				if ($serial_by_id == '') {
					foreach ($serial_by_ids as $serial_links) {
						if (strpos($serial_links, '/serial/by-path') !== false) {
							$serial_by_id = trim($serial_links);
							break;
						}
					}
				}
			}
		}
		$path = '/dev/' . $_usb;
		if ($serial_by_id != '') {
			$path = $serial_by_id;
		}
		if ($vendor == '' && $model == '') {
			$_usbMapping[$path] = $path;
		} else {
			$name = trim($vendor . ' ' . $model);
			$number = 2;
			while (isset($_usbMapping[$name])) {
				$name = trim($vendor . ' ' . $model . ' ' . $number);
				$number++;
			}
			$_usbMapping[$name] = $path;
		}
		return $_usbMapping;
	}

	public static function getUsbLegacy(array $_usbMapping = []): array {
		foreach (ls('/dev/', 'ttyUSB*') as $usb) {
			$_usbMapping['/dev/' . $usb] = '/dev/' . $usb;
		}
		foreach (ls('/dev/', 'ttyACM*') as $value) {
			$_usbMapping['/dev/' . $value] = '/dev/' . $value;
		}
		return $_usbMapping;
	}

	public static function getUsbMapping(string $_name = '', bool $_getGPIO = false) {
		$cache = cache::byKey('jeedom::usbMapping');
		if (!is_json($cache->getValue()) || $_name == '') {
			$usbMapping = array();
			foreach (ls('/dev/', 'ttyUSB*') as $usb) {
				$usbMapping = self::getUsbDetails($usb, $usbMapping);
			}
			foreach (ls('/dev/', 'ttyACM*') as $value) {
				$usbMapping = self::getUsbDetails($value, $usbMapping);
			}
			$usbMapping = self::getUsbLegacy($usbMapping);
			if ($_getGPIO) {
				if (file_exists('/dev/ttyLuna-Zigbee')) {
					$usbMapping['Jeedom Luna Zigbee'] = '/dev/ttyLuna-Zigbee';
				}
				if (file_exists('/dev/ttyS0')) {
					$usbMapping['Cubiboard'] = '/dev/ttyS0';
				}
				if (file_exists('/dev/ttyS1')) {
					$usbMapping['Jeedom Luna Zwave'] = '/dev/ttyS1';
					$usbMapping['Odroid (old)'] = '/dev/ttyS1';
				}
				if (file_exists('/dev/ttyS2')) {
					$usbMapping['Jeedom Atlas'] = '/dev/ttyS2';
					$usbMapping['Rock Pi'] = '/dev/ttyS2';
				}
				if (file_exists('/dev/ttyS3')) {
					$usbMapping['Orange Pi'] = '/dev/ttyS3';
				}
				if (file_exists('/dev/ttymxc0')) {
					$usbMapping['Jeedom board'] = '/dev/ttymxc0';
				}
				if (file_exists('/dev/ttyAMA0')) {
					$usbMapping['Raspberry Pi'] = '/dev/ttyAMA0';
				}
				if (file_exists('/dev/ttyAML1')) {
					$usbMapping['Jeedom Smart'] = '/dev/ttyAML1';
					$usbMapping['Odroid'] = '/dev/ttyAML1';
				}
				if (file_exists('/dev/S2')) {
					$usbMapping['Banana Pi'] = '/dev/S2';
				}
				foreach (ls('/dev/', 'ttyAMA*') as $value) {
					$usbMapping['/dev/' . $value] = '/dev/' . $value;
				}
				foreach (ls('/dev/', 'ttyAML*') as $value) {
					$usbMapping['/dev/' . $value] = '/dev/' . $value;
				}
			}
			cache::set('jeedom::usbMapping', json_encode($usbMapping));
		} else {
			$usbMapping = json_decode($cache->getValue(), true);
		}
		if ($_name != '') {
			if (isset($usbMapping[$_name])) {
				return $usbMapping[$_name];
			}
			$usbMapping = self::getUsbMapping('', true);
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

	public static function getBluetoothMapping(string $_name = '') {
		$cache = cache::byKey('jeedom::bluetoothMapping');
		if (!is_json($cache->getValue()) || $_name == '') {
			$bluetoothMapping = array();
			foreach (explode("\n", shell_exec('hcitool dev')) as $line) {
				if (strpos($line, 'hci') === false || trim($line) == '') {
					continue;
				}
				$infos = explode("\t", $line);
				$bluetoothMapping[$infos[2]] = $infos[1];
			}
			cache::set('jeedom::bluetoothMapping', json_encode($bluetoothMapping));
		} else {
			$bluetoothMapping = json_decode($cache->getValue(), true);
		}
		if ($_name != '') {
			if (isset($bluetoothMapping[$_name])) {
				return $bluetoothMapping[$_name];
			}
			$bluetoothMapping = self::getBluetoothMapping('');
			if (isset($bluetoothMapping[$_name])) {
				return $bluetoothMapping[$_name];
			}
			if (file_exists($_name)) {
				return $_name;
			}
			return '';
		}
		return $bluetoothMapping;
	}

	public static function consistency() {
		log::clear('consistency');
		$cmd = __DIR__ . '/../../install/consistency.php';
		$cmd .= ' >> ' . log::getPathToLog('consistency') . ' 2>&1 &';
		system::php($cmd, true);
	}

	/********************************************BACKUP*****************************************************************/

	public static function backup(bool $_background = false) {
		if ($_background) {
			log::clear('backup');
			$cmd = __DIR__ . '/../../install/backup.php';
			$cmd .= ' >> ' . log::getPathToLog('backup') . ' 2>&1 &';
			system::php($cmd, true);
		} else {
			require_once __DIR__ . '/../../install/backup.php';
		}
	}

	public static function listBackup() {
		if (substr(config::byKey('backup::path'), 0, 1) != '/') {
			$backup_dir = __DIR__ . '/../../' . config::byKey('backup::path');
		} else {
			$backup_dir = config::byKey('backup::path');
		}
		$backups = ls($backup_dir, '*.tar.gz', false, array('files', 'quiet', 'datetime_asc'));
		$return = array();
		foreach ($backups as $backup) {
			$return[$backup_dir . '/' . $backup] = $backup . ' (' . sizeFormat(filesize($backup_dir . '/' . $backup)) . ')';
		}
		return $return;
	}

	public static function removeBackup(string $_backup) {
		if (file_exists($_backup)) {
			unlink($_backup);
		} else {
			throw new Exception(__('Impossible de trouver le fichier :', __FILE__) . ' ' . $_backup);
		}
	}

	public static function restore(string $_backup = '', bool $_background = false) {
		if ($_background) {
			log::clear('restore');
			$cmd = __DIR__ . '/../../install/restore.php "backup=' . $_backup . '"';
			$cmd .= ' >> ' . log::getPathToLog('restore') . ' 2>&1 &';
			system::php($cmd, true);
		} else {
			global $BACKUP_FILE;
			$BACKUP_FILE = $_backup;
			require_once __DIR__ . '/../../install/restore.php';
		}
	}

	/****************************UPDATE*****************************************************************/

	public static function update(array $_options = []) {
		log::clear('update');
		$params = '';
		foreach ($_options as $key => $value) {
			$params .= '"' . $key . '"="' . $value . '" ';
		}
		$cmd = __DIR__ . '/../../install/update.php ' . $params;
		$cmd .= ' >> ' . log::getPathToLog('update') . ' 2>&1 &';
		system::php($cmd);
	}

	/****************************CONFIGURATION MANAGEMENT*****************************************************************/

	public static function getConfiguration(string $_key = '', bool $_default = false) {
		global $JEEDOM_INTERNAL_CONFIG;
		if ($_key == '') {
			return $JEEDOM_INTERNAL_CONFIG;
		}
		if (!is_array(self::$jeedomConfiguration)) {
			self::$jeedomConfiguration = array();
		}
		if (!$_default && isset(self::$jeedomConfiguration[$_key])) {
			return self::$jeedomConfiguration[$_key];
		}
		$keys = explode(':', $_key);

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

	private static function checkValueInconfiguration(string $_key, $_value) {
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

	public static function version(bool $_force = false) {
		static $version = null;
		if ($version !== null && !$_force) {
			return $version;
		}
		$path = __DIR__ . '/../config/version';
		$version = file_exists($path) ? trim(file_get_contents($path)) : '';
		return $version;
	}

	/**********************START AND DATE MANAGEMENT*************************************************************/

	public static function stop() {
		echo "Disable all task";
		config::save('enableCron', 0);
		foreach ((cron::all()) as $cron) {
			if ($cron->running()) {
				try {
					$cron->halt();
					echo '.';
				} catch (\Throwable $e) {
					sleep(5);
					$cron->halt();
				}
			}
		}
		echo " OK\n";

		/*         * **********arrêt des crons********************* */

		if (cron::jeeCronRun()) {
			echo "Stop cron master...";
			$pid = cron::getPidFile();
			system::kill($pid);
			echo " OK\n";
		}

		/*         * *********Arrêt des scénarios**************** */

		echo "Disable all scenario";
		config::save('enableScenario', 0);
		foreach ((scenario::all()) as $scenario) {
			try {
				$scenario->stop();
				echo '.';
			} catch (\Throwable $e) {
				sleep(5);
				$scenario->stop();
			}
		}
		echo " OK\n";
	}

	public static function start() {
		try {
			/*             * *********Réactivation des scénarios**************** */
			echo "Enable scenario : ";
			config::save('enableScenario', 1);
			echo "OK\n";
			/*             * *********Réactivation des tâches**************** */
			echo "Enable task : ";
			config::save('enableCron', 1);
			echo "OK\n";
			/*             * *********Check Network Conf**************** */
			echo "Check Network Conf : ";
			network::checkConf('internal');
			echo "OK\n";
		} catch (\Throwable $e) {
			if (!isset($_GET['mode']) || $_GET['mode'] != 'force') {
				throw $e;
			} else {
				echo '***ERROR*** ' . log::exception($e);
			}
		}
	}

	public static function isStarted() {
		return file_exists(self::getTmpFolder() . '/started');
	}

	/**
	 *
	 * @return boolean
	 */
	public static function isDateOk() {
		if (config::byKey('ignoreHourCheck') == 1) {
			return true;
		}
		$cache = cache::byKey('hour');
		$lastKnowDate = $cache->getValue();
		if ($lastKnowDate > strtotime('UTC')) {
			self::forceSyncHour();
			sleep(3);
			if ($lastKnowDate > strtotime('UTC')) {
				return false;
			}
		}
		return true;
	}

	public static function event($_event, $_forceSyncMode = false, $_options = null) {
		scenario::check($_event, $_forceSyncMode, null, null, null, $_options);
	}

	/*****************************************CRON JEEDOM****************************************************************/

	public static function cron5() {
		try {
			foreach ((update::listRepo()) as $name => $repo) {
				$class = 'repo_' . $name;
				if (class_exists($class) && method_exists($class, 'cron5') && config::byKey($name . '::enable') == 1) {
					$class::cron5();
				}
			}
		} catch (\Throwable $e) {
			log::add('jeedom', 'error', log::exception($e));
		}
		try {
			eqLogic::checkAlive();
		} catch (\Throwable $e) {
		}
	}

	public static function cron10() {
		try {
			foreach ((update::listRepo()) as $name => $repo) {
				$class = 'repo_' . $name;
				if (class_exists($class) && method_exists($class, 'cron10') && config::byKey($name . '::enable') == 1) {
					$class::cron10();
				}
			}
		} catch (\Throwable $e) {
			log::add('jeedom', 'error', log::exception($e));
		}
	}

	public static function cron() {
		if (!self::isStarted()) {
			echo date('Y-m-d H:i:s') . ' starting Jeedom';
			log::add('starting', 'debug', __('Démarrage de jeedom', __FILE__));
			try {
				log::add('starting', 'debug', __('Arrêt des crons', __FILE__));
				foreach ((cron::all()) as $cron) {
					if ($cron->running() && $cron->getClass() != 'jeedom' && $cron->getFunction() != 'cron') {
						try {
							$cron->halt();
						} catch (\Throwable $e) {
							log::add('starting', 'error', __('Erreur sur l\'arrêt d\'une tâche cron :', __FILE__) . ' ' . log::exception($e));
						}
					}
				}
			} catch (\Throwable $e) {
				log::add('starting', 'error', __('Erreur sur l\'arrêt des tâches crons :', __FILE__) . ' ' . log::exception($e));
			}

			try {
				log::add('starting', 'debug', __('Restauration du cache', __FILE__));
				cache::restore();
			} catch (\Throwable $e) {
				log::add('starting', 'error', __('Erreur sur la restauration du cache :', __FILE__) . ' ' . log::exception($e));
			}

			try {
				log::add('starting', 'debug', __('Consolidation de l\'historique', __FILE__));
				history::checkCurrentValueAndHistory();
			} catch (\Throwable $e) {
				log::add('starting', 'error', __('Erreur sur la consolidation de l\'historique :', __FILE__) . ' ' . log::exception($e));
			}

			try {
				log::add('starting', 'debug', __('Nettoyage du cache des péripheriques USB', __FILE__));
				$cache = cache::byKey('jeedom::usbMapping');
				$cache->remove();
			} catch (\Throwable $e) {
				log::add('starting', 'error', __('Erreur sur le nettoyage du cache des péripheriques USB :', __FILE__) . ' ' . log::exception($e));
			}

			try {
				log::add('starting', 'debug', __('Nettoyage du cache des péripheriques Bluetooth', __FILE__));
				$cache = cache::byKey('jeedom::bluetoothMapping');
				$cache->remove();
			} catch (\Throwable $e) {
				log::add('starting', 'error', __('Erreur sur le nettoyage du cache des péripheriques Bluetooth :', __FILE__) . ' ' . log::exception($e));
			}

			try {
				log::add('starting', 'debug', __('Démarrage des processus Internet de Jeedom', __FILE__));
				self::start();
			} catch (\Throwable $e) {
				log::add('starting', 'error', __('Erreur sur le démarrage interne de Jeedom :', __FILE__) . ' ' . log::exception($e));
			}

			try {
				log::add('starting', 'debug', __('Ecriture du fichier', __FILE__) . ' ' . self::getTmpFolder() . '/started');
				if (file_put_contents(self::getTmpFolder() . '/started', date('Y-m-d H:i:s')) === false) {
					log::add('starting', 'error', __('Impossible d\'écrire', __FILE__) . ' ' . self::getTmpFolder() . '/started');
				}
			} catch (\Throwable $e) {
				log::add('starting', 'error', __('Impossible d\'écrire', __FILE__) . ' ' . self::getTmpFolder() . '/started : ' . log::exception($e));
			}

			if (!file_exists(self::getTmpFolder() . '/started')) {
				log::add('starting', 'critical', __('Impossible d\'écrire', __FILE__) . ' ' . self::getTmpFolder() . __('/started pour une raison inconnue. Jeedom ne peut démarrer', __FILE__));
				return;
			}

			try {
				log::add('starting', 'debug', __('Vérification de la configuration réseau interne', __FILE__));
				if (!network::test('internal')) {
					network::checkConf('internal');
				}
			} catch (\Throwable $e) {
				log::add('starting', 'error', __('Erreur sur la configuration réseau interne :', __FILE__) . ' ' . log::exception($e));
			}

			try {
				log::add('starting', 'debug', __('Envoi de l\'événement de démarrage', __FILE__));
				self::event('start');
			} catch (\Throwable $e) {
				log::add('starting', 'error', __('Erreur sur l\'envoi de l\'événement de démarrage :', __FILE__) . ' ' . log::exception($e));
			}

			try {
				log::add('starting', 'debug', __('Démarrage des plugins', __FILE__));
				plugin::start();
			} catch (\Throwable $e) {
				log::add('starting', 'error', __('Erreur sur le démarrage des plugins :', __FILE__) . ' ' . log::exception($e));
			}

			try {
				if (config::byKey('market::enable') == 1) {
					log::add('starting', 'debug', __('Test de connexion au market', __FILE__));
					repo_market::test();
				}
			} catch (\Throwable $e) {
				log::add('starting', 'error', __('Erreur sur la connexion au market :', __FILE__) . ' ' . log::exception($e));
			}
			log::add('starting', 'debug', __('Démarrage de jeedom fini avec succès', __FILE__));
		}
		self::isDateOk();
	}

	public static function cronDaily() {
		try {
			scenario::cleanTable();
			scenario::consystencyCheck();
			log::chunk();
			cron::clean();
			report::clean();
			DB::optimize();
			listener::clean();
			user::regenerateHashes();
			jeeObject::cronDaily();
			timeline::clean(false);
		} catch (\Throwable $e) {
			log::add('jeedom', 'error', log::exception($e));
		}
		try {
			foreach ((update::listRepo()) as $name => $repo) {
				$class = 'repo_' . $name;
				if (class_exists($class) && method_exists($class, 'cronDaily') && config::byKey($name . '::enable') == 1) {
					$class::cronDaily();
				}
			}
		} catch (\Throwable $e) {
			log::add('jeedom', 'error', log::exception($e));
		}
		$disk_space = self::checkSpaceLeft();
		if ($disk_space < 10) {
			log::add('jeedom', 'error', __('Espace disque disponible faible : ', __FILE__) . $disk_space . '%.' . __('Veuillez faire de la place (suppression de backup, de video/capture du plugin camera, d\'historique...)', __FILE__));
		}
	}

	public static function cronHourly() {
		try {
			cache::set('hour', strtotime('UTC'));
		} catch (\Throwable $e) {
			log::add('jeedom', 'error', log::exception($e));
		}
		try {
			cache::clean();
		} catch (\Throwable $e) {
			log::add('jeedom', 'error', log::exception($e));
		}
		try {
			//Check for updates every 24h according to config
			if (config::byKey('update::autocheck', 'core', 1) == 1 && (config::byKey('update::lastCheck') == '' || (strtotime('now') - strtotime(config::byKey('update::lastCheck'))) > (23 * 3600) || strtotime('now') < strtotime(config::byKey('update::lastCheck')))) {
				update::checkAllUpdate();
				update::refreshUpdateMessage();
			}
		} catch (\Throwable $e) {
			log::add('jeedom', 'error', log::exception($e));
		}
		try {
			foreach ((update::listRepo()) as $name => $repo) {
				$class = 'repo_' . $name;
				if (class_exists($class) && method_exists($class, 'cronHourly') && config::byKey($name . '::enable') == 1) {
					$class::cronHourly();
				}
			}
		} catch (\Throwable $e) {
			log::add('jeedom', 'error', log::exception($e));
		}
		try {
			log::chunk('', True);
		} catch (\Throwable $e) {
			log::add('jeedom', 'error', log::exception($e));
		}
	}

	/*************************************************************************************/

	public static function replaceTag(array $_replaces) {
		$datas = array();
		foreach ($_replaces as $key => $value) {
			$datas = array_merge($datas, cmd::searchConfiguration($key));
			$datas = array_merge($datas, eqLogic::searchConfiguration($key));
			$datas = array_merge($datas, jeeObject::searchConfiguration($key));
			$datas = array_merge($datas, scenario::searchByUse(array(array('action' => $key))));
			$datas = array_merge($datas, scenarioExpression::searchExpression($key, $key, false));
			$datas = array_merge($datas, scenarioExpression::searchExpression('variable(' . str_replace('#', '', $key) . ')'));
			$datas = array_merge($datas, scenarioExpression::searchExpression('variable', str_replace('#', '', $key), true));
			$datas = array_merge($datas, scenarioExpression::searchExpression('genericType(' . str_replace('#', '', $key) . ')'));
			$datas = array_merge($datas, scenarioExpression::searchExpression('genericType', str_replace('#', '', $key), true));
			$datas = array_merge($datas, viewData::searchByConfiguration($key));
			$datas = array_merge($datas, plan::searchByConfiguration($key));
			$datas = array_merge($datas, plan3d::searchByConfiguration($key));
			$datas = array_merge($datas, listener::searchEvent($key));
			$datas = array_merge($datas, user::searchByOptions($key));
			$datas = array_merge($datas, user::searchByRight($key));
		}
		if (count($datas) > 0) {
			foreach ($datas as $data) {
				try {
					if (method_exists($data, 'refresh')) {
						$data->refresh();
					}
					utils::a2o($data, json_decode(str_replace(array_keys($_replaces), $_replaces, json_encode(utils::o2a($data))), true));
					$data->save(true);
				} catch (\Exception $e) {
				}
			}
		}
		foreach ($_replaces as $key => $value) {
			$viewDatas = viewData::byTypeLinkId('cmd', str_replace('#', '', $key));
			if (count($viewDatas)  > 0) {
				foreach ($viewDatas as $viewData) {
					try {
						$viewData->setLink_id(str_replace('#', '', $value));
						$viewData->save();
					} catch (\Exception $e) {
					}
				}
			}
			$plans = plan::byLinkTypeLinkId('cmd', str_replace('#', '', $key));
			if (count($plans)  > 0) {
				foreach ($plans as $plan) {
					try {
						$plan->setLink_id(str_replace('#', '', $value));
						$plan->save();
					} catch (\Exception $e) {
					}
				}
			}
			$plan3ds = plan3d::byLinkTypeLinkId('cmd', str_replace('#', '', $key));
			if (count($plan3ds)  > 0) {
				foreach ($plan3ds as $plan3d) {
					try {
						$plan3d->setLink_id(str_replace('#', '', $value));
						$plan3d->save();
					} catch (\Exception $e) {
					}
				}
			}
		}
	}

	/***************************************THREAD MANGEMENT**********************************************/

	public static function checkOngoingThread(string $_cmd) {
		return shell_exec('(ps ax || ps w) | grep "' . $_cmd . '$" | grep -v "grep" | wc -l');
	}

	public static function retrievePidThread(string $_cmd) {
		return shell_exec('(ps ax || ps w) | grep "' . $_cmd . '$" | grep -v "grep" | awk \'{print $1}\'');
	}

	/******************************************UTILS******************************************************/

	/**
	 * @param boolean $_lightMode DEPRECATED, this parameter is not used anymore and will be removed in future versions
	 */
	public static function versionAlias(string $_version, bool $_lightMode = true): string {
		if ($_version == 'mview') {
			return 'mobile';
		}
		if ($_version == 'dview' || $_version == 'dplan' || $_version == 'plan' || $_version == 'view') {
			return 'dashboard';
		}
		return $_version;
	}

	public static function toHumanReadable($_input) {
		return jeeObject::toHumanReadable(scenario::toHumanReadable(eqLogic::toHumanReadable(cmd::cmdToHumanReadable($_input))));
	}

	public static function fromHumanReadable($_input) {
		return jeeObject::fromHumanReadable(scenario::fromHumanReadable(eqLogic::fromHumanReadable(cmd::humanReadableToCmd($_input))));
	}

	public static function evaluateExpression($_input, $_scenario = null) {
		try {
			return evaluate(scenarioExpression::setTags($_input, $_scenario, true));
		} catch (Exception $exc) {
			return $_input;
		}
	}

	public static function calculStat(string $_calcul, array $_values, int $_precision = 1) {
		switch ($_calcul) {
			case 'sum':
				return round(array_sum($_values), $_precision);
			case 'avg':
				return round(array_sum($_values) / count($_values), $_precision);
			case 'text':
				return trim(implode(',', $_values), ',');
		}
		return null;
	}

	public static function getTypeUse(string $_string = '') {
		$return = array('cmd' => array(), 'scenario' => array(), 'eqLogic' => array(), 'dataStore' => array(), 'plan' => array(), 'plan3d' => array(), 'view' => array());
		preg_match_all("/#([0-9]*)#/", $_string, $matches);
		foreach ($matches[1] as $cmd_id) {
			if (isset($return['cmd'][$cmd_id])) {
				continue;
			}
			$cmd = cmd::byId($cmd_id);
			if (!is_object($cmd)) {
				continue;
			}
			$return['cmd'][$cmd_id] = $cmd;
		}
		preg_match_all('/"scenario_id":"([0-9]*)"/', $_string, $matches);
		foreach ($matches[1] as $scenario_id) {
			if (isset($return['scenario'][$scenario_id])) {
				continue;
			}
			$scenario = scenario::byId($scenario_id);
			if (!is_object($scenario)) {
				continue;
			}
			$return['scenario'][$scenario_id] = $scenario;
		}
		preg_match_all("/#scenario([0-9]*)#/", $_string, $matches);
		foreach ($matches[1] as $scenario_id) {
			if (isset($return['scenario'][$scenario_id])) {
				continue;
			}
			$scenario = scenario::byId($scenario_id);
			if (!is_object($scenario)) {
				continue;
			}
			$return['scenario'][$scenario_id] = $scenario;
		}
		preg_match_all("/#eqLogic([0-9]*)#/", $_string, $matches);
		foreach ($matches[1] as $eqLogic_id) {
			if (isset($return['eqLogic'][$eqLogic_id])) {
				continue;
			}
			$eqLogic = eqLogic::byId($eqLogic_id);
			if (!is_object($eqLogic)) {
				continue;
			}
			$return['eqLogic'][$eqLogic_id] = $eqLogic;
		}
		preg_match_all('/"eqLogic":"([0-9]*)"/', $_string, $matches);
		foreach ($matches[1] as $eqLogic_id) {
			if (isset($return['eqLogic'][$eqLogic_id])) {
				continue;
			}
			$eqLogic = eqLogic::byId($eqLogic_id);
			if (!is_object($eqLogic)) {
				continue;
			}
			$return['eqLogic'][$eqLogic_id] = $eqLogic;
		}
		preg_match_all('/variable\((.*?)\)/', $_string, $matches);
		foreach ($matches[1] as $variable) {
			if (isset($return['dataStore'][$variable])) {
				continue;
			}
			$dataStore = dataStore::byTypeLinkIdKey('scenario', -1, trim($variable));
			if (!is_object($dataStore)) {
				continue;
			}
			$return['dataStore'][$variable] = $dataStore;
		}
		preg_match_all('/"view_id":"([0-9]*)"/', $_string, $matches);
		foreach ($matches[1] as $view_id) {
			if (isset($return['view'][$view_id])) {
				continue;
			}
			$view = view::byId($view_id);
			if (!is_object($view)) {
				continue;
			}
			$return['view'][$view_id] = $view;
		}
		preg_match_all('/"plan_id":"([0-9]*)"/', $_string, $matches);
		foreach ($matches[1] as $plan_id) {
			if (isset($return['plan'][$plan_id])) {
				continue;
			}
			$plan = planHeader::byId($plan_id);
			if (!is_object($plan)) {
				continue;
			}
			$return['plan'][$plan_id] = $plan;
		}
		preg_match_all('/"plan3d_id":"([0-9]*)"/', $_string, $matches);
		foreach ($matches[1] as $plan3d_id) {
			if (isset($return['plan3d'][$plan3d_id])) {
				continue;
			}
			$plan3d = plan3dHeader::byId($plan3d_id);
			if (!is_object($plan3d)) {
				continue;
			}
			$return['plan3d'][$plan3d_id] = $plan3d;
		}
		return $return;
	}

	public static function getRemovehistory() {
		if (file_exists(__DIR__ . '/../../data/remove_history.json')) {
			$remove_history = json_decode(file_get_contents(__DIR__ . '/../../data/remove_history.json'), true);
		}
		if (!isset($remove_history) || !is_array($remove_history)) {
			$remove_history = array();
		}
		return $remove_history;
	}

	public static function massReplace(array $_options = [], array $_eqlogics = [], array $_cmds = []) {
		if (count($_eqlogics) == 0 && count($_cmds) == 0) {
			throw new Exception('{{Aucun équipement ou commande à remplacer ou copier}}');
		}
		foreach (['copyEqProperties', 'hideEqs', 'copyCmdProperties', 'removeCmdHistory', 'copyCmdHistory', 'disableEqs'] as $key) {
			if (!isset($_options[$key])) {
				$_options[$key] = false;
			}
		}
		if (!isset($_options['mode'])) {
			$_options['mode'] = 'replace';
		}

		$_mode = $_options['mode'];

		$return = array('eqlogics' => 0, 'cmds' => 0);

		if ($_mode == 'replace') {
			//replace equipment where used:
			foreach ($_eqlogics as $_sourceId => $_targetId) {
				$sourceEq = eqLogic::byId($_sourceId);
				$targetEq = eqLogic::byId($_targetId);
				if (!is_object($sourceEq) || !is_object($targetEq)) continue;
				jeedom::replaceTag(array('eqLogic' . $_sourceId => 'eqLogic' . $_targetId));
				$return['eqlogics'] += 1;
			}
		}


		//for each source eqlogic:
		if ($_options['copyEqProperties'] == "true") {
			foreach ($_eqlogics as $_sourceId => $_targetId) {
				$sourceEq = eqLogic::byId($_sourceId);
				$targetEq = eqLogic::byId($_targetId);
				if (!is_object($sourceEq) || !is_object($targetEq)) continue;

				//Migrate plan cmd config for eqLogic:
				$planEqlogics = plan::byLinkTypeLinkId('eqLogic', $sourceEq->getId());
				foreach ($planEqlogics as $planEqlogic) {
					$savePlan = false;
					foreach (['cmdHideName', 'cmdHide', 'cmdTransparentBackground'] as $key) {
						$displayValue = $planEqlogic->getDisplay($key, null);
						if ($displayValue) {
							$savePlan = true;
							$newDisplayValue = array();
							foreach ($displayValue as $cmdId => $value) {
								if (isset($_cmds[$cmdId])) {
									$newDisplayValue[$_cmds[$cmdId]] = $value;
								}
							}
							$planEqlogic->setDisplay($key, $newDisplayValue);
						}
					}
					if ($savePlan) {
						$planEqlogic->save();
					}
				}

				//Migrate eqLogic configurations:
				$targetEq = eqLogic::migrateEqlogic($_sourceId, $_targetId, $_mode);

				//migrate graphInfo if cmd known:
				$var = $sourceEq->getDisplay('backGraph::info', '0');
				if ($sourceEq->getDisplay('backGraph::info', '0') != '0') {
					$cmdGraphId = $sourceEq->getDisplay('backGraph::info');
					if (isset($_cmds[$cmdGraphId])) {
						$targetEq->setDisplay('backGraph::info', $_cmds[$cmdGraphId]);
					}
				} else {
					$targetEq->setDisplay('backGraph::info', '0');
				}

				//display table dynamic settings:
				$targetEq->setDisplay('layout::dashboard', $sourceEq->getDisplay('layout::dashboard', ''));
				$sourceDisplay = $sourceEq->getDisplay();
				$targetEq->setDisplay('layout::dashboard::table::parameters', $sourceEq->getDisplay('layout::dashboard::table::parameters', null));
				foreach ($sourceDisplay as $key => $value) {
					$query = 'layout::dashboard::table::cmd::';
					if (substr($key, 0, strlen($query)) === $query) {
						$targetEq->setDisplay($key, null);
						$sourceCmdId = explode('::', str_replace($query, '', $key))[0];
						$end = explode('::', str_replace($query, '', $key))[1];
						if (isset($_cmds[$sourceCmdId])) {
							$targetEq->setDisplay($query . $_cmds[$sourceCmdId] . '::' . $end, $value);
						}
					}
				}

				$targetEq->save();
				$return['eqlogics'] += 1;
			}
		}
		if ($_options['hideEqs'] == "true") {
			foreach ($_eqlogics as $_sourceId => $_targetId) {
				$sourceEq = eqLogic::byId($_sourceId);
				if (!is_object($sourceEq)) continue;
				$sourceEq->setIsVisible(0);
				$sourceEq->save();
			}
		}
		if ($_options['disableEqs'] == "true") {
			foreach ($_eqlogics as $_sourceId => $_targetId) {
				$sourceEq = eqLogic::byId($_sourceId);
				if (!is_object($sourceEq)) continue;
				$sourceEq->setIsEnable(0);
				$sourceEq->save();
			}
		}

		//for each source cmd:
		foreach ($_cmds as $_sourceId => $_targetId) {
			$sourceCmd = cmd::byId($_sourceId);
			$targetCmd = cmd::byId($_targetId);
			if (!is_object($sourceCmd) || !is_object($targetCmd)) continue;
			if ($sourceCmd->getLogicalId() == 'refresh') continue;

			//copy properties:
			if ($_options['copyCmdProperties'] == "true") {
				$targetCmd = cmd::migrateCmd($_sourceId, $_targetId);
			}

			if ($_mode == 'replace') {
				//replace command where used:
				jeedom::replaceTag(array('#' . str_replace('#', '', $sourceCmd->getId()) . '#' => '#' . str_replace('#', '', $targetCmd->getId()) . '#'));
			}

			//remove history:
			if ($_options['removeCmdHistory'] == "true" && $targetCmd->getType() == 'info') {
				history::removes($targetCmd->getId());
			}

			//copy history:
			if ($_options['copyCmdHistory'] == "true" && $sourceCmd->getIsHistorized() == 1) {
				if ($sourceCmd->getSubType() == $targetCmd->getSubType()) {
					history::copyHistoryToCmd($sourceCmd->getId(), $targetCmd->getId());
				}
			}
			$return['cmds'] += 1;
		}

		return $return;
	}


	/******************************SYSTEM MANAGEMENT**********************************************************/

	public static function haltSystem() {
		plugin::stop();
		cache::persist();
		if (self::isCapable('sudo')) {
			exec(system::getCmdSudo() . 'shutdown -fh now');
		} else {
			throw new Exception(__('Vous pouvez arrêter le système', __FILE__));
		}
	}

	public static function rebootSystem() {
		plugin::stop();
		cache::persist();
		if (self::isCapable('sudo')) {
			exec(system::getCmdSudo() . 'shutdown -fr now');
		} else {
			throw new Exception(__('Vous pouvez lancer le redémarrage du système', __FILE__));
		}
	}

	public static function forceSyncHour() {
		if (config::byKey('disable_ntp', 'core', 0) == 1) {
			return;
		}
		$ntpServer = config::byKey('ntp::optionalServer', 'core', '0.debian.pool.ntp.org');
		shell_exec(system::getCmdSudo() . 'chronyc add server ' . $ntpServer . ' iburst');
		shell_exec(system::getCmdSudo() . 'chronyc makestep');
	}

	public static function cleanDatabase() {
		log::clear('cleaningdb');
		$cmd = __DIR__ . '/../../install/cleaning.php';
		$cmd .= ' >> ' . log::getPathToLog('cleaningdb') . ' 2>&1 &';
		system::php($cmd, true);
	}

	public static function cleanFileSytemRight() {
		self::cleanFileSystemRight();
	}

	public static function cleanFileSystemRight() {
		$cmd = system::getCmdSudo() . 'chown -R ' . system::get('www-uid') . ':' . system::get('www-gid') . ' ' . __DIR__ . '/../../*;';
		$cmd .= system::getCmdSudo() . 'chmod 775 -R ' . __DIR__ . '/../../*;';
		$cmd .= system::getCmdSudo() . 'find ' . __DIR__ . '/../../log -type f -exec chmod 665 {} +;';
		$cmd .= system::getCmdSudo() . 'chmod 775 -R ' . __DIR__ . '/../../.* ;';
		exec($cmd);
	}

	public static function checkSpaceLeft(?string $_dir = null) {
		$path = $_dir ?? (__DIR__ . '/../../');
		return round(disk_free_space($path) / disk_total_space($path) * 100);
	}

	public static function getTmpFolder(string $_plugin = '') {
		if (isset(self::$cache['getTmpFolder::' . $_plugin])) {
			return self::$cache['getTmpFolder::' . $_plugin];
		}
		$return = '/' . trim(config::byKey('folder::tmp'), '/');
		if ($_plugin !== '') {
			$return .= '/' . $_plugin;
		}
		if (!file_exists($return)) {
			mkdir($return, 0774, true);
			$cmd = system::getCmdSudo() . 'chown -R ' . system::get('www-uid') . ':' . system::get('www-gid') . ' ' . $return . ';';
			com_shell::execute($cmd);
		}
		self::$cache['getTmpFolder::' . $_plugin] = $return;
		return $return;
	}

	/*     * ******************hardware management*************************** */

	public static function getHardwareKey() {
		$return = config::byKey('jeedom::installKey');
		if ($return == '') {
			$return = substr(sha512(microtime() . config::genKey()), 0, 63);
			config::save('jeedom::installKey', $return);
		}
		return $return;
	}

	public static function getHardwareName() {
		if (config::byKey('hardware_name') != '') {
			return config::byKey('hardware_name');
		}
		$result = 'diy';
		$uname = shell_exec('uname -a');
		$hostname = shell_exec('cat /etc/hostname');
		if (file_exists('/.dockerinit') || file_exists('/.dockerenv')) {
			$result = 'docker';
		} else if (file_exists('/usr/bin/raspi-config')) {
			$result = 'rpi';
			$hardware_revision = strtolower(shell_exec('cat /proc/cpuinfo | grep Revision'));
			global $JEEDOM_RPI_HARDWARE;
			foreach ($JEEDOM_RPI_HARDWARE as $key => $values) {
				foreach ($values as $value) {
					if (strpos($hardware_revision, $value) !== false) {
						$result = $key;
					}
				}
			}
		} else if (strpos($uname, 'cubox') !== false || strpos($uname, 'imx6') !== false) {
			$result = 'miniplus';
		} else if (file_exists('/usr/bin/grille-pain')) {
			$result = 'freeboxDelta';
		} else if (file_exists('/media/boot/multiboot/meson64_odroidc2.dtb.linux')) {
			$result = 'smart';
		} else if (file_exists('/etc/update-motd.d/10-armbian-header-jeedomatlas')) {
			$result = 'Atlas';
		} else if (strpos($hostname, 'Luna') !== false) {
			$result = 'Luna';
		} else if (file_exists('/proc/1/sched') && strpos(shell_exec('cat /proc/1/sched | head -n 1'), 'systemd') === false) {
			$result = 'docker';
		}
		config::save('hardware_name', $result);
		return config::byKey('hardware_name');
	}

	public static function isCapable($_function, bool $_forceRefresh = false) {
		global $JEEDOM_COMPATIBILIY_CONFIG;
		if ($_function == 'sudo') {
			if (!$_forceRefresh) {
				$cache = cache::byKey('jeedom::isCapable::sudo');
				if ($cache->getValue(0) == 1) {
					return true;
				}
			}
			$result = (shell_exec('sudo -l > /dev/null 2>&1; echo $?') == 0) ? true : false;
			cache::set('jeedom::isCapable::sudo', $result, 3600 * 24);
			return $result;
		}
		$hardware = self::getHardwareName();
		if (!isset($JEEDOM_COMPATIBILIY_CONFIG[$hardware])) {
			return false;
		}
		if (in_array($_function, $JEEDOM_COMPATIBILIY_CONFIG[$hardware])) {
			return true;
		}
		return false;
	}

	/*     * ******************Benchmark*************************** */

	public static function benchmark() {
		$return = array();

		$param = array('cache_write' => 5000, 'cache_read' => 5000, 'database_write_delete' => 1000, 'database_update' => 1000, 'database_replace' => 1000, 'database_read' => 50000, 'subprocess' => 200);

		$starttime = getmicrotime();
		for ($i = 0; $i < $param['cache_write']; $i++) {
			cache::set('jeedom_benchmark', $i);
		}
		$return['cache_write_' . $param['cache_write']] = getmicrotime() - $starttime;

		$starttime = getmicrotime();
		for ($i = 0; $i < $param['cache_read']; $i++) {
			$cache = cache::byKey('jeedom_benchmark');
			$cache->getValue();
		}
		$return['cache_read_' . $param['cache_read']] = getmicrotime() - $starttime;

		$starttime = getmicrotime();
		for ($i = 0; $i < $param['database_write_delete']; $i++) {
			$sql = 'DELETE FROM config
			WHERE `key`="jeedom_benchmark"
			AND plugin="core"';
			try {
				DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
			} catch (Exception $e) {
			}
			$sql = 'INSERT INTO config
			SET `key`="jeedom_benchmark",plugin="core",`value`="' . $i . '"';
			try {
				DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
			} catch (Exception $e) {
			}
		}
		$return['database_write_delete_' . $param['database_write_delete']] = getmicrotime() - $starttime;

		$sql = 'INSERT INTO config
		SET `key`="jeedom_benchmark",plugin="core",`value`="0"';
		try {
			DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
		} catch (Exception $e) {
		}
		$starttime = getmicrotime();
		for ($i = 0; $i < $param['database_update']; $i++) {
			$sql = 'UPDATE config
			SET `value`=:value
			WHERE `key`="jeedom_benchmark"
			AND plugin="core"';
			try {
				DB::Prepare($sql, array('value' => $i), DB::FETCH_TYPE_ROW);
			} catch (Exception $e) {
			}
		}
		$return['database_update_' . $param['database_update']] = getmicrotime() - $starttime;

		$starttime = getmicrotime();
		for ($i = 0; $i < $param['database_replace']; $i++) {
			config::save('jeedom_benchmark', $i);
		}
		$return['database_replace_' . $param['database_replace']] = getmicrotime() - $starttime;

		$starttime = getmicrotime();
		for ($i = 0; $i < $param['database_read']; $i++) {
			config::byKey('jeedom_benchmark');
		}
		$return['database_read_' . $param['database_read']] = getmicrotime() - $starttime;

		$starttime = getmicrotime();
		for ($i = 0; $i < $param['subprocess']; $i++) {
			shell_exec('echo ' . $i);
		}
		$return['subprocess_' . $param['subprocess']] = getmicrotime() - $starttime;

		$total = 0;
		foreach ($return as $value) {
			$total += $value;
		}
		$return['total'] = $total;
		return $return;
	}
}
