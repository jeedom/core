function initEquipment(_object_id) {
    jeedom.object.all({
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (objects) {
            var li = ' <ul data-role="listview">';
            for (var i in objects) {
                if (objects[i].isVisible == 1) {
                    var icon = '';
                    if (isset(objects[i].display) && isset(objects[i].display.icon)) {
                        icon = objects[i].display.icon;
                    }
                    li += '<li></span><a href="#" class="link" data-page="equipment" data-title="' + icon.replace(/\"/g, "\'") + ' ' + objects[i].name + '" data-option="' + objects[i].id + '"><span>' + icon + '</span> ' + objects[i].name + '</a></li>';
                }
            }
            li += '</ul>';
            panel(li);
        }
    });

    if (isset(_object_id) && is_numeric(_object_id)) {
        jeedom.object.toHtml({
            id: _object_id,
            version: 'mobile',
            error: function (error) {
                $('#div_alert').showAlert({message: error.message, level: 'danger'});
            },
            success: function (html) {
                $('#div_displayEquipement').empty().html(html).trigger('create');
                setTileSize('.eqLogic');
                setTimeout(function () {
                    $('#div_displayEquipement').packery();
                }, 10);
            }
        });
    } else {
        $('#panel_right').panel('open');
    }

    $(window).on("orientationchange", function (event) {
        deviceInfo = getDeviceType();
        setTileSize('.eqLogic');
        $('#div_displayEquipement').packery();
    });
}