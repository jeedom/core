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
			<span style="font-size : 1em;" class="label">{{Auteur(s)}}</span>
		</center>
		<center>
			<span>Loïc</span>
		</center>
	</div>

	<div class="form-group">
		<center class="label-info">
			<span style="font-size : 1em;" class="label">{{Développeurs principaux}}</span>
		</center>
		<center>
			<span>Loïc</span><br>
			<span>Ludovic</span>
		</center>
	</div>

	<div class="form-group">
		<center class="label-info">
			<span style="font-size : 1em;" class="label">{{Traducteur(s)}}</span>
		</center>
		<center>
			<span>Loïc</span><br>
			<span>Marie</span><br>
			<a class="cursor" href="http://ma-maison-domotique.blogspot.com/" target="_blank">Mathieu (alias Algeroth)</a>
		</center>
	</div>

	<div class="form-group">
		<center class="label-info">
			<span style="font-size : 1em;" class="label">{{Beta-testeur(s)}}</span>
		</center>
		<center>
			<span>Awesome people</span>
		</center>
	</div>

	<div class="form-group">
		<center class="label-info">
			<span style="font-size : 1em;" class="label">{{Remerciements}}</span>
		</center>
		<center>
			  <span><a class="cursor" href="http://bootboxjs.com/" target="_blank">bootbox</a></span><br>
			  <span><a class="cursor" href="http://getbootstrap.com/" target="_blank">bootstrap</a></span><br>
			  <span><a class="cursor" href="http://codemirror.net/" target="_blank">codemirror</a></span><br>
			  <span><a class="cursor" href="https://github.com/mtdowling/cron-expression" target="_blank">cron-expression</a></span><br>
			  <span><a class="cursor" href="http://fontawesome.io/" target="_blank">font-awesome</a></span><br>
			  <span><a class="cursor" href="http://www.highcharts.com/" target="_blank">hightstock</a></span><br>
			  <span><a class="cursor" href="http://jquery.com/" target="_blank">jquery</a></span><br>
			  jquery.alert - Loïc
			  <a class="cursor" href="http://www.abeautifulsite.net/blog/2008/03/jquery-file-tree/" target="_blank">jquery.fileTree</a><br>
			  jquery.loading - Loïc<br>
			  <a class="cursor" href="http://jquerymobile.com/" target="_blank">jquery.mobile</a><br>
			  <a class="cursor" href="https://github.com/tactivos/jquery-sew" target="_blank">jquery.sew</a><br>
			  <a class="cursor" href="http://tablesorter.com/docs/" target="_blank">jquery.tablesorter</a><br>
			  <a class="cursor" href="http://www.jstree.com/" target="_blank">jquery.tree</a><br>
			  <a class="cursor" href="http://jqueryui.com/" target="_blank">jquery.ui</a><br>
			  jquery.value - Loïc<br>
			  <a class="cursor" href="http://phpjs.org/" target="_blank">php.js</a><br>
			  <a class="cursor" href="https://github.com/PHPMailer/PHPMailer" target="_blank">PHPMailer</a><br>
		</center>
	</div>


	<div class="form-group">
		<center class="label-info">
			<span style="font-size : 1em;" class="label">Licence</span>
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
