
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

 positionEqLogic();
 $('.alertListContainer .jeedomAlreadyPosition').removeClass('jeedomAlreadyPosition');
 $('.batteryListContainer, .alertListContainer').packery({
 	itemSelector: ".eqLogic-widget",
 	columnWidth:40,
 	rowHeight: 80,
 	gutter : 2,
 });
 
 $('.alerts, .batteries').on('click',function(){
 	setTimeout(function(){ 
 		positionEqLogic();
 		$('.batteryListContainer, .alertListContainer').packery({
 			itemSelector: ".eqLogic-widget",
 			columnWidth:40,
 			rowHeight: 80,
 			gutter : 2,
 		});
 	}, 10);
 });




 $('.cmdAction[data-action=configure]').on('click', function () {
 	$('#md_modal').dialog({title: "{{Configuration commande}}"});
 	$('#md_modal').load('index.php?v=d&modal=cmd.configure&cmd_id=' + $(this).attr('data-cmd_id')).dialog('open');
 });