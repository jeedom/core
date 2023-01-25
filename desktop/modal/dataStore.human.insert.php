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

<table id="table_mod_insertDataSotreValue" class="table table-condensed table-bordered">
  <thead>
    <tr>
      <th style="width: 150px;">{{Nom}}</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td class="mod_insertDataStoreValue_name">
        <select class='form-control'>
          <?php
            foreach ((dataStore::byTypeLinkId(init('type', 'scenario'))) as $dataStore) {
              echo '<option value="' . $dataStore->getKey() . '">' . $dataStore->getKey() . '</option>';
            }
          ?>
         </select>
      </td>
    </tr>
  </tbody>
</table>

<script>
(function() {// Self Isolation!
  if (window.mod_insertDataStore == undefined) {
    window.mod_insertDataStore = function() {}
    mod_insertDataStore.setOptions = function(_options) {}
  }

  mod_insertDataStore.getValue = function() {
    let variable = document.querySelector('#table_mod_insertDataSotreValue .mod_insertDataStoreValue_name > select')?.selectedOptions
    if (!variable || variable.length == 0) {
      return ''
    }
    return '#variable(' + variable[0].text + ')#'
  }

  mod_insertDataStore.getId = function() {
    return document.querySelector('#table_mod_insertDataSotreValue tbody tr').querySelector('.mod_insertDataStoreValue_name select').jeeValue()
  }
})()
</script>