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

if (!jeeFrontEnd.view_edit) {
  jeeFrontEnd.view_edit = {
    init: function() {
      window.jeeP = this
      if (is_numeric(getUrlVars('view_id'))) {
        this.printView(getUrlVars('view_id'))
      }
    },
    saveView: function(_viewResult) {
      jeedomUtils.hideAlert()
      var view = document.getElementById('div_view').getJeeValues('.viewAttr')[0]
      view.zones = []
      var viewZoneInfo, line, col
      document.querySelectorAll('.viewZone').forEach(function(_viewZone) {
        viewZoneInfo = _viewZone.getJeeValues('.viewZoneAttr')[0]
        if (viewZoneInfo.type == 'table') {
          viewZoneInfo.viewData = [{
            'configuration': {}
          }]
          line = 0
          col = 0
          _viewZone.querySelectorAll('table tbody tr').forEach(function(_tr) {
            viewZoneInfo.viewData[0]['configuration'][line] = {}
            col = 0
            _tr.querySelectorAll('td input').forEach(function(_tdInput) {
              viewZoneInfo.viewData[0]['configuration'][line][col] = _tdInput.jeeValue()
              col++
            })
            line++
          })
          viewZoneInfo.configuration.nbcol = col
          viewZoneInfo.configuration.nbline = line
        } else {
          viewZoneInfo.viewData = _viewZone.querySelectorAll('.viewData').getJeeValues('.viewDataAttr')
        }
        view.zones.push(viewZoneInfo)
      })
      
      jeedom.view.save({
        id: document.querySelector(".li_view.active").getAttribute('data-view_id'),
        view: view,
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function() {
          jeedomUtils.showAlert({
            message: '{{Modification enregistrée}}',
            level: 'success'
          })
          jeeFrontEnd.modifyWithoutSave = false
          if (isset(_viewResult) && _viewResult) {
            window.location.href = 'index.php?v=d&p=view&view_id=' + document.querySelector(".li_view.active").getAttribute('data-view_id')
          } else {
            window.location.reload()
          }
        }
      })
    },
    addEditviewZone: function(_viewZone) {
      if (!isset(_viewZone.configuration)) {
        _viewZone.configuration = {};
      }
      if (init(_viewZone.emplacement) == '') {
        var id = document.querySelectorAll('div_viewZones .viewZone').length
        var div = '<div class="viewZone" id="div_viewZone' + id + '">'
        div += '<legend><span class="viewZoneAttr" data-l1key="name"></span><span class="small viewtype"></span>'
        div += '<div class="input-group pull-right" style="display:inline-flex">'
        div += '<span class="input-group-btn" style="width: 100%;">'
        div += '<select class="viewZoneAttr form-control input-sm" data-l1key="configuration" data-l2key="zoneCol" style="width : 110px;">'
        div += '<option value="12">{{Largeur}} 12</option>'
        div += '<option value="11">{{Largeur}} 11</option>'
        div += '<option value="10">{{Largeur}} 10</option>'
        div += '<option value="9">{{Largeur}} 9</option>'
        div += '<option value="8">{{Largeur}} 8</option>'
        div += '<option value="7">{{Largeur}} 7</option>'
        div += '<option value="6">{{Largeur}} 6</option>'
        div += '<option value="5">{{Largeur}} 5</option>'
        div += '<option value="4">{{Largeur}} 4</option>'
        div += '<option value="3">{{Largeur}} 3</option>'
        div += '<option value="2">{{Largeur}} 2</option>'
        div += '<option value="1">{{Largeur}} 1</option>'
        div += '</select>'
        if (init(_viewZone.type, 'widget') == 'graph') {
          div += '<span class="viewZoneAttr form-control" style="width: auto; background: transparent !important;">{{Hauteur}} (px)</span>'
          div += '<input class="viewZoneAttr form-control input-sm" data-l1key="configuration" data-l2key="height" style="width : 150px;">'

          div += '<select class="viewZoneAttr form-control input-sm" data-l1key="configuration" data-l2key="dateRange" style="width : 200px;">'
          div += '<option value="30 min">{{30 min}}</option>'
          div += '<option value="1 hour">{{1 heure}}</option>'
          div += '<option value="1 day">{{Jour}}</option>'
          div += '<option value="7 days">{{Semaine}}</option>'
          div += '<option value="1 month">{{Mois}}</option>'
          div += '<option value="1 year">{{Année}}</option>'
          div += '<option value="all">{{Tous}}</option>'
          div += '</select>'
        }
        if (init(_viewZone.type, 'widget') == 'graph') {
          div += '<a class="btn btn-primary btn-sm bt_addViewGraph"><i class="fas fa-plus-circle"></i> {{Ajouter courbe}}</a>'
        } else if (init(_viewZone.type, 'widget') == 'table') {
          div += '<a class="btn btn-primary btn-sm bt_addViewTable" data-type="col"><i class="fas fa-plus-circle"></i> {{Ajouter colonne}}</a>'
          div += '<a class="btn btn-primary btn-sm bt_addViewTable" data-type="line"><i class="fas fa-plus-circle"></i> {{Ajouter ligne}}</a>'
        } else {
          div += '<a class="btn btn-primary btn-sm bt_addViewWidget"><i class="fas fa-plus-circle"></i> {{Ajouter Equipement}}</a>'
          div += '<a class="btn btn-primary btn-sm bt_addViewScenario"><i class="fas fa-plus-circle"></i> {{Ajouter Scénario}}</a>'
        }
        div += '<a class="btn btn-warning btn-sm bt_editviewZone"><i class="fas fa-pencil-alt"></i> {{Editer}}</a>'
        div += '<a class="btn btn-danger btn-sm bt_removeviewZone"><i class="far fa-trash-alt"></i> {{Supprimer}}</a>'
        div += '</span>'
        div += '</div>'
        div += '</legend>'
        div += '<input style="display : none;" class="viewZoneAttr" data-l1key="type">'
        if (init(_viewZone.type, 'widget') == 'graph') {
          div += '<table class="table table-condensed div_viewData">'
          div += '<thead>'
          div += '<tr><th></th><th>{{Nom}}</th><th>{{Couleur}}</th><th>{{Type}}</th><th>{{Groupement}}</th><th>{{Echelle}}</th><th>{{Escalier}}</th><th>{{Empiler}}</th><th>{{Variation}}</th></tr>'
          div += '</thead>'
          div += '<tbody>'
          div += '</tbody>'
          div += '</table>'
        } else if (init(_viewZone.type, 'widget') == 'table') {
          if (init(_viewZone.configuration.nbcol) == '') {
            _viewZone.configuration.nbcol = 2
          }
          if (init(_viewZone.configuration.nbline) == '') {
            _viewZone.configuration.nbline = 2
          }
          div += '<table class="table table-condensed div_viewData">'
          div += '<thead>'
          div += '<tr>'
          div += '<td></td>'
          for (var i = 0; i < _viewZone.configuration.nbcol; i++) {
            div += '<td><a class="btn btn-danger bt_removeAddViewTable" data-type="col"><i class="far fa-trash-alt"></a></td>'
          }
          div += '</thead>'
          div += '<tbody>'
          for (var j = 0; j < _viewZone.configuration.nbline; j++) {
            div += '<tr class="viewData">';
            div += '<td><a class="btn btn-danger bt_removeAddViewTable" data-type="line"><i class="far fa-trash-alt"></a></td>'
            for (var i = 0; i < _viewZone.configuration.nbcol; i++) {
              div += '<td>'
              div += '<div class="input-group">'
              div += '<input class="form-control viewDataAttr roundedLeft" data-l1key="configuration" data-l2key="' + j + '" data-l3key="' + i + '" />'
              div += '<span class="input-group-btn">'
              div += '<a class="btn btn-default bt_listEquipementInfo roundedRight"><i class="fas fa-list-alt"></i></a>'
              div += '</span>'
              div += '</div>'
              div += '</td>'
            }
            div += '</tr>'
          }
          div += '</tbody>'
          div += '</table>'
        } else {
          div += '<table class="table table-condensed div_viewData">'
          div += '<thead>'
          div += '<tr><th></th><th>{{Nom}}</th></tr>'
          div += '</thead>'
          div += '<tbody>'
          div += '</tbody>'
          div += '</table>'
        }
        div += '</div>'

        document.getElementById('div_viewZones').insertAdjacentHTML('beforeend', div)
        document.querySelectorAll('#div_viewZones .viewZone').last().setJeeValues(_viewZone, '.viewZoneAttr')
        $("#div_viewZones .viewZone:last .div_viewData tbody").sortable({
          axis: "y",
          cursor: "move",
          items: ".viewData",
          placeholder: "ui-state-highlight",
          tolerance: "intersect",
          forcePlaceholderSize: true
        })
      } else {
        document.getElementById(_viewZone.emplacement).querySelector('.viewZoneAttr[data-l1key="name"]').innerHTML = _viewZone.name
      }
    },
    addGraphService: function(_viewData) {
      if (!isset(_viewData.configuration) || _viewData.configuration == '') {
        _viewData.configuration = {}
      }
      var tr = '<tr>'
      tr += '<td><i class="far fa-trash-alt cursor bt_removeViewData"></i></td>'

      tr += '<td>'
      tr += '<input class="viewDataAttr" data-l1key="link_id" style="display: none;"/>'
      tr += '<input class="viewDataAttr" data-l1key="type" style="display: none;"/>'
      tr += '<span class="viewDataAttr" data-l1key="name"></span>'
      tr += '</td>'

      tr += '<td>'
      tr += '<input type="color" class="viewDataAttr form-control input-sm" data-l1key="configuration" data-l2key="graphColor" />'
      tr += '</td>'

      tr += '<td>'
      tr += '<select class="viewDataAttr form-control input-sm" data-l1key="configuration" data-l2key="graphType">'
      tr += '<option value="line">{{Ligne}}</option>'
      tr += '<option value="area">{{Aire}}</option>'
      tr += '<option value="column">{{Colonne}}</option>'
      tr += '<option value="pie">{{Camembert}}</option>'
      tr += '</select>'
      tr += '</td>'

      tr += '<td>'
      tr += '<select class="viewDataAttr form-control input-sm" data-l1key="configuration" data-l2key="groupingType">'
      tr += '<option value="">{{Aucun groupement}}</option>'
      tr += '<option value="sum::hour">{{Somme par heure}}</option>'
      tr += '<option value="average::hour">{{Moyenne par heure}}</option>'
      tr += '<option value="low::hour">{{Minimum par heure}}</option>'
      tr += '<option value="high::hour">{{Maximum par heure}}</option>'
      tr += '<option value="sum::day">{{Somme par jour}}</option>'
      tr += '<option value="average::day">{{Moyenne par jour}}</option>'
      tr += '<option value="low::day">{{Minimum par jour}}</option>'
      tr += '<option value="high::day">{{Maximum par jour}}</option>'
      tr += '<option value="sum::week">{{Somme par semaine}}</option>'
      tr += '<option value="average::week">{{Moyenne par semaine}}</option>'
      tr += '<option value="low::week">{{Minimum par semaine}}</option>'
      tr += '<option value="high::week">{{Maximum par semaine}}</option>'
      tr += '<option value="sum::month">{{Somme par mois}}</option>'
      tr += '<option value="average::month">{{Moyenne par mois}}</option>'
      tr += '<option value="low::month">{{Minimum par mois}}</option>'
      tr += '<option value="high::month">{{Maximum par mois}}</option>'
      tr += '<option value="sum::year">{{Somme par année}}</option>'
      tr += '<option value="average::year">{{Moyenne par année}}</option>'
      tr += '<option value="low::year">{{Minimum par année}}</option>'
      tr += '<option value="high::year">{{Maximum par année}}</option>'
      tr += '</select>'
      tr += '</td>'

      tr += '<td>'
      tr += '<select class="viewDataAttr form-control input-sm" data-l1key="configuration" data-l2key="graphScale" style="width : 90px;">'
      tr += '<option value="0">{{Droite}}</option>'
      tr += '<option value="1">{{Gauche}}</option>'
      tr += '</select>'
      tr +=  '<select class="viewDataAttr form-control input-sm" data-l1key="configuration" data-l2key="graphScaleVisible" style="width : 90px;">'
      tr +=  '<option value="0">{{Invisible}}</option>'
      tr +=  '<option value="1">{{Visible}}</option>'
      tr +=  '</select>'
      tr += '</td>'

      tr += '<td>'
      tr += '<select class="viewDataAttr form-control input-sm" data-l1key="configuration" data-l2key="graphStep" style="width : 90px;">'
      tr += '<option value="0">{{Non}}</option>'
      tr += '<option value="1">{{Oui}}</option>'
      tr += '</select>'
      tr += '</td>'

      tr += '<td>'
      tr += '<select class="viewDataAttr form-control input-sm" data-l1key="configuration" data-l2key="graphStack" style="width : 90px;">'
      tr += '<option value="0">{{Non}}</option>'
      tr += '<option value="1">{{Oui}}</option>'
      tr += '</select>'
      tr += '</td>'

      tr += '<td>'
      tr += '<select class="viewDataAttr form-control input-sm" data-l1key="configuration" data-l2key="derive" style="width : 90px;">'
      tr += '<option value="0">{{Non}}</option>'
      tr += '<option value="1">{{Oui}}</option>'
      tr += '</select>'
      tr += '</td>'

      tr += '</tr>'

      let newRow = document.createElement("tr")
      newRow.innerHTML = tr
      newRow.addClass('viewData')
      newRow.style = "cursor: move;"
      newRow.setJeeValues(_viewData, '.viewDataAttr')
      return newRow
    },
    addWidgetService: function(_viewData) {
      if (!isset(_viewData.configuration) || _viewData.configuration == '') {
        _viewData.configuration = {}
      }
      var tr = '<tr>'
      tr += '<td><i class="far fa-trash-alt cursor bt_removeViewData"></i></td>'
      tr += '<td>'
      tr += '<input class="viewDataAttr" data-l1key="link_id" style="display  : none;"/>'
      tr += '<input class="viewDataAttr" data-l1key="type" style="display  : none;"/>'
      tr += '<span class="viewDataAttr" data-l1key="name"></span><span class="small viewtype"></span>'
      tr += '</td>'
      tr += '</tr>'

      let newRow = document.createElement("tr")
      newRow.innerHTML = tr
      newRow.addClass('viewData')
      newRow.style = "cursor : move;"
      newRow.setJeeValues(_viewData, '.viewDataAttr')
      return newRow
    },
    printView: function(_viewId) {
      jeedomUtils.hideAlert()
      document.querySelectorAll('.li_view').removeClass('active')
      document.querySelector('.li_view[data-view_id="' + _viewId + '"]').addClass('active')
      document.getElementById('div_view').seen()
      jeedom.view.get({
        id: _viewId,
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          jeedomUtils.addOrUpdateUrl('view_id', data.id)
          document.querySelectorAll('#div_viewZones').empty()
          document.getElementById('div_view').setJeeValues(data, '.viewAttr')
          var viewZone
          for (var i in data.viewZone) {
            viewZone = data.viewZone[i]
            jeeP.addEditviewZone(viewZone)
            for (var j in viewZone.viewData) {
              var viewData = viewZone.viewData[j]
              if (init(viewZone.type, 'widget') == 'graph') {
                document.querySelectorAll('#div_viewZones .viewZone').last().querySelector('.div_viewData tbody').appendChild(jeeP.addGraphService(viewData))
              } else if (init(viewZone.type, 'widget') == 'table') {
                document.querySelectorAll('#div_viewZones').forEach(function (element) {
                  element.querySelectorAll('.viewZone').last().setJeeValues(viewData, '.viewDataAttr')
                })
              } else {
                document.querySelectorAll('#div_viewZones .viewZone').last().querySelector('.div_viewData tbody').appendChild(jeeP.addWidgetService(viewData))
              }
            }
            let typeEl = document.querySelectorAll('#div_viewZones .viewZone').last().querySelector('span.viewtype')
            typeEl.innerHTML = '<i> | ' + viewZone.type + '</i>'
          }
          jeeFrontEnd.modifyWithoutSave = false
        }
      })
    },
  }
}

jeeFrontEnd.view_edit.init()


//sortable
$(document.getElementById('ul_view')).sortable({
  axis: "y",
  cursor: "move",
  items: ".li_view",
  placeholder: "ui-state-highlight",
  tolerance: "intersect",
  forcePlaceholderSize: true,
  dropOnEmpty: true,
  stop: function(event, ui) {
    var views = []
    document.querySelectorAll('#ul_view .li_view').forEach(_li => {
      views.push(_li.getAttribute('data-view_id'))
    })
    jeedom.view.setOrder({
      views: views,
      error: function(error) {
        jeedomUtils.showAlert({
          message: error.message,
          level: 'danger'
        })
      }
    })
  }
}).sortable("enable")

$(document.getElementById('div_viewZones')).sortable({
  axis: "y",
  distance: 5,
  cursor: "move",
  placeholder: "ui-state-highlight",
  tolerance: "intersect",
  forcePlaceholderSize: true
})


//Register events on top of page container:
document.registerEvent('keydown', function(event) {
  if (jeedomUtils.getOpenedModal()) return
  if ((event.ctrlKey || event.metaKey) && event.which == 83) { //s
    event.preventDefault()
    jeeP.saveView()
  }
})


/*Events delegations
*/
document.getElementById('div_pageContainer').addEventListener('click', function(event) {
  var _target = null
  //General ->
  if (_target = event.target.closest('#bt_viewResult')) {
    jeeP.saveView(true)
    return
  }

  if (_target = event.target.closest('#bt_addView')) {
    jeeDialog.prompt("{{Nom de la vue ?}}", function(result) {
      if (result !== null) {
        jeedom.view.save({
          id: '',
          view: {
            name: result
          },
          error: function(error) {
            jeedomUtils.showAlert({
              message: error.message,
              level: 'danger'
            })
          },
          success: function(data) {
            jeedomUtils.loadPage('index.php?v=d&p=view_edit&view_id=' + data.id)
          }
        })
      }
    })
    return
  }

  if (_target = event.target.closest('#bt_editView')) {
    let id = document.querySelector('.li_view.active').getAttribute('data-view_id')
    jeeDialog.dialog({
      id: 'jee_modal',
      title: "{{Configuration de la vue}}",
      contentUrl: 'index.php?v=d&modal=view.configure&view_id=' + id
    })
    return
  }

  if (_target = event.target.closest('#bt_saveView')) {
    jeeP.saveView()
    return
  }

  if (_target = event.target.closest('#bt_removeView')) {
    let text = document.querySelector('.li_view.active a').textContent
    let id = document.querySelector('.li_view.active').getAttribute('data-view_id')
    jeedomUtils.hideAlert()
    jeeDialog.confirm('{{Êtes-vous sûr de vouloir supprimer la vue}} <span style="font-weight: bold;">' + text + '</span> ?', function(result) {
      if (result) {
        jeedom.view.remove({
          id: id,
          error: function(error) {
            jeedomUtils.showAlert({
              message: error.message,
              level: 'danger'
            })
          },
          success: function() {
            jeeFrontEnd.modifyWithoutSave = false
            jeedomUtils.loadPage('index.php?v=d&p=view_edit')
          }
        })
      }
    })
    return
  }

  if (_target = event.target.closest('.li_view')) {
    if (jeedomUtils.checkPageModified()) return
    jeeFrontEnd.view_edit.printView(_target.getAttribute('data-view_id'))
    return
  }

  //ViewZone ->
  if (_target = event.target.closest('#bt_addviewZone')) {
    let content = '<input class="promptAttr" data-l1key="name" autocomplete="off" type="text" placeholder="{{Nom}}">'
    content += '<select class="promptAttr" data-l1key="type" id="sel_addEditviewZoneType">'
    content += '<option value="widget">{{Equipement}}</option>'
    content += '<option value="graph">{{Graphique}}</option>'
    content += '<option value="table">{{Tableau}}</option>'
    content += '</select>'

    jeeDialog.prompt({
      title: "{{Ajouter/Editer viewZone}}",
      message: content,
      inputType: false,
      callback: function(result) {
        if (result) {
          if (result.name == '') {
            jeedomUtils.showAlert({
              message: '{{Le nom de la viewZone ne peut être vide}}',
              level: 'warning'
            })
            return
          }
          var viewZone = {
            name: result.name,
            emplacement: '',
            type: result.type
          }
          jeeP.addEditviewZone(viewZone)
          jeeFrontEnd.modifyWithoutSave = true
        }
      }
    })
    return
  }

  if (_target = event.target.closest('.bt_removeviewZone')) {
    let name = _target.closest('div.viewZone').querySelector('.viewZoneAttr[data-l1key="name"]').innerHTML
    jeeDialog.confirm('{{Supprimer cette zone}} : <b>' + name + '</b> ?', function(result) {
      if (result !== null) {
        event.target.closest('.viewZone').remove()
        jeeFrontEnd.modifyWithoutSave = true
      }
    })
    return
  }

  if (_target = event.target.closest('.bt_editviewZone')) {
    let id = _target.closest('div.viewZone').getAttribute('id')
    let name = _target.closest('div.viewZone').querySelector('.viewZoneAttr[data-l1key="name"]').textContent
    let type = _target.closest('div.viewZone').querySelector('.viewZoneAttr[data-l1key="type"]').value
    let content = '<input class="promptAttr" data-l1key="name" autocomplete="off" type="text" placeholder="{{Nom}}" value="' + name.replaceAll('"',"'") + '">'
    content += '<input class="promptAttr" data-l1key="emplacement" type="text" value="' + id + '" style="display:none;">'
    content += '<select class="promptAttr" data-l1key="type" id="sel_addEditviewZoneType">'
    content += (type == 'widget') ? '<option value="widget" selected>{{Equipement}}</option>' : '<option value="widget">{{Equipement}}</option>'
    content += (type == 'graph') ? '<option value="graph" selected>{{Graphique}}</option>' : '<option value="graph">{{Graphique}}</option>'
    content += (type == 'table') ? '<option value="table" selected>{{Tableau}}</option>' : '<option value="table">{{Tableau}}</option>'
    content += '</select>'
    jeeDialog.prompt({
      title: "{{Ajouter/Editer viewZone}}",
      message: content,
      inputType: false,
      callback: function(result) {
        if (result) {
          if (result.name == '') {
            jeedomUtils.showAlert({
              message: '{{Le nom de la viewZone ne peut être vide}}',
              level: 'warning'
            })
            return
          }
          var viewZone = {
            name: result.name,
            emplacement: result.emplacement,
            type: result.type
          }
          jeeP.addEditviewZone(viewZone)
          jeeFrontEnd.modifyWithoutSave = true
        }
      }
    })
    return
  }

  //Data ->
  if (_target = event.target.closest('#div_viewZones .bt_removeViewData')) {
    _target.closest('tr').remove()
    jeeFrontEnd.modifyWithoutSave = true
    return
  }

  if (_target = event.target.closest('#div_viewZones .bt_addViewTable')) {
    var table = _target.closest('.viewZone').querySelector('table.div_viewData')
    if (_target.getAttribute('data-type') == 'line') {
      var line = '<tr class="viewData">'
      line += '<td><a class="btn btn-danger bt_removeAddViewTable" data-type="line"><i class="far fa-trash-alt"></a></td>'
      for (var i = 0; i < table.tBodies[0].children[0].children.length - 1; i++) {
        line += '<td>'
        line += '<div class="input-group">'
        line += '<input class="form-control viewDataAttr roundedLeft" data-l1key="configuration" />'
        line += '<span class="input-group-btn">'
        line += '<a class="btn btn-default bt_listEquipementInfo roundedRight"><i class="fas fa-list-alt"></i></a>'
        line += '</span>'
        line += '</div>'
        line += '</td>'
      }
      line += '</tr>'
      table.tBodies[0].insertAdjacentHTML('beforeend', line)

    } else if (_target.getAttribute('data-type') == 'col') {
      table.tHead.childNodes[0].insertAdjacentHTML('beforeend', '<td><a class="btn btn-danger bt_removeAddViewTable" data-type="col"><i class="far fa-trash-alt"></a></td>')
      table.tBodies[0].childNodes.forEach(_tr => {
        var col = '<td>'
        col += '<div class="input-group">'
        col += '<input class="form-control viewDataAttr roundedLeft" data-l1key="configuration" />'
        col += '<span class="input-group-btn">'
        col += '<a class="btn btn-default bt_listEquipementInfo roundedRight"><i class="fas fa-list-alt"></i></a>'
        col += '</span>'
        col += '</div>'
        col += '</td>'
        _tr.insertAdjacentHTML('beforeend', col)
      })
    }
    jeeFrontEnd.modifyWithoutSave = true
    return
  }

  if (_target = event.target.closest('#div_viewZones .bt_removeAddViewTable')) {
    if (_target.getAttribute('data-type') == 'line') {
      _target.closest('tr').remove()
    } else if (_target.getAttribute('data-type') == 'col') {
      let table = _target.closest('table')
      let tdIdx = _target.closest('td').cellIndex
      table.tHead.childNodes[0].deleteCell(tdIdx)
      table.tBodies[0].childNodes.forEach(_tr => {
        _tr.deleteCell(tdIdx)
      })
    }
    jeeFrontEnd.modifyWithoutSave = true
    return
  }

  if (_target = event.target.closest('#div_viewZones .bt_listEquipementInfo')) {
    jeedom.cmd.getSelectModal({}, function(result) {
      _target.closest('td').querySelector('input.viewDataAttr[data-l1key="configuration"]').insertAtCursor(result.human)
    })
    return
  }

  if (_target = event.target.closest('#div_viewZones .bt_addViewGraph')) {
    jeedom.cmd.getSelectModal({
      cmd: {
        isHistorized: 1
      }
    }, function(result) {
      _target.closest('.viewZone').querySelector('.div_viewData tbody').appendChild(jeeP.addGraphService({
        name: result.human.replace(/\#/g, ''),
        link_id: result.cmd.id,
        type: 'cmd'
      }))
    })
    jeeFrontEnd.modifyWithoutSave = true
    return
  }

  if (_target = event.target.closest('#div_viewZones .bt_addViewWidget')) {
    jeedom.eqLogic.getSelectModal({}, function(result) {
      _target.closest('.viewZone').querySelector('.div_viewData tbody').appendChild(jeeP.addWidgetService({
        name: result.human.replace('#', '').replace('#', ''),
        link_id: result.id,
        type: 'eqLogic'
      }))
      jeeFrontEnd.modifyWithoutSave = true
    })
    return
  }

  if (_target = event.target.closest('#div_viewZones .bt_addViewScenario')) {
    jeedom.scenario.getSelectModal({}, function(result) {
      _target.closest('.viewZone').querySelector('.div_viewData tbody').appendChild(jeeP.addWidgetService({
        name: result.human.replace('#', '').replace('#', ''),
        link_id: result.id,
        type: 'scenario'
      }))
      jeeFrontEnd.modifyWithoutSave = true
    })
    return
  }
})

document.getElementById('div_pageContainer').addEventListener('change', function(event) {
  var _target = null
  if (_target = event.target.closest('.viewZoneAttr')) {
    if (_target.isVisible()) jeeFrontEnd.modifyWithoutSave = true
  }

  if (_target = event.target.closest('#table_addViewData .enable')) {
    if (_target.jeeValue() == 1) {
      _target.closest('tr').querySelectorAll('div.option').seen()
    } else {
      _target.closest('tr').querySelectorAll('div.option').unseen()
    }
    return
  }

  if (_target = event.target.closest('.viewDataAttr[data-l1key="configuration"][data-l2key="graphColor"]')) {
    _target.style.backgroundColor = event.target.jeeValue()
    jeeFrontEnd.modifyWithoutSave = true
    return
  }
})

