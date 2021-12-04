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

global $JEEDOM_INTERNAL_CONFIG;
$JEEDOM_INTERNAL_CONFIG = array(
	'eqLogic' => array(
		'category' => array(
			'heating' => array('name' => __('Chauffage', __FILE__), 'icon' => 'fas fa-fire'),
			'security' => array('name' => __('Sécurité', __FILE__), 'icon' => 'fas fa-lock'),
			'energy' => array('name' => __('Energie', __FILE__), 'icon' => 'fas fa-bolt'),
			'light' => array('name' => __('Lumière', __FILE__), 'icon' => 'far fa-lightbulb'),
			'opening' => array('name' => __('Ouvrant', __FILE__), 'icon' => 'fas fa-door-open'),
			'automatism' => array('name' => __('Automatisme', __FILE__), 'icon' => 'fas fa-magic'),
			'multimedia' => array('name' => __('Multimédia', __FILE__), 'icon' => 'fas fa-sliders-h'),
			'default' => array('name' => __('Autre', __FILE__), 'icon' => 'far fa-circle'),
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
			'security' => array('name' => __('Sécurité', __FILE__), 'icon' => 'fas fa-lock'),
			'automation protocol' => array('name' => __('Protocole domotique', __FILE__), 'icon' => 'fas fa-rss'),
			'home automation protocol' => array('name' => __('Passerelle domotique', __FILE__), 'icon' => 'fas fa-asterisk'),
			'programming' => array('name' => __('Programmation', __FILE__), 'icon' => 'fas fa-code'),
			'organization' => array('name' => __('Organisation', __FILE__), 'icon' => 'far fa-calendar-alt', 'alias' => array('travel', 'finance')),
			'weather' => array('name' => __('Météo', __FILE__), 'icon' => 'far fa-sun'),
			'communication' => array('name' => __('Communication', __FILE__), 'icon' => 'fas fa-comment'),
			'devicecommunication' => array('name' => __('Objets connectés', __FILE__), 'icon' => 'fas fa-language'),
			'multimedia' => array('name' => __('Multimédia', __FILE__), 'icon' => 'fas fa-sliders-h'),
			'wellness' => array('name' => __('Confort', __FILE__), 'icon' => 'far fa-user'),
			'monitoring' => array('name' => __('Monitoring', __FILE__), 'icon' => 'fas fa-tachometer-alt'),
			'health' => array('name' => __('Santé', __FILE__), 'icon' => 'icon loisir-runner5'),
			'nature' => array('name' => __('Nature', __FILE__), 'icon' => 'icon nature-leaf32'),
			'automatisation' => array('name' => __('Automatisme', __FILE__), 'icon' => 'fas fa-magic'),
			'energy' => array('name' => __('Energie', __FILE__), 'icon' => 'fas fa-bolt'),
			'other' => array('name' => __('Autre', __FILE__), 'icon' => 'fas fa-bars'),
		),
	),
	'alerts' => array(
		'timeout' => array('name' => __('Timeout', __FILE__), 'icon' => 'far fa-clock', 'level' => 6, 'check' => false, 'color' => '#FF0000'),
		'batterywarning' => array('name' => __('Batterie en Warning', __FILE__), 'icon' => 'fas fa-battery-quarter', 'level' => 2, 'check' => false, 'color' => '#FFAB00'),
		'batterydanger' => array('name' => __('Batterie en Danger', __FILE__), 'icon' => 'fas fa-battery-empty', 'level' => 3, 'check' => false, 'color' => '#FF0000'),
		'warning' => array('name' => __('Warning', __FILE__), 'icon' => 'fas fa-bell', 'level' => 4, 'check' => true, 'color' => '#FFAB00'),
		'danger' => array('name' => __('Danger', __FILE__), 'icon' => 'fas fa-exclamation', 'level' => 5, 'check' => true, 'color' => '#FF0000'),
	),
	'cmd' => array(
		'widgets' => array(
			'action' => array(
				'other' => array(
					'light' => array('template' => 'tmplicon', 'replace' => array('#_icon_on_#' => '<i class=\'icon_yellow icon jeedom-lumiere-on\'></i>', '#_icon_off_#' => '<i class=\'icon jeedom-lumiere-off\'></i>')),
					'circle' => array('template' => 'tmplicon', 'replace' => array('#_icon_on_#' => '<i class=\'fas fa-circle\'></i>', '#_icon_off_#' => '<i class=\'far fa-circle\'></i>')),
					'fan' => array('template' => 'tmplicon', 'replace' => array('#_icon_on_#' => '<i class=\'icon jeedom-ventilo\'></i>', '#_icon_off_#' => '<i class=\'fas fa-times\'></i>')),
					'garage' => array('template' => 'tmplicon', 'replace' => array('#_icon_on_#' => '<i class=\'icon_green icon jeedom-garage-ferme\'></i>', '#_icon_off_#' => '<i class=\'icon_red icon jeedom-garage-ouvert\'></i>')),
					'lock' => array('template' => 'tmplicon', 'replace' => array('#_icon_on_#' => '<i class=\'icon_green icon jeedom-lock-ferme\'></i>', '#_icon_off_#' => '<i class=\'icon_orange icon jeedom-lock-ouvert\'></i>')),
					'prise' => array('template' => 'tmplicon', 'replace' => array('#_icon_on_#' => '<i class=\'icon jeedom-prise\'></i>', '#_icon_off_#' => '<i class=\'fas fa-times\'></i>')),
					'sprinkle' => array('template' => 'tmplicon', 'replace' => array('#_icon_on_#' => '<i class=\'icon_blue icon nature-watering1\'></i>', '#_icon_off_#' => '<i class=\'fas fa-times\'></i>')),
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
					'default' => array('template' => 'tmplicon', 'replace' => array('#_icon_on_#' => '<i class=\'icon_green fas fa-check\'></i>', '#_icon_off_#' => '<i class=\'icon_red fas fa-times\'></i>')),
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
			'LIGHT_TOGGLE' => array(
				'name' => __('Lumière Toggle', __FILE__), 'familyid' => 'Light', 'family' => __('Lumière', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'LIGHT_STATE' => array(
				'name' => __('Lumière Etat', __FILE__), 'familyid' => 'Light', 'family' => __('Lumière', __FILE__),
				'type' => 'Info', 'subtype' => array('binary', 'numeric')
			),
			'LIGHT_ON' => array(
				'name' => __('Lumière Bouton On', __FILE__), 'familyid' => 'Light', 'family' => __('Lumière', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'other'), 'subtype' => array('other')
			),
			'LIGHT_OFF' => array(
				'name' => __('Lumière Bouton Off', __FILE__), 'familyid' => 'Light', 'family' => __('Lumière', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'other'), 'subtype' => array('other')
			),
			'LIGHT_SLIDER' => array(
				'name' => __('Lumière Slider', __FILE__), 'familyid' => 'Light', 'family' => __('Lumière', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'slider'), 'subtype' => array('slider')
			),
			'LIGHT_BRIGHTNESS' => array(
				'name' => __('Lumière Luminosité', __FILE__), 'familyid' => 'Light', 'family' => __('Lumière', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'LIGHT_COLOR' => array(
				'name' => __('Lumière Couleur', __FILE__), 'familyid' => 'Light', 'family' => __('Lumière', __FILE__),
				'type' => 'Info', 'subtype' => array('string')
			),
			'LIGHT_SET_COLOR' => array(
				'name' => __('Lumière Couleur', __FILE__), 'familyid' => 'Light', 'family' => __('Lumière', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'color'), 'subtype' => array('color')
			),
			'LIGHT_MODE' => array(
				'name' => __('Lumière Mode', __FILE__), 'familyid' => 'Light', 'family' => __('Lumière', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'LIGHT_STATE_BOOL' => array(
				'name' => __('Lumière Etat (Binaire)', __FILE__), 'familyid' => 'Light', 'family' => __('Lumière', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'LIGHT_COLOR_TEMP' => array(
				'name' => __('Lumière Température Couleur', __FILE__), 'familyid' => 'Light', 'family' => __('Lumière', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric')
			),
			'LIGHT_SET_COLOR_TEMP' => array(
				'name' => __('Lumière Température Couleur', __FILE__), 'familyid' => 'Light', 'family' => __('Lumière', __FILE__),
				'type' => 'Action'
			),
			'ENERGY_STATE' => array(
				'name' => __('Prise Etat', __FILE__), 'familyid' => 'Outlet', 'family' => __('Prise', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric', 'binary')
			),
			'ENERGY_ON' => array(
				'name' => __('Prise Bouton On', __FILE__), 'familyid' => 'Outlet', 'family' => __('Prise', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'other'), 'subtype' => array('other')
			),
			'ENERGY_OFF' => array(
				'name' => __('Prise Bouton Off', __FILE__), 'familyid' => 'Outlet', 'family' => __('Prise', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'other'), 'subtype' => array('other')
			),
			'ENERGY_SLIDER' => array(
				'name' => __('Prise Slider', __FILE__), 'familyid' => 'Outlet', 'family' => __('Prise', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'slider')
			),
			'FLAP_STATE' => array(
				'name' => __('Volet Etat', __FILE__), 'familyid' => 'Shutter', 'family' => __('Volet', __FILE__),
				'type' => 'Info', 'subtype' => array('binary', 'numeric')
			),
			'FLAP_UP' => array(
				'name' => __('Volet Bouton Monter', __FILE__), 'familyid' => 'Shutter', 'family' => __('Volet', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'other'), 'subtype' => array('other')
			),
			'FLAP_DOWN' => array(
				'name' => __('Volet Bouton Descendre', __FILE__), 'familyid' => 'Shutter', 'family' => __('Volet', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'other'), 'subtype' => array('other')
			),
			'FLAP_STOP' => array(
				'name' => __('Volet Bouton Stop', __FILE__), 'familyid' => 'Shutter', 'family' => __('Volet', __FILE__),
				'type' => 'Action'
			),
			'FLAP_SLIDER' => array(
				'name' => __('Volet Bouton Slider', __FILE__), 'familyid' => 'Shutter', 'family' => __('Volet', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'slider'), 'subtype' => array('slider')
			),
			'FLAP_BSO_STATE' => array(
				'name' => __('Volet BSO Etat', __FILE__), 'familyid' => 'Shutter', 'family' => __('Volet', __FILE__),
				'type' => 'Info', 'subtype' => array('binary', 'numeric')
			),
			'FLAP_BSO_UP' => array(
				'name' => __('Volet BSO Bouton Monter', __FILE__), 'familyid' => 'Shutter', 'family' => __('Volet', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'other'), 'subtype' => array('other')
			),
			'FLAP_BSO_DOWN' => array(
				'name' => __('Volet BSO Bouton Descendre', __FILE__), 'familyid' => 'Shutter', 'family' => __('Volet', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'other'), 'subtype' => array('other')
			),
			'HEATING_ON' => array(
				'name' => __('Chauffage fil pilote Bouton ON', __FILE__), 'familyid' => 'Heating', 'family' => __('Chauffage', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'other'), 'subtype' => array('other')
			),
			'HEATING_OFF' => array(
				'name' => __('Chauffage fil pilote Bouton OFF', __FILE__), 'familyid' => 'Heating', 'family' => __('Chauffage', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'other'), 'subtype' => array('other')
			),
			'HEATING_STATE' => array(
				'name' => __('Chauffage fil pilote Etat', __FILE__), 'familyid' => 'Heating', 'family' => __('Chauffage', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'HEATING_OTHER' => array(
				'name' => __('Chauffage fil pilote Bouton', __FILE__), 'familyid' => 'Heating', 'family' => __('Chauffage', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'LOCK_STATE' => array(
				'name' => __('Serrure Etat', __FILE__), 'familyid' => 'Opening', 'family' => __('Ouvrant', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'LOCK_OPEN' => array(
				'name' => __('Serrure Bouton Ouvrir', __FILE__), 'familyid' => 'Opening', 'family' => __('Ouvrant', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'other'), 'subtype' => array('other')
			),
			'LOCK_CLOSE' => array(
				'name' => __('Serrure Bouton Fermer', __FILE__), 'familyid' => 'Opening', 'family' => __('Ouvrant', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'other'), 'subtype' => array('other')
			),
			'GB_OPEN' => array(
				'name' => __('Portail ou garage bouton d\'ouverture', __FILE__), 'familyid' => 'Opening', 'family' => __('Ouvrant', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'other'), 'subtype' => array('other')
			),
			'GB_CLOSE' => array(
				'name' => __('Portail ou garage bouton de fermeture', __FILE__), 'familyid' => 'Opening', 'family' => __('Ouvrant', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'other'), 'subtype' => array('other')
			),
			'GB_TOGGLE' => array(
				'name' => __('Portail ou garage bouton toggle', __FILE__), 'familyid' => 'Opening', 'family' => __('Ouvrant', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'BARRIER_STATE' => array(
				'name' => __('Portail (ouvrant) Etat', __FILE__), 'familyid' => 'Opening', 'family' => __('Ouvrant', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'GARAGE_STATE' => array(
				'name' => __('Garage (ouvrant) Etat', __FILE__), 'familyid' => 'Opening', 'family' => __('Ouvrant', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'OPENING' => array(
				'name' => __('Porte', __FILE__), 'familyid' => 'Opening', 'family' => __('Ouvrant', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'OPENING_WINDOW' => array(
				'name' => __('Fenêtre', __FILE__), 'familyid' => 'Opening', 'family' => __('Ouvrant', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'THERMOSTAT_STATE' => array(
				'name' => __('Thermostat Etat', __FILE__), 'familyid' => 'Thermostat', 'family' => __('Thermostat', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'THERMOSTAT_TEMPERATURE' => array(
				'name' => __('Thermostat Température ambiante', __FILE__), 'familyid' => 'Thermostat', 'family' => __('Thermostat', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'THERMOSTAT_SET_SETPOINT' => array(
				'name' => __('Thermostat consigne', __FILE__), 'familyid' => 'Thermostat', 'family' => __('Thermostat', __FILE__),
				'type' => 'Action', 'subtype' => array('slider')
			),
			'THERMOSTAT_SETPOINT' => array(
				'name' => __('Thermostat consigne', __FILE__), 'familyid' => 'Thermostat', 'family' => __('Thermostat', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'THERMOSTAT_SET_MODE' => array(
				'name' => __('Thermostat Mode', __FILE__), 'familyid' => 'Thermostat', 'family' => __('Thermostat', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'THERMOSTAT_MODE' => array(
				'name' => __('Thermostat Mode', __FILE__), 'familyid' => 'Thermostat', 'family' => __('Thermostat', __FILE__),
				'type' => 'Info', 'subtype' => array('string')
			),
			'THERMOSTAT_SET_LOCK' => array(
				'name' => __('Thermostat Verrouillage', __FILE__), 'familyid' => 'Thermostat', 'family' => __('Thermostat', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'THERMOSTAT_SET_UNLOCK' => array(
				'name' => __('Thermostat Déverrouillage', __FILE__), 'familyid' => 'Thermostat', 'family' => __('Thermostat', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'THERMOSTAT_LOCK' => array(
				'name' => __('Thermostat Verrouillage', __FILE__), 'familyid' => 'Thermostat', 'family' => __('Thermostat', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'THERMOSTAT_TEMPERATURE_OUTDOOR' => array(
				'name' => __('Thermostat Température Exterieur', __FILE__), 'familyid' => 'Thermostat', 'family' => __('Thermostat', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'THERMOSTAT_STATE_NAME' => array(
				'name' => __('Thermostat Etat (HUMAIN)', __FILE__), 'familyid' => 'Thermostat', 'family' => __('Thermostat', __FILE__),
				'type' => 'Info', 'subtype' => array('string')
			),
			'THERMOSTAT_HUMIDITY' => array(
				'name' => __('Thermostat humidité ambiante', __FILE__), 'familyid' => 'Thermostat', 'family' => __('Thermostat', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'HUMIDITY_SET_SETPOINT' => array(
				'name' => __('Humidité consigne', __FILE__), 'familyid' => 'Thermostat', 'family' => __('Thermostat', __FILE__),
				'type' => 'Action', 'subtype' => array('slider')
			),
			'HUMIDITY_SETPOINT' => array(
				'name' => __('Humidité consigne', __FILE__), 'familyid' => 'Thermostat', 'family' => __('Thermostat', __FILE__),
				'type' => 'Info', 'subtype' => array('slider')
			),
			'CAMERA_UP' => array(
				'name' => __('Mouvement caméra vers le haut', __FILE__), 'familyid' => 'Camera', 'family' => __('Caméra', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'CAMERA_DOWN' => array(
				'name' => __('Mouvement caméra vers le bas', __FILE__), 'familyid' => 'Camera', 'family' => __('Caméra', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'CAMERA_LEFT' => array(
				'name' => __('Mouvement caméra vers la gauche', __FILE__), 'familyid' => 'Camera', 'family' => __('Caméra', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'CAMERA_RIGHT' => array(
				'name' => __('Mouvement caméra vers la droite', __FILE__), 'familyid' => 'Camera', 'family' => __('Caméra', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'CAMERA_ZOOM' => array(
				'name' => __('Zoom caméra vers l\'avant', __FILE__), 'familyid' => 'Camera', 'family' => __('Caméra', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'CAMERA_DEZOOM' => array(
				'name' => __('Zoom caméra vers l\'arrière', __FILE__), 'familyid' => 'Camera', 'family' => __('Caméra', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'CAMERA_STOP' => array(
				'name' => __('Stop caméra', __FILE__), 'familyid' => 'Camera', 'family' => __('Caméra', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'CAMERA_PRESET' => array(
				'name' => __('Preset caméra', __FILE__), 'familyid' => 'Camera', 'family' => __('Caméra', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'CAMERA_URL' => array(
				'name' => __('URL caméra', __FILE__), 'familyid' => 'Camera', 'family' => __('Caméra', __FILE__),
				'type' => 'Info', 'subtype' => array('string')
			),
			'CAMERA_RECORD_STATE' => array(
				'name' => __('État enregistrement caméra', __FILE__), 'familyid' => 'Camera', 'family' => __('Caméra', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'CAMERA_RECORD' => array(
				'name' => __('Enregistrement caméra', __FILE__), 'familyid' => 'Camera', 'family' => __('Caméra', __FILE__),
				'type' => 'Action'
			),
			'CAMERA_TAKE' => array(
				'name' => __('Snapshot caméra', __FILE__), 'familyid' => 'Camera', 'family' => __('Caméra', __FILE__),
				'type' => 'Action'
			),
			'MODE_STATE' => array(
				'name' => __('Mode Etat', __FILE__), 'familyid' => 'Mode', 'family' => __('Mode', __FILE__),
				'type' => 'Info', 'subtype' => array('string')
			),
			'MODE_SET_STATE' => array(
				'name' => __('Changer Mode', __FILE__), 'familyid' => 'Mode', 'family' => __('Mode', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'SIREN_STATE' => array(
				'name' => __('Sirène Etat', __FILE__), 'familyid' => 'Security', 'family' => __('Sécurité', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'SIREN_OFF' => array(
				'name' => __('Sirène Bouton Off', __FILE__), 'familyid' => 'Security', 'family' => __('Sécurité', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'other'), 'subtype' => array('other')
			),
			'SIREN_ON' => array(
				'name' => __('Sirène Bouton On', __FILE__), 'familyid' => 'Security', 'family' => __('Sécurité', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'other'), 'subtype' => array('other')
			),
			'ALARM_STATE' => array(
				'name' => __('Alarme Etat', __FILE__), 'familyid' => 'Security', 'family' => __('Sécurité', __FILE__),
				'type' => 'Info', 'subtype' => array('binary', 'string')
			),
			'ALARM_MODE' => array(
				'name' => __('Alarme mode', __FILE__), 'familyid' => 'Security', 'family' => __('Sécurité', __FILE__),
				'type' => 'Info', 'subtype' => array('string')
			),
			'ALARM_ENABLE_STATE' => array(
				'name' => __('Alarme Etat activée', __FILE__), 'familyid' => 'Security', 'family' => __('Sécurité', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'ALARM_ARMED' => array(
				'name' => __('Alarme armée', __FILE__), 'familyid' => 'Security', 'family' => __('Sécurité', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'ALARM_RELEASED' => array(
				'name' => __('Alarme libérée', __FILE__), 'familyid' => 'Security', 'family' => __('Sécurité', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'ALARM_SET_MODE' => array(
				'name' => __('Alarme Mode', __FILE__), 'familyid' => 'Security', 'family' => __('Sécurité', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'FLOOD' => array(
				'name' => __('Inondation', __FILE__), 'familyid' => 'Security', 'family' => __('Sécurité', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'SABOTAGE' => array(
				'name' => __('Sabotage', __FILE__), 'familyid' => 'Security', 'family' => __('Sécurité', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'SHOCK' => array(
				'name' => __('Choc', __FILE__), 'familyid' => 'Security', 'family' => __('Sécurité', __FILE__),
				'type' => 'Info', 'subtype' => array('binary', 'numeric')
			),
			'WEATHER_TEMPERATURE' => array(
				'name' => __('Météo Température', __FILE__), 'familyid' => 'Weather', 'family' => __('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WEATHER_HUMIDITY' => array(
				'name' => __('Météo Humidité', __FILE__), 'familyid' => 'Weather', 'family' => __('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WEATHER_PRESSURE' => array(
				'name' => __('Météo Pression', __FILE__), 'familyid' => 'Weather', 'family' => __('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WEATHER_WIND_SPEED' => array(
				'name' => __('Météo vitesse du vent', __FILE__), 'familyid' => 'Weather', 'family' => __('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WEATHER_WIND_DIRECTION' => array(
				'name' => __('Météo direction du vent', __FILE__), 'familyid' => 'Weather', 'family' => __('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WEATHER_SUNSET' => array(
				'name' => __('Météo lever de soleil', __FILE__), 'familyid' => 'Weather', 'family' => __('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WEATHER_SUNRISE' => array(
				'name' => __('Météo coucher de soleil', __FILE__), 'familyid' => 'Weather', 'family' => __('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WEATHER_TEMPERATURE_MIN' => array(
				'name' => __('Météo Température min', __FILE__), 'familyid' => 'Weather', 'family' => __('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WEATHER_TEMPERATURE_MAX' => array(
				'name' => __('Météo Température max', __FILE__), 'familyid' => 'Weather', 'family' => __('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WEATHER_CONDITION' => array(
				'name' => __('Météo condition', __FILE__), 'familyid' => 'Weather', 'family' => __('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('string')
			),
			'WEATHER_CONDITION_ID' => array(
				'name' => __('Météo condition (id)', __FILE__), 'familyid' => 'Weather', 'family' => __('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'text'
			),
			'WEATHER_TEMPERATURE_MIN_1' => array(
				'name' => __('Météo Température min j+1', __FILE__), 'familyid' => 'Weather', 'family' => __('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WEATHER_TEMPERATURE_MAX_1' => array(
				'name' => __('Météo Température max j+1', __FILE__), 'familyid' => 'Weather', 'family' => __('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WEATHER_CONDITION_1' => array(
				'name' => __('Météo condition j+1', __FILE__), 'familyid' => 'Weather', 'family' => __('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('string'), 'calcul' => 'text'
			),
			'WEATHER_CONDITION_ID_1' => array(
				'name' => __('Météo condition (id) j+1', __FILE__), 'familyid' => 'Weather', 'family' => __('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'text'
			),
			'WEATHER_TEMPERATURE_MIN_2' => array(
				'name' => __('Météo Température min j+2', __FILE__), 'familyid' => 'Weather', 'family' => __('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WEATHER_TEMPERATURE_MAX_2' => array(
				'name' => __('Météo condition j+1 max j+2', __FILE__), 'familyid' => 'Weather', 'family' => __('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WEATHER_CONDITION_2' => array(
				'name' => __('Météo condition j+2', __FILE__), 'familyid' => 'Weather', 'family' => __('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('string'), 'calcul' => 'text'
			),
			'WEATHER_CONDITION_ID_2' => array(
				'name' => __('Météo condition (id) j+2', __FILE__), 'familyid' => 'Weather', 'family' => __('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'text'
			),
			'WEATHER_TEMPERATURE_MIN_3' => array(
				'name' => __('Météo Température min j+3', __FILE__), 'familyid' => 'Weather', 'family' => __('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WEATHER_TEMPERATURE_MAX_3' => array(
				'name' => __('Météo Température max j+3', __FILE__), 'familyid' => 'Weather', 'family' => __('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WEATHER_CONDITION_3' => array(
				'name' => __('Météo condition j+3', __FILE__), 'familyid' => 'Weather', 'family' => __('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('string'), 'calcul' => 'text'
			),
			'WEATHER_CONDITION_ID_3' => array(
				'name' => __('Météo condition (id) j+3', __FILE__), 'familyid' => 'Weather', 'family' => __('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'text'
			),
			'WEATHER_TEMPERATURE_MIN_4' => array(
				'name' => __('Météo Température min j+4', __FILE__), 'familyid' => 'Weather', 'family' => __('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WEATHER_TEMPERATURE_MAX_4' => array(
				'name' => __('Météo Température max j+4', __FILE__), 'familyid' => 'Weather', 'family' => __('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WEATHER_CONDITION_4' => array(
				'name' => __('Météo condition j+4', __FILE__), 'familyid' => 'Weather', 'family' => __('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('string'), 'calcul' => 'text'
			),
			'WEATHER_CONDITION_ID_4' => array(
				'name' => __('Météo condition (id) j+4', __FILE__), 'familyid' => 'Weather', 'family' => __('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'text'
			),
			'RAIN_CURRENT' => array(
				'name' => __('Pluie (mm/h)', __FILE__), 'familyid' => 'Weather', 'family' => __('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'RAIN_TOTAL' => array(
				'name' => __('Pluie (accumulation)', __FILE__), 'familyid' => 'Weather', 'family' => __('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WIND_SPEED' => array(
				'name' => __('Vent (vitesse)', __FILE__), 'familyid' => 'Weather', 'family' => __('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WIND_DIRECTION' => array(
				'name' => __('Vent (direction)', __FILE__), 'familyid' => 'Weather', 'family' => __('Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'POWER' => array(
				'name' => __('Puissance Electrique', __FILE__), 'familyid' => 'Electricity', 'family' => __('Electricité', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric')
			),
			'CONSUMPTION' => array(
				'name' => __('Consommation Electrique', __FILE__), 'familyid' => 'Electricity', 'family' => __('Electricité', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric')
			),
			'VOLTAGE' => array(
				'name' => __('Tension', __FILE__), 'familyid' => 'Electricity', 'family' => __('Electricité', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'REBOOT' => array(
				'name' => __('Redémarrage', __FILE__), 'familyid' => 'Electricity', 'family' => __('Electricité', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'TEMPERATURE' => array(
				'name' => __('Température', __FILE__), 'familyid' => 'Environment', 'family' => __('Environnement', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'AIR_QUALITY' => array(
				'name' => __('Qualité de l\'air', __FILE__), 'familyid' => 'Environment', 'family' => __('Environnement', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'BRIGHTNESS' => array(
				'name' => __('Luminosité', __FILE__), 'familyid' => 'Environment', 'family' => __('Environnement', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'PRESENCE' => array(
				'name' => __('Présence', __FILE__), 'familyid' => 'Environment', 'family' => __('Environnement', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'SMOKE' => array(
				'name' => __('Détection de fumée', __FILE__), 'familyid' => 'Environment', 'family' => __('Environnement', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'HUMIDITY' => array(
				'name' => __('Humidité', __FILE__), 'familyid' => 'Environment', 'family' => __('Environnement', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'UV' => array(
				'name' => __('UV', __FILE__), 'familyid' => 'Environment', 'family' => __('Environnement', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'CO2' => array(
				'name' => __('CO2 (ppm)', __FILE__), 'familyid' => 'Environment', 'family' => __('Environnement', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'CO' => array(
				'name' => __('CO (ppm)', __FILE__), 'familyid' => 'Environment', 'family' => __('Environnement', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'NOISE' => array(
				'name' => __('Son (dB)', __FILE__), 'familyid' => 'Environment', 'family' => __('Environnement', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'PRESSURE' => array(
				'name' => __('Pression', __FILE__), 'familyid' => 'Environment', 'family' => __('Environnement', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WATER_LEAK' => array(
				'name' => __('Fuite d\'eau', __FILE__), 'familyid' => 'Environment', 'family' => __('Environnement', __FILE__),
				'type' => 'Info'
			),
			'FILTER_CLEAN_STATE' => array(
				'name' => __('Etat du filtre', __FILE__), 'familyid' => 'Environment', 'family' => __('Environnement', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'DEPTH' => array(
				'name' => __('Profondeur', __FILE__), 'familyid' => 'Generic', 'family' => __('Generic', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'DISTANCE' => array(
				'name' => __('Distance', __FILE__), 'familyid' => 'Generic', 'family' => __('Generic', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'BUTTON' => array(
				'name' => __('Bouton', __FILE__), 'familyid' => 'Generic', 'family' => __('Generic', __FILE__),
				'type' => 'Info', 'subtype' => array('binary', 'numeric')
			),
			'GENERIC_INFO' => array(
				'name' => ' ' . __('Générique', __FILE__), 'familyid' => 'Generic', 'family' => __('Generic', __FILE__),
				'type' => 'Info'
			),
			'GENERIC_ACTION' => array(
				'name' => ' ' . __('Générique', __FILE__), 'familyid' => 'Generic', 'family' => __('Generic', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'DONT' => array(
				'name' => __('Ne pas tenir compte de cette commande', __FILE__), 'familyid' => 'Generic', 'family' => __('Generic', __FILE__),
				'type' => 'All'
			),
			'BATTERY' => array(
				'name' => __('Batterie', __FILE__), 'familyid' => 'Battery', 'family' => __('Batterie', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'BATTERY_CHARGING' => array(
				'name' => __('Batterie en charge', __FILE__), 'familyid' => 'Battery', 'family' => __('Batterie', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'VOLUME' => array(
				'name' => __('Volume', __FILE__), 'familyid' => 'Multimedia', 'family' => __('Multimédia', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'MEDIA_STATUS' => array(
				'name' => __('Status', __FILE__), 'familyid' => 'Multimedia', 'family' => __('Multimédia', __FILE__),
				'type' => 'Info', 'subtype' => array('string')
			),
			'MEDIA_ALBUM' => array(
				'name' => __('Album', __FILE__), 'familyid' => 'Multimedia', 'family' => __('Multimédia', __FILE__),
				'type' => 'Info', 'subtype' => array('string')
			),
			'MEDIA_ARTIST' => array(
				'name' => __('Artiste', __FILE__), 'familyid' => 'Multimedia', 'family' => __('Multimédia', __FILE__),
				'type' => 'Info', 'subtype' => array('string')
			),
			'MEDIA_TITLE' => array(
				'name' => __('Titre', __FILE__), 'familyid' => 'Multimedia', 'family' => __('Multimédia', __FILE__),
				'type' => 'Info', 'subtype' => array('string')
			),
			'MEDIA_POWER' => array(
				'name' => __('Power', __FILE__), 'familyid' => 'Multimedia', 'family' => __('Multimédia', __FILE__),
				'type' => 'Info', 'subtype' => array('string')
			),
			'SET_VOLUME' => array(
				'name' => __('Volume', __FILE__), 'familyid' => 'Multimedia', 'family' => __('Multimédia', __FILE__),
				'type' => 'Action', 'subtype' => array('slider')
			),
			'CHANNEL' => array(
				'name' => __('Chaine', __FILE__), 'familyid' => 'Multimedia', 'family' => __('Multimédia', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric', 'string'), 'calcul' => 'text'
			),
			'SET_CHANNEL' => array(
				'name' => __('Chaine', __FILE__), 'familyid' => 'Multimedia', 'family' => __('Multimédia', __FILE__),
				'type' => 'Action', 'subtype' => array('other', 'slider')
			),
			'MEDIA_PAUSE' => array(
				'name' => __('Pause', __FILE__), 'familyid' => 'Multimedia', 'family' => __('Multimédia', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'MEDIA_RESUME' => array(
				'name' => __('Lecture', __FILE__), 'familyid' => 'Multimedia', 'family' => __('Multimédia', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'MEDIA_STOP' => array(
				'name' => __('Stop', __FILE__), 'familyid' => 'Multimedia', 'family' => __('Multimédia', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'MEDIA_NEXT' => array(
				'name' => __('Suivant', __FILE__), 'familyid' => 'Multimedia', 'family' => __('Multimédia', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'MEDIA_PREVIOUS' => array(
				'name' => __('Précedent', __FILE__), 'familyid' => 'Multimedia', 'family' => __('Multimédia', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'MEDIA_ON' => array(
				'name' => __('On', __FILE__), 'familyid' => 'Multimedia', 'family' => __('Multimédia', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'MEDIA_OFF' => array(
				'name' => __('Off', __FILE__), 'familyid' => 'Multimedia', 'family' => __('Multimédia', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'MEDIA_STATE' => array(
				'name' => __('Etat', __FILE__), 'familyid' => 'Multimedia', 'family' => __('Multimédia', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'MEDIA_MUTE' => array(
				'name' => __('Muet', __FILE__), 'familyid' => 'Multimedia', 'family' => __('Multimédia', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'MEDIA_UNMUTE' => array(
				'name' => __('Non Muet', __FILE__), 'familyid' => 'Multimedia', 'family' => __('Multimédia', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'FAN_SPEED' => array(
				'name' => __('Vitesse ventilateur', __FILE__), 'familyid' => 'Fan', 'family' => __('Ventilateur', __FILE__),
				'type' => 'Action', 'subtype' => array('slider')
			),
			'FAN_SPEED_STATE' => array(
				'name' => __('Vitesse ventilateur Etat', __FILE__), 'familyid' => 'Fan', 'family' => __('Ventilateur', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'ROTATION' => array(
				'name' => __('Rotation', __FILE__), 'familyid' => 'Fan', 'family' => __('Ventilateur', __FILE__),
				'type' => 'Action', 'subtype' => array('slider')
			),
			'ROTATION_STATE' => array(
				'name' => __('Rotation Etat', __FILE__), 'familyid' => 'Fan', 'family' => __('Ventilateur', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'DOCK' => array(
				'name' => __('Retour base', __FILE__), 'familyid' => 'Robot', 'family' => __('Robot', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'DOCK_STATE' => array(
				'name' => __('Base Etat', __FILE__), 'familyid' => 'Robot', 'family' => __('Robot', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'TIMER' => array(
				'name' => __('Minuteur Etat', __FILE__), 'familyid' => 'Other', 'family' => __('Autre', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'SET_TIMER' => array(
				'name' => __('Minuteur', __FILE__), 'familyid' => 'Other', 'family' => __('Autre', __FILE__),
				'type' => 'Action', 'subtype' => array('slider')
			),
			'TIMER_STATE' => array(
				'name' => __('Minuteur Etat (pause ou non)', __FILE__), 'familyid' => 'Other', 'family' => __('Autre', __FILE__),
				'type' => 'Info', 'subtype' => array('binary', 'numeric'), 'calcul' => 'avg'
			),
			'TIMER_PAUSE' => array(
				'name' => __('Minuteur pause', __FILE__), 'familyid' => 'Other', 'family' => __('Autre', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'TIMER_RESUME' => array(
				'name' => __('Minuteur reprendre', __FILE__), 'familyid' => 'Other', 'family' => __('Autre', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
		),
		'type' => array(
			'info' => array(
				'name' => __('Info', __FILE__),
				'subtype' => array(
					'numeric' => array(
						'name' => __('Numérique', __FILE__),
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
						'name' => __('Binaire', __FILE__),
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
						'name' => __('Autre', __FILE__),
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
				'name' => __('Action', __FILE__),
				'subtype' => array(
					'other' => array(
						'name' => __('Défaut', __FILE__),
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
						'name' => __('Curseur', __FILE__),
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
						'name' => __('Message', __FILE__),
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
						'name' => __('Couleur', __FILE__),
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
						'name' => __('Liste', __FILE__),
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
	'startManual' 			=> array('txt' => __('Scénario lancé manuellement', __FILE__), 'replace' => '<label class="success">::</label>'),
	'startAutoOnEvent'		=> array('txt' => __('Scénario exécuté automatiquement sur événement venant de :', __FILE__) . ' ', 'replace' => '<label class="success">::</label>'),
	'startOnEvent'			=> array('txt' => __('Scénario exécuté sur événement', __FILE__), 'replace' => '<label class="success">::</label>'),
	'startAutoOnShedule'	=> array('txt' => __('Scénario exécuté automatiquement sur programmation', __FILE__), 'replace' => '<label class="success">::</label>'),
	'finishOk' 				=> array('txt' => __('Fin correcte du scénario', __FILE__), 'replace' => '<label class="success">::</label>'),
	'sheduledOn'			=> array('txt' => ' ' . __('programmée à :', __FILE__) . ' ', 'replace' => '<label class="success"> :: </label>'),
	'startByScenario'		=> array('txt' => __('Lancement provoqué par le scénario  :', __FILE__) . ' ', 'replace' => '<label class="success">:: </label>'),
	'startCausedBy'			=> array('txt' => __('Lancement provoqué', __FILE__), 'replace' => '<label class="success">::</label>'),
	'startSubTask' 			=> array('txt' => __('************Lancement sous tâche**************', __FILE__), 'replace' => '<label class="success">::</label>'),
	'endSubTask' 			=> array('txt' => __('************FIN sous tâche**************', __FILE__), 'replace' => '<label class="success">::</label>'),
	'sheduleNow'			=> array('txt' => ' ' . __('lancement immédiat', __FILE__) . ' ', 'replace' => '<label class="success">::</label>'),

	'execAction'			=> array('txt' => __('Exécution du sous-élément de type [action] :', __FILE__) . ' ', 'replace' => '<label class="info">- ::</label>'),
	'execCondition'			=> array('txt' => __('Exécution du sous-élément de type [condition] :', __FILE__) . ' ', 'replace' => '<label class="info">- ::</label>'),

	'execCmd'				=> array('txt' => __('Exécution de la commande', __FILE__) . ' ', 'replace' => '<label class="warning">:: </label>'),
	'execCode'				=> array('txt' => __('Exécution d\'un bloc code', __FILE__), 'replace' => '<label class="warning">:: </label>'),
	'launchScenario'		=> array('txt' => __('Lancement du scénario :', __FILE__) . ' ', 'replace' => '<label class="warning">:: </label>'),
	'launchScenarioSync'	=> array('txt' => __('Lancement du scénario en mode synchrone', __FILE__), 'replace' => '<label class="warning">:: </label>'),
	'task'					=> array('txt' => __('Tâche :', __FILE__) . ' ', 'replace' => '<label class="warning">:: </label>'),
	'event'					=> array('txt' => __('Changement de', __FILE__) . ' ', 'replace' => '<label class="warning">:: </label>'),

	'stopTimeout'			=> array('txt' => __('Arrêt du scénario car il a dépassé son temps de timeout :', __FILE__) . ' ', 'replace' => '<label class="danger">:: </label>'),
	'disableNoSubtask'		=> array('txt' => __('Scénario désactivé non lancement de la sous tâche', __FILE__), 'replace' => '<label class="danger">::</label>'),
	'disableEqNoExecCmd'	=> array('txt' => __('Equipement désactivé - impossible d\'exécuter la commande :', __FILE__) . ' ', 'replace' => '<label class="danger">:: </label>'),
	'toStartUnfound'		=> array('txt' => __('Eléments à lancer non trouvé', __FILE__), 'replace' => '<label class="danger">::</label>'),
	'invalideShedule'		=> array('txt' => __(', heure programmée invalide :', __FILE__) . ' ', 'replace' => '<label class="danger">::</label>'),
	'noCmdFoundFor'			=> array('txt' => __('[Erreur] Aucune commande trouvée pour', __FILE__) . ' ', 'replace' => '<label class="danger">::</label>'),
	'unfoundCmd'			=> array('txt' => __('Commande introuvable', __FILE__), 'replace' => '<label class="danger">::</label>'),
	'unfoundCmdCheckId'		=> array('txt' => __('Commande introuvable - Vérifiez l\'id', __FILE__), 'replace' => '<label class="danger">::</label>'),
	'unfoundEq'				=> array('txt' => __('Action sur l\'équipement impossible. Equipement introuvable - Vérifiez l\'id :', __FILE__) . ' ', 'replace' => '<label class="danger">::</label>'),
	'unfoundScenario'		=> array('txt' => __('Action sur scénario impossible. Scénario introuvable - Vérifiez l\'id :', __FILE__) . ' ', 'replace' => '<label class="danger">::</label>'),
	'disableScenario'		=> array('txt' => __('Impossible d\'exécuter le scénario :', __FILE__) . ' ', 'replace' => '<label class="danger">:: </label>'),
	'invalidExpr'			=> array('txt' => __('Expression non valide :', __FILE__) . ' ', 'replace' => '<label class="danger">:: </label>'),
	'invalidDuration'		=> array('txt' => __('Aucune durée trouvée pour l\'action sleep ou la durée n\'est pas valide :', __FILE__) . ' ', 'replace' => '<label class="danger">::</label>'),
);
//$this->setLog(    $GLOBALS['JEEDOM_SCLOG_TEXT']['startCausedBy']['txt']    )