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
<div id="md_historyCalcul" data-modalType="md_historyCalcul">
	<span class="label label-info" id="span_lastUpdateCheck"></span>
	<div class="input-group pull-right" style="display:inline-flex">
		<a class='btn btn-success roundedLeft' id="bt_addCalculHistory"><i class="fas fa-plus"></i> {{Ajouter}}</a>
		<a class='btn btn-success roundedRight' id="bt_saveCalculHistory"><i class="fas fa-check"></i> {{Sauvegarder}}</a>
	</span>
	</div>
	<br/><br/>
	<table class="table table-bordered" id="table_calculHisotry">
		<thead>
			<tr>
				<th style="width : 150px;">{{Nom}}</th>
				<th>{{Calcul}}</th>
				<th style="width : 150px;">{{Type}}</th>
				<th style="width : 200px;">{{Groupement}}</th>
				<th style="width : 100px;">{{Escalier}}</th>
				<th style="width : 100px;">{{Action}}</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>
<script>
(function() {// Self Isolation!
	jeedom.config.load({
		configuration: 'calculHistory',
		convertToHumanReadable : true,
		error: function(error) {
			jeedomUtils.showAlert({
				attachTo: jeeDialog.get('#md_historyCalcul', 'dialog'),
				message: error.message,
				level: 'danger'
			})
		},
		success: function(data) {
			for (var i in data) {
				if (isset(data[i].calcul) && data[i].calcul != '') {
					addCalculHistory(data[i])
				}
			}
		}
	})


	/*Events delegations
	*/
	document.getElementById('md_historyCalcul').addEventListener('click', function(event) {
	  var _target = null
	  if (_target = event.target.closest('#bt_saveCalculHistory')) {
		var calculHistory = document.querySelectorAll('#table_calculHisotry tbody tr').getJeeValues('.calculHistoryAttr')
		jeedom.config.save({
			configuration: {'calculHistory' : calculHistory},
			error: function(error) {
				jeedomUtils.showAlert({
					attachTo: jeeDialog.get('#md_historyCalcul', 'dialog'),
					message: error.message,
					level: 'danger'
				})
			},
			success: function() {
				jeedomUtils.showAlert({
					attachTo: jeeDialog.get('#md_historyCalcul', 'dialog'),
					message: '{{Sauvegarde effectuée}}',
					level: 'success'
				})
			}
		})
	    return
	  }

	  if (_target = event.target.closest('#bt_addCalculHistory')) {
	  	addCalculHistory()
	    return
	  }

	  if (_target = event.target.closest('.bt_removeCalculHistory')) {
	  	_target.closest('tr').remove()
	    return
	  }

	  if (_target = event.target.closest('.bt_findCmdCalculHistory')) {
		var tr = _target.closest('tr')
		jeedom.cmd.getSelectModal({cmd: {type: 'info',subType : 'numeric',isHistorized : 1}}, function(result) {
			tr.querySelector('.calculHistoryAttr[data-l1key="calcul"]').insertAtCursor(result.human)
		})
	    return
	  }

	  if (_target = event.target.closest('.bt_displayGraphCalculHistory')) {
	  	var tr = _target.closest('tr')
		var options = {
			graphType: tr.querySelector('.calculHistoryAttr[data-l1key=graphType]').value,
			groupingType: tr.querySelector('.calculHistoryAttr[data-l1key=groupingType]').value,
			graphStep: (tr.querySelector('.calculHistoryAttr[data-l1key=graphStep]').value == 0) ? false : true
		}
		try { jeeFrontEnd.history.addChart(tr.querySelector('.calculHistoryAttr[data-l1key=calcul]').value, 1, options) } catch(e) { }
	    return
	  }
	})

	function addCalculHistory(_calculHistory) {
		if (!isset(_calculHistory)) {
			_calculHistory = {}
		}
		var html = '<tr>'
		html += '<td>'
		html += '<input class="form-control calculHistoryAttr input-sm" data-l1key="name" placeholder="{{Nom}}" />'
		html += '</td>'
		html += '<td>'
		html += '<div class="input-group input-group-sm" style="width: 100%">'
		html += '<input class="form-control calculHistoryAttr roundedLeft" data-l1key="calcul" placeholder="{{Formule de calcul}}" />'
		html += '<span class="input-group-btn">'
		html += '<a class="btn btn-default bt_findCmdCalculHistory roundedRight" title="{{Sélectionner la commande}}"><i class="fas fa-list-alt"></i></a>'
		html += '</span>'
		html += '</div>'
		html += '</td>'
		html += '<td>'
		html += '<select class="form-control input-sm calculHistoryAttr" data-l1key="graphType" style="width : 140px;">'
		html +=  '<option value="line">{{Ligne}}</option>'
		html +=  '<option value="area">{{Aire}}</option>'
		html +=  '<option value="column">{{Barre}}</option>'
		html +=  '</select>'
		html += '</td>'
		html += '<td>'
		html += '<select class="form-control input-sm calculHistoryAttr" data-l1key="groupingType" style="width : 190px;">'
		html += '<option value="">{{Aucun groupement}}</option>'
		html += '<option value="sum::hour">{{Somme par heure}}</option>'
		html += '<option value="average::hour">{{Moyenne par heure}}</option>'
		html += '<option value="low::hour">{{Minimum par heure}}</option>'
		html += '<option value="sum::day">{{Somme par jour}}</option>'
		html += '<option value="high::hour">{{Maximum par heure}}</option>'
		html += '<option value="average::day">{{Moyenne par jour}}</option>'
		html += '<option value="low::day">{{Minimum par jour}}</option>'
		html += '<option value="high::day">{{Maximum par jour}}</option>'
		html += '<option value="sum::week">{{Somme par semaine}}</option>'
		html += '<option value="average::week">{{Moyenne par semaine}}</option>'
		html += '<option value="low::week">{{Minimum par semaine}}</option>'
		html += '<option value="high::week">{{Maximum par semaine}}</option>'
		html += '<option value="sum::month">{{Somme par mois}}</option>'
		html += '<option value="average::month">{{Moyenne par mois}}</option>'
		html += '<option value="low::month">{{Minimum par mois}}</option>'
		html += '<option value="high::month">{{Maximum par mois}}</option>'
		html += '<option value="average::year">{{Moyenne par année}}</option>'
		html += '<option value="low::year">{{Minimum par année}}</option>'
		html += '<option value="high::year">{{Maximum par année}}</option>'
		html +=  '</select>'
		html += '</td>'
		html += '<td>'
		html += '<select class="form-control input-sm calculHistoryAttr" data-l1key="graphStep" style="width : 90px;">'
		html += '<option value="0">{{Non}}</option>'
		html += '<option value="1">{{Oui}}</option>'
		html += '</select>'
		html += '</td>'
		html += '<td>'
		html += '<a class="btn btn-danger btn-sm pull-right bt_removeCalculHistory"><i class="fas fa-trash"></i></a>'
		html += '<a class="btn btn-default btn-sm pull-right bt_displayGraphCalculHistory" title="{{Afficher le graphique}}"><i class="fas fa-chart-area"></i></a>'
		html += '</td>'
		html += '</tr>'

		var table = document.getElementById('table_calculHisotry').querySelector('tbody')
		let newRow = document.createElement("tr")
	    newRow.innerHTML = html
	    newRow.setJeeValues(_calculHistory,'.calculHistoryAttr')
	    table.appendChild(newRow)
	}
})()
</script>