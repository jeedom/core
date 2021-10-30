<?php
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$plan3dHeader = null;
$list_plan3dHeader = plan3dHeader::all();
if (init('plan3d_id') == '') {
	if ($_SESSION['user']->getOptions('defaultDesktopPlan3d') != '') {
		$plan3dHeader = plan3dHeader::byId($_SESSION['user']->getOptions('defaultDesktopPlan3d'));
	}
	if (!is_object($plan3dHeader) && isset($list_plan3dHeader[0])) {
		$plan3dHeader = $list_plan3dHeader[0];
	}
} else {
	$plan3dHeader = plan3dHeader::byId(init('plan3d_id'));
	if (!is_object($plan3dHeader) && isset($list_plan3dHeader[0])) {
		$plan3dHeader = $list_plan3dHeader[0];
	}
}
if (is_object($plan3dHeader)) {
	sendVarToJS('plan3dHeader_id', $plan3dHeader->getId());
} else {
	sendVarToJS('plan3dHeader_id', -1);
}
?>

<div class="row <?php if (init('fullscreen') != 1) {
					echo 'row-overflow';
				}	?>">
	<div class="col-lg-10" style="height: 100%" id="div_colPlan3d">
		<div class="div_background3d" style="height: 100%">
			<div class="container-fluid" id="div_display3d" style="position: relative;padding:0;user-select: none;-khtml-user-select: none;-o-user-select: none;-moz-user-select: -moz-none;-webkit-user-select: none;height: 100%">
			</div>
		</div>
	</div>
	<div class="col-lg-2 bs-sidebar" id="div_colMenu">
		<div id="div_btEdit" style="display: none;">
			<a class="btn btn-default btn-xs" id="bt_plan3dHeaderConfigure" title="{{Configuration du design 3D}}"><i class="fas fa-cogs"></i></a>
			<a class="btn btn-default btn-xs" id="bt_plan3dHeaderAdd" title="{{Créer un nouveau design 3D}}"><i class="fas fa-plus"></i></a>
			<a class="btn btn-default btn-xs" id="bt_showAllObject" title="{{Afficher tous les objets du design 3D}}"><i class="fas fa-eye"></i></a>
		</div>
		<legend>{{Informations}}
			<a class="btn btn-default btn-xs pull-right" id="bt_editMode" title="{{Edition}}"><i class="fas fa-pencil-alt"></i></a>
			<a class="btn btn-default btn-xs pull-right" id="bt_plan3dHeaderFullScreen" title="{{Plein écran}}"><i class="fas fa-desktop"></i></a>
		</legend>

		<ul id="ul_plan3d" class="nav nav-list bs-sidenav">
			<?php
			$plan3dHeaders = plan3dHeader::all();
			foreach ($plan3dHeaders as $li_plan3dHeader) {
				if (!$li_plan3dHeader->hasRight('r')) {
					continue;
				}
				if ($li_plan3dHeader->getId() == $plan3dHeader->getId()) {
					echo '<li class="cursor active" ><a data-3d_id="' . $li_plan3dHeader->getId() . '" href="index.php?v=d&p=plan3d&plan3d_id=' . $li_plan3dHeader->getId() . '" style="padding: 2px 0px;">' . $li_plan3dHeader->getName() . '</a></li>';
				} else {
					echo '<li class="cursor" ><a data-3d_id="' . $li_plan3dHeader->getId() . '" href="index.php?v=d&p=plan3d&plan3d_id=' . $li_plan3dHeader->getId() . '" style="padding: 2px 0px;">' . $li_plan3dHeader->getName() . '</a></li>';
				}
			}
			?>
		</ul>
	</div>
</div>

<div id="md_plan3dWidget" style="position : fixed;top:60px;left:20px"></div>

<?php
include_file('3rdparty', 'three.js/three.min', 'js');
include_file('3rdparty', 'three.js/loaders/LoaderSupport', 'js');
include_file('3rdparty', 'three.js/loaders/OBJLoader', 'js');
include_file('3rdparty', 'three.js/loaders/MTLLoader', 'js');
include_file('3rdparty', 'three.js/controls/TrackballControls', 'js');
include_file('3rdparty', 'three.js/controls/OrbitControls', 'js');
include_file('3rdparty', 'three.js/renderers/Projector', 'js');
include_file('3rdparty', 'three.js/objects/Sky', 'js');
include_file('desktop', 'plan3d', 'js');
?>