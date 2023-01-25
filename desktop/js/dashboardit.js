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

if (!jeeFrontEnd.dashboardit) {
  jeeFrontEnd.dashboardit = {
    init: function() {
      window.jeeP = this
    },
    getObjectHtml: function(_object_id) {
      jeedom.object.toHtml({
        id: _object_id,
        version: 'dashboard',
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(html) {
          try {
            document.querySelector('div.div_displayEquipement').html(html, false, function() {
              this.addClass('posEqWidthRef')
              this.style.userSelect = 'none'
              jeedomUtils.positionEqLogic()
              new Packery(this)
            })
          } catch (err) {
            console.warn(err)
          }
        }
      })
    }
  }
}

jeeFrontEnd.dashboardit.init()

$("#div_treeObject").jstree({
  "search": {
    show_only_matches: true,
    show_only_matches_children: true,
  },
  "plugins": ["search"]
})

document.getElementById('div_treeObject').addEventListener('click', function(event) {
  var _target = null
  if (_target = event.target.closest('li.jstree-node')) {
    if (event.target.hasClass('jstree-ocl')) return
    let object_id = _target.querySelector('a').getAttribute('data-object_id')
    let name = _target.querySelector('a').getAttribute('data-name')
    let parent = document.querySelector('div.div_displayEquipement').parentNode
    parent.empty()
    parent.insertAdjacentHTML('afterbegin', '<legend>' + name + '</legend><div class="div_displayEquipement"></div>')
    jeeP.getObjectHtml(object_id)
    return
  }
})

document.getElementById('in_searchObject').addEventListener('keyup', function(event) {
  $('#div_treeObject').jstree(true).search(document.getElementById('in_searchObject').value)
})

//Resize responsive tiles:
window.registerEvent('resize', function dashboard(event) {
  if (event.isTrigger) return
  jeedomUtils.positionEqLogic()
})