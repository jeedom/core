<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$rootPath =  __DIR__ . '/../../';
if (init('type') == 'widget') {
	$rootPath = __DIR__ . '/../../data/customTemplates/';
}
if (init('root') != '') {
	$rootPath = __DIR__ . '/../../' . init('root');
}
sendVarToJS([
	'rootPath' => $rootPath,
	'editorType' => init('type')
]);
global $JEEDOM_INTERNAL_CONFIG;
?>

<div class="row row-overflow">
	<div class="col-lg-2">
		<legend>
			<?php
			if (init('type') == 'widget') {
				echo '<a class="btn btn-default btn-sm pull-left" href="index.php?v=d&p=widgets" style="position:relative;top:-6px;"><i class="fas fa-arrow-left"></i> {{Widget}}</a>';
			}
			?>
			<i class="fas fa-folder"></i> {{Dossiers}}
			<i class="fas fa-plus pull-right cursor" id="bt_createFolder"></i>
		</legend>
		<div id="div_treeFolder">
			<ul id="ul_Folder">
				<?php
				foreach (ls($rootPath, '*', false, array('folders')) as $folder) {
					echo '<li data-jstree=\'{"opened":true}\'><a data-path="' . $rootPath . $folder . '">' . $folder . '</a></li>';
				}
				?>
			</ul>
		</div>
	</div>

	<div class="col-lg-2">
		<legend><i class="far fa-file"></i> {{Fichiers}}</legend>
		<div id="div_fileList" style="list-style-type: none;"></div>
	</div>

	<div id="editorContainer" class="col-lg-8">
		<div id="editorOptions">
			<legend><i class="fas fa-edit"></i> {{Edition}} <span class="success" id="span_editorFileName"></span>
				<div class="input-group pull-right" style="display:inline-flex">
					<span class="input-group-btn">
						<a class="btn btn-sm roundedLeft" id="bt_createFile" style="position:relative;top:-6px;"><i class="far fa-file"></i> {{Nouveau}}</a><a class="btn btn-success btn-sm" id="bt_saveFile" style="position:relative;top:-6px;"><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a><a class="btn btn-danger btn-sm roundedRight" id="bt_deleteFile" style="position:relative;top:-6px;"><i class="fas fa-times"></i> {{Supprimer}}</a>
					</span>
				</div>
			</legend>
		</div>
		<textarea class="form-control hidden" id="ta_fileContent"></textarea>
	</div>
</div>

<div id="md_widgetCreate" style="overflow-x: hidden;">
	<form class="form-horizontal">
		<fieldset>
			<div class="form-group">
				<label class="col-xs-4 control-label">{{Version}}</label>
				<div class="col-xs-8">
					<select id="sel_widgetVersion">
						<option value="dashboard">{{Dashboard}}</option>
						<option value="mobile">{{Mobile}}</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-4 control-label">{{Type}}</label>
				<div class="col-xs-8">
					<select id="sel_widgetType">
						<?php
						foreach ($JEEDOM_INTERNAL_CONFIG['cmd']['type'] as $key => $value) {
							echo '<option value="' . $key . '"><a>' . $value['name'] . '</option>';
						}
						?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-4 control-label">{{Sous-type}}</label>
				<div class="col-xs-8">
					<select id="sel_widgetSubtype">
						<option value="" data-default="1"><a></option>
						<?php
						foreach ($JEEDOM_INTERNAL_CONFIG['cmd']['type'] as $key => $value) {
							foreach ($value['subtype'] as $skey => $svalue) {
								echo '<option data-type="' . $key . '" value="' . $skey . '"><a>' . $svalue['name'] . '</option>';
							}
						}
						?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-4 control-label">{{Nom}}</label>
				<div class="col-xs-8">
					<input id="in_widgetName" class="form-control" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-4 control-label"></label>
				<div class="col-xs-8">
					<a class="btn btn-success" style="color:white;" id="bt_widgetCreate"><i class="fas fa-check"></i> {{Créer}}</a>
				</div>
			</div>
</div>
</fieldset>
</form>
</div>
<?php
include_file('3rdparty', 'jquery.tree/jstree.min', 'js');
include_file("desktop", "editor", "js");
include_file('3rdparty', 'codemirror/addon/selection/active-line', 'js');
include_file('3rdparty', 'codemirror/addon/search/search', 'js');
include_file('3rdparty', 'codemirror/addon/search/searchcursor', 'js');
include_file('3rdparty', 'codemirror/addon/dialog/dialog', 'js');
include_file('3rdparty', 'codemirror/addon/dialog/dialog', 'css');

include_file('3rdparty', 'codemirror/addon/fold/brace-fold', 'js');
include_file('3rdparty', 'codemirror/addon/fold/comment-fold', 'js');
include_file('3rdparty', 'codemirror/addon/fold/foldcode', 'js');
include_file('3rdparty', 'codemirror/addon/fold/indent-fold', 'js');
include_file('3rdparty', 'codemirror/addon/fold/foldgutter', 'js');
include_file('3rdparty', 'codemirror/addon/fold/foldgutter', 'css');
?>