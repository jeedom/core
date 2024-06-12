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
$count = array('history' => config::getHistorizedCmdNum(), 'timeline' => config::getTimelinedCmdNum());
$cmds = cmd::all();
sendVarToJs('jeephp2js.md_cmdConfigureHistory_numCmds', count($cmds));
?>

<div id="md_cmdConfigureHistory" data-modalType="md_cmdConfigureHistory">
  <a class="btn btn-success btn-xs pull-right" id="bt_cmdConfigureCmdHistoryApply"><i class="fas fa-check"></i> {{Valider}}</a>
  <div class="center">
    <span class="label label-info">{{Commandes :}} <?php echo ' ' . count($cmds) ?> | {{Commandes historisées :}}<?php echo ' ' . $count['history'] ?> | {{Commandes timeline :}}<?php echo ' ' . $count['timeline'] ?></span>
  </div>
  <br />

  <table id="table_cmdConfigureHistory" class="table table-condensed stickyHead">
    <thead>
      <tr style="margin-top: 20px;">
        <th>{{Nom}}</th>
        <th>{{Plugin}}</th>
        <th>{{Type}}</th>
        <th data-filter="false" data-type="checkbox"><input id="historizeAll" type="checkbox"> {{Historisé}}</th>
        <th data-filter="false" data-type="checkbox"> {{Timeline}}</th>
        <th data-filter="false" data-type="checkbox"> {{Inversée}}</th>
        <th data-filter="false" data-type="select-custom">
        <select id="smoothSelectAll" class="input-xs">
            <option value="none">{{Aucun}}</option>
            <option value="avg">{{Moyenne}}</option>
            <option value="min">{{Minimum}}</option>
            <option value="max">{{Maximum}}</option>
          </select> {{Lissage}}</th>
        <th data-filter="false" data-type="select-custom">
        <select id="purgeSelectAll" class="input-xs">
            <option value="">{{Default}}</option>
            <option value="-1 day">{{1 jour}}</option>
            <option value="-7 days">{{7 jours}}</option>
            <option value="-1 month">{{1 mois}}</option>
            <option value="-3 month">{{3 mois}}</option>
            <option value="-6 month">{{6 mois}}</option>
            <option value="-1 year">{{1 an}}</option>
            <option value="-2 years">{{2 ans}}</option>
            <option value="-3 years">{{3 ans}}</option>
            <option value="never">{{Jamais}}</option>
          </select> {{Purge}}</th>
          <th data-filter="false" data-type="select-custom">
          <select id="smoothTimeSelectAll" class="input-xs">
            <option value="">{{Default}}</option>
            <option value="-1">{{Aucun}}</option>
            <option value="60">{{1 min}}</option>
            <option value="300">{{5 min}}</option>
            <option value="600">{{10 min}}</option>
          </select> {{Limiter à}}</th>
        <th data-filter="false" style="width:100px;">{{Action}}</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $tr = '';
      foreach ($cmds as $cmd) {
        $right = 'x';
        if (!isConnect('admin')) {
          if (strpos($_SESSION['user']->getRights('eqLogic' . $cmd->getEqLogic_id()), 'x') !== false) {
            $right = 'x';
          } elseif (strpos($_SESSION['user']->getRights('eqLogic' . $cmd->getEqLogic_id()), 'r') !== false) {
            $right = 'r';
          } else {
            continue;
          }
        }

        $tr .= '<tr data-change="0" data-cmd_id="' . $cmd->getId() . '" data-right="' . $right . '">';

        //humanName:
        $tr .= '<td>';
        $tr .= '<span class="cmdAttr" data-l1key="humanName">' . str_replace('<br/>', '', $cmd->getHumanName(true, true)) . '</span>';
        $tr .= '<span class="cmdAttr" data-l1key="id" style="display:none;">' . $cmd->getId() . '</span>';
        $tr .= '</td>';

        //plugin:
        $tr .= '<td>';
        if (is_object($cmd->getEqLogic())) {
          $tr .= '<span class="cmdAttr" data-l1key="plugins">' . $cmd->getEqLogic()->getEqType_name() . '</span>';
        }
        $tr .= '</td>';

        //type / subType:
        $tr .= '<td>';
        $tr .= '<span class="cmdAttr">' . $cmd->getType() . ' / ' . $cmd->getSubType() . '</span>';
        $tr .= '</td>';

        //historized:
        $tr .= '<td class="center">';
        if ($cmd->getType() == 'info') {
          $tr .= '<input type="checkbox" class="cmdAttr" data-l1key="isHistorized" ' . (($cmd->getIsHistorized()) ? 'checked' : '') . ' ' .  (($right != 'x') ? 'disabled' : '') .  ' />';
        }
        $tr .= '</td>';

        //timeline:
        $tr .= '<td>';
        $tr .= '<input type="checkbox" class="cmdAttr" data-l1key="configuration" data-l2key="timeline::enable" ' . (($cmd->getConfiguration('timeline::enable')) ? 'checked' : '') . ' ' .  (($right != 'x') ? 'disabled' : '') .  ' />';
        $tr .= ' <input class="cmdAttr input-xs form-control" data-l1key="configuration" data-l2key="timeline::folder" value="' . $cmd->getConfiguration('timeline::folder') . '" style="width:80%;display:inline-block" ' .  (($right != 'x') ? 'disabled' : '') .  ' />';
        $tr .= '</td>';

        //Invert:
        $tr .= '<td class="center">';
        if ($cmd->getType() == 'info' && $cmd->getSubType() == 'binary') {
          $tr .= '<input type="checkbox" class="cmdAttr" data-l1key="display" data-l2key="invertBinary"' . (($cmd->getDisplay('invertBinary') == 1) ? 'checked' : '') . ' ' .  (($right != 'x') ? 'disabled' : '') .  ' />';
        }
        $tr .= '</td>';

        //historizeMode
        $tr .= '<td>';
        if ($cmd->getType() == 'info' && $cmd->getSubType() == 'numeric') {
          $confHistorized = $cmd->getConfiguration('historizeMode');
          $tr .= '<div class="form-group">';
          $tr .= '<select class="form-control cmdAttr input-xs" data-l1key="configuration" data-l2key="historizeMode" ' .  (($right != 'x') ? 'disabled' : '') .  '>';
          $tr .= '<option data-sorton="2" value="avg" ' . (($confHistorized == 'avg') ? 'selected' : '') . '>{{Moyenne}}</option>';
          $tr .= '<option data-sorton="4" value="min" ' . (($confHistorized == 'min') ? 'selected' : '') . '>{{Minimum}}</option>';
          $tr .= '<option data-sorton="3" value="max" ' . (($confHistorized == 'max') ? 'selected' : '') . '>{{Maximum}}</option>';
          $tr .= '<option data-sorton="1" value="none" ' . (($confHistorized == 'none') ? 'selected' : '') . '>{{Aucun}}</option>';
          $tr .= '</select>';
        }
        $tr .= '</td>';

        //historyPurge
        $tr .= '<td>';
        if ($cmd->getType() == 'info') {
          $confHistoryPurge = $cmd->getConfiguration('historyPurge');
          $tr .= '<select class="form-control cmdAttr input-xs" data-l1key="configuration" data-l2key="historyPurge" ' .  (($right != 'x') ? 'disabled' : '') .  '>';
          $tr .= '<option data-sorton="0" value="" ' . (($confHistoryPurge == '') ? 'selected' : '') . '>{{Default}}</option>';
          $tr .= '<option data-sorton="1" value="-1 day" ' . (($confHistoryPurge == '-1 day') ? 'selected' : '') . '>{{1 jour}}</option>';
          $tr .= '<option data-sorton="2" value="-7 days" ' . (($confHistoryPurge == '-7 days') ? 'selected' : '') . '>{{7 jours}}</option>';
          $tr .= '<option data-sorton="3" value="-1 month" ' . (($confHistoryPurge == '-1 month') ? 'selected' : '') . '>{{1 mois}}</option>';
          $tr .= '<option data-sorton="4" value="-3 month" ' . (($confHistoryPurge == '-3 month') ? 'selected' : '') . '>{{3 mois}}</option>';
          $tr .= '<option data-sorton="5" value="-6 month" ' . (($confHistoryPurge == '-6 month') ? 'selected' : '') . '>{{6 mois}}</option>';
          $tr .= '<option data-sorton="6" value="-1 year" ' . (($confHistoryPurge == '-1 year') ? 'selected' : '') . '>{{1 an}}</option>';
          $tr .= '<option data-sorton="7" value="-2 years" ' . (($confHistoryPurge == '-2 years') ? 'selected' : '') . '>{{2 ans}}</option>';
          $tr .= '<option data-sorton="8" value="-3 years" ' . (($confHistoryPurge == '-3 years') ? 'selected' : '') . '>{{3 ans}}</option>';
          $tr .= '<option data-sorton="9" value="never" ' . (($confHistoryPurge == 'never') ? 'selected' : '') . '>{{Jamais}}</option>';
          $tr .= '</select>';
        }
        $tr .= '</td>';

        //smoothTime
        $tr .= '<td>';
        if ($cmd->getType() == 'info') {
          $confHistoryPurge = $cmd->getConfiguration('history::smooth');
          $tr .= '<select class="form-control cmdAttr input-xs" data-l1key="configuration" data-l2key="history::smooth" ' .  (($right != 'x') ? 'disabled' : '') .  '>';
          $tr .= '<option data-sorton="0"  value="" ' . (($confHistoryPurge == '') ? 'selected' : '') . '>{{Default}}</option>';
          $tr .= '<option data-sorton="1"  value="-1" ' . (($confHistoryPurge == '-1') ? 'selected' : '') . '>{{Aucun}}</option>';
          $tr .= '<option data-sorton="2" value="60" ' . (($confHistoryPurge == '60') ? 'selected' : '') . '>{{1 min}}</option>';
          $tr .= '<option data-sorton="3" value="300" ' . (($confHistoryPurge == '300') ? 'selected' : '') . '>{{5 min}}</option>';
          $tr .= '<option data-sorton="4" value="600" ' . (($confHistoryPurge == '600') ? 'selected' : '') . '>{{10 min}}</option>';
          $tr .= '</select>';
        }
        $tr .= '</td>';

        //Actions:
        $tr .= '<td>';
        if ($right == 'x') {
          $tr .= '<a class="btn btn-default btn-xs pull-right cursor bt_configureHistoryAdvanceCmdConfiguration" data-id="'  . $cmd->getId() . '" title="{{Configuration de la commande}}"><i class="fas fa-cogs"></i></a>';
        }
        if ($cmd->getType() == 'info') {
          $tr .= '<a class="btn btn-default btn-xs pull-right cursor bt_configureHistoryExportData" data-id="' . $cmd->getId() . '" title="{{Exporter la commande}}"><i class="fas fa-share export"></i></a>';
        }
        $tr .= '</td>';
        $tr .= '</tr>';
      }
      echo $tr;
      ?>
    </tbody>
  </table>
</div>

<script>
if (!jeeFrontEnd.md_cmdConfigureHistory) {
  jeeFrontEnd.md_cmdConfigureHistory = {
    vDataTable: null,
    init: function() {
      this.tableConfig = document.getElementById('table_cmdConfigureHistory')
      this.modal = this.tableConfig.closest('div.jeeDialogMain')

      this.setConfigTable()

      if (jeephp2js.md_cmdConfigureHistory_numCmds < 500) jeedomUtils.initTooltips(this.tableConfig)
      if (jeephp2js.md_cmdConfigureHistory_numCmds < 1500) jeedom.timeline.autocompleteFolder()
    },
    setConfigTable: function() {
      if (jeeFrontEnd.md_cmdConfigureHistory.vDataTable) jeeFrontEnd.md_cmdConfigureHistory.vDataTable.destroy()

      jeeFrontEnd.md_cmdConfigureHistory.vDataTable = new DataTable(jeeFrontEnd.md_cmdConfigureHistory.tableConfig, {
        columns: [
          { select: 0, sort: "asc" },
          { select: [9], sortable: false }
        ],
        paging: true,
        perPage: 20,
        perPageSelect: [20, 30, 40, 50, 100, 250],
      })
    },
    resetChanges: function() {
      jeeFrontEnd.md_cmdConfigureHistory.vDataTable.table.rows.forEach(_row => {
        _row.node.setAttribute('data-change', '0')
      })
    },
    saveConfig: function(event) {
      var cmds = []
      if (jeeFrontEnd.md_cmdConfigureHistory.vDataTable) {
        jeeFrontEnd.md_cmdConfigureHistory.vDataTable.table.rows.forEach(_tr => {
          if (_tr.node.getAttribute('data-change') == '1') {
            cmds.push(_tr.node.getJeeValues('.cmdAttr')[0])
          }
        })
      } else {
        this.tableConfig.tBodies[0].querySelectorAll('tr[data-right=x]').forEach(_tr => {
          if (_tr.getAttribute('data-change') == '1') {
            cmds.push(_tr.getJeeValues('.cmdAttr')[0])
          }
        })
      }

      jeedom.cmd.multiSave({
        cmds: cmds,
        error: function(error) {
          jeedomUtils.showAlert({
            attachTo: jeeDialog.get('#md_cmdConfigureHistory', 'dialog'),
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          if (jeeFrontEnd.md_cmdConfigureHistory.vDataTable) {
            jeeFrontEnd.md_cmdConfigureHistory.resetChanges()
          }
          jeedomUtils.showAlert({
            attachTo: jeeDialog.get('#md_cmdConfigureHistory', 'dialog'),
            message: '{{Modifications sauvegardées avec succès}}',
            level: 'success'
          })
        }
      })
    },
  }
}

(function() {// Self Isolation!
  var jeeM = jeeFrontEnd.md_cmdConfigureHistory
  jeeM.init()

  //Manage events outside parents delegations:
  document.getElementById('bt_cmdConfigureCmdHistoryApply')?.addEventListener('click', function(event) {
    jeeFrontEnd.md_cmdConfigureHistory.saveConfig(event)
  })

  /*Events delegations
  */
  document.getElementById('table_cmdConfigureHistory')?.addEventListener('click', function(event) {
    var _target = null
    if (_target = event.target.closest('.bt_configureHistoryAdvanceCmdConfiguration')) {
      jeeDialog.dialog({
        id: 'jee_modal2',
        title: '{{Configuration de la commande}}',
        contentUrl: 'index.php?v=d&modal=cmd.configure&cmd_id=' + _target.getAttribute('data-id')
      })
      return
    }

    if (_target = event.target.closest('.bt_configureHistoryExportData')) {
      window.open('core/php/export.php?type=cmdHistory&id=' + _target.getAttribute('data-id'), "_blank", null)
      return
    }

    if (_target = event.target.closest('.cmdAttr')) {
      _target.closest('tr').setAttribute('data-change', '1')
    }
  })

  document.getElementById('table_cmdConfigureHistory')?.addEventListener('change', function(event) {
    var _target = null
    if (_target = event.target.closest('.cmdAttr')) {
      _target.closest('tr').setAttribute('data-change', '1')
    }

    if (_target = event.target.closest('#historizeAll')) {
      event.stopPropagation()
      event.preventDefault()
      var cells = Array.from(document.querySelectorAll('#table_cmdConfigureHistory input.cmdAttr[data-l1key="isHistorized"]')).filter(c => c.isVisible() && c.disabled == false )
      cells.forEach(_cell => {
        _cell.checked = _target.checked
        _cell.closest('tr').setAttribute('data-change', '1')
      })
      return
    }

    if (_target = event.target.closest('#smoothSelectAll')) {
      event.stopPropagation()
      event.preventDefault()
      var cells = Array.from(document.querySelectorAll('#table_cmdConfigureHistory select.cmdAttr[data-l2key="historizeMode"]')).filter(c => c.isVisible() && c.disabled == false )
      cells.forEach(_cell => {
        _cell.value = _target.value
        _cell.closest('tr').setAttribute('data-change', '1')
      })
      return
    }

    if (_target = event.target.closest('#purgeSelectAll')) {
      event.stopPropagation()
      event.preventDefault()
      var cells = Array.from(document.querySelectorAll('#table_cmdConfigureHistory select.cmdAttr[data-l2key="historyPurge"]')).filter(c => c.isVisible() && c.disabled == false )
      cells.forEach(_cell => {
        _cell.value = _target.value
        _cell.closest('tr').setAttribute('data-change', '1')
      })
      return
    }

    if (_target = event.target.closest('#smoothTimeSelectAll')) {
      event.stopPropagation()
      event.preventDefault()
      var cells = Array.from(document.querySelectorAll('#table_cmdConfigureHistory select.cmdAttr[data-l2key="history::smooth"]')).filter(c => c.isVisible() && c.disabled == false )
      cells.forEach(_cell => {
        _cell.value = _target.value
        _cell.closest('tr').setAttribute('data-change', '1')
      })
      return
    }
  })

})()
</script>
