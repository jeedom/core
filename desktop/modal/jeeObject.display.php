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
	throw new Exception(__('La classe demandée n\'existe pas : ', __FILE__) . $class);
}
if (!method_exists($class, 'byId')) {
	throw new Exception(__('La classe demandée n\'a pas de méthode byId : ', __FILE__) . $class);
}

$object = $class::byId(init('id'));
if (!is_object($object)) {
	throw new Exception(__('L\'objet n\'existe pas : ', __FILE__) . $class);
}

$array = utils::o2a($object);
if (count($array) == 0) {
	throw new Exception(__('L\'objet n\'a aucun élément : ', __FILE__) . print_r($array, true));
}
?>

<form class="form-horizontal">
	<fieldset>
		<?php
foreach ($array as $key => $value) {
	echo '<div class="form-group">';
	echo '<label class="col-sm-2 control-label">' . $key . '</label>';
	echo '<div class="col-sm-10">';
	if (is_array($value)) {
		echo '<textarea class="form-control" rows="3" disabled>';
		echo json_encode($value);
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