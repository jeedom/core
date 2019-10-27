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
$('.backgroundforJeedom').css({
  'background-position':'bottom right',
  'background-repeat':'no-repeat',
  'background-size':'auto'
});

jwerty.key('ctrl+s/⌘+s', function (e) {
  e.preventDefault();
  $("#bt_saveObject").click();
});

//searching
$('#in_searchObject').keyup(function () {
  var search = $(this).value()
  if (search == '') {
    $('.objectDisplayCard').show()
    $('.objectListContainer').packery()
    return;
  }
  search = normTextLower(search)

  $('.objectDisplayCard').hide()
  $('.objectDisplayCard .name').each(function(){
    var text = $(this).text()
    text = normTextLower(text)
    if (text.indexOf(search) >= 0) {
      $(this).closest('.objectDisplayCard').show()
    }
  })
  $('.objectListContainer').packery()
})
$('#bt_resetObjectSearch').on('click', function () {
  $('#in_searchObject').val('')
  $('#in_searchObject').keyup()
})

/* contextMenu */
$(function(){
  try{
    $.contextMenu('destroy', $('.nav.nav-tabs'));
    jeedom.object.all({
      error: function (error) {
        $('#div_alert').showAlert({message: error.message, level: 'danger'});
      },
      success: function (_objects) {
        if(_objects.length == 0){
          return;
        }
        var contextmenuitems = {}
        for(i=0; i<_objects.length; i++)
        {
          ob = _objects[i]
          var decay = 0
          if (isset(ob.configuration) && isset(ob.configuration.parentNumber)) {
            decay = ob.configuration.parentNumber
          }
          contextmenuitems[i] = {'name': '\u00A0\u00A0\u00A0'.repeat(decay) + ob.name, 'id' : ob.id}
        }

        $('.nav.nav-tabs').contextMenu({
          selector: 'li',
          autoHide: true,
          zIndex: 9999,
          className: 'object-context-menu',
          callback: function(key, options) {
            url = 'index.php?v=d&p=object&id=' + options.commands[key].id;
            if (document.location.toString().match('#')) {
              url += '#' + document.location.toString().split('#')[1];
            }
            loadPage(url);
          },
          items: contextmenuitems
        })
      }
    })
  }
  catch(err) {}
})

$('#bt_graphObject').on('click', function () {
  $('#md_modal').dialog({title: "{{Graphique des liens}}"}).load('index.php?v=d&modal=graph.link&filter_type=object&filter_id='+$('.objectAttr[data-l1key=id]').value()).dialog('open');
});

$('#bt_libraryBackgroundImage').on('click', function () {
  $('#md_modal').dialog({title: "{{Bibliotheque d'images}}"}).load('index.php?v=d&modal=object.img.selector&object_id='+$('.objectAttr[data-l1key=id]').value()).dialog('open');
});

setTimeout(function(){
  $('.objectListContainer').packery();
},100);

$('#bt_returnToThumbnailDisplay').on('click',function(){
  setTimeout(function(){
    $('.nav li.active').removeClass('active');
    $('a[href="#'+$('.tab-pane.active').attr('id')+'"]').closest('li').addClass('active')
  },500);
  if (modifyWithoutSave) {
    if (!confirm('{{Attention vous quittez une page ayant des données modifiées non sauvegardées. Voulez-vous continuer ?}}')) {
      return;
    }
    modifyWithoutSave = false;
  }
  $('#div_conf').hide();
  $('#div_resumeObjectList').show();
  $('.objectListContainer').packery();
  addOrUpdateUrl('id',null,'{{Objets}} - '+JEEDOM_PRODUCT_NAME);
});

$(".objectDisplayCard").on('click', function (event) {
  loadObjectConfiguration($(this).attr('data-object_id'));
  $('.objectname_resume').empty().append($(this).attr('data-object_icon')+'  '+$(this).attr('data-object_name'));
  if(document.location.toString().split('#')[1] == '' || document.location.toString().split('#')[1] == undefined){
    $('.nav-tabs a[href="#objecttab"]').click();
  }
  return false;
});

$('#bt_removeBackgroundImage').off('click').on('click', function () {
  jeedom.object.removeImage({
    id: $('.objectAttr[data-l1key=id]').value(),
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function () {
      $('.objectImg img').hide()
      $('#div_alert').showAlert({message: '{{Image supprimée}}', level: 'success'});
    },
  });
});

function loadObjectConfiguration(_id){
  try {
    $('#bt_uploadImage').fileupload('destroy');
    $('#bt_uploadImage').parent().html('<i class="fas fa-cloud-upload-alt"></i> {{Envoyer}}<input  id="bt_uploadImage" type="file" name="file" style="display: inline-block;">');
  } catch(error) {

  }
  $('#bt_uploadImage').fileupload({
    replaceFileInput: false,
    url: 'core/ajax/object.ajax.php?action=uploadImage&id=' +_id +'&jeedom_token='+JEEDOM_AJAX_TOKEN,
    dataType: 'json',
    done: function (e, data) {
      if (data.result.state != 'ok') {
        $('#div_alert').showAlert({message: data.result.result, level: 'danger'});
        return;
      }
      if (isset(data.result.result.filepath)) {
        filePath = data.result.result.filepath
        filePath = '/data/object/' + filePath.split('/data/object/')[1]
        $('.objectImg img').attr('src',filePath);
        $('.objectImg img').show()
      } else {
        $('.objectImg img').hide()
      }
      $('#div_alert').showAlert({message: '{{Image ajoutée}}', level: 'success'});
    }
  });
  $(".objectDisplayCard").removeClass('active');
  $('.objectDisplayCard[data-object_id='+_id+']').addClass('active');
  $('#div_conf').show();
  $('#div_resumeObjectList').hide();
  jeedom.object.byId({
    id: _id,
    cache: false,
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function (data) {
      $('.objectAttr').value('');
      $('.objectAttr[data-l1key=father_id] option').show();
      $('#summarytab input[type=checkbox]').value(0);
      $('.object').setValues(data, '.objectAttr');

      if (!isset(data.configuration.useCustomColor) || data.configuration.useCustomColor == "0") {
        bodyStyles = window.getComputedStyle(document.body);
        objectBkgdColor = bodyStyles.getPropertyValue('--objectBkgd-color')
        objectTxtColor = bodyStyles.getPropertyValue('--objectTxt-color')

        if (!objectBkgdColor === undefined){
          objectBkgdColor = rgbToHex(objectBkgdColor)
        } else {
          objectBkgdColor = '#696969'
        }
        if (!objectTxtColor === undefined) {
          objectTxtColor = rgbToHex(objectTxtColor)
        } else {
          objectTxtColor = '#ebebeb'
        }

        $('.objectAttr[data-l1key=display][data-l2key=tagColor]').value(objectBkgdColor);
        $('.objectAttr[data-l1key=display][data-l2key=tagTextColor]').value(objectTxtColor);

        $('.objectAttr[data-l1key=display][data-l2key=tagColor]').click(function () {
          $('input[data-l2key="useCustomColor"').prop('checked', true)
        })
        $('.objectAttr[data-l1key=display][data-l2key=tagTextColor]').click(function () {
          $('input[data-l2key="useCustomColor"').prop('checked', true)
        })
      }

      $('.objectAttr[data-l1key=father_id] option[value=' + data.id + ']').hide();
      $('.div_summary').empty();
      $('.tabnumber').empty();

      if (isset(data.img)) {
        $('.objectImg img').attr('src',data.img);
        $('.objectImg img').show()
      } else {
        $('.objectImg img').hide()
      }

      if (isset(data.configuration) && isset(data.configuration.summary)) {
        for(var i in data.configuration.summary){
          var el = $('.type'+i);
          if(el != undefined){
            for(var j in data.configuration.summary[i]){
              addSummaryInfo(el,data.configuration.summary[i][j]);
            }
            if (data.configuration.summary[i].length != 0){
              $('.summarytabnumber'+i).append('(' + data.configuration.summary[i].length + ')');
            }
          }

        }
      }
      addOrUpdateUrl('id',data.id);
      modifyWithoutSave = false;
      setTimeout(function(){
        modifyWithoutSave = false;
      },1000)
    }
  });
}

$("#bt_addObject,#bt_addObject2").on('click', function (event) {
  bootbox.prompt("Nom de l'objet ?", function (result) {
    if (result !== null) {
      jeedom.object.save({
        object: {name: result, isVisible: 1},
        error: function (error) {
          $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
          modifyWithoutSave = false;
          loadPage('index.php?v=d&p=object&id=' + data.id + '&saveSuccessFull=1');
          $('#div_alert').showAlert({message: '{{Sauvegarde effectuée avec succès}}', level: 'success'});
        }
      });
    }
  });
});

$('.objectAttr[data-l1key=display][data-l2key=icon]').on('dblclick',function(){
  $('.objectAttr[data-l1key=display][data-l2key=icon]').value('');
});

$("#bt_saveObject").on('click', function (event) {
  var object = $('.object').getValues('.objectAttr')[0];
  if (!isset(object.configuration)) {
    object.configuration = {};
  }
  if (!isset(object.configuration.summary)) {
    object.configuration.summary = {};
  }
  $('.object .div_summary').each(function () {
    var type = $(this).attr('data-type');
    object.configuration.summary[type] = [];
    summaries = {};
    $(this).find('.summary').each(function () {
      var summary = $(this).getValues('.summaryAttr')[0];
      object.configuration.summary[type].push(summary);
    });
  });
  jeedom.object.save({
    object: object,
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function (data) {
      modifyWithoutSave = false;
      window.location = 'index.php?v=d&p=object&id=' + data.id + '&saveSuccessFull=1';
    }
  });
  return false;
});

$("#bt_removeObject").on('click', function (event) {
  $.hideAlert();
  bootbox.confirm('{{Êtes-vous sûr de vouloir supprimer l\'objet}} <span style="font-weight: bold ;">' + $('.objectDisplayCard.active .name').text() + '</span> ?', function (result) {
    if (result) {
      jeedom.object.remove({
        id: $('.objectDisplayCard.active').attr('data-object_id'),
        error: function (error) {
          $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function () {
          modifyWithoutSave = false;
          loadPage('index.php?v=d&p=object&removeSuccessFull=1');
        }
      });
    }
  });
  return false;
});

$('#bt_chooseIcon').on('click', function () {
  var _icon = false
  var icon = false
  var color = false
  if ( $('div[data-l2key="icon"] > i').length ) {
    color = '';
    class_icon = $('div[data-l2key="icon"] > i').attr('class')
    class_icon = class_icon.replace(' ', '.').split(' ');
    icon = '.'+class_icon[0];
    if(class_icon[1]){
      color = class_icon[1];
    }
  }
  chooseIcon(function (_icon) {
    $('.objectAttr[data-l1key=display][data-l2key=icon]').empty().append(_icon);
  },{icon:icon,color:color});
});

if (is_numeric(getUrlVars('id'))) {
  if ($('.objectDisplayCard[data-object_id=' + getUrlVars('id') + ']').length != 0) {
    $('.objectDisplayCard[data-object_id=' + getUrlVars('id') + ']').click();
  } else {
    $('.objectDisplayCard').first().click();
  }
}

$('#div_pageContainer').off('change','.objectAttr').on('change','.objectAttr:visible', function () {
  modifyWithoutSave = true;
});

$('.addSummary').on('click',function(){
  var type = $(this).attr('data-type');
  var el = $('.type'+type);
  addSummaryInfo(el);
});

$('.bt_checkAll').on('click',function(){
  $(this).closest('tr').find('input[type="checkbox"]').each(function () {
    $(this).prop( "checked", true )
  })
})

$('.bt_checkNone').on('click',function(){
  $(this).closest('tr').find('input[type="checkbox"]').each(function () {
    $(this).prop( "checked", false )
  })
});

$('#div_pageContainer').delegate(".listCmdInfo", 'click', function () {
  var el = $(this).closest('.summary').find('.summaryAttr[data-l1key=cmd]');
  jeedom.cmd.getSelectModal({cmd: {type: 'info'}}, function (result) {
    el.value(result.human);
  });
});

$('#div_pageContainer').delegate('.bt_removeSummary', 'click', function () {
  $(this).closest('.summary').remove();
});

function addSummaryInfo(_el, _summary) {
  if (!isset(_summary)) {
    _summary = {};
  }
  var div = '<div class="summary">';
  div += '<div class="form-group">';
  div += '<label class="col-sm-1 control-label">{{Commande}}</label>';
  div += '<div class="col-sm-4 has-success">';
  div += '<div class="input-group">';
  div += '<span class="input-group-btn">';
  div += '<input type="checkbox" class="summaryAttr checkbox-inline roundedLeft" data-l1key="enable" checked title="{{Activer}}" />';
  div += '<a class="btn btn-default bt_removeSummary btn-sm"><i class="fas fa-minus-circle"></i></a>';
  div += '</span>';
  div += '<input class="summaryAttr form-control input-sm" data-l1key="cmd" />';
  div += '<span class="input-group-btn">';
  div += '<a class="btn btn-sm listCmdInfo btn-success roundedRight"><i class="fas fa-list-alt"></i></a>';
  div += '</span>';
  div += '</div>';
  div += '</div>';
  div += '<div class="col-sm-2 has-success">';
  div += '<label><input type="checkbox" class="summaryAttr checkbox-inline" data-l1key="invert" />{{Inverser}}</label>';
  div += '</div>';
  div += '</div>';
  _el.find('.div_summary').append(div);
  _el.find('.summary').last().setValues(_summary, '.summaryAttr');
}

$('.bt_showObjectSummary').off('click').on('click', function () {
  $('#md_modal').dialog({title: "{{Résumé Objets}}"});
  $("#md_modal").load('index.php?v=d&modal=object.summary').dialog('open');
});
