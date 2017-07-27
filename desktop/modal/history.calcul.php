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

if (!isConnect()) {
	throw new Exception('401 Unauthorized');
}
?>
<div id="div_alertHistoryCalcul"></div>
<a class='btn btn-success pull-right' id="bt_saveCalculHistory"><i class="fa fa-check"></i> {{Sauvegarder}}</a>
<a class='btn btn-success pull-right' id="bt_addCalculHistory"><i class="fa fa-plus"></i> {{Ajouter}}</a>
<br/><br/>
<table class="table table-bordered" id="table_calculHisotry">
	<thead>
		<tr>
			<th>{{Calcul}}</th>
			<th>{{Action}}</th>
		</tr>
	</thead>
	<tbody>


	</tbody>
</table>

<script>
	jeedom.config.load({
		configuration: 'calculHistory',
		convertToHumanReadable : true,
		error: function (error) {
			$('#div_alertHistoryCalcul').showAlert({message: error.message, level: 'danger'});
		},
		success: function (data) {
			for(var i in data){
				if(isset(data[i].calcul) && data[i].calcul != ''){
					addCalculHistory(data[i]);
				}
			}
		}
	});

	$('#bt_saveCalculHistory').on('click',function(){
		var calculHistory = $('#table_calculHisotry tbody tr').getValues('.calculHistoryAttr');
		jeedom.config.save({
			configuration: {'calculHistory' : calculHistory},
			error: function (error) {
				$('#div_alertHistoryCalcul').showAlert({message: error.message, level: 'danger'});
			},
			success: function () {
				$('#div_alertHistoryCalcul').showAlert({message: '{{Sauvegarde effectuée}}', level: 'success'});
			}
		});
	});

	$('#bt_addCalculHistory').on('click',function(){
		addCalculHistory();
	});

	$('#table_calculHisotry tbody').on('click','.bt_removeCalculHistory',function(){
		$(this).closest('tr').remove();
	});

	$('#table_calculHisotry tbody').on('click','.bt_findCmdCalculHistory',function(){
		var tr = $(this).closest('tr');
		jeedom.cmd.getSelectModal({cmd: {type: 'info',subType : 'numeric',isHistorized : 1}}, function(result) {
			tr.find('.calculHistoryAttr[data-l1key=calcul]').atCaret('insert', result.human);
		});
	});

	$('#table_calculHisotry tbody').on('click','.bt_displayGraphCalculHistory',function(){
		 addChart($(this).closest('tr').find('.calculHistoryAttr[data-l1key=calcul]').value(), 1)
	});

	function addCalculHistory(_calculHistory){
		if(!isset(_calculHistory)){
			_calculHistory = {}
		}
		var html = '<tr>';
		html += '<td>';
		html += '<div class="input-group input-group-sm" style="width: 100%">';
		html += '<input class="form-control calculHistoryAttr" data-l1key="calcul" placeholder="{{Formule de calcul}}" />';
		html += '<span class="input-group-btn">';
		html += '<a class="btn btn-default bt_findCmdCalculHistory" title="{{Sélectionner la commande}}"><i class="fa fa-list-alt"></i></a>';
		html += '</span>';
		html += '</div>';
		html += '</td>';
		html += '<td>';
		html += '<a class="btn btn-danger btn-sm pull-right bt_removeCalculHistory"><i class="fa fa-trash"></i></a>';
		html += '<a class="btn btn-default btn-sm pull-right bt_displayGraphCalculHistory" title="{{Afficher le graphique}}"><i class="fa fa-bar-chart"></i></a>';
		html += '</td>';
		html += '</tr>';
		$('#table_calculHisotry tbody').append(html);
		$('#table_calculHisotry tbody tr:last').setValues(_calculHistory,'.calculHistoryAttr');
	}

</script>