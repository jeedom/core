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
    $divScenario: null,
    dom_divScenario: null,
    tab: null,
    dataDefinedAction: null,
    PREV_FOCUS: null,
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
    init: function() {
      this.tab = null
      this.PREV_FOCUS = null
      this.editors = []
      this.undoStack = []
      this.bt_undo = document.getElementById('bt_undo')
      this.bt_redo = document.getElementById('bt_redo')
      this.$divScenario = $('#div_editScenario')
      this.dom_divScenario= document.getElementById('div_editScenario')
      window.jeeP = this
    },
    checkNoMode: function() {
      if (document.querySelectorAll('div.scheduleDisplay .schedule').length > 0 ||
          document.querySelectorAll('div.provokeDisplay .trigger').length > 0 ||
          document.querySelectorAll('div.defined_actions .action_link:not(.cross)').length > 0) {
        document.getElementById('emptyModeWarning').unseen()
      } else {
        document.getElementById('emptyModeWarning').seen()
      }
    },
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
        message += '    <option value="==">{{égal}}</option>'
        message += '    <option value=">">{{supérieur}}</option>'
        message += '    <option value="<">{{inférieur}}</option>'
        message += '    <option value="!=">{{différent}}</option>'
        message += '  </select>'
        message += '</div>'
        message += '<div class="col-xs-4">'
        message += '  <input class="conditionAttr form-control radio-inline" data-l1key="operande" style="width: calc(100% - 45px);" />'
        message += '  <button type="button" class="btn btn-default cursor bt_selectCmdFromModal"><i class="fas fa-list-alt"></i></button>'
        message += '</div>'
        message += '</div>'
      }

      if (subType == 'string') {
        message += '<div class="col-xs-2">'
        message += '  <select class="conditionAttr form-control" data-l1key="operator">'
        message += '    <option value="==">{{égale}}</option>'
        message += '    <option value="matches">{{contient}}</option>'
        message += '    <option value="!=">{{différent}}</option>'
        message += '  </select>'
        message += '</div>'
        message += '<div class="col-xs-4">'
        message += '  <input class="conditionAttr form-control radio-inline" data-l1key="operande" style="width: calc(100% - 45px);" />'
        message += '  <button type="button" class="btn btn-default cursor bt_selectCmdFromModal"><i class="fas fa-list-alt"></i></button>'
        message += '</div>'
        message += '</div>'
      }

      if (subType == 'binary') {
        message += '<div class="col-xs-7">'
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
      message += '    <option value="&&">&& (AND)</option>'
      message += '    <option value="||">|| (OR)</option>'
      if (jeeFrontEnd.language == "fr_FR") message += '    <option value="ET">{{et}}</option>'
      if (jeeFrontEnd.language == "fr_FR") message += '    <option value="OU">{{ou}}</option>'
      message += '  </select>'
      message += '</div>'
      message += '</div>'
      message += '</div></div>'
      message += '</form></div></div>'
      return message
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
    //Scenario loading
    updateSortable: function() {
      document.querySelectorAll('.element').removeClass('sortable')
      document.querySelectorAll('#div_scenarioElement > .element').addClass('sortable')
      document.querySelectorAll('.subElement .expressions').forEach(function(exp) {
        if (exp.querySelectorAll(':scope > .sortable:not(.empty)').length > 0) {
          exp.querySelectorAll(':scope > .sortable.empty')?.unseen()
        } else {
          exp.querySelectorAll(':scope > .sortable.empty')?.seen()
        }
      })
    },
    updateElseToggle: function() {
      document.querySelectorAll('.subElementELSE').forEach(function(elElse) {
        if (!elElse.closest('.element').querySelector(':scope > .subElementTHEN')?.querySelector('.bt_showElse i')?.hasClass('fa-sort-down')) {
          if (elElse.querySelector(':scope > .expressions')?.querySelectorAll(':scope > .expression').length == 0) {
            elElse.closest('.element').querySelector(':scope > .subElementTHEN').querySelector('.bt_showElse').triggerEvent('click')
          }
        }
      })
    },
    updateElementCollpase: function() {
      document.querySelectorAll('.bt_collapse').forEach(function(bt) {
        if (bt.jeeValue() == 0) {
          bt.closest('.element').removeClass('elementCollapse')
        } else {
          bt.closest('.element').addClass('elementCollapse')
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
    updateDefinedActions: function(cmdModal=false) {
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
    printScenario: function(_id) {
      jeedomUtils.hideAlert()
      domUtils.showLoading()
      document.getElementById('emptyModeWarning').unseen()
      jeedom.scenario.update[_id] = function(_options) {
        if (_options.scenario_id = !jeeP.dom_divScenario.getJeeValues('.scenarioAttr')[0]['id']) {
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
          });
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

          if ($.isArray(data.schedule)) {
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
          document.querySelectorAll('.subElementAttr[data-l1key="options"][data-l2key="enable"], .expressionAttr[data-l1key="options"][data-l2key="enable"]').triggerEvent('change')
          jeeP.setScenarioActionsOptions()
          document.getElementById('div_editScenario').seen()
          jeeP.updateSortable()
          jeedom.scenario.setAutoComplete()
          jeeP.updateElementCollpase()
          jeeP.updateElseToggle()
          jeedomUtils.taAutosize()
          var title = ''
          if (data.name) title = data.name + ' - ' + JEEDOM_PRODUCT_NAME
          var hash = window.location.hash
          jeedomUtils.addOrUpdateUrl('id', data.id, title)
          if (hash == '') {
            $('.nav-tabs a[data-target="#generaltab"]').click()
          } else {
            window.location.hash = hash
          }
          setTimeout(function() {
            jeeP.setEditors()
          }, 100)
          jeeFrontEnd.modifyWithoutSave = false
          jeeP.resetUndo()
          setTimeout(function() {
            jeeFrontEnd.modifyWithoutSave = false
          }, 1000)
          setTimeout(function() {
            jeeP.checkNoMode()
            jeeP.updateTooltips()
          }, 500)

          jeedom.scenario.setAutoComplete()
          document.getElementById('humanNameTag').innerHTML = data.humanNameTag
        }
      })
    },
    saveScenario: function(_callback) {
      jeedomUtils.hideAlert()
      var scenario = this.dom_divScenario.getJeeValues('.scenarioAttr')[0]
      if (typeof scenario.trigger == 'undefined') {
        scenario.trigger = ''
      }
      if (typeof scenario.schedule == 'undefined') {
        scenario.schedule = ''
      }
      var elements = []
      document.getElementById('div_scenarioElement').querySelectorAll(':scope > .element').forEach(function(_el) {
        elements.push(jeeP.getElement(_el))
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
    addTrigger: function(_trigger) {
      var div = '<div class="form-group trigger">'
      div += '<label class="col-xs-3 control-label">{{Evénement}}</label>'
      div += '<div class="col-xs-9">'
      div += '<div class="input-group">'
      div += '<input class="scenarioAttr input-sm form-control roundedLeft" data-l1key="trigger" value="' + _trigger.replace(/"/g, '&quot;') + '" >'
      div += '<span class="input-group-btn">'
      div += '<a class="btn btn-default btn-sm cursor bt_selectTrigger" tooltip="{{Choisir une commande}}"><i class="fas fa-list-alt"></i></a>'
      div += '<a class="btn btn-default btn-sm cursor bt_selectGenericTrigger" tooltip="{{Choisir un Type Générique}}"><i class="fas fa-puzzle-piece"></i></a>'
      div += '<a class="btn btn-default btn-sm cursor bt_selectDataStoreTrigger" tooltip="{{Choisir une variable}}"><i class="fas fa-calculator"></i></a>'
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
      if (!isset(_expression) || !isset(_expression.type) || _expression.type == '') {
        return ''
      }
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
            _expression.expression = _expression.expression.replace(/"/g, '&quot;')
          }
          retour += '<div class="input-group input-group-sm" >'
          retour += '<input class="expressionAttr form-control roundedLeft" data-l1key="expression" value="' + init(_expression.expression) + '" />'
          retour += '<span class="input-group-btn">'
          retour += '<button type="button" class="btn btn-default cursor bt_selectCmdExpression" tooltip="{{Rechercher une commande}}"><i class="fas fa-list-alt"></i></button>'
          retour += '<button type="button" class="btn btn-default cursor bt_selectGenericExpression" tooltip="{{Rechercher un type générique}}"><i class="fas fa-puzzle-piece"></i></button>'
          retour += '<button type="button" class="btn btn-default cursor bt_selectScenarioExpression" tooltip="{{Rechercher un scénario}}"><i class="fas fa-history"></i></button>'
          retour += '<button type="button" class="btn btn-default cursor bt_selectEqLogicExpression roundedRight"  tooltip="{{Rechercher un équipement}}"><i class="fas fa-cube"></i></button>'
          retour += '</span>'
          retour += '</div>'
          break
        case 'element':
          retour += '<div class="col-xs-12" >';
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
            retour += '<input type="checkbox" class="expressionAttr" data-l1key="options" data-l2key="enable" checked  tooltip="{{Décocher pour désactiver l\'action}}"/>'
          } else {
            retour += '<input type="checkbox" class="expressionAttr" data-l1key="options" data-l2key="enable"  tooltip="{{Décocher pour désactiver l\'action}}"/>'
          }
          if (!isset(_expression.options) || !isset(_expression.options.background) || _expression.options.background == 0) {
            retour += '<input type="checkbox" class="expressionAttr" data-l1key="options" data-l2key="background"  tooltip="{{Cocher pour que la commande s\'exécute en parallèle des autres actions}}"/>'
          } else {
            retour += '<input type="checkbox" class="expressionAttr" data-l1key="options" data-l2key="background" checked  tooltip="{{Cocher pour que la commande s\'exécute en parallèle des autres actions}}"/>'
          }
          var expression_txt = init(_expression.expression)
          if (typeof expression_txt != 'string') {
            expression_txt = JSON.stringify(expression_txt)
          }
          retour += '</div>'
          retour += '<div class="col-xs-4" ><div class="input-group input-group-sm">'
          retour += '<span class="input-group-btn">'
          retour += '<button class="btn btn-default bt_removeExpression roundedLeft" type="button" tooltip="{{Supprimer l\'action}}"><i class="fas fa-minus-circle"></i></button>'
          retour += '</span>'
          retour += '<input class="expressionAttr form-control" data-l1key="expression" prevalue="' + init(_expression.expression) + '" value="' + expression_txt.replace(/"/g, '&quot;') + '"/>'
          retour += '<span class="input-group-btn">'
          retour += '<button class="btn btn-default bt_selectOtherActionExpression" type="button" tooltip="{{Sélectionner un mot-clé}}"><i class="fas fa-tasks"></i></button>'
          retour += '<button class="btn btn-default bt_selectCmdExpression roundedRight" type="button" tooltip="{{Sélectionner la commande}}"><i class="fas fa-list-alt"></i></button>'
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
          retour += '</div>';
          break;
        case 'comment':
          retour += '<textarea class="expressionAttr form-control" data-l1key="expression">' + init(_expression.expression) + '</textarea>'
          break
      }
      retour += '</div>'
      return retour
    },
    addSubElement: function(_subElement) {
      if (!isset(_subElement.type) || _subElement.type == '') {
        return ''
      }
      if (!isset(_subElement.options)) {
        _subElement.options = {}
      }
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
            retour += '<a class="bt_collapse cursor subElementAttr" tooltip="{{Masquer ce bloc.<br>Ctrl+click: tous.}}" data-l1key="options" data-l2key="collapse" value="0"><i class="far fa-eye"></i></a>'
          } else {
            retour += '<a class="bt_collapse cursor subElementAttr" tooltip="{{Afficher ce bloc.<br>Ctrl+click: tous.}}" data-l1key="options" data-l2key="collapse" value="1"><i class="far fa-eye-slash"></i></a>'
          }
          if (!isset(_subElement.options) || !isset(_subElement.options.enable) || _subElement.options.enable == 1) {
            retour += '<input type="checkbox" class="subElementAttr" data-l1key="options" data-l2key="enable" checked tooltip="{{Décocher pour désactiver l\'élément}}" />'
          } else {
            retour += '<input type="checkbox" class="subElementAttr" data-l1key="options" data-l2key="enable" tooltip="{{Décocher pour désactiver l\'élément}}" />'
          }
          retour += '</div>'
          retour += '<div><legend >{{SI}}</legend></div>'

          retour += '<div >'
          if (!isset(_subElement.options) || !isset(_subElement.options.allowRepeatCondition) || _subElement.options.allowRepeatCondition == 0) {
            retour += '<a class="bt_repeat cursor subElementAttr" tooltip="{{Autoriser ou non la répétition des actions si l\'évaluation de la condition est la même que la précédente}}" data-l1key="options" data-l2key="allowRepeatCondition" value="0"><span><i class="fas fa-sync"></i></span></a>'
          } else {
            retour += '<a class="bt_repeat cursor subElementAttr" tooltip="{{Autoriser ou non la répétition des actions si l\'évaluation de la condition est la même que la précédente}}" data-l1key="options" data-l2key="allowRepeatCondition" value="1"><span><i class="fas fa-ban text-danger"></i></span></a>'
          }
          retour += '</div>'

          retour += '<div class="expressions" >'
          var expression = {
            type: 'condition'
          }
          if (isset(_subElement.expressions) && isset(_subElement.expressions[0])) {
            expression = _subElement.expressions[0]
          }
          retour += this.addExpression(expression);
          retour += '  </div>'
          retour = this.addElButtons(retour)
          break

        case 'then':
          retour += '<input class="subElementAttr" data-l1key="subtype" style="display : none;" value="action"/>'
          retour += '<div class="subElementFields">'
          retour += '<legend >{{ALORS}}</legend>'
          retour += this.getAddButton(_subElement.type,true)
          retour += '</div>'
          retour += '<div class="expressions">'
          retour += '<div class="sortable empty" ></div>'
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
          retour += '<div class="sortable empty" ></div>'
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
            retour += '<a class="bt_collapse cursor subElementAttr" tooltip="{{Masquer ce bloc.<br>Ctrl+click: tous.}}" data-l1key="options" data-l2key="collapse" value="0"><i class="far fa-eye"></i></a>'
          } else {
            retour += '<a class="bt_collapse cursor subElementAttr" tooltip="{{Afficher ce bloc.<br>Ctrl+click: tous.}}" data-l1key="options" data-l2key="collapse" value="1"><i class="far fa-eye-slash"></i></a>'
          }
          if (!isset(_subElement.options) || !isset(_subElement.options.enable) || _subElement.options.enable == 1) {
            retour += '<input type="checkbox" class="subElementAttr" data-l1key="options" data-l2key="enable" checked tooltip="{{Décocher pour désactiver l\'élément}}" />'
          } else {
            retour += '<input type="checkbox" class="subElementAttr" data-l1key="options" data-l2key="enable" tooltip="{{Décocher pour désactiver l\'élément}}" />'
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
            retour += '<a class="bt_collapse cursor subElementAttr" tooltip="{{Masquer ce bloc.<br>Ctrl+click: tous.}}" data-l1key="options" data-l2key="collapse" value="0"><i class="far fa-eye"></i></a>'
          } else {
            retour += '<a class="bt_collapse cursor subElementAttr" tooltip="{{Afficher ce bloc.<br>Ctrl+click: tous.}}" data-l1key="options" data-l2key="collapse" value="1"><i class="far fa-eye-slash"></i></a>'
          }
          if (!isset(_subElement.options) || !isset(_subElement.options.enable) || _subElement.options.enable == 1) {
            retour += '<input type="checkbox" class="subElementAttr" data-l1key="options" data-l2key="enable" checked tooltip="{{Décocher pour désactiver l\'élément}}" />'
          } else {
            retour += '<input type="checkbox" class="subElementAttr" data-l1key="options" data-l2key="enable" tooltip="{{Décocher pour désactiver l\'élément}}" />'
          }
          retour += '</div>'
          retour += '<div>'
          retour += '<legend tooltip="{{Action DANS x minutes}}">{{DANS}}</legend>'
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
            retour += '<a class="bt_collapse cursor subElementAttr" tooltip="{{Masquer ce bloc.<br>Ctrl+click: tous.}}" data-l1key="options" data-l2key="collapse" value="0"><i class="far fa-eye"></i></a>'
          } else {
            retour += '<a class="bt_collapse cursor subElementAttr" tooltip="{{Afficher ce bloc.<br>Ctrl+click: tous.}}" data-l1key="options" data-l2key="collapse" value="1"><i class="far fa-eye-slash"></i></a>'
          }
          if (!isset(_subElement.options) || !isset(_subElement.options.enable) || _subElement.options.enable == 1) {
            retour += '<input type="checkbox" class="subElementAttr" data-l1key="options" data-l2key="enable" checked tooltip="{{Décocher pour désactiver l\'élément}}" />'
          } else {
            retour += '<input type="checkbox" class="subElementAttr" data-l1key="options" data-l2key="enable" tooltip="{{Décocher pour désactiver l\'élément}}" />'
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
          retour += '<div class="sortable empty" ></div>'
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
            retour += '<a class="bt_collapse cursor subElementAttr" tooltip="{{Masquer ce bloc.<br>Ctrl+click: tous.}}" data-l1key="options" data-l2key="collapse" value="0"><i class="far fa-eye"></i></a>'
          } else {
            retour += '<a class="bt_collapse cursor subElementAttr" tooltip="{{Afficher ce bloc.<br>Ctrl+click: tous.}}" data-l1key="options" data-l2key="collapse" value="1"><i class="far fa-eye-slash"></i></a>'
          }
          if (!isset(_subElement.options) || !isset(_subElement.options.enable) || _subElement.options.enable == 1) {
            retour += '<input type="checkbox" class="subElementAttr" data-l1key="options" data-l2key="enable" checked tooltip="{{Décocher pour désactiver l\'élément}}" />'
          } else {
            retour += '<input type="checkbox" class="subElementAttr" data-l1key="options" data-l2key="enable" tooltip="{{Décocher pour désactiver l\'élément}}" />'
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
            retour += '<a class="bt_collapse cursor subElementAttr" tooltip="{{Masquer ce bloc.<br>Ctrl+click: tous.}}" data-l1key="options" data-l2key="collapse" value="0"><i class="far fa-eye"></i></a>'
          } else {
            retour += '<a class="bt_collapse cursor subElementAttr" tooltip="{{Afficher ce bloc.<br>Ctrl+click: tous.}}" data-l1key="options" data-l2key="collapse" value="1"><i class="far fa-eye-slash"></i></a>'
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
            retour += '<a class="bt_collapse cursor subElementAttr" tooltip="{{Masquer ce bloc.<br>Ctrl+click: tous.}}" data-l1key="options" data-l2key="collapse" value="0"><i class="far fa-eye"></i></a>'
          } else {
            retour += '<a class="bt_collapse cursor subElementAttr" tooltip="{{Afficher ce bloc.<br>Ctrl+click: tous.}}" data-l1key="options" data-l2key="collapse" value="1"><i class="far fa-eye-slash"></i></a>'
          }
          if (!isset(_subElement.options) || !isset(_subElement.options.enable) || _subElement.options.enable == 1) {
            retour += '<input type="checkbox" class="subElementAttr" data-l1key="options" data-l2key="enable" checked tooltip="{{Décocher pour désactiver l\'élément}}" />'
          } else {
            retour += '<input type="checkbox" class="subElementAttr" data-l1key="options" data-l2key="enable" tooltip="{{Décocher pour désactiver l\'élément}}" />'
          }
          retour += '<legend class="legendHidden">{{ACTION}}</legend>'
          if (isset(_subElement.expressions) && isset(_subElement.expressions[0])) {
            expression = _subElement.expressions[0]
            if (expression.type == 'element' && isset(expression.element.subElements) && isset(expression.element.subElements[0]) && isset(expression.element.subElements[0].expressions) && isset(expression.element.subElements[0].expressions[0])) {
              retour += '<div class="blocPreview">' + expression.element.subElements[0].expressions[0].expression.substring(0, 200).HTMLFormat() + '</div>'
            } else {
              retour += '<div class="blocPreview">' + _subElement.expressions[0].expression.substring(0, 200).HTMLFormat() + '</div>'
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
          retour += '<div class="sortable empty" ></div>'
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
      _retour += '  <div><i class="fas fa-minus-circle pull-right cursor bt_removeElement" tooltip="{{Supprimer ce bloc.<br>Ctrl+Click: Supprimer sans confirmation.}}"></i></div>'
      _retour += '  <div><i class="fas fa-copy pull-right cursor bt_copyElement" tooltip="{{Copier ce bloc.<br>Ctrl+Click: Couper ce bloc.}}"></i></div>'
      _retour += '  <div><i class="fas fa-paste pull-right cursor bt_pasteElement" tooltip="{{Coller un bloc après celui-ci.<br>Ctrl+Click: remplacer ce bloc par le bloc copié.}}"></i></div>'
      return _retour
    },
    addElement: function(_element) {
      if (!isset(_element)) {
        return
      }
      if (!isset(_element.type) || _element.type == '') {
        return ''
      }

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
          break;
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
      if (element.length == 0) {
        return
      }
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
    updateTooltips: function() {
      //in scenarios, for faster undo/redo, tooltips are specially created with tooltip attribute and copied as title to keep track of it!
      var toTips = document.querySelectorAll('[tooltip]:not(.tooltipstered)')
      toTips.forEach(function(_tooltip) {
        _tooltip.setAttribute('title', _tooltip.getAttribute('tooltip'))
      })
      $(toTips).tooltipster(jeedomUtils.TOOLTIPSOPTIONS)
    },
    getAddButton: function(_type,_caret) {
      if (!isset(_caret)) _caret = false
      var retour = ''
      if (_caret) {
        retour += '<div class="input-group">'
        retour += '<button class="bt_showElse btn btn-xs btn-default roundedLeft" type="button" data-toggle="dropdown" tooltip="{{Afficher/masquer le bloc Sinon}}" aria-haspopup="true" aria-expanded="true">'
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
      if(_type != 'action' && _type != 'if'  && _type != 'in'  && _type != 'for'  && _type != 'at'){
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
    //Undo management
    reloadStack: function(loadStack) {
      document.getElementById('div_scenarioElement').empty()
      this.actionOptions = []
      var elements = ''
      for (var i in loadStack) {
        elements += this.addElement(loadStack[i])
      }
      document.getElementById('div_scenarioElement').empty().html(elements, true)
      //Synch collapsed elements:
      /*
      document.querySelectorAll('i.fa-eye-slash').forEach(eye => {
        eye.closest(.element).addClass('elementCollapse')
      })
      */
      this.updateElseToggle()
      this.setScenarioActionsOptions()
    },
    setUndoStack: function(state = 0) {
      this.syncEditors()
      this.bt_undo.removeClass('disabled')
      this.bt_redo.addClass('disabled')
      var newStack = []

      document.getElementById('div_scenarioElement').querySelectorAll(':scope > .element').forEach(element => {
        newStack.push(jeeP.getElement(element))
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
        console.log('undo ERROR:', error)
      }
      setTimeout(function() { jeeP.updateTooltips() }, 500)
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
        console.log('redo ERROR:', error)
      }
      setTimeout(function() { jeeP.updateTooltips() }, 500)
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
      document.querySelectorAll('.expressionAttr[data-l1key="type"][value="code"]').forEach(function(elCode) {
        expression = elCode.closest('.expression')
        code = expression.querySelector('.expressionAttr[data-l1key="expression"]')
        code. removeAttribute('id').show()
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

//searching
$('#in_searchScenario').keyup(function() {
  var search = this.value
  if (search == '') {
    $('.panel-collapse.in').closest('.panel').find('.accordion-toggle').click()
    $('.scenarioDisplayCard').show()
    return
  }
  search = jeedomUtils.normTextLower(search)
  var not = search.startsWith(":not(")
  if (not) {
    search = search.replace(':not(', '')
  }
  $('.panel-collapse').attr('data-show', 0)
  $('.scenarioDisplayCard').hide()
  var match, text
  $('.scenarioDisplayCard .name').each(function() {
    match = false
    text = jeedomUtils.normTextLower($(this).text())
    if (text.includes(search)) {
      match = true
    }

    if (not) match = !match
    if (match) {
      $(this).closest('.scenarioDisplayCard').show()
      $(this).closest('.panel-collapse').attr('data-show', 1)
    }

  })
  $('.panel-collapse[data-show=1]').collapse('show')
  $('.panel-collapse[data-show=0]').collapse('hide')
})
$('#bt_openAll').off('click').on('click', function() {
  $('.accordion-toggle[aria-expanded="false"]').each(function() {
    $(this).click()
  })
})
$('#bt_closeAll').off('click').on('click', function() {
  $('.accordion-toggle[aria-expanded="true"]').each(function() {
    $(this).click()
  })
})
$('#bt_resetScenarioSearch').on('click', function() {
  $('#in_searchScenario').val('').keyup()
})

$('.bt_ViewLog').off('click').on('click', function(event) {
  event.stopPropagation()
  var id = $(this).closest('.scenarioDisplayCard').attr('data-scenario_id')
  $('#md_modal2').dialog({
    title: "{{Log d'exécution du scénario}}"
  }).load('index.php?v=d&modal=scenario.log.execution&scenario_id=' + id).dialog('open')
})

//inside searching
$('#in_searchInsideScenario').keyup(function() {
  var search = this.value
  $('#div_scenarioElement .insideSearch').removeClass('insideSearch')
  $('#div_scenarioElement div.CodeMirror.CodeMirror-wrap').each(function() {
    $(this).get(0).CodeMirror.setCursor(0)
  })

  if (search == '' || search.length < 3) {
    $('i.fa-eye-slash').each(function() {
      $(this).parents('.element').first().addClass('elementCollapse')
    })
    return
  }
  search = jeedomUtils.normTextLower(search)

  //search code blocks:
  var cmEditor, code, cursor
  $('#div_scenarioElement div.elementCODE').each(function() {
    try {
      cmEditor = $(this).find('div.CodeMirror.CodeMirror-wrap').get(0).CodeMirror
      code = jeedomUtils.normTextLower(cmEditor.getValue())
      if (code.indexOf(search) >= 0) {
        $(this).removeClass('elementCollapse')
        cursor = cmEditor.getSearchCursor(search, CodeMirror.Pos(cmEditor.firstLine(), 0), {
          caseFold: true,
          multiline: true
        })
        if (cursor.find(false)) {
          cmEditor.setSelection(cursor.from(), cursor.to())
        }
      } else {
        $(this).addClass('elementCollapse')
        cmEditor.setCursor(0)
      }
    } catch {}
  })
  //search in expressions:
  var text
  $('#div_scenarioElement div.element:not(.elementCODE) .expressionAttr').each(function() {
    text = jeedomUtils.normTextLower($(this).val())
    if (text.indexOf(search) >= 0) {
      $(this).addClass('insideSearch')
      $(this).parents('.element').removeClass('elementCollapse')
    }
  })
})
$('#bt_resetInsideScenarioSearch').on('click', function() {
  var btn = $(this)
  if (btn.hasClass('disabled')) return
  var searchField = $('#in_searchInsideScenario')
  if (btn.data('state') == '0') {
    //show search:
    searchField.show()
    btn.find('i').removeClass('fa-search').addClass('fa-times')
    btn.data('state', '1')
    //open code blocks for later search:
    $('#div_scenarioElement div.elementCODE.elementCollapse').each(function() {
      $(this).removeClass('elementCollapse')
      $(this).find('textarea[data-l1key="expression"]').show()
    })
    jeeP.setEditors()
    $('textarea[data-l1key="expression"]').hide()
    searchField.focus()
  } else {
    if (searchField.val() == '') {
      $('i.fa-eye-slash').each(function() {
        $(this).parents('.element').first().addClass('elementCollapse')
      })
      btn.find('i').removeClass('fa-times').addClass('fa-search')
      searchField.hide()
      btn.data('state', '0')
    } else {
      searchField.val('').keyup()
    }
  }
})


//Handle auto hide context menu
$('#div_pageContainer').on({
  'mouseleave': function(event) {
    $(this).fadeOut().trigger('contextmenu:hide')
  }
}, '.context-menu-root')

//tabs context menu
try {
    $.contextMenu('destroy', $('.nav.nav-tabs'))
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
          $.contextMenu({
            selector: '.nav.nav-tabs li',
            appendTo: 'div#div_pageContainer',
            zIndex: 9999,
            className: 'scenario-context-menu',
            callback: function(key, options, event) {
              if (!jeedomUtils.checkPageModified()) {
                if (event.ctrlKey || event.metaKey || event.originalEvent.which == 2) {
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
  } catch (err) {}


//general context menu
try {
    $.contextMenu({
    selector: "#accordionScenario .scenarioDisplayCard",
    appendTo: 'div#div_pageContainer',
    build: function($trigger) {
      var scGroups = []
      Object.keys(jeephp2js.scenarioListGroup).forEach(function(key) {
        scGroups.push(jeephp2js.scenarioListGroup[key].group)
      })

      var scId = $trigger.attr('data-scenario_id')
      var isActive = !$trigger.hasClass('inactive')

      var contextmenuitems = {}
      contextmenuitems['scId'] = {'name': 'id: ' + scId, 'id': 'scId', 'disabled': true}
      if (isActive) {
        contextmenuitems['disable'] = {'name': '{{Rendre inactif}}', 'id': 'disable', 'icon': 'fas fa-toggle-on'}
      } else {
        contextmenuitems['enable'] = {'name': '{{Rendre actif}}', 'id': 'enable', 'icon': 'fas fa-toggle-off'}
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
              scenario : scenario,
              error: function(error) {
                jeedomUtils.showAlert({message: error.message, level: 'danger'})
              },
              success: function(data) {
                $('.scenarioDisplayCard[data-scenario_id="' + data.id + '"]').appendTo($('div.scenarioListContainer[data-groupName="' + key + '"]'))
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
              scenario : scenario,
              error: function(error) {
                jeedomUtils.showAlert({message: error.message, level: 'danger'})
              },
              success: function(data) {
                $('.scenarioDisplayCard[data-scenario_id="' + data.id + '"]').find('.name .label').replaceWith(humanName)
                $('.scenarioDisplayCard[data-scenario_id="' + data.id + '"]').find('.name .label i').remove()
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
              scenario : scenario,
              error: function(error) {
                jeedomUtils.showAlert({message: error.message, level: 'danger'})
              },
              success: function(data) {
                $('.scenarioDisplayCard[data-scenario_id="' + data.id + '"]').addClass('inactive')
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
              scenario : scenario,
              error: function(error) {
                jeedomUtils.showAlert({message: error.message, level: 'danger'})
              },
              success: function(data) {
                $('.scenarioDisplayCard[data-scenario_id="' + data.id + '"]').removeClass('inactive')
              }
            })
            return true
          }
        },
        items: contextmenuitems
      }
    }
  })
  } catch (err) {}


/* ---------Scenario Management UI---------- */
$("#bt_addScenario").off('click').on('click', function(event) {
  bootbox.prompt("{{Nom du scénario}} ?", function(result) {
    if (result !== null) {
      jeedom.scenario.save({
        scenario: {
          name: result
        },
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          });
        },
        success: function(data) {
          var vars = getUrlVars();
          var url = 'index.php?';
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
$("#bt_changeAllScenarioState").off('click').on('click', function() {
  var el = $(this)
  if (el.attr('data-state') == 0) {
    var msg = '{{Êtes-vous sûr de vouloir désactiver les scénarios ?}}'
  } else {
    var msg = '{{Êtes-vous sûr de vouloir activer les scénarios ?}}'
  }

  bootbox.confirm(msg, function(result) {
    if (result) {
      jeedom.config.save({
        configuration: {
          enableScenario: el.attr('data-state')
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

$(".bt_clearAllLogs").on('click', function(event) {
  bootbox.confirm("{{Êtes-vous sûr de vouloir supprimer tous les logs des scénarios ?}}", function(result) {
    if (result) {
      jeedom.scenario.clearAllLogs({
        error: function(error) {
          $('#div_alertError').showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          $('#div_alertError').showAlert({
            message: "{{Logs des scénarios supprimés.}}",
            level: 'success'
          })
        }
      })
    }
  })
})


$('.bt_showScenarioSummary').off('click').on('click', function() {
  $('#md_modal').dialog({
    title: "{{Vue d'ensemble des scénarios}}"
  }).load('index.php?v=d&modal=scenario.summary').dialog('open')
})

$('#bt_generalTab').on('click', function() {
  $('#bt_resetInsideScenarioSearch').addClass('disabled')
  $('#in_searchInsideScenario').prop("disabled", true)
})

$('#bt_scenarioTab').on('click', function() {
  $('#bt_resetInsideScenarioSearch').removeClass('disabled')
  $('#in_searchInsideScenario').prop("disabled", false)
  setTimeout(function() {
    jeeP.setEditors()
    jeedomUtils.taAutosize()
    jeeP.updateElseToggle()
  }, 50)
})

$('.scenarioAttr[data-l2key="timeline::enable"]').off('change').on('change', function() {
  if (this.jeeValue() == 1) {
    $('.scenarioAttr[data-l2key="timeline::folder"]').show()
  } else {
    $('.scenarioAttr[data-l2key="timeline::folder"]').hide()
  }
})

$('.scenarioDisplayCard').off('click').on('click', function(event) {
  if (event.ctrlKey || event.metaKey) {
    var url = '/index.php?v=d&p=scenario&id=' + $(this).attr('data-scenario_id')
    window.open(url).focus()
  } else {
    $('#scenarioThumbnailDisplay').hide()
    jeeP.printScenario($(this).attr('data-scenario_id'))
  }
})
$('.scenarioDisplayCard').off('mouseup').on('mouseup', function(event) {
  if (event.which == 2) {
    event.preventDefault()
    var id = $(this).attr('data-scenario_id')
    $('.scenarioDisplayCard[data-scenario_id="' + id + '"]').trigger(jQuery.Event('click', {
      ctrlKey: true
    }))
  }
})

$('#bt_scenarioThumbnailDisplay').off('click').on('click', function() {
  setTimeout(function() {
    $('.nav li.active').removeClass('active')
    $('a[data-target="#' + $('.tab-pane.active').attr('id') + '"]').closest('li').addClass('active')
  }, 500)
  if (jeedomUtils.checkPageModified()) return

  $('#div_editScenario').hide()
  $('#scenarioThumbnailDisplay').show()
  jeedomUtils.addOrUpdateUrl('id', null, '{{Scénario}} - ' + JEEDOM_PRODUCT_NAME)
})


/* ---------Scenario UI---------- */
document.registerEvent('keydown', function(event) {
  if (jeedomUtils.getOpenedModal()) return

  if ((event.ctrlKey || event.metaKey) && event.which == 83) { //s
    event.preventDefault()
    if ($('#bt_saveScenario').is(':visible')) {
      jeeP.saveScenario()
      return
    }
  }

  if ((event.ctrlKey || event.metaKey) && event.which == 76) { //l
    event.preventDefault()
    $('#md_modal').dialog({
      title: "{{Log d'exécution du scénario}}"
    }).load('index.php?v=d&modal=scenario.log.execution&scenario_id=' + document.querySelector('.scenarioAttr[data-l1key=id]').jeeValue()).dialog('open')
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

//ctrl-click input popup
jeeP.$divScenario.on('click', 'input:not([type="checkbox"]).expressionAttr, textarea.expressionAttr', function(event) {
  if (!event.ctrlKey) return true
  var $thisInput = $(this)
  var button = '<button class="btn btn-default bt_selectBootboxCmdExpression" type="button"><i class="fas fa-list-alt"></i></button>'
  bootbox.prompt({
    title: '{{Edition}}',
    size: 'large',
    inputType: "textarea",
    container: jeeP.$divScenario,
    backdrop: false,
    onShown: function(event) {
      $(this).addClass('bootboxScInput')
      $(this).find('.bootbox-input-textarea')
        .val($thisInput.val())
        .addClass('expression')
        .after(button)
    },
    callback: function(result) {
      if (result != null) {
        $thisInput.val(result)
      }
    }
  })
})
jeeP.$divScenario.on('click', '.bt_selectBootboxCmdExpression', function(event) {
  var expression = this.parentNode.querySelector('.expression')
  jeedom.cmd.getSelectModal({}, function(result) {
    expression.insertAtCursor(result.human)
  })
})




$('#div_scenarioElement').on('click', ':input', function() {
  jeeP.PREV_FOCUS = $(this)
})

jeedom.timeline.autocompleteFolder()
$('sub.itemsNumber').html('(' + $('.scenarioDisplayCard').length + ')')
if (jeephp2js.initSearch != 0) {
  setTimeout(function() {
    $('#bt_scenarioTab').trigger('click')
    $('#bt_resetInsideScenarioSearch').trigger('click')
    setTimeout(function() {
      $('#in_searchInsideScenario').val(jeephp2js.initSearch).keyup().blur().focus()
    }, 500)
  }, 200)
}

//trigger:
setTimeout(function() {
  jeeP.checkNoMode()
}, 250)

$('.scenario_link_getUsedBy, .scenario_link_getUse').off('click', '.scenario_link').on('click', '.scenario_link', function(event) {
  jeedomUtils.hideAlert()
  if (event.ctrlKey || event.metaKey) {
    var url = '/index.php?v=d&p=scenario&id=' + $(this).attr('data-scenario_id')
    window.open(url).focus()
  } else {
    $('#scenarioThumbnailDisplay').hide()
    jeeP.printScenario($(this).attr('data-scenario_id'))
  }
})
$('.scenario_link_getUsedBy, .scenario_link_getUse').off('mouseup', '.scenario_link').on('mouseup', '.scenario_link', function(event) {
  if (event.which == 2) {
    event.preventDefault()
    var id = $(this).attr('data-scenario_id')
    $('.scenario_link[data-scenario_id="' + id + '"]').trigger(jQuery.Event('click', {
      ctrlKey: true
    }))
  }
})

$('.defined_actions').off('click', '.action_link').on('click', '.action_link', function(event) {
  jeedomUtils.hideAlert()
  var cmdId = $(this).attr('data-cmd_id')
  $('#md_modal').dialog().load('index.php?v=d&modal=cmd.configure&cmd_id=' + cmdId).dialog('open')

})

$('#bt_chooseIcon').on('click', function() {
  var _icon = false
  if ($('div[data-l2key="icon"] > i').length) {
    _icon = $('div[data-l2key="icon"] > i').attr('class')
    _icon = '.' + _icon.replace(' ', '.')
  }
  jeedomUtils.chooseIcon(function(_icon) {
    $('.scenarioAttr[data-l1key="display"][data-l2key="icon"]').empty().append(_icon)
  }, {
    icon: _icon
  })
  jeeFrontEnd.modifyWithoutSave = true
})

$('.scenarioAttr[data-l1key=display][data-l2key=icon]').on('dblclick', function() {
  document.querySelector('.scenarioAttr[data-l1key="display"][data-l2key="icon"]').value = ''
})

$('.scenarioAttr[data-l1key=group]').autocomplete({
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
})

$('.scenarioAttr[data-l1key=mode]').off('change').on('change', function() {
  $('#bt_addSchedule').removeClass('roundedRight')
  $('#bt_addTrigger').removeClass('roundedRight')
  if (this.jeeValue() == 'schedule' || this.jeeValue() == 'all') {
    $('.scheduleDisplay').show()
    $('#bt_addSchedule').show()
  } else {
    $('.scheduleDisplay').hide()
    $('#bt_addSchedule').hide()
    $('#bt_addTrigger').addClass('roundedRight')
  }
  if (this.jeeValue() == 'provoke' || this.jeeValue() == 'all') {
    $('.provokeDisplay').show()
    $('#bt_addTrigger').show()
  } else {
    $('.provokeDisplay').hide()
    $('#bt_addTrigger').hide()
    $('#bt_addSchedule').addClass('roundedRight')
  }
  if (this.jeeValue() == 'all') {
    $('#bt_addSchedule').addClass('roundedRight')
  }
})

$('#bt_addTrigger').off('click').on('click', function() {
  jeeP.addTrigger('')
  jeeP.checkNoMode()
})

$('#bt_addSchedule').off('click').on('click', function() {
  jeeP.addSchedule('')
  jeeP.checkNoMode()
})

jeeP.$divScenario.on('click', '.bt_removeTrigger', function(event) {
  $(this).closest('.trigger').remove()
  jeeP.checkNoMode()
})

jeeP.$divScenario.on('click', '.bt_removeSchedule', function(event) {
  $(this).closest('.schedule').remove()
  jeeP.checkNoMode()
})

jeeP.$divScenario.on('click', '.bt_selectTrigger', function(event) {
  var el = this
  jeedom.cmd.getSelectModal({
    cmd: {
      type: 'info'
    }
  }, function(result) {
    el.closest('.trigger').querySelector('.scenarioAttr[data-l1key="trigger"]').jeeValue(result.human)
  })
})

jeeP.$divScenario.on('click', '.bt_selectDataStoreTrigger', function(event) {
  var el = this
  jeedom.dataStore.getSelectModal({
    cmd: {
      type: 'info'
    }
  }, function(result) {
    el.closest('.trigger').querySelector('.scenarioAttr[data-l1key="trigger"]').jeeValue(result.human)
  })
})

jeeP.$divScenario.on('click', '.bt_selectGenericTrigger', function(event) {
  var el = this
  jeedom.config.getGenericTypeModal({
    type: 'info',
    object: true
  }, function(result) {
    el.closest('.trigger').querySelector('.scenarioAttr[data-l1key="trigger"]').jeeValue('#' + result.human + '#')
  })
})


//Scenario bar:
jeeP.$divScenario.on('click', '.bt_addScenarioElement', function(event) {
  if (!window.location.href.includes('#scenariotab')) $('#bt_scenarioTab').trigger('click')
  var expression = false
  var insertAfter = false
  var elementDiv = $(this).closest('.element')

  //is scenario empty:
  if ($('#div_scenarioElement').children('.element').length == 0) {
    elementDiv = $('#div_scenarioElement')
    $('#div_scenarioElement .span_noScenarioElement').remove()
  } else {
    //had focus ?
    if (jeeP.PREV_FOCUS != null && $(jeeP.PREV_FOCUS).closest('div.element').html() != undefined) {
      insertAfter = true
      elementDiv = $(jeeP.PREV_FOCUS).closest('div.element')
      if (elementDiv.parent().attr('id') != 'div_scenarioElement') {
        elementDiv = elementDiv.parents('.expression').eq(0)
        expression = true
      }
    } else {
      elementDiv = $('#div_scenarioElement')
    }
  }

  $('#md_addElement').modal('show')
  $("#bt_addElementSave").off('click').on('click', function(event) {
    jeeP.setUndoStack()
    if (expression) {
      var newEL = $(jeeP.addExpression({
        type: 'element',
        element: {
          type: document.getElementById("in_addElementType").jeeValue()
        }
      }))
    } else {
      var newEL = $(jeeP.addElement({
        type: document.getElementById("in_addElementType").jeeValue()
      }))
    }
    if (insertAfter) {
      elementDiv.after(newEL.addClass('disableElement'))
    } else {
      elementDiv.append(newEL.addClass('disableElement'))
    }

    jeeP.setEditors()
    jeeP.updateSortable()
    jeeP.updateElseToggle()
    $('#md_addElement').modal('hide')
    jeeFrontEnd.modifyWithoutSave = true
    jeeP.updateTooltips()
    jeedom.scenario.setAutoComplete()
    setTimeout(function() {
      newEL.removeClass('disableElement')
    }, 600)
  })
})
$('#in_addElementType').off('change').on('change', function() {
  document.querySelectorAll('.addElementTypeDescription').unseen()
  document.querySelectorAll('.addElementTypeDescription.' + this.jeeValue()).seen()
})

$('#bt_logScenario').off('click').on('click', function() {
  $('#md_modal').dialog({
    title: "{{Log d'exécution du scénario}}"
  }).load('index.php?v=d&modal=scenario.log.execution&scenario_id=' + document.querySelector('.scenarioAttr[data-l1key="id"]').jeeValue()).dialog('open')
})

$("#bt_copyScenario").off('click').on('click', function() {
  bootbox.prompt("{{Nom du scénario}} ?", function(result) {
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
})

$('#bt_graphScenario').off('click').on('click', function() {
  $('#md_modal').dialog({
    title: "{{Graphique de lien(s)}}"
  }).load('index.php?v=d&modal=graph.link&filter_type=scenario&filter_id=' + document.querySelector('.scenarioAttr[data-l1key="id"]').jeeValue()).dialog('open')
})

$('#bt_editJsonScenario').on('click', function() {
  $('#md_modal').dialog({
    title: "{{Edition texte scénarios}}"
  }).load('index.php?v=d&modal=scenario.jsonEdit&id=' + document.querySelector('.scenarioAttr[data-l1key="id"]').jeeValue()).dialog('open')
})

$('#bt_exportScenario').off('click').on('click', function() {
  $('#md_modal').dialog({
    title: "{{Export du scénario}}"
  }).load('index.php?v=d&modal=scenario.export&scenario_id=' + document.querySelector('.scenarioAttr[data-l1key="id"]').jeeValue()).dialog('open')
})

$('#bt_templateScenario').off('click').on('click', function() {
  $('#md_modal').dialog({
    title: "{{Template de scénario}}"
  }).load('index.php?v=d&modal=scenario.template&scenario_id=' + document.querySelector('.scenarioAttr[data-l1key="id"]').jeeValue()).dialog('open')
})

$("#bt_runScenario").off('click').on('click', function(event) {
  jeedomUtils.hideAlert()
  var scenario_id = document.querySelector('.scenarioAttr[data-l1key="id"]').jeeValue()
  var logmode = $('button[data-l2key="logmode"]').attr('value')
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
            $('#md_modal').dialog({
                title: "{{Log d'exécution du scénario}}"
              })
              .load('index.php?v=d&modal=scenario.log.execution&scenario_id=' + scenario_id)
              .dialog('open')
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
})

$("#bt_stopScenario").off('click').on('click', function() {
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
})

$("#bt_saveScenario").off('click').on('click', function(event) {
  jeeP.saveScenario()
  jeeP.clipboard = null
})

$("#bt_delScenario").off('click').on('click', function(event) {
  jeedomUtils.hideAlert()
  bootbox.confirm('{{Êtes-vous sûr de vouloir supprimer le scénario}} <span style="font-weight: bold ;">' + document.querySelector('.scenarioAttr[data-l1key="name"]').jeeValue() + '</span> ?', function(result) {
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
})


/*******************Element***********************/
jeeP.$divScenario.on('change', '.subElementAttr[data-l1key="options"][data-l2key="enable"]', function() {
  var checkbox = this
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
})

jeeP.$divScenario.on('change', '.expressionAttr[data-l1key="options"][data-l2key="enable"]', function() {
  var checkbox = this
  var element = checkbox.closest('.expression')
  if (checkbox.checked) {
    element.removeClass('disableSubElement')
  } else {
    element.addClass('disableSubElement')
  }
})

jeeP.$divScenario.on('click', '.bt_removeElement', function(event) {
  var button = $(this)
  if (event.ctrlKey || event.metaKey) {
    if (button.closest('.expression').length != 0) {
      jeeP.setUndoStack()
      button.closest('.expression').remove()
    } else {
      jeeP.setUndoStack()
      button.closest('.element').remove()
    }
  } else {
    bootbox.confirm("{{Êtes-vous sûr de vouloir supprimer ce bloc ?}}", function(result) {
      if (result) {
        if (button.closest('.expression').length != 0) {
          jeeP.setUndoStack()
          button.closest('.expression').remove()
        } else {
          jeeP.setUndoStack()
          button.closest('.element').remove()
        }
      }
    })
  }
  jeeFrontEnd.modifyWithoutSave = true
  jeeP.PREV_FOCUS = null
})

jeeP.$divScenario.on('click', '.bt_addAction', function(event) {
  jeeP.setUndoStack()
  $(this).closest('.subElement').children('.expressions').append(jeeP.addExpression({
    type: 'action'
  }))
  jeedom.scenario.setAutoComplete()
  jeeP.updateSortable()
  jeeP.updateTooltips()
})

jeeP.$divScenario.on('click', '.bt_showElse', function(event) {
  if ($(this).children('i').hasClass('fa-sort-down')) {
    $(this).children('i').removeClass('fa-sort-down').addClass('fa-sort-up')
    $(this).closest('.element').children('.subElementELSE').show()
  } else {
    if ($(this).closest('.element').children('.subElementELSE').children('.expressions').children('.expression').length > 0) {
      jeedomUtils.showAlert({
        message: "{{Le bloc Sinon ne peut être masqué s'il contient des éléments.}}",
        level: 'danger'
      })
      return
    }
    $(this).children('i').removeClass('fa-sort-up').addClass('fa-sort-down')
    $(this).closest('.element').children('.subElementELSE').hide()
  }
})

jeeP.$divScenario.on('click', '.bt_collapse', function(event) {
  var changeThis = $(this)
  if (event.ctrlKey || event.metaKey) changeThis = $('.element').find('.bt_collapse')
  if ($(this).children('i').hasClass('fa-eye')) {
    // -> Collapse!
    changeThis.children('i').removeClass('fa-eye').addClass('fa-eye-slash')
    changeThis.closest('.element').addClass('elementCollapse')
    changeThis.attr('value', 1)
    changeThis.attr('title', "{{Afficher ce bloc.<br>Ctrl+click: tous.}}")
    //update action, comment and code blocPreview:
    var txt, _el, id
    changeThis.closest('.element').find('.blocPreview').each(function() {
      txt = '<i>Unfound</i>'
      _el = $(this).closest('.element')
      if (_el.hasClass('elementACTION')) {
        txt = _el.find('.expressions .expression').first().find('input.form-control').first().val()
        if (!txt) txt = _el.find('.expression textarea').val()
      } else if (_el.hasClass('elementCODE')) {
        id = _el.find('.expressionAttr[data-l1key="expression"]').attr('id')
        if (isset(jeeP.editors[id])) txt = jeeP.editors[id].getJeeValue()
      } else {
        //comment
        txt = _el.find('.expression textarea').val().HTMLFormat()
        if (typeof txt === 'object') {
          txt = JSON.stringify(expression.expression)
        }
        txt = '<b>' + txt.split('\n')[0] + '</b>' + txt.replace(txt.split('\n')[0], '')
        if (!txt) txt = _el.find('.expression input.form-control').val()
      }
      if (txt) $(this).html(txt.substring(0, 200))
    })
    jeeP.updateTooltips()
  } else {
    // -> Uncollapse!
    changeThis.children('i').addClass('fa-eye').removeClass('fa-eye-slash')
    changeThis.closest('.element').removeClass('elementCollapse')
    changeThis.attr('value', 0)
    changeThis.attr('title', "{{Masquer ce bloc.<br>Ctrl+click: tous.}}")
    jeeP.setEditors()
    jeeP.updateTooltips()
  }
})

jeeP.$divScenario.on('click', '.bt_removeExpression', function(event) {
  jeeP.setUndoStack()
  $(this).closest('.expression').remove()
  jeeP.updateSortable()
})

$('body').on('click', '.modal-body .bt_selectCmdFromModal', function(event) {
  var modal = $(this).closest('.bootbox.modal')
  modal.hide()
  jeedom.cmd.getSelectModal({
    cmd: {
      type: 'info'
    },
    returnCancel: 1
  }, function(result) {
    modal.show()
    if (isset(result.human)) modal.find('input[data-l1key="operande"]').val(result.human)
  })
})

jeeP.$divScenario.on('click', '.bt_selectCmdExpression', function(event) {
  var el = this
  var expression = this.closest('.expression')
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
        $(expression).find('.expressionOptions').html(html)
        jeedomUtils.taAutosize()
        jeeP.updateTooltips()
      })
    }

    if (expression.querySelector('.expressionAttr[data-l1key="type"]').jeeValue() == 'condition') {
      var condType = el.closest('.subElement')
      if (!condType.hasClass('subElementIF') && !condType.hasClass('subElementFOR')) {
        expression.querySelector('.expressionAttr[data-l1key="expression"]').insertAtCursor(result.human)
        return
      }

      var message = jeeP.getSelectCmdExpressionMessage(result.cmd.subType, result.human)
      bootbox.dialog({
        title: "{{Ajout d'une nouvelle condition}}",
        message: message,
        size: 'large',
        buttons: {
          "{{Ne rien mettre}}": {
            className: "btn-default",
            callback: function() {
              expression.querySelector('.expressionAttr[data-l1key="expression"]').insertAtCursor(result.human)
            }
          },
          success: {
            label: "{{Valider}}",
            className: "btn-primary",
            callback: function() {
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
              expression.querySelector('.expressionAttr[data-l1key="expression"]').insertAtCursor(result.human)
              if (document.querySelector('.conditionAttr[data-l1key="next"]').jeeValue() != '') {
                el.triggerEvent('click')
              }
            }
          },
        }
      })
    }
  })
})

jeeP.$divScenario.on('click', '.bt_selectOtherActionExpression', function(event) {
  var expression = this.closest('.expression')
  jeedom.getSelectActionModal({
    scenario: true
  }, function(result) {
    jeeP.setUndoStack()
    expression.querySelector('.expressionAttr[data-l1key="expression"]').jeeValue(result.human);
    jeedom.cmd.displayActionOption(result.human, '', function(html) {
      $(expression).find('.expressionOptions').html(html)
      jeedomUtils.taAutosize()
    })
  })
})

jeeP.$divScenario.on('click', '.bt_selectScenarioExpression', function(event) {
  var expression = this.closest('.expression')
  jeedom.scenario.getSelectModal({}, function(result) {
    if (expression.querySelector('.expressionAttr[data-l1key="type"]').jeeValue() == 'action') {
      expression.querySelector('.expressionAttr[data-l1key="expression"]').jeeValue(result.human)
    }
    if (expression.querySelector('.expressionAttr[data-l1key="type"]').jeeValue() == 'condition') {
      expression.querySelector('.expressionAttr[data-l1key="expression"]').insertAtCursor(result.human)
    }
  })
})

jeeP.$divScenario.on('click', '.bt_selectGenericExpression', function(event) {
  var expression = this.closest('.expression')
  jeedom.config.getGenericTypeModal({type: 'info', object: true}, function(result) {
    expression.querySelector('.expressionAttr[data-l1key="expression"]').insertAtCursor(result.human)
  })
})

jeeP.$divScenario.on('click', '.bt_selectEqLogicExpression', function(event) {
  var expression = this.closest('.expression')
  jeedom.eqLogic.getSelectModal({}, function(result) {
    if (expression.querySelector('.expressionAttr[data-l1key="type"]').jeeValue() == 'action') {
      expression.querySelector('.expressionAttr[data-l1key="expression"]').jeeValue(result.human)
    }
    if (expression.querySelector('.expressionAttr[data-l1key="type"]').jeeValue() == 'condition') {
      expression.querySelector('.expressionAttr[data-l1key="expression"]').insertAtCursor(result.human)
    }
  })
})

jeeP.$divScenario.on('focusout', '.expression .expressionAttr[data-l1key="expression"]', function(event) {
  if (this.getAttribute('prevalue') == this.value) return
  var el = this
  if (el.closest('.expression').querySelector('.expressionAttr[data-l1key="type"]').value == 'action') {
    var expression = el.closest('.expression').getJeeValues('.expressionAttr')
    jeedom.cmd.displayActionOption(el.value, init(expression[0].options), function(html) {
      $(el).closest('.expression').find('.expressionOptions').html(html)
      jeedomUtils.taAutosize()
      jeeP.updateTooltips()
      el.setAttribute('prevalue', el.value)
    })
  }
})

//COPY - PASTE
jeeP.$divScenario.on('click', '.bt_copyElement', function(event) {
  var clickedBloc = this.closest('.element')

  jeeP.clipboard = jeeP.getElement(clickedBloc)

  //delete all id in properties to make it unique:
  jeeP.removeObjectProp(jeeP.clipboard, 'id')
  localStorage.removeItem('jeedomScCopy')
  localStorage.setItem('jeedomScCopy', JSON.stringify(jeeP.clipboard))

  //cut:
  if (event.ctrlKey || event.metaKey) {
    jeeP.setUndoStack()
    clickedBloc.remove()
  }
  jeeFrontEnd.modifyWithoutSave = true
})

jeeP.$divScenario.on('click', '.bt_pasteElement', function(event) {
  if (localStorage.getItem('jeedomScCopy')) {
    jeeP.clipboard = JSON.parse(localStorage.getItem('jeedomScCopy'))
  } else {
    return false
  }
  var clickedBloc = $(this).closest('.element')

  jeeP.setUndoStack()
  jeeP.actionOptions = []
  var pastedElement = jeeP.addElement(jeeP.clipboard)
  pastedElement = $(pastedElement)

  //Are we pasting inside an expresion:
  if (clickedBloc.parent('#div_scenarioElement').length) {
    //get the element if copied from an expression:
    if (pastedElement.hasClass('expression')) {
      pastedElement = pastedElement.find('.element')
    }
    pastedElement.insertAfter(clickedBloc)
  } else {
    //make it an expression if not yet:
    if (pastedElement.hasClass('expression')) {
      pastedElement.insertAfter(clickedBloc.parent().parent())
    } else {
      var newDiv = '<div class="expression sortable col-xs-12">'
      newDiv += '<input class="expressionAttr" data-l1key="type" style="display: none;" value="element">'
      newDiv += '<div class="col-xs-12" id="insertHere">'
      newDiv += '</div>'
      newDiv += '</div>'
      $(newDiv).insertAfter(clickedBloc.parent().parent())
      pastedElement.appendTo('#insertHere')
    }
  }

  $('#insertHere').removeAttr('id')

  //Synch collapsed elements:
  pastedElement.find('i.fa-eye-slash').each(function() {
    $(this).parents('.element').first().addClass('elementCollapse')
  })
  //Synch disabled elements:
  pastedElement.find('input[data-l1key="options"][data-l2key="enable"]:not(:checked)').each(function() {
    $(this).trigger('change')
  })

  jeeP.updateElseToggle()

  //replace:
  if (event.ctrlKey || event.metaKey) {
    clickedBloc.remove()
  }

  jeeP.setScenarioActionsOptions()
  jeeP.updateSortable()
  jeedom.scenario.setAutoComplete()
  jeeP.setEditors()
  jeeP.updateTooltips()

  setTimeout(function() { jeeP.updateTooltips() }, 500)

  jeeFrontEnd.modifyWithoutSave = true
})

jeeP.$divScenario.on('mouseenter', '.bt_sortable', function() {
  var expressions = $(this).closest('.expressions')
  $("#div_scenarioElement").sortable({
    cursor: "move",
    grid: [5, 15],
    items: ".sortable",
    appendTo: $("#div_scenarioElement"),
    zIndex: 0,
    opacity: 0.5,
    forcePlaceholderSize: true,
    placeholder: "sortable-placeholder",
    start: function(event, ui) {
      $('.dropdown.open').removeClass('open')
      if (expressions.find('.sortable').length < 3) {
        expressions.find('.sortable.empty').show()
      }
    },
    change: function(event, ui) {
      if (ui.placeholder.next().length == 0) {
        ui.placeholder.addClass('sortable-placeholderLast')
      } else {
        ui.placeholder.removeClass('sortable-placeholderLast')
      }

      var getClass = true
      if (ui.placeholder.parent().hasClass('subElement')) {
        getClass = false
      }
      if (ui.helper.hasClass('expressionACTION') && ui.placeholder.parent().attr('id') == 'div_scenarioElement') {
        getClass = false
      }
      var thisSub = ui.placeholder.parents('.expressions').parents('.subElement')
      if (thisSub.hasClass('subElementCOMMENT') || thisSub.hasClass('subElementCODE')) {
        getClass = false
      }

      if (getClass) {
        ui.placeholder.addClass('sortable-placeholder')
      } else {
        ui.placeholder.removeClass('sortable-placeholder')
      }
    },
    update: function(event, ui) {
      if (ui.item.closest('.subElement').hasClass('subElementCOMMENT')) {
        $("#div_scenarioElement").sortable('cancel')
      }
      if (ui.item.findAtDepth('.element', 2).length == 1 && ui.item.parent().attr('id') == 'div_scenarioElement') {
        ui.item.replaceWith(ui.item.findAtDepth('.element', 2))
      }

      if (ui.item.hasClass('element') && ui.item.parent().attr('id') != 'div_scenarioElement') {
        ui.item.find('.expressionAttr,.subElementAttr,.elementAttr').each(function() {
          var value = this.jeeValue()
          if (value != undefined && value != '') {
            $(this).attr('data-tmp-value', value)
          }
        })
        var el = $(jeeP.addExpression({
          type: 'element',
          element: {
            html: ui.item.wrapAll("<div/>").parent().html()
          }
        }))
        var value
        el.find('.expressionAttr,.subElementAttr,.elementAttr').each(function() {
          value = $(this).attr('data-tmp-value')
          if (value != undefined && value != '') {
            this.jeeValue(value)
          }
          $(this).removeAttr('data-tmp-value')
        })
        ui.item.parent().replaceWith(el)
      }

      if (ui.item.hasClass('expression') && ui.item.parent().attr('id') == 'div_scenarioElement') {
        $("#div_scenarioElement").sortable("cancel")
      }
      if (ui.item.closest('.subElement').hasClass('noSortable')) {
        $("#div_scenarioElement").sortable("cancel")
      }

      jeeP.updateTooltips()
      jeeP.updateSortable()
    },
    stop: function(event, ui) {
      $("#div_scenarioElement").sortable("disable")
      ui.item.attr('style', '')
      jeeFrontEnd.modifyWithoutSave = true
    }
  })
  $("#div_scenarioElement").sortable("enable")
})

jeeP.$divScenario.on('mousedown', '.bt_sortable', function() {
  jeeP.setUndoStack()
})

jeeP.$divScenario.on('mouseout', '.bt_sortable', function() {
  $("#div_scenarioElement").sortable("disable")
})

jeeP.$divScenario.on('click', '.subElementAttr[data-l1key="options"][data-l2key="allowRepeatCondition"]', function() {
  if ($(this).attr('value') == 0) {
    $(this).attr('value', 1).html('<span><i class="fas fa-ban text-danger"></i></span>')
  } else {
    $(this).attr('value', 0).html('<span><i class="fas fa-sync"></span>')
  }
})

/**************** Initialisation **********************/
if (is_numeric(getUrlVars('id'))) {
  if ($('.scenarioDisplayCard[data-scenario_id="' + getUrlVars('id') + '"]').length != 0) {
    $('.scenarioDisplayCard[data-scenario_id=' + getUrlVars('id') + ']').click()
  }
}

jeeP.$divScenario.on('shown.bs.dropdown', '.dropdown', function() {
  if ( $(this).offset().top > window.innerHeight - 220) {
    $(this).addClass('dropup')
  }
})
.on('hidden.bs.dropdown', '.dropdown', function() {
  $(this).removeClass("dropup")
})

jeeP.$divScenario.on('click', '.fromSubElement', function(event) {
  var elementType = $(this).attr('data-type')
  jeeP.setUndoStack()

  var elementDiv = $(this).closest('.subElement').find('.expressions').eq(0)
  var newEL = $(jeeP.addExpression({
    type: 'element',
    element: {
      type: elementType
    }
  }))
  elementDiv.append(newEL.addClass('disableElement'))

  jeeP.setEditors()
  jeeP.updateSortable()
  jeeP.updateElseToggle()
  jeeFrontEnd.modifyWithoutSave = true
  jeeP.updateTooltips()
  jeedom.scenario.setAutoComplete()
  setTimeout(function() {
    newEL.removeClass('disableElement')
  }, 600)
})


/*Events delegation
Seperated between edit tab, floating bar, and scenarioo elements div.
*/
document.getElementById('generaltab').addEventListener('click', function(event) {
  //console.log('click __ generaltab', event)


})

document.getElementById('div_editScenario').querySelector('div.floatingbar').addEventListener('click', function(event) {
  //console.log('click __ floatingbar', event)
  if (event.target.matches('#bt_undo, #bt_undo i')) {
    if (!jeedomUtils.getOpenedModal()) {
      jeeP.undo()
      jeeP.PREV_FOCUS = null
    }
    return
  }
  if (event.target.matches('#bt_redo, #bt_redo i')) {
    if (!jeedomUtils.getOpenedModal()) {
      jeeP.redo()
      jeeP.PREV_FOCUS = null
    }
    return
  }
})

document.getElementById('scenariotab').addEventListener('click', function(event) {
  //console.log('click __ div_scenarioElement', event)

})

document.getElementById('scenariotab').addEventListener('mouseenter', function(event) {
  //console.log('mouseenter __ div_scenarioElement', event)

}, {capture: true})
document.getElementById('scenariotab').addEventListener('mousedown', function(event) {
  //console.log('mousedown __ div_scenarioElement', event)

})
document.getElementById('scenariotab').addEventListener('mouseout', function(event) {
  //console.log('mouseout __ div_scenarioElement', event)

})


document.getElementById('div_editScenario').addEventListener('change', function(event) {
  //console.log('change __ div_editScenario', event)
  jeeFrontEnd.modifyWithoutSave = true
})