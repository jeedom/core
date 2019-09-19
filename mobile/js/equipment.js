
function initEquipment(_object_id) {
  $('#searchContainer').show();
  var objectMapping = {}
  var objects_info = {}

  jeedom.object.all({
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'})
    },
    success: function (objects) {
      var summaries = []
      var li = ' <ul data-role="listview" data-inset="false">'
      li += '<li><a href="#" class="link" data-page="equipment" data-title="<i class=\'fas fa-globe\'></i> {{Tout}}" data-option="all"><span><i class=\'fas fa-globe\'></i> {{Tout}}</a></li>'
      for (var i in objects) {
        if (objects[i].isVisible == 1) {
          var icon = ''
          if (isset(objects[i].display) && isset(objects[i].display.icon)) {
            icon = objects[i].display.icon
          }
          objects_info[objects[i].id] = objects[i]
          if (objects[i].father_id != '' && objects[i].father_id != null) {
            if (objects[i].father_id in objectMapping) {
              objectMapping[objects[i].father_id].push(parseInt(objects[i].id))
            } else {
              objectMapping[objects[i].father_id] = [parseInt(objects[i].id)]
            }
          }
          li += '<li><a href="#" class="link" data-page="equipment" data-title="' + icon.replace(/\"/g, "\'") + ' ' + objects[i].name + '" data-option="' + objects[i].id + '"><span>' + icon + '</span> ' + objects[i].name + ' <span class="summaryMenu"><span class="objectSummary'+objects[i].id+'" data-version="mobile"></span></span></a></li>'
          summaries.push({object_id : objects[i].id})
        }
      }
      li += '</ul>'
      panel(li)
      jeedom.object.summaryUpdate(summaries)
    }
  })

  if (isset(_object_id)) {
    jeedom.object.getImgPath({
      id : _object_id,
      success : function(_path){
        setBackgroundImage(_path)
      }
    })
    summary = ''
    var childList = [_object_id]
    if(_object_id.indexOf(':') != -1){
      temp = _object_id.split(':')
      _object_id = temp[0]
      summary = temp[1]
    }
    jeedom.object.toHtml({
      id: _object_id,
      version: 'mobile',
      summary: summary,
      error: function (error) {
        $('#div_alert').showAlert({message: error.message, level: 'danger'})
      },
      success: function (html) {
        if((_object_id == 'all' || _object_id == '')){
          var div = ''
          summaries = []
          for(var i in html) {
            if($.trim(html[i]) == ''){
              continue
            }
            var id = i.split('::')[1]
            if (!isset(objects_info[id])) {
              continue
            }
            div += '<div class="div_displayEquipement">'
            div += '<legend>'
            var icon = ''
            if (isset(objects_info[id].display) && isset(objects_info[id].display.icon)) {
              icon = objects_info[id].display.icon
            }
            objectName = objects_info[id].name
            objectName = objectName.charAt(0).toUpperCase() + objectName.slice(1)
            div += '<span>' + icon + '</span> ' + objectName
            div += '</legend>'
            div += '<div class="nd2-card objectSummaryHide" style="max-width:100% !important"><div class="card-title has-supporting-text"><center><span class="objectSummary'+id+'" data-version="mobile"></span></center></div></div>'
            div += '<div class="objectHtml">'
            div += html[i]
            div += '</div>'
            div += '</div>'
            summaries.push({object_id : id})
          }
          try {
            $('#div_displayEquipement').empty().html(div).trigger('create')
            jeedom.object.summaryUpdate(summaries)
          }catch(err) {
            console.log(err)
          }
        } else{
          $('#div_displayEquipement').empty().html('<div class="nd2-card objectSummaryHide" style="max-width:100% !important"><div class="card-title has-supporting-text"><center><span class="objectSummary'+_object_id+'" data-version="mobile"></span></center></div></div><div class="objectHtml">'+html+'</div></div>').trigger('create')
          jeedom.object.summaryUpdate([{object_id:_object_id}])
        }
        setTileSize('.eqLogic')
        setTileSize('.scenario')
        $('#div_displayEquipement .objectHtml').packery({gutter :0})
        setTimeout(function() {
          $('#div_displayEquipement .objectHtml').packery({gutter :0})
          }, 1000)
      }
    })
  } else {
    $('#bottompanel').panel('open')
  }

  $(window).on("resize", function (event) {
    deviceInfo = getDeviceType()
    setTileSize('.eqLogic')
    setTileSize('.scenario')
    $('#div_displayEquipement > .objectHtml').packery({gutter :0})
    $('.div_displayEquipement .objectHtml').packery({gutter :0})
  })

  $('#in_searchWidget').off('keyup').on('keyup',function() {
    window.scrollTo(0, 0)
    $('.div_displayEquipement').show()
    var search = $(this).value()
    if(search == ''){
      $('.eqLogic-widget').show()
      $('.scenario-widget').show()
      $('.objectHtml').packery()
      return
    }
    search = normTextLower(search)
    $('.eqLogic-widget').each(function() {
      var match = false
      if(match || normTextLower($(this).find('.widget-name').text()).indexOf(search) >= 0){
        match = true
      }
      if(match || ($(this).attr('data-tags') != undefined && normTextLower($(this).attr('data-tags')).indexOf(search) >= 0)){
        match = true
      }
      if(match ||($(this).attr('data-category') != undefined && normTextLower($(this).attr('data-category')).indexOf(search) >= 0)){
        match = true
      }
      if(match ||($(this).attr('data-eqType') != undefined && normTextLower($(this).attr('data-eqType')).indexOf(search) >= 0)){
        match = true
      }
      if(match ||($(this).attr('data-translate-category') != undefined && normTextLower($(this).attr('data-translate-category')).indexOf(search) >= 0)){
        match = true
      }
      if(match){
        $(this).show()
      }else{
        $(this).hide()
      }
    })
    $('.scenario-widget').each(function(){
      var match = false
      if(match || normTextLower($(this).find('.widget-name').text()).indexOf(search) >= 0){
        match = true
      }
      if(match){
        $(this).show()
      }else{
        $(this).hide()
      }
    })
    $('.objectHtml').packery()
    $('.objectHtml').each(function(){
      var count = $(this).find('.scenario-widget:visible').length + $(this).find('.eqLogic-widget:visible').length
      if(count == 0){
        $(this).closest('.div_displayEquipement').hide()
      }
    })
  })

  $('#bt_eraseSearchInput').off('click').on('click',function(){
    $('#in_searchWidget').val('').keyup()
  })

}
