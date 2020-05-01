<?php
if (!isConnect()) {
  throw new Exception('{{401 - Accès non autorisé}}');
}
$licenceText = file_get_contents('/var/www/html/desktop/modal/about.txt');
?>

<div class="col-lg-12">
  <form class="form-horizontal col-lg-12">
    <br/>
    <center>
      <img id="logoJeedom" src="core/img/logo-jeedom-grand-nom-couleur.svg" style="position: relative; top:-5px;" height="40">
      <br>
      <a class="badge cursor" href="https://www.jeedom.com" target="_blank">Site</a> |
      <a class="badge cursor" href="https://blog.jeedom.com/" target="_blank">Blog</a> |
      <a class="badge cursor" href="https://community.jeedom.com/" target="_blank">Community</a> |
      <a class="badge cursor" href="https://doc.jeedom.com/" target="_blank">Doc</a>
      <br><br>
      {{Version}} : <span class="badge" style="cursor:default!important"><?php echo jeedom::version(); ?></span>
      <br>
      {{Source}} : <span class="badge" style="cursor:default!important"><?php echo config::byKey('core::repo::provider'); ?></span>
      <br>
      {{Branche}} : <span class="badge" style="cursor:default!important"><?php echo config::byKey('core::branch'); ?></span>
      <br>
      {{Système}} : <span class="badge" style="cursor:default!important"><?php echo jeedom::getHardwareName() ?></span>
      <br><br>
      <a class="btn btn-xs" id="bt_changelogCore" target="_blank"><i class="fas fa-book"></i> {{Changelog}}</a>
      <a class="btn btn-xs" id="bt_faq" target="_blank"><i class="fas fa-question-circle"></i> {{FAQ}}</a>
      <br><br>
    </center>

    <div class="form-group">
      <center class="label-info">
        <span class="label">{{Auteur(s)}}</span>
      </center>
      <center>
        <br>
        <span>Jeedom SAS</span>
        <br><br>
      </center>
    </div>

    <div class="form-group">
      <center class="label-info">
        <span class="label">Licence</span>
      </center>
      <center>
        <textarea readonly class="form-control" style="resize:none!important; min-height:15em; padding:5px; height:15em;"><?php echo $licenceText ?></textarea>
      </center>
    </div>

    <div class="form-group">
      <center>
        <br>
        <a class="cursor" href="https://www.jeedom.com" target="_blank">www.jeedom.com</a>
        <br>
      </center>
    </div>
  </form>
</div>

<script>
$(function(){
    var currentTheme = $('body').attr('data-theme')
    if (currentTheme !== undefined && currentTheme.endsWith('Dark')) {
      $('#logoJeedom').attr('src', jeedom.theme.logo_dark)
    }
    var parentWidth = $( window ).width()
    var parentHeight = $( window ).height()
    if (parentWidth > 850 && parentHeight > 750) {
      $('#md_modal').dialog("option", "width", 800).dialog("option", "height", 650)
      $("#md_modal").dialog({
        position: {
          my: "center center",
          at: "center center",
          of: window
        }
      })
    }
})

$('body').off('click','#bt_changelogCore').on('click','#bt_changelogCore',function() {
  jeedom.getDocumentationUrl({
    page: 'changelog',
    theme: $('body').attr('data-theme'),
    error: function(error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'})
    },
    success: function(url) {
      window.open(url,'_blank')
    }
  })
})
$('body').off('click','#bt_faq').on('click','#bt_faq',function() {
  jeedom.getDocumentationUrl({
    page: 'faq',
    theme: $('body').attr('data-theme'),
    error: function(error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'})
    },
    success: function(url) {
      window.open(url,'_blank')
    }
  })
})
</script>
