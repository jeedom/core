function initHome() {
    refreshMessageNumber();
    jeedom.object.all({
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (objects) {
            var li = '';
            for (var i in objects) {
                if (objects[i].isVisible == 1) {
                    var icon = '';
                    if (isset(objects[i].display) && isset(objects[i].display.icon)) {
                        icon = objects[i].display.icon;
                    }
                    li += '<a href="#" class="link ui-bottom-sheet-link ui-btn ui-btn-inline waves-effect waves-button" data-page="equipment" data-title="' + icon.replace(/\"/g, "\'") + ' ' + objects[i].name.replace(/\"/g, "\'") + '" data-option="' + objects[i].id + '"><span>' + icon + '</span> ' + objects[i].name + '</a>';
                }
            }
            $('#bottompanel_objectList').empty().append(li);
        }
    });

    jeedom.view.all({
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (views) {
            var li = '';
            for (var i in views) {
                var icon = '';
                if (isset(views[i].display) && isset(views[i].display.icon)) {
                    icon = views[i].display.icon;
                }
                li += '<a href="#" class="link ui-bottom-sheet-link ui-btn ui-btn-inline waves-effect waves-button" data-page="view" data-title="'+ icon.replace(/\"/g, "\'") + ' ' + views[i].name + '" data-option="' + views[i].id + '">'+ icon + ' ' + views[i].name + '</a>'
            }
            $('#bottompanel_viewList').empty().append(li);
        }
    });

    jeedom.plan.allHeader({
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (planHeader) {
            var li = '';
            for (var i in planHeader) {
                if (deviceInfo.type != 'phone' || (deviceInfo.type == 'phone' && planHeader[i].configuration.enableOnMobile == "1")) {
                    li += '<a class="ui-bottom-sheet-link ui-btn ui-btn-inline waves-effect waves-button" href="index.php?v=d&p=plan&plan_id=' + planHeader[i].id + '" data-ajax="false">' +init(planHeader[i].configuration['icon'])+' '+ planHeader[i].name + '</a>'
                }
            }
            $('#bottompanel_planList').empty().append(li);
        }
    });


    if (plugins.length > 0) {
        var li = '';
        for (var i in plugins) {
            li += '<a href="#" class="link ui-bottom-sheet-link ui-btn ui-btn-inline waves-effect waves-button" data-page="' + plugins[i].mobile + '" data-plugin="' + plugins[i].id + '" data-title="' + plugins[i].name + '">';
            li += '<img src="plugins/'+plugins[i].id +'/doc/images/'+plugins[i].id +'_icon.png" style="width : 20px;position:relative;top:5px;" onerror=\'this.style.display = "none"\' /> ';
            li +=  plugins[i].name;
            li +=  '</a>';
        }
        $('#bottompanel_pluginList').empty().append(li);
    } else {
        $('#bt_listPlugin').hide();
    }

    $('#bt_logout').off().on('click', function () {
        $.ajax({// fonction permettant de faire de l'ajax
            type: "POST", // methode de transmission des données au fichier php
            url: "core/ajax/user.ajax.php", // url du fichier php
            data: {
                action: "logout",
            },
            dataType: 'json',
            error: function (request, status, error) {
                handleAjaxError(request, status, error, $('#div_alert'));
            },
            success: function (data) { // si l'appel a bien fonctionné
            if (data.state != 'ok') {
                $('#div_alert').showAlert({message: data.result, level: 'danger'});
                return;
            }
            initApplication();
        }
    });
    });
    setTimeout(function(){$('#pagecontainer').css('padding-top','64px');; }, 100);

}

