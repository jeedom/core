<?php
if (!isConnect()) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
?>
<div class="md_history" title="Historique">
    <center><div id="div_historyChart"></div></center>
    <select class="pull-right sel_chartType form-control" data-cmd_id="#id#" style="width: 200px;display: inline-block;">
        <option value="line"> {{Ligne}} </option>
        <option value="area"> {{Aire}} </option>
        <option value="column">{{Colonne}}</option>
    </select>

    <?php
    if (init('derive', 0) == 1) {
        echo '<span class="pull-right">Variation : <input type="checkbox" data-cmd_id="#id#" class="cb_derive" checked /></span>';
    } else {
        echo '<span class="pull-right">Variation : <input type="checkbox" data-cmd_id="#id#" class="cb_derive" /></span>';
    }
    if (init('step', 0) == 1) {
        echo '<span class="pull-right">Escalier : <input type="checkbox" data-cmd_id="#id#" class="cb_step" checked /></span>';
    } else {
        echo '<span class="pull-right">Escalier : <input type="checkbox" data-cmd_id="#id#" class="cb_step" /></span>';
    }
    ?>
    <script>

        $('#div_historyChart').css('position', 'relative').css('width', '100%');
        delete jeedom.history.chart['div_historyChart'];
        jeedom.history.drawChart({
            cmd_id: "<?php echo init('id') ?>",
            el: 'div_historyChart',
            daterange: 'all',
            newGraph : true,
            success: function (data) {
                if (init(data.cmd.display.graphStep) != '') {
                    $('.cb_step[data-cmd_id=#id#]').off().value(init(data.cmd.display.graphStep));
                }
                if (init(data.cmd.display.graphType) != '') {
                    $('.sel_chartType[data-cmd_id=#id#]').off().value(init(data.cmd.display.graphType));
                }
                if (init(data.cmd.display.graphDerive) != '') {
                    $('.cb_derive[data-cmd_id=#id#]').off().value(init(data.cmd.display.graphDerive));
                }

                $('.sel_chartType[data-cmd_id=#id#]').on('change', function () {
                    jeedom.cmd.save({
                        cmd: {id: <?php echo init('id') ?>, display: {graphType: $(this).value()}},
                        error: function (error) {
                            $('#div_alert').showAlert({message: error.message, level: 'danger'});
                        },
                        success: function () {
                            $('#md_modal').dialog({title: "{{Historique}}"});
                            $("#md_modal").load('index.php?v=d&modal=cmd.history&id=<?php echo init('id') ?>').dialog('open');
                        }
                    });

                });
                $('.cb_derive[data-cmd_id=#id#]').on('change', function () {
                    jeedom.cmd.save({
                        cmd: {id: <?php echo init('id') ?>, display: {graphDerive: $(this).value()}},
                        error: function (error) {
                            $('#div_alert').showAlert({message: error.message, level: 'danger'});
                        },
                        success: function () {
                            $('#md_modal').dialog({title: "{{Historique}}"});
                            $("#md_modal").load('index.php?v=d&modal=cmd.history&id=<?php echo init('id') ?>').dialog('open');
                        }
                    });
                });
                $('.cb_step[data-cmd_id=#id#]').on('change', function () {
                    jeedom.cmd.save({
                        cmd: {id: <?php echo init('id') ?>, display: {graphStep: $(this).value()}},
                        error: function (error) {
                            $('#div_alert').showAlert({message: error.message, level: 'danger'});
                        },
                        success: function () {
                            $('#md_modal').dialog({title: "{{Historique}}"});
                            $("#md_modal").load('index.php?v=d&modal=cmd.history&id=<?php echo init('id') ?>').dialog('open');
                        }
                    });
                });
            }
        });
    </script>
</div>





