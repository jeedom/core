<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}

$rootPath = false;
if (init('type') == 'widget') {
	$rootPath = 'data/customTemplates';
}
sendVarToJS([
	'rootPath' => $rootPath
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
?>

<div id="elfinder" class=""></div>

<?php

$lang = substr(config::byKey('language', 'core', 'en'), 0, 2);
$plufinSrc = '3rdparty/elfinder/js/i18n/elfinder.' . $lang .'.js';
echo '<script src="'.$plufinSrc.'"></script>';

include_file("desktop", "editor", "js");
?>