
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
 uniqId_count = 0;
 modifyWithoutSave = false;
 nbActiveAjaxRequest = 0;
 utid = Date.now();

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
if (typeof unload_page !== "undefined") {
    unload_page();
}
$("#md_modal").dialog('close');
$("#md_modal2").dialog('close');
if(!isset(_noPushHistory) || _noPushHistory == false){
    window.history.pushState('','', _url);
}
$('#div_pageContainer').add("#div_pageContainer *").off();
$.hideAlert();
$('.bt_pluginTemplateShowSidebar').remove();
removeContextualFunction();
$('#div_pageContainer').empty().load(_url+'&ajax=1',function(){
    $('#bt_getHelpPage').attr('data-page',getUrlVars('p')).attr('data-plugin',getUrlVars('m'));
    var title = getUrlVars('p');
    document.title = title[0].toUpperCase() + title.slice(1) +' - Jeedom';
    initPage();
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
/*********************Gestion de l'heure********************************/
setInterval(function () {
    var date = new Date();
    date.setTime(date.getTime() + clientServerDiffDatetime);
    var hour = date.getHours();
    var minute = date.getMinutes();
    var seconde = date.getSeconds();
    var horloge = (hour < 10) ? '0' + hour : hour;
    horloge += ':';
    horloge += (minute < 10) ? '0' + minute : minute;
    horloge += ':';
    horloge += (seconde < 10) ? '0' + seconde : seconde;
    $('#horloge').text(horloge);
}, 1000);



$.fn.modal.Constructor.prototype.enforceFocus = function () {
};

$('body').on( "show", ".modal",function () {
    document.activeElement.blur();
    $(this).find(".modal-body :input:visible:first").focus();
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
        modal: false,
        closeText: '',
        height: ((jQuery(window).height() - 100) < 700) ? jQuery(window).height() - 100 : 700,
        width: ((jQuery(window).width() - 100) < 900) ? (jQuery(window).width() - 100) : 900,
        position: {my: 'center', at: 'center bottom-10px', of: window},
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
        position: {my: 'center', at: 'center bottom-10px', of: window},
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
        modal: false,
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
        modal: false,
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

    /******************Gestion mode expert**********************/

    $('#bt_expertMode').on('click', function () {
        if ($(this).attr('state') == 1) {
            var value = {options: {expertMode: 0}};
            $(this).attr('state', 0);
            $(this).find('i').removeClass('fa-check-square-o').addClass('fa-square-o');
        } else {
            var value = {options: {expertMode: 1}};
            $(this).attr('state', 1);
            $(this).find('i').removeClass('fa-square-o').addClass('fa-check-square-o');
        }
        initExpertMode();
        jeedom.user.saveProfils({
            profils: value,
            global: false,
            error: function (error) {
                $('#div_alert').showAlert({message: error.message, level: 'danger'});
            },
            success: function () {
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

    $('#bt_gotoDashboard').on('click',function(){
        $('ul.dropdown-menu [data-toggle=dropdown]').parent().parent().parent().siblings().removeClass('open');
        loadPage('index.php?v=d&p=dashboard');
    });

    $('#bt_gotoView').on('click',function(){
        $('ul.dropdown-menu [data-toggle=dropdown]').parent().parent().parent().siblings().removeClass('open');
        loadPage('index.php?v=d&p=view');
    });

    $('#bt_gotoPlan').on('click',function(){
        $('ul.dropdown-menu [data-toggle=dropdown]').parent().parent().parent().siblings().removeClass('open');
        loadPage('index.php?v=d&p=plan');
    });

    $('#bt_messageModal').on('click',function(){
        $('#md_modal').dialog({title: "{{Message Jeedom}}"});
        $('#md_modal').load('index.php?v=d&p=message&ajax=1').dialog('open');
    });

    $('body').on('click','.objectSummaryParent',function(){
       loadPage('index.php?v=d&p=dashboard&summary='+$(this).data('summary')+'&object_id='+$(this).data('object_id'));
   });

    initPage();
});

function initTextArea(){
    $('body').on('change keyup keydown paste cut', 'textarea.autogrow', function () {
        $(this).height(0).height(this.scrollHeight);
    });
}

function initCheckBox(){

}

function initPage(){
    initTableSorter();
    initExpertMode();
    initReportMode();
    $.initTableFilter();
    initRowOverflow();
    initHelp();
    initTextArea();
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
    if ($(window).width() < 1180) {
        $('.row-overflow > div').css('height', 'auto').css('overflow-y', 'initial').css('overflow-x', 'initial');
    } else {
        var hWindow = $(window).outerHeight() - $('header').outerHeight() - $('#div_alert').outerHeight()-4;
        if($('#div_alert').outerHeight() > 0){
            hWindow -= 10;
        }
        $('.row-overflow > div').height(hWindow).css('overflow-y', 'auto').css('overflow-x', 'hidden').css('padding-top','5px');
    }
}

function initExpertMode() {
    if ($('#bt_expertMode').attr('state') == 1) {
        $('.expertModeDisable').attr('disabled', false);
        $('.expertModeVisible').show();
        $('.expertModeHidden').hide();
    } else {
        $('.expertModeDisable').attr('disabled', true);
        $('.expertModeVisible').hide();
        $('.expertModeHidden').show();
    }
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
            $(this).append(' <sup><i class="fa fa-question-circle tooltips" title="'+$(this).attr('data-help')+'" style="font-size : 1em;color:grey;"></i></sup>');
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


function positionEqLogic(_id,_preResize) {
    if(_id != undefined){
        var eqLogic = $('.eqLogic-widget[data-eqlogic_id='+_id+']');
        eqLogic.css('margin','0px').css('padding','0px');
        if($(this).width() == 0){
            $(this).width('auto');
        }
        if($(this).height() == 0){
            $(this).height('auto');
        }
        if(init(_preResize,true)){
         eqLogic.width(Math.floor(eqLogic.width() / 40) * 40);
         eqLogic.height(Math.floor(eqLogic.height() / 80) * 80);
     }
     eqLogic.width(Math.ceil(eqLogic.width() / 40) * 40 + (Math.ceil(eqLogic.width() / 40)-1) * 2);
     eqLogic.height(Math.ceil(eqLogic.height() / 80) * 80 + (Math.ceil(eqLogic.height() / 80)-1) * 2);
 }else{
    $('.eqLogic-widget:not(.jeedomAlreadyPosition)').css('margin','0px').css('padding','0px');
    $('.eqLogic-widget:not(.jeedomAlreadyPosition)').each(function () {
        if($(this).width() == 0){
            $(this).width('auto');
        }
        if($(this).height() == 0){
            $(this).height('auto');
        }
        $(this).width(Math.ceil($(this).width() / 40) * 40 + (Math.ceil($(this).width() / 40)-1) * 2);
        $(this).height(Math.ceil($(this).height() / 80) * 80 + (Math.ceil($(this).height() / 80)-1) * 2);
    });
    $('.eqLogic-widget').addClass('jeedomAlreadyPosition');
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
