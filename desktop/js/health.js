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

if (!jeeFrontEnd.health) {
  jeeFrontEnd.health = {
    init: function() {
      window.jeeP = this
    }
  }
}

jeeFrontEnd.health.init()

/*Events delegations
*/
document.getElementById('accordionHealth').addEventListener('click', event => {
  var _target = null
  if (_target = event.target.closest('.bt_configurationPlugin')) {
    jeeDialog.dialog({
      id: 'jee_modal',
      title: "{{Configuration du plugin}}",
      contentUrl: 'index.php?v=d&p=plugin&ajax=1&id=' + _target.getAttribute('data-pluginid')
    })
    return
  }

  if (_target = event.target.closest('.bt_healthSpecific')) {
    jeeDialog.dialog({
      id: 'jee_modal',
      title: "{{Santé}} " + _target.getAttribute('data-pluginname'),
      contentUrl: 'index.php?v=d&plugin=' + _target.getAttribute('data-pluginid') + '&modal=health'
    })
    return
  }

  if (_target = event.target.closest('#bt_benchmarkJeedom')) {
    jeeDialog.dialog({
      id: 'jee_modal',
      title: "{{Jeedom benchmark}}",
      contentUrl: 'index.php?v=d&modal=jeedom.benchmark'
    })
    return
  }

  if (_target = event.target.closest('.panel-title')) {
    _target.querySelector('a.accordion-toggle').click()
    return
  }
})
