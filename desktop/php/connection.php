<?php
include_file('3rdparty', 'animate/animate', 'css');
include_file('3rdparty', 'animate/animate', 'js');
?>
<div id="wrap">
	<div style="display: none;width : 100%" id="div_alert"></div>
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
					<h3>Login</h3>
					<div class="mail">
						<input type="text" id="in_login_username">
						<label>{{Nom d'utilisateur}}</label>
					</div>
					<div class="passwd">
						<input type="password" id="in_login_password">
						<label>{{Mot de passe}}</label>
					</div>
					<div class="passwd" id="div_twoFactorCode" style="display:none;">
						<input type="text" id="in_twoFactorCode">
						<label>{{Code à 2 facteurs}}</label>
					</div>
					<div class="checkbox">
						<input type="checkbox" style="top: -11px;" id="cb_storeConnection" /><label>{{Enregistrer cet ordinateur}}</label>
					</div>
					<div class="submit">
						<button class="dark btn-lg" id="bt_login_validate"><i class="fas fa-sign-in-alt" ></i> {{Connexion}}</button>
					</div>
					<div class="resetPassword">
						<a href="https://jeedom.github.io/documentation/howto/fr_FR/reset.password" target="_blank">{{J'ai perdu mon mot de passe}}</a>
					</div>
				</div>
				<div id="market" tabindex="502" class="form-group" style="display:none;">
					<h3>Je n'ai pas de compte Market</h3>
					<button class="dark btn-lg" id="bt_compte_market"><i class="fas fa-sign-in-alt" ></i> {{En créer un !}}</button>
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
						<button class="dark btn-lg" id="bt_login_validate_market"><i class="fas fa-sign-in-alt" ></i> {{Connecter Jeedom au Market}}</button>
					</div>
					<div class="resetPassword">
						<a href="https://www.jeedom.com/market/index.php?v=d&p=connection" target="_blank">{{J'ai perdu mon mot de passe}}</a>
					</div>
					<br/>
				</div>
				<div id="register" tabindex="500" class="form-group">
					<h3>CHANGER VOTRE MOT DE PASSE</h3>
					<div class="passwd">
						<input type="password" autocomplete="new-password" id="in_change_password">
						<label>Mot de passe</label>
					</div>
					<div class="passwd">
						<input type="password" autocomplete="new-password" id="in_change_passwordToo">
						<label>Mot de passe</label>
					</div>
					<div class="submit">
						<button class="dark btn-lg" id="bt_change_validate">C'est parti !</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<button class="btn_help animated bounceInUp" onclick="window.open('https://jeedom.github.io/documentation/premiers-pas/fr_FR/index#tocAnchor-1-3-1')">
		?
	</button>
</div>
<?php
if(config::byKey('product_connection_BG')){
	echo "<style>";
	echo "body {";
		echo "background-image: url(".config::byKey('product_connection_BG').") !important;";
		echo "background-position: center !important;";
		echo "background-repeat: no-repeat !important;";
		echo "background-size: cover !important;";
		echo "}";
		echo "</style>";
	}elseif(config::byKey('product_connection_color')){
		echo "<style>";
		echo "body { background:".config::byKey('product_connection_color')." !important;}";
		echo "</style>";
	}elseif(config::byKey('product_btn_login_color')){
	echo "<style>";
	echo "#bt_login_validate { background:".config::byKey('product_connection_color')." !important; border-color:".config::byKey('product_connection_color')." !important; }";
	echo "</style>";
	}
	if(stristr(config::byKey('product_name'), 'Jeedom') == false){
		echo "<style>";
		echo ".btn_help { display:none; }";
		echo "</style>";
	}
	include_file('desktop', 'connection', 'css');
	include_file('desktop', 'connection', 'js');
	
