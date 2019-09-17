<?php
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
sendVarToJs('img_object_id',init('object_id'));
?>
<div style="display: none;" id="div_imgObjSelectorAlert"></div>
<style>
.divIconSel{
	height: 80px;
	border: 1px solid #fff;
	box-sizing: border-box;
	cursor: pointer;
	text-align: center;
}

.iconSel{
	line-height: 1.4;
	font-size: 1.5em;
}

.iconSelected{
	background-color: #563d7c;
	color: white;
}

.iconDesc{
	font-size: 0.8em;
}

.imgContainer img{
	max-width: 120px;
	max-height: 70px;
	padding: 10px;
}
</style>

<div class="tab-content" style="height:calc(100% - 20px);overflow-y:scroll;">
	<div id="mySearch" class="input-group" style="margin-left:6px;margin-top:6px">
		<input class="form-control" placeholder="{{Rechercher}}" id="in_searchImgSelector">
		<div class="input-group-btn">
			<a id="bt_resetSearch" class="btn roundedRight" style="width:30px"><i class="fas fa-times"></i> </a>
		</div>
	</div>
	<div class="imgContainer" style="width:calc(100% - 15px)">
		<?php
		foreach (ls(__DIR__.'/../../core/img/object_background','*') as $category) {
			echo '<div class="imgCategory"><legend>'.ucfirst(str_replace(array('/','_'),array('',' '),$category)).'</legend>';
			echo '<div class="row">';
			foreach (ls(__DIR__.'/../../core/img/object_background/'.$category,'*') as $file) {
				echo '<div class="col-lg-1 divImgSel">';
				echo '<span class="imgSel"><img src="core/img/object_background/'.$category.$file.'" /></span>';
				echo '<center class="imgDesc">'.ucfirst(substr(str_replace(array('/','_','.jpg'),array('',' ',''),basename($file)),0,12)).'</center>';
				echo '<center><a class="btn btn-success btn-xs btSelectImgObj" data-filename="'.__DIR__.'/../../core/img/object_background/'.$category.$file.'"><i class="fas fa-check"></i> {{Selectionner}}</a></center>';
				echo '</div>';
			}
			echo '</div></div>';
		}
		?>
	</div>
	<script>
	$('.btSelectImgObj').on('click',function(){
		var filename = $(this).attr('data-filename');
		jeedom.object.uploadImage({
			id : img_object_id,
			file : filename,
			error: function (error) {
				$('#div_imgObjSelectorAlert').showAlert({message: error.message, level: 'danger'});
			},
			success: function (data) {
				$('#div_imgObjSelectorAlert').showAlert({message: '{{Image ajouté avec succès}}', level: 'success'});
				if (isset(data.filepath)) {
					filePath = data.filepath
					filePath = '/data/object/' + filePath.split('/data/object/')[1]
					$('.objectImg img').attr('src',filePath);
					$('.objectImg img').show()
				} else {
					$('.objectImg img').hide()
				}
				$('#md_modal').dialog('close');
			}
		});
	});
	setTimeout(function() {
		if (getDeviceType()['type'] == 'desktop') $("input[id^='in_search']").focus()
	}, 500);
	$('#in_searchImgSelector').on('keyup',function(){
		$('.divImgSel').show();
		$('.imgCategory').show();
		var search = $(this).value();
		if(search != ''){
			$('.imgDesc').each(function(){
				if($(this).text().indexOf(search) == -1){
					$(this).closest('.divImgSel').hide();
				}
			})
		}
		$('.imgCategory').each(function(){
			var hide = true;
			if($(this).find('.divImgSel:visible').length == 0){
				$(this).hide();
			}
		});
	});
	$('#bt_resetSearch').on('click', function () {
		$('#in_searchImgSelector').val('')
		$('#in_searchImgSelector').keyup();
	})
</script>
