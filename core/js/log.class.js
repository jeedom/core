
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


 jeedom.log = function () {
 };

 jeedom.log.get = function (_params) {
 	var paramsRequired = ['log'];
 	var paramsSpecifics = {
 		global: _params.global || true,
 	};
 	try {
 		jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
 	} catch (e) {
 		(_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
 		return;
 	}
 	var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
 	var paramsAJAX = jeedom.private.getParamsAJAX(params);
 	paramsAJAX.url = 'core/ajax/log.ajax.php';
 	paramsAJAX.data = {
 		action: 'get',
 		logfile: _params.log,
 	};
 	$.ajax(paramsAJAX);
 }

 jeedom.log.autoupdate = function (_params) {
 	if(!isset(_params['log'])){
 		console.log('[jeedom.log.autoupdate] No logfile');
 		return;
 	}
 	if(!isset(_params['display'])){
 		console.log('[jeedom.log.autoupdate] No display');
 		return;
 	}
 	if (!_params['display'].is(':visible')) {
 		return;
 	}
 	if(isset(_params['control']) && _params['control'].attr('data-state') != 1){
 		return;
 	}
 	jeedom.log.get({
 		log : _params['log'],
 		global : false,
 		success : function(result){
 			var log = '';
 			var regex = /<br\s*[\/]?>/gi;
 			for (var i in result.reverse()) {
 				if(!isset(_params['search']) || _params['search'].value() == '' || result[i][2].toLowerCase().indexOf(_params['search'].value().toLowerCase()) != -1){
 					log += result[i][0]+' - ';
 					log += result[i][2].replace(regex, "\n");
 					log += "\n";
 				}
 			}
 			_params['display'].text(log);
 			_params['display'].scrollTop(_params['display'].height() + 200000);

 			setTimeout(function() {
 				jeedom.log.autoupdate(_params)
 			}, 1000);
 		},
 		error : function(){
 			setTimeout(function() {
 				jeedom.log.autoupdate(_params)
 			}, 1000);
 		},
 	});
 }