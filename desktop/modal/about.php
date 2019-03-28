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
      <a class="cursor" href="https://www.jeedom.com" target="_blank">
        <img src="core/img/logo-jeedom-grand-nom-couleur.svg" style="position: relative; top:-5px;" height="50">
      </a><br>
      <br>
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
        <textarea readonly class="form-control" style="resize:none;min-height:25em;padding:15px;">
          <?php echo $licenceText ?>
        </textarea>
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
