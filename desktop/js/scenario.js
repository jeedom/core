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

if (!jeeFrontEnd.scenario) {
  jeeFrontEnd.scenario = {
    dom_divScenario: null,
    tab: null,
    dataDefinedAction: null,
    PREV_FOCUS: null, //create new bloc after current used bloc
    actionOptions: [],
    editors: [],
    clipboard: null,
    undoStack: [],
    undoState: -1,
    firstState: 0,
    undoLimit: 12,
    reDo: 0,
    bt_undo: null,
    bt_redo: null,
    addElementSave: {
      expression: false,
      insertAfter: false,
      elementDiv: null,
    },
    init: function() {
      this.tab = null
      this.PREV_FOCUS = null
      this.editors = []
      this.undoStack = []
      this.bt_undo = document.getElementById('bt_undo')
      this.bt_redo = document.getElementById('bt_redo')
      this.dom_divScenario = document.getElementById('div_editScenario')
      window.jeeP = this

      jeeP.loadId = getUrlVars('id')
      if (is_numeric(jeeP.loadId)) {
        this.printScenario(jeeP.loadId, function() {
          if (jeephp2js.initSearch != 0) {
            document.getElementById('bt_scenarioTab').click()
            document.getElementById('bt_resetInsideScenarioSearch').click()
            setTimeout(function() {
              document.getElementById('in_searchInsideScenario').jeeValue(jeephp2js.initSearch).triggerEvent('keyup').focus()
            }, 200)
          }
        })
      }

      //Global scenario state:
      if (jeephp2js.globalActiveState == '0') {
        document.getElementById('bt_runScenario').addClass('disabled')
        document.getElementById('generaltab').querySelector('input[data-l1key="isActive"]').addClass('warning')
        document.getElementById('scenarioThumbnailDisplay').querySelector('div.scenarioListContainer').addClass('warning')
        document.getElementById('div_editScenario').querySelectorAll('ul[role="tablist"] > li').addClass('warning')
      }
    },
    postInit: function() {
      //autocomplete timeline input:
      jeedom.timeline.autocompleteFolder()
      //autocomplete group input:
      document.querySelector('.scenarioAttr[data-l1key="group"]')?.jeeComplete({
        source: function(request, response, url) {
          domUtils.ajax({
            type: 'POST',
            url: 'core/ajax/scenario.ajax.php',
            data: {
              action: 'autoCompleteGroup',
              term: request.term
            },
            dataType: 'json',
            global: false,
            error: function(request, status, error) {
              handleAjaxError(request, status, error)
            },
            success: function(data) {
              if (data.state != 'ok') {
                jeedomUtils.showAlert({
                  message: data.result,
                  level: 'danger'
                })
                return
              }
              response(data.result)
            }
          })
        },
        minLength: 1,
        forceSingle: true
      })

      document.querySelector('sub.itemsNumber').innerHTML = '(' + document.querySelectorAll('.scenarioDisplayCard').length + ')'
      this.setSortables()
    },
    checkNoTriggeringMode: function() {
      if (document.querySelectorAll('div.scheduleDisplay .schedule').length > 0 ||
        document.querySelectorAll('div.provokeDisplay .trigger').length > 0 ||
        document.querySelectorAll('div.defined_actions .action_link:not(.cross)').length > 0) {
        document.getElementById('emptyModeWarning').unseen()
      } else {
        document.getElementById('emptyModeWarning').seen()
      }
    },
    //Copy / Paste
    removeObjectProp: function(obj, propToDelete) {
      for (var property in obj) {
        if (obj.hasOwnProperty(property)) {
          if (typeof obj[property] == 'object') {
            this.removeObjectProp(obj[property], propToDelete)
          } else {
            if (property === propToDelete) {
              delete obj[property]
            }
          }
        }
      }
    },
    setSortables: function() {
      var selector = '.subElementACTION > .expressions, .subElementDO > .expressions, .subElementTHEN > .expressions, .subElementELSE > .expressions'
      var containers = document.getElementById('div_scenarioElement').querySelectorAll(selector)
      var commonOptions = {
        group: {
          name: 'expressionSorting',
        },
        delay: 250,
        delayOnTouchOnly: true,
        animation: 100,
        draggable: '.sortable',
        handle: '.bt_sortable',
        direction: 'vertical',
        swapThreshold: 0.07,
        removeCloneOnHide: true,
        onStart: function(event) {
          jeeFrontEnd.scenario.setUndoStack()
          document.querySelectorAll('.dropdown.open').removeClass('open')
          document.querySelectorAll('.subElementCODE .expressions .expression').addClass('disabled') //Prevent paste dragged element into code
          setTimeout(() => {
            document.querySelectorAll('div[data-tippy-root]').remove()
          }, 100)
        },
        onMove: function(event, originalEvent) { //Prevent actions on root
          if (event.dragged.hasClass('expressionACTION') && event.to.getAttribute('id') == 'root') {
            return false
          }
        },
        onEnd: function(event) {
          document.querySelectorAll('.subElementCODE .expressions .expression').removeClass('disabled')

          if (event.from.getAttribute('id') == 'root') {
            if (event.to.getAttribute('id') != 'root') {
              event.item.insertAdjacentHTML('afterbegin', '<input class="expressionAttr" data-l1key="type" style="display : none;" value="element"/>')
            }
          }
          if (event.to.getAttribute('id') == 'root') {
            if (event.from.getAttribute('id') != 'root') {
              event.item.querySelector(':scope > input[data-l1key="type"]')?.remove()
            }
          }

          jeeFrontEnd.modifyWithoutSave = true
        },
      }
      containers.forEach(_Sortcontainer => {
        if (Sortable.get(_Sortcontainer)) Sortable.get(_Sortcontainer).destroy()
        new Sortable(_Sortcontainer, commonOptions)
      })
      var root = document.getElementById('root')
      if (root) {
        if (Sortable.get(root)) Sortable.get(root).destroy()
        new Sortable(root, commonOptions)
      }
    },
    setRootElements: function() {
      /*
      Create same hierarchie for root elements to manage sortables:
      //root -> expressions -> expression -> col-xs-12 -> element
      */
      var root = document.querySelector('#div_scenarioElement #root')
      if (!root) {
        document.getElementById('div_scenarioElement').insertAdjacentHTML('afterbegin', '<div id="root" class="expressions"></div>')
        root = document.querySelector('#div_scenarioElement #root')
      }
      var rootElements = document.querySelectorAll('#div_scenarioElement > div.element')
      if (rootElements.length > 0) {
        rootElements.forEach(_element => {
          var exp = document.createElement('div')
          exp.addClass('expression sortable col-xs-12')
          exp.insertAdjacentHTML('afterbegin', '<input class="expressionAttr" data-l1key="id" style="display : none;" value=""/>')
          exp.insertAdjacentHTML('afterbegin', '<input class="expressionAttr" data-l1key="scenarioSubElement_id" style="display : none;" value=""/>')

          exp.innerHTML = '<div class="col-xs-12 "></div>'
          exp.childNodes[0].appendChild(_element)
          root.appendChild(exp)
        })
        jeeP.setSortables()
      }
    },
    updateElseToggle: function() {
      document.querySelectorAll('.subElementELSE').forEach(function(elElse) {
        if (!elElse.closest('.element').querySelector(':scope > .subElementTHEN')?.querySelector('.bt_showElse i')?.hasClass('fa-sort-down')) {
          if (elElse.querySelector(':scope > .expressions')?.querySelectorAll(':scope > .expression').length == 0) {
            elElse.closest('.element').querySelector(':scope > .subElementTHEN').querySelector('.bt_showElse').click()
          }
        }
      })
    },
    updateElementCollapse: function() {
      document.querySelectorAll('a.bt_collapse').forEach(_bt => {
        if (_bt.getAttribute('value') == '0') {
          _bt.closest('.element').removeClass('elementCollapse')
        } else {
          _bt.closest('.element').addClass('elementCollapse')
        }
      })
    },
    setScenarioActionsOptions: function() {
      jeedom.cmd.displayActionsOption({
        params: this.actionOptions,
        async: false,
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          domUtils.showLoading()
          for (var i in data) {
            document.getElementById(data[i].id).html(data[i].html.html, true)
          }
          domUtils.hideLoading()
          jeedomUtils.taAutosize()
        }
      })
    },
    updateDefinedActions: function(cmdModal = false) {
      //cmdModal called from cmd.configure modal to update ui list!
      if (cmdModal) {
        var scId = document.querySelector('div#div_editScenario span[data-l1key="id"]').textContent
        var cmdId = document.querySelector('div#cmd_information span[data-l1key="id"]')?.textContent

        var cmdName = jeephp2js.md_cmdConfigure_cmdInfo.eqLogicHumanName + ' [' + jeephp2js.md_cmdConfigure_cmdInfo.name + ']'

        //clean actual cmd from list:
        jeeP.dataDefinedAction = jeeP.dataDefinedAction.filter(i => i['cmdId'] != cmdId)

        var action, scenario_id, enable
        document.querySelectorAll('.actionCheckCmd').forEach(function(_cmd) {
          action = _cmd.querySelector('input[data-type="actionCheckCmd"]').jeeValue()
          if (action != "scenario") return true
          scenario_id = _cmd.querySelector('select[data-l2key="scenario_id"]').jeeValue()
          if (scenario_id != scId) return true
          enable = _cmd.querySelector('input[data-l2key="enable"]').jeeValue()
          action = {
            'cmdId': cmdId,
            'name': cmdName,
            'enable': enable,
            'type': 'actionCheckCmd'
          }
          jeeP.dataDefinedAction.push(action)
        })

        document.querySelectorAll('.actionPreExecCmd').forEach(function(_cmd) {
          action = _cmd.querySelector('input[data-type="actionPreExecCmd"]').jeeValue()
          if (action != "scenario") return true
          scenario_id = _cmd.querySelector('select[data-l2key="scenario_id"]').jeeValue()
          if (scenario_id != scId) return true
          enable = _cmd.querySelector('input[data-l2key="enable"]').jeeValue()
          action = {
            'cmdId': cmdId,
            'name': cmdName,
            'enable': enable,
            'type': 'jeedomPreExecCmd'
          }
          jeeP.dataDefinedAction.push(action)
        })

        document.querySelectorAll('.actionPostExecCmd').forEach(function(_cmd) {
          action = _cmd.querySelector('input[data-type="actionPostExecCmd"]').jeeValue()
          if (action != "scenario") return true
          scenario_id = _cmd.querySelector('select[data-l2key="scenario_id"]').jeeValue()
          if (scenario_id != scId) return true
          enable = _cmd.querySelector('input[data-l2key="enable"]').jeeValue()
          action = {
            'cmdId': cmdId,
            'name': cmdName,
            'enable': enable,
            'type': 'jeedomPostExecCmd'
          }
          jeeP.dataDefinedAction.push(action)
        })
      }

      document.querySelector('.defined_actions').empty()
      var htmlActions = ''
      var prefix = ''
      for (var i in jeeP.dataDefinedAction) {
        if (jeeP.dataDefinedAction[i]['type'] == 'actionCheckCmd') prefix = 'Value -> '
        if (jeeP.dataDefinedAction[i]['type'] == 'jeedomPreExecCmd') prefix = 'PreExec -> '
        if (jeeP.dataDefinedAction[i]['type'] == 'jeedomPostExecCmd') prefix = 'PostExec -> '

        if (jeeP.dataDefinedAction[i]['enable'] == '1') {
          htmlActions += '<span class="label label-info cursor action_link" data-cmd_id="' + jeeP.dataDefinedAction[i]['cmdId'] + '">' + prefix + jeeP.dataDefinedAction[i]['name'] + '</span><br/>'
        } else {
          htmlActions += '<span class="label label-info cursor cross action_link" data-cmd_id="' + jeeP.dataDefinedAction[i]['cmdId'] + '">' + prefix + jeeP.dataDefinedAction[i]['name'] + '</span><br/>'
        }
      }
      document.querySelector('.defined_actions').insertAdjacentHTML('beforeend', htmlActions)
    },
    //Load / Save:
    printScenario: function(_id, _callback) {
      jeedomUtils.hideAlert()
      domUtils.showLoading()
      document.getElementById('scenarioThumbnailDisplay').unseen()
      document.getElementById('emptyModeWarning').unseen()
      jeedom.scenario.update[_id] = function(_options) {
        if (_options.scenario_id != undefined && _options.scenario_id != jeeP.dom_divScenario.getJeeValues('.scenarioAttr')[0]['id']) {
          return
        }
        switch (_options.state) {
          case 'error':
            document.getElementById('bt_stopScenario').unseen()
            document.getElementById('span_ongoing').textContent = '{{Erreur}}'
            document.getElementById('span_ongoing').removeClass('label-info', 'label-danger', 'label-success', 'label-info').addClass('label-warning')
            break
          case 'on':
            document.getElementById('bt_stopScenario').seen()
            document.getElementById('span_ongoing').textContent = '{{Actif}}'
            document.getElementById('span_ongoing').removeClass('label-info', 'label-danger', 'label-warning', 'label-info').addClass('label-success')
            break
          case 'starting':
            document.getElementById('bt_stopScenario').seen()
            document.getElementById('span_ongoing').textContent = '{{Démarrage}}'
            document.getElementById('span_ongoing').removeClass('label-success', 'label-danger', 'label-warning', 'label-info').addClass('label-warning')
            break
          case 'in progress':
            document.getElementById('bt_stopScenario').seen()
            document.getElementById('span_ongoing').textContent = '{{En cours}}'
            document.getElementById('span_ongoing').removeClass('label-success', 'label-danger', 'label-warning', 'label-info').addClass('label-info')
            break
          case 'stop':
            document.getElementById('bt_stopScenario').unseen()
            document.getElementById('span_ongoing').textContent = '{{Arrêté}}'
            document.getElementById('span_ongoing').removeClass('label-info', 'label-success', 'label-warning', 'label-info').addClass('label-danger')
            break
        }
      }
      jeedom.scenario.get({
        id: _id,
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          document.querySelectorAll('.scenarioAttr').jeeValue('')
          document.querySelector('.scenarioAttr[data-l1key="object_id"] option').selected = true
          document.querySelector('.scenarioAttr[data-l1key="object_id"]').value = ''
          jeeP.dom_divScenario.setJeeValues(data, '.scenarioAttr')
          data.lastLaunch = (data.lastLaunch == 'false') ? '{{Jamais}}' : data.lastLaunch
          document.querySelector('span[data-l1key="lastLaunch"]').textContent = data.lastLaunch
          document.getElementById('div_scenarioElement').empty()
          document.querySelectorAll('.provokeMode').empty()
          document.querySelectorAll('.scheduleMode').empty()

          if (data.trigger[0] == null) {
            document.querySelector('.scenarioAttr[data-l1key="mode"]').selectedIndex = 0
          }
          document.querySelector('.scenarioAttr[data-l1key="mode"]').triggerEvent('change')


          jeedom.scenario.update[_id](data)
          if (data.isActive != 1) {
            document.getElementById('span_ongoing').removeClass('label-danger').removeClass('label-success').textContent = '{{Inactif}}'
          }

          //Triggers:
          if (Array.isArray(data.trigger)) {
            for (var i in data.trigger) {
              if (data.trigger[i] != '' && data.trigger[i] != null) {
                jeeP.addTrigger(data.trigger[i])
              }
            }
          } else {
            if (data.trigger != '' && data.trigger != null) {
              jeeP.addTrigger(data.trigger)
            }
          }

          if (Array.isArray(data.schedule)) {
            for (var i in data.schedule) {
              if (data.schedule[i] != '' && data.schedule[i] != null) {
                jeeP.addSchedule(data.schedule[i])
              }
            }
          } else {
            if (data.schedule != '' && data.schedule != null) {
              jeeP.addSchedule(data.schedule)
            }
          }

          //Defines actions:
          if (data.definedAction) {
            jeeP.dataDefinedAction = data.definedAction
            jeeP.updateDefinedActions()
          }

          //Links:
          document.querySelector('#generaltab div.scenario_link_getUsedBy').empty()
          document.querySelector('#generaltab div.scenario_link_getUse').empty()
          var html_getUsedBy = ''
          var html_getUse = ''
          if (data.scenario_link.scenario) {
            for (var i in data.scenario_link.scenario) {
              if (data.scenario_link.scenario[i].link == 'getUsedBy') {
                if (data.scenario_link.scenario[i].isActive == 1) {
                  html_getUsedBy += '<span class="label label-success cursor scenario_link" data-scenario_id="' + i + '">' + data.scenario_link.scenario[i].name + '</span><br/>'
                } else {
                  html_getUsedBy += '<span class="label label-danger cursor scenario_link" data-scenario_id="' + i + '">' + data.scenario_link.scenario[i].name + '</span><br/>'
                }
              }
              else if (data.scenario_link.scenario[i].link == 'getUse') {
                if (data.scenario_link.scenario[i].isActive == 1) {
                  html_getUse += '<span class="label label-success cursor scenario_link" data-scenario_id="' + i + '">' + data.scenario_link.scenario[i].name + '</span><br/>'
                } else {
                  html_getUse += '<span class="label label-danger cursor scenario_link" data-scenario_id="' + i + '">' + data.scenario_link.scenario[i].name + '</span><br/>'
                }
              }
            }
          }
          document.querySelector('#generaltab div.scenario_link_getUsedBy').insertAdjacentHTML('beforeend', html_getUsedBy)
          document.querySelector('#generaltab div.scenario_link_getUse').insertAdjacentHTML('beforeend', html_getUse)

          //Empty scenario ?
          if (data.elements.length == 0) {
            document.getElementById('div_scenarioElement').insertAdjacentHTML('beforeend', '<center class="span_noScenarioElement"><span>{{Pour constituer votre scénario, veuillez ajouter des blocs}}.</span></center>')
          }

          jeeP.actionOptions = []


          var elements = ''
          for (var i in data.elements) {
            elements += jeeP.addElement(data.elements[i])
          }
          document.getElementById('div_scenarioElement').insertAdjacentHTML('beforeend', elements)
          jeeFrontEnd.scenario.setRootElements()

          document.querySelectorAll('.subElementAttr[data-l1key="options"][data-l2key="enable"], .expressionAttr[data-l1key="options"][data-l2key="enable"]').triggerEvent('change')
          jeeP.setScenarioActionsOptions()
          document.getElementById('div_editScenario').seen()
          jeedom.scenario.setAutoComplete()
          jeeP.updateElementCollapse()
          jeeP.updateElseToggle()
          jeedomUtils.taAutosize()
          var title = ''
          if (data.name) title = data.name + ' - ' + JEEDOM_PRODUCT_NAME
          var hash = window.location.hash
          jeedomUtils.addOrUpdateUrl('id', data.id, title)
          if (hash == '') {
            document.querySelector('.nav-tabs a[data-target="#generaltab"]').click()
          } else {
            window.location.hash = hash
          }

          domUtils(function() {
            jeeP.setEditors()
            jeeP.checkNoTriggeringMode()
            jeedomUtils.initTooltips()
            jeedom.scenario.setAutoComplete()
            jeeP.resetUndo()
            jeeFrontEnd.modifyWithoutSave = false

            if (isset(_callback) && typeof _callback === 'function') {
              _callback()
            }
          })

          document.getElementById('humanNameTag').innerHTML = data.humanNameTag
        }
      })
    },
    saveScenario: function(_callback) {
      jeedomUtils.hideAlert()
      //Get scenarios settings:
      var scenario = this.dom_divScenario.getJeeValues('.scenarioAttr')[0]
      if (typeof scenario.trigger == 'undefined') scenario.trigger = ''
      if (typeof scenario.schedule == 'undefined') scenario.schedule = ''

      //Get scenario elements:
      var elements = []
      document.getElementById('root').querySelectorAll(':scope > .expression').forEach(_exp => {
        elements.push(jeeP.getElement(_exp.querySelector('div.element')))
      })

      scenario.elements = elements
      jeedom.scenario.save({
        scenario: scenario,
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          jeeFrontEnd.modifyWithoutSave = false
          jeeP.resetUndo()
          var url = 'index.php?v=d&p=scenario&id=' + data.id + '&saveSuccessFull=1'
          if (window.location.hash != '') {
            url += window.location.hash
          }
          jeedomUtils.loadPage(url)
          if (typeof _callback == 'function') {
            _callback()
          }
        }
      })
    },
    //Adding:
    addTrigger: function(_trigger) {
      var div = '<div class="form-group trigger">'
      div += '<label class="col-xs-3 control-label">{{Evénement}}</label>'
      div += '<div class="col-xs-9">'
      div += '<div class="input-group">'
      div += '<input class="scenarioAttr input-sm form-control roundedLeft" data-l1key="trigger" value="' + _trigger.replace(/"/g, '&quot;') + '" >'
      div += '<span class="input-group-btn">'
      div += '<a class="btn btn-default btn-sm cursor bt_selectTrigger" title="{{Choisir une commande}}"><i class="fas fa-list-alt"></i></a>'
      div += '<a class="btn btn-default btn-sm cursor bt_selectGenericTrigger" title="{{Choisir un Type Générique}}"><i class="fas fa-puzzle-piece"></i></a>'
      div += '<a class="btn btn-default btn-sm cursor bt_selectDataStoreTrigger" title="{{Choisir une variable}}"><i class="fas fa-calculator"></i></a>'
      div += '<a class="btn btn-default btn-sm cursor bt_removeTrigger roundedRight"><i class="fas fa-minus-circle"></i></a>'
      div += '</span>'
      div += '</div>'
      div += '</div>'
      div += '</div>'
      document.querySelector('.provokeMode').insertAdjacentHTML('beforeend', div)
    },
    addSchedule: function(_schedule) {
      var div = '<div class="form-group schedule">'
      div += '<label class="col-xs-3 control-label">{{Programmation}}</label>'
      div += '<div class="col-xs-9">'
      div += '<div class="input-group">'
      div += '<input class="scenarioAttr input-sm form-control roundedLeft" data-l1key="schedule" value="' + _schedule.replace(/"/g, '&quot;') + '">'
      div += '<span class="input-group-btn">'
      div += '<a class="btn btn-default btn-sm cursor jeeHelper" data-helper="cron"><i class="fas fa-question-circle"></i></a>'
      div += '<a class="btn btn-default btn-sm cursor bt_removeSchedule roundedRight"><i class="fas fa-minus-circle"></i></a>'
      div += '</span>'
      div += '</div>'
      div += '</div>'
      div += '</div>'
      document.querySelector('.scheduleMode').insertAdjacentHTML('beforeend', div)
    },
    addExpression: function(_expression) {
      if (!isset(_expression) || !isset(_expression.type) || _expression.type == '') return ''
      var sortable = 'sortable'
      if (_expression.type == 'condition' || _expression.type == 'code') {
        sortable = 'noSortable'
      }

      var retour = '<div class="expression ' + sortable + ' col-xs-12" >'
      if (_expression.type == 'action') {
        retour = '<div class="expression expressionACTION ' + sortable + ' col-xs-12" >'
      }
      retour += '<input class="expressionAttr" data-l1key="id" style="display : none;" value="' + init(_expression.id) + '"/>'
      retour += '<input class="expressionAttr" data-l1key="scenarioSubElement_id" style="display : none;" value="' + init(_expression.scenarioSubElement_id) + '"/>'
      retour += '<input class="expressionAttr" data-l1key="type" style="display : none;" value="' + init(_expression.type) + '"/>'
      switch (_expression.type) {
        case 'condition':
          if (isset(_expression.expression)) {
            try {
              _expression.expression = _expression.expression.replace(/"/g, '&quot;')
            } catch (error) {
            }
          }
          retour += '<div class="input-group input-group-sm" >'
          retour += '<input class="expressionAttr form-control roundedLeft" data-l1key="expression" value="' + init(_expression.expression) + '" />'
          retour += '<span class="input-group-btn">'
          retour += '<button type="button" class="btn btn-default cursor bt_selectCmdExpression" title="{{Rechercher une commande}}"><i class="fas fa-list-alt"></i></button>'
          retour += '<button type="button" class="btn btn-default cursor bt_selectGenericExpression" title="{{Rechercher un type générique}}"><i class="fas fa-puzzle-piece"></i></button>'
          retour += '<button type="button" class="btn btn-default cursor bt_selectScenarioExpression" title="{{Rechercher un scénario}}"><i class="fas fa-history"></i></button>'
          retour += '<button type="button" class="btn btn-default cursor bt_selectEqLogicExpression roundedRight"  title="{{Rechercher un équipement}}"><i class="fas fa-cube"></i></button>'
          retour += '</span>'
          retour += '</div>'
          break
        case 'element':
          retour += '<div class="col-xs-12" >'
          if (isset(_expression.element) && isset(_expression.element.html)) {
            retour += _expression.element.html
          } else {
            var element = jeeP.addElement(_expression.element, true)
            if (element.trim() == '') {
              return ''
            }
            retour += element
          }
          retour += '</div>'
          break
        case 'action':
          retour += '<div class="col-xs-1" >'
          retour += '<i class="fas fa-arrows-alt-v cursor bt_sortable" ></i>'
          if (!isset(_expression.options) || !isset(_expression.options.enable) || _expression.options.enable == 1) {
            retour += '<input type="checkbox" class="expressionAttr" data-l1key="options" data-l2key="enable" checked  title="{{Décocher pour désactiver l\'action}}"/>'
          } else {
            retour += '<input type="checkbox" class="expressionAttr" data-l1key="options" data-l2key="enable"  title="{{Décocher pour désactiver l\'action}}"/>'
          }
          if (!isset(_expression.options) || !isset(_expression.options.background) || _expression.options.background == 0) {
            retour += '<input type="checkbox" class="expressionAttr" data-l1key="options" data-l2key="background"  title="{{Cocher pour que la commande s\'exécute en parallèle des autres actions}}"/>'
          } else {
            retour += '<input type="checkbox" class="expressionAttr" data-l1key="options" data-l2key="background" checked  title="{{Cocher pour que la commande s\'exécute en parallèle des autres actions}}"/>'
          }
          var expression_txt = init(_expression.expression)
          if (typeof expression_txt != 'string') {
            expression_txt = JSON.stringify(expression_txt)
          }
          retour += '</div>'
          retour += '<div class="col-xs-4" ><div class="input-group input-group-sm">'
          retour += '<span class="input-group-btn">'
          retour += '<button class="btn btn-default bt_removeExpression roundedLeft" type="button" title="{{Supprimer l\'action}}"><i class="fas fa-minus-circle"></i></button>'
          retour += '</span>'
          retour += '<input class="expressionAttr form-control" data-l1key="expression" prevalue="' + init(_expression.expression) + '" value="' + expression_txt.replace(/"/g, '&quot;') + '"/>'
          retour += '<span class="input-group-btn">'
          retour += '<button class="btn btn-default bt_selectOtherActionExpression" type="button" title="{{Sélectionner un mot-clé}}"><i class="fas fa-tasks"></i></button>'
          retour += '<button class="btn btn-default bt_selectCmdExpression roundedRight" type="button" title="{{Sélectionner la commande}}"><i class="fas fa-list-alt"></i></button>'
          retour += '</span>'
          retour += '</div></div>'
          var actionOption_id = jeedomUtils.uniqId()
          retour += '<div class="col-xs-7 expressionOptions"  id="' + actionOption_id + '">'
          retour += '</div>'
          this.actionOptions.push({
            expression: init(_expression.expression, ''),
            options: _expression.options,
            id: actionOption_id
          })
          break
        case 'code':
          retour += '<div>'
          retour += '<textarea class="expressionAttr form-control" data-l1key="expression">' + init(_expression.expression) + '</textarea>'
          retour += '</div>'
          break
        case 'comment':
          retour += '<textarea class="expressionAttr form-control" data-l1key="expression">' + init(_expression.expression) + '</textarea>'
          break
      }
      retour += '</div>'
      return retour
    },
    addSubElement: function(_subElement) {
      if (!isset(_subElement.type) || _subElement.type == '') return ''
      if (!isset(_subElement.options)) _subElement.options = {}
      var noSortable = ''
      if (_subElement.type == 'if' || _subElement.type == 'for' || _subElement.type == 'code') {
        noSortable = 'noSortable'
      }

      var blocClass = ''
      switch (_subElement.type) {
        case 'if':
          blocClass = 'subElementIF'
          break
        case 'then':
          blocClass = 'subElementTHEN'
          break
        case 'else':
          blocClass = 'subElementELSE'
          break
        case 'for':
          blocClass = 'subElementFOR'
          break
        case 'in':
          blocClass = 'subElementIN'
          break
        case 'at':
          blocClass = 'subElementAT'
          break
        case 'do':
          blocClass = 'subElementDO'
          break
        case 'code':
          blocClass = 'subElementCODE'
          break
        case 'comment':
          blocClass = 'subElementCOMMENT'
          break
        case 'action':
          blocClass = 'subElementACTION'
          break
      }
      var retour = '<div class="subElement ' + blocClass + ' ' + noSortable + '">'
      retour += '<input class="subElementAttr" data-l1key="id" style="display : none;" value="' + init(_subElement.id) + '"/>'
      retour += '<input class="subElementAttr" data-l1key="scenarioElement_id" style="display : none;" value="' + init(_subElement.scenarioElement_id) + '"/>'
      retour += '<input class="subElementAttr" data-l1key="type" style="display : none;" value="' + init(_subElement.type) + '"/>'

      switch (_subElement.type) {
        case 'if':
          retour += '<input class="subElementAttr" data-l1key="subtype" style="display : none;" value="condition"/>'
          retour += '<div>'
          retour += '<i class="bt_sortable fas fa-arrows-alt-v pull-left cursor" ></i>'
          if (!isset(_subElement.options) || !isset(_subElement.options.collapse) || _subElement.options.collapse == 0) {
            retour += '<a class="bt_collapse cursor subElementAttr" title="{{Masquer ce bloc.<br>Ctrl+click: tous.}}" data-l1key="options" data-l2key="collapse" value="0"><i class="far fa-eye"></i></a>'
          } else {
            retour += '<a class="bt_collapse cursor subElementAttr" title="{{Afficher ce bloc.<br>Ctrl+click: tous.}}" data-l1key="options" data-l2key="collapse" value="1"><i class="far fa-eye-slash"></i></a>'
          }
          if (!isset(_subElement.options) || !isset(_subElement.options.enable) || _subElement.options.enable == 1) {
            retour += '<input type="checkbox" class="subElementAttr" data-l1key="options" data-l2key="enable" checked title="{{Décocher pour désactiver l\'élément}}" />'
          } else {
            retour += '<input type="checkbox" class="subElementAttr" data-l1key="options" data-l2key="enable" title="{{Décocher pour désactiver l\'élément}}" />'
          }
          retour += '</div>'
          retour += '<div><legend >{{SI}}</legend></div>'

          retour += '<div >'
          if (!isset(_subElement.options) || !isset(_subElement.options.allowRepeatCondition) || _subElement.options.allowRepeatCondition == 0) {
            retour += '<a class="bt_repeat cursor subElementAttr" title="{{Autoriser ou non la répétition des actions si l\'évaluation de la condition est la même que la précédente}}" data-l1key="options" data-l2key="allowRepeatCondition" value="0"><span><i class="fas fa-sync"></i></span></a>'
          } else {
            retour += '<a class="bt_repeat cursor subElementAttr" title="{{Autoriser ou non la répétition des actions si l\'évaluation de la condition est la même que la précédente}}" data-l1key="options" data-l2key="allowRepeatCondition" value="1"><span><i class="fas fa-ban text-danger"></i></span></a>'
          }
          retour += '</div>'

          retour += '<div class="expressions" >'
          var expression = {
            type: 'condition'
          }
          if (isset(_subElement.expressions) && isset(_subElement.expressions[0])) {
            expression = _subElement.expressions[0]
          }
          retour += this.addExpression(expression)
          retour += '  </div>'
          retour = this.addElButtons(retour)
          break

        case 'then':
          retour += '<input class="subElementAttr" data-l1key="subtype" style="display : none;" value="action"/>'
          retour += '<div class="subElementFields">'
          retour += '<legend >{{ALORS}}</legend>'
          retour += this.getAddButton(_subElement.type, true)
          retour += '</div>'
          retour += '<div class="expressions">'
          if (isset(_subElement.expressions)) {
            for (var k in _subElement.expressions) {
              retour += this.addExpression(_subElement.expressions[k])
            }
          }
          retour += '</div>'
          break

        case 'else':
          retour += '<input class="subElementAttr subElementElse" data-l1key="subtype" style="display : none;" value="action"/>'
          retour += '<div class="subElementFields">'
          retour += '<legend >{{SINON}}</legend>'
          retour += this.getAddButton(_subElement.type)
          retour += '</div>'
          retour += '<div class="expressions">'
          if (isset(_subElement.expressions)) {
            for (var k in _subElement.expressions) {
              retour += this.addExpression(_subElement.expressions[k])
            }
          }
          retour += '</div>'
          break

        case 'for':
          retour += '<input class="subElementAttr" data-l1key="subtype" style="display : none;" value="condition"/>'
          retour += '<div>'
          retour += '<i class="bt_sortable fas fa-arrows-alt-v pull-left cursor"></i>'
          if (!isset(_subElement.options) || !isset(_subElement.options.collapse) || _subElement.options.collapse == 0) {
            retour += '<a class="bt_collapse cursor subElementAttr" title="{{Masquer ce bloc.<br>Ctrl+click: tous.}}" data-l1key="options" data-l2key="collapse" value="0"><i class="far fa-eye"></i></a>'
          } else {
            retour += '<a class="bt_collapse cursor subElementAttr" title="{{Afficher ce bloc.<br>Ctrl+click: tous.}}" data-l1key="options" data-l2key="collapse" value="1"><i class="far fa-eye-slash"></i></a>'
          }
          if (!isset(_subElement.options) || !isset(_subElement.options.enable) || _subElement.options.enable == 1) {
            retour += '<input type="checkbox" class="subElementAttr" data-l1key="options" data-l2key="enable" checked title="{{Décocher pour désactiver l\'élément}}" />'
          } else {
            retour += '<input type="checkbox" class="subElementAttr" data-l1key="options" data-l2key="enable" title="{{Décocher pour désactiver l\'élément}}" />'
          }
          retour += '</div>'
          retour += '<div>'
          retour += '<legend >{{DE 1 A}}</legend>'
          retour += '</div>'
          retour += '<div class="expressions" >'
          var expression = {
            type: 'condition'
          }
          if (isset(_subElement.expressions) && isset(_subElement.expressions[0])) {
            expression = _subElement.expressions[0]
          }
          retour += this.addExpression(expression)
          retour += '</div>'
          retour = this.addElButtons(retour)
          break

        case 'in':
          retour += '<input class="subElementAttr" data-l1key="subtype" style="display : none;" value="condition"/>'
          retour += '<div>'
          retour += '<i class="bt_sortable fas fa-arrows-alt-v pull-left cursor"></i>'
          if (!isset(_subElement.options) || !isset(_subElement.options.collapse) || _subElement.options.collapse == 0) {
            retour += '<a class="bt_collapse cursor subElementAttr" title="{{Masquer ce bloc.<br>Ctrl+click: tous.}}" data-l1key="options" data-l2key="collapse" value="0"><i class="far fa-eye"></i></a>'
          } else {
            retour += '<a class="bt_collapse cursor subElementAttr" title="{{Afficher ce bloc.<br>Ctrl+click: tous.}}" data-l1key="options" data-l2key="collapse" value="1"><i class="far fa-eye-slash"></i></a>'
          }
          if (!isset(_subElement.options) || !isset(_subElement.options.enable) || _subElement.options.enable == 1) {
            retour += '<input type="checkbox" class="subElementAttr" data-l1key="options" data-l2key="enable" checked title="{{Décocher pour désactiver l\'élément}}" />'
          } else {
            retour += '<input type="checkbox" class="subElementAttr" data-l1key="options" data-l2key="enable" title="{{Décocher pour désactiver l\'élément}}" />'
          }
          retour += '</div>'
          retour += '<div>'
          retour += '<legend title="{{Action DANS x minutes}}">{{DANS}}</legend>'
          retour += '</div>'
          retour += '<div class="expressions" >'
          var expression = {
            type: 'condition'
          }
          if (isset(_subElement.expressions) && isset(_subElement.expressions[0])) {
            expression = _subElement.expressions[0]
          }
          retour += this.addExpression(expression)
          retour += '</div>'
          retour = this.addElButtons(retour)
          break

        case 'at':
          retour += '<input class="subElementAttr" data-l1key="subtype" style="display : none;" value="condition"/>'
          retour += '<div>'
          retour += '<i class="bt_sortable fas fa-arrows-alt-v pull-left cursor"></i>'
          if (!isset(_subElement.options) || !isset(_subElement.options.collapse) || _subElement.options.collapse == 0) {
            retour += '<a class="bt_collapse cursor subElementAttr" title="{{Masquer ce bloc.<br>Ctrl+click: tous.}}" data-l1key="options" data-l2key="collapse" value="0"><i class="far fa-eye"></i></a>'
          } else {
            retour += '<a class="bt_collapse cursor subElementAttr" title="{{Afficher ce bloc.<br>Ctrl+click: tous.}}" data-l1key="options" data-l2key="collapse" value="1"><i class="far fa-eye-slash"></i></a>'
          }
          if (!isset(_subElement.options) || !isset(_subElement.options.enable) || _subElement.options.enable == 1) {
            retour += '<input type="checkbox" class="subElementAttr" data-l1key="options" data-l2key="enable" checked title="{{Décocher pour désactiver l\'élément}}" />'
          } else {
            retour += '<input type="checkbox" class="subElementAttr" data-l1key="options" data-l2key="enable" title="{{Décocher pour désactiver l\'élément}}" />'
          }
          retour += '</div>'
          retour += '<div>'
          retour += '<legend >{{A}}</legend><span>(Hmm)</span>'
          retour += '</div>'
          retour += '<div class="expressions" >'
          var expression = {
            type: 'condition'
          }
          if (isset(_subElement.expressions) && isset(_subElement.expressions[0])) {
            expression = _subElement.expressions[0]
          }
          retour += this.addExpression(expression)
          retour += '</div>'
          retour = this.addElButtons(retour)
          break

        case 'do':
          retour += '<input class="subElementAttr" data-l1key="subtype" style="display : none;" value="action"/>'
          retour += '<div class="subElementFields">'
          retour += '<legend >{{FAIRE}}</legend>'
          retour += this.getAddButton(_subElement.type)
          retour += '</div>'
          retour += '<div class="expressions">'
          if (isset(_subElement.expressions)) {
            for (var k in _subElement.expressions) {
              retour += this.addExpression(_subElement.expressions[k])
            }
          }
          retour += '</div>'
          break

        case 'code':
          retour += '<input class="subElementAttr" data-l1key="subtype" style="display : none;" value="action"/>'
          retour += '<div>'
          retour += '<i class="bt_sortable fas fa-arrows-alt-v pull-left cursor"></i>'
          if (!isset(_subElement.options) || !isset(_subElement.options.collapse) || _subElement.options.collapse == 0) {
            retour += '<a class="bt_collapse cursor subElementAttr" title="{{Masquer ce bloc.<br>Ctrl+click: tous.}}" data-l1key="options" data-l2key="collapse" value="0"><i class="far fa-eye"></i></a>'
          } else {
            retour += '<a class="bt_collapse cursor subElementAttr" title="{{Afficher ce bloc.<br>Ctrl+click: tous.}}" data-l1key="options" data-l2key="collapse" value="1"><i class="far fa-eye-slash"></i></a>'
          }
          if (!isset(_subElement.options) || !isset(_subElement.options.enable) || _subElement.options.enable == 1) {
            retour += '<input type="checkbox" class="subElementAttr" data-l1key="options" data-l2key="enable" checked title="{{Décocher pour désactiver l\'élément}}" />'
          } else {
            retour += '<input type="checkbox" class="subElementAttr" data-l1key="options" data-l2key="enable" title="{{Décocher pour désactiver l\'élément}}" />'
          }
          retour += '</div>'
          retour += '<div>'
          retour += '<legend >{{CODE}}</legend>'
          var expression = {
            type: 'code'
          }
          if (isset(_subElement.expressions) && isset(_subElement.expressions[0]) && typeof _subElement.expressions[0].expression == "string") {
            expression = _subElement.expressions[0]
            retour += '<div class="blocPreview">' + expression.expression.substring(0, 200).HTMLFormat() + '</div>'
          } else {
            retour += '<div class="blocPreview"></div>'
          }
          retour += '</div>'
          retour += '<div class="expressions">'
          retour += this.addExpression(expression)
          retour += '</div>'
          retour = this.addElButtons(retour)
          break

        case 'comment':
          retour += '<input class="subElementAttr" data-l1key="subtype" style="display : none;" value="comment"/>'
          retour += '<div>'
          retour += '<i class="bt_sortable fas fa-arrows-alt-v pull-left cursor"></i>'
          if (!isset(_subElement.options) || !isset(_subElement.options.collapse) || _subElement.options.collapse == 0) {
            retour += '<a class="bt_collapse cursor subElementAttr" title="{{Masquer ce bloc.<br>Ctrl+click: tous.}}" data-l1key="options" data-l2key="collapse" value="0"><i class="far fa-eye"></i></a>'
          } else {
            retour += '<a class="bt_collapse cursor subElementAttr" title="{{Afficher ce bloc.<br>Ctrl+click: tous.}}" data-l1key="options" data-l2key="collapse" value="1"><i class="far fa-eye-slash"></i></a>'
          }
          retour += '</div>'
          retour += '<div>'
          retour += '<legend >{{COMMENTAIRE}}</legend>'
          var expression = {
            type: 'comment'
          }
          if (isset(_subElement.expressions) && isset(_subElement.expressions[0])) {
            expression = _subElement.expressions[0]
            if (typeof expression.expression === 'object') {
              expression.expression = JSON.stringify(expression.expression, null, 2)
            }
            var txt = expression.expression.substring(0, 200).HTMLFormat()
            txt = '<b>' + txt.split('\n')[0] + '</b>' + txt.replace(txt.split('\n')[0], '')
            retour += '<div class="blocPreview">' + txt + '</div>'
          } else {
            retour += '<div class="blocPreview"></div>'
          }
          retour += '</div>'
          retour += '<div class="expressions">'
          retour += this.addExpression(expression)
          retour += '</div>'
          retour = this.addElButtons(retour)
          break

        case 'action':
          retour += '<input class="subElementAttr" data-l1key="subtype" style="display : none;" value="action"/>'
          retour += '<div>'
          retour += '<i class="bt_sortable fas fa-arrows-alt-v pull-left cursor"></i>'
          if (!isset(_subElement.options) || !isset(_subElement.options.collapse) || _subElement.options.collapse == 0) {
            retour += '<a class="bt_collapse cursor subElementAttr" title="{{Masquer ce bloc.<br>Ctrl+click: tous.}}" data-l1key="options" data-l2key="collapse" value="0"><i class="far fa-eye"></i></a>'
          } else {
            retour += '<a class="bt_collapse cursor subElementAttr" title="{{Afficher ce bloc.<br>Ctrl+click: tous.}}" data-l1key="options" data-l2key="collapse" value="1"><i class="far fa-eye-slash"></i></a>'
          }
          if (!isset(_subElement.options) || !isset(_subElement.options.enable) || _subElement.options.enable == 1) {
            retour += '<input type="checkbox" class="subElementAttr" data-l1key="options" data-l2key="enable" checked title="{{Décocher pour désactiver l\'élément}}" />'
          } else {
            retour += '<input type="checkbox" class="subElementAttr" data-l1key="options" data-l2key="enable" title="{{Décocher pour désactiver l\'élément}}" />'
          }
          retour += '<legend class="legendHidden">{{ACTION}}</legend>'
          if (isset(_subElement.expressions) && isset(_subElement.expressions[0])) {
            expression = _subElement.expressions[0]
            if (expression.type == 'element' && isset(expression.element.subElements) && isset(expression.element.subElements[0]) && isset(expression.element.subElements[0].expressions) && isset(expression.element.subElements[0].expressions[0])) {
              retour += '<div class="blocPreview">' + expression.element.subElements[0].expressions[0].expression.substring(0, 200).HTMLFormat() + '</div>'
            } else {
              try { retour += '<div class="blocPreview">' + _subElement.expressions[0].expression.substring(0, 200).HTMLFormat() + '</div>' } catch (e) { }
            }
          } else {
            retour += '<div class="blocPreview"></div>'
          }
          retour += '</div>'
          retour += '<div class="subElementFields">'
          retour += '<legend >{{ACTION}}</legend><br/>'
          retour += this.getAddButton(_subElement.type)
          retour += '</div>'
          retour += '<div class="expressions">'
          if (isset(_subElement.expressions)) {
            for (var k in _subElement.expressions) {
              retour += this.addExpression(_subElement.expressions[k])
            }
          }
          retour += '</div>'
          retour = this.addElButtons(retour)
          break
      }
      retour += '</div>'
      return retour
    },
    addElButtons: function(_retour) {
      _retour += '  <div><i class="fas fa-minus-circle pull-right cursor bt_removeElement" title="{{Supprimer ce bloc.<br>Ctrl+Click: Supprimer sans confirmation.}}"></i></div>'
      _retour += '  <div><i class="fas fa-copy pull-right cursor bt_copyElement" title="{{Copier ce bloc.<br>Ctrl+Click: Couper ce bloc.}}"></i></div>'
      _retour += '  <div><i class="fas fa-paste pull-right cursor bt_pasteElement" title="{{Coller un bloc après celui-ci.<br>Ctrl+Click: remplacer ce bloc par le bloc copié.}}"></i></div>'
      return _retour
    },
    addElement: function(_element) {
      if (!isset(_element)) return
      if (!isset(_element.type) || _element.type == '') return ''

      var elementClass = ''
      switch (_element.type) {
        case 'if':
          elementClass = 'elementIF'
          break
        case 'for':
          elementClass = 'elementFOR'
          break
        case 'in':
          elementClass = 'elementIN'
          break
        case 'at':
          elementClass = 'elementAT'
          break
        case 'code':
          elementClass = 'elementCODE'
          break
        case 'comment':
          elementClass = 'elementCOM'
          break
        case 'action':
          elementClass = 'elementACTION'
      }

      var div = '<div class="element ' + elementClass + '">'

      div += '<input class="elementAttr" data-l1key="id" style="display : none;" value="' + init(_element.id) + '"/>'
      div += '<input class="elementAttr" data-l1key="type" style="display : none;" value="' + init(_element.type) + '"/>'
      switch (_element.type) {
        case 'if':
          if (isset(_element.subElements) && isset(_element.subElements)) {
            for (var j in _element.subElements) {
              div += this.addSubElement(_element.subElements[j])
            }
          } else {
            div += this.addSubElement({
              type: 'if'
            })
            div += this.addSubElement({
              type: 'then'
            })
            div += this.addSubElement({
              type: 'else'
            })
          }
          break
        case 'for':
          if (isset(_element.subElements) && isset(_element.subElements)) {
            for (var j in _element.subElements) {
              div += this.addSubElement(_element.subElements[j])
            }
          } else {
            div += this.addSubElement({
              type: 'for'
            })
            div += this.addSubElement({
              type: 'do'
            })
          }
          break
        case 'in':
          if (isset(_element.subElements) && isset(_element.subElements)) {
            for (var j in _element.subElements) {
              div += this.addSubElement(_element.subElements[j])
            }
          } else {
            div += this.addSubElement({
              type: 'in'
            })
            div += this.addSubElement({
              type: 'do'
            })
          }
          break
        case 'at':
          if (isset(_element.subElements) && isset(_element.subElements)) {
            for (var j in _element.subElements) {
              div += this.addSubElement(_element.subElements[j])
            }
          } else {
            div += this.addSubElement({
              type: 'at'
            })
            div += this.addSubElement({
              type: 'do'
            })
          }
          break
        case 'code':
          if (isset(_element.subElements) && isset(_element.subElements)) {
            for (var j in _element.subElements) {
              div += this.addSubElement(_element.subElements[j])
            }
          } else {
            div += this.addSubElement({
              type: 'code'
            })
          }
          break
        case 'comment':
          if (isset(_element.subElements) && isset(_element.subElements)) {
            for (var j in _element.subElements) {
              div += this.addSubElement(_element.subElements[j])
            }
          } else {
            div += this.addSubElement({
              type: 'comment'
            })
          }
          break
        case 'action':
          if (isset(_element.subElements) && isset(_element.subElements)) {
            for (var j in _element.subElements) {
              div += this.addSubElement(_element.subElements[j])
            }
          } else {
            div += this.addSubElement({
              type: 'action'
            })
          }
          break
      }
      div += '</div>'
      return div
    },
    getElement: function(dom_element) {
      var element = dom_element.getJeeValues('.elementAttr', 1)
      if (element.length == 0) return
      element = element[0]
      element.subElements = []

      //Single html element ?:
      if (!dom_element.length) {
        dom_element = [dom_element]
      }

      var subElement, expression_dom, expression, id
      dom_element.forEach(function(dom_el) {
        dom_el.findAtDepth('.subElement', 2).forEach(function(_el) {
          subElement = _el.getJeeValues('.subElementAttr', 2)[0]
          subElement.expressions = []
          expression_dom = _el.querySelector(':scope > .expressions')
          if (expression_dom.length == 0) {
            expression_dom = this.querySelector(':scope > legend').findAtDepth('.expressions', 2)
          }

          if (expression_dom != null) {
            expression_dom.querySelectorAll(':scope > .expression').forEach(function(_exp) {
              expression = _exp.getJeeValues('.expressionAttr', 3)[0]
              if (expression.type == 'element') {
                expression.element = jeeP.getElement(_exp.findAtDepth('.element', 2))
              }
              if (subElement.type == 'code') {
                id = _exp.querySelector('.expressionAttr[data-l1key="expression"]').getAttribute('id')
                if (id != undefined && isset(jeeFrontEnd.scenario.editors[id])) {
                  expression.expression = jeeFrontEnd.scenario.editors[id].getValue()
                }
              }
              subElement.expressions.push(expression)
            })
          }
          element.subElements.push(subElement)
        })
      })
      return element
    },
    getAddButton: function(_type, _caret) {
      if (!isset(_caret)) _caret = false
      var retour = ''
      if (_caret) {
        retour += '<div class="input-group">'
        retour += '<button class="bt_showElse btn btn-xs btn-default roundedLeft" type="button" data-toggle="dropdown" title="{{Afficher/masquer le bloc Sinon}}" aria-haspopup="true" aria-expanded="true">'
        retour += '<i class="fas fa-sort-up"></i>'
        retour += '</button>'
        retour += '<span class="input-group-btn">'
      }
      retour += '<div class="dropdown">'
      if (_caret) {
        retour += '<button class="btn btn-default dropdown-toggle roundedRight" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">'
      } else {
        retour += '<button class="btn btn-xs btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">'
      }
      retour += '<i class="fas fa-plus-circle"></i> {{Ajouter}}'
      retour += '<span class="caret"></span>'
      retour += '</button>'

      retour += '<ul class="dropdown-menu">'
      retour += '<li><a class="bt_addAction">{{Action}}</a></li>'
      retour += '<li><a class="fromSubElement" data-type="if">{{Bloc Si/Alors/Sinon}}</a></li>'
      if (_type != 'action' && _type != 'if' && _type != 'in' && _type != 'for' && _type != 'at') {
        retour += '<li><a class="fromSubElement" data-type="action">{{Bloc Action}}</a></li>'
      }
      retour += '<li><a class="fromSubElement" data-type="for">{{Bloc Boucle}}</a></li>'
      retour += '<li><a class="fromSubElement" data-type="in">{{Bloc Dans}}</a></li>'
      retour += '<li><a class="fromSubElement" data-type="at">{{Bloc A}}</a></li>'
      retour += '<li><a class="fromSubElement" data-type="code">{{Bloc Code}}</a></li>'
      retour += '<li><a class="fromSubElement" data-type="comment">{{Bloc Commentaire}}</a></li>'
      retour += '</ul>'

      retour += '</div>'
      if (_caret) {
        retour += '</span>'
        retour += '</div>'
      }
      return retour
    },
    updateAccordionName: function() {
      document.querySelectorAll('a.accordion-toggle').forEach(function(_acc) {
        var name = _acc.getAttribute('data-groupname')
        var num = _acc.closest('div.panel').querySelectorAll('div.scenarioDisplayCard').length
        var newName = name + ' - ' + num + ' ' + (num > 1 ? '{{scénarios}}' : '{{scénario}}')
        _acc.textContent = newName
      })
    },
    //misc
    getSelectCmdExpressionMessage: function(subType, cmdHumanName) {
      if (!['numeric', 'string', 'binary'].includes(subType)) return '{{Aucun choix possible}}'

      var message = '<div class="row">'
      message += '<div class="col-md-12">'
      message += '<form class="form-horizontal" onsubmit="return false;">'
      message += '<div class="form-group">'
      message += '<label class="col-xs-5 control-label" >' + cmdHumanName + ' {{est}}</label>'

      if (subType == 'numeric') {
        message += '<div class="col-xs-3">'
        message += '  <select class="conditionAttr form-control" data-l1key="operator">'
        message += '    <option value="===">{{égal}}</option>'
        message += '    <option value=">">{{supérieur}}</option>'
        message += '    <option value="<">{{inférieur}}</option>'
        message += '    <option value="!=">{{différent}}</option>'
        message += '    <option value=">=">{{supérieur ou égal}}</option>'
        message += '    <option value="<=">{{inférieur ou égal}}</option>'
        message += '  </select>'
        message += '</div>'
        message += '<div class="col-xs-4">'
        message += '<input class="conditionAttr form-control radio-inline" data-l1key="operande" style="width: calc(100% - 45px);" />'
        message += '<button onclick="jeeFrontEnd.scenario.selectCmdFromModal(event)" type="button" class="btn btn-default cursor bt_selectCmdFromModal" style="margin-top: -5px;"><i class="fas fa-list-alt"></i></button>'
        message += '</div>'
        message += '</div>'
      }

      if (subType == 'string') {
        message += '<div class="col-xs-2">'
        message += '  <select class="conditionAttr form-control" data-l1key="operator">'
        message += '    <option value="==">{{égal}}</option>'
        message += '    <option value="matches">{{contient}}</option>'
        message += '    <option value="!=">{{différent}}</option>'
        message += '  </select>'
        message += '</div>'
        message += '<div class="col-xs-4">'
        message += '<input class="conditionAttr form-control radio-inline" data-l1key="operande" style="width: calc(100% - 45px);" />'
        message += '<button onclick="jeeFrontEnd.scenario.selectCmdFromModal(event)" type="button" class="btn btn-default cursor bt_selectCmdFromModal" style="margin-top: -5px;"><i class="fas fa-list-alt"></i></button>'
        message += '</div>'
        message += '</div>'
      }

      if (subType == 'binary') {
        message += '<div class="col-xs-3">'
        message += '<input class="conditionAttr" data-l1key="operator" value="==" style="display : none;" />'
        message += '  <select class="conditionAttr form-control" data-l1key="operande">'
        message += '    <option value="1">{{Ouvert}}</option>'
        message += '    <option value="0">{{Fermé}}</option>'
        message += '    <option value="1">{{Allumé}}</option>'
        message += '    <option value="0">{{Eteint}}</option>'
        message += '    <option value="1">{{Déclenché}}</option>'
        message += '    <option value="0">{{Au repos}}</option>'
        message += '  </select>'
        message += '</div>'
        message += '</div>'
      }

      message += '<div class="form-group">'
      message += '<label class="col-xs-5 control-label" >{{Ensuite}}</label>'
      message += '<div class="col-xs-3">'
      message += '  <select class="conditionAttr form-control" data-l1key="next">'
      message += '    <option value="">{{rien}}</option>'
      if (jeeFrontEnd.language == "fr_FR") {
        message += '    <option value="&&">&& {{et}}</option>'
        message += '    <option value="||">|| {{ou}}</option>'
      } else {
        message += '    <option value="&&">&& (AND)</option>'
        message += '    <option value="||">|| (OR)</option>'
      }
      message += '  </select>'
      message += '</div>'
      message += '</div>'
      message += '</div></div>'
      message += '</form></div></div>'
      return message
    },
    selectCmdFromModal: function(event) { //Condition chaining modals outside page_container, called from bt_selectCmdFromModal onclick()
      var modal = event.target.closest('.jeeDialogPrompt')
      modal.style.display = 'none'
      jeedom.cmd.getSelectModal({
        cmd: {
          type: 'info'
        },
        returnCancel: 1
      }, function(result) {
        modal.style.display = 'block'
        if (isset(result.human)) modal.querySelector('input[data-l1key="operande"]').value = result.human
      })
    },
    //Undo management
    reloadStack: function(loadStack) {
      document.getElementById('div_scenarioElement').empty()
      this.actionOptions = []
      var elements = ''
      for (var i in loadStack) {
        elements += this.addElement(loadStack[i])
      }
      document.getElementById('div_scenarioElement').empty().html(elements, true)
      this.setRootElements()
      this.updateElseToggle()
      this.setScenarioActionsOptions()
    },
    setUndoStack: function(state = 0) {
      this.syncEditors()
      this.bt_undo.removeClass('disabled')
      this.bt_redo.addClass('disabled')

      var newStack = []
      document.getElementById('root').querySelectorAll(':scope > .expression').forEach(_exp => {
        newStack.push(jeeP.getElement(_exp.querySelector('div.element')))
      })

      if (newStack == this.undoStack[state - 1]) return
      if (state == 0) {
        state = this.undoState = this.undoStack.length
        this.reDo = 0
      }
      this.undoStack[state] = newStack
      //limit stack:
      if (state >= this.firstState + this.undoLimit) {
        this.firstState += 1
        this.undoStack[this.firstState - 1] = 0
      }
    },
    undo: function() {
      if (this.undoState < this.firstState) {
        return
      }
      try {
        var loadState = this.undoState
        if (this.reDo == 0) this.setUndoStack(this.undoState + 1)
        this.reloadStack(this.undoStack[loadState])
        this.undoState -= 1

        if (this.undoState < this.firstState) this.bt_undo.addClass('disabled')
        this.bt_redo.removeClass('disabled')
      } catch (error) {
        console.warn('undo ERROR:', error)
      }
      setTimeout(function() { jeedomUtils.initTooltips() }, 500)
      this.updateElementCollapse()
      this.setRootElements()
      this.setSortables()
      jeedom.scenario.setAutoComplete()
      this.resetEditors()
    },
    redo: function() {
      this.reDo = 1
      if (this.undoState < this.firstState - 1 || this.undoState + 2 >= this.undoStack.length) {
        return
      }
      this.bt_undo.removeClass('disabled')
      try {
        var loadState = this.undoState + 2
        this.reloadStack(this.undoStack[loadState])
        this.undoState += 1

        if (this.undoState < this.firstState - 1 || this.undoState + 2 >= this.undoStack.length) this.bt_redo.addClass('disabled')
      } catch (error) {
        console.warn('redo ERROR:', error)
      }
      setTimeout(function() { jeedomUtils.initTooltips() }, 500)
      this.updateElementCollapse()
      this.setRootElements()
      this.setSortables()
      jeedom.scenario.setAutoComplete()
      this.resetEditors()
    },
    resetUndo: function() {
      this.undoStack = []
      this.undoState = -1
      this.firstState = 0
      this.undoLimit = 10
      this.bt_undo.addClass('disabled')
      this.bt_redo.addClass('disabled')
    },
    //Code Editors:
    setEditors: function() {
      var expression, code
      document.querySelectorAll('.expressionAttr[data-l1key="type"][value="code"]').forEach(function(elCode) {
        expression = elCode.closest('.expression')
        code = expression.querySelector('.expressionAttr[data-l1key="expression"]')
        elCode.querySelector('.blocPreview')?.html(code.value)
        if (code.getAttribute('id') == undefined && code.isVisible()) {
          code.uniqueId()
          var id = code.getAttribute('id')
          setTimeout(function() {
            jeeP.editors[id] = CodeMirror.fromTextArea(document.getElementById(id), {
              lineNumbers: true,
              lineWrapping: true,
              mode: 'text/x-php',
              matchBrackets: true,
              viewportMargin: Infinity,
              foldGutter: true,
              gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"]
            })
            jeeP.editors[id].setOption("extraKeys", {
              "Ctrl-Y": cm => CodeMirror.commands.foldAll(cm),
              "Ctrl-I": cm => CodeMirror.commands.unfoldAll(cm)
            })
          }, 1)
        }
      })
    },
    resetEditors: function() {
      this.editors = []
      var expression, code
      document.querySelectorAll('.expressionAttr[data-l1key="type"][value="code"]').forEach(elCode => {
        expression = elCode.closest('.expression')
        code = expression.querySelector('.expressionAttr[data-l1key="expression"]')
        code.removeAttribute('id')
        code.seen()
        expression.querySelectorAll('.CodeMirror.CodeMirror-wrap').remove()
      })
      this.setEditors()
    },
    syncEditors: function() {
      var expression, code, id
      document.querySelectorAll('.expressionAttr[data-l1key="type"][value="code"]').forEach(function(elCode) {
        expression = elCode.closest('.expression')
        code = expression.querySelector('.expressionAttr[data-l1key="expression"]')
        id = code.getAttribute('id')
        if (isset(jeeP.editors[id])) code.html(jeeP.editors[id].getValue()) //codemirror getValue()!
      })
    },
  }
}

jeeFrontEnd.scenario.init()


//Register events on top of page container:
document.registerEvent('keydown', function(event) {
  if (jeedomUtils.getOpenedModal()) return

  if ((event.ctrlKey || event.metaKey) && event.which == 83) { //s
    event.preventDefault()
    if (document.getElementById('bt_saveScenario').isVisible()) {
      jeeP.saveScenario()
      return
    }
  }

  if ((event.ctrlKey || event.metaKey) && event.which == 76) { //l
    event.preventDefault()
    jeeDialog.dialog({
      id: 'jee_modal',
      title: "{{Log d'exécution du scénario}}",
      contentUrl: 'index.php?v=d&modal=scenario.log.execution&scenario_id=' + document.querySelector('.scenarioAttr[data-l1key=id]').jeeValue()
    })
    return
  }

  if ((event.ctrlKey || event.metaKey) && event.shiftKey && event.which == 90) { //z
    event.preventDefault()
    jeeP.undo()
    jeeP.PREV_FOCUS = null
    return
  }

  if ((event.ctrlKey || event.metaKey) && event.shiftKey && event.which == 89) { //y
    event.preventDefault()
    jeeP.redo()
    jeeP.PREV_FOCUS = null
    return
  }
})

//Manage events outside parents delegations:
document.getElementById('bt_addScenario').addEventListener('click', function(event) {
  jeeDialog.prompt("{{Nom du scénario}} ?", function(result) {
    if (result !== null) {
      jeedom.scenario.save({
        scenario: {
          name: result
        },
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          var vars = getUrlVars()
          var url = 'index.php?'
          for (var i in vars) {
            if (i != 'id' && i != 'saveSuccessFull' && i != 'removeSuccessFull') {
              url += i + '=' + vars[i].replace('#', '') + '&'
            }
          }
          url += 'id=' + data.id + '&saveSuccessFull=1'
          jeeFrontEnd.modifyWithoutSave = false
          jeeP.resetUndo()
          jeedomUtils.loadPage(url)
        }
      })
    }
  })
})

document.getElementById('bt_changeAllScenarioState').addEventListener('click', function(event) {
  var _target = event.target.closest('#bt_changeAllScenarioState')
  if (_target.getAttribute('data-state') == '0') {
    var msg = '{{Êtes-vous sûr de vouloir désactiver les scénarios ?}}'
  } else {
    var msg = '{{Êtes-vous sûr de vouloir activer les scénarios ?}}'
  }

  jeeDialog.confirm(msg, function(result) {
    if (result) {
      jeedom.config.save({
        configuration: {
          enableScenario: _target.getAttribute('data-state')
        },
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function() {
          jeedomUtils.loadPage('index.php?v=d&p=scenario')
        }
      })
    }
  })
})

document.getElementById('bt_clearAllLogs').addEventListener('click', function(event) {
  jeeDialog.confirm("{{Êtes-vous sûr de vouloir supprimer tous les logs des scénarios ?}}", function(result) {
    if (result) {
      jeedom.scenario.clearAllLogs({
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          jeedomUtils.showAlert({
            message: "{{Logs des scénarios supprimés.}}",
            level: 'success'
          })
        }
      })
    }
  })
})

document.getElementById('bt_showScenarioSummary').addEventListener('click', function(event) {
  jeeDialog.dialog({
    id: 'jee_modal',
    title: "{{Vue d'ensemble des scénarios}}",
    contentUrl: 'index.php?v=d&modal=scenario.summary'
  })
})

document.getElementById('bt_scenarioThumbnailDisplay').addEventListener('click', function(event) {
  if (jeedomUtils.checkPageModified()) return
  document.getElementById('div_editScenario').unseen()
  document.getElementById('scenarioThumbnailDisplay').seen()
  jeedomUtils.addOrUpdateUrl('id', null, '{{Scénario}} - ' + JEEDOM_PRODUCT_NAME)
})

document.getElementById('bt_generalTab').addEventListener('click', function(event) {
  document.getElementById('bt_resetInsideScenarioSearch').addClass('disabled')
  document.getElementById('in_searchInsideScenario').setAttribute('disabled', '')
})

document.getElementById('bt_scenarioTab').addEventListener('click', function(event) {
  document.getElementById('bt_resetInsideScenarioSearch').removeClass('disabled')
  document.getElementById('in_searchInsideScenario').removeAttribute('disabled')
  setTimeout(function() {
    jeeP.setEditors()
    jeedomUtils.taAutosize()
    jeeP.updateElseToggle()
  }, 50)
})

//Specials
document.querySelector('.scenarioAttr[data-l1key="mode"]').addEventListener('change', function(event) {
  document.getElementById('bt_addSchedule').removeClass('roundedRight')
  document.getElementById('bt_addTrigger').removeClass('roundedRight')
  if (event.target.value == 'schedule' || event.target.value == 'all') {
    document.querySelectorAll('.scheduleDisplay').seen()
    document.getElementById('bt_addSchedule').seen()
  } else {
    document.querySelectorAll('.scheduleDisplay').unseen()
    document.getElementById('bt_addSchedule').unseen()
    document.getElementById('bt_addTrigger').addClass('roundedRight')
  }
  if (event.target.value == 'provoke' || event.target.value == 'all') {
    document.querySelectorAll('.provokeDisplay').seen()
    document.getElementById('bt_addTrigger').seen()
  } else {
    document.querySelectorAll('.provokeDisplay').unseen()
    document.getElementById('bt_addTrigger').unseen()
    document.getElementById('bt_addSchedule').addClass('roundedRight')
  }
  if (event.target.value == 'all') {
    document.getElementById('bt_addSchedule').addClass('roundedRight')
  }
})

var select = document.getElementById('in_addElementType')
var input = document.getElementById('in_addElementTypeFilter')
var allOptions = Array.from(select.options)

function filterOptions() {
  const text = input.value.trim().toLowerCase().stripAccents()

  select.innerHTML = ''

  allOptions
    .filter(option => {
      const optionText = option.textContent.toLowerCase().stripAccents()
      return text === '' || optionText.includes(text)
    })
    .forEach(option => {
      select.add(option.cloneNode(true))
    })
}

input.addEventListener('input', filterOptions) 

select.addEventListener('change', function(event) {
  document.querySelectorAll('.addElementTypeDescription').unseen()
  document.querySelectorAll('.addElementTypeDescription.' + this.jeeValue()).seen()
})

document.getElementById('in_searchInsideScenario').addEventListener('keyup', function(event) {
  var search = this.value
  document.querySelectorAll('#div_scenarioElement .insideSearch').removeClass('insideSearch')
  document.querySelectorAll('#div_scenarioElement div.CodeMirror.CodeMirror-wrap').forEach(_code => {
    _code.CodeMirror.setCursor(0)
  })
  if (search == '' || search.length < 3) {
    document.querySelectorAll('i.fa-eye-slash').forEach(_bt => {
      _bt.closest('.element')?.addClass('elementCollapse')
    })
    return
  }
  search = jeedomUtils.normTextLower(search)

  //search code blocks:
  var cmEditor, code, cursor
  document.querySelectorAll('#div_scenarioElement div.elementCODE').forEach(_code => {
    try {
      cmEditor = _code.querySelector('div.CodeMirror.CodeMirror-wrap').CodeMirror
      code = jeedomUtils.normTextLower(cmEditor.getValue())
      if (code.includes(search)) {
        _code.removeClass('elementCollapse')
        cursor = cmEditor.getSearchCursor(search, CodeMirror.Pos(cmEditor.firstLine(), 0), {
          caseFold: true,
          multiline: true
        })
        if (cursor.find(false)) {
          cmEditor.setSelection(cursor.from(), cursor.to())
        }
      } else {
        _code.addClass('elementCollapse')
        cmEditor.setCursor(0)
      }
    } catch { }
  })
  //search in expressions:
  var text
  document.querySelectorAll('#div_scenarioElement div.element:not(.elementCODE) .expressionAttr').forEach(_expr => {
    text = jeedomUtils.normTextLower(_expr.value)
    if (text.includes(search)) {
      _expr.addClass('insideSearch')
      _expr.closestAll('.element').forEach(_parent => {
        _parent.removeClass('elementCollapse')
      })
    }
  })
})


/*Events delegations
*/

//_________________Root page events:
document.getElementById('in_searchScenario').addEventListener('keyup', function() {
  var search = this.value
  if (search == '') {
    document.querySelectorAll('#accordionScenario .accordion-toggle:not(.collapsed)').forEach(_panel => { _panel.click() })
    document.querySelectorAll('.scenarioDisplayCard').seen()
    return
  }
  search = jeedomUtils.normTextLower(search)
  var not = search.startsWith(":not(")
  if (not) {
    search = search.replace(':not(', '')
  }
  document.querySelectorAll('#accordionScenario .accordion-toggle').forEach(_panel => { _panel.setAttribute('data-show', 0) })
  document.querySelectorAll('.scenarioDisplayCard').unseen()
  var match, text

  document.querySelectorAll('.scenarioDisplayCard .name').forEach(scName => {
    match = false
    text = jeedomUtils.normTextLower(scName.textContent)
    if (text.includes(search)) {
      match = true
    }

    if (not) match = !match
    if (match) {
      scName.closest('.scenarioDisplayCard').seen()
      scName.closest('.panel').querySelector('.accordion-toggle').setAttribute('data-show', 1)
    }

  })
  document.querySelectorAll('.accordion-toggle.collapsed[data-show="1"]').forEach(_panel => { _panel.click() })
  document.querySelectorAll('.accordion-toggle:not(.collapsed)[data-show="0"]').forEach(_panel => { _panel.click() })
})

document.getElementById('scenarioThumbnailDisplay').addEventListener('click', function(event) {
  var _target = null
  if (!event.target.matches('a.accordion-toggle')) {
    event.preventDefault()
    event.stopImmediatePropagation()
    event.stopPropagation()
  }

  if (_target = event.target.closest('#bt_openAll')) {
    document.querySelectorAll('#accordionScenario .accordion-toggle.collapsed').forEach(_panel => { _panel.click() })
    return
  }

  if (_target = event.target.closest('#bt_closeAll')) {
    document.querySelectorAll('#accordionScenario .accordion-toggle:not(.collapsed)').forEach(_panel => { _panel.click() })
    return
  }

  if (_target = event.target.closest('#bt_resetScenarioSearch')) {
    document.getElementById('in_searchScenario').jeeValue('').triggerEvent('keyup')
    return
  }

  if (_target = event.target.closest('#accordionScenario .bt_ViewLog')) {
    var id = _target.closest('.scenarioDisplayCard').getAttribute('data-scenario_id')
    jeeDialog.dialog({
      id: 'jee_modal2',
      title: "{{Log d'exécution du scénario}}",
      contentUrl: 'index.php?v=d&modal=scenario.log.execution&scenario_id=' + id
    })
    return
  }

  if (_target = event.target.closest('.scenarioDisplayCard')) {
    var id = _target.getAttribute('data-scenario_id')
    if ((isset(event.detail) && event.detail.ctrlKey) || event.ctrlKey || event.metaKey) {
      var url = '/index.php?v=d&p=scenario&id=' + id
      window.open(url).focus()
    } else {
      document.getElementById('scenarioThumbnailDisplay').unseen()
      jeeP.printScenario(id)
    }
    return
  }
})

document.getElementById('scenarioThumbnailDisplay').addEventListener('mouseup', function(event) {
  var _target = null
  if (_target = event.target.closest('.scenarioDisplayCard')) {
    if (event.which == 2) {
      event.preventDefault()
      var id = _target.getAttribute('data-scenario_id')
      document.querySelector('.scenarioDisplayCard[data-scenario_id="' + id + '"]').triggerEvent('click', { detail: { ctrlKey: true } })
    }
    return
  }
})


//_________________Floating bar events:
document.getElementById('div_editScenario').querySelector('div.floatingbar').addEventListener('click', function(event) {
  var _target = null
  if (_target = event.target.closest('#bt_logScenario')) {
    jeeDialog.dialog({
      id: 'jee_modal',
      title: "{{Log d'exécution du scénario}}",
      contentUrl: 'index.php?v=d&modal=scenario.log.execution&scenario_id=' + document.querySelector('.scenarioAttr[data-l1key="id"]').jeeValue()
    })
    return
  }

  if (_target = event.target.closest('#bt_copyScenario')) {
    jeeDialog.prompt({
      title: "{{Nom du scénario}} ?",
      value: document.querySelector('.scenarioAttr[data-l1key="name"]').jeeValue() + ' {{copie}}'
    }, function(result) {
      if (result !== null) {
        jeedom.scenario.copy({
          id: document.querySelector('.scenarioAttr[data-l1key="id"]').jeeValue(),
          name: result,
          error: function(error) {
            jeedomUtils.showAlert({
              message: error.message,
              level: 'danger'
            })
          },
          success: function(data) {
            jeedomUtils.loadPage('index.php?v=d&p=scenario&id=' + data.id)
          }
        })
      }
    })
    return
  }

  if (_target = event.target.closest('#bt_graphScenario')) {
    jeeDialog.dialog({
      id: 'jee_modal',
      title: "{{Graphique de lien(s)}}",
      contentUrl: 'index.php?v=d&modal=graph.link&filter_type=scenario&filter_id=' + document.querySelector('.scenarioAttr[data-l1key="id"]').jeeValue()
    })
    return
  }

  if (_target = event.target.closest('#bt_editJsonScenario')) {
    jeeDialog.dialog({
      id: 'jee_modal',
      title: "{{Edition texte scénarios}}",
      contentUrl: 'index.php?v=d&modal=scenario.jsonEdit&id=' + document.querySelector('.scenarioAttr[data-l1key="id"]').jeeValue()
    })
    return
  }

  if (_target = event.target.closest('#bt_exportScenario')) {
    jeeDialog.dialog({
      id: 'jee_modal',
      title: "{{Export du scénario}}",
      contentUrl: 'index.php?v=d&modal=scenario.export&scenario_id=' + document.querySelector('.scenarioAttr[data-l1key="id"]').jeeValue()
    })
    return
  }

  if (_target = event.target.closest('#bt_templateScenario')) {
    jeeDialog.dialog({
      id: 'jee_modal',
      title: "{{Template de scénario}}",
      contentUrl: 'index.php?v=d&modal=scenario.template&scenario_id=' + document.querySelector('.scenarioAttr[data-l1key="id"]').jeeValue()
    })
    return
  }

  if (_target = event.target.closest('#bt_runScenario')) {
    jeedomUtils.hideAlert()
    var scenario_id = document.querySelector('.scenarioAttr[data-l1key="id"]').jeeValue()
    var logmode = document.querySelector('select[data-l2key="logmode"]').jeeValue()
    if (event.ctrlKey || event.metaKey) {
      jeeP.saveScenario(function() {
        jeedom.scenario.changeState({
          id: scenario_id,
          state: 'start',
          error: function(error) {
            jeedomUtils.showAlert({
              message: error.message,
              level: 'danger'
            })
          },
          success: function() {
            jeedomUtils.showAlert({
              message: '{{Lancement du scénario réussi}}',
              level: 'success'
            })
            if (logmode != 'none') {
              jeeDialog.dialog({
                id: 'jee_modal',
                title: "{{Log d'exécution du scénario}}",
                contentUrl: 'index.php?v=d&modal=scenario.log.execution&scenario_id=' + scenario_id
              })
            }
          }
        })
      })
    } else {
      jeedom.scenario.changeState({
        id: document.querySelector('.scenarioAttr[data-l1key="id"]').jeeValue(),
        state: 'start',
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function() {
          jeedomUtils.showAlert({
            message: '{{Lancement du scénario réussi}}',
            level: 'success'
          })
        }
      })
    }
    return
  }

  if (_target = event.target.closest('#bt_stopScenario')) {
    jeedom.scenario.changeState({
      id: document.querySelector('.scenarioAttr[data-l1key="id"]').jeeValue(),
      state: 'stop',
      error: function(error) {
        jeedomUtils.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function() {
        jeedomUtils.showAlert({
          message: '{{Arrêt du scénario réussi}}',
          level: 'success'
        })
      }
    })
    return
  }

  if (_target = event.target.closest('#bt_saveScenario')) {
    jeeP.saveScenario()
    jeeP.clipboard = null
    return
  }

  if (_target = event.target.closest('#bt_delScenario')) {
    jeedomUtils.hideAlert()
    jeeDialog.confirm('{{Êtes-vous sûr de vouloir supprimer le scénario}} <span style="font-weight: bold ;">' + document.querySelector('.scenarioAttr[data-l1key="name"]').jeeValue() + '</span> ?', function(result) {
      if (result) {
        jeedom.scenario.remove({
          id: document.querySelector('.scenarioAttr[data-l1key="id"]').jeeValue(),
          error: function(error) {
            jeedomUtils.showAlert({
              message: error.message,
              level: 'danger'
            })
          },
          success: function() {
            jeeFrontEnd.modifyWithoutSave = false
            jeeP.resetUndo()
            jeedomUtils.loadPage('index.php?v=d&p=scenario')
          }
        })
      }
    })
    return
  }

  if (_target = event.target.closest('#bt_addScenarioElement')) {
    if (!window.location.href.includes('#scenariotab')) document.getElementById('bt_scenarioTab').click()

    jeeP.addElementSave = {
      expression: false,
      insertAfter: false,
      elementDiv: null
    }
    //is scenario empty:
    if (document.getElementById('root').querySelectorAll(':scope > .expression').length == 0) {
      jeeP.addElementSave.elementDiv = document.getElementById('div_scenarioElement')
      jeeP.addElementSave.elementDiv.querySelector('.span_noScenarioElement')?.remove()
    } else {
      //had focus ?
      if (jeeP.PREV_FOCUS != null && jeeP.PREV_FOCUS.closest('div.element') != null) {
        jeeP.addElementSave.insertAfter = true
        jeeP.addElementSave.elementDiv = jeeP.PREV_FOCUS.closest('div.element')
        if (jeeP.addElementSave.elementDiv.parentNode.getAttribute('id') != 'div_scenarioElement') {
          jeeP.addElementSave.elementDiv = jeeP.addElementSave.elementDiv.closest('.expression')
          jeeP.addElementSave.expression = true
        }
      } else {
        jeeP.addElementSave.elementDiv = document.getElementById('div_scenarioElement')
      }
    }
    input.value = ''
    input.triggerEvent('input')
    jeeDialog.modal(document.getElementById('md_addElement'))._jeeDialog.show() //=> #bt_addElementSave
    return
  }

  if (_target = event.target.closest('#bt_resetInsideScenarioSearch')) {
    if (_target.hasClass('disabled')) return
    var searchField = document.getElementById('in_searchInsideScenario')
    if (_target.getAttribute('data-state') == '0') {
      //show search:
      searchField.seen()
      _target.querySelectorAll('i').removeClass('fa-search').addClass('fa-times')
      _target.setAttribute('data-state', '1')
      //open code blocks for later search:
      document.querySelectorAll('#div_scenarioElement div.elementCODE.elementCollapse').forEach(_code => {
        _code.removeClass('elementCollapse')
        _code.querySelector('textarea[data-l1key="expression"]').show()
      })
      jeeP.setEditors()
      document.querySelectorAll('textarea[data-l1key="expression"]').unseen()
      searchField.focus()
    } else {
      if (searchField.value == '') {
        document.querySelectorAll('i.fa-eye-slash').forEach(_bt => {
          _bt.closest('.element').addClass('elementCollapse')
        })
        _target.querySelector('i').removeClass('fa-times').addClass('fa-search')
        searchField.unseen()
        _target.setAttribute('data-state', '0')
      } else {
        searchField.jeeValue('').triggerEvent('keyup')
      }
    }
    return
  }

  if (_target = event.target.closest('#bt_undo')) {
    if (!jeedomUtils.getOpenedModal()) {
      jeeP.undo()
      jeeP.PREV_FOCUS = null
    }
    return
  }

  if (_target = event.target.closest('#bt_redo')) {
    if (!jeedomUtils.getOpenedModal()) {
      jeeP.redo()
      jeeP.PREV_FOCUS = null
    }
    return
  }
})


//_________________General tab events:
document.getElementById('generaltab').addEventListener('click', function(event) {
  var _target = null
  if (_target = event.target.closest('.scenarioAttr[data-l2key="timeline::enable"]')) {
    if (_target.checked) {
      document.querySelector('.scenarioAttr[data-l2key="timeline::folder"]').seen()
    } else {
      document.querySelector('.scenarioAttr[data-l2key="timeline::folder"]').unseen()
    }
    return
  }

  if (_target = event.target.closest('.scenario_link')) {
    jeedomUtils.hideAlert()
    if ((isset(event.detail) && event.detail.ctrlKey) || event.ctrlKey || event.metaKey) {
      var url = '/index.php?v=d&p=scenario&id=' + event.target.getAttribute('data-scenario_id')
      window.open(url).focus()
    } else {
      document.getElementById('scenarioThumbnailDisplay').unseen()
      jeeP.printScenario(_target.getAttribute('data-scenario_id'))
    }
  }

  if (_target = event.target.closest('.action_link')) {
    jeedomUtils.hideAlert()
    var cmdId = _target.getAttribute('data-cmd_id')
    jeeDialog.dialog({
      id: 'jee_modal2',
      title: '{{Configuration de la commande}}',
      contentUrl: 'index.php?v=d&modal=cmd.configure&cmd_id=' + cmdId
    })
    return
  }

  if (_target = event.target.closest('#bt_chooseIcon')) {
    jeedomUtils.hideAlert()
    var _icon = false
    if (document.querySelector('div[data-l2key="icon"] > i') != null) {
      _icon = document.querySelector('div[data-l2key="icon"] > i').getAttribute('class')
    }
    jeedomUtils.chooseIcon(function(_icon) {
      document.querySelector('.scenarioAttr[data-l1key="display"][data-l2key="icon"]').innerHTML = _icon
    }, {
      icon: _icon
    })
    jeeFrontEnd.modifyWithoutSave = true
    return
  }

  if (_target = event.target.closest('#bt_addTrigger')) {
    jeeP.addTrigger('')
    jeeP.checkNoTriggeringMode()
    jeedomUtils.initTooltips()
    return
  }

  if (_target = event.target.closest('#bt_addSchedule')) {
    jeeP.addSchedule('')
    jeeP.checkNoTriggeringMode()
    jeedomUtils.initTooltips()
    return
  }

  if (_target = event.target.closest('.bt_removeTrigger')) {
    _target.closest('.trigger').remove()
    jeeP.checkNoTriggeringMode()
    return
  }

  if (_target = event.target.closest('.bt_removeSchedule')) {
    _target.closest('.schedule').remove()
    jeeP.checkNoTriggeringMode()
    return
  }

  if (_target = event.target.closest('.bt_selectTrigger')) {
    jeedom.cmd.getSelectModal({
      cmd: {
        type: 'info'
      }
    }, function(result) {
      _target.closest('.trigger').querySelector('.scenarioAttr[data-l1key="trigger"]').jeeValue(result.human)
    })
    return
  }

  if (_target = event.target.closest('.bt_selectDataStoreTrigger')) {
    jeedom.dataStore.getSelectModal({
      cmd: {
        type: 'info'
      }
    }, function(result) {
      _target.closest('.trigger').querySelector('.scenarioAttr[data-l1key="trigger"]').jeeValue(result.human)
    })
    return
  }

  if (_target = event.target.closest('.bt_selectGenericTrigger')) {
    jeedom.config.getGenericTypeModal({
      type: 'info',
      object: true
    }, function(result) {
      _target.closest('.trigger').querySelector('.scenarioAttr[data-l1key="trigger"]').jeeValue('#' + result.human + '#')
    })
    return
  }
})

document.getElementById('generaltab').addEventListener('mouseup', function(event) {
  if (event.target.matches('.scenario_link')) {
    if (event.which == 2) {
      event.preventDefault()
      var id = event.target.getAttribute('data-scenario_id')
      document.querySelector('.scenario_link[data-scenario_id="' + id + '"]').triggerEvent('click', { detail: { ctrlKey: true } })
    }
  }
})

document.getElementById('generaltab').addEventListener('dblclick', function(event) {
  if (event.target.matches('.scenarioAttr[data-l1key="display"][data-l2key="icon"] i')) {
    document.querySelector('.scenarioAttr[data-l1key="display"][data-l2key="icon"]').innerHTML = ''
    jeeFrontEnd.modifyWithoutSave = true
    return
  }
})


//_________________Scenario tab events:
document.getElementById('scenariotab').addEventListener('click', function(event) {
  var _target = null
  if (_target = event.target.closest('#bt_addElementSave')) { //Ok button from new block modal
    jeeP.setUndoStack()
    jeeFrontEnd.modifyWithoutSave = true
    if (jeeP.addElementSave.expression) {
      var newEL = domUtils.parseHTML(jeeP.addExpression({
        type: 'element',
        element: {
          type: document.getElementById("in_addElementType").jeeValue()
        }
      })
      )
    } else {
      var newEL = domUtils.parseHTML(jeeP.addElement({
        type: document.getElementById("in_addElementType").jeeValue()
      })
      )
    }

    if (jeeP.addElementSave.insertAfter) {
      newEL = jeeP.addElementSave.elementDiv.parentNode.insertBefore(newEL.childNodes[0], jeeP.addElementSave.elementDiv.nextSibling)
    } else {
      newEL = jeeP.addElementSave.elementDiv.appendChild(newEL.childNodes[0])
    }

    jeeP.setRootElements()
    newEL.addClass('disableElement')

    jeeP.setEditors()
    jeeP.setSortables()
    jeeP.updateElseToggle()
    jeeDialog.modal(document.getElementById('md_addElement'))._jeeDialog.hide()
    setTimeout(() => {
      if (!isInWindow(newEL)) newEL.scrollIntoView({ behavior: "smooth", block: "end", inline: "nearest" })
    }, 250)
    jeedomUtils.initTooltips()
    jeedom.scenario.setAutoComplete()
    setTimeout(function() {
      newEL.removeClass('disableElement')
    }, 500)
    return
  }

  if (_target = event.target.closest('#bt_cancelElementSave'))  {
    jeeDialog.modal(document.getElementById('md_addElement'))._jeeDialog.hide()
  }

  if (_target = event.target.closest('#bt_crossElementSave'))  {
    jeeDialog.modal(document.getElementById('md_addElement'))._jeeDialog.hide()
  }

  if (_target = event.target.closest('input:not([type="checkbox"]).expressionAttr, textarea.expressionAttr')) { //ctrl-click input popup
    jeeP.PREV_FOCUS = _target //Place new block next
    if (event.ctrlKey) {
      var selfInput = _target
      jeeDialog.prompt({
        title: '{{Edition}}',
        width: '80%',
        inputType: "textarea",
        zIndex: 1109,
        value: selfInput.value,
        container: document.getElementById('scenariotab'),
        backdrop: false,
        onShown: function(dialog) {
          let button = document.createElement('button')
          button.setAttribute('type', 'button')
          button.innerHTML = '<i class="fas fa-list-alt"></i>'
          button.classList = 'button bt_selectJeeDialogCmdExpression'
          let footer = dialog.querySelector('.jeeDialogFooter')
          footer.insertBefore(button, footer.firstChild)
        },
        callback: function(result) {
          if (result != null) {
            selfInput.value = result
          }
        }
      })
      return
    }
  }

  if (_target = event.target.closest('button.bt_selectJeeDialogCmdExpression')) { //ctrl-click input popup getSelectModal
    var expression = _target.closest('.jeeDialogPrompt').querySelector('.promptAttr')
    jeedom.cmd.getSelectModal({}, function(result) {
      expression.insertAtCursor(result.human)
    })
    return
  }

  if (_target = event.target.closest('.bt_removeElement')) {
    if (event.ctrlKey || event.metaKey) {
      if (_target.closest('.expression') != null) {
        jeeP.setUndoStack()
        _target.closest('.expression').remove()
      } else {
        jeeP.setUndoStack()
        _target.closest('.element').remove()
      }
    } else {
      jeeDialog.confirm("{{Êtes-vous sûr de vouloir supprimer ce bloc ?}}", function(result) {
        if (result) {
          if (_target.closest('.expression') != null) {
            jeeP.setUndoStack()
            _target.closest('.expression').remove()
          } else {
            jeeP.setUndoStack()
            _target.closest('.element').remove()
          }
        }
      })
    }
    jeeFrontEnd.modifyWithoutSave = true
    jeeP.PREV_FOCUS = null
    domUtils.syncJeeCompletes()
    return
  }

  if (_target = event.target.closest('.bt_addAction')) {
    jeeP.setUndoStack()
    _target.closest('.subElement').querySelector(':scope > .expressions').insertAdjacentHTML('beforeend', jeeP.addExpression({ type: 'action' }))
    jeedom.scenario.setAutoComplete()
    jeeP.setSortables()
    jeedomUtils.initTooltips()
    return
  }

  if (_target = event.target.closest('.bt_showElse')) {
    let icon = _target.querySelector(':scope > i') || event.target
    let elElse = _target.closest('.element').querySelector(':scope > .subElementELSE')
    if (icon == null) return
    if (icon.hasClass('fa-sort-down')) {
      icon.removeClass('fa-sort-down').addClass('fa-sort-up')
      elElse.seen()
    } else {
      if (elElse.querySelector(':scope > .expressions')?.querySelector(':scope > .expression') != null) {
        jeedomUtils.showAlert({
          message: "{{Le bloc Sinon ne peut être masqué s'il contient des éléments.}}",
          level: 'warning'
        })
        return
      }
      icon.removeClass('fa-sort-up').addClass('fa-sort-down')
      elElse.unseen()
    }
    return
  }

  if (_target = event.target.closest('.bt_collapse')) {
    var open = _target.querySelector(':scope > i').hasClass('fa-eye') ? true : false

    if (event.ctrlKey || event.metaKey) {
      changeThese = _target.closest('.expressions')?.querySelectorAll('.bt_collapse') || document.querySelectorAll('.bt_collapse')
    } else {
      var changeThese = [_target]
    }

    for (_target of changeThese) {
      var icon = _target.querySelector(':scope > i')
      if (open) { // -> Collapse!
        icon.removeClass('fa-eye').addClass('fa-eye-slash')
        _target.closest('.element').addClass('elementCollapse')
        _target.setAttribute('value', 1)
        _target.setAttribute('title', "{{Afficher ce bloc.<br>Ctrl+click: tous.}}")
        //update action, comment and code blocPreview:
        var txt, _el, id
        _target.closest('.element').querySelectorAll('.blocPreview').forEach(function(_blocPreview) {
          txt = '<i>Unfound</i>'
          _el = _blocPreview.closest('.element')
          if (_el.hasClass('elementACTION')) {
            txt = _el.querySelector('.expressions .expression').querySelector('input.form-control')?.value
            if (!txt) txt = _el.querySelector('.expression textarea').value
          } else if (_el.hasClass('elementCODE')) {
            id = _el.querySelector('.expressionAttr[data-l1key="expression"]').getAttribute('id')
            if (isset(jeeP.editors[id])) txt = jeeP.editors[id].getValue()
          } else {
            //comment
            txt = (_el.querySelector('.expression textarea').value).HTMLFormat()
            if (typeof txt === 'object') {
              txt = JSON.stringify(expression.expression)
            }
            txt = '<b>' + txt.split('\n')[0] + '</b>' + txt.replace(txt.split('\n')[0], '')
            if (!txt) txt = _el.querySelector('.expression input.form-control').value
          }
          if (txt) _blocPreview.innerHTML = txt.substring(0, 200)
        })
      } else { // -> Uncollapse!
        icon.addClass('fa-eye').removeClass('fa-eye-slash')
        _target.closest('.element').removeClass('elementCollapse')
        _target.setAttribute('value', 0)
        _target.setAttribute('title', "{{Masquer ce bloc.<br>Ctrl+click: tous.}}")
        jeeP.setEditors()
      }
    }
    jeedomUtils.initTooltips()
    return
  }

  if (_target = event.target.closest('.bt_removeExpression')) {
    jeeP.setUndoStack()
    _target.closest('.expression').remove()
    jeeP.setSortables()
    return
  }

  if (_target = event.target.closest('.bt_selectCmdExpression')) {
    var expression = _target.closest('.expression')
    var type = 'info'
    if (expression.querySelector('.expressionAttr[data-l1key="type"]').jeeValue() == 'action') {
      type = 'action'
    }

    jeedom.cmd.getSelectModal({
      cmd: {
        type: type
      }
    }, function(result) {
      if (expression.querySelector('.expressionAttr[data-l1key="type"]').jeeValue() == 'action') {
        jeeP.setUndoStack()
        expression.querySelector('.expressionAttr[data-l1key="expression"]').jeeValue(result.human)
        jeedom.cmd.displayActionOption(expression.querySelector('.expressionAttr[data-l1key="expression"]').jeeValue(), '', function(html) {
          expression.querySelector('.expressionOptions').html(html)
          jeedomUtils.taAutosize()
          jeedomUtils.initTooltips()
        })
      }

      if (expression.querySelector('.expressionAttr[data-l1key="type"]').jeeValue() == 'condition') {
        var condType = _target.closest('.subElement')
        if (!condType.hasClass('subElementIF') && !condType.hasClass('subElementFOR')) {
          expression.querySelector('.expressionAttr[data-l1key="expression"]').insertAtCursor(result.human)
          return
        }

        var message = jeeP.getSelectCmdExpressionMessage(result.cmd.subType, result.human)
        jeeDialog.prompt({
          title: "{{Ajout d'une nouvelle condition}}",
          inputType: false,
          message: message,
          width: '70%',
          buttons: {
            cancel: {
              label: "{{Ne rien mettre}}",
              className: "",
              callback: {
                click: function(event) {
                  expression.querySelector('.expressionAttr[data-l1key="expression"]').insertAtCursor(result.human)
                  event.target.closest('.jeeDialogPrompt')._jeeDialog.close()
                }
              }
            },
            confirm: {
              label: "{{Valider}}",
              className: "info",
              callback: {
                click: function(event) {
                  jeeP.setUndoStack()
                  jeeFrontEnd.modifyWithoutSave = true
                  var condition = result.human
                  condition += ' ' + document.querySelector('.conditionAttr[data-l1key="operator"]').jeeValue()
                  if (result.cmd.subType == 'string') {
                    if (document.querySelector('.conditionAttr[data-l1key="operator"]').jeeValue() == 'matches') {
                      condition += ' "/' + document.querySelector('.conditionAttr[data-l1key="operande"]').jeeValue() + '/"'
                    } else {
                      condition += " '" + document.querySelector('.conditionAttr[data-l1key="operande"]').jeeValue() + "'"
                    }
                  } else {
                    condition += ' ' + document.querySelector('.conditionAttr[data-l1key="operande"]').jeeValue()
                  }
                  condition += ' ' + document.querySelector('.conditionAttr[data-l1key="next"]').jeeValue() + ' '
                  expression.querySelector('.expressionAttr[data-l1key="expression"]').insertAtCursor(condition)

                  if (document.querySelector('.conditionAttr[data-l1key="next"]').jeeValue() != '') {
                    _target.click()
                  }
                  event.target.closest('.jeeDialogPrompt')._jeeDialog.close()
                }
              }
            },
          }
        })
      }
    })
    return
  }

  if (_target = event.target.closest('.bt_selectOtherActionExpression')) {
    var expression = _target.closest('.expression')
    jeedom.getSelectActionModal({
      scenario: true
    }, function(result) {
      jeeP.setUndoStack()
      expression.querySelector('.expressionAttr[data-l1key="expression"]').value = result.human
      jeedom.cmd.displayActionOption(result.human, '', function(html) {
        expression.querySelector('.expressionOptions').html(html)
        jeedomUtils.taAutosize()
      })
    })
    return
  }

  if (_target = event.target.closest('.bt_selectScenarioExpression')) {
    var expression = _target.closest('.expression')
    jeedom.scenario.getSelectModal({}, function(result) {
      if (expression.querySelector('.expressionAttr[data-l1key="type"]').jeeValue() == 'action') {
        expression.querySelector('.expressionAttr[data-l1key="expression"]').jeeValue(result.human)
      }
      if (expression.querySelector('.expressionAttr[data-l1key="type"]').jeeValue() == 'condition') {
        expression.querySelector('.expressionAttr[data-l1key="expression"]').insertAtCursor(result.human)
      }
    })
    return
  }

  if (_target = event.target.closest('.bt_selectGenericExpression')) {
    var expression = _target.closest('.expression')
    jeedom.config.getGenericTypeModal({ type: 'info', object: true }, function(result) {
      expression.querySelector('.expressionAttr[data-l1key="expression"]').insertAtCursor(result.human)
    })
    return
  }

  if (_target = event.target.closest('.bt_selectEqLogicExpression')) {
    var expression = _target.closest('.expression')
    jeedom.eqLogic.getSelectModal({}, function(result) {
      if (expression.querySelector('.expressionAttr[data-l1key="type"]').jeeValue() == 'action') {
        expression.querySelector('.expressionAttr[data-l1key="expression"]').jeeValue(result.human)
      }
      if (expression.querySelector('.expressionAttr[data-l1key="type"]').jeeValue() == 'condition') {
        expression.querySelector('.expressionAttr[data-l1key="expression"]').insertAtCursor(result.human)
      }
    })
    return
  }

  if (_target = event.target.closest('.subElementAttr[data-l2key="allowRepeatCondition"]')) {
    if (_target.getAttribute('value') == '0') {
      _target.setAttribute('value', '1')
      _target.html('<span><i class="fas fa-ban text-danger"></i></span>')
    } else {
      _target.setAttribute('value', '0')
      _target.html('<span><i class="fas fa-sync"></span>')
    }
    return
  }

  if (_target = event.target.closest('.fromSubElement')) {
    jeeP.setUndoStack()

    var elementDiv = _target.closest('.subElement').querySelector('.expressions')
    var newEL = domUtils.parseHTML(jeeP.addExpression({
      type: 'element',
      element: {
        type: _target.getAttribute('data-type')
      }
    }))
    elementDiv.appendChild(newEL)
    elementDiv.lastChild.addClass('disableElement')

    jeeP.setEditors()
    jeeP.setSortables()
    jeeP.updateElseToggle()
    jeeFrontEnd.modifyWithoutSave = true
    jeedomUtils.initTooltips()
    jeedom.scenario.setAutoComplete()
    setTimeout(function() {
      elementDiv.lastChild.removeClass('disableElement')
    }, 600)
    return
  }

  //COPY - PASTE
  if (_target = event.target.closest('i.bt_copyElement')) {
    var clickedBloc = _target.closest('.element')
    jeeP.clipboard = jeeP.getElement(clickedBloc)

    //delete all id in properties to make it unique later:
    jeeP.removeObjectProp(jeeP.clipboard, 'id')
    localStorage.removeItem('jeedomScCopy')
    localStorage.setItem('jeedomScCopy', JSON.stringify(jeeP.clipboard))

    //cut:
    if (event.ctrlKey || event.metaKey) {
      jeeP.setUndoStack()
      clickedBloc.closest('.expression').remove()
    }
    jeeFrontEnd.modifyWithoutSave = true
    return
  }

  if (_target = event.target.closest('i.bt_pasteElement')) {
    if (localStorage.getItem('jeedomScCopy')) {
      jeeP.clipboard = JSON.parse(localStorage.getItem('jeedomScCopy'))
    } else {
      return false
    }
    var clickedBloc = _target.closest('.element')

    jeeP.setUndoStack()
    jeeP.actionOptions = []

    var pastedElement = document.createElement('div')
    pastedElement.innerHTML = jeeP.addElement(jeeP.clipboard)

    //Are we pasting inside an expresion:
    if (clickedBloc.parentNode.getAttribute('id') == 'div_scenarioElement') {
      pastedElement = clickedBloc.parentNode.insertBefore(pastedElement, clickedBloc.nextSibling)
    } else {
      let divHtml = '<input class="expressionAttr" data-l1key="type" style="display: none;" value="element">'
      divHtml += '<div class="col-xs-12" id="insertHere">'
      divHtml += '</div>'
      let newDiv = document.createElement('div')
      newDiv.innerHTML = divHtml
      newDiv.classList = 'expression sortable col-xs-12'

      clickedBloc.parentNode.parentNode.parentNode.insertBefore(newDiv, clickedBloc.closest('.expression').nextSibling)
      document.getElementById('insertHere').appendChild(pastedElement)
      document.getElementById('insertHere').removeAttribute('id')
    }

    pastedElement.querySelectorAll('input[data-l1key="options"][data-l2key="enable"]').forEach(_input => {
      if (!_input.checked) _input.triggerEvent('change')
    })

    pastedElement.replaceWith(...pastedElement.childNodes)

    jeeP.updateElseToggle()

    //replace:
    if (event.ctrlKey || event.metaKey) {
      clickedBloc.remove()
    }

    jeeP.updateElementCollapse()
    jeeP.setScenarioActionsOptions()
    jeeP.setSortables()
    jeedom.scenario.setAutoComplete()
    jeeP.setEditors()
    jeedomUtils.initTooltips()

    setTimeout(function() { jeedomUtils.initTooltips() }, 500)

    jeeFrontEnd.modifyWithoutSave = true
    return
  }
})

document.getElementById('scenariotab').addEventListener('change', function(event) {
  if (event.target.matches('.subElementAttr[data-l1key="options"][data-l2key="enable"]')) {
    var checkbox = event.target
    var element = checkbox.closest('.element')
    if (checkbox.checked) {
      element.removeClass('disableElement')
    } else {
      element.addClass('disableElement')
    }
    var subElement = checkbox.closest('.element').querySelector('.subElement:not(.noSortable)')
    if (!subElement) return
    if (checkbox.checked) {
      subElement.querySelectorAll('.expressions')?.removeClass('disableSubElement')
    } else {
      subElement.querySelectorAll('.expressions')?.addClass('disableSubElement')
    }
    return
  }

  if (event.target.matches('.expressionAttr[data-l1key="options"][data-l2key="enable"]')) {
    var element = event.target.closest('.expression')
    if (event.target.checked) {
      element.removeClass('disableSubElement')
    } else {
      element.addClass('disableSubElement')
    }
    return
  }
})

document.getElementById('scenariotab').addEventListener('mouseenter', function(event) {
  var _target = null
  if (_target = event.target.closest('button.dropdown-toggle')) {
    if (event.clientY > window.innerHeight - 220) {
      _target.closest('div.dropdown').addClass('dropup')
    } else {
      _target.closest('div.dropdown').removeClass('dropup')
    }
  }
}, { capture: true })

document.getElementById('scenariotab').addEventListener('mouseout', function(event) {
  var _target = null
  if (_target = event.target.closest('button.dropdown-toggle')) {
    if (event.clientY > window.innerHeight - 220) {
      event.target.closest('div.dropdown').addClass('dropup')
    } else {
      event.target.closest('div.dropdown').removeClass('dropup')
    }
  }
})

document.getElementById('scenariotab').addEventListener('focusout', function(event) {
  var _target = null
  if (_target = event.target.closest('.expression .expressionAttr[data-l1key="expression"]')) {
    if (_target.getAttribute('prevalue') == _target.value) return
    if (_target.closest('.expression').querySelector('.expressionAttr[data-l1key="type"]').value == 'action') {
      var expression = _target.closest('.expression').getJeeValues('.expressionAttr')
      jeedom.cmd.displayActionOption(_target.value, init(expression[0].options), function(html) {
        _target.closest('.expression').querySelector('.expressionOptions').html(html)
        jeedomUtils.taAutosize()
        jeedomUtils.initTooltips()
        _target.setAttribute('prevalue', _target.value)
      })
    }
  }
})


domUtils(function() {
  jeeFrontEnd.scenario.postInit()
})


//tabs context menu
try {
  jeedom.scenario.allOrderedByGroupObjectName({
    asGroup: 1,
    error: function(error) {
      jeedomUtils.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(scenarioGroupedList) {
      if (scenarioGroupedList.length == 0) return

      var contextmenuitems = {}
      var uniqId = 0
      var items, scName, scId
      for (var group in scenarioGroupedList) {
        items = {}
        for (var i in scenarioGroupedList[group]) {
          scName = scenarioGroupedList[group][i].humanName.replace('[' + group + ']', '')
          scId = scenarioGroupedList[group][i].id
          items[uniqId] = {
            'name': scName,
            'id': scId
          }
          uniqId++
        }
        contextmenuitems[group] = {
          'name': group,
          'items': items
        }
      }

      if (Object.entries(contextmenuitems).length > 0 && contextmenuitems.constructor === Object) {
        new jeeCtxMenu({
          selector: '.nav.nav-tabs li',
          appendTo: 'div#div_pageContainer',
          zIndex: 9999,
          className: 'scenarioTab-context-menu',
          callback: function(key, options, event) {
            if (!jeedomUtils.checkPageModified()) {
              if (event.ctrlKey || event.metaKey || event.which == 2) {
                var url = 'index.php?v=d&p=scenario&id=' + options.commands[key].id
                if (window.location.hash != '') {
                  url += window.location.hash
                }
                window.open(url).focus()
              } else {
                jeeP.printScenario(options.commands[key].id)
              }
            }
          },
          items: contextmenuitems
        })
      }
    }
  })
} catch (err) { }


//general context menu
try {
  new jeeCtxMenu({
    selector: "#accordionScenario .scenarioDisplayCard",
    appendTo: 'div#div_pageContainer',
    className: 'scenario-context-menu',
    build: function(trigger) {
      var scGroups = []
      Object.keys(jeephp2js.scenarioListGroup).forEach(function(key) {
        scGroups.push(jeephp2js.scenarioListGroup[key].group)
      })

      var scId = trigger.getAttribute('data-scenario_id')
      var scName = trigger.querySelector('.name strong').innerHTML
      var isActive = !trigger.hasClass('inactive')

      var contextmenuitems = {}
      contextmenuitems['scId'] = { 'name': scName + '(id: ' + scId + ')', 'id': 'scId', 'disabled': true }
      if (isActive) {
        contextmenuitems['disable'] = { 'name': '{{Rendre inactif}}', 'id': 'disable', 'icon': 'fas fa-toggle-on' }
      } else {
        contextmenuitems['enable'] = { 'name': '{{Rendre actif}}', 'id': 'enable', 'icon': 'fas fa-toggle-off' }
      }

      //group submenu:
      var idx = 0
      var items = {}
      items['group_none'] = {
        'name': '{{Aucun}}',
        'id': 'group_none',
        'jType': 'group'
      }
      scGroups.forEach(function(grpName) {
        if (grpName != '') {
          items[grpName] = {
            'name': grpName,
            'id': 'group_' + idx,
            'jType': 'group'
          }
          idx += 1
        }
      })
      contextmenuitems['groups'] = {
        'name': '{{Groupe}}',
        'items': items
      }

      //parent submenu
      var items = {}
      items[0] = {
        'name': '<span class="label labelObjectHuman">None</span>',
        'id': 'parent_0',
        'jType': 'parent',
        'jId': '',
        'jHumanName': '{{Aucun}}',
        'isHtmlName': true,
      }
      var idx = 1
      for (var parent of jeephp2js.objectList) {
        items[idx] = {
          'name': parent.tag,
          'id': 'parent_' + idx,
          'jType': 'parent',
          'jId': parent.id,
          'jHumanName': parent.humanName,
          'isHtmlName': true,
        }
        idx += 1
      }
      contextmenuitems['parents'] = {
        'name': '{{Objet parent}}',
        'items': items
      }

      return {
        callback: function(key, options) {
          if (options.commands[key].jType == 'group') {
            if (key == 'group_none') key = null
            var scenario = {
              id: scId,
              group: key
            }
            jeedom.scenario.save({
              scenario: scenario,
              error: function(error) {
                jeedomUtils.showAlert({ message: error.message, level: 'danger' })
              },
              success: function(data) {
                document.querySelector('div.scenarioListContainer[data-groupName="' + key + '"]').appendChild(document.querySelector('.scenarioDisplayCard[data-scenario_id="' + data.id + '"]'))
                jeeP.updateAccordionName()
              }
            })
            return true
          }

          if (options.commands[key].jType == 'parent') {
            var humanName = options.commands[key].jHumanName
            var objectId = options.commands[key].jId
            if (key == '0') {
              humanName = '<span class="label labelObjectHuman">None</span>'
            }
            var scenario = {
              id: scId,
              object_id: objectId
            }
            jeedom.scenario.save({
              scenario: scenario,
              error: function(error) {
                jeedomUtils.showAlert({ message: error.message, level: 'danger' })
              },
              success: function(data) {
                let dispCard = document.querySelector('.scenarioDisplayCard[data-scenario_id="' + data.id + '"]')
                dispCard.querySelector('.name > .label')?.remove()
                dispCard.querySelector('.name').insertAdjacentHTML('afterbegin', humanName)
                dispCard.querySelector('.name > .label i')?.remove()
              }
            })
            return true
          }

          if (key == 'disable') {
            var scenario = {
              id: scId,
              isActive: "0"
            }
            jeedom.scenario.save({
              scenario: scenario,
              error: function(error) {
                jeedomUtils.showAlert({ message: error.message, level: 'danger' })
              },
              success: function(data) {
                document.querySelector('.scenarioDisplayCard[data-scenario_id="' + data.id + '"]').addClass('inactive')
              }
            })
            return true
          }

          if (key == 'enable') {
            var scenario = {
              id: scId,
              isActive: "1"
            }
            jeedom.scenario.save({
              scenario: scenario,
              error: function(error) {
                jeedomUtils.showAlert({ message: error.message, level: 'danger' })
              },
              success: function(data) {
                document.querySelector('.scenarioDisplayCard[data-scenario_id="' + data.id + '"]').removeClass('inactive')
              }
            })
            return true
          }
        },
        items: contextmenuitems
      }
    }
  })
} catch (err) { }
