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
				<div id="login" tabindex="503" class="form-group">
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
								<input type="text" class="inputPassword" id="in_login_password" autocomplete="off" autofill="off">
								<span class="input-group-btn">
									<a class="btn btn-default form-control bt_showPass roundedRight"><i class="fas fa-eye"></i></a>
								</span>
							</div>
						</div>
						<div class="passwd" id="div_twoFactorCode" style="display:none;">
							<label>{{Code à 2 facteurs}}</label>
							<input type="text" id="in_twoFactorCode" autocomplete="off">
						</div>
						<div class="checkbox">
							<input type="checkbox" id="cb_storeConnection" /><label>{{Enregistrer cet ordinateur}}</label>
						</div>
						<div class="submit center">
							<button class="dark btn-lg" id="bt_login_validate"><i class="fas fa-sign-in-alt"></i> {{Connexion}}</button>
						</div>
						<?php
						$mbState = config::byKey('mbState');
						if ($mbState == 0) {
							if (config::byKey('doc::base_url', 'core') != '') { ?>
								<div class="resetPassword center">
									<a href="<?php echo config::byKey('doc::base_url', 'core'); ?>/fr_FR/howto/reset.password" target="_blank">{{J'ai perdu mon mot de passe}}</a>
								</div>
						<?php }
						} ?>
					</form>
				</div>
				<div id="market" tabindex="502" class="form-group" style="display:none;">
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
				<div id="register" tabindex="500" class="form-group">
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
		<button class="btn_help animated bounceInUp" onclick="window.open('https://doc.jeedom.com/fr_FR/premiers-pas/#tocAnchor-4')">
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
