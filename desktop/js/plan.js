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

 function unload_page(){
   if(getUrlVars('p') != 'plan'){
    return;
}
$.contextMenu('destroy', '#div_pageContainer' );
}

$('main').css('padding-right','0px').css('padding-left','0px').css('margin-right','0px').css('margin-left','0px');

var deviceInfo = getDeviceType();
var editOption = {state : false, snap : false,grid : false,gridSize:false,highlight:true};
var clickedOpen = false;

planHeaderContextMenu = {};
for(var i in planHeader){
    planHeaderContextMenu[planHeader[i].id] = {
        name:planHeader[i].name,
        callback: function(key, opt){
            planHeader_id = key;
            displayPlan();
        }
    }
}
if(deviceInfo.type == 'desktop' && user_isAdmin == 1){
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
                icon : 'fa-picture-o',
                items: planHeaderContextMenu
            },
            edit: {
                name: "{{Edition}}",
                icon : 'fa-pencil',
                callback: function(key, opt){
                    editOption.state = !editOption.state;
                    this.data('editOption.state', editOption.state);
                    initEditOption(editOption.state);
                }
            },
            fullscreen: {
                name: "{{Plein écran}}",
                icon : 'fa-desktop',
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
                icon : 'fa-line-chart',
                disabled:function(key, opt) { 
                    return !this.data('editOption.state'); 
                },
                callback: function(key, opt){
                    addObject({link_type : 'graph',link_id:Math.round(Math.random() * 99999999) + 9999});
                }
            },
            addText: {
                name: "{{Ajouter texte/html}}",
                icon : 'fa-align-center',
                disabled:function(key, opt) { 
                    return !this.data('editOption.state'); 
                },
                callback: function(key, opt){
                 addObject({link_type : 'text',link_id:Math.round(Math.random() * 99999999) + 9999,display: {text: 'Texte à insérer ici'}});
             }
         },
         addScenario: {
            name: "{{Ajouter scénario}}",
            icon : 'fa-plus-circle',
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
        icon : 'fa-link',
        disabled:function(key, opt) { 
            return !this.data('editOption.state'); 
        },
        items: {
         addViewLink: {
            name: "{{Vers une vue}}",
            icon : 'fa-link',
            disabled:function(key, opt) { 
                return !this.data('editOption.state'); 
            },
            callback: function(key, opt){
                addObject({link_type :'view',link_id : -(Math.round(Math.random() * 99999999) + 9999),display : {name : 'A configurer'}});
            }
        },
        addPlanLink: {
            name: "{{Vers un design}}",
            icon : 'fa-link',
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
    icon : 'fa-plus-circle',
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
    icon : 'fa-plus-circle',
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
    icon : 'fa-plus-circle',
    disabled:function(key, opt) { 
        return !this.data('editOption.state'); 
    },
    callback: function(key, opt){
        addObject({link_type : 'image',link_id : Math.round(Math.random() * 99999999) + 9999});
    }
},
addZone: {
    name: "{{Ajouter une zone}}",
    icon : 'fa-plus-circle',
    disabled:function(key, opt) { 
        return !this.data('editOption.state'); 
    },
    callback: function(key, opt){
        addObject({link_type : 'zone',link_id : Math.round(Math.random() * 99999999) + 9999});
    }
},
addSummary: {
    name: "{{Ajouter un résumé}}",
    icon : 'fa-plus-circle',
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
    icon : 'fa-th',
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
        snap: {
            name: "{{Aimanter les élements}}", 
            type: 'checkbox', 
            radio: 'radio', 
            selected:  editOption.snap,
            events: {
                click : function(e) {
                 editOption.snap = $(this).value();
                 initEditOption(1);
             }
         }
     },
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
                console.log($(this).value())
                editOption.highlight = ($(this).value() == 1) ? false : true;
                initEditOption(1);
            }
        }
    },
}
},
removePlan: {
    name: "{{Supprimer le design}}",
    icon : 'fa-trash',
    disabled:function(key, opt) { 
        return !this.data('editOption.state'); 
    },
    callback: function(key, opt){
      bootbox.confirm('{{Etes-vous sûr de vouloir supprimer ce design ?}}', function (result) {
        if (result) {
            jeedom.plan.removeHeader({
                id:planHeader_id,
                error: function (error) {
                    $('#div_alert').showAlert({message: error.message, level: 'danger'});
                },
                success: function () {
                 $('#div_alert').showAlert({message: 'Design supprimé', level: 'success'});
                 loadPage('index.php?v=d&p=plan');
             },
         });
        }
    });
  }
},
addPlan: {
    name: "{{Creer un design}}",
    icon : 'fa-plus-circle',
    disabled:function(key, opt) { 
        return !this.data('editOption.state'); 
    },
    callback: function(key, opt){
        createNewDesign();
    }
},
duplicatePlan: {
    name: "{{Dupliquer le design}}",
    icon : 'fa-files-o',
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
    icon : 'fa-cogs',
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
    icon : 'fa-floppy-o',
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
            $(this).removeClass('widget-shadow-edit').addClass('contextMenu_select');
        }
    },
    hide : function(opt){
     $.contextMenu.getInputValues(opt, this.data());
     if(editOption.highlight){
       $(this).removeClass('contextMenu_select').addClass('widget-shadow-edit');
   }
}
},
items: {
    parameter: {
        name: '{{Paramètres d\'affichage}}',
        icon:'fa-cogs',
        callback: function(key, opt){
            savePlan(false,false);
            $('#md_modal').dialog({title: "{{Configuration du widget}}"});
            $('#md_modal').load('index.php?v=d&modal=plan.configure&id='+$(this).attr('data-plan_id')).dialog('open');
        }
    },
    configuration: {
        name: '{{Configuration avancée}}',
        icon:'fa-cog',
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
                            var tr = $('#table_addViewData tbody tr:first');
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
    icon:'fa-trash',
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
    icon:'fa-files-o',
    disabled: function(key, opt){ 
        var info = getObjectInfo($(this));    
        return !(info.type == 'text' || info.type == 'graph' || info.type == 'zone');
    },
    callback: function(key, opt){
        var info = getObjectInfo($(this));
        jeedom.plan.copy({
            id: $(this).attr('data-plan_id'),
            version: 'dplan',
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
            console.log(opt);
            if($(this).value() == 1){
                opt.handleObj.data.$trigger.addClass('locked');
            }else{
               opt.handleObj.data.$trigger.removeClass('locked');
           }
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

$('#div_pageContainer').delegate('.plan-link-widget', 'click', function () {
    if (!editOption.state) {
        planHeader_id = $(this).attr('data-link_id');
        displayPlan();
    }
});

$('#div_pageContainer').on( 'click','.zone-widget:not(.zoneEqLogic)', function () {
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

$('#div_pageContainer').on('mouseenter','.zone-widget.zoneEqLogic.zoneEqLogicOnFly',  function () {
    if (!editOption.state) {
      clickedOpen = true;
      var el = $(this);
      jeedom.eqLogic.toHtml({
        id : el.attr('data-eqLogic_id'),
        version : 'dplan',
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

$('#div_pageContainer').on('click','.zone-widget.zoneEqLogic.zoneEqLogicOnClic', function () {
    if (!editOption.state && !clickedOpen) {
      clickedOpen = true;
      var el = $(this);
      jeedom.eqLogic.toHtml({
        id : el.attr('data-eqLogic_id'),
        version : 'dplan',
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

function initEditOption(_state) {
    if (_state) {
      $('.plan-link-widget,.view-link-widget,.graph-widget,.div_displayObject >.eqLogic-widget,.div_displayObject > .cmd-widget,.scenario-widget,.text-widget,.image-widget,.zone-widget,.summary-widget').draggable({
        snap : (editOption.snap == 1),
        grid : (editOption.grid == 1) ? editOption.gridSize : false,
        containment: 'parent',
        cancel : '.locked',
        stop: function( event, ui ) {
            savePlan(false,false);
        }   
    });
      if(editOption.highlight){
        $('.plan-link-widget,.view-link-widget,.graph-widget,.div_displayObject >.eqLogic-widget,.div_displayObject > .cmd-widget,.scenario-widget,.text-widget,.image-widget,.zone-widget,.summary-widget').addClass('widget-shadow-edit');
    }else{
        $('.plan-link-widget,.view-link-widget,.graph-widget,.div_displayObject >.eqLogic-widget,.div_displayObject > .cmd-widget,.scenario-widget,.text-widget,.image-widget,.zone-widget,.summary-widget').removeClass('widget-shadow-edit').removeClass('contextMenu_select');
    }
    if(editOption.gridSize){
       $('.div_grid').show().css('background-size',editOption.gridSize[0]+'px '+editOption.gridSize[1]+'px');
   }else{
    $('.div_grid').hide();
}
$('.plan-link-widget,.view-link-widget,.graph-widget,.div_displayObject >.eqLogic-widget,.scenario-widget,.text-widget,.image-widget,.zone-widget,.summary-widget').resizable({
    containment: "parent",
    cancel : '.locked',
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
    try{
        $('.plan-link-widget,.view-link-widget,.graph-widget,.div_displayObject >.eqLogic-widget,.div_displayObject > .cmd-widget,.scenario-widget,.text-widget,.image-widget,.zone-widget,.summary-widget').draggable("destroy");
        $('.plan-link-widget,.view-link-widget,.graph-widget,.div_displayObject >.eqLogic-widget,.div_displayObject > .cmd-widget,.scenario-widget,.text-widget,.image-widget,.zone-widget,.summary-widget').removeClass('widget-shadow-edit');
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
        version: 'dplan',
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
                $('.div_backgroundPlan').css('background-color','transparent');
            }else if (isset(data.configuration.backgroundColor)) {
                $('.div_backgroundPlan').css('background-color',data.configuration.backgroundColor);
            }else{
                $('.div_backgroundPlan').css('background-color','#ffffff');
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
    if (_plan.link_type == 'eqLogic' || _plan.link_type == 'scenario' || _plan.link_type == 'text' || _plan.link_type == 'image') {
        $('.div_displayObject .'+_plan.link_type+'-widget[data-'+_plan.link_type+'_id=' + _plan.link_id + ']').remove();
    }else if (_plan.link_type == 'view' || _plan.link_type == 'plan') {
        $('.div_displayObject .'+_plan.link_type+'-link-widget[data-link_id=' + _plan.link_id + ']').remove();
    }else if (_plan.link_type == 'cmd') {
        $('.div_displayObject > .cmd-widget[data-cmd_id=' + _plan.link_id + ']').remove();
    }else if (_plan.link_type == 'graph') {
        for (var i in jeedom.history.chart) {
            delete jeedom.history.chart[i];
        }
        $('.div_displayObject .graph-widget[data-graph_id=' + _plan.link_id + ']').remove();
    }
    var html = $(_html);
    html.attr('data-plan_id',_plan.id);
    html.addClass('jeedomAlreadyPosition');
    html.css('z-index', 1000);
    html.css('position', 'absolute');
    html.css('top',  init(_plan.position.top, '10') * $('.div_displayObject').height() / 100);
    html.css('left', init(_plan.position.left, '10') * $('.div_displayObject').width() / 100);
    html.css('transform-origin', '0 0');
    html.css('transform', 'scale(' + init(_plan.css.zoom, 1) + ')');
    html.css('-webkit-transform-origin', '0 0');
    html.css('-webkit-transform', 'scale(' + init(_plan.css.zoom, 1) + ')');
    html.css('-moz-transform-origin', '0 0');
    html.css('-moz-transform', 'scale(' + init(_plan.css.zoom, 1) + ')');
    html.addClass('noResize');
    if (isset(_plan.display) && isset(_plan.display.width)) {
        html.css('width', init(_plan.display.width, 50));
    }
    if (isset(_plan.display) && isset(_plan.display.height)) {
        html.css('height', init(_plan.display.height, 50));
    }
    for (var key in _plan.css) {
        if (_plan.css[key] != '' && key != 'zoom' && key != 'color' && key != 'rotate' && key != 'background-color') {
            if(key == 'z-index' && _plan.css[key] < 999){
                continue;
            }
            html.css(key, _plan.css[key]);
        }else if (_plan.link_type == 'text' || _plan.link_type == 'graph' || _plan.link_type == 'plan' || _plan.link_type == 'view') {
            if (key == 'background-color' && (!isset(_plan.display) || !isset(_plan.display['background-defaut']) || _plan.display['background-defaut'] != 1)) {
             if (isset(_plan.display) && isset(_plan.display['background-transparent']) && _plan.display['background-transparent'] == 1) {
                 html.css('background-color', 'transparent');
                 html.css('border-radius', '0px'); 
                 html.css('box-shadow', 'none'); 
             }else{
              html.css(key, _plan.css[key]);
          }
      }else if (key == 'color' && (!isset(_plan.display) || !isset(_plan.display['color-defaut']) || _plan.display['color-defaut'] != 1)) {
        html.css(key, _plan.css[key]);
    }
}
}
if(_plan.link_type == 'graph'){
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
                });
            }
        }
    }
    initEditOption(editOption.state);
    return;
}
if (init(_noRender, false)) {
 return html;
}
$('.div_displayObject').append(html);
initEditOption(editOption.state);
}
