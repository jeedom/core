<?php
use PragmaRX\Google2FA\Google2FA;
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$google2fa = new Google2FA();
@session_start();
$_SESSION['user']->refresh();
if ($_SESSION['user']->getOptions('twoFactorAuthentificationSecret') == '' || $_SESSION['user']->getOptions('twoFactorAuthentification', 0) == 0) {
	$_SESSION['user']->setOptions('twoFactorAuthentificationSecret', $google2fa->generateSecretKey());
	$_SESSION['user']->save();
}
@session_write_close();
$google2fa_url = $google2fa->getQRCodeGoogleUrl(
	'Jeedom',
	$_SESSION['user']->getLogin(),
	$_SESSION['user']->getOptions('twoFactorAuthentificationSecret')
);
?>
	<div id="div_alertTwoFactorAuthentification"></div>
	<div class="panel panel-warning">
		<div class="panel-heading"><h3 class="panel-title"><i class="fa fa-cogs"></i> {{Etape 1 : Installation sur le téléphone}}</h3></div>
		<div class="panel-body">
			{{La vérification en 2 étapes fournit une couche supplémentaire de protection pour votre compte Jeedom. Une fois la vérification en 2 étapes configurée, votre mot de passe sera nécessaire en plus d’un code de vérification unique pour vous connecter à Jeedom. Veuillez noter qu’un appareil mobile sera nécessaire pour générer des codes de vérification.}}
			<hr/>
			{{Veuillez installer une appli d’authentification sur votre appareil mobile. Si vous n’en avez pas encore installé une, Jeedom prend en charge les applis d’authentification suivantes : Google Authenticator (Android, iOS, BlackBerry), Authenticator (Windows Phone).}}
			<hr/>
			<strong>{{IMPORTANT : si votre Jeedom n'est plus à l'heure vous ne pourrez pas vous connecter du tout}}</strong>
		</div>

	</div>

	<div class="panel panel-primary">
		<div class="panel-heading"><h3 class="panel-title"><i class="fa fa-cogs"></i> {{Etape 2 : Configuration}}</h3></div>
		<div class="panel-body">
			<center>
				{{Ouvrez et configurez l’appli d’authentification en scannant le code QR ci-dessous.}}<br/>
				<img src="<?php echo $google2fa_url; ?>" /><br/>
				{{Vous pouvez aussi entrée manuellement le code suivant : }}<strong><?php echo $_SESSION['user']->getOptions('twoFactorAuthentificationSecret'); ?></strong>
			</center>
		</div>
	</div>

	<div class="panel panel-success">
		<div class="panel-heading"><h3 class="panel-title"><i class="fa fa-cogs"></i> {{Etape 3 : vérification}}</h3></div>
		<div class="panel-body">
			<form class="form-horizontal">
				<fieldset>
					<center>{{Veuillez entrée le code fournis pour verification et activation de la double authentification}}</center><br/>
					<div class="form-group">
						<label class="col-xs-4 control-label">{{Code de test}}</label>
						<div class="col-xs-3">
							<input class="form-control" id="in_testCode" />
						</div>
						<div class="col-xs-2">
							<a class="btn btn-success" id="bt_validateTestCode" >{{OK}}</a>
						</div>
					</div>
				</fieldset>
			</form>
		</div>
	</div>

	<script>
		$('#bt_validateTestCode').on('click',function(){
			jeedom.user.validateTwoFactorCode({
				code: $('#in_testCode').value(),
				enableTwoFactorAuthentification : 1,
				error: function (error) {
					$('#div_alertTwoFactorAuthentification').showAlert({message: error.message, level: 'danger'});
				},
				success: function (data) {
					if(data){
						$('#div_alertTwoFactorAuthentification').showAlert({message: '{{Configuration réussie}}', level: 'success'});
					}else{
						$('#div_alertTwoFactorAuthentification').showAlert({message: '{{Code invalide}}', level: 'danger'});
					}
				}
			});
		});
	</script>