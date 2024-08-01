"use strict"

function initEquipment(_object_id) {
  document.getElementById('searchContainer').seen();
  let objectMapping = {}
  let objects_info = {}
  let objectsAll = {}

  if (isset(_object_id)) {
    if (_object_id == '') _object_id == 'all'
    var summary = ''
    if (_object_id.indexOf(':') != -1) {
      let temp = _object_id.split(':')
      _object_id = temp[0]
      summary = temp[1]
    }
  }

  //set hamburger panel:
  jeedomUtils.showLoading()
  jeedom.object.all({
    global: false,
    error: function(error) {
      $.fn.showAlert({message: error.message, level: 'danger'})
    },
    success: function(objects) {
      if (_object_id == '') {
        objectsAll = objects
      }

      let summaries = []
      let li = '<ul data-role="listview" data-inset="false">'
      li += '<li class="li-splitter">'
      li += '<div>'
      li += '<a href="#" class="link" data-page="equipment" data-title="<i class=\'fas fa-globe\'></i> {{Tout}}" data-option="all"><i class="fas fa-globe"> </i> {{Tout}}</a>'
      li += '<a href="#" class="link" data-page="overview" data-title="<i class=\'fab fa-hubspot\'></i> {{Synthèse}}" style="float: right;margin: 0;padding: 0 !important;"><i class="fab fa-hubspot"> </i> {{Synthèse}}</a>'
      li += '</div>'
      li += '</li>'
      for (let i in objects) {
        if (_object_id != '' && _object_id == objects[i].id) objectsAll = [objects[i]]
        if (objects[i].isVisible == 1) {
          let icon = ''
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
          let decay = 0
          if (isset(objects[i].configuration) && isset(objects[i].configuration.parentNumber)) {
            decay = objects[i].configuration.parentNumber
          }
          let name = (objects[i].configuration.display_name && objects[i].configuration.display_name != '') ? objects[i].configuration.display_name : objects[i].name;
          li += '<li><a href="#" class="link" data-page="equipment" data-title="' + icon.replace(/\"/g, "\'") + ' ' + objects[i].name + '" data-option="' + objects[i].id + '">'
          li += '<span>' + '&nbsp;&nbsp;'.repeat(decay) + icon + '</span> ' + name
          li += ' <span class="summaryMenu"><span class="objectSummaryContainer objectSummary'+objects[i].id+'" data-version="mobile"></span></span></a></li>'
          summaries.push({object_id : objects[i].id})
        }
      }
      li += '</ul>'
      jeedomUtils.loadPanel(li)
      jeedom.object.summaryUpdate(summaries)
    }
  })

  if (isset(_object_id)) {
    jeedom.object.getImgPath({
      id: _object_id,
      success : function(_path) {
        jeedomUtils.setBackgroundImage(_path)
      }
    })
    if (summary != '') {
      displayObjectsBySummary(objectsAll, summary)
    } else {
      displayEqsByObject(objects_info, _object_id, summary)
    }
  } else {
    $('#bottompanel').panel('open')
  }

  $('body').on('orientationChanged', function(event, _orientation) {
    jeedomUtils.userDevice = getDeviceType()
    jeedomUtils.setTileSize('.eqLogic, .scenario')
    document.querySelectorAll('#div_displayEquipement > .objectHtml, .div_displayEquipement .objectHtml').forEach(_div => { Packery.data(_div).layout() })
  })

  $('#in_searchDashboard').off('keyup').on('keyup',function() {
    window.scrollTo(0, 0)
    $('.div_displayEquipement').show()
    var search = this.value
    if(search == '') {
      $('div.eqLogic-widget, div.scenario-widget').show()
      document.querySelectorAll('.objectHtml').forEach(_div => { Packery.data(_div).layout() })
      return
    }
    search = jeedomUtils.normTextLower(search)
    $('div.eqLogic-widget').each(function() {
      let match = false
      if (match || jeedomUtils.normTextLower($(this).find('.widget-name').text()).indexOf(search) >= 0) {
        match = true
      }
      if (match || ($(this).attr('data-tags') != undefined && jeedomUtils.normTextLower($(this).attr('data-tags')).indexOf(search) >= 0)) {
        match = true
      }
      if (match ||($(this).attr('data-category') != undefined && jeedomUtils.normTextLower($(this).attr('data-category')).indexOf(search) >= 0)) {
        match = true
      }
      if (match ||($(this).attr('data-eqType') != undefined && jeedomUtils.normTextLower($(this).attr('data-eqType')).indexOf(search) >= 0)) {
        match = true
      }
      if (match ||($(this).attr('data-translate-category') != undefined && jeedomUtils.normTextLower($(this).attr('data-translate-category')).indexOf(search) >= 0)) {
        match = true
      }
      if (match) {
        $(this).show()
      } else {
        $(this).hide()
      }
    })
    $('.scenario-widget').each(function() {
      let match = false
      if (match || jeedomUtils.normTextLower($(this).find('.widget-name').text()).indexOf(search) >= 0) {
        match = true
      }
      if (match) {
        $(this).show()
      } else {
        $(this).hide()
      }
    })
    document.querySelectorAll('.objectHtml').forEach(_div => { Packery.data(_div).layout() })
    $('.objectHtml').each(function() {
      let count = $(this).find('.scenario-widget:visible').length + $(this).find('.eqLogic-widget:visible').length
      if (count == 0) {
        $(this).closest('.div_displayEquipement').hide()
      }
    })
  })

  $('#bt_eraseSearchInput').off('click').on('click',function() {
    $('#in_searchDashboard').val('').keyup()
  })
}

//objects:
function displayEqsByObject(objects_info, _objectId, _summary) {
  jeedomUtils.showLoading()
  jeedom.object.toHtml({
    id: _objectId,
    version: 'mobile',
    summary: _summary,
    global: false,
    error: function(error) {
      $.fn.showAlert({message: error.message, level: 'danger'})
    },
    success: function(html) {
      if (_objectId == 'all' || _objectId == '') {
        document.getElementById('div_displayEquipement').empty()
        let div = ''
        let summaries = []
        let id, icon, objectName
        for (var i in html) {
          id = i.split('::')[1]
          if (!isset(objects_info[id])) continue
          div += '<div class="div_displayEquipement" data-objectid="'+id+'">'
          div += '<legend>'
          icon = ''
          if (isset(objects_info[id].display) && isset(objects_info[id].display.icon)) {
            icon = objects_info[id].display.icon
          }
          objectName = objects_info[id].name
          objectName = objectName.charAt(0).toUpperCase() + objectName.slice(1)
          div += '<span>' + icon + '</span> ' + objectName
          div += '</legend>'
          div += '<div class="nd2-card" style="max-width:100% !important"><div class="card-title has-supporting-text"><center><span class="objectSummaryContainer objectSummary'+id+'" data-version="mobile"></span></center></div></div>'
          div += '<div class="objectHtml">'
          div += html[i]
          div += '</div></div></div>'
          summaries.push({object_id : id})
        }
        try {
          document.getElementById('div_displayEquipement').html(div)
          $('#div_displayEquipement').trigger('create')
          jeedomUtils.setTileSize('.eqLogic, .scenario')
          jeedom.object.summaryUpdate(summaries)
        } catch(err) {
          console.log(err)
        }
      } else {
        let div = '<div class="div_displayEquipement" data-objectid="'+_objectId+'">'
        div += '<div class="nd2-card" style="max-width:100% !important">'
        div += '<div class="card-title has-supporting-text"><center><span class="objectSummaryContainer objectSummary'+_objectId+'" data-version="mobile"></span></center></div></div><div class="objectHtml">'
        div += html
        div += '</div></div></div>'
        document.getElementById('div_displayEquipement').empty().html(div)
        $('#div_displayEquipement').trigger('create')
        jeedomUtils.setTileSize('.eqLogic, .scenario')
        jeedom.object.summaryUpdate([{object_id:_objectId}])
      }
      let divObject = document.querySelector('#div_displayEquipement .objectHtml')
      new Packery(divObject, {gutter :0})
    },
    complete: function() {
      jeedomUtils.hideLoading()
      window.triggerEvent('resize')
    }
  })
}

//summary:
function displayObjectsBySummary(_objectsAll, _summary) {
  document.getElementById('div_displayEquipement').empty()
  //show objects hidden:
  for (let i in _objectsAll) {
    let thisObject = _objectsAll[i]
    let summaries = []
    let  div = '<div class="div_displayEquipement hidden" data-objectid="' + thisObject.id + '">'
    div += '<legend>'
    let icon = ''
    if (isset(thisObject.display) && isset(thisObject.display.icon)) {
      icon = thisObject.display.icon
    }
    let objectName = thisObject.name
    objectName = objectName.charAt(0).toUpperCase() + objectName.slice(1)
    div += '<span>' + icon + '</span> ' + objectName
    div += '</legend>'
    div += '<div class="nd2-card" style="max-width:100% !important"><div class="card-title has-supporting-text"><center><span class="objectSummaryContainer objectSummary'+thisObject.id+'" data-version="mobile"></span></center></div></div>'
    div += '<div class="objectHtml">'
    div += '</div>'
    div += '</div>'
    document.getElementById('div_displayEquipement').html(div, true)
    displayEqsBySummary(_objectsAll, thisObject.id, _summary)
    jeedom.object.summaryUpdate([{object_id: thisObject.id}])
  }
  document.getElementById('div_displayEquipement').triggerEvent('create')
  window.triggerEvent('resize')
}

var summaryObjEqs = []
function displayEqsBySummary(_objectsAll, _objectId, _summary) {
  summaryObjEqs[_objectId] = []
  jeedom.object.getEqLogicsFromSummary({
    id: _objectId,
    summary: _summary,
    version: 'mobile',
    onlyEnable: '1',
    onlyVisible: '0',
    error: function(error) {
      $.fn.showAlert({message: error.message, level: 'danger'})
    },
    success: function(eqLogics) {
      if (eqLogics.length == 0) return
      for (let j in eqLogics) {
        if (summaryObjEqs[_objectId].includes(eqLogics[j].id)) {
          return
        }
        summaryObjEqs[_objectId].push(eqLogics[j].id)
        jeedom.eqLogic.toHtml({
          id: eqLogics[j].id,
          version: 'mobile',
          error: function(error) {
            $.fn.showAlert({message: error.message, level: 'danger'})
          },
          success: function(html) {
            document.querySelector('.div_displayEquipement[data-objectid="'+_objectId+'"]').removeClass('hidden')
            document.querySelectorAll('.div_displayEquipement[data-objectid="'+_objectId+'"] > .objectHtml').forEach(function(element) {
              element.html(html.html, true)
            })
            jeedomUtils.setTileSize('.eqLogic')

            $('.div_displayEquipement[data-objectid="'+_objectId+'"]').trigger('create')
            let divObject = document.querySelector('.div_displayEquipement[data-objectid="'+_objectId+'"]')
            new Packery(divObject, {gutter :0})
          }
        })
      }
    },
    complete: function() {
      jeedomUtils.hideLoading()
      window.triggerEvent('resize')
    }
  })
}
