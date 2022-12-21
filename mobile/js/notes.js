"use strict"

$('body').attr('data-page', 'notes')

function initNotes(note_id) {
  if (note_id !== undefined) {
    jeedom.note.byId({
      id: note_id,
      error: function(error) {
        $.fn.showAlert({message: error.message, level: 'danger'})
      },
      success: function(note) {
        $('#div_noteManagerDisplay .noteAttr').value('')
        $('#div_noteManagerDisplay').setValues(note, '.noteAttr')
      }
    })
  } else {
    $('#bt_bottompanel').trigger('click')
  }

  updateNoteList()

  function updateNoteList() {
    jeedom.note.all({
      error: function(error) {
        $.fn.showAlert({message: error.message, level: 'danger'})
      },
      success: function(notes) {
        var note = $('#div_noteManagerDisplay').getValues('.noteAttr')[0]
        var li = ' <ul data-role="listview" data-inset="false">'
        for (var i in notes) {
          li += '<li class="li_noteDisplay link" data-page="notes" data-title="<i class=\'fas fa-sticky-note\'></i> ' + notes[i].name + '" data-option="'+notes[i].id+'"><a><i class="fas fa-sticky-note"></i> ' + notes[i].name + '</a></li>'
        }
        li += '</ul>'
        jeedomUtils.loadPanel(li)
        if (note.id != '') {
          $('.li_noteDisplay[data-id=' + note.id + ']').addClass('active')
        }
      }
    })
  }

  $('#bt_noteManagerAdd').on('click',function() {
    $('#div_noteManagerDisplay .noteAttr').value('')
    $('#ul_noteList li.active').removeClass('active')
  })

  $('#bt_noteManagerSave').on('click',function() {
    var note = $('#div_noteManagerDisplay').getValues('.noteAttr')[0]
    jeedom.note.save({
      note : note,
      error: function(error) {
        $.fn.showAlert({message: error.message, level: 'danger'})
      },
      success: function(note) {
        $.fn.showAlert({message: '{{Note sauvegardée avec succès}}', level: 'success'})
        $('#div_noteManagerDisplay').setValues(note, '.noteAttr')
        updateNoteList()
      }
    })
  })

  $('#bt_noteManagerRemove').on('click',function() {
    var note = $('#div_noteManagerDisplay').getValues('.noteAttr')[0]
    var r = confirm('{{Voulez-vous vraiment supprimer la note :}}' + ' ' + note.name + ' ?')
    if (r == true) {
      jeedom.note.remove({
        id : note.id,
        error: function(error) {
          $.fn.showAlert({message: error.message, level: 'danger'})
        },
        success: function(notes) {
          $.fn.showAlert({message: '{{Note supprimée avec succès}}', level: 'success'})
          $('#div_noteManagerDisplay .noteAttr').value('')
          updateNoteList()
        }
      })
    }
  })
}