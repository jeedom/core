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

class conversionUnits {

    /** @const */
    
    public static $conversions = array(
        '*W'=> array(1000,'W','KW','MW'),
        '*io'=> array(1024,'io','Kio','Mio','Gio','Tio'),
        '*o'=> array(1000,'o','Ko','Mo','Go','To'),
        '*Hz'=> array(1000,'Hz','KHz','MHz','GHz'),
        '*l'=> array(1000,'l','m<sup>3</sup>'),
        '*s'=> array(60,'s','min','h')
        );
}
