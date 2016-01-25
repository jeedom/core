<div id="wrap">
    <div class="container">
        <center>
            <?php
if (init('error') == 1) {
	echo '<div class="alert alert-danger" style="width: 100%; padding: 7px 35px 7px 15px; margin-bottom: 5px; overflow: auto; max-height: 855px; z-index: 9999;">{{Nom d\'utilisateur ou mot de passe incorrect !}}</div>';
}
$getParams = "";
foreach ($_GET AS $var => $value) {
	if ($var != 'logout') {
		$getParams .= '&' . $var . '=' . $value;
	}
}
?>
          <div style="display: none;width : 100%" id="div_alert"></div>
          <img src="core/img/logo-jeedom-grand-nom-couleur-460x320.png" class="img-responsive" />

          <form method="post" name="login" action="index.php?v=d<?php echo htmlspecialchars($getParams); ?>" style="position : relative;top : -80px;" class="form-signin">
            <input type="text" name="connect" id="connect" hidden value="1" style="display: none;"/>
            <input class="input-block-level" type="text" name="login" id="login" placeholder="{{Nom d'utilisateur}}"/><br/>
            <br/><input class="input-block-level" type="password" id="mdp" name="mdp" placeholder="{{Mot de passe}}"/>
            <div id="div_twoFactorCode" style="display:none;">
                <input class="input-block-level" type="text" id="twoFactorCode" name="twoFactorCode" placeholder="{{Code à 2 facteurs}}"/>
            </div>
            <br/>
            <br/><input class="input-block-level" type="checkbox" id="registerDevice" name="registerDevice"/> {{Enregistrer cet ordinateur}}<br/>
            <button type="submit" class="btn-lg btn-primary btn-block" style="margin-top: 10px;"><i class="fa fa-sign-in"></i> {{Connexion}}</button>
        </form>
    </center>
</div>
<br/>
</div>
<script>
    $('#login').on('focusout',function(){
        jeedom.user.useTwoFactorAuthentification({
            login: $('#login').value(),
            error: function (error) {
                $('#div_alertTwoFactorAuthentification').showAlert({message: error.message, level: 'danger'});
            },
            success: function (data) {
                if(data == 1){
                    $('#div_twoFactorCode').show();
                }else{
                    $('#div_twoFactorCode').hide();
                }
            }
        });
    });
</script>