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

$('body').attr('data-type', 'plugin')
$('.nav-tabs a:not(.eqLogicAction)').first().click()

document.onkeydown = function(event) {
  if (jeedomUtils.getOpenedModal()) return

  if ((event.ctrlKey || event.metaKey) && event.which == 83) { //s
    event.preventDefault()
    if ($('.eqLogicAction[data-action=save]').is(':visible')) {
      $(".eqLogicAction[data-action=save]").click()
      return
    }
  }
}

//contextMenu
$(function() {
  try {
    if ('undefined' !== typeof Core_noEqContextMenu) return false
    if ($('.nav.nav-tabs').length == 0) return false
    $.contextMenu('destroy', $('.nav.nav-tabs'))
    pluginId = $('body').attr('data-page')
    jeedom.eqLogic.byType({
      type: pluginId,
      error: function(error) {
        $.fn.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function(_eqs) {
        if (_eqs.length == 0) {
          return;
        }
        var eqsGroups = []
        for (var i = 0; i < _eqs.length; i++) {
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
        for (var i = 0; i < eqsGroups.length; i++) {
          group = eqsGroups[i]
          eqsList[group] = []
          for (j = 0; j < _eqs.length; j++) {
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
            items[uniqId] = {
              'name': eqName,
              'id': eqId
            }
            uniqId++
          }
          contextmenuitems[group] = {
            'name': group,
            'items': items
          }
        }
        if (Object.entries(contextmenuitems).length > 0 && contextmenuitems.constructor === Object) {
          $('.nav.nav-tabs').contextMenu({
            selector: 'li',
            autoHide: true,
            zIndex: 9999,
            className: 'eq-context-menu',
            callback: function(key, options, event) {
              if (!jeedomUtils.checkPageModified()) {
                tab = null
                tabObj = null
                if (document.location.toString().match('#')) {
                  tab = '#' + document.location.toString().split('#')[1]
                  if (tab != '#') {
                    tabObj = $('a[href="' + tab + '"]')
                  }
                }
                $.hideAlert()
                if (event.ctrlKey || event.originalEvent.which == 2) {
                  var type = $('body').attr('data-page')
                  var url = 'index.php?v=d&m=' + type + '&p=' + type + '&id=' + options.commands[key].id
                  if (tabObj) url += tab
                  window.open(url).focus()
                } else {
                  $('.eqLogicDisplayCard[data-eqLogic_id="' + options.commands[key].id + '"]').click()
                  if (tabObj) tabObj.click()
                }
              }
            },
            items: contextmenuitems
          })
        }
      }
    })
  } catch (err) {
    console.log(err)
  }
})

//displayAsTable if plugin support it:
if ($('#bt_pluginDisplayAsTable').length) {
  $('#bt_pluginDisplayAsTable').removeClass('hidden') //Not shown on previous core versions
  if (getCookie('jeedom_displayAsTable') == 'true' || jeedom.theme.theme_displayAsTable == 1) {
    $('#bt_pluginDisplayAsTable').data('state', '1').addClass('active')
    if ($('#bt_pluginDisplayAsTable[data-coreSupport="1"]').length) {
      $('.eqLogicDisplayCard').addClass('displayAsTable')
      $('.eqLogicDisplayCard .hiddenAsCard').removeClass('hidden')
      $('.eqLogicThumbnailContainer').first().addClass('containerAsTable')
    }
  }
  //core event:
  $('#bt_pluginDisplayAsTable[data-coreSupport="1"]').off('click').on('click', function() {
    if ($(this).data('state') == "0") {
      $(this).data('state', '1').addClass('active')
      setCookie('jeedom_displayAsTable', 'true', 2)
      $('.eqLogicDisplayCard').addClass('displayAsTable')
      $('.eqLogicDisplayCard .hiddenAsCard').removeClass('hidden')
      $('.eqLogicThumbnailContainer').first().addClass('containerAsTable')
    } else {
      $(this).data('state', '0').removeClass('active')
      setCookie('jeedom_displayAsTable', 'false', 2)
      $('.eqLogicDisplayCard').removeClass('displayAsTable')
      $('.eqLogicDisplayCard .hiddenAsCard').addClass('hidden')
      $('.eqLogicThumbnailContainer').first().removeClass('containerAsTable')
    }
  })
}

$(function() {
  if ($("#table_cmd").sortable("instance")) {
    $("#table_cmd").sortable({
      delay: 500,
      distance: 30,
      stop: function(event, ui) {
        modifyWithoutSave = true
      }
    })
  }
})


$('#div_pageContainer').on({
  'change': function(event) {
    modifyWithoutSave = true
  }
}, '.cmd .cmdAttr:visible, .eqLogic .eqLogicAttr:visible')

$('.eqLogicAction[data-action=gotoPluginConf]').on('click', function() {
  $('#md_modal').dialog({
    title: "{{Configuration du plugin}}"
  }).load('index.php?v=d&p=plugin&ajax=1&id=' + eqType).dialog('open')
})

$('.eqLogicAction[data-action=returnToThumbnailDisplay]').removeAttr('href').off('click').on('click', function(event) {
  setTimeout(function() {
    $('.nav li.active').removeClass('active')
    $('a[href="#' + $('.tab-pane.active').attr('id') + '"]').closest('li').addClass('active')
  }, 500)
  if (jeedomUtils.checkPageModified()) return
  $.hideAlert()
  $('.eqLogic').hide()
  $('.eqLogicThumbnailDisplay').show()
  $(this).closest('ul').find('li').removeClass('active')
  jeedomUtils.addOrUpdateUrl('id', null, )
})

$(".eqLogicDisplayCard").on('click', function(event) {
  $.hideAlert()
  if (event.ctrlKey) {
    var type = $('body').attr('data-page')
    var url = 'index.php?v=d&m=' + type + '&p=' + type + '&id=' + $(this).attr('data-eqlogic_id')
    window.open(url).focus()
  } else {
    jeedom.eqLogic.cache.getCmd = Array()
    if ($('.eqLogicThumbnailDisplay').html() != undefined) {
      $('.eqLogicThumbnailDisplay').hide()
    }
    $('.eqLogic').hide()
    if ('function' == typeof(prePrintEqLogic)) {
      prePrintEqLogic($(this).attr('data-eqLogic_id'))
    }
    if (isset($(this).attr('data-eqLogic_type')) && isset($('.' + $(this).attr('data-eqLogic_type')))) {
      $('.' + $(this).attr('data-eqLogic_type')).show()
    } else {
      $('.eqLogic').show()
    }
    $('.eqLogicDisplayCard.active').removeClass('active')
    $(this).addClass('active')
    $('.nav-tabs a:not(.eqLogicAction)').first().click()
    $.showLoading()
    jeedom.eqLogic.print({
      type: isset($(this).attr('data-eqLogic_type')) ? $(this).attr('data-eqLogic_type') : eqType,
      id: $(this).attr('data-eqLogic_id'),
      status: 1,
      getCmdState : 1,
      error: function(error) {
        $.hideLoading()
        $.fn.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function(data) {
        $('body .eqLogicAttr').value('')
        if (isset(data) && isset(data.timeout) && data.timeout == 0) {
          data.timeout = ''
        }
        $('body').setValues(data, '.eqLogicAttr')
        if (!isset(data.category.opening)) $('input[data-l2key="opening"]').prop('checked', false)

        if ('function' == typeof(printEqLogic)) {
          printEqLogic(data)
        }
        $('.cmd').remove()
        for (var i in data.cmd) {
          if(data.cmd[i].type == 'info'){
            data.cmd[i].state = String(data.cmd[i].state).replace(/<[^>]*>?/gm, '');
            data.cmd[i]['htmlstate'] =  '<span class="cmdTableState"';
            data.cmd[i]['htmlstate'] += 'data-cmd_id="' + data.cmd[i].id+ '"';
            data.cmd[i]['htmlstate'] += 'title="{{Date de valeur}} : ' + data.cmd[i].valueDate + '<br/>{{Date de collecte}} : ' + data.cmd[i].collectDate;
            if(data.cmd[i].state.length > 50){
              data.cmd[i]['htmlstate'] += '<br/>'+data.cmd[i].state.replaceAll('"','&quot;');
            }
            data.cmd[i]['htmlstate'] += '" >';
            data.cmd[i]['htmlstate'] += data.cmd[i].state.substring(0, 50) +  ' ' + data.cmd[i].unite;
            data.cmd[i]['htmlstate'] += '<span>';
          }else{
            data.cmd[i]['htmlstate'] = '';
          }
          if(typeof addCmdToTable == 'function'){
            addCmdToTable(data.cmd[i])
          }else{
            addCmdToTableDefault(data.cmd[i]);
          }
        }
        $('.cmdTableState').each(function() {
          jeedom.cmd.addUpdateFunction($(this).attr('data-cmd_id'), function(_options) {
            _options.display_value = String(_options.display_value).replace(/<[^>]*>?/gm, '');
            let cmd = $('.cmdTableState[data-cmd_id=' + _options.cmd_id + ']')
            let title = '{{Date de collecte}} : ' + _options.collectDate+' - {{Date de valeur}} ' + _options.valueDate;
            if(_options.display_value.length > 50){
              title += ' - '+_options.display_value;
            }
            cmd.attr('title', title)
              cmd.empty().append(_options.display_value.substring(0, 50) + ' ' + _options.unit);
            cmd.css('color','var(--logo-primary-color)');
            setTimeout(function(){
              cmd.css('color','');
            }, 1000);
          });
        })
        $('#div_pageContainer').on({
          'change': function(event) {
            jeedom.cmd.changeType($(this).closest('.cmd'))
          }
        }, '.cmd .cmdAttr[data-l1key=type]')

        $('#div_pageContainer').on({
          'change': function(event) {
            jeedom.cmd.changeSubType($(this).closest('.cmd'))
          }
        }, '.cmd .cmdAttr[data-l1key=subType]')

        jeedomUtils.addOrUpdateUrl('id', data.id)
        $.hideLoading()
        modifyWithoutSave = false
        setTimeout(function() {
          modifyWithoutSave = false
        }, 1000)
      }
    })
  }
  return false
})

$('.eqLogicDisplayCard').off('mouseup').on('mouseup', function(event) {
  if (event.which == 2) {
    event.preventDefault()
    var id = $(this).attr('data-eqlogic_id')
    $('.eqLogicDisplayCard[data-eqlogic_id="' + id + '"]').trigger(jQuery.Event('click', {
      ctrlKey: true
    }))
  }
})


/**************************EqLogic*********************************************/
$('.eqLogicAction[data-action=copy]').off('click').on('click', function() {
  if ($('.eqLogicAttr[data-l1key=id]').value() != undefined && $('.eqLogicAttr[data-l1key=id]').value() != '') {
    bootbox.prompt({
      size: 'small',
      value: $('.eqLogicAttr[data-l1key=name]').value(),
      title: '{{Nom de la copie de l\'équipement ?}}',
      callback: function(result) {
        if (result !== null) {
          jeedom.eqLogic.copy({
            id: $('.eqLogicAttr[data-l1key=id]').value(),
            name: result,
            error: function(error) {
              $.fn.showAlert({
                message: error.message,
                level: 'danger'
              });
            },
            success: function(data) {
              modifyWithoutSave = false
              var vars = getUrlVars()
              var url = 'index.php?'
              for (var i in vars) {
                if (i != 'id' && i != 'saveSuccessFull' && i != 'removeSuccessFull') {
                  url += i + '=' + vars[i].replace('#', '') + '&'
                }
              }
              url += 'id=' + data.id + '&saveSuccessFull=1'
              jeedomUtils.loadPage(url)
              bootbox.hideAll()
            }
          })
          return false
        }
      }
    })
  }
})

$('.eqLogicAction[data-action=export]').off('click').on('click', function() {
  window.open('core/php/export.php?type=eqLogic&id=' + $('.eqLogicAttr[data-l1key=id]').value(), "_blank", null)
})

$('.eqLogicAction[data-action=save]').off('click').on('click', function() {
  var eqLogics = []
  $('.eqLogic').each(function() {
    if ($(this).is(':visible')) {
      var eqLogic = $(this).getValues('.eqLogicAttr')
      eqLogic = eqLogic[0]
      eqLogic.cmd = $(this).find('.cmd').getValues('.cmdAttr')
      if ('function' == typeof(saveEqLogic)) {
        eqLogic = saveEqLogic(eqLogic)
      }
      eqLogics.push(eqLogic)
    }
  })
  jeedom.eqLogic.save({
    type: isset($(this).attr('data-eqLogic_type')) ? $(this).attr('data-eqLogic_type') : eqType,
    id: $(this).attr('data-eqLogic_id'),
    eqLogics: eqLogics,
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(data) {
      modifyWithoutSave = false
      var vars = getUrlVars()
      var url = 'index.php?'
      for (var i in vars) {
        if (i != 'id' && i != 'saveSuccessFull' && i != 'removeSuccessFull') {
          url += i + '=' + vars[i].replace('#', '') + '&'
        }
      }

      var id
      if (Array.isArray(data)) {
        id = data[0].id
      } else {
        id = data.id
      }
      url += 'id=' + id + '&saveSuccessFull=1'

      if (document.location.toString().match('#')) {
        url += '#' + document.location.toString().split('#')[1]
      }
      jeedomUtils.loadPage(url)
      modifyWithoutSave = false
    }
  })
  return false
})

$('.eqLogicAction[data-action=remove]').off('click').on('click', function() {
  if ($('.eqLogicAttr[data-l1key=id]').value() != undefined) {
    jeedom.eqLogic.getUseBeforeRemove({
      id: $('.eqLogicAttr[data-l1key=id]').value(),
      error: function(error) {
        $.fn.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function(data) {
        var text = '{{Êtes-vous sûr de vouloir supprimer l\'équipement}} ' + eqType + ' <b>' + $('.eqLogicAttr[data-l1key=name]').value() + '</b> ?'
        if (Object.keys(data).length > 0) {
          text += ' </br> {{Il est utilisé par ou utilise :}}</br>'
          var complement = null
          for (var i in data) {
            complement = ''
            if ('sourceName' in data[i]) {
              complement = ' (' + data[i].sourceName + ')'
            }
            text += '- ' + '<a href="' + data[i].url + '" target="_blank">' + data[i].type + '</a> : <b>' + data[i].name + '</b>' + complement + ' <sup><a href="' + data[i].url + '" target="_blank"><i class="fas fa-external-link-alt"></i></a></sup></br>'
          }
        }
        text = text.substring(0, text.length - 2)
        bootbox.confirm(text, function(result) {
          if (result) {
            jeedom.eqLogic.remove({
              type: isset($(this).attr('data-eqLogic_type')) ? $(this).attr('data-eqLogic_type') : eqType,
              id: $('.eqLogicAttr[data-l1key=id]').value(),
              error: function(error) {
                $.fn.showAlert({
                  message: error.message,
                  level: 'danger'
                })
              },
              success: function() {
                var vars = getUrlVars()
                var url = 'index.php?'
                for (var i in vars) {
                  if (i != 'id' && i != 'removeSuccessFull' && i != 'saveSuccessFull') {
                    url += i + '=' + vars[i].replace('#', '') + '&'
                  }
                }
                modifyWithoutSave = false
                url += 'removeSuccessFull=1'
                jeedomUtils.loadPage(url)
              }
            })
          }
        })
      }
    })
  } else {
    $.fn.showAlert({
      message: '{{Veuillez d\'abord sélectionner un}} ' + eqType,
      level: 'danger'
    })
  }
})

$('.eqLogicAction[data-action=add]').off('click').on('click', function() {
  bootbox.prompt("{{Nom de l'équipement ?}}", function(result) {
    if (result !== null) {
      jeedom.eqLogic.save({
        type: eqType,
        eqLogics: [{
          name: result
        }],
        error: function(error) {
          $.fn.showAlert({
            message: error.message,
            level: 'danger'
          });
        },
        success: function(_data) {
          var vars = getUrlVars()
          var url = 'index.php?'
          for (var i in vars) {
            if (i != 'id' && i != 'saveSuccessFull' && i != 'removeSuccessFull') {
              url += i + '=' + vars[i].replace('#', '') + '&'
            }
          }
          modifyWithoutSave = false
          url += 'id=' + _data.id + '&saveSuccessFull=1'
          jeedomUtils.loadPage(url)
        }
      })
    }
  })
})

$('.eqLogic .eqLogicAction[data-action=configure]').off('click').on('click', function() {
  var eqName = $('input.eqLogicAttr[data-l1key="name"]')
  eqName = (eqName.length ? ' : ' + eqName.val() : '')
  $('#md_modal').dialog().load('index.php?v=d&modal=eqLogic.configure&eqLogic_id=' + $('.eqLogicAttr[data-l1key=id]').value()).dialog('open')
});

$('#in_searchEqlogic').off('keyup').keyup(function() {
  var search = $(this).value()
  if (search == '') {
    $('.eqLogicDisplayCard').show()
    return
  }
  $('.eqLogicDisplayCard').hide()
  search = jeedomUtils.normTextLower(search)
  var text
  $('.eqLogicDisplayCard .name').each(function() {
    text = jeedomUtils.normTextLower($(this).text())
    if (text.indexOf(search) >= 0) {
      $(this).closest('.eqLogicDisplayCard').show()
    }
  })
})

/*
 * Remise à zéro du champ de Recherche
 */
$('#bt_resetSearch').on('click', function() {
  $('#in_searchEqlogic').val('').keyup()
})

/**************************CMD*********************************************/
$('.cmdAction[data-action=add]').on('click', function() {
  if(typeof addCmdToTable == 'function'){
    addCmdToTable()
  }else{
    addCmdToTableDefault();
  }
  $('.cmd:last .cmdAttr[data-l1key=type]').trigger('change')
  modifyWithoutSave = true
})

$('#div_pageContainer').on('click', '.cmd .cmdAction[data-l1key=chooseIcon]', function() {
  var cmd = $(this).closest('.cmd')
  jeedomUtils.chooseIcon(function(_icon) {
    cmd.find('.cmdAttr[data-l1key=display][data-l2key=icon]').empty().append(_icon)
    modifyWithoutSave = true
  })
})

$('#div_pageContainer').on('click', '.cmd .cmdAttr[data-l1key=display][data-l2key=icon]', function() {
  modifyWithoutSave = true
  $(this).empty()
})

$('#div_pageContainer').on('click', '.cmd .cmdAction[data-action=remove]', function() {
  modifyWithoutSave = true
  $(this).closest('tr').remove()
})

$('#div_pageContainer').on('click', '.cmd .cmdAction[data-action=copy]', function() {
  modifyWithoutSave = true
  var cmd = $(this).closest('.cmd').getValues('.cmdAttr')[0]
  cmd.id = ''
  if(typeof addCmdToTable == 'function'){
    addCmdToTable(cmd)
  }else{
    addCmdToTableDefault(cmd);
  }
})

$('#div_pageContainer').on('click', '.cmd .cmdAction[data-action=test]', function(event) {
  $.hideAlert()
  if ($('.eqLogicAttr[data-l1key=isEnable]').is(':checked')) {
    var id = $(this).closest('.cmd').attr('data-cmd_id')
    jeedom.cmd.test({
      id: id
    })
  } else {
    $.fn.showAlert({
      message: '{{Veuillez activer l\'équipement avant de tester une de ses commandes}}',
      level: 'warning'
    })
  }
})

$('#div_pageContainer').on('dblclick', '.cmd input,textarea,select,span,a', function(event) {
  event.stopPropagation()
})

$('#div_pageContainer').on('dblclick', '.cmd', function() {
  $('#md_modal').dialog().load('index.php?v=d&modal=cmd.configure&cmd_id=' + $(this).closest('.cmd').attr('data-cmd_id')).dialog('open')
})

$('#div_pageContainer').on('click', '.cmd .cmdAction[data-action=configure]', function() {
  $('#md_modal').dialog().load('index.php?v=d&modal=cmd.configure&cmd_id=' + $(this).closest('.cmd').attr('data-cmd_id')).dialog('open')
})

if (is_numeric(getUrlVars('id'))) {
  if ($('#ul_eqLogic .li_eqLogic[data-eqLogic_id=' + getUrlVars('id') + ']').length != 0) {
    $('#ul_eqLogic .li_eqLogic[data-eqLogic_id=' + getUrlVars('id') + ']').click()
  } else if ($('.eqLogicThumbnailContainer .eqLogicDisplayCard[data-eqLogic_id=' + getUrlVars('id') + ']').length != 0) {
    $('.eqLogicThumbnailContainer .eqLogicDisplayCard[data-eqLogic_id=' + getUrlVars('id') + ']').click();
  } else if ($('.eqLogicThumbnailDisplay').html() == undefined) {
    $('#ul_eqLogic .li_eqLogic').first().click()
  }
} else {
  if ($('.eqLogicThumbnailDisplay').html() == undefined) {
    $('#ul_eqLogic .li_eqLogic').first().click()
  }
}

$("img.lazy").lazyload({
  event: "sporty"
})

$("img.lazy").each(function() {
  var el = $(this)
  if (el.attr('data-original2') != undefined) {
    $("<img>", {
      src: el.attr('data-original'),
      error: function() {
        $("<img>", {
          src: el.attr('data-original2'),
          error: function() {
            if (el.attr('data-original3') != undefined) {
              $("<img>", {
                src: el.attr('data-original3'),
                error: function() {
                  el.lazyload({
                    event: "sporty"
                  });
                  el.trigger("sporty")
                },
                load: function() {
                  el.attr("data-original", el.attr('data-original3'))
                  el.lazyload({
                    event: "sporty"
                  });
                  el.trigger("sporty")
                }
              });
            } else {
              el.lazyload({
                event: "sporty"
              });
              el.trigger("sporty")
            }
          },
          load: function() {
            el.attr("data-original", el.attr('data-original2'))
            el.lazyload({
              event: "sporty"
            });
            el.trigger("sporty")
          }
        });
      },
      load: function() {
        el.lazyload({
          event: "sporty"
        });
        el.trigger("sporty")
      }
    });
  } else {
    el.lazyload({
      event: "sporty"
    });
    el.trigger("sporty")
  }
})


function addCmdToTableDefault(_cmd) {
  if($('#table_cmd thead').text() == ''){
    table = '<thead>';
		table += '<tr>';
		table += '<th>{{Id}}</th>';
		table += '<th>{{Nom}}</th>';
		table += '<th>{{Type}}</th>';
		table += '<th>{{Logical ID}}</th>';
		table += '<th>{{Options}}</th>';
		table += '<th>{{Paramètres}}</th>';
		table += '<th>{{Etat}}</th>';
		table += '<th>{{Action}}</th>';
		table += '</tr>';
		table += '</thead>';
		table += '<tbody>';
		table += '</tbody>';
    $('#table_cmd').append(table);
  }
  if (!isset(_cmd)) {
    var _cmd = {configuration: {}};
  }
  if (!isset(_cmd.configuration)) {
    _cmd.configuration = {};
  }
  var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
  tr += '<td style="min-width:50px;width:70px;">';
  tr += '</td>';
  tr += '<td>';
  tr += '<div class="row">';
  tr += '<div class="col-sm-6">';
  tr += '<a class="cmdAction btn btn-default btn-sm" data-l1key="chooseIcon"><i class="fa fa-flag"></i> Icône</a>';
  tr += '<span class="cmdAttr" data-l1key="display" data-l2key="icon" style="margin-left : 10px;"></span>';
  tr += '</div>';
  tr += '<div class="col-sm-6">';
  tr += '<input class="cmdAttr form-control input-sm" data-l1key="name">';
  tr += '</div>';
  tr += '</div>';
  tr += '<select class="cmdAttr form-control input-sm" data-l1key="value" style="display : none;margin-top : 5px;" title="{{La valeur de la commande vaut par défaut la commande}}">';
  tr += '<option value="">Aucune</option>';
  tr += '</select>';
  tr += '</td>';
  tr += '<td>';
  tr += '<input class="cmdAttr form-control input-sm" data-l1key="id" style="display : none;">';
  tr += '<span class="type" type="' + init(_cmd.type) + '">' + jeedom.cmd.availableType() + '</span>';
  tr += '<span class="subType" subType="' + init(_cmd.subType) + '"></span>';
  tr += '</td>';
  tr += '<td><input class="cmdAttr form-control input-sm" data-l1key="logicalId" value="0" style="width : 70%; display : inline-block;" placeholder="{{Commande}}"><br/>';
  tr += '</td>';
  tr += '<td>';
  tr += '<input class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="returnStateValue" placeholder="{{Valeur retour d\'état}}" style="width:48%;display:inline-block;">';
  tr += '<input class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="returnStateTime" placeholder="{{Durée avant retour d\'état (min)}}" style="width:48%;display:inline-block;margin-left:2px;">';
  tr += '<select class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="updateCmdId" style="display : none;" title="{{Commande d\'information à mettre à jour}}">';
  tr += '<option value="">Aucune</option>';
  tr += '</select>';
  tr += '</td>';
  tr += '<td>';
  tr += '<input class="tooltips cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="minValue" placeholder="{{Min}}" title="{{Min}}" style="width:30%;display:inline-block;">';
  tr += '<input class="tooltips cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="maxValue" placeholder="{{Max}}" title="{{Max}}" style="width:30%;display:inline-block;">';
  tr += '<input class="cmdAttr form-control input-sm" data-l1key="unite" placeholder="{{Unité}}" title="{{Unité}}" style="width:30%;display:inline-block;margin-left:2px;">';
  tr += '<input class="tooltips cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="listValue" placeholder="{{Liste de valeur|texte séparé par ;}}" title="{{Liste}}">';
  tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isVisible" checked/>{{Afficher}}</label></span> ';
  tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isHistorized" checked/>{{Historiser}}</label></span> ';
  tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr" data-l1key="display" data-l2key="invertBinary"/>{{Inverser}}</label></span> ';
  tr += '</td>';
  tr += '<td>';
  tr += '<span class="cmdAttr" data-l1key="htmlstate"></span>';
  tr += '</td>';
  tr += '<td>';
  if (is_numeric(_cmd.id)) {
    tr += '<a class="btn btn-default btn-xs cmdAction" data-action="configure"><i class="fas fa-cogs"></i></a> ';
    tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fa fa-rss"></i> {{Tester}}</a>';
  }
  tr += '<i class="fas fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i>';
  tr += '</td>';
  tr += '</tr>';
  $('#table_cmd tbody').append(tr);
  var tr = $('#table_cmd tbody tr').last();
  jeedom.eqLogic.buildSelectCmd({
    id: $('.eqLogicAttr[data-l1key=id]').value(),
    filter: {type: 'info'},
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function (result) {
      tr.find('.cmdAttr[data-l1key=value]').append(result);
      tr.setValues(_cmd, '.cmdAttr');
      jeedom.cmd.changeType(tr, init(_cmd.subType));
    }
  });
}
