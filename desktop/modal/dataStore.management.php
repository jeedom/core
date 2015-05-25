<?php
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
include_file('3rdparty', 'jquery.tablesorter/theme.bootstrap', 'css');
include_file('3rdparty', 'jquery.tablesorter/jquery.tablesorter.min', 'js');
include_file('3rdparty', 'jquery.tablesorter/jquery.tablesorter.widgets.min', 'js');
sendVarToJS('dataStore_type', init('type'));
sendVarToJS('dataStore_link_id', init('link_id', -1));
?>
<div style="display: none;" id="div_dataStoreManagementAlert"></div>
<a class="btn btn-default pull-right" id="bt_dataStoreManagementAdd" style="margin-bottom: 5px;"><i class="fa fa-plus"></i> {{Ajouter}}</a>
<table id="table_dataStore" class="tablesorter">
    <thead>
        <tr>
            <th>{{Nom}}</th>
            <th>{{Valeur}}</th>
            <th>{{Utilisée dans}}</th>
            <th data-sorter="false" data-filter="false">{{Action}}</th>
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>

<script>
    $(function() {
        initTableSorter();

        refreshDataStoreMangementTable();

        $('#table_dataStore').delegate('.bt_removeDataStore', 'click', function() {
            var tr = $(this).closest('tr');
            bootbox.confirm('Etes-vous sûr de vouloir supprimer la variable <span style="font-weight: bold ;">' + tr.find('.key').value() + '</span> ?', function(result) {
                if (result) {
                    $.ajax({
                        type: "POST",
                        url: "core/ajax/dataStore.ajax.php",
                        data: {
                            action: "remove",
                            id: tr.attr('data-dataStore_id'),
                        },
                        dataType: 'json',
                        error: function(request, status, error) {
                            handleAjaxError(request, status, error, $('#div_dataStoreManagementAlert'));
                        },
                        success: function(data) {
                            if (data.state != 'ok') {
                                $('#div_dataStoreManagementAlert').showAlert({message: data.result, level: 'danger'});
                                return;
                            }
                            $('#div_dataStoreManagementAlert').showAlert({message: '{{Data store supprimé}}', level: 'success'});
                            refreshDataStoreMangementTable();
                        }
                    });
}
});
});

$('#table_dataStore').delegate('.bt_saveDataStore', 'click', function() {
    var tr = $(this).closest('tr');
    $.ajax({
        type: "POST",
        url: "core/ajax/dataStore.ajax.php",
        data: {
            action: "save",
            id: tr.attr('data-dataStore_id'),
            value: tr.find('.value').value(),
            type: dataStore_type,
            key: tr.find('.key').value(),
            link_id: dataStore_link_id,
        },
        dataType: 'json',
        error: function(request, status, error) {
            handleAjaxError(request, status, error, $('#div_dataStoreManagementAlert'));
        },
        success: function(data) {
            if (data.state != 'ok') {
                $('#div_dataStoreManagementAlert').showAlert({message: data.result, level: 'danger'});
                return;
            }
            $('#div_dataStoreManagementAlert').showAlert({message: '{{Data store sauvegardé}}', level: 'success'});
            refreshDataStoreMangementTable();
        }
    });
});

$('#bt_dataStoreManagementAdd').on('click', function() {
    var tr = '<tr data-dataStore_id="">';
    tr += '<td>';
    tr += '<input class="form-control input-sm key" value="" />';
    tr += '</td>';
    tr += '<td>';
    tr += '<input class="form-control input-sm value" value="" />';
    tr += '</td>';
    tr += '<td>';
    tr += '<td>';
    tr += '</td>';
    tr += '<a class="btn btn-success pull-right btn-xs bt_saveDataStore" style="color : white"><i class="fa fa-check"></i> {{Enregistrer}}</a>';
    tr += '<a class="btn btn-danger pull-right btn-xs bt_removeDataStore" style="color : white"><i class="fa fa-trash-o"></i> {{Supprimer}}</a>';
    tr += '</td>';
    tr += '</tr>';
    $('#table_dataStore tbody').append(tr);
    $("#table_dataStore").trigger("update");
});

function refreshDataStoreMangementTable() {
    $.ajax({
        type: "POST",
        url: "core/ajax/dataStore.ajax.php",
        data: {
            action: "all",
            type: dataStore_type,
            usedBy : 1,
        },
        dataType: 'json',
        error: function(request, status, error) {
            handleAjaxError(request, status, error, $('#div_dataStoreManagementAlert'));
        },
        success: function(data) {
            if (data.state != 'ok') {
                $('#div_dataStoreManagementAlert').showAlert({message: data.result, level: 'danger'});
                return;
            }
            $('#table_dataStore tbody').empty();
            var tr = '';
            for (var i in data.result) {
                tr += '<tr data-dataStore_id="' + data.result[i].id + '">';
                tr += '<td>';
                tr += '<span style="display : none;">' + data.result[i].key + '</span><input class="form-control input-sm key" value="' + data.result[i].key + '" />';
                tr += '</td>';
                tr += '<td>';
                tr += '<span style="display : none;">' + data.result[i].value + '</span><input class="form-control input-sm value" value="' + data.result[i].value + '" />';
                tr += '</td>';
                tr += '<td>';
                for(var j in data.result[i].usedBy.scenario){
                    tr += '<span class="label label-primary">'+data.result[i].usedBy.scenario[j]+'</span>';
                }
                tr += '</td>';
                tr += '<td>';
                tr += '<a class="btn btn-success pull-right btn-xs bt_saveDataStore" style="color : white"><i class="fa fa-check"></i> {{Enregistrer}}</a>';
                tr += '<a class="btn btn-danger pull-right btn-xs bt_removeDataStore" style="color : white"><i class="fa fa-trash-o"></i> {{Supprimer}}</a>';
                tr += '</td>';
                tr += '</tr>';
            }
            $('#table_dataStore tbody').append(tr);
            $("#table_dataStore").trigger("update");
        }
    });
}
});
</script>