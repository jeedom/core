<?php
if (!hasRight('viewview')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
$list_view = view::all();

if (init('view_id') == '') {
    if ($_SESSION['user']->getOptions('defaultDesktopView') != '') {
        $view = view::byId($_SESSION['user']->getOptions('defaultDesktopView'));
    }
    if (!is_object($view)) {
     $view = $list_view[0];
 }
}else {
    $view = view::byId(init('view_id'));
    if (!is_object($view)) {
        throw new Exception('{{Vue inconnue. Verifier l\'id}}');
    }
}
if (!is_object($view)) {
    throw new Exception('{{Aucune vue n\'éxiste, cliquez <a href="index.php?v=d&p=view_edit">ici</a> pour en creer une}}');
}
sendVarToJS('view_id', $view->getId());
?>

<div class="row row-overflow">
    <?php
    if ($_SESSION['user']->getOptions('displayViewByDefault') == 1) {
        echo '<div class="col-lg-2 col-md-3 col-sm-4" id="div_displayViewList">';
    } else {
        echo '<div class="col-lg-2 col-md-3 col-sm-4" style="display:none;" id="div_displayViewList">';
    }
    ?>
    <div class="bs-sidebar">
        <ul id="ul_view" class="nav nav-list bs-sidenav">
            <?php if (hasRight('viewedit', true)) { ?>
            <a class="btn btn-default" style="width : 100%;margin-top : 5px;margin-bottom: 5px;" href="index.php?v=d&p=view_edit"><i class="fa fa-plus-circle"></i> {{Ajouter une vue}}</a>
            <?php } ?>
            <li class="filter"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
            <?php
            foreach (view::all() as $view_info) {
                if ($view->getId() == $view_info->getId()) {
                    echo '<li class="cursor li_view active"><a href="index.php?v=d&p=view&view_id=' . $view_info->getId() . '">' . $view_info->getName() . '</a></li>';
                } else {
                    echo '<li class="cursor li_view"><a href="index.php?v=d&p=view&view_id=' . $view_info->getId() . '">' . $view_info->getName() . '</a></li>';
                }
            }
            ?>
        </ul>
    </div>
</div>
<?php
if ($_SESSION['user']->getOptions('displayViewByDefault') == 1) {
    echo '<div class="col-lg-10 col-md-9 col-sm-8" id="div_displayViewContainer">';
} else {
    echo '<div class="col-lg-12 col-md-12 col-sm-12" id="div_displayViewContainer">';
}
?>
<i class='fa fa-picture-o cursor tooltips pull-left' id='bt_displayView' data-display='<?php echo $_SESSION['user']->getOptions('displayViewByDefault') ?>' title="Afficher/Masquer les vues"></i>
<legend style="height: 35px;color : #563d7c;">Vue <?php
    echo $view->getName();
    if (hasRight('viewedit', true)) {
        ?> <a href="index.php?v=d&p=view_edit&view_id=<?php echo $view->getId(); ?>" class="btn btn-warning btn-xs pull-right" id="bt_addviewZone"><i class="fa fa-pencil"></i> {{Editer}}</a><?php } ?></legend>
        <div id="div_displayView"></div>
    </div>

</div>

<?php //include_file('desktop', 'view', 'js'); ?>
