/*
 Highcharts JS v8.1.0 (2020-05-05)

 Dot plot series type for Highcharts

 (c) 2010-2019 Torstein Honsi

 License: www.highcharts.com/license
*/
(function(a){"object"===typeof module&&module.exports?(a["default"]=a,module.exports=a):"function"===typeof define&&define.amd?define("highcharts/modules/dotplot",["highcharts"],function(d){a(d);a.Highcharts=d;return a}):a("undefined"!==typeof Highcharts?Highcharts:void 0)})(function(a){function d(a,c,d,p){a.hasOwnProperty(c)||(a[c]=p.apply(null,d))}a=a?a._modules:{};d(a,"modules/dotplot.src.js",[a["parts/Globals.js"],a["parts/Utilities.js"]],function(a,c){var d=c.extend,p=c.objectEach,t=c.pick;c=
c.seriesType;c("dotplot","column",{itemPadding:.2,marker:{symbol:"circle",states:{hover:{},select:{}}}},{markerAttribs:void 0,drawPoints:function(){var a=this,c=a.chart.renderer,k=this.options.marker,l=this.yAxis.transA*a.options.itemPadding,m=this.borderWidth%2?.5:1;this.points.forEach(function(b){var e;var f=b.marker||{};var u=f.symbol||k.symbol,x=t(f.radius,k.radius),v="rect"!==u;b.graphics=e=b.graphics||{};var n=b.pointAttr?b.pointAttr[b.selected?"selected":""]||a.pointAttr[""]:a.pointAttribs(b,
b.selected&&"select");delete n.r;a.chart.styledMode&&(delete n.stroke,delete n["stroke-width"]);if(null!==b.y){b.graphic||(b.graphic=c.g("point").add(a.group));var h=b.y;var w=t(b.stackY,b.y);var q=Math.min(b.pointWidth,a.yAxis.transA-l);for(f=w;f>w-b.y;f--){var g=b.barX+(v?b.pointWidth/2-q/2:0);var r=a.yAxis.toPixels(f,!0)+l/2;a.options.crisp&&(g=Math.round(g)-m,r=Math.round(r)+m);g={x:g,y:r,width:Math.round(v?q:b.pointWidth),height:Math.round(q),r:x};e[h]?e[h].animate(g):e[h]=c.symbol(u).attr(d(g,
n)).add(b.graphic);e[h].isActive=!0;h--}}p(e,function(a,b){a.isActive?a.isActive=!1:(a.destroy(),delete a[b])})})}});a.SVGRenderer.prototype.symbols.rect=function(c,d,k,l,m){return a.SVGRenderer.prototype.symbols.callout(c,d,k,l,m)}});d(a,"masters/modules/dotplot.src.js",[],function(){})});
//# sourceMappingURL=dotplot.js.map