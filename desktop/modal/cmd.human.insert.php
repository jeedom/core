<?php
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
?>
<table class="table table-condensed table-bordered" id="table_mod_insertCmdValue_valueEqLogicToMessage">
    <thead>
        <tr>
            <th style="width: 150px;">{{Objet}}</th>
            <th style="width: 150px;">{{Equipement}}</th>
            <th style="width: 150px;">{{Commande}}</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="mod_insertCmdValue_jeeObject">
                <select class='form-control'>
                    <option value="-1">{{Aucun}}</option>
                    <?php
foreach (jeeObject::all() as $jeeObject) {
	echo '<option value="' . $jeeObject->getId() . '">' . $jeeObject->getName() . '</option>';
}

?>
                </select>
            </td>
            <td class="mod_insertCmdValue_eqLogic"></td>
            <td class="mod_insertCmdValue_cmd"></td>
        </tr>
    </tbody>
</table>
<script>
    function mod_insertCmd() {
    }

    mod_insertCmd.options = {};
    mod_insertCmd.options.cmd = {};
    mod_insertCmd.options.eqLogic = {};
    mod_insertCmd.options.jeeObject = {};


    $("#table_mod_insertCmdValue_valueEqLogicToMessage").delegate("td.mod_insertCmdValue_jeeObject select", 'change', function () {
        mod_insertCmd.changeJeeObjectCmd($('#table_mod_insertCmdValue_valueEqLogicToMessage td.mod_insertCmdValue_jeeObject select'), mod_insertCmd.options);
    });

    mod_insertCmd.setOptions = function (_options) {
        mod_insertCmd.options = _options;
        if (!isset(mod_insertCmd.options.cmd)) {
            mod_insertCmd.options.cmd = {};
        }
        if (!isset(mod_insertCmd.options.eqLogic)) {
            mod_insertCmd.options.eqLogic = {};
        }
        if (!isset(mod_insertCmd.options.jeeObject)) {
            mod_insertCmd.options.jeeObject = {};
        }
        if (isset(mod_insertCmd.options.jeeObject.id)) {
            $('#table_mod_insertCmdValue_valueEqLogicToMessage td.mod_insertCmdValue_jeeObject select').value(mod_insertCmd.options.jeeObject.id);
        }
        mod_insertCmd.changeJeeObjectCmd($('#table_mod_insertCmdValue_valueEqLogicToMessage td.mod_insertCmdValue_jeeObject select'), mod_insertCmd.options);
    }

    mod_insertCmd.getValue = function () {
        var jeeObject_name = $('#table_mod_insertCmdValue_valueEqLogicToMessage tbody tr:first .mod_insertCmdValue_jeeObject select option:selected').html();
        var equipement_name = $('#table_mod_insertCmdValue_valueEqLogicToMessage tbody tr:first .mod_insertCmdValue_eqLogic select option:selected').html();
        var cmd_name = $('#table_mod_insertCmdValue_valueEqLogicToMessage tbody tr:first .mod_insertCmdValue_cmd select option:selected').html();
        if (cmd_name == undefined) {
            return '';
        }
        return '#[' + jeeObject_name + '][' + equipement_name + '][' + cmd_name + ']#';
    }

    mod_insertCmd.getCmdId = function () {
        return $('#table_mod_insertCmdValue_valueEqLogicToMessage tbody tr:first .mod_insertCmdValue_cmd select').value();
    }

    mod_insertCmd.getType = function () {
        return $('#table_mod_insertCmdValue_valueEqLogicToMessage tbody tr:first .mod_insertCmdValue_cmd select option:selected').attr('data-type');
    }

     mod_insertCmd.getSubType = function () {
        return $('#table_mod_insertCmdValue_valueEqLogicToMessage tbody tr:first .mod_insertCmdValue_cmd select option:selected').attr('data-subType');
    }

    mod_insertCmd.changeJeeObjectCmd = function (_select) {
        jeedom.jeeObject.getEqLogic({
            id: _select.value(),
            orderByName : true,
            error: function (error) {
                $('#div_alert').showAlert({message: error.message, level: 'danger'});
            },
            success: function (eqLogics) {
                _select.closest('tr').find('.mod_insertCmdValue_eqLogic').empty();
                var selectEqLogic = '<select class="form-control">';
                for (var i in eqLogics) {
                    if (init(mod_insertCmd.options.eqLogic.eqType_name, 'all') == 'all' || eqLogics[i].eqType_name == mod_insertCmd.options.eqLogic.eqType_name){
                        selectEqLogic += '<option value="' + eqLogics[i].id + '">' + eqLogics[i].name + '</option>';
                    }
                }
                selectEqLogic += '</select>';
                _select.closest('tr').find('.mod_insertCmdValue_eqLogic').append(selectEqLogic);
                _select.closest('tr').find('.mod_insertCmdValue_eqLogic select').change(function () {
                    mod_insertCmd.changeEqLogic($(this), mod_insertCmd.options);
                });
                if (isset(mod_insertCmd.options.jeeObject.id)) {
                    _select.closest('tr').find('.mod_insertCmdValue_eqLogic select').value(mod_insertCmd.options.eqLogic.id);
                }
                mod_insertCmd.changeEqLogic(_select.closest('tr').find('.mod_insertCmdValue_eqLogic select'), mod_insertCmd.options);
            }
        });

}

mod_insertCmd.changeEqLogic = function (_select) {
    jeedom.eqLogic.builSelectCmd({
        id: _select.value(),
        filter: mod_insertCmd.options.cmd,
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (html) {
            _select.closest('tr').find('.mod_insertCmdValue_cmd').empty();
            var selectCmd = '<select class="form-control">';
            selectCmd += html;
            selectCmd += '</select>';
            _select.closest('tr').find('.mod_insertCmdValue_cmd').append(selectCmd);
        }
    });
}

mod_insertCmd.changeJeeObjectCmd($('#table_mod_insertCmdValue_valueEqLogicToMessage td.mod_insertCmdValue_jeeObject select'), mod_insertCmd.options);
</script>
