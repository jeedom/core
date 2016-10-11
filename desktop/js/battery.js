
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

 if((isset(userProfils.doNotAutoHideMenu) && userProfils.doNotAutoHideMenu == 1) || jQuery.support.touch){
 	$('#sd_filterList').show();
 }

 if((!isset(userProfils.doNotAutoHideMenu) || userProfils.doNotAutoHideMenu != 1) && !jQuery.support.touch){
 	$('#div_resumeBatteryList').addClass('col-lg-12').removeClass('col-md-9 col-sm-8');
 	$('#bt_displayFilter').on('mouseenter',function(){
 		var timer = setTimeout(function(){
 			$('#bt_displayFilter').find('i').hide();
 			$('#div_resumeBatteryList').addClass('col-md-9 col-sm-8').removeClass('col-lg-12');
 			$('#sd_filterList').show();
 			$('.batteryListContainer').packery({
 				itemSelector: ".eqLogic-widget",
 				columnWidth:40,
 				rowHeight: 80,
 				gutter : 2,
 			});
 		}, 100);
 		setTimeout(function(){
 			$('.batteryListContainer').packery();
 		},200);
 		$(this).data('timerMouseleave', timer)
 	}).on("mouseleave", function(){
 		clearTimeout($(this).data('timerMouseleave'));
 	});

 	$('#sd_filterList').on('mouseleave',function(){
 		var timer = setTimeout(function(){
 			$('#sd_filterList').hide();
 			$('#bt_displayFilter').find('i').show();
 			$('#div_resumeBatteryList').removeClass('col-md-9 col-sm-8').addClass('col-lg-12');
 			$('.batteryListContainer').packery({
 				itemSelector: ".eqLogic-widget",
 				columnWidth:40,
 				rowHeight: 80,
 				gutter : 2,
 			});
 		}, 300);
 		$(this).data('timerMouseleave', timer);
 	}).on("mouseenter", function(){
 		clearTimeout($(this).data('timerMouseleave'));
 	});
 }

 $('#ul_object input[type=checkbox]').on('change', function (event) {
 	if ($(this).value() == 0) {
 		$('.'+event.currentTarget.id).hide();
 		$('.batteryListContainer').packery({
 			itemSelector: ".eqLogic-widget",
 			columnWidth:40,
 			rowHeight: 80,
 			gutter : 2,
 		});
 	} else {
 		var listclass = new Array();
 		$('#ul_object input[type=checkbox]').each(function() {
 			if ($(this).value() == 1){
 				if (listclass.indexOf($(this)[0].id) == -1){
 					listclass.push($(this)[0].id);
 				}
 			}
 		});
 		$('.eqLogic-widget').each(function() {
 			var count = 0;
 			var listclasswidget = $(this)[0].id.split("__");
 			listclasswidget.forEach(function(entry) {
 				if (listclass.indexOf(entry) == -1){
 					count += 1; 
 				}
 			});
 			if (count == 0) {
 				$('#'+$(this)[0].id).show();
 			}
 		});
 		$('.batteryListContainer').packery({
 			itemSelector: ".eqLogic-widget",
 			columnWidth:40,
 			rowHeight: 80,
 			gutter : 2,
 		});
 	};
 });

 $('.bt_globalsanteoff').on('click',function(){
 	$('.globalsante').value(0);
 });
 $('.bt_globalsanteon').on('click',function(){
 	$('.globalsante').value(1);
 });
 $('.bt_globalsantetoggle').on('click',function(){
 	$('.globalsante').value(!$('.globalsante').value());
 });

 $('.bt_globalpileoff').on('click',function(){
 	$('.globalpile').value(0);
 });
 $('.bt_globalpileon').on('click',function(){
 	$('.globalpile').value(1);
 });
 $('.bt_globalpiletoggle').on('click',function(){
 	$('.globalpile').value(!$('.globalpile').value());
 });

 $('.bt_globalpluginoff').on('click',function(){
 	$('.globalplugin').value(0);
 });
 $('.bt_globalpluginon').on('click',function(){
 	$('.globalplugin').value(1);
 });
 $('.bt_globalplugintoggle').on('click',function(){
 	$('.globalplugin').value(!$('.globalplugin').value());
 });

 $('.bt_globalobjetoff').on('click',function(){
 	$('.globalobjet').value(0);
 });
 $('.bt_globalobjeton').on('click',function(){
 	$('.globalobjet').value(1);
 });
 $('.bt_globalobjettoggle').on('click',function(){
 	$('.globalobjet').value(!$('.globalobjet').value());
 });

 positionEqLogic();
 $('.batteryListContainer').packery({
 	itemSelector: ".eqLogic-widget",
 	columnWidth:40,
 	rowHeight: 80,
 	gutter : 2,
 });