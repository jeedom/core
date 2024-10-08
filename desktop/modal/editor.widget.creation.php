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
					<select id="sel_widgetVersion" class="form-control">
						<option value="dashboard">{{Dashboard}}</option>
						<option value="mobile">{{Mobile}}</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-4 control-label">{{Type}}</label>
				<div class="col-xs-8">
					<select id="sel_widgetType" class="form-control" data-l1key="type">
						<?php
						foreach ($JEEDOM_INTERNAL_CONFIG['cmd']['type'] as $key => $value) {
							echo '<option value="' . $key . '">' . $value['name'] . '</option>';
						}
						?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-4 control-label">{{Sous-type}}</label>
				<div class="col-xs-8">
					<?php
					foreach ($JEEDOM_INTERNAL_CONFIG['cmd']['type'] as $key => $value) {
						echo '<select class="form-control selectWidgetSubType" data-l1key="subtype" data-type="' . $key . '">';
						echo '<option value=""></option>';
						foreach ($value['subtype'] as $skey => $svalue) {
							echo '<option data-type="' . $key . '" value="' . $skey . '">' . $svalue['name'] . '</option>';
						}
						echo '</select>';
					}
					?>
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
                          
<script>
/*Events delegations
*/
	document.getElementById('md_widgetCreate').addEventListener('change', function(event) {
		var _target = null
          
		if (_target = event.target.closest('#sel_widgetType[data-l1key="type"]')) {
			document.querySelectorAll('.selectWidgetSubType').unseen()
			document.querySelector('.selectWidgetSubType[data-type="' + event.target.jeeValue() + '"]')?.seen().triggerEvent('change')
		}
	})
	
/*Init SubType
*/
	document.querySelector('#sel_widgetType[data-l1key="type"]')?.triggerEvent('change')
</script>
