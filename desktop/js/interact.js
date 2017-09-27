
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

 $("#div_action").sortable({axis: "y", cursor: "move", items: ".action", placeholder: "ui-state-highlight", tolerance: "intersect", forcePlaceholderSize: true});

 $('.displayInteracQuery').on('click', function () {
  $('#md_modal').dialog({title: "{{Liste des interactions}}"});
  $('#md_modal').load('index.php?v=d&modal=interact.query.display&interactDef_id=' + $('.interactAttr[data-l1key=id]').value()).dialog('open');
});

 if((!isset(userProfils.doNotAutoHideMenu) || userProfils.doNotAutoHideMenu != 1) && !jQuery.support.touch){
  $('#div_listInteract').hide();
  $('#interactThumbnailDisplay').removeClass('col-xs-10').addClass('col-xs-12');
  $('#div_conf').removeClass('col-xs-10').addClass('col-xs-12');

  $('#bt_displayInteractList').on('mouseenter',function(){
   var timer = setTimeout(function(){
    $('#bt_displayInteractList').find('i').hide();
    $('#interactThumbnailDisplay').addClass('col-xs-10').removeClass('col-xs-12');
    $('#div_conf').addClass('col-xs-10').removeClass('col-xs-12');
    $('#div_listInteract').show();
    $('.interactListContainer').packery();
  }, 100);
   $(this).data('timerMouseleave', timer)
 }).on("mouseleave", function(){
  clearTimeout($(this).data('timerMouseleave'));
});

 $('#div_listInteract').on('mouseleave',function(){
  var timer = setTimeout(function(){
   $('#div_listInteract').hide();
   $('#bt_displayInteractList').find('i').show();
   $('#interactThumbnailDisplay').removeClass('col-xs-10').addClass('col-xs-12');
   $('#div_conf').removeClass('col-xs-10').addClass('col-xs-12');
   $('.interactListContainer').packery();
 }, 300);
  $(this).data('timerMouseleave', timer);
}).on("mouseenter", function(){
  clearTimeout($(this).data('timerMouseleave'));
});
}

setTimeout(function(){
  $('.interactListContainer').packery();
},100);

$("#div_listInteract").trigger('resize');

$('.interactListContainer').packery();

$('#bt_interactThumbnailDisplay').on('click', function () {
  $('#div_conf').hide();
  $('#interactThumbnailDisplay').show();
  $('.li_interact').removeClass('active');
  $('.interactListContainer').packery();
});

$('.interactDisplayCard').on('click', function () {
  $('#div_tree').jstree('deselect_all');
  $('#div_tree').jstree('select_node', 'interact' + $(this).attr('data-interact_id'));
});

$('#div_tree').on('select_node.jstree', function (node, selected) {
  if (selected.node.a_attr.class == 'li_interact') {
    $.hideAlert();
    $(".li_interact").removeClass('active');
    $(this).addClass('active');
    $('#interactThumbnailDisplay').hide();
    displayInteract(selected.node.a_attr['data-interact_id']);
  }
});

$("#div_tree").jstree({
  "plugins": ["search"]
});
$('#in_treeSearch').keyup(function () {
  $('#div_tree').jstree(true).search($('#in_treeSearcxh').val());
});

$('.interactDisplayCard').on('click',function(){
  displayInteract($(this).attr('data-interact_id'));
});

$('.accordion-toggle').off('click').on('click', function () {
  setTimeout(function(){
    $('.interactListContainer').packery();
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
         loadPage('index.php?v=d&p=interact&id=' + data.id + '&saveSuccessFull=1');
       }
     });
    }
  });
});

if (is_numeric(getUrlVars('id'))) {
  if ($('.li_interact[data-interact_id=' + getUrlVars('id') + ']').length != 0) {
    $('.li_interact[data-interact_id=' + getUrlVars('id') + ']').click();
  }
}

if (getUrlVars('saveSuccessFull') == 1) {
  $('#div_alert').showAlert({message: '{{Sauvegarde effectuée avec succès}}', level: 'success'});
}

if (getUrlVars('removeSuccessFull') == 1) {
  $('#div_alert').showAlert({message: '{{Suppression effectuée avec succès}}', level: 'success'});
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

jwerty.key('ctrl+s', function (e) {
  e.preventDefault();
  $("#bt_saveInteract").click();
});

$("#bt_saveInteract").on('click', function () {
  var interact = $('.interact').getValues('.interactAttr')[0];
  interact.actions = {};
  interact.actions.cmd = $('#div_action .action').getValues('.expressionAttr');
  jeedom.interact.save({
    interact: interact,
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function (data) {
     $('.li_interact[data-interact_id=' + data.id + ']').click();
     $('#div_alert').showAlert({message: '{{Sauvegarde réussie avec succès}}', level: 'success'});
   }
 });
});


$("#bt_regenerateInteract,#bt_regenerateInteract2").on('click', function () {
  bootbox.confirm('{{Etes-vous sûr de vouloir regénérer toutes les interations (cela peut être très long) ?}}', function (result) {
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
         loadPage('index.php?v=d&p=interact&id=' + data.id + '&saveSuccessFull=1');
       }
     });
    }
  });
});

$("#bt_removeInteract").on('click', function () {
  if ($('.li_interact.active').attr('data-interact_id') != undefined) {
    $.hideAlert();
    bootbox.confirm('{{Etes-vous sûr de vouloir supprimer l\'interaction}} <span style="font-weight: bold ;">' + $('.li_interact.active a').text() + '</span> ?', function (result) {
      if (result) {
        jeedom.interact.remove({
          id: $('.li_interact.active').attr('data-interact_id'),
          error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
          },
          success: function () {
           loadPage('index.php?v=d&p=interact&removeSuccessFull=1');
         }
       });
      }
    });
  } else {
    $('#div_alert').showAlert({message: '{{Veuillez d\'abord sélectionner un objet}}', level: 'danger'});
  }
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
  jeedom.cmd.getSelectModal({}, function (result) {
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

$("body").undelegate('.bt_removeAction', 'click').delegate('.bt_removeAction', 'click', function () {
  var type = $(this).attr('data-type');
  $(this).closest('.' + type).remove();
});

function displayInteract(_id){
  $('#div_conf').show();
  $('#interactThumbnailDisplay').hide();
  $('.li_interact').removeClass('active');
  $('.li_interact[data-interact_id='+_id+']').addClass('active');
  jeedom.interact.get({
    id: _id,
    success: function (data) {
     actionOptions = []
     $('#div_action').empty();
     $('.interactAttr').value('');
     $('.interact').setValues(data, '.interactAttr');
     $('.interactAttr[data-l1key=filtres][data-l2key=type]').value(1);
     $('.interactAttr[data-l1key=filtres][data-l2key=subtype]').value(1);
     $('.interactAttr[data-l1key=filtres][data-l2key=unite]').value(1);
     $('.interactAttr[data-l1key=filtres][data-l2key=object]').value(1);
     $('.interactAttr[data-l1key=filtres][data-l2key=plugin]').value(1);
     $('.interactAttr[data-l1key=filtres][data-l2key=category]').value(1);
     if(isset(data.filtres) && isset(data.filtres.type) && $.isPlainObject(data.filtres.type)){
      for(var i in data.filtres.type){
       $('.interactAttr[data-l1key=filtres][data-l2key=type][data-l3key='+i+']').value(data.filtres.type[i]);
     }
   }
   if(isset(data.filtres) && isset(data.filtres.subtype) && $.isPlainObject(data.filtres.subtype)){
    for(var i in data.filtres.subtype){
     $('.interactAttr[data-l1key=filtres][data-l2key=subtype][data-l3key='+i+']').value(data.filtres.subtype[i]);
   }
 }
 if(isset(data.filtres) && isset(data.filtres.unite) && $.isPlainObject(data.filtres.unite)){
  for(var i in data.filtres.unite){
   $('.interactAttr[data-l1key=filtres][data-l2key=unite][data-l3key="'+i+'"]').value(data.filtres.unite[i]);
 }
}
if(isset(data.filtres) && isset(data.filtres.object) && $.isPlainObject(data.filtres.object)){
  for(var i in data.filtres.object){
   $('.interactAttr[data-l1key=filtres][data-l2key=object][data-l3key='+i+']').value(data.filtres.object[i]);
 }
}
if(isset(data.filtres) && isset(data.filtres.plugin) && $.isPlainObject(data.filtres.plugin)){
  for(var i in data.filtres.plugin){
   $('.interactAttr[data-l1key=filtres][data-l2key=plugin][data-l3key='+i+']').value(data.filtres.plugin[i]);
 }
}
if(isset(data.filtres) && isset(data.filtres.category) && $.isPlainObject(data.filtres.category)){
  for(var i in data.filtres.category){
   $('.interactAttr[data-l1key=filtres][data-l2key=category][data-l3key='+i+']').value(data.filtres.category[i]);
 }
}
if(isset(data.actions.cmd) && $.isArray(data.actions.cmd) && data.actions.cmd.length != null){
  for(var i in data.actions.cmd){
    addAction(data.actions.cmd[i], 'action','{{Action}}');
  }
}
taAutosize();
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
  div += '<a class="btn btn-default btn-sm bt_removeAction" data-type="' + _type + '"><i class="fa fa-minus-circle"></i></a>';
  div += '</span>';
  div += '<input class="expressionAttr form-control cmdAction" data-l1key="cmd" data-type="' + _type + '" />';
  div += '<span class="input-group-btn">';
  div += '<a class="btn btn-default btn-sm listAction"" data-type="' + _type + '" title="{{Sélectionner un mot-clé}}"><i class="fa fa-tasks"></i></a>';
  div += '<a class="btn btn-default btn-sm listCmd" data-type="' + _type + '"><i class="fa fa-list-alt"></i></a>';
  div += '</span>';
  div += '</div>';
  div += '</div>';
  var actionOption_id = uniqId();
  div += '<div class="col-sm-7 actionOptions" id="'+actionOption_id+'"></div>';
  $('#div_' + _type).append(div);
  $('#div_' + _type + ' .' + _type + ':last').setValues(_action, '.expressionAttr');
  actionOptions.push({
    expression : init(_action.cmd, ''),
    options : _action.options,
    id : actionOption_id
  });
}

