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
  <option value="equipement">{{Activer/Désactiver Masquer/Afficher un équipement}}</option>
  <option value="sleep">{{Pause}}</option>
  <option value="variable">{{Variable}}</option>
  <option value="delete_variable">{{Supprimer une variable}}</option>
  <option value="scenario">{{Scénario}}</option>
  <option value="stop">{{Stop}}</option>
  <option value="wait">{{Attendre}}</option>
  <option value="gotodesign">{{Aller au design}}</option>
  <option value="log">{{Ajouter un log}}</option>
  <option value="message">{{Créer un message}}</option>
  <option value="ask">{{Faire une demande}}</option>
  <option value="jeedom_poweroff">{{Arrêter}} <?php echo config::byKey('product_name'); ?></option>
  <option value="jeedom_reboot">{{Redémarrer}} <?php echo config::byKey('product_name'); ?></option>
  <option value="scenario_return">{{Retourner un texte/une donnée}}</option>
  <option value="icon">{{Icône}}</option>
  <option value="alert">{{Alerte}}</option>
  <option value="popup">{{Pop-up}}</option>
  <option value="report">{{Rapport}}</option>
  <option value="exportHistory">{{Export historique}}</option>
  <option value="remove_inat">{{Supprimer bloc DANS/A programmé}}</option>
  <option value="event">{{Evènement}}</option>
  <option value="tag">{{Tag}}</option>
  <option value="setColoredIcon">{{Coloration des icones}}</option>
  <option value="genericType">{{Type générique}}</option>
  <option value="changeTheme">{{Changer de thème}}</option>
</select>
<input id="mod_actionValue_fil" class="form-control" placeholder="{{Filtre des commandes}}">
<br />
<div class="alert alert-info mod_actionValue_selDescription sleep" style="display:none;">
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
  {{Permet de faire dire un texte à}} <?php echo config::byKey('product_name'); ?> {{(ne marche que si un onglet}} <?php echo ' ' . config::byKey('product_name') . ' '; ?> {{ est ouvert dans le navigateur)}}
</div>

<div class="alert alert-info mod_actionValue_selDescription gotodesign" style="display:none;">
  {{Sur tous les navigateurs qui affichent un design, le remplace par celui demandé}}
</div>

<div class="alert alert-info mod_actionValue_selDescription log" style="display:none;">
  {{Permet de rajouter un message dans les logs}}
</div>

<div class="alert alert-info mod_actionValue_selDescription message" style="display:none;">
  {{Permet d'ajouter un message dans le centre de message}}
</div>

<div class="alert alert-info mod_actionValue_selDescription equipement">
  {{Permet de modifier les proriétés visible/invisible actif/inactif d'un équipement}}
</div>

<div class="alert alert-info mod_actionValue_selDescription ask" style="display:none;">
  {{Action qui permet à}} <?php echo ' ' . config::byKey('product_name') . ' '; ?> {{de faire une demande puis de stocker la réponse dans une variable. Cette action est bloquante et ne finit que si}} <?php echo ' ' . config::byKey('product_name') . ' '; ?> {{reçoit une réponse ou si le timeout est atteint. Pour le moment cette action n'est compatible qu'avec les plugins SMS, Slack, SARAH et Telegram.}}
</div>

<div class="alert alert-info mod_actionValue_selDescription jeedom_poweroff" style="display:none;">
  {{Envoi l'ordre à}} <?php echo ' ' . config::byKey('product_name') . ' '; ?> {{de s'éteindre}}
</div>

<div class="alert alert-info mod_actionValue_selDescription jeedom_reboot" style="display:none;">
  {{Envoi l'ordre à}} <?php echo ' ' . config::byKey('product_name') . ' '; ?> {{de redémarrer}}
</div>

<div class="alert alert-info mod_actionValue_selDescription scenario_return" style="display:none;">
  {{Retourne un texte ou une valeur pour une interaction par exemple}}
</div>

<div class="alert alert-info mod_actionValue_selDescription icon" style="display:none;">
  {{Permet d'affecter une icône au scénario}}
</div>

<div class="alert alert-info mod_actionValue_selDescription alert2" style="display:none;">
  {{Permet d'afficher un petit message d'alerte sur tous les navigateurs qui ont une page}} <?php echo ' ' . config::byKey('product_name') . ' '; ?> {{d'ouvert. Vous pouvez en plus choisir 4 niveaux d'alerte}}
</div>

<div class="alert alert-info mod_actionValue_selDescription popup" style="display:none;">
  {{Permet d'afficher un popup qui doit absolument être validé sur tous les navigateurs qui ont une page}} <?php echo config::byKey('product_name'); ?> {{ouverte.}}
</div>

<div class="alert alert-info mod_actionValue_selDescription report" style="display:none;">
  {{Permet d'envoyer, par une commande message, un rapport d'une vue, d'un design ou d'un panel en PNG/PDF/JPEG/SVG.}}
</div>

<div class="alert alert-info mod_actionValue_selDescription remove_inat" style="display:none;">
  {{Permet de supprimer la programmation de tous les blocs DANS et A d'un scénario}}
</div>

<div class="alert alert-info mod_actionValue_selDescription event" style="display:none;">
  {{Permet de pousser une valeur dans une commande de type information de maniere arbitraire}}
</div>

<div class="alert alert-info mod_actionValue_selDescription tag" style="display:none;">
  {{Permet d'ajouter/modifier un tag (le tag n'existe que pendant l'execution en cours du scénario à la difference des variables qui survive à la fin du scénario)}}
</div>

<div class="alert alert-info mod_actionValue_selDescription setColoredIcon" style="display:none;">
  {{Permet d'activer ou non la coloration des icones sur le dashboard}}
</div>

<div class="alert alert-info mod_actionValue_selDescription genericType" style="display:none;">
  {{Permet d'actionner des équipements en fonction de types génériques}}
</div>

<div class="alert alert-info mod_actionValue_selDescription changeTheme" style="display:none;">
  {{Permet de changer le thème en cours de l'interface}}
</div>

<script>
  (function() { // Self Isolation!
    if (window.mod_insertAction == undefined) {
      window.mod_insertAction = function() {}
      mod_insertAction.options = {}
    }

    mod_insertAction.setOptions = function(_options) {
      mod_insertAction.options = _options
      if (init(_options.scenario, false) == false) {
        document.querySelectorAll('#mod_actionValue_sel option').forEach(_opt => {
          if (jeedom.scenario.autoCompleteActionScOnly.includes(_opt.value)) {
            _opt.unseen()
          }
        })
      } else {
        document.querySelectorAll('#mod_actionValue_sel option').seen()
      }
    }
    mod_insertAction.getValue = function() {
      return document.getElementById('mod_actionValue_sel').value
    }

    document.getElementById('mod_actionValue_sel').sortOptions()

    document.getElementById('mod_actionValue_sel').addEventListener('change', function(event) {
      var value = event.target.value
      if (value == 'alert') value = 'alert2'
      document.querySelectorAll('.mod_actionValue_selDescription').unseen()
      document.querySelector('.mod_actionValue_selDescription.' + value).seen()
    })

    const select = document.getElementById('mod_actionValue_sel')
    const input = document.getElementById('mod_actionValue_fil')
    const allOptions = Array.from(select.options)
                                                                                      
    function filterOptions() {
      const text = input.value.trim().toLowerCase().stripAccents()

      select.innerHTML = ''

      allOptions
        .filter(option => {
          const optionText = option.textContent.toLowerCase().stripAccents()
          return text === '' || optionText.includes(text)
        })
        .forEach(option => {
          select.add(option.cloneNode(true))
        })
    }
                                                                                      
    input.addEventListener('input', filterOptions)                                                                                                                        
  })()
</script>
