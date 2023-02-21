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

<div id="md_cmdGraphSelect" data-modalType="md_cmdGraphSelect">
  <table id="table_addViewDataHidden" style="display: none;">
    <tbody></tbody>
  </table>
  <table id="table_addViewData" class="table table-condensed">
    <thead>
      <tr>
        <th data-type="checkbox">#</th>
        <th>{{Objet}}</th>
        <th>{{Nom}}</th>
        <th data-sortable="false" data-filter="false" style="width:740px">{{Affichage}}</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $html = '';
      foreach ((cmd::isHistorized()) as $cmd) {
        $eqLogic = $cmd->getEqLogic();
        if (!is_object($eqLogic)) {
          continue;
        }
        $object = $cmd->getEqLogic()->getObject();
        $html .= '<tr data-link_id="' . $cmd->getId() . '" data-type="graph" data-viewDataType="cmd">';
        $html .= '<td style="width: 50px;">';
        $html .= '<input type="checkbox" class="enable" />';
        $html .= '<input class="graphDataOption" data-l1key="link_id" value="' . $cmd->getId() . '" hidden/>';
        $html .= '</td>';
        $html .= '<td class="object_name">';
        if (is_object($object)) {
          $html .= $object->getName();
        }
        $html .= '<input class="graphDataOption" data-l1key="type" value="cmd" hidden/>';
        $html .= '</td>';
        $html .= '<td class="name">[' . $eqLogic->getName() . ']['.$cmd->getName() . ']</td>';
        $html .= '<td class="display">';
        $html .= '<div class="option">';
        $html .= '<form class="form-inline">';
        $html .= '<div class="form-group">';
        $html .= '<input type="color" class="graphDataOption form-control input-sm" data-l1key="configuration" data-l2key="graphColor">';
        $html .= '</div> ';

        $html .= '<div class="form-group">';
        $html .= ' <select class="graphDataOption form-control input-sm" data-l1key="configuration" data-l2key="graphType" style="width : 70px;">';
        $html .= '<option value="line">{{Ligne}}</option>';
        $html .= '<option value="area">{{Aire}}</option>';
        $html .= '<option value="column">{{Colonne}}</option>';
        $html .= '<option value="pie">{{Camembert}}</option>';
        $html .= '</select> ';
        $html .= '</div> ';

        $html .= '<div class="form-group">';
        $html .= ' <select class="graphDataOption form-control input-sm" data-l1key="configuration" data-l2key="groupingType" style="width : 140px;">';
        $html .= '<option value="">{{Aucun groupement}}</option>';
        $html .= '<option value="sum::hour">{{Somme par heure}}</option>';
        $html .= '<option value="average::hour">{{Moyenne par heure}}</option>';
        $html .= '<option value="low::hour">{{Minimum par heure}}</option>';
        $html .= '<option value="high::hour">{{Maximum par heure}}</option>';
        $html .= '<option value="sum::day">{{Somme par jour}}</option>';
        $html .= '<option value="average::day">{{Moyenne par jour}}</option>';
        $html .= '<option value="low::day">{{Minimum par jour}}</option>';
        $html .= '<option value="high::day">{{Maximum par jour}}</option>';
        $html .= '<option value="sum::week">{{Somme par semaine}}</option>';
        $html .= '<option value="average::week">{{Moyenne par semaine}}</option>';
        $html .= '<option value="low::week">{{Minimum par semaine}}</option>';
        $html .= '<option value="high::week">{{Maximum par semaine}}</option>';
        $html .= '<option value="sum::month">{{Somme par mois}}</option>';
        $html .= '<option value="average::month">{{Moyenne par mois}}</option>';
        $html .= '<option value="low::month">{{Minimum par mois}}</option>';
        $html .= '<option value="high::month">{{Maximum par mois}}</option>';
        $html .= '<option value="sum::year">{{Somme par année}}</option>';
        $html .= '<option value="average::year">{{Moyenne par année}}</option>';
        $html .= '<option value="low::year">{{Minimum par année}}</option>';
        $html .= '<option value="high::year">{{Maximum par année}}</option>';
        $html .= '</select> ';
        $html .= '</div> ';

        $html .= '<div class="form-group">';
        $html .= '<label>{{Escalier}} :</label> <input type="checkbox" class="graphDataOption" data-l1key="configuration" data-l2key="graphStep"> ';
        $html .= '<label>{{Empiler}} :</label> <input type="checkbox" class="graphDataOption" data-l1key="configuration" data-l2key="graphStack"> ';
        $html .= '<label>{{Variation}} :</label> <input type="checkbox" class="graphDataOption" data-l1key="configuration" data-l2key="derive"> ';

        $html .= '<label>{{Echelle}} :</label> <select class="graphDataOption form-control input-sm" data-l1key="configuration" data-l2key="graphScale" style="width : 80px;">';
        $html .= '<option value="0">{{Droite}}</option>';
        $html .= '<option value="1">{{Gauche}}</option>';
        $html .= '</select>';
        $html .= '<select class="graphDataOption form-control input-sm" data-l1key="configuration" data-l2key="graphScaleVisible" style="width : 80px;">';
        $html .= '<option value="0">{{Invisible}}</option>';
        $html .= '<option value="1">{{Visible}}</option>';
        $html .= '</select>';

        $html .= '</div>';
        $html .= '</form>';
        $html .= '</div>';
        $html .= '</td>';
        $html .= '</tr>';
      }
      echo $html;
      ?>
    </tbody>
  </table>
</div>
<script>
if (!jeeFrontEnd.md_cmdGraphSelect) {
  jeeFrontEnd.md_cmdGraphSelect = {
    tableViewData: null,
    vDataTable: null,
    init: function() {
      this.tableViewData = document.getElementById('table_addViewData')
      this.vDataTable = new DataTable(this.tableViewData, {
        columns: [
          { select: 1, sort: "asc" },
          { select: 3, sortable: false }
        ],
        searching: true,
        paging: true,
        perPage: 20,
        perPageSelect: [10, 20, 30, 40, 50, 100, 200],
      })
    },
    displayOptions: function(_options) {
      var tr, idx
      for (idx in _options) {
        tr = jeeFrontEnd.md_cmdGraphSelect.vDataTable.table.rows.filter(t => t.node.getAttribute('data-link_id') == _options[idx].link_id)
        if (tr.length == 0) continue
        tr[0].node.querySelector('.enable').jeeValue(1)
        tr[0].node.setJeeValues(_options[idx], '.graphDataOption')
      }
    },
    getOptions: function() {
      var options = []
      var graphData
      jeeFrontEnd.md_cmdGraphSelect.vDataTable.table.rows.forEach(_tr => {
        if (_tr.node.querySelector('.enable').checked === true) {
          graphData = _tr.node.getJeeValues('.graphDataOption')[0]
          graphData.link_id = _tr.node.getAttribute('data-link_id')
          options.push(graphData)
        }
      })
      return options
    },
  }
}

(function() {// Self Isolation!
  var jeeM = jeeFrontEnd.md_cmdGraphSelect
  jeeM.init()
})()
</script>