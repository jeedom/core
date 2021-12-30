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
sendVarToJs('jeephp2js.cmd_id', $id);
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
    resizeDone: null,
    init: function(_cmdIds) {
      this.$divGraph = $('#' + this.__el__)
      this.$pageContainer = $('#md_history')
      this.$divGraph.css('position', 'relative').css('width', '100%')
      delete jeedom.history.chart[this.__el__]
      $.clearDivContent(this.__el__)

      this.modalContent = $('#md_history').parents('.ui-dialog-content.ui-widget-content')
      this.modal = this.modalContent.parents('.ui-dialog.ui-resizable')

      _cmdIds = _cmdIds.split('-')
      _cmdIds = $.unique(_cmdIds)
      _cmdIds = _cmdIds.filter(Boolean)
      this.loadIds = _cmdIds

      this.done = _cmdIds.length
      this.noChart = true

      self = this
      this.loadIds.forEach(function(cmd_id) {
        jeedom.history.drawChart({
          cmd_id: cmd_id,
          el: self.__el__,
          dateRange: 'all',
          dateStart: $('#in_startDate').value(),
          dateEnd:  $('#in_endDate').value(),
          newGraph: false,
          showLegend: (self.loadIds.length > 1) ? true : false,
          height: jQuery(window).height() - 270,
          success: function(data) {
            self.noChart = false
            self.done -= 1
            self.setModal()
          }
        })
      })
    },
    resizeDn: function() {
      if (jeedom.history.chart[this.__el__] === undefined) return
      this.resizeHighChartModal()
      this.modal.data({
        'width': this.modal.width(),
        'height': this.modal.height(),
        'top': this.modal.css('top'),
        'left': this.modal.css('left')
      })
    },
    resizeHighChartModal: function() {
      if (!jeedom.history.chart[this.__el__]) return
      if (!this.$divGraph || !jeedom.history.chart[this.__el__].chart) return
      jeedom.history.chart[this.__el__].chart.setSize(this.modalContent.width(), this.modalContent.height() - this.modalContent.find('#md_history .row').height()-10)
    },
    setModal: function() {
      if (this.done == 0 || this.noChart) {
        //check previous size/pos:
        var datas = this.modal.data()
        if (datas && datas.width && datas.height && datas.top && datas.left) {
          this.modal.width(datas.width).height(datas.height).css({'top': datas.top, 'left': datas.left})
          this.modalContent.width(datas.width-26).height(datas.height-40)
          this.resizeHighChartModal()
        } else if ($(window).width() > 860) {
          width = 800
          height = 560
          this.modal.width(width).height(height)
          this.modal.position({
            my: "center",
            at: "center",
            of: window
          })
          this.modalContent.width(width-26).height(height-40)
        }

        //store size/pos:
        this.modal.find('.ui-draggable-handle').on('mouseup', function(event) {
          jeeFrontEnd.md_history.modal.data({
            'width':jeeFrontEnd.md_history.modal.width(),
            'height':jeeFrontEnd.md_history.modal.height(),
            'top':jeeFrontEnd.md_history.modal.css('top'),
            'left':jeeFrontEnd.md_history.modal.css('left')
          })
        })

        //only one history loaded:
        if (this.loadIds.length == 1) {
          if (isset(jeedom.history.chart[this.__el__]) && isset(jeedom.history.chart[this.__el__].chart)) {
            this.modal.find('.ui-dialog-title').html(this.modal.find('.ui-dialog-title').html() + ' : ' + jeedom.history.chart[this.__el__].chart.series[0].name)
          }
        }
        this.resizeHighChartModal()
      }
    }
  }
}

$.hideAlert()
jeeFrontEnd.md_history.init(jeephp2js.cmd_id)

$(function() {
  jeedomUtils.datePickerInit()
})

//handle resizing:
$(window).resize(function() {
  clearTimeout(jeeFrontEnd.md_history.resizeDone)
  jeeFrontEnd.md_history.resizeDone = setTimeout(function() { jeeFrontEnd.md_history.resizeDn() }, 100)
})

//Modal buttons:
jeeFrontEnd.md_history.$pageContainer.on({
  'click': function(event) {
    jeeFrontEnd.md_history.modal = false
    if ($('#md_modal').is(':visible')) {
      jeeFrontEnd.md_history.modal = $('#md_modal')
    } else if ($('#md_modal2').is(':visible')) {
      jeeFrontEnd.md_history.modal = $('#md_modal2')
    }
    if (jeeFrontEnd.md_history.modal !== false) {
      jeeFrontEnd.md_history.modal.dialog({title: "{{Historique}}"}).load('index.php?v=d&modal=cmd.history&id=' + jeephp2js.cmd_id + '&startDate='+$('#in_startDate').val()+'&endDate='+$('#in_endDate').val()).dialog('open')
    }
  }
}, '#bt_validChangeDate')

jeeFrontEnd.md_history.$pageContainer.on({
  'click': function(event) {
    jeedomUtils.loadPage('index.php?v=d&p=history&cmd_id=' + jeephp2js.cmd_id)
  }
}, '#bt_openInHistory')

</script>