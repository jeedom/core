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
$object = jeeObject::byId(init('object_id'));
if (!is_object($object)) {
  throw new Exception('Objet non trouvé : ' . init('object_id'));
}
sendVarToJS('objectInfo', utils::o2a($object));
?>

<div id='div_displayObjectConfigure'>
  <legend>{{Informations}}</legend>
  <div class="row">
    <form class="form-horizontal">
      <fieldset>
        <div class="form-group">
          <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{ID}}</label>
          <div class="col-sm-4">
            <span class="objectAttr label label-primary" data-l1key="id"></span>
          </div>
        </div>

        <div class="form-group">
          <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Nom}}</label>
          <div class="col-sm-4">
            <span class="objectAttr label label-primary" data-l1key="name"></span>
          </div>
        </div>

        <div class="form-group">
          <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Visible}}</label>
          <div class="col-sm-4">
            <span class="objectAttr label label-primary" data-l1key="isVisible"></span>
          </div>
        </div>

      </fieldset>
    </form>
  </div>
</div>

<script>
  $('#div_displayObjectConfigure').setValues(objectInfo, '.objectAttr')
</script>