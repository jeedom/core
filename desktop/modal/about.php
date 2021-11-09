<?php
/* This file is part of Jeedom.
*
* Jeedom is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Jeedom is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
*/

if (!isConnect()) {
  throw new Exception('{{401 - Accès non autorisé}}');
}
$licenceText = file_get_contents('/var/www/html/desktop/modal/about.txt');
?>

<div class="col-lg-12">
  <form class="form-horizontal col-lg-12">
    <br/>
    <div class="center">
      <img id="logoJeedom" src="core/img/logo-jeedom-grand-nom-couleur.svg" style="position: relative; top:-5px;" height="40">
      <br>
      <a class="badge cursor" href="https://www.jeedom.com" target="_blank">Site</a> |
      <a class="badge cursor" href="https://blog.jeedom.com/" target="_blank">Blog</a> |
      <a class="badge cursor" href="https://community.jeedom.com/" target="_blank">Community</a> |
      <?php if (config::byKey('doc::base_url', 'core') != ''){ ?>
        <a class="badge cursor" href="<?php echo config::byKey('doc::base_url', 'core'); ?>" target="_blank">Doc</a> |
      <?php } ?>
      <a class="badge cursor" href="https://market.jeedom.com/" target="_blank">Market</a>
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
      <a class="btn btn-xs" id="bt_firstUse" target="_blank"><i class="fas fa-image"></i> {{Guide de démarrage}}</a>
      <br><br>
    </div>
    
    <div class="form-group">
      <div class="center label-info">
        <span class="label">{{Auteur(s)}}</span>
      </div>
      <div class="center">
        <br>
        <span>Jeedom SAS</span>
        <br><br>
      </div>
    </div>
    
    <div class="form-group">
      <div class="center label-info">
        <span class="label">Licence</span>
      </div>
      <div class="center">
        <textarea readonly class="form-control" style="resize:none!important; min-height:15em; padding:5px; height:15em;"><?php echo $licenceText ?></textarea>
      </div>
    </div>
    
    <div class="form-group">
      <div class="center">
        <br>
        <a class="cursor" href="https://www.jeedom.com" target="_blank">www.jeedom.com</a>
        <br>
      </div>
    </div>
  </form>
</div>

<script>
$(function() {
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
      $.fn.showAlert({message: error.message, level: 'danger'})
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
      $.fn.showAlert({message: error.message, level: 'danger'})
    },
    success: function(url) {
      window.open(url,'_blank')
    }
  })
})

$('#bt_firstUse').on('click',function(){
  $('#md_modal').dialog({title: "{{Bienvenue dans Jeedom}}"}).load('index.php?v=d&modal=first.use').dialog('open')
})
</script>