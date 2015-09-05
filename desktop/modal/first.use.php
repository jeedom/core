<?php
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
?>
<legend>
    Mes premiers pas dans Jeedom
    <a class='btn btn-default btn-xs pull-right' id='bt_doNotDisplayFirstUse'><i class="fa fa-eye-slash"></i> Ne plus afficher</a>
</legend>
<div id="div_alertFirstUse"></div>
<center>
    {{Bienvenue dans Jeedom, et merci d'avoir choisi cet outil pour votre habitat connecté. Voici 3 guides pour bien débuter avec Jeedom : }}
</center>
<br/><br/>

<div class="row">
    <div class="col-xs-4">
       <center>
        <a href="https://jeedom.fr/start" target="_blank">
            <i class="fa fa-picture-o" style="font-size:12em;"></i><br/>
            {{Guide de démarrage}}
        </a>
    </center>
</div>
<div class="col-xs-4">
   <center>
    <a href="https://jeedom.fr/doc/documentation/premiers-pas/fr_FR/doc-premiers-pas.html" target="_blank">
        <i class="fa fa-check-square" style="font-size:12em;"></i><br/>
        {{Documentation de démarrage}}
    </a>
</center>
</div>
<div class="col-xs-4">
 <center>
    <a href="https://jeedom.fr/doc" target="_blank">
        <i class="fa fa-book" style="font-size:12em;"></i><br/>
        {{Documentation}}
    </a>
</center>
</div>
</div>

<script>
    $('#bt_doNotDisplayFirstUse').on('click', function () {
        jeedom.config.save({
            configuration: {'jeedom::firstUse': 0,'jeedom::installKey' : ''},
            error: function (error) {
                $('#div_alertFirstUse').showAlert({message: error.message, level: 'danger'});
            },
            success: function () {
                $.ajax({// fonction permettant de faire de l'ajax
                        type: "POST", // methode de transmission des données au fichier php
                        url: "core/ajax/config.ajax.php", // url du fichier php
                        data: {
                            action: "genKeyAPI"
                        },
                        dataType: 'json',
                        error: function (request, status, error) {
                            handleAjaxError(request, status, error);
                        },
                        success: function (data) { // si l'appel a bien fonctionné
                        if (data.state != 'ok') {
                            $('#div_alert').showAlert({message: data.result, level: 'danger'});
                            return;
                        }
                        $('#in_keyAPI').value(data.result);
                    }
                });
                $('#div_alertFirstUse').showAlert({message: '{{Sauvegarde réussie}}', level: 'success'});
            }
        });
});
</script>
