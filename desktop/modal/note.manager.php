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

if (!isConnect('admin')) {
  throw new Exception('{{401 - Accès non autorisé}}');
}
$id = init('id', '');
sendVarToJs('jeephp2js.md_noteManagemer_noteId', $id);
?>

<div style="display: none;" id="div_noteManagerAlert" data-modalType="md_noteManager"></div>
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
        <a class="btn btn-sm roundedLeft" id="bt_noteManagerAdd"><i class="fas fa-plus-circle"></i> {{Ajouter}}
        </a><a class="btn btn-success btn-sm" id="bt_noteManagerSave"><i class="fas fa-check-circle"></i> {{Sauvegarder}}
        </a><a class="btn btn-danger btn-sm roundedRight" id="bt_noteManagerRemove"><i class="fas fa-trash"></i> {{Supprimer}}</a>
      </span>
    </div>
    <br/><br/>
    <div id="div_noteManagerDisplay">
      <input class="noteAttr form-control" data-l1key="id" style="display:none;" disabled/>
      <input class="noteAttr form-control" data-l1key="name" placeholder="{{Titre}}"/>
      <br/>
      <textarea class="noteAttr form-control ta_autosize" data-l1key="text" placeholder="{{Texte}}"></textarea>
    </div>
  </div>
</div>

<script>
if (!jeeFrontEnd.md_noteManager) {
  jeeFrontEnd.md_noteManager = {
    init: function() {
      this.updateNoteList()
    },
    updateNoteList: function() {
      jeedom.note.all({
        error: function(error) {
          $('#div_noteManagerAlert').showAlert({message: error.message, level: 'danger'})
        },
        success: function(notes) {
          var note = document.getElementById('div_noteManagerDisplay').getJeeValues('.noteAttr')[0]
          var ul = ''
          for (var i in notes) {
            ul += '<li class="cursor li_noteDisplay" data-id="' + notes[i].id + '"><a>' + notes[i].name + '</a></li>'
          }
          $('#ul_noteList').empty().append(ul)
          if (note.id != '') {
            $('.li_noteDisplay[data-id=' + note.id + ']').addClass('active')
          }
        }
      })
    },
  }
}

(function() {
  $.hideAlert()
  var jeeM = jeeFrontEnd.md_noteManager
  jeeM.init()

  $(function() {
    jeedomUtils.taAutosize()
    if (jeephp2js.md_noteManagemer_noteId != '') {
      setTimeout(function(){
        $('li.li_noteDisplay[data-id="' + jeephp2js.md_noteManagemer_noteId + '"]').trigger('click')
      }, 500)
    }
  })

  $('#bt_noteManagerAdd').on('click',function() {
    document.querySelectorAll('#div_noteManagerDisplay .noteAttr').jeeValue('')
    document.querySelector('#ul_noteList li.active').removeClass('active')
  })

  $('#ul_noteList').on('click','.li_noteDisplay',function() {
    $('.li_noteDisplay').removeClass('active')
    $(this).addClass('active')
    jeedom.note.byId({
      id : $(this).attr('data-id'),
      error: function(error) {
        $('#div_noteManagerAlert').showAlert({message: error.message, level: 'danger'})
      },
      success: function(note) {
        document.querySelectorAll('#div_noteManagerDisplay .noteAttr').jeeValue('')
        document.getElementById('div_noteManagerDisplay').setJeeValues(note, '.noteAttr')
        jeedomUtils.taAutosize()
      }
    })
  })

  $('#bt_noteManagerSave').on('click',function() {
    var note = document.getElementById('div_noteManagerDisplay').getJeeValues('.noteAttr')[0]
    jeedom.note.save({
      note : note,
      error: function(error) {
        $('#div_noteManagerAlert').showAlert({message: error.message, level: 'danger'})
      },
      success: function(note) {
        $('#div_noteManagerAlert').showAlert({message: '{{Note sauvegardée avec succès}}', level: 'success'})
        document.getElementById('div_noteManagerDisplay').setJeeValues(note, '.noteAttr')
        jeeM.updateNoteList()
      }
    })
  })

  $('#bt_noteManagerRemove').on('click',function() {
    var note = document.getElementById('div_noteManagerDisplay').getJeeValues('.noteAttr')[0]
    var r = confirm('{{Voulez-vous vraiment supprimer la note :}}' + ' ' + note.name + ' ?')
    if (r == true) {
      jeedom.note.remove({
        id : note.id,
        error: function(error) {
          $('#div_noteManagerAlert').showAlert({message: error.message, level: 'danger'})
        },
        success: function(notes) {
          $('#div_noteManagerAlert').showAlert({message: '{{Note supprimée avec succès}}', level: 'success'})
          document.querySelectorAll('#div_noteManagerDisplay .noteAttr').jeeValue('')
          jeeM.updateNoteList()
        }
      })
    }
  })
})()
</script>