<?php
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$date = array(
	'start' => init('startDate', date('Y-m-d', strtotime(config::byKey('history::defautShowPeriod') . ' ' . date('Y-m-d')))),
	'end' => init('endDate', date('Y-m-d')),
);
?>
    <div class="md_history">
        <input id="in_startDate" class="form-control input-sm in_datepicker" style="display : inline-block; width: 150px;" value="<?php echo $date['start'] ?>"/>
        <input id="in_endDate" class="form-control input-sm in_datepicker" style="display : inline-block; width: 150px;" value="<?php echo $date['end'] ?>"/>
        <a class="btn btn-success btn-sm tooltips" id='bt_validChangeDate' title="{{Attention une trop grande plage de dates peut mettre très longtemps à être calculée ou même ne pas s'afficher}}">{{Ok}}</a>
        <select class="pull-right sel_chartType form-control" data-cmd_id="#id#" style="width: 200px;display: inline-block;">
            <option value="line"> {{Ligne}} </option>
            <option value="area"> {{Aire}} </option>
            <option value="column">{{Colonne}}</option>
        </select>

        <?php
if (init('derive', 0) == 1) {
	echo '<span class="pull-right"><input type="checkbox" id="toto" class="bootstrapSwitch cb_derive" data-label-text="{{Variation}}" checked /></span>';
} else {
	echo '<span class="pull-right"><input type="checkbox" id="toto" class="bootstrapSwitch cb_derive" data-label-text="{{Variation}}" /></span>';
}
if (init('step', 0) == 1) {
	echo '<span class="pull-right"><input type="checkbox" class="bootstrapSwitch cb_step" data-label-text="{{Escalier}}" checked /></span>';
} else {
	echo '<span class="pull-right"><input type="checkbox" class="bootstrapSwitch cb_step" data-label-text="{{Escalier}}" /></span>';
}
?>
       <center><div id="div_historyChart"></div></center>
       <script>
         initCheckBox();
         $(".in_datepicker").datepicker();
         $('#ui-datepicker-div').hide();

         $('#div_historyChart').css('position', 'relative').css('width', '100%');
         delete jeedom.history.chart['div_historyChart'];
         jeedom.history.drawChart({
            cmd_id: "<?php echo init('id') ?>",
            el: 'div_historyChart',
            dateRange : 'all',
            dateStart : $('#in_startDate').value(),
            dateEnd :  $('#in_endDate').value(),
            newGraph : true,
            success: function (data) {
                if(isset(data.cmd.display)){
                    if (init(data.cmd.display.graphStep) != '') {
                        $('.cb_step').off().value(init(data.cmd.display.graphStep));
                    }
                    if (init(data.cmd.display.graphType) != '') {
                        $('.sel_chartType').off().value(init(data.cmd.display.graphType));
                    }
                    if (init(data.cmd.display.graphDerive) != '') {
                        $('.cb_derive').off().value(init(data.cmd.display.graphDerive));
                    }
                }

                $('.sel_chartType').on('change', function () {
                    jeedom.cmd.save({
                        cmd: {id: <?php echo init('id') ?>, display: {graphType: $(this).value()}},
                        error: function (error) {
                            $('#div_alert').showAlert({message: error.message, level: 'danger'});
                        },
                        success: function () {
                            $('#md_modal2').dialog({title: "{{Historique}}"});
                            $("#md_modal2").load('index.php?v=d&modal=cmd.history&id=<?php echo init('id') ?>&startDate='+$('#in_startDate').val()+'&endDate='+$('#in_endDate').val()).dialog('open');
                        }
                    });

                });
                $('.cb_derive').on('switchChange.bootstrapSwitch', function () {
                    jeedom.cmd.save({
                        cmd: {id: <?php echo init('id') ?>, display: {graphDerive: $(this).value()}},
                        error: function (error) {
                            $('#div_alert').showAlert({message: error.message, level: 'danger'});
                        },
                        success: function () {
                            $('#md_modal2').dialog({title: "{{Historique}}"});
                            $("#md_modal2").load('index.php?v=d&modal=cmd.history&id=<?php echo init('id') ?>&startDate='+$('#in_startDate').val()+'&endDate='+$('#in_endDate').val()).dialog('open');
                        }
                    });
                });
                $('.cb_step').on('switchChange.bootstrapSwitch', function () {
                    jeedom.cmd.save({
                        cmd: {id: <?php echo init('id') ?>, display: {graphStep: $(this).value()}},
                        error: function (error) {
                            $('#div_alert').showAlert({message: error.message, level: 'danger'});
                        },
                        success: function () {
                            $('#md_modal2').dialog({title: "{{Historique}}"});
                            $("#md_modal2").load('index.php?v=d&modal=cmd.history&id=<?php echo init('id') ?>&startDate='+$('#in_startDate').val()+'&endDate='+$('#in_endDate').val()).dialog('open');
                        }
                    });
                });
                $('#bt_validChangeDate').on('click',function(){
                    $('#md_modal2').dialog({title: "{{Historique}}"});
                    $("#md_modal2").load('index.php?v=d&modal=cmd.history&id=<?php echo init('id') ?>&startDate='+$('#in_startDate').val()+'&endDate='+$('#in_endDate').val()).dialog('open');
                });
            }
        });
    </script>
</div>





