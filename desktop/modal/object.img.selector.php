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
sendVarToJs('img_object_id',init('object_id'));
?>

<div style="display: none;" id="div_imgObjSelectorAlert"></div>

<div id="tabicon" class="tab-content" style="height:calc(100% - 20px);overflow-y:scroll;">
  <div id="mySearch" class="input-group" style="margin-left:6px;margin-top:6px">
    <input class="form-control" placeholder="{{Rechercher}}" id="in_searchImgSelector">
    <div class="input-group-btn">
      <a id="bt_resetSearch" class="btn roundedRight" style="width:30px"><i class="fas fa-times"></i> </a>
    </div>
  </div>
  <div class="imgContainer" style="width:calc(100% - 15px)">
    <?php
    $ls1 = ls(__DIR__.'/../../core/img/object_background','*');
    foreach ($ls1 as $category) {
      $div = '';
      $div .= '<div class="imgCategory"><legend>'.ucfirst(str_replace(array('/','_'),array('',' '),$category)).'</legend>';
      $div .= '<div class="row">';
      $ls2 = ls(__DIR__.'/../../core/img/object_background/'.$category,'*');
      foreach ($ls2 as $file) {
        $div .= '<div class="col-lg-1 divImgSel">';
        $div .= '<span class="imgSel"><img src="core/img/object_background/'.$category.$file.'" /></span>';
        $div .= '<center class="imgDesc">'.ucfirst(substr(str_replace(array('/','_','.jpg'),array('',' ',''),basename($file)),0,12)).'</center>';
        $div .= '<center><a class="btn btn-success btn-xs btSelectImgObj" data-filename="'.__DIR__.'/../../core/img/object_background/'.$category.$file.'"><i class="fas fa-check"></i> {{Sélectionner}}</a></center>';
        $div .= '</div>';
      }
      $div .= '</div></div>';
      echo $div;
    }
    ?>
  </div>
</div>

<script>
$('.btSelectImgObj').on('click',function() {
  var filename = $(this).attr('data-filename')
  jeedom.object.uploadImage({
    id : img_object_id,
    file : filename,
    error: function (error) {
      $('#div_imgObjSelectorAlert').showAlert({message: error.message, level: 'danger'})
    },
    success: function (data) {
      $('#div_imgObjSelectorAlert').showAlert({message: '{{Image ajouté avec succès}}', level: 'success'})
      if (isset(data.filepath)) {
        filePath = data.filepath
        filePath = '/data/object/' + filePath.split('/data/object/')[1]
        $('.objectImg img').attr('src',filePath);
        $('.objectImg img').show()
      } else {
        $('.objectImg img').hide()
      }
      $('#md_modal').dialog('close')
    }
  })
})

setTimeout(function() {
  if (getDeviceType()['type'] == 'desktop') $("input[id^='in_search']").focus()
}, 500)

//searching
$('#in_searchImgSelector').on('keyup',function(){
  $('.divImgSel').show()
  $('.imgCategory').show()
  var search = $(this).value()
  var text = null
  if (search != '') {
    search = jeedomUtils.normTextLower(search)
    $('.imgDesc').each(function() {
      text = jeedomUtils.normTextLower($(this).text())
      if (text.indexOf(search) == -1) {
        $(this).closest('.divImgSel').hide()
      }
    })
  }
  $('.imgCategory').each(function() {
    if ($(this).find('.divImgSel:visible').length == 0) {
      $(this).hide()
    }
  })
})

$('#bt_resetSearch').on('click', function () {
  $('#in_searchImgSelector').val('').keyup()
})
</script>