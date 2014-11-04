<?php
if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
include_file('3rdparty', 'jquery.lazyload/jquery.lazyload', 'js');
include_file('3rdparty', 'bootstrap.rating/bootstrap.rating', 'js');
include_file('3rdparty', 'jquery.tablesorter/theme.bootstrap', 'css');
include_file('3rdparty', 'jquery.tablesorter/jquery.tablesorter.min', 'js');
include_file('3rdparty', 'jquery.tablesorter/jquery.tablesorter.widgets.min', 'js');

$markets = market::byStatusAndType('stable', init('type'));
if (config::byKey('market::showBetaMarket') == 1) {
    $markets = array_merge($markets, market::byStatusAndType('beta', init('type')));
} else {
    if (config::byKey('market::apikey') != '' || (config::byKey('market::username') != '' && config::byKey('market::password') != '')) {
        foreach (market::byMe() as $myMarket) {
            if ($myMarket->getStatus() != 'stable' && $myMarket->getType() == init('type')) {
                $markets[] = $myMarket;
            }
        }
    }
}
$findMarket = array();
?>

<span class="pull-right"><input type="checkbox" id="bt_marketListDisplayInstallObject" /> Afficher les objets installés</span>
<table id="table_market" class="table table-bordered table-condensed tablesorter" data-sortlist="[[2,0]]">
    <thead>
        <tr>
            <th data-sorter="false"></th>
            <th>{{Certification}}</th>
            <th>{{Catégorie}}</th>
            <th>{{Nom}}</th>
            <th>{{Description}}</th>
            <th>{{Prix}}</th>
            <th>{{Statut}}</th>
            <th style="width: 110px;">{{Note}}</th>
            <th>{{Téléchargé}}</th>
            <th>{{Achat}}</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($markets as $market) {
            if (!isset($findMarket[$market->getId()])) {
                $update = update::byLogicalId($market->getLogicalId());
                $findMarket[$market->getId()] = true;
                $rating = $market->getRating();
                $install = "install";
                if (!is_object($update)) {
                    $install = "notInstall";
                }
                echo '<tr data-market_id="' . $market->getId() . '" data-market_type="' . $market->getType() . '" class="cursor ' . $install . '" style="height:60px;">';
                if ($market->getStatus('stable') == 1 && $market->getImg('stable')) {
                    $urlPath = config::byKey('market::address') . '/' . $market->getImg('stable');
                } else {
                    if ($market->getImg('beta')) {
                        $urlPath = config::byKey('market::address') . '/' . $market->getImg('beta');
                    }
                }
                echo '<td><center><img src="core/img/no_image.gif" data-original="' . $urlPath . '"  class="lazy" height="60" width="60" /></center></td>';
                echo '<td><center>';
                if ($market->getCertification() == 'Officiel') {
                    echo '<span class="label label-success" style="font-size : 1.4em;position : relative;top : 5px;">Officiel</span>';
                }
                if ($market->getCertification() == 'Recommandé') {
                    echo '<span class="label label-primary" style="font-size : 1.1em;position : relative;top : 5px;">Recommandé</span>';
                }
                echo '</center></td>';
                echo '<td>' . $market->getCategorie() . '</td>';
                echo '<td>' . $market->getName() . '</td>';
                echo '<td>' . $market->getDescription() . '</td>';
                echo '<td>';
                if ($market->getCost() > 0) {
                    echo '<span class="label label-primary" data-l1key="rating" style="font-size: 1em;">' . number_format($market->getCost(), 2) . ' €</span>';
                } else {
                    echo '<span class="label label-success" data-l1key="rating" style="font-size: 1em;">Gratuit</span>';
                }
                echo '</td>';
                echo '<td>';
                if ($market->getStatus('stable') == 1) {
                    echo '<span class="label label-success">';
                    echo '{{Stable}} : ';
                    echo $market->getDatetime('stable');
                    echo '</span><br/>';
                }
                if ($market->getStatus('beta') == 1) {
                    echo '<span class="label label-warning">';
                    echo '{{Beta}} : ';
                    echo $market->getDatetime('beta');
                    echo '</span><br/>';
                }
                if (is_object($update)) {
                    if ($update->getConfiguration('version', 'stable') == 'beta') {
                        echo '<span class="label label-danger">';
                        echo '{{Installé en BETA}}';
                        echo '</span>';
                    } else {
                        echo '<span class="label label-info">';
                        echo '{{Installé en STABLE}}';
                        echo '</span>';
                    }
                }

                echo '</td>';
                echo '<td><center>';
                echo '<input type="number" class="rating" data-max="5" data-empty-value="0" data-min="1" value="' . ceil($market->getRating()) . '" data-disabled="1" />';
                echo $market->getRating('total') . ' vote(s)';
                echo '</center></td>';
                echo '<td><center>' . $market->getDownloaded() . '</center></td>';
                echo '<td><center>' . $market->getBuyer() . '</center></td>';
                echo '</tr>';
            }
        }
        ?>
    </tbody>
</table>

<script>
    $(function () {
        $("img.lazy").lazyload({
            event: "sporty"
        });
        $("img.lazy").trigger("sporty");
        initTableSorter();

        setTimeout(function () {
            $('#table_market tbody tr.install').hide();
        }, 500);


        $('#bt_marketListDisplayInstallObject').on('change', function () {
            if ($(this).value() == 1) {
                $('#table_market tbody tr.install').show();
            } else {
                $('#table_market tbody tr.install').hide();
            }
        });


        $('#table_market tbody tr').on('click', function () {
            $('#md_modal2').dialog({title: "{{Market Jeedom}}"});
            $('#md_modal2').load('index.php?v=d&modal=market.display&type=' + $(this).attr('data-market_type') + '&id=' + $(this).attr('data-market_id')).dialog('open');
        });
    });
</script>