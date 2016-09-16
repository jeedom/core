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
		'displayType' => array(
			'dashboard' => array('name' => 'Dashboard'),
			'plan' => array('name' => 'Design'),
			'view' => array('name' => 'Vue'),
			'mobile' => array('name' => 'Mobile'),
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
			'devicecommunication' => array('name' => 'Objets communicants', 'icon' => 'fa-language'),
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
			'LIGHT_TOGGLE' => array('name' => 'Lumière Toggle', 'family' => 'Lumière', 'type' => 'Action'),
			'LIGHT_STATE' => array('name' => 'Lumière Etat', 'family' => 'Lumière', 'type' => 'Info'),
			'LIGHT_ON' => array('name' => 'Lumière Bouton On', 'family' => 'Lumière', 'type' => 'Action'),
			'LIGHT_OFF' => array('name' => 'Lumière Bouton Off', 'family' => 'Lumière', 'type' => 'Action'),
			'LIGHT_SLIDER' => array('name' => 'Lumière Slider', 'family' => 'Lumière', 'type' => 'Action'),
			'LIGHT_COLOR' => array('name' => 'Lumière Couleur', 'family' => 'Lumière', 'type' => 'Info'),
			'LIGHT_SET_COLOR' => array('name' => 'Lumière Couleur', 'family' => 'Lumière', 'type' => 'Action'),
			'LIGHT_MODE' => array('name' => 'Lumière Mode', 'family' => 'Lumière', 'type' => 'Action'),
			'ENERGY_STATE' => array('name' => 'Prise Etat', 'family' => 'Prise', 'type' => 'Info'),
			'ENERGY_ON' => array('name' => 'Prise Bouton On', 'family' => 'Prise', 'type' => 'Action'),
			'ENERGY_OFF' => array('name' => 'Prise Bouton Off', 'family' => 'Prise', 'type' => 'Action'),
			'ENERGY_SLIDER' => array('name' => 'Prise Slider', 'family' => 'Prise', 'type' => 'Action'),
			'FLAP_STATE' => array('name' => 'Volet Etat', 'family' => 'Volet', 'type' => 'Info'),
			'FLAP_UP' => array('name' => 'Volet Bouton Monter', 'family' => 'Volet', 'type' => 'Action'),
			'FLAP_DOWN' => array('name' => 'Volet Bouton Descendre', 'family' => 'Volet', 'type' => 'Action'),
			'FLAP_STOP' => array('name' => 'Volet Bouton Stop', 'family' => 'Volet', 'type' => 'Action'),
			'FLAP_SLIDER' => array('name' => 'Volet Bouton Slider', 'family' => 'Volet', 'type' => 'Action'),
			'FLAP_BSO_STATE' => array('name' => 'Volet BSO Etat', 'family' => 'Volet', 'type' => 'Info'),
			'FLAP_BSO_UP' => array('name' => 'Volet BSO Bouton Up', 'family' => 'Volet', 'type' => 'Action'),
			'FLAP_BSO_DOWN' => array('name' => 'Volet BSO Bouton Down', 'family' => 'Volet', 'type' => 'Action'),
			'HEATING_ON' => array('name' => 'Chauffage fil pilote Bouton ON', 'family' => 'Chauffage', 'type' => 'Action'),
			'HEATING_OFF' => array('name' => 'Chauffage fil pilote Bouton OFF', 'family' => 'Chauffage', 'type' => 'Action'),
			'HEATING_STATE' => array('name' => 'Chauffage fil pilote Etat', 'family' => 'Chauffage', 'type' => 'Info'),
			'HEATING_OTHER' => array('name' => 'Chauffage fil pilote Bouton', 'family' => 'Chauffage', 'type' => 'Action'),
			'LOCK_STATE' => array('name' => 'Serrure Etat', 'family' => 'Serrure', 'type' => 'Info'),
			'LOCK_OPEN' => array('name' => 'Serrure Bouton Ouvrir', 'family' => 'Serrure', 'type' => 'Action'),
			'LOCK_CLOSE' => array('name' => 'Serrure Bouton Fermer', 'family' => 'Serrure', 'type' => 'Action'),
			'SIREN_STATE' => array('name' => 'Sirène Etat', 'family' => 'Sirène', 'type' => 'Info'),
			'SIREN_OFF' => array('name' => 'Sirène Bouton Off', 'family' => 'Sirène', 'type' => 'Action'),
			'SIREN_ON' => array('name' => 'Sirène Bouton On', 'family' => 'Sirène', 'type' => 'Action'),
			'THERMOSTAT_STATE' => array('name' => 'Thermostat Etat (BINAIRE)', 'family' => 'Thermostat', 'type' => 'Info', 'ignore' => true),
			'THERMOSTAT_TEMPERATURE' => array('name' => 'Thermostat Température ambiante', 'family' => 'Thermostat', 'type' => 'Info', 'ignore' => true),
			'THERMOSTAT_SET_SETPOINT' => array('name' => 'Thermostat consigne', 'family' => 'Thermostat', 'type' => 'Action', 'ignore' => true),
			'THERMOSTAT_SETPOINT' => array('name' => 'Thermostat consigne', 'family' => 'Thermostat', 'type' => 'Info', 'ignore' => true),
			'THERMOSTAT_SET_MODE' => array('name' => 'Thermostat Mode', 'family' => 'Thermostat', 'type' => 'Action', 'ignore' => true),
			'THERMOSTAT_MODE' => array('name' => 'Thermostat Mode', 'family' => 'Thermostat', 'type' => 'Info', 'ignore' => true),
			'THERMOSTAT_SET_LOCK' => array('name' => 'Thermostat Verrouillage', 'family' => 'Thermostat', 'type' => 'Action', 'ignore' => true),
			'THERMOSTAT_SET_UNLOCK' => array('name' => 'Thermostat Dévéroullage', 'family' => 'Thermostat', 'type' => 'Action', 'ignore' => true),
			'THERMOSTAT_LOCK' => array('name' => 'Thermostat Verrouillage', 'family' => 'Thermostat', 'type' => 'Info', 'ignore' => true),
			'THERMOSTAT_TEMPERATURE_OUTDOOR' => array('name' => 'Thermostat Température Exterieur', 'family' => 'Thermostat', 'type' => 'Info', 'ignore' => true),
			'THERMOSTAT_STATE_NAME' => array('name' => 'Thermostat Etat (HUMAIN)', 'family' => 'Thermostat', 'type' => 'Info', 'ignore' => true),
			'CAMERA_UP' => array('name' => 'Mouvement caméra vers le haut', 'family' => 'Caméra', 'type' => 'Action'),
			'CAMERA_DOWN' => array('name' => 'Mouvement caméra vers le bas', 'family' => 'Caméra', 'type' => 'Action'),
			'CAMERA_LEFT' => array('name' => 'Mouvement caméra vers le gauche', 'family' => 'Caméra', 'type' => 'Action'),
			'CAMERA_RIGHT' => array('name' => 'Mouvement caméra vers le droite', 'family' => 'Caméra', 'type' => 'Action'),
			'CAMERA_ZOOM' => array('name' => 'Zoom caméra vers l\'avant', 'family' => 'Caméra', 'type' => 'Action'),
			'CAMERA_DEZOOM' => array('name' => 'Zoom caméra vers l\'arrière', 'family' => 'Caméra', 'type' => 'Action'),
			'CAMERA_STOP' => array('name' => 'Stop caméra', 'family' => 'Caméra', 'type' => 'Action'),
			'CAMERA_PRESET' => array('name' => 'Preset caméra', 'family' => 'Caméra', 'type' => 'Action'),
			'MODE_STATE' => array('name' => 'Mode', 'family' => 'Mode', 'type' => 'Info'),
			'MODE_SET_STATE' => array('name' => 'Mode', 'family' => 'Mode', 'type' => 'Action'),
			'ALARM_STATE' => array('name' => 'Alarme état', 'family' => 'Alarme', 'type' => 'Info', 'ignore' => true),
			'ALARM_MODE' => array('name' => 'Alarme mode', 'family' => 'Alarme', 'type' => 'Info', 'ignore' => true),
			'ALARM_ENABLE_STATE' => array('name' => 'Alarme état activée', 'family' => 'Alarme', 'type' => 'Info', 'ignore' => true),
			'ALARM_ARMED' => array('name' => 'Alarme armée', 'family' => 'Alarme', 'type' => 'Action', 'ignore' => true),
			'ALARM_RELEASED' => array('name' => 'Alarme libérée', 'family' => 'Alarme', 'type' => 'Action', 'ignore' => true),
			'ALARM_SET_MODE' => array('name' => 'Alarme Mode', 'family' => 'Alarme', 'type' => 'Action', 'ignore' => true),
			'WEATHER_TEMPERATURE' => array('name' => 'Météo Température', 'family' => 'Météo', 'type' => 'Info', 'ignore' => true),
			'WEATHER_HUMIDITY' => array('name' => 'Météo Humidité', 'family' => 'Météo', 'type' => 'Info', 'ignore' => true),
			'WEATHER_PRESSURE' => array('name' => 'Météo Pression', 'family' => 'Météo', 'type' => 'Info', 'ignore' => true),
			'WEATHER_WIND_SPEED' => array('name' => 'Météo vitesse du vent', 'family' => 'Météo', 'type' => 'Info', 'ignore' => true),
			'WEATHER_WIND_DIRECTION' => array('name' => 'Météo direction du vent', 'family' => 'Météo', 'type' => 'Info', 'ignore' => true),
			'WEATHER_SUNSET' => array('name' => 'Météo lever de soleil', 'family' => 'Météo', 'type' => 'Info', 'ignore' => true),
			'WEATHER_SUNRISE' => array('name' => 'Météo coucher de soleil', 'family' => 'Météo', 'type' => 'Info', 'ignore' => true),
			'WEATHER_TEMPERATURE_MIN' => array('name' => 'Météo Température min', 'family' => 'Météo', 'type' => 'Info', 'ignore' => true),
			'WEATHER_TEMPERATURE_MAX' => array('name' => 'Météo Température max', 'family' => 'Météo', 'type' => 'Info', 'ignore' => true),
			'WEATHER_CONDITION' => array('name' => 'Météo condition', 'family' => 'Météo', 'type' => 'Info', 'ignore' => true),
			'WEATHER_CONDITION_ID' => array('name' => 'Météo condition (id)', 'family' => 'Météo', 'type' => 'Info', 'ignore' => true),
			'WEATHER_TEMPERATURE_MIN_1' => array('name' => 'Météo Température min j+1', 'family' => 'Météo', 'type' => 'Info', 'ignore' => true),
			'WEATHER_TEMPERATURE_MAX_1' => array('name' => 'Météo Température max j+1', 'family' => 'Météo', 'type' => 'Info', 'ignore' => true),
			'WEATHER_CONDITION_1' => array('name' => 'Météo condition j+1', 'family' => 'Météo', 'type' => 'Info', 'ignore' => true),
			'WEATHER_CONDITION_ID_1' => array('name' => 'Météo condition (id) j+1', 'family' => 'Météo', 'type' => 'Info', 'ignore' => true),
			'WEATHER_TEMPERATURE_MIN_2' => array('name' => 'Météo Température min j+2', 'family' => 'Météo', 'type' => 'Info', 'ignore' => true),
			'WEATHER_TEMPERATURE_MAX_2' => array('name' => 'Météo condition j+1 max j+2', 'family' => 'Météo', 'type' => 'Info', 'ignore' => true),
			'WEATHER_CONDITION_2' => array('name' => 'Météo condition j+2', 'family' => 'Météo', 'type' => 'Info', 'ignore' => true),
			'WEATHER_CONDITION_ID_2' => array('name' => 'Météo condition (id) j+2', 'family' => 'Météo', 'type' => 'Info', 'ignore' => true),
			'WEATHER_TEMPERATURE_MIN_3' => array('name' => 'Météo Température min j+3', 'family' => 'Météo', 'type' => 'Info', 'ignore' => true),
			'WEATHER_TEMPERATURE_MAX_3' => array('name' => 'Météo Température max j+3', 'family' => 'Météo', 'type' => 'Info', 'ignore' => true),
			'WEATHER_CONDITION_3' => array('name' => 'Météo condition j+3', 'family' => 'Météo', 'type' => 'Info', 'ignore' => true),
			'WEATHER_CONDITION_ID_3' => array('name' => 'Météo condition (id) j+3', 'family' => 'Météo', 'type' => 'Info', 'ignore' => true),
			'WEATHER_TEMPERATURE_MIN_4' => array('name' => 'Météo Température min j+4', 'family' => 'Météo', 'type' => 'Info', 'ignore' => true),
			'WEATHER_TEMPERATURE_MAX_4' => array('name' => 'Météo Température max j+4', 'family' => 'Météo', 'type' => 'Info', 'ignore' => true),
			'WEATHER_CONDITION_4' => array('name' => 'Météo condition j+4', 'family' => 'Météo', 'type' => 'Info', 'ignore' => true),
			'WEATHER_CONDITION_ID_4' => array('name' => 'Météo condition (id) j+4', 'family' => 'Météo', 'type' => 'Info', 'ignore' => true),
			'POWER' => array('name' => 'Puissance Electrique', 'family' => 'Generic', 'type' => 'Info', 'ignore' => true),
			'CONSUMPTION' => array('name' => 'Consommation Electrique', 'family' => 'Generic', 'type' => 'Info', 'ignore' => true),
			'TEMPERATURE' => array('name' => 'Température', 'family' => 'Generic', 'type' => 'Info'),
			'BRIGHTNESS' => array('name' => 'Luminosité', 'family' => 'Generic', 'type' => 'Info'),
			'PRESENCE' => array('name' => 'Présence', 'family' => 'Generic', 'type' => 'Info'),
			'BATTERY' => array('name' => 'Batterie', 'family' => 'Generic', 'type' => 'Info', 'ignore' => true),
			'SMOKE' => array('name' => 'Détection de fumée', 'family' => 'Generic', 'type' => 'Info'),
			'FLOOD' => array('name' => 'Inondation', 'family' => 'Generic', 'type' => 'Info'),
			'HUMIDITY' => array('name' => 'Humidité', 'family' => 'Generic', 'type' => 'Info'),
			'UV' => array('name' => 'UV', 'family' => 'Generic', 'type' => 'Info', 'ignore' => true),
			'OPENING' => array('name' => 'Porte', 'family' => 'Generic', 'type' => 'Info'),
			'OPENING_WINDOW' => array('name' => 'Fenêtre', 'family' => 'Generic', 'type' => 'Info'),
			'SABOTAGE' => array('name' => 'Sabotage', 'family' => 'Generic', 'type' => 'Info'),
			'CO2' => array('name' => 'CO2 (ppm', 'family' => 'Generic', 'type' => 'Info', 'ignore' => true),
			'VOLTAGE' => array('name' => 'Tension', 'family' => 'Generic', 'type' => 'Info', 'ignore' => true),
			'NOISE' => array('name' => 'Son (dB)', 'family' => 'Generic', 'type' => 'Info', 'ignore' => true),
			'PRESSURE' => array('name' => 'Pression', 'family' => 'Generic', 'type' => 'Info', 'ignore' => true),
			'RAIN_CURRENT' => array('name' => 'Pluie (mm/h)', 'family' => 'Generic', 'type' => 'Info', 'ignore' => true),
			'RAIN_TOTAL' => array('name' => 'Pluie (accumulation)', 'family' => 'Generic', 'type' => 'Info', 'ignore' => true),
			'WIND_SPEED' => array('name' => 'Vent (vitesse)', 'family' => 'Generic', 'type' => 'Info', 'ignore' => true),
			'WIND_DIRECTION' => array('name' => 'Vent (direction)', 'family' => 'Generic', 'type' => 'Info', 'ignore' => true),
			'SHOCK' => array('name' => 'Choc', 'family' => 'Generic', 'type' => 'Info'),
			'GENERIC_INFO' => array('name' => ' Générique', 'family' => 'Generic', 'type' => 'Info'),
			'GENERIC_ACTION' => array('name' => ' Générique', 'family' => 'Generic', 'type' => 'Action'),
			'DONT' => array('name' => 'Ne pas tenir compte de cette commande', 'family' => 'Generic', 'type' => 'All'),
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
						'isHistorized' => array('visible' => true),
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
						'isHistorized' => array('visible' => true),
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
						'isHistorized' => array('visible' => false),
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
						'isHistorized' => array('visible' => false),
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
						'isHistorized' => array('visible' => false),
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
						'isHistorized' => array('visible' => false),
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
?>
