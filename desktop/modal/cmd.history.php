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
$date = array(
  'start' => init('startDate', date('Y-m-d', strtotime(config::byKey('history::defautShowPeriod') . ' ' . date('Y-m-d')))),
  'end' => init('endDate', date('Y-m-d')),
);
$id = init('id');
if (trim($id) == '') {
  $id = init('showId');
}
sendVarToJs('jeephp2js.md_history_cmdId', $id);
?>

<div id="md_history" class="md_history" data-modalType="md_history">
  <button id="bt_toggleOptions" class="btn" style="position: absolute; right: 5px; top: 12px;z-index: 2;"><i class="fas fa-arrow-down"></i></button>
  <div id="div_modalHistoryOptions">
    <div class="options col-lg-4" style="display:none">
      <div class="input-group input-group-sm">
        <input id="in_startDate" class="form-control input-sm in_datepicker roundedLeft" style="width: 90px;" value="<?php echo $date['start'] ?>"/>
        <input id="in_endDate" class="form-control input-sm in_datepicker" style="width: 90px;" value="<?php echo $date['end'] ?>"/>
        <a class="btn btn-success btn-sm roundedRight" id='bt_validChangeDate' title="{{Attention : une trop grande plage de dates peut mettre très longtemps à être calculée ou même ne pas s'afficher.}}"><i class="fas fa-check"></i></a>
      </div>
    </div>
    <div class="options col-lg-8" style="display:none">
      <div class="input-group input-group-sm">
        <a class="btn btn-success btn-sm roundedRight" id='bt_openInHistory' title="{{Ouvrir dans Analyse / Historique.}}"><i class="fas fa-chart-line"></i></a>
        <select class="form-control input input-sm roundedLeft" id="sel_groupingType" style="width: 180px">
          <option value="">Aucun groupement</option>
          <option value="sum::hour">Somme par heure</option>
          <option value="average::hour">Moyenne par heure</option>
          <option value="low::hour">Minimum par heure</option>
          <option value="high::hour">Maximum par heure</option>
          <option value="sum::day">Somme par jour</option>
          <option value="average::day">Moyenne par jour</option>
          <option value="low::day">Minimum par jour</option>
          <option value="high::day">Maximum par jour</option>
          <option value="sum::week">Somme par semaine</option>
          <option value="average::week">Moyenne par semaine</option>
          <option value="low::week">Minimum par semaine</option>
          <option value="high::week">Maximum par semaine</option>
          <option value="sum::month">Somme par mois</option>
          <option value="average::month">Moyenne par mois</option>
          <option value="low::month">Minimum par mois</option>
          <option value="high::month">Maximum par mois</option>
          <option value="sum::year">Somme par année</option>
          <option value="average::year">Moyenne par année</option>
          <option value="low::year">Minimum par année</option>
          <option value="high::year">Maximum par année</option>
        </select>
        <select class="form-control input input-sm" id="sel_chartType" style="width: 80px;">
          <option value="line">Ligne</option>
          <option value="area">Aire</option>
          <option value="column">Barre</option>
        </select>
        <span class="input input-sm">Variation</span><input type="checkbox" id="cb_derive" />
        <span class="input input-sm">Escalier</span><input type="checkbox" id="cb_step" />
      </div>
    </div>
  </div>
  <div id="div_modalGraph" class="chartContainer"></div>
</div>

<script>
if (!jeeFrontEnd.md_history) {
  jeeFrontEnd.md_history = {
    __el__: 'div_modalGraph',
    done: null,
    loadIds: null,
    init: function(_cmdIds) {
      let self = this
      document.getElementById(this.__el__).style.position = 'relative'
      document.getElementById(this.__el__).style.width = '100%'
      delete jeedom.history.chart[this.__el__]
      document.getElementById(this.__el__).empty()

      this.modal = document.getElementById(this.__el__).closest('div.jeeDialogMain')
      this.modalContent = this.modal.querySelector('div.jeeDialogContent')
      this.modalContent.style.overflow = 'hidden'
      jeeDialog.get(this.modal).options.onResize = function(event) {
        jeeFrontEnd.md_history.resizeHighChartModal()
      }

      _cmdIds = [... new Set(_cmdIds.split('-'))]
      this.loadIds = _cmdIds.filter(Boolean)
      this.done = _cmdIds.length

      //check previous size/pos:
      if (window.offsetWidth > 860) {
        var width = 800
        var height = 560
        Object.assign(this.modal.style, {
          width: width + 'px',
          height: height + 'px',
          top: (window.offsetHeight / 2) + (height / 2) + 'px',
          left: (window.offsetWidth / 2) + (width / 2) + 'px'
        })
      }

      this.loadIds.forEach(function(cmd_id) {
        jeedom.history.drawChart({
          cmd_id: cmd_id,
          el: self.__el__,
          dateRange: 'all',
          dateStart: document.getElementById('in_startDate').value,
          dateEnd: document.getElementById('in_endDate').value,
          newGraph: false,
          showLegend: (self.loadIds.length > 1) ? true : false,
          height: window.innerHeight - 270,
          success: function(data) {
            self.done -= 1
            let d = (data && data.cmd && data.cmd.display && self.loadIds.length == 1) ? data.cmd.display : {groupingType:'', graphType: 'area', graphDerive: '0', graphStep: '0'}
            document.getElementById('sel_groupingType').value = (d.groupingType != null ? d.groupingType : '')
            document.getElementById('sel_chartType').value = (d.graphType != null && d.graphType != '' ? d.graphType : 'area')
            document.getElementById('cb_derive').checked = (d.graphDerive == '1')
            document.getElementById('cb_step').checked = (d.graphStep == '1')
            if (self.done == 0) {
              self.setModal()
            }
          }
        })
      })
    },
    resizeHighChartModal: function() {
      if (!jeedom.history.chart[this.__el__]) return
      let hDecay = this.loadIds.length == 1 ? 40 : 35

      //Top options visible ?
      if (document.querySelector('#bt_toggleOptions i').hasClass('fa-arrow-down')) hDecay -= 30
      jeedom.history.chart[this.__el__].chart.setSize(
        this.modalContent.offsetWidth,
        this.modalContent.offsetHeight - hDecay
      )
    },
    setModal: function() {
      document.querySelector('#md_history g.highcharts-range-selector-group')?.unseen()
      document.querySelectorAll('.highcharts-button')?.unseen()

      //only one history loaded:
      if (this.loadIds.length == 1) {
        if (isset(jeedom.history.chart[this.__el__]) && isset(jeedom.history.chart[this.__el__].chart)) {
          let titleEl = this.modal.querySelector('div.jeeDialogTitle > span.title')
          let curTitle = titleEl.innerHTML
          titleEl.innerHTML = curTitle  + ' : ' + jeedom.history.chart[this.__el__].chart.series[0].name
        }
      } else {
        document.getElementById('div_modalHistoryOptions').querySelectorAll('input, select, a').forEach(_ctrl => {
          if (_ctrl.getAttribute('id') != 'bt_openInHistory' && _ctrl.getAttribute('id') != 'in_startDate'  && _ctrl.getAttribute('id') != 'in_endDate' && _ctrl.getAttribute('id') != 'bt_validChangeDate') {
            _ctrl.addClass('disabled')
          }
        })
      }
      this.resizeHighChartModal()
      if (getCookie('md_history_toggleOptions') == 1) this.toggleOptions(true)
    },
    reloadModal: function() {
      let url = 'index.php?v=d&modal=cmd.history&id='
      url += jeephp2js.md_history_cmdId + '&startDate=' + document.getElementById('in_startDate').value + '&endDate=' + document.getElementById('in_endDate').value
      jeeDialog.dialog({
        id: 'md_cmdHistory',
        title: "{{Historique}}",
        contentUrl: url
      })
    },
    toggleOptions: function(_value) {
      let btIcon = document.querySelector('#bt_toggleOptions i')
      if(_value){
        btIcon.removeClass('fa-arrow-down').addClass('fa-arrow-up')
        document.querySelectorAll('#md_history div.options')?.seen()
        document.querySelector('#md_history g.highcharts-range-selector-group')?.seen()
        document.querySelectorAll('.highcharts-button')?.seen()
        jeeFrontEnd.md_history.resizeHighChartModal(true)
      } else {
        btIcon.removeClass('fa-arrow-up').addClass('fa-arrow-down')
        document.querySelectorAll('#md_history div.options')?.unseen()
        document.querySelector('#md_history g.highcharts-range-selector-group')?.unseen()
        document.querySelectorAll('.highcharts-button')?.unseen()
        jeeFrontEnd.md_history.resizeHighChartModal(false)
      }
    },
    saveOptions: function(_option, _value) {
      setCookie('md_history_' + _option, _value, 7)
    },
  }
}

(function() {// Self Isolation!
  jeedomUtils.hideAlert()
  var jeeM = jeeFrontEnd.md_history
  jeeM.init(jeephp2js.md_history_cmdId)

  jeedomUtils.datePickerInit()

  //Modal buttons:
  document.getElementById('bt_toggleOptions').addEventListener('click', function(event) {
    let btIcon = document.querySelector('#bt_toggleOptions i')
    if (btIcon.hasClass('fa-arrow-down')) {
      jeeM.toggleOptions(true)
      jeeM.saveOptions('toggleOptions', 1)
    } else {
      jeeM.toggleOptions(false)
      jeeM.saveOptions('toggleOptions', 0)
    }
  })

  document.getElementById('bt_validChangeDate').addEventListener('click', function(event) {
    jeeM.reloadModal();
  })

  document.getElementById('bt_openInHistory').addEventListener('click', function(event) {
    let url = 'index.php?v=d&p=history&cmd_id=' + jeephp2js.md_history_cmdId
    url += '&startDate=' + document.getElementById('in_startDate').value + '&endDate=' + document.getElementById('in_endDate').value
    jeedomUtils.loadPage(url)
  })

  document.getElementById('sel_groupingType').addEventListener('change', function(event) {
    jeedom.cmd.save({
      cmd: {
        id: jeephp2js.md_history_cmdId,
        display: {
          groupingType: event.target.value
        }
      },
      error: function(error) {
        jeedomUtils.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function() {
        jeeM.reloadModal();
      }
    })
  })
  
  document.getElementById('sel_chartType').addEventListener('change', function(event) {
    jeedom.cmd.save({
      cmd: {
        id: jeephp2js.md_history_cmdId,
        display: {
          graphType: event.target.value
        }
      },
      error: function(error) {
        jeedomUtils.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function() {
        jeeM.reloadModal();
      }
    })
  })

  document.getElementById('cb_derive').addEventListener('change', function(event) {
    jeedom.cmd.save({
      cmd: {
        id: jeephp2js.md_history_cmdId,
        display: {
          graphDerive: (event.target.checked ? '1' : '0')
        }
      },
      error: function(error) {
        jeedomUtils.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function() {
        jeeM.reloadModal();
      }
    })
  })

  document.getElementById('cb_step').addEventListener('change', function(event) {
    jeedom.cmd.save({
      cmd: {
        id: jeephp2js.md_history_cmdId,
        display: {
          graphStep: (event.target.checked ? '1' : '0')
        }
      },
      error: function(error) {
        jeedomUtils.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function() {
        jeeM.reloadModal();
      }
    })
  })

})()
</script>

