<?php
if (!isConnect()) {
  throw new Exception('{{401 - Accès non autorisé}}');
}

$allObject = jeeObject::all(true);
?>

<div class="row" >
    <div class="objectPreviewContainer">
  <?php
  foreach ($allObject as $_object) {
      $backUrl = $_object->getImgLink();
      if ($backUrl == '') {
        $backUrl = 'core/img/background/jeedom_abstract_04_light.jpg';
      }
      $div = '<div class="objectPreview cursor shadowed fullCorner" style="background:url('.$backUrl.')" data-object_id="'.$_object->getId().'">';
        $div .= '<div class="topPreview topCorner">';
        $div .= '<span class="name">' . $_object->getName() . '</span>';
        $div .= '</div>';
        $div .= '<div class="bottomPreview bottomCorner">';
          $div .= '<span class="settings"><i class="fas fa-cog"></i></span>';
          $div .= '<div class="resume">' . $_object->getHtmlSummary() . '</div>';
        $div .= '</div>';
      $div .= '</div>';
    echo $div;
  }
  ?>
    </div>
</div>
</div>
</div>

<?php include_file('desktop', 'preview', 'js'); ?>