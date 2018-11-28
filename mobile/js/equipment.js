function initEquipment(_object_id) {
  var objectMapping = {};
  var objectName = {};
  jeedom.object.all({
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
          objectName[objects[i].id] = objects[i].name;
          if (objects[i].father_id != '' && objects[i].father_id != null) {
           if (objects[i].father_id in objectMapping) {
            objectMapping[objects[i].father_id].push(parseInt(objects[i].id));
          } else {
            objectMapping[objects[i].father_id] = [parseInt(objects[i].id)];
          }
        }
        li += '<li><a href="#" class="link" data-page="equipment" data-title="' + icon.replace(/\"/g, "\'") + ' ' + objects[i].name + '" data-option="' + objects[i].id + '"><span>' + icon + '</span> ' + objects[i].name + '<span style="float:right;font-size:0.6em;color:#787c84;"><span class="objectSummary'+objects[i].id+'" data-version="mobile"></span></span></a></li>';
        summaries.push({object_id : objects[i].id})
      }
    }
    li += '</ul>';
    panel(li);
    jeedom.object.summaryUpdate(summaries);
  }
});
  if (isset(_object_id)) {
    jeedom.object.getImgPath({
      id : _object_id,
      success : function(_path){
        setBackgroundImage(_path);
      }
    });
    summary = '';
    var childList = [_object_id];
    var initObject = '';
    if(_object_id.indexOf(':') != -1){
      temp = _object_id.split(':');
      _object_id = temp[0];
      summary = temp[1];
    }
    function findChildren(_object_id) {
      if (_object_id in objectMapping){
       initObject = _object_id;
       for (var children in objectMapping[_object_id]){
        var childId1 = parseInt(objectMapping[_object_id][children]);
        childList.push(childId1);
        findChildren(childId1);
      }
    }
  }
  if (_object_id in objectMapping){
    findChildren(_object_id);
    _object_id = JSON.stringify(childList);
  }

  jeedom.object.toHtml({
    id: _object_id,
    version: 'mobile',
    summary :summary,
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function (html) {
      if((_object_id == 'all' || _object_id == '' || initObject != '')){
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
        div += objectName[id];
        div += '</legend>';
        div += '<div class="nd2-card objectSummaryHide" style="max-width:100% !important;"><div class="card-title has-supporting-text" style="padding:4px;font-size:0.6em;"><center><span class="objectSummary'+id+'" data-version="mobile"></span></center></div></div>';
        div += '<div class="objectHtml">';
        div += html[i]
        div += '</div>';
        div += '</div>';
        number = 1;
        summaries.push({object_id : id})
      }
      try {
       $('#div_displayEquipement').empty().html(div).trigger('create');
       jeedom.object.summaryUpdate(summaries)
     }catch(err) {
      console.log(err);
    }
    setTileSize('.eqLogic');
    setTimeout(function () {
      $('.div_displayEquipement .objectHtml').packery({gutter : 4});
    }, 10); 
  } else{
   $('#div_displayEquipement').empty().html('<div class="nd2-card objectSummaryHide" style="max-width:100% !important;"><div class="card-title has-supporting-text" style="padding:4px;font-size:0.6em;"><center><span class="objectSummary'+_object_id+'" data-version="mobile"></span></center></div></div><div class="objectHtml">'+html+'</div></div>').trigger('create');
   jeedom.object.summaryUpdate([{object_id:_object_id}]);
   setTileSize('.eqLogic');
   setTimeout(function () {
    $('#div_displayEquipement > .objectHtml').packery({gutter : 4});
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
  if(_object_id == 'all'){
    $('.div_displayEquipement > .objectHtml').packery({gutter : 4});
  }else{
   $('#div_displayEquipement > .objectHtml').packery({gutter : 4}); 
 }
});

}

