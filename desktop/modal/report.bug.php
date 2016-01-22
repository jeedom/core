<?php
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
if (config::byKey('market::address') == '') {
	throw new Exception('{{Aucune adresse pour le market n\'est renseignée}}');
}
if (config::byKey('market::apikey') == '' && config::byKey('market::username') == '') {
	throw new Exception('{{Aucune clé pour le market n\'est renseignée. Veuillez vous enregistrer sur le market puis renseigner la clé dans Jeedom avant d\'ouvrir un ticket}}');
}
?>
<div id='div_alertReportBug'></div>
<form class="form-horizontal" role="form" id="form_reportBug">
    <div class='alert alert-info'>
        {{Vous avez une idée ? Vous voulez la partager ? N'hésitez pas à nous la décrire <a href="https://jeedom.com/feedback" target="_blank">ici</a>}}
    </div>
    <div class='alert alert-warning'>
        {{Merci de vérifier avant toute ouverture de ticket :}}<br/>
        {{- que la question n'a pas déjà été posée sur le <a href='https://jeedom.com/forum'>forum</a>}}<br/>
        {{- que la catégorie est bien sélectionnée pour que votre ticket soit traité dans les plus courts délais}}<br/>
        {{- que la réponse n'est pas déjà dans la <a href='https://jeedom.com/doc'>documentation</a>}}<br/>
        {{- que la réponse n'est pas déjà dans la <a href='https://jeedom.com/faq'>faq</a>}}<br/><br/>
        {{N'oubliez pas que poser la question sur le forum vous fournira généralement une réponse plus rapide que par ticket.}}
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">{{Titre}}</label>
        <div class="col-sm-7">
            <input class="form-control ticketAttr" data-l1key="title"/>
        </div>
        <div class="col-sm-2">
            <a class="btn btn-default" id="bt_searchOnFaq"><i class="fa fa-search"></i> {{Chercher}}</a>
        </div>
    </div>
    <div id="div_reportModalSendAction" style="display:none;">
        <div class="form-group">
            <label class="col-sm-2 control-label">{{Type}}</label>
            <div class="col-sm-2">
                <select class="form-control ticketAttr" data-l1key="type">
                    <option value='question'>{{Question}}</option>
                    <option value='incident'>{{Incident}}</option>
                </select>
            </div>
            <label class="col-sm-2 control-label">{{Catégorie}}</label>
            <div class="col-sm-4">
                <select class="form-control ticketAttr" data-l1key="category">
                    <option>{{Général}}</option>
                    <option>{{Scénario}}</option>
                    <option>{{Historique}}</option>
                    <?php
foreach (plugin::listPlugin(true) as $plugin) {
	echo '<option value="plugin::' . $plugin->getId() . '">Plugin ' . $plugin->getName() . '</option>';
}
?>
               </select>
           </div>
       </div>
       <div class="form-group">
        <label class="col-sm-2 control-label">{{Message}}</label>
        <div class="col-sm-9">
            <textarea class="form-control messageAttr input-sm" data-l1key="message" rows="8" ></textarea>
            <input class="form-control ticketAttr" data-l1key="options" data-l2key="page" style="display: none;"/>
        </div>
    </div>
    <div class="form-actions" style="height: 20px;">
        <a class="btn btn-success pull-right" id="bt_sendBugReport" style="color:white;"><i class="fa fa-check-circle"></i> {{Envoyer}}</a>
    </div>
</div>
</form>

<script>
    initCheckBox();
    $('.ticketAttr[data-l1key=options][data-l2key=page]').value(location.href);

    $('#bt_sendBugReport').on('click', function () {
        var ticket = $('#form_reportBug').getValues('.ticketAttr');
        var ticket = ticket[0];
        ticket.messages = $('#form_reportBug').getValues('.messageAttr');
        $.ajax({// fonction permettant de faire de l'ajax
            type: "POST", // méthode de transmission des données au fichier php
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
            $('#div_alertReportBug').showAlert({message: '{{Votre ticket a bien été ouvert. Un mail va vous être envoyé.}}', level: 'success'});
        }
    });
    });

$('#bt_searchOnFaq').on('click',function(){
    $('#div_reportModalSendAction').show();
    window.open('https://jeedom.com/faq/?submit=&action=search&search='+encodeURIComponent($('#form_reportBug .ticketAttr[data-l1key=title]').value()), '_blank');
});
</script>
