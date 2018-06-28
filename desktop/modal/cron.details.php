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

$cronId = init('id');
$cron = cron::byId($cronId);
$cron = $cron->toArray();

//give more details for DoIn crons:
if ($cron['function'] == 'doIn') {
  $optionAr = $cron['option'];
  $scenario_id = $optionAr['scenario_id'];
  $scenarioElement_id = $optionAr['scenarioElement_id'];
  $scName = scenario::byId($scenario_id)->getName();
  $scenarioElement = scenarioElement::byId($scenarioElement_id)->export();

  $ar = array();
  $ar['scenario'] = $scName.' | '.$scenario_id;
  $ar['scenarioElement'] = $scenarioElement;
  $ar['scenarioElement_id'] = $scenarioElement_id;

  $cron['option'] = $ar;
}
?>

<form class="form-horizontal">
  <fieldset>
    <?php
    foreach ($cron as $key => $value) {
      echo '<div class="form-group">';
      echo '<label class="col-sm-2 control-label">' . $key . '</label>';
      echo '<div class="col-sm-10">';
            if (is_array($value)) {
                echo '<textarea class="form-control" rows="10" disabled>';
                  $str = json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                  $str = str_replace('\n', '&#13;', $str);
                  $str = str_replace('\"', "'", $str);
                  $str = substr($str, 1, -1);
                  echo $str;
                echo '</textarea>';
            } else {
                echo '<input class="form-control" disabled value="' . $value . '" />';
            }
            echo '</div>';
            echo '</div>';
    }
    ?>
  </fieldset>
</form>