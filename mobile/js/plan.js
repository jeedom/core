function initPlan(_planHeader_id) {
    jeedom.plan.allHeader({
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (planHeader) {
            var li = ' <ul data-role="listview">';
            for (var i in planHeader) {
                li += '<li><a href="#" class="link" data-page="plan" data-title="' + planHeader[i].name + '" data-option="' + planHeader[i].id + '">' + planHeader[i].name + '</a></li>'
            }
            li += '</ul>';
            panel(li);
        }
    });

    displayPlan(_planHeader_id);
    if (init(userProfils.defaultMobilePlanFullscreen) == 1) {
        $("div[data-role=header]").remove();
        $(this).css('top', '15px');
        $('.ui-content').css('padding', '0');
        displayPlan(_planHeader_id);
    }

    $("#bt_fullScreen").on("click", function () {
        if ($("div[data-role=header]").length != 0) {
            $("div[data-role=header]").remove();
            $(this).css('top', '15px');
            $('.ui-content').css('padding', '0');
            displayPlan(_planHeader_id);
        } else {
            window.location.reload();
        }
    });

    $("body:not(.eqLogic)").off("swipeleft");
    $("body:not(.eqLogic)").off("swiperight");
}

function displayPlan(_planHeader_id) {
    jeedom.plan.getHeader({
        id: _planHeader_id,
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
            $('#div_displayObject').empty().append(data.image);
            var sizeSet = false;
            if (deviceInfo.type == 'desktop') {
                if (data.configuration != null && init(data.configuration.desktopSizeX) != '' && init(data.configuration.desktopSizeY) != '') {
                    $('#div_displayObject').height(data.configuration.desktopSizeY);
                    $('#div_displayObject').width(data.configuration.desktopSizeX);
                    $('#div_displayObject img').css('height', data.configuration.desktopSizeY + 'px');
                    $('#div_displayObject img').css('width', data.configuration.desktopSizeX + 'px');
                    sizeSet = true;
                }
            }
            if (deviceInfo.type == 'tablet') {
                if (data.configuration != null && init(data.configuration.tabletSizeX) != '' && init(data.configuration.tabletSizeY) != '') {
                    $('#div_displayObject').height(data.configuration.tabletSizeY);
                    $('#div_displayObject').width(data.configuration.tabletSizeX);
                    $('#div_displayObject img').css('height', data.configuration.tabletSizeY + 'px');
                    $('#div_displayObject img').css('width', data.configuration.tabletSizeX + 'px');
                    sizeSet = true;
                }
            }
            if (deviceInfo.type == 'phone') {
                if (data.configuration != null && init(data.configuration.mobileSizeX) != '' && init(data.configuration.mobileSizeY) != '') {
                    $('#div_displayObject').height(data.configuration.mobileSizeY);
                    $('#div_displayObject').width(data.configuration.mobileSizeX);
                    $('#div_displayObject img').css('height', data.configuration.mobileSizeY + 'px');
                    $('#div_displayObject img').css('width', data.configuration.mobileSizeX + 'px');
                    sizeSet = true;
                }
            }
            if (!sizeSet) {
                $('#div_displayObject').width($('#div_displayObject img').attr('data-sixe_x'));
                $('#div_displayObject').height($('#div_displayObject img').attr('data-sixe_y'));
                $('#div_displayObject img').css('height', $('#div_displayObject img').attr('data-sixe_y') + 'px');
                $('#div_displayObject img').css('width', $('#div_displayObject img').attr('data-sixe_x') + 'px');
            }

            $('.eqLogic-widget,.scenario-widget,.plan-link-widget,.view-link-widget,.graph-widget').remove();

            grid = false;
            if (data.configuration != null && isset(data.configuration.gridX) && isset(data.configuration.gridY) && !isNaN(data.configuration.gridX) && !isNaN(data.configuration.gridY) && data.configuration.gridX > 0 && data.configuration.gridY > 0) {
                grid = [$('#div_displayObject').width() / data.configuration.gridX, $('#div_displayObject').height() / data.configuration.gridY];
                eqLogic_width_step = grid[0] - 5;
                eqLogic_height_step = grid[1] - 5;
            }
            jeedom.plan.byPlanHeader({
                id: _planHeader_id,
                version: 'mobile',
                error: function (error) {
                    $('#div_alert').showAlert({message: error.message, level: 'danger'});
                },
                success: function (data) {
                    for (var i in data) {
                        if (data[i].plan.link_type == 'graph') {
                            addGraph(data[i].plan);
                        } else {
                            displayObject(data[i].plan.link_type, data[i].plan.link_id, data[i].html, data[i].plan);
                        }
                    }
                }
            });
        }
    });
}

function displayObject(_type, _id, _html, _plan) {
    for (var i in jeedom.history.chart) {
        delete jeedom.history.chart[i];
    }
    _plan = init(_plan, {});
    _plan.position = init(_plan.position, {});
    _plan.css = init(_plan.css, {});
    var defaultZoom = 1;
    if (_type == 'eqLogic') {
        if (grid === false) {
            defaultZoom = 0.65;
        }
        $('.eqLogic-widget[data-eqLogic_id=' + _id + ']').remove();
    }
    if (_type == 'scenario') {
        $('.scenario-widget[data-scenario_id=' + _id + ']').remove();
    }
    if (_type == 'view') {
        $('.view-link-widget[data-link_id=' + _id + ']').remove();
    }
    if (_type == 'plan') {
        $('.plan-link-widget[data-link_id=' + _id + ']').remove();
    }
    if (_type == 'graph') {
        $('.graph-widget[data-graph_id=' + _id + ']').remove();
    }
    var parent = {
        height: $('#div_displayObject').height(),
        width: $('#div_displayObject').width(),
    };
    var html = $(_html);
    $('#div_displayObject').append(html);

    for (var key in _plan.css) {
        if (key == 'background-color') {
            if (!isset(_plan.display) || !isset(_plan.display['background-defaut']) || _plan.display['background-defaut'] != 1) {
                html.css(key, _plan.css[key]);
            }
        } else {
            html.css(key, _plan.css[key]);
        }
        if (key == 'color') {
            html.find('.ui-btn').css("cssText", key + ': ' + _plan.css[key] + ' !important;border-color : ' + _plan.css[key] + ' !important');
            html.find('tspan').css('fill', _plan.css[key]);
            html.find('span').css(key, _plan.css[key]);
        }
    }
    if (!isset(_plan.display) || !isset(_plan.display['background-defaut']) || _plan.display['background-defaut'] != 1) {
        if (isset(_plan.display) && isset(_plan.display['background-transparent']) && _plan.display['background-transparent'] == 1) {
            html.css('background-color', 'transparent');
        }
    }
    html.css('position', 'absolute');
    html.css('transform-origin', '0 0');
    html.css('transform', 'scale(' + init(_plan.css.zoom, defaultZoom) + ')');
    html.css('-webkit-transform-origin', '0 0');
    html.css('-webkit-transform', 'scale(' + init(_plan.css.zoom, defaultZoom) + ')');
    html.css('-moz-transform-origin', '0 0');
    html.css('-moz-transform', 'scale(' + init(_plan.css.zoom, defaultZoom) + ')');
    var position = {
        top: init(_plan.position.top, '10') * parent.height / 100,
        left: init(_plan.position.left, '10') * parent.width / 100,
    };
    html.css('top', position.top);
    html.css('left', position.left);
    if (_type == 'eqLogic') {
        if (isset(_plan.display) && isset(_plan.display.cmd)) {
            for (var id in _plan.display.cmd) {
                if (_plan.display.cmd[id] == 1) {
                    $('.cmd[data-cmd_id=' + id + ']').remove();
                }
            }
        }
        if (isset(_plan.display) && (isset(_plan.display.name) && _plan.display.name == 1)) {
            html.find('.widget-name').remove();
        }
    }

    if (grid === false) {
        html.addClass('noResize');
        setTileSize('.eqLogic');
    } else {
        html.css("max-width", "");
        html.css("min-width", eqLogic_width_step - 6);
        html.css("min-height", eqLogic_height_step - 6);
        if (!isset(_plan.display) || !isset(_plan.display.width)) {
            html.css('width', 'auto');
        }
        if (!isset(_plan.display) || !isset(_plan.display.height)) {
            html.css('height', 'auto');
        }
        if (_type == 'eqLogic') {
            positionEqLogic('', false, 'eqLogic-widget');
        }
        if (_type == 'scenario') {
            positionEqLogic('', false, 'scenario-widget');
        }
        if (_type == 'view') {
            positionEqLogic('', false, 'view-link-widget');
        }
        if (_type == 'plan') {
            positionEqLogic('', false, 'plan-link-widget');
        }
        if (_type == 'graph') {
            positionEqLogic('', false, 'graph-widget');
        }
    }

    if (_type == 'view' || _type == 'plan') {
        html.addClass('link');
        html.attr('data-page', _type);
        html.attr('data-option', _plan.link_id);
    }
    html.trigger('create');
}

function addGraph(_plan) {
    var parent = {
        height: $('#div_displayObject').height(),
        width: $('#div_displayObject').width(),
    };
    _plan = init(_plan, {});
    _plan.display = init(_plan.display, {});
    _plan.link_id = init(_plan.link_id, Math.round(Math.random() * 99999999) + 9999);
    var options = init(_plan.display.graph, '[]');
    var html = '<div class="graph-widget" data-graph_id="' + _plan.link_id + '" style="width : ' + (init(_plan.display.width, 10) * parent.width / 100) + 'px;height : ' + (init(_plan.display.height, 10) * parent.height / 100) + 'px;background-color : white;border : solid 1px black;">';
    if ($('#bt_editPlan').attr('data-mode') == "1") {
        html += '<i class="fa fa-cogs pull-right editMode configureGraph" style="margin-right : 5px;margin-top : 5px;"></i>';
    } else {
        html += '<i class="fa fa-cogs pull-right editMode configureGraph" style="margin-right : 5px;margin-top : 5px;display:none;"></i>';
    }
    html += '<span class="graphOptions" style="display:none;">' + json_encode(init(_plan.display.graph, '[]')) + '</span>';
    html += '<div class="graph" id="graph' + _plan.link_id + '" style="width : 100%;height : 100%;"></div>';
    html += '</div>';
    displayObject('graph', _plan.link_id, html, _plan);

    for (var i in options) {
        if (init(options[i].link_id) != '') {
            jeedom.history.drawChart({
                cmd_id: options[i].link_id,
                el: 'graph' + _plan.link_id,
                showLegend: init(_plan.display.showLegend, true),
                showTimeSelector: init(_plan.display.showTimeSelector, true),
                showScrollbar: init(_plan.display.showScrollbar, true),
                dateRange: init(_plan.display.dateRange, '7 days'),
                option: init(options[i].configuration, {}),
                global: false,
            });
        }
    }
}

