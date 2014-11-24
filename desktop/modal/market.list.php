<?php
if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
include_file('3rdparty', 'jquery.lazyload/jquery.lazyload', 'js');
include_file('3rdparty', 'jquery.masonry/jquery.masonry', 'js');

$status = init('status', null);
$type = init('type', null);
$categorie = init('categorie', null);
$name = init('name', null);
if ($name == 'false') {
    $name = null;
}
$markets = market::byFilter(
                array(
                    'status' => $status,
                    'type' => $type,
                    'categorie' => $categorie,
                    'name' => $name,
                    'cost' => init('cost', null),
                    'timeState' => init('timeState', null),
                    'certification' => init('certification', null)
                )
);

function buildUrl($_key, $_value) {
    $url = 'index.php?v=d&modal=market.display&';
    foreach ($_GET as $key => $value) {
        if ($_key != $key) {
            $url .= $key . '=' . $value . '&';
        }
    }
    if ($_key != '' && $_value != '') {
        $url .= $_key . '=' . $_value;
    }
    return $url;
}
?>
<div style="margin-bottom: 5px; margin-top : 5px; background-color: #e7e7e7">
    <form class="form-inline" role="form" onsubmit="return false;">
        <div class="form-group">
            <div class="btn-group" >
                <a class="btn btn-default bt_pluginFilter <?php echo (init('cost') == 'free') ? 'btn-primary' : '' ?>" data-href="<?php echo buildUrl('cost', 'free'); ?>">Gratuit</a>
                <a class="btn btn-default bt_pluginFilter <?php echo (init('cost') == 'paying') ? 'btn-primary' : '' ?>" data-href="<?php echo buildUrl('cost', 'paying'); ?>">Payant</a>
                <a class="btn btn-default bt_pluginFilter" data-href="<?php echo buildUrl('cost', ''); ?>"><i class="fa fa-times"></i></a>
            </div>
        </div>
        <div class="form-group">
            <div class="btn-group" >
                <a class="btn btn-default bt_pluginFilter <?php echo (init('timeState') == 'newest') ? 'btn-primary' : '' ?>" data-href="<?php echo buildUrl('timeState', 'newest'); ?>">Nouveau</a>
                <a class="btn btn-default bt_pluginFilter <?php echo (init('timeState') == 'popular') ? 'btn-primary' : '' ?>" data-href="<?php echo buildUrl('timeState', 'popular'); ?>">Populaire</a>
                <a class="btn btn-default bt_pluginFilter" data-href="<?php echo buildUrl('timeState', ''); ?>"><i class="fa fa-times"></i></a>
            </div>
        </div>
        <div class="form-group">
            <div class="btn-group" >
                <a class="btn btn-default bt_pluginFilter <?php echo (init('certification') == 'Officiel') ? 'btn-primary' : '' ?>" data-href="<?php echo buildUrl('certification', 'Officiel'); ?>">Officiel</a>
                <a class="btn btn-default bt_pluginFilter <?php echo (init('certification') == 'Recommandé') ? 'btn-primary' : '' ?>" data-href="<?php echo buildUrl('certification', 'Recommandé'); ?>">Recommandé</a>
                <a class="btn btn-default bt_pluginFilter" data-href="<?php echo buildUrl('certification', ''); ?>"><i class="fa fa-times"></i></a>
            </div>
        </div>
        <div class="form-group">
            <select class="form-control" id="sel_categorie" data-href='<?php echo buildUrl('categorie', ''); ?>'>
                <option value="">Toutes les categories</option>
                <?php
                foreach (market::distinctCategorie($type) as $category) {
                    if (trim($category) != '') {
                        echo '<option value="' . $category . '"';
                        echo (init('categorie') == $category) ? 'selected >' : '>';
                        echo $category;
                        echo '</option>';
                    }
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <input class="form-control" data-href='<?php echo buildUrl('name', ''); ?>' placeholder="Rechercher" id="in_search" value="<?php echo $name ?>"/>
            <a class="btn btn-success" id="bt_search" data-href='<?php echo buildUrl('name', ''); ?>'><i class="fa fa-search"></i></a>
        </div>
    </form>
</div>
<div style="padding : 5px;">
    <?php
    $categorie = '';
    $first = true;
    foreach ($markets as $market) {
        $category = $market->getCategorie();
        if ($category == '') {
            $category = 'Aucune';
        }
        if ($categorie != $category) {
            $categorie = $category;
            if (!$first) {
                echo '</div>';
            }
            echo '<legend style="border-bottom: 1px solid #34495e; color : #34495e;">' . ucfirst($categorie) . '</legend>';
            echo '<div class="pluginContainer">';
            $first = false;
        }

        echo '<div class="market cursor" data-market_id="' . $market->getId() . '" data-market_type="' . $market->getType() . '" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 170px;margin-left : 10px;" >';
        if (!is_object(update::byLogicalId($market->getLogicalId()))) {
            echo '<i class="fa fa-check" style="position : absolute; right : 5px;"></i>';
        }

        echo "<center>";
        if ($market->getStatus('stable') == 1 && $market->getImg('stable')) {
            $urlPath = config::byKey('market::address') . '/' . $market->getImg('stable');
        } else {
            if ($market->getImg('beta')) {
                $urlPath = config::byKey('market::address') . '/' . $market->getImg('beta');
            }
        }
        echo '<img class="lazy" src="core/img/no_image.gif" data-original="' . $urlPath . '" height="100" width="85" />';
        echo "</center>";
        echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;">' . $market->getName() . '</span>';
        if ($market->getCertification() == 'Officiel') {
            echo '<br/><span style="font-size : 0.85em;color:#7f8c8d;position:relative; top : 10px;">Officiel</span>';
        }
        if ($market->getCertification() == 'Recommandé') {
            echo '<br/><span style="font-size : 0.85em;color:#7f8c8d;position:relative; top : 10px;">Recommandé</span>';
        }
        $note = $market->getRating();

        echo '<span style="position : absolute;bottom : 14px;left : 5px;font-size : 0.7em;">';
        for ($i = 1; $i < 6; $i++) {
            if ($i <= $note) {
                echo '<i class="fa fa-star"></i>';
            } else {
                echo '<i class="fa fa-star-o"></i>';
            }
        }
        echo '</span>';
        if ($market->getCost() > 0) {
            echo '<span style="position : absolute;bottom : 14px;right : 12px;font-size : 0.85em;color:#97bd44;">' . number_format($market->getCost(), 2) . ' €</span>';
        } else {
            echo '<span style="position : absolute;bottom : 14px;right : 12px;font-size : 0.85em;color:#97bd44;">Gratuit</span>';
        }
        echo '</div>';
    }
    ?>
</div>
<style>
    .market:hover{
        background-color : #F2F1EF !important;
    }

    #md_modal{
        background-color: #e7e7e7
    }
</style>

<script>
    $(function () {
        $('.pluginContainer').each(function () {
            $(this).masonry({columnWidth: 10});
        });

        $("img.lazy").lazyload({
            event: "sporty"
        });
        $("img.lazy").trigger("sporty");
        initTableSorter();

        setTimeout(function () {
            $('#table_market tbody tr.install').hide();
        }, 500);

        $('.bt_pluginFilter').on('click', function () {
            $('#md_modal').load($(this).attr('data-href'));
        });

        $('#sel_categorie').on('change', function () {
            $('#md_modal').load($(this).attr('data-href') + '&categorie=' + $(this).value());
        });

        $('#bt_search').on('click', function () {
            $('#md_modal').load($(this).attr('data-href') + '&name=' + $('#in_search').value());
        });

        $('#in_search').keypress(function (e) {
            if (e.which == 13) {
                $('#md_modal').load($(this).attr('data-href') + '&name=' + $('#in_search').value());
            }
        });

        $('#bt_marketListDisplayInstallObject').on('change', function () {
            if ($(this).value() == 1) {
                $('#table_market tbody tr.install').show();
            } else {
                $('#table_market tbody tr.install').hide();
            }
        });

        $('.market').on('click', function () {
            $('#md_modal2').dialog({title: "{{Market Jeedom}}"});
            $('#md_modal2').load('index.php?v=d&modal=market.display&type=' + $(this).attr('data-market_type') + '&id=' + $(this).attr('data-market_id')).dialog('open');
        });
    });
</script>