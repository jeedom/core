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

if (!isConnect('admin')) {
  throw new Exception('{{401 - Accès non autorisé}}');
}
if (init('interactDef_id') == '') {
  throw new Exception('{{Interact Def ID ne peut être vide}}');
}
$interactQueries = interactQuery::byInteractDefId(init('interactDef_id'));
sendVarToJS('interactDisplay_interactDef_id', init('interactDef_id'));
if (count($interactQueries) == 0) {
  throw new Exception('{{Aucune phrase trouvée}}');
}
?>

<div style="display: none;" id="md_displayInteractQueryAlert"></div>
<table class="table table-bordered table-condensed" id="table_interactQuery" style="width:100%">
  <thead>
    <tr>
      <th>{{Phrase}}</th>
    </tr>
  </thead>
  <tbody>
    <?php
    foreach ($interactQueries as $interactQuery) {
      $tr = '<tr data-interactQuery_id="' . $interactQuery->getId() . '"">';
      $tr .= '<td>' . $interactQuery->getQuery() . '</td>';
      $tr .= '</tr>';
      echo $tr;
    }
    ?>
  </tbody>
</table>

<script>
  jeedomUtils.initTableSorter()
</script>