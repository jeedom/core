
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


$('#div_tree').on('select_node.jstree', function (node, selected) {
    if (selected.node.a_attr.class == 'infoObject') {
        $('#div_displayInfo').empty().load('index.php?v=d&modal=object.configure&object_id=' + selected.node.a_attr['data-object_id']);
    }
    if (selected.node.a_attr.class == 'infoEqLogic') {
        $('#div_displayInfo').empty().load('index.php?v=d&modal=eqLogic.configure&eqLogic_id=' + selected.node.a_attr['data-eqlogic_id']);
    }
    if (selected.node.a_attr.class == 'infoCmd') {
        $('#div_displayInfo').empty().load('index.php?v=d&modal=cmd.configure&cmd_id=' + selected.node.a_attr['data-cmd_id']);
    }
});

$("#div_tree").jstree({
    "plugins": ["search"]
});
$('#in_treeSearch').keyup(function () {
    $('#div_tree').jstree(true).search($('#in_treeSearch').val());
});

$("#bt_displayConfig").on('click', function (event) {
    $.hideAlert();
    saveConfiguration($('#display_configuration'));
});

$('.bt_resetColor').on('click', function () {
    var el = $(this);
    jeedom.getConfiguration({
        key: $(this).attr('data-l1key'),
        default: 1,
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
            $('.configKey[data-l1key="' + el.attr('data-l1key') + '"]').value(data);
        }
    });
});


function saveConfiguration(_el) {
    jeedom.config.save({
        configuration: _el.getValues('.configKey')[0],
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function () {
            $('#div_alert').showAlert({message: '{{Sauvegarde effectuée}}', level: 'success'});
            modifyWithoutSave = false;
        }
    });
}