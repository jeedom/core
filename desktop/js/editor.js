
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

 $('#div_treeFolder').off('click').on('select_node.jstree', function (node, selected) {
 	if (selected.node.a_attr['data-path'] != undefined) {
 		path = selected.node.a_attr['data-path'];
 		printFileFolder(path);
 		var ref = $('#div_treeFolder').jstree(true);
 		sel = ref.get_selected()[0];
 		var nodesList = ref.get_children_dom(sel);
 		if(nodesList.length != 0){
 			return;
 		}
 		jeedom.getFileFolder({
 			type : 'folders',
 			path : path,
 			error: function (error) {
 				$('#div_alert').showAlert({message: error.message, level: 'danger'});
 			},
 			success : function(data){
 				$('#bt_createFile').attr('data-path',path);
 				var li = '';
 				for(var i in data){
 					node = ref.create_node(sel, {"type":"folder","text":data[i],state:{opened:true},a_attr:{'data-path':path+data[i]}});
 					$('li#'+node+' a').addClass('li_folder');
 				}
 			}
 		});
 	}
 });

 $("#div_treeFolder").jstree({"core" : {
 	"check_callback": true
 }});

 function printFileFolder(_path){
 	jeedom.getFileFolder({
 		type : 'files',
 		path : _path,
 		error: function (error) {
 			$('#div_alert').showAlert({message: error.message, level: 'danger'});
 		},
 		success : function(data){
 			$('#div_fileList').empty();
 			var li = '';
 			for(var i in data){
 				li += '<li class="cursor"><a class="li_file" data-path="'+_path+data[i]+'">'+data[i]+'</a></li>';
 			}
 			$('#div_fileList').append(li);
 		}
 	});
 }

 $('#div_fileList').off('click').on('click','.li_file',function(){
 	$('#bt_saveFile').attr('data-path',$(this).attr('data-path'));
 	$('#bt_deleteFile').attr('data-path',$(this).attr('data-path'));
 	jeedom.getFileContent({
 		path : $(this).attr('data-path'),
 		error: function (error) {
 			$('#div_alert').showAlert({message: error.message, level: 'danger'});
 		},
 		success : function(data){
 			$('#ta_fileContent').empty();
 			$('#ta_fileContent').value(data);
 			taAutosize();
 		}
 	});
 });

 $('#bt_saveFile').on('click',function(){
 	jeedom.setFileContent({
 		path : $(this).attr('data-path'),
 		content :$('#ta_fileContent').value(),
 		error: function (error) {
 			$('#div_alert').showAlert({message: error.message, level: 'danger'});
 		},
 		success : function(data){
 			$('#div_alert').showAlert({message: '{{Fichier enregistré avec succès}}', level: 'success'});
 		}
 	});
 })

 $('#bt_deleteFile').on('click',function(){
 	var path=$(this).attr('data-path');
 	bootbox.confirm('{{Etes vous sur de vouloir supprimer ce fichier : }} <span style="font-weight: bold ;">' +path + '</span> ?', function (result) {
 		if (result) {
 			jeedom.deleteFile({
 				path : path,
 				error: function (error) {
 					$('#div_alert').showAlert({message: error.message, level: 'danger'});
 				},
 				success : function(data){
 					$('#div_alert').showAlert({message: '{{Fichier enregistré avec succès}}', level: 'success'});
 				}
 			});
 		}
 	});
 })

 $('#bt_createFile').on('click',function(){
 	var path=$(this).attr('data-path');
 	bootbox.prompt("Nom du fichier ?", function (result) {
 		if (result !== null) {
 			jeedom.createFile({
 				path : path,
 				name :result,
 				error: function (error) {
 					$('#div_alert').showAlert({message: error.message, level: 'danger'});
 				},
 				success : function(data){
 					$('#div_alert').showAlert({message: '{{Fichier enregistré avec succès}}', level: 'success'});
 				}
 			});
 		}
 	});
 })