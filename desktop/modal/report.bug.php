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
if (config::byKey('market::address') == '') {
  throw new Exception('{{Aucune adresse pour le market n\'est renseignée}}');
}
if (config::byKey('market::apikey') == '' && config::byKey('market::username') == '') {
  throw new Exception('{{Aucun compte market n\'est renseigné. Veuillez vous enregistrer sur le market, puis renseignez vos identifiants dans}} ' . config::byKey('product_name') . ' {{avant d\'ouvrir un ticket}}');
}
$healths = jeedom::health();
$first = true;
foreach ($healths as $health) {
  if ($health['state']) {
    continue;
  }
  if ($first) {
    echo '<div class="alert alert-danger">';
    echo __('Attention nous avons detecté les soucis suivant :', __FILE__) . ' ' . '<br/>';
  }
  $first = false;
  echo '- ' . $health['name'] . '  : ' . $health['result'];
  if ($health['comment'] != '') {
    echo '(' . $health['name'] . ')';
  }
  echo '<br/>';
}
if (!$first) {
  echo '</div>';
}
?>
<div id="md_reportBug" data-modalType="md_reportBug">
  <div id='div_alertReportBug'></div>
  <form class="form-horizontal" role="form" id="form_reportBug">
    <div class="panel panel-success">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fas fa-info"></i> {{Etape 1 : Information sur les tickets}}</h3>
      </div>
      <div class="panel-body">
        {{Merci de vérifier avant toute ouverture de ticket :}}<br />
        {{- que la question n'a pas déjà été posée sur le <a href='https://community.jeedom.com/'>forum</a>}}<br />
        {{- que la catégorie est bien sélectionnée pour que votre ticket soit traité dans les plus courts délais}}<br />
        <?php if (config::byKey('doc::base_url', 'core') != '') { ?>
          {{- que la réponse n'est pas déjà dans la <a href='<?php echo config::byKey('doc::base_url', 'core'); ?>'>documentation</a>}}
        <?php } ?>
      </div>
    </div>
    <div class="panel panel-danger">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fas fa-info"></i> {{Etape 2 : Choix du type de demande}}</h3>
      </div>
      <div class="panel-body">
        <strong>{{Assistance technique}}</strong> : {{Rédigez votre question à l'attention de notre service Technique qui y répondra dans les meilleurs délais.}}<br /><br />
        <strong>{{Rapport}}</strong> : {{Vous pouvez déclarer un bug qui sera publié sur notre Bug Tracker public (ATTENTION votre message sera public, il pourra être supprimé s'il ne s'agit pas d'un bug,  vous ne recevrez pas d'assistance technique suite à cette déclaration).}}<br /><br />
        <strong>{{Demande d'amélioration}}</strong> : {{Vous pouvez envoyer des propositions d'amélioration qui seront publiées sur notre page publique dédiée et qui pourront être intégrées dans notre feuille de route.}}<br /><br />
        <div class="center">
          <a href="https://community.jeedom.com/tags/bug" target="_blank" style="font-weight:bold;">{{Voir les bugs}}</a><br />
          <a href="https://community.jeedom.com/tag/am%C3%A9lioration" target="_blank" style="font-weight:bold;">{{Voir les propositions d'amélioration}}</a>
        </div>
      </div>
    </div>

    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fas fa-cogs"></i> {{Etape 3 : Catégorie et type de la demande}}</h3>
      </div>
      <div class="panel-body">
        <div class="form-group">
          <label class="col-sm-2 control-label">{{Type}}</label>
          <div class="col-sm-4">
            <select class="form-control ticketAttr" data-l1key="type">
              <option value=''>{{Aucun}}</option>
              <option value='support'>{{Assistance technique}}</option>
              <option value='Bug'>{{Rapport}}</option>
              <option value='improvement'>{{Demande d'amélioration}}</option>
            </select>
          </div>
          <label class="col-sm-2 control-label">{{Catégorie}}</label>
          <div class="col-sm-4">
            <select class="form-control ticketAttr" data-l1key="category">
              <option value=''>{{Aucune}}</option>
              <option data-issue="" value="core" data-pagehelp="doc/<?php echo config::byKey('language', 'core', 'fr_FR'); ?>/depannage.html">{{Général}}</option>
              <option data-issue="" value="core" data-pagehelp="doc/<?php echo config::byKey('language', 'core', 'fr_FR'); ?>/scenario.html">{{Scénario}}</option>
              <?php
              foreach ((plugin::listPlugin(true)) as $plugin) {
                echo '<option data-issue="' . $plugin->getIssue() . '" value="plugin::' . $plugin->getId() . '" data-pagehelp="' . $plugin->getDocumentation() . '">Plugin ' . $plugin->getName() . '</option>';
              }
              ?>
            </select>
          </div>
        </div>
      </div>
    </div>
    <div class="panel panel-primary" id="div_reportModalSendAction" style="display:none;">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fas fa-pencil-alt"></i> {{Etape 4 : Demande de support}}</h3>
      </div>
      <div class="panel-body">
        <div class="alert alert-info">{{IMPORTANT : pour avoir une réponse rapide et précise merci de lire cette <a target="_blank" href="https://doc.jeedom.com/fr_FR/howto/remonter_un_bug">documentation}}</a></div>
        <div class="form-group">
          <label class="col-sm-2 control-label">{{Titre}}</label>
          <div class="col-sm-7">
            <input class="form-control ticketAttr" data-l1key="title" />
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-2 control-label">{{Message}}</label>
          <div class="col-sm-9">
            <textarea class="form-control messageAttr input-sm" data-l1key="message" rows="8"></textarea>
            <input class="form-control ticketAttr" data-l1key="options" data-l2key="page" style="display: none;" />
          </div>
        </div>
        <div class="form-actions">
          <label style="margin-left: 140px;"><input type="checkbox" class="ticketAttr" data-l1key="openSupport" checked="checked" /> {{Ouvrir un accès au support}}</label>
          <a class="btn btn-success pull-right" id="bt_sendBugReport"><i class="far fa-check-circle"></i> {{Envoyer}}</a>
        </div>
      </div>
    </div>

    <div class="panel panel-primary" id="div_reportModalPrivateIssue" style="display:none;">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fas fa-pencil-alt"></i> {{Etape 4 : Demande de support}}</h3>
      </div>
      <div class="panel-body">
        <div class="form-group">
          <label class="col-sm-5 control-label">{{Ce plugin utilise un gestionnaire de demande de support}}</label>
          <div class="col-sm-2">
            <a class="btn btn-success" id="bt_reportBugIssueUrl" href="#" target="_blank"><i class="far fa-check-circle"></i> {{Accéder}}</a>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>

<script>
if (!jeeFrontEnd.md_reportBug) {
  jeeFrontEnd.md_reportBug = {
    init: function() {
      document.querySelector('.ticketAttr[data-l1key="options"][data-l2key="page"]').jeeValue(location.href)
      if (getUrlVars('m') !== false) {
        document.querySelector('.ticketAttr[data-l1key="category"]').jeeValue('plugin::' + getUrlVars('m'))
      }
    },
    sendReport: function() {
      var ticket = document.getElementById('form_reportBug').getJeeValues('.ticketAttr')[0]
      ticket.messages = document.getElementById('form_reportBug').getJeeValues('.messageAttr')
      domUtils.ajax({
        type: "POST",
        url: "core/ajax/repo.ajax.php",
        data: {
          action: "sendReportBug",
          repo: 'market',
          ticket: json_encode(ticket),
        },
        dataType: 'json',
        error: function(request, status, error) {
          handleAjaxError(request, status, error, document.getElementById('div_alertReportBug'))
        },
        success: function(data) {
          if (data.state != 'ok') {
            jeedomUtils.showAlert({
              message: data.result,
              level: 'danger'
            })
            return
          }
          document.getElementById('form_reportBug').unseen()
          document.getElementById('bt_sendBugReport').unseen()
          document.getElementById('md_reportBug').scrollIntoView()
          if (data.result != '' && data.result != null && data.result != 'ok') {
            jeedomUtils.showAlert({
              message: '{{Vous venez de déclarer un bug qui sera publié sur notre Bug Tracker public.<br/>Vous pouvez le suivre}} <a target="_blank" href="' + data.result + '">ici</a><br/><br/><strong>ATTENTION</strong> votre message sera public, il pourra être supprimé s\'il ne s\'agit pas d\'un bug, vous ne recevrez pas d\'assistance technique suite à cette déclaration)',
              level: 'success'
            })
          } else {
            jeedomUtils.showAlert({
              message: '{{Votre ticket a bien été ouvert. Un mail va vous être envoyé}}',
              level: 'success'
            })
          }
        }
      })
    },
  }
}
(function() {// Self Isolation!
  var jeeM = jeeFrontEnd.md_reportBug
  jeeM.init()

  //Manage events outside parents delegations:
  document.querySelector('#md_reportBug #bt_sendBugReport').addEventListener('click', function(event) {
    jeeFrontEnd.md_reportBug.sendReport()
  })
  /*Events delegations
  */
  document.getElementById('md_reportBug').addEventListener('change', function(event) {
    var _target = null
    if ((_target = event.target.closest('.ticketAttr[data-l1key="type"]')) || (_target = event.target.closest('.ticketAttr[data-l1key="category"]'))) {
      var type = document.querySelector('.ticketAttr[data-l1key="type"]').value
      var cat = document.querySelector('.ticketAttr[data-l1key="category"]').value
      var catIssue = document.querySelector('.ticketAttr[data-l1key="category"]').selectedOptions[0].getAttribute('data-issue')

      jeedomUtils.hideAlert()
      if (type == 'Bug' || type == 'improvement') {
        jeedomUtils.showAlert({
          message: '{{ATTENTION : cette demande sera publique, il ne faut SURTOUT PAS mettre d\'information personnelle (mail, compte market, clé api...)}}',
          level: 'warning'
        })
      }
      document.getElementById('div_reportModalPrivateIssue').unseen()
      if (type == '' || cat == '') {
        document.getElementById('div_reportModalSendAction').unseen()
      } else {
        if (catIssue == '') {
          document.getElementById('div_reportModalSendAction').seen()
        } else {
          document.getElementById('div_reportModalPrivateIssue').seen()
          document.getElementById('bt_reportBugIssueUrl').setAttribute('href', catIssue)
        }
      }
      return
    }
  })

})()
</script>