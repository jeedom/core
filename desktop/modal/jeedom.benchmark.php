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
?>

<div id="md_jeedomBenchmark" data-modalType="md_jeedomBenchmark">
  <table class="table table-condensed">
    <thead>
      <tr>
        <th>{{Nom}}</th>
        <th>{{Temps}}</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $benchmark = jeedom::benchmark();
      foreach ($benchmark as $key => $value) {
        $tr = '<tr>';
        $tr .= '<td>' . $key . '</td>';
        $tr .= '<td>' . $value . '</td>';
        $tr .= '</tr>';
        echo $tr;
      }
      ?>
    </tbody>
  </table>
</div>