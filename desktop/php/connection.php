<?php
$productName = config::byKey('product_name');
// TODO: temporary fallback for white labels missing mbState=1, remove once they all set it
$isWhiteLabel = (config::byKey('mbState') == 1 || stripos($productName, 'Jeedom') === false);
?>

<div class="hidden" id="div_connection">
	<div class="connectionView hidden" id="login">
		<form onsubmit="return false;">
			<h3>
				<?php
				if (config::byKey('display_name_login') == 1) {
					$displayName = config::byKey('name', 'core', $productName);
				} else {
					$displayName = $productName;
				}
				echo sprintf(__('Connexion à %s', __FILE__), htmlspecialchars($displayName));
				?>
			</h3>
			<p>{{Renseignez votre nom d'utilisateur et votre mot de passe pour vous connecter.}}</p>
			<div>
				<label>{{Nom d'utilisateur}}</label>
				<input type="text" id="in_loginUsername">
			</div>
			<div>
				<label>{{Mot de passe}}</label>
				<div class="input-group">
					<input type="password" class="roundedLeft" id="in_loginPassword">
					<span class="input-group-btn">
						<a class="btn bt_showPassConnection roundedRight"><i class="fas fa-eye"></i></a>
					</span>
				</div>
			</div>
			<div class="hidden" id="div_twoFactorCode">
				<label>{{Code de double authentification}}</label>
				<input type="text" id="in_twoFactorCode" autocomplete="off">
			</div>

			<div class="checkbox center">
				<label><input type="checkbox" id="cb_storeConnection"> {{Se souvenir de cet appareil}}</label>
			</div>

			<div class="center">
				<?php // TODO: rename to bt_loginValidate once white labels stop targeting this id in their custom.css 
				?>
				<button id="bt_login_validate"><i class="fas fa-sign-in-alt"></i> {{Connexion}}</button>
			</div>

			<div class="center">
				<a class="btn bt_goToLostPassword"><i class="fas fa-key"></i> {{Mot de passe oublié ?}}</a>
			</div>
		</form>
	</div>

	<div class="connectionView hidden" id="lostPassword">
		<form onsubmit="return false;">
			<h3>{{Mot de passe oublié}}</h3>
			<p>{{Renseignez un nom d'utilisateur pour envoyer un lien de réinitialisation via la commande de notification de son profil.}}</p>
			<div>
				<label>{{Nom d'utilisateur}}</label>
				<input type="text" id="in_lostPasswordUsername">
			</div>
			<div class="center">
				<button id="bt_sendResetLink"><i class="fas fa-paper-plane"></i> {{Envoyer le lien}}</button>
			</div>
			<div class="center">
				<a class="btn bt_goToLogin"><i class="fas fa-arrow-left"></i> {{Retour à la connexion}}</a>
			</div>
			<?php if (!$isWhiteLabel) { ?>
				<div class="center">
					{{Autre méthode :}} <a href="<?= jeedom::getDocUrl('howto', 'reset.password') ?>" target="_blank">{{réinitialiser le mot de passe en SSH}}</a>
				</div>
			<?php } ?>
		</form>
	</div>

	<?php $resetLogin = (init('rpk') != '') ? user::loginFromResetToken(init('rpk')) : ''; ?>
	<div class="connectionView hidden" id="resetPassword">
		<form onsubmit="return false;">
			<h3>{{Réinitialisation du mot de passe}}</h3>
			<?php if ($resetLogin != '') { ?>
				<p><?= sprintf(__("Choisissez un nouveau mot de passe pour l'utilisateur %s.", __FILE__), '<strong>' . htmlspecialchars($resetLogin) . '</strong>') ?></p>
				<div>
					<label>{{Nouveau mot de passe}}</label>
					<input type="password" autocomplete="new-password" id="in_resetPassword">
				</div>
				<div>
					<label>{{Confirmer le mot de passe}}</label>
					<input type="password" autocomplete="new-password" id="in_resetPasswordConfirmation">
				</div>
				<div class="center">
					<button id="bt_resetPassword"><i class="fas fa-check"></i> {{Changer le mot de passe}}</button>
				</div>
			<?php } else { ?>
				<p>{{Ce lien de réinitialisation est invalide ou a expiré.}}</p>
				<div class="center">
					<button class="bt_goToLostPassword"><i class="fas fa-paper-plane"></i> {{Demander un nouveau lien}}</button>
				</div>
			<?php } ?>
			<div class="center">
				<a class="btn bt_goToLogin"><i class="fas fa-arrow-left"></i> {{Retour à la connexion}}</a>
			</div>
		</form>
	</div>

	<div class="connectionView hidden" id="changePassword">
		<form onsubmit="return false;">
			<h3>{{Changer votre mot de passe}}</h3>
			<p>{{Votre mot de passe est identique à votre nom d'utilisateur, veuillez en choisir un nouveau.}}</p>
			<div>
				<label>{{Nouveau mot de passe}}</label>
				<input type="password" autocomplete="new-password" id="in_changePassword">
			</div>
			<div>
				<label>{{Confirmer le mot de passe}}</label>
				<input type="password" autocomplete="new-password" id="in_changePasswordConfirmation">
			</div>
			<div class="center">
				<button id="bt_changePassword"><i class="fas fa-check"></i> {{Changer le mot de passe}}</button>
			</div>
		</form>
	</div>

	<?php
	$marketAddress = config::byKey('market::address');
	$marketEnable = (config::byKey('market::enable') == 1 && $marketAddress != '');
	$isJeedomMarket = (parse_url($marketAddress, PHP_URL_HOST) == 'market.jeedom.com');
	if ($marketEnable) {
	?>
		<div class="connectionView hidden" id="market" <?= ($isJeedomMarket) ? ' data-logo="https://market.jeedom.com/core/img/logo-MARKET.svg"' : '' ?>>
			<form onsubmit="return false;">
				<h3>{{Compte Market}}</h3>
				<p>{{Liez votre installation à un compte Market pour installer des plugins et profiter des services associés. Vous pourrez aussi le faire plus tard depuis la configuration.}}</p>
				<div>
					<label>{{Nom d'utilisateur}}</label>
					<input type="text" id="in_marketUsername">
				</div>
				<div>
					<label>{{Mot de passe}}</label>
					<div class="input-group">
						<input type="password" class="roundedLeft" id="in_marketPassword">
						<span class="input-group-btn">
							<a class="btn bt_showPassConnection roundedRight"><i class="fas fa-eye"></i></a>
						</span>
					</div>
				</div>
				<div class="center">
					<button id="bt_validateMarket"><i class="fas fa-link"></i> {{Lier au Market}}</button>
				</div>
				<div class="center">
					<?php if ($isJeedomMarket) { ?>
						<a class="btn" href="https://market.jeedom.com/index.php?v=d&p=connection" target="_blank"><i class="fas fa-key"></i> {{Mot de passe Market oublié ?}}</a>
						<a class="btn btn-info" href="https://market.jeedom.com/index.php?v=d&p=register" target="_blank"><i class="fas fa-user-plus"></i> {{Créer un compte Market}}</a>
					<?php } ?>
					<a class="btn btn-warning" id="bt_ignoreMarket"><i class="fas fa-times"></i> {{Ignorer}}</a>
				</div>
			</form>
		</div>
	<?php } ?>

	<div id="div_productImg">
		<img src="<?php echo config::byKey('product_connection_image') ?>" id="img_product">
	</div>
</div>

<?php if (!$isWhiteLabel) { ?>
	<a class="animated bounceInUp" href="<?= jeedom::getDocUrl('premiers-pas') ?>" target="_blank" title="{{Documentation}}" id="bt_help">?</a>
<?php } ?>

<?php
// if (config::byKey('product_connection_BG')) {
// 	echo "<style>";
// 	echo "body {";
// 	echo "background-image: url(" . config::byKey('product_connection_BG') . ") !important;";
// 	echo "background-position: center !important;";
// 	echo "background-repeat: no-repeat !important;";
// 	echo "background-size: cover !important;";
// 	echo "}";
// 	echo "</style>";
// } elseif (config::byKey('product_connection_color')) {
// 	echo "<style>";
// 	echo "body { background:" . config::byKey('product_connection_color') . " !important;}";
// 	echo "</style>";
// }
// if (config::byKey('product_btn_login_color')) {
// 	echo "<style>";
// 	echo "#bt_login_validate { background:" . config::byKey('product_connection_color') . " !important; border-color:" . config::byKey('product_connection_color') . " !important; }";
// 	echo "</style>";
// }
// if (stristr(config::byKey('product_name'), 'Jeedom') == false) {
// 	echo "<style>";
// 	echo ".btn_help { display:none; }";
// 	echo "</style>";
// }

include_file('3rdparty', 'animate/animate', 'css');
include_file('desktop', 'connection', 'css');
include_file('desktop', 'connection', 'js');
?>
