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

//contextMenu
$(function(){
  try{
    $.contextMenu('destroy', $('.nav.nav-tabs'));
    pluginId =  $('body').attr('data-page')
    jeedom.eqLogic.byType({
      type: pluginId,
      error: function (error) {
        $('#div_alert').showAlert({message: error.message, level: 'danger'});
      },
      success: function (_eqs) {
        if(_eqs.length == 0){
          return;
        }
        var eqsGroups = []
        for(i=0; i<_eqs.length; i++){
          eq = _eqs[i]
          humanName = eq.humanName
          humanCut = humanName.split(']')
          group = humanCut[0].substr(1)
          name = humanCut[1].substr(1)
          eqsGroups.push(group)
        }
        eqsGroups = Array.from(new Set(eqsGroups))
        eqsGroups.sort()
        var eqsList = []
        for(i=0; i<eqsGroups.length; i++){
          group = eqsGroups[i]
          eqsList[group] = []
          for(j=0; j<_eqs.length; j++){
            eq = _eqs[j]
            humanName = eq.humanName
            humanCut = humanName.split(']')
            eqGroup = humanCut[0].substr(1)
            name = humanCut[1].substr(1)
            if (eqGroup.toLowerCase() != group.toLowerCase()) continue
            eqsList[group].push([name, eq.id])
          }
        }
        //set context menu!
        var contextmenuitems = {}
        var uniqId = 0
        for (var group in eqsList) {
          groupEq = eqsList[group]
          items = {}
          for (var index in groupEq) {
            eq = groupEq[index]
            eqName = eq[0]
            eqId = eq[1]
            items[uniqId] = {'name': eqName, 'id' : eqId}
            uniqId ++
          }
          contextmenuitems[group] = {'name':group, 'items':items}
        }
        if (Object.entries(contextmenuitems).length > 0 && contextmenuitems.constructor === Object){
          $('.nav.nav-tabs').contextMenu({
            selector: 'li',
            autoHide: true,
            zIndex: 9999,
            className: 'eq-context-menu',
            callback: function(key, options) {
              tab = null
              tabObj = null
              if (document.location.toString().match('#')) {
                tab = '#' + document.location.toString().split('#')[1]
                if (tab != '#') {
                  tabObj = $('a[href="' + tab + '"]')
                }
              }
              $('.eqLogicDisplayCard[data-eqLogic_id="' + options.commands[key].id + '"]').click()
              if (tabObj) tabObj.click()
            },
            items: contextmenuitems
          })
        }
        
      }
    })
  }catch(err) {
    console.log(err)
  }
})

$('body').attr('data-type', 'plugin');

$('.nav-tabs a:not(.eqLogicAction)').first().click();

$('.eqLogicAction[data-action=gotoPluginConf]').on('click', function () {
  $('#md_modal').dialog({title: "{{Configuration du plugin}}"});
  $("#md_modal").load('index.php?v=d&p=plugin&ajax=1&id='+eqType).dialog('open');
});

$('.eqLogicAction[data-action=returnToThumbnailDisplay]').removeAttr('href').off('click').on('click', function (event) {
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
  $('.eqLogic').hide();
  $('.eqLogicThumbnailDisplay').show();
  $(this).closest('ul').find('li').removeClass('active');
  $('.eqLogicThumbnailContainer').packery();
  addOrUpdateUrl('id',null,);
});

$(".li_eqLogic,.eqLogicDisplayCard").on('click', function () {
  jeedom.eqLogic.cache.getCmd = Array();
  if ($('.eqLogicThumbnailDisplay').html() != undefined) {
    $('.eqLogicThumbnailDisplay').hide();
  }
  $('.eqLogic').hide();
  if ('function' == typeof (prePrintEqLogic)) {
    prePrintEqLogic($(this).attr('data-eqLogic_id'));
  }
  if (isset($(this).attr('data-eqLogic_type')) && isset($('.' + $(this).attr('data-eqLogic_type')))) {
    $('.' + $(this).attr('data-eqLogic_type')).show();
  } else {
    $('.eqLogic').show();
  }
  if($('.li_eqLogic').length != 0){
    $('.li_eqLogic').removeClass('active');
  }
  $(this).addClass('active');
  if($('.li_eqLogic[data-eqLogic_id='+$(this).attr('data-eqLogic_id')+']').html() != undefined){
    $('.li_eqLogic[data-eqLogic_id='+$(this).attr('data-eqLogic_id')+']').addClass('active');
  }
  $('.nav-tabs a:not(.eqLogicAction)').first().click();
  $.showLoading();
  jeedom.eqLogic.print({
    type: isset($(this).attr('data-eqLogic_type')) ? $(this).attr('data-eqLogic_type') : eqType,
    id: $(this).attr('data-eqLogic_id'),
    status : 1,
    error: function (error) {
      $.hideLoading();
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function (data) {
      $('body .eqLogicAttr').value('');
      if(isset(data) && isset(data.timeout) && data.timeout == 0){
        data.timeout = '';
      }
      $('body').setValues(data, '.eqLogicAttr');
      if ('function' == typeof (printEqLogic)) {
        printEqLogic(data);
      }
      if ('function' == typeof (addCmdToTable)) {
        $('.cmd').remove();
        for (var i in data.cmd) {
          addCmdToTable(data.cmd[i]);
        }
      }
      $('body').delegate('.cmd .cmdAttr[data-l1key=type]', 'change', function () {
        jeedom.cmd.changeType($(this).closest('.cmd'));
      });
      
      $('body').delegate('.cmd .cmdAttr[data-l1key=subType]', 'change', function () {
        jeedom.cmd.changeSubType($(this).closest('.cmd'));
      });
      addOrUpdateUrl('id',data.id);
      $.hideLoading();
      modifyWithoutSave = false;
      setTimeout(function(){
        modifyWithoutSave = false;
      },1000)
    }
  });
  return false;
});

/**************************EqLogic*********************************************/
$('.eqLogicAction[data-action=copy]').off('click').on('click', function () {
  if ($('.eqLogicAttr[data-l1key=id]').value() != undefined && $('.eqLogicAttr[data-l1key=id]').value() != '') {
    bootbox.prompt({
      size: 'small',
      value : $('.eqLogicAttr[data-l1key=name]').value(),
      title:'{{Nom de la copie de l\'équipement ?}}',
      callback : function (result) {
        if (result !== null) {
          jeedom.eqLogic.copy({
            id: $('.eqLogicAttr[data-l1key=id]').value(),
            name: result,
            error: function (error) {
              $('#div_alert').showAlert({message: error.message, level: 'danger'});
            },
            success: function (data) {
              modifyWithoutSave = false;
              var vars = getUrlVars();
              var url = 'index.php?';
              for (var i in vars) {
                if (i != 'id' && i != 'saveSuccessFull' && i != 'removeSuccessFull') {
                  url += i + '=' + vars[i].replace('#', '') + '&';
                }
              }
              url += 'id=' + data.id + '&saveSuccessFull=1';
              loadPage(url);
              bootbox.hideAll();
            }
          });
          return false;
        }
      }
    });
  }
});

$('.eqLogicAction[data-action=export]').off('click').on('click', function () {
  window.open('core/php/export.php?type=eqLogic&id=' + $('.eqLogicAttr[data-l1key=id]').value(), "_blank", null);
});

jwerty.key('ctrl+s/⌘+s', function (e) {
  e.preventDefault();
  $('.eqLogicAction[data-action=save]').click();
});

$('.eqLogicAction[data-action=save]').off('click').on('click', function () {
  var eqLogics = [];
  $('.eqLogic').each(function () {
    if ($(this).is(':visible')) {
      var eqLogic = $(this).getValues('.eqLogicAttr');
      eqLogic = eqLogic[0];
      eqLogic.cmd = $(this).find('.cmd').getValues('.cmdAttr');
      if ('function' == typeof (saveEqLogic)) {
        eqLogic = saveEqLogic(eqLogic);
      }
      eqLogics.push(eqLogic);
    }
  });
  jeedom.eqLogic.save({
    type: isset($(this).attr('data-eqLogic_type')) ? $(this).attr('data-eqLogic_type') : eqType,
    id: $(this).attr('data-eqLogic_id'),
    eqLogics: eqLogics,
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function (data) {
      modifyWithoutSave = false;
      var vars = getUrlVars();
      var url = 'index.php?';
      for (var i in vars) {
        if (i != 'id' && i != 'saveSuccessFull' && i != 'removeSuccessFull') {
          url += i + '=' + vars[i].replace('#', '') + '&';
        }
      }
      url += 'id=' + data.id + '&saveSuccessFull=1';
      if (document.location.toString().match('#')) {
        url += '#' + document.location.toString().split('#')[1];
      }
      loadPage(url);
      modifyWithoutSave = false;
    }
  });
  return false;
});

$('.eqLogicAction[data-action=remove]').off('click').on('click', function () {
  if ($('.eqLogicAttr[data-l1key=id]').value() != undefined) {
    jeedom.eqLogic.getUseBeforeRemove({
      id: $('.eqLogicAttr[data-l1key=id]').value(),
      error: function (error) {
        $('#div_alert').showAlert({message: error.message, level: 'danger'});
      },
      success: function (data) {
        var text = '{{Êtes-vous sûr de vouloir supprimer l\'équipement}} ' + eqType + ' <b>' + $('.eqLogicAttr[data-l1key=name]').value() + '</b> ?';
        if(Object.keys(data).length > 0){
          text += ' </br> Il est utilisé par ou utilise : </br>';
          for(var i in data){
            var complement = '';
            if ('sourceName' in data[i]) {
              complement = ' ('+data[i].sourceName+')';
            }
            text += '- ' + '<a href="'+data[i].url+'" target="_blank">' +data[i].type +'</a> : <b>'+ data[i].name + '</b>'+ complement+' <sup><a href="'+data[i].url+'" target="_blank"><i class="fas fa-external-link-alt"></i></a></sup></br>';
          }
        }
        text = text.substring(0, text.length - 2)
        bootbox.confirm(text, function (result) {
          if (result) {
            jeedom.eqLogic.remove({
              type: isset($(this).attr('data-eqLogic_type')) ? $(this).attr('data-eqLogic_type') : eqType,
              id: $('.eqLogicAttr[data-l1key=id]').value(),
              error: function (error) {
                $('#div_alert').showAlert({message: error.message, level: 'danger'});
              },
              success: function () {
                var vars = getUrlVars();
                var url = 'index.php?';
                for (var i in vars) {
                  if (i != 'id' && i != 'removeSuccessFull' && i != 'saveSuccessFull') {
                    url += i + '=' + vars[i].replace('#', '') + '&';
                  }
                }
                modifyWithoutSave = false;
                url += 'removeSuccessFull=1';
                loadPage(url);
              }
            });
          }
        });
      }
    });
  } else {
    $('#div_alert').showAlert({message: '{{Veuillez d\'abord sélectionner un}} ' + eqType, level: 'danger'});
  }
});

$('.eqLogicAction[data-action=add]').off('click').on('click', function () {
  bootbox.prompt("{{Nom de l'équipement ?}}", function (result) {
    if (result !== null) {
      jeedom.eqLogic.save({
        type: eqType,
        eqLogics: [{name: result}],
        error: function (error) {
          $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (_data) {
          var vars = getUrlVars();
          var url = 'index.php?';
          for (var i in vars) {
            if (i != 'id' && i != 'saveSuccessFull' && i != 'removeSuccessFull') {
              url += i + '=' + vars[i].replace('#', '') + '&';
            }
          }
          modifyWithoutSave = false;
          url += 'id=' + _data.id + '&saveSuccessFull=1';
          loadPage(url);
        }
      });
    }
  });
});

$('.eqLogic .eqLogicAction[data-action=configure]').off('click').on('click', function () {
  $('#md_modal').dialog({title: "{{Configuration de l'équipement}}"});
  $('#md_modal').load('index.php?v=d&modal=eqLogic.configure&eqLogic_id=' + $('.eqLogicAttr[data-l1key=id]').value()).dialog('open');
});

$('#in_searchEqlogic').off('keyup').keyup(function () {
  var search = $(this).value().toLowerCase();
  search = search.normalize('NFD').replace(/[\u0300-\u036f]/g, "")
  if(search == ''){
    $('.eqLogicDisplayCard').show();
    $('.eqLogicThumbnailContainer').packery();
    return;
  }
  $('.eqLogicDisplayCard').hide();
  $('.eqLogicDisplayCard .name').each(function(){
    var text = $(this).text().toLowerCase();
    text = text.normalize('NFD').replace(/[\u0300-\u036f]/g, "")
    if(text.indexOf(search) >= 0){
      $(this).closest('.eqLogicDisplayCard').show();
    }
  });
  $('.eqLogicThumbnailContainer').packery();
});

/**************************CMD*********************************************/
$('.cmdAction[data-action=add]').on('click', function () {
  addCmdToTable();
  $('.cmd:last .cmdAttr[data-l1key=type]').trigger('change');
  modifyWithoutSave = true;
});

$('#div_pageContainer').on( 'click', '.cmd .cmdAction[data-l1key=chooseIcon]',function () {
  var cmd = $(this).closest('.cmd');
  chooseIcon(function (_icon) {
    cmd.find('.cmdAttr[data-l1key=display][data-l2key=icon]').empty().append(_icon);
    modifyWithoutSave = true;
  });
});

$('#div_pageContainer').on( 'click','.cmd .cmdAttr[data-l1key=display][data-l2key=icon]', function () {
  modifyWithoutSave = true;
  $(this).empty();
});

$('#div_pageContainer').on( 'click', '.cmd .cmdAction[data-action=remove]',function () {
  modifyWithoutSave = true;
  $(this).closest('tr').remove();
});

$('#div_pageContainer').on( 'click', '.cmd .cmdAction[data-action=copy]',function () {
  modifyWithoutSave = true;
  var cmd = $(this).closest('.cmd').getValues('.cmdAttr')[0];
  cmd.id= '';
  addCmdToTable(cmd);
});

$('#div_pageContainer').on( 'click','.cmd .cmdAction[data-action=test]',function (event) {
  $.hideAlert();
  if ($('.eqLogicAttr[data-l1key=isEnable]').is(':checked')) {
    var id = $(this).closest('.cmd').attr('data-cmd_id');
    jeedom.cmd.test({id: id});
  } else {
    $('#div_alert').showAlert({message: '{{Veuillez activer l\'équipement avant de tester une de ses commandes}}', level: 'warning'});
  }
  
});

$('#div_pageContainer').on( 'dblclick','.cmd input,select,span,a', function (event) {
  event.stopPropagation();
});

$('#div_pageContainer').on( 'dblclick','.cmd', function () {
  $('#md_modal').dialog({title: "{{Configuration commande}}"});
  $('#md_modal').load('index.php?v=d&modal=cmd.configure&cmd_id=' + $(this).closest('.cmd').attr('data-cmd_id')).dialog('open');
});

$('#div_pageContainer').on( 'click', '.cmd .cmdAction[data-action=configure]',function () {
  $('#md_modal').dialog({title: "{{Configuration commande}}"});
  $('#md_modal').load('index.php?v=d&modal=cmd.configure&cmd_id=' + $(this).closest('.cmd').attr('data-cmd_id')).dialog('open');
});

$('.eqLogicThumbnailContainer').packery();

if (is_numeric(getUrlVars('id'))) {
  if ($('#ul_eqLogic .li_eqLogic[data-eqLogic_id=' + getUrlVars('id') + ']').length != 0) {
    $('#ul_eqLogic .li_eqLogic[data-eqLogic_id=' + getUrlVars('id') + ']').click();
  } else if ($('.eqLogicThumbnailContainer .eqLogicDisplayCard[data-eqLogic_id=' + getUrlVars('id') + ']').length != 0) {
    $('.eqLogicThumbnailContainer .eqLogicDisplayCard[data-eqLogic_id=' + getUrlVars('id') + ']').click();
  } else  if ($('.eqLogicThumbnailDisplay').html() == undefined) {
    $('#ul_eqLogic .li_eqLogic').first().click();
  }
} else {
  if ($('.eqLogicThumbnailDisplay').html() == undefined) {
    $('#ul_eqLogic .li_eqLogic').first().click();
  }
}

$("img.lazy").lazyload({
  event: "sporty"
});

$("img.lazy").each(function () {
  var el = $(this);
  if (el.attr('data-original2') != undefined) {
    $("<img>", {
      src: el.attr('data-original'),
      error: function () {
        $("<img>", {
          src: el.attr('data-original2'),
          error: function () {
            if (el.attr('data-original3') != undefined) {
              $("<img>", {
                src: el.attr('data-original3'),
                error: function () {
                  el.lazyload({
                    event: "sporty"
                  });
                  el.trigger("sporty");
                },
                load: function () {
                  el.attr("data-original", el.attr('data-original3'));
                  el.lazyload({
                    event: "sporty"
                  });
                  el.trigger("sporty");
                }
              });
            } else {
              el.lazyload({
                event: "sporty"
              });
              el.trigger("sporty");
            }
          },
          load: function () {
            el.attr("data-original", el.attr('data-original2'));
            el.lazyload({
              event: "sporty"
            });
            el.trigger("sporty");
          }
        });
      },
      load: function () {
        el.lazyload({
          event: "sporty"
        });
        el.trigger("sporty");
      }
    });
  } else {
    el.lazyload({
      event: "sporty"
    });
    el.trigger("sporty");
  }
});

$('#div_pageContainer').delegate('.cmd .cmdAttr:visible', 'change', function () {
  modifyWithoutSave = true;
});

$('#div_pageContainer').delegate('.eqLogic .eqLogicAttr:visible', 'change', function () {
  modifyWithoutSave = true;
});
