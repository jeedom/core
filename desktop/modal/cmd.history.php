<?php
if (!isConnect()) {
  throw new Exception('{{401 - Accès non autorisé}}');
}
$date = array(
  'start' => init('startDate', date('Y-m-d', strtotime(config::byKey('history::defautShowPeriod') . ' ' . date('Y-m-d')))),
  'end' => init('endDate', date('Y-m-d')),
);
$id = init('id');
$showId = init('showId');
if($id == '' && $showId != ''){
  $id = $showId;
}
sendVarToJs('cmd_id',$id);
sendVarToJs('cmd_show',$showId);
?>
<div class="md_history">
  <div class="row">
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
  <center><div id="div_historyChart"></div></center>
</div>

<script>
var cmdIds = cmd_id.split('-')
cmdIds = $.unique(cmdIds)
cmdIds = cmdIds.filter(Boolean)
var cmd_showName = ''

$(".in_datepicker").datepicker()
$('#ui-datepicker-div').hide()

$('#div_historyChart').css('position', 'relative').css('width', '100%')
//remove any previously loaded history:
if (jeedom.history.chart['div_historyChart'] != undefined) {
  while (jeedom.history.chart['div_historyChart'].chart.series.length > 0) {
    jeedom.history.chart['div_historyChart'].chart.series[0].remove(true)
  }
  delete jeedom.history.chart['div_historyChart']
}

var _showLegend = (cmdIds.length > 1) ? true : false
var done = cmdIds.length
cmdIds.forEach(function(cmd_id) {
  var _visible = (cmd_id == cmd_show) ? true : false
  jeedom.history.drawChart({
    cmd_id: cmd_id,
    el: 'div_historyChart',
    dateRange : 'all',
    dateStart : $('#in_startDate').value(),
    dateEnd :  $('#in_endDate').value(),
    newGraph : false,
    showLegend : _showLegend,
    visible : _visible,
    height : jQuery(window).height() - 270,
    success: function (data) {
      if (cmd_id == cmd_show) {
        cmd_showName = data.history_name
        if (data.unite != '') cmd_showName += ' ' + data.unite
      }
      done -= 1
    }
  })
})

var modalSetter = setInterval(setModal, 100)

function setModal() {
  if (done == 0) {
    clearInterval(modalSetter)

    $('#bt_validChangeDate').on('click', function() {
      var modal = false;
      if ($('#md_modal').is(':visible')) {
        modal = $('#md_modal')
      } else if ($('#md_modal2').is(':visible')) {
        modal = $('#md_modal2')
      }
      if (modal !== false) {
        modal.dialog({title: "{{Historique}}"})
        modal.load('index.php?v=d&modal=cmd.history&id='+cmd_id+'&startDate='+$('#in_startDate').val()+'&endDate='+$('#in_endDate').val()).dialog('open')
      }
    })
    $('#bt_openInHistory').on('click', function() {
      loadPage('index.php?v=d&p=history&cmd_id=' + cmd_id)
    });

    $('.highcharts-legend-item').on('click',function(event) {
      if (!event.ctrlKey && !event.altKey) return
      event.stopImmediatePropagation()
      var chart = $('#div_historyChart').highcharts()
      if (event.altKey) {
        $(chart.series).each(function(idx, item) {
          item.show()
        })
      } else {
        var serieId = $(this).attr("class").split('highcharts-series-')[1].split(' ')[0]
        $(chart.series).each(function(idx, item) {
          item.hide()
        })
        chart.series[serieId].show()
      }
    })

    var modalContent = $('.md_history').parents('.ui-dialog-content.ui-widget-content')
    var modal = modalContent.parents('.ui-dialog.ui-resizable')
    var divHighChart = $('#div_historyChart')

    //check previous size/pos:
    var datas = modal.data()
    if (datas.width && datas.height && datas.top && datas.left) {
      modal.width(datas.width).height(datas.height).css('top',datas.top).css('left',datas.left)
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

    resizeHighChartModal()
    modal.resize(function() {
      modal.data( {'width':modal.width(), 'height':modal.height(), 'top':modal.css('top'), 'left':modal.css('left')} )
      resizeHighChartModal()
    })
    modal.find('.ui-draggable-handle').on('mouseup', function(event) {
      modal.data( {'width':modal.width(), 'height':modal.height(), 'top':modal.css('top'), 'left':modal.css('left')} )
    })

    function resizeHighChartModal() {
      divHighChart.highcharts().setSize( modalContent.width(), modalContent.height() - modalContent.find('.md_history .row').height()-10)
    }
  }
}
</script>
