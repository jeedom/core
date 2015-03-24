<div id="div_alertComment"></div>
<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}

$market = market::byId(init('id'));
if (!is_object($market)) {
	throw new Exception('{{404  - Objet non trouvé sur le market}}');
}
sendVarToJS('market_id', init('id'));
echo '<div style="height : 80%; overflow : auto;">';
foreach ($market->getComment() as $comment) {
	if (count($comment) >= 3) {
		$alert = 'alert alert-info';
		if (isset($comment['apikey']) && $comment['apikey'] != '') {
			$alert = 'alert alert-success';
		}

		echo '<div class="' . $alert . '">';
		echo '<b>' . $comment['login'] . '</b> - ' . $comment['datetime'];
		if (isset($comment['apikey']) && $comment['apikey'] != '') {
			echo '<i class="fa fa-times pull-right tooltips bt_removeComment cursor" title="{{Supprimer mon commentaire}}" data-order="' . $comment['order'] . '"></i>';
		}
		echo '<br/>' . $comment['comment'];
		echo '</div>';
	}
}
echo '</div>';
if (config::byKey('market::apikey') != '' || (config::byKey('market::username') != '' && config::byKey('market::password') != '')) {
	echo '<textarea class="form-control" id="ta_comment"></textarea>';
	echo '<a class="btn btn-success pull-right" style="color : white;margin-top : 5px;" id="bt_sendComment"><i class="fa fa-comment"></i> {{Envoyer}}</a>';
}
?>


<script>
    $(function () {
        $('.bt_removeComment').on('click', function () {
          var order = $(this).attr('data-order');
          jeedom.market.setComment({
             id: market_id,
             order: order,
             error: function (error) {
                $('#div_alertComment').showAlert({message: error.message, level: 'danger'});
            },
 success: function (data) { // si l'appel a bien fonctionné
 reloadMarketComment();
}
});

      });


        $('#bt_sendComment').on('click', function () {
           jeedom.market.setComment({
             id: market_id,
             comment: $('#ta_comment').value(),
             error: function (error) {
                $('#div_alertComment').showAlert({message: error.message, level: 'danger'});
            },
 success: function (data) { // si l'appel a bien fonctionné
 reloadMarketComment();
}
});
       });

    });
</script>