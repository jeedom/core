<?php
if (!isConnect('admin')) {
  throw new Exception('{{401 - Accès non autorisé}}');
}
?>

<div class="row row-overflow">
  <div class="hasfloatingbar col-xs-12">
    <div class="floatingbar">
        <div class="input-group">
          <span class="input-group-btn">
            <?php
            if (config::byKey('enableCron') == 0) {
              echo '<a class="btn btn-success btn-sm roundedLeft" id="bt_changeCronState" data-state="1"><i class="fas fa-check"></i> {{Activer le système cron}}';
            } else {
              echo '<a class="btn btn-danger btn-sm roundedLeft" id="bt_changeCronState" data-state="0"><i class="fas fa-times"></i> {{Désactiver le système cron}}';
            }
            ?>
          <a class="btn btn-sm" id="bt_refreshCron"><i class="fas fa-sync"></i> {{Rafraîchir}}
          </a><a class="btn btn-sm" id="bt_addCron"><i class="fas fa-plus-circle"></i> {{Ajouter}}
          </a><a class="btn btn-success roundedRight btn-sm" id="bt_save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a>
        </span>
      </div>
    </div>

    <ul class="nav nav-tabs" role="tablist">
      <li role="presentation" class="active"><a href="#cron" role="tab" data-toggle="tab"><i class="fas fa-clock"></i> {{Cron}}</a></li>
      <li role="presentation"><a href="#listener" role="tab" data-toggle="tab"><i class="fas fa-assistive-listening-systems"></i> {{Listener}}</a></li>
      <li role="presentation"><a href="#deamon" role="tab" data-toggle="tab"><i class="fas fa-bug" ></i> {{Démon}}</a></li>
    </ul>

    <div class="tab-content">
      <div role="tabpanel" class="tab-pane active" id="cron">
        <table id="table_cron" class="ui-table-reflow table table-bordered table-condensed tablesorter">
          <thead>
            <tr>
              <th data-filter="false" data-resizable="false">{{ID}}</th>
              <th data-filter="false" data-sorter="checkbox" data-resizable="false">{{Actif}}</th>
              <th data-filter="false">{{PID}}</th>
              <th data-filter="false" data-sorter="checkbox">{{Démon}}</th>
              <th data-filter="false" data-sorter="checkbox">{{Unique}}</th>
              <th data-filter="false" data-sorter="inputs">{{Classe}}</th>
              <th data-filter="false" data-sorter="inputs">{{Fonction}}</th>
              <th data-filter="false" data-sorter="inputs">{{Programmation}}</th>
              <th data-filter="false" data-sorter="inputs">{{Timeout (min)}}</th>
              <th data-filter="false">{{Dernier lancement}}</th>
              <th data-filter="false">{{Dernière durée}}</th>
              <th data-filter="false">{{Statut}}</th>
              <th data-filter="false" data-sorter="false">{{Actions}}</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>

      <div role="tabpanel" class="tab-pane" id="listener">
        <table id="table_listener" class="ui-table-reflow table table-bordered table-condensed" >
          <thead>
            <tr>
              <th style="width: 40px;">#</th>
              <th style="width: 80px;" data-sorter="false" data-filter="false"></th>
              <th>{{Event}}</th>
              <th style="width: 120px;">{{Classe}}</th>
              <th style="width: 120px;">{{Fonction}}</th>
              <th data-filter="false" data-sorter="false" style="width: 50px;">{{Actions}}</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>

      <div role="tabpanel" class="tab-pane" id="deamon">
        <a id="bt_refreshDeamon" class="btn btn-sm btn-default pull-right" style="margin-top: 5px;"><i class="fas fa-sync"></i> {{Rafraîchir}}</a>
        <table id="table_deamon" class="ui-table-reflow table table-bordered table-condensed" >
          <thead>
            <tr>
              <th>{{Nom}}</th>
              <th>{{Status}}</th>
              <th>{{Date lancement}}</th>
              <th>{{Action}}</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>

  </div>
</div>

<?php include_file('desktop', 'cron', 'js');?>
