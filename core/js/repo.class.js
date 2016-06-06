
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


 jeedom.repo = function() {
 };

 jeedom.repo.install = function (_params) {
 	var paramsRequired = ['id','repo'];
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
 	paramsAJAX.url = 'core/ajax/repo.ajax.php';
 	paramsAJAX.data = {
 		action: 'install',
 		repo: _params.repo,
 		id: _params.id,
 		version: _params.version || 'stable'
 	};
 	$.ajax(paramsAJAX);
 }

 jeedom.repo.remove = function (_params) {
 	var paramsRequired = ['id','repo'];
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
 	paramsAJAX.url = 'core/ajax/repo.ajax.php';
 	paramsAJAX.data = {
 		action: 'remove',
 		repo: _params.repo,
 		id: _params.id,
 	};
 	$.ajax(paramsAJAX);
 }

 jeedom.repo.setRating = function (_params) {
 	var paramsRequired = ['id','rating','repo'];
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
 	paramsAJAX.url = 'core/ajax/repo.ajax.php';
 	paramsAJAX.data = {
 		action: 'setRating',
 		repo: _params.repo,
 		id: _params.id,
 		rating: _params.rating,
 	};
 	$.ajax(paramsAJAX);
 }

 jeedom.repo.test = function (_params) {
 	var paramsRequired = ['repo'];
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
 	paramsAJAX.url = 'core/ajax/repo.ajax.php';
 	paramsAJAX.data = {
 		action: 'test',
 		repo: _params.repo,
 	};
 	$.ajax(paramsAJAX);
 }