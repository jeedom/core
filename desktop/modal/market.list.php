<?php
if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}

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
            $url .= $key . '=' . urlencode($value) . '&';
        }
    }
    if ($_key != '' && $_value != '') {
        $url .= $_key . '=' . urlencode($_value);
    }
    return $url;
}
?>
<div style="margin-bottom: 5px; margin-top : 5px; background-color: #e7e7e7">
    <form class="form-inline" role="form" onsubmit="return false;">
        <?php if (init('type', 'plugin') == 'plugin') { ?>
            <div class="form-group">
                <div class="btn-group" >
                    <a class="btn btn-default bt_pluginFilter <?php echo (init('cost') == 'free') ? 'btn-primary' : '' ?>" data-href="<?php echo buildUrl('cost', 'free'); ?>">Gratuit</a>
                    <a class="btn btn-default bt_pluginFilter <?php echo (init('cost') == 'paying') ? 'btn-primary' : '' ?>" data-href="<?php echo buildUrl('cost', 'paying'); ?>">Payant</a>
                    <a class="btn btn-default bt_pluginFilter" data-href="<?php echo buildUrl('cost', ''); ?>"><i class="fa fa-times"></i></a>
                </div>
            </div>
        <?php } ?>
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
            <div class="btn-group" >
                <a class="btn btn-default bt_installFilter" data-state="-1">Installé</a>
                <a class="btn btn-default bt_installFilter" data-state="1">Non installé</a>
                <a class="btn btn-default bt_installFilter" data-state="0"><i class="fa fa-times"></i></a>
            </div>
        </div>
        <div class="form-group">
            <select class="form-control" id="sel_categorie" data-href='<?php echo buildUrl('categorie', ''); ?>'>
                <?php if (init('type', 'plugin') == 'zwave') { ?>
                    <option value="">Toutes les marques</option>
                <?php } else { ?>
                    <option value="">Toutes les categories</option>
                <?php
                }

                foreach (market::distinctCategorie($type) as $id => $category) {
                    if (trim($category) != '' && is_numeric($id)) {
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
    $nCategory = 0;
    foreach ($markets as $market) {
        $update = update::byLogicalId($market->getLogicalId());
        $category = $market->getCategorie();
        if ($category == '') {
            $category = 'Aucune';
        }
        if ($categorie != $category) {
            $categorie = $category;
            if (!$first) {
                echo '</div>';
            }
            echo '<legend style="border-bottom: 1px solid #34495e; color : #34495e;" data-category="' . $nCategory . '">' . ucfirst($categorie) . '</legend>';
            echo '<div class="pluginContainer" data-category="' . $nCategory . '">';
            $first = false;
            $nCategory++;
        }
        $install = 'notInstall';
        if (!is_object($update)) {
            $install = 'install';
        }
        echo '<div class="market cursor ' . $install . '" data-market_id="' . $market->getId() . '" data-market_type="' . $market->getType() . '" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >';
        if ($market->getType() == 'widget') {
            if (strpos($market->getName(), 'mobile.') !== false) {
                echo '<i class="fa fa-mobile pull-left" style="color:#c5c5c5"></i>';
            } else {
                echo '<i class="fa fa-desktop pull-left" style="color:#c5c5c5"></i>';
            }
        }
        if (is_object($update)) {
            echo '<i class="fa fa-check" style="position : absolute; right : 5px;"></i>';
        }
        echo "<center>";
        $urlPath = config::byKey('market::address') . '/' . $market->getImg('icon');
        echo '<img class="lazy" src="core/img/no_image.gif" data-original="' . $urlPath . '" height="105" width="95" />';
        echo "</center>";
        echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;">' . $market->getName() . '</span>';
        if ($market->getCertification() == 'Officiel') {
            echo '<br/><span style="font-size : 0.85em;color:#7f8c8d;position:relative; top : 10px;">Officiel</span>';
        }
        if ($market->getCertification() == 'Recommandé') {
            echo '<br/><span style="font-size : 0.85em;color:#7f8c8d;position:relative; top : 10px;">Recommandé</span>';
        }
        $note = $market->getRating();

        echo '<span style="position : absolute;bottom : 5px;left : 5px;font-size : 0.7em;">';
        for ($i = 1; $i < 6; $i++) {
            if ($i <= $note) {
                echo '<i class="fa fa-star"></i>';
            } else {
                echo '<i class="fa fa-star-o"></i>';
            }
        }
        echo '</span>';
        if ($market->getCost() > 0) {
            echo '<span style="position : absolute;bottom : 5px;right : 12px;color:#97bd44;">';
            if ($market->getCost() != $market->getRealCost()) {
                echo '<span style="text-decoration:line-through;">' . number_format($market->getRealCost(), 2) . ' €</span> ';
            }
            echo number_format($market->getCost(), 2) . ' €</span>';
        } else {
            echo '<span style="position : absolute;bottom : 5px;right : 12px;color:#97bd44;">Gratuit</span>';
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
        $('.pluginContainer').packery();

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
            $('#md_modal').load($(this).attr('data-href') + '&categorie=' + encodeURI($(this).value()));
        });

        $('#bt_search').on('click', function () {
            $('#md_modal').load($(this).attr('data-href') + '&name=' + encodeURI($('#in_search').value()));
        });

        $('#in_search').keypress(function (e) {
            if (e.which == 13) {
                $('#md_modal').load($(this).attr('data-href') + '&name=' + encodeURI($('#in_search').value()));
            }
        });

        $('.bt_installFilter').on('click', function () {
            $('.bt_installFilter').removeClass('btn-primary');
            $('.pluginContainer').show();
            $('.market').show();
            if ($(this).attr('data-state') == 1) {
                $(this).addClass('btn-primary');
                $('.notInstall').hide();
            }
            if ($(this).attr('data-state') == -1) {
                $(this).addClass('btn-primary');
                $('.install').hide();
            }
            $('.pluginContainer').each(function () {
                var hasVisible = false;
                $(this).find('.market').each(function () {
                    if ($(this).is(':visible')) {
                        hasVisible = true;
                    }
                });
                if (hasVisible) {
                    $('legend[data-category=' + $(this).attr('data-category') + ']').show();
                    $(this).packery();
                } else {
                    $(this).hide();
                    $('legend[data-category=' + $(this).attr('data-category') + ']').hide();
                }
            });
        });

        $('.market').on('click', function () {
            $('#md_modal2').dialog({title: "{{Market Jeedom}}"});
            $('#md_modal2').load('index.php?v=d&modal=market.display&type=' + $(this).attr('data-market_type') + '&id=' + $(this).attr('data-market_id')).dialog('open');
        });
    });
</script>