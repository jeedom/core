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
  throw new Exception('401 - {{Accès non autorisé}}');
}

$objectId = init('object_id');

sendVarToJS([
  'jeephp2js.md_iconSelector_objectId' => $objectId,
  'jeephp2js.md_iconSelector_selectIcon' => init('selectIcon', 0),
  'jeephp2js.md_iconSelector_colorIcon' => init('colorIcon', 0)
]);
?>

<div id="md_iconSelector" data-modalType="md_iconSelector">
  <ul class="nav nav-tabs" role="tablist">
    <?php if (!$objectId) { ?>
      <li role="presentation" class="active"><a href="#tabicon" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-icons"></i> {{Icônes}}</a></li>
      <?php if (init('showimg') == 1) { ?>
        <li role="presentation"><a href="#tabimg" aria-controls="home" role="tab" data-toggle="tab"><i class="far fa-images"></i> {{Images}}</a></li>
      <?php }
    } else { ?>
      <li role="presentation" class="active"><a href="#tabobjectbg" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-image"></i> {{Images de fond}}</a></li>
    <?php } ?>
  </ul>

  <div class="tab-content" style="overflow-y:scroll;">
    <?php if (!$objectId) { ?>
      <div role="tabpanel" class="tab-pane active" id="tabicon">
        <div class="imgContainer">
          <div id="treeFolder-icon" class="div_treeFolder">
            <ul id="ul_Folder-bg">
              <?php
              $scanPaths = (init('path', '') != '') ? [__DIR__ . '/../../' . init('path')] : array(__DIR__ . '/../../core/css/icon', __DIR__ . '/../../data/fonts');
              foreach ($scanPaths as $root) {
                $ls = ls($root, '*', false, array('folders'));
                foreach ($ls as $dir) {
                  if (!file_exists($root . '/' . $dir . 'style.css') || !file_exists($root . '/' . $dir . 'fonts/' . substr($dir, 0, -1) . '.ttf')) {
                    continue;
                  }
                  echo '<li><a data-path="' . $root . '/' . $dir . '">' . ucfirst(str_replace(array('/', '_'), array('', ' '), $dir)) . '</a></li>';
                }
              }
              if (init('path', '') == '') {
                echo '<li><a data-path="' . __DIR__ . '/../../3rdparty/font-awesome5/">Font-Awesome</a></li>';
              }
              ?>
            </ul>
          </div>
          <div class="div_imageGallery"></div>
        </div>
      </div>

      <div role="tabpanel" class="tab-pane" id="tabimg">
        <input class="hidden" id="bt_uploadImg" type="file" name="file" multiple="multiple" data-path="">
        <div class="imgContainer">
          <div id="treeFolder-img" class="div_treeFolder">
            <ul id="ul_Folder-img">
              <?php
              $rootPath = __DIR__ . '/../../data/';
              foreach (ls($rootPath, 'img', false, array('folders')) as $folder) {
                echo '<li data-jstree=\'{"opened":true}\'><a data-path="' . $rootPath . $folder . '">' . ucfirst(str_replace(array('/', '_'), array('', ' '), $folder)) . '</a></li>';
              }
              ?>
            </ul>
          </div>
          <div class="div_imageGallery"></div>
        </div>
      </div>
    <?php } else { ?>
      <div role="tabpanel" class="tab-pane active" id="tabobjectbg">
        <div class="imgContainer">
          <div id="treeFolder-bg" class="div_treeFolder">
            <ul id="ul_Folder-bg">
              <?php
              $rootPath = __DIR__ . '/../../core/img/object_background/';
              foreach (ls($rootPath, '*') as $category) {
                echo '<li data-jstree=\'{"opened":true}\'><a data-path="' . $rootPath . $category . '">' . ucfirst(str_replace(array('/', '_'), array('', ' '), $category)) . '</a></li>';
              }
              ?>
            </ul>
          </div>
          <div class="div_imageGallery"></div>
        </div>
      </div>
    <?php } ?>
  </div>

  <div id="mySearch" class="input-group">
    <div class="input-group-btn">
      <select class="form-control roundedLeft" style="width:200px;display:none;" id="sel_colorIcon">
        <option disabled>---{{Couleur des icônes}}---</option>
        <option value="">{{Aucune couleur}}</option>
        <option value="icon_blue" class="icon_blue">{{Icônes bleues}}</option>
        <option value="icon_yellow" class="icon_yellow">{{Icônes jaunes}}</option>
        <option value="icon_orange" class="icon_orange">{{Icônes oranges}}</option>
        <option value="icon_red" class="icon_red">{{Icônes rouges}}</option>
        <option value="icon_green" class="icon_green">{{Icônes vertes}}</option>
      </select>
    </div>
    <input class="form-control" placeholder="{{Rechercher}}" id="in_searchIconSelector">
    <div class="input-group-btn">
      <a id="bt_resetIconSelectorSearch" class="btn roundedRight" style="width:30px"><i class="fas fa-times"></i> </a>
    </div>
  </div>
</div>
<?php
  include_file('3rdparty', 'jquery.tree/themes/default/style.min', 'css');
  include_file('3rdparty', 'jquery.tree/jstree.min', 'js');
?>

<script>

if (!jeeFrontEnd.md_iconSelector) {
  jeeFrontEnd.md_iconSelector = {
    iconClasses: null,
    init: function() {
      this.setModal()
      this.setJsTree()
    },
    postInit: function() {
      if (getDeviceType()['type'] == 'desktop') document.getElementById("in_searchIconSelector").focus()

      var icon = '<sup class="pull-right"><i class="fas fa-question-circle" title="{{Clic droit sur un dossier pour ouvrir le menu contextuel}}"></i></sup>'
      document.getElementById('treeFolder-img')?.insertAdjacentHTML('afterbegin', icon)

      if (jeephp2js.md_iconSelector_selectIcon != "0") {
        var iconClasses = jeephp2js.md_iconSelector_selectIcon.split('.')
        var lookPath = iconClasses[0]
        if (iconClasses[0].substr(0, 2) === 'fa') {
          lookPath = 'font-awesome5'
        } else if (iconClasses[0] === 'icon') {
          lookPath = iconClasses[1].split('-')[0]
        }
        jeephp2js.md_iconSelector_selectIcon.iconClasses = iconClasses
        lookPath = '/' + lookPath + '/'
        document.querySelectorAll('a.jstree-anchor').forEach(_anchor => {
          if (_anchor.dataset.path.includes(lookPath)) {
            jeeFrontEnd.md_iconSelector.printFileFolder(_anchor.dataset.path, 'treeFolder-icon', function() {
              if (jeephp2js.md_iconSelector_colorIcon != '0') {
                let select = document.getElementById('sel_colorIcon')
                select.value = jeephp2js.md_iconSelector_colorIcon
                select.triggerEvent('change')
              }
              document.querySelector('.jstree-anchor[data-path*="' + lookPath + '"]')?.addClass('jstree-clicked')
              //Select current icon:
              let icon = document.querySelector('#tabicon div.div_imageGallery').querySelector('span.iconSel > i.' + iconClasses[1])
              if (icon) {
                icon.closest('div.divIconSel').addClass('iconSelected')
                icon.scrollIntoView()
              }
            })
          }
        })
      } else {
        document.querySelector('a.jstree-anchor').click()
      }
    },
    setModal: function() {
      var modal = jeeDialog.get('#sel_colorIcon', 'dialog')
      var modalFooter = jeeDialog.get('#sel_colorIcon', 'footer')
      var uiOptions = modal.querySelector('#mySearch')
      modalFooter.insertBefore(uiOptions, modalFooter.firstChild)
      document.getElementById('sel_colorIcon').selectedIndex = 1
    },
    setJsTree: function() {
      $(".div_treeFolder").jstree({
        core: {
          check_callback: true,
          force_text: true,
          themes: {
            icons: false
          }
        },
        contextmenu: {
          items: function(node) {
            var tree = $.jstree.reference(node)
            if (tree.element[0].id !== 'treeFolder-img') {
              return false
            }
            var items = {
              upload: {
                separator_after: true,
                label: "{{Ajouter}}",
                icon: "fas fa-file-image",
                action: function() {
                  $('#bt_uploadImg').click()
                }
              },
              create: {
                label: "{{Nouveau}}",
                icon: "fas fa-folder-plus",
                action: function() {
                  jeedom.createFolder({
                    name: '{{Nouveau}}',
                    path: node.a_attr['data-path'],
                    error: function(error) {
                      jeedomUtils.showAlert({
                        attachTo: jeeDialog.get('#md_iconSelector', 'content'),
                        message: error.message,
                        level: 'danger'
                      })
                    },
                    success: function() {
                      tree.create_node(node, {
                        text: "{{Nouveau}}",
                        a_attr: {
                          "data-path": node.a_attr['data-path'] + '{{Nouveau}}/'
                        }
                      }, 'inside', function(newNode) {
                        tree.deselect_node(node)
                        tree.select_node(newNode)
                        tree.edit(newNode)
                      })
                    }
                  })
                }
              }
            }
            if (node.parent !== '#') {
              items.rename = {
                "label": "{{Renommer}}",
                "icon": "fas fa-folder",
                "action": function() {
                  tree.edit(node)
                }
              }
              items.remove = {
                "label": "{{Supprimer}}",
                "icon": "fas fa-folder-minus",
                "action": function() {
                  jeeDialog.confirm("{{Etes-vous sûr de vouloir supprimer le dossier}} <strong>" + tree.get_node(node.parent).text + "/" + node.text + "</strong> ?<br>{{Attention : le contenu du dossier sera définitivement supprimé lors de l'opération.}}", function(result) {
                    if (result) {
                      jeedom.deleteFolder({
                        path: node.a_attr['data-path'],
                        error: function(error) {
                          jeedomUtils.showAlert({
                            attachTo: jeeDialog.get('#md_iconSelector', 'content'),
                            message: error.message,
                            level: 'danger'
                          })
                        },
                        success: function(result) {
                          if (result) {
                            tree.delete_node(node)
                            tree.select_node(node.parent)
                          }
                        }
                      })
                    }
                  })
                }
              }
            }
            return items
          }
        },
        unique: {
          case_sensitive: true
        },
        plugins: ['contextmenu', 'unique']
      })
    },
    capitalizeFirstLetter: function(_string) {
      return _string.charAt(0).toUpperCase() + _string.slice(1)
    },
    printFileFolder: function(_path, jstreeId, callback) {
      jeedomUtils.hideAlert()
      jeedom.getFileFolder({
        type: 'files',
        path: _path,
        error: function(error) {
          jeedomUtils.showAlert({
            attachTo: jeeDialog.get('#md_iconSelector', 'content'),
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          var div = ''
          $('#' + jstreeId).siblings('.div_imageGallery').empty()
          if (_path.indexOf('data/') != -1) {
            var realPath = _path.substr(_path.search('data/'))
          } else if (_path.indexOf('core/') != -1) {
            var realPath = _path.substr(_path.search('core/'))
          } else {
            var realPath = _path.substr(_path.search('3rdparty/'))
          }

          if (jstreeId === 'treeFolder-icon') {
            $('#sel_colorIcon').show()
            var category = realPath.slice(0, -1).split('/').pop()
            var iconClasses = jeephp2js.md_iconSelector_selectIcon.iconClasses
            if (category === 'font-awesome5') {
              jeedom.getFileContent({
                path: realPath + 'icons.json',
                success: function(data) {
                  data = JSON.parse(data)
                  for (var i in data.icons) {
                    var test = (iconClasses && iconClasses[2] === data.icons[i].substr(4)) ? ' iconSelected' : ''
                    div += '<div class="divIconSel text-center' + test + '">'
                    div += '<span class="cursor iconSel"><i class="' + data.icons[i] + ' ' + document.getElementById('sel_colorIcon').value + '"></i></span><br/><span class="iconDesc">' + data.icons[i].substr(7) + '</span>'
                    div += '</div>'
                  }
                  $('#' + jstreeId).siblings('.div_imageGallery').append(div)
                  if (isset(callback) && typeof callback === 'function') {
                    setTimeout(function() {
                      callback()
                    })
                  }
                  return
                }
              })

            } else {
              $.get(realPath + 'style.css', function(data, status) {
                if (status === 'success') {
                  var exp_reg = new RegExp('(?=\.)' + category + '-(.*?).+?(?=\:)', "gi")
                  var matches = data.match(exp_reg)
                  for (var i in matches) {
                    var selected = (iconClasses && iconClasses[2] === matches[i]) ? ' iconSelected' : ''
                    div += '<div class="divIconSel text-center' + selected + '">'
                    div += '<span class="cursor iconSel"><i class=\'icon ' + matches[i] + ' ' + document.getElementById('sel_colorIcon').value + '\'></i></span><br/><span class="iconDesc">' + matches[i].replace(category + '-', '') + '</span>'
                    div += '</div>'
                  }
                  $('#' + jstreeId).siblings('.div_imageGallery').append(div)
                  if (isset(callback) && typeof callback === 'function') {
                    setTimeout(function() {
                      callback()
                    })
                  }
                  return
                }
              })
            }
          } else if (jstreeId === 'treeFolder-img') {
            $('#sel_colorIcon').hide()
            $('#bt_uploadImg').attr('data-path', realPath)
            for (var i in data) {
              div += '<div class="divIconSel divImgSel">'
              div += '<div class="cursor iconSel"><img class="img-responsive" src="' + realPath + data[i] + '"/></div>'
              div += '<div class="iconDesc">' + jeeFrontEnd.md_iconSelector.capitalizeFirstLetter(data[i].substr(0, data[i].lastIndexOf('.')).substr(0, 15).split('_').join(' ')) + '</div>'
              div += '<a class="btn btn-danger btn-xs bt_removeImg" data-realfilepath="' + realPath + data[i] + '"><i class="fas fa-trash-alt"></i> {{Supprimer}}</a>'
              div += '</div>'
            }
          } else if (jstreeId === 'treeFolder-bg') {
            $('#sel_colorIcon').hide()
            for (var i in data) {
              div += '<div class="divIconSel divBgSel">'
              div += '<div class="cursor iconSel"><img class="img-responsive" src="' + realPath + data[i] + '" data-filename="' + _path + data[i] + '"/></div>'
              div += '<div class="iconDesc">' + jeeFrontEnd.md_iconSelector.capitalizeFirstLetter(data[i].substr(0, data[i].lastIndexOf('.')).split('_').join(' ')) + '</div>'
              div += '</div>'
            }

          }
          $('#' + jstreeId).siblings('.div_imageGallery').append(div)
        }
      })
    }
  }
}

(function() {// Self Isolation!
  var jeeM = jeeFrontEnd.md_iconSelector
  jeeM.init()

  //Manage events outside parents delegations:
  document.getElementById('in_searchIconSelector').addEventListener('keyup', function(event) {
    document.querySelectorAll('.divIconSel').seen()
    var search = event.target.value
    if (search != '') {
      search = jeedomUtils.normTextLower(search)
      document.querySelectorAll('.iconDesc').forEach(_icon => {
        if (!_icon.textContent.includes(search)) {
          _icon.closest('.divIconSel').unseen()
        }
      })
    }
  })
  document.getElementById('bt_resetIconSelectorSearch').addEventListener('click', function(event) {
    document.getElementById('in_searchIconSelector').jeeValue('').triggerEvent('keyup')
  })

  document.getElementById('sel_colorIcon').addEventListener('change', function(event) {
    document.querySelectorAll('.iconSel i').removeClass('icon_green', 'icon_blue', 'icon_orange', 'icon_red', 'icon_yellow').addClass(event.target.value)
  })

  /*Events delegations
  */
  document.getElementById('md_iconSelector').addEventListener('click', function(event) {
    //Capture jsTree node!!
    if (event.target.hasClass('jstree-anchor')) {
      var node = event.target.closest('li')
      var tree = $.jstree.reference(node)
      tree.select_node(node)
      return
    }

    var _target = null
    if (_target = event.target.closest('.divIconSel')) {
      document.querySelectorAll('.divIconSel').removeClass('iconSelected')
      _target.closest('.divIconSel').addClass('iconSelected')
      return
    }

    if (_target = event.target.closest('.bt_removeImg')) {
      jeedomUtils.hideAlert()
      var filepath = _target.getAttribute('data-realfilepath')
      jeeDialog.confirm('{{Êtes-vous sûr de vouloir supprimer cette image}} <strong>' + filepath + '</strong> ?', function(result) {
        if (result) {
          jeedom.removeImageIcon({
            filepath: filepath,
            error: function(error) {
              jeedomUtils.showAlert({
                attachTo: jeeDialog.get('#md_iconSelector', 'content'),
                message: error.message,
                level: 'danger'
              })
            },
            success: function() {
              document.querySelector('.jstree-clicked').click()
            }
          })
        }
      })
      return
    }

    if (_target = event.target.closest('#mod_selectIcon ul.nav.nav-tabs li a')) {
      jeedomUtils.hideAlert()
      var tabhref = _target.getAttribute('href')
      if (tabhref === '#tabicon') {
        document.getElementById('sel_colorIcon').seen()
      } else {
        document.getElementById('sel_colorIcon').unseen()
        if (!document.querySelector(tabhref + ' .div_treeFolder .jstree-clicked')) {
          document.querySelector(tabhref + ' .div_treeFolder a').click()
        }
      }
      return
    }
  })

  document.getElementById('md_iconSelector').addEventListener('dblclick', function(event) {
    var _target = null
    if (_target = event.target.closest('.divIconSel')) {
      document.querySelectorAll('.divIconSel').removeClass('iconSelected')
      _target.closest('.divIconSel').addClass('iconSelected')
      document.getElementById('mod_selectIcon').querySelector('button[data-type="confirm"]').click()
      return
    }
  })

  $('.div_treeFolder').off('click').on('select_node.jstree', function(node, selected) {
    $('#in_searchIconSelector').val('')
    if (selected.node.a_attr['data-path'] != undefined) {
      var path = selected.node.a_attr['data-path']
      jeeFrontEnd.md_iconSelector.printFileFolder(path, $(this).attr('id'))
      if ($(this).attr('id') === 'treeFolder-img') {
        var ref = $(this).jstree(true)
        var sel = ref.get_selected()[0]
        ref.open_node(sel)
        var nodesList = ref.get_children_dom(sel)
        if (nodesList.length != 0) {
          return
        }
        jeedom.getFileFolder({
          type: 'folders',
          path: path,
          error: function(error) {
            jeedomUtils.showAlert({
              attachTo: jeeDialog.get('#md_iconSelector', 'content'),
              message: error.message,
              level: 'danger'
            })
          },
          success: function(data) {
            for (var i in data) {
              node = ref.create_node(sel, {
                "type": "folder",
                "text": data[i].replace('/', ''),
                state: {
                  opened: true
                },
                a_attr: {
                  'data-path': path + data[i]
                }
              })
              $('li#' + node + ' a').addClass('li_folder')
            }
          }
        })
      }
    }
  })

  $('.div_treeFolder').on("rename_node.jstree", function(event, data) {
    if (data.text !== data.old) {
      var newPath = data.node.a_attr['data-path'].replace('/' + data.old + '/', '/' + data.text + '/')
      jeedom.renameFolder({
        src: data.node.a_attr['data-path'],
        dst: newPath,
        error: function(error) {
          jeedomUtils.showAlert({
            attachTo: jeeDialog.get('#md_iconSelector', 'content'),
            message: error.message,
            level: 'danger'
          })
        },
        success: function(result) {
          if (result) {
            $.jstree.reference(data.node).get_node(data.node.id).a_attr['data-path'] = newPath
            $('#bt_uploadImg').attr('data-path', newPath.substr(newPath.search('data/')))
          }
        }
      })
    }
  })

  new jeeFileUploader({
    fileInput: document.getElementById('bt_uploadImg'),
    add: function(event, data) {
      let currentPath = document.getElementById('bt_uploadImg').getAttribute('data-path')
      data.url = 'core/ajax/jeedom.ajax.php?action=uploadImageIcon&filepath=' + currentPath
      data.submit()
    },
    done: function(event, data) {
      if (data.result.state != 'ok') {
        jeedomUtils.showAlert({
          attachTo: jeeDialog.get('#md_iconSelector', 'content'),
          message: data.result.result,
          level: 'danger'
        })
        return
      }
      document.querySelector('.jstree-clicked').click()
    }
  })

  jeeM.postInit()

})()
</script>
