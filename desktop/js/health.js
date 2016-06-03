
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

 $('.bt_configurationPlugin').on('click',function(){
 	$('#md_modal').dialog({title: "{{Configuration du plugin}}"});
 	$("#md_modal").load('index.php?v=d&p=plugin&ajax=1&id='+$(this).attr('data-pluginid')).dialog('open');
 });
 
 $('.bt_healthSpecific').on('click', function () {
 	$('#md_modal').dialog({title: "{{Santé}} " + $(this).attr('data-pluginname')});
 	$('#md_modal').load('index.php?v=d&plugin='+$(this).attr('data-pluginid')+'&modal=health').dialog('open');
 });

 $('#bt_benchmarkJeedom').on('click',function(){
 	$('#md_modal').dialog({title: "{{Jeedom benchmark}}"});
 	$("#md_modal").load('index.php?v=d&modal=jeedom.benchmark').dialog('open');
 });