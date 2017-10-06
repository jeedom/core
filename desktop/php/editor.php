<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
?>

<div class="row row-overflow">
	<div class="col-lg-2">
		<legend><i class="fa fa-folder"></i> {{Dossiers}}</legend>
		<div id="div_treeFolder">
			<ul id="ul_Folder">
				<?php
foreach (ls(dirname(__FILE__) . '/../../', '*', false, array('folders')) as $folder) {
	echo '<li data-jstree=\'{"opened":true}\'><a data-path="' . dirname(__FILE__) . '/../../' . $folder . '">' . $folder . '</a></li>';
}
?>
			</ul>
		</div>

	</div>

	<div class="col-lg-2">
		<legend><i class="fa fa-file"></i> {{Fichiers}}</legend>
		<div id="div_fileList"></div>
	</div>

	<div class="col-lg-8">
		<legend><i class="fa fa-pencil"></i> {{Edition}}
			<a class="btn btn-success btn-xs pull-right" id="bt_saveFile" style="position: relative;top:4px;"><i class="fa fa-check"></i> {{Sauvegarder}}</a>
			<a class="btn btn-danger btn-xs pull-right" id="bt_deleteFile" style="position: relative;top:4px;"><i class="fa fa-times"></i> {{Supprimer}}</a>
			<a class="btn btn-default btn-xs pull-right" id="bt_createFile" style="position: relative;top:4px;"><i class="fa fa-file"></i> {{Nouveau}}</a>
		</legend>
		<div class="alert alert-danger">{{Attention la moindre erreur dans la modification d'un fichier peut rendre votre Jeedom non fonctionnel et vous interdir l'accès au support}}</div>
		<textarea class="form-control ta_autosize" id="ta_fileContent"></textarea>
	</div>
</div>



<?php include_file("desktop", "editor", "js");?>