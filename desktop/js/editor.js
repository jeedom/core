
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

$('#sel_widgetType').off('change').on('change',function(){
  $('#sel_widgetSubtype option').hide();
  if($(this).value() != ''){
    $('#sel_widgetSubtype option[data-type='+$(this).value()+']').show();
  }
  $('#sel_widgetSubtype option[data-default=1]').show();
  $('#sel_widgetSubtype').value('');
});

$("#md_widgetCreate").dialog({
  closeText: '',
  autoOpen: false,
  modal: true,
  height: 260,
  width: 300,
  open: function () {
    $("body").css({overflow: 'hidden'});
  },
  beforeClose: function (event, ui) {
    $("body").css({overflow: 'inherit'});
  }
});

fileEditor = null
var CURRENT_FOLDER=rootPath
printFileFolder(CURRENT_FOLDER);

$('#div_treeFolder').off('click').on('select_node.jstree', function (node, selected) {
  if (selected.node.a_attr['data-path'] != undefined) {
    path = selected.node.a_attr['data-path'];
    printFileFolder(path);
    ref = $('#div_treeFolder').jstree(true);
    sel = ref.get_selected()[0];
    ref.open_node(sel);
    nodesList = ref.get_children_dom(sel);
    if(nodesList.length != 0){
      return;
    }
    jeedom.getFileFolder({
      type : 'folders',
      path : path,
      error: function (error) {
        $('#div_alert').showAlert({message: error.message, level: 'danger'});
      },
      success : function(data){
        for(var i in data){
          node = ref.create_node(sel, {"type":"folder","text":data[i],state:{opened:true},a_attr:{'data-path':path+data[i]}});
          $('li#'+node+' a').addClass('li_folder');
        }
      }
    });
  }
});

$("#div_treeFolder").jstree({"core" : {
  "check_callback": true
}});

$('#div_fileList').off('click').on('click','.li_file',function(){
  displayFile($(this).attr('data-path'));
});

function printFileFolder(_path){
  CURRENT_FOLDER = _path;
  jeedom.getFileFolder({
    type : 'files',
    path : _path,
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success : function(data){
      $('#div_fileList').empty();
      var li = '';
      for(var i in data){
        li += '<li class="cursor"><a class="li_file" data-path="'+_path+data[i]+'">'+data[i]+'</a></li>';
      }
      $('#div_fileList').append(li);
    }
  });
}

function getEditorMode(_path){
  var ext = _path.split('.').pop();
  switch (ext) {
    case 'sh' :
    return 'shell';
    case 'py' :
    return 'text/x-python';
    case 'rb' :
    return 'text/x-ruby';
    case 'js' :
    return 'text/javascript';
    case 'json' :
    return 'application/json';
  }
  return 'application/x-httpd-php';
}

function displayFile(_path){
  $.hideAlert();
  $('#span_editorFileName').empty().html(_path.replace(/^.*[\\\/]/, ''));
  $('#span_editorFileName').attr('title',_path);
  $('#bt_saveFile').attr('data-path',_path);
  $('#bt_deleteFile').attr('data-path',_path);
  jeedom.getFileContent({
    path : _path,
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success : function(data){
      if (fileEditor != null) {
        fileEditor.getDoc().setValue(data);
        fileEditor.setOption("mode", getEditorMode(_path));
        setTimeout(function () {
          fileEditor.refresh();
        }, 1);
      } else {
        $('#ta_fileContent').val(data);
        setTimeout(function () {
          fileEditor = CodeMirror.fromTextArea(document.getElementById("ta_fileContent"), {
            lineNumbers: true,
            mode: getEditorMode(_path),
            matchBrackets: true
          });
          fileEditor.getWrapperElement().style.height = ($('#ta_fileContent').closest('.row-overflow').find('.col-lg-2').height() - 60) + 'px';
          fileEditor.refresh();
        }, 1);
      }
    }
  });
}

$('#bt_saveFile').on('click',function(){
  if (!fileEditor){
    $('#div_alert').showAlert({message: '{{Aucun fichier ouvert.}}', level: 'warning'});
    return
  }
  jeedom.setFileContent({
    path : $(this).attr('data-path'),
    content :fileEditor.getValue(),
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success : function(data){
      $('#div_alert').showAlert({message: '{{Fichier enregistré avec succès}}', level: 'success'});
    }
  });
})

$('#bt_deleteFile').on('click',function(){
  if (!fileEditor){
    $('#div_alert').showAlert({message: '{{Aucun fichier ouvert.}}', level: 'warning'});
    return
  }
  $('#span_editorFileName').empty();
  var path = $(this).attr('data-path');
  bootbox.confirm('{{Êtes-vous sûr de vouloir supprimer ce fichier : }} <span style="font-weight: bold ;">' +path + '</span> ?', function (result) {
    if (result) {
      jeedom.deleteFile({
        path : path,
        error: function (error) {
          $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success : function(data){
          $('#div_alert').showAlert({message: '{{Fichier supprimé avec succès}}', level: 'success'});
          if (fileEditor != null) {
            fileEditor.getDoc().setValue('');
            setTimeout(function () {
              fileEditor.refresh();
            }, 1);
          } else {
            $('#ta_fileContent').val('');
            setTimeout(function () {
              fileEditor = CodeMirror.fromTextArea(document.getElementById("ta_fileContent"), {
                lineNumbers: true,
                mode: 'application/x-httpd-php',
                matchBrackets: true
              });
              fileEditor.getWrapperElement().style.height = ($('#ta_fileContent').closest('.row-overflow').find('.col-lg-2').height() - 60) + 'px';
              fileEditor.refresh();
            }, 1);
          }
          printFileFolder(CURRENT_FOLDER);
        }
      });
    }
  });
})

$('#bt_widgetCreate').off('click').on('click',function(){
  CURRENT_FOLDER = rootPath+$('#sel_widgetVersion').value()+'/';
  if($('#sel_widgetSubtype').value() == ''){
    $('#div_alert').showAlert({message: '{{Le sous-type ne peut être vide}}', level: 'danger'});
    return;
  }
  if($('#in_widgetName').value() == ''){
    $('#div_alert').showAlert({message: '{{Le nom ne peut être vide}}', level: 'danger'});
    return;
  }
  var name = 'cmd.'+$('#sel_widgetType').value()+'.'+$('#sel_widgetSubtype').value()+'.'+$('#in_widgetName').value()+'.html';
  jeedom.createFile({
    path : CURRENT_FOLDER,
    name :name,
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success : function(data){
      $("#md_widgetCreate").dialog('close');
      $('#div_alert').showAlert({message: '{{Fichier enregistré avec succès}}', level: 'success'});
      printFileFolder(CURRENT_FOLDER);
      displayFile(CURRENT_FOLDER+'/'+name);
    }
  });
});

$('#bt_createFile').off('click').on('click',function(){
  if(editorType == 'widget'){
    $('#md_widgetCreate').dialog({title: "{{Options}}"});
    $("#md_widgetCreate").dialog('open');
    $('#sel_widgetType').trigger('change');
  }else{
    bootbox.prompt("Nom du fichier ?", function (result) {
      if (result !== null) {
        jeedom.createFile({
          path : CURRENT_FOLDER,
          name :result,
          error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
          },
          success : function(data){
            $('#div_alert').showAlert({message: '{{Fichier enregistré avec succès}}', level: 'success'});
            printFileFolder(CURRENT_FOLDER);
            displayFile(CURRENT_FOLDER+'/'+result);
          }
        });
      }
    });
  }
})
