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
  <div class="row">
    <div id="div_alertHistory" style="margin: 0 14px; display: none;"></div>
    <div class="col-lg-12">
      <div class="input-group input-group-sm">
        <input id="in_startDate" class="form-control input-sm in_datepicker roundedLeft" style="width: 90px;" value="<?php echo $date['start'] ?>"/>
        <input id="in_endDate" class="form-control input-sm in_datepicker" style="width: 90px;" value="<?php echo $date['end'] ?>"/>
        <a class="btn btn-success btn-sm roundedRight" id='bt_validChangeDate' title="{{Attention : une trop grande plage de dates peut mettre très longtemps à être calculée ou même ne pas s'afficher.}}">
          <i class="fas fa-check"></i>
        </a>
        <a class="btn btn-success btn-sm pull-right" id='bt_openInHistory' title="{{Ouvrir dans Analyse / Historique.}}"><i class="fas fa-chart-line"></i></a>

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
      self = this
      document.getElementById('div_modalGraph').style.position = 'relative'
      document.getElementById('div_modalGraph').style.width = '100%'

      this.$pageContainer = $('#md_history')
      delete jeedom.history.chart[this.__el__]
      document.getElementById(this.__el__).empty()

      this.modal = document.getElementById('div_modalGraph').closest('div.jeeDialogMain')
      this.modalContent = this.modal.querySelector('div.jeeDialogContent')
      this.modalContent.style.overflow = 'hidden'
      if (this.modal.data == undefined) this.modal.data = {}
      this.modal._jeeDialog.options.onResize = function(event) {
        Object.assign(self.modal.data, {
          width: self.modal.style.width,
          height: self.modal.style.height,
          top: self.modal.style.top,
          left: self.modal.style.left
        })
        self.resizeHighChartModal()
      }
      this.modal._jeeDialog.options.onMove = function(event) {
        Object.assign(self.modal.data, {
          width: self.modal.style.width,
          height: self.modal.style.height,
          top: self.modal.style.top,
          left: self.modal.style.left
        })
      }

      _cmdIds = [... new Set(_cmdIds.split('-'))]
      this.loadIds = _cmdIds.filter(Boolean)
      this.done = _cmdIds.length

      //check previous size/pos:
      var datas = this.modal.data
      if (datas && datas.width && datas.height && datas.top && datas.left) {
        Object.assign(this.modal.style, {
          width: datas.width,
          height: datas.height,
          top: datas.top,
          left: datas.left
        })
        this.resizeHighChartModal()
      } else if (window.offsetWidth > 860) {
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
          height: jQuery(window).height() - 270,
          success: function(data) {
            self.done -= 1
            if (self.done == 0) {
              self.setModal()
            }
          }
        })
      })
    },
    resizeHighChartModal: function() {
      if (!jeedom.history.chart[this.__el__]) return
      jeedom.history.chart[this.__el__].chart.setSize(
        this.modalContent.offsetWidth - 0,
        this.modalContent.offsetHeight - 40
      )
    },
    setModal: function() {
      //only one history loaded:
      if (this.loadIds.length == 1) {
        if (isset(jeedom.history.chart[this.__el__]) && isset(jeedom.history.chart[this.__el__].chart)) {
          let titleEl = this.modal.querySelector('div.jeeDialogTitle > span.title')
          let curTitle = titleEl.innerHTML
          titleEl.innerHTML = curTitle  + ' : ' + jeedom.history.chart[this.__el__].chart.series[0].name
        }
      }
      this.resizeHighChartModal()
    }
  }
}

(function() {
  jeedomUtils.hideAlert()
  var jeeM = jeeFrontEnd.md_history
  jeeM.init(jeephp2js.md_history_cmdId)

  jeedomUtils.datePickerInit()

  //Modal buttons:
  jeeM.$pageContainer.on({
    'click': function(event) {
      jeeM.md_modal.dialog({title: "{{Historique}}"}).load('index.php?v=d&modal=cmd.history&id=' + jeephp2js.md_history_cmdId + '&startDate='+$('#in_startDate').val()+'&endDate='+$('#in_endDate').val()).dialog('open')
    }
  }, '#bt_validChangeDate')

  jeeM.$pageContainer.on({
    'click': function(event) {
      jeedomUtils.loadPage('index.php?v=d&p=history&cmd_id=' + jeephp2js.md_history_cmdId + '&startDate=' + $('#in_startDate').val() + '&endDate=' + $('#in_endDate').val())
    }
  }, '#bt_openInHistory')

})()
</script>