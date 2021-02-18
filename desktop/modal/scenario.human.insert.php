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
<center>
  <select id="mod_insertScenariocValue_value" class="form-control">
    <?php
      $scenarioList = scenario::allOrderedByGroupObjectName();
      $options = '';
      foreach ($scenarioList as $scenario) {
        $scName = $scenario->getHumanName();
        $options .= '<option value="#' . $scName . '#" data-scenario_id="' . $scenario->getId() . '">' . $scName . '</option>';
      }
      echo $options;
    ?>
  </select>
</center>
<script>
  function mod_insertScenario() {}

  mod_insertScenario.setOptions = function(_options) {}

  mod_insertScenario.getId = function() {
    return $('#mod_insertScenariocValue_value option:selected').attr('data-scenario_id');
  }

  mod_insertScenario.getValue = function() {
    return $('#mod_insertScenariocValue_value').value()
  }
</script>