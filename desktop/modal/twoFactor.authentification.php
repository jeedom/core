<?php
/* This file is part of Jeedom.
*
* Jeedom is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Jeedom is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
*/

use PragmaRX\Google2FAQRCode\Google2FA;
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
$google2fa_url = $google2fa->getQRCodeInline(
  'Jeedom',
  $_SESSION['user']->getLogin(),
  $_SESSION['user']->getOptions('twoFactorAuthentificationSecret')
);
?>

<div id="div_alertTwoFactorAuthentification"></div>
<div class="panel panel-warning">
  <div class="panel-heading"><h3 class="panel-title"><i class="fas fa-cogs"></i> {{Etape 1 : Installation sur le téléphone}}</h3></div>
  <div class="panel-body">
    {{La vérification en 2 étapes fournit une couche supplémentaire de protection pour votre compte}} <?php echo config::byKey('product_name'); ?>{{. Une fois la vérification en 2 étapes configurée, votre mot de passe sera nécessaire en plus d’un code de vérification unique pour vous connecter à}} <?php echo config::byKey('product_name'); ?>{{. Veuillez noter qu’un appareil mobile sera nécessaire pour générer des codes de vérification.}}
    <hr/>
    {{Veuillez installer une application d’authentification sur votre appareil mobile. Si vous n’en avez pas encore installé une,}} <?php echo config::byKey('product_name'); ?> {{prend en charge les applications d’authentification suivantes : Google Authenticator (Android, iOS, BlackBerry), Microsoft Authenticator (Android, iOS, Windows Phone).}}
    <hr/>
    {{A noter que la double authentification n'est nécessaire que pour les connexions externes, elle ne sera donc pas active sur une connexion locale.}}
  </div>

</div>

<div class="panel panel-primary">
  <div class="panel-heading"><h3 class="panel-title"><i class="fas fa-cogs"></i> {{Etape 2 : Configuration}}</h3></div>
  <div class="panel-body">
    <div class="center">
      {{Ouvrez et configurez l’application d’authentification en scannant le code QR ci-dessous.}}<br/>
      <img src="<?php echo $google2fa_url; ?>" /><br/>
      {{Vous pouvez aussi entrer manuellement le code suivant :}} <strong><?php echo $_SESSION['user']->getOptions('twoFactorAuthentificationSecret'); ?></strong>
    </div>
  </div>
</div>

<div class="panel panel-success">
  <div class="panel-heading"><h3 class="panel-title"><i class="fas fa-cogs"></i> {{Etape 3 : vérification}}</h3></div>
  <div class="panel-body">
    <form class="form-horizontal">
      <fieldset>
        <center>{{Veuillez entrer le code fourni pour vérification et activation de la double authentification}}</center><br/>
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
$('#bt_validateTestCode').on('click',function() {
  jeedom.user.validateTwoFactorCode({
    code: $('#in_testCode').value(),
    enableTwoFactorAuthentification : 1,
    error: function(error) {
      $('#div_alertTwoFactorAuthentification').showAlert({message: error.message, level: 'danger'})
    },
    success: function(data) {
      if (data) {
        $('#div_alertTwoFactorAuthentification').showAlert({message: '{{Configuration réussie}}', level: 'success'})
      } else {
        $('#div_alertTwoFactorAuthentification').showAlert({message: '{{Code invalide}}', level: 'danger'})
      }
    }
  })
})
</script>
