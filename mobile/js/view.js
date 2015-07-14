function initView(_view_id) {
    jeedom.view.all({
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (views) {
            var li = ' <ul data-role="listview">';
            for (var i in views) {
                li += '<li><a href="#" class="link" data-page="view" data-title="' + views[i].name + '" data-option="' + views[i].id + '">' + views[i].name + '</a></li>'
            }
            li += '</ul>';
            panel(li);
        }
    });

    if (isset(_view_id) && is_numeric(_view_id)) {
        jeedom.history.chart = [];
        jeedom.view.toHtml({
            id: _view_id,
            version: 'mobile',
            error: function (error) {
                $('#div_alert').showAlert({message: error.message, level: 'danger'});
            },
            success: function (html) {
                displayView(html);
            }});
    } else {
        $('#panel_right').panel('open');
    }

    $(window).on("orientationchange", function (event) {
        if (deviceInfo.type == 'phone') {
            $('.chartContainer').width((deviceInfo.width - 20));
        } else {
            $('.chartContainer').width(((deviceInfo.width / 2) - 20));
        }
        setTileSize('.eqLogic');
        setTileSize('.scenario');
        $('.eqLogicZone').packery({gutter : 4});
    });
}

function displayView(html) {
    $('#div_displayView').empty().html(html.html).trigger('create');
    if (deviceInfo.type == 'phone') {
        $('.chartContainer').width((deviceInfo.width - 20));
    } else {
        $('.chartContainer').width(((deviceInfo.width / 2) - 20));
    }
    setTileSize('.eqLogic');
    setTileSize('.scenario');
    $('.eqLogicZone').packery({gutter : 4});
}