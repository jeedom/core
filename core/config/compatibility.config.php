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

global $JEEDOM_COMPATIBILIY_CONFIG;
$JEEDOM_COMPATIBILIY_CONFIG = array(
	'miniplus' => array('wifi', 'ipfix', 'systemUpdate'),
	'smart' => array('systemUpdate'),
	'rpi' => array(),
	'docker' => array(),
	'diy' => array(),
);

global $JEEDOM_RPI_HARDWARE;
$JEEDOM_RPI_HARDWARE = array(
	'RPI 1 A' => array('0007','0008','0009'),
	'RPI 1 B' => array('0002','0003','0004','0005','0006','000d','000e','000f'),
	'RPI 1 B+' => array('0010','900032','0013'),
	'RPI 1 Compute' => array('0011','0014','900061'),
	'RPI 1 A+' => array('0012','0015','900021'),
	'RPI 2 B' => array('a01041','a21041','a22042','a02042','a01040'),
	'RPI Zero' => array('900092','900093','920092','920093'),
	'RPI Zero W' => array('9000c1'),
	'RPI Zero 2 W' => array('902120'),
	'RPI 3 A+' => array('9020e0'),
	'RPI 3 B' => array('a02082','a22082','a22083','a52082','a32082'),
	'RPI 3 B+' => array('a020d3'),
	'RPI 3 Compute' => array('a220a0','a020a0'),
	'RPI 3 Compute+' => array('a02100'),
	'RPI 4 Compute' => array('a03140','b03140','c03140','d03140'),
	'RPI 4 B' => array('a03111','b03111','b03112','b03114','b03115','c03111','c03112','c03114','c03115','d03114','d03115'),
	'RPI 5' => array('b04170', 'c04170','d04170', 'b04171', 'c04171', 'd04171', 'e04171'),
);
