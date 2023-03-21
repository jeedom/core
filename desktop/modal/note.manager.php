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

<div id="md_noteManager" data-modalType="md_noteManager">
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
</div>

<script>
if (!jeeFrontEnd.md_noteManager) {
  jeeFrontEnd.md_noteManager = {
    init: function() {
      jeedomUtils.hideAlert()
      this.updateNoteList()
      jeedomUtils.taAutosize()

      if (jeephp2js.md_noteManagemer_noteId != '') {
        this.displayNote(jeephp2js.md_noteManagemer_noteId)
      }
    },
    updateNoteList: function() {
      jeedom.note.all({
        error: function(error) {
          jeedomUtils.showAlert({
            attachTo: jeeDialog.get('#md_noteManager', 'dialog'),
            message: error.message,
            level: 'danger'})
        },
        success: function(notes) {
          var note = document.getElementById('div_noteManagerDisplay').getJeeValues('.noteAttr')[0]
          var ul = ''
          for (var i in notes) {
            ul += '<li class="cursor li_noteDisplay" data-id="' + notes[i].id + '"><a>' + notes[i].name + '</a></li>'
          }
          document.getElementById('ul_noteList').empty().insertAdjacentHTML('beforeend', ul)
          if (note.id != '') {
            document.querySelector('.li_noteDisplay[data-id="' + note.id + '"]').addClass('active')
          }
        }
      })
    },
    displayNote: function(_noteId) {
      document.querySelectorAll('.li_noteDisplay').removeClass('active')
      document.querySelector('.li_noteDisplay[data-id="' + _noteId + '"]')?.addClass('active')
      jeedom.note.byId({
        id : _noteId,
        error: function(error) {
          jeedomUtils.showAlert({
            attachTo: jeeDialog.get('#md_noteManager', 'dialog'),
            message: error.message,
            level: 'danger'
            })
        },
        success: function(note) {
          document.querySelectorAll('#div_noteManagerDisplay .noteAttr').jeeValue('')
          document.getElementById('div_noteManagerDisplay').setJeeValues(note, '.noteAttr')
          jeedomUtils.taAutosize()
        }
      })
    },
  }
}

(function() {// Self Isolation!
  var jeeM = jeeFrontEnd.md_noteManager
  jeeM.init()


  /*Events delegations
  */
  document.getElementById('md_noteManager').addEventListener('click', function(event) {
    var _target = null
    if (_target = event.target.closest('#bt_noteManagerAdd')) {
      document.querySelectorAll('#div_noteManagerDisplay .noteAttr').jeeValue('')
      document.querySelector('#ul_noteList li.active')?.removeClass('active')
      return
    }

    if (_target = event.target.closest('.li_noteDisplay')) {
      jeeM.displayNote(_target.getAttribute('data-id'))
      return
    }

    if (_target = event.target.closest('#bt_noteManagerSave')) {
      var note = document.getElementById('div_noteManagerDisplay').getJeeValues('.noteAttr')[0]
      jeedom.note.save({
        note : note,
        error: function(error) {
          jeedomUtils.showAlert({
            attachTo: jeeDialog.get('#md_noteManager', 'dialog'),
            message: error.message,
            level: 'danger'
          })
        },
        success: function(note) {
          jeedomUtils.showAlert({
            attachTo: jeeDialog.get('#md_noteManager', 'dialog'),
            message: '{{Note sauvegardée avec succès}}',
            level: 'success'
          })
          document.getElementById('div_noteManagerDisplay').setJeeValues(note, '.noteAttr')
          jeeM.updateNoteList()
        }
      })
      return
    }

    if (_target = event.target.closest('#bt_noteManagerRemove')) {
      var note = document.getElementById('div_noteManagerDisplay').getJeeValues('.noteAttr')[0]
      jeeDialog.confirm('{{Voulez-vous vraiment supprimer la note :}}' + ' ' + note.name + ' ?', function(result) {
        if (result) {
          jeedom.note.remove({
            id : note.id,
            error: function(error) {
              jeedomUtils.showAlert({
                attachTo: jeeDialog.get('#md_noteManager', 'dialog'),
                message: error.message,
                level: 'danger'
              })
            },
            success: function(notes) {
              jeedomUtils.showAlert({
                attachTo: jeeDialog.get('#md_noteManager', 'dialog'),
                message: '{{Note supprimée avec succès}}',
                level: 'success'
              })
              document.querySelectorAll('#div_noteManagerDisplay .noteAttr').jeeValue('')
              jeeM.updateNoteList()
            }
          })
        }
      })
      return
    }
  })
})()
</script>