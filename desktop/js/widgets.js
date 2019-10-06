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

var widget_parameters_opt = {
  'desktop_width' : {
    'type' : 'input',
    'name' : '{{Largeur desktop}} <sub>px</sub>'
  },
  'mobile_width' : {
    'type' : 'input',
    'name' : '{{Largeur mobile}} <sub>px</sub>'
  },
  'time_widget' : {
    'type' : 'checkbox',
    'name' : '{{Time widget}}'
  }
}

$('#in_searchWidgets').keyup(function () {
  var search = $(this).value()
  if (search == '') {
    $('.panel-collapse.in').closest('.panel').find('.accordion-toggle').click()
    $('.widgetsDisplayCard').show()
    $('.widgetsListContainer').packery()
    return;
  }
  search = normTextLower(search)
  $('.widgetsDisplayCard').hide()
  $('.panel-collapse').attr('data-show',0)
  $('.widgetsDisplayCard .name').each(function() {
    var text = $(this).text()
    text = normTextLower(text)
    if(text.indexOf(search) >= 0){
      $(this).closest('.widgetsDisplayCard').show();
      $(this).closest('.panel-collapse').attr('data-show',1)
    }
  })
  $('.panel-collapse[data-show=1]').collapse('show')
  $('.panel-collapse[data-show=0]').collapse('hide')
  $('.widgetsListContainer').packery()
})

$('#bt_openAll').off('click').on('click', function () {
  $(".accordion-toggle[aria-expanded='false']").click()
});
$('#bt_closeAll').off('click').on('click', function () {
  $(".accordion-toggle[aria-expanded='true']").click()
})

$('#bt_resetWidgetsSearch').off('click').on('click', function () {
  $('#in_searchWidgets').val('')
  $('#in_searchWidgets').keyup();
})

$('#bt_editCode').off('click').on('click', function () {
  loadPage('index.php?v=d&p=editor&type=widget');
})

$('#bt_replaceWidget').off('click').on('click',function(){
  $('#md_modal').dialog({title: "{{Remplacement de widget}}"}).load('index.php?v=d&modal=widget.replace').dialog('open')
  $('#md_modal').dialog("option", "width", 800).dialog("option", "height", 500)
  $("#md_modal").dialog({
    position: {
      my: "center center",
      at: "center center",
      of: window
    }
  })
})


$('#bt_applyToCmd').off('click').on('click', function () {
  $('#md_modal').dialog({title: "{{Appliquer sur}}"})
  .load('index.php?v=d&modal=cmd.selectMultiple&type='+$('.widgetsAttr[data-l1key=type]').value()+'&subtype='+$('.widgetsAttr[data-l1key=subtype]').value(), function() {
    initTableSorter();
    $('#bt_cmdConfigureSelectMultipleAlertToogle').off('click').on('click', function () {
      var state = false;
      if ($(this).attr('data-state') == 0) {
        state = true;
        $(this).attr('data-state', 1)
        .find('i').removeClass('fa-check-circle-o').addClass('fa-circle-o');
        $('#table_cmdConfigureSelectMultiple tbody tr .selectMultipleApplyCmd:visible').value(1);
      } else {
        state = false;
        $(this).attr('data-state', 0)
        .find('i').removeClass('fa-circle-o').addClass('fa-check-circle-o');
        $('#table_cmdConfigureSelectMultiple tbody tr .selectMultipleApplyCmd:visible').value(0);
      }
    });
    
    $('#bt_cmdConfigureSelectMultipleAlertApply').off().on('click', function () {
      var widgets = $('.widgets').getValues('.widgetsAttr')[0];
      widgets.test = $('#div_templateTest .test').getValues('.testAttr');
      jeedom.widgets.save({
        widgets: widgets,
        error: function (error) {
          $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
          modifyWithoutSave = false;
          cmd = {template : {dashboard : 'custom::'+$('.widgetsAttr[data-l1key=name]').value(),mobile : 'custom::'+$('.widgetsAttr[data-l1key=name]').value()}};
          $('#table_cmdConfigureSelectMultiple tbody tr').each(function () {
            if ($(this).find('.selectMultipleApplyCmd').prop('checked')) {
              cmd.id = $(this).attr('data-cmd_id');
              jeedom.cmd.save({
                cmd: cmd,
                error: function (error) {
                  $('#md_cmdConfigureSelectMultipleAlert').showAlert({message: error.message, level: 'danger'});
                },
                success: function () {}
              });
              $('#md_cmdConfigureSelectMultipleAlert').showAlert({message: "{{Modification(s) appliquée(s) avec succès}}", level: 'success'});
            }
          });
        }
      });
    });
  }).dialog('open');;
});

//context menu
$(function(){
  try{
    $.contextMenu('destroy', $('.nav.nav-tabs'));
    jeedom.widgets.all({
      error: function (error) {
        $('#div_alert').showAlert({message: error.message, level: 'danger'});
      },
      success: function (_widgets) {
        if(_widgets.length == 0){
          return;
        }
        var widgetsList = []
        widgetsList['info'] = []
        widgetsList['action'] = []
        for(i=0; i<_widgets.length; i++)
        {
          wg = _widgets[i]
          if (wg.type == 'info') widgetsList['info'].push([wg.name, wg.id])
          if (wg.type == 'action') widgetsList['action'].push([wg.name, wg.id])
        }
        
        //set context menu!
        var contextmenuitems = {}
        var uniqId = 0
        for (var group in widgetsList) {
          groupWidgets = widgetsList[group]
          items = {}
          for (var index in groupWidgets) {
            wg = groupWidgets[index]
            wgName = wg[0]
            wgId = wg[1]
            items[uniqId] = {'name': wgName, 'id' : wgId}
            uniqId ++
          }
          contextmenuitems[group] = {'name':group, 'items':items}
        }
        
        $('.nav.nav-tabs').contextMenu({
          selector: 'li',
          autoHide: true,
          zIndex: 9999,
          className: 'widget-context-menu',
          callback: function(key, options) {
            url = 'index.php?v=d&p=widgets&id=' + options.commands[key].id;
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

jwerty.key('ctrl+s/⌘+s', function (e) {
  e.preventDefault();
  $("#bt_saveWidgets").click();
});

$('#bt_chooseIcon').on('click', function () {
  var _icon = false
  if ( $('div[data-l2key="icon"] > i').length ) {
    _icon = $('div[data-l2key="icon"] > i').attr('class')
    _icon = '.' + _icon.replace(' ', '.')
  }
  chooseIcon(function (_icon) {
    $('.widgetsAttr[data-l1key=display][data-l2key=icon]').empty().append(_icon);
  },{icon:_icon});
});

$('.widgetsAttr[data-l1key=display][data-l2key=icon]').off('dblclick').on('dblclick',function(){
  $('.widgetsAttr[data-l1key=display][data-l2key=icon]').value('');
});

$('.widgetsAttr[data-l1key=type]').off('change').on('change',function(){
  $('#div_templateReplace').empty();
  $('#div_templateTest').empty();
  $('#div_usedBy').empty()
  $('.selectWidgetSubType').hide().removeClass('widgetsAttr');
  $('.selectWidgetSubType[data-type='+$(this).value()+']').show().addClass('widgetsAttr').change();
});

$('.selectWidgetSubType').off('change').on('change',function(){
  $('#div_templateReplace').empty();
  $('#div_templateTest').empty();
  $('#div_usedBy').empty()
  $('.selectWidgetTemplate').hide().removeClass('widgetsAttr');
  $('.selectWidgetTemplate[data-type='+$('.widgetsAttr[data-l1key=type]').value()+'][data-subtype='+$(this).value()+']').show().addClass('widgetsAttr').change();
});

$('#div_templateReplace').off('click','.chooseIcon').on('click','.chooseIcon', function () {
  var bt = $(this);
  chooseIcon(function (_icon) {
    bt.closest('.form-group').find('.widgetsAttr[data-l1key=replace]').value(_icon);
  },{img:true});
});

$('#div_templateTest').off('click','.chooseIcon').on('click','.chooseIcon', function () {
  var bt = $(this);
  chooseIcon(function (_icon) {
    bt.closest('.input-group').find('.testAttr').value(_icon);
  },{img:true});
});

function capitalizeFirstLetter(string) {
  return string.charAt(0).toUpperCase() + string.slice(1);
}

function loadTemplateConfiguration(_template,_data){
  $('.selectWidgetTemplate').off('change')
  jeedom.widgets.getTemplateConfiguration({
    template:_template,
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function (data) {
      $('#div_templateReplace').empty();
      if(typeof data.replace != 'undefined' && data.replace.length > 0){
        $('.type_replace').show();
        var replace = '';
        for(var i in data.replace){
          replace += '<div class="form-group">';
          if(widget_parameters_opt[data.replace[i]]){
            replace += '<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">'+widget_parameters_opt[data.replace[i]].name+'</label>';
          }else{
            replace += '<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">'+capitalizeFirstLetter(data.replace[i].replace("icon_", "").replace("img_", "").replace("_", " "))+'</label>';
          }
          replace += '<div class="col-lg-6 col-md-8 col-sm-8 col-xs-8">';
          replace += '<div class="input-group">';
          if(widget_parameters_opt[data.replace[i]]){
            if(widget_parameters_opt[data.replace[i]].type == 'checkbox'){
              replace += '<input type="checkbox" class="form-control widgetsAttr roundedLeft" data-l1key="replace" data-l2key="#_'+data.replace[i]+'_#"/>';
            }else if(widget_parameters_opt[data.replace[i]].type == 'number'){
              replace += '<input type="number" class="form-control widgetsAttr roundedLeft" data-l1key="replace" data-l2key="#_'+data.replace[i]+'_#"/>';
            }else if(widget_parameters_opt[data.replace[i]].type == 'input'){
              replace += '<input class="form-control widgetsAttr roundedLeft" data-l1key="replace" data-l2key="#_'+data.replace[i]+'_#"/>';
            }
          }else{
            replace += '<input class="form-control widgetsAttr roundedLeft" data-l1key="replace" data-l2key="#_'+data.replace[i]+'_#"/>';
          }
          if(data.replace[i].indexOf('icon_') != -1 || data.replace[i].indexOf('img_') != -1){
            replace += '<span class="input-group-btn">';
            replace += '<a class="btn chooseIcon roundedRight"><i class="fas fa-flag"></i> {{Choisir}}</a>';
            replace += '</span>';
          }
          replace += '</div>';
          replace += '</div>';
          replace += '</div>';
        }
        $('#div_templateReplace').append(replace);
      }else{
        $('.type_replace').hide();
      }
      if(typeof _data != 'undefined'){
        $('.widgets').setValues({replace : _data.replace}, '.widgetsAttr');
      }
      if(data.test){
        $('.type_test').show();
      }else{
        $('.type_test').hide();
      }
      $('.selectWidgetTemplate').on('change',function(){
        if($(this).value() == '' || !$(this).hasClass('widgetsAttr')){
          return;
        }
        loadTemplateConfiguration('cmd.'+ $('.widgetsAttr[data-l1key=type]').value()+'.'+$('.widgetsAttr[data-l1key=subtype]').value()+'.'+$(this).value());
      });
      modifyWithoutSave = false;
    }
  });
}

setTimeout(function(){
  $('.widgetsListContainer').packery();
},100);

$('.accordion-toggle').off('click').on('click', function () {
  setTimeout(function(){
    $('.widgetsListContainer').packery();
  },100);
});

$('#div_pageContainer').off('change','.widgetsAttr').on('change','.widgetsAttr:visible', function () {
  modifyWithoutSave = true;
});

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
  $('#div_widgetsList').show();
  $('.widgetsListContainer').packery();
  addOrUpdateUrl('id',null,'{{Widgets}} - '+JEEDOM_PRODUCT_NAME);
});

$('#bt_widgetsAddTest').off('click').on('click', function (event) {
  addTest({})
});

$('#div_templateTest').off('click','.bt_removeTest').on('click','.bt_removeTest',function(){
  $(this).closest('.test').remove();
});

function addTest(_test){
  if (!isset(_test)) {
    _trigger = {};
  }
  var div = '<div class="test">';
  div += '<div class="form-group">';
  div += '<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Test}}</label>';
  div += '<div class="col-sm-3">';
  div += '<div class="input-group">';
  div += '<span class="input-group-btn">';
  div += '<a class="btn btn-sm bt_removeTest roundedLeft"><i class="fas fa-minus-circle"></i></a>';
  div += '</span>';
  div += '<input class="testAttr form-control input-sm roundedRight" data-l1key="operation" placeholder="Test, utiliser #value# pour la valeur"/>';
  div += '</div>';
  div += '</div>';
  div += '<div class="col-sm-3">';
  div += '<div class="input-group">';
  div += '<input class="testAttr form-control input-sm roundedLeft" data-l1key="state_light" placeholder="Résultat si test ok (light)"/>';
  div += '<span class="input-group-btn">';
  div += '<a class="btn btn-sm chooseIcon roundedRight"><i class="fas fa-flag"></i> {{Choisir}}</a>';
  div += '</span>';
  div += '</div>';
  div += '</div>';
  div += '<div class="col-sm-3">';
  div += '<div class="input-group">';
  div += '<input class="testAttr form-control input-sm roundedLeft" data-l1key="state_dark" placeholder="Résultat si test ok (dark)"/>';
  div += '<span class="input-group-btn">';
  div += '<a class="btn btn-sm chooseIcon roundedRight"><i class="fas fa-flag"></i> {{Choisir}}</a>';
  div += '</span>';
  div += '</div>';
  div += '</div>';
  
  div += '</div>';
  div += '</div>';
  $('#div_templateTest').append(div);
  $('#div_templateTest').find('.test').last().setValues(_test, '.testAttr');
}

$("#bt_addWidgets").off('click').on('click', function (event) {
  bootbox.prompt("Nom du widget ?", function (result) {
    if (result !== null) {
      jeedom.widgets.save({
        widgets: {name: result},
        error: function (error) {
          $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
          modifyWithoutSave = false;
          loadPage('index.php?v=d&p=widgets&id=' + data.id + '&saveSuccessFull=1');
          $('#div_alert').showAlert({message: '{{Sauvegarde effectuée avec succès}}', level: 'success'});
        }
      });
    }
  });
});

$('#div_usedBy').off('click','.cmdAdvanceConfigure').on('click','.cmdAdvanceConfigure',function(){
  $('#md_modal').dialog({title: "{{Configuration de la commande}}"});
  $('#md_modal').load('index.php?v=d&modal=cmd.configure&cmd_id=' + $(this).attr('data-cmd_id')).dialog('open');
});

$(".widgetsDisplayCard").on('click', function (event) {
  $('#div_conf').show();
  $('#div_widgetsList').hide();
  $('#div_templateTest').empty();
  jeedom.widgets.byId({
    id: $(this).attr('data-widgets_id'),
    cache: false,
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function (data) {
      $('a[href="#widgetstab"]').click();
      $('.selectWidgetTemplate').off('change')
      $('.widgetsAttr').value('');
      $('.widgetsAttr[data-l1key=type]').value('info')
      $('.widgetsAttr[data-l1key=subtype]').value($('.widgetsAttr[data-l1key=subtype]').find('option:first').attr('value'));
      $('.widgets').setValues(data, '.widgetsAttr');
      if (isset(data.test)) {
        for (var i in data.test) {
          addTest(data.test[i]);
        }
      }
      var usedBy = '';
      for(var i in data.usedBy){
        usedBy += '<span class="label label-info cursor cmdAdvanceConfigure" data-cmd_id="'+i+'">'+ data.usedBy[i]+'</span> ';
      }
      $('#div_usedBy').empty().append(usedBy);
      var template = 'cmd.';
      if(data.type && data.type !== null){
        template += data.type+'.';
      }else{
        template += 'action.';
      }
      if(data.subtype && data.subtype !== null){
        template += data.subtype+'.';
      }else{
        template += 'other.';
      }
      if(data.template && data.template !== null){
        template += data.template;
      }else{
        template += 'tmplicon';
      }
      loadTemplateConfiguration(template,data);
      addOrUpdateUrl('id',data.id);
      modifyWithoutSave = false;
      jeedom.widgets.getPreview({
        id: data.id,
        cache: false,
        error: function (error) {
          $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
          $('#div_widgetPreview').empty().html(data.html);
        }
      })
    }
  });
});

if (is_numeric(getUrlVars('id'))) {
  if ($('.widgetsDisplayCard[data-widgets_id=' + getUrlVars('id') + ']').length != 0) {
    $('.widgetsDisplayCard[data-widgets_id=' + getUrlVars('id') + ']').click();
  } else {
    $('.widgetsDisplayCard').first().click();
  }
}

$("#bt_saveWidgets").on('click', function (event) {
  var widgets = $('.widgets').getValues('.widgetsAttr')[0];
  widgets.test = $('#div_templateTest .test').getValues('.testAttr');
  jeedom.widgets.save({
    widgets: widgets,
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function (data) {
      modifyWithoutSave = false;
      window.location = 'index.php?v=d&p=widgets&id=' + data.id + '&saveSuccessFull=1';
    }
  });
  return false;
});

$('#bt_removeWidgets').on('click', function (event) {
  bootbox.confirm('{{Êtes-vous sûr de vouloir supprimer ce widget ?}}', function (result) {
    if (result) {
      jeedom.widgets.remove({
        id: $('.widgetsAttr[data-l1key=id]').value(),
        error: function (error) {
          $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
          modifyWithoutSave = false;
          window.location = 'index.php?v=d&p=widgets&removeSuccessFull=1';
        }
      });
    }
  });
});

$("#bt_exportWidgets").on('click', function (event) {
  var widgets = $('.widgets').getValues('.widgetsAttr')[0]
  widgets.test = $('#div_templateTest .test').getValues('.testAttr')
  widgets.id = ""
  jeedom.version({success : function(version) {
    widgets.jeedomCoreVersion = version
    downloadObjectAsJson(widgets, widgets.name)
  }
})
return false
});

$("#bt_mainImportWidgets").change(function(event) {
  $('#div_alert').hide()
  var uploadedFile = event.target.files[0]
  if(uploadedFile.type !== "application/json") {
    $('#div_alert').showAlert({message: "{{L'import de widgets se fait au format json à partir de widgets précedemment exporté.}}", level: 'danger'})
    return false
  }
  
  if (uploadedFile) {
    bootbox.prompt("Nom du widget ?", function (result) {
      if (result !== null) {
        jeedom.widgets.save({
          widgets: {name: result},
          error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
          },
          success: function (data) {
            var readFile = new FileReader()
            readFile.readAsText(uploadedFile)
            readFile.onload = function(e) {
              objectData = JSON.parse(e.target.result)
              if (!isset(objectData.jeedomCoreVersion)) {
                $('#div_alert').showAlert({message: "{{Fichier json non compatible.}}", level: 'danger'})
                return false
              }
              objectData.id = data.id
              objectData.name = data.name
              if (isset(objectData.test)) {
                for (var i in objectData.test) {
                  addTest(objectData.test[i])
                }
              }
              jeedom.widgets.save({
                widgets: objectData,
                error: function (error) {
                  $('#div_alert').showAlert({message: error.message, level: 'danger'});
                },
                success: function (data) {
                  loadPage('index.php?v=d&p=widgets&id=' + objectData.id+ '&saveSuccessFull=1');
                }
              })
            }
          }
        })
      }
    })
  } else {
    $('#div_alert').showAlert({message: "{{Problème lors de la lecture du fichier.}}", level: 'danger'})
    return false
  }
})

$("#bt_importWidgets").change(function(event) {
  $('#div_alert').hide()
  var uploadedFile = event.target.files[0]
  if(uploadedFile.type !== "application/json") {
    $('#div_alert').showAlert({message: "{{L'import de widgets se fait au format json à partir de widgets précedemment exporté.}}", level: 'danger'})
    return false
  }
  if (uploadedFile) {
    var readFile = new FileReader()
    readFile.readAsText(uploadedFile)
    
    readFile.onload = function(e) {
      objectData = JSON.parse(e.target.result)
      if (!isset(objectData.jeedomCoreVersion)) {
        $('#div_alert').showAlert({message: "{{Fichier json non compatible.}}", level: 'danger'})
        return false
      }
      objectData.id = $('.widgetsAttr[data-l1key=id]').value();
      objectData.name = $('.widgetsAttr[data-l1key=name]').value();
      if (isset(objectData.test)) {
        for (var i in objectData.test) {
          addTest(objectData.test[i])
        }
      }
      loadTemplateConfiguration('cmd.'+objectData.type+'.'+objectData.subtype+'.'+objectData.template, objectData)
    }
  } else {
    $('#div_alert').showAlert({message: "{{Problème lors de la lecture du fichier.}}", level: 'danger'})
    return false
  }
})

function downloadObjectAsJson(exportObj, exportName){
  var dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(exportObj))
  var downloadAnchorNode = document.createElement('a')
  downloadAnchorNode.setAttribute("href",     dataStr)
  downloadAnchorNode.setAttribute("target", "_blank")
  downloadAnchorNode.setAttribute("download", exportName + ".json")
  document.body.appendChild(downloadAnchorNode) // required for firefox
  downloadAnchorNode.click()
  downloadAnchorNode.remove()
}
