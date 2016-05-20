
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

 jeedom.log.currentAutoupdate = [];

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
 		log: _params.log,
 		jeeNetwork_id: _params.slaveId || ''
 	};
 	$.ajax(paramsAJAX);
 }

 jeedom.log.remove = function (_params) {
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
 		action: 'remove',
 		log: _params.log,
 		jeeNetwork_id: _params.slaveId || ''
 	};
 	$.ajax(paramsAJAX);
 }

 jeedom.log.clear = function (_params) {
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
 		action: 'clear',
 		log: _params.log,
 		jeeNetwork_id: _params.slaveId || ''
 	};
 	$.ajax(paramsAJAX);
 }

 jeedom.log.autoupdate = function (_params) {
 	if(!isset(_params.callNumber)){
 		_params.callNumber = 0;
 	}
 	if(!isset(_params.log)){
 		console.log('[jeedom.log.autoupdate] No logfile');
 		return;
 	}
 	if(!isset(_params.display)){
 		console.log('[jeedom.log.autoupdate] No display');
 		return;
 	}
 	if (!_params['display'].is(':visible')) {
 		return;
 	}
 	if(_params.callNumber > 0 && isset(_params['control']) && _params['control'].attr('data-state') != 1){
 		return;
 	}
 	if(_params.callNumber > 0 && isset(jeedom.log.currentAutoupdate[_params.display.uniqueId().attr('id')]) && jeedom.log.currentAutoupdate[_params.display.uniqueId().attr('id')].log != _params.log){
 		return;
 	}
 	if(_params.callNumber == 0){
 		_params['search'].value('');
 		_params.display.scrollTop(_params.display.height() + 200000);
 		if(_params['control'].attr('data-state') == 0){
 			_params['control'].attr('data-state',1);
 		}
 		_params['control'].off('click').on('click',function(){
 			if($(this).attr('data-state') == 1){
 				$(this).attr('data-state',0);
 				$(this).removeClass('btn-warning').addClass('btn-success');
 				$(this).html('<i class="fa fa-play"></i> {{Reprendre}}');
 			}else{
 				$(this).removeClass('btn-success').addClass('btn-warning');
 				$(this).html('<i class="fa fa-pause"></i> {{Pause}}');
 				$(this).attr('data-state',1);
 				_params.display.scrollTop(_params.display.height() + 200000);
 				jeedom.log.autoupdate(_params);
 			}
 		});

 		_params['search'].off('keypress').on('keypress',function(){
 			if(_params['control'].attr('data-state') == 0){
 				_params['control'].trigger('click');
 			}
 		});
 	}
 	_params.callNumber++;
 	jeedom.log.currentAutoupdate[_params.display.uniqueId().attr('id')] = {log : _params.log};

 	if(_params.callNumber > 0 && (_params.display.scrollTop() + _params.display.innerHeight() + 1) < _params.display[0].scrollHeight){
 		if(_params['control'].attr('data-state') == 1){
 			_params['control'].trigger('click');
 		}
 		return;
 	}
 	jeedom.log.get({
 		log : _params.log,
 		slaveId : _params.slaveId,
 		global : (_params.callNumber == 1),
 		success : function(result){
 			var log = '';
 			var regex = /<br\s*[\/]?>/gi;
 			if($.isArray(result)){
 				for (var i in result.reverse()) {
 					if(!isset(_params['search']) || _params['search'].value() == '' || result[i].toLowerCase().indexOf(_params['search'].value().toLowerCase()) != -1){
 						log += $.trim(result[i])+"\n";
 					}
 				}
 			}
 			_params.display.text(log);
 			_params.display.scrollTop(_params.display.height() + 200000);
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