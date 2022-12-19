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

"use strict"

if (!jeeFrontEnd.object) {
  jeeFrontEnd.object = {
    objectList: Array(),
    init: function() {
      window.jeeP = this
      this.getObjectList()
    },
    printObject: function(_id) {
      var objName = $('.objectListContainer .objectDisplayCard[data-object_id="' + _id + '"]').attr('data-object_name')
      var objIcon = $('.objectListContainer .objectDisplayCard[data-object_id="' + _id + '"]').attr('data-object_icon')
      this.loadObjectConfiguration(_id)
      $('.objectname_resume').empty().append(objIcon + '  ' + objName)
    },
    loadObjectConfiguration: function(_id) {
      try {
        $('#bt_uploadImage').fileupload('destroy')
        $('#bt_uploadImage').parent().html('<i class="fas fa-cloud-upload-alt"></i> {{Envoyer}}<input  id="bt_uploadImage" type="file" name="file" style="display: inline-block;">')
      } catch (error) {}

      $('#bt_uploadImage').fileupload({
        replaceFileInput: false,
        url: 'core/ajax/object.ajax.php?action=uploadImage&id=' + _id,
        dataType: 'json',
        done: function(e, data) {
          if (data.result.state != 'ok') {
            jeedomUtils.showAlert({
              message: data.result.result,
              level: 'danger'
            })
            return
          }
          if (isset(data.result.result.filepath)) {
            var filePath = data.result.result.filepath
            filePath = '/data/object/' + filePath.split('/data/object/')[1]
            $('.objectImg').show().find('img').attr('src', filePath)
          } else {
            $('.objectImg').hide()
          }
          jeedomUtils.showAlert({
            message: '{{Image de fond ajoutée avec succès}}',
            level: 'success'
          })
        }
      })

      $(".objectDisplayCard").removeClass('active')
      $('.objectDisplayCard[data-object_id=' + _id + ']').addClass('active')
      $('#div_conf').show()
      $('#div_resumeObjectList').hide()
      jeedom.object.byId({
        id: _id,
        cache: false,
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          document.querySelectorAll('.objectAttr').jeeValue('')
          $('.objectAttr[data-l1key=father_id] option').show()
          document.querySelectorAll('#summarytab input[type=checkbox]').jeeValue(0)
          if (!isset(data.configuration['info::type'])) {
            data.configuration['info::type'] = 'room'
          }
          document.querySelectorAll('#div_conf').setJeeValues(data, '.objectAttr')

          if (!isset(data.configuration.hideOnOverview)) {
            $('input[data-l2key="hideOnOverview"]').prop('checked', false)
          }
          if (!isset(data.configuration.useBackground)) {
            $('.objectAttr[data-l1key=configuration][data-l2key=useBackground]').prop('checked', false)
          }
          if (!isset(data.configuration.synthToAction)) {
            $('select[data-l2key="synthToView"], select[data-l2key="synthToPlan"], select[data-l2key="synthToPlan3d"]').parent().addClass('hidden')
            $('select[data-l2key="synthToAction"]').val('synthToDashboard')
          }

          if (!isset(data.configuration.useCustomColor) || data.configuration.useCustomColor == "0") {
            var bodyStyles = window.getComputedStyle(document.body)
            var objectBkgdColor = bodyStyles.getPropertyValue('--objectBkgd-color')
            var objectTxtColor = bodyStyles.getPropertyValue('--objectTxt-color')

            if (!objectBkgdColor === undefined) {
              objectBkgdColor = jeedomUtils.rgbToHex(objectBkgdColor)
            } else {
              objectBkgdColor = '#696969'
            }
            if (!objectTxtColor === undefined) {
              objectTxtColor = jeedomUtils.rgbToHex(objectTxtColor)
            } else {
              objectTxtColor = '#ebebeb'
            }

            document.querySelectorAll('.objectAttr[data-l1key=display][data-l2key=tagColor]').jeeValue(objectBkgdColor)
            document.querySelectorAll('.objectAttr[data-l1key=display][data-l2key=tagTextColor]').jeeValue(objectTxtColor)

            $('.objectAttr[data-l1key=display][data-l2key=tagColor]').click(function() {
              $('input[data-l2key="useCustomColor"').prop('checked', true)
            })
            $('.objectAttr[data-l1key=display][data-l2key=tagTextColor]').click(function() {
              $('input[data-l2key="useCustomColor"').prop('checked', true)
            })
          }

          $('.objectAttr[data-l1key=father_id] option[value=' + data.id + ']').hide()
          $('.div_summary').empty()
          $('.tabnumber').empty()

          if (isset(data.img) && data.img != '') {
            $('.objectImg').show().find('img').attr('src', data.img)
          } else {
            $('.objectImg').hide()
          }

          //set summary tab:
          if (isset(data.configuration) && isset(data.configuration.summary)) {
            var el
            var summary = data.configuration.summary
            for (var i in summary) {
              el = $('.type' + i)
              if (el != undefined) {
                for (var j in summary[i]) {
                  jeeP.addSummaryInfo('.type' + i, summary[i][j])
                }
                if (summary[i].length != 0) {
                  $('.summarytabnumber' + i).append('(' + summary[i].length + ')')
                }
              }
            }
          } else {
            var summary = {}
          }

          //set eqlogics tab:
          jeeP.addEqlogicsInfo(_id, data.name, summary)

          var hash = window.location.hash
          jeedomUtils.addOrUpdateUrl('id', data.id)
          if (hash == '') {
            $('.nav-tabs a[data-target="#objecttab"]').click()
          } else {
            window.location.hash = hash
          }
          jeeFrontEnd.modifyWithoutSave = false
          setTimeout(function() {
            jeeFrontEnd.modifyWithoutSave = false
          }, 500)
        }
      })
    },
    //-> summary tab
    addSummaryInfo: function(_selector, _summary) {
      if (!isset(_summary)) {
        _summary = {}
      }
      var div = '<div class="summary">'
      div += '<div class="form-group">'
      div += '<label class="col-sm-1 control-label">{{Commande}}</label>'
      div += '<div class="col-sm-4 has-success">'
      div += '<div class="input-group">'
      div += '<span class="input-group-btn">'
      div += '<input type="checkbox" class="summaryAttr roundedLeft" data-l1key="enable" checked title="{{Activer}}" />'
      div += '<a class="btn btn-default bt_removeSummary btn-sm"><i class="fas fa-minus-circle"></i></a>'
      div += '</span>'
      div += '<input class="summaryAttr form-control input-sm" data-l1key="cmd" />'
      div += '<span class="input-group-btn">'
      div += '<a class="btn btn-sm listCmdInfo btn-success roundedRight"><i class="fas fa-list-alt"></i></a>'
      div += '</span>'
      div += '</div>'
      div += '</div>'
      div += '<div class="col-sm-2 has-success">'
      div += '<label><input type="checkbox" class="summaryAttr" data-l1key="invert" />{{Inverser}}</label>'
      div += '</div>'
      div += '</div>'
      document.querySelector(_selector).querySelector('.div_summary').insertAdjacentHTML('beforeend', div)
      document.querySelector(_selector).querySelectorAll('.summary').last().setJeeValues(_summary, '.summaryAttr')
    },
    //-> eqLogics tab
    addEqlogicsInfo: function(_id, _objName, _summay) {
      $('#eqLogicsCmds').empty()
      jeedom.eqLogic.byObjectId({
        object_id: _id,
        onlyVisible: '0',
        onlyEnable: '0',
        getCmd: '1',
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(eqLogics) {
          var summarySelect = '<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">'
          summarySelect += '<i class="fas fa-tags"></i>&nbsp;&nbsp;{{Résumé(s)}}&nbsp;&nbsp;<span class="caret"></span>'
          summarySelect += '</button>'
          summarySelect += '<ul class="dropdown-menu" role="menu" style="top:unset;left:unset;height: 190px;overflow: auto;">'
          if (typeof jeephp2js.configObjSummary === 'object') {
            Object.keys(jeephp2js.configObjSummary).forEach(function(key) {
              summarySelect += '<li><a tabIndex="-1"><input type="checkbox" data-value="' + jeephp2js.configObjSummary[key].key + '" data-name="' + jeephp2js.configObjSummary[key].name + '" />&nbsp' + jeephp2js.configObjSummary[key].name + '</a></li>'
            })
          }
          summarySelect += '</ul>'

          var nbEqs = eqLogics.length
          var thisEq, thisId, thisEqName, panel, nbCmds, ndCmdsInfo, humanName
          for (var i = 0; i < nbEqs; i++) {
            thisEq = eqLogics[i]
            thisId = thisEq.id
            thisEqName = thisEq.name
            panel = '<div class="panel-group" style="margin-bottom:5px;">'
            panel += '<div class="panel panel-default">'
            panel += '<div class="panel-heading">'
            panel += '<h3 class="panel-title">'
            panel += '<a class="accordion-toggle" data-toggle="collapse" data-parent="" aria-expanded="false" href="#eqlogicId-' + thisId + '">' + thisEqName + '</a>'
            panel += '<span><a href="index.php?v=d&p=' + thisEq.eqType_name + '&m=' + thisEq.eqType_name + '&id=' + thisId + '" class="pull-right"><i class="fas fa-external-link-alt"></i></a></span>'
            panel += '</h3>'
            panel += '</div>'
            panel += '<div id="eqlogicId-' + thisId + '" class="panel-collapse collapse">'
            panel += '<div class="panel-body">'

            nbCmds = thisEq.cmds.length
            ndCmdsInfo = 0
            for (var j = 0; j < nbCmds; j++) {
              if (thisEq.cmds[j].type != 'info') continue

              ndCmdsInfo += 1
              humanName = '#[' + _objName + '][' + thisEqName + '][' + thisEq.cmds[j].name + ']#'
              panel += '<form class="form-horizontal">'
              panel += '<div class="form-group" data-cmdname="' + humanName + '">'
              panel += '<label class="col-sm-5 col-xs-6 control-label">' + humanName + '</label>'
              panel += '<div class="col-sm-2 col-xs-4">'
              panel += summarySelect
              panel += '</div>'
              panel += '<div class="col-sm-5 col-xs-2 buttontext"></div>'
              panel += '</div>'
              panel += '</form>'
            }
            panel += '</div>'
            panel += '</div>'
            panel += '</div>'

            if (ndCmdsInfo > 0) $('#eqLogicsCmds').append(panel)
          }

          //set select values:
          for (var i in _summay) {
            for (var j in _summay[i]) {
              jeeP.updateSummaryButton(_summay[i][j].cmd, i, true)
            }
          }
        }
      })
    },
    updateSummaryButton: function(_cmd, _key, _state) {
      var cmdDiv = $('#eqlogicsTab div[data-cmdname="' + _cmd + '"]')
      cmdDiv.find('ul input[data-value="' + _key + '"]').prop("checked", _state)
      var txtDiv = cmdDiv.find('.buttontext')
      var summaryName = jeephp2js.configObjSummary[_key].name
      if (_state) {
        //add new summary:
        cmdDiv.find('i').addClass('warning')
        if (txtDiv.text() == '') {
          txtDiv.text(summaryName)
        } else {
          txtDiv.text(txtDiv.text() + ' | ' + summaryName)
        }
      } else {
        //remove summary:
        var hasSummary = false
        var newText = ''
        txtDiv.text('')
        cmdDiv.find('ul input').each(function() {
          if ($(this).is(':checked')) {
            hasSummary = true
            if (newText == '') {
              newText = $(this).data('name')
            } else {
              newText += ' | ' + $(this).data('name')
            }
          }
        })

        if (hasSummary) {
          cmdDiv.find('i').addClass('warning')
          txtDiv.text(newText)
        } else {
          cmdDiv.find('i').removeClass('warning')
        }
      }
    },
    updateSummaryTabNbr: function(type) {
      var tab = $('.summarytabnumber' + type)
      var nbr = $('#summarytab' + type).find('.summary').length
      tab.empty()
      if (nbr > 0) tab.append('(' + nbr + ')')
    },
    //-> context menu functions
    reOrderChilds: function(_cardId) {
      $('div.objectDisplayCard[data-father_id="' + _cardId + '"]').each(function() {
        $('div.objectDisplayCard[data-object_id="' + _cardId + '"]').after($(this))
        var recId = $(this).attr('data-object_id')
        if ($('div.objectDisplayCard[data-father_id="' + recId + '"]')) jeeFrontEnd.object.reOrderChilds(recId)
      })
    },
    getObjectList: function(_reorder=false) {
      jeedom.object.all({
        onlyVisible: 0,
        noCache: true,
        error: function(error) {
          jeedomUtils.showAlert({message: error.message, level: 'danger'})
        },
        success: function(data) {
          jeeFrontEnd.object.objectList = Array()
          var decay
          data.forEach(function(object) {
            decay = object.configuration.parentNumber
            jeeFrontEnd.object.objectList.push({
              id: object.id,
              name: object.name,
              father_id: object.father_id,
              hideOnOverview: object.configuration.hideOnOverview,
              hideOnDashboard: object.configuration.hideOnDashboard,
              parentNumber: object.configuration.parentNumber,
              tag: '<span class="label" style="background-color:' + object.display.tagColor + ' ;color:' + object.display.tagTextColor + '">' + '\u00A0\u00A0\u00A0'.repeat(decay) + object.name + '</span>'
            })
          })

          if (_reorder) {
            var $objectContainer = $('#objectPanel .objectListContainer')
            data.forEach(function(object) {
              decay = parseInt(jeeFrontEnd.object.objectList.filter(x => x.id == object.id)[0].parentNumber)
              $objectContainer.find('.objectDisplayCard[data-object_id="' + object.id + '"] .name .hiddenAsCard').text('\u00A0\u00A0\u00A0'.repeat(decay))
              $objectContainer.append($objectContainer.find('.objectDisplayCard[data-object_id="' + object.id + '"]'))
            })
          }
        }
      })
    }
  }
}

jeeFrontEnd.object.init()

document.registerEvent('keydown', function(event) {
  if (jeedomUtils.getOpenedModal()) return

  if ((event.ctrlKey || event.metaKey) && event.which == 83) { //s
    event.preventDefault()
    if ($('#bt_saveObject').is(':visible')) {
      $("#bt_saveObject").click()
    }
  }
})

$('sub.itemsNumber').html('(' + $('.objectDisplayCard').length + ')')

//searching
$('#in_searchObject').keyup(function() {
  var search = this.value
  if (search == '') {
    $('.objectDisplayCard').show()
    return
  }
  search = jeedomUtils.normTextLower(search)
  var not = search.startsWith(":not(")
  if (not) {
    search = search.replace(':not(', '')
  }

  $('.objectDisplayCard').hide()
  var match, text
  $('.objectDisplayCard .name').each(function() {
    match = false
    text = jeedomUtils.normTextLower($(this).text())
    if (text.includes(search)) match = true

    if (not) match = !match
    if (match) {
      $(this).closest('.objectDisplayCard').show()
    }
  })
})
$('#bt_resetObjectSearch').on('click', function() {
  $('#in_searchObject').val('').keyup()
})

//Handle auto hide context menu
$('#div_pageContainer').on({
  'mouseleave': function(event) {
    $(this).fadeOut().trigger('contextmenu:hide')
  }
}, '.context-menu-root')

//context menu
try {
    $.contextMenu({
    selector: '.nav.nav-tabs',
    appendTo: 'div#div_pageContainer',
    build: function($trigger) {
      var thisObjectId = $('span.objectAttr[data-l1key="id"]').text()
      var contextmenuitems = {}
      var idx = 0
      for (var object of jeeP.objectList) {
        contextmenuitems[idx] = {
          'name': thisObjectId == object.id ? '\u3009' + object.tag : object.tag,
          'disabled': thisObjectId == object.id ? true : false,
          'id': object.id,
          'jType': 'after',
          'jId': object.id,
          'isHtmlName': true,
        }
        idx += 1
      }
      return {
        callback: function(key, options, event) {
          if (event.ctrlKey || event.metaKey || event.originalEvent.which == 2) {
            var url = 'index.php?v=d&p=object&id=' + options.commands[key].id
            if (window.location.hash != '') {
              url += window.location.hash
            }
            window.open(url).focus()
          } else {
            jeeP.printObject(options.commands[key].id)
          }
        },
        items: contextmenuitems
      }
    }
  })
  } catch (err) {}

//general context menu
try {
    $.contextMenu({
    selector: "#objectPanel .objectDisplayCard",
    appendTo: 'div#div_pageContainer',
    build: function($trigger) {
      var thisObjectId = $trigger.attr('data-object_id')
      var thisFatherId = $trigger.attr('data-father_id')
      var isActive = !$trigger.hasClass('inactive')

      //id, visible:
      var contextmenuitems = {}
      contextmenuitems['thisObjectId'] = {'name': 'id: ' + thisObjectId, 'id': 'thisObjectId', 'disabled': true}
      if (isActive) {
        contextmenuitems['hide'] = {'name': '{{Rendre invisible}}', 'id': 'hide', 'icon': 'fas fa-toggle-on'}
      } else {
        contextmenuitems['show'] = {'name': '{{Rendre visible}}', 'id': 'show', 'icon': 'fas fa-toggle-off'}
      }

      //configuration hideOnDashboard, hideOnOverview:
      var thisObjectFromList = jeeP.objectList.filter(x => x.id == thisObjectId)[0]
      if (thisObjectFromList.hideOnOverview == '0') {
        contextmenuitems['hideSynthesis'] = {'name': '{{Rendre invisible sur la Synthèse}}', 'id': 'hideSynthesis', 'icon': 'fas fa-toggle-on'}
      } else {
        contextmenuitems['showSynthesis'] = {'name': '{{Rendre visible sur la Synthèse}}', 'id': 'showSynthesis', 'icon': 'fas fa-toggle-off'}
      }
      if (thisObjectFromList.hideOnDashboard == '0') {
        contextmenuitems['hideDashboard'] = {'name': '{{Rendre invisible sur le Dashboard}}', 'id': 'hideDashboard', 'icon': 'fas fa-toggle-on'}
      } else {
        contextmenuitems['showDashboard'] = {'name': '{{Rendre visible sur le Dashboard}}', 'id': 'showDashboard', 'icon': 'fas fa-toggle-off'}
      }

      //parent submenu:
      var parentItems = {}
      parentItems[0] = {
        'name': '<span class="label labelObjectHuman">None</span>',
        'disabled': (thisFatherId == '') ? true : false,
        'jType': 'parent',
        'jId': '',
        'isHtmlName': true,
      }
      var idx = 1
      for (var object of jeeP.objectList) {
        parentItems[idx] = {
          'name': thisFatherId == object.id ? '\u3009' + object.tag : object.tag,
          'disabled': (thisObjectId == object.id) ? true : false,
          'jType': 'parent',
          'jId': object.id,
          'isHtmlName': true,
        }
        idx += 1
      }
      contextmenuitems['parents'] = {
        'name': '{{Objet parent}}',
        'items': parentItems
      }

      //position after submenu:
      var afterItems = {}
      for (var object of jeeP.objectList) {
        afterItems[idx] = {
          'name': object.tag,
          'disabled': (thisFatherId != object.father_id || thisObjectId == object.id) ? true : false,
          'jType': 'after',
          'jId': object.id,
          'isHtmlName': true,
        }
        idx += 1
      }
      contextmenuitems['after'] = {
        'name': '{{Positionner après}}',
        'items': afterItems
      }

      return {
        callback: function(key, options) {
          if (options.commands[key].jType == 'parent') {
            var objectId = options.commands[key].jId
            var object = {
              id: thisObjectId,
              father_id: objectId
            }
            jeedom.object.save({
              object: object,
              error: function(error) {
                jeedomUtils.showAlert({message: error.message, level: 'danger'})
              },
              success: function(data) {
                var $object = $('.objectDisplayCard[data-object_id="' + data.id + '"]')
                var span = '<span class="hiddenAsCard">' + '&nbsp;&nbsp;&nbsp;'.repeat(data.configuration.parentNumber) + '</span>' + data.name
                $object.find('.name').empty().append(span)
                $object.attr('data-father_id', objectId)
                jeeP.getObjectList(true)
              }
            })
            return true
          }

          if (options.commands[key].jType == 'after') {
            var objectId = options.commands[key].jId

            //move them so we can store them in right order and setOrder()
            var afterPosition = $('.objectDisplayCard[data-object_id="' + objectId + '"]').attr('data-position')
            $('.objectDisplayCard[data-position="' + afterPosition + '"]').after($('.objectDisplayCard[data-object_id="' + thisObjectId + '"]'))
             jeeP.reOrderChilds(thisObjectId)

            var objects = []
            $('div.objectDisplayCard').each(function() {
              objects.push($(this).attr('data-object_id'))
            })

            jeedom.object.setOrder({
              objects: objects,
              error: function(error) {
                jeedomUtils.showAlert({message: error.message, level: 'danger'})
              },
              success: function() {
                jeeP.getObjectList(true)
              }
            })
            return true
          }

          if (key == 'hide') {
            var object = {
              id: thisObjectId,
              isVisible: "0"
            }
            jeedom.object.save({
              object : object,
              error: function(error) {
                jeedomUtils.showAlert({message: error.message, level: 'danger'})
              },
              success: function(data) {
                $('.objectDisplayCard[data-object_id="' + data.id + '"]').addClass('inactive')
              }
            })
            return true
          }

          if (key == 'show') {
            var object = {
              id: thisObjectId,
              isVisible: "1"
            }
            jeedom.object.save({
              object : object,
              error: function(error) {
                jeedomUtils.showAlert({message: error.message, level: 'danger'})
              },
              success: function(data) {
                $('.objectDisplayCard[data-object_id="' + data.id + '"]').removeClass('inactive')
              }
            })
            return true
          }

          //hide on synthesis / dashboard:
          if (key == 'hideSynthesis') {
            var object = {
              id: thisObjectId,
              configuration: {hideOnOverview: "1"}
            }
            thisObjectFromList.hideOnOverview = "1"
          }

          if (key == 'showSynthesis') {
            var object = {
              id: thisObjectId,
              configuration: {hideOnOverview: "0"}
            }
            thisObjectFromList.hideOnOverview = "0"
          }

          if (key == 'hideDashboard') {
            var object = {
              id: thisObjectId,
              configuration: {hideOnDashboard: "1"}
            }
            thisObjectFromList.hideOnDashboard = "1"
          }

          if (key == 'showDashboard') {
            var object = {
              id: thisObjectId,
              configuration: {hideOnDashboard: "0"}
            }
            thisObjectFromList.hideOnDashboard = "0"
          }
          jeedom.object.save({
            object : object,
            error: function(error) {
              jeedomUtils.showAlert({message: error.message, level: 'danger'})
            },
            success: function(data) {}
          })
          return true
        },
        items: contextmenuitems
      }
    }
  })
  } catch (err) {}

$('#bt_graphObject').on('click', function() {
  $('#md_modal').dialog({
    title: "{{Graphique des liens}}"
  }).load('index.php?v=d&modal=graph.link&filter_type=object&filter_id=' + document.querySelector('.objectAttr[data-l1key="id"]').innerHTML).dialog('open')
})

$('#bt_libraryBackgroundImage').on('click', function() {
  jeedomUtils.chooseIcon(function(_icon) {
    $('.objectImg').show().find('img').replaceWith(_icon)
    $('.objectImg img').attr('width', '240px')
    jeedom.object.uploadImage({
      id: document.querySelector('.objectAttr[data-l1key="id"]').innerHTML,
      file: $('.objectImg img').data('filename'),
      error: function(error) {
        jeedomUtils.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function(data) {
        jeedomUtils.showAlert({
          message: '{{Image de fond appliquée avec succès}}',
          level: 'success'
        })
      }
    })
  }, {
    object_id: document.querySelector('.objectAttr[data-l1key="id"]').innerHTML
  })
})

$('#bt_removeBackgroundImage').off('click').on('click', function() {
  bootbox.confirm('{{Êtes-vous sûr de vouloir enlever l\'image de fond de cet objet ?}}', function(result) {
    if (result) {
      jeedom.object.removeImage({
        id: document.querySelector('.objectAttr[data-l1key="id"]').innerHTML,
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function() {
          $('.objectImg').hide()
          jeedomUtils.showAlert({
            message: '{{Image de fond enlevée}}',
            level: 'success'
          })
        },
      })
    }
  })
  return false
})

$('#bt_returnToThumbnailDisplay').on('click', function() {
  setTimeout(function() {
    $('.nav li.active').removeClass('active')
    $('a[href="#' + $('.tab-pane.active').attr('id') + '"]').closest('li').addClass('active')
  }, 500)
  if (jeedomUtils.checkPageModified()) return
  $('#div_conf').hide()
  $('#div_resumeObjectList').show()
  jeedomUtils.addOrUpdateUrl('id', null, '{{Objets}} - ' + JEEDOM_PRODUCT_NAME)
})

$(".objectDisplayCard").off('click').on('click', function(event) {
  if (event.ctrlKey || event.metaKey) {
    var url = '/index.php?v=d&p=object&id=' + $(this).attr('data-object_id')
    window.open(url).focus()
  } else {
    jeeP.printObject($(this).attr('data-object_id'))
  }
  return false
})
$('.objectDisplayCard').off('mouseup').on('mouseup', function(event) {
  if (event.which == 2) {
    event.preventDefault()
    var id = $(this).attr('data-object_id')
    $('.objectDisplayCard[data-object_id="' + id + '"]').trigger(jQuery.Event('click', {
      ctrlKey: true
    }))
  }
})

$('select[data-l2key="synthToAction"]').off().on('change', function() {
  $('select[data-l2key="synthToView"], select[data-l2key="synthToPlan"], select[data-l2key="synthToPlan3d"]').parent().addClass('hidden')
  $('select[data-l2key="' + $(this).val() + '"]').parent().removeClass('hidden')
})

$("#bt_addObject, #bt_addObject2").on('click', function(event) {
  bootbox.prompt("{{Nom du nouvel objet}} ?", function(result) {
    if (result !== null) {
      jeedom.object.save({
        object: {
          name: result,
          isVisible: 1
        },
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          jeeFrontEnd.modifyWithoutSave = false
          jeedomUtils.loadPage('index.php?v=d&p=object&id=' + data.id + '&saveSuccessFull=1')
        }
      })
    }
  })
})

$('.objectAttr[data-l1key=display][data-l2key=icon]').on('dblclick', function() {
  jeeFrontEnd.modifyWithoutSave = true
  this.innerHTML = ''
})

document.getElementById('bt_saveObject').addEventListener('click', function (event) {
  var object = document.querySelectorAll('.object').getJeeValues('.objectAttr')[0]
  if (!isset(object.configuration)) {
    object.configuration = {}
  }
  if (!isset(object.configuration.summary)) {
    object.configuration.summary = {}
  }

  var type, summaries, data
  document.querySelectorAll('#summarytab .div_summary').forEach(function(divSummary) {
    type = divSummary.getAttribute('data-type')
    object.configuration.summary[type] = []
    summaries = {}
    divSummary.querySelectorAll('#summarytab .summary').forEach(function(summary) {
      data = summary.getJeeValues('.summaryAttr')[0]
      object.configuration.summary[type].push(data)
    })
  })

  jeedom.object.save({
    object: object,
    error: function(error) {
      jeedomUtils.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(data) {
      jeeFrontEnd.modifyWithoutSave = false
      var url = 'index.php?v=d&p=object&id=' + data.id + '&saveSuccessFull=1'
      if (window.location.hash != '') {
        url += window.location.hash
      }
      jeedomUtils.loadPage(url)
    }
  })
  return false
})

$("#bt_removeObject").on('click', function(event) {
  jeedomUtils.hideAlert()
  bootbox.confirm('{{Êtes-vous sûr de vouloir supprimer l\'objet}} <span style="font-weight: bold ;">' + $('.objectDisplayCard.active .name').text().trim() + '</span> ?', function(result) {
    if (result) {
      var removeId = $('.objectDisplayCard.active').attr('data-object_id')
      jeedom.object.remove({
        id: removeId,
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function() {
          jeeFrontEnd.modifyWithoutSave = false
          $('#bt_gotoDashboard').siblings('ul').find('li').each(function() {
            var href = $(this).find('a').attr('href')
            if (href.includes('dashboard&object_id=' + removeId)) {
              $(this).remove()
            }
          })
          jeedomUtils.loadPage('index.php?v=d&p=object&removeSuccessFull=1')
        }
      })
    }
  })
  return false
})

$('#bt_chooseIcon').on('click', function() {
  var _icon = false
  var icon = false
  var color = false
  if ($('div[data-l2key="icon"] > i').length) {
    color = '';
    var class_icon = $('div[data-l2key="icon"] > i').attr('class')
    class_icon = class_icon.replace(' ', '.').split(' ')
    icon = '.' + class_icon[0]
    if (class_icon[1]) {
      color = class_icon[1]
    }
  }
  jeedomUtils.chooseIcon(function(_icon) {
    jeeFrontEnd.modifyWithoutSave = true
    $('.objectAttr[data-l1key=display][data-l2key=icon]').empty().append(_icon)
  }, {
    icon: icon,
    color: color
  })
})

$('#div_pageContainer').off('change', '.objectAttr').on('change', '.objectAttr:visible', function() {
  jeeFrontEnd.modifyWithoutSave = true
});

$('.addSummary').on('click', function() {
  var type = $(this).attr('data-type')
  jeeP.addSummaryInfo('.type' + type)
  jeeFrontEnd.modifyWithoutSave = true
})

$('.bt_checkAll').on('click', function() {
  $(this).closest('tr').find('input[type="checkbox"]').each(function() {
    $(this).prop("checked", true)
  })
})

$('.bt_checkNone').on('click', function() {
  $(this).closest('tr').find('input[type="checkbox"]').each(function() {
    $(this).prop("checked", false)
  })
})

//cmd info modal autoselect object:
$('#div_pageContainer').on({
  'click': function(event) {
    var el = this.closest('.summary').querySelector('.summaryAttr[data-l1key="cmd"]')
    var type = this.closest('.div_summary').dataset.type
    jeedom.cmd.getSelectModal({
      object: {
        id: document.querySelector('.objectAttr[data-l1key="id"]').innerHTML
      },
      cmd: {
        type: 'info'
      }
    }, function(result) {
      el.jeeValue(result.human)
      jeeP.updateSummaryTabNbr(type)
      jeeP.updateSummaryButton(result.human, type, true)
      jeeFrontEnd.modifyWithoutSave = true
    })
    $('body').trigger('mod_insertCmdValue_Visible')
  }
}, '.listCmdInfo')

$('#div_pageContainer').on({
  'click': function(event) {
    var cmd = $(this).closest('.summary').find('input[data-l1key="cmd"]').val()
    var type = $(this).closest('.div_summary').data('type')
    $(this).closest('.summary').remove()
    jeeP.updateSummaryTabNbr(type)
    jeeP.updateSummaryButton(cmd, type, false)
  }
}, '.bt_removeSummary')

//populate summary tab:
$('.bt_showObjectSummary').off('click').on('click', function() {
  $('#md_modal').dialog({
    title: "{{Vue d'ensemble des objets}}"
  }).load('index.php?v=d&modal=object.summary').dialog('open')
})

//eqLogics tab searching
$('#in_searchCmds').keyup(function() {
  var search = this.value
  if (search == '') {
    $('#eqLogicsCmds .panel-collapse.in').closest('.panel').find('.accordion-toggle').click()
    return
  }
  search = jeedomUtils.normTextLower(search)
  $('#eqLogicsCmds .panel-collapse').attr('data-show', 0)
  var text
  $('#eqLogicsCmds .form-group').each(function() {
    text = jeedomUtils.normTextLower($(this).attr('data-cmdname'))
    if (text.indexOf(search) >= 0) {
      $(this).closest('.panel-collapse').attr('data-show', 1)
    }
  })
  $('#eqLogicsCmds .panel-collapse[data-show=1]').collapse('show')
  $('#eqLogicsCmds .panel-collapse[data-show=0]').collapse('hide')
})
$('#bt_openAll').off('click').on('click', function() {
  $(".accordion-toggle[aria-expanded='false']").each(function() {
    $(this).click()
  })
})
$('#bt_closeAll').off('click').on('click', function() {
  $(".accordion-toggle[aria-expanded='true']").each(function() {
    $(this).click()
  })
})
$('#bt_resetCmdSearch').on('click', function() {
  $('#in_searchCmds').val('').keyup()
})


//sync eqLogic cmd -> summaryInfo
$('#eqlogicsTab').on({
  'click': function(event){
    if (event.target.type == 'checkbox') return
    var checkbox = $(this).find('input[type="checkbox"]')
    checkbox.prop("checked", !checkbox.prop("checked")).change()
    event.stopPropagation()
  }
}, 'ul.dropdown-menu a')


$('#eqlogicsTab').on({
  'change': function(event) {
    var type = $(this).data('value')
    var cmd = $(this).closest('.form-group').data('cmdname')
    var state = $(this).is(':checked')
    jeeP.updateSummaryButton(cmd, type, state)
    jeeFrontEnd.modifyWithoutSave = true

    var el = $('.type' + type)
    var summary = {
      cmd: cmd,
      enable: "1",
      invert: "0"
    }
    if (el != undefined) {
      if (state) {
        jeeP.addSummaryInfo('.type' + type, summary)
      } else {
        el.find('input[data-l1key="cmd"]').each(function() {
          if ($(this).val() == cmd) {
            $(this).closest('.summary').remove()
          }
        })
      }
      jeeP.updateSummaryTabNbr(type)
    }
  }
}, 'ul.dropdown-menu input[type="checkbox"]')

if (is_numeric(jeephp2js.selectId)) {
  if ($('.objectDisplayCard[data-object_id=' + jeephp2js.selectId + ']').length != 0) {
    $('.objectDisplayCard[data-object_id=' + jeephp2js.selectId + ']').click()
  }
}