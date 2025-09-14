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

/** @internal */
final class Translation{

    /** @var string  */
    private $key;

    /** @var string  */
    private $file;

    /** @var bool */
    private $backslash;

    /** @var string */
    private $prefix;

    /** @var string */
    private $suffix;

    public function __construct(string $key, string $file, bool $backslash, string $prefix, string $suffix) {
        $this->key = $key;
        $this->file = $file;
        $this->backslash = $backslash;
        $this->prefix = $prefix;
        $this->suffix = $suffix;
    }

    public function __toString(): string {
        return $this->prefix.\translate::sentence(str_replace("\'", "'", $this->key), $this->file, $this->backslash).$this->suffix;
    }
}

function t(string $key, string $file, bool $backslash = false, string $prefix = '', string $suffix = ''): Translation {
    return new Translation($key, $file, $backslash, $prefix, $suffix);
}

global $JEEDOM_INTERNAL_CONFIG;
$JEEDOM_INTERNAL_CONFIG = array(
	'eqLogic' => array(
		'category' => array(
			'heating' => array('name' => t('Chauffage', __FILE__), 'icon' => 'fas fa-fire'),
			'security' => array('name' => t('Sécurité', __FILE__), 'icon' => 'fas fa-lock'),
			'energy' => array('name' => t('Energie', __FILE__), 'icon' => 'fas fa-bolt'),
			'light' => array('name' => t('Lumière', __FILE__), 'icon' => 'far fa-lightbulb'),
			'opening' => array('name' => t('Ouvrant', __FILE__), 'icon' => 'fas fa-door-open'),
			'automatism' => array('name' => t('Automatisme', __FILE__), 'icon' => 'fas fa-magic'),
			'multimedia' => array('name' => t('Multimédia', __FILE__), 'icon' => 'fas fa-sliders-h'),
			'default' => array('name' => t('Autre', __FILE__), 'icon' => 'far fa-circle'),
		),
		'displayType' => array(
			'dashboard' => array('name' => 'Dashboard'),
			'mobile' => array('name' => 'Mobile'),
		),
	),
	'interact' => array(
		'test' => array(
			'>' => array('superieur', '>', 'plus de', 'depasse'),
			'<' => array('inferieur', '<', 'moins de', 'descends en dessous'),
			'=' => array('egale', '=', 'vaut'),
			'!=' => array('different'),
		),
	),
	'plugin' => array(
		'category' => array(
			'security' => array('name' => t('Sécurité', __FILE__), 'icon' => 'fas fa-lock'),
			'automation protocol' => array('name' => t('Protocole domotique', __FILE__), 'icon' => 'fas fa-rss'),
			'home automation protocol' => array('name' => t('Passerelle domotique', __FILE__), 'icon' => 'fas fa-asterisk'),
			'programming' => array('name' => t('Programmation', __FILE__), 'icon' => 'fas fa-code'),
			'organization' => array('name' => t('Organisation', __FILE__), 'icon' => 'far fa-calendar-alt', 'alias' => array('travel', 'finance')),
			'weather' => array('name' => t('Météo', __FILE__), 'icon' => 'far fa-sun'),
			'communication' => array('name' => t('Communication', __FILE__), 'icon' => 'fas fa-comment'),
			'devicecommunication' => array('name' => t('Objets connectés', __FILE__), 'icon' => 'fas fa-language'),
			'multimedia' => array('name' => t('Multimédia', __FILE__), 'icon' => 'fas fa-sliders-h'),
			'wellness' => array('name' => t('Confort', __FILE__), 'icon' => 'far fa-user'),
			'monitoring' => array('name' => t('Monitoring', __FILE__), 'icon' => 'fas fa-tachometer-alt'),
			'health' => array('name' => t('Santé', __FILE__), 'icon' => 'icon loisir-runner5'),
			'nature' => array('name' => t('Nature', __FILE__), 'icon' => 'icon nature-leaf32'),
			'automatisation' => array('name' => t('Automatisme', __FILE__), 'icon' => 'fas fa-magic'),
			'energy' => array('name' => t('Energie', __FILE__), 'icon' => 'fas fa-bolt'),
			'other' => array('name' => t('Autre', __FILE__), 'icon' => 'fas fa-bars'),
		),
	),
	'messageChannel' => array(
		'alerting' => array('name' => t('Alerte des commandes', __FILE__), 'icon' => '<i class="far fa-bell"></i>'),
		'alertingReturnBack' => array('name' => t('Retour à l\'état normal des commandes', __FILE__), 'icon' => '<i class="fas fa-check"></i>')
	),
	'alerts' => array(
		'timeout' => array('name' => t('Timeout', __FILE__), 'icon' => 'far fa-clock', 'level' => 6, 'check' => false, 'color' => 'var(--al-danger-color)'),
		'batterywarning' => array('name' => t('Batterie en Warning', __FILE__), 'icon' => 'fas fa-battery-quarter', 'level' => 2, 'check' => false, 'color' => 'var(--al-warning-color)'),
		'batterydanger' => array('name' => t('Batterie en Danger', __FILE__), 'icon' => 'fas fa-battery-empty', 'level' => 3, 'check' => false, 'color' => 'var(--al-danger-color)'),
		'warning' => array('name' => t('Warning', __FILE__), 'icon' => 'fas fa-bell', 'level' => 4, 'check' => true, 'color' => 'var(--al-warning-color)'),
		'danger' => array('name' => t('Danger', __FILE__), 'icon' => 'fas fa-exclamation', 'level' => 5, 'check' => true, 'color' => 'var(--al-danger-color)'),
	),
	'cmd' => array(
		'widgets' => array(
			'action' => array(
				'other' => array(
					'toggle' => array('template' => 'tmplicon', 'replace' => array('#_icon_on_#' => '<i class=\'icon_yellow fas fa-toggle-on\'></i>', '#_icon_off_#' => '<i class=\'fas fa-toggle-off\'></i>')),
					'toggleLine' => array('template' => 'tmpliconline', 'replace' => array('#_icon_on_#' => '<i class=\'icon_yellow fas fa-toggle-on\'></i>', '#_icon_off_#' => '<i class=\'fas fa-toggle-off\'></i>')),
					'light' => array('template' => 'tmplicon', 'replace' => array('#_icon_on_#' => '<i class=\'icon_yellow icon jeedom-lumiere-on\'></i>', '#_icon_off_#' => '<i class=\'icon jeedom-lumiere-off\'></i>')),
					'circle' => array('template' => 'tmplicon', 'replace' => array('#_icon_on_#' => '<i class=\'fas fa-circle\'></i>', '#_icon_off_#' => '<i class=\'far fa-circle\'></i>')),
					'fan' => array('template' => 'tmplicon', 'replace' => array('#_icon_on_#' => '<i class=\'icon jeedom-ventilo\'></i>', '#_icon_off_#' => '<i class=\'fas fa-times\'></i>')),
					'garage' => array('template' => 'tmplicon', 'replace' => array('#_icon_on_#' => '<i class=\'icon_green icon jeedom-garage-ferme\'></i>', '#_icon_off_#' => '<i class=\'icon_red icon jeedom-garage-ouvert\'></i>')),
					'lock' => array('template' => 'tmplicon', 'replace' => array('#_icon_on_#' => '<i class=\'icon_green icon jeedom-lock-ferme\'></i>', '#_icon_off_#' => '<i class=\'icon_orange icon jeedom-lock-ouvert\'></i>')),
					'prise' => array('template' => 'tmplicon', 'replace' => array('#_icon_on_#' => '<i class=\'icon jeedom-prise\'></i>', '#_icon_off_#' => '<i class=\'fas fa-times\'></i>')),
					'sprinkle' => array('template' => 'tmplicon', 'replace' => array('#_icon_on_#' => '<i class=\'icon_blue icon nature-watering1\'></i>', '#_icon_off_#' => '<i class=\'fas fa-times\'></i>')),
					'timeToggle' => array('template' => 'tmplicon', 'replace' => array('#_time_widget_#' => '1', '#_icon_on_#' => '<i class=\'icon_yellow fas fa-toggle-on\'></i>', '#_icon_off_#' => '<i class=\'fas fa-toggle-off\'></i>')),
					'timeLight' => array('template' => 'tmplicon', 'replace' => array('#_time_widget_#' => '1', '#_icon_on_#' => '<i class=\'icon_yellow icon jeedom-lumiere-on\'></i>', '#_icon_off_#' => '<i class=\'icon jeedom-lumiere-off\'></i>')),
					'timeCircle' => array('template' => 'tmplicon', 'replace' => array('#_time_widget_#' => '1', '#_icon_on_#' => '<i class=\'fas fa-circle\'></i>', '#_icon_off_#' => '<i class=\'fas fa-circle-thin\'></i>')),
					'timeFan' => array('template' => 'tmplicon', 'replace' => array('#_time_widget_#' => '1', '#_icon_on_#' => '<i class=\'icon jeedom-ventilo\'></i>', '#_icon_off_#' => '<i class=\'fas fa-times\'></i>')),
					'timeGarage' => array('template' => 'tmplicon', 'replace' => array('#_time_widget_#' => '1', '#_icon_on_#' => '<i class=\'icon_green icon jeedom-garage-ferme\'></i>', '#_icon_off_#' => '<i class=\'icon_red icon jeedom-garage-ouvert\'></i>')),
					'timeLock' => array('template' => 'tmplicon', 'replace' => array('#_time_widget_#' => '1', '#_icon_on_#' => '<i class=\'icon jeedom-lock-ferme\'></i>', '#_icon_off_#' => '<i class=\'icon jeedom-lock-ouvert\'></i>')),
					'timePrise' => array('template' => 'tmplicon', 'replace' => array('#_time_widget_#' => '1', '#_icon_on_#' => '<i class=\'icon jeedom-prise\'></i>', '#_icon_off_#' => '<i class=\'fas fa-times\'></i>')),
					'timeSprinkle' => array(
						'template' => 'tmplicon', 'replace' => array('#_time_widget_#' => '1', '#_icon_on_#' => '<i class=\'icon_blue icon nature-watering1\'></i>', '#_icon_off_#' => '<i class=\'fas fa-times\'></i>')
					),
				),
				'slider' => array(
					'light' => array('template' => 'tmplicon', 'replace' => array('#_icon_on_#' => '<i class=\'icon_yellow icon jeedom-lumiere-on\'></i>', '#_icon_off_#' => '<i class=\'icon jeedom-lumiere-off\'></i>')),
					'timeLight' => array('template' => 'tmplicon', 'replace' => array('#_time_widget_#' => '1', '#_icon_on_#' => '<i class=\'icon_yellow icon jeedom-lumiere-on\'></i>', '#_icon_off_#' => '<i class=\'icon jeedom-lumiere-off\'></i>')),
				)
			),
			'info' => array(
				'binary' => array(
					'icon' => array('template' => 'tmplicon', 'replace' => array('#_icon_on_#' => '<i class=\'icon_green fas fa-check\'></i>', '#_icon_off_#' => '<i class=\'icon_red fas fa-times\'></i>')),
					'line' => array('template' => 'tmpliconline', 'replace' => array('#_icon_on_#' => '<i class=\'icon_green fas fa-check\'></i>', '#_icon_off_#' => '<i class=\'icon_red fas fa-times\'></i>')),
					'alert' => array('template' => 'tmplicon', 'replace' => array('#_icon_on_#' => '<i class=\'icon_green fas fa-check\'></i>', '#_icon_off_#' => '<i class=\'icon_red icon jeedom-alerte2\'></i>')),
					'door' => array('template' => 'tmplicon', 'replace' => array('#_icon_on_#' => '<i class=\'icon_green icon jeedom-porte-ferme\'></i>', '#_icon_off_#' => '<i class=\'icon_red icon jeedom-porte-ouverte\'></i>')),
					'heat' => array('template' => 'tmplicon', 'replace' => array('#_icon_on_#' => '<i class=\'icon_red icon jeedom-feu\'></i>', '#_icon_off_#' => '<i class=\'fas fa-times\'></i>')),
					'light' => array('template' => 'tmplicon', 'replace' => array('#_icon_on_#' => '<i class=\'icon_yellow icon jeedom-lumiere-on\'></i>', '#_icon_off_#' => '<i class=\'icon jeedom-lumiere-off\'></i>')),
					'lock' => array('template' => 'tmplicon', 'replace' => array('#_icon_on_#' => '<i class=\'icon jeedom-lock-ferme\'></i>', '#_icon_off_#' => '<i class=\'icon_red icon jeedom-lock-ouvert\'></i>')),
					'presence' => array('template' => 'tmplicon', 'replace' => array('#_icon_on_#' => '<i class=\'icon_green fas fa-check\'></i>', '#_icon_off_#' => '<i class=\'icon_red icon jeedom-mouvement\'></i>')),
					'prise' => array('template' => 'tmplicon', 'replace' => array('#_icon_on_#' => '<i class=\'icon jeedom-prise\'></i>', '#_icon_off_#' => '<i class=\'fas fa-times\'></i>')),
					'window' => array('template' => 'tmplicon', 'replace' => array('#_icon_on_#' => '<i class=\'icon_green icon jeedom-fenetre-ferme\'></i>', '#_icon_off_#' => '<i class=\'icon_red icon jeedom-fenetre-ouverte\'></i>')),
					'flood' => array('template' => 'tmplicon', 'replace' => array('#_icon_on_#' => '<i class=\'icon_green fas fa-tint-slash\'></i>', '#_icon_off_#' => '<i class=\'icon_blue fas fa-tint\'></i>')),
					'timeDoor' => array('template' => 'tmplicon', 'replace' => array('#_time_widget_#' => '1', '#_icon_on_#' => '<i class=\'icon_green icon jeedom-porte-ferme\'></i>', '#_icon_off_#' => '<i class=\'icon_red icon jeedom-porte-ouverte\'></i>')),
					'timePresence' => array('template' => 'tmplicon', 'replace' => array('#_time_widget_#' => '1', '#_icon_on_#' => '<i class=\'icon_green fas fa-check\'></i>', '#_icon_off_#' => '<i class=\'icon_red icon jeedom-mouvement\'></i>')),
					'timeWindow' => array('template' => 'tmplicon', 'replace' => array('#_time_widget_#' => '1', '#_icon_on_#' => '<i class=\'icon_green icon jeedom-fenetre-ferme\'></i>', '#_icon_off_#' => '<i class=\'icon_red icon jeedom-fenetre-ouverte\'></i>')),
					'timeAlert' => array('template' => 'tmplicon', 'replace' => array('#_time_widget_#' => '1', '#_icon_on_#' => '<i class=\'icon_green fas fa-check\'></i>', '#_icon_off_#' => '<i class=\'icon_red icon jeedom-alerte2\'></i>')),
				),
				'numeric' => array(
					'heatPiloteWire' => array(
						'template' => 'tmplmultistate',
						'test' => array(
							array('operation' => '#value# == 3', 'state_light' => '<i class=\'icon jeedom-pilote-eco\'></i>'),
							array('operation' => '#value# == 2', 'state_light' => '<i class=\'icon jeedom-pilote-off\'></i>'),
							array('operation' => '#value# == 1', 'state_light' => '<i class=\'icon jeedom-pilote-hg\'></i>'),
							array('operation' => '#value# == 0', 'state_light' => '<i class=\'icon jeedom-pilote-conf\'></i>')
						)
					),
					'timeHeatPiloteWire' => array(
						'template' => 'tmplmultistate',
						'replace' => array('#_time_widget_#' => '1'),
						'test' => array(
							array('operation' => '#value# == 3', 'state_light' => '<i class=\'icon jeedom-pilote-eco\'></i>'),
							array('operation' => '#value# == 2', 'state_light' => '<i class=\'icon jeedom-pilote-off\'></i>'),
							array('operation' => '#value# == 1', 'state_light' => '<i class=\'icon jeedom-pilote-hg\'></i>'),
							array('operation' => '#value# == 0', 'state_light' => '<i class=\'icon jeedom-pilote-conf\'></i>')
						)
					),
					'heatPiloteWireQubino' => array(
						'template' => 'tmplmultistate',
						'test' => array(
							array('operation' => '#value# >= 51 && #value# <= 255', 'state_light' => '<i class=\'icon jeedom-pilote-conf\'></i>'),
							array('operation' => '#value# >= 41 && #value# <= 50', 'state_light' => '<i class=\'icon jeedom-pilote-conf\'></i><sup style=\'font-size: 0.3em; margin-left: 1px\'>-1</sup>'),
							array('operation' => '#value# >= 31 && #value# <= 40', 'state_light' => '<i class=\'icon jeedom-pilote-conf\'></i><sup style=\'font-size: 0.3em; margin-left: 1px\'>-2</sup>'),
							array('operation' => '#value# >= 21 && #value# <= 30', 'state_light' => '<i class=\'icon jeedom-pilote-eco\'></i>'),
							array('operation' => '#value# >= 11 && #value# <= 20', 'state_light' => '<i class=\'icon jeedom-pilote-hg\'></i>'),
							array('operation' => '#value# >= 0 && #value# <= 10', 'state_light' => '<i class=\'icon jeedom-pilote-off\'></i>'),
						)
					),
					'timeHeatPiloteWireQubino' => array(
						'template' => 'tmplmultistate',
						'replace' => array('#_time_widget_#' => '1'),
						'test' => array(
							array('operation' => '#value# >= 51 && #value# <= 255', 'state_light' => '<i class=\'icon jeedom-pilote-conf\'></i>'),
							array('operation' => '#value# >= 41 && #value# <= 50', 'state_light' => '<i class=\'icon jeedom-pilote-conf\'></i><sup style=\'font-size: 0.3em; margin-left: 1px\'>-1</sup>'),
							array('operation' => '#value# >= 31 && #value# <= 40', 'state_light' => '<i class=\'icon jeedom-pilote-conf\'></i><sup style=\'font-size: 0.3em; margin-left: 1px\'>-2</sup>'),
							array('operation' => '#value# >= 21 && #value# <= 30', 'state_light' => '<i class=\'icon jeedom-pilote-eco\'></i>'),
							array('operation' => '#value# >= 11 && #value# <= 20', 'state_light' => '<i class=\'icon jeedom-pilote-hg\'></i>'),
							array('operation' => '#value# >= 0 && #value# <= 10', 'state_light' => '<i class=\'icon jeedom-pilote-off\'></i>'),
						)
					)
				)
			)
		),
		'generic_type' => array(
			'TOGGLE' => array(
				'name' => t('Toggle', __FILE__), 'familyid' => 'Other', 'family' => t('Autre', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'ONLINE' => array(
				'name' => t('Connecté', __FILE__), 'familyid' => 'Other', 'family' => t('Autre', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'LIGHT_TOGGLE' => array(
				'name' => t('Lumière Toggle', __FILE__), 'familyid' => 'Light', 'family' => t('Lumière', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'LIGHT_STATE' => array(
				'name' => t('Lumière Etat', __FILE__), 'familyid' => 'Light', 'family' => t('Lumière', __FILE__),
				'type' => 'Info', 'subtype' => array('binary', 'numeric')
			),
			'LIGHT_ON' => array(
				'name' => t('Lumière Bouton On', __FILE__), 'familyid' => 'Light', 'family' => t('Lumière', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'other'), 'subtype' => array('other')
			),
			'LIGHT_OFF' => array(
				'name' => t('Lumière Bouton Off', __FILE__), 'familyid' => 'Light', 'family' => t('Lumière', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'other'), 'subtype' => array('other')
			),
			'LIGHT_SLIDER' => array(
				'name' => t('Lumière Slider', __FILE__), 'familyid' => 'Light', 'family' => t('Lumière', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'slider'), 'subtype' => array('slider')
			),
			'LIGHT_BRIGHTNESS' => array(
				'name' => t('Lumière Luminosité', __FILE__), 'familyid' => 'Light', 'family' => t('Lumière', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'LIGHT_COLOR' => array(
				'name' => t('Lumière Couleur', __FILE__), 'familyid' => 'Light', 'family' => t('Lumière', __FILE__),
				'type' => 'Info', 'subtype' => array('string')
			),
			'LIGHT_SET_COLOR' => array(
				'name' => t('Lumière Couleur', __FILE__), 'familyid' => 'Light', 'family' => t('Lumière', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'color'), 'subtype' => array('color')
			),
			'LIGHT_MODE' => array(
				'name' => t('Lumière Mode', __FILE__), 'familyid' => 'Light', 'family' => t('Lumière', __FILE__),
				'type' => 'Action', 'subtype' => array('other','select')
			),
			'LIGHT_STATE_BOOL' => array(
				'name' => t('Lumière Etat (Binaire)', __FILE__), 'familyid' => 'Light', 'family' => t('Lumière', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'LIGHT_COLOR_TEMP' => array(
				'name' => t('Lumière Température Couleur', __FILE__), 'familyid' => 'Light', 'family' => t('Lumière', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric')
			),
			'LIGHT_SET_COLOR_TEMP' => array(
				'name' => t('Lumière Température Couleur', __FILE__), 'familyid' => 'Light', 'family' => t('Lumière', __FILE__),
				'type' => 'Action'
			),
			'ENERGY_STATE' => array(
				'name' => t('Prise Etat', __FILE__), 'familyid' => 'Outlet', 'family' => t('Prise', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric', 'binary')
			),
			'ENERGY_ON' => array(
				'name' => t('Prise Bouton On', __FILE__), 'familyid' => 'Outlet', 'family' => t('Prise', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'other'), 'subtype' => array('other')
			),
			'ENERGY_OFF' => array(
				'name' => t('Prise Bouton Off', __FILE__), 'familyid' => 'Outlet', 'family' => t('Prise', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'other'), 'subtype' => array('other')
			),
			'ENERGY_SLIDER' => array(
				'name' => t('Prise Slider', __FILE__), 'familyid' => 'Outlet', 'family' => t('Prise', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'slider')
			),
			'FLAP_STATE' => array(
				'name' => t('Volet Etat', __FILE__), 'familyid' => 'Shutter', 'family' => t('Volet', __FILE__),
				'type' => 'Info', 'subtype' => array('binary', 'numeric')
			),
			'FLAP_UP' => array(
				'name' => t('Volet Bouton Monter', __FILE__), 'familyid' => 'Shutter', 'family' => t('Volet', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'other'), 'subtype' => array('other')
			),
			'FLAP_DOWN' => array(
				'name' => t('Volet Bouton Descendre', __FILE__), 'familyid' => 'Shutter', 'family' => t('Volet', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'other'), 'subtype' => array('other')
			),
			'FLAP_STOP' => array(
				'name' => t('Volet Bouton Stop', __FILE__), 'familyid' => 'Shutter', 'family' => t('Volet', __FILE__),
				'type' => 'Action'
			),
			'FLAP_SLIDER' => array(
				'name' => t('Volet Bouton Slider', __FILE__), 'familyid' => 'Shutter', 'family' => t('Volet', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'slider'), 'subtype' => array('slider')
			),
			'FLAP_BSO_STATE' => array(
				'name' => t('Volet BSO Etat', __FILE__), 'familyid' => 'Shutter', 'family' => t('Volet', __FILE__),
				'type' => 'Info', 'subtype' => array('binary', 'numeric')
			),
			'FLAP_BSO_UP' => array(
				'name' => t('Volet BSO Bouton Monter', __FILE__), 'familyid' => 'Shutter', 'family' => t('Volet', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'other'), 'subtype' => array('other')
			),
			'FLAP_BSO_DOWN' => array(
				'name' => t('Volet BSO Bouton Descendre', __FILE__), 'familyid' => 'Shutter', 'family' => t('Volet', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'other'), 'subtype' => array('other')
			),
			'HEATING_ON' => array(
				'name' => t('Chauffage fil pilote Bouton ON', __FILE__), 'familyid' => 'Heating', 'family' => t('Chauffage', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'other'), 'subtype' => array('other')
			),
			'HEATING_OFF' => array(
				'name' => t('Chauffage fil pilote Bouton OFF', __FILE__), 'familyid' => 'Heating', 'family' => t('Chauffage', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'other'), 'subtype' => array('other')
			),
			'HEATING_STATE' => array(
				'name' => t('Chauffage fil pilote Etat', __FILE__), 'familyid' => 'Heating', 'family' => t('Chauffage', __FILE__),
				'type' => 'Info', 'subtype' => array('binary', 'numeric')
			),
			'HEATING_OTHER' => array(
				'name' => t('Chauffage fil pilote Bouton', __FILE__), 'familyid' => 'Heating', 'family' => t('Chauffage', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'LOCK_STATE' => array(
				'name' => t('Serrure Etat', __FILE__), 'familyid' => 'Opening', 'family' => t('Ouvrant', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'LOCK_OPEN' => array(
				'name' => t('Serrure Bouton Ouvrir', __FILE__), 'familyid' => 'Opening', 'family' => t('Ouvrant', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'other'), 'subtype' => array('other')
			),
			'LOCK_CLOSE' => array(
				'name' => t('Serrure Bouton Fermer', __FILE__), 'familyid' => 'Opening', 'family' => t('Ouvrant', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'other'), 'subtype' => array('other')
			),
			'GB_OPEN' => array(
				'name' => t('Portail ou garage bouton d\'ouverture', __FILE__), 'familyid' => 'Opening', 'family' => t('Ouvrant', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'other'), 'subtype' => array('other')
			),
			'GB_CLOSE' => array(
				'name' => t('Portail ou garage bouton de fermeture', __FILE__), 'familyid' => 'Opening', 'family' => t('Ouvrant', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'other'), 'subtype' => array('other')
			),
			'GB_TOGGLE' => array(
				'name' => t('Portail ou garage bouton toggle', __FILE__), 'familyid' => 'Opening', 'family' => t('Ouvrant', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'BARRIER_STATE' => array(
				'name' => t('Portail (ouvrant) Etat', __FILE__), 'familyid' => 'Opening', 'family' => t('Ouvrant', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'GARAGE_STATE' => array(
				'name' => t('Garage (ouvrant) Etat', __FILE__), 'familyid' => 'Opening', 'family' => t('Ouvrant', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'OPENING' => array(
				'name' => t('Porte', __FILE__), 'familyid' => 'Opening', 'family' => t('Ouvrant', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'OPENING_WINDOW' => array(
				'name' => t('Fenêtre', __FILE__), 'familyid' => 'Opening', 'family' => t('Ouvrant', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'THERMOSTAT_STATE' => array(
				'name' => t('Thermostat Etat', __FILE__), 'familyid' => 'Thermostat', 'family' => t('Thermostat', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'THERMOSTAT_TEMPERATURE' => array(
				'name' => t('Thermostat Température ambiante', __FILE__), 'familyid' => 'Thermostat', 'family' => t('Thermostat', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'THERMOSTAT_SET_SETPOINT' => array(
				'name' => t('Thermostat consigne', __FILE__), 'familyid' => 'Thermostat', 'family' => t('Thermostat', __FILE__),
				'type' => 'Action', 'subtype' => array('slider')
			),
			'THERMOSTAT_SETPOINT' => array(
				'name' => t('Thermostat consigne', __FILE__), 'familyid' => 'Thermostat', 'family' => t('Thermostat', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'THERMOSTAT_SET_MODE' => array(
				'name' => t('Thermostat Mode', __FILE__), 'familyid' => 'Thermostat', 'family' => t('Thermostat', __FILE__),
				'type' => 'Action', 'subtype' => array('other','select')
			),
			'THERMOSTAT_MODE' => array(
				'name' => t('Thermostat Mode', __FILE__), 'familyid' => 'Thermostat', 'family' => t('Thermostat', __FILE__),
				'type' => 'Info', 'subtype' => array('string')
			),
			'THERMOSTAT_SET_LOCK' => array(
				'name' => t('Thermostat Verrouillage', __FILE__), 'familyid' => 'Thermostat', 'family' => t('Thermostat', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'THERMOSTAT_SET_UNLOCK' => array(
				'name' => t('Thermostat Déverrouillage', __FILE__), 'familyid' => 'Thermostat', 'family' => t('Thermostat', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'THERMOSTAT_LOCK' => array(
				'name' => t('Thermostat Verrouillage', __FILE__), 'familyid' => 'Thermostat', 'family' => t('Thermostat', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'THERMOSTAT_TEMPERATURE_OUTDOOR' => array(
				'name' => t('Thermostat Température Exterieur', __FILE__), 'familyid' => 'Thermostat', 'family' => t('Thermostat', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'THERMOSTAT_STATE_NAME' => array(
				'name' => t('Thermostat Etat (HUMAIN)', __FILE__), 'familyid' => 'Thermostat', 'family' => t('Thermostat', __FILE__),
				'type' => 'Info', 'subtype' => array('string')
			),
			'THERMOSTAT_HUMIDITY' => array(
				'name' => t('Thermostat humidité ambiante', __FILE__), 'familyid' => 'Thermostat', 'family' => t('Thermostat', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'THERMOSTAT_SET_MAX_TEMP' => array(
				'name' => t('Thermostat maximum consigne', __FILE__), 'familyid' => 'Thermostat', 'family' => t('Thermostat', __FILE__),
				'type' => 'Action', 'subtype' => array('slider')
			),
			'THERMOSTAT_SET_MIN_TEMP' => array(
				'name' => t('Thermostat minimum consigne', __FILE__), 'familyid' => 'Thermostat', 'family' => t('Thermostat', __FILE__),
				'type' => 'Action', 'subtype' => array('slider')
			),
			'THERMOSTAT_HUMIDITY' => array(
				'name' => t('Thermostat humidité ambiante', __FILE__), 'familyid' => 'Thermostat', 'family' => t('Thermostat', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'HUMIDITY_SETPOINT' => array(
				'name' => t('Humidité consigne', __FILE__), 'familyid' => 'Thermostat', 'family' => t('Thermostat', __FILE__),
				'type' => 'Info', 'subtype' => array('slider')
			),
			'HUMIDITY_SET_SETPOINT' => array(
				'name' => t('Humidité consigne', __FILE__), 'familyid' => 'Thermostat', 'family' => t('Thermostat', __FILE__),
				'type' => 'Action', 'subtype' => array('slider')
			),
			'CAMERA_UP' => array(
				'name' => t('Mouvement caméra vers le haut', __FILE__), 'familyid' => 'Camera', 'family' => t('Caméra', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'CAMERA_DOWN' => array(
				'name' => t('Mouvement caméra vers le bas', __FILE__), 'familyid' => 'Camera', 'family' => t('Caméra', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'CAMERA_LEFT' => array(
				'name' => t('Mouvement caméra vers la gauche', __FILE__), 'familyid' => 'Camera', 'family' => t('Caméra', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'CAMERA_RIGHT' => array(
				'name' => t('Mouvement caméra vers la droite', __FILE__), 'familyid' => 'Camera', 'family' => t('Caméra', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'CAMERA_ZOOM' => array(
				'name' => t('Zoom caméra vers l\'avant', __FILE__), 'familyid' => 'Camera', 'family' => t('Caméra', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'CAMERA_DEZOOM' => array(
				'name' => t('Zoom caméra vers l\'arrière', __FILE__), 'familyid' => 'Camera', 'family' => t('Caméra', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'CAMERA_STOP' => array(
				'name' => t('Stop caméra', __FILE__), 'familyid' => 'Camera', 'family' => t('Caméra', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'CAMERA_PRESET' => array(
				'name' => t('Preset caméra', __FILE__), 'familyid' => 'Camera', 'family' => t('Caméra', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'CAMERA_URL' => array(
				'name' => t('URL caméra', __FILE__), 'familyid' => 'Camera', 'family' => t('Caméra', __FILE__),
				'type' => 'Info', 'subtype' => array('string')
			),
			'CAMERA_RECORD_STATE' => array(
				'name' => t('État enregistrement caméra', __FILE__), 'familyid' => 'Camera', 'family' => t('Caméra', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'CAMERA_RECORD' => array(
				'name' => t('Enregistrement caméra', __FILE__), 'familyid' => 'Camera', 'family' => t('Caméra', __FILE__),
				'type' => 'Action'
			),
			'CAMERA_TAKE' => array(
				'name' => t('Snapshot caméra', __FILE__), 'familyid' => 'Camera', 'family' => t('Caméra', __FILE__),
				'type' => 'Action'
			),
			'MODE_STATE' => array(
				'name' => t('Mode Etat', __FILE__), 'familyid' => 'Mode', 'family' => t('Mode', __FILE__),
				'type' => 'Info', 'subtype' => array('string')
			),
			'MODE_SET_STATE' => array(
				'name' => t('Changer Mode', __FILE__), 'familyid' => 'Mode', 'family' => t('Mode', __FILE__),
				'type' => 'Action', 'subtype' => array('other','select')
			),
			'SIREN_STATE' => array(
				'name' => t('Sirène Etat', __FILE__), 'familyid' => 'Security', 'family' => t('Sécurité', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'SIREN_OFF' => array(
				'name' => t('Sirène Bouton Off', __FILE__), 'familyid' => 'Security', 'family' => t('Sécurité', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'other'), 'subtype' => array('other')
			),
			'SIREN_ON' => array(
				'name' => t('Sirène Bouton On', __FILE__), 'familyid' => 'Security', 'family' => t('Sécurité', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'other'), 'subtype' => array('other')
			),
			'ALARM_STATE' => array(
				'name' => t('Alarme Etat', __FILE__), 'familyid' => 'Security', 'family' => t('Sécurité', __FILE__),
				'type' => 'Info', 'subtype' => array('binary', 'string')
			),
			'ALARM_MODE' => array(
				'name' => t('Alarme mode', __FILE__), 'familyid' => 'Security', 'family' => t('Sécurité', __FILE__),
				'type' => 'Info', 'subtype' => array('string')
			),
			'ALARM_ENABLE_STATE' => array(
				'name' => t('Alarme Etat activée', __FILE__), 'familyid' => 'Security', 'family' => t('Sécurité', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'ALARM_ARMED' => array(
				'name' => t('Alarme armée', __FILE__), 'familyid' => 'Security', 'family' => t('Sécurité', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'ALARM_RELEASED' => array(
				'name' => t('Alarme libérée', __FILE__), 'familyid' => 'Security', 'family' => t('Sécurité', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'ALARM_SET_MODE' => array(
				'name' => t('Alarme Mode', __FILE__), 'familyid' => 'Security', 'family' => t('Sécurité', __FILE__),
				'type' => 'Action', 'subtype' => array('other','select')
			),
			'FLOOD' => array(
				'name' => t('Inondation', __FILE__), 'familyid' => 'Security', 'family' => t('Sécurité', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'SABOTAGE' => array(
				'name' => t('Sabotage', __FILE__), 'familyid' => 'Security', 'family' => t('Sécurité', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'SHOCK' => array(
				'name' => t('Choc', __FILE__), 'familyid' => 'Security', 'family' => t('Sécurité', __FILE__),
				'type' => 'Info', 'subtype' => array('binary', 'numeric')
			),
			'WEATHER_TEMPERATURE' => array(
				'name' => t('Météo Température', __FILE__), 'familyid' => 'Weather', 'family' => t('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WEATHER_HUMIDITY' => array(
				'name' => t('Météo Humidité', __FILE__), 'familyid' => 'Weather', 'family' => t('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WEATHER_PRESSURE' => array(
				'name' => t('Météo Pression', __FILE__), 'familyid' => 'Weather', 'family' => t('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WEATHER_WIND_SPEED' => array(
				'name' => t('Météo vitesse du vent', __FILE__), 'familyid' => 'Weather', 'family' => t('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WEATHER_WIND_DIRECTION' => array(
				'name' => t('Météo direction du vent', __FILE__), 'familyid' => 'Weather', 'family' => t('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WEATHER_SUNSET' => array(
				'name' => t('Météo coucher de soleil', __FILE__), 'familyid' => 'Weather', 'family' => t('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WEATHER_SUNRISE' => array(
				'name' => t('Météo lever de soleil', __FILE__), 'familyid' => 'Weather', 'family' => t('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WEATHER_TEMPERATURE_MIN' => array(
				'name' => t('Météo Température min', __FILE__), 'familyid' => 'Weather', 'family' => t('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WEATHER_TEMPERATURE_MAX' => array(
				'name' => t('Météo Température max', __FILE__), 'familyid' => 'Weather', 'family' => t('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WEATHER_CONDITION' => array(
				'name' => t('Météo condition', __FILE__), 'familyid' => 'Weather', 'family' => t('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('string')
			),
			'WEATHER_CONDITION_ID' => array(
				'name' => t('Météo condition (id)', __FILE__), 'familyid' => 'Weather', 'family' => t('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'text'
			),
			'WEATHER_TEMPERATURE_MIN_1' => array(
				'name' => t('Météo Température min j+1', __FILE__), 'familyid' => 'Weather', 'family' => t('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WEATHER_TEMPERATURE_MAX_1' => array(
				'name' => t('Météo Température max j+1', __FILE__), 'familyid' => 'Weather', 'family' => t('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WEATHER_CONDITION_1' => array(
				'name' => t('Météo condition j+1', __FILE__), 'familyid' => 'Weather', 'family' => t('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('string'), 'calcul' => 'text'
			),
			'WEATHER_CONDITION_ID_1' => array(
				'name' => t('Météo condition (id) j+1', __FILE__), 'familyid' => 'Weather', 'family' => t('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'text'
			),
			'WEATHER_TEMPERATURE_MIN_2' => array(
				'name' => t('Météo Température min j+2', __FILE__), 'familyid' => 'Weather', 'family' => t('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WEATHER_TEMPERATURE_MAX_2' => array(
				'name' => t('Météo condition j+1 max j+2', __FILE__), 'familyid' => 'Weather', 'family' => t('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WEATHER_CONDITION_2' => array(
				'name' => t('Météo condition j+2', __FILE__), 'familyid' => 'Weather', 'family' => t('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('string'), 'calcul' => 'text'
			),
			'WEATHER_CONDITION_ID_2' => array(
				'name' => t('Météo condition (id) j+2', __FILE__), 'familyid' => 'Weather', 'family' => t('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'text'
			),
			'WEATHER_TEMPERATURE_MIN_3' => array(
				'name' => t('Météo Température min j+3', __FILE__), 'familyid' => 'Weather', 'family' => t('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WEATHER_TEMPERATURE_MAX_3' => array(
				'name' => t('Météo Température max j+3', __FILE__), 'familyid' => 'Weather', 'family' => t('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WEATHER_CONDITION_3' => array(
				'name' => t('Météo condition j+3', __FILE__), 'familyid' => 'Weather', 'family' => t('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('string'), 'calcul' => 'text'
			),
			'WEATHER_CONDITION_ID_3' => array(
				'name' => t('Météo condition (id) j+3', __FILE__), 'familyid' => 'Weather', 'family' => t('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'text'
			),
			'WEATHER_TEMPERATURE_MIN_4' => array(
				'name' => t('Météo Température min j+4', __FILE__), 'familyid' => 'Weather', 'family' => t('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WEATHER_TEMPERATURE_MAX_4' => array(
				'name' => t('Météo Température max j+4', __FILE__), 'familyid' => 'Weather', 'family' => t('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WEATHER_CONDITION_4' => array(
				'name' => t('Météo condition j+4', __FILE__), 'familyid' => 'Weather', 'family' => t('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('string'), 'calcul' => 'text'
			),
			'WEATHER_CONDITION_ID_4' => array(
				'name' => t('Météo condition (id) j+4', __FILE__), 'familyid' => 'Weather', 'family' => t('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'text'
			),
			'RAIN_CURRENT' => array(
				'name' => t('Pluie (mm/h)', __FILE__), 'familyid' => 'Weather', 'family' => t('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'RAIN_TOTAL' => array(
				'name' => t('Pluie (accumulation)', __FILE__), 'familyid' => 'Weather', 'family' => t('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WIND_SPEED' => array(
				'name' => t('Vent (vitesse)', __FILE__), 'familyid' => 'Weather', 'family' => t('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WIND_DIRECTION' => array(
				'name' => t('Vent (direction)', __FILE__), 'familyid' => 'Weather', 'family' => t('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'POWER' => array(
				'name' => t('Puissance Electrique', __FILE__), 'familyid' => 'Electricity', 'family' => t('Electricité', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric')
			),
			'CONSUMPTION' => array(
				'name' => t('Consommation Electrique', __FILE__), 'familyid' => 'Electricity', 'family' => t('Electricité', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric')
			),
			'DAILY_CONSUMPTION' => array(
				'name' => t('Consommation Electrique Journalière', __FILE__), 'familyid' => 'Electricity', 'family' => t('Electricité', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric')
			),
			'PRODUCTION' => array(
				'name' => t('Production Electrique', __FILE__), 'familyid' => 'Electricity', 'family' => t('Electricité', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric')
			),
			'DAILY_PRODUCTION' => array(
				'name' => t('Production Electrique Journalière', __FILE__), 'familyid' => 'Electricity', 'family' => t('Electricité', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric')
			),
			'VOLTAGE' => array(
				'name' => t('Tension', __FILE__), 'familyid' => 'Electricity', 'family' => t('Electricité', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'REBOOT' => array(
				'name' => t('Redémarrage', __FILE__), 'familyid' => 'Electricity', 'family' => t('Electricité', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'TEMPERATURE' => array(
				'name' => t('Température', __FILE__), 'familyid' => 'Environment', 'family' => t('Environnement', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'AIR_QUALITY' => array(
				'name' => t('Qualité de l\'air', __FILE__), 'familyid' => 'Environment', 'family' => t('Environnement', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'BRIGHTNESS' => array(
				'name' => t('Luminosité', __FILE__), 'familyid' => 'Environment', 'family' => t('Environnement', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'PRESENCE' => array(
				'name' => t('Présence', __FILE__), 'familyid' => 'Environment', 'family' => t('Environnement', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'SMOKE' => array(
				'name' => t('Détection de fumée', __FILE__), 'familyid' => 'Environment', 'family' => t('Environnement', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'HUMIDITY' => array(
				'name' => t('Humidité', __FILE__), 'familyid' => 'Environment', 'family' => t('Environnement', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'UV' => array(
				'name' => t('UV', __FILE__), 'familyid' => 'Environment', 'family' => t('Environnement', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'CO2' => array(
				'name' => t('CO2 (ppm)', __FILE__), 'familyid' => 'Environment', 'family' => t('Environnement', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'CO' => array(
				'name' => t('CO (ppm)', __FILE__), 'familyid' => 'Environment', 'family' => t('Environnement', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'NOISE' => array(
				'name' => t('Son (dB)', __FILE__), 'familyid' => 'Environment', 'family' => t('Environnement', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'PRESSURE' => array(
				'name' => t('Pression', __FILE__), 'familyid' => 'Environment', 'family' => t('Environnement', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WATER_LEAK' => array(
				'name' => t('Fuite d\'eau', __FILE__), 'familyid' => 'Environment', 'family' => t('Environnement', __FILE__),
				'type' => 'Info'
			),
			'FILTER_CLEAN_STATE' => array(
				'name' => t('Etat du filtre', __FILE__), 'familyid' => 'Environment', 'family' => t('Environnement', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'DEPTH' => array(
				'name' => t('Profondeur', __FILE__), 'familyid' => 'Generic', 'family' => t('Generic', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'DISTANCE' => array(
				'name' => t('Distance', __FILE__), 'familyid' => 'Generic', 'family' => t('Generic', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'BUTTON' => array(
				'name' => t('Bouton', __FILE__), 'familyid' => 'Generic', 'family' => t('Generic', __FILE__),
				'type' => 'Info', 'subtype' => array('binary', 'numeric')
			),
			'GENERIC_INFO' => array(
				'name' => t('Générique', __FILE__, false, ' '), 'familyid' => 'Generic', 'family' => t('Generic', __FILE__),
				'type' => 'Info'
			),
			'GENERIC_ACTION' => array(
				'name' => t('Générique', __FILE__, false, ' '), 'familyid' => 'Generic', 'family' => t('Generic', __FILE__),
				'type' => 'Action'
			),
			'DONT' => array(
				'name' => t('Ne pas tenir compte de cette commande', __FILE__), 'familyid' => 'Generic', 'family' => t('Generic', __FILE__),
				'type' => 'All'
			),
			'BATTERY' => array(
				'name' => t('Batterie', __FILE__), 'familyid' => 'Battery', 'family' => t('Batterie', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'BATTERY_CHARGING' => array(
				'name' => t('Batterie en charge', __FILE__), 'familyid' => 'Battery', 'family' => t('Batterie', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'VOLUME' => array(
				'name' => t('Volume', __FILE__), 'familyid' => 'Multimedia', 'family' => t('Multimédia', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'MEDIA_STATUS' => array(
				'name' => t('Status', __FILE__), 'familyid' => 'Multimedia', 'family' => t('Multimédia', __FILE__),
				'type' => 'Info', 'subtype' => array('string')
			),
			'MEDIA_ALBUM' => array(
				'name' => t('Album', __FILE__), 'familyid' => 'Multimedia', 'family' => t('Multimédia', __FILE__),
				'type' => 'Info', 'subtype' => array('string')
			),
			'MEDIA_ARTIST' => array(
				'name' => t('Artiste', __FILE__), 'familyid' => 'Multimedia', 'family' => t('Multimédia', __FILE__),
				'type' => 'Info', 'subtype' => array('string')
			),
			'MEDIA_TITLE' => array(
				'name' => t('Titre', __FILE__), 'familyid' => 'Multimedia', 'family' => t('Multimédia', __FILE__),
				'type' => 'Info', 'subtype' => array('string')
			),
			'MEDIA_POWER' => array(
				'name' => t('Power', __FILE__), 'familyid' => 'Multimedia', 'family' => t('Multimédia', __FILE__),
				'type' => 'Info', 'subtype' => array('string')
			),
			'SET_VOLUME' => array(
				'name' => t('Volume', __FILE__), 'familyid' => 'Multimedia', 'family' => t('Multimédia', __FILE__),
				'type' => 'Action', 'subtype' => array('slider')
			),
			'CHANNEL' => array(
				'name' => t('Chaine', __FILE__), 'familyid' => 'Multimedia', 'family' => t('Multimédia', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric', 'string'), 'calcul' => 'text'
			),
			'SET_CHANNEL' => array(
				'name' => t('Chaine', __FILE__), 'familyid' => 'Multimedia', 'family' => t('Multimédia', __FILE__),
				'type' => 'Action', 'subtype' => array('other', 'slider')
			),
			'MEDIA_PAUSE' => array(
				'name' => t('Pause', __FILE__), 'familyid' => 'Multimedia', 'family' => t('Multimédia', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'MEDIA_RESUME' => array(
				'name' => t('Lecture', __FILE__), 'familyid' => 'Multimedia', 'family' => t('Multimédia', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'MEDIA_STOP' => array(
				'name' => t('Stop', __FILE__), 'familyid' => 'Multimedia', 'family' => t('Multimédia', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'MEDIA_NEXT' => array(
				'name' => t('Suivant', __FILE__), 'familyid' => 'Multimedia', 'family' => t('Multimédia', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'MEDIA_PREVIOUS' => array(
				'name' => t('Précedent', __FILE__), 'familyid' => 'Multimedia', 'family' => t('Multimédia', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'MEDIA_ON' => array(
				'name' => t('On', __FILE__), 'familyid' => 'Multimedia', 'family' => t('Multimédia', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'MEDIA_OFF' => array(
				'name' => t('Off', __FILE__), 'familyid' => 'Multimedia', 'family' => t('Multimédia', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'MEDIA_STATE' => array(
				'name' => t('Etat', __FILE__), 'familyid' => 'Multimedia', 'family' => t('Multimédia', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'MEDIA_MUTE' => array(
				'name' => t('Muet', __FILE__), 'familyid' => 'Multimedia', 'family' => t('Multimédia', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'MEDIA_UNMUTE' => array(
				'name' => t('Non Muet', __FILE__), 'familyid' => 'Multimedia', 'family' => t('Multimédia', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'FAN_SPEED' => array(
				'name' => t('Vitesse ventilateur', __FILE__), 'familyid' => 'Fan', 'family' => t('Ventilateur', __FILE__),
				'type' => 'Action', 'subtype' => array('slider')
			),
			'FAN_SPEED_STATE' => array(
				'name' => t('Vitesse ventilateur Etat', __FILE__), 'familyid' => 'Fan', 'family' => t('Ventilateur', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'ROTATION' => array(
				'name' => t('Rotation', __FILE__), 'familyid' => 'Fan', 'family' => t('Ventilateur', __FILE__),
				'type' => 'Action', 'subtype' => array('slider')
			),
			'ROTATION_STATE' => array(
				'name' => t('Rotation Etat', __FILE__), 'familyid' => 'Fan', 'family' => t('Ventilateur', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'DOCK' => array(
				'name' => t('Retour base', __FILE__), 'familyid' => 'Robot', 'family' => t('Robot', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'DOCK_STATE' => array(
				'name' => t('Base Etat', __FILE__), 'familyid' => 'Robot', 'family' => t('Robot', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'TIMER' => array(
				'name' => t('Minuteur Etat', __FILE__), 'familyid' => 'Other', 'family' => t('Autre', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'SET_TIMER' => array(
				'name' => t('Minuteur', __FILE__), 'familyid' => 'Other', 'family' => t('Autre', __FILE__),
				'type' => 'Action', 'subtype' => array('slider')
			),
			'TIMER_STATE' => array(
				'name' => t('Minuteur Etat (pause ou non)', __FILE__), 'familyid' => 'Other', 'family' => t('Autre', __FILE__),
				'type' => 'Info', 'subtype' => array('binary', 'numeric'), 'calcul' => 'avg'
			),
			'TIMER_PAUSE' => array(
				'name' => t('Minuteur pause', __FILE__), 'familyid' => 'Other', 'family' => t('Autre', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'TIMER_RESUME' => array(
				'name' => t('Minuteur reprendre', __FILE__), 'familyid' => 'Other', 'family' => t('Autre', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
		),
		'type' => array(
			'info' => array(
				'name' => t('Info', __FILE__),
				'subtype' => array(
					'numeric' => array(
						'name' => t('Numérique', __FILE__),
						'configuration' => array(
							'minValue' => array('visible' => true),
							'maxValue' => array('visible' => true),
							'listValue' => array('visible' => false)
						),
						'unite' => array('visible' => true),
						'isHistorized' => array('visible' => true, 'timelineOnly' => false, 'canBeSmooth' => true),
						'display' => array(
							'invertBinary' => array('visible' => true, 'parentVisible' => true),
							'icon' => array('visible' => true, 'parentVisible' => true),
						),
					),
					'binary' => array(
						'name' => t('Binaire', __FILE__),
						'configuration' => array(
							'minValue' => array('visible' => false),
							'maxValue' => array('visible' => false),
							'listValue' => array('visible' => false)
						),
						'unite' => array('visible' => false),
						'isHistorized' => array('visible' => true, 'timelineOnly' => false, 'canBeSmooth' => false),
						'display' => array(
							'invertBinary' => array('visible' => true, 'parentVisible' => true),
							'icon' => array('visible' => true, 'parentVisible' => true),
						),
					),
					'string' => array(
						'name' => t('Autre', __FILE__),
						'configuration' => array(
							'minValue' => array('visible' => false),
							'maxValue' => array('visible' => false),
							'listValue' => array('visible' => false)
						),
						'unite' => array('visible' => true),
						'isHistorized' => array('visible' => true, 'timelineOnly' => true, 'canBeSmooth' => false),
						'display' => array(
							'invertBinary' => array('visible' => false),
							'icon' => array('visible' => true, 'parentVisible' => true),
						),
					),
				),
			),
			'action' => array(
				'name' => t('Action', __FILE__),
				'subtype' => array(
					'other' => array(
						'name' => t('Défaut', __FILE__),
						'configuration' => array(
							'minValue' => array('visible' => false),
							'maxValue' => array('visible' => false),
							'listValue' => array('visible' => false)
						),
						'unite' => array('visible' => false),
						'isHistorized' => array('visible' => false),
						'display' => array(
							'invertBinary' => array('visible' => false),
							'icon' => array('visible' => true, 'parentVisible' => true),
						),
					),
					'slider' => array(
						'name' => t('Curseur', __FILE__),
						'configuration' => array(
							'minValue' => array('visible' => true),
							'maxValue' => array('visible' => true),
							'listValue' => array('visible' => false)
						),
						'unite' => array('visible' => false),
						'isHistorized' => array('visible' => false),
						'display' => array(
							'invertBinary' => array('visible' => false),
							'icon' => array('visible' => true, 'parentVisible' => true),
						),
					),
					'message' => array(
						'name' => t('Message', __FILE__),
						'configuration' => array(
							'minValue' => array('visible' => false),
							'maxValue' => array('visible' => false),
							'listValue' => array('visible' => false)
						),
						'unite' => array('visible' => false),
						'isHistorized' => array('visible' => false),
						'display' => array(
							'invertBinary' => array('visible' => false),
							'icon' => array('visible' => true, 'parentVisible' => true),
						),
					),
					'color' => array(
						'name' => t('Couleur', __FILE__),
						'configuration' => array(
							'minValue' => array('visible' => false),
							'maxValue' => array('visible' => false),
							'listValue' => array('visible' => false)
						),
						'unite' => array('visible' => false),
						'isHistorized' => array('visible' => false),
						'display' => array(
							'invertBinary' => array('visible' => false),
							'icon' => array('visible' => true, 'parentVisible' => true),
						),
					),
					'select' => array(
						'name' => t('Liste', __FILE__),
						'configuration' => array(
							'minValue' => array('visible' => false),
							'maxValue' => array('visible' => false),
							'listValue' => array('visible' => true)
						),
						'unite' => array('visible' => false),
						'isHistorized' => array('visible' => false),
						'display' => array(
							'invertBinary' => array('visible' => false),
							'icon' => array('visible' => true, 'parentVisible' => true),
						),
					),
				),
			),
		),
	),
);
$GLOBALS['JEEDOM_SCLOG_TEXT'] = array(
	'startManual' 			=> array('txt' => t('Scénario lancé manuellement', __FILE__), 'replace' => '<label class="success">::</label>'),
	'startAutoOnEvent'		=> array('txt' => t('Scénario exécuté automatiquement sur événement venant de :', __FILE__, false, '', ' '), 'replace' => '<label class="success">::</label>'),
	'startOnEvent'			=> array('txt' => t('Scénario exécuté sur événement', __FILE__), 'replace' => '<label class="success">::</label>'),
	'startAutoOnShedule'	=> array('txt' => t('Scénario exécuté automatiquement sur programmation', __FILE__), 'replace' => '<label class="success">::</label>'),
	'finishOk' 				=> array('txt' => t('Fin correcte du scénario', __FILE__), 'replace' => '<label class="success">::</label>'),
	'sheduledOn'			=> array('txt' => t('programmée à :', __FILE__, false, ' ', ' '), 'replace' => '<label class="success">::</label>'),
	'startByScenario'		=> array('txt' => t('Lancement provoqué par le scénario  :', __FILE__, false, '', ' '), 'replace' => '<label class="success">::</label>'),
	'startCausedBy'			=> array('txt' => t('Lancement provoqué', __FILE__), 'replace' => '<label class="success">::</label>'),
	'startSubTask' 			=> array('txt' => t('************Lancement sous tâche**************', __FILE__), 'replace' => '<label class="success">::</label>'),
	'endSubTask' 			=> array('txt' => t('************FIN sous tâche**************', __FILE__), 'replace' => '<label class="success">::</label>'),
	'sheduleNow'			=> array('txt' => t('lancement immédiat', __FILE__, false, ' ', ' '), 'replace' => '<label class="success">::</label>'),

	'execAction'			=> array('txt' => t('Exécution du sous-élément de type [action] :', __FILE__, false, '- ', ' '), 'replace' => '<label class="info">::</label>'),
	'execCondition'			=> array('txt' => t('Exécution du sous-élément de type [condition] :', __FILE__, false, '- ', ' '), 'replace' => '<label class="info">::</label>'),

	'execCmd'				=> array('txt' => t('Exécution de la commande', __FILE__, false, '', ' '), 'replace' => '<label class="warning">::</label>'),
	'execCode'				=> array('txt' => t('Exécution d\'un bloc code', __FILE__, false, '', ' '), 'replace' => '<label class="warning">::</label>'),
	'launchScenario'		=> array('txt' => t('Lancement du scénario :', __FILE__, false, '', ' '), 'replace' => '<label class="warning">::</label>'),
	'launchScenarioSync'	=> array('txt' => t('Lancement du scénario en mode synchrone', __FILE__, false, '', ' '), 'replace' => '<label class="warning">::</label>'),
	'start'					=> array('txt' => t('Début :', __FILE__, false, '-- '), 'replace' => '<strong>::</strong>'),
	'task'					=> array('txt' => t('Tâche :', __FILE__, false, '', ' '), 'replace' => '<label class="warning">::</label>'),
	'event'					=> array('txt' => t('Changement de', __FILE__, false, '', ' '), 'replace' => '<label class="warning">::</label>'),
	'setTag'				=> array('txt' => t('Mise à jour du tag', __FILE__, false, '', ' '), 'replace' => '<label class="warning">::</label>'),

	'stopTimeout'			=> array('txt' => t('Arrêt du scénario car il a dépassé son temps de timeout :', __FILE__, false, '', ' '), 'replace' => '<label class="danger">::</label>'),
	'disableNoSubtask'		=> array('txt' => t('Scénario désactivé non lancement de la sous tâche', __FILE__), 'replace' => '<label class="danger">::</label>'),
	'disableEqNoExecCmd'	=> array('txt' => t('Equipement désactivé - impossible d\'exécuter la commande :', __FILE__, false, '', ' '), 'replace' => '<label class="danger">::</label>'),
	'toStartUnfound'		=> array('txt' => t('Eléments à lancer non trouvé', __FILE__), 'replace' => '<label class="danger">::</label>'),
	'invalideShedule'		=> array('txt' => t(', heure programmée invalide :', __FILE__, false, '', ' '), 'replace' => '<label class="danger">::</label>'),
	'noCmdFoundFor'			=> array('txt' => t('[Erreur] Aucune commande trouvée pour', __FILE__, false, '', ' '), 'replace' => '<label class="danger">::</label>'),
	'unfoundCmd'			=> array('txt' => t('Commande introuvable', __FILE__), 'replace' => '<label class="danger">::</label>'),
	'unfoundCmdCheckId'		=> array('txt' => t('Commande introuvable - Vérifiez l\'id', __FILE__), 'replace' => '<label class="danger">::</label>'),
	'unfoundEq'				=> array('txt' => t('Action sur l\'équipement impossible. Equipement introuvable - Vérifiez l\'id :', __FILE__, false, '', ' '), 'replace' => '<label class="danger">::</label>'),
	'unfoundScenario'		=> array('txt' => t('Action sur scénario impossible. Scénario introuvable - Vérifiez l\'id :', __FILE__, false, '', ' '), 'replace' => '<label class="danger">::</label>'),
	'disableScenario'		=> array('txt' => t('Impossible d\'exécuter le scénario :', __FILE__, false, '', ' '), 'replace' => '<label class="danger">::</label>'),
	'invalidExpr'			=> array('txt' => t('Expression non valide :', __FILE__, false, '', ' '), 'replace' => '<label class="danger">::</label>'),
	'invalidDuration'		=> array('txt' => t('Aucune durée trouvée pour l\'action sleep ou la durée n\'est pas valide :', __FILE__, false, '', ' '), 'replace' => '<label class="danger">::</label>'),
);
