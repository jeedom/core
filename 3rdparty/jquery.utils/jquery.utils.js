
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
 function in_array(a, b, d) {
    var c = "";
    if (d)
        for (c in b) {
            if (b[c] === a)
                return!0
        }
        else
            for (c in b)
                if (b[c] == a)
                    return!0;
                return!1
            }
            function json_decode(a) {
                var b = this.window.JSON;
                if ("object" === typeof b && "function" === typeof b.parse)
                    try {
                        return b.parse(a)
                    } catch (d) {
                        if (!(d instanceof SyntaxError))
                            throw Error("Unexpected error type in json_decode()");
                        this.php_js = this.php_js || {};
                        this.php_js.last_error_json = 4;
                        return null
                    }
                    b = /[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g;
                    b.lastIndex = 0;
                    b.test(a) && (a = a.replace(b, function (a) {
                        return"\\u" + ("0000" + a.charCodeAt(0).toString(16)).slice(-4)
                    }));
                    if (/^[\],:{}\s]*$/.test(a.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g, "@").replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, "]").replace(/(?:^|:|,)(?:\s*\[)+/g, "")))
                        return a = eval("(" + a + ")");
                    this.php_js = this.php_js || {};
                    this.php_js.last_error_json = 4;
                    return null
                }

                function json_encode(a) {
                    var b, d = this.window.JSON;
                    try {
                        if ("object" === typeof d && "function" === typeof d.stringify) {
                            b = d.stringify(a);
                            if (void 0 === b)
                                throw new SyntaxError("json_encode");
                            return b
                        }
                        var c = function (a) {
                            var b = /[\\\"\u0000-\u001f\u007f-\u009f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g, c = {"\b": "\\b", "\t": "\\t", "\n": "\\n", "\f": "\\f", "\r": "\\r", '"': '\\"', "\\": "\\\\"};
                            b.lastIndex = 0;
                            return b.test(a) ? '"' + a.replace(b, function (a) {
                                var b = c[a];
                                return"string" ===
                                typeof b ? b : "\\u" + ("0000" + a.charCodeAt(0).toString(16)).slice(-4)
                            }) + '"' : '"' + a + '"'
                        }, e = function (a, b) {
                            var d = "", f = 0, m = f = "", m = 0, s = d, k = [], l = b[a];
                            l && ("object" === typeof l && "function" === typeof l.toJSON) && (l = l.toJSON(a));
                            switch (typeof l) {
                                case "string":
                                return c(l);
                                case "number":
                                return isFinite(l) ? String(l) : "null";
                                case "boolean":
                                case "null":
                                return String(l);
                                case "object":
                                if (!l)
                                    return"null";
                                if (this.PHPJS_Resource && l instanceof this.PHPJS_Resource || window.PHPJS_Resource && l instanceof window.PHPJS_Resource)
                                    throw new SyntaxError("json_encode");
                                d += "    ";
                                k = [];
                                if ("[object Array]" === Object.prototype.toString.apply(l)) {
                                    m = l.length;
                                    for (f = 0; f < m; f += 1)
                                        k[f] = e(f, l) || "null";
                                    return m = 0 === k.length ? "[]" : d ? "[\n" + d + k.join(",\n" + d) + "\n" + s + "]" : "[" + k.join(",") + "]"
                                }
                                for (f in l)
                                    Object.hasOwnProperty.call(l, f) && (m = e(f, l)) && k.push(c(f) + (d ? ": " : ":") + m);
                                return m = 0 === k.length ? "{}" : d ? "{\n" + d + k.join(",\n" + d) + "\n" + s + "}" : "{" + k.join(",") + "}";
                                default:
                                throw new SyntaxError("json_encode");
                            }
                        };
                        return e("", {"": a})
                    } catch (f) {
                        if (!(f instanceof SyntaxError))
                            throw Error("Unexpected error type in json_encode()");
                        this.php_js = this.php_js || {};
                        this.php_js.last_error_json = 4;
                        return null
                    }
                }

                function isset() {
                    var a = arguments, b = a.length, d = 0;
                    if (0 === b)
                        throw Error("Empty isset");
                    for (; d !== b; ) {
                        if (void 0 === a[d] || null === a[d])
                            return!1;
                        d++
                    }
                    return!0
                }

                function is_double(a) {
                    return this.is_float(a)
                }
                function is_float(a) {
                    return+a === a && (!isFinite(a) || !!(a % 1))
                }
                function is_int(a) {
                    return a === +a && isFinite(a) && !(a % 1)
                }
                function is_integer(a) {
                    return this.is_int(a)
                }
                function is_long(a) {
                    return this.is_float(a)
                }
                function is_null(a) {
                    return null === a
                }
                function is_numeric(a) {
                    return("number" === typeof a || "string" === typeof a) && "" !== a && !isNaN(a)
                }
                function is_object(a) {
                    return"[object Array]" === Object.prototype.toString.call(a) ? !1 : null !== a && "object" === typeof a
                }
                function is_real(a) {
                    return this.is_float(a)
                }
                function is_scalar(a) {
                    return/boolean|number|string/.test(typeof a)
                }
                function is_string(a) {
                    return"string" == typeof a
                }
                function is_unicode(a) {
                    if ("string" !== typeof a)
                        return!1;
                    for (var b = [], d = RegExp("[\ud800-\udbff]([sS])", "g"), c = RegExp("([sS])[\udc00-\udfff]", "g"), e = RegExp("^[\udc00-\udfff]$"), f = RegExp("^[\ud800-\udbff]$"); null !== (b = d.exec(a)); )
                        if (!b[1] || !b[1].match(e))
                            return!1;
                        for (; null !== (b = c.exec(a)); )
                            if (!b[1] || !b[1].match(f))
                                return!1;
                            return!0
                        }

                        function is_array(a) {
                            var b, d = function (a) {
                                return(a = /\W*function\s+([\w\$]+)\s*\(/.exec(a)) ? a[1] : "(Anonymous)"
                            };
                            if (!a || "object" !== typeof a)
                                return!1;
                            this.php_js = this.php_js || {};
                            this.php_js.ini = this.php_js.ini || {};
                            b = this.php_js.ini["phpjs.objectsAsArrays"];
                            return function (a) {
                                if (!a || "object" !== typeof a || "number" !== typeof a.length)
                                    return!1;
                                var b = a.length;
                                a[a.length] = "bogus";
                                if (b !== a.length)
                                    return a.length -= 1, !0;
                                delete a[a.length];
                                return!1
                            }(a) || (!b || 0 !== parseInt(b.local_value, 10) && (!b.local_value.toLowerCase ||
                                "off" !== b.local_value.toLowerCase())) && "[object Object]" === Object.prototype.toString.call(a) && "Object" === d(a.constructor)
                        }
                        function is_binary(a) {
                            return"string" === typeof a
                        }
                        function is_bool(a) {
                            return!0 === obj || !1 === obj
                        }
                        function is_buffer(a) {
                            return"string" === typeof a
                        }

                        function count(a, b) {
                            var d, c = 0;
                            if (null === a || "undefined" === typeof a)
                                return 0;
                            if (a.constructor !== Array && a.constructor !== Object)
                                return 1;
                            "COUNT_RECURSIVE" === b && (b = 1);
                            1 != b && (b = 0);
                            for (d in a)
                                a.hasOwnProperty(d) && (c++, 1 != b || (!a[d] || a[d].constructor !== Array && a[d].constructor !== Object) || (c += this.count(a[d], 1)));
                            return c
                        }

                        function init(_value, _default) {
                            if (!isset(_default)) {
                                _default = '';
                            }
                            if (!isset(_value)) {
                                return _default;
                            }
                            return _value;
                        }

                        (function ($) {
                            var scriptsCache = [];
                            $.include = function (_path, _callback) {
                                $.ajaxPrefilter(function (options, originalOptions, jqXHR) {
                                    if (options.dataType == 'script' || originalOptions.dataType == 'script') {
                                        options.cache = true;
                                    }
                                });
                                for (var i in _path) {
                                    if (jQuery.inArray(_path[i], scriptsCache) == -1) {
                                        var extension = _path[i].substr(_path[i].length - 3);

                                        if (extension == 'css') {
                                            $('<link rel="stylesheet" href="' + _path[i] + '" type="text/css" />').appendTo('head');
                                        }
                                        if (extension == '.js') {
                                            if (_path[i].indexOf('core/php/getJS.php?file=') >= 0) {
                                                $('<script type="text/javascript" src="' + _path[i] + '"></script>').appendTo('head');
                                            } else {
                                                $('<script type="text/javascript" src="core/php/getJS.php?file=' + _path[i] + '"></script>').appendTo('head');
                                            }
                                        }

                                        scriptsCache.push(_path[i]);
                                    }
                                }
                                $.ajaxPrefilter(function (options, originalOptions, jqXHR) {
                                    if (options.dataType == 'script' || originalOptions.dataType == 'script') {
                                        options.cache = false;
                                    }
                                });
                                _callback();
                                return;
                            };

                            /********************************loading************************/
                            $.showLoading = function () {
                                if ($.mobile) {
                                    $('#div_loadingSpinner').show()
                                } else {
                                    if ($('#jqueryLoadingDiv').length == 0) {
                                        $('body').append('<div id="jqueryLoadingDiv"><div class="overlay"></div><i class="fa fa-cog fa-spin loadingImg"></i></div>');
                                    }
                                    $('#jqueryLoadingDiv').show();
                                }
                            };
                            $.hideLoading = function () {
                                if ($.mobile) {
                                    $('#div_loadingSpinner').hide()
                                } else {
                                    $('#jqueryLoadingDiv').hide();
                                }
                            };

                            /*********************jquery alert*************************************/
                            $.fn.showAlert = function (_options) {
                                var options = init(_options, {});
                                options.message = init(options.message, '');
                                options.level = init(options.level, '');
                                options.emptyBefore = init(options.emptyBefore, true);
                                options.show = init(options.show, true);
                                if ($.mobile) {
                                    $(this).empty();
                                    $(this).addClass('jqAlert');
                                    $(this).html('<a href="#" data-rel="back" data-role="button" data-theme="h" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>' + options.message);
                                    $(this).enhanceWithin().popup();
                                    $(this).popup('open');
                                } else {
                                    if (options.emptyBefore == false) {
                                        var html = $(this).find('.displayError').html();
                                        if (isset(html)) {
                                            options.message = html + '<br/>' + options.message;
                                        }
                                    }
                                    $(this).empty();
                                    $(this).html('<span href="#" class="btn_closeAlert pull-right cursor" style="position : relative; left : 30px;color : grey">×</span><span class="displayError">' + options.message + '</span>');
                                    $(this).removeClass('alert alert-warning alert-danger alert-info alert-success jqAlert');
                                    $(this).addClass('alert jqAlert');
                                    if (options.level != '') {
                                        $(this).addClass('alert-' + options.level);
                                    }
                                    if (options.show) {
                                        $(this).show();
                                        $(this).css('padding', '7px 35px 7px 15px');
                                        $(this).css('margin-bottom', '5px');
                                        $(this).css('overflow', 'auto');
                                        $(this).css('max-height', $(window).height() - 100 + 'px');
                                        $(this).css('z-index', '9999');
                                    }

                                    if ($(this).offset().top - $(window).scrollTop() < $(this).height()) {
                                        $('html, body').animate({
                                            scrollTop: $(this).offset().top - 60
                                        }, 650);
                                    }

                                    $(this).find('.btn_closeAlert').on('click', function () {
                                        $(this).closest('.jqAlert').hide();
                                    });
                                }
        //Hide/show debug trace
        $(this).find('.bt_errorShowTrace').on('click', function () {
            var errorTrace = $(this).parent().find('.pre_errorTrace');
            if (errorTrace.is(':visible')) {
                errorTrace.hide();
                $(this).text('Show traces');
            } else {
                errorTrace.show();
                $(this).text('Hide traces');
            }
        });
        return this;
    };

    $.fn.hideAlert = function () {
        $('#jqAlertSpacer' + $(this).attr('id')).remove();
        $(this).text('').hide();
        return $(this);
    };

    $.hideAlert = function () {
        if ($.mobile) {
            $('.jqAlert').popup("close");
        } else {
            $('.jqAlert').text('');
            $('.jqAlert').hide();
        }
    };

    /**********************Jquery.value******************************/

    jQuery.fn.findAtDepth = function (selector, maxDepth) {
        var depths = [], i;

        if (maxDepth > 0) {
            for (i = 1; i <= maxDepth; i++) {
                depths.push('> ' + new Array(i).join('* > ') + selector);
            }

            selector = depths.join(', ');
            return this.find(selector).first();
        }
        return this.find(selector);
    };


    $.fn.value = function (_value) {
        if (isset(_value)) {
            if ($(this).length > 1) {
                $(this).each(function () {
                    $(this).value(_value);
                });
            } else {
                if ($(this).is('input')) {
                    if ($(this).attr('type') == 'checkbox') {
                        if($(this).hasClass('bootstrapSwitch')){
                            $(this).bootstrapSwitch('destroy');
                            $(this).prop('checked', (init(_value) == 1) ? true : false);
                        }else{
                            $(this).prop('checked', (init(_value) == 1) ? true : false);
                        }
                    } else {
                        $(this).val(init(_value));
                    }
                }
                if ($(this).is('select')) {
                    if (init(_value) == '') {
                        $(this).find('option:first').prop('selected', true);
                    } else {
                        $(this).val(init(_value));
                    }
                }
                if ($(this).is('textarea')) {
                    $(this).val(init(_value));
                }
                if ($(this).is('span') || $(this).is('div') || $(this).is('p')) {
                    $(this).html(init(_value));
                }
                if ($(this).is('pre')) {
                    $(this).html(init(_value));
                }
                $(this).trigger('change');
            }
        } else {
            var value = '';
            if ($(this).is('input') || $(this).is('select') || $(this).is('textarea')) {
                if ($(this).attr('type') == 'checkbox') {
                    value = ($(this).is(':checked')) ? '1' : '0';
                } else {
                    value = $(this).val();
                }
            }
            if ($(this).is('div') || $(this).is('span') || $(this).is('p')) {
                value = $(this).text();
                if (value == '') {
                    value = $(this).html();
                }
            }
            if ($(this).is('a') && $(this).attr('value') != undefined) {
                value = $(this).attr('value');
            }
            if (value == '') {
                value = $(this).val();
            }
            return value;

        }
    };

    $.fn.getValues = function (_attr, _depth) {
        var values = [];
        if ($(this).length > 1) {
            $(this).each(function () {
                var value = {};
                $(this).findAtDepth(_attr, init(_depth, 0)).each(function () {
                    var elValue = $(this).value();
                    try {
                        if ($.trim(elValue).substr(0, 1) == '{') {
                            var elValue = JSON.parse($(this).value());
                        }
                    } catch (e) {

                    }
                    if ($(this).attr('data-l1key') != undefined && $(this).attr('data-l1key') != '') {
                        var l1key = $(this).attr('data-l1key');
                        if ($(this).attr('data-l2key') !== undefined) {
                            var l2key = $(this).attr('data-l2key');
                            if (!isset(value[l1key])) {
                                value[l1key] = {};
                            }
                            if ($(this).attr('data-l3key') !== undefined) {
                                var l3key = $(this).attr('data-l3key');
                                if (!isset(value[l1key][l2key])) {
                                    value[l1key][l2key] = {};
                                }
                                if (isset(value[l1key][l2key][l3key])) {
                                    if (!is_array(value[l1key][l2key][l3key])) {
                                        value[l1key][l2key][l3key] = [value[l1key][l2key][l3key]];
                                    }
                                    value[l1key][l2key][l3key].push(elValue);
                                } else {
                                    value[l1key][l2key][l3key] = elValue;
                                }
                            } else {
                                if (isset(value[l1key][l2key])) {
                                    if (!is_array(value[l1key][l2key])) {
                                        value[l1key][l2key] = [value[l1key][l2key]];
                                    }
                                    value[l1key][l2key].push(elValue);
                                } else {
                                    value[l1key][l2key] = elValue;
                                }
                            }
                        } else {
                            if (isset(value[l1key])) {
                                if (!is_array(value[l1key])) {
                                    value[l1key] = [value[l1key]];
                                }
                                value[l1key].push(elValue);
                            } else {
                                value[l1key] = elValue;
                            }
                        }
                    }
                });
values.push(value);
});
}
if ($(this).length == 1) {
    var value = {};
    $(this).findAtDepth(_attr, init(_depth, 0)).each(function () {
        if ($(this).attr('data-l1key') != undefined && $(this).attr('data-l1key') != '') {
            var elValue = $(this).value();
            try {
                if ($.trim(elValue).substr(0, 1) == '{') {
                    var elValue = JSON.parse($(this).value());
                }
            } catch (e) {

            }
            var l1key = $(this).attr('data-l1key');
            if ($(this).attr('data-l2key') !== undefined) {
                var l2key = $(this).attr('data-l2key');
                if (!isset(value[l1key])) {
                    value[l1key] = {};
                }
                if ($(this).attr('data-l3key') !== undefined) {
                    var l3key = $(this).attr('data-l3key');
                    if (!isset(value[l1key][l2key])) {
                        value[l1key][l2key] = {};
                    }
                    if (isset(value[l1key][l2key][l3key])) {
                        if (!is_array(value[l1key][l2key][l3key])) {
                            value[l1key][l2key][l3key] = [value[l1key][l2key][l3key]];
                        }
                        value[l1key][l2key][l3key].push(elValue);
                    } else {
                        value[l1key][l2key][l3key] = elValue;
                    }
                } else {
                    if (isset(value[l1key][l2key])) {
                        if (!is_array(value[l1key][l2key])) {
                            value[l1key][l2key] = [value[l1key][l2key]];
                        }
                        value[l1key][l2key].push(elValue);
                    } else {
                        value[l1key][l2key] = elValue;
                    }
                }
            } else {
                if (isset(value[l1key])) {
                    if (!is_array(value[l1key])) {
                        value[l1key] = [value[l1key]];
                    }
                    value[l1key].push(elValue);
                } else {
                    value[l1key] = elValue;
                }
            }
        }
    });
values.push(value);
}
return values;
}

$.fn.setValues = function (_object, _attr) {
    for (var i in _object) {
        if (!is_array(_object[i]) && !is_object(_object[i])) {
            $(this).find(_attr + '[data-l1key="' + i + '"]').value(_object[i]);
        } else {
            for (var j in _object[i]) {
                if (is_array(_object[i][j]) || is_object(_object[i][j])) {
                    for (var k in _object[i][j]) {
                        $(this).find(_attr + '[data-l1key="' + i + '"][data-l2key="' + j + '"][data-l3key="' + k + '"]').value(_object[i][j][k]);
                    }
                } else {
                    $(this).find(_attr + '[data-l1key="' + i + '"][data-l2key="' + j + '"]').value(_object[i][j]);
                }
            }
        }
    }
}


/**************LI FILTER*****************************/

$.initTableFilter = function () {
    $("body").delegate("ul li input.filter", 'keyup', function () {
        $(this).closest('ul').ulFilter();
    });
};


$.fn.ulFilter = function () {
    var ul = $(this);
    var li = $(this).find('li:not(.filter):not(.nav-header):first');
    var find = 'li.filter input.filter';
    delete inputs;
    var inputs = new Array();
    ul.find(find).each(function (i) {
        var filterOn = '';
        if ($(this).is(':visible')) {
            var value = $(this).value();
            var filterOn = $(this).attr('filterOn');
        }
        if (filterOn != '' && value != '') {
            var infoInput = new Array();
            infoInput[0] = filterOn;
            infoInput[1] = value.toLowerCase();
            inputs.push(infoInput);
        }
    });
    var searchText = 1;
    var showLi = true;
    $(this).find('li:not(.filter):not(.nav-header)').each(function () {
        showLi = true;
        for (var i = 0; i < inputs.length; i++) {
            searchText = $(this).find('a').text().toLowerCase().stripAccents().indexOf(inputs[i][1].stripAccents());
            if (searchText < 0) {
                showLi = false;
                break;
            }
        }
        if (showLi) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
    return this;
};

String.prototype.stripAccents = function () {
    var in_chrs = 'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ',
    out_chrs = 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY',
    transl = {};
    eval('var chars_rgx = /[' + in_chrs + ']/g');
    for (var i = 0; i < in_chrs.length; i++) {
        transl[in_chrs.charAt(i)] = out_chrs.charAt(i);
    }
    return this.replace(chars_rgx, function (match) {
        return transl[match];
    });
};
})(jQuery);


