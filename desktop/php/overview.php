<?php
if (!isConnect()) {
  throw new Exception('{{401 - Accès non autorisé}}');
}

$allObject = jeeObject::buildTree(null, true);
?>

<div class="row">
  <div id="objectOverviewContainer">
    <?php
    $div = '';
    foreach ($allObject as $_object) {
      if (!$_object->hasRight('r')) {
        continue;
      }
      if ($_object->getConfiguration('hideOnOverview') == 1) continue;

      $backUrl = $_object->getImgLink();
      if ($backUrl == '') {
        $backUrl = 'core/img/background/jeedom_abstract_04_light.jpg';
      }

      $synthAction = $_object->getConfiguration('synthToAction', -1);
      if ($synthAction != -1 && $synthAction != 'synthToDashboard') {
        if ($synthAction == 'synthToView') {
          $dataUrl = 'index.php?v=d&p=view&view_id=' . $_object->getConfiguration('synthToView');
        }
        if ($synthAction == 'synthToPlan') {
          $dataUrl = 'index.php?v=d&p=plan&plan_id=' . $_object->getConfiguration('synthToPlan');
        }
        if ($synthAction == 'synthToPlan3d') {
          $dataUrl = 'index.php?v=d&p=plan3d&plan3d_id=' . $_object->getConfiguration('synthToPlan3d');
        }
      } else {
        $dataUrl = 'index.php?v=d&p=dashboard&object_id=' . $_object->getId() . '&childs=0' . '&btover=1';
      }

      $div .= '<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">';
      $div .= '<div class="objectPreview cursor shadowed fullCorner" style="background:url(' . $backUrl . ')" data-url="' . $dataUrl . '" data-object_id="' . $_object->getId() . '">';
      $div .= '<div class="topPreview topCorner nocursor">';
      $div .= '<span class="name cursor">' . $_object->getDisplay('icon') . ' ' . $_object->getName() . '</span>';
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
<div id="md_overviewSummary" class="cleanableModal" style="overflow-x: hidden;">
  <div id="summaryEqlogics"></div>
</div>


<?php
include_file('desktop/common', 'ui', 'js');
include_file('desktop', 'overview', 'js');
?>