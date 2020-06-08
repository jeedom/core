/*
 Highcharts Gantt JS v8.1.0 (2020-05-05)

 StaticScale

 (c) 2016-2019 Torstein Honsi, Lars A. V. Cabrera

 License: www.highcharts.com/license
*/
(function(a){"object"===typeof module&&module.exports?(a["default"]=a,module.exports=a):"function"===typeof define&&define.amd?define("highcharts/modules/static-scale",["highcharts"],function(c){a(c);a.Highcharts=c;return a}):a("undefined"!==typeof Highcharts?Highcharts:void 0)})(function(a){function c(a,b,c,f){a.hasOwnProperty(b)||(a[b]=f.apply(null,c))}a=a?a._modules:{};c(a,"modules/static-scale.src.js",[a["parts/Globals.js"],a["parts/Utilities.js"]],function(a,b){var c=b.addEvent,f=b.defined,g=
b.isNumber,h=b.pick;b=a.Chart;c(a.Axis,"afterSetOptions",function(){var a=this.chart.options&&this.chart.options.chart;!this.horiz&&g(this.options.staticScale)&&(!a.height||a.scrollablePlotArea&&a.scrollablePlotArea.minHeight)&&(this.staticScale=this.options.staticScale)});b.prototype.adjustHeight=function(){"adjustHeight"!==this.redrawTrigger&&((this.axes||[]).forEach(function(a){var b=a.chart,c=!!b.initiatedScale&&b.options.animation,d=a.options.staticScale;if(a.staticScale&&f(a.min)){var e=h(a.brokenAxis&&
a.brokenAxis.unitLength,a.max+a.tickInterval-a.min)*d;e=Math.max(e,d);d=e-b.plotHeight;1<=Math.abs(d)&&(b.plotHeight=e,b.redrawTrigger="adjustHeight",b.setSize(void 0,b.chartHeight+d,c));a.series.forEach(function(a){(a=a.sharedClipKey&&b[a.sharedClipKey])&&a.attr({height:b.plotHeight})})}}),this.initiatedScale=!0);this.redrawTrigger=null};c(b,"render",b.prototype.adjustHeight)});c(a,"masters/modules/static-scale.src.js",[],function(){})});
//# sourceMappingURL=static-scale.js.map