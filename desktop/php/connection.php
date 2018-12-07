<div id="wrap">
    <div class="container">
        <div style="display: none;width : 100%" id="div_alert"></div>
        <img style="display:block; margin-left:auto; margin-right:auto;" src="<?php echo config::byKey('product_connection_image') ?>" class="img-responsive" />
	    <div style="text-align:center; display : block; margin-left:auto; margin-right:auto; width:300px">
            <div class="form-group">
		        <input  class="form-control" type="text" id="in_login_username" placeholder="{{Nom d'utilisateur}}">
            </div>
            <div class="form-group">
		        <input  class="form-control" type="password" id="in_login_password" placeholder="{{Mot de passe}}">
            </div>
	        <div class = "form-group" id="div_twoFactorCode" style="display:none;">
	            <input class="form-control" type="text" id="in_twoFactorCode" placeholder="{{Code à 2 facteurs}}">
            </div>
            <div class="checkbox">
		        <label><input type="checkbox" id="cb_storeConnection">{{Enregistrer cet ordinateur}}</label>
            </div>
		    <button class="btn-lg btn-primary btn-block" id="bt_login_validate"><i class="fa fa-sign-in"></i> {{Connexion}}</button>
            <a href="https://jeedom.github.io/documentation/howto/fr_FR/reset.password" target="_blank">{{J'ai perdu mon mot de passe}}</a>
        </div>
    </div>
</div>
<script>
    $('#in_login_username').on('focusout change keypress',function(){
        jeedom.user.useTwoFactorAuthentification({
            login: $('#in_login_username').value(),
            error: function (error) {
                $('#div_alert').showAlert({message: error.message, level: 'danger'});
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

    $('#bt_login_validate').on('click', function() {
        jeedom.user.login({
            username: $('#in_login_username').val(),
            password: $('#in_login_password').val(),
            twoFactorCode: $('#in_twoFactorCode').val(),
            storeConnection: $('#cb_storeConnection').value(),
            error: function (error) {
                $('#div_alert').showAlert({message: error.message, level: 'danger'});
            },
            success: function (data) {
                window.location.href = 'index.php?v=d';
            }
        });
    });

    $('#in_login_password').keypress(function(e) {
      if(e.which == 13) {
         $('#bt_login_validate').trigger('click');
     }
 });

    $('#in_twoFactorCode').keypress(function(e) {
      if(e.which == 13) {
        $('#bt_login_validate').trigger('click');
    }
});
</script>
