
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


jeedom.market = function() {
};


 jeedom.market.byLogicalId = function (_params) {
 	var paramsRequired = ['logicalId'];
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
 	paramsAJAX.url = 'core/ajax/market.ajax.php';
 	paramsAJAX.data = {
 		action: 'byLogicalId',
 		logicalId: _params.logicalId,
 		noExecption: _params.noExecption || 0,
 	};
 	$.ajax(paramsAJAX);
 }

  jeedom.market.test = function (_params) {
 	var paramsRequired = [];
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
 	paramsAJAX.url = 'core/ajax/market.ajax.php';
 	paramsAJAX.data = {
 		action: 'test',
 	};
 	$.ajax(paramsAJAX);
 }

   jeedom.market.setComment = function (_params) {
 	var paramsRequired = ['id'];
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
 	paramsAJAX.url = 'core/ajax/market.ajax.php';
 	paramsAJAX.data = {
 		action: 'setComment',
 		id: _params.id,
 		comment: _params.comment || '',
 		order: _params.order || '',
 	};
 	$.ajax(paramsAJAX);
 }

  jeedom.market.install = function (_params) {
 	var paramsRequired = ['id'];
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
 	paramsAJAX.url = 'core/ajax/market.ajax.php';
 	paramsAJAX.data = {
 		action: 'install',
 		id: _params.id,
 		version: _params.version || 'stable'
 	};
 	$.ajax(paramsAJAX);
 }

   jeedom.market.remove = function (_params) {
 	var paramsRequired = ['id'];
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
 	paramsAJAX.url = 'core/ajax/market.ajax.php';
 	paramsAJAX.data = {
 		action: 'remove',
 		id: _params.id,
 	};
 	$.ajax(paramsAJAX);
 }

 jeedom.market.setRating = function (_params) {
 	var paramsRequired = ['id','rating'];
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
 	paramsAJAX.url = 'core/ajax/market.ajax.php';
 	paramsAJAX.data = {
 		action: 'setRating',
 		id: _params.id,
 		rating: _params.rating,
 	};
 	$.ajax(paramsAJAX);
 }