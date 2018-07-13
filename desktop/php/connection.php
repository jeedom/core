<div id="wrap">
    <div style="display: none;width : 100%" id="div_alert"></div>
    <div class="bodyLogin">
		<div class="veen">
			<div class="login-btn splits">
				<h3 id="titre_login_btn"></h3>
				<p id="phrase_login_btn"></p>
			</div>
			<div class="rgstr-btn splits">
				<img style="display:block; margin-left:auto; margin-right:5%; width:45%;" src="<?php echo config::byKey('product_connection_image') ?>" class="img-responsive" />
			</div>
			<div class="wrapper">
				<div id="login" tabindex="500" class="form-group">
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
						<button class="dark btn-lg" id="bt_login_validate"><i class="fa fa-sign-in" ></i> {{Connexion}}</button>
					</div>
					<div class="resetPassword">
					<a href="https://jeedom.github.io/documentation/howto/fr_FR/reset.password" target="_blank">{{J'ai perdu mon mot de passe}}</a>
					</div>
				</div>
				<div id="register" tabindex="502" class="form-group">
					<h3>CHANGER VOTRE MOT DE PASSE</h3>
					<div class="passwd">
						<input type="password" id="in_change_password">
						<label>Mot de passe</label>
					</div>
					<div class="passwd">
						<input type="password" id="in_change_passwordToo">
						<label>Mot de passe</label>
					</div>
					<div class="submit">
						<button class="dark btn-lg" id="bt_change_validate">C'est parti !</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
include_file('desktop', 'connection', 'css');
include_file('desktop', 'connection', 'js');
