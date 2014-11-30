<?php
if (!hasRight('messageview')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
include_file('3rdparty', 'jquery.tablesorter/theme.bootstrap', 'css');
include_file('3rdparty', 'jquery.tablesorter/jquery.tablesorter.min', 'js');
include_file('3rdparty', 'jquery.tablesorter/jquery.tablesorter.widgets.min', 'js');

$selectPlugin = init('plugin');
if ($selectPlugin != '') {
    $listMessage = message::byPlugin($selectPlugin);
} else {
    $listMessage = message::all();
}
?>
<a class="btn btn-danger pull-right" id="bt_clearMessage"><i class="fa fa-trash-o icon-white"></i> {{Vider}}</a>
<select id="sel_plugin" class="form-control" style="width: 200px;">
    <option value="" selected>{{Tout}}</option>
    <?php
    foreach (message::listPlugin() as $plugin) {
        if ($selectPlugin == $plugin['plugin']) {
            echo '<option value="' . $plugin['plugin'] . '" selected>' . $plugin['plugin'] . '</option>';
        } else {
            echo '<option value="' . $plugin['plugin'] . '">' . $plugin['plugin'] . '</option>';
        }
    }
    ?>
</select>

<table class="table table-condensed table-bordered tablesorter" id="table_message" style="margin-top: 5px;">
    <thead>
        <tr>
            <th data-sorter="false" data-filter="false"></th><th>{{Date et heure}}</th><th>{{Plugin}}</th><th>{{Description}}</th><th data-sorter="false" data-filter="false">{{Action}}</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($listMessage as $message) {
            echo '<tr data-message_id="' . $message->getId() . '">';
            echo '<td><center><i class="fa fa-trash-o cursor removeMessage"></i></center></td>';
            echo '<td class="datetime">' . $message->getDate() . '</td>';
            echo '<td class="plugin">' . $message->getPlugin() . '</td>';
            echo '<td class="message">' . $message->getMessage() . '</td>';
            echo '<td class="message_action">' . $message->getAction() . '</td>';
            echo '</tr>';
        }
        ?>
    </tbody>
</table>

<?php include_file('desktop', 'message', 'js'); ?>
