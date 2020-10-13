<?php
if (!isConnect()) {
  throw new Exception('{{401 - Accès non autorisé}}');
}

$allObject = jeeObject::buildTree(null, true);
?>

<div class="row" >
  <div id="objectOverviewContainer">
    <?php
    $div = '';
    foreach ($allObject as $_object) {
      if ($_object->getConfiguration('hideOnOverview') == 1) continue;
      $backUrl = $_object->getImgLink();
      if ($backUrl == '') {
        $backUrl = 'core/img/background/jeedom_abstract_04_light.jpg';
      }
      $div .= '<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">';
      $div .= '<div class="objectPreview cursor shadowed fullCorner" style="background:url('.$backUrl.')" data-object_id="'.$_object->getId().'">';
      $div .= '<div class="topPreview topCorner nocursor">';
      $div .= '<span class="name cursor">' . $_object->getDisplay('icon') .' '.$_object->getName() . '</span>';
      $div .= '</div>';
      $div .= '<div class="bottomPreview bottomCorner">';
      $div .= '<div class="resume" style="display:none;">' . $_object->getHtmlSummary() . '</div>';
      $div .= '</div>';
      $div .= '</div>';
      $div .= '</div>';
    }
    echo $div;
    ?>
  </div>
</div>
<div id="md_overviewSummary" style="overflow-x: hidden;">
  <div id="summaryEqlogics"></div>
</div>


<?php
  include_file('desktop/common', 'ui', 'js');
  include_file('desktop', 'overview', 'js');
?>