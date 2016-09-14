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
                    jeedom.dataStore.remove({
                     id: tr.attr('data-dataStore_id'),
                     error: function (error) {
                        $('#div_dataStoreManagementAlert').showAlert({message: error.message, level: 'danger'});
                    },
                    success: function (data) {
                        $('#div_dataStoreManagementAlert').showAlert({message: '{{Data store supprimé}}', level: 'success'});
                        refreshDataStoreMangementTable();
                    }
                });
                }
            });
        });

        $('#table_dataStore').delegate('.bt_saveDataStore', 'click', function() {
            var tr = $(this).closest('tr');
            jeedom.dataStore.save({
              id: tr.attr('data-dataStore_id'),
              value: tr.find('.value').value(),
              type: dataStore_type,
              key: tr.find('.key').value(),
              link_id: dataStore_link_id,
              error: function (error) {
                $('#div_dataStoreManagementAlert').showAlert({message: error.message, level: 'danger'});
            },
            success: function (data) {
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
            tr += '</td>';
            tr += '<td>';
            tr += '<a class="btn btn-success pull-right btn-sm bt_saveDataStore" style="color : white"><i class="fa fa-check"></i></a>';
            tr += '<a class="btn btn-danger pull-right btn-sm bt_removeDataStore" style="color : white"><i class="fa fa-trash-o"></i></a>';
            tr += '</td>';
            tr += '</tr>';
            $('#table_dataStore tbody').append(tr);
            $("#table_dataStore").trigger("update");
        });

        function refreshDataStoreMangementTable() {
            jeedom.dataStore.all({
               type: dataStore_type,
               usedBy : 1,
               error: function (error) {
                $('#div_dataStoreManagementAlert').showAlert({message: error.message, level: 'danger'});
            },
            success: function (data) {
               $('#table_dataStore tbody').empty();
               var tr = '';
               for (var i in data) {
                tr += '<tr data-dataStore_id="' + data[i].id + '">';
                tr += '<td>';
                tr += '<span style="display : none;">' + data[i].key + '</span><input class="form-control input-sm key" value="' + data[i].key + '" disabled />';
                tr += '</td>';
                tr += '<td>';
                tr += '<span style="display : none;">' + data[i].value + '</span><input class="form-control input-sm value" value="' + data[i].value + '" />';
                tr += '</td>';
                tr += '<td>';
                for(var j in data[i].usedBy.scenario){
                    tr += '<span class="label label-primary">'+data[i].usedBy.scenario[j]+'</span> ';
                }
                tr += '</td>';
                tr += '<td>';
                tr += '<a class="btn btn-success pull-right btn-sm bt_saveDataStore" style="color : white"><i class="fa fa-check"></i></a>';
                tr += '<a class="btn btn-danger pull-right btn-sm bt_removeDataStore" style="color : white"><i class="fa fa-trash-o"></i></a>';
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