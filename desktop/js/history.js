
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

initHistoryTrigger();

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
  while(jeedom.history.chart['div_graph'].chart.series.length > 0){
    jeedom.history.chart['div_graph'].chart.series[0].remove(true);
  }
  delete jeedom.history.chart['div_graph'];
  $(this).closest('.li_history').removeClass('active');
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

$('#bt_openCmdHistoryConfigure').on('click',function(){
  $('#md_modal').dialog({title: "{{Configuration de l'historique des commandes}}"});
  $("#md_modal").load('index.php?v=d&modal=cmd.configureHistory').dialog('open');
});

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

function initHistoryTrigger() {
  $('#sel_chartType').off('change').on('change', function () {
    if(lastId == null){
      return;
    }
    if(lastId.indexOf('#') != -1){
      addChart(lastId,0);
      addChart(lastId,1);
      return;
    }
    $('.li_history[data-cmd_id=' + lastId + ']').removeClass('active');
    addChart(lastId,0);
    jeedom.cmd.save({
      cmd: {id: lastId, display: {graphType: $(this).value()}},
      error: function (error) {
        $('#div_alert').showAlert({message: error.message, level: 'danger'});
      },
      success: function () {
        $('.li_history[data-cmd_id=' + lastId + '] .history').click();
      }
    });
  });
  $('#sel_groupingType').off('change').on('change', function () {
    if(lastId == null){
      return;
    }
    if(lastId.indexOf('#') != -1){
      addChart(lastId,0);
      addChart(lastId,1);
      return;
    }
    $('.li_history[data-cmd_id=' + lastId + ']').removeClass('active');
    addChart(lastId,0);
    jeedom.cmd.save({
      cmd: {id: lastId, display: {groupingType: $(this).value()}},
      error: function (error) {
        $('#div_alert').showAlert({message: error.message, level: 'danger'});
      },
      success: function () {
        $('.li_history[data-cmd_id=' + lastId + '] .history').click();
      }
    });
  });
  $('#cb_derive').off('change').on('change', function () {
    if(lastId == null){
      return;
    }
    if(lastId.indexOf('#') != -1){
      addChart(lastId,0);
      addChart(lastId,1);
      return;
    }
    $('.li_history[data-cmd_id=' + lastId + ']').removeClass('active');
    addChart(lastId,0);
    jeedom.cmd.save({
      cmd: {id: lastId, display: {graphDerive: $(this).value()}},
      error: function (error) {
        $('#div_alert').showAlert({message: error.message, level: 'danger'});
      },
      success: function () {
        $('.li_history[data-cmd_id=' + lastId + '] .history').click();
      }
    });
  });
  $('#cb_step').off('change').on('change', function () {
    if(lastId == null){
      return;
    }
    if(lastId.indexOf('#') != -1){
      addChart(lastId,0);
      addChart(lastId,1);
      return;
    }
    $('.li_history[data-cmd_id=' + lastId + ']').removeClass('active');
    addChart(lastId,0);
    jeedom.cmd.save({
      cmd: {id: lastId, display: {graphStep: $(this).value()}},
      error: function (error) {
        $('#div_alert').showAlert({message: error.message, level: 'danger'});
      },
      success: function () {
        $('.li_history[data-cmd_id=' + lastId + '] .history').click();
      }
    });
  });
}

$('#bt_validChangeDate').on('click',function(){
  $(jeedom.history.chart['div_graph'].chart.series).each(function(i, serie){
    if(!isNaN(serie.options.id)){
      var cmd_id = serie.options.id;
      addChart(cmd_id, 0);
      addChart(cmd_id, 1);
    }
  });
});

function addChart(_cmd_id, _action,_options) {
  if (_action == 0) {
    if (isset(jeedom.history.chart['div_graph']) && isset(jeedom.history.chart['div_graph'].chart) && isset(jeedom.history.chart['div_graph'].chart.series)) {
      $(jeedom.history.chart['div_graph'].chart.series).each(function(i, serie){
        try {
          if(serie.options.id == _cmd_id){
            serie.remove();
          }
        }catch(error) {
        }
      });
    }
    return;
  }
  lastId = _cmd_id
  if(init(_options) == ''){
    _options = {};
    if(_cmd_id.indexOf('#') != 1){
      _options.graphType = $('#sel_chartType').value()
      _options.groupingType = $('#sel_groupingType').value()
      _options.graphStep =  ($('#cb_step').value() == 0) ? false : true;
    }
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
      if(isset(data.cmd) && isset(data.cmd.display)){
        if (init(data.cmd.display.graphStep) != '') {
          $('#cb_step').off().value(init(data.cmd.display.graphStep));
        }
        if (init(data.cmd.display.graphType) != '') {
          $('#sel_chartType').off().value(init(data.cmd.display.graphType));
        }
        if (init(data.cmd.display.groupingType) != '') {
          $('#sel_groupingType').off().value(init(data.cmd.display.groupingType));
        }
        if (init(data.cmd.display.graphDerive) != '') {
          $('#cb_derive').off().value(init(data.cmd.display.graphDerive));
        }
      }
      initHistoryTrigger();
    }
  });
  
}

/**************TIMELINE********************/

$('#bt_tabTimeline').on('click',function(){
  $('#div_visualization').empty();
  displayTimeline();
});

$('#bt_configureTimelineCommand').on('click',function(){
  $('#md_modal').dialog({title: "{{Configuration de l'historique des commandes}}"});
  $("#md_modal").load('index.php?v=d&modal=cmd.configureHistory').dialog('open');
});

$('#bt_configureTimelineScenario').on('click',function(){
  $('#md_modal').dialog({title: "{{Résumé scénario}}"});
  $("#md_modal").load('index.php?v=d&modal=scenario.summary').dialog('open');
});

$('#div_visualization').on('click','.bt_scenarioLog',function(){
  $('#md_modal').dialog({title: "{{Log d'exécution du scénario}}"});
  $("#md_modal").load('index.php?v=d&modal=scenario.log.execution&scenario_id=' + $(this).closest('.scenario').attr('data-id')).dialog('open');
});

$('#div_visualization').on('click','.bt_gotoScenario',function(){
  loadPage('index.php?v=d&p=scenario&id='+ $(this).closest('.scenario').attr('data-id'));
});

$('#div_visualization').on('click','.bt_configureCmd',function(){
  $('#md_modal').dialog({title: "{{Configuration de la commande}}"});
  $('#md_modal').load('index.php?v=d&modal=cmd.configure&cmd_id=' + $(this).closest('.cmd').attr('data-id')).dialog('open');
});

$('#bt_refreshTimeline').on('click',function(){
  displayTimeline();
});

$("#sel_typesTimeline").change(function(){
  displayTimeline();
});

$("#sel_objectsTimeline").change(function(){
  displayTimeline();
});

$("#sel_categoryTimeline").change(function(){
  displayTimeline();
});

$("#sel_pluginsTimeline").change(function(){
  displayTimeline();
});

$('.bt_timelineZoom').on('click',function(){
  zoom = $(this).attr('data-zoom');
  var end = new Date();
  var start = new Date();
  if(zoom == 'all'){
    timeline.fit();
    return;
  }else if (zoom == 'y'){
    start.setFullYear(end.getFullYear() - 1);
    end.setTime(start.getTime() + 390 * 24 *3600 *1000);
  }else if (zoom == 'm'){
    if(end.getMonth() == 1){
      start.setFullYear(end.getFullYear() - 1);
      start.setMonth(12);
      end.setTime(start.getTime() + 35 * 24 *3600 *1000);
    }else{
      start.setMonth(end.getMonth() - 1);
      end.setTime(start.getTime() + 33 * 24 *3600 *1000);
    }
  }else if (zoom == 'w'){
    start.setTime(end.getTime() - 7 * 24 *3600 * 1000);
    end.setTime(start.getTime() + 7.5 * 24 *3600 *1000);
  }else if (zoom == 'd'){
    start.setTime(end.getTime() - 1 * 24 *3600 * 1000);
    end.setTime(start.getTime() + 1.1 * 24 *3600 *1000);
  }else if (zoom == 'h'){
    start.setTime(end.getTime() -  3600 * 1000);
    end.setTime(start.getTime() + 3700 *1000);
  }
  timeline.setWindow(start,end);
});

timeline = null;

function displayTimeline(){
  var typefilter = $("#sel_typesTimeline").value();
  var pluginfilter = $("#sel_pluginsTimeline").value();
  var categoryfilter = $("#sel_categoryTimeline").value();
  var objectfilter = $("#sel_objectsTimeline").value();
  var end = new Date();
  var start = new Date();
  start.setTime(end.getTime() -  3600 * 1000);
  end.setTime(start.getTime() + 3700 *1000);
  jeedom.getTimelineEvents({
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function (data) {
      if(timeline != null){
        windowTimeline = timeline.getWindow()
        end=windowTimeline.end
        start = windowTimeline.start
        timeline.destroy()
      }
      data_item = [];
      id = 0;
      for(var i in data){
        if (typefilter != 'all' && data[i].type != typefilter) {
          continue;
        }
        if (pluginfilter != 'all' && data[i].plugins != pluginfilter && typefilter != 'scenario') {
          continue;
        }
        if (objectfilter != 'all' && data[i].object != objectfilter) {
          continue;
        }
        if (categoryfilter != 'all' && typefilter != 'scenario'){
          var hascat =0;
          for (var category in data[i].category){
            if (category == categoryfilter && data[i].category[category] == 1) {
              hascat += 1;
            }
          }
          if (hascat==0){
            continue;
          }
        }
        item = {id : id,start : data[i].date,content : data[i].html,group : data[i].group,title:data[i].date};
        id++;
        data_item.push(item);
      }
      var items = new vis.DataSet(data_item);
      var options = {
        groupOrder:'content',
        verticalScroll: true,
        zoomKey: 'ctrlKey',
        orientation : 'top',
        maxHeight: $('body').height() - $('header').height() - 75
      };
      timeline = new vis.Timeline(document.getElementById('div_visualization'),items,options);
      timeline.setWindow(start,end);
    }
  });
}
