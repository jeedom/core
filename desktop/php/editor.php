<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}

global $JEEDOM_INTERNAL_CONFIG;

if (init('type', '') == 'custom') {
	$rootPaths = ['desktop/custom', 'mobile/custom'];
	foreach ($rootPaths as $rootPath) {
		$path = __DIR__ . '/../../' . $rootPath;
		if (!file_exists($path)) {
			mkdir($path);
			}
		$filePath = $path . '/custom.css';
		if (!is_file($filePath)) {
			@file_put_contents($filePath, '/* Custom CSS Core ' . jeedom::version() . ' */');
		}
		$filePath = $path . '/custom.js';
		if (!is_file($filePath)) {
			@file_put_contents($filePath, '/* Custom js Core ' . jeedom::version() . ' */');
		}
	}
}

sendVarToJS([
	'editorType' => init('type', ''),
	'customActive' => config::byKey('enableCustomCss')
]);

//Core CodeMirror:
include_file('3rdparty', 'codemirror/lib/codemirror', 'js');
include_file('3rdparty', 'codemirror/lib/codemirror', 'css');
include_file('3rdparty', 'codemirror/addon/mode/loadmode', 'js');
include_file('3rdparty', 'codemirror/mode/meta', 'js');
//Core CodeMirror addons:
include_file('3rdparty', 'codemirror/addon/edit/matchbrackets', 'js');
include_file('3rdparty', 'codemirror/addon/selection/active-line', 'js');
include_file('3rdparty', 'codemirror/addon/search/search', 'js');
include_file('3rdparty', 'codemirror/addon/search/searchcursor', 'js');
include_file('3rdparty', 'codemirror/addon/dialog/dialog', 'js');
include_file('3rdparty', 'codemirror/addon/dialog/dialog', 'css');
include_file('3rdparty', 'codemirror/addon/fold/brace-fold', 'js');
include_file('3rdparty', 'codemirror/addon/fold/comment-fold', 'js');
include_file('3rdparty', 'codemirror/addon/fold/foldcode', 'js');
include_file('3rdparty', 'codemirror/addon/fold/indent-fold', 'js');
include_file('3rdparty', 'codemirror/addon/fold/markdown-fold', 'js');
include_file('3rdparty', 'codemirror/addon/fold/xml-fold', 'js');
include_file('3rdparty', 'codemirror/addon/fold/foldgutter', 'js');
include_file('3rdparty', 'codemirror/addon/fold/foldgutter', 'css');
include_file('3rdparty', 'codemirror/theme/monokai', 'css');

//elFinder:
include_file('3rdparty', 'elfinder/css/elfinder.min', 'css');
include_file('3rdparty', 'elfinder/themes/css/theme-gray', 'css');
include_file('desktop', 'editor', 'css');
include_file('3rdparty', 'elfinder/js/elfinder.full', 'js');

$lang = substr(config::byKey('language', 'core', 'en'), 0, 2);
if ($lang != 'en') {
	$plufinSrc = '3rdparty/elfinder/js/i18n/elfinder.' . $lang . '.js';
	echo '<script src="' . $plufinSrc . '"></script>';
}


include_file("desktop", "editor", "js");
?>

<div id="elfinder" class=""></div>

<div id="md_widgetCreate" style="display: none;overflow-x: hidden;">
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
					<select  id="sel_widgetType">
						<?php
						foreach ($JEEDOM_INTERNAL_CONFIG['cmd']['type'] as $key => $value) {
							echo '<option value="'.$key.'"><a>'.$value['name'].'</option>';
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
									echo '<option data-type="'.$key.'" value="'.$skey.'"><a>'.$svalue['name'].'</option>';
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