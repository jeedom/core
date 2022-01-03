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
sendVarToJs('cmd_id',$id);
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
delete jeedom.history.chart['div_modalGraph']
var cmdIds = cmd_id.split('-')
cmdIds = $.unique(cmdIds)
cmdIds = cmdIds.filter(Boolean)

$('#div_modalGraph').css('position', 'relative').css('width', '100%')
//remove any previously loaded history:
jeedom.history.emptyChart('div_modalGraph')

$.hideAlert()
var noChart = true
setModal()
jeedomUtils.datePickerInit()

var _showLegend = (cmdIds.length > 1) ? true : false
var done = cmdIds.length

$(function() {
  cmdIds.forEach(function(cmd_id) {
    jeedom.history.drawChart({
      cmd_id: cmd_id,
      el: 'div_modalGraph',
      dateRange : 'all',
      dateStart : $('#in_startDate').value(),
      dateEnd :  $('#in_endDate').value(),
      newGraph : false,
      showLegend : _showLegend,
      height : jQuery(window).height() - 270,
      success: function(data) {
        noChart = false
        done -= 1
        setModal()
      }
    })
  })
})


function setModal() {
  if (done == 0 || noChart) {
    var md_modal = $('#md_history').parents('.ui-dialog-content.ui-widget-content')
    $('#bt_validChangeDate').off('click').on('click', function() {
      $('div_modalGraph').remove()
      md_modal.dialog({title: "{{Historique}}"}).load('index.php?v=d&modal=cmd.history&id='+cmd_id+'&startDate='+$('#in_startDate').val()+'&endDate='+$('#in_endDate').val()).dialog('open')
    })

    $('#bt_openInHistory').off('click').on('click', function() {
      jeedomUtils.loadPage('index.php?v=d&p=history&cmd_id=' + cmd_id)
    });

    var modalContent = $('.md_history').parents('.ui-dialog-content.ui-widget-content')
    var modal = modalContent.parents('.ui-dialog.ui-resizable')
    var divHighChart = $('#div_modalGraph')
    var chart = divHighChart.highcharts()

    //check previous size/pos:
    var datas = modal.data()
    if (datas && datas.width && datas.height && datas.top && datas.left) {
      modal.width(datas.width).height(datas.height).css({'top': datas.top, 'left': datas.left})
      modalContent.width(datas.width-26).height(datas.height-40)
      resizeHighChartModal()
    } else if ($(window).width() > 860) {
      width = 800
      height = 560
      modal.width(width).height(height)
      modal.position({
        my: "center",
        at: "center",
        of: window
      })
      modalContent.width(width-26).height(height-40)
    }

    //handle resizing:
    var resizeDone
    function resizeDn() {
      modal.data( {'width':modal.width(), 'height':modal.height(), 'top':modal.css('top'), 'left':modal.css('left')} )
      resizeHighChartModal()
    }
    md_modal.off('dialogresize').on('dialogresize', function() {
      clearTimeout(resizeDone);
      resizeDone = setTimeout(resizeDn, 100);
    })

    //store size/pos:
    modal.find('.ui-draggable-handle').on('mouseup', function(event) {
      modal.data( {'width':modal.width(), 'height':modal.height(), 'top':modal.css('top'), 'left':modal.css('left')} )
    })

    //only one history loaded:
    if (cmdIds.length == 1) {
      if (chart) {
        modal.find('.ui-dialog-title').html(modal.find('.ui-dialog-title').html() + ' : ' + chart.series[0].name)
      }
    }

    function resizeHighChartModal() {
      if (!divHighChart || !chart) {
        return
      }
      chart.setSize( modalContent.width(), modalContent.height() - modalContent.find('.md_history .row').height()-10)
    }

    resizeHighChartModal()
  }
}

</script>
