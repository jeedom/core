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

      TreeConfig.leaf_icon = '<i class="far fa-folder cursor"></i>'
      TreeConfig.parent_icon = '<i class="far fa-folder-tree cursor"></i>'
      TreeConfig.open_icon = '<i class="far fa-folder-open cursor"></i>'
      TreeConfig.close_icon = '<i class="far fa-folder cursor"></i>'
      this.setObjectTree()
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
              new Packery(this, {isLayoutInstant: true, transitionDuration: 0})
            })
          } catch (err) {
            console.warn(err)
          }
        }
      })
    },
    //Tree functions:
    setObjectTree: function() {
      this.object_root = new TreeNode('object_root', { expanded: true })
      this.object_tree = new TreeView(this.object_root, document.getElementById('div_treeObject'), { show_root: false })
      this.object_tree.setContainer(document.getElementById('div_treeObject'))

      var newNode
      buildTree()
      function buildTree(_childs, _parentNode) {
        if (!isset(_childs)) _childs = jeephp2js.object_Struct
        if (!isset(_parentNode)) _parentNode = jeeFrontEnd.dashboardit.object_root
        _childs.forEach(_obj => {
          newNode = new TreeNode('<span class="leafRef cursor" data-object_id="' + _obj.id + '">' + _obj.name + '</span>', {
            options: {
              object_id: _obj.id,
              name: _obj.name,
            }
          })
          newNode.setOptions('object_id', _obj.id)
          newNode.on('click', (event, node) => {
            if (!event.target.matches('i')) node.toggleExpanded() //Default behavior allways toggle
            var objectId = node.getOptions().options.object_id
            var name = node.getOptions().options.name
            jeeP.objectTreeClick(objectId, name)
          })
          _parentNode.addChild(newNode)

          if (_obj.childs) buildTree(_obj.childs, newNode)
        })
      }
      this.object_tree.reload()
      this.object_tree.expandAllNodes()
      document.querySelector('#div_treeObject .tj_description').click()
    },
    objectTreeClick: function(_objectId, _name) {
      let parent = document.querySelector('div.div_displayEquipement').parentNode
      parent.empty()
      parent.insertAdjacentHTML('afterbegin', '<legend>' + _name + '</legend><div class="div_displayEquipement"></div>')
      jeeP.getObjectHtml(_objectId)
    }
  }
}

jeedom.object.getImgPath({
  id: jeephp2js.object_Struct[0].id,
  success: function(_path) {
    jeedomUtils.setBackgroundImage(_path)
  }
})

jeeFrontEnd.dashboardit.init()

//Resize responsive tiles:
window.registerEvent('resize', function dashboard(event) {
  if (event.isTrigger) return
  jeedomUtils.positionEqLogic()
})