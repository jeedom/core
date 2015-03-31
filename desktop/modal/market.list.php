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
		'certification' => init('certification', null),
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

function displayWidgetName($_name) {
	$result = '';
	$name = explode('.', $_name);
	if (count($name) != 4) {
		return $name;
	}
	switch ($name[1]) {
		case 'info':
			$result .= '<i class="fa fa-eye fa-fw" title="Widget de type information"></i> ';
			break;
		case 'action':
			$result .= '<i class="fa fa-exclamation-circle fa-fw" title="Widget de type action"></i> ';
			break;
		default:
			$result .= $name[1];
			break;
	}
	switch ($name[2]) {
		case 'other':
			$result .= '<span class="label label-warning" style="text-shadow: none;">other</span> ';
			break;
		case 'color':
			$result .= '<span class="label label-success" style="text-shadow: none;">color</span> ';
			break;
		case 'slider':
			$result .= '<span class="label label-primary" style="text-shadow: none;">slider</span> ';
			break;
		case 'binary':
			$result .= '<span class="label label-info" style="text-shadow: none;">binary</span> ';
			break;
		case 'numeric':
			$result .= '<span class="label label-danger" style="text-shadow: none;">numeric</span> ';
			break;
		case 'string':
			$result .= '<span class="label label-default" style="text-shadow: none;">string</span> ';
			break;
		default:
			$result .= $name[2];
			break;
	}
	return $result .= $name[3];
}

function displayWidgetType($_name) {
	$result = '';
	$name = explode('.', $_name);
	if (count($name) != 4) {
		return "";
	}
	switch ($name[1]) {
		case 'info':
			$result .= '<i class="fa fa-eye fa-fw" title="Widget de type information" style="position: absolute;top: 31px; left: 15px;"></i> ';
			break;
		case 'action':
			$result .= '<i class="fa fa-exclamation-circle fa-fw" title="Widget de type action" style="position: absolute;top: 31px; left: 15px;"></i> ';
			break;
		default:
			$result .= "";
			break;
	}
	return $result;
}

function displayWidgetSubtype($_name) {
	$result = '';
	$name = explode('.', $_name);
	if (count($name) != 4) {
		return "";
	}
	switch ($name[2]) {
		case 'other':
			$result .= '<span class="label label-warning" style="text-shadow: none;position: absolute;top: 70px; left: -21px;transform: rotate(90deg);-webkit-transform: rotate(90deg);transform-origin: 38px 16px;-webkittransform-origin: 38px 16px;">other</span> ';
			break;
		case 'color':
			$result .= '<span class="label label-success" style="text-shadow: none;position: absolute;top: 70px; left: -21px;transform: rotate(90deg);-webkit-transform: rotate(90deg);transform-origin: 38px 16px;-webkittransform-origin: 38px 16px;">color</span> ';
			break;
		case 'slider':
			$result .= '<span class="label label-primary" style="text-shadow: none;position: absolute;top: 70px; left: -21px;transform: rotate(90deg);-webkit-transform: rotate(90deg);transform-origin: 38px 16px;-webkittransform-origin: 41px 16px;">slider</span> ';
			break;
		case 'binary':
			$result .= '<span class="label label-info" style="text-shadow: none;position: absolute;top: 70px; left: -21px;transform: rotate(90deg);-webkit-transform: rotate(90deg);transform-origin: 38px 16px;-webkittransform-origin: 44px 16px;">binary</span> ';
			break;
		case 'numeric':
			$result .= '<span class="label label-danger" style="text-shadow: none;position: absolute;top: 70px; left: -21px;transform: rotate(90deg);-webkit-transform: rotate(90deg);transform-origin: 38px 16px;-webkittransform-origin: 53px 16px;">numeric</span> ';
			break;
		case 'string':
			$result .= '<span class="label label-default" style="text-shadow: none;position: absolute;top: 70px; left: -21px;transform: rotate(90deg);-webkit-transform: rotate(90deg);transform-origin: 38px 16px;-webkittransform-origin: 41px 16px;">string</span> ';
			break;
		default:
			$result .= "";
			break;
	}
	return $result;
}
?>

<style type="text/css">
	.noPaddingLeft { padding-left: 0;}
	.noPaddingRight { padding-right: 0;}
	.noMarginBottom { margin-bottom: 0;}
	.noPaddingWell {
		padding-bottom: 0;
		padding-top: 0;
	}

	.market:hover{
		background-color : #F2F1EF !important;
	}
</style>


<div style="margin-bottom: 5px; margin-top : 5px; background-color: #e7e7e7">
	<form class="form-inline" role="form" onsubmit="return false;">
		<?php if (init('type', 'plugin') == 'plugin') {?>
		<div class="form-group">
			<div class="btn-group" >
				<a class="btn btn-default bt_pluginFilter <?php echo (init('cost') == 'free') ? 'btn-primary' : ''?>" data-href="<?php echo buildUrl('cost', 'free');?>">Gratuit</a>
				<a class="btn btn-default bt_pluginFilter <?php echo (init('cost') == 'paying') ? 'btn-primary' : ''?>" data-href="<?php echo buildUrl('cost', 'paying');?>">Payant</a>
				<a class="btn btn-default bt_pluginFilter" data-href="<?php echo buildUrl('cost', '');?>"><i class="fa fa-times"></i></a>
			</div>
		</div>
		<?php }?>
		<div class="form-group">
			<div class="btn-group" >
				<a class="btn btn-default bt_pluginFilter <?php echo (init('timeState') == 'newest') ? 'btn-primary' : ''?>" data-href="<?php echo buildUrl('timeState', 'newest');?>">Nouveau</a>
				<a class="btn btn-default bt_pluginFilter <?php echo (init('timeState') == 'popular') ? 'btn-primary' : ''?>" data-href="<?php echo buildUrl('timeState', 'popular');?>">Populaire</a>
				<a class="btn btn-default bt_pluginFilter" data-href="<?php echo buildUrl('timeState', '');?>"><i class="fa fa-times"></i></a>
			</div>
		</div>
		<div class="form-group">
			<div class="btn-group" >
				<a class="btn btn-default bt_pluginFilter <?php echo (init('certification') == 'Officiel') ? 'btn-primary' : ''?>" data-href="<?php echo buildUrl('certification', 'Officiel');?>">Officiel</a>
				<a class="btn btn-default bt_pluginFilter <?php echo (init('certification') == 'Recommandé') ? 'btn-primary' : ''?>" data-href="<?php echo buildUrl('certification', 'Recommandé');?>">Recommandé</a>
				<a class="btn btn-default bt_pluginFilter" data-href="<?php echo buildUrl('certification', '');?>"><i class="fa fa-times"></i></a>
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
			<select class="form-control" id="sel_categorie" data-href='<?php echo buildUrl('categorie', '');?>'>
				<?php if (init('type', 'plugin') == 'zwave') {?>
				<option value="">Toutes les marques</option>
				<?php } else {?>
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
		<input class="form-control" data-href='<?php echo buildUrl('name', '');?>' placeholder="Rechercher" id="in_search" value="<?php echo $name?>"/>
		<a class="btn btn-success" id="bt_search" data-href='<?php echo buildUrl('name', '');?>'><i class="fa fa-search"></i></a>
	</div>
</form>

<?php
if ($name != null && strpos($name, '$') !== false) {
	echo '<br/><a class="btn btn-default" id="bt_returnMarketList" data-href=' . buildUrl('name', '') . '><i class="fa fa-arrow-circle-left"></i> {{Retour}}</a>';
}
?>
</div>
<div style="padding : 5px;">
	<?php
$categorie = '';
$first = true;
$nCategory = 0;
$widget_found = array();
if ($type == 'widget') {
	foreach ($markets as $market) {
		$widget_id = explode('.', $market->getLogicalId());
		if (isset($widget_id[3])) {
			$widget_id = $widget_id[3];
		} else {
			$widget_id = $widget_id[0];
		}
		$widget_id = explode('_', $widget_id);
		$widget_id = $widget_id[0];
		if (trim($widget_id) == '') {
			$widget_id = $market->getLogicalId();
		}
		if (isset($widget_found[$widget_id])) {
			$widget_found[$widget_id]++;
		} else {
			$widget_found[$widget_id] = 1;
		}
	}
}

$widget = array();
foreach ($markets as $market) {
	if ($type == 'widget') {
		$widget_id = explode('.', $market->getLogicalId());
		$widget_id = $widget_id[3];
		$widget_id = explode('_', $widget_id);
		$widget_id = $widget_id[0];
		if (isset($widget[$widget_id]) && $name == null) {
			continue;
		}
		$widget[$widget_id] = true;
		if (trim($widget_id) == '') {
			$widget_id = $market->getLogicalId();
		}
		$widget_name = explode('.', $market->getName());
		$widget_name = $widget_name[3];
		$widget_name = explode('_', $widget_name);
		$widget_name = $widget_name[0];

		$widget[$widget_name] = true;
		if (trim($widget_name) == '') {
			$widget_name = $market->getName();
		}
	}
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

	if ($name == null && $type == 'widget' && isset($widget_found[$widget_id]) && $widget_found[$widget_id] > 1) {
		echo '<div class="marketMultiple cursor" data-href=' . buildUrl('name', '') . ' data-market_name="' . $widget_id . '$" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >';
	} else {
		$install = 'notInstall';
		if (!is_object($update)) {
			$install = 'install';
		}
		echo '<div class="market cursor ' . $install . '" data-market_id="' . $market->getId() . '" data-market_type="' . $market->getType() . '" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >';
	}

	if ($name == null && $type == 'widget' && isset($widget_found[$widget_id]) && $widget_found[$widget_id] > 1) {

	} else {
		if ($market->getType() == 'widget') {
			if (strpos($market->getName(), 'mobile.') !== false) {
				echo '<i class="fa fa-mobile" style="position: absolute;top: 15px;left: 21px;" title="{{Widget pour la version mobile}}"></i>';
			} else {
				echo '<i class="fa fa-desktop" style="position: absolute;top: 15px;left: 17px;" title="{{Widget pour la version bureau}}"></i>';
			}
		}
		if (is_object($update)) {
			echo '<i class="fa fa-check" style="position : absolute; right : 5px;"></i>';
		}
	}

	echo "<center>";
	$urlPath = config::byKey('market::address') . '/' . $market->getImg('icon');
	if ($market->getType() == 'widget') {
		echo '<img class="lazy" src="core/img/no_image.gif" data-original="' . $urlPath . '" height="105" width="95" style="margin-left: 20px;border: 1px solid #C5C5C5;border-radius:5px; padding: 3px" />';
	} else {
		echo '<img class="lazy" src="core/img/no_image.gif" data-original="' . $urlPath . '" height="105" width="95" />';
	}

	echo "</center>";

	if ($name == null && $type == 'widget' && isset($widget_found[$widget_id]) && $widget_found[$widget_id] > 1) {
		echo '<strong class="well col-sm-12 text-center" style="font-size : 1em;position:relative;padding: 5px; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;">' . $widget_id;
		echo '<span style="font-size : 1.1em;" class="badge pull-right tooltips" title="{{Nombre de widgets}}">' . $widget_found[$widget_id] . '</span>';
		echo '</strong>';
	} else {
		if ($market->getType() == 'widget') {
			echo displayWidgetType($market->getName()) . displayWidgetSubtype($market->getName());
			echo '<strong class="well col-sm-12 text-center" style="font-size : 1em;position:relative;padding: 5px; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;">' . $widget_name . '</strong>';
		} else {
			echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;">' . $market->getName() . '</span>';
		}
	}
	if ($market->getType() != 'widget') {
		if ($market->getCertification() == 'Officiel') {
			echo '<br/><span style="font-size : 0.85em;color:#7f8c8d;position:relative; top : 10px;">Officiel</span>';
		}
		if ($market->getCertification() == 'Recommandé') {
			echo '<br/><span style="font-size : 0.85em;color:#7f8c8d;position:relative; top : 10px;">Recommandé</span>';
		}
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

		$('#bt_returnMarketList').on('click', function () {
			$('#md_modal').load($(this).attr('data-href'));
		});

		$('.marketMultiple').on('click',function(){
			$('#md_modal').load($(this).attr('data-href') + '&name=' + encodeURI('.'+$(this).attr('data-market_name')));
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