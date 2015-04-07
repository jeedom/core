
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


jeedom.jeeNetwork = function () {
};

jeedom.jeeNetwork.remove = function (_params) {
    var paramsRequired = ['id'];
    var paramsSpecifics = {};
    try {
        jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
    } catch (e) {
        (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
        return;
    }
    var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/jeeNetwork.ajax.php';
    paramsAJAX.data = {
        action: 'remove',
        id: _params.id
    };
    $.ajax(paramsAJAX);
};

jeedom.jeeNetwork.save = function (_params) {
    var paramsRequired = ['jeeNetwork'];
    var paramsSpecifics = {};
    try {
        jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
    } catch (e) {
        (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
        return;
    }
    var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/jeeNetwork.ajax.php';
    paramsAJAX.data = {
        action: 'save',
        jeeNetwork: json_encode(_params.jeeNetwork),
    };
    $.ajax(paramsAJAX);
};


jeedom.jeeNetwork.byId = function (_params) {
    var paramsRequired = ['id'];
    var paramsSpecifics = {};
    try {
        jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
    } catch (e) {
        (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
        return;
    }
    var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/jeeNetwork.ajax.php';
    paramsAJAX.data = {
        action: 'byId',
        id: _params.id
    };
    $.ajax(paramsAJAX);
};

jeedom.jeeNetwork.changeMode = function (_params) {
    var paramsRequired = ['mode'];
    var paramsSpecifics = {};
    try {
        jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
    } catch (e) {
        (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
        return;
    }
    var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/jeeNetwork.ajax.php';
    paramsAJAX.data = {
        action: 'changeMode',
        mode: _params.mode
    };
    $.ajax(paramsAJAX);
};

jeedom.jeeNetwork.haltSystem = function (_params) {
    var paramsRequired = ['id'];
    var paramsSpecifics = {};
    try {
        jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
    } catch (e) {
        (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
        return;
    }
    var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/jeeNetwork.ajax.php';
    paramsAJAX.data = {
        action: 'haltSystem',
        id: _params.id
    };
    $.ajax(paramsAJAX);
};

jeedom.jeeNetwork.rebootSystem = function (_params) {
    var paramsRequired = ['id'];
    var paramsSpecifics = {};
    try {
        jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
    } catch (e) {
        (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
        return;
    }
    var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/jeeNetwork.ajax.php';
    paramsAJAX.data = {
        action: 'rebootSystem',
        id: _params.id
    };
    $.ajax(paramsAJAX);
};

jeedom.jeeNetwork.update = function (_params) {
    var paramsRequired = ['id'];
    var paramsSpecifics = {};
    try {
        jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
    } catch (e) {
        (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
        return;
    }
    var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/jeeNetwork.ajax.php';
    paramsAJAX.data = {
        action: 'update',
        id: _params.id
    };
    $.ajax(paramsAJAX);
};

jeedom.jeeNetwork.checkUpdate = function (_params) {
    var paramsRequired = ['id'];
    var paramsSpecifics = {};
    try {
        jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
    } catch (e) {
        (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
        return;
    }
    var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/jeeNetwork.ajax.php';
    paramsAJAX.data = {
        action: 'checkUpdate',
        id: _params.id
    };
    $.ajax(paramsAJAX);
};

jeedom.jeeNetwork.getLog = function (_params) {
    var paramsRequired = ['id', 'log'];
    var paramsSpecifics = {};
    try {
        jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
    } catch (e) {
        (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
        return;
    }
    var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/jeeNetwork.ajax.php';
    paramsAJAX.data = {
        action: 'getLog',
        id: _params.id,
        log: _params.log,
    };
    $.ajax(paramsAJAX);
};

jeedom.jeeNetwork.listLog = function (_params) {
    var paramsRequired = ['id'];
    var paramsSpecifics = {};
    try {
        jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
    } catch (e) {
        (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
        return;
    }
    var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/jeeNetwork.ajax.php';
    paramsAJAX.data = {
        action: 'getListLog',
        id: _params.id,
    };
    $.ajax(paramsAJAX);
};

jeedom.jeeNetwork.listLocalSlaveBackup = function (_params) {
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
    paramsAJAX.url = 'core/ajax/jeeNetwork.ajax.php';
    paramsAJAX.data = {
        action: 'listLocalSlaveBackup',
    };
    $.ajax(paramsAJAX);
};

jeedom.jeeNetwork.restoreLocalBackup = function (_params) {
    var paramsRequired = ['backup'];
    var paramsSpecifics = {};
    try {
        jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
    } catch (e) {
        (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
        return;
    }
    var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/jeeNetwork.ajax.php';
    paramsAJAX.data = {
        action: 'restoreLocalBackup',
        backup: _params.backup,
        id: _params.id
    };
    $.ajax(paramsAJAX);
};

jeedom.jeeNetwork.backup = function (_params) {
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
    paramsAJAX.url = 'core/ajax/jeeNetwork.ajax.php';
    paramsAJAX.data = {
        action: 'backup',
        id: _params.id
    };
    $.ajax(paramsAJAX);
};

jeedom.jeeNetwork.getMessage = function (_params) {
    var paramsRequired = ['id'];
    var paramsSpecifics = {};
    try {
        jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
    } catch (e) {
        (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
        return;
    }
    var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/jeeNetwork.ajax.php';
    paramsAJAX.data = {
        action: 'getMessage',
        id: _params.id,
    };
    $.ajax(paramsAJAX);
};

jeedom.jeeNetwork.removeAllMessage = function (_params) {
    var paramsRequired = ['id'];
    var paramsSpecifics = {};
    try {
        jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
    } catch (e) {
        (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
        return;
    }
    var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/jeeNetwork.ajax.php';
    paramsAJAX.data = {
        action: 'removeAllMessage',
        id: _params.id,
    };
    $.ajax(paramsAJAX);
};


jeedom.jeeNetwork.emptyLog = function (_params) {
    var paramsRequired = ['id', 'log'];
    var paramsSpecifics = {};
    try {
        jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
    } catch (e) {
        (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
        return;
    }
    var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/jeeNetwork.ajax.php';
    paramsAJAX.data = {
        action: 'emptyLog',
        id: _params.id,
        log: _params.log,
    };
    $.ajax(paramsAJAX);
};


jeedom.jeeNetwork.removeLog = function (_params) {
    var paramsRequired = ['id', 'log'];
    var paramsSpecifics = {};
    try {
        jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
    } catch (e) {
        (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
        return;
    }
    var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/jeeNetwork.ajax.php';
    paramsAJAX.data = {
        action: 'removeLog',
        id: _params.id,
        log: _params.log,
    };
    $.ajax(paramsAJAX);
};

jeedom.jeeNetwork.saveConfig = function (_params) {
    var paramsRequired = ['configuration','id'];
    var paramsSpecifics = {};
    try {
        jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
    } catch (e) {
        (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
        return;
    }
    var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/jeeNetwork.ajax.php';
    paramsAJAX.data = {
        action: 'addKey',
        value: json_encode(_params.configuration),
        plugin: _params.plugin || 'core',
        id : _params.id
    };
    $.ajax(paramsAJAX);
}

jeedom.jeeNetwork.loadConfig = function (_params) {
    var paramsRequired = ['configuration','id'];
    var paramsSpecifics = {};
    try {
        jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
    } catch (e) {
        (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
        return;
    }
    var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/jeeNetwork.ajax.php';
    paramsAJAX.data = {
        action: 'getKey',
        key: ($.isArray(_params.configuration) || $.isPlainObject(_params.configuration)) ? json_encode(_params.configuration) : _params.configuration,
        plugin: _params.plugin || 'core',
        id : _params.id
    };
    $.ajax(paramsAJAX);
};

jeedom.jeeNetwork.restartNgrok = function (_params) {
    var paramsRequired = ['id'];
    var paramsSpecifics = {};
    try {
        jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
    } catch (e) {
        (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
        return;
    }
    var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/jeeNetwork.ajax.php';
    paramsAJAX.data = {
        action: 'restartNgrok',
        id : _params.id
    };
    $.ajax(paramsAJAX);
};

jeedom.jeeNetwork.stopNgrok = function (_params) {
    var paramsRequired = ['id'];
    var paramsSpecifics = {};
    try {
        jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
    } catch (e) {
        (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
        return;
    }
    var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/jeeNetwork.ajax.php';
    paramsAJAX.data = {
        action: 'stopNgrok',
        id : _params.id
    };
    $.ajax(paramsAJAX);
};

jeedom.jeeNetwork.ngrokRun = function (_params) {
    var paramsRequired = ['id'];
    var paramsSpecifics = {};
    try {
        jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
    } catch (e) {
        (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
        return;
    }
    var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/jeeNetwork.ajax.php';
    paramsAJAX.data = {
        action: 'ngrokRun',
        id : _params.id,
        proto: _params.proto || 'http',
        port: _params.port || 80,
        name : _params.name || ''
    };
    $.ajax(paramsAJAX);
};