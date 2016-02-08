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
			'heating' => array('name' => 'Chauffage', 'color' => '#2980b9', 'mcolor' => '#2980b9', 'cmdColor' => '#3498db', 'mcmdColor' => '#3498db'),
			'security' => array('name' => 'Sécurité', 'color' => '#745cb0', 'mcolor' => '#745cb0', 'cmdColor' => '#ac92ed', 'mcmdColor' => '#ac92ed'),
			'energy' => array('name' => 'Energie', 'color' => '#2eb04b', 'mcolor' => '#2eb04b', 'cmdColor' => '#69e283', 'mcmdColor' => '#69e283'),
			'light' => array('name' => 'Lumière', 'color' => '#f39c12', 'mcolor' => '#f39c12', 'cmdColor' => '#f1c40f', 'mcmdColor' => '#f1c40f'),
			'automatism' => array('name' => 'Automatisme', 'color' => '#808080', 'mcolor' => '#808080', 'cmdColor' => '#c2beb8', 'mcmdColor' => '#c2beb8'),
			'multimedia' => array('name' => 'Multimedia', 'color' => '#34495e', 'mcolor' => '#34495e', 'cmdColor' => '#576E84', 'mcmdColor' => '#576E84'),
			'default' => array('name' => 'Defaut', 'color' => '#19bc9c', 'mcolor' => '#19bc9c', 'cmdColor' => '#4CDFC2', 'mcmdColor' => '#4CDFC2'),
		),
		'style' => array(
			'noactive' => '-webkit-filter: grayscale(100%);-moz-filter: grayscale(100);-o-filter: grayscale(100%);-ms-filter: grayscale(100%);filter: grayscale(100%); opacity: 0.35;',
		),
	),
	'plugin' => array(
		'category' => array(
			'security' => array('name' => 'Sécurité', 'icon' => 'fa-lock'),
			'automation protocol' => array('name' => 'Protocole domotique', 'icon' => 'fa-rss'),
			'programming' => array('name' => 'Programmation', 'icon' => 'fa-code'),
			'Panel' => array('name' => 'Panel', 'icon' => 'fa-thumb-tack'),
			'organization' => array('name' => 'Organisation', 'icon' => 'fa-calendar'),
			'weather' => array('name' => 'Météo', 'icon' => 'fa-sun-o'),
			'communication' => array('name' => 'Communication', 'icon' => 'fa-comment-o'),
			'multimedia' => array('name' => 'Multimédia', 'icon' => 'fa-sliders'),
			'wellness' => array('name' => 'Bien-être', 'icon' => 'fa-user'),
			'jeedomBox' => array('name' => 'Jeedom Box', 'icon' => 'fa-dropbox'),
			'monitoring' => array('name' => 'Monitoring', 'icon' => 'fa-tachometer'),
			'health' => array('name' => 'Santé', 'icon' => 'icon loisir-runner5'),
			'nature' => array('name' => 'Nature', 'icon' => 'icon nature-leaf32'),
			'finance' => array('name' => 'Finance', 'icon' => 'fa fa-eur'),
			'automatisation' => array('name' => 'Automatisme', 'icon' => 'fa fa-magic'),
			'energy' => array('name' => 'Energie', 'icon' => 'fa fa-bolt'),
			'travel' => array('name' => 'Déplacement', 'icon' => 'fa fa-car'),
		),
	),
	'cmd' => array(
		'generic_type' => array(
			'LIGHT_STATE' => array('name' => 'Lumière Etat (info)'),
			'LIGHT_ON' => array('name' => 'Lumière Bouton On (action)'),
			'LIGHT_OFF' => array('name' => 'Lumière Bouton Off (action)'),
			'LIGHT_SLIDER' => array('name' => 'Lumière Slider (action)'),
			'LIGHT_COLOR' => array('name' => 'Lumière Couleur (info)'),
			'LIGHT_SET_COLOR' => array('name' => 'Lumière Couleur (action)'),
			'ENERGY_STATE' => array('name' => 'Prise Etat (info)'),
			'ENERGY_ON' => array('name' => 'Prise Bouton On (action)'),
			'ENERGY_OFF' => array('name' => 'Prise Bouton Off (action)'),
			'ENERGY_SLIDER' => array('name' => 'Prise Slider (action)'),
			'FLAP_STATE' => array('name' => 'Volet Etat (info)'),
			'FLAP_UP' => array('name' => 'Volet Bouton Monter (action)'),
			'FLAP_DOWN' => array('name' => 'Volet Bouton Descendre (action)'),
			'FLAP_STOP' => array('name' => 'Volet Bouton Stop (action)'),
			'FLAP_SLIDER' => array('name' => 'Volet Bouton Slider (action)'),
			'FLAP_BSO_STATE' => array('name' => 'Volet BSO Etat (info)'),
			'FLAP_BSO_UP' => array('name' => 'Volet BSO Bouton Up (action)'),
			'FLAP_BSO_DOWN' => array('name' => 'Volet BSO Bouton Down (action)'),
			'HEATING_ON' => array('name' => 'Chauffage fil pilote Bouton ON (action)'),
			'HEATING_OFF' => array('name' => 'Chauffage fil pilote Bouton OFF (action)'),
			'HEATING_STATE' => array('name' => 'Chauffage fil pilote Etat (info)'),
			'HEATING_OTHER' => array('name' => 'Chauffage fil pilote Bouton (action)'),
			'LOCK_STATE' => array('name' => 'Serrure Etat (info)'),
			'LOCK_OPEN' => array('name' => 'Serrure Bouton Ouvrir (action)'),
			'LOCK_CLOSE' => array('name' => 'Serrure Bouton Fermer (action)'),
			'SIREN_STATE' => array('name' => 'Sirène Etat (info)'),
			'SIREN_OFF' => array('name' => 'Sirène Bouton Off (action)'),
			'SIREN_ON' => array('name' => 'Sirène Bouton On (info)'),
			'THERMOSTAT_STATE' => array('name' => 'Thermostat Etat (info)'),
			'THERMOSTAT_TEMPERATURE' => array('name' => 'Thermostat Température ambiante (info)'),
			'THERMOSTAT_SET_SETPOINT' => array('name' => 'Thermostat consigne (action)'),
			'THERMOSTAT_SETPOINT' => array('name' => 'Thermostat consigne (info)'),
			'THERMOSTAT_SET_MODE' => array('name' => 'Thermostat Mode (action)'),
			'THERMOSTAT_MODE' => array('name' => 'Thermostat Mode (info)'),
			'THERMOSTAT_SET_LOCK' => array('name' => 'Thermostat Verrouillage (action)'),
			'THERMOSTAT_LOCK' => array('name' => 'Thermostat Verrouillage (info)'),
			'MODE_STATE' => array('name' => 'Mode (info)'),
			'MODE_SET_STATE' => array('name' => 'Mode (action)'),
			'ALARM_STATE' => array('name' => 'Alarme état (info)'),
			'ALARM_MODE' => array('name' => 'Alarme mode (info)'),
			'ALARM_ENABLE_STATE' => array('name' => 'Alarme état activée (info)'),
			'ALARM_ARMED' => array('name' => 'Alarme armée (action)'),
			'ALARM_RELEASED' => array('name' => 'Alarme libérée (action)'),
			'ALARM_SET_MODE' => array('name' => 'Alarme Mode (action)'),
			'POWER' => array('name' => 'Puissance Electrique (info)'),
			'CONSUMPTION' => array('name' => 'Consommation Electrique (info)'),
			'TEMPERATURE' => array('name' => 'Température (info)'),
			'BRIGHTNESS' => array('name' => 'Luminosité (info)'),
			'PRESENCE' => array('name' => 'Présence (info)'),
			'BATTERY' => array('name' => 'Batterie (info)'),
			'SMOKE' => array('name' => 'Détection de fumée (info)'),
			'FLOOD' => array('name' => 'Inondation (info)'),
			'HUMIDITY' => array('name' => 'Humidité (info)'),
			'UV' => array('name' => 'UV (info)'),
			'OPENING' => array('name' => 'Ouvrant (info)'),
			'SABOTAGE' => array('name' => 'Sabotage (info)'),
			'CO2' => array('name' => 'CO2 (ppm) (info)'),
			'VOLTAGE' => array('name' => 'Tension (info)'),
			'NOISE' => array('name' => 'Son (dB) (info)'),
			'PRESSURE' => array('name' => 'Pression (info)'),
			'RAIN_CURRENT' => array('name' => 'Pluie (mm/h) (info)'),
			'RAIN_TOTAL' => array('name' => 'Pluie (accumulation) (info)'),
			'WIND_SPEED' => array('name' => 'Vent (vitesse) (info)'),
			'WIND_DIRECTION' => array('name' => 'Vent (direction) (info)'),
			'GENERIC' => array('name' => 'Générique (info)'),
			'DONT' => array('name' => 'Ne pas tenir compte de cette commande'),
		),
		'type' => array(
			'info' => array(
				'name' => 'Info',
				'subtype' => array(
					'numeric' => array(
						'name' => 'Numérique',
						'configuration' => array(
							'minValue' => array('visible' => true),
							'maxValue' => array('visible' => true)),
						'unite' => array('visible' => true),
						'eventOnly' => array('visible' => true),
						'isHistorized' => array('visible' => true),
						'cache' => array(
							'lifetime' => array('visible' => true),
							'enable' => array('visible' => true),
						),
						'display' => array(
							'invertBinary' => array('visible' => false),
							'icon' => array('visible' => false, 'parentVisible' => false),
						),
					),
					'binary' => array(
						'name' => 'Binaire',
						'configuration' => array(
							'minValue' => array('visible' => false),
							'maxValue' => array('visible' => false)),
						'unite' => array('visible' => false),
						'eventOnly' => array('visible' => true),
						'isHistorized' => array('visible' => true),
						'cache' => array(
							'lifetime' => array('visible' => true),
							'enable' => array('visible' => true),
						),
						'display' => array(
							'invertBinary' => array('visible' => true, 'parentVisible' => true),
							'icon' => array('visible' => false, 'parentVisible' => false),
						),
					),
					'string' => array(
						'name' => 'Autre',
						'configuration' => array(
							'minValue' => array('visible' => false),
							'maxValue' => array('visible' => false)),
						'unite' => array('visible' => true),
						'eventOnly' => array('visible' => true),
						'isHistorized' => array('visible' => false),
						'cache' => array(
							'lifetime' => array('visible' => true),
							'enable' => array('visible' => true),
						),
						'display' => array(
							'invertBinary' => array('visible' => false),
							'icon' => array('visible' => false, 'parentVisible' => false),
						),
					),
				),
			),
			'action' => array(
				'name' => 'Action',
				'subtype' => array(
					'other' => array(
						'name' => 'Défaut',
						'configuration' => array(
							'minValue' => array('visible' => false),
							'maxValue' => array('visible' => false)),
						'unite' => array('visible' => false),
						'eventOnly' => array('visible' => false),
						'isHistorized' => array('visible' => false),
						'cache' => array(
							'lifetime' => array('visible' => false),
							'enable' => array('visible' => false),
						),
						'display' => array(
							'invertBinary' => array('visible' => false),
							'icon' => array('visible' => true, 'parentVisible' => true),
						),
					),
					'slider' => array(
						'name' => 'Curseur',
						'configuration' => array(
							'minValue' => array('visible' => true),
							'maxValue' => array('visible' => true)),
						'unite' => array('visible' => false),
						'eventOnly' => array('visible' => false),
						'isHistorized' => array('visible' => false),
						'cache' => array(
							'lifetime' => array('visible' => false),
							'enable' => array('visible' => false),
						),
						'display' => array(
							'invertBinary' => array('visible' => false),
							'icon' => array('visible' => true, 'parentVisible' => true),
						),
					),
					'message' => array(
						'name' => 'Message',
						'configuration' => array(
							'minValue' => array('visible' => false),
							'maxValue' => array('visible' => false)),
						'unite' => array('visible' => false),
						'eventOnly' => array('visible' => false),
						'isHistorized' => array('visible' => false),
						'cache' => array(
							'lifetime' => array('visible' => false),
							'enable' => array('visible' => false),
						),
						'display' => array(
							'invertBinary' => array('visible' => false),
							'icon' => array('visible' => true, 'parentVisible' => true),
						),
					),
					'color' => array(
						'name' => 'Couleur',
						'configuration' => array(
							'minValue' => array('visible' => false),
							'maxValue' => array('visible' => false)),
						'unite' => array('visible' => false),
						'eventOnly' => array('visible' => false),
						'isHistorized' => array('visible' => false),
						'cache' => array(
							'lifetime' => array('visible' => false),
							'enable' => array('visible' => false),
						),
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
?>
