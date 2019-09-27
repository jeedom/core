$('body').attr('data-page', 'about')
function initAbout() {
  var currentTheme = $('body').attr('data-theme')
  if (currentTheme.endsWith('Dark')) {
    $('#logoJeedom').attr('src', jeedom.theme.logo_dark)
  }  
  $('.licenceText').removeClass('ui-body-inherit')
  $.ajax({
    url:'../desktop/modal/about.txt',
    success: function (data){
      $('.licenceText').val(data)
    }
  })
  jeedom.version({
    success : function(version) {
      $('#jeedomVersion').html(version)
    }
  });
  jeedom.config.load({
    configuration:{'core::repo::provider':'','core::branch':'','hardware_name' : ''},
    success: function (data) {
      $('#jeedomProvider').text(data['core::repo::provider'])
      $('#jeedomBranch').text(data['core::branch'])
      $('#jeedomHardwareName').text(data['hardware_name'])
    }
  })
}
