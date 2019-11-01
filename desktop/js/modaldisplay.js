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

var params = getUrlVars()
var title = params['title']
var modal = params['loadmodal']

delete params['p']
delete params['v']
delete params['loadmodal']
delete params['title']
delete params['length']

var url = 'index.php?v=d&modal=' + modal
for ([key, value] of Object.entries(params)) {
  url += '&' + key + '=' + value
}

$(function () {
  $('#modalTitle').html(decodeURI(title))
  $('#modalDisplay').empty().load(url, function(data) {
    $('body').attr('data-page',getUrlVars('p'))
    $('#bt_getHelpPage').attr('data-page',getUrlVars('p')).attr('data-plugin',getUrlVars('m'))
    initPage()
    $('body').trigger('jeedom_page_load')
    if (window.location.hash != '' && $('.nav-tabs a[href="'+window.location.hash+'"]').length != 0) {
      $('.nav-tabs a[href="'+window.location.hash+'"]').click()
    }
  })
})
