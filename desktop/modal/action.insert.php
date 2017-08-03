<?php
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
?>
<center>
    <select id="mod_actionValue_sel" class="form-control">
        <option value="sleep">{{Pause}}</option>
        <option value="variable">{{Variable}}</option>
        <option value="scenario">{{Scénario}}</option>
        <option value="stop" class="scenarioOnly">{{Stop}}</option>
        <option value="wait">{{Attendre}}</option>
        <option value="gotodesign">{{Aller au design}}</option>
        <option value="log" class="scenarioOnly">{{Ajouter un log}}</option>
        <option value="message">{{Creer un message}}</option>
        <option value="equipement">{{Activer/Desactiver Masquer/Afficher un équipement}}</option>
        <option value="ask">{{Faire une demande}}</option>
        <option value="jeedom_poweroff">{{Arrêter Jeedom}}</option>
        <option value="scenario_return" class="scenarioOnly">{{Retourner un texte/une données}}</option>
        <option value="icon" class="scenarioOnly">{{Icône}}</option>
        <option value="alert">{{Alerte}}</option>
        <option value="popup">{{Pop-up}}</option>
        <option value="report">{{Rapport}}</option>
    </select>
</center>
<br/>
<div class="alert alert-info mod_actionValue_selDescription sleep">
    {{Pause de x seconde(s)}}
</div>

<div class="alert alert-info mod_actionValue_selDescription wait" style="display:none;">
    {{Attend jusqu’à ce que la condition soit valide (maximum 2h)}}
</div>

<div class="alert alert-info mod_actionValue_selDescription variable" style="display:none;">
    {{Création/modification d’une variable ou de la valeur d’une variable}}
</div>

<div class="alert alert-info mod_actionValue_selDescription scenario" style="display:none;">
    {{Permet le contrôle des scénarios}}
</div>

<div class="alert alert-info mod_actionValue_selDescription stop" style="display:none;">
 {{Arrête le scénario}}
</div>

<div class="alert alert-info mod_actionValue_selDescription say" style="display:none;">
    {{Permet de faire dire un texte à Jeedom (ne marche que si un onglet jeedom est ouvert dans le navigateur)}}
</div>

<div class="alert alert-info mod_actionValue_selDescription gotodesign" style="display:none;">
    {{Change le design affiché sur tous les navigateurs qui affichent un design par le design demandé}}
</div>

<div class="alert alert-info mod_actionValue_selDescription log" style="display:none;">
 {{Permet de rajouter un message dans les logs}}
</div>

<div class="alert alert-info mod_actionValue_selDescription message" style="display:none;">
 {{Permet d'ajouter une message dans le centre de message}}
</div>

<div class="alert alert-info mod_actionValue_selDescription equipement" style="display:none;">
    {{Permet de modifier les prorietés visible/invisible actif/inactif d'un équipement}}
</div>

<div class="alert alert-info mod_actionValue_selDescription ask" style="display:none;">
    {{Action qui permet à Jeedom de faire une demande puis de stocker la réponse dans une variable. Cette action est bloquante et ne finie que : si jeedom reçoit une réponse ou si le timeout est atteint. Pour le moment cette action n'est compatible qu'avec les plugins SMS, Slack, SARAH et Telegram.}}
</div>

<div class="alert alert-info mod_actionValue_selDescription jeedom_poweroff" style="display:none;">
    {{Envoi l'ordre à Jeedom de s'éteindre}}
</div>

<div class="alert alert-info mod_actionValue_selDescription scenario_return" style="display:none;">
    {{Retourne un texte ou une valeur pour une interaction par exemple}}
</div>

<div class="alert alert-info mod_actionValue_selDescription icon" style="display:none;">
    {{Permet d'affecter une icone au scénario}}
</div>

<div class="alert alert-info mod_actionValue_selDescription alert2" style="display:none;">
    {{Permet d'afficher un petit message d'alerte sur tous les navigateurs qui ont une page jeedom d'ouvert. Vous pouvez en plus choisir 4 niveaux d'alerte}}
</div>

<div class="alert alert-info mod_actionValue_selDescription popup" style="display:none;">
    {{Permet d'afficher un popup qui doit absolument être validé sur tous les navigateurs qui ont une page jeedom d'ouvert.}}
</div>

<div class="alert alert-info mod_actionValue_selDescription report" style="display:none;">
    {{Permet d'envoyer par une commande message un rapport d'une vue, d'un design ou d'un panel en PNG/PDF/JPEG/SVG.}}
</div>
<script>
    $('#mod_actionValue_sel').on('change',function(){
      var value = $(this).value();
      if(value == 'alert'){
        value = 'alert2';
    }
    $('.mod_actionValue_selDescription').hide();
    $('.mod_actionValue_selDescription.'+value).show();
});

    function mod_insertAction() {
    }

    mod_insertAction.options = {};

    mod_insertAction.setOptions = function (_options) {
        mod_insertAction.options = _options;
        if(init(_options.scenario,false) == false){
            $('#mod_actionValue_sel .scenarioOnly').hide();
        }else{
             $('#mod_actionValue_sel .scenarioOnly').show();
        }
    }

    mod_insertAction.getValue = function () {
        return $('#mod_actionValue_sel').value();
    }
</script>
