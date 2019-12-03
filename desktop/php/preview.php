<?php
if (!isConnect()) {
  throw new Exception('{{401 - Accès non autorisé}}');
}

$allObject = jeeObject::all(true);
?>

<div class="row" >
  <div id="objectPreviewContainer">
    <?php
    $div = '';
    foreach ($allObject as $_object) {
      if ($_object->getConfiguration('hideOnPreview') == 1) continue;
      $backUrl = $_object->getImgLink();
      if ($backUrl == '') {
        $backUrl = 'core/img/background/jeedom_abstract_04_light.jpg';
      }
      $div .= '<div class="col-lg-2 col-md-3 col-sm-6 col-xs-12" style="padding-right:4px !important;padding-left:0px !important">';
      $div .= '<div class="objectPreview cursor shadowed fullCorner" style="background:url('.$backUrl.')" data-object_id="'.$_object->getId().'">';
      $div .= '<div class="topPreview topCorner">';
      $div .= '<span class="name">' . $_object->getName() . '</span>';
      $div .= '</div>';
      $div .= '<div class="bottomPreview bottomCorner">';
      $div .= '<div class="resume">' . $_object->getHtmlSummary() . '</div>';
      $div .= '</div>';
      $div .= '</div>';
      $div .= '</div>';
    }
    echo $div;
    ?>
  </div>
</div>
</div>
</div>

<?php include_file('desktop', 'preview', 'js'); ?>
