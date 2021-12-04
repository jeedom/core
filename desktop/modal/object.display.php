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
  throw new Exception('401 Unauthorized');
}
$class = init('class');
if ($class == '' || !class_exists($class)) {
  throw new Exception(__('La classe demandée n\'existe pas :', __FILE__) . ' ' . $class);
}
if (!method_exists($class, 'byId')) {
  throw new Exception(__('La classe demandée n\'a pas de méthode byId :', __FILE__) . ' ' . $class);
}

$object = $class::byId(init('id'));
if (!is_object($object)) {
  throw new Exception(__('L\'objet n\'existe pas :', __FILE__) . ' ' . init('id'));
}

$array = utils::o2a($object);
if (count($array) == 0) {
  throw new Exception(__('L\'objet n\'a aucun élément :', __FILE__) . ' ' . print_r($array, true));
}
$otherInfo = array();

if ($class == 'cron' && $array['class'] == 'scenario' && $array['function'] == 'doIn') {
  $scenario = scenario::byId($array['option']['scenario_id']);
  $scenarioElement = scenarioElement::byId($array['option']['scenarioElement_id']);
  if (is_object($scenarioElement) && is_object($scenario)) {
    $otherInfo['doIn'] = __('Scénario :', __FILE__) . ' ' . $scenario->getName() . "\n" . str_replace(array('"'), array("'"), $scenarioElement->export());
  }
}
?>

<form class="form-horizontal">
  <fieldset>
    <?php
    if (count($otherInfo) > 0) {
      foreach ($otherInfo as $key => $value) {
        $div = '<div class="form-group">';
        $div .= '<label class="col-sm-2 control-label">' . $key . '</label>';
        $div .= '<div class="col-sm-10">';
        if (is_array($value)) {
          $div .= '<textarea class="form-control ta_autosize" rows="3" disabled>';
          $div .= json_encode($value);
          $div .= '</textarea>';
        } else if (strpos($value, "\n")) {
          $div .= '<pre disabled>';
          $div .= $value;
          $div .= '</pre>';
        } else {
          $div .= '<input class="form-control" disabled value="' . $value . '" />';
        }
        $div .= '</div>';
        $div .= '</div>';
      }
      $div .= '<hr/>';
      echo $div;
    }
    foreach ($array as $key => $value) {
      $div = '<div class="form-group">';
      $div .= '<label class="col-sm-2 control-label">' . $key . '</label>';
      $div .= '<div class="col-sm-10">';
      if (is_array($value)) {
        $div .= '<textarea class="form-control ta_autosize" rows="3" disabled>';
        $div .= json_encode($value);
        $div .= '</textarea>';
      } else if (strpos($value, "\n")) {
        $div .= '<pre disabled>';
        $div .= $value;
        $div .= '</pre>';
      } else {
        $div .= '<input class="form-control" disabled value="' . $value . '" />';
      }
      $div .= '</div>';
      $div .= '</div>';
      echo $div;
    }
    ?>
  </fieldset>
</form>

<script>
  jeedomUtils.taAutosize()
</script>