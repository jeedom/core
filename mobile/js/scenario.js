function initScenario() {
    jeedom.scenario.toHtml({
        id: 'all',
        version: 'mobile',
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (htmls) {
            var html = '';
            for (var i in htmls) {
                html += htmls[i];
            }
            $('#div_displayScenario').empty().html(html).trigger('create');
            setTileSize('.scenario');
            $('#div_displayScenario').packery({gutter : 4});
        }
    });
    $(window).on("orientationchange", function (event) {
        setTileSize('.scenario');
        $('#div_displayScenario').packery({gutter : 4});
    });
}