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

$('#div_treeObject').off('click').on('select_node.jstree', function(node, selected) {
  if (selected.node.a_attr['data-object_id'] != undefined) {
    var object_id = selected.node.a_attr['data-object_id']
    $('.div_displayEquipement').parent().empty().append('<legend>' + selected.node.a_attr['data-name'] + '</legend><div class="div_displayEquipement"></div>')
    getObjectHtml(object_id)
  }
})

function getObjectHtml(_object_id) {
  jeedom.object.toHtml({
    id: _object_id,
    version: 'dashboard',
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(html) {
      try {
        $('.div_displayEquipement').html(html)
      } catch (err) {
        console.log(err)
      }
      setTimeout(function() {
        jeedomUtils.positionEqLogic()
        $('.div_displayEquipement').packery().disableSelection()
        $("input").click(function() {
          $(this).focus()
        })
        $("textarea").click(function() {
          $(this).focus()
        })
        $("select").click(function() {
          $(this).focus()
        })
      }, 10)
    }
  })
}

$("#div_treeObject").jstree({
  "search": {
    show_only_matches: true,
    show_only_matches_children: true,
  },
  "plugins": ["search"]
})

$('#in_searchObject').keyup(function() {
  $('#div_treeObject').jstree(true).search($('#in_searchObject').val())
})