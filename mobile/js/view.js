function initView(_view_id) {
    jeedom.view.all({
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (views) {
            var li = ' <ul data-role="listview">';
            for (var i in views) {
                var icon = '';
                if (isset(views[i].display) && isset(views[i].display.icon)) {
                    icon = views[i].display.icon;
                }
                li += '<li><a href="#" class="link" data-page="view" data-title="'+ icon.replace(/\"/g, "\'") + ' ' + views[i].name + '" data-option="' + views[i].id + '">'+ icon + ' ' + views[i].name + '</a></li>'
            }
            li += '</ul>';
            panel(li);
        }
    });

    if (isset(_view_id) && is_numeric(_view_id)) {
        jeedom.history.chart = [];
        jeedom.view.toHtml({
            id: _view_id,
            version: 'mview',
            error: function (error) {
                $('#div_alert').showAlert({message: error.message, level: 'danger'});
            },
            success: function (html) {
                displayView(html);
            }
        });
    } else {
        $('#bottompanel').panel('open');
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
    if(isset(html.raw) && isset(html.raw.img) && html.raw.img != ''){
        setBackgroundImage(html.raw.img);
    }else{
        setBackgroundImage('');
    }
    try{
        $('#div_displayView').empty().html(html.html).trigger('create');
    }catch(err) {
        console.log(err);
    }
    if (deviceInfo.type == 'phone') {
        $('.chartContainer').width((deviceInfo.width - 20));
    } else {
        $('.chartContainer').width(((deviceInfo.width / 2) - 20));
    }
    setTileSize('.eqLogic');
    $('.eqLogicZone').packery({gutter : 4});
    $('#div_displayView .ui-table-columntoggle-btn').remove();
}