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

var deviceInfo = getDeviceType();
var editOption = {state : false, snap : false,grid : false,gridSize:false,highlight:true};
var clickedOpen = false;
var $pageContainer = $('#div_pageContainer')
var style_css = '';

function unload_page(){
  if(getUrlVars('p') != 'plan'){
    return;
  }
  $.contextMenu('destroy', '#div_pageContainer' );
}

$('main').css('padding-right','0px').css('padding-left','0px').css('margin-right','0px').css('margin-left','0px');

planHeaderContextMenu = {};
for(var i in planHeader){
  planHeaderContextMenu[planHeader[i].id] = {
    name:planHeader[i].name,
    callback: function(key, opt){
      planHeader_id = key;
      editOption = {state : false, snap : false,grid : false,gridSize:false,highlight:true};
      displayPlan();
    }
  }
}
if(deviceInfo.type == 'desktop' && user_isAdmin == 1){
   jwerty.key('ctrl+shift+e/⌘+shift+e', function (e) {
    e.preventDefault()
    editOption.state = !editOption.state;
    $('#div_pageContainer').data('editOption.state', editOption.state);
    initEditOption(editOption.state);
  })
  
  $.contextMenu({
    selector: '#div_pageContainer',
    zIndex: 9999,
    events: {
      show: function(opt) {
        $.contextMenu.setInputValues(opt, this.data());
      },
      hide: function(opt) {
        $.contextMenu.getInputValues(opt, this.data());
      }
    },
    items: {
      fold1: {
        name: "{{Designs}}",
        icon : 'far fa-image',
        items: planHeaderContextMenu
      },
      edit: {
        name: "{{Edition}}",
        icon : 'fas fa-pencil-alt',
        callback: function(key, opt){
          editOption.state = !editOption.state;
          this.data('editOption.state', editOption.state);
          initEditOption(editOption.state);
        }
      },
      fullscreen: {
        name: "{{Plein écran}}",
        icon : 'fas fa-desktop',
        callback: function(key, opt){
          if(this.data('fullscreen') == undefined){
            this.data('fullscreen',1)
          }
          fullScreen(this.data('fullscreen'));
          this.data('fullscreen',!this.data('fullscreen'));
        }
      },
      sep1 : "---------",
      addGraph: {
        name: "{{Ajouter Graphique}}",
        icon : 'fas fa-chart-line',
        disabled:function(key, opt) {
          return !this.data('editOption.state');
        },
        callback: function(key, opt){
          addObject({link_type : 'graph',link_id:Math.round(Math.random() * 99999999) + 9999});
        }
      },
      addText: {
        name: "{{Ajouter texte/html}}",
        icon : 'fas fa-align-center',
        disabled:function(key, opt) {
          return !this.data('editOption.state');
        },
        callback: function(key, opt){
          addObject({link_type : 'text',link_id:Math.round(Math.random() * 99999999) + 9999,display: {text: 'Texte à insérer ici'}});
        }
      },
      addScenario: {
        name: "{{Ajouter scénario}}",
        icon : 'fas fa-plus-circle',
        disabled:function(key, opt) {
          return !this.data('editOption.state');
        },
        callback: function(key, opt){
          jeedom.scenario.getSelectModal({}, function (data) {
            addObject({link_type : 'scenario',link_id : data.id});
          });
        }
      },
      fold4: {
        name: "{{Ajouter un lien}}",
        icon : 'fas fa-link',
        disabled:function(key, opt) {
          return !this.data('editOption.state');
        },
        items: {
          addViewLink: {
            name: "{{Vers une vue}}",
            icon : 'fas fa-link',
            disabled:function(key, opt) {
              return !this.data('editOption.state');
            },
            callback: function(key, opt){
              addObject({link_type :'view',link_id : -(Math.round(Math.random() * 99999999) + 9999),display : {name : 'A configurer'}});
            }
          },
          addPlanLink: {
            name: "{{Vers un design}}",
            icon : 'fas fa-link',
            disabled:function(key, opt) {
              return !this.data('editOption.state');
            },
            callback: function(key, opt){
              addObject({link_type :'plan',link_id : -(Math.round(Math.random() * 99999999) + 9999),display : {name : 'A configurer'}});
            }
          },
        }
      },
      addEqLogic: {
        name: "{{Ajouter équipement}}",
        icon : 'fas fa-plus-circle',
        disabled:function(key, opt) {
          return !this.data('editOption.state');
        },
        callback: function(key, opt){
          jeedom.eqLogic.getSelectModal({}, function (data) {
            addObject({link_type : 'eqLogic',link_id : data.id});
          });
        }
      },
      addCommand: {
        name: "{{Ajouter commande}}",
        icon : 'fas fa-plus-circle',
        disabled:function(key, opt) {
          return !this.data('editOption.state');
        },
        callback: function(key, opt){
          jeedom.cmd.getSelectModal({}, function (data) {
            addObject({link_type : 'cmd',link_id : data.cmd.id});
          });
        }
      },
      addImage: {
        name: "{{Ajouter une image/caméra}}",
        icon : 'fas fa-plus-circle',
        disabled:function(key, opt) {
          return !this.data('editOption.state');
        },
        callback: function(key, opt){
          addObject({link_type : 'image',link_id : Math.round(Math.random() * 99999999) + 9999});
        }
      },
      addZone: {
        name: "{{Ajouter une zone}}",
        icon : 'fas fa-plus-circle',
        disabled:function(key, opt) {
          return !this.data('editOption.state');
        },
        callback: function(key, opt){
          addObject({link_type : 'zone',link_id : Math.round(Math.random() * 99999999) + 9999});
        }
      },
      addSummary: {
        name: "{{Ajouter un résumé}}",
        icon : 'fas fa-plus-circle',
        disabled:function(key, opt) {
          return !this.data('editOption.state');
        },
        callback: function(key, opt){
          addObject({link_type : 'summary',link_id : -1});
        }
      },
      sep2 : "---------",
      fold2: {
        name: "{{Affichage}}",
        icon : 'fas fa-th',
        disabled:function(key, opt) {
          return !this.data('editOption.state');
        },
        items: {
          grid_none: {
            name: "Aucune",
            type: 'radio',
            radio: 'radio',
            value: '0',
            selected: true,
            events: {
              click : function(e) {
                editOption.gridSize = false;
                initEditOption(1);
              }
            }
          },
          grid_10x10: {
            name: "10x10",
            type: 'radio',
            radio: 'radio',
            value: '10',
            events: {
              click : function(e) {
                editOption.gridSize = [10,10];
                initEditOption(1);
              }
            }
          },
          grid_15x15: {
            name: "15x15",
            type: 'radio',
            radio: 'radio',
            value: '15',
            events: {
              click : function(e) {
                editOption.gridSize = [15,15];
                initEditOption(1);
              }
            }
          },
          grid_20x20: {
            name: "20x20",
            type: 'radio',
            radio: 'radio',
            value: '20',
            events: {
              click : function(e) {
                editOption.gridSize = [20,20];
                initEditOption(1);
              }
            }
          },
          sep4 : "---------",
          snapGrid: {
            name: "{{Aimanter à la grille}}",
            type: 'checkbox',
            radio: 'radio',
            selected:  editOption.grid,
            events: {
              click : function(e) {
                editOption.grid = $(this).value();
                initEditOption(1);
              }
            }
          },
          highlightWidget: {
            name: "{{Masquer surbrillance des éléments}}",
            type: 'checkbox',
            radio: 'radio',
            selected:  editOption.highlight,
            events: {
              click : function(e) {
                editOption.highlight = ($(this).value() == 1) ? false : true;
                initEditOption(1);
              }
            }
          },
        }
      },
      removePlan: {
        name: "{{Supprimer le design}}",
        icon : 'fas fa-trash',
        disabled:function(key, opt) {
          return !this.data('editOption.state');
        },
        callback: function(key, opt){
          bootbox.confirm('{{Êtes-vous sûr de vouloir supprimer ce design ?}}', function (result) {
            if (result) {
              jeedom.plan.removeHeader({
                id:planHeader_id,
                error: function (error) {
                  $('#div_alert').showAlert({message: error.message, level: 'danger'});
                },
                success: function () {
                  $('#div_alert').showAlert({message: 'Design supprimé', level: 'success'});
                  window.location.reload();
                },
              });
            }
          });
        }
      },
      addPlan: {
        name: "{{Creer un design}}",
        icon : 'fas fa-plus-circle',
        disabled:function(key, opt) {
          return !this.data('editOption.state');
        },
        callback: function(key, opt){
          createNewDesign();
        }
      },
      duplicatePlan: {
        name: "{{Dupliquer le design}}",
        icon : 'far fa-copy',
        disabled:function(key, opt) {
          return !this.data('editOption.state');
        },
        callback: function(key, opt){
          bootbox.prompt("{{Nom la copie du design ?}}", function (result) {
            if (result !== null) {
              savePlan(false,false);
              jeedom.plan.copyHeader({
                name: result,
                id: planHeader_id,
                error: function (error) {
                  $('#div_alert').showAlert({message: error.message, level: 'danger'});
                },
                success: function (data) {
                  loadPage('index.php?v=d&p=plan&plan_id=' + data.id);
                },
              });
            }
          });
        }
      },
      configurePlan: {
        name: "{{Configurer le design}}",
        icon : 'fas fa-cogs',
        disabled:function(key, opt) {
          return !this.data('editOption.state');
        },
        callback: function(key, opt){
          savePlan(false,false);
          $('#md_modal').dialog({title: "{{Configuration du design}}"});
          $('#md_modal').load('index.php?v=d&modal=planHeader.configure&planHeader_id=' + planHeader_id).dialog('open');
        }
      },
      sep3 : "---------",
      save: {
        name: "{{Sauvegarder}}",
        icon : 'fas fa-save',
        callback: function(key, opt){
          savePlan();
        }
      },
    }
  });
  
  $.contextMenu({
    selector: '.div_displayObject > .eqLogic-widget,.div_displayObject > .cmd-widget,.scenario-widget,.plan-link-widget,.text-widget,.view-link-widget,.graph-widget,.image-widget,.zone-widget,.summary-widget',
    zIndex: 9999,
    events: {
      show : function(opt){
        $.contextMenu.setInputValues(opt, this.data());
        if(editOption.highlight){
          $(this).removeClass('editingMode').addClass('contextMenu_select');
        }
      },
      hide : function(opt){
        $.contextMenu.getInputValues(opt, this.data());
        if(editOption.highlight){
          $(this).removeClass('contextMenu_select').addClass('editingMode');
        }
      }
    },
    items: {
      parameter: {
        name: '{{Paramètres d\'affichage}}',
        icon:'fas fa-cogs',
        callback: function(key, opt){
          savePlan(false,false);
          $('#md_modal').dialog({title: "{{Configuration du composant}}"});
          $('#md_modal').load('index.php?v=d&modal=plan.configure&id='+$(this).attr('data-plan_id')).dialog('open');
        }
      },
      configuration: {
        name: '{{Configuration avancée}}',
        icon:'fas fa-cog',
        disabled: function(key, opt){
          var info = getObjectInfo($(this));
          return !(info.type == 'eqLogic' || info.type == 'cmd' || info.type == 'graph');
        },
        callback: function(key, opt){
          $('#md_modal').dialog({title: "{{Configuration avancée}}"});
          var info = getObjectInfo($(this));
          if(info.type == 'graph'){
            var el = $(this);
            $("#md_modal").load('index.php?v=d&modal=cmd.graph.select', function () {
              $('#table_addViewData tbody tr .enable').prop('checked', false);
              var options = json_decode(el.find('.graphOptions').value());
              for (var i in options) {
                var tr = $('#table_addViewData tbody tr[data-link_id=' + options[i].link_id + ']');
                tr.find('.enable').value(1);
                tr.setValues(options[i], '.graphDataOption');
                setColorSelect(tr.find('.graphDataOption[data-l1key=configuration][data-l2key=graphColor]'));
              }
              $("#md_modal").dialog('option', 'buttons', {
                "Annuler": function () {
                  $(this).dialog("close");
                },
                "Valider": function () {
                  var tr = $('#table_addViewData tbody tr').first();
                  var options = [];
                  while (tr.attr('data-link_id') != undefined) {
                    if (tr.find('.enable').is(':checked')) {
                      var graphData = tr.getValues('.graphDataOption')[0];
                      graphData.link_id = tr.attr('data-link_id');
                      options.push(graphData);
                    }
                    tr = tr.next();
                  }
                  el.find('.graphOptions').empty().append(json_encode(options));
                  savePlan(true);
                  $(this).dialog('close');
                }
              });
              $('#md_modal').dialog('open');
            });
          }else{
            $('#md_modal').load('index.php?v=d&modal='+info.type+'.configure&'+info.type+'_id=' + info.id).dialog('open');
          }
        }
      },
      remove: {
        name: '{{Supprimer}}',
        icon:'fas fa-trash',
        callback: function(key, opt){
          savePlan(false,false);
          jeedom.plan.remove({
            id:  $(this).attr('data-plan_id'),
            error: function (error) {
              $('#div_alert').showAlert({message: error.message, level: 'danger'});
            },
            success: function () {
              displayPlan();
            },
          });
        }
      },
      duplicate: {
        name: '{{Dupliquer}}',
        icon:'far fa-copy',
        disabled: function(key, opt){
          var info = getObjectInfo($(this));
          return !(info.type == 'text' || info.type == 'graph' || info.type == 'zone');
        },
        callback: function(key, opt){
          var info = getObjectInfo($(this));
          jeedom.plan.copy({
            id: $(this).attr('data-plan_id'),
            version: 'dashboard',
            error: function (error) {
              $('#div_alert').showAlert({message: error.message, level: 'danger'});
            },
            success: function (data) {
              displayObject(data.plan,data.html);
            }
          });
          
        }
      },
      lock: {
        name: "{{Verrouiller}}",
        type: 'checkbox',
        events: {
          click : function(opt) {
            if($(this).value() == 1){
              opt.handleObj.data.$trigger.addClass('locked');
            }else{
              opt.handleObj.data.$trigger.removeClass('locked');
            }
            $('.context-menu-root').hide()
          }
        }
      },
    }
  });
  
}
/**************************************init*********************************************/
displayPlan();

$('#bt_createNewDesign').on('click',function(){
  createNewDesign();
});

$pageContainer.delegate('.plan-link-widget', 'click', function () {
  if (!editOption.state) {
    planHeader_id = $(this).attr('data-link_id');
    editOption = {state : false, snap : false,grid : false,gridSize:false,highlight:true};
    displayPlan();
  }
});

$pageContainer.on( 'click','.zone-widget:not(.zoneEqLogic)', function () {
  var el = $(this);
  if (!editOption.state) {
    el.append('<center class="loading"><i class="fas fa-spinner fa-spin fa-4x"></i></center>');
    jeedom.plan.execute({
      id: el.attr('data-plan_id'),
      error: function (error) {
        $('#div_alert').showAlert({message: error.message, level: 'danger'});
        el.empty().append('<center class="loading"><i class="fas fa-times fa-4x"></i></center>');
        setTimeout(function() {
          el.empty();
          clickedOpen = false;
        }, 3000);
      },
      success: function () {
        el.empty();
        clickedOpen = false;
      },
    });
  }
});

$pageContainer.on('mouseenter','.zone-widget.zoneEqLogic.zoneEqLogicOnFly',  function () {
  if (!editOption.state) {
    clickedOpen = true;
    var el = $(this);
    jeedom.eqLogic.toHtml({
      id : el.attr('data-eqLogic_id'),
      version : 'dashboard',
      global:false,
      success:function(data){
        var html = $(data.html).css('position','absolute');
        html.attr("style", html.attr("style") + "; " + el.attr('data-position'));
        el.empty().append(html);
        positionEqLogic(el.attr('data-eqLogic_id'),false);
        if(deviceInfo.type == 'desktop'){
          el.off('mouseleave').on('mouseleave',function(){
            el.empty()
            clickedOpen = false;
          });
        }
      }
    });
  }
});

$pageContainer.on('click','.zone-widget.zoneEqLogic.zoneEqLogicOnClic', function () {
  if (!editOption.state && !clickedOpen) {
    clickedOpen = true;
    var el = $(this);
    jeedom.eqLogic.toHtml({
      id : el.attr('data-eqLogic_id'),
      version : 'dashboard',
      global:false,
      success:function(data){
        el.empty().append($(data.html).css('position','absolute'));
        positionEqLogic(el.attr('data-eqLogic_id'));
        if(deviceInfo.type == 'desktop' && el.hasClass('zoneEqLogicOnFly')){
          el.off('mouseleave').on('mouseleave',function(){
            el.empty();
            clickedOpen = false;
          });
        }
      }
    });
  }
});

$(document).click(function(event) {
  if (!editOption.state) {
    if ( (!$(event.target).hasClass('.zone-widget.zoneEqLogic') && $(event.target).closest('.zone-widget.zoneEqLogic').html() == undefined) && (!$(event.target).hasClass('.zone-widget.zoneEqLogicOnFly') && $(event.target).closest('.zone-widget.zoneEqLogicOnFly').html() == undefined)) {
      $('.zone-widget.zoneEqLogic').each(function(){
        if($(this).hasClass('zoneEqLogicOnClic') || $(this).hasClass('zoneEqLogicOnFly')){
          $(this).empty();
          clickedOpen = false;
        }
      });
    }
  }
});

jwerty.key('ctrl+s/⌘+s', function (e) {
  e.preventDefault();
  savePlan();
});

$('.view-link-widget').off('click').on('click', function () {
  if (!editOption.state) {
    $(this).find('a').click();
  }
});

$('.div_displayObject').delegate('.graph-widget', 'resize', function () {
  if (isset(jeedom.history.chart['graph' + $(this).attr('data-graph_id')])) {
    jeedom.history.chart['graph' + $(this).attr('data-graph_id')].chart.reflow();
  }
});

$('#div_pageContainer').delegate('.div_displayObject > .eqLogic-widget .history', 'click', function () {
    if (!editOption.state) {
        $('#md_modal').dialog({title: "Historique"}).load('index.php?v=d&modal=cmd.history&id=' + $(this).data('cmd_id')).dialog('open');
    }
});

$('#div_pageContainer').delegate('.div_displayObject > .cmd-widget.history', 'click', function () {
    if (!editOption.state) {
        $('#md_modal').dialog({title: "Historique"}).load('index.php?v=d&modal=cmd.history&id=' + $(this).data('cmd_id')).dialog('open');
    }
});
/***********************************************************************************/

function createNewDesign(){
  bootbox.prompt("{{Nom du design ?}}", function (result) {
    if (result !== null) {
      jeedom.plan.saveHeader({
        planHeader: {name: result},
        error: function (error) {
          $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
          window.location = 'index.php?v=d&p=plan&plan_id=' + data.id;
        }
      });
    }
  });
}

function setColorSelect(_select) {
  _select.css('background-color', _select.find('option:selected').val());
}

$('.graphDataOption[data-l1key=configuration][data-l2key=graphColor]').off('change').on('change', function () {
  setColorSelect($(this).closest('select'));
});

function fullScreen(_mode) {
  if(_mode){
    $('header').hide();
    $('footer').hide();
    $('#div_mainContainer').css('margin-top', '-50px');
    $('#wrap').css('margin-bottom', '0px');
    $('.div_backgroundPlan').height($('html').height());
  }else{
    $('header').show();
    $('footer').show();
    $('#div_mainContainer').css('margin-top', '0px');
    $('#wrap').css('margin-bottom', '15px');
    $('.div_backgroundPlan').height($('body').height());
  }
}


var dragClick = {x: 0, y: 0}
var dragStartPos = {top: 0, left: 0}
var dragStep = false
function draggableStartFix(event, ui) {
  isDragLocked = false
  if ($(event.target).hasClass('locked')) {
    isDragLocked = true
    document.body.style.cursor = "default"
    return false
  }
  zoomScale = parseFloat($(ui.helper).attr('data-zoom'))
  if (editOption.grid == 1) {
    dragStep = editOption.gridSize[0]
  } else {
    dragStep = false
  }
  
  dragClick.x = event.clientX
  dragClick.y = event.clientY
  dragStartPos = ui.originalPosition
  
  $container = $('.div_displayObject')
  containerWidth = $container.width()
  containerHeight = $container.height()
  
  clientWidth = $(ui.helper[0]).width()
  clientHeight = $(ui.helper[0]).height()
  
  marginLeft = $(ui.helper[0]).css('margin-left')
  marginLeft = parseFloat(marginLeft.replace('px', ''))
  
  minLeft = 0 - marginLeft
  minTop = 0
  
  maxLeft = containerWidth + minLeft - (clientWidth * zoomScale)
  maxTop = containerHeight + minTop - (clientHeight * zoomScale)
}
function draggableDragFix(event, ui) {
  if (isDragLocked == true) return false
  newLeft = event.clientX - dragClick.x + dragStartPos.left
  newTop = event.clientY - dragClick.y + dragStartPos.top
  
  if (newLeft < minLeft) newLeft = minLeft
  if (newLeft > maxLeft) newLeft = maxLeft
  
  if (newTop < minTop) newTop = minTop
  if (newTop > maxTop) newTop = maxTop
  
  if (dragStep) {
    newLeft = (Math.round(newLeft / dragStep) * dragStep)
    newTop = (Math.round(newTop / dragStep) * dragStep)
  }
  
  ui.position = {left: newLeft, top: newTop}
}

function initEditOption(_state) {
  var $container = $('.container-fluid.div_displayObject'), _zoom, containmentW, containmentH, objW, objH;
  if (_state) {
    if(!$('#div_pageContainer').data('editOption.state')){
      $('#div_pageContainer').data('editOption.state',true)
    }
    editOption.state = true;
    $('.tooltipstered').tooltipster('disable')
    $('.div_displayObject').addClass('editingMode')
    jeedom.cmd.disableExecute = true;
    $('.plan-link-widget,.view-link-widget,.graph-widget,.div_displayObject >.eqLogic-widget,.div_displayObject > .cmd-widget,.scenario-widget,.text-widget,.image-widget,.zone-widget,.summary-widget').draggable({
      cancel: '.locked',
      containment: 'parent',
      cursor: 'move',
      start: draggableStartFix,
      drag: draggableDragFix,
      stop: function( event, ui ) {
        savePlan(false,false);
      }
    });
    if(editOption.highlight){
      $('.plan-link-widget,.view-link-widget,.graph-widget,.div_displayObject > .eqLogic-widget,.div_displayObject > .cmd-widget,.scenario-widget,.text-widget,.image-widget,.zone-widget,.summary-widget').addClass('editingMode');
    }else{
      $('.plan-link-widget,.view-link-widget,.graph-widget,.div_displayObject >.eqLogic-widget,.div_displayObject > .cmd-widget,.scenario-widget,.text-widget,.image-widget,.zone-widget,.summary-widget').removeClass('editingMode').removeClass('contextMenu_select');
    }
    if(editOption.gridSize){
      $('.div_grid').show().css('background-size',editOption.gridSize[0]+'px '+editOption.gridSize[1]+'px');
    }else{
      $('.div_grid').hide();
    }
    $('.plan-link-widget,.view-link-widget,.graph-widget,.div_displayObject >.eqLogic-widget,.scenario-widget,.text-widget,.image-widget,.zone-widget,.summary-widget').resizable({
      cancel: '.locked',
      handles: 'n,e,s,w,se,sw,nw,ne',
      start: function( event, ui ) {
        zoomScale = parseFloat($(ui.helper).attr('data-zoom'))
        if (editOption.grid == 1) {
          dragStep = editOption.gridSize[0]
          dragStep = dragStep / zoomScale
        } else {
          dragStep = false
        }
      },
      resize: function( event, ui ) {
        if (dragStep) {
          newWidth = (Math.round(ui.size.width / dragStep) * dragStep)
          newHeight = (Math.round(ui.size.height / dragStep) * dragStep)
          ui.element.width(newWidth)
          ui.element.height(newHeight)
        }
        ui.element.find('.camera').trigger('resize');
      },
      stop: function( event, ui ) {
        savePlan(false,false);
      }
    });
    $('.div_displayObject a').each(function () {
      if ($(this).attr('href') != '#') {
        $(this).attr('data-href', $(this).attr('href')).removeAttr('href');
      }
    });
    try{
      $('.plan-link-widget,.view-link-widget,.graph-widget,.div_displayObject >.eqLogic-widget,.div_displayObject > .cmd-widget,.scenario-widget,.text-widget,.image-widget,.zone-widget,.summary-widget').contextMenu(true);
    }catch (e) {
      
    }
  }else{
    if($('#div_pageContainer').data('editOption.state')){
      $('#div_pageContainer').data('editOption.state',false)
    }
    editOption.state = false;
    jeedom.cmd.disableExecute = false;
    $('.div_displayObject').removeClass('editingMode')
    try{
      $('.tooltipstered').tooltipster('enable')
      $('.plan-link-widget,.view-link-widget,.graph-widget,.div_displayObject >.eqLogic-widget,.div_displayObject > .cmd-widget,.scenario-widget,.text-widget,.image-widget,.zone-widget,.summary-widget').draggable("destroy");
      $('.plan-link-widget,.view-link-widget,.graph-widget,.div_displayObject >.eqLogic-widget,.div_displayObject > .cmd-widget,.scenario-widget,.text-widget,.image-widget,.zone-widget,.summary-widget').removeClass('editingMode');
      $('.plan-link-widget,.view-link-widget,.graph-widget,.div_displayObject >.eqLogic-widget,.scenario-widget,.text-widget,.image-widget,.zone-widget,.summary-widget').resizable("destroy");
      $('.div_displayObject a').each(function () {
        $(this).attr('href', $(this).attr('data-href'));
      });
    }catch (e) {
      
    }
    $('.div_grid').hide();
    try{
      $('.plan-link-widget,.view-link-widget,.graph-widget,.div_displayObject >.eqLogic-widget,.div_displayObject > .cmd-widget,.scenario-widget,.text-widget,.image-widget,.zone-widget,.summary-widget').contextMenu(false);
    }catch (e) {
      
    }
  }
}

function addObject(_plan){
  _plan.planHeader_id = planHeader_id;
  jeedom.plan.create({
    plan: _plan,
    version: 'dashboard',
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function (data) {
      displayObject(data.plan,data.html);
    }
  });
}

function displayPlan(_code) {
  if(planHeader_id == -1){
    return;
  }
  if(typeof _code == "undefined"){
    _code = null;
  }
  if (getUrlVars('fullscreen') == 1) {
    fullScreen(true);
  }
  jeedom.plan.getHeader({
    id: planHeader_id,
    code : _code,
    error: function (error) {
      if(error.code == -32005){
        var result = prompt("{{Veuillez indiquer le code ?}}", "")
        if(result == null){
          $('#div_alert').showAlert({message: error.message, level: 'danger'});
          return;
        }
        displayPlan(result);
      }else{
        planHeader_id = -1
        $('#div_alert').showAlert({message: error.message, level: 'danger'});
      }
    },
    success: function (data) {
      $('.div_displayObject').empty();
      $('.div_displayObject').append('<div class="container-fluid div_grid" style="display:none;position: absolute;padding:0;width:100%;height:100%;user-select: none;-khtml-user-select: none;-o-user-select: none;-moz-user-select: -moz-none;-webkit-user-select: none;"></div>');
      $('.div_displayObject').height('auto').width('auto');
      if (isset(data.image)) {
        $('.div_displayObject').append(data.image);
      }
      if (isset(data.configuration.backgroundTransparent) && data.configuration.backgroundTransparent == 1) {
        $('.div_displayObject').css('background-color','transparent');
      }else if (isset(data.configuration.backgroundColor)) {
        $('.div_displayObject').css('background-color',data.configuration.backgroundColor);
      }else{
        $('.div_displayObject').css('background-color','#ffffff');
      }
      if (data.configuration != null && init(data.configuration.desktopSizeX) != '' && init(data.configuration.desktopSizeY) != '') {
        $('.div_displayObject').height(data.configuration.desktopSizeY).width(data.configuration.desktopSizeX);
        $('.div_displayObject img').height(data.configuration.desktopSizeY).width(data.configuration.desktopSizeX);
      } else {
        $('.div_displayObject').width($('.div_displayObject img').attr('data-sixe_x')).height($('.div_displayObject img').attr('data-sixe_y'));
        $('.div_displayObject img').css('height', ($('.div_displayObject img').attr('data-sixe_y')) + 'px').css('width', ($('.div_displayObject img').attr('data-sixe_x')) + 'px');
      }
      if($('body').height() > $('.div_displayObject').height()){
        $('.div_backgroundPlan').height($('body').height());
      }else{
        $('.div_backgroundPlan').height($('.div_displayObject').height());
      }
      $('.div_grid').width($('.div_displayObject').width()).height($('.div_displayObject').height());
      if(deviceInfo.type != 'desktop'){
        $('meta[name="viewport"]').prop('content', 'width=' + $('.div_displayObject').width() + ',height=' + $('.div_displayObject').height());
        fullScreen(true);
        $(window).on("navigate", function (event, data) {
          var direction = data.state.direction;
          if (direction == 'back') {
            window.location.href = 'index.php?v=m';
          }
        });
      }
      $('.div_displayObject').find('.eqLogic-widget,.div_displayObject > .cmd-widget,.scenario-widget,.plan-link-widget,.view-link-widget,.graph-widget,.text-widget,.image-widget,.zone-widget,.summary-widget').remove();
      
      
      jeedom.plan.byPlanHeader({
        id: planHeader_id,
        error: function (error) {
          $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (plans) {
          var objects = [];
          for (var i in plans) {
            objects.push(displayObject(plans[i].plan,plans[i].html, true));
          }
          try {
            $('.div_displayObject').append(objects);
          }catch(e) {
            
          }
          try {
            $('#div_pageContainer').append(style_css);
            style_css  ='';
          }catch(e) {
            
          }
          addOrUpdateUrl('plan_id',planHeader_id,data.name+' - Jeedom');
          initEditOption(editOption.state);
          initReportMode();
        }
      });
    },
  });
}

function getObjectInfo(_object){
  if(_object.hasClass('eqLogic-widget')){
    return {type : 'eqLogic',id : _object.attr('data-eqLogic_id')};
  }
  if(_object.hasClass('cmd-widget')){
    return {type :  'cmd',id : _object.attr('data-cmd_id')};
  }
  if(_object.hasClass('scenario-widget')){
    return {type :  'scenario',id : _object.attr('data-scenario_id')};
  }
  if(_object.hasClass('plan-link-widget')){
    return {type :  'plan',id : _object.attr('data-link_id')};
  }
  if(_object.hasClass('view-link-widget')){
    return {type :  'view',id : _object.attr('data-link_id')};
  }
  if(_object.hasClass('graph-widget')){
    return {type :  'graph',id : _object.attr('data-graph_id')};
  }
  if(_object.hasClass('text-widget')){
    return {type : 'text',id : _object.attr('data-text_id')};
  }
  if(_object.hasClass('image-widget')){
    return {type : 'image',id : _object.attr('data-image_id')};
  }
  if(_object.hasClass('zone-widget')){
    return {type : 'zone',id : _object.attr('data-zone_id')};
  }
  if(_object.hasClass('summary-widget')){
    return {type : 'summary',id : _object.attr('data-summary_id')};
  }
}

function savePlan(_refreshDisplay,_async) {
  if(planHeader_id == -1){
    return;
  }
  var plans = [];
  $('.div_displayObject >.eqLogic-widget,.div_displayObject > .cmd-widget,.scenario-widget,.plan-link-widget,.view-link-widget,.graph-widget,.text-widget,.image-widget,.zone-widget,.summary-widget').each(function () {
    var info = getObjectInfo($(this));
    var plan = {};
    plan.position = {};
    plan.display = {};
    plan.id = $(this).attr('data-plan_id');
    plan.link_type = info.type;
    plan.link_id = info.id;
    plan.planHeader_id = planHeader_id;
    plan.display.height = $(this).outerHeight();
    plan.display.width = $(this).outerWidth();
    if(info.type == 'graph'){
      plan.display.graph = json_decode($(this).find('.graphOptions').value());
    }
    if(!$(this).is(':visible')){
      var position = $(this).show().position();
      $(this).hide();
    }else{
      var position = $(this).position();
    }
    plan.position.top = (((position.top)) / $('.div_displayObject').height()) * 100;
    plan.position.left = (((position.left)) / $('.div_displayObject').width()) * 100;
    plans.push(plan);
  });
  jeedom.plan.save({
    plans: plans,
    async : _async || true,
    global : false,
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function () {
      if (init(_refreshDisplay, false)) {
        displayPlan();
      }
    },
  });
}

function displayObject(_plan,_html, _noRender) {
  _plan = init(_plan, {});
  _plan.position = init(_plan.position, {});
  _plan.css = init(_plan.css, {});
  var css_selector = '';
  var another_css = '';
  var style = {}
  if (_plan.link_type == 'eqLogic' || _plan.link_type == 'scenario' || _plan.link_type == 'text' || _plan.link_type == 'image' || _plan.link_type == 'zone' || _plan.link_type == 'summary') {
    css_selector = '.div_displayObject .'+_plan.link_type+'-widget[data-'+_plan.link_type+'_id="' + _plan.link_id + '"]';
    $(css_selector).remove();
  }else if (_plan.link_type == 'view' || _plan.link_type == 'plan') {
    css_selector = '.div_displayObject .'+_plan.link_type+'-link-widget[data-id="' + _plan.id + '"]';
    $(css_selector).remove();
  }else if (_plan.link_type == 'cmd') {
    css_selector = '.div_displayObject > .cmd-widget[data-cmd_id="' + _plan.link_id + '"]';
    $(css_selector).remove();
  }else if (_plan.link_type == 'graph') {
    if(jeedom.history.chart['graph'+_plan.link_id]){
      delete jeedom.history.chart['graph'+_plan.link_id];
    }
    css_selector = '.div_displayObject .graph-widget[data-graph_id="' + _plan.link_id + '"]';
    $(css_selector).remove();
  }
  var html = $(_html);
  
  html.attr('data-plan_id',_plan.id)
  .addClass('jeedomAlreadyPosition')
  .attr('data-zoom', init(_plan.css.zoom, 1))
  .addClass('noResize');
  style['z-index'] = '1000';
  style['position'] = 'absolute';
  style['top'] = (init(_plan.position.top, '10') * $('.div_displayObject').height() / 100)+'px';
  style['left'] = (init(_plan.position.left, '10') * $('.div_displayObject').width() / 100)+'px';
  if (init(_plan.css.zoom, 1) != 1) {
    style['transform'] = 'scale(' + init(_plan.css.zoom, 1) + ')';
  }
  style['transform-origin'] = '0 0';
  
  if(_plan.link_type != 'cmd'){
    if (isset(_plan.display) && isset(_plan.display.width)) {
      style['width'] = init(_plan.display.width, 50)+'px';
      html.width(init(_plan.display.width, 50));
    }
    if (isset(_plan.display) && isset(_plan.display.height)) {
      style['height'] = init(_plan.display.height, 50)+'px';
      html.height(init(_plan.display.height, 50));
    }
  }
  
  for (var key in _plan.css) {
    if (_plan.css[key] === '' || key == 'zoom' || key == 'rotate'){
      continue;
    }
    if(key == 'z-index' && _plan.css[key] < 999){
      continue;
    }
    if (key == 'background-color') {
      if(isset(_plan.display) && (!isset(_plan.display['background-defaut']) || _plan.display['background-defaut'] != 1)){
        if (isset(_plan.display['background-transparent']) && _plan.display['background-transparent'] == 1) {
          style['background-color'] = 'transparent';
          style['border-radius'] = '0px';
          style['box-shadow'] = 'none';
          if(_plan.link_type == 'eqLogic'){
            another_css += css_selector+' .widget-name{background-color : transparent !important;\n}'
            if(_plan.display['color-defaut'] == 0 && isset(_plan.css.color)){
              another_css += css_selector+' .widget-name a{color : '+_plan.css.color+' !important;\n}'
              another_css += css_selector+' .state{color : '+_plan.css.color+' !important;\n}'
            }
          }else if(_plan.link_type == 'cmd'){
            if(_plan.display['color-defaut'] == 0 && isset(_plan.css.color)){
              another_css += css_selector+' .widget-name a{color : '+_plan.css.color+' !important;\n}'
              another_css += css_selector+' .state{color : '+_plan.css.color+' !important;\n}'
            }
          }
        }else{
          style[key] = _plan.css[key];
        }
      }
      continue;
    }else if (key == 'color') {
      if(!isset(_plan.display) || !isset(_plan.display['color-defaut']) || _plan.display['color-defaut'] != 1){
        style[key] = _plan.css[key];
        if(_plan.link_type == 'eqLogic' || _plan.link_type == 'cmd' || _plan.link_type == 'summary'){
          another_css += css_selector+' * {\n'+key+' : '+_plan.css[key]+' !important;\n}';
          another_css += css_selector+' .state {\n'+key+' : '+_plan.css[key]+' !important;}';
        }
      }
      continue;
    }
    if (key == 'opacity'){
      continue;
    }
    if (key == 'font-size' && _plan.link_type == 'summary'){
      another_css += css_selector+' .objectSummaryParent{\n'+key+' : '+_plan.css[key]+' !important;\n}';
      continue;
    }
    style[key] = _plan.css[key];
  }
  if (_plan.css['opacity'] && _plan.css['opacity'] !== '' && style['background-color'] && style['background-color'] != 'transparent'){
    if(style['background-color'].indexOf('#') != -1){
      var rgb = hexToRgb(style['background-color']);
      style['background-color'] = 'rgba('+rgb.r+','+rgb.g+','+rgb.b+','+_plan.css['opacity']+')';
    }else{
      style['background-color'] = style['background-color'].replace(')', ','+_plan.css['opacity']+')').replace('rgb', 'rgba');
    }
  }
  
  if(_plan.link_type == 'eqLogic'){
    if(isset(_plan.display.hideName) && _plan.display.hideName == 1){
      html.addClass('hideEqLogicName')
      another_css += css_selector+' .verticalAlign{top: 50% !important;\n}'
    }
    if(isset(_plan.display.showObjectName) && _plan.display.showObjectName == 1){
      html.addClass('displayObjectName')
    }
    if(isset(_plan.display.cmdHide)){
      for(var i in _plan.display.cmdHide){
        if(_plan.display.cmdHide[i] == 0){
          continue;
        }
        another_css += css_selector+' .cmd[data-cmd_id="'+i+'"]{display : none !important;}';
      }
    }
    if(isset(_plan.display.cmdHideName)){
      for(var i in _plan.display.cmdHideName){
        if(_plan.display.cmdHideName[i] == 0){
          continue;
        }
        another_css += css_selector+' .cmd[data-cmd_id="'+i+'"] .cmdName{display : none !important;}';
        another_css += css_selector+' .cmd[data-cmd_id="'+i+'"] .title{display : none !important;}';
      }
    }
    if(isset(_plan.display.cmdTransparentBackground)){
      for(var i in _plan.display.cmdTransparentBackground){
        if(_plan.display.cmdTransparentBackground[i] == 0){
          continue;
        }
        another_css += css_selector+' .cmd[data-cmd_id="'+i+'"]{';
        another_css += 'background-color: transparent !important';
        another_css += 'border-radius: 0px !important';
        another_css += 'box-shadow: none !important';
        another_css += '\n}'
      }
    }
    style['min-width'] = '0px'
    style['min-height'] = '0px'
    another_css += css_selector+' *:not([class^="content"]:not(.cmd-widget) {';
    another_css += 'min-width:0px !important;'
    another_css += 'min-height:0px !important;'
    another_css += '\n}'
  }
  if(_plan.link_type == 'cmd'){
    var insideHtml = html.html();
    html = html.empty().append('<center>'+insideHtml+'</center>');
    delete style['height']
    style['min-height'] = '0px';
    delete style['width']
    style['min-width'] = '0px';
    html.css({
      'width':'',
      'height':'',
    });
    if(isset(_plan.display.hideName) && _plan.display.hideName == 1){
      another_css += css_selector+' .cmdName{\ndisplay : none !important;\n}';
      another_css += css_selector+' .title{\ndisplay : none !important;\n}';
    }
  }
  if(_plan.link_type == 'image'){
    if(isset(_plan.display.allowZoom) && _plan.display.allowZoom == 1){
      html.find('.directDisplay').addClass('zoom cursor');
    }
  }
  
  $('#style_'+_plan.link_type+'_'+_plan.link_id).remove();
  var style_el = '<style id="style_'+_plan.link_type+'_'+_plan.link_id+'">';
  if(_plan.display.css && _plan.display.css != ''){
    if(_plan.display.cssApplyOn && _plan.display.cssApplyOn != ''){
      var cssApplyOn = _plan.display.cssApplyOn.split(',');
      for(var i in cssApplyOn){
        style_el += css_selector+' '+cssApplyOn[i]+'{'+_plan.display.css+'}';
      }
    } else {
      style_el += css_selector+' '+'{'+_plan.display.css+'}';
    }
  }
  style_el += css_selector+'{'
  for(var i in style){
    if(['left','top','bottom','right','height','width','box-shadow'].indexOf(i) !== -1){
      style_el += i+':'+style[i]+';';
    }else{
      style_el += i+':'+style[i]+' !important;';
    }
  }
  style_el += '}\n';
  style_el += another_css;
  style_el +='</style>';
  
  if(_plan.link_type == 'graph'){
    $('#div_pageContainer').append(style_el);
    $('.div_displayObject').append(html);
    if(isset(_plan.display) && isset(_plan.display.graph)){
      for (var i in _plan.display.graph) {
        if (init(_plan.display.graph[i].link_id) != '') {
          jeedom.history.drawChart({
            cmd_id: _plan.display.graph[i].link_id,
            el: 'graph' + _plan.link_id,
            showLegend: init(_plan.display.showLegend, true),
            showTimeSelector: init(_plan.display.showTimeSelector, false),
            showScrollbar: init(_plan.display.showScrollbar, true),
            dateRange: init(_plan.display.dateRange, '7 days'),
            option: init(_plan.display.graph[i].configuration, {}),
            transparentBackground : init(_plan.display.transparentBackground, false),
            showNavigator : init(_plan.display.showNavigator, true),
            enableExport : false,
            global: false,
            success : function(){
              if(init(_plan.display.transparentBackground, false)){
                $('#graph' + _plan.link_id).find('.highcharts-background').style('fill-opacity', '0', 'important')
              }
            }
          });
        }
      }
    }
    initEditOption(editOption.state);
    return;
  }
  
  if (init(_noRender, false)) {
    style_css += style_el;
    return html;
  }
  $('#div_pageContainer').append(style_el);
  $('.div_displayObject').append(html);
  initEditOption(editOption.state);
}
