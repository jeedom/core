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
$repos = update::listRepo();
?>

<div id="md_updateAdd" data-modalType="md_updateAdd">
  <legend>{{Source}}</legend>
  <div class="alert alert-danger">{{Attention, il n’y a pas d’assistance de l’équipe}} <?php echo config::byKey('product_name'); ?> {{sur les plugins installés depuis une autre source que le Market}} <?php echo config::byKey('product_name'); ?>. {{De plus, l’installation d’un plugin depuis une autre source que le Market}} <?php echo config::byKey('product_name'); ?> {{entraine la perte globale d’assistance par l’équipe}} <?php echo config::byKey('product_name'); ?>.</div>
  <form class="form-horizontal">
    <fieldset>
      <div class="form-group">
        <label class="col-lg-4 control-label">{{Type de source}}</label>
        <div class="col-lg-8">
          <select class="updateAttr form-control" data-l1key="source">
            <option value="nothing">{{Aucun}}</option>
            <?php
            foreach ($repos as $key => $value) {
              if ($value['configuration'] === false) {
                continue;
              }
              if ($value['scope']['plugin'] === false) {
                continue;
              }
              if (!isset($value['configuration']['parameters_for_add'])) {
                continue;
              }
              if (config::byKey($key . '::enable') == 0) {
                continue;
              }
              $name = (isset($value['configuration']['translate_name'])) ? $value['configuration']['translate_name'] : $value['name'];
              echo '<option value="' . $key . '">' . $name . '</option>';
            }
            ?>
          </select>
        </div>
      </div>
    </fieldset>
  </form>
  <legend>{{Configuration}}</legend>
  <form class="form-horizontal">
    <fieldset>
      <?php
      foreach ($repos as $key => $value) {
        if ($value['configuration'] === false) {
          continue;
        }
        if ($value['scope']['plugin'] === false) {
          continue;
        }
        if (!isset($value['configuration']['parameters_for_add'])) {
          continue;
        }
        $div = '<div class="repoSource repo_' . $key . '" style="display:none;">';
        $div .= '<div class="form-group">';
        $div .= '<label class="col-lg-4 control-label">';
        $div .= '{{ID logique du plugin}}';
        $div .= '</label>';
        $div .= '<div class="col-lg-8">';
        $div .= '<input class="updateAttr form-control" data-l1key="logicalId" />';
        $div .= '</div>';
        $div .= '</div>';
        foreach ($value['configuration']['parameters_for_add'] as $pKey => $parameter) {
          $div .= '<div class="form-group">';
          $div .= '<label class="col-lg-4 control-label">';
          $div .= $parameter['name'];
          $div .= '</label>';
          $div .= '<div class="col-lg-8">';
          $default = (isset($parameter['default'])) ? $parameter['default'] : '';
          switch ($parameter['type']) {
            case 'input':
            $div .= '<input class="updateAttr form-control" data-l1key="configuration" data-l2key="' . $pKey . '" value="' . $default . '" />';
            break;
            case 'number':
            $div .= '<input type="number" class="updateAttr form-control" data-l1key="configuration" data-l2key="' . $pKey . '" value="' . $default . '" />';
            break;
            case 'file':
            $div .= '<input class="updateAttr form-control" data-l1key="configuration" data-l2key="' . $pKey . '" style="display:none;" />';
            $div .= '<span class="btn btn-default btn-file">';
            $div .= '<i class="fas fa-cloud-upload-alt"></i> {{Envoyer un plugin}}<input id="bt_uploadPlugin" data-key="' . $pKey . '" type="file" name="file" style="display : inline-block;">';
            $div .= '</span>';
            break;
          }
          $div .= '</div>';
          $div .= '</div>';
        }

        $div .= '</div>';
        echo $div;
      }
      ?>
      <a class="btn btn-success pull-right" id="bt_repoAddSaveUpdate"><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a>
    </fieldset>
  </form>
</div>

<script>
//Manage events outside parents delegations:
document.getElementById('bt_repoAddSaveUpdate')?.addEventListener('click', function() {
  var source = document.querySelector('.updateAttr[data-l1key="source"]').jeeValue()
  var update = document.querySelectorAll('.repoSource.repo_' + source).getJeeValues('.updateAttr')[0]
  update.source = source
  jeedom.update.save({
    update : update,
    error: function(error) {
      jeedomUtils.showAlert({message: error.message, level: 'danger'})
    },
    success: function() {
      jeedomUtils.showAlert({message: '{{Sauvegarde réussie}}', level: 'success'})
    }
  })
})

/*Events delegations
*/
document.getElementById('md_updateAdd')?.addEventListener('click', function(event) {
  var _target = null
  if (_target = event.target.closest('.updateAttr[data-l1key="source"]')) {
    document.querySelectorAll('#md_updateAdd .repoSource').unseen()
    document.querySelector('#md_updateAdd .repoSource.repo_' + _target.value)?.seen()
    return
  }
})

new jeeFileUploader({
  fileInput: document.getElementById('bt_uploadPlugin'),
  dataType: 'json',
  replaceFileInput: false,
  url: 'core/ajax/update.ajax.php?action=preUploadFile',
  done: function(e, data) {
    if (data.result.state != 'ok') {
      jeedomUtils.showAlert({message: data.result.result, level: 'danger'})
      return
    }
    document.querySelector('.updateAttr[data-l1key="configuration"][data-l2key="' + document.getElementById('bt_uploadPlugin').getAttribute('data-key') + '"]').jeeValue(data.result.result)
  }
})
</script>