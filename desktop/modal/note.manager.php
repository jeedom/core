<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
?>
<div style="display: none;" id="div_noteManagementAlert"></div>
<div class="row row-overflow">
	<div id="div_notes" class="col-lg-2 col-md-3 col-sm-4" style="overflow-y:auto;overflow-x:hidden;">
		<div class="bs-sidebar">
			<ul class="nav nav-list bs-sidenav list-group" id="ul_noteList">

			</ul>
		</div>
	</div>
	<div class="col-lg-10 col-md-9 col-sm-8" style="overflow:hidden;">
		<div class="input-group pull-right" style="display:inline-flex">
			<span class="input-group-btn">
				<a class="btn btn-sm roundedLeft" id="bt_noteManagerAdd"><i class="fas fa-plus"></i> {{Ajouter}}
				</a><a class="btn btn-success btn-sm" id="bt_noteManagerSave"><i class="fas fa-save"></i> {{Sauvegarder}}
				</a><a class="btn btn-danger btn-sm roundedRight" id="bt_noteManagerRemove"><i class="fas fa-trash"></i> {{Supprimer}}</a>
			</span>
		</div>
		<br/><br/>
		<div id="div_noteManagerDisplay">
			<input class="noteAttr form-control" data-l1key="id" style="display:none;" disabled/>
			<input class="noteAttr form-control" data-l1key="name" placeholder="Titre"/>
			<br/>
			<textarea class="noteAttr form-control ta_autosize" data-l1key="text" placeholder="Note..."></textarea>
		</div>
	</div>
</div>

<script type="text/javascript">
function updateNoteList(){
	jeedom.note.all({
		error: function (error) {
			$('#div_noteManagementAlert').showAlert({message: error.message, level: 'danger'});
		},
		success: function (notes) {
			var note = $('#div_noteManagerDisplay').getValues('.noteAttr')[0];
			var ul = '';
			for(var i in notes){
				ul += '<li class="cursor li_noteDisplay" data-id="'+notes[i].id+'"><a>'+notes[i].name+'</a></li>';
			}
			$('#ul_noteList').empty().append(ul);
			if(note.id != ''){
				$('.li_noteDisplay[data-id='+note.id+']').addClass('active');
			}
		}
	});
}

$('#bt_noteManagerAdd').on('click',function(){
	$('#div_noteManagerDisplay .noteAttr').value('');
	$('#ul_noteList li.active').removeClass('active');
});

$('#ul_noteList').on('click','.li_noteDisplay',function(){
	$('.li_noteDisplay').removeClass('active');
	$(this).addClass('active');
	jeedom.note.byId({
		id : $(this).attr('data-id'),
		error: function (error) {
			$('#div_noteManagementAlert').showAlert({message: error.message, level: 'danger'});
		},
		success: function (note) {
			$('#div_noteManagerDisplay .noteAttr').value('');
			$('#div_noteManagerDisplay').setValues(note, '.noteAttr');
			taAutosize();
		}
	});
});

$('#bt_noteManagerSave').on('click',function(){
	var note = $('#div_noteManagerDisplay').getValues('.noteAttr')[0];
	jeedom.note.save({
		note : note,
		error: function (error) {
			$('#div_noteManagementAlert').showAlert({message: error.message, level: 'danger'});
		},
		success: function (note) {
			$('#div_noteManagementAlert').showAlert({message: '{{Note sauvegardée avec succès}}', level: 'success'});
			$('#div_noteManagerDisplay').setValues(note, '.noteAttr');
			updateNoteList();
		}
	});
});

$('#bt_noteManagerRemove').on('click',function(){
	var note = $('#div_noteManagerDisplay').getValues('.noteAttr')[0];
	var r = confirm('{{Etês vous sur de vouloir supprimer la note : }}'+note.name+' ?');
	if (r == true) {
		jeedom.note.remove({
			id : note.id,
			error: function (error) {
				$('#div_noteManagementAlert').showAlert({message: error.message, level: 'danger'});
			},
			success: function (notes) {
				$('#div_noteManagementAlert').showAlert({message: '{{Note supprimée avec succès}}', level: 'success'});
				$('#div_noteManagerDisplay .noteAttr').value('');
				updateNoteList();
			}
		});
	}
});

updateNoteList();
taAutosize();
</script>
