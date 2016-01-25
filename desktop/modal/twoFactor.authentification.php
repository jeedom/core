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
	<center>
		<img src="<?php echo $google2fa_url; ?>" /><br/>
	</center>
	<form class="form-horizontal">
		<fieldset>
			<div class="form-group">
				<label class="col-xs-4 control-label">{{Code de test}}</label>
				<div class="col-xs-3">
					<input class="form-control" id="in_testCode" />
				</div>
				<div class="col-xs-2">
					<a class="btn btn-default" id="bt_validateTestCode" >{{OK}}</a>
				</div>
			</div>
		</fieldset>
	</form>

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