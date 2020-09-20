<?php
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

if (!isConnect()) {
  throw new Exception('{{401 - Accès non autorisé}}');
}
?>

<select id="mod_actionValue_sel" class="form-control">
  <option value="sleep">{{Pause}}</option>
  <option value="variable">{{Variable}}</option>
  <option value="delete_variable">{{Supprimer une variable}}</option>
  <option value="scenario">{{Scénario}}</option>
  <option value="stop" class="scenarioOnly">{{Stop}}</option>
  <option value="wait">{{Attendre}}</option>
  <option value="gotodesign">{{Aller au design}}</option>
  <option value="log" class="scenarioOnly">{{Ajouter un log}}</option>
  <option value="message">{{Créer un message}}</option>
  <option value="equipement">{{Activer/Désactiver Masquer/Afficher un équipement}}</option>
  <option value="ask">{{Faire une demande}}</option>
  <option value="jeedom_poweroff">{{Arrêter}} <?php echo config::byKey('product_name'); ?></option>
  <option value="jeedom_reboot">{{Redémarrer}} <?php echo config::byKey('product_name'); ?></option>
  <option value="scenario_return" class="scenarioOnly">{{Retourner un texte/une donnée}}</option>
  <option value="icon" class="scenarioOnly">{{Icône}}</option>
  <option value="alert">{{Alerte}}</option>
  <option value="popup">{{Pop-up}}</option>
  <option value="report">{{Rapport}}</option>
  <option value="exportHistory">{{Export historique}}</option>
  <option value="remove_inat">{{Supprimer bloc DANS/A programmé}}</option>
  <option value="event">{{Evènement}}</option>
  <option value="tag">{{Tag}}</option>
  <option value="setColoredIcon">{{Coloration des icones}}</option>
</select>
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

<div class="alert alert-info mod_actionValue_selDescription delete_variable" style="display:none;">
  {{Suppression d’une variable}}
</div>

<div class="alert alert-info mod_actionValue_selDescription scenario" style="display:none;">
  {{Permet le contrôle des scénarios}}
</div>

<div class="alert alert-info mod_actionValue_selDescription stop" style="display:none;">
  {{Arrête le scénario}}
</div>

<div class="alert alert-info mod_actionValue_selDescription say" style="display:none;">
  {{Permet de faire dire un texte à}} <?php echo config::byKey('product_name'); ?> {{(ne marche que si un onglet Jeedom est ouvert dans le navigateur)}}
</div>

<div class="alert alert-info mod_actionValue_selDescription gotodesign" style="display:none;">
  {{Sur tous les navigateurs qui affichent un design, le remplace par celui demandé}}
</div>

<div class="alert alert-info mod_actionValue_selDescription log" style="display:none;">
  {{Permet de rajouter un message dans les logs}}
</div>

<div class="alert alert-info mod_actionValue_selDescription message" style="display:none;">
  {{Permet d'ajouter une message dans le centre de message}}
</div>

<div class="alert alert-info mod_actionValue_selDescription equipement" style="display:none;">
  {{Permet de modifier les proriétés visible/invisible actif/inactif d'un équipement}}
</div>

<div class="alert alert-info mod_actionValue_selDescription ask" style="display:none;">
  {{Action qui permet à Jeedom de faire une demande puis de stocker la réponse dans une variable. Cette action est bloquante et ne finit que si Jeedom reçoit une réponse ou si le timeout est atteint. Pour le moment cette action n'est compatible qu'avec les plugins SMS, Slack, SARAH et Telegram.}}
</div>

<div class="alert alert-info mod_actionValue_selDescription jeedom_poweroff" style="display:none;">
  {{Envoi l'ordre à Jeedom de s'éteindre}}
</div>

<div class="alert alert-info mod_actionValue_selDescription jeedom_reboot" style="display:none;">
  {{Envoi l'ordre à Jeedom de redémarrer}}
</div>

<div class="alert alert-info mod_actionValue_selDescription scenario_return" style="display:none;">
  {{Retourne un texte ou une valeur pour une interaction par exemple}}
</div>

<div class="alert alert-info mod_actionValue_selDescription icon" style="display:none;">
  {{Permet d'affecter une icône au scénario}}
</div>

<div class="alert alert-info mod_actionValue_selDescription alert2" style="display:none;">
  {{Permet d'afficher un petit message d'alerte sur tous les navigateurs qui ont une page Jeedom d'ouvert. Vous pouvez en plus choisir 4 niveaux d'alerte}}
</div>

<div class="alert alert-info mod_actionValue_selDescription popup" style="display:none;">
  {{Permet d'afficher un popup qui doit absolument être validé sur tous les navigateurs qui ont une page}} <?php echo config::byKey('product_name'); ?> {{ouverte.}}
</div>

<div class="alert alert-info mod_actionValue_selDescription report" style="display:none;">
  {{Permet d'envoyer, par une commande message, un rapport d'une vue, d'un design ou d'un panel en PNG/PDF/JPEG/SVG.}}
</div>

<div class="alert alert-info mod_actionValue_selDescription remove_inat" style="display:none;">
  {{Permet de supprimer la programmation de tous les blocs DANS et A du scénario}}
</div>

<div class="alert alert-info mod_actionValue_selDescription event" style="display:none;">
  {{Permet de pousser une valeur dans une commande de type information de maniere arbitraire}}
</div>

<div class="alert alert-info mod_actionValue_selDescription tag" style="display:none;">
  {{Permets d'ajouter/modifier un tag (le tag n'existe que pendant l'execution en cours du scénario à la difference des variables qui survive à la fin du scénario)}}
</div>

<div class="alert alert-info mod_actionValue_selDescription setColoredIcon" style="display:none;">
  {{Permets d'activer ou non la coloration des icones sur le dashboard}}
</div>

<script>
$(function() {
  var select = $('#mod_actionValue_sel')
  select.html(select.find('option').sort(function(x, y) {
    return $(x).text() > $(y).text() ? 1 : -1
  }))
  select.prop("selectedIndex", 0).trigger("change")
})

$('#mod_actionValue_sel').on('change',function() {
  var value = $(this).value()
  if (value == 'alert') value = 'alert2'
  $('.mod_actionValue_selDescription').hide()
  $('.mod_actionValue_selDescription.'+value).show()
})

function mod_insertAction() {}

mod_insertAction.options = {}

mod_insertAction.setOptions = function(_options) {
  mod_insertAction.options = _options;
  if (init(_options.scenario,false) == false) {
    $('#mod_actionValue_sel .scenarioOnly').hide()
  } else {
    $('#mod_actionValue_sel .scenarioOnly').show()
  }
}

mod_insertAction.getValue = function() {
  return $('#mod_actionValue_sel').value()
}
</script>
