
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

var JS_ERROR = [];
uniqId_count = 0;
modifyWithoutSave = false;
nbActiveAjaxRequest = 0;
jeedomBackgroundImg = null;
utid = Date.now();

window.addEventListener('error', function (evt) {
  if(evt.filename.indexOf('file=3rdparty/') != -1){
    return;
  }
  JS_ERROR.push(evt);
});

$(document).ajaxStart(function () {
  nbActiveAjaxRequest++;
  $.showLoading();
});
$(document).ajaxStop(function () {
  nbActiveAjaxRequest--;
  if (nbActiveAjaxRequest <= 0) {
    nbActiveAjaxRequest = 0;
    $.hideLoading();
  }
});

function loadPage(_url,_noPushHistory){
  if (modifyWithoutSave) {
    if (!confirm('{{Attention vous quittez une page ayant des données modifiées non sauvegardées. Voulez-vous continuer ?}}')) {
      return;
    }
    modifyWithoutSave = false;
  }
  if(JS_ERROR.length > 0){
    document.location.href = _url;
    return;
  }
  if (typeof unload_page !== "undefined") {
    unload_page();
  }
  $(".ui-dialog-content").dialog("close");
  if(!isset(_noPushHistory) || _noPushHistory == false){
    window.history.pushState('','', _url);
  }
  if(isset(bootbox)){
    bootbox.hideAll();
  }
  jeedom.cmd.update = Array();
  jeedom.scenario.update = Array();
  $('main').css('padding-right','').css('padding-left','').css('margin-right','').css('margin-left','');
  $('#div_pageContainer').add("#div_pageContainer *").off();
  $.hideAlert();
  $('.bt_pluginTemplateShowSidebar').remove();
  removeContextualFunction();
  if(_url.indexOf('#') == -1){
    var url = _url+'&ajax=1';
  }else{
    var n=_url.lastIndexOf("#");
    var url = _url.substring(0,n)+"&ajax=1"+_url.substring(n)
  }
  $('.backgroundforJeedom').css('background-image','');
  $('.backgroundforJeedom').css('background-position','center center');
  $('.backgroundforJeedom').css('background-repeat','no-repeat');
  $('.backgroundforJeedom').css('background-size','cover');
  setBackgroundImg('');
  jeedomBackgroundImg = null;
  $('#div_pageContainer').empty().load(url,function(){
    $('body').attr('data-page',getUrlVars('p'));
    $('#bt_getHelpPage').attr('data-page',getUrlVars('p')).attr('data-plugin',getUrlVars('m'));
    initPage();
    $('body').trigger('jeedom_page_load');
    if(jeedomBackgroundImg !== null){
      setBackgroundImg(jeedomBackgroundImg);
    }
  });
  return;
}

function removeContextualFunction(){
  printEqLogic = undefined
}

$(function () {
  $.alertTrigger = function(){
    initRowOverflow();
  }
  $('body').attr('data-page',getUrlVars('p'));
  
  //touch punch fix:
  $('body').on('click','input', function() {
    $(this).focus()
  })
  
  window.addEventListener('popstate', function (event){
    if(event.state === null){
      return;
    }
    var url = window.location.href.split("index.php?");
    loadPage('index.php?'+url[1],true)
  });
  
  $('body').on('click','a',function(e){
    if($(this).hasClass('noOnePageLoad')){
      return;
    }
    if($(this).hasClass('fancybox-nav')){
      return;
    }
    if($(this).attr('href') == undefined || $(this).attr('href') == '' || $(this).attr('href') == '#'){
      return;
    }
    if ($(this).attr('href').match("^http")) {
      return;
    }
    if ($(this).attr('href').match("^#")) {
      return;
    }
    if($(this).attr('target') == '_blank'){
      return;
    }
    $('li.dropdown.open').click();
    loadPage($(this).attr('href'));
    e.preventDefault();
    e.stopPropagation();
  });
  
  
  toastr.options = {
    "closeButton": true,
    "debug": false,
    "positionClass": "toast-top-right",
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
  }
  
  
  $('ul.dropdown-menu [data-toggle=dropdown]').on('click', function (event) {
    event.preventDefault();
    event.stopPropagation();
    $(this).parent().siblings().removeClass('open');
    $(this).parent().toggleClass('open');
  });
  if (!navigator.userAgent.match(/Android/i)
  && !navigator.userAgent.match(/webOS/i)
  && !navigator.userAgent.match(/iPhone/i)
  && !navigator.userAgent.match(/iPad/i)
  && !navigator.userAgent.match(/iPod/i)
  && !navigator.userAgent.match(/BlackBerry/i)
  & !navigator.userAgent.match(/Windows Phone/i)
) {
  $('ul.dropdown-menu [data-toggle=dropdown]').on('mouseenter', function (event) {
    event.preventDefault();
    event.stopPropagation();
    $(this).parent().siblings().removeClass('open');
    $(this).parent().toggleClass('open');
  });
}
/*********************Gestion des dialogs********************************/

$.fn.modal.Constructor.prototype.enforceFocus = function () {};

$('body').on( "show", ".modal",function () {
  document.activeElement.blur();
  $(this).find(".modal-body :input:visible:first").focus();
});

$('body').on('focusin','.bootbox-input', function (e) {
  e.stopPropagation();
});
/************************Help*************************/

if (isset(jeedom_langage)) {
  bootbox.setDefaults({
    locale: jeedom_langage.substr(0, 2),
  });
}

//Display report bug
$("#md_reportBug").dialog({
  autoOpen: false,
  modal: true,
  closeText: '',
  height: ((jQuery(window).height() - 100) < 700) ? jQuery(window).height() - 100 : 700,
  width: ((jQuery(window).width() - 100) < 900) ? (jQuery(window).width() - 100) : 900,
  position: { my: "center bottom-10", at: "center bottom", of: window },
  open: function () {
    $("body").css({overflow: 'hidden'})
    $(this).closest( ".ui-dialog" ).find(":button").blur();
  },
  beforeClose: function (event, ui) {
    $("body").css({overflow: 'inherit'})
    $("#md_reportBug").empty();
  }
});

//Display help
$("#md_pageHelp").dialog({
  autoOpen: false,
  modal: false,
  closeText: '',
  height: (jQuery(window).height() - 100),
  width: ((jQuery(window).width() - 100) < 1500) ? (jQuery(window).width() - 50) : 1500,
  position: { my: "center bottom-10", at: "center bottom", of: window },
  open: function () {
    $("body").css({overflow: 'hidden'});
    $(this).closest( ".ui-dialog" ).find(":button").blur();
  },
  beforeClose: function (event, ui) {
    $("body").css({overflow: 'inherit'});
    $("#md_pageHelp").empty();
  }
});

$("#md_modal").dialog({
  autoOpen: false,
  modal: true,
  closeText: '',
  height: (jQuery(window).height() - 100),
  width: ((jQuery(window).width() - 50) < 1500) ? (jQuery(window).width() - 50) : 1500,
  position: {my: 'center', at: 'center bottom-10px', of: window},
  open: function () {
    $("body").css({overflow: 'hidden'});
    $(this).closest( ".ui-dialog" ).find(":button").blur();
  },
  beforeClose: function (event, ui) {
    $("body").css({overflow: 'inherit'});
    $("#md_modal").empty();
  }
});

$("#md_modal2").dialog({
  autoOpen: false,
  modal: true,
  closeText: '',
  height: (jQuery(window).height() - 150),
  width: ((jQuery(window).width() - 150) < 1200) ? (jQuery(window).width() - 50) : 1200,
  position: {my: 'center', at: 'center bottom-10px', of: window},
  open: function () {
    $("body").css({overflow: 'hidden'});
    $(this).closest( ".ui-dialog" ).find(":button").blur();
  },
  beforeClose: function (event, ui) {
    $("body").css({overflow: 'inherit'});
    $("#md_modal2").empty();
  }
});

$('#bt_jeedomAbout').on('click', function () {
  $('#md_modal').dialog({title: "{{A propos}}"});
  $('#md_modal').load('index.php?v=d&modal=about').dialog('open');
});

$('#bt_getHelpPage').on('click',function(){
  jeedom.getDocumentationUrl({
    plugin: $(this).attr('data-plugin'),
    page: $(this).attr('data-page'),
    error: function(error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function(url) {
      window.open(url,'_blank');
    }
  });
});

$('body').on( 'click','.bt_pageHelp', function () {
  showHelpModal($(this).attr('data-name'), $(this).attr('data-plugin'));
});

$('body').on( 'click','.bt_reportBug', function () {
  $('#md_reportBug').load('index.php?v=d&modal=report.bug').dialog('open');
});

$(window).bind('beforeunload', function (e) {
  if (modifyWithoutSave) {
    return '{{Attention vous quittez une page ayant des données modifiées non sauvegardées. Voulez-vous continuer ?}}';
  }
});


$(window).resize(function () {
  initRowOverflow();
});


if (typeof jeedom_firstUse != 'undefined' && isset(jeedom_firstUse) && jeedom_firstUse == 1 && getUrlVars('noFirstUse') != 1) {
  $('#md_modal').dialog({title: "{{Bienvenue dans Jeedom}}"});
  $("#md_modal").load('index.php?v=d&modal=first.use').dialog('open');
}

$('#bt_haltSystem').on('click', function () {
  $.hideAlert();
  bootbox.confirm('{{Etes-vous sûr de vouloir arrêter le système ?}}', function (result) {
    if (result) {
      window.location.href = 'index.php?v=d&p=shutdown';
    }
  });
});

$('#bt_rebootSystem').on('click', function () {
  $.hideAlert();
  bootbox.confirm('{{Etes-vous sûr de vouloir redémarrer le système ?}}', function (result) {
    if (result) {
      window.location.href = 'index.php?v=d&p=reboot';
    }
  });
});

$('#bt_showEventInRealTime').on('click',function(){
  $('#md_modal').dialog({title: "{{Evénement en temps réel}}"});
  $("#md_modal").load('index.php?v=d&modal=log.display&log=event').dialog('open');
});

$('#bt_showNoteManager').on('click',function(){
  $('#md_modal').dialog({title: "{{Note}}"});
  $("#md_modal").load('index.php?v=d&modal=note.manager').dialog('open');
});

$('#bt_gotoDashboard').on('click',function(){
  if('ontouchstart' in window || navigator.msMaxTouchPoints){
    return;
  }
  $('ul.dropdown-menu [data-toggle=dropdown]').parent().parent().parent().siblings().removeClass('open');
  loadPage('index.php?v=d&p=dashboard');
});

$('#bt_gotoView').on('click',function(){
  if('ontouchstart' in window || navigator.msMaxTouchPoints){
    return;
  }
  $('ul.dropdown-menu [data-toggle=dropdown]').parent().parent().parent().siblings().removeClass('open');
  loadPage('index.php?v=d&p=view');
});

$('#bt_gotoPlan').on('click',function(){
  if('ontouchstart' in window || navigator.msMaxTouchPoints){
    return;
  }
  $('ul.dropdown-menu [data-toggle=dropdown]').parent().parent().parent().siblings().removeClass('open');
  loadPage('index.php?v=d&p=plan');
});

$('#bt_gotoPlan3d').on('click',function(){
  if('ontouchstart' in window || navigator.msMaxTouchPoints){
    return;
  }
  $('ul.dropdown-menu [data-toggle=dropdown]').parent().parent().parent().siblings().removeClass('open');
  loadPage('index.php?v=d&p=plan3d');
});

$('#bt_messageModal').on('click',function(){
  $('#md_modal').dialog({title: "{{Message Jeedom}}"});
  $('#md_modal').load('index.php?v=d&p=message&ajax=1').dialog('open');
});
  
$('body').off('click','.jeeHelper[data-helper=cron]').on('click','.jeeHelper[data-helper=cron]',function(){
    var el = $(this).closest('div').find('input');
    jeedom.getCronSelectModal({},function (result) {
      el.value(result.value);
    });
  });

$('body').on('click','.objectSummaryParent',function(){
  loadPage('index.php?v=d&p=dashboard&summary='+$(this).data('summary')+'&object_id='+$(this).data('object_id'));
});
initPage();
if(jeedomBackgroundImg != null){
  setBackgroundImg(jeedomBackgroundImg);
}
setTimeout(function(){
  $('body').trigger('jeedom_page_load');
}, 1);
});

setInterval(function () {
  var dateLoc = new Date;
  var dateJeed = new Date;
  dateJeed.setTime(dateLoc.getTime() +(dateLoc.getTimezoneOffset() + serverTZoffsetMin)*60000 + clientServerDiffDatetime);
  $('#horloge').text(dateJeed.toLocaleTimeString());
}, 1000);

function setBackgroundImg(_path){
  if(isset(userProfils) && isset(userProfils.hideBackgroundImg) && userProfils.hideBackgroundImg == 1){
    return;
  }
  if(_path == ''){
    document.body.style.setProperty('--dashBkg-url','url("")');
    $('.backgroundforJeedom').css('background-image','url("")');
  }else{
    $('.backgroundforJeedom').css('background-image','url("'+_path+'")');
    document.body.style.setProperty('--dashBkg-url','url("../../../../'+_path+'")');
  }
}

function initTextArea(){
  $('body').on('change keyup keydown paste cut', 'textarea.autogrow', function () {
    $(this).height(0).height(this.scrollHeight);
  });
}

function initCheckBox(){
  
}

function initPage(){
  initTableSorter();
  initReportMode();
  $.initTableFilter();
  initRowOverflow();
  initHelp();
  initTextArea();
  $('.nav-tabs a').on('click',function (e) {
    var scrollHeight = $(document).scrollTop();
    $(this).tab('show');
    $(window).scrollTop(scrollHeight);
    setTimeout(function() {
      $(window).scrollTop(scrollHeight);
    }, 0);
  });
}

function linkify(inputText) {
  var replacePattern1 = /(\b(https?|ftp):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/gim;
  var replacedText = inputText.replace(replacePattern1, '<a href="$1" target="_blank">$1</a>');
  var replacePattern2 = /(^|[^\/])(www\.[\S]+(\b|$))/gim;
  var replacedText = replacedText.replace(replacePattern2, '$1<a href="http://$2" target="_blank">$2</a>');
  var replacePattern3 = /(\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,6})/gim;
  var replacedText = replacedText.replace(replacePattern3, '<a href="mailto:$1">$1</a>');
  return replacedText
}

function initRowOverflow() {
  var hWindow = $(window).outerHeight() - $('header').outerHeight() - $('#div_alert').outerHeight()-5;
  if($('#div_alert').outerHeight() > 0){
    hWindow -= 10;
  }
  if($('.row-overflow').attr('data-offset') != undefined){
    hWindow -= $('.row-overflow').attr('data-offset');
  }
  $('.row-overflow > div').css('padding-top','0px').height(hWindow).css('overflow-y', 'auto').css('overflow-x', 'hidden').css('padding-top','5px');
}

function initReportMode() {
  if (getUrlVars('report') == 1) {
    $('header').hide();
    $('footer').hide();
    $('#div_mainContainer').css('margin-top', '-50px');
    $('#wrap').css('margin-bottom', '0px');
    $('.reportModeVisible').show();
    $('.reportModeHidden').hide();
  }
}

function initTableSorter() {
  $(".tablesorter").each(function () {
    var widgets = ['uitheme', 'filter', 'zebra', 'resizable'];
    $(".tablesorter").tablesorter({
      theme: "bootstrap",
      widthFixed: true,
      headerTemplate: '{content} {icon}',
      widgets: widgets,
      widgetOptions: {
        filter_ignoreCase: true,
        resizable: true,
        stickyHeaders_offset: $('header.navbar-fixed-top').height(),
        zebra: ["ui-widget-content even", "ui-state-default odd"],
      }
    });
  });
}

function initHelp(){
  $('.help').each(function(){
    if($(this).attr('data-help') != undefined){
      $(this).append(' <sup><i class="fas fa-question-circle tooltips" title="'+$(this).attr('data-help')+'" style="font-size : 1em;color:grey;"></i></sup>');
    }
  });
}

function showHelpModal(_name, _plugin) {
  if (init(_plugin) != '' && _plugin != undefined) {
    $('#div_helpWebsite').load('index.php?v=d&modal=help.website&page=doc_plugin_' + _plugin + '.php #primary', function () {
      if ($('#div_helpWebsite').find('.alert.alert-danger').length > 0 || $.trim($('#div_helpWebsite').text()) == '') {
        $('a[href="#div_helpSpe"]').click();
        $('a[href="#div_helpWebsite"]').hide();
      } else {
        $('a[href="#div_helpWebsite"]').show();
        $('a[href="#div_helpWebsite"]').click();
      }
    });
    $('#div_helpSpe').load('index.php?v=d&plugin=' + _plugin + '&modal=help.' + init(_name));
  } else {
    $('#div_helpWebsite').load('index.php?v=d&modal=help.website&page=doc_' + init(_name) + '.php #primary', function () {
      if ($('#div_helpWebsite').find('.alert.alert-danger').length > 0 || $.trim($('#div_helpWebsite').text()) == '') {
        $('a[href="#div_helpSpe"]').click();
        $('a[href="#div_helpWebsite"]').hide();
      } else {
        $('a[href="#div_helpWebsite"]').show();
        $('a[href="#div_helpWebsite"]').click();
      }
    });
    $('#div_helpSpe').load('index.php?v=d&modal=help.' + init(_name));
  }
  $('#md_pageHelp').dialog('open');
}

function refreshMessageNumber() {
  jeedom.message.number({
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success : function (_number) {
      MESSAGE_NUMBER = _number;
      if (_number == 0 || _number == '0') {
        $('#span_nbMessage').hide();
      } else {
        $('#span_nbMessage').html(_number);
        $('#span_nbMessage').show();
      }
    }
  });
}

function refreshUpdateNumber() {
  jeedom.update.number({
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success : function (_number) {
      UPDATE_NUMBER = _number;
      if (_number == 0 || _number == '0') {
        $('#span_nbUpdate').hide();
      } else {
        $('#span_nbUpdate').html(_number);
        $('#span_nbUpdate').show();
      }
    }
  });
}

function notify(_title, _text, _class_name) {
  if (_title == '' && _text == '') {
    return true;
  }
  if (isset(_class_name) != '' && isset(toastr[_class_name])) {
    toastr[_class_name](_text, _title);
  } else {
    toastr.info(_text, _title);
  }
}

jQuery.fn.findAtDepth = function (selector, maxDepth) {
  var depths = [], i;
  
  if (maxDepth > 0) {
    for (i = 1; i <= maxDepth; i++) {
      depths.push('> ' + new Array(i).join('* > ') + selector);
    }
    
    selector = depths.join(', ');
  }
  return this.find(selector);
};

function sleep(milliseconds) {
  var start = new Date().getTime();
  for (var i = 0; i < 1e7; i++) {
    if ((new Date().getTime() - start) > milliseconds){
      break;
    }
  }
}


function chooseIcon(_callback) {
  if ($("#mod_selectIcon").length == 0) {
    $('#div_pageContainer').append('<div id="mod_selectIcon" title="{{Choisissez votre icône}}" ></div>');
    
    $("#mod_selectIcon").dialog({
      closeText: '',
      autoOpen: false,
      modal: true,
      height: (jQuery(window).height() - 150),
      width: 1500,
      open: function () {
        if ((jQuery(window).width() - 50) < 1500) {
          $('#mod_selectIcon').dialog({width: jQuery(window).width() - 50});
        }
        $("body").css({overflow: 'hidden'});
      },
      beforeClose: function (event, ui) {
        $("body").css({overflow: 'inherit'});
      }
    });
    jQuery.ajaxSetup({async: false});
    $('#mod_selectIcon').load('index.php?v=d&modal=icon.selector');
    jQuery.ajaxSetup({async: true});
  }
  $("#mod_selectIcon").dialog('option', 'buttons', {
    "Annuler": function () {
      $(this).dialog("close");
    },
    "Valider": function () {
      var icon = $('.iconSelected .iconSel').html();
      if (icon == undefined) {
        icon = '';
      }
      icon = icon.replace(/"/g, "'");
      _callback(icon);
      $(this).dialog('close');
    }
  });
  $('#mod_selectIcon').dialog('open');
}


function calculWidgetSize(_size,_step,_margin){
  var result = Math.ceil(_size / _step) * _step - (2*_margin);
  if(result < _size){
    result += Math.ceil((_size - result) / _step)* _step;
  }
  return result;
}

function positionEqLogic(_id,_preResize,_scenario) {
  if(_id != undefined){
    var widget = (_scenario) ? $('.scenario-widget[data-scenario_id='+_id+']') : $('.eqLogic-widget[data-eqlogic_id='+_id+']');
    widget.css('margin','0px').css('padding','0px');
    if($(this).width() == 0){
      $(this).width('230');
    }
    if($(this).height() == 0){
      $(this).height('110');
    }
    if(init(_preResize,true)){
      widget.width(Math.floor(widget.width() / widget_width_step) * widget_width_step - (2 * widget_margin));
      widget.height(Math.floor(widget.height() / widget_height_step) * widget_height_step - (2 * widget_margin));
    }
    widget.width(calculWidgetSize(widget.width(),widget_width_step,widget_margin));
    widget.height(calculWidgetSize(widget.height(),widget_height_step,widget_margin));
    if(!widget.hasClass(widget.attr('data-category'))){
      widget.addClass(widget.attr('data-category'));
    }
    widget.css('margin',widget_margin+'px');
  }else{
    $('.eqLogic-widget:not(.jeedomAlreadyPosition),.scenario-widget:not(.jeedomAlreadyPosition)').css('margin','0px').css('padding','0px');
    $('.eqLogic-widget:not(.jeedomAlreadyPosition),.scenario-widget:not(.jeedomAlreadyPosition)').each(function () {
      if($(this).width() == 0){
        $(this).width('230');
      }
      if($(this).height() == 0){
        $(this).height('110');
      }
      $(this).width(calculWidgetSize($(this).width(),widget_width_step,widget_margin));
      $(this).height(calculWidgetSize($(this).height(),widget_height_step,widget_margin));
      if(!$(this).hasClass($(this).attr('data-category'))){
        $(this).addClass($(this).attr('data-category'));
      }
    });
    $('.eqLogic-widget:not(.jeedomAlreadyPosition),.scenario-widget:not(.jeedomAlreadyPosition)').css('margin',widget_margin+'px');
    $('.eqLogic-widget,.scenario-widget').addClass('jeedomAlreadyPosition');
  }
}


function uniqId(_prefix){
  if(typeof _prefix == 'undefined'){
    _prefix = 'jee-uniq';
  }
  var result = _prefix +'-'+ uniqId_count + '-'+Math.random().toString(36).substring(8);;
  uniqId_count++;
  if($('#'+result).length){
    return uniqId(_prefix);
  }
  return result;
}


function taAutosize(){
  autosize($('.ta_autosize'));
  autosize.update($('.ta_autosize'));
}

function saveWidgetDisplay(_params){
  if(init(_params) == ''){
    _params = {};
  }
  var cmds = [];
  var eqLogics = [];
  var scenarios = [];
  $('.eqLogic-widget:not(.eqLogic_layout_table)').each(function(){
    var eqLogic = $(this);
    order = 1;
    eqLogic.find('.cmd').each(function(){
      cmd = {};
      cmd.id = $(this).attr('data-cmd_id');
      cmd.order = order;
      cmds.push(cmd);
      order++;
    });
  });
  $('.eqLogic-widget.eqLogic_layout_table').each(function(){
    var eqLogic = $(this);
    order = 1;
    eqLogic.find('.cmd').each(function(){
      cmd = {};
      cmd.id = $(this).attr('data-cmd_id');
      cmd.line = $(this).closest('td').attr('data-line');
      cmd.column = $(this).closest('td').attr('data-column');
      cmd.order = order;
      cmds.push(cmd);
      order++;
    });
  });
  if(init(_params['dashboard']) == 1){
    $('.div_displayEquipement').each(function(){
      order = 1;
      $(this).find('.eqLogic-widget,.scenario-widget').each(function(){
        if($(this).hasClass('eqLogic-widget')){
          var eqLogic = {id :$(this).attr('data-eqlogic_id')}
          eqLogic.display = {};
          eqLogic.display.width =  Math.floor($(this).outerWidth() / 2) * 2 + 'px';
          eqLogic.display.height = Math.floor($(this).outerHeight() / 2) * 2+ 'px';
          eqLogic.order = ($(this).attr('data-order') != undefined) ? $(this).attr('data-order') : order;
          eqLogics.push(eqLogic);
        }
        if($(this).hasClass('scenario-widget')){
          var scenario = {id :$(this).attr('data-scenario_id')}
          scenario.display = {};
          scenario.display.width =  Math.floor($(this).outerWidth() / 2) * 2 + 'px';
          scenario.display.height = Math.floor($(this).outerHeight() / 2) * 2+ 'px';
          scenario.order = ($(this).attr('data-order') != undefined) ? $(this).attr('data-order') : order;
          scenarios.push(scenario);
        }
        order++;
      });
    });
    jeedom.eqLogic.setOrder({
      eqLogics: eqLogics,
      error: function (error) {
        $('#div_alert').showAlert({message: error.message, level: 'danger'});
      },
      success:function(data){
        jeedom.cmd.setOrder({
          cmds: cmds,
          error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
          }
        });
      }
    });
    jeedom.scenario.setOrder({
      scenarios: scenarios,
      error: function (error) {
        $('#div_alert').showAlert({message: error.message, level: 'danger'});
      }
    });
  } else if(init(_params['view']) == 1){
    $('.eqLogicZone').each(function(){
      order = 1;
      $(this).find('.eqLogic-widget').each(function(){
        var eqLogic = {id :$(this).attr('data-eqlogic_id')}
        eqLogic.display = {};
        eqLogic.display.width =  Math.floor($(this).outerWidth() / 2) * 2 + 'px';
        eqLogic.display.height = Math.floor($(this).outerHeight() / 2) * 2+ 'px';
        eqLogic.viewZone_id = $(this).closest('.eqLogicZone').attr('data-viewZone-id');
        eqLogic.order = order;
        eqLogics.push(eqLogic);
        order++;
      });
    });
    jeedom.view.setEqLogicOrder({
      eqLogics: eqLogics,
      error: function (error) {
        $('#div_alert').showAlert({message: error.message, level: 'danger'});
      },
      success:function(data){
        jeedom.cmd.setOrder({
          cmds: cmds,
          error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
          }
        });
      }
    });
  }
}


function editWidgetCmdMode(_mode){
  if(!isset(_mode)){
    if($('#bt_editDashboardWidgetOrder').attr('data-mode') != undefined && $('#bt_editDashboardWidgetOrder').attr('data-mode') == 1){
      editWidgetMode(0);
      editWidgetMode(1);
    }
    return;
  }
  if(_mode == 0){
    $( ".eqLogic-widget.eqLogic_layout_table table.tableCmd").removeClass('table-bordered');
    $.contextMenu('destroy');
    if( $('.eqLogic-widget.allowReorderCmd.eqLogic_layout_table table.tableCmd.ui-sortable').length > 0){
      try{
        $('.eqLogic-widget.allowReorderCmd.eqLogic_layout_table table.tableCmd').sortable('destroy');
      }catch(e){
        
      }
    }
    if( $('.eqLogic-widget.allowReorderCmd.eqLogic_layout_default.ui-sortable').length > 0){
      try{
        $('.eqLogic-widget.allowReorderCmd.eqLogic_layout_default').sortable('destroy');
      }catch(e){
        
      }
    }
    if( $('.eqLogic-widget.ui-draggable').length > 0){
      $('.eqLogic-widget.allowReorderCmd').off('mouseover','.cmd');
      $('.eqLogic-widget.allowReorderCmd').off('mouseleave','.cmd');
    }
  }else{
    $( ".eqLogic-widget.allowReorderCmd.eqLogic_layout_default").sortable({items: ".cmd"});
    $(".eqLogic-widget.eqLogic_layout_table table.tableCmd").addClass('table-bordered');
    $('.eqLogic-widget.eqLogic_layout_table table.tableCmd td').sortable({
      connectWith: '.eqLogic-widget.eqLogic_layout_table table.tableCmd td',items: ".cmd"});
      $('.eqLogic-widget.allowReorderCmd').on('mouseover','.cmd',function(){
        $('.eqLogic-widget').draggable('disable');
      });
      $('.eqLogic-widget.allowReorderCmd').on('mouseleave','.cmd',function(){
        $('.eqLogic-widget').draggable('enable');
      });
      $.contextMenu({
        selector: '.eqLogic-widget',
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
          configuration: {
            name: "{{Configuration avancée}}",
            icon : 'fa-cog',
            callback: function(key, opt){
              saveWidgetDisplay()
              $('#md_modal').dialog({title: "{{Configuration du widget}}"});
              $('#md_modal').load('index.php?v=d&modal=eqLogic.configure&eqLogic_id='+$(this).attr('data-eqLogic_id')).dialog('open');
            }
          },
          sep1 : "---------",
          layoutDefaut: {
            name: "{{Defaut}}",
            icon : 'fa-square-o',
            disabled:function(key, opt) {
              return !$(this).hasClass('allowLayout') || !$(this).hasClass('eqLogic_layout_table');
            },
            callback: function(key, opt){
              saveWidgetDisplay()
              jeedom.eqLogic.simpleSave({
                eqLogic : {
                  id : $(this).attr('data-eqLogic_id'),
                  display : {'layout::dashboard' : 'default'},
                },
                error: function (error) {
                  $('#div_alert').showAlert({message: error.message, level: 'danger'});
                }
              });
            }
          },
          layoutTable: {
            name: "{{Table}}",
            icon : 'fa-table',
            disabled:function(key, opt) {
              return !$(this).hasClass('allowLayout') || $(this).hasClass('eqLogic_layout_table');
            },
            callback: function(key, opt){
              saveWidgetDisplay()
              jeedom.eqLogic.simpleSave({
                eqLogic : {
                  id : $(this).attr('data-eqLogic_id'),
                  display : {'layout::dashboard' : 'table'},
                },
                error: function (error) {
                  $('#div_alert').showAlert({message: error.message, level: 'danger'});
                }
              });
            }
          },
          sep2 : "---------",
          addTableColumn: {
            name: "{{Ajouter colonne}}",
            icon : 'fa-plus',
            disabled:function(key, opt) {
              return !$(this).hasClass('eqLogic_layout_table');
            },
            callback: function(key, opt){
              saveWidgetDisplay()
              jeedom.eqLogic.simpleSave({
                eqLogic : {
                  id : $(this).attr('data-eqLogic_id'),
                  display : {'layout::dashboard::table::nbColumn' : parseInt($(this).find('table.tableCmd').attr('data-column')) + 1},
                },
                error: function (error) {
                  $('#div_alert').showAlert({message: error.message, level: 'danger'});
                }
              });
            }
          },
          addTableLine: {
            name: "{{Ajouter ligne}}",
            icon : 'fa-plus',
            disabled:function(key, opt) {
              return !$(this).hasClass('eqLogic_layout_table');
            },
            callback: function(key, opt){
              saveWidgetDisplay()
              jeedom.eqLogic.simpleSave({
                eqLogic : {
                  id : $(this).attr('data-eqLogic_id'),
                  display : {'layout::dashboard::table::nbLine' : parseInt($(this).find('table.tableCmd').attr('data-line')) + 1},
                },
                error: function (error) {
                  $('#div_alert').showAlert({message: error.message, level: 'danger'});
                }
              });
            }
          },
          removeTableColumn: {
            name: "{{Supprimer colonne}}",
            icon : 'fa-minus',
            disabled:function(key, opt) {
              return !$(this).hasClass('eqLogic_layout_table');
            },
            callback: function(key, opt){
              saveWidgetDisplay()
              jeedom.eqLogic.simpleSave({
                eqLogic : {
                  id : $(this).attr('data-eqLogic_id'),
                  display : {'layout::dashboard::table::nbColumn' : parseInt($(this).find('table.tableCmd').attr('data-column')) - 1},
                },
                error: function (error) {
                  $('#div_alert').showAlert({message: error.message, level: 'danger'});
                }
              });
            }
          },
          removeTableLine: {
            name: "{{Supprimer ligne}}",
            icon : 'fa-minus',
            disabled:function(key, opt) {
              return !$(this).hasClass('eqLogic_layout_table');
            },
            callback: function(key, opt){
              saveWidgetDisplay()
              jeedom.eqLogic.simpleSave({
                eqLogic : {
                  id : $(this).attr('data-eqLogic_id'),
                  display : {'layout::dashboard::table::nbLine' : parseInt($(this).find('table.tableCmd').attr('data-line')) - 1},
                },
                error: function (error) {
                  $('#div_alert').showAlert({message: error.message, level: 'danger'});
                }
              });
            }
          },
        }
      });
    }
  }
  
  function hexToRgb(hex) {
    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? {
      r: parseInt(result[1], 16),
      g: parseInt(result[2], 16),
      b: parseInt(result[3], 16)
    } : null;
  }
  
