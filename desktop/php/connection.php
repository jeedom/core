<?php
$mbState = config::byKey('mbState');
?>
<div id="wrap">
	<div class="bodyLogin">
		<div class="veen animated zoomIn">
			<div class="login-btn splits">
				<h3 id="titre_login_btn"></h3>
				<p id="phrase_login_btn"></p>
			</div>
			<div class="rgstr-btn splits">
				<img class="img-responsive" src="<?php echo config::byKey('product_connection_image') ?>" style="display:block; margin: 10% 5% 10% auto; width:45%;">
			</div>
			<div class="wrapper">
				<div class="form-group" id="login">
					<form onsubmit="return false;">
						<h3>{{Connexion}}
							<?php
							if (config::byKey('display_name_login') == 1) {
								echo ' {{à}} ' . config::byKey('name');
							}
							?>
						</h3>
						<div class="mail">
							<label>{{Nom d'utilisateur}}</label>
							<input type="text" id="in_login_username">
						</div>
						<div class="passwd">
							<label>{{Mot de passe}}</label>
							<div class="input-group">
								<input type="password" class="roundedLeft" id="in_login_password">
								<span class="input-group-btn">
									<a class="btn btn-default form-control bt_showPassConnection roundedRight"><i class="fas fa-eye"></i></a>
								</span>
							</div>
						</div>
						<div class="passwd hidden" id="div_twoFactorCode">
							<label>{{Code à 2 facteurs}}</label>
							<input type="text" id="in_twoFactorCode" autocomplete="off">
						</div>
						<div class="checkbox">
							<input type="checkbox" id="cb_storeConnection" /><label>{{Enregistrer cet ordinateur}}</label>
						</div>
						<div class="submit center">
							<button class="dark btn-lg" id="bt_login_validate"><i class="fas fa-sign-in-alt"></i> {{Connexion}}</button>
						</div>
						<div class="submit center">
							<a class="btn" id="bt_go_to_lostpassword">{{J'ai perdu mon mot de passe}}</a>
						</div>
					</form>
				</div>
				<div class="form-group hidden" id="lostpassword">
					<form onsubmit="return false;">
						<h3>{{Mot de passe perdu}}</h3>
						<div class="mail">
							<label>{{Nom d'utilisateur}}</label>
							<input type="text" id="in_lostpassword_username">
						</div>
						<div class="submit center">
							<button class="dark btn-lg" id="bt_ask_password"><i class="fas fa-unlock-alt"></i> {{Recevoir un lien de réinitialisation}}</button>
						</div>
						<div class="submit center">
							<a class="btn" id="bt_go_to_login"><i class="fas fa-arrow-left"></i> {{Retour à la page de connexion}}</a>
						</div>
						<?php if ($mbState == 0 && config::byKey('doc::base_url', 'core') != '') { ?>
							<div class="resetPassword center">
								{{Autre méthode :}} <a href="<?= jeedom::getDocUrl('howto', 'reset.password') ?>" target="_blank">{{réinitialiser le mot de passe en SSH}}</a>
							</div>
						<?php } ?>
					</form>
				</div>
				<div class="form-group hidden" id="resetpassword">
					<form onsubmit="return false;">
						<h3>{{Réinitialiser le mot de passe}}</h3>
						<div class="passwd">
							<label>{{Nouveau mot de passe}}</label>
							<input type="password" autocomplete="new-password" id="in_reset_password">
						</div>
						<div class="passwd">
							<label>{{Confirmer le mot de passe}}</label>
							<input type="password" autocomplete="new-password" id="in_reset_passwordToo">
						</div>
						<div class="submit center">
							<button class="dark btn-lg" id="bt_reset_password"><i class="fas fa-check"></i> {{Changer le mot de passe}}</button>
						</div>
						<div class="submit center">
							<a class="btn" id="bt_go_to_login"><i class="fas fa-arrow-left"></i> {{Retour à la page de connexion}}</a>
						</div>
					</form>
				</div>
				<div class="form-group hidden" id="market">
					<h3>Je n'ai pas de compte Market</h3>
					<button class="dark btn-lg" id="bt_compte_market"><i class="fas fa-sign-in-alt"></i> {{En créer un !}}</button>
					<hr align=center size=2 width="70%">
					<h3>J'ai un compte market</h3>
					<div class="mail">
						<input type="text" id="in_login_username_market">
						<label>{{Nom d'utilisateur}}</label>
					</div>
					<div class="passwd">
						<input type="password" autocomplete="new-password" id="in_login_password_market">
						<label>{{Mot de passe}}</label>
					</div>
					<div class="submit">
						<button class="dark btn-lg" id="bt_login_validate_market"><i class="fas fa-sign-in-alt"></i> {{Connecter}} <?php echo ' ' . config::byKey('product_name') . ' '; ?> {{au Market}}</button>
					</div>
					<div class="submit">
						<button class="dark btn-lg" id="bt_ignore_market"><i class="fas fa-times"></i></i> {{Configurer plus tard}}</button>
					</div>
					<?php if ($mbState == 0) { ?>
						<div class="resetPassword">
							<a href="https://market.jeedom.com/index.php?v=d&p=connection" target="_blank">{{J'ai perdu mon mot de passe}}</a>
						</div>
					<?php } ?>
					<br />
				</div>
				<div class="form-group" id="register">
					<h3>{{CHANGER VOTRE MOT DE PASSE}}</h3>
					<div class="passwd">
						<input type="password" autocomplete="new-password" id="in_change_password">
						<label>{{Mot de passe}}</label>
					</div>
					<div class="passwd">
						<input type="password" autocomplete="new-password" id="in_change_passwordToo">
						<label>{{Mot de passe}}</label>
					</div>
					<div class="submit center">
						<button class="dark btn-lg" id="bt_change_validate">{{C'est parti !}}</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php if ($mbState == 0) { ?>
		<button class="btn_help animated bounceInUp" onclick="window.open('<?= jeedom::getDocUrl('premiers-pas') ?>')">
			?
		</button>
	<?php } ?>
</div>

<?php
if (config::byKey('product_connection_BG')) {
	echo "<style>";
	echo "body {";
	echo "background-image: url(" . config::byKey('product_connection_BG') . ") !important;";
	echo "background-position: center !important;";
	echo "background-repeat: no-repeat !important;";
	echo "background-size: cover !important;";
	echo "}";
	echo "</style>";
} elseif (config::byKey('product_connection_color')) {
	echo "<style>";
	echo "body { background:" . config::byKey('product_connection_color') . " !important;}";
	echo "</style>";
}
if (config::byKey('product_btn_login_color')) {
	echo "<style>";
	echo "#bt_login_validate { background:" . config::byKey('product_connection_color') . " !important; border-color:" . config::byKey('product_connection_color') . " !important; }";
	echo "</style>";
}
if (stristr(config::byKey('product_name'), 'Jeedom') == false) {
	echo "<style>";
	echo ".btn_help { display:none; }";
	echo "</style>";
}

include_file('3rdparty', 'animate/animate', 'css');
include_file('desktop', 'connection', 'css');
include_file('desktop', 'connection', 'js');
?>
