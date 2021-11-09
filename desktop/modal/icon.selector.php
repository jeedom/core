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

$objectId = init('object_id');

sendVarToJS([
  'objectId' => $objectId,
  'selectIcon' => init('selectIcon', 0),
  'colorIcon' => init('colorIcon', 0)
]);
?>

<div style="display: none;" id="div_iconSelectorAlert"></div>

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
  <div id="mySearch" class="input-group" style="margin-left:6px;margin-top:6px">
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
      <a id="bt_resetSearch" class="btn roundedRight" style="width:30px"><i class="fas fa-times"></i> </a>
    </div>
  </div>

  <?php if (!$objectId) { ?>
    <div role="tabpanel" class="tab-pane active" id="tabicon">
      <div class="imgContainer">
        <div id="treeFolder-icon" class="div_treeFolder">
          <ul id="ul_Folder-bg">
            <?php
            $scanPaths = array('/var/www/html/core/css/icon', '/var/www/html/data/fonts');
            foreach ($scanPaths as $root) {
              $ls = ls($root, '*', false, array('folders'));
              foreach ($ls as $dir) {
                if (!file_exists($root . '/' . $dir . 'style.css') || !file_exists($root . '/' . $dir . 'fonts/' . substr($dir, 0, -1) . '.ttf')) {
                  continue;
                }
                echo '<li><a data-path="' . $root . '/' . $dir . '">' . ucfirst(str_replace(array('/', '_'), array('', ' '), $dir)) . '</a></li>';
              }
            }
            echo '<li><a data-path="/var/www/html/3rdparty/font-awesome5/">Font-Awesome</a></li>';
            ?>
          </ul>
        </div>
        <div class="div_imageGallery">
        </div>
      </div>
    </div>

    <div role="tabpanel" class="tab-pane" id="tabimg">
      <span class="btn btn-default btn-file" style="position:absolute;right:25px;top:40px;">
        <i class="fas fa-cloud-upload-alt"></i> {{Envoyer}}<input id="bt_uploadImg" type="file" name="file" multiple="multiple" data-path="" style="display: inline-block;">
      </span>
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
        <div class="div_imageGallery">
        </div>
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
        <div class="div_imageGallery">
        </div>
      </div>
    </div>
  <?php } ?>
</div>
<?php include_file('3rdparty', 'jquery.tree/jstree.min', 'js'); ?>
<script>
  $(document).ready(function() {
    // //move color_select/search_input in modal bottom
    var buttonSet = $('.ui-dialog[aria-describedby="mod_selectIcon"]').find('.ui-dialog-buttonpane')
    buttonSet.find('#mySearch').remove()
    var mySearch = $('.ui-dialog[aria-describedby="mod_selectIcon"]').find('#mySearch')
    buttonSet.append(mySearch)
  })

  $('.div_treeFolder').off('click').on('select_node.jstree', function(node, selected) {
    $('#in_searchIconSelector').val('')
    if (selected.node.a_attr['data-path'] != undefined) {
      var path = selected.node.a_attr['data-path'];
      printFileFolder(path, $(this).attr('id'));
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
            $.fn.showAlert({
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

  $(".div_treeFolder").jstree({
    "core": {
      "check_callback": true
    }
  });

  function printFileFolder(_path, jstreeId) {
    $.hideAlert()
    jeedom.getFileFolder({
      type: 'files',
      path: _path,
      error: function(error) {
        $.fn.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function(data) {
        var div = '';
        $('#' + jstreeId).siblings('.div_imageGallery').empty();
        if (_path.indexOf('data/') != -1) {
          var realPath = _path.substr(_path.search('data/'));
        } else if (_path.indexOf('core/') != -1) {
          var realPath = _path.substr(_path.search('core/'));
        } else {
          var realPath = _path.substr(_path.search('3rdparty/'));
        }
        if (jstreeId === 'treeFolder-icon') {
          $('#sel_colorIcon').show()
          var category = realPath.slice(0, -1).split('/').pop();
          if (category === 'font-awesome5') {
            jeedom.getFileContent({
              path: realPath + 'icons.json',
              success: function(data) {
                data = JSON.parse(data);
                for (var i in data.icons) {
                  var test = (iconClasses && iconClasses[2] === data.icons[i].substr(4)) ? ' iconSelected' : ''
                  div += '<div class="divIconSel text-center' + test + '">';
                  div += '<span class="cursor iconSel"><i class="' + data.icons[i] + ' ' + $('#sel_colorIcon').value() + '"></i></span><br/><span class="iconDesc">' + data.icons[i].substr(7) + '</span>';
                  div += '</div>';
                }
                $('#' + jstreeId).siblings('.div_imageGallery').append(div)

              }
            });

          } else {
            $.get(realPath + 'style.css', function(data, status) {
              if (status === 'success') {
                var exp_reg = new RegExp('(?=\.)' + category + '-(.*?).+?(?=\:)', "gi");
                var matches = data.match(exp_reg);
                for (var i in matches) {
                  var selected = (iconClasses && iconClasses[2] === matches[i]) ? ' iconSelected' : ''
                  div += '<div class="divIconSel text-center' + selected + '">';
                  div += '<span class="cursor iconSel"><i class=\'icon ' + matches[i] + ' ' + $('#sel_colorIcon').value() + '\'></i></span><br/><span class="iconDesc">' + matches[i].replace(category + '-', '') + '</span>';
                  div += '</div>';
                }
                $('#' + jstreeId).siblings('.div_imageGallery').append(div)
              }
            });
          }
        } else if (jstreeId === 'treeFolder-img') {
          $('#sel_colorIcon').hide()
          $('#bt_uploadImg').attr('data-path', realPath);
          for (var i in data) {
            div += '<div class="divIconSel divImgSel">';
            div += '<div class="cursor iconSel"><img class="img-responsive" src="' + realPath + data[i] + '"/></div>';
            div += '<div class="iconDesc">' + capitalizeFirstLetter(data[i].substr(0, data[i].lastIndexOf('.')).substr(0, 15).split('_').join(' ')) + '</div>';
            div += '<a class="btn btn-danger btn-xs bt_removeImg" data-realfilepath="' + realPath + data[i] + '"><i class="fas fa-trash"></i> {{Supprimer}}</a>';
            div += '</div>';
          }
        } else if (jstreeId === 'treeFolder-bg') {
          $('#sel_colorIcon').hide()
          for (var i in data) {
            div += '<div class="divIconSel divBgSel">';
            div += '<div class="cursor iconSel"><img class="img-responsive" src="' + realPath + data[i] + '" data-filename="' + _path + data[i] + '"/></div>';
            div += '<div class="iconDesc">' + capitalizeFirstLetter(data[i].substr(0, data[i].lastIndexOf('.')).split('_').join(' ')) + '</div>';
            div += '</div>';
          }

        }
        $('#' + jstreeId).siblings('.div_imageGallery').append(div)
      }
    })
  }

  $('#mod_selectIcon ul.nav.nav-tabs li a').click(function() {
    $.hideAlert();
    var tabhref = $(this).attr('href')
    if (tabhref === '#tabicon') {
      $('#sel_colorIcon').show()
    } else {
      $('#sel_colorIcon').hide()
      if ($(tabhref + ' .div_treeFolder .jstree-clicked').length === 0) {
        $(tabhref + ' .div_treeFolder').find('a:first').click();
      }
    }
  })

  $('#sel_colorIcon').off('change').on('change', function() {
    $('.iconSel i').removeClass('icon_green icon_blue icon_orange icon_red icon_yellow').addClass($(this).value())
  })

  if (selectIcon != "0") {
    var iconClasses = selectIcon.split('.')
    if (iconClasses[1].substr(0, 2) === 'fa') {
      iconClasses[1] = 'font-awesome5';
    } else if (iconClasses[1] === 'icon') {
      iconClasses[1] = iconClasses[2].split('-')[0]
    }
    $('a.jstree-anchor').each(function() {
      if ($(this).data('path').includes('/' + iconClasses[1] + '/')) {
        $(this).click()
        return
      }
    })
    setTimeout(function() {
      if (colorIcon != "0") {
        $('#sel_colorIcon').value(colorIcon)
      }
      elem = $('div.divIconSel.iconSelected')
      if (elem.position()) {
        container = $('#mod_selectIcon > .tab-content')
        pos = elem.position().top + container.scrollTop() - container.position().top
        container.animate({
          scrollTop: pos - 20
        })
      }
    }, 250)
  } else {
    $('a.jstree-anchor').first().click()
  }

  $('#bt_uploadImg').fileupload({
    add: function(e, data) {
      let currentPath = $('#bt_uploadImg').attr('data-path');
      data.url = 'core/ajax/jeedom.ajax.php?action=uploadImageIcon&filepath=' + currentPath;
      data.submit();
    },
    done: function(e, data) {
      if (data.result.state != 'ok') {
        $('#div_iconSelectorAlert').showAlert({
          message: data.result.result,
          level: 'danger'
        });
        return;
      }
      $('.jstree-clicked').click();
      $('#div_iconSelectorAlert').showAlert({
        message: 'Fichier(s) ajouté(s) avec succès',
        level: 'success'
      });
    }
  });

  $('#tabimg .div_imageGallery').off('click').on('click', '.bt_removeImg', function() {
    $.hideAlert();
    var filepath = $(this).attr('data-realfilepath');
    bootbox.confirm('{{Êtes-vous sûr de vouloir supprimer cette image}} <span style="font-weight: bold ;">' + filepath + '</span> ?', function(result) {
      if (result) {
        jeedom.removeImageIcon({
          filepath: filepath,
          error: function(error) {
            $('#div_iconSelectorAlert').showAlert({
              message: error.message,
              level: 'danger'
            });
          },
          success: function(data) {
            $('.jstree-clicked').click();
            $('#div_iconSelectorAlert').showAlert({
              message: 'Fichier supprimé avec succès',
              level: 'success'
            });
          }
        })
      }
    })
  });

  $('.div_imageGallery').on('click', '.divIconSel', function() {
    $('.divIconSel').removeClass('iconSelected');
    $(this).closest('.divIconSel').addClass('iconSelected');
  })

  $('.div_imageGallery').on('dblclick', '.divIconSel', function() {
    $('.divIconSel').removeClass('iconSelected');
    $(this).closest('.divIconSel').addClass('iconSelected');
    $('#mod_selectIcon').dialog("option", "buttons")['Valider'].apply($('#mod_selectIcon'));
  })

  setTimeout(function() {
    if (getDeviceType()['type'] == 'desktop') $("input[id^='in_search']").focus()
  }, 500)

  $('#in_searchIconSelector').on('keyup', function() {
    $('.divIconSel').show()
    var search = $(this).value()
    if (search != '') {
      search = jeedomUtils.normTextLower(search)
      $('.iconDesc').each(function() {
        if ($(this).text().indexOf(search) == -1) {
          $(this).closest('.divIconSel').hide()
        }
      })
    }
  })

  $('#bt_resetSearch').on('click', function() {
    $('#in_searchIconSelector').val('').keyup()
  })

  function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1)
  }
</script>