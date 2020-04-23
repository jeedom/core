<?php
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$objects = jeeObject::all();
$objectList = [];
foreach ($objects as $object) {
	$objectLists[$object->getId()] = $object->getName();
}
sendVarToJS('__objectList', $objectLists);
?>
<div id="div_alertScenarioSearch"></div>

<form class="form-horizontal">
	<div class="form-group">
		<label class="col-lg-2 control-label">{{Recherche par}}</label>
		<div class="col-lg-4">
			<select id="sel_searchByType" class="form-control">
				<option value="equipment">{{Equipement}}</option>
				<option value="command">{{Commande}}</option>
				<option value="variable">{{Variable}}</option>
				<option value="plugin">{{Plugin}}</option>
				<option value="value">{{Valeur}}</option>
			</select>
		</div>
		<div class="input-group pull-right" style="margin-bottom:5px;">
			<div class="input-group-btn col-lg-6">
				<a class="btn roundedLeft" href="index.php?v=d&p=display"><i class="fas fa-th"></i> {{Résumé Domotique}}
				</a><a class="btn btn-success roundedRight" id="bt_search"><i class="fas fa-search"></i> {{Rechercher}}</a>
			</div>
		</div>
	</div>

	<br/>
	<div id="searchByTypes" class="form-group">
		<div class="col-lg-2"></div>
		<div class="col-lg-4 searchType" data-searchType="plugin" style="display: none;">
			<select id="in_searchFor_plugin" class="form-control">
				<?php
					$plugins = plugin::listPlugin();
					foreach ($plugins as $plugin) {
					    echo '<option value="'.$plugin->getId().'">'.$plugin->getName().'</option>';
					}
				?>
			</select>
		</div>

		<div class="col-lg-4 searchType" data-searchType="variable" style="display: none;">
			<select id="in_searchFor_variable" class="form-control">
				<?php
					$variables = dataStore::byTypeLinkId('scenario');
					foreach ($variables as $var) {
					    echo '<option>'.$var->getKey().'</option>';
					}
				?>
			</select>
		</div>

		<div class="col-lg-4 searchType" data-searchType="equipment">
			<div class="input-group input-group-sm" >
			    <input id="in_searchFor_equipment" class="form-control roundedLeft" value="" />
			    <span class="input-group-btn">
				    <button type="button" class="btn btn-default cursor bt_selectEqLogic roundedRight"  tooltip="{{Rechercher un équipement}}"><i class="fas fa-cube"></i></button>
			    </span>
		    </div>
		</div>

		<div class="col-lg-4 searchType" data-searchType="command" style="display: none;">
			<div class="input-group input-group-sm" >
			    <input id="in_searchFor_command" class="form-control roundedLeft" value="" />
			    <span class="input-group-btn">
				    <button type="button" class="btn btn-default cursor bt_selectCommand"  tooltip="{{Rechercher une commande}}"><i class="fas fa-list-alt"></i></button>
			    </span>
		    </div>
		</div>

		<div class="col-lg-4 searchType" data-searchType="value" style="display: none;">
			<input id="in_searchFor_value" class="form-control" placeholder="{{Rechercher}}"/>
		</div>
	</div>
</form>

<br/>
<br/>
<div class="form-horizontal">
	<hr class="hrPrimary">
	<table id="table_ScenarioSearch" class="table table-condensed table-bordered tablesorter" style="width:100%; min-width:100%">
		<thead>
			<tr>
				<th>{{ID}}</th>
				<th>{{Scénario}}</th>
				<th data-sorter="false" data-filter="false">{{Actions}}</th>
			</tr>
		</thead>
		<tbody>

		</tbody>
	</table>
	<hr class="hrPrimary">
	<table id="table_DesignSearch" class="table table-condensed table-bordered tablesorter" style="width:100%; min-width:100%">
		<thead>
			<tr>
				<th>{{ID}}</th>
				<th>{{Design}}</th>
				<th data-sorter="false" data-filter="false">{{Actions}}</th>
			</tr>
		</thead>
		<tbody>

		</tbody>
	</table>
	<hr class="hrPrimary">
	<table id="table_ViewSearch" class="table table-condensed table-bordered tablesorter" style="width:100%; min-width:100%">
		<thead>
			<tr>
				<th>{{ID}}</th>
				<th>{{Vue}}</th>
				<th data-sorter="false" data-filter="false">{{Actions}}</th>
			</tr>
		</thead>
		<tbody>

		</tbody>
	</table>
	<hr class="hrPrimary">
</div>
<script>

var tableScSearch = $('#table_ScenarioSearch')
var tablePlanSearch = $('#table_DesignSearch')
var tableViewSearch = $('#table_ViewSearch')
initResultTables()

/* ------            Search UI            -------*/
function initResultTables() {
	initTableSorter()
	tableScSearch[0].config.widgetOptions.resizable_widths = ['60px', '', '60px']
	tableScSearch.trigger('applyWidgets')
	tableScSearch.trigger('resizableReset')
	tableScSearch.trigger('sorton', [[[1,0]]])

	tablePlanSearch[0].config.widgetOptions.resizable_widths = ['60px', '', '60px']
	tablePlanSearch.trigger('applyWidgets')
	tablePlanSearch.trigger('resizableReset')
	tablePlanSearch.trigger('sorton', [[[1,0]]])

	tableViewSearch[0].config.widgetOptions.resizable_widths = ['60px', '', '60px']
	tableViewSearch.trigger('applyWidgets')
	tableViewSearch.trigger('resizableReset')
	tableViewSearch.trigger('sorton', [[[1,0]]])
}

$('#sel_searchByType').change(function() {
	$('#searchByTypes > div.searchType').hide()
	var option = $(this).find('option:selected').val()
	$('#searchByTypes > div[data-searchType="'+option+'"').show()
})

$('.bt_selectEqLogic').on('click', function() {
	jeedom.eqLogic.getSelectModal({}, function(result) {
		$('#in_searchFor_equipment').value(result.human)
		$('#in_searchFor_equipment').attr('data-id', result.id)
	})
})

$('.bt_selectCommand').on('click', function() {
	jeedom.cmd.getSelectModal({},function (result) {
	    $('#in_searchFor_command').value(result.human)
	    $('#in_searchFor_command').attr('data-id', result.cmd.id)
	  })
})


$('#bt_search').off().on('click',function() {
	var searchType = $('#sel_searchByType').find('option:selected').val()
	var searchFor = $('#in_searchFor_'+searchType).val().toLowerCase()
	if (searchFor != '') {
		tableScSearch.find('tbody').empty()
		tablePlanSearch.find('tbody').empty()
		tableViewSearch.find('tbody').empty()
		window['searchFor_'+searchType](searchFor)
	}
})

/* ------            Searching            -------*/
function searchFor_plugin(_searchFor) {
	$('#table_ScenarioSearch tbody').empty()
	jeedom.eqLogic.byType({
		type: _searchFor,
		error: function(error) {
			$('#div_alertScenarioSearch').showAlert({message: error.message, level: 'danger'})
		},
		success: function(result) {
			for (var eq in result) {
				searchFor_equipment('', result[eq].id)
			}
		}
	})
}

function searchFor_variable(_searchFor) {
	console.log('Miss scenario trigger in result.')
	jeedom.dataStore.all({
		type: 'scenario',
		usedBy : 1,
		error: function (error) {
			$('#div_dataStoreManagementAlert').showAlert({message: error.message, level: 'danger'});
		},
		success: function (result) {
			console.log(result)
			scenarioResult = []
			for (var i in result) {
				if (result[i].key != _searchFor) continue
				for (var sc in result[i].usedBy.scenario) {
					scenarioResult.push({'humanName':result[i].usedBy.scenario[sc]['humanName'], 'id':result[i].usedBy.scenario[sc]['id']})
				}
			}
			showScenariosResult(scenarioResult)
		}
	})
}

function searchFor_equipment(_searchFor, _byId=false) {
	$('#table_ScenarioSearch tbody').empty()
	if (!_byId) {
		var eQiD = $('#in_searchFor_equipment').attr('data-id')
	} else {
		var eQiD = _byId
	}

	jeedom.eqLogic.usedBy({
		id : eQiD,
		error: function(error) {
			$('#div_alertScenarioSearch').showAlert({message: error.message, level: 'danger'})
		},
		success: function(result) {
			for (var i in result.scenario) {
				showScenariosResult({'humanName':result.scenario[i].humanName, 'id':result.scenario[i].linkId}, false)
			}
			jeedom.eqLogic.getCmd({
				id : eQiD,
				error: function(error) {
					$('#div_alertScenarioSearch').showAlert({message: error.message, level: 'danger'})
				},
				success: function(result) {
					for (var i in result) {
						jeedom.cmd.usedBy({
							id : result[i].id,
							error: function(error) {
								$('#div_alertScenarioSearch').showAlert({message: error.message, level: 'danger'})
							},
							success: function(result) {
								console.log(result)
								for (var i in result.scenario) {
									showScenariosResult({'humanName':result.scenario[i].humanName, 'id':result.scenario[i].linkId}, false)
								}
								for (var i in result.view) {
									showViewsResult({'humanName':result.view[i].name, 'id':result.view[i].id}, false)
								}
							}
						})
					}
				}
			})
		}
	})
}

function searchFor_command(_searchFor) {
	var cmdId = $('#in_searchFor_command').attr('data-id')
	jeedom.cmd.usedBy({
		id : cmdId,
		error: function(error) {
			$('#div_alertScenarioSearch').showAlert({message: error.message, level: 'danger'})
		},
		success: function(result) {
			var scenarioResult = []
			for (var i in result.scenario) {
				scenarioResult.push({'humanName':result.scenario[i].humanName, 'id':result.scenario[i].linkId})
			}
			showScenariosResult(scenarioResult)
		}
	})
}

function searchFor_value(_searchFor) {
	console.log('searchFor_value: ' + _searchFor)
	console.log('-> search scenarios expressions for string !')
}

/* ------            Search results display            -------*/
//display result in scenario table:
function showScenariosResult(_scenarios, _empty=true) {
	if (!Array.isArray(_scenarios)) _scenarios = [_scenarios]
	//$('#div_alertScenarioSearch').hide()
	if (_empty) $('#table_ScenarioSearch tbody').empty()

	for (var sc in _scenarios) {
		if (tableScSearch.find('.scenario[data-id="'+_scenarios[sc].id+'"]').length) return
		var tr = '<tr class="scenario" data-id="' + _scenarios[sc].id + '">'
		tr += '<td>'
		tr += '<span class="label label-info">'+_scenarios[sc].id+'</span>'
		tr += '</td>'

		tr += '<td>'
		tr += '<span>'+_scenarios[sc].humanName+'</span>'
		tr += '</td>'

		tr += '<td>'
		tr += '<a class="btn btn-xs btn-default tooltips bt_openLog" title="{{Voir les logs}}"><i class="far fa-file"></i></a> '
		tr += '<a class="btn btn-xs btn-success tooltips bt_openScenario" target="_blank" title="{{Aller sur la page du scénario.}}"><i class="fa fa-arrow-circle-right"></i></a>'
		tr += '</td>'

		tr += '</tr>'
		tableScSearch.find('tbody').append(tr)
		tableScSearch.trigger("update")
		initTooltips(tableScSearch)
	}
}

//display result in design table:
function showPlansResult(_plans, _empty=true) {
	if (!Array.isArray(_plans)) _plans = [_plans]
	//$('#div_alertScenarioSearch').hide()
	if (_empty) $('#table_ScenarioSearch tbody').empty()

	for (var sc in _plans) {
		if (tablePlanSearch.find('.plan[data-id="'+_plans[sc].id+'"]').length) return
		var tr = '<tr class="plan" data-id="' + _plans[sc].id + '">'
		tr += '<td>'
		tr += '<span class="label label-info">'+_plans[sc].id+'</span>'
		tr += '</td>'

		tr += '<td>'
		tr += '<span>'+_plans[sc].humanName+'</span>'
		tr += '</td>'

		tr += '<td>'
		tr += '<a class="btn btn-xs btn-success tooltips bt_openDesign" target="_blank" title="{{Aller sur le design.}}"><i class="fa fa-arrow-circle-right"></i></a>'
		tr += '</td>'

		tr += '</tr>'
		tablePlanSearch.find('tbody').append(tr)
		tablePlanSearch.trigger("update")
		initTooltips(tablePlanSearch)
	}
}

//display result in view table:
function showViewsResult(_views, _empty=true) {
	if (!Array.isArray(_views)) _views = [_views]
	//$('#div_alertScenarioSearch').hide()
	if (_empty) $('#table_ScenarioSearch tbody').empty()

	for (var sc in _views) {
		if (tableViewSearch.find('.view[data-id="'+_views[sc].id+'"]').length) return
		var tr = '<tr class="view" data-id="' + _views[sc].id + '">'
		tr += '<td>'
		tr += '<span class="label label-info">'+_views[sc].id+'</span>'
		tr += '</td>'

		tr += '<td>'
		tr += '<span>'+_views[sc].humanName+'</span>'
		tr += '</td>'

		tr += '<td>'
		tr += '<a class="btn btn-xs btn-success tooltips bt_openView" target="_blank" title="{{Aller sur la vue.}}"><i class="fa fa-arrow-circle-right"></i></a>'
		tr += '</td>'

		tr += '</tr>'
		tableViewSearch.find('tbody').append(tr)
		tableViewSearch.trigger("update")
		initTooltips(tableViewSearch)
	}
}

/* ------            Search results Tables Actions            -------*/
$('#table_ScenarioSearch').delegate('.bt_openScenario', 'click', function () {
	var tr = $(this).closest('tr')
	var searchType = $('#sel_searchByType').find('option:selected').val()
	var url = 'index.php?v=d&p=scenario&id=' + tr.attr('data-id')
	if (searchType != 'plugin') {
		var searchFor = $('#in_searchFor_'+searchType).val()
		url += '&search=' + searchFor.replace('#', '')
	}
	window.open(url).focus()
})

$('#table_DesignSearch').delegate('.bt_openDesign', 'click', function () {
	var tr = $(this).closest('tr')
	var url = 'index.php?v=d&p=design&id=' + tr.attr('data-id')
	window.open(url).focus()
})

$('#table_ViewSearch').delegate('.bt_openView', 'click', function () {
	var tr = $(this).closest('tr')
	var url = 'index.php?v=d&p=view&id=' + tr.attr('data-id')
	window.open(url).focus()
})

$('#table_ScenarioSearch').delegate('.bt_openLog', 'click', function () {
	var tr = $(this).closest('tr')
	$('#md_modal2').dialog({title: "{{Log d'exécution du scénario}}"}).load('index.php?v=d&modal=scenario.log.execution&scenario_id=' + tr.attr('data-id')).dialog('open')
})

</script>
