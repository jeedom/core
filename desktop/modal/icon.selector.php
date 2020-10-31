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
$rootPath = __DIR__ . '/../../data/';

sendVarToJS([
	'tabimg' => init('tabimg'),
	'selectIcon' => init('selectIcon', 0),
	'colorIcon' => init('colorIcon', 0)
]);
?>

<div style="display: none;" id="div_iconSelectorAlert"></div>
<ul class="nav nav-tabs" role="tablist">
	<li role="presentation" class="active"><a href="#tabicon" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-icons"></i> {{Icône}}</a></li>
	<?php if(init('tabimg') == 1 || init('showimg') == 1){ ?>
		<li role="presentation"><a href="#tabimg" aria-controls="home" role="tab" data-toggle="tab"><i class="far fa-images"></i> {{Image}}</a></li>
	<?php } ?>
</ul>

<div class="tab-content" style="overflow-y:scroll;">
	<div id="mySearch" class="input-group" style="margin-left:6px;margin-top:6px">
		<div class="input-group-btn">
			<select class="form-control roundedLeft" style="width : 200px;" id="sel_colorIcon">
				<option value="">{{Aucune}}</option>
				<option value="icon_blue">{{Bleu}}</option>
				<option value="icon_yellow">{{Jaune}}</option>
				<option value="icon_orange">{{Orange}}</option>
				<option value="icon_red">{{Rouge}}</option>
				<option value="icon_green">{{Vert}}</option>
			</select>
		</div>
		<input class="form-control" placeholder="{{Rechercher}}" id="in_searchIconSelector">
		<div class="input-group-btn">
			<a id="bt_resetSearch" class="btn roundedRight" style="width:30px"><i class="fas fa-times"></i> </a>
		</div>
	</div>

	<?php if(init('tabimg') == 1 || init('showimg') == 1){ ?>
		<div role="tabpanel" class="tab-pane" id="tabimg" style="width:calc(100% - 20px);">
			<span class="btn btn-default btn-file pull-right">
				<i class="fas fa-cloud-upload-alt"></i> {{Envoyer}}<input id="bt_uploadImageIcon" type="file" name="file" multiple="multiple" data-path="" style="display: inline-block;">
			</span>

			<div class="imgContainer" style="padding-top: 32px;display:flex;align-items:flex-start;">
 				<div id="div_treeFolder" style="height:100%;min-width:180px;">
				<ul id="ul_Folder">
					<?php
					foreach (ls($rootPath, 'img', false, array('folders')) as $folder) {
						echo '<li><a data-path="' . $rootPath . $folder . '">' . $folder . '</a></li>';
					}
					?>
				</ul>
			</div>
			<div id="div_imageGallery" style="height:100%;margin-left:15px;display:flex;flex-wrap:wrap;">

				</div>
			</div>
		</div>
	<?php }
		include_file('3rdparty', 'jquery.tree/jstree.min', 'js');
		?>

<script>
$( document ).ready(function() {
	$('#div_treeFolder').off('click').on('select_node.jstree', function(node, selected) {
		if (selected.node.a_attr['data-path'] != undefined) {
	   	var path = selected.node.a_attr['data-path'];
			printFileFolder(path);
    	var ref = $('#div_treeFolder').jstree(true)
    	var sel = ref.get_selected()[0]
    	ref.open_node(sel)
    	var nodesList = ref.get_children_dom(sel)
    	if (nodesList.length != 0) {
      	return
    	}
    	jeedom.getFileFolder({
      	type : 'folders',
      	path : path,
      	error: function(error) {
        	$('#div_alert').showAlert({message: error.message, level: 'danger'})
      	},
      	success : function(data) {
        	for (var i in data) {
          	node = ref.create_node(sel, {"type":"folder","text":data[i],state:{opened:true},a_attr:{'data-path':path+data[i]}})
          	$('li#'+node+' a').addClass('li_folder')
        	}
      	}
    	})
  	}
	})

	$("#div_treeFolder").jstree({
		"core" : {
  		"check_callback": true
		}
	});

	$('#div_treeFolder ul li a:first').click();

	$('#div_imageGallery').on('click', '.divIconSel', function() {
		$('.divIconSel').removeClass('iconSelected').find('.iconSel').css('outline','none');
		$(this).closest('.divIconSel').addClass('iconSelected').find('.iconSel').css('outline','solid 1px #94CA02');
	})

	$('#div_imageGallery').on('dblclick', '.divIconSel', function() {
		$('.divIconSel').removeClass('iconSelected');
		$(this).closest('.divIconSel').addClass('iconSelected');
		$('#mod_selectIcon').dialog("option", "buttons")['Valider'].apply($('#mod_selectIcon'));
	})

	$('a[href="#tabicon"]').on('click', function () {
		$.hideAlert();
	})

});

	$('#bt_uploadImageIcon').fileupload({
    add: function (e, data) {
			let currentPath = $('#bt_uploadImageIcon').attr('data-path');
			data.url = 'core/ajax/jeedom.ajax.php?action=uploadImageIcon&filepath='+currentPath;
      data.submit();
    },
		done: function(e, data) {
			if (data.result.state != 'ok') {
				$('#div_iconSelectorAlert').showAlert({message: data.result.result, level: 'danger'});
				return;
			}
			$('.jstree-clicked').click();
			$('#div_iconSelectorAlert').showAlert({message: 'Fichier(s) ajouté(s) avec succès', level: 'success'});
		}
	});

	$('#div_imageGallery').off('click').on('click', '.bt_removeImgIcon',function() {
		$.hideAlert();
		var filepath = $(this).attr('data-realfilepath');
		bootbox.confirm('{{Êtes-vous sûr de vouloir supprimer cette image}} <span style="font-weight: bold ;">' + filepath + '</span> ?', function(result) {
			if (result) {
				jeedom.removeImageIcon({
					filepath : filepath,
					error: function(error) {
						$('#div_iconSelectorAlert').showAlert({message: error.message, level: 'danger'});
					},
					success: function(data) {
						$('.jstree-clicked').click();
						$('#div_iconSelectorAlert').showAlert({message: 'Fichier supprimé avec succès', level: 'success'});
					}
				})
			}
		})
	});

	function printFileFolder(_path) {
		$.hideAlert()
	  CURRENT_FOLDER = _path;
	  jeedom.getFileFolder({
	    type : 'files',
	    path : _path,
	    error: function(error) {
	      $('#div_alert').showAlert({message: error.message, level: 'danger'})
	    },
	    success : function(data){
				let realPath = _path.substr(_path.search('data/'));
				$('#bt_uploadImageIcon').attr('data-path', realPath);
	      $('#div_imageGallery').empty();
	      var div = '';
	      for (var i in data) {
					div += '<div class="divIconSel divImgSel" style="height:140px;min-width:120px;display:flex;flex-direction:column;align-items:center;">';
					div += '<div class="cursor iconSel" style="width:80px;height:80px;display:flex;justify-content:center;align-items:center;"><img class="img-responsive" src="'+realPath+data[i]+'"/></div>';
					div += '<div class="iconDesc" style="font-size: 0.8em;">'+data[i].substr(0,15)+'</div>';
					div += '<center><a class="btn btn-danger btn-xs bt_removeImgIcon" data-realfilepath="'+realPath+data[i]+'"><i class="fas fa-trash"></i> {{Supprimer}}</a></center>';
					div += '</div>';
	      }
	      $('#div_imageGallery').append(div)
	    }
	  })
	}
</script>

	<div role="tabpanel" class="tab-pane active" id="tabicon" style="width:calc(100% - 20px)">
		<?php
		$scanPaths = array('core/css/icon', 'data/fonts');
		$div = '';
		foreach ($scanPaths as $root) {
			$ls = ls($root, '*');
			foreach ($ls as $dir) {
				$root .= '/';
				if (!is_dir($root . $dir) || !file_exists($root . $dir . '/style.css')) {
					continue;
				}
				$fontfile = $root . $dir . 'fonts/' . substr($dir, 0, -1) . '.ttf';
				if (!file_exists($fontfile)) continue;

				$css = file_get_contents($root . $dir . '/style.css');
				$research = strtolower(str_replace('/', '', $dir));
				preg_match_all("/\." . $research . "-(.*?):/", $css, $matches, PREG_SET_ORDER);
				$div .= '<div class="iconCategory"><legend>' . str_replace('/', '', $dir) . '</legend>';

				$number = 1;
				foreach ($matches as $match) {
					if (isset($match[0])) {
						if ($number == 1) {
							$div .= '<div class="row">';
						}
						$div .= '<div class="col-lg-1 divIconSel">';
						$icon = str_replace(array(':', '.'), '', $match[0]);
						$div .= '<span class="iconSel"><i class=\'icon ' . $icon . '\'></i></span><br/><span class="iconDesc">' . $icon . '</span>';
						$div .= '</div>';
						$number++;
					}
				}
				if($number != 0){
					$div .= '</div>';
				}
				$div .= '</div>';
			}
		}
		echo $div;
		?>
		<div class="iconCategory generalCategory">
			<legend>{{Général}}</legend>
			<div class="row">
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-glasses'></i></span><br/><span class="iconDesc">fa-glasses</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-music'></i></span><br/><span class="iconDesc">fa-music</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-search'></i></span><br/><span class="iconDesc">fa-search</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-envelope'></i></span><br/><span class="iconDesc">fa-envelope-o</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-heart'></i></span><br/><span class="iconDesc">fa-heart</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-star'></i></span><br/><span class="iconDesc">fa-star</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-user'></i></span><br/><span class="iconDesc">fa-user</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-film'></i></span><br/><span class="iconDesc">fa-film</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-th-large'></i></span><br/><span class="iconDesc">fa-th-large</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-check'></i></span><br/><span class="iconDesc">fa-check</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-times'></i></span><br/><span class="iconDesc">fa-times</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-power-off'></i></span><br/><span class="iconDesc">fa-power-off</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-list-alt'></i></span><br/><span class="iconDesc">fa-list-alt</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-signal'></i></span><br/><span class="iconDesc">fa-signal</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-cog'></i></span><br/><span class="iconDesc">fa-cog</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-trash-alt'></i></span><br/><span class="iconDesc">fa-trash-o</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-home'></i></span><br/><span class="iconDesc">fa-home</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-file'></i></span><br/><span class="iconDesc">fa-file</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='far fa-clock'></i></span><br/><span class="iconDesc">fa-clock</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-road'></i></span><br/><span class="iconDesc">fa-road</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-download'></i></span><br/><span class="iconDesc">fa-download</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-inbox'></i></span><br/><span class="iconDesc">fa-inbox</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='far fa-play-circle'></i></span><br/><span class="iconDesc">fa-play-circle</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-sync'></i></span><br/><span class="iconDesc">fa-sync</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-lock'></i></span><br/><span class="iconDesc">fa-lock</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-flag'></i></span><br/><span class="iconDesc">fa-flag</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-headphones'></i></span><br/><span class="iconDesc">fa-headphones</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-volume-up'></i></span><br/><span class="iconDesc">fa-volume-up</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-volume-down'></i></span><br/><span class="iconDesc">fa-volume-down</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-volume-off'></i></span><br/><span class="iconDesc">fa-volume-off</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-qrcode'></i></span><br/><span class="iconDesc">fa-qrcode</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-barcode'></i></span><br/><span class="iconDesc">fa-barcode</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-tag'></i></span><br/><span class="iconDesc">fa-tag</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-book'></i></span><br/><span class="iconDesc">fa-book</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-print'></i></span><br/><span class="iconDesc">fa-print</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-camera'></i></span><br/><span class="iconDesc">fa-camera</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='far fa-image'></i></span><br/><span class="iconDesc">fa-image</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-pencil-alt'></i></span><br/><span class="iconDesc">fa-pencil</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-map-marker'></i></span><br/><span class="iconDesc">fa-map-marker</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-tint'></i></span><br/><span class="iconDesc">fa-tint</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-check-square'></i></span><br/><span class="iconDesc">fa-check-square-o</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-arrows-alt'></i></span><br/><span class="iconDesc">fa-arrows</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-step-backward'></i></span><br/><span class="iconDesc">fa-step-backward</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-fast-backward'></i></span><br/><span class="iconDesc">fa-fast-backward</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-backward'></i></span><br/><span class="iconDesc">fa-backward</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-play'></i></span><br/><span class="iconDesc">fa-play</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-pause'></i></span><br/><span class="iconDesc">fa-pause</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-video'></i></span><br/><span class="iconDesc">fa-video</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-stop'></i></span><br/><span class="iconDesc">fa-stop</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-forward'></i></span><br/><span class="iconDesc">fa-forward</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-fast-forward'></i></span><br/><span class="iconDesc">fa-fast-forward</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-step-forward'></i></span><br/><span class="iconDesc">fa-step-forward</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-eject'></i></span><br/><span class="iconDesc">fa-eject</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-chevron-left'></i></span><br/><span class="iconDesc">fa-chevron-left</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-chevron-right'></i></span><br/><span class="iconDesc">fa-chevron-right</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-plus-circle'></i></span><br/><span class="iconDesc">fa-plus-circle</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-minus-circle'></i></span><br/><span class="iconDesc">fa-minus-circle</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='far fa-times-circle'></i></span><br/><span class="iconDesc">fa-times-circle</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='far fa-check-circle'></i></span><br/><span class="iconDesc">fa-check-circle</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-question-circle'></i></span><br/><span class="iconDesc">fa-question-circle</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-info-circle'></i></span><br/><span class="iconDesc">fa-info-circle</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-crosshairs'></i></span><br/><span class="iconDesc">fa-crosshairs</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='far fa-times-circle'></i></span><br/><span class="iconDesc">fa-times-circle-o</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-ban'></i></span><br/><span class="iconDesc">fa-ban</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-arrow-left'></i></span><br/><span class="iconDesc">fa-arrow-left</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-arrow-right'></i></span><br/><span class="iconDesc">fa-arrow-right</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-arrow-up'></i></span><br/><span class="iconDesc">fa-arrow-up</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-arrow-down'></i></span><br/><span class="iconDesc">fa-arrow-down</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-plus'></i></span><br/><span class="iconDesc">fa-plus</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-minus'></i></span><br/><span class="iconDesc">fa-minus</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-asterisk'></i></span><br/><span class="iconDesc">fa-asterisk</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-wheelchair'></i></span><br/><span class="iconDesc">fa-wheelchair</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-exclamation-circle'></i></span><br/><span class="iconDesc">fa-exclamation-circle</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-gift'></i></span><br/><span class="iconDesc">fa-gift</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-leaf'></i></span><br/><span class="iconDesc">fa-leaf</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-fire'></i></span><br/><span class="iconDesc">fa-fire</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-eye'></i></span><br/><span class="iconDesc">fa-eye</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-eye-slash'></i></span><br/><span class="iconDesc">fa-slash</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-exclamation-triangle'></i></span><br/><span class="iconDesc">fa-exclamation-triangle</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-plane'></i></span><br/><span class="iconDesc">fa-plane</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-calendar'></i></span><br/><span class="iconDesc">fa-calendar</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-random'></i></span><br/><span class="iconDesc">fa-random</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-comment'></i></span><br/><span class="iconDesc">fa-comment</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-magnet'></i></span><br/><span class="iconDesc">fa-magnet</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-chevron-up'></i></span><br/><span class="iconDesc">fa-chevron-up</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-chevron-down'></i></span><br/><span class="iconDesc">fa-chevron-down</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-shopping-cart'></i></span><br/><span class="iconDesc">fa-shopping-cart</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-folder'></i></span><br/><span class="iconDesc">fa-folder</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-folder-open'></i></span><br/><span class="iconDesc">fa-folder-open</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-chart-bar'></i></span><br/><span class="iconDesc">fa-chart-bar</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-key'></i></span><br/><span class="iconDesc">fa-key</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='far fa-heart'></i></span><br/><span class="iconDesc">fa-heart-o</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-sign-out-alt'></i></span><br/><span class="iconDesc">fa-sign-out</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-thumbtack'></i></span><br/><span class="iconDesc">fa-thumbtack</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-sign-in-alt'></i></span><br/><span class="iconDesc">fa-sign-in</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-phone'></i></span><br/><span class="iconDesc">fa-phone</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-unlock'></i></span><br/><span class="iconDesc">fa-unlock</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-credit-card'></i></span><br/><span class="iconDesc">fa-credit-card</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-rss'></i></span><br/><span class="iconDesc">fa-rss</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-hdd'></i></span><br/><span class="iconDesc">fa-hdd</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-bullhorn'></i></span><br/><span class="iconDesc">fa-bullhorn</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-bell'></i></span><br/><span class="iconDesc">fa-bell</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-globe'></i></span><br/><span class="iconDesc">fa-globe</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-wrench'></i></span><br/><span class="iconDesc">fa-wrench</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-filter'></i></span><br/><span class="iconDesc">fa-filter</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-briefcase'></i></span><br/><span class="iconDesc">fa-briefcase</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-cloud'></i></span><br/><span class="iconDesc">fa-cloud</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-flask'></i></span><br/><span class="iconDesc">fa-flask</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-cut'></i></span><br/><span class="iconDesc">fa-cut</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-paperclip'></i></span><br/><span class="iconDesc">fa-paperclip</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-save'></i></span><br/><span class="iconDesc">fa-save</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-table'></i></span><br/><span class="iconDesc">fa-table</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-magic'></i></span><br/><span class="iconDesc">fa-magic</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-truck'></i></span><br/><span class="iconDesc">fa-truck</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='far fa-money-bill-alt'></i></span><br/><span class="iconDesc">fa-money</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-columns'></i></span><br/><span class="iconDesc">fa-columns</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-envelope'></i></span><br/><span class="iconDesc">fa-envelope</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-gavel'></i></span><br/><span class="iconDesc">fa-gavel</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-tachometer-alt'></i></span><br/><span class="iconDesc">fa-tachometer-alt</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-bolt'></i></span><br/><span class="iconDesc">fa-bolt</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-sitemap'></i></span><br/><span class="iconDesc">fa-sitemap</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-umbrella'></i></span><br/><span class="iconDesc">fa-umbrella</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-lightbulb'></i></span><br/><span class="iconDesc">fa-lightbulb</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-stethoscope'></i></span><br/><span class="iconDesc">fa-stethoscope</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-suitcase'></i></span><br/><span class="iconDesc">fa-suitcase</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-coffee'></i></span><br/><span class="iconDesc">fa-coffee</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-utensils'></i></span><br/><span class="iconDesc">fa-cutlery</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-building'></i></span><br/><span class="iconDesc">fa-building-o</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-medkit'></i></span><br/><span class="iconDesc">fa-medkit</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-beer'></i></span><br/><span class="iconDesc">fa-beer</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-h-square'></i></span><br/><span class="iconDesc">fa-square</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-desktop'></i></span><br/><span class="iconDesc">fa-desktop</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-laptop'></i></span><br/><span class="iconDesc">fa-laptop</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-tablet'></i></span><br/><span class="iconDesc">fa-tablet</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-mobile'></i></span><br/><span class="iconDesc">fa-mobile</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='far fa-circle'></i></span><br/><span class="iconDesc">fa-circle-o</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-spinner'></i></span><br/><span class="iconDesc">fa-spinner</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-circle'></i></span><br/><span class="iconDesc">fa-circle</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='far fa-smile'></i></span><br/><span class="iconDesc">fa-smile</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='far fa-frown'></i></span><br/><span class="iconDesc">fa-frown</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='far fa-meh'></i></span><br/><span class="iconDesc">fa-meh</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-gamepad'></i></span><br/><span class="iconDesc">fa-gamepad</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-keyboard'></i></span><br/><span class="iconDesc">fa-keyboard</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-terminal'></i></span><br/><span class="iconDesc">fa-terminal</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-location-arrow'></i></span><br/><span class="iconDesc">fa-location-arrow</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-microphone'></i></span><br/><span class="iconDesc">fa-microphone</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-microphone-slash'></i></span><br/><span class="iconDesc">fa-microphone-slash</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-shield-alt'></i></span><br/><span class="iconDesc">fa-shield</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='far fa-calendar'></i></span><br/><span class="iconDesc">fa-calendar</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-fire-extinguisher'></i></span><br/><span class="iconDesc">fa-fire-extinguisher</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-rocket'></i></span><br/><span class="iconDesc">fa-rocket</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-anchor'></i></span><br/><span class="iconDesc">fa-anchor</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-unlock-alt'></i></span><br/><span class="iconDesc">fa-unlock-alt</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-compass'></i></span><br/><span class="iconDesc">fa-compass</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fab fa-apple'></i></span><br/><span class="iconDesc">fa-apple</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fab fa-windows'></i></span><br/><span class="iconDesc">fa-windows</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fab fa-android'></i></span><br/><span class="iconDesc">fa-android</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fab fa-linux'></i></span><br/><span class="iconDesc">fa-linux</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fab fa-dribbble'></i></span><br/><span class="iconDesc">fa-dribbble</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fab fa-trello'></i></span><br/><span class="iconDesc">fa-trello</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-female'></i></span><br/><span class="iconDesc">fa-female</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-male'></i></span><br/><span class="iconDesc">fa-male</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fab fa-gratipay'></i></span><br/><span class="iconDesc">fa-gratipay</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='far fa-sun'></i></span><br/><span class="iconDesc">fa-sun</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-moon'></i></span><br/><span class="iconDesc">fa-moon</span></div>
				<div class="col-lg-1 divIconSel"><span class="iconSel"><i class='fas fa-archive'></i></span><br/><span class="iconDesc">fa-archive</span></div>
			</div>
			<div class="row">
				<div class="col-lg-1 divIconSel customIcon" style="display: none;"></div>
			</div>
		</div>
	</div>
</div>

<script>
setTimeout(function() {
	if (getDeviceType()['type'] == 'desktop') $("input[id^='in_search']").focus()
}, 500)

$('#sel_colorIcon').off('change').on('change',function() {
	$('.iconSel i').removeClass('icon_green icon_blue icon_orange icon_red icon_yellow').addClass($(this).value())
})

//searching
$('#in_searchIconSelector').on('keyup',function() {
	$('.divIconSel').show()
	$('.customIcon').hide()
	$('.iconCategory').show()
	var search = $(this).value()
	if (search != '') {
		search = normTextLower(search)
		$('.iconDesc').each(function() {
			if ($(this).text().indexOf(search) == -1) {
				$(this).closest('.divIconSel').hide()
			}
		})
	}

	var somethingFound = 0
	$('.iconCategory').each(function() {
		var hide = true
		if ($(this).find('.divIconSel:visible').length == 0) {
			$(this).hide()
		} else {
			somethingFound +=1
		}
	})
	if (somethingFound == 0) {
		$('.generalCategory').show()
		$('.customIcon').empty().append('<span class="iconSel"><i class="' + $(this).value() + '"></i></span><br/><span class="iconDesc">' + $(this).value() + '</span>').show()
	}
})

$('#bt_resetSearch').on('click', function() {
	$('#in_searchIconSelector').val('').keyup()
})

setTimeout(function() {
	if (tabimg && tabimg == 1) {
		$('#mod_selectIcon ul li a[href="#img"]').click()
	}
}, 500)

$('#mod_selectIcon ul li a[href="#tabicon"]').click(function() {
	$('#mySearch').show()
	$('.iconCategory').show()
})
$('#mod_selectIcon ul li a[href="#tabimg"]').click(function() {
	$('#mySearch').hide()
	$('.iconCategory').hide()
})

$('.divIconSel').on('click', function() {
	$('.divIconSel').removeClass('iconSelected');
	$(this).closest('.divIconSel').addClass('iconSelected');
})

$('.divIconSel').on('dblclick', function() {
	$('.divIconSel').removeClass('iconSelected');
	$(this).closest('.divIconSel').addClass('iconSelected');
	$('#mod_selectIcon').dialog("option", "buttons")['Valider'].apply($('#mod_selectIcon'));
})

$('#mod_selectIcon').css('overflow', 'hidden')

$(function() {
	$('.imgContainer').show()
	//move select/search in modal bottom:
	var buttonSet = $('.ui-dialog[aria-describedby="mod_selectIcon"]').find('.ui-dialog-buttonpane')
	buttonSet.find('#mySearch').remove()
	var mySearch = $('.ui-dialog[aria-describedby="mod_selectIcon"]').find('#mySearch')
	buttonSet.append(mySearch)
	//auto select actual icon:
	var iconName = (selectIcon.split('.').join(' ')).trim()
	if (selectIcon != "0") {
		$(selectIcon).closest('.divIconSel').addClass('iconSelected')
		setTimeout(function() {
			elem = $('div.divIconSel.iconSelected')
			if (elem.position()) {
				container = $('#mod_selectIcon > .tab-content')
				pos = elem.position().top + container.scrollTop() - container.position().top
				container.animate({scrollTop: pos-20})
			} else {
				$('.customIcon').empty().append('<span class="iconSel"><i class="' + iconName + '"></i></span><br/><span class="iconDesc">' + iconName + '</span>').show()
				$(selectIcon).closest('.divIconSel').addClass('iconSelected')
				elem = $('div.divIconSel.iconSelected')
				if (elem.position()) {
					container = $('#mod_selectIcon > .tab-content')
					pos = elem.position().top + container.scrollTop() - container.position().top
					container.animate({scrollTop: pos-20})
				}
			}
		}, 250)
	}
	if (colorIcon != "0") {
		$('#sel_colorIcon').value(colorIcon)
	}
})
</script>
