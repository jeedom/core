/* DARK SOBRE JS v0.9.12 */
var dark_sobre_config = {
   local_adress: '192.168.1.1' , //entrez votre adresse locale, ex:'192.168.100.1' ou 'jeedom.local'
   local_timeout: 200 ,  //delay en local (1000 correspond à 1 seconde)
   external_timeout: 800 //delay lors d'un accès distant (1000 correspond à 1 seconde)
}
$(document).ready(function(){dark_sobre_config.rgba_timeout=window.location.href.indexOf(dark_sobre_config.local_adress)>-1?dark_sobre_config.local_timeout:dark_sobre_config.external_timeout,dark_sobre_config.end_process=function(){$("#div_mainContainer").css("visibility","visible")},dark_sobre_config.process=function(o,e){setTimeout(function(){$(o).each(function(){$(this).css("background-color",$(this).css("background-color").replace(")",", "+e+")").replace("rgb","rgba"))}),dark_sobre_config.end_process()},dark_sobre_config.rgba_timeout)},window.location.href.indexOf("p=display")>-1?dark_sobre_config.process("div.col-xs-4.object.col-xs-height",.4):window.location.href.indexOf("p=dashboard")>-1||window.location.href.indexOf("p=view")>-1||window.location.href.indexOf("p=panel")>-1?dark_sobre_config.process("div.eqLogic.eqLogic-widget",.4):dark_sobre_config.end_process()});