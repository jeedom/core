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

jeedom.timeline = function() {
};

jeedom.timeline.byFolder = function(_params) {
  var paramsRequired = [];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/timeline.ajax.php';
  paramsAJAX.data = {
    action: 'byFolder',
    folder : _params.folder || 'main'
  };
  $.ajax(paramsAJAX);
};

jeedom.timeline.deleteAll = function(_params) {
  var paramsRequired = [];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/timeline.ajax.php';
  paramsAJAX.data = {
    action: 'deleteAll'
  };
  $.ajax(paramsAJAX);
};

jeedom.timeline.listFolder = function(_params) {
  var paramsRequired = [];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/timeline.ajax.php';
  paramsAJAX.data = {
    action: 'listFolder'
  };
  $.ajax(paramsAJAX);
};


jeedom.timeline.autocompleteFolder = function(){
  jeedom.timeline.listFolder({
    global : false,
    success : function(data){
      var values = [];
      for(var i in data){
        values.push({val : data[i]});
      }
      $('[data-l2key="timeline::folder"]').sew({values: values,token:'[ |,]'});
    }
  })
}
