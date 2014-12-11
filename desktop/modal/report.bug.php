<?php
if (!isConnect()) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
if (config::byKey('market::address') == '') {
    throw new Exception('{{Aucune addresse pour le market de renseignée}}');
}
if (config::byKey('market::apikey') == '' && config::byKey('market::username') == '') {
    throw new Exception('{{Aucune clef market de renseigner. Veuillez vous enregistrer sur le market puis renseigner la clef dans Jeedom avant d\'ouvrir un ticket}}');
}
?>
<div id='div_alertReportBug'></div>
<form class="form-horizontal" role="form" id="form_reportBug">
    <div class='alert alert-info'>
        {{Attention lors de l'envoi d'un rapport de bug, tous les logs sont automatiquement envoyés en même temps. Cependant ces logs ne seront accessibles que par vous ou l'équipe de support.}}
    </div>
    <div class='alert alert-warning'>
        {{Merci avant tout ouverture de ticket de vérifier :}}<br/>
        {{- que la question n'a pas déjà été posée sur le <a href='https://forum.jeedom.fr'>forum</a>}}<br/>
        {{- que la question ne porte pas sur un plugin beta (sauf demande d'ouverture d'un ticket du développeur)}}<br/>
        {{- que la catégorie est bien selectionnée pour que votre ticket soit traité dans les plus cours délais)}}<br/>
        {{- que la question porte sur un plugin market officiel}}<br/>
        {{- que la réponse n'est pas déja dans la <a href='http://doc.jeedom.fr'>documentation</a>}}<br/><br/>
        {{N'oubliez pas que poser la question sur le forum vous fournira généralement une réponse plus rapide que par ticket.}}
    </div>
    <div class="form-group">
        <label class="col-lg-2 control-label">{{Titre}}</label>
        <div class="col-lg-10">
            <input class="form-control input-sm ticketAttr" data-l1key="title"/>
        </div> 
    </div>
    <div class="form-group">
        <label class="col-lg-2 control-label">{{Catégorie}}</label>
        <div class="col-lg-3">
            <select class="form-control input-sm ticketAttr" data-l1key="category">
                <option>{{Générale}}</option>
                <option>{{Scénario}}</option>
                <option>{{Historique}}</option>
                <?php
                foreach (plugin::listPlugin(true) as $plugin) {
                    echo '<option>plugin::' . $plugin->getId() . '</option>';
                }
                ?>
            </select>
        </div> 
        <label class="col-lg-1 control-label">{{Type}}</label>
        <div class="col-lg-3">
            <select class="form-control input-sm ticketAttr" data-l1key="type">
                <option value='question'>{{Question}}</option>
                <option value='problem'>{{Probleme}}</option>
                <option value='incident'>{{Incident}}</option>
            </select>
        </div> 
        <label class="col-lg-1 control-label">{{Priorité}}</label>
        <div class="col-lg-2">
            <select class="form-control input-sm ticketAttr" data-l1key="priority">
                <option value='low'>{{Bas}}</option>
                <option value='normal'>{{Normale}}</option>
                <option value='high'>{{Haut}}</option>
                <option value='urgent'>{{Urgent}}</option>
            </select>
        </div> 
    </div>
    <div class="form-group">
        <label class="col-lg-2 control-label">{{Message}}</label>
        <div class="col-lg-10">
            <textarea class="form-control messageAttr input-sm" data-l1key="message" ></textarea>
            <input class="form-control ticketAttr" data-l1key="options" data-l2key="page" style="display: none;"/>
        </div> 
    </div>
    <div class="form-actions" style="height: 20px;">
        <a class="btn btn-success pull-right" id="bt_sendBugReport" style="color:white;"><i class="fa fa-check-circle"></i> {{Envoyer}}</a>
    </div>
</form>

<script>
    $('.ticketAttr[data-l1key=options][data-l2key=page]').value(location.href);

    $('#bt_sendBugReport').on('click', function () {
        var ticket = $('#form_reportBug').getValues('.ticketAttr');
        var ticket = ticket[0];
        ticket.messages = $('#form_reportBug').getValues('.messageAttr');
        $.ajax({// fonction permettant de faire de l'ajax
            type: "POST", // methode de transmission des données au fichier php
            url: "core/ajax/market.ajax.php", // url du fichier php
            data: {
                action: "sendReportBug",
                ticket: json_encode(ticket),
            },
            dataType: 'json',
            error: function (request, status, error) {
                handleAjaxError(request, status, error, $('#div_alertReportBug'));
            },
            success: function (data) { // si l'appel a bien fonctionné
                if (data.state != 'ok') {
                    $('#div_alertReportBug').showAlert({message: data.result, level: 'danger'});
                    return;
                }
                $('#bt_sendBugReport').hide();
                $('#div_alertReportBug').showAlert({message: '{{Votre ticket à bien été ouvert. Vous pouvez le suivre}} <a target="_blank" href="' + data.result.url + '">{{ici}}</a>', level: 'success'});
            }
        });
    });
</script>