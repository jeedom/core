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
<div class="alert alert-info">Attention : Pour des raisons de performance, les informations ci-dessous ne sont pas mises à jour en temps réel, utilisez le bouton Rafraichir avec parcimonie.
	<a class="btn btn-xs btn-success pull-right roundedLeft" id="bt_cacheAdd" style="margin-bottom: 5px;"><i class="fas fa-plus-circle"></i> {{Ajouter}} </a>
	<a class="btn btn-xs pull-right roundedRight" id="bt_cacheRefresh" style="margin-bottom: 5px;"><i class="fas fa-sync-alt"></i> {{Rafraichir}} </a>
</div>
<form class="form-horizontal">
	<table id="tableCache" class="table table-bordered table-condensed tablesorter">
		<thead>
			<tr>
				<th>{{Clé}}</th>
				<th>{{Valeur}}</th>
				<th>{{Options}}</th>
				<th>{{Life Time}}</th>
				<th>{{Date Time}}</th>
				<th data-sorter="false" data-filter="false">{{Action}}</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$c = cache::stats(true);
			foreach ($c["details"] as $key) {
				$k = cache::byKey($key);
				$tr = '<tr data-key="' . $key . '">';
				$tr .= '<td>' . $key . '</td>';
				$tr .= '<td><textarea class="cmdAttr form-control input-sm" id="cacheVal">';
				$v = $k->getValue();
				if (is_int($v) || is_string($v))
					$tr .= $v;
				else
					$tr .= json_encode($v);
				$tr .= '</textarea></td>';
				$tr .= '<td><textarea class="cmdAttr form-control input-sm" id="cacheOpt">';
				$opt = $k->getOptions();
				if (is_array($opt) && count($opt) != 0) {
					$tr .= json_encode($opt, JSON_UNESCAPED_UNICODE);
				}
				$tr .= '</textarea></td>';
				$tr .= '<td><textarea class="cmdAttr form-control input-sm" id="cacheLife" style="text-align: right;">' . $k->getLifetime() . '</textarea></td>';
				$tr .= '<td>' . $k->getDatetime() . '</td>';
				$tr .= '<td>';
				$tr .= '<a class="btn btn-xs btn-warning bt_cacheSave" style="width:24px"><i class="fas fa-check-circle" title="{{Sauvegarder}}"></i></a> ';
				$tr .= '<a class="btn btn-xs btn-danger bt_cacheRemove" style="width:24px"><i class="fas fa-trash" title="{{Supprimer}}"></i></a>';
				$tr .= '</td>';
				$tr .= '</tr>';
				echo $tr;
			}
			?>
		</tbody>
	</table>
</form>
<?php include_file("desktop", "cache", "js"); ?>
