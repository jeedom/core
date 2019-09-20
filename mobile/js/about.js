$('body').attr('data-page', 'about')

function initAbout() {
    $('.licenceText').removeClass('ui-body-inherit')
    $.ajax({
        url:'../desktop/modal/about.txt',
        success: function (data){
            $('.licenceText').val(data)
        }
    })

    jeedom.config.load({
        configuration: 'version',
        success: function (data) {
            $('#jeedomVersion').text(data)
        }
    })

    jeedom.config.load({
        configuration: 'core::repo::provider',
        success: function (data) {
            $('#jeedomProvider').text(data)
        }
    })

    jeedom.config.load({
        configuration: 'core::branch',
        success: function (data) {
            $('#jeedomBranch').text(data)
        }
    })

    jeedom.config.load({
        configuration: 'hardware_name',
        success: function (data) {
            $('#jeedomHardwareName').text(data)
        }
    })


}
