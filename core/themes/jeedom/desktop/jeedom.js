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

 $(function(){
 	$('body').on('jeedom_page_load',function(){
 		$('.backgroundforJeedom').css('background-image','');
 		$('.backgroundforJeedom').css('background-position','');
 		$('.backgroundforJeedom').css('background-repeat','no-repeat');
 		$('.backgroundforJeedom').css('background-size','cover');
 		if(typeof jeedomBackgroundImg !== 'undefined' && jeedomBackgroundImg != null){
 			$('.backgroundforJeedom').css('background-image','url("'+jeedomBackgroundImg+'")');
 			if(typeof jeedomBackgroundPosition !== 'undefined' && jeedomBackgroundPosition != null){
 				$('.backgroundforJeedom').css('background-position',''+jeedomBackgroundPosition+'');
 			}
 			if(typeof jeedomBackgroundRepeat !== 'undefined' && jeedomBackgroundRepeat != null){
 				$('.backgroundforJeedom').css('background-repeat',''+jeedomBackgroundRepeat+'');
 			}
 		}else{
 			switch (getUrlVars('p')){
 				case 'scenario':
 				$('.backgroundforJeedom').css('background-image','url("core/themes/jeedom/desktop/background/scenario.png")');
 				$('.backgroundforJeedom').css('background-position','bottom right');
 				$('.backgroundforJeedom').css('background-repeat','no-repeat');
 				$('.backgroundforJeedom').css('background-size','auto');
 				break;
 				case 'interact':
 				$('.backgroundforJeedom').css('background-image','url("core/themes/jeedom/desktop/background/interact.png")');
 				$('.backgroundforJeedom').css('background-position','bottom right');
 				$('.backgroundforJeedom').css('background-repeat','no-repeat');
 				$('.backgroundforJeedom').css('background-size','auto');
 				break;
 				case 'object':
 				$('.backgroundforJeedom').css('background-image','url("core/themes/jeedom/desktop/background/object.png")');
 				$('.backgroundforJeedom').css('background-position','bottom right');
 				$('.backgroundforJeedom').css('background-repeat','no-repeat');
 				$('.backgroundforJeedom').css('background-size','auto');
 				break;
 				case 'display':
 				$('.backgroundforJeedom').css('background-image','url("core/themes/jeedom/desktop/background/display.png")');
 				$('.backgroundforJeedom').css('background-position','bottom right');
 				$('.backgroundforJeedom').css('background-repeat','no-repeat');
 				$('.backgroundforJeedom').css('background-size','auto');
 				break;
 			}
 		}
 	});
 });