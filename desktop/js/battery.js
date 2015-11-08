
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

$('.bootstrapSwitch').on('switchChange.bootstrapSwitch', function (event, state) {
	if (state === false) {
		$('.'+event.currentTarget.id).hide();
		$('.batteryListContainer').packery({
			itemSelector: ".eqLogic-widget",
			columnWidth:40,
			rowHeight: 80,
			gutter : 2,
		});
	} else {
		var listclass = new Array();
		$('.bootstrapSwitch').each(function( index ) {
			if ($(this).bootstrapSwitch('state') === true){
				if (listclass.indexOf($(this)[0].id) == -1){
					listclass.push($(this)[0].id);
				}
			}
		});
		$('.eqLogic-widget').each(function( index ) {
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
	$('.globalsante').bootstrapSwitch('state', false);
});
$('.bt_globalsanteon').on('click',function(){
	$('.globalsante').bootstrapSwitch('state', true);
});
$('.bt_globalsantetoggle').on('click',function(){
	$('.globalsante').bootstrapSwitch('toggleState');
});

$('.bt_globalpileoff').on('click',function(){
	$('.globalpile').bootstrapSwitch('state', false);
});
$('.bt_globalpileon').on('click',function(){
	$('.globalpile').bootstrapSwitch('state', true);
});
$('.bt_globalpiletoggle').on('click',function(){
	$('.globalpile').bootstrapSwitch('toggleState');
});

$('.bt_globalpluginoff').on('click',function(){
	$('.globalplugin').bootstrapSwitch('state', false);
});
$('.bt_globalpluginon').on('click',function(){
	$('.globalplugin').bootstrapSwitch('state', true);
});
$('.bt_globalplugintoggle').on('click',function(){
	$('.globalplugin').bootstrapSwitch('toggleState');
});

$('.bt_globalobjetoff').on('click',function(){
	$('.globalobjet').bootstrapSwitch('state', false);
});
$('.bt_globalobjeton').on('click',function(){
	$('.globalobjet').bootstrapSwitch('state', true);
});
$('.bt_globalobjettoggle').on('click',function(){
	$('.globalobjet').bootstrapSwitch('toggleState');
});

 positionEqLogic();
 $('.batteryListContainer').packery({
	itemSelector: ".eqLogic-widget",
	columnWidth:40,
	rowHeight: 80,
	gutter : 2,
});