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
$('.nav-tabs a').on('shown.bs.tab', function (e) {
  window.location.hash = e.target.hash;
})

jwerty.key('ctrl+s/⌘+s', function (e) {
  e.preventDefault();
  $("#bt_saveWidgets").click();
});

if (getUrlVars('saveSuccessFull') == 1) {
  $('#div_alert').showAlert({message: '{{Sauvegarde effectuée avec succès}}', level: 'success'});
}

if (getUrlVars('removeSuccessFull') == 1) {
  $('#div_alert').showAlert({message: '{{Suppression effectuée avec succès}}', level: 'success'});
}

$('.widgetsAttr[data-l1key=type]').off('change').on('change',function(){
  $('.widgetsAttr[data-l1key=subtype] option').hide();
  $('.widgetsAttr[data-l1key=subtype] option[data-type='+$(this).value()+']').show();
});

$('.widgetsAttr[data-l1key=subtype]').off('change').on('change',function(){
  $('.widgetsAttr[data-l1key=template] option').hide();
  $('.widgetsAttr[data-l1key=template] option[data-type='+$('.widgetsAttr[data-l1key=type]').value()+'][data-subtype='+$(this).value()+']').show();
});

setTimeout(function(){
  $('.widgetsListContainer').packery();
},100);

$('#bt_returnToThumbnailDisplay').on('click',function(){
  $('#div_conf').hide();
  $('#div_widgetsList').show();
  $('.widgetsListContainer').packery();
});

$('#in_searchWidgets').keyup(function () {
  var search = $(this).value();
  if(search == ''){
    $('.widgetsDisplayCard').show();
    $('.widgetsListContainer').packery();
    return;
  }
  $('.widgetsDisplayCard').hide();
  $('.widgetsDisplayCard .name').each(function(){
    var text = $(this).text().toLowerCase();
    if(text.indexOf(search.toLowerCase()) >= 0){
      $(this).closest('.widgetsDisplayCard').show();
    }
  });
  $('.widgetsListContainer').packery();
});

$("#bt_addWidgets").on('click', function (event) {
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

$(".widgetsDisplayCard").on('click', function (event) {
  $('#div_conf').show();
  $('#div_widgetsList').hide();
  jeedom.widgets.byId({
    id: $(this).attr('data-widgets_id'),
    cache: false,
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function (data) {
      $('.widgetsAttr').value('');
      $('.widgets').setValues(data, '.widgetsAttr');
      modifyWithoutSave = false;
    }
  });
});

if (is_numeric(getUrlVars('id'))) {
  if ($('.widgetsDisplayCard[data-widgets_id=' + getUrlVars('id') + ']').length != 0) {
    $('.widgetsDisplayCard[data-widgets_id=' + getUrlVars('id') + ']').click();
  } else {
    $('.widgetsDisplayCard:first').click();
  }
}

$("#bt_saveWidgets").on('click', function (event) {
  var widgets = $('.widgets').getValues('.widgetsAttr')[0];
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
