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

if (!jeeFrontEnd.modaldisplay) {
  jeeFrontEnd.modaldisplay = {
    params: null,
    title: null,
    modal: null,
    url: false,
    init: function() {
      window.jeeP = this
      this.params = getUrlVars()
      this.title = this.params['title']
      this.modal = this.params['loadmodal']
      this.url = 'index.php?v=d&modal=' + this.modal

      delete this.params['p']
      delete this.params['v']
      delete this.params['loadmodal']
      delete this.params['title']

      for (var [key, value] of Object.entries(this.params)) {
        this.url += '&' + key + '=' + value
      }
    },
  }
}

jeeFrontEnd.modaldisplay.init()

document.title = decodeURI(jeeP.title)
document.getElementById('modalTitle').innerHTML = '<i class="far fa-window-maximize"></i> ' + decodeURI(jeeP.title)
document.getElementById('modalDisplay').load(jeeP.url, function(data) {
  document.body.setAttribute('data-page', getUrlVars('p'))
  document.getElementById('bt_getHelpPage')?.setAttribute('data-page', getUrlVars('p'))
  document.getElementById('bt_getHelpPage')?.setAttribute('data-plugin', getUrlVars('m'))
  jeedomUtils.initPage()
  document.body.triggerEvent('jeedom_page_load')
  var tab = document.querySelector('.nav-tabs a[href="' + window.location.hash + '"]')
  if (tab == null) {
    tab = document.querySelector('.nav-tabs a[data-target="' + window.location.hash + '"]')
  }
  if (window.location.hash != '' && tab != null) {
    tab.click()
  }
})
