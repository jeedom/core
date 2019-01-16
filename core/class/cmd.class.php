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

use Jeedom\Core\Api\Security\ApiKey;
use Jeedom\Core\Application\Configuration\Configuration;
use Jeedom\Core\Infrastructure\Factory\ConfigurationFactory;
use Jeedom\Core\Domain\Repository\CommandRepository;
use Jeedom\Core\Infrastructure\Factory\RepositoryFactory;
use Jeedom\Core\Infrastructure\Factory\ServiceFactory;
use Jeedom\Core\Presenter\ColorConverter;
use Jeedom\Core\Presenter\HumanCommandMap;
use Jeedom\Core\Presenter\Service\WidgetService;

require_once __DIR__ . '/../../core/php/core.inc.php';

class cmd {
	/*     * *************************Attributs****************************** */
	
	protected $id;
	protected $logicalId;
	protected $generic_type;
	protected $eqType;
	protected $name;
	protected $order;
	protected $type;
	protected $subType;
	protected $eqLogic_id;
	protected $isHistorized = 0;
	protected $unite = '';
	protected $configuration;
	protected $template;
	protected $display;
	protected $html;
	protected $value = null;
	protected $isVisible = 1;
	protected $alert;
	protected $_collectDate = '';
	protected $_valueDate = '';
	protected $_eqLogic = null;
	protected $_needRefreshWidget;
	protected $_needRefreshAlert;
	private static $_templateArray = array();
	
	/*     * ***********************Méthodes statiques*************************** */

    public static function byId($_id) {
        if ($_id == '') {
            return;
        }

        return self::getRepository()->get($_id);
    }

    public static function byIds($_ids) {
        if (!is_array($_ids) || count($_ids) === 0) {
            return;
        }

        return self::getRepository()->findByIds($_ids);
    }

    public static function all() {
        return self::getRepository()->all();
    }

    public static function allHistoryCmd() {
        return self::getRepository()->allHistoryCmd();
    }

    public static function byEqLogicId($_eqLogic_id, $_type = null, $_visible = null, $_eqLogic = null, $_has_generic_type = null) {
        return self::getRepository()->findByEqLogicId($_eqLogic_id, $_type, $_visible, $_eqLogic, $_has_generic_type);
    }

    public static function byLogicalId($_logical_id, $_type = null) {
        return self::getRepository()->findByLogicalId($_logical_id, $_type);
    }

    public static function byGenericType($_generic_type, $_eqLogic_id = null, $_one = false) {
        if ($_one) {
            return self::getRepository()->findOneByGenericType($_generic_type, $_eqLogic_id);
        }
        return self::getRepository()->findByGenericType($_generic_type, $_eqLogic_id);
    }

    public static function searchConfiguration($_configuration, $_eqType = null) {
        return self::getRepository()->searchConfiguration($_configuration, $_eqType);
    }

    public static function searchConfigurationEqLogic($_eqLogic_id, $_configuration, $_type = null) {
        return self::getRepository()->searchConfigurationEqLogic($_eqLogic_id, $_configuration, $_type);
    }

    public static function searchTemplate($_template, $_eqType = null, $_type = null, $_subtype = null) {
        return self::getRepository()->searchTemplate($_template, $_eqType, $_type, $_subtype);
    }

    public static function byEqLogicIdAndLogicalId($_eqLogic_id, $_logicalId, $_multiple = false, $_type = null) {
        if ($_multiple) {
            return self::getRepository()->findByEqLogicIdAndLogicalId($_eqLogic_id, $_logicalId, $_type);
        }
        return self::getRepository()->findOneByEqLogicIdAndLogicalId($_eqLogic_id, $_logicalId, $_type);
    }

    public static function byEqLogicIdAndGenericType($_eqLogic_id, $_generic_type, $_multiple = false, $_type = null) {
        if ($_multiple) {
            return self::getRepository()->findByEqLogicIdAndGenericType($_eqLogic_id, $_generic_type, $_type);
        }
        return self::getRepository()->findOneByEqLogicIdAndGenericType($_eqLogic_id, $_generic_type, $_type);
    }

    public static function byValue($_value, $_type = null, $_onlyEnable = false) {
        return self::getRepository()->findByValue($_value, $_type, $_onlyEnable);
    }

    public static function byTypeEqLogicNameCmdName($_eqType_name, $_eqLogic_name, $_cmd_name) {
        return self::getRepository()->findOneByTypeEqLogicNameCmdName($_eqType_name, $_eqLogic_name, $_cmd_name);
    }

    public static function byEqLogicIdCmdName($_eqLogic_id, $_cmd_name) {
        return self::getRepository()->findOneByEqLogicIdCmdName($_eqLogic_id, $_cmd_name);
    }

    public static function byObjectNameEqLogicNameCmdName($_object_name, $_eqLogic_name, $_cmd_name) {
        return self::getRepository()->findOneByObjectNameEqLogicNameCmdName($_object_name, $_eqLogic_name, $_cmd_name);
    }

    public static function byObjectNameCmdName($_object_name, $_cmd_name) {
        return self::getRepository()->findOneByObjectNameCmdName($_object_name, $_cmd_name);
    }

    public static function byTypeSubType($_type, $_subType = '') {
        return self::getRepository()->findByTypeSubType($_type, $_subType);
    }

    /**
     * @param $_input
     *
     * @return mixed
     * @throws ReflectionException
     */
	public static function cmdToHumanReadable($_input) {
        return self::getCommandMap()->cmdToHumanReadable($_input);
	}

    /**
     * @param $_input
     *
     * @return array|false|mixed|string
     * @throws ReflectionException
     */
	public static function humanReadableToCmd($_input) {
		return self::getCommandMap()->humanReadableToCmd($_input);
	}

    /**
     * @param string $_string
     *
     * @return \cmd
     * @throws ReflectionException
     */
	public static function byString($_string) {
	    $hashedId = self::getCommandMap()->humanReadableToCmd($_string);
	    $id = str_replace('#', '', $hashedId);
	    $cmd = self::getRepository()->get($id);
        if (!is_object($cmd)) {
            throw new \DomainException(__('La commande n\'a pas pu être trouvée : ', __FILE__) . $_string . __(' => ', __FILE__) . \cmd::humanReadableToCmd($_string));
        }

        return $cmd;
	}

    /**
     * @param $_input
     * @param bool $_quote
     *
     * @return array|mixed
     * @throws ReflectionException
     */
	public static function cmdToValue($_input, $_quote = false) {
		return self::getCommandMap()->cmdToValue($_input, $_quote);
	}

    /**
     * @return array
     * @throws Exception
     */
	public static function allType() {
        return self::getRepository()->listTypes();
	}

    /**
     * @param string $_type
     *
     * @return array
     * @throws Exception
     */
	public static function allSubType($_type = '') {
        return self::getRepository()->listSubTypes($_type);
	}

    /**
     * @return array
     * @throws Exception
     */
	public static function allUnite() {
        return self::getRepository()->listUnites();
	}

    /**
     * @param $_color
     *
     * @return mixed
     * @throws Exception
     */
	public static function convertColor($_color) {
	    return self::getColorConverter()->convert($_color);
	}
	
	public static function availableWidget($_version) {
	    return self::getWidgetService()->getAvailables('cmd', $_version);
	}
	
	public static function returnState($_options) {
		$cmd = self::getRepository()->get($_options['cmd_id']);
		if (is_object($cmd)) {
			$cmd->event($cmd->getConfiguration('returnStateValue', 0));
		}
	}
	
	public static function deadCmd() {
		$return = [];
        $tempActionCmds = [];
        $configs = [
            'actionCheckCmd',
            'jeedomPostExecCmd',
            'jeedomPreExecCmd'
        ];
		foreach (self::getRepository()->all() as $cmd) {
		    foreach ($configs as $config) {
                $actionCommand = $cmd->getConfiguration($config, '');
                if (is_array($actionCommand)) {
                    $tempActionCmds[] = $actionCommand;
                }
            }

            $actionCmds = array_merge(...$tempActionCmds);
            foreach ($actionCmds as $actionCmd) {
                if ($actionCmd['cmd'] != ''
                    && strpos($actionCmd['cmd'], '#') !== false
                    && !self::getRepository()->get(str_replace('#', '', $actionCmd['cmd']))
                ) {
                    $return[] = [
                        'detail' => 'Commande ' . $cmd->getName() . ' de ' . $cmd->getEqLogic()->getName() . ' (' . $cmd->getEqLogic()->getEqType_name() . ')',
                        'help' => 'Action sur valeur', 'who' => $actionCmd['cmd']
                    ];
                }
            }
		}
		return $return;
	}
	
	public static function cmdAlert($_options) {
		$cmd = self::getRepository()->get($_options['cmd_id']);
		if (!is_object($cmd)) {
			return;
		}
		$value = $cmd->execCmd();
		$check = jeedom::evaluateExpression($value . $cmd->getConfiguration('jeedomCheckCmdOperator') . $cmd->getConfiguration('jeedomCheckCmdTest'));
		if ($check == 1 || $check || $check == '1') {
			$cmd->executeAlertCmdAction();
		}
	}
	
	public static function timelineDisplay($_event) {
		$return = array();
		$return['date'] = $_event['datetime'];
		$return['type'] = $_event['type'];
		$return['group'] = $_event['subtype'];
		$cmd = self::getRepository()->get($_event['id']);
		if (!is_object($cmd)) {
			return null;
		}
		$eqLogic = $cmd->getEqLogic();
		$object = $eqLogic->getObject();
		$return['object'] = is_object($object) ? $object->getId() : 'aucun';
		$return['plugins'] = $eqLogic->getEqType_name();
		$return['category'] = $eqLogic->getCategory();
		
		if ($_event['subtype'] == 'action') {
			$return['html'] = '<div class="cmd" data-id="' . $_event['id'] . '">'
			. '<div style="background-color:#F5A9BC;padding:1px;font-size:0.9em;font-weight: bold;cursor:help;">' . $_event['name'] . '<i class="fa fa-cogs pull-right cursor bt_configureCmd"></i></div>'
			. '<div style="background-color:white;padding:1px;font-size:0.8em;cursor:default;">' . $_event['options'] . '<div/>'
			. '</div>';
		} else {
			$backgroundColor = '#A9D0F5';
			if (isset($_event['cmdType']) && $_event['cmdType'] == 'binary') {
				$backgroundColor = ($_event['value'] == 0 ? '#ff8693' : '#c1e5bd');
			}
			$return['html'] = '<div class="cmd" data-id="' . $_event['id'] . '">'
			. '<div style="background-color:' . $backgroundColor . ';padding:1px;font-size:0.9em;font-weight: bold;cursor:help;">' . $_event['name'] . '<i class="fa fa-cogs pull-right cursor bt_configureCmd"></i></div>'
			. '<div style="background-color:white;padding:1px;font-size:0.8em;cursor:default;">' . $_event['value'] . '<div/>'
			. '</div>';
		}
		return $return;
	}
	
	/*     * *********************Méthodes d'instance************************* */
	
	public function formatValue($_value, $_quote = false) {
		if (is_array($_value)) {
			return '';
		}
		if (trim($_value) == '' && $_value !== false && $_value !== 0) {
			return '';
		}
		$_value = trim(trim($_value), '"');
		if (@strpos(strtolower($_value), 'error::') !== false) {
			return $_value;
		}
		if ($this->getType() == 'info') {
			switch ($this->getSubType()) {
				case 'string':
				case 'other':
                    if ($_quote) {
                        return '"' . $_value . '"';
                    }
                    return $_value;
				case 'binary':
                    if ($this->getConfiguration('calculValueOffset') != '') {
                        try {
                            if (preg_match("/[a-zA-Z#]/", $_value)) {
                                $_value = jeedom::evaluateExpression(str_replace('#value#', '"' . $_value . '"', str_replace('\'#value#\'', '#value#', str_replace('"#value#"', '#value#', $this->getConfiguration('calculValueOffset')))));
                            } else {
                                $_value = jeedom::evaluateExpression(str_replace('#value#', $_value, $this->getConfiguration('calculValueOffset')));
                            }
                        } catch (Exception $ex) {

                        } catch (Error $ex) {

                        }
                    }
                    $value = strtolower($_value);
                    if ($value == 'on' || $value == 'high' || $value == 'true' || $value === true) {
                        return 1;
                    }
                    if ($value == 'off' || $value == 'low' || $value == 'false' || $value === false) {
                        return 0;
                    }
                    if ((is_numeric(intval($_value)) && intval($_value) > 1) || $_value === true || $_value == 1) {
                        return 1;
                    }
                    return 0;
				case 'numeric':
                    $_value = (float) str_replace(',', '.', $_value);
                    if ($this->getConfiguration('calculValueOffset') != '') {
                        try {
                            if (preg_match("/[a-zA-Z#]/", $_value)) {
                                $_value = jeedom::evaluateExpression(str_replace('#value#', '"' . $_value . '"', str_replace('\'#value#\'', '#value#', str_replace('"#value#"', '#value#', $this->getConfiguration('calculValueOffset')))));
                            } else {
                                $_value = jeedom::evaluateExpression(str_replace('#value#', $_value, $this->getConfiguration('calculValueOffset')));
                            }
                        } catch (Exception $ex) {

                        } catch (Error $ex) {

                        }
                    }
                    if ($this->getConfiguration('historizeRound') !== '' && is_numeric($this->getConfiguration('historizeRound')) && $this->getConfiguration('historizeRound') >= 0) {
                        $_value = round($_value, $this->getConfiguration('historizeRound'));
                    }
                    if ($_value > $this->getConfiguration('maxValue', $_value) && $this->getConfiguration('maxValueReplace') == 1) {
                        $_value = $this->getConfiguration('maxValue', $_value);
                    }
                    if ($_value < $this->getConfiguration('minValue', $_value) && $this->getConfiguration('minValueReplace') == 1) {
                        $_value = $this->getConfiguration('minValue', $_value);
                    }
                    return (float) $_value;
			}
		}
		return $_value;
	}
	
	public function getLastValue() {
		return $this->getConfiguration('lastCmdValue', null);
	}
	
	public function dontRemoveCmd() {
		return false;
	}
	
	public function getTableName() {
		return 'cmd';
	}
	
	public function save() {
        self::getRepository()->add($this);

        return true;
	}
	
	public function refresh() {
        self::getRepository()->refresh($this);
	}
	
	public function remove() {
        try {
            self::getRepository()->remove($this);
        } catch (\Exception $e) {
            return false;
        }

        return true;
	}
	
	public function execute($_options = array()) {
		return false;
	}
	
	public function preExecCmd($_values = array()) {
		if (!is_array($this->getConfiguration('jeedomPreExecCmd')) || count($this->getConfiguration('jeedomPreExecCmd')) === 0) {
			return;
		}
		foreach ($this->getConfiguration('jeedomPreExecCmd') as $action) {
			try {
				$options = array();
				if (isset($action['options'])) {
					$options = $action['options'];
				}
				if (is_array($_values) && count($_values) > 0) {
					foreach ($_values as $key => $value) {
						foreach ($options as &$option) {
							if (!is_array($option)) {
								$option = str_replace('#' . $key . '#', $value, $option);
							}
						}
					}
				}
				scenarioExpression::createAndExec('action', $action['cmd'], $options);
			} catch (Exception $e) {
				log::add('cmd', 'error', __('Erreur lors de l\'exécution de ', __FILE__) . $action['cmd'] . __('. Sur preExec de la commande', __FILE__) . $this->getHumanName() . __('. Détails : ', __FILE__) . $e->getMessage());
			}
		}
	}
	
	public function postExecCmd($_values = array()) {
		if (!is_array($this->getConfiguration('jeedomPostExecCmd'))) {
			return;
		}
		foreach ($this->getConfiguration('jeedomPostExecCmd') as $action) {
			try {
				$options = array();
				if (isset($action['options'])) {
					$options = $action['options'];
				}
				if (count($_values) > 0) {
					foreach ($_values as $key => $value) {
						foreach ($options as &$option) {
							if (!is_array($option)) {
								$option = str_replace('#' . $key . '#', $value, $option);
							}
						}
					}
				}
				scenarioExpression::createAndExec('action', $action['cmd'], $options);
			} catch (Exception $e) {
				log::add('cmd', 'error', __('Erreur lors de l\'exécution de ', __FILE__) . $action['cmd'] . __('. Sur preExec de la commande', __FILE__) . $this->getHumanName() . __('. Détails : ', __FILE__) . $e->getMessage());
			}
		}
	}

    /**
     *
     * @param type $_options
     * @param bool $_sendNodeJsEvent  ---> Not used
     * @param bool $_quote
     *
     * @return command result
     * @throws Exception
     */
	public function execCmd($_options = null, $_sendNodeJsEvent = false, $_quote = false) {
		if ($this->getType() == 'info') {
			$state = $this->getCache(array('collectDate', 'valueDate', 'value'));
			if(isset($state['collectDate'])){
				$this->setCollectDate($state['collectDate']);
			}else{
				$this->setCollectDate(date('Y-m-d H:i:s'));
			}
			if(isset($state['valueDate'])){
				$this->setValueDate($state['valueDate']);
			}else{
				$this->setValueDate($this->getCollectDate());
			}
			return $state['value'];
		}
		$eqLogic = $this->getEqLogic();
		if ($this->getType() != 'info' && (!is_object($eqLogic) || $eqLogic->getIsEnable() != 1)) {
			throw new Exception(__('Equipement désactivé - impossible d\'exécuter la commande : ', __FILE__) . $this->getHumanName());
		}
		try {
			if ($_options !== null && $_options !== '') {
				$options = self::cmdToValue($_options);
				if (is_json($_options)) {
					$options = json_decode($_options, true);
				}
			} else {
				$options = null;
			}
			if (isset($options['color'])) {
				$options['color'] = str_replace('"', '', $options['color']);
			}
			if ($this->getSubType() == 'color' && isset($options['color']) && substr($options['color'], 0, 1) != '#') {
				$options['color'] = self::convertColor($options['color']);
			}
			$str_option = '';
			if (is_array($options) && ((count($options) > 1 && isset($options['uid'])) || count($options) > 0)) {
				log::add('event', 'info', __('Exécution de la commande ', __FILE__) . $this->getHumanName() . __(' avec les paramètres ', __FILE__) . json_encode($options, true));
			} else {
				log::add('event', 'info', __('Exécution de la commande ', __FILE__) . $this->getHumanName());
			}
			
			if ($this->getConfiguration('timeline::enable')) {
				jeedom::addTimelineEvent(array('type' => 'cmd', 'subtype' => 'action', 'id' => $this->getId(), 'name' => $this->getHumanName(true), 'datetime' => date('Y-m-d H:i:s'), 'options' => $str_option));
			}
			$this->preExecCmd($options);
			$value = $this->formatValue($this->execute($options), $_quote);
			$this->postExecCmd($options);
		} catch (Exception $e) {
			$type = $eqLogic->getEqType_name();
			if ($eqLogic->getConfiguration('nerverFail') != 1) {
				$numberTryWithoutSuccess = $eqLogic->getStatus('numberTryWithoutSuccess', 0);
				$eqLogic->setStatus('numberTryWithoutSuccess', $numberTryWithoutSuccess);
				if ($numberTryWithoutSuccess >= self::getConfig()->get('numberOfTryBeforeEqLogicDisable')) {
					$message = 'Désactivation de <a href="' . $eqLogic->getLinkToConfiguration() . '">' . $eqLogic->getName();
					$message .= '</a> car il n\'a pas répondu ou mal répondu lors des 3 derniers essais';
					message::add($type, $message);
					$eqLogic->setIsEnable(0);
					$eqLogic->save();
				}
			}
			log::add($type, 'error', __('Erreur exécution de la commande ', __FILE__) . $this->getHumanName() . ' : ' . $e->getMessage());
			throw $e;
		}
		if ($options !== null && $this->getValue() == '') {
			if (isset($options['slider'])) {
				$this->setConfiguration('lastCmdValue', $options['slider']);
				self::getRepository()->add($this);
			}
			if (isset($options['color'])) {
				$this->setConfiguration('lastCmdValue', $options['color']);
				self::getRepository()->add($this);
			}
		}
		if ($this->getConfiguration('updateCmdId') != '') {
			$cmd = self::getRepository()->get($this->getConfiguration('updateCmdId'));
			if (is_object($cmd)) {
				$value = $this->getConfiguration('updateCmdToValue');
				switch ($this->getSubType()) {
					case 'slider':
					$value = str_replace('#slider#', $options['slider'], $value);
					break;
					case 'color':
					$value = str_replace('#color#', $options['color'], $value);
					break;
				}
				$cmd->event($value);
			}
		}
		return $value;
	}
	
	public function getWidgetTemplateCode($_version = 'dashboard', $_noCustom = false) {
		$version = jeedom::versionAlias($_version);
		if (!$_noCustom && $this->getHtml('enable', 0) == 1 && $this->getHtml($_version) != '') {
			return $this->getHtml($_version);
		}
		$template_name = 'cmd.' . $this->getType() . '.' . $this->getSubType() . '.' . $this->getTemplate($version, 'default');
		if (array_key_exists($version . '::' . $template_name, self::$_templateArray)) {
            return self::$_templateArray[$version . '::' . $template_name];
        }

        $template = getTemplate('core', $version, $template_name);
        if ($template == '') {
            if (self::getConfig('widget')->get('active') == 1) {
                $template = getTemplate('core', $version, $template_name, 'widget');
            }
            if ($template == '') {
                foreach (plugin::listPlugin(true) as $plugin) {
                    $template = getTemplate('core', $version, $template_name, $plugin->getId());
                    if ($template != '') {
                        break;
                    }
                }
            }
            if ($template == '') {
                $template_name = 'cmd.' . $this->getType() . '.' . $this->getSubType() . '.default';
                $template = getTemplate('core', $version, $template_name);
            }
        }
        self::$_templateArray[$version . '::' . $template_name] = $template;

		return $template;
	}
	
	public function toHtml($_version = 'dashboard', $_options = '', $_cmdColor = null) {
		$version2 = jeedom::versionAlias($_version, false);
		if ($this->getDisplay('showOn' . $version2, 1) == 0) {
			return '';
		}
		$version = jeedom::versionAlias($_version);
		$html = '';
		$replace = array(
			'#id#' => $this->getId(),
			'#name#' => $this->getName(),
			'#name_display#' => ($this->getDisplay('icon') != '') ? $this->getDisplay('icon') : $this->getName(),
			'#history#' => '',
			'#displayHistory#' => 'display : none;',
			'#unite#' => $this->getUnite(),
			'#minValue#' => $this->getConfiguration('minValue', 0),
			'#maxValue#' => $this->getConfiguration('maxValue', 100),
			'#logicalId#' => $this->getLogicalId(),
			'#uid#' => 'cmd' . $this->getId() . eqLogic::UIDDELIMITER . mt_rand() . eqLogic::UIDDELIMITER,
			'#version#' => $_version,
			'#eqLogic_id#' => $this->getEqLogic()->getId(),
			'#hideCmdName#' => '',
		);
		if ($this->getConfiguration('listValue', '') != '') {
			$listOption = '';
			$elements = explode(';', $this->getConfiguration('listValue', ''));
			$foundSelect = false;
			foreach ($elements as $element) {
				$coupleArray = explode('|', $element);
				$cmdValue = $this->getCmdValue();
				if (is_object($cmdValue) && $cmdValue->getType() == 'info') {
					if ($cmdValue->execCmd() == $coupleArray[0]) {
						$listOption .= '<option value="' . $coupleArray[0] . '" selected>' . $coupleArray[1] . '</option>';
						$foundSelect = true;
					} else {
						$listOption .= '<option value="' . $coupleArray[0] . '">' . $coupleArray[1] . '</option>';
					}
				} else {
					$listOption .= '<option value="' . $coupleArray[0] . '">' . $coupleArray[1] . '</option>';
				}
			}
			if (!$foundSelect) {
				$listOption = '<option value="">Aucun</option>' . $listOption;
			}
			$replace['#listValue#'] = $listOption;
		}
		if ($this->getDisplay('showNameOn' . $version2, 1) == 0) {
			$replace['#hideCmdName#'] = 'display:none;';
		}
		if ($this->getDisplay('showIconAndName' . $version2, 0) == 1) {
			$replace['#name_display#'] = $this->getDisplay('icon') . ' ' . $this->getName();
		}
		$template = $this->getWidgetTemplateCode($_version);
		
		if ($_cmdColor === null && $version != 'scenario') {
			$eqLogic = $this->getEqLogic();
			$vcolor = ($version == 'mobile') ? 'mcmdColor' : 'cmdColor';
			if ($eqLogic->getPrimaryCategory() == '') {
				$replace['#cmdColor#'] = jeedom::getConfiguration('eqLogic:category:default:' . $vcolor);
			} else {
				$replace['#cmdColor#'] = jeedom::getConfiguration('eqLogic:category:' . $eqLogic->getPrimaryCategory() . ':' . $vcolor);
			}
		} else {
			$replace['#cmdColor#'] = $_cmdColor;
		}
		
		if ($this->getType() == 'info') {
			$replace['#state#'] = '';
			$replace['#tendance#'] = '';
			if ($this->getEqLogic()->getIsEnable() == 0) {
				$template = getTemplate('core', $version, 'cmd.error');
				$replace['#state#'] = 'N/A';
			} else {
				$replace['#state#'] = $this->execCmd();
				if (strpos($replace['#state#'], 'error::') !== false) {
					$template = getTemplate('core', $version, 'cmd.error');
					$replace['#state#'] = str_replace('error::', '', $replace['#state#']);
				} else {
					if ($this->getSubType() == 'binary' && $this->getDisplay('invertBinary') == 1) {
						$replace['#state#'] = ($replace['#state#'] == 1) ? 0 : 1;
					}
					if ($this->getSubType() == 'numeric' && trim($replace['#state#']) === '') {
						$replace['#state#'] = 0;
					}
				}
				if (method_exists($this, 'formatValueWidget')) {
					$replace['#state#'] = $this->formatValueWidget($replace['#state#']);
				}
			}
			
			$replace['#state#'] = str_replace(array("\'", "'"), array("'", "\'"), $replace['#state#']);
			$replace['#collectDate#'] = $this->getCollectDate();
			$replace['#valueDate#'] = $this->getValueDate();
			$replace['#alertLevel#'] = $this->getCache('alertLevel', 'none');
            $config = self::getConfig();
			if ($this->getIsHistorized() == 1) {
				$replace['#history#'] = 'history cursor';
				if ($config->get('displayStatsWidget') == 1
                    && strpos($template, '#displayHistory#') !== false
                    && $this->getDisplay('showStatsOn' . $version2, 1) == 1) {
                        $startHist = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' -' . $config->get('historyCalculPeriod') . ' hour'));
                        $replace['#displayHistory#'] = '';
                        $historyStatistique = $this->getStatistique($startHist, date('Y-m-d H:i:s'));
                        if ($historyStatistique['avg'] == 0 && $historyStatistique['min'] == 0 && $historyStatistique['max'] == 0) {
                            $replace['#averageHistoryValue#'] = round($replace['#state#'], 1);
                            $replace['#minHistoryValue#'] = round($replace['#state#'], 1);
                            $replace['#maxHistoryValue#'] = round($replace['#state#'], 1);
                        } else {
                            $replace['#averageHistoryValue#'] = round($historyStatistique['avg'], 1);
                            $replace['#minHistoryValue#'] = round($historyStatistique['min'], 1);
                            $replace['#maxHistoryValue#'] = round($historyStatistique['max'], 1);
                        }
                        $startHist = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' -' . $config->get('historyCalculTendance') . ' hour'));
                        $tendance = $this->getTendance($startHist, date('Y-m-d H:i:s'));
                        if ($tendance > $config->get('historyCalculTendanceThresholddMax')) {
                            $replace['#tendance#'] = 'fa fa-arrow-up';
                        } else if ($tendance < $config->get('historyCalculTendanceThresholddMin')) {
                            $replace['#tendance#'] = 'fa fa-arrow-down';
                        } else {
                            $replace['#tendance#'] = 'fa fa-minus';
                        }
                    }
			}
			$parameters = $this->getDisplay('parameters');
			if (is_array($parameters)) {
				foreach ($parameters as $key => $value) {
					$replace['#' . $key . '#'] = $value;
				}
			}
			return template_replace($replace, $template);
		} else {
			$cmdValue = $this->getCmdValue();
			if (is_object($cmdValue) && $cmdValue->getType() == 'info') {
				$replace['#state#'] = $cmdValue->execCmd();
				$replace['#valueName#'] = $cmdValue->getName();
				$replace['#unite#'] = $cmdValue->getUnite();
				if (trim($replace['#state#']) === '' && ($cmdValue->getSubtype() == 'binary' || $cmdValue->getSubtype() == 'numeric')) {
					$replace['#state#'] = 0;
				}
				if ($cmdValue->getSubType() == 'binary' && $cmdValue->getDisplay('invertBinary') == 1) {
					$replace['#state#'] = ($replace['#state#'] == 1) ? 0 : 1;
				}
			} else {
				$replace['#state#'] = ($this->getLastValue() !== null) ? $this->getLastValue() : '';
				$replace['#valueName#'] = $this->getName();
				$replace['#unite#'] = $this->getUnite();
			}
			$replace['#state#'] = str_replace(array("\'", "'"), array("'", "\'"), $replace['#state#']);
			$parameters = $this->getDisplay('parameters');
			if (is_array($parameters)) {
				foreach ($parameters as $key => $value) {
					$replace['#' . $key . '#'] = $value;
				}
			}
			
			$html .= template_replace($replace, $template);
			if (trim($html) == '') {
				return $html;
			}
			if ($_options != '') {
				$options = jeedom::toHumanReadable($_options);
				$options = is_json($options, $options);
				if (is_array($options)) {
					foreach ($options as $key => $value) {
						$replace['#' . $key . '#'] = $value;
					}
				}
			}
			if (!isset($replace['#title#'])) {
				$replace['#title#'] = '';
			}
			if (!isset($replace['#message#'])) {
				$replace['#message#'] = '';
			}
			if (!isset($replace['#slider#'])) {
				$replace['#slider#'] = '';
			}
			if (!isset($replace['#color#'])) {
				$replace['#color#'] = '';
			}
			$replace['#title_placeholder#'] = $this->getDisplay('title_placeholder', __('Titre', __FILE__));
			$replace['#message_placeholder#'] = $this->getDisplay('message_placeholder', __('Message', __FILE__));
			$replace['#message_cmd_type#'] = $this->getDisplay('message_cmd_type', 'info');
			$replace['#message_cmd_subtype#'] = $this->getDisplay('message_cmd_subtype', '');
			$replace['#message_disable#'] = $this->getDisplay('message_disable', 0);
			$replace['#title_disable#'] = $this->getDisplay('title_disable', 0);
			$replace['#title_color#'] = $this->getDisplay('title_color', 0);
			$replace['#title_possibility_list#'] = str_replace("'", "\'", $this->getDisplay('title_possibility_list', ''));
			$replace['#slider_placeholder#'] = $this->getDisplay('slider_placeholder', __('Valeur', __FILE__));
			$replace['#other_tooltips#'] = ($replace['#name#'] != $this->getName()) ? $this->getName() : '';
			$html = template_replace($replace, $html);
			return $html;
		}
	}
	
	public function event($_value, $_datetime = null, $_loop = 1) {
		if ($_loop > 4 || $this->getType() != 'info') {
			return;
		}
		$eqLogic = $this->getEqLogic();
		if (!is_object($eqLogic) || $eqLogic->getIsEnable() == 0) {
			return;
		}
		$value = $this->formatValue($_value);
		if ($this->getSubType() == 'numeric' && ($value > $this->getConfiguration('maxValue', $value) || $value < $this->getConfiguration('minValue', $value)) && strpos($value, 'error') === false) {
			log::add('cmd', 'info', __('La commande n\'est pas dans la plage de valeur autorisée : ', __FILE__) . $this->getHumanName() . ' => ' . $value);
			return;
		}
		if ($this->getConfiguration('denyValues') != '' && in_array($value, explode(';', $this->getConfiguration('denyValues')))) {
			return;
		}
		$oldValue = $this->execCmd();
		$repeat = ($oldValue == $value && $oldValue !== '' && $oldValue !== null);
		$this->setCollectDate(($_datetime !== null) ? $_datetime : date('Y-m-d H:i:s'));
		$this->setCache('collectDate', $this->getCollectDate());
		$this->setValueDate(($repeat) ? $this->getValueDate() : $this->getCollectDate());
		$eqLogic->setStatus(array('lastCommunication' => $this->getCollectDate(), 'timeout' => 0));
		$display_value = $value;
		if (method_exists($this, 'formatValueWidget')) {
			$display_value = $this->formatValueWidget($value);
		} else if ($this->getSubType() == 'binary' && $this->getDisplay('invertBinary') == 1) {
			$display_value = ($value == 1) ? 0 : 1;
		} else if ($this->getSubType() == 'numeric' && trim($value) === '') {
			$display_value = 0;
		} else if ($this->getSubType() == 'binary' && trim($value) === '') {
			$display_value = 0;
		}
		if ($repeat && $this->getConfiguration('repeatEventManagement', 'auto') == 'never') {
			$this->addHistoryValue($value, $this->getCollectDate());
			$eqLogic->emptyCacheWidget();
			event::adds('cmd::update', array(array('cmd_id' => $this->getId(), 'value' => $value, 'display_value' => $display_value, 'valueDate' => $this->getValueDate(), 'collectDate' => $this->getCollectDate())));
			return;
		}
		$_loop++;
		if ($repeat && ($this->getConfiguration('repeatEventManagement', 'auto') == 'always' || $this->getSubtype() == 'binary')) {
			$repeat = false;
		}
		$message = __('Evènement sur la commande ', __FILE__) . $this->getHumanName() . __(' valeur : ', __FILE__) . $value;
		if ($repeat) {
			$message .= ' (répétition)';
		}
		log::add('event', 'info', $message);
		$events = array();
		if (!$repeat) {
			$this->setCache(array('value' => $value, 'valueDate' => $this->getValueDate()));
			scenario::check($this);
			$eqLogic->emptyCacheWidget();
			$level = $this->checkAlertLevel($value);
			$events[] = array('cmd_id' => $this->getId(), 'value' => $value, 'display_value' => $display_value, 'valueDate' => $this->getValueDate(), 'collectDate' => $this->getCollectDate(), 'alertLevel' => $level);
			$foundInfo = false;
			$value_cmd = self::getRepository()->findByValue($this->getId(), null, true);
			if (is_array($value_cmd) && count($value_cmd) > 0) {
				foreach ($value_cmd as $cmd) {
					if ($cmd->getType() == 'action') {
						if (!$repeat) {
							$events[] = [
							    'cmd_id' => $cmd->getId(),
                                'value' => $value,
                                'display_value' => $display_value,
                                'valueDate' => $this->getValueDate(),
                                'collectDate' => $this->getCollectDate()
                            ];
						}
					} else {
						if ($_loop > 1) {
							$cmd->event($cmd->execute(), null, $_loop);
						} else {
							$foundInfo = true;
						}
					}
				}
			}
			if ($foundInfo) {
				listener::backgroundCalculDependencyCmd($this->getId());
			}
		} else {
			$events[] = [
			    'cmd_id' => $this->getId(),
                'value' => $value,
                'display_value' => $display_value,
                'valueDate' => $this->getValueDate(),
                'collectDate' => $this->getCollectDate()
            ];
		}
		if (count($events) > 0) {
			event::adds('cmd::update', $events);
		}
		if (!$repeat) {
			listener::check($this->getId(), $value, $this->getCollectDate());
			jeeObject::checkSummaryUpdate($this->getId());
		}
		$this->addHistoryValue($value, $this->getCollectDate());
		$this->checkReturnState($value);
		if (!$repeat) {
			$this->checkCmdAlert($value);
			if (isset($level) && $level != $this->getCache('alertLevel')) {
				$this->actionAlertLevel($level, $value);
			}
			if ($this->getConfiguration('timeline::enable')) {
				jeedom::addTimelineEvent([
				    'type' => 'cmd',
                    'subtype' => 'info',
                    'cmdType' => $this->getSubType(),
                    'id' => $this->getId(),
                    'name' => $this->getHumanName(true),
                    'datetime' => $this->getValueDate(),
                    'value' => $value . $this->getUnite()
                ]);
			}
			$this->pushUrl($value);
		}
	}
	
	public function checkReturnState($_value) {
		if (is_numeric($this->getConfiguration('returnStateTime')) && $this->getConfiguration('returnStateTime') > 0 && $_value != $this->getConfiguration('returnStateValue') && trim($this->getConfiguration('returnStateValue')) != '') {
			$cron = cron::byClassAndFunction('cmd', 'returnState', array('cmd_id' => intval($this->getId())));
			if (!is_object($cron)) {
				$cron = new cron();
			}
			$cron->setClass('cmd');
			$cron->setFunction('returnState');
			$cron->setOnce(1);
			$cron->setOption(array('cmd_id' => intval($this->getId())));
			$next = strtotime('+ ' . ($this->getConfiguration('returnStateTime') + 1) . ' minutes ' . date('Y-m-d H:i:s'));
			$cron->setSchedule(cron::convertDateToCron($next));
			$cron->setLastRun(date('Y-m-d H:i:s'));
			$cron->save();
		}
	}
	
	public function checkCmdAlert($_value) {
		if ($this->getConfiguration('jeedomCheckCmdOperator') == '' || $this->getConfiguration('jeedomCheckCmdTest') == '' || is_nan($this->getConfiguration('jeedomCheckCmdTime', 0))) {
			return;
		}
		$check = jeedom::evaluateExpression($_value . $this->getConfiguration('jeedomCheckCmdOperator') . $this->getConfiguration('jeedomCheckCmdTest'));
		if ($check == 1 || $check || $check == '1') {
			if ($this->getConfiguration('jeedomCheckCmdTime', 0) == 0) {
				$this->executeAlertCmdAction();
				return;
			}
			$next = strtotime('+ ' . ($this->getConfiguration('jeedomCheckCmdTime') + 1) . ' minutes ' . date('Y-m-d H:i:s'));
			$cron = cron::byClassAndFunction('cmd', 'cmdAlert', ['cmd_id' => (int) $this->getId()]);
			if (!is_object($cron)) {
				$cron = new cron();
			} else {
				$nextRun = $cron->getNextRunDate();
                $nextRunTime = strtotime($nextRun);
                if ($nextRun !== false && $next > $nextRunTime && $nextRunTime > time()) {
					return;
				}
			}
			$cron->setClass('cmd');
			$cron->setFunction('cmdAlert');
			$cron->setOnce(1);
			$cron->setOption(array('cmd_id' => (int) $this->getId()));
			$cron->setSchedule(cron::convertDateToCron($next));
			$cron->setLastRun(date('Y-m-d H:i:s'));
			$cron->save();
		} else {
			$cron = cron::byClassAndFunction('cmd', 'cmdAlert', array('cmd_id' => (int) $this->getId()));
			if (is_object($cron)) {
				$cron->remove();
			}
		}
	}
	
	public function executeAlertCmdAction() {
		if (!is_array($this->getConfiguration('actionCheckCmd'))) {
			return;
		}
		foreach ($this->getConfiguration('actionCheckCmd') as $action) {
			try {
				$options = array();
				if (isset($action['options'])) {
					$options = $action['options'];
				}
				scenarioExpression::createAndExec('action', $action['cmd'], $options);
			} catch (Exception $e) {
				log::add('cmd', 'error', __('Erreur lors de l\'exécution de ', __FILE__) . $action['cmd'] . __('. Détails : ', __FILE__) . $e->getMessage());
			}
		}
	}
	
	public function checkAlertLevel($_value, $_allowDuring = true) {
		if ($this->getType() != 'info' || ($this->getAlert('warningif') == '' && $this->getAlert('dangerif') == '')) {
			return 'none';
		}
		global $JEEDOM_INTERNAL_CONFIG;
		
		$currentLevel = 'none';
		foreach ($JEEDOM_INTERNAL_CONFIG['alerts'] as $level => $value) {
			if (!$value['check']) {
				continue;
			}
			if ($this->getAlert($level . 'if') != '') {
				$check = jeedom::evaluateExpression(str_replace('#value#', $_value, $this->getAlert($level . 'if')));
				if ($check == 1 || $check || $check == '1') {
					$currentLevel = $level;
				}
			}
		}
		$level = $this->getEqLogic()->getAlert();
		if (is_array($level) && isset($level['name']) && $currentLevel == strtolower($level['name'])) {
			return $currentLevel;
		}
		if ($_allowDuring && $this->getAlert($currentLevel . 'during') != '' && $this->getAlert($currentLevel . 'during') > 0) {
			$cron = cron::byClassAndFunction('cmd', 'duringAlertLevel', array('cmd_id' => (int) $this->getId()));
			$next = strtotime('+ ' . $this->getAlert($currentLevel . 'during', 1) . ' minutes ' . date('Y-m-d H:i:s'));
			if (!is_object($cron)) {
				$cron = new cron();
			} else {
				$nextRun = $cron->getNextRunDate();
				if ($nextRun !== false && $next > strtotime($nextRun) && strtotime($nextRun) > strtotime('now')) {
					return 'none';
				}
			}
			$cron->setClass('cmd');
			$cron->setFunction('duringAlertLevel');
			$cron->setOnce(1);
			$cron->setOption(array('cmd_id' => intval($this->getId())));
			$cron->setSchedule(cron::convertDateToCron($next));
			$cron->setLastRun(date('Y-m-d H:i:s'));
			$cron->save();
			return 'none';
		}
		if ($_allowDuring && $currentLevel == 'none') {
			$cron = cron::byClassAndFunction('cmd', 'duringAlertLevel', array('cmd_id' => (int) $this->getId()));
			if (is_object($cron)) {
				$cron->remove(false);
			}
		}
		return $currentLevel;
	}
	
	public static function duringAlertLevel($_options) {
		$cmd = self::getRepository()->get($_options['cmd_id']);
		if (!is_object($cmd)) {
			return;
		}
		if ($cmd->getType() != 'info') {
			return;
		}
		$value = $cmd->execCmd();
		$level = $cmd->checkAlertLevel($value, false);
		if ($level != 'none') {
			$cmd->actionAlertLevel($level, $value);
		}
	}
	
	public function actionAlertLevel($_level, $_value) {
		if ($this->getType() != 'info') {
			return;
		}
		global $JEEDOM_INTERNAL_CONFIG;
		$this->setCache('alertLevel', $_level);
		$eqLogic = $this->getEqLogic();
		$maxAlert = $eqLogic->getMaxCmdAlert();
		$prevAlert = $eqLogic->getAlert();
		if (!$_value) {
			$_value = $this->execCmd();
		}
		$config = self::getConfig();
		if ($_level != 'none') {
			$message = __('Alert sur la commande ', __FILE__) . $this->getHumanName() . __(' niveau ', __FILE__) . $_level . __(' valeur : ', __FILE__) . $_value . trim(' ' . $this->getUnite());
			if ($this->getAlert($_level . 'during') != '' && $this->getAlert($_level . 'during') > 0) {
				$message .= ' ' . __('pendant plus de ', __FILE__) . $this->getAlert($_level . 'during') . __(' minute(s)', __FILE__);
			}
			$message .= ' => ' . str_replace('#value#', $_value, $this->getAlert($_level . 'if'));
			log::add('event', 'info', $message);
			$eqLogic = $this->getEqLogic();
			if ($config->get('alert::addMessageOn' . ucfirst($_level)) == 1) {
				message::add($eqLogic->getEqType_name(), $message);
			}
			$cmds = explode('&&', $config->get('alert::' . $_level . 'Cmd'));
			if (count($cmds) > 0 && trim($config->get('alert::' . $_level . 'Cmd')) != '') {
				foreach ($cmds as $id) {
					$cmd = self::getRepository()->get(str_replace('#', '', $id));
					if (is_object($cmd)) {
						$cmd->execCmd(array(
							'title' => __('[' . $config->get('name', 'JEEDOM') . '] ', __FILE__) . $message,
							'message' => $config->get('name', 'JEEDOM') . ' : ' . $message,
						));
					}
				}
			}
		}
		
		if ($prevAlert != $maxAlert) {
			$status = array(
				'warning' => 0,
				'danger' => 0,
			);
			if ($maxAlert != 'none' && isset($JEEDOM_INTERNAL_CONFIG['alerts'][$maxAlert])) {
				$status[$maxAlert] = 1;
			}
			$eqLogic->setStatus($status);
			$eqLogic->refreshWidget();
		}
	}
	
	public function pushUrl($_value) {
		$url = $this->getConfiguration('jeedomPushUrl');
		if ($url == '') {
			$url = self::getConfig()->get('cmdPushUrl');
		}
		if ($url == '') {
			return;
		}
		$replace = array(
			'#value#' => urlencode($_value),
			'#cmd_name#' => urlencode($this->getName()),
			'#cmd_id#' => $this->getId(),
			'#humanname#' => urlencode($this->getHumanName()),
			'#eq_name#' => urlencode($this->getEqLogic()->getName()),
		);
		$url = str_replace(array_keys($replace), $replace, $url);
		log::add('event', 'info', __('Appels de l\'URL de push pour la commande ', __FILE__) . $this->getHumanName() . ' : ' . $url);
		$http = new com_http($url);
		$http->setLogError(false);
		try {
			$http->exec();
		} catch (Exception $e) {
			log::add('cmd', 'error', __('Erreur push sur : ', __FILE__) . $url . ' => ' . $e->getMessage());
		} catch (Error $e) {
			log::add('cmd', 'error', __('Erreur push sur : ', __FILE__) . $url . ' => ' . $e->getMessage());
		}
	}
	
	public function generateAskResponseLink($_response, $_plugin = 'core', $_network = 'external') {
		$token = $this->getCache('ask::token', ApiKey::generate());
		$this->setCache(array('ask::count' => 0, 'ask::token' => $token));
		$return = network::getNetworkAccess($_network) . '/core/api/jeeApi.php?';
		$return .= 'type=ask';
		$return .= '&plugin=' . $_plugin;
		$return .= '&apikey=' . jeedom::getApiKey($_plugin);
		$return .= '&token=' . $token;
		$return .= '&response=' . urlencode($_response);
		$return .= '&cmd_id=' . $this->getId();
		return $return;
	}
	
	public function askResponse($_response) {
		if ($this->getCache('ask::variable', 'none') == 'none') {
			return false;
		}
		$askEndTime = $this->getCache('ask::endtime', null);
		if ($askEndTime === null || $askEndTime < strtotime('now')) {
			return false;
		}
		$dataStore = new dataStore();
		$dataStore->setType('scenario');
		$dataStore->setKey($this->getCache('ask::variable', 'none'));
		$dataStore->setValue($_response);
		$dataStore->setLink_id(-1);
		$dataStore->save();
		$this->setCache(array('ask::variable' => 'none', 'ask::count' => 0, 'ask::token' => null, 'ask::endtime' => null));
		return true;
	}
	
	public function emptyHistory($_date = '') {
		return history::emptyHistory($this->getId(), $_date);
	}
	
	public function addHistoryValue($_value, $_datetime = '') {
		if ($this->getIsHistorized() == 1 && ($_value === null || ($_value !== '' && $this->getType() == 'info' && $_value <= $this->getConfiguration('maxValue', $_value) && $_value >= $this->getConfiguration('minValue', $_value)))) {
			$history = new history();
			$history->setCmd_id($this->getId());
			$history->setValue($_value);
			$history->setDatetime($_datetime);
			return $history->save($this);
		}
	}
	
	public function getStatistique($_startTime, $_endTime) {
		if ($this->getType() != 'info' || $this->getType() == 'string') {
			return array();
		}
		return history::getStatistique($this->getId(), $_startTime, $_endTime);
	}
	
	public function getTendance($_startTime, $_endTime) {
		return history::getTendance($this->getId(), $_startTime, $_endTime);
	}
	
	public function getCmdValue() {
		$cmd = self::getRepository()->get(str_replace('#', '', $this->getValue()));
		if (is_object($cmd)) {
			return $cmd;
		}
		return false;
	}
	
	public function getHumanName($_tag = false, $_prettify = false) {
		$name = '';
		$eqLogic = $this->getEqLogic();
		if (is_object($eqLogic)) {
			$name .= $eqLogic->getHumanName($_tag, $_prettify);
		}
		if ($_tag) {
			$name .= ' - ' . $this->getName();
		} else {
			$name .= '[' . $this->getName() . ']';
		}
		return $name;
	}
	
	public function getHistory($_dateStart = null, $_dateEnd = null) {
		return history::all($this->id, $_dateStart, $_dateEnd);
	}
	
	public function getPluralityHistory($_dateStart = null, $_dateEnd = null, $_period = 'day', $_offset = 0) {
		return history::getPlurality($this->id, $_dateStart, $_dateEnd, $_period, $_offset);
	}

    /**
     * @param string $_key
     * @param bool $_default
     *
     * @return array|bool|mixed
     * @throws ReflectionException
     */
	public function widgetPossibility($_key = '', $_default = true) {
		$class = new ReflectionClass($this->getEqType_name());
		$method_toHtml = $class->getMethod('toHtml');
		$return = array();
		if ($method_toHtml->class == 'eqLogic') {
			$return['custom'] = true;
		} else {
			$return['custom'] = false;
		}
		$class = new ReflectionClass($this->getEqType_name() . 'Cmd');
		$method_toHtml = $class->getMethod('toHtml');
		if ($method_toHtml->class == 'cmd') {
			$return['custom'] = true;
		} else {
			$return['custom'] = false;
		}
		$class = $this->getEqType_name() . 'Cmd';
		if (property_exists($class, '_widgetPossibility')) {
			$return = $class::$_widgetPossibility;
			if ($_key != '') {
				$keys = explode('::', $_key);
				foreach ($keys as $k) {
					if (!isset($return[$k])) {
						return false;
					}
					if (is_array($return[$k])) {
						$return = $return[$k];
					} else {
						return $return[$k];
					}
				}
				if (is_array($return)) {
					return $_default;
				}
				return $return;
			}
		}
		
		if ($_key != '') {
			if (isset($return['custom']) && !isset($return[$_key])) {
				return $return['custom'];
			}
			return isset($return[$_key]) ? $return[$_key] : $_default;
		}
		return $return;
	}
	
	public function export() {
		$cmd = clone $this;
		$cmd->setId('');
		$cmd->setOrder('');
		$cmd->setEqLogic_id('');
		$cmd->setDisplay('graphType', '');
		$cmdValue = $cmd->getCmdValue();
		if (is_object($cmdValue)) {
			$cmd->setValue($cmdValue->getName());
		} else {
			$cmd->setValue('');
		}
		$return = utils::o2a($cmd);
		foreach ($return as $key => $value) {
			if (is_array($value)) {
				foreach ($value as $key2 => $value2) {
					if ($value2 == '') {
						unset($return[$key][$key2]);
					}
				}
			} elseif ($value == '') {
                unset($return[$key]);
            }
		}
		if (isset($return['configuration']) && count($return['configuration']) === 0) {
			unset($return['configuration']);
		}
		if (isset($return['display']) && count($return['display']) === 0) {
			unset($return['display']);
		}
		return $return;
	}
	
	public function getDirectUrlAccess() {
		$url = '/core/api/jeeApi.php?apikey=' . self::getConfig()->get('api') . '&type=cmd&id=' . $this->getId();
		if ($this->getType() == 'action') {
			switch ($this->getSubType()) {
				case 'slider':
				$url .= '&slider=50';
				break;
				case 'color':
				$url .= '&color=#123456';
				break;
				case 'message':
				$url .= '&title=montitre&message=monmessage';
				break;
				case 'select':
				$url .= '&select=value';
				break;
			}
		}
		return network::getNetworkAccess('external') . $url;
	}
	
	public function checkAccessCode($_code) {
		if ($this->getType() != 'action' || trim($this->getConfiguration('actionCodeAccess')) == '') {
			return true;
		}
		if (sha1($_code) == $this->getConfiguration('actionCodeAccess')) {
			$this->setConfiguration('actionCodeAccess', sha512($_code));
			self::getRepository()->add($this);
			return true;
		}
		if (sha512($_code) == $this->getConfiguration('actionCodeAccess')) {
			return true;
		}
		return false;
	}
	
	public function exportApi() {
		$return = utils::o2a($this);
		$return['currentValue'] = ($this->getType() !== 'action') ? $this->execCmd(null, 2) : $this->getConfiguration('lastCmdValue', null);
		return $return;
	}
	
	public function getLinkData(&$_data = array('node' => array(), 'link' => array()), $_level = 0, $_drill = null) {
		if ($_drill === null) {
			$_drill = self::getConfig()->get('graphlink::cmd::drill');
		}
		if (isset($_data['node']['cmd' . $this->getId()])) {
			return;
		}
		$_level++;
		if ($_level > $_drill) {
			return $_data;
		}
		$icon = ($this->getType() == 'info') ? findCodeIcon('fa-eye') : findCodeIcon('fa-hand-paper-o');
		$_data['node']['cmd' . $this->getId()] = array(
			'id' => 'cmd' . $this->getId(),
			'name' => $this->getName(),
			'icon' => $icon['icon'],
			'fontfamily' => $icon['fontfamily'],
			'fontsize' => '1.5em',
			'texty' => -14,
			'textx' => 0,
			'fontweight' => ($_level == 1) ? 'bold' : 'normal',
			'title' => $this->getHumanName(),
			'url' => $this->getEqLogic()->getLinkToConfiguration(),
		);
		$usedBy = $this->getUsedBy();
		$use = $this->getUse();
		addGraphLink($this, 'cmd', $usedBy['scenario'], 'scenario', $_data, $_level, $_drill);
		addGraphLink($this, 'cmd', $usedBy['eqLogic'], 'eqLogic', $_data, $_level, $_drill);
		addGraphLink($this, 'cmd', $usedBy['cmd'], 'cmd', $_data, $_level, $_drill);
		addGraphLink($this, 'cmd', $usedBy['interactDef'], 'interactDef', $_data, $_level, $_drill, array('dashvalue' => '2,6', 'lengthfactor' => 0.6));
		addGraphLink($this, 'cmd', $usedBy['plan'], 'plan', $_data, $_level, $_drill, array('dashvalue' => '2,6', 'lengthfactor' => 0.6));
		addGraphLink($this, 'cmd', $usedBy['view'], 'view', $_data, $_level, $_drill, array('dashvalue' => '2,6', 'lengthfactor' => 0.6));
		addGraphLink($this, 'cmd', $use['scenario'], 'scenario', $_data, $_level, $_drill);
		addGraphLink($this, 'cmd', $use['eqLogic'], 'eqLogic', $_data, $_level, $_drill);
		addGraphLink($this, 'cmd', $use['cmd'], 'cmd', $_data, $_level, $_drill);
		addGraphLink($this, 'cmd', $use['dataStore'], 'dataStore', $_data, $_level, $_drill);
		addGraphLink($this, 'cmd', $this->getEqLogic(), 'eqLogic', $_data, $_level, $_drill, array('dashvalue' => '1,0', 'lengthfactor' => 0.6));
		return $_data;
	}
	
	public function getUsedBy($_array = false) {
		$return = ['cmd' => [], 'eqLogic' => [], 'scenario' => [], 'plan' => [], 'view' => []];
		$return['cmd'] = self::getRepository()->searchConfiguration('#' . $this->getId() . '#');
		$return['eqLogic'] = eqLogic::searchConfiguration('#' . $this->getId() . '#');
		$return['scenario'] = scenario::searchByUse(array(array('action' => '#' . $this->getId() . '#')));
		$return['interactDef'] = interactDef::searchByUse('#' . $this->getId() . '#');
		$return['view'] = view::searchByUse('cmd', $this->getId());
		$return['plan'] = planHeader::searchByUse('cmd', $this->getId());
		if ($_array) {
			foreach ($return as &$value) {
				$value = utils::o2a($value);
			}
		}
		return $return;
	}
	
	public function getUse() {
		$json = jeedom::fromHumanReadable(json_encode(utils::o2a($this)));
		return jeedom::getTypeUse($json);
	}
	
	public function hasRight($_user = null) {
		if ($this->getType() == 'action') {
			return $this->getEqLogic()->hasRight('x', $_user);
		} else {
			return $this->getEqLogic()->hasRight('r', $_user);
		}
	}
	
	/*     * **********************Getteur Setteur*************************** */
	
	public function getId() {
		return $this->id;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function getGeneric_type() {
		return $this->generic_type;
	}
	
	public function setGeneric_type($_generic_type) {
		$this->generic_type = $_generic_type;
		return $this;
	}
	
	public function getType() {
		return $this->type;
	}
	
	public function getSubType() {
		return $this->subType;
	}
	
	public function getEqType_name() {
		return $this->eqType;
	}
	
	public function getEqLogic_id() {
		return $this->eqLogic_id;
	}
	
	public function getIsHistorized() {
		return $this->isHistorized;
	}
	
	public function getUnite() {
		return $this->unite;
	}
	
	public function getEqLogic() {
		if ($this->_eqLogic === null) {
			$this->setEqLogic(eqLogic::byId($this->eqLogic_id));
		}
		return $this->_eqLogic;
	}
	
	public function setEqLogic($_eqLogic) {
		$this->_eqLogic = $_eqLogic;
		return $this;
	}
	
	public function getEventOnly() {
		return 1;
	}
	
	public function setId($id = '') {
		$this->id = $id;
		return $this;
	}
	
	/**
	*
	* @param type $name
	* @return $this
	*/
	public function setName($name) {
		$this->name = str_replace(array('&', '#', ']', '[', '%', "'"), '', $name);
		return $this;
	}
	
	public function setType($type) {
		$this->type = $type;
		return $this;
	}
	
	public function setSubType($subType) {
		$this->subType = $subType;
		return $this;
	}
	
	public function setEqLogic_id($eqLogic_id) {
		$this->eqLogic_id = $eqLogic_id;
		return $this;
	}
	
	public function setIsHistorized($isHistorized) {
		$this->isHistorized = $isHistorized;
		return $this;
	}
	
	public function setUnite($unite) {
		$this->unite = $unite;
		return $this;
	}

    /**
     * @deprecated This method is deprecated
     */
	public function setEventOnly($eventOnly) {
		trigger_error('This method is deprecated', E_USER_DEPRECATED);
	}
	
	public function getHtml($_key = '', $_default = '') {
		return utils::getJsonAttr($this->html, $_key, $_default);
	}
	
	public function setHtml($_key, $_value) {
		if (in_array($_key, array('dashboard', 'mobile', 'dview', 'mview', 'dplan')) && $this->getWidgetTemplateCode($_key, true) == $_value) {
			$_value = '';
		}
		if ($this->getHtml($_key) != $_value) {
			$this->_needRefreshWidget = true;
		}
		$this->html = utils::setJsonAttr($this->html, $_key, $_value);
		return $this;
	}
	
	public function getTemplate($_key = '', $_default = '') {
		return utils::getJsonAttr($this->template, $_key, $_default);
	}
	
	public function setTemplate($_key, $_value) {
		if ($this->getTemplate($_key) != $_value) {
			$this->_needRefreshWidget = true;
		}
		$this->template = utils::setJsonAttr($this->template, $_key, $_value);
		return $this;
	}
	
	public function getConfiguration($_key = '', $_default = '') {
		return utils::getJsonAttr($this->configuration, $_key, $_default);
	}
	
	public function setConfiguration($_key, $_value) {
		if ($_key == 'actionCodeAccess' && $_value != '') {
			if (!is_sha1($_value) && !is_sha512($_value)) {
				$_value = sha512($_value);
			}
		}
		$this->configuration = utils::setJsonAttr($this->configuration, $_key, $_value);
		return $this;
	}
	
	public function getDisplay($_key = '', $_default = '') {
		return utils::getJsonAttr($this->display, $_key, $_default);
	}
	
	public function setDisplay($_key, $_value) {
		if ($this->getDisplay($_key) != $_value) {
			$this->_needRefreshWidget = true;
		}
		$this->display = utils::setJsonAttr($this->display, $_key, $_value);
		return $this;
	}
	
	public function getAlert($_key = '', $_default = '') {
		return utils::getJsonAttr($this->alert, $_key, $_default);
	}
	
	public function setAlert($_key, $_value) {
		$this->alert = utils::setJsonAttr($this->alert, $_key, $_value);
		$this->_needRefreshAlert = true;
		return $this;
	}
	
	public function getCollectDate() {
		return $this->_collectDate;
	}
	
	public function setCollectDate($_collectDate) {
		$this->_collectDate = $_collectDate;
		return $this;
	}
	
	public function getValueDate() {
		return $this->_valueDate;
	}
	
	public function setValueDate($_valueDate) {
		$this->_valueDate = $_valueDate;
		return $this;
	}
	
	public function getValue() {
		return $this->value;
	}
	
	public function setValue($value) {
		$this->value = $value;
		return $this;
	}
	
	public function getIsVisible() {
		return $this->isVisible;
	}
	
	public function setIsVisible($isVisible) {
		if ($this->isVisible != $isVisible) {
			$this->_needRefreshWidget = true;
		}
		$this->isVisible = $isVisible;
		return $this;
	}
	
	public function getOrder() {
		if ($this->order == '') {
			return 0;
		}
		return $this->order;
	}
	
	public function setOrder($order) {
		if ($this->order != $order) {
			$this->_needRefreshWidget = true;
		}
		$this->order = $order;
		return $this;
	}
	
	public function getLogicalId() {
		return $this->logicalId;
	}
	
	public function setLogicalId($logicalId) {
		$this->logicalId = $logicalId;
		return $this;
	}
	
	public function getEqType() {
		return $this->eqType;
	}
	
	public function setEqType($eqType) {
		$this->eqType = $eqType;
		return $this;
	}
	
	public function getCache($_key = '', $_default = '') {
		$cache = cache::byKey('cmdCacheAttr' . $this->getId())->getValue();
		return utils::getJsonAttr($cache, $_key, $_default);
	}
	
	public function setCache($_key, $_value = null) {
		cache::set('cmdCacheAttr' . $this->getId(), utils::setJsonAttr(cache::byKey('cmdCacheAttr' . $this->getId())->getValue(), $_key, $_value));
		return $this;
	}

	public function needRefreshWidget()
    {
        return $this->_needRefreshWidget;
    }

    public function needRefreshAlert()
    {
        return $this->_needRefreshAlert;
    }

    public function refreshWidget($refresh)
    {
        $this->_needRefreshWidget = $refresh;
    }

    /** **************************** Dépendances ************************************** */

    /**
     * @return CommandRepository
     */
    private static function getRepository()
    {
        return RepositoryFactory::build(CommandRepository::class);
    }

    /**
     * @param $plugin
     *
     * @return Configuration
     */
    private static function getConfig($plugin = 'core')
    {
        return ConfigurationFactory::build($plugin);
    }

    /**
     *
     */
    private static function getCommandMap()
    {
        return ServiceFactory::build(HumanCommandMap::class);
    }

    private static function getColorConverter() {
        return ServiceFactory::build(ColorConverter::class);
    }

    private static function getWidgetService()
    {
        return ServiceFactory::build(WidgetService::class);
    }
}
