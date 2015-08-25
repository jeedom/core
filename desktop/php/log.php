<?php
if (!hasRight('logview', true)) {
	throw new Exception('{{401 - Accès non autorisé}}');
}

$page = init('page', 1);
$logfile = init('logfile', 'core');
$list_logfile = array();
$dir = opendir('log/');
$logExist = false;
while ($file = readdir($dir)) {
	if ($file != '.' && $file != '..' && !is_dir('log/' . $file)) {
		$list_logfile[] = $file;
		if ($logfile == $file) {
			$logExist = true;
		}
	}
}
natcasesort($list_logfile);
if ((!$logExist || $logfile == '') && count($list_logfile) > 0) {
	$logfile = $list_logfile[0];
}
if ($logfile == '') {
	throw new Exception('No log file');
}
?>
<a class="btn btn-danger pull-right" id="bt_removeAllLog"><i class="fa fa-trash-o"></i> {{Supprimer tous les logs}}</a>
<a class="btn btn-danger pull-right" id="bt_removeLog"><i class="fa fa-trash-o"></i> {{Supprimer}}</a>
<a class="btn btn-warning pull-right" id="bt_clearLog"><i class="fa fa-times"></i> {{Vider}}</a>
<a class="btn btn-success pull-right" id="bt_downloadLog"><i class="fa fa-cloud-download"></i> {{Télécharger}}</a>
<a class="btn btn-primary pull-right" id="bt_refreshLog"><i class="fa fa-refresh"></i> {{Rafraîchir}}</a>
<select id="sel_log" class="pull-left form-control" style="width: 200px;">
    <?php
foreach ($list_logfile as $file) {
	if ($file == $logfile) {
		echo '<option value="' . $file . '" selected>' . $file . '</option>';
	} else {
		echo '<option value="' . $file . '">' . $file . '</option>';
	}
}
?>
</select>
<br/><br/>
<div id="div_logDisplay" style="overflow: scroll;"><pre><?php
echo htmlspecialchars(shell_exec('cat ' . dirname(__FILE__) . '/../../log/' . $logfile), ENT_QUOTES, 'UTF-8');?></pre></div>
    <script>
        $(function() {
            $('#div_logDisplay').height($(window).height() - $('header').height() - $('footer').height() - 90);
            $('#div_logDisplay').scrollTop(999999999);
            $('#bt_downloadLog').click(function() {
                window.open('core/php/downloadFile.php?pathfile=log/' + $('#sel_log').value(), "_blank", null);
            });

            $("#sel_log").on('change', function() {
                log = $('#sel_log').value();
                $('#div_pageContainer').empty().load('index.php?v=d&p=log&logfile=' + log+'&ajax=1',function(){
                    initPage();
                });
            });

            $('#bt_refreshLog').on('click', function() {
                log = $('#sel_log').value();
                $('#div_pageContainer').empty().load('index.php?v=d&p=log&logfile=' + log+'&ajax=1',function(){
                    initPage();
                });
            });

            $("#bt_clearLog").on('click', function(event) {
            $.ajax({// fonction permettant de faire de l'ajax
                type: "POST", // methode de transmission des données au fichier php
                url: "core/ajax/log.ajax.php", // url du fichier php
                data: {
                    action: "clear",
                    logfile: $('#sel_log').value()
                },
                dataType: 'json',
                error: function(request, status, error) {
                    handleAjaxError(request, status, error);
                },
                success: function(data) { // si l'appel a bien fonctionné
                if (data.state != 'ok') {
                    $('#div_alertError').showAlert({message: data.result, level: 'danger'});
                } else {
                 $('#div_pageContainer').empty().load('index.php?v=d&p=log&ajax=1',function(){
                    initPage();
                });
             }
         }
     });
        });

            $("#bt_removeLog").on('click', function(event) {
            $.ajax({// fonction permettant de faire de l'ajax
                type: "POST", // methode de transmission des données au fichier php
                url: "core/ajax/log.ajax.php", // url du fichier php
                data: {
                    action: "remove",
                    logfile: $('#sel_log').value()
                },
                dataType: 'json',
                error: function(request, status, error) {
                    handleAjaxError(request, status, error);
                },
                success: function(data) { // si l'appel a bien fonctionné
                if (data.state != 'ok') {
                    $('#div_alertError').showAlert({message: data.result, level: 'danger'});
                } else {
                   $('#div_pageContainer').empty().load('index.php?v=d&p=log&ajax=1',function(){
                    initPage();
                });
               }
           }
       });
        });

            $("#bt_removeAllLog").on('click', function(event) {
                bootbox.confirm("{{Etes-vous sur de vouloir supprimer tous les logs ?}}", function(result) {
                    if (result) {
                    $.ajax({// fonction permettant de faire de l'ajax
                        type: "POST", // methode de transmission des données au fichier php
                        url: "core/ajax/log.ajax.php", // url du fichier php
                        data: {
                            action: "removeAll",
                        },
                        dataType: 'json',
                        error: function(request, status, error) {
                            handleAjaxError(request, status, error);
                        },
                        success: function(data) { // si l'appel a bien fonctionné
                        if (data.state != 'ok') {
                            $('#div_alertError').showAlert({message: data.result, level: 'danger'});
                            return;
                        }
                        $('#div_pageContainer').empty().load('index.php?v=d&p=log&ajax=1',function(){
                            initPage();
                        });

                    }
                });
}
});
});
});
</script>