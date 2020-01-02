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


jeedom.history = function () {
};

jeedom.history.chart = [];

jeedom.history.get = function (_params) {
  var paramsRequired = ['cmd_id', 'dateStart', 'dateEnd'];
  var paramsSpecifics = {
    pre_success: function (data) {
      if (isset(jeedom.cmd.cache.byId[data.result.id])) {
        delete jeedom.cmd.cache.byId[data.result.id];
      }
      return data;
    }
  };
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/cmd.ajax.php';
  paramsAJAX.data = {
    action: 'getHistory',
    id: _params.cmd_id,
    dateStart: _params.dateStart || '',
    dateEnd: _params.dateEnd || ''
  };
  $.ajax(paramsAJAX);
}

jeedom.history.copyHistoryToCmd = function (_params) {
  var paramsRequired = ['source_id', 'target_id'];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/cmd.ajax.php';
  paramsAJAX.data = {
    action: 'copyHistoryToCmd',
    source_id: _params.source_id,
    target_id: _params.target_id
  };
  $.ajax(paramsAJAX);
}

jeedom.history.drawChart = function (_params) {
  $.showLoading();
  if ($.type(_params.dateRange) == 'object') {
    _params.dateRange = json_encode(_params.dateRange);
  }
  _params.option = init(_params.option, {derive: ''});
  var _visible = (isset(_params.visible)) ? _params.visible : true
  $.ajax({
    type: "POST",
    url: "core/ajax/cmd.ajax.php",
    data: {
      action: "getHistory",
      id: _params.cmd_id,
      dateRange:  ($.type(_params.dateRange) == 'object') ? json_encode(_params.dateRange)  || '' : _params.dateRange || '',
      dateStart: _params.dateStart || '',
      dateEnd: _params.dateEnd || '',
      derive: _params.option.derive || '',
      allowZero: init(_params.option.allowZero, 0),
      groupingType : _params.option.groupingType || '',
    },
    dataType: 'json',
    global: _params.global || true,
    error: function (request, status, error) {
      handleAjaxError(request, status, error);
    },
    success: function (data) {
      if (data.state != 'ok') {
        $('#div_alert').showAlert({message: data.result, level: 'danger'});
        return;
      }
      if (data.result.data.length < 1) {
        if(_params.option.displayAlert == false){
          return;
        }
        if(!_params.noError){
          var message = '{{Il n\'existe encore aucun historique pour cette commande :}} ' + data.result.history_name;
          if (init(data.result.dateStart) != '') {
            message += (init(data.result.dateEnd) != '') ?  ' {{du}} ' + data.result.dateStart + ' {{au}} ' + data.result.dateEnd : ' {{à partir de}} ' + data.result.dateStart;
          } else {
            message += (init(data.result.dateEnd) != '') ? ' {{jusqu\'au}} ' + data.result.dateEnd:'';
          }
          $('#div_alert').showAlert({message: message, level: 'danger'});
        }
        return;
      }
      if (isset(jeedom.history.chart[_params.el]) && isset(jeedom.history.chart[_params.el].cmd[_params.cmd_id])) {
        jeedom.history.chart[_params.el].cmd[_params.cmd_id] = null;
      }
      _params.option.graphDerive = (data.result.derive == "1") ? true : false;
      var colors = Highcharts.getOptions().colors
      var seriesNumber = 1
      if (isset(jeedom.history.chart[_params.el])) {
        seriesNumber = jeedom.history.chart[_params.el].chart.series.length
      }
      if (seriesNumber > colors.length){
        seriesNumber = 1
      }
      if(! _params.option.graphColor){
        _params.option.graphColor = colors[seriesNumber-1];
      }
      _params.option.graphStep = (_params.option.graphStep == "1") ? true : false;
      if(isset(data.result.cmd)){
        if (init(_params.option.graphStep) == '') {
          _params.option.graphStep = (data.result.cmd.subType == 'binary') ? true : false;
          if (isset(data.result.cmd.display) && init(data.result.cmd.display.graphStep) != '') {
            _params.option.graphStep = (data.result.cmd.display.graphStep == "0") ? false : true;
          }
        }
        if (init(_params.option.graphType) == '') {
          _params.option.graphType = (isset(data.result.cmd.display) && init(data.result.cmd.display.graphType) != '') ? data.result.cmd.display.graphType : 'line';
        }
        if (init(_params.option.groupingType) == '' && isset(data.result.cmd.display) && init(data.result.cmd.display.groupingType) != '') {
          var split = data.result.cmd.display.groupingType.split('::');
          _params.option.groupingType = {function :split[0],time : split[1] };
        }
      }
      var stacking = (_params.option.graphStack == undefined || _params.option.graphStack == null || _params.option.graphStack == 0) ? null : 'value';
      _params.option.graphStack = (_params.option.graphStack == undefined || _params.option.graphStack == null || _params.option.graphStack == 0) ? Math.floor(Math.random() * 10000 + 2) : 1;
      _params.option.graphScale = (_params.option.graphScale == undefined) ? 0 : parseInt(_params.option.graphScale);
      _params.showLegend = (init(_params.showLegend, true) && init(_params.showLegend, true) != "0") ? true : false;
      _params.showTimeSelector = (init(_params.showTimeSelector, true) && init(_params.showTimeSelector, true) != "0") ? true : false;
      _params.showScrollbar = (init(_params.showScrollbar, true) && init(_params.showScrollbar, true) != "0") ? true : false;
      _params.showNavigator = (init(_params.showNavigator, true) && init(_params.showNavigator, true) != "0") ? true : false;
      
      var legend = {borderColor: 'black',borderWidth: 2,shadow: true};
      legend.enabled = init(_params.showLegend, true);
      if(isset(_params.newGraph) && _params.newGraph == true){
        delete jeedom.history.chart[_params.el];
      }
      var charts = {
        zoomType: 'x',
        renderTo: _params.el,
        alignTicks: false,
        spacingBottom: 5,
        spacingTop: 5,
        spacingRight: 5,
        spacingLeft: 5,
        height : _params.height || null,
        style: {fontFamily: 'Roboto'}
      }
      if(charts.height < 10){
        charts.height = null;
      }
      if(isset(_params.transparentBackground) && _params.transparentBackground == "1"){
        charts.backgroundColor = 'rgba(255, 255, 255, 0)';
      }
      if (isset(jeedom.history.chart[_params.el]) && jeedom.history.chart[_params.el].type == 'pie') {
        _params.option.graphType = 'pie';
      }
      
      if( _params.option.graphType == 'pie'){
        var series = {
          type: _params.option.graphType,
          id: _params.cmd_id,
          cursor: 'pointer',
          data: [{y:data.result.data[data.result.data.length - 1][1], name : (isset(_params.option.name)) ? _params.option.name + ' '+ data.result.unite : data.result.history_name + ' '+ data.result.unite}],
          color: _params.option.graphColor
        };
        if (!isset(jeedom.history.chart[_params.el]) || (isset(_params.newGraph) && _params.newGraph == true)) {
          jeedom.history.chart[_params.el] = {};
          jeedom.history.chart[_params.el].cmd = new Array();
          jeedom.history.chart[_params.el].type = _params.option.graphType;
          jeedom.history.chart[_params.el].chart = new Highcharts.Chart({
            chart: charts,
            title: {
              text: ''
            },
            credits: {
              text: '',
              href: '',
            },
            exporting: {
              enabled: _params.enableExport || ($.mobile) ? false : true
            },
            tooltip: {
              pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b><br/>',
              valueDecimals: 2,
            },
            plotOptions: {
              pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                  enabled: true,
                  format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                  style: {
                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                  }
                },
                showInLegend: true
              }
            },
            series: [series]
          });
        } else {
          jeedom.history.chart[_params.el].chart.series[0].addPoint({y:data.result.data[data.result.data.length - 1][1], name : (isset(_params.option.name)) ? _params.option.name + ' '+ data.result.unite : data.result.history_name + ' '+ data.result.unite, color: _params.option.graphColor});
        }
      }else{
        var dataGrouping = {
          enabled: false
        };
        if(isset(_params.option.groupingType) && jQuery.type(_params.option.groupingType) == 'string' && _params.option.groupingType != ''){
          var split = _params.option.groupingType.split('::');
          _params.option.groupingType = {function :split[0],time : split[1] };
        }
        if(isset(_params.option.groupingType) && isset(_params.option.groupingType.function) && isset(_params.option.groupingType.time)){
          dataGrouping = {
            approximation: _params.option.groupingType.function,
            enabled: true,
            forced: true,
            units: [[_params.option.groupingType.time,[1]]]
          };
        }
        if(data.result.timelineOnly){
          if(!isset(jeedom.history.chart[_params.el]) || !isset(jeedom.history.chart[_params.el].nbTimeline)){
            nbTimeline = 1;
          }else{
            jeedom.history.chart[_params.el].nbTimeline++;
            nbTimeline = jeedom.history.chart[_params.el].nbTimeline;
          }
          
          var series = {
            type: 'flags',
            visible: _visible,
            name: (isset(_params.option.name)) ? _params.option.name + ' '+ data.result.unite : data.result.history_name+ ' '+ data.result.unite,
            data: [],
            id: _params.cmd_id,
            color: _params.option.graphColor,
            shape: 'squarepin',
            cursor: 'pointer',
            y : -30 - 25*(nbTimeline - 1),
            point: {
              events: {
                click: function (event) {
                  var deviceInfo = getDeviceType();
                  if ($.mobile || deviceInfo.type == 'tablet' || deviceInfo.type == 'phone') {
                    return
                  }
                  if($('#md_modal2').is(':visible')){
                    return;
                  }
                  if($('#md_modal1').is(':visible')){
                    return;
                  }
                  var id = this.series.userOptions.id;
                  var datetime = Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x);
                  var value = this.y;
                  bootbox.prompt("{{Edition de la série :}} <b>" + this.series.name + "</b> {{et du point de}} <b>" + datetime + "</b> ({{valeur :}} <b>" + value + "</b>) ? {{Ne rien mettre pour supprimer la valeur}}", function (result) {
                    if (result !== null) {
                      jeedom.history.changePoint({cmd_id: id, datetime: datetime,oldValue:value, value: result});
                    }
                  });
                }
              }
            }
          }
          for(var i in data.result.data){
            series.data.push({
              x : data.result.data[i][0],
              title : data.result.data[i][1]
            });
          }
        }else{
          if (_params.option.graphType == 'areaspline') {
            _params.option.graphType = 'area'
          }
          
          var series = {
            dataGrouping: dataGrouping,
            type: _params.option.graphType,
            visible: _visible,
            id: _params.cmd_id,
            cursor: 'pointer',
            name: (isset(_params.mobile))? data.result.unite : ((isset(_params.option.name)) ? _params.option.name + ' '+ data.result.unite : data.result.history_name+ ' '+ data.result.unite),
            data: data.result.data,
            color: _params.option.graphColor,
            stack: _params.option.graphStack,
            derive: _params.option.graphDerive,
            step: _params.option.graphStep,
            yAxis: _params.option.graphScale,
            stacking : stacking,
            tooltip: {
              valueDecimals: 2
            },
            point: {
              events: {
                click: function (event) {
                  var deviceInfo = getDeviceType();
                  if ($.mobile || deviceInfo.type == 'tablet' || deviceInfo.type == 'phone') {
                    return
                  }
                  if($('#md_modal2').is(':visible')){
                    return;
                  }
                  if($('#md_modal1').is(':visible')){
                    return;
                  }
                  var id = this.series.userOptions.id;
                  var datetime = Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x);
                  var value = this.y;
                  bootbox.prompt("{{Edition de la série :}} <b>" + this.series.name + "</b> {{et du point de}} <b>" + datetime + "</b> ({{valeur :}} <b>" + value + "</b>) ? {{Ne rien mettre pour supprimer la valeur}}", function (result) {
                    if (result !== null) {
                      jeedom.history.changePoint({cmd_id: id, datetime: datetime,oldValue:value, value: result});
                    }
                  });
                }
              }
            }
          };
        }
        if(isset(_params.option.graphZindex)){
          series.zIndex = _params.option.graphZindex;
        }
        
        if (!isset(jeedom.history.chart[_params.el]) || (isset(_params.newGraph) && _params.newGraph == true)) {
          jeedom.history.chart[_params.el] = {};
          jeedom.history.chart[_params.el].cmd = new Array();
          jeedom.history.chart[_params.el].color = seriesNumber - 1;
          jeedom.history.chart[_params.el].nbTimeline = 1;
          
          if(_params.dateRange == '30 min'){
            var dateRange = 1
          }else  if(_params.dateRange == '1 hour'){
            var dateRange = 2
          }else  if(_params.dateRange == '1 day'){
            var dateRange = 3
          }else  if(_params.dateRange == '7 days'){
            var dateRange = 4
          }else  if(_params.dateRange == '1 month'){
            var dateRange = 5
          }else  if(_params.dateRange == '1 year'){
            var dateRange = 6
          }else  if(_params.dateRange == 'all'){
            var dateRange = 0
          }else{
            var dateRange = 4;
          }
          jeedom.history.chart[_params.el].type = _params.option.graphType;
          jeedom.history.chart[_params.el].chart = new Highcharts.StockChart({
            chart: charts,
            credits: {
              text: '',
              href: '',
            },
            navigator: {
              enabled:  _params.showNavigator,
              series: {
                includeInCSVExport: false
              }
            },
            exporting: {
              enabled: _params.enableExport || ($.mobile) ? false : true
            },
            rangeSelector: {
              buttons: [ {
                type: 'all',
                count: 1,
                text: 'Tous'
              },{
                type: 'minute',
                count: 30,
                text: '30m'
              }, {
                type: 'hour',
                count: 1,
                text: 'H'
              }, {
                type: 'day',
                count: 1,
                text: 'J'
              }, {
                type: 'week',
                count: 1,
                text: 'S'
              }, {
                type: 'month',
                count: 1,
                text: 'M'
              }, {
                type: 'year',
                count: 1,
                text: 'A'
              }],
              selected: dateRange,
              inputEnabled: false,
              enabled: _params.showTimeSelector
            },
            legend: legend,
            tooltip: {
              xDateFormat: '%Y-%m-%d %H:%M:%S',
              pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b><br/>',
              valueDecimals: 2,
            },
            yAxis: [{
              format: '{value}',
              showEmpty: false,
              minPadding: 0.001,
              maxPadding: 0.001,
              showLastLabel: true,
            }, {
              opposite: false,
              format: '{value}',
              showEmpty: false,
              gridLineWidth: 0,
              minPadding: 0.001,
              maxPadding: 0.001,
              labels: {
                align: 'left',
                x: 2
              }
            }],
            xAxis: {
              type: 'datetime',
              ordinal: false,
              maxPadding : 0.02,
              minPadding : 0.02
            },
            scrollbar: {
              barBackgroundColor: 'gray',
              barBorderRadius: 7,
              barBorderWidth: 0,
              buttonBackgroundColor: 'gray',
              buttonBorderWidth: 0,
              buttonBorderRadius: 7,
              trackBackgroundColor: 'none', trackBorderWidth: 1,
              trackBorderRadius: 8,
              trackBorderColor: '#CCC',
              enabled: _params.showScrollbar
            },
            series: [series]
          });
        } else {
          jeedom.history.chart[_params.el].chart.addSeries(series);
        }
        jeedom.history.chart[_params.el].cmd[_params.cmd_id] = {option: _params.option, dateRange: _params.dateRange};
      }
      
      var extremes = jeedom.history.chart[_params.el].chart.xAxis[0].getExtremes();
      var plotband = jeedom.history.generatePlotBand(extremes.min,extremes.max);
      for(var i in plotband){
        jeedom.history.chart[_params.el].chart.xAxis[0].addPlotBand(plotband[i]);
      }
      $.hideLoading();
      if (typeof (init(_params.success)) == 'function') {
        _params.success(data.result);
      }
    }
  });
}

jeedom.history.generatePlotBand = function (_startTime, _endTime) {
  var plotBands = [];
  if((_endTime - _startTime) > (7* 86400000)){
    return plotBands;
  }
  var pas = 86400000;
  var offset = 0;
  _startTime = (Math.floor(_startTime / 86400000) * 86400000) - offset;
  while (_startTime < _endTime) {
    var plotBand = {};
    plotBand.color = '#F8F8F8';
    plotBand.from = _startTime;
    plotBand.to = _startTime + pas;
    if(plotBand.to > _endTime){
      plotBand.to = _endTime;
    }
    plotBands.push(plotBand);
    _startTime += 2 * pas;
  }
  return plotBands;
}

jeedom.history.changePoint = function (_params) {
  var paramsRequired = ['cmd_id','datetime','value','oldValue'];
  var paramsSpecifics = {
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function (result) {
      $('#div_alert').showAlert({message: '{{La valeur a été éditée avec succès}}', level: 'success'});
      var serie = null;
      for (var i in jeedom.history.chart) {
        serie = jeedom.history.chart[i].chart.get(_params.cmd_id);
        if (serie != null && serie != undefined) {
          serie.remove();
          serie = null;
          jeedom.history.drawChart({
            cmd_id: _params.cmd_id,
            el: i,
            dateRange: jeedom.history.chart[i].cmd[_params.cmd_id].dateRange,
            option: jeedom.history.chart[i].cmd[_params.cmd_id].option
          });
        }
      }
    }
  };
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/cmd.ajax.php';
  paramsAJAX.data = {
    action: 'changeHistoryPoint',
    cmd_id: _params.cmd_id,
    datetime: _params.datetime,
    value: _params.value,
    oldValue : _params.oldValue
  };
  $.ajax(paramsAJAX);
}
