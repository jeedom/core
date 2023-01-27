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
      if (is_numeric(getUrlVars('id'))) {
        this.printObject(getUrlVars('id'))
      }
      document.querySelector('sub.itemsNumber').innerHTML = '(' + document.querySelectorAll('.objectDisplayCard').length + ')'
    },
    printObject: function(_id) {
      this.loadObjectConfiguration(_id)
    },
    loadObjectConfiguration: function(_id) {
      try {
        jeeFrontEnd.object.bckUploader.destroy()
      } catch (error) {}
      jeeFrontEnd.object.bckUploader = new jeeFileUploader({
        fileInput: document.getElementById('bt_uploadImage'),
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
            document.querySelector('.objectImg').seen().querySelector('img').src = filePath
          } else {
            document.querySelector('.objectImg').unseen()
          }
          jeedomUtils.showAlert({
            message: '{{Image de fond ajoutée avec succès}}',
            level: 'success'
          })
        }
      })

      document.querySelectorAll('.objectDisplayCard').removeClass('active')
      document.querySelector('.objectDisplayCard[data-object_id="' + _id + '"]')?.addClass('active')
      document.getElementById('div_resumeObjectList').unseen()
      document.getElementById('div_conf').seen()
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

          document.querySelectorAll('.objectAttr[data-l1key="father_id"] option').seen()
          document.querySelectorAll('#summarytab input[type="checkbox"]').jeeValue(0)
          if (!isset(data.configuration['info::type'])) {
            data.configuration['info::type'] = 'room'
          }
          document.querySelectorAll('#div_conf').setJeeValues(data, '.objectAttr')

          if (!isset(data.configuration.hideOnOverview)) {
            document.querySelector('input[data-l2key="hideOnOverview"]').checked = false
          }
          if (!isset(data.configuration.useBackground)) {
            document.querySelector('.objectAttr[data-l1key="configuration"][data-l2key="useBackground"]').checked = false
          }
          if (!isset(data.configuration.synthToAction)) {
            document.querySelectorAll('select[data-l2key="synthToView"], select[data-l2key="synthToPlan"], select[data-l2key="synthToPlan3d"]').forEach(_select => {
              _select.parentNode.addClass('hidden')
            })
            document.querySelector('select[data-l2key="synthToAction"]').value = 'synthToDashboard'
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

            $('.objectAttr[data-l1key="display"][data-l2key="tagColor"]').click(function() {
              document.querySelector('input[data-l2key="useCustomColor"').checked = true
            })
            $('.objectAttr[data-l1key="display"][data-l2key="tagTextColor"]').click(function() {
              document.querySelector('input[data-l2key="useCustomColor"').checked = true
            })
          }

          document.querySelector('.objectAttr[data-l1key="father_id"] option[value="' + data.id + '"]').unseen()
          document.querySelectorAll('.div_summary').empty()
          document.querySelectorAll('.tabnumber').empty()

          if (isset(data.img) && data.img != '') {
            document.querySelector('.objectImg').seen().querySelector('img').src = data.img
          } else {
            document.querySelector('.objectImg').unseen()
          }

          //set summary tab:
          if (isset(data.configuration) && isset(data.configuration.summary)) {
            var el
            var summary = data.configuration.summary
            for (var i in summary) {
              el = document.querySelector('.type' + i)
              if (el != null) {
                for (var j in summary[i]) {
                  jeeP.addSummaryInfo('.type' + i, summary[i][j])
                }
                if (summary[i].length != 0) {
                  document.querySelector('.summarytabnumber' + i).append('(' + summary[i].length + ')')
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
            document.querySelector('.nav-tabs a[data-target="#objecttab"]')?.click()
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
      div += '<label class="col-xs-1 col-sm-1 control-label hidden-xs">{{Commande}}</label>'
      div += '<div class="col-xs-12 col-sm-4 has-success">'
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
      div += '<div class="col-xs-10 col-xs-offset-2 col-sm-2 has-success">'
      div += '<label><input type="checkbox" class="summaryAttr" data-l1key="invert" />{{Inverser}}</label>'
      div += '</div>'
      div += '</div>'
      document.querySelector(_selector).querySelector('.div_summary').insertAdjacentHTML('beforeend', div)
      document.querySelector(_selector).querySelectorAll('.summary').last().setJeeValues(_summary, '.summaryAttr')
    },
    //-> eqLogics tab
    addEqlogicsInfo: function(_id, _objName, _summay) {
      document.getElementById('eqLogicsCmds').empty()
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
      var cmdDiv = document.querySelector('#eqLogicsCmds div[data-cmdname="' + _cmd + '"]')
      if (cmdDiv == null) return //cmd is not from this object!
      cmdDiv.querySelector('ul input[data-value="' + _key + '"]').checked = _state
      var txtDiv = cmdDiv.querySelector('.buttontext')
      var summaryName = jeephp2js.configObjSummary[_key].name
      if (_state) {
        //add new summary:
        cmdDiv.querySelector('i').addClass('warning')
        if (txtDiv.textContent == '') {
          txtDiv.textContent = summaryName
        } else {
          txtDiv.textContent = txtDiv.textContent + ' | ' + summaryName
        }
      } else {
        //remove summary:
        var hasSummary = false
        var newText = ''
        txtDiv.textContent = ''
        cmdDiv.querySelectorAll('ul input').forEach(_check => {
          if (_check.checked) {
            hasSummary = true
            if (newText == '') {
              newText = _check.getAttribute('data-name')
            } else {
              newText += ' | ' + _check.getAttribute('data-name')
            }
          }
        })

        if (hasSummary) {
          cmdDiv.querySelector('i').addClass('warning')
          txtDiv.textContent = newText
        } else {
          cmdDiv.querySelector('i').removeClass('warning')
        }
      }
    },
    updateSummaryTabNbr: function(type) {
      var tab = document.querySelector('.summarytabnumber' + type)
      var nbr = document.querySelector('#summarytab' + type).querySelectorAll('.summary').length
      tab.empty()
      if (nbr > 0) tab.append('(' + nbr + ')')
    },
    //-> context menu functions
    reOrderChilds: function(_cardId) {
      document.querySelectorAll('div.objectDisplayCard[data-father_id="' + _cardId + '"]').forEach(_card => {
        _card.parentNode.insertBefore(_card, _card.nextSibling)
        var recId = _card.getAttribute('data-object_id')
        if (document.querySelector('div.objectDisplayCard[data-father_id="' + recId + '"]') != null) jeeFrontEnd.object.reOrderChilds(recId)
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
    },
    saveObject: function() {
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
    },
  }
}

jeeFrontEnd.object.init()

//context menu
try {
  new jeeCtxMenu({
    selector: '.nav.nav-tabs',
    appendTo: 'div#div_pageContainer',
    build: function(trigger) {
      var thisObjectId = document.querySelector('span.objectAttr[data-l1key="id"]').textContent
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
          if (event.ctrlKey || event.metaKey || event.which == 2) {
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
  new jeeCtxMenu({
    selector: "#objectPanel .objectDisplayCard",
    appendTo: 'div#div_pageContainer',
    build: function(trigger) {
      var thisObjectId = trigger.getAttribute('data-object_id')
      var thisFatherId = trigger.getAttribute('data-father_id')
      var thisName = trigger.getAttribute('data-object_name')
      var isActive = !trigger.hasClass('inactive')

      //id, visible:
      var contextmenuitems = {}
      contextmenuitems['thisObjectId'] = {'name': thisName + ' (id: ' + thisObjectId + ')', 'id': 'thisObjectId', 'disabled': true}
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

//searching
document.getElementById('in_searchObject')?.addEventListener('keyup', function(event) {
  var search = event.target.value
  if (search == '') {
    document.querySelectorAll('.objectDisplayCard').seen()
    return
  }
  search = jeedomUtils.normTextLower(search)
  var not = search.startsWith(":not(")
  if (not) {
    search = search.replace(':not(', '')
  }

  document.querySelectorAll('.objectDisplayCard').unseen()
  var match, text
  document.querySelectorAll('.objectDisplayCard .name').forEach(_name => {
    match = false
    text = jeedomUtils.normTextLower(_name.textContent)
    if (text.includes(search)) match = true

    if (not) match = !match
    if (match) {
      _name.closest('.objectDisplayCard').seen()
    }
  })
})
document.getElementById('bt_resetObjectSearch')?.addEventListener('click', function(event) {
  document.getElementById('in_searchObject').jeeValue('').triggerEvent('keyup')
})

//eqLogics tab searching
document.getElementById('in_searchCmds')?.addEventListener('keyup', function(event) {
  var search = event.target.value
  if (search == '') {
    document.querySelectorAll('#eqLogicsCmds .panel-collapse.in').removeClass('in')
    document.querySelectorAll('#eqLogicsCmds .form-group[data-cmdname]').seen()
    return
  }
  search = jeedomUtils.normTextLower(search)
  document.querySelectorAll('#eqLogicsCmds .panel-collapse.in').removeClass('in')
  document.querySelectorAll('#eqLogicsCmds .form-group[data-cmdname]').unseen()
  document.querySelectorAll('#eqLogicsCmds .panel-collapse').forEach(_panel => { _panel.setAttribute('data-show', '0') })
  var text
  document.querySelectorAll('#eqLogicsCmds .form-group[data-cmdname]').forEach(_eqLogic => {
    text = jeedomUtils.normTextLower(_eqLogic.getAttribute('data-cmdname'))
    if (text.indexOf(search) >= 0) {
      _eqLogic.seen()
      _eqLogic.closest('.panel-collapse').setAttribute('data-show', '1')
    }
  })
  document.querySelectorAll('#eqLogicsCmds .panel-collapse[data-show="1"]').addClass('in')
  document.querySelectorAll('#eqLogicsCmds .panel-collapse[data-show="0"]').removeClass('in')
})
document.getElementById('bt_openAll')?.addEventListener('click', function(event) {
  document.querySelectorAll('#eqLogicsCmds .panel-collapse').forEach(_panel => { _panel.addClass('in') })
})
document.getElementById('bt_closeAll')?.addEventListener('click', function(event) {
  document.querySelectorAll('#eqLogicsCmds .panel-collapse').forEach(_panel => { _panel.removeClass('in') })
})
document.getElementById('bt_resetCmdSearch')?.addEventListener('click', function(event) {
  document.getElementById('in_searchCmds').jeeValue('').triggerEvent('keyup')
})


//Register events on top of page container:
document.registerEvent('keydown', function(event) {
  if (jeedomUtils.getOpenedModal()) return
  if ((event.ctrlKey || event.metaKey) && event.which == 83) { //s
    event.preventDefault()
    jeeP.saveObject()
  }
})

/*Events delegations
*/
//ThumbnailDisplay
document.getElementById('div_resumeObjectList').addEventListener('click', function(event) {
  var _target = null
  if (_target = event.target.closest('#bt_addObject')) {
    jeeDialog.prompt("{{Nom du nouvel objet}} ?", function(result) {
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
    return
  }

  if (_target = event.target.closest('#bt_showObjectSummary')) {
    jeeDialog.dialog({
      id: 'jee_modal',
      title: "{{Vue d'ensemble des objets}}",
      contentUrl: 'index.php?v=d&modal=object.summary'
    })
    return
  }

  if (_target = event.target.closest('.objectDisplayCard')) {
    if (_target.closest('.objectSummaryParent') != null) return
    if (event.ctrlKey || event.metaKey) {
      var url = '/index.php?v=d&p=object&id=' + _target.getAttribute('data-object_id')
      window.open(url).focus()
    } else {
      jeeP.printObject(_target.getAttribute('data-object_id'))
      if ((isset(event.detail) && event.detail.summaryType)) {
        document.querySelector('a[data-target="#summarytab"]').click()
        document.querySelector('a[data-target="#summarytab' + event.detail.summaryType + '"]').click()
      }
    }
    return
  }

  if (_target = event.target.closest('#objectPanel .objectSummaryParent')) {
    event.stopPropagation()
    var summaryType = _target.closest('.objectSummaryParent').getAttribute('data-summary')
    var objectId = _target.closest('.objectDisplayCard').getAttribute('data-object_id')
    event.target.closest('.objectDisplayCard').triggerEvent('click', {detail: {summaryType: summaryType}})
    return
  }
})

document.getElementById('div_resumeObjectList').addEventListener('mousedown', function(event) {
  var _target = null
  if (_target = event.target.closest('#objectPanel .objectSummaryParent')) { //Open object summary config on summmary click
    event.stopPropagation()
    var summaryType = _target.closest('.objectSummaryParent').getAttribute('data-summary')
    var objectId = _target.closest('.objectDisplayCard').getAttribute('data-object_id')
    event.target.closest('.objectDisplayCard').triggerEvent('click', {detail: {summaryType: summaryType}})
    return
  }
})

document.getElementById('div_resumeObjectList').addEventListener('click', function(event) {
  var _target = null
  if (_target = event.target.closest('.objectDisplayCard')) {
    if (event.which == 2) {
      event.preventDefault()
      var id = _target.getAttribute('data-object_id')
      $('.objectDisplayCard[data-object_id="' + id + '"]').trigger(jQuery.Event('click', {
        ctrlKey: true
      }))
    }
    return
  }
})


//Object
document.getElementById('div_conf').addEventListener('click', function(event) {
  var _target = null
  if (_target = event.target.closest('#bt_returnToThumbnailDisplay')) {
    setTimeout(function() {
      document.querySelector('.nav li.active').removeClass('active')
      document.querySelector('a[data-target="#' + document.querySelector('.tab-pane.active').getAttribute('id') + '"]').closest('li').addClass('active')
    }, 500)
    if (jeedomUtils.checkPageModified()) return
    document.getElementById('div_conf').unseen()
    document.getElementById('div_resumeObjectList').seen()
    jeedomUtils.addOrUpdateUrl('id', null, '{{Objets}} - ' + JEEDOM_PRODUCT_NAME)
    return
  }

  if (_target = event.target.closest('#bt_graphObject')) {
    jeeDialog.dialog({
      id: 'jee_modal',
      title: "{{Graphique des liens}}",
      contentUrl: 'index.php?v=d&modal=graph.link&filter_type=object&filter_id=' + document.querySelector('.objectAttr[data-l1key="id"]').innerHTML
    })
    return
  }

  if (_target = event.target.closest('#bt_saveObject')) {
    jeeP.saveObject()
    return
  }

  if (_target = event.target.closest('#bt_removeObject')) {
    jeedomUtils.hideAlert()
    let name = document.querySelector('input[data-l1key="name"]').value.trim()
    jeeDialog.confirm('{{Êtes-vous sûr de vouloir supprimer l\'objet}} <span style="font-weight: bold ;">' + name + '</span> ?', function(result) {
      if (result) {
        var removeId = _target.getAttribute('data-object_id')
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
            document.getElementById('bt_gotoDashboard').querySelectorAll(':scope li a').forEach(_link => {
              if (_link.href.includes(removeId)) _link.parentNode.remove()
            })
            jeedomUtils.loadPage('index.php?v=d&p=object&removeSuccessFull=1')
          }
        })
      }
    })
    return
  }

  if (_target = event.target.closest('#bt_chooseIcon')) {
    var _icon = document.querySelector('div[data-l2key="icon"] > i')
    if (_icon != null) {
      _icon = _icon.getAttribute('class')
    } else {
      _icon = false
    }
    jeedomUtils.chooseIcon(function(_icon) {
      document.querySelector('div[data-l2key="icon"]').innerHTML = _icon
      jeeFrontEnd.modifyWithoutSave = true
    }, {icon: _icon})
    return
  }

  if (_target = event.target.closest('#bt_libraryBackgroundImage')) {
    jeedomUtils.chooseIcon(function(_icon) {
      document.querySelector('.objectImg').seen().querySelector('img').replaceWith(_icon)
      document.querySelector('.objectImg img').setAttribute('width', '240px')
      jeedom.object.uploadImage({
        id: document.querySelector('.objectAttr[data-l1key="id"]').innerHTML,
        file: document.querySelector('.objectImg img').getAttribute('data-filename'),
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
    return
  }

  if (_target = event.target.closest('#bt_removeBackgroundImage')) {
    jeeDialog.confirm('{{Êtes-vous sûr de vouloir enlever l\'image de fond de cet objet ?}}', function(result) {
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
            document.querySelector('.objectImg').unseen()
            jeedomUtils.showAlert({
              message: '{{Image de fond enlevée}}',
              level: 'success'
            })
          },
        })
      }
    })
    return
  }

  if (_target = event.target.closest('#bt_orderEqLogicByUsage')) {
    jeeDialog.confirm('{{Êtes-vous sûr de vouloir réordonner les équipements par utilisation ?}}', function(result) {
      if (result) {
        jeedom.object.orderEqLogicByUsage({
          id: document.querySelector('.objectAttr[data-l1key="id"]').innerHTML,
          error: function(error) {
            jeedomUtils.showAlert({
              message: error.message,
              level: 'danger'
            })
          },
          success: function() {
            jeedomUtils.showAlert({
              message: '{{Equipements réoordonnés avec succes}}',
              level: 'success'
            })
          },
        })
      }
    })
    return
  }

  if (_target = event.target.closest('.bt_checkAll')) {
    _target.closest('tr').querySelectorAll('input[type="checkbox"]').forEach(_check => {
      _check.checked = true
    })
    jeeFrontEnd.modifyWithoutSave = true
    return
  }

  if (_target = event.target.closest('.bt_checkNone')) {
    _target.closest('tr').querySelectorAll('input[type="checkbox"]').forEach(_check => {
      _check.checked = false
    })
    jeeFrontEnd.modifyWithoutSave = true
    return
  }

  if (_target = event.target.closest('.addSummary')) {
    var type = _target.getAttribute('data-type')
    jeeP.addSummaryInfo('.type' + type)
    jeeFrontEnd.modifyWithoutSave = true
    return
  }

  if (_target = event.target.closest('.listCmdInfo')) {
    var el = _target.closest('.summary').querySelector('.summaryAttr[data-l1key="cmd"]')
    var type = _target.closest('.div_summary').dataset.type
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
    return
  }

  if (_target = event.target.closest('.bt_removeSummary')) {
    var cmd = _target.closest('.summary').querySelector('input[data-l1key="cmd"]').values
    var type = _target.closest('.div_summary').dataset.type
    _target.closest('.summary').remove()
    jeeP.updateSummaryTabNbr(type)
    jeeP.updateSummaryButton(cmd, type, false)
    jeeFrontEnd.modifyWithoutSave = true
    return
  }

  if (_target = event.target.closest('ul.dropdown-menu a')) {
    if (event.target.type == 'checkbox') return
    var checkbox = _target.querySelector('input[type="checkbox"]')
    checkbox.checked = !checkbox.checked
    checkbox.triggerEvent('change')
    event.stopPropagation()
    return
  }
})

document.getElementById('div_conf').addEventListener('dblclick', function(event) {
  var _target = null
  if (_target = event.target.closest('.objectAttr[data-l1key="display"][data-l2key="icon"]')) {
    _target.closest('.objectAttr[data-l2key="icon"]').innerHTML = ''
    jeeFrontEnd.modifyWithoutSave = true
    return
  }
})

document.getElementById('div_conf').addEventListener('change', function(event) {
  var _target = null
  if (_target = event.target.closest('select[data-l2key="synthToAction"]')) {
    document.querySelectorAll('select[data-l2key="synthToView"], select[data-l2key="synthToPlan"], select[data-l2key="synthToPlan3d"]').forEach(_select => {
      _select.parentNode.addClass('hidden')
    })
    let select = document.querySelector('select[data-l2key="' + _target.value + '"]')
    if (select != null) select.parentNode.removeClass('hidden')
    return
  }

  if (_target = event.target.closest('ul.dropdown-menu input[type="checkbox"]')) {
    var type = _target.getAttribute('data-value')
    var cmd = _target.closest('.form-group').getAttribute('data-cmdname')
    var state = _target.checked
    jeeP.updateSummaryButton(cmd, type, state)
    jeeFrontEnd.modifyWithoutSave = true

    var el = document.querySelector('.type' + type)
    var summary = {
      cmd: cmd,
      enable: "1",
      invert: "0"
    }
    if (el != null) {
      if (state) {
        jeeP.addSummaryInfo('.type' + type, summary)
      } else {
        el.querySelectorAll('input[data-l1key="cmd"]').forEach(_cmd => {
          if (_cmd.value == cmd) {
            _cmd.closest('.summary').remove()
          }
        })
      }
      jeeP.updateSummaryTabNbr(type)
    }
    return
  }

  if (_target = event.target.closest('.objectAttr')) {
    jeeFrontEnd.modifyWithoutSave = true
    return
  }
})





