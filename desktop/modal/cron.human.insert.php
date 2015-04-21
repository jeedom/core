<?php
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
?>
<div class="form-group mode schedule">
    <label class="col-xs-3 control-label" >{{A exécuter}}</label>
    <div class="col-xs-9">
        <select class="form-control" id="mod_cron_sel_scheduleMode">
            <option value="once">une seule fois</option>
            <option value="repete">répététivement</option>
        </select>
    </div>
</div>
<br/><br/>
<div class="form-group mode schedule" id="mod_cron_div_scheduleConfig"></div>

<script>
    $('#mod_cron_sel_scheduleMode').on('change', function () {
        $('#mod_cron_div_scheduleConfig').empty();
        if ($(this).value() == 'once') {
            var html = '<label class="col-xs-3 control-label" >{{En date du}}</label>';
            html += '<div class="col-xs-9">';
            html += '<input class="form-control" id="mod_cron_in_dateScenarioTrigger">';
            html += '</div>';
            html += '<span id="mod_cron_span_cronResult" style="display: none;"></span>';
            $('#mod_cron_div_scheduleConfig').append(html);
            $('#mod_cron_in_dateScenarioTrigger').datetimepicker({lang: 'fr',
                i18n: {
                    fr: {
                        months: [
                        'Janvier', 'Février', 'Mars', 'Avril',
                        'Mai', 'Juin', 'Juillet', 'Aout',
                        'Septembre', 'Octobre', 'Novembre', 'Décembre',
                        ],
                        dayOfWeek: [
                        "Di", "Lu", "Ma", "Me",
                        "Je", "Ve", "Sa",
                        ]
                    }
                },
                format: 'Y-m-d H:i:00',
                step: 15
            });
            $('#mod_cron_in_dateScenarioTrigger').on('change', function () {
                if ($(this).value() != '') {
                    var date = new Date(Date.parse($(this).value().replace(/-/g, "/")));
                    var minute = (date.getMinutes() < 10 ? '0' : '') + date.getMinutes();
                    var hour = (date.getHours() < 10 ? '0' : '') + date.getHours();
                    var strdate = (date.getDate() < 10 ? '0' : '') + date.getDate();
                    var month = ((date.getMonth() + 1) < 10 ? '0' : '') + (date.getMonth() + 1);
                    var cron = minute + ' ' + hour + ' ' + strdate + ' ' + month + ' ' + date.getDay() + ' ' + date.getFullYear()
                    $('#mod_cron_span_cronResult').value(cron);
                }
            });
        } else {
            var html = '<div class="col-xs-3"></div>';
            html += '<div id="mod_cron_div_cronGenerator"></div>';
            html += '<span id="mod_cron_span_cronResult" style="display: none;">* * * * *</span>';
            $('#mod_cron_div_scheduleConfig').append(html);
            $('#mod_cron_div_cronGenerator').empty().cron({
                initial: '* * * * *',
                onChange: function () {
                    $('#mod_cron_span_cronResult').text($(this).cron("value"));
                }
            });
        }
    });
$('#mod_cron_sel_scheduleMode').trigger('change');

function mod_insertCron() {
}



mod_insertCron.getValue = function () {
    return $('#mod_cron_span_cronResult').text();
}
</script>
