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

var $interactListContainer = $('.interactListContainer')
$('.backgroundforJeedom').css({
  'background-position':'bottom right',
  'background-repeat':'no-repeat',
  'background-size':'auto'
});


jwerty.key('ctrl+s/⌘+s', function (e) {
  e.preventDefault();
  $("#bt_saveInteract").click();
});

//searching
$('#in_searchInteract').keyup(function () {
  var search = $(this).value()
  if (search == '') {
    $('.panel-collapse.in').closest('.panel').find('.accordion-toggle').click()
    $('.interactDisplayCard').show()
    $interactListContainer.packery()
    return
  }
  search = normTextLower(search)

  $('.panel-collapse:not(.in)').closest('.panel').find('.accordion-toggle').click()
  $('.interactDisplayCard').hide()
  $('.panel-collapse').attr('data-show',0)
  $('.interactDisplayCard .name').each(function(){
    var text = $(this).text()
    text = normTextLower(text)
    if (text.indexOf(search) >= 0) {
      $(this).closest('.interactDisplayCard').show()
      $(this).closest('.panel-collapse').attr('data-show',1)
    }
  })
  $('.panel-collapse[data-show=1]').collapse('show')
  $('.panel-collapse[data-show=0]').collapse('hide')
  $interactListContainer.packery()
})
$('#bt_resetInteractSearch').on('click', function () {
  $('#in_searchInteract').val('').keyup()
})

$('#bt_openAll').off('click').on('click', function () {
  $(".accordion-toggle[aria-expanded='false']").each(function() {
    $(this).click()
  })
})
$('#bt_closeAll').off('click').on('click', function () {
  $(".accordion-toggle[aria-expanded='true']").each(function() {
    $(this).click()
  })
})

$('#bt_chooseIcon').on('click', function () {
  var _icon = false
  if ( $('div[data-l2key="icon"] > i').length ) {
    _icon = $('div[data-l2key="icon"] > i').attr('class')
    _icon = '.' + _icon.replace(' ', '.')
  }
  chooseIcon(function (_icon) {
    $('.interactAttr[data-l1key=display][data-l2key=icon]').empty().append(_icon);
  },{icon:_icon});
});

$('.interactAttr[data-l1key=display][data-l2key=icon]').on('dblclick',function(){
  $(this).value('');
});

//contextMenu:
$(function(){
  try{
    $.contextMenu('destroy', $('.nav.nav-tabs'));
    jeedom.interact.all({
      error: function (error) {
        $('#div_alert').showAlert({message: error.message, level: 'danger'});
      },
      success: function (interacts) {
        if(interacts.length == 0){
          return;
        }
        var interactGroups = []
        for(i=0; i<interacts.length; i++){
          group = interacts[i].group
          if (group == null) continue
          if (group == "") group = 'Aucun'
          group = group[0].toUpperCase() + group.slice(1)
          interactGroups.push(group)
        }
        interactGroups = Array.from(new Set(interactGroups))
        interactGroups.sort()
        var interactList = []
        for(i=0; i<interactGroups.length; i++)
        {
          group = interactGroups[i]
          interactList[group] = []
          for(j=0; j<interacts.length; j++)
          {
            sc = interacts[j]
            scGroup = sc.group
            if (scGroup == null) continue
            if (scGroup == "") scGroup = 'Aucun'
            if (scGroup.toLowerCase() != group.toLowerCase()) continue
            if (sc.name == "") sc.name = sc.query
            interactList[group].push([sc.name, sc.id])
          }
        }
        //set context menu!
        var contextmenuitems = {}
        var uniqId = 0
        for (var group in interactList) {
          groupinteracts = interactList[group]
          items = {}
          for (var index in groupinteracts) {
            sc = groupinteracts[index]
            scName = sc[0]
            scId = sc[1]
            items[uniqId] = {'name': scName, 'id' : scId}
            uniqId ++
          }
          contextmenuitems[group] = {'name':group, 'items':items}
        }

        if (Object.entries(contextmenuitems).length > 0 && contextmenuitems.constructor === Object){
          $('.nav.nav-tabs').contextMenu({
            selector: 'li',
            autoHide: true,
            zIndex: 9999,
            className: 'interact-context-menu',
            callback: function(key, options) {
              url = 'index.php?v=d&p=interact&id=' + options.commands[key].id;
              if (document.location.toString().match('#')) {
                url += '#' + document.location.toString().split('#')[1];
              }
              loadPage(url);
            },
            items: contextmenuitems
          })
        }
      }
    })
  }
  catch(err) {}
})

$("#div_action").sortable({axis: "y", cursor: "move", items: ".action", placeholder: "ui-state-highlight", tolerance: "intersect", forcePlaceholderSize: true});

$('.displayInteracQuery').on('click', function () {
  $('#md_modal').dialog({title: "{{Liste des interactions}}"});
  $('#md_modal').load('index.php?v=d&modal=interact.query.display&interactDef_id=' + $('.interactAttr[data-l1key=id]').value()).dialog('open');
});

setTimeout(function(){
  $interactListContainer.packery();
},100);

$("#div_listInteract").trigger('resize');

$interactListContainer.packery();

$('#bt_interactThumbnailDisplay').on('click', function () {
  if (modifyWithoutSave) {
    if (!confirm('{{Attention vous quittez une page ayant des données modifiées non sauvegardées. Voulez-vous continuer ?}}')) {
      return
    }
    modifyWithoutSave = false
  }

  $('#div_conf').hide();
  $('#interactThumbnailDisplay').show();
  $interactListContainer.packery();
  addOrUpdateUrl('id',null,'{{Interactions}} - '+JEEDOM_PRODUCT_NAME);
});

$('.interactDisplayCard').on('click', function () {
  $('#div_tree').jstree('deselect_all');
  $('#div_tree').jstree('select_node', 'interact' + $(this).attr('data-interact_id'));
});

$("#div_tree").jstree({
  "plugins": ["search"]
});
$('#in_treeSearch').keyup(function () {
  $('#div_tree').jstree(true).search($('#in_treeSearcxh').val());
});

$('.interactDisplayCard').on('click',function(){
  displayInteract($(this).attr('data-interact_id'));
  if(document.location.toString().split('#')[1] == '' || document.location.toString().split('#')[1] == undefined){
    $('.nav-tabs a[href="#generaltab"]').click();
  }
});

$('#div_pageContainer').off('change','.interactAttr').on('change','.interactAttr:visible', function () {
  modifyWithoutSave = true;
});

$('.accordion-toggle').off('click').on('click', function () {
  setTimeout(function(){
    $interactListContainer.packery();
  },100);
});

$('#bt_duplicate').on('click', function () {
  bootbox.prompt("Nom ?", function (result) {
    if (result !== null) {
      var interact = $('.interact').getValues('.interactAttr')[0];
      interact.actions = {};
      interact.actions.cmd = $('#div_action .action').getValues('.expressionAttr');
      interact.name = result;
      interact.id = '';
      jeedom.interact.save({
        interact: interact,
        error: function (error) {
          $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
          modifyWithoutSave = false;
          loadPage('index.php?v=d&p=interact&id=' + data.id + '&saveSuccessFull=1');
        }
      });
    }
  });
});

if (is_numeric(getUrlVars('id'))) {
  if ($('.interactDisplayCard[data-interact_id=' + getUrlVars('id') + ']').length != 0) {
    $('.interactDisplayCard[data-interact_id=' + getUrlVars('id') + ']').click();
  }
}

$('#bt_testInteract,#bt_testInteract2').on('click', function () {
  $('#md_modal').dialog({title: "{{Tester les interactions}}"});
  $('#md_modal').load('index.php?v=d&modal=interact.test').dialog('open');
});

$('#div_pageContainer').delegate('.listEquipementInfoReply', 'click', function () {
  jeedom.cmd.getSelectModal({cmd : {type : 'info'}}, function (result) {
    $('.interactAttr[data-l1key=reply]').atCaret('insert',result.human);
  });
});

$("#bt_saveInteract").on('click', function () {
  var interact = $('.interact').getValues('.interactAttr')[0];
  interact.filtres.type = {};
  $('option[data-l1key=filtres][data-l2key=type]').each(function() {
    interact.filtres.type[$(this).attr('data-l3key')] = ($(this).prop('selected') === true) ? '1' : '0';
  });
  interact.filtres.subtype = {};
  $('option[data-l1key=filtres][data-l2key=subtype]').each(function() {
    interact.filtres.subtype[$(this).attr('data-l3key')] = ($(this).prop('selected') === true) ? '1' : '0';
  });
  interact.filtres.unite = {};
  $('option[data-l1key=filtres][data-l2key=unite]').each(function() {
    interact.filtres.unite[$(this).attr('data-l3key')] = ($(this).prop('selected') === true) ? '1' : '0';
  });
  interact.filtres.object = {};
  $('option[data-l1key=filtres][data-l2key=object]').each(function() {
    interact.filtres.object[$(this).attr('data-l3key')] = ($(this).prop('selected') === true) ? '1' : '0';
  });
  interact.filtres.plugin = {};
  $('option[data-l1key=filtres][data-l2key=plugin]').each(function() {
    interact.filtres.plugin[$(this).attr('data-l3key')] = ($(this).prop('selected') === true) ? '1' : '0';
  });
  interact.filtres.category = {};
  $('option[data-l1key=filtres][data-l2key=category]').each(function() {
    interact.filtres.category[$(this).attr('data-l3key')] = ($(this).prop('selected') === true) ? '1' : '0';
  });
  interact.filtres.visible = {};
  $('option[data-l1key=filtres][data-l2key=visible]').each(function() {
    interact.filtres.visible[$(this).attr('data-l3key')] = ($(this).prop('selected') === true) ? '1' : '0';
  });

  interact.actions = {};
  interact.actions.cmd = $('#div_action .action').getValues('.expressionAttr');

  jeedom.interact.save({
    interact: interact,
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function (data) {
      $('.interactDisplayCard[data-interact_id=' + data.id + ']').click();
      $('#div_alert').showAlert({message: '{{Sauvegarde réussie avec succès}}', level: 'success'});
    }
  });
});

$("#bt_regenerateInteract,#bt_regenerateInteract2").on('click', function () {
  bootbox.confirm('{{Êtes-vous sûr de vouloir regénérer toutes les interations (cela peut être très long) ?}}', function (result) {
    if (result) {
      jeedom.interact.regenerateInteract({
        interact: {query: result},
        error: function (error) {
          $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
          $('#div_alert').showAlert({message: '{{Toutes les interations ont été regénérées}}', level: 'success'});
        }
      });
    }
  });
});

$("#bt_addInteract,#bt_addInteract2").on('click', function () {
  bootbox.prompt("Demande ?", function (result) {
    if (result !== null) {
      jeedom.interact.save({
        interact: {query: result},
        error: function (error) {
          $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
          modifyWithoutSave = false;
          loadPage('index.php?v=d&p=interact&id=' + data.id + '&saveSuccessFull=1');
        }
      });
    }
  });
});

$("#bt_removeInteract").on('click', function () {
  $.hideAlert();
  bootbox.confirm('{{Êtes-vous sûr de vouloir supprimer l\'interaction}} <span style="font-weight: bold ;">' + $('.interactDisplayCard.active .name').text() + '</span> ?', function (result) {
    if (result) {
      jeedom.interact.remove({
        id: $('.interactDisplayCard.active').attr('data-interact_id'),
        error: function (error) {
          $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function () {
          modifyWithoutSave = false;
          loadPage('index.php?v=d&p=interact&removeSuccessFull=1');
        }
      });
    }
  });
});

$('#bt_addAction').off('click').on('click',function(){
  addAction({}, 'action','{{Action}}');
});

$('#div_pageContainer').undelegate(".cmdAction.expressionAttr[data-l1key=cmd]", 'focusout').delegate('.cmdAction.expressionAttr[data-l1key=cmd]', 'focusout', function (event) {
  var type = $(this).attr('data-type')
  var expression = $(this).closest('.' + type).getValues('.expressionAttr');
  var el = $(this);
  jeedom.cmd.displayActionOption($(this).value(), init(expression[0].options), function (html) {
    el.closest('.' + type).find('.actionOptions').html(html);
    taAutosize();
  })
});

$("body").undelegate(".listCmd", 'click').delegate(".listCmd", 'click', function () {
  var type = $(this).attr('data-type');
  var el = $(this).closest('.' + type).find('.expressionAttr[data-l1key=cmd]');
  jeedom.cmd.getSelectModal({cmd:{type:'info'}}, function (result) {
    el.value(result.human);
    jeedom.cmd.displayActionOption(el.value(), '', function (html) {
      el.closest('.' + type).find('.actionOptions').html(html);
      taAutosize();
    });
  });
});

$("body").undelegate(".listAction", 'click').delegate(".listAction", 'click', function () {
  var type = $(this).attr('data-type');
  var el = $(this).closest('.' + type).find('.expressionAttr[data-l1key=cmd]');
  jeedom.getSelectActionModal({}, function (result) {
    el.value(result.human);
    jeedom.cmd.displayActionOption(el.value(), '', function (html) {
      el.closest('.' + type).find('.actionOptions').html(html);
      taAutosize();
    });
  });
});

$("body").undelegate(".listCmdAction", 'click').delegate(".listCmdAction", 'click', function () {
  var type = $(this).attr('data-type');
  var el = $(this).closest('.' + type).find('.expressionAttr[data-l1key=cmd]');
  jeedom.cmd.getSelectModal({cmd:{type:'action'}}, function (result) {
    el.value(result.human);
    jeedom.cmd.displayActionOption(el.value(), '', function (html) {
      el.closest('.' + type).find('.actionOptions').html(html);
      taAutosize();
    });
  });
});

$("body").undelegate('.bt_removeAction', 'click').delegate('.bt_removeAction', 'click', function () {
  var type = $(this).attr('data-type');
  $(this).closest('.' + type).remove();
});

function displayInteract(_id){
  $('#div_conf').show();
  $('#interactThumbnailDisplay').hide();
  $('.interactDisplayCard').removeClass('active');
  $('.interactDisplayCard[data-interact_id='+_id+']').addClass('active');
  jeedom.interact.get({
    id: _id,
    success: function (data) {
      actionOptions = []
      $('#div_action').empty();
      $('.interactAttr').value('');
      $('.interact').setValues(data, '.interactAttr');
      $('.interactAttr[data-l1key=filtres][data-l2key=type]').prop('selected', false);
      $('.interactAttr[data-l1key=filtres][data-l2key=subtype]').prop('selected', false);
      $('.interactAttr[data-l1key=filtres][data-l2key=unite]').prop('selected', false);
      $('.interactAttr[data-l1key=filtres][data-l2key=object]').prop('selected', false);
      $('.interactAttr[data-l1key=filtres][data-l2key=plugin]').prop('selected', false);
      $('.interactAttr[data-l1key=filtres][data-l2key=category]').prop('selected', false);
      if(isset(data.filtres) && isset(data.filtres.type) && $.isPlainObject(data.filtres.type)){
        for(var i in data.filtres.type){
          if(data.filtres.type[i] == 1) $('.interactAttr[data-l1key=filtres][data-l2key=type][data-l3key='+i+']').prop('selected', true)
        }
      }
      if(isset(data.filtres) && isset(data.filtres.subtype) && $.isPlainObject(data.filtres.subtype)){
        for(var i in data.filtres.subtype){
          if(data.filtres.subtype[i] == 1) $('.interactAttr[data-l1key=filtres][data-l2key=subtype][data-l3key='+i+']').prop('selected', true)
        }
      }
      if(isset(data.filtres) && isset(data.filtres.unite) && $.isPlainObject(data.filtres.unite)){
        for(var i in data.filtres.unite){
          if(data.filtres.unite[i] == 1) $('.interactAttr[data-l1key=filtres][data-l2key=unite][data-l3key="'+i+'"]').prop('selected', true)
        }
      }
      if(isset(data.filtres) && isset(data.filtres.object) && $.isPlainObject(data.filtres.object)){
        for(var i in data.filtres.object){
          if(data.filtres.object[i] == 1) $('.interactAttr[data-l1key=filtres][data-l2key=object][data-l3key='+i+']').prop('selected', true)
        }
      }
      if(isset(data.filtres) && isset(data.filtres.plugin) && $.isPlainObject(data.filtres.plugin)){
        for(var i in data.filtres.plugin){
          if(data.filtres.plugin[i] == 1) $('.interactAttr[data-l1key=filtres][data-l2key=plugin][data-l3key='+i+']').prop('selected', true)
        }
      }
      if(isset(data.filtres) && isset(data.filtres.category) && $.isPlainObject(data.filtres.category)){
        for(var i in data.filtres.category){
          if(data.filtres.category[i] == 1) $('.interactAttr[data-l1key=filtres][data-l2key=category][data-l3key='+i+']').prop('selected', true)
        }
      }
      if(isset(data.actions.cmd) && $.isArray(data.actions.cmd) && data.actions.cmd.length != null){
        for(var i in data.actions.cmd){
          addAction(data.actions.cmd[i], 'action','{{Action}}');
        }
      }
      taAutosize();
      addOrUpdateUrl('id',data.id);
      jeedom.cmd.displayActionsOption({
        params : actionOptions,
        async : false,
        error: function (error) {
          $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success : function(data){
          for(var i in data){
            if(data[i].html != ''){
              $('#'+data[i].id).append(data[i].html.html);
            }
          }
          taAutosize();
        }
      });
      modifyWithoutSave = false;
    }
  });
}

function addAction(_action, _type, _name) {
  if (!isset(_action)) {
    _action = {};
  }
  if (!isset(_action.options)) {
    _action.options = {};
  }
  var div = '<div class="' + _type + '">';
  div += '<div class="form-group ">';
  div += '<div class="col-sm-5">';
  div += '<div class="input-group input-group-sm">';
  div += '<span class="input-group-btn">';
  div += '<a class="btn btn-default btn-sm bt_removeAction roundedLeft" data-type="' + _type + '"><i class="fas fa-minus-circle"></i></a>';
  div += '</span>';
  div += '<input class="expressionAttr form-control cmdAction input-sm" data-l1key="cmd" data-type="' + _type + '" />';
  div += '<span class="input-group-btn">';
  div += '<a class="btn btn-default btn-sm listAction"" data-type="' + _type + '" title="{{Sélectionner un mot-clé}}"><i class="fas fa-tasks"></i></a>';
  div += '<a class="btn btn-default btn-sm listCmdAction roundedRight" data-type="' + _type + '"><i class="fas fa-list-alt"></i></a>';
  div += '</span>';
  div += '</div>';
  div += '</div>';
  var actionOption_id = uniqId();
  div += '<div class="col-sm-7 actionOptions" id="'+actionOption_id+'"></div>';
  $('#div_' + _type).append(div);
  $('#div_' + _type + ' .' + _type + '').last().setValues(_action, '.expressionAttr');
  actionOptions.push({
    expression : init(_action.cmd, ''),
    options : _action.options,
    id : actionOption_id
  });
}
