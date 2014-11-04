<?php
if (!isConnect()) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
?>
<table class="table table-condensed table-bordered" id="table_mod_insertEqLogicValue_valueEqLogicToMessage">
    <thead>
        <tr>
            <th style="width: 150px;">{{Object}}</th>
            <th style="width: 150px;">{{Equipement}}</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="mod_insertEqLogicValue_object">
                <select class='form-control'>
                    <option value="-1">{{Aucun}}</option>
                    <?php
                    foreach (object::all() as $object)
                        echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
                    ?>
                </select>
            </td>
            <td class="mod_insertEqLogicValue_eqLogic"></td>
        </tr>
    </tbody>
</table> 
<script>
    function mod_insertEqLogic() {
    }

    mod_insertEqLogic.options = {};
    mod_insertEqLogic.options.cmd = {};


    $("#table_mod_insertEqLogicValue_valueEqLogicToMessage").delegate("td.mod_insertEqLogicValue_object select", 'change', function() {
        mod_insertEqLogic.changeObjectCmd($('#table_mod_insertEqLogicValue_valueEqLogicToMessage td.mod_insertEqLogicValue_object select'), mod_insertEqLogic.options);
    });

    mod_insertEqLogic.setOptions = function(_options) {
        mod_insertEqLogic.options = _options;
        if (!isset(mod_insertEqLogic.options.cmd)) {
            mod_insertEqLogic.options.cmd = {};
        }
        mod_insertEqLogic.changeObjectCmd($('#table_mod_insertEqLogicValue_valueEqLogicToMessage td.mod_insertEqLogicValue_object select'), mod_insertEqLogic.options);
    }

    mod_insertEqLogic.getValue = function() {
        var object_name = $('#table_mod_insertEqLogicValue_valueEqLogicToMessage tbody tr:first .mod_insertEqLogicValue_object select option:selected').html();
        var equipement_name = $('#table_mod_insertEqLogicValue_valueEqLogicToMessage tbody tr:first .mod_insertEqLogicValue_eqLogic select option:selected').html();
        if (equipement_name == undefined) {
            return '';
        }
        return '#[' + object_name + '][' + equipement_name + ']#';
    }

    mod_insertEqLogic.getId = function() {
        return $('.mod_insertEqLogicValue_eqLogic select').value();
    }

    mod_insertEqLogic.changeObjectCmd = function(_select) {
        jeedom.object.getEqLogic({
            id: _select.value(),
            error: function(error) {
                $('#div_alert').showAlert({message: error.message, level: 'danger'});
            },
            success: function(eqLogics) {
                _select.closest('tr').find('.mod_insertEqLogicValue_eqLogic').empty();
                var selectEqLogic = '<select class="form-control">';
                for (var i in eqLogics) {
                    selectEqLogic += '<option value="' + eqLogics[i].id + '">' + eqLogics[i].name + '</option>';
                }
                selectEqLogic += '</select>';
                _select.closest('tr').find('.mod_insertEqLogicValue_eqLogic').append(selectEqLogic);
            }
        });
    }

    mod_insertEqLogic.changeObjectCmd($('#table_mod_insertEqLogicValue_valueEqLogicToMessage td.mod_insertEqLogicValue_object select'), mod_insertEqLogic.options);
</script>
