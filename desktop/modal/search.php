<?php
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

if (!isConnect()) {
  throw new Exception('{{401 - Accès non autorisé}}');
}
?>

<div id="md_search" data-modalType="md_search">
  <a id="bt_getHelpModal" class="cursor" style="position: absolute;right: 20px;top: 15px;" title="{{Aide}}"><i class="fas fa-question-circle" ></i></a>
  <!-- Search engine UI -->
  <form class="form-horizontal shadowed">
    <div class="floatingbar">
      <div class="input-group">
        <div class="input-group-btn col-lg-6">
          <a class="btn btn-sm roundedLeft" href="index.php?v=d&p=display"><i class="fas fa-th"></i> {{Résumé Domotique}}
          </a><a class="btn btn-sm btn-success roundedRight" id="bt_search"><i class="fas fa-search"></i> {{Rechercher}}</a>
        </div>
      </div>
    </div>
    <br/>
    <div class="form-group">
      <label class="col-lg-2 control-label">{{Recherche par}}</label>
      <div class="col-lg-4">
        <select id="sel_searchByType" class="form-control">
          <option value="equipment">{{Equipement}}</option>
          <option value="command">{{Commande}}</option>
          <option value="variable">{{Variable}}</option>
          <option value="plugin">{{Plugin}}</option>
          <option value="string">{{Mot}}</option>
          <option value="id">{{ID}}</option>
        </select>
      </div>
    </div>

    <br/>
    <div id="searchByTypes" class="form-group">
      <label class="col-lg-2 control-label">{{Rechercher}}</label>

      <div class="col-lg-4 searchType" data-searchType="plugin" data-tableFilter="1111110" style="display: none;">
        <select id="in_searchFor_plugin" class="form-control">
          <?php
            $plugins = plugin::listPlugin();
            foreach ($plugins as $plugin) {
              echo '<option value="'.$plugin->getId().'">'.$plugin->getName().'</option>';
            }
          ?>
        </select>
      </div>

      <div class="col-lg-4 searchType" data-searchType="variable" data-tableFilter="1001110" style="display: none;">
        <select id="in_searchFor_variable" class="form-control">
          <?php
            $variables = dataStore::byTypeLinkId('scenario');
            foreach ($variables as $var) {
              echo '<option>'.$var->getKey().'</option>';
            }
          ?>
        </select>
      </div>

      <div class="col-lg-4 searchType" data-searchType="equipment" data-tableFilter="1111110">
        <div class="input-group input-group-sm" >
          <input id="in_searchFor_equipment" class="form-control roundedLeft" value="" />
          <span class="input-group-btn">
            <button type="button" class="btn btn-default cursor bt_selectEqLogic roundedRight" title="{{Rechercher un équipement}}"><i class="fas fa-cube"></i></button>
          </span>
        </div>
      </div>

      <div class="col-lg-4 searchType" data-searchType="command" data-tableFilter="1111110" style="display: none;">
        <div class="input-group input-group-sm" >
          <input id="in_searchFor_command" class="form-control roundedLeft" value="" />
          <span class="input-group-btn">
            <button type="button" class="btn btn-default cursor bt_selectCommand" title="{{Rechercher une commande}}"><i class="fas fa-list-alt"></i></button>
          </span>
        </div>
      </div>

      <div class="col-lg-4 searchType" data-searchType="string" data-tableFilter="1001111" style="display: none;">
        <div class="input-group input-group-sm" >
          <input id="in_searchFor_string" class="form-control roundedLeft" placeholder="{{Rechercher}}"/>
          <span class="input-group-btn">
            <button type="button" class="btn btn-default cursor bt_selectGeneric roundedRight" title="{{Type Générique}}"><i class="fas fa-puzzle-piece"></i></button>
          </span>
        </div>
      </div>

      <div class="col-lg-4 searchType" data-searchType="id" data-tableFilter="1111111" style="display: none;">
        <input id="in_searchFor_id" class="form-control" placeholder="{{Rechercher}}"/>
      </div>
    </div>
    <br/>
  </form>
  <br/>
  <!-- Results UI -->
  <div class="form-horizontal">
    <legend><i class="fas fa-search"></i> {{Résultats}}</legend>
    <div>
      <table id="table_ScenarioSearch" class="table table-condensed dataTable shadowed" style="width:100%; min-width:100%;">
        <thead>
          <tr>
            <th><i class="fas fa-cogs"></i> {{Scénario}}</th>
            <th style="width:250px;">{{ID}}</th>
            <th data-sortable="false" style="width:120px;">{{Actions}}</th>
          </tr>
        </thead>
        <tbody>

        </tbody>
      </table>
    </div>
    <div>
      <table id="table_DesignSearch" class="table table-condensed dataTable shadowed" style="width:100%; min-width:100%;">
        <thead>
          <tr>
            <th><i class="fas fa-paint-brush"></i> {{Design}}</th>
            <th style="width:250px;">{{ID}}</th>
            <th data-sortable="false" style="width:120px;">{{Actions}}</th>
          </tr>
        </thead>
        <tbody>

        </tbody>
      </table>
    </div>
    <div>
      <table id="table_ViewSearch" class="table table-condensed dataTable shadowed" style="width:100%; min-width:100%;">
        <thead>
          <tr>
            <th><i class="far fa-image"></i> {{Vue}}</th>
            <th style="width:250px;">{{ID}}</th>
            <th data-sortable="false" style="width:120px;">{{Actions}}</th>
          </tr>
        </thead>
        <tbody>

        </tbody>
      </table>
    </div>
    <div>
      <table id="table_InteractSearch" class="table table-condensed dataTable shadowed" style="width:100%; min-width:100%;">
        <thead>
          <tr>
            <th><i class="far fa-comments"></i> {{Interaction}}</th>
            <th style="width:250px;">{{ID}}</th>
            <th data-sortable="false" style="width:120px;">{{Actions}}</th>
          </tr>
        </thead>
        <tbody>

        </tbody>
      </table>
    </div>
    <div>
      <table id="table_EqlogicSearch" class="table table-condensed dataTable shadowed" style="width:100%; min-width:100%;">
        <thead>
          <tr>
            <th><i class="icon divers-svg"></i></i> {{Equipement}}</th>
            <th style="width:250px;">{{ID}}</th>
            <th data-sortable="false" style="width:120px;">{{Actions}}</th>
          </tr>
        </thead>
        <tbody>

        </tbody>
      </table>
    </div>
    <div>
      <table id="table_CmdSearch" class="table table-condensed dataTable shadowed" style="width:100%; min-width:100%;">
        <thead>
          <tr>
            <th><i class="fas fa-terminal"></i></i> {{Commande}}</th>
            <th style="width:250px;">{{ID}}</th>
            <th data-sortable="false" style="width:120px;">{{Actions}}</th>
          </tr>
        </thead>
        <tbody>

        </tbody>
      </table>
    </div>

    <div style="display:none;">
      <table id="table_NoteSearch" class="table table-condensed dataTable shadowed" style="width:100%; min-width:100%;">
        <thead>
          <tr>
            <th><i class="fas fa-sticky-note"></i></i> {{Note}}</th>
            <th style="width:250px;">{{ID}}</th>
            <th data-sortable="false" style="width:120px;">{{Actions}}</th>
          </tr>
        </thead>
        <tbody>

        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
if (!jeeFrontEnd.md_search) {
  jeeFrontEnd.md_search = {
    init: function() {
      let self = this
      this.tableScSearch = document.getElementById('table_ScenarioSearch')
      this.tablePlanSearch = document.getElementById('table_DesignSearch')
      this.tableViewSearch = document.getElementById('table_ViewSearch')
      this.tableInteractSearch = document.getElementById('table_InteractSearch')
      this.tableEqlogicSearch = document.getElementById('table_EqlogicSearch')
      this.tableCmdSearch = document.getElementById('table_CmdSearch')
      this.tableNoteSearch = document.getElementById('table_NoteSearch')
      this.tableStore = [
        this.tableScSearch,
        this.tablePlanSearch,
        this.tableViewSearch,
        this.tableInteractSearch,
        this.tableEqlogicSearch,
        this.tableCmdSearch,
        this.tableNoteSearch
      ]

      jeedomUtils.initDataTables('#md_search', false, false)
      this.modalOptions = jeeDialog.get('#table_ScenarioSearch')

      jeedomUtils.initTooltips()
    },
    emptyResultTables: function() {
      this.tableStore.forEach(_table => {
        _table.tBodies[0].empty()
        _table._dataTable.refresh()
      })
    },
    searchFor: function() {
      this.emptyResultTables()
      var searchType = document.getElementById('sel_searchByType').selectedOptions[0].value
      var thisSearch = document.getElementById('in_searchFor_' + searchType).value.toLowerCase()
      if (thisSearch != '') {
        this['searchFor_' + searchType](thisSearch)
      }
    },
    /* ------            Searching            -------*/
    searchFor_variable: function(_searchFor) {
      let self = this
      jeedom.dataStore.byTypeLinkIdKey({
        type: 'scenario',
        linkId: -1,
        key: _searchFor,
        usedBy: 1,
        error: function(error) {
          jeedomUtils.showAlert({
            attachTo: jeeDialog.get('#md_search', 'dialog'),
            message: error.message,
            level: 'danger'
          })
        },
        success: function(result) {
          scenarioResult = []
          interactResult = []
          eqlogicResult = []
          cmdResult = []
          for (var i in result) {
            for (var sc in result[i].usedBy.scenario) {
              scenarioResult.push({'humanName':result[i].usedBy.scenario[sc]['humanNameTag'], 'id':result[i].usedBy.scenario[sc]['id']})
            }
            for (var sc in result[i].usedBy.interactDef) {
              interactResult.push({'humanName':result[i].usedBy.interactDef[sc]['humanName'], 'id':result[i].usedBy.interactDef[sc]['id']})
            }
            for (var sc in result[i].usedBy.eqLogic) {
              eqlogicResult.push({'humanName':result[i].usedBy.eqLogic[sc]['humanName'], 'id':result[i].usedBy.eqLogic[sc]['link']})
            }
            for (var sc in result[i].usedBy.cmd) {
              cmdResult.push({'humanName':result[i].usedBy.cmd[sc]['humanName'], 'id':result[i].usedBy.cmd[sc]['id']})
            }
          }
          self.showScenariosResult(scenarioResult)
          self.showInteractsResult(interactResult)
          self.showEqlogicsResult(eqlogicResult)
          self.showCmdsResult(cmdResult)
        }
      })
    },
    searchFor_plugin: function(_searchFor) {
      let self = this
      jeedom.eqLogic.byType({
        type: _searchFor,
        error: function(error) {
          jeedomUtils.showAlert({
            attachTo: jeeDialog.get('#md_search', 'dialog'),
            message: error.message,
            level: 'danger'
          })
        },
        success: function(result) {
          for (var eq in result) {
            self.searchFor_equipment('', result[eq].id)
          }
        }
      })
    },
    searchFor_equipment: function(_searchFor, _byId=false) {
      if (!_byId) {
        var eQiD = document.getElementById('in_searchFor_equipment').getAttribute('data-id')
      } else {
        var eQiD = _byId
      }

      let self = this
      jeedom.eqLogic.usedBy({
        id: eQiD,
        error: function(error) {
          jeedomUtils.showAlert({
            attachTo: jeeDialog.get('#md_search', 'dialog'),
            message: error.message,
            level: 'danger'
          })
        },
        success: function(result) {
          for (var i in result.scenario) {
            self.showScenariosResult({'humanName':result.scenario[i].humanNameTag, 'id':result.scenario[i].linkId}, false)
          }
          for (var i in result.plan) {
            self.showPlansResult({'humanName':result.plan[i].name, 'id':result.plan[i].id}, false)
          }
          for (var i in result.view) {
            self.showViewsResult({'humanName':result.view[i].name, 'id':result.view[i].id}, false)
          }
          for (var i in result.interactDef) {
            self.showInteractsResult({'humanName':result.interactDef[i].humanName, 'id':result.interactDef[i].linkId}, false)
          }
          for (var i in result.eqLogic) {
            self.showEqlogicsResult({'humanName':result.eqLogic[i].humanName, 'id':result.eqLogic[i].link}, false)
          }
          for (var i in result.cmd) {
            self.showCmdsResult({'humanName':result.cmd[i].humanName, 'id':result.cmd[i].linkId}, false)
          }
          jeedom.eqLogic.getCmd({
            id: eQiD,
            error: function(error) {
              jeedomUtils.showAlert({
                attachTo: jeeDialog.get('#md_search', 'dialog'),
                message: error.message,
                level: 'danger'
              })
            },
            success: function(result) {
              for (var i in result) {
                self.searchFor_command('', result[i].id)
              }
            }
          })
        }
      })
    },
    searchFor_command: function(_searchFor, _byId=false) {
      if (!_byId) {
        var cmdId = document.getElementById('in_searchFor_command').getAttribute('data-id')
      } else {
        var cmdId = _byId
      }

      let self = this
      jeedom.cmd.usedBy({
        id: cmdId,
        error: function(error) {
          jeedomUtils.showAlert({
            attachTo: jeeDialog.get('#md_search', 'dialog'),
            message: error.message,
            level: 'danger'
          })
        },
        success: function(result) {
          for (var i in result.scenario) {
            self.showScenariosResult({'humanName':result.scenario[i].humanNameTag, 'id':result.scenario[i].linkId}, false)
          }
          for (var i in result.plan) {
            self.showPlansResult({'humanName':result.plan[i].name, 'id':result.plan[i].id}, false)
          }
          for (var i in result.view) {
            self.showViewsResult({'humanName':result.view[i].name, 'id':result.view[i].id}, false)
          }
          for (var i in result.interactDef) {
            self.showInteractsResult({'humanName':result.interactDef[i].humanName, 'id':result.interactDef[i].linkId}, false)
          }
          for (var i in result.eqLogic) {
            self.showEqlogicsResult({'humanName':result.eqLogic[i].humanName, 'id':result.eqLogic[i].link}, false)
          }
          for (var i in result.cmd) {
            self.showCmdsResult({'humanName':result.cmd[i].humanName, 'id':result.cmd[i].linkId}, false)
          }
        }
      })
    },
    searchFor_string: function(_searchFor) {
      let self = this
      jeedom.getStringUsedBy({
        search: _searchFor,
        error: function(error) {
          jeedomUtils.showAlert({
            attachTo: jeeDialog.get('#md_search', 'dialog'),
            message: error.message,
            level: 'danger'
          })
        },
        success: function(result) {
          for (var i in result.scenario) {
            self.showScenariosResult({'humanName':result.scenario[i].humanNameTag, 'id':result.scenario[i].linkId}, false)
          }
          for (var i in result.interactDef) {
            self.showInteractsResult({'humanName':result.interactDef[i].humanName, 'id':result.interactDef[i].linkId}, false)
          }
          for (var i in result.eqLogic) {
            self.showEqlogicsResult({'humanName':result.eqLogic[i].humanName, 'id':result.eqLogic[i].link}, false)
          }
          for (var i in result.cmd) {
            self.showCmdsResult({'humanName':result.cmd[i].humanName, 'id':result.cmd[i].linkId}, false)
          }
          for (var i in result.note) {
            self.showNotesResult({'humanName':result.note[i].humanName, 'id':result.note[i].linkId}, false)
          }
        }
      })
    },
    searchFor_id: function(_searchFor) {
      let self = this
      jeedom.getIdUsedBy({
        search: _searchFor,
        error: function(error) {
          jeedomUtils.showAlert({
            attachTo: jeeDialog.get('#md_search', 'dialog'),
            message: error.message,
            level: 'danger'
          })
        },
        success: function(result) {
          for (var i in result.scenario) {
            self.showScenariosResult({'humanName':result.scenario[i].humanNameTag, 'id':result.scenario[i].linkId}, false)
          }
          for (var i in result.plan) {
            self.showPlansResult({'humanName':result.plan[i].name, 'id':result.plan[i].id}, false)
          }
          for (var i in result.view) {
            self.showViewsResult({'humanName':result.view[i].name, 'id':result.view[i].id}, false)
          }
          for (var i in result.interactDef) {
            self.showInteractsResult({'humanName':result.interactDef[i].humanName, 'id':result.interactDef[i].linkId}, false)
          }
          for (var i in result.eqLogic) {
            self.showEqlogicsResult({'humanName':result.eqLogic[i].humanName, 'id':result.eqLogic[i].link}, false)
          }
          for (var i in result.cmd) {
            self.showCmdsResult({'humanName':result.cmd[i].humanName, 'id':result.cmd[i].linkId}, false)
          }
          for (var i in result.note) {
            self.showNotesResult({'humanName':result.note[i].humanName, 'id':result.note[i].linkId}, false)
          }
        }
      })
    },
    /* ------            Search results display            -------*/
    //display result in scenario table:
    showScenariosResult: function(_scenarios, _empty=true) {
      if (!Array.isArray(_scenarios)) _scenarios = [_scenarios]
      for (var i in _scenarios) {
        if (this.tableScSearch.querySelector('.scenario[data-id="' + _scenarios[i].id + '"]') != null) return
        var tr = '<tr class="scenario" data-id="' + _scenarios[i].id + '">'
        tr += '<td>'
        tr += '<span>' + _scenarios[i].humanName + '</span>'
        tr += '</td>'

        tr += '<td>'
        tr += '<span class="label label-info">' + _scenarios[i].id + '</span>'
        tr += '</td>'

        tr += '<td>'
        tr += '<a class="btn btn-xs btn-default tooltips bt_openLog" title="{{Voir les logs}}"><i class="far fa-file"></i></a> '
        tr += '<a class="btn btn-xs btn-success tooltips bt_openScenario" target="_blank" title="{{Aller sur la page du scénario.}}"><i class="fa fa-arrow-circle-right"></i></a>'
        tr += '</td>'

        tr += '</tr>'
        this.tableScSearch.tBodies[0].insertAdjacentHTML('beforeend', tr)
        //this.tableScSearch._dataTable.refresh()
        this.tableScSearch._dataTable.refresh()
        jeedomUtils.initTooltips(this.tableScSearch)
      }
    },
    //display result in design table:
    showPlansResult: function(_plans, _empty=true) {
      if (!Array.isArray(_plans)) _plans = [_plans]
      for (var i in _plans) {
        if (this.tablePlanSearch.querySelector('.plan[data-id="' + _plans[i].id + '"]') != null) return
        var tr = '<tr class="plan" data-id="' + _plans[i].id + '">'
        tr += '<td>'
        tr += '<span>' + _plans[i].humanName + '</span>'
        tr += '</td>'

        tr += '<td>'
        tr += '<span class="label label-info">' + _plans[i].id + '</span>'
        tr += '</td>'

        tr += '<td>'
        tr += '<a class="btn btn-xs btn-success tooltips bt_openDesign" target="_blank" title="{{Aller sur le design.}}"><i class="fa fa-arrow-circle-right"></i></a>'
        tr += '</td>'

        tr += '</tr>'
        this.tablePlanSearch.tBodies[0].insertAdjacentHTML('beforeend', tr)
        this.tablePlanSearch._dataTable.refresh()
        jeedomUtils.initTooltips(this.tablePlanSearch)
      }
    },
    //display result in view table:
    showViewsResult: function(_views, _empty=true) {
      if (!Array.isArray(_views)) _views = [_views]
      for (var i in _views) {
        if (this.tableViewSearch.querySelector('.view[data-id="' + _views[i].id + '"]') != null) return
        var tr = '<tr class="view" data-id="' + _views[i].id + '">'
        tr += '<td>'
        tr += '<span>' + _views[i].humanName + '</span>'
        tr += '</td>'

        tr += '<td>'
        tr += '<span class="label label-info">' + _views[i].id + '</span>'
        tr += '</td>'

        tr += '<td>'
        tr += '<a class="btn btn-xs btn-success tooltips bt_openView" target="_blank" title="{{Aller sur la vue.}}"><i class="fa fa-arrow-circle-right"></i></a>'
        tr += '</td>'

        tr += '</tr>'
        this.tableViewSearch.tBodies[0].insertAdjacentHTML('beforeend', tr)
        this.tableViewSearch._dataTable.refresh()
        jeedomUtils.initTooltips(this.tableViewSearch)
      }
    },
    //display result in interact table:
    showInteractsResult: function(_Interacts, _empty=true) {
      if (!Array.isArray(_Interacts)) _Interacts = [_Interacts]
      for (var i in _Interacts) {
        if (this.tableInteractSearch.querySelector('.view[data-id="' + _Interacts[i].id + '"]') != null) return
        var tr = '<tr class="view" data-id="' + _Interacts[i].id + '">'
        tr += '<td>'
        tr += '<span>' + _Interacts[i].humanName + '</span>'
        tr += '</td>'

        tr += '<td>'
        tr += '<span class="label label-info">' + _Interacts[i].id + '</span>'
        tr += '</td>'

        tr += '<td>'
        tr += '<a class="btn btn-xs btn-success tooltips bt_openInteract" target="_blank" title="{{Aller sur l\'interaction.}}"><i class="fa fa-arrow-circle-right"></i></a>'
        tr += '</td>'

        tr += '</tr>'
        this.tableInteractSearch.tBodies[0].insertAdjacentHTML('beforeend', tr)
        this.tableInteractSearch._dataTable.refresh()
        jeedomUtils.initTooltips(this.tableInteractSearch)
      }
    },
    //display result in cmd table:
    showEqlogicsResult: function(_Eqlogics, _empty=true) {
      if (!Array.isArray(_Eqlogics)) _Eqlogics = [_Eqlogics]
      for (var i in _Eqlogics) {
        if (this.tableEqlogicSearch.querySelector('.view[data-id="' + _Eqlogics[i].id + '"]') != null) return
        var tr = '<tr class="view" data-id="' + _Eqlogics[i].id + '">'
        var id = _Eqlogics[i].id.split('=').pop()
        tr += '<td>'
        tr += '<span>' + _Eqlogics[i].humanName + '</span>'
        tr += '</td>'

        tr += '<td>'
        tr += '<span class="label label-info">' + id + '</span>'
        tr += '</td>'

        tr += '<td>'
        tr += '<a class="btn btn-xs btn-success tooltips bt_openEqlogic" target="_blank" title="{{Aller sur l\'équipement.}}"><i class="fa fa-arrow-circle-right"></i></a>'
        tr += '</td>'

        tr += '</tr>'
        this.tableEqlogicSearch.tBodies[0].insertAdjacentHTML('beforeend', tr)
        this.tableEqlogicSearch._dataTable.refresh()
        jeedomUtils.initTooltips(this.tableEqlogicSearch)
      }
    },
    //display result in cmd table:
    showCmdsResult: function(_Cmds, _empty=true) {
      if (!Array.isArray(_Cmds)) _Cmds = [_Cmds]
      for (var i in _Cmds) {
        if (this.tableCmdSearch.querySelector('.view[data-id="' + _Cmds[i].id + '"]') != null) return
        var tr = '<tr class="view" data-id="' + _Cmds[i].id + '">'
        tr += '<td>'
        tr += '<span>' + _Cmds[i].humanName + '</span>'
        tr += '</td>'

        tr += '<td>'
        tr += '<span class="label label-info">' + _Cmds[i].id + '</span>'
        tr += '</td>'

        tr += '<td>'
        tr += '<a class="btn btn-xs btn-success tooltips bt_openCmd" target="_blank" title="{{Ouvrir configuration de la commande.}}"><i class="fas fa-cog"></i></a>'
        tr += '</td>'

        tr += '</tr>'
        this.tableCmdSearch.tBodies[0].insertAdjacentHTML('beforeend', tr)
        this.tableCmdSearch._dataTable.refresh()
        jeedomUtils.initTooltips(this.tableCmdSearch)
      }
    },
    //display result in note table:
    showNotesResult: function(_Notes, _empty=true) {
      if (!Array.isArray(_Notes)) _Notes = [_Notes]
      for (var i in _Notes) {
        if (this.tableNoteSearch.querySelector('.view[data-id="' + _Notes[i].id + '"]') != null) return
        var tr = '<tr class="view" data-id="' + _Notes[i].id + '">'
        tr += '<td>'
        tr += '<span>' + _Notes[i].humanName + '</span>'
        tr += '</td>'

        tr += '<td>'
        tr += '<span class="label label-info">' + _Notes[i].id + '</span>'
        tr += '</td>'

        tr += '<td>'
        tr += '<a class="btn btn-xs btn-success tooltips bt_openNote" target="_blank" title="{{Ouvrir la note.}}"><i class="fa fa-arrow-circle-right"></i></a>'
        tr += '</td>'

        tr += '</tr>'
        this.tableNoteSearch.tBodies[0].insertAdjacentHTML('beforeend', tr)
        this.tableNoteSearch._dataTable.refresh()
        jeedomUtils.initTooltips(this.tableNoteSearch)
      }
    },
  }
}

(function() {// Self Isolation!
  jeedomUtils.hideAlert()
  var jeeM = jeeFrontEnd.md_search
  jeeM.init()


  //Manage events outside parents delegations:
  document.querySelector('#md_search #in_searchFor_string')?.addEventListener('keypress', function(event) {
    if (event.which === 13) {
      jeeM.searchFor()
    }
  })

  /*Events delegations
  */
  document.getElementById('md_search')?.addEventListener('click', function(event) {
    var _target = null
    if (_target = event.target.closest('#bt_search')) {
      jeeM.searchFor()
      return
    }

    if (_target = event.target.closest('.bt_selectEqLogic')) {
      jeedom.eqLogic.getSelectModal({}, function(result) {
        document.getElementById('in_searchFor_equipment').value = result.human
        document.getElementById('in_searchFor_equipment').setAttribute('data-id', result.id)
        jeeM.searchFor()
      })
      return
    }

    if (_target = event.target.closest('.bt_selectCommand')) {
      jeedom.cmd.getSelectModal({},function (result) {
        document.getElementById('in_searchFor_command').value = result.human
        document.getElementById('in_searchFor_command').setAttribute('data-id', result.cmd.id)
        jeeM.searchFor()
       })
      return
    }

    if (_target = event.target.closest('.bt_selectGeneric')) {
      jeedom.config.getGenericTypeModal({type: 'all', object: false}, function(result) {
        document.getElementById('in_searchFor_string').value = result.id
      })
      return
    }

    if (_target = event.target.closest('.bt_openLog')) {
      jeeDialog.dialog({
        id: 'jee_modal2',
        title: "{{Log d'exécution du scénario}}",
        contentUrl: 'index.php?v=d&modal=scenario.log.execution&scenario_id=' + _target.closest('tr').getAttribute('data-id')
      })
      return
    }

    if (_target = event.target.closest('.bt_openScenario')) {
      var searchType = document.getElementById('sel_searchByType').selectedOptions[0].value
      var url = 'index.php?v=d&p=scenario&id=' + _target.closest('tr').getAttribute('data-id')
      if (searchType != 'plugin') {
        var searchFor = document.getElementById('in_searchFor_' + searchType).value
        url += '&search=' + encodeURI(searchFor.replace('#', ''))
      }
      window.open(url).focus()
      return
    }

    if (_target = event.target.closest('.bt_openDesign')) {
      var url = 'index.php?v=d&p=plan&plan_id=' + _target.closest('tr').getAttribute('data-id')
      window.open(url).focus()
      return
    }

    if (_target = event.target.closest('.bt_openView')) {
      var url = 'index.php?v=d&p=view&view_id=' + _target.closest('tr').getAttribute('data-id')
      window.open(url).focus()
      return
    }

    if (_target = event.target.closest('.bt_openInteract')) {
      var url = 'index.php?v=d&p=interact&id=' + _target.closest('tr').getAttribute('data-id')
      window.open(url).focus()
      return
    }

    if (_target = event.target.closest('.bt_openEqlogic')) {
      var url = _target.closest('tr').getAttribute('data-id')
      window.open(url).focus()
      return
    }

    if (_target = event.target.closest('.bt_openCmd')) {
      jeeDialog.dialog({
        id: 'jee_modal2',
        title: '{{Configuration de la commande}}',
        contentUrl: 'index.php?v=d&modal=cmd.configure&cmd_id=' + _target.closest('tr').getAttribute('data-id')
      })
      return
    }

    if (_target = event.target.closest('.bt_openNote')) {
      var url = 'index.php?v=d&p=modaldisplay&loadmodal=note.manager&title=Notes&id=' + _target.closest('tr').getAttribute('data-id')
      window.open(url).focus()
      return
    }

    if (_target = event.target.closest('#bt_getHelpModal')) {
      jeedom.getDocumentationUrl({
        page: 'search',
        theme: document.body.getAttribute('data-theme'),
        error: function(error) {
        jeedomUtils.showAlert({
          attachTo: jeeDialog.get('#md_search', 'dialog'),
          message: error.message,
          level: 'danger'
        })
        },
        success: function(url) {
        window.open(url,'_blank')
        }
      })
      return
    }
  })

  document.getElementById('md_search')?.addEventListener('change', function(event) {
    var _target = null
    if (_target = event.target.closest('#sel_searchByType')) {
      jeeM.emptyResultTables()
      document.querySelectorAll('#searchByTypes > div.searchType').unseen()
      var option = _target.selectedOptions[0].value
      var div = document.querySelector('#searchByTypes > div[data-searchType="' + option + '"')
      div.seen()
      var dataFilter = Array.from(div.getAttribute('data-tableFilter'), x => parseInt(x))
      for (var i in jeeM.tableStore) {
        var table = jeeM.tableStore[i]
        if (dataFilter[i] == 1) table.parentNode.seen()
        else table.parentNode.unseen()
      }
      return
    }

    if (_target = event.target.closest('#in_searchFor_plugin')) {
      jeeM.searchFor()
      return
    }

    if (_target = event.target.closest('#in_searchFor_variable')) {
      jeeM.searchFor()
      return
    }
  })
})()
</script>