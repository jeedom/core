
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

jeedom.history.drawChart = function (_params) {
    $.showLoading();
    if ($.type(_params.dateRange) == 'object') {
        _params.dateRange = json_encode(_params.dateRange);
    }
    _params.option = init(_params.option, {derive: ''});
    $.ajax({// fonction permettant de faire de l'ajax
        type: "POST", // methode de transmission des données au fichier php
        url: "core/ajax/cmd.ajax.php", // url du fichier php
        data: {
            action: "getHistory",
            id: _params.cmd_id,
            dateRange: _params.dateRange || '',
            dateStart: _params.dateStart || '',
            dateEnd: _params.dateEnd || '',
            derive: _params.option.derive || '',
            allowZero: init(_params.option.allowZero, 1)
        },
        dataType: 'json',
        global: _params.global || true,
        error: function (request, status, error) {
            handleAjaxError(request, status, error);
        },
        success: function (data) { // si l'appel a bien fonctionné 
            if (data.state != 'ok') {
                $('#div_alert').showAlert({message: data.result, level: 'danger'});
                return;
            }
            if (data.result.data.length < 1) {
                var message = '{{Il n\'existe encore aucun historique pour cette commande :}} ' + data.result.history_name;
                if (init(data.result.dateStart) != '') {
                    if (init(data.result.dateEnd) != '') {
                        message += ' {{du}} ' + data.result.dateStart + ' {{au}} ' + data.result.dateEnd;
                    } else {
                        message += ' {{à partir de}} ' + data.result.dateStart;
                    }
                } else {
                    if (init(data.result.dateEnd) != '') {
                        message += ' {{jusqu\'au}} ' + data.result.dateEnd;
                    }
                }
                $('#div_alert').showAlert({message: message, level: 'danger'});
                return;
            }
            if (isset(jeedom.history.chart[_params.el]) && isset(jeedom.history.chart[_params.el].cmd[parseInt(_params.cmd_id)])) {
                jeedom.history.chart[_params.el].cmd[parseInt(_params.cmd_id)] = null;
            }


            if (isset(jeedom.history.chart[_params.el])) {
                _params.option.graphColor = init(_params.option.graphColor, Highcharts.getOptions().colors[init(jeedom.history.chart[_params.el].color, 0)]);
            } else {
                _params.option.graphColor = init(_params.option.graphColor, Highcharts.getOptions().colors[0]);
            }

            if (init(_params.option.graphStep) == '') {
                if (isset(data.result.cmd.display) && init(data.result.cmd.display.graphStep) != '') {
                    _params.option.graphStep = (data.result.cmd.display.graphStep == "0") ? false : true;
                } else {
                    _params.option.graphStep = (data.result.cmd.subType == 'binary') ? true : false;
                }
            } else {
                _params.option.graphStep = (_params.option.graphStep == "1") ? true : false;
            }
            if (init(_params.option.graphType) == '') {
                if (isset(data.result.cmd.display) && init(data.result.cmd.display.graphType) != '') {
                    _params.option.graphType = data.result.cmd.display.graphType;
                } else {
                    _params.option.graphType = 'line';
                }
            }

            _params.option.graphStack = (_params.option.graphStack == undefined || _params.option.graphStack == null || _params.option.graphStack == 0) ? Math.floor(Math.random() * 10000 + 2) : 1;
            _params.option.graphScale = (_params.option.graphScale == undefined) ? 0 : parseInt(_params.option.graphScale);
            _params.showLegend = (init(_params.showLegend, true) && init(_params.showLegend, true) != "0") ? true : false;
            _params.showTimeSelector = (init(_params.showTimeSelector, true) && init(_params.showTimeSelector, true) != "0") ? true : false;
            _params.showScrollbar = (init(_params.showScrollbar, true) && init(_params.showScrollbar, true) != "0") ? true : false;

            var series = {
                dataGrouping: {
                    enabled: false
                },
                type: _params.option.graphType,
                id: parseInt(_params.cmd_id),
                cursor: 'pointer',
                name: (isset(_params.option.name)) ? _params.option.name : data.result.history_name,
                data: data.result.data,
                color: _params.option.graphColor,
                stack: _params.option.graphStack,
                step: _params.option.graphStep,
                yAxis: _params.option.graphScale,
                tooltip: {
                    valueDecimals: 2
                },
                point: {
                    events: {
                        click: function (event) {
                            var deviceInfo = getDeviceType();
                            if (!$.mobile && deviceInfo.type != 'tablet' && deviceInfo.type != 'phone') {
                                var id = this.series.userOptions.id;
                                var datetime = Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x);
                                var value = this.y;
                                bootbox.prompt("{{Edition de la série :}} <b>" + this.series.name + "</b> {{et du point de}} <b>" + datetime + "</b> ({{valeur :}} <b>" + value + "</b>) ? {{Ne rien mettre pour supprimer la valeur}}", function (result) {
                                    if (result !== null) {
                                        jeedom.history.changePoint({cmd_id: id, datetime: datetime, value: result});
                                    }
                                });
                            }
                        }
                    }
                }
            };
            var legend = {
                borderColor: 'black',
                borderWidth: 2,
                shadow: true
            };
            legend.enabled = init(_params.showLegend, true);
            if(isset(_params.newGraph) && _params.newGraph == true){
                delete jeedom.history.chart[_params.el];
            }


            if (!isset(jeedom.history.chart[_params.el]) || (isset(_params.newGraph) && _params.newGraph == true)) {
                jeedom.history.chart[_params.el] = {};
                jeedom.history.chart[_params.el].cmd = new Array();
                jeedom.history.chart[_params.el].color = 0;

                var dateRange = 3;
                switch (_params.dateRange) {
                    case '30 min' :
                        dateRange = 0
                        break;
                    case '1 hour' :
                        dateRange = 1
                        break;
                    case '1 day' :
                        dateRange = 2
                        break;
                    case '7 days' :
                        dateRange = 3
                        break;
                    case '1 month' :
                        dateRange = 4
                        break;
                    case '1 year' :
                        dateRange = 5
                        break;
                    case 'all' :
                        dateRange = 6
                        break;
                }

                jeedom.history.chart[_params.el].chart = new Highcharts.StockChart({
                    chart: {
                        zoomType: 'x',
                        renderTo: _params.el,
                        alignTicks: false,
                        spacingBottom: 5,
                        spacingTop: 5,
                        spacingRight: 5,
                        spacingLeft: 5,
                    },
                    credits: {
                        text: '',
                        href: '',
                    },
                   /* navigator: {
                        enabled: false
                    },*/
                    rangeSelector: {
                        buttons: [{
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
                            }, {
                                type: 'all',
                                count: 1,
                                text: 'Tous'
                            }],
                        selected: dateRange,
                        inputEnabled: false,
                        enabled: _params.showTimeSelector
                    },
                    legend: legend,
                    tooltip: {
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
            jeedom.history.chart[_params.el].cmd[parseInt(_params.cmd_id)] = {option: _params.option, dateRange: _params.dateRange};
            jeedom.history.chart[_params.el].color++;
            if (jeedom.history.chart[_params.el].color > 9) {
                jeedom.history.chart[_params.el].color = 0;
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
    var pas = 43200000;
    var offset = 14400000; //Debut du jour - 4 (soit 20h)
    _startTime = (Math.floor(_startTime / 86400000) * 86400000) - offset;
    while (_startTime < _endTime) {
        var plotBand = {};
        plotBand.color = '#E6E6E6';
        plotBand.from = _startTime;
        plotBand.to = _startTime + pas;
        plotBands.push(plotBand);
        _startTime += 2 * pas;
    }
    return plotBands;
}

jeedom.history.changePoint = function (_params) {
    var paramsRequired = ['cmd_id'];
    var paramsSpecifics = {
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (result) {
            $('#div_alert').showAlert({message: '{{La valeur a été éditée avec succès}}', level: 'success'});
            var serie = null;
            for (var i in jeedom.history.chart) {
                serie = jeedom.history.chart[i].chart.get(parseInt(_params.cmd_id));
                if (serie != null && serie != undefined) {
                    serie.remove();
                    serie = null;
                    jeedom.history.drawChart({
                        cmd_id: _params.cmd_id,
                        el: i,
                        dateRange: jeedom.history.chart[i].cmd[parseInt(_params.cmd_id)].dateRange,
                        option: jeedom.history.chart[i].cmd[parseInt(_params.cmd_id)].option
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
        value: _params.value
    };
    $.ajax(paramsAJAX);
}
