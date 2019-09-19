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
      <img src="core/img/logo-jeedom-grand-nom-couleur.svg" style="position: relative; top:-5px;" height="50">
      <br>
      <a class="badge cursor" href="https://www.jeedom.com" target="_blank">Site</a> | 
      <a class="badge cursor" href="https://www.jeedom.com/blog/" target="_blank">Blog</a> | 
      <a class="badge cursor" href="https://www.jeedom.com/forum/" target="_blank">Forum</a> | 
      <a class="badge cursor" href="https://jeedom.github.io/documentation/" target="_blank">Doc</a>
      <br><br>
      {{Version}} : <span class="badge" style="cursor:default!important"><?php echo jeedom::version(); ?></span>
      <br>
      {{Source}} : <span class="badge" style="cursor:default!important"><?php echo config::byKey('core::repo::provider'); ?></span>
      <br>
      {{Branche}} : <span class="badge" style="cursor:default!important"><?php echo config::byKey('core::branch'); ?></span>
      <br>
      {{Système}} : <span class="badge" style="cursor:default!important"><?php echo jeedom::getHardwareName() ?></span>
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
</script>
