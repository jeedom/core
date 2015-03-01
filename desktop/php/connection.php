<?php sendVarToJS('nodeJsKey', '');?>
<div id="wrap">
    <div class="container">
        <center>
            <img src="core/img/logo-jeedom-grand-nom-couleur.svg"/><br/><br/>
            <div style="display: none;width : 100%" id="div_alert"></div>
            <?php
if (init('error') == 1) {
	echo '<div class="alert alert-danger">{{Nom d\'utilisateur ou mot de passe incorrect !}}</div>';
}
$getParams = "";
foreach ($_GET AS $var => $value) {
	if ($var != 'logout') {
		$getParams .= '&' . $var . '=' . $value;
	}
}
?>
            <form method="post" name="login" action="index.php?v=d<?php print htmlspecialchars($getParams);?>" class="form-signin">
                <h2 class="form-signin-heading">{{Connectez-vous}}</h2>
                <input type="text" name="connect" id="connect" hidden value="1" style="display: none;"/>
                <br/><input class="input-block-level" type="text" name="login" id="login" placeholder="{{Nom d'utilisateur}}"/><br/>
                <br/><input class="input-block-level" type="password" id="mdp" name="mdp" placeholder="{{Mot de passe}}"/><br/>
                <br/><input class="input-block-level" type="checkbox" id="registerDevice" name="registerDevice"/> {{Enregistrer cet ordinateur}}<br/>
                <a class='cursor' id="bt_forgotPassword">{{J'ai oublié mon mot de passe}}</a>
                <button type="submit" class="btn-lg btn-primary btn-block" style="margin-top: 10px;"><i class="fa fa-sign-in"></i> {{Connexion}}</button>
            </form>
        </center>
    </div>
    <br/>
</div>

<script>
    $('#bt_forgotPassword').on('click', function () {
        if ($('#login').value() == '') {
            $('#div_alert').showAlert({message: '{{Vous devez d\'abord entrer votre nom d\'utilisateur}}', level: 'danger'});
            return;
        }
        bootbox.confirm('{{Etes-vous sûr de vouloir réinitialiser votre mot de passe ?}}', function (result) {
            if (result) {
                $.ajax({// fonction permettant de faire de l'ajax
                    type: "POST", // methode de transmission des données au fichier php
                    url: "core/ajax/user.ajax.php", // url du fichier php
                    data: {
                        action: 'forgotPassword',
                        login: $('#login').value(),
                    },
                    dataType: 'json',
                    error: function (request, status, error) {
                        handleAjaxError(request, status, error);
                    },
                    success: function (data) { // si l'appel a bien fonctionné
                        if (data.state != 'ok') {
                            $('#div_alert').showAlert({message: data.result, level: 'danger'});
                            return;
                        }
                        $('#div_alert').showAlert({message: '{{Un nouveau mot de passe vient de vous être envoyé}}', level: 'success'});
                    }
                });
            }
        });
    });
</script>