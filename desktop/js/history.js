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

var chart;
var noChart = 1;
var colorChart = 0;
var lastId = null;
delete jeedom.history.chart['div_graph']

$(function() {
  var cmdIds = getUrlVars('cmd_id')
  if (typeof cmdIds == 'string') {
    cmdIds = cmdIds.split('-')
    if (is_numeric(cmdIds[0])) {
      cmdIds.forEach(function(cmd_id) {
        let li = $('.li_history[data-cmd_id='+cmd_id+']');
        if(li){
          li.find('.history').click();
          $('.displayObject[data-object_id='+li.closest('.cmdList').attr('data-object_id')+']').click();
        }
      })
    }
  }
  setChartOptions()
})

$(window).resize(function() {
  $('#div_graph').highcharts().setSize( $('#div_graph').width(), $('#div_graph').height())
})

function setChartOptions() {
  var _prop = 'disabled'
  if ($('.highcharts-legend-item:not(.highcharts-legend-item-hidden)').length == 1) {
    lastId = $('.highcharts-legend-item:not(.highcharts-legend-item-hidden)').attr('data-cmd_id')
    _prop = false
    var chart = $('#div_graph').highcharts()
    $(chart.series).each(function(idx, serie) {
      if (serie.userOptions.id == lastId) {
        var grouping = serie.userOptions.dataGrouping.enabled
        if (grouping) {
          var groupingType = serie.userOptions.dataGrouping.approximation + '::' + serie.userOptions.dataGrouping.units[0][0]
          $('#sel_groupingType').off().value(groupingType)
        } else {
          $('#sel_groupingType').off().val($('#sel_groupingType option:first').val())
        }
        var type = serie.userOptions.type
        if (type == 'area') type = 'areaspline'
        $('#sel_chartType').off().value(type)
        $('#cb_derive').prop('checked', serie.userOptions.derive)
        $('#cb_step').prop('checked', serie.userOptions.step)
        initHistoryTrigger()
        return false
      }
    })
  } else {
    lastId = null
    $('#sel_groupingType').val($('#sel_groupingType option:first').val())
    $('#sel_chartType').val($('#sel_chartType option:first').val())
  }
  $('#sel_groupingType').prop('disabled', _prop)
  $('#sel_chartType').prop('disabled', _prop)
  $('#cb_derive').prop('disabled', _prop)
  $('#cb_step').prop('disabled', _prop)
}

$('#bt_findCmdCalculHistory').on('click',function(){
  jeedom.cmd.getSelectModal({cmd: {type: 'info',subType : 'numeric',isHistorized : 1}}, function(result) {
    $('#in_calculHistory').atCaret('insert', result.human);
  });
});

$('#bt_displayCalculHistory').on('click',function(){
  addChart($('#in_calculHistory').value(), 1)
});

$('#bt_configureCalculHistory').on('click',function(){
  $('#md_modal').dialog({title: "{{Configuration des formules de calcul}}"});
  $("#md_modal").load('index.php?v=d&modal=history.calcul').dialog('open');
});

$('#bt_clearGraph').on('click',function(){
  if (jeedom.history.chart['div_graph'] === undefined) {
    return
  }
  while(jeedom.history.chart['div_graph'].chart.series.length > 0){
    jeedom.history.chart['div_graph'].chart.series[0].remove(true);
  }
  delete jeedom.history.chart['div_graph'];
  $('#ul_history').find('.li_history.active').removeClass('active');
  setChartOptions()
});

$(".in_datepicker").datepicker();

$(".li_history .history").on('click', function (event) {
  $.hideAlert();
  if ($(this).closest('.li_history').hasClass('active')) {
    $(this).closest('.li_history').removeClass('active');
    addChart($(this).closest('.li_history').attr('data-cmd_id'), 0);
  } else {
    $(this).closest('.li_history').addClass('active');
    addChart($(this).closest('.li_history').attr('data-cmd_id'), 1);
  }
  return false;
});

$("body").delegate("ul li input.filter", 'keyup', function () {
  if ($(this).value() == '') {
    $('.cmdList').hide();
  } else {
    $('.cmdList').show();
  }
});

$(".li_history .remove").on('click', function () {
  var bt_remove = $(this);
  $.hideAlert();
  bootbox.prompt('{{Veuillez indiquer la date (Y-m-d H:m:s) avant laquelle il faut supprimer l\'historique de }} <span style="font-weight: bold ;">' + bt_remove.closest('.li_history').find('.history').text() + '</span> (laissez vide pour tout supprimer) ?', function (result) {
    if (result !== null) {
      emptyHistory(bt_remove.closest('.li_history').attr('data-cmd_id'),result);
    }
  });
});

$('.displayObject').on('click', function () {
  var list = $('.cmdList[data-object_id=' + $(this).attr('data-object_id') + ']');
  if (list.is(':visible')) {
    $(this).find('i.fa').removeClass('fa-arrow-circle-down').addClass('fa-arrow-circle-right');
    list.hide();
  } else {
    $(this).find('i.fa').removeClass('fa-arrow-circle-right').addClass('fa-arrow-circle-down');
    list.show();
  }
});

$(".li_history .export").on('click', function () {
  window.open('core/php/export.php?type=cmdHistory&id=' + $(this).closest('.li_history').attr('data-cmd_id'), "_blank", null);
});

$('#bt_openCmdHistoryConfigure, #bt_openCmdHistoryConfigure2').on('click',function(){
  $('#md_modal').dialog({title: "{{Configuration de l'historique des commandes}}"});
  $("#md_modal").load('index.php?v=d&modal=cmd.configureHistory').dialog('open');
});

$('#bt_validChangeDate').on('click',function(){
  $(jeedom.history.chart['div_graph'].chart.series).each(function(i, serie){
    if(serie.options && !isNaN(serie.options.id)){
      var cmd_id = serie.options.id;
      addChart(cmd_id, 0);
      addChart(cmd_id, 1);
    }
  });
});

function initHistoryTrigger() {
  $('#sel_groupingType').off('change').on('change', function () {
    if(lastId == null){
      return;
    }
    var currentId = lastId
    var groupingType = $(this).value()
    $('.li_history[data-cmd_id=' + currentId + ']').removeClass('active');
    addChart(currentId,0);
    jeedom.cmd.save({
      cmd: {id: currentId, display: {groupingType: groupingType}},
      error: function (error) {
        $('#div_alert').showAlert({message: error.message, level: 'danger'});
      },
      success: function () {
        $('.li_history[data-cmd_id=' + currentId + '] .history').click();
      }
    });
  });

  $('#sel_chartType').off('change').on('change', function () {
    if(lastId == null){
      return;
    }
    var currentId = lastId
    var graphType = $(this).value()
    $('.li_history[data-cmd_id=' + currentId + ']').removeClass('active');
    addChart(currentId,0);
    jeedom.cmd.save({
      cmd: {id: currentId, display: {graphType: graphType}},
      error: function (error) {
        $('#div_alert').showAlert({message: error.message, level: 'danger'});
      },
      success: function () {
        $('.li_history[data-cmd_id=' + currentId + '] .history').click();
      }
    });
  });

  $('#cb_derive').off('change').on('change', function () {
    if(lastId == null){
      return;
    }
    var currentId = lastId
    var graphDerive = $(this).value()
    $('.li_history[data-cmd_id=' + currentId + ']').removeClass('active');
    addChart(currentId,0);
    jeedom.cmd.save({
      cmd: {id: currentId, display: {graphDerive: graphDerive}},
      error: function (error) {
        $('#div_alert').showAlert({message: error.message, level: 'danger'});
      },
      success: function () {
        $('.li_history[data-cmd_id=' + currentId + '] .history').click();
      }
    });
  });

  $('#cb_step').off('change').on('change', function () {
    if(lastId == null){
      return;
    }
    var currentId = lastId
    var graphStep = $(this).value()
    $('.li_history[data-cmd_id=' + currentId + ']').removeClass('active');
    addChart(currentId,0);
    jeedom.cmd.save({
      cmd: {id: currentId, display: {graphStep: graphStep}},
      error: function (error) {
        $('#div_alert').showAlert({message: error.message, level: 'danger'});
      },
      success: function () {
        $('.li_history[data-cmd_id=' + currentId + '] .history').click();
      }
    });
  });

  $('.highcharts-legend-item').off('click').on('click',function(event) {
    if (event.ctrlKey || event.altKey) {
      event.stopImmediatePropagation()
      var chart = $('#div_graph').highcharts()
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
    }
    setChartOptions()
  })
}

function emptyHistory(_cmd_id,_date) {
  $.ajax({
    type: "POST",
    url: "core/ajax/cmd.ajax.php",
    data: {
      action: "emptyHistory",
      id: _cmd_id,
      date: _date
    },
    dataType: 'json',
    error: function (request, status, error) {
      handleAjaxError(request, status, error);
    },
    success: function (data) {
      if (data.state != 'ok') {
        $('#div_alert').showAlert({message: data.result, level: 'danger'});
        return;
      }
      $('#div_alert').showAlert({message: '{{Historique supprimé avec succès}}', level: 'success'});
      li = $('li[data-cmd_id=' + _cmd_id + ']');
      if (li.hasClass('active')) {
        li.find('.history').click();
      }
    }
  });
}

function addChart(_cmd_id, _action, _options) {
  if (_action == 0) {
    if (isset(jeedom.history.chart['div_graph']) && isset(jeedom.history.chart['div_graph'].chart) && isset(jeedom.history.chart['div_graph'].chart.series)) {
      $(jeedom.history.chart['div_graph'].chart.series).each(function(i, serie) {
        try {
          if (serie.options.id == _cmd_id) {
            serie.remove()
            setChartOptions()
          }
        } catch(error) {
        }
      })
    }
    return
  }
  jeedom.history.drawChart({
    cmd_id: _cmd_id,
    el: 'div_graph',
    dateRange : 'all',
    dateStart : $('#in_startDate').value(),
    dateEnd :  $('#in_endDate').value(),
    height : $('#div_graph').height(),
    option : _options,
    success: function (data) {
      $('.highcharts-legend-item').last().attr('data-cmd_id', _cmd_id)
      setChartOptions()
      initHistoryTrigger()
    }
  })
}

/**************TIMELINE********************/

$('#sel_timelineFolder').off('change').on('change',function(){
  displayTimeline();
});

$('#bt_tabTimeline').on('click',function(){
  displayTimeline();
});

$('#table_timeline').on('click','.bt_scenarioLog',function(){
  $('#md_modal').dialog({title: "{{Log d'exécution du scénario}}"});
  $("#md_modal").load('index.php?v=d&modal=scenario.log.execution&scenario_id=' + $(this).closest('.tml-scenario').attr('data-id')).dialog('open');
});

$('#table_timeline').on('click','.bt_gotoScenario',function(){
  loadPage('index.php?v=d&p=scenario&id='+ $(this).closest('.tml-scenario').attr('data-id'));
});

$('#table_timeline').on('click','.bt_configureCmd',function(){
  $('#md_modal').dialog({title: "{{Configuration de la commande}}"});
  $('#md_modal').load('index.php?v=d&modal=cmd.configure&cmd_id=' + $(this).closest('.tml-cmd').attr('data-id')).dialog('open');
});

$('#bt_refreshTimeline').on('click',function(){
  displayTimeline();
});

function displayTimeline(){
  jeedom.timeline.byFolder({
    folder : $('#sel_timelineFolder').value(),
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'})
    },
    success: function (data) {
      data = data.reverse()
      var tr = ''
      for (var i in data) {
        tr += '<tr>'
        tr += '<td>'
        tr += data[i].date
        tr += '</td>'
        tr += '<td>'
        if (data[i].group && data[i].plugins) {
          if (data[i].group == 'action') {
            tr += data[i].type + '&#160&#160<i class="warning fas fa-terminal"></i><span class="hidden">action</span>'
          } else {
            tr += data[i].type + '&#160&#160<i class="info fas fa-info-circle"></i><span class="hidden">info</span>'
          }
          tr += '&#160&#160' + data[i].plugins
        }
        if (data[i].type == 'scenario') {
          tr += data[i].type + '&#160&#160<i class="success jeedom-clap_cinema"></i>'
        }
        tr += '</td>'
        tr += '<td>'
        tr += data[i].html
        tr += '</td>'
        tr += '</tr>'
      }
      $('#table_timeline tbody').empty().append(tr).trigger('update')
      $('#table_timeline').on('sortEnd', function(){
        sepDays()
      })
      $('#timelinetab #table_timeline').find('th[data-column="0"]').trigger('sort').trigger('sort')
    }
  });
}

function sepDays() {
  doIt = false
  if ($('#table_timeline [data-column="0"]').is('[data-sortedby]')) doIt = true

  prevDate = ''
  $('#table_timeline tbody tr').each(function() {
    thisDate = $(this).text().substring(0,10)
    if (doIt && thisDate != prevDate) {
      $(this).style('border-top', '1px dotted var(--txt-color)', 'important')
    } else {
      $(this).removeAttr('style')
    }
    prevDate = thisDate
  })
}