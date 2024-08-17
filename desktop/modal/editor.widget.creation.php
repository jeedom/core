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
global $JEEDOM_INTERNAL_CONFIG;
?>

<div id="md_widgetCreate">
	<form class="form-horizontal">
		<fieldset>
			<div class="form-group">
				<label class="col-xs-4 control-label">{{Version}}</label>
				<div class="col-xs-8">
					<select id="sel_widgetVersion">
						<option value="dashboard">{{Dashboard}}</option>
						<option value="mobile">{{Mobile}}</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-4 control-label">{{Type}}</label>
				<div class="col-xs-8">
					<select id="sel_widgetType">
						<?php
						foreach ($JEEDOM_INTERNAL_CONFIG['cmd']['type'] as $key => $value) {
							echo '<option value="' . $key . '"><a>' . $value['name'] . '</option>';
						}
						?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-4 control-label">{{Sous-type}}</label>
				<div class="col-xs-8">
					<select id="sel_widgetSubtype">
						<option value="" data-default="1"><a></option>
						<?php
						foreach ($JEEDOM_INTERNAL_CONFIG['cmd']['type'] as $key => $value) {
							foreach ($value['subtype'] as $skey => $svalue) {
								echo '<option data-type="' . $key . '" value="' . $skey . '"><a>' . $svalue['name'] . '</option>';
							}
						}
						?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-4 control-label">{{Nom}}</label>
				<div class="col-xs-8">
					<input id="in_widgetName" class="form-control" />
				</div>
			</div>
		</fieldset>
	</form>
</div>
