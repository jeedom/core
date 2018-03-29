function initEquipment(_jeeObject_id) {
  jeedom.jeeObject.all({
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function (objects) {
      var summaries = [];
      var li = ' <ul data-role="listview" data-inset="false">';
      li += '<li><a href="#" class="link" data-page="equipment" data-title="<i class=\'fa fa-circle-o-notch\'></i> {{Tout}}" data-option="all"><span><i class=\'fa fa-circle-o-notch\'></i> {{Tout}}</a></li>';
      for (var i in objects) {
        if (objects[i].isVisible == 1) {
          var icon = '';
          if (isset(objects[i].display) && isset(objects[i].display.icon)) {
            icon = objects[i].display.icon;
          }
          li += '<li><a href="#" class="link" data-page="equipment" data-title="' + icon.replace(/\"/g, "\'") + ' ' + objects[i].name + '" data-option="' + objects[i].id + '"><span>' + icon + '</span> ' + objects[i].name + '<span style="float:right;font-size:0.6em;color:#787c84;"><span class="jeeObjectSummary'+objects[i].id+'" data-version="mobile"></span></span></a></li>';
          summaries.push({jeeObject_id : objects[i].id})
        }
      }
      li += '</ul>';
      panel(li);
      jeedom.jeeObject.summaryUpdate(summaries);
    }
  });
  if (isset(_jeeObject_id)) {
    summary = '';
    if(_jeeObject_id.indexOf(':') != -1){
      temp = _jeeObject_id.split(':');
      _jeeObject_id = temp[0];
      summary = temp[1];
    }
    jeedom.jeeObject.toHtml({
      id: _jeeObject_id,
      version: 'mobile',
      summary :summary,
      error: function (error) {
        $('#div_alert').showAlert({message: error.message, level: 'danger'});
      },
      success: function (html) {
        if(_jeeObject_id == 'all' || _jeeObject_id == ''){
         jeedom.jeeObject.all({
          error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
          },
          success: function (objects) {
           var div = '';
           var number = 0;
           summaries= [];
           for(var i in html){
            if($.trim(html[i]) == ''){
              continue;
            }
            var id = i.split('::')[1]
            div += '<div class="div_displayEquipement" style="margin-top : '+(number * 10 )+'px;">';
            div += '<legend style="margin : 0px;padding-bottom: 0px;">';
            for(var j in objects){
              if(objects[j].id == id){
               div += objects[j].name;
             }
           }
           div += '</legend>';
           div += '<div class="nd2-card jeeObjectSummaryHide" style="max-width:100% !important;"><div class="card-title has-supporting-text" style="padding:4px;font-size:0.6em;"><center><span class="jeeObjectSummary'+id+'" data-version="mobile"></span></center></div></div>';
           div += '<div class="jeeObjectHtml">';
           div += html[i]
           div += '</div>';
           div += '</div>';
           number = 1;
           summaries.push({jeeObject_id : id})
         }
         try {
           $('#div_displayEquipement').empty().html(div).trigger('create');
           jeedom.jeeObject.summaryUpdate(summaries)
         }catch(err) {
          console.log(err);
        }
        setTileSize('.eqLogic');
        setTimeout(function () {
          $('.div_displayEquipement .jeeObjectHtml').packery({gutter : 4});
        }, 10);
      }
    });  
       }else{
         $('#div_displayEquipement').empty().html('<div class="nd2-card jeeObjectSummaryHide" style="max-width:100% !important;"><div class="card-title has-supporting-text" style="padding:4px;font-size:0.6em;"><center><span class="jeeObjectSummary'+_jeeObject_id+'" data-version="mobile"></span></center></div></div><div class="jeeObjectHtml">'+html+'</div>').trigger('create');
         jeedom.jeeObject.summaryUpdate([{jeeObject_id:_jeeObject_id}]);
         setTileSize('.eqLogic');
         setTimeout(function () {
          $('#div_displayEquipement > .jeeObjectHtml').packery({gutter : 4});
        }, 10);
       }

     }
   });
  } else {
    $('#bottompanel').panel('open');
  }

  $(window).on("orientationchange", function (event) {
    deviceInfo = getDeviceType();
    setTileSize('.eqLogic');
    if(_jeeObject_id == 'all'){
      $('.div_displayEquipement > .jeeObjectHtml').packery({gutter : 4});
    }else{
     $('#div_displayEquipement > .jeeObjectHtml').packery({gutter : 4}); 
   }
 });
}