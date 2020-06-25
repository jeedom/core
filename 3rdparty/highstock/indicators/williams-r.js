/*
 Highstock JS v8.1.2 (2020-06-16)

 Indicator series type for Highstock

 (c) 2010-2019 Wojciech Chmiel

 License: www.highcharts.com/license
*/
(function(a){"object"===typeof module&&module.exports?(a["default"]=a,module.exports=a):"function"===typeof define&&define.amd?define("highcharts/indicators/williams-r",["highcharts","highcharts/modules/stock"],function(c){a(c);a.Highcharts=c;return a}):a("undefined"!==typeof Highcharts?Highcharts:void 0)})(function(a){function c(a,b,l,e){a.hasOwnProperty(b)||(a[b]=e.apply(null,l))}a=a?a._modules:{};c(a,"mixins/reduce-array.js",[],function(){return{minInArray:function(a,b){return a.reduce(function(a,
e){return Math.min(a,e[b])},Number.MAX_VALUE)},maxInArray:function(a,b){return a.reduce(function(a,e){return Math.max(a,e[b])},-Number.MAX_VALUE)},getArrayExtremes:function(a,b,c){return a.reduce(function(a,h){return[Math.min(a[0],h[b]),Math.max(a[1],h[c])]},[Number.MAX_VALUE,-Number.MAX_VALUE])}}});c(a,"indicators/williams-r.src.js",[a["parts/Utilities.js"],a["mixins/reduce-array.js"]],function(a,b){var c=a.isArray;a=a.seriesType;var e=b.getArrayExtremes;a("williamsr","sma",{params:{period:14}},
{nameBase:"Williams %R",getValues:function(a,b){b=b.period;var k=a.xData,h=(a=a.yData)?a.length:0,m=[],n=[],p=[],d;if(!(k.length<b)&&c(a[0])&&4===a[0].length){for(d=b-1;d<h;d++){var f=a.slice(d-b+1,d+1);var g=e(f,2,1);f=g[0];g=g[1];var l=a[d][3];f=(g-l)/(g-f)*-100;k[d]&&(m.push([k[d],f]),n.push(k[d]),p.push(f))}return{values:m,xData:n,yData:p}}}});""});c(a,"masters/indicators/williams-r.src.js",[],function(){})});
//# sourceMappingURL=williams-r.js.map