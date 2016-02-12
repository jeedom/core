<div id="wrap">
    <div class="container">
        <center>
          <div style="display: none;width : 100%" id="div_alert"></div>
          <img src="core/img/logo-jeedom-grand-nom-couleur-460x320.png" class="img-responsive" />
          <div style="width:300px">
              <input class="form-control" type="text" id="in_login_username" placeholder="{{Nom d'utilisateur}}"/><br/>
              <input class="form-control" type="password" id="in_login_password" placeholder="{{Mot de passe}}" style="margin-top:10px;" />
              <div id="div_twoFactorCode" style="display:none;">
                <input class="form-control" type="text" id="in_twoFactorCode" placeholder="{{Code à 2 facteurs}}" style="margin-top:10px;"/>
            </div>
            <input type="checkbox" id="cb_storeConnection" style="margin-top:10px;" /> {{Enregistrer cet ordinateur}}<br/>
            <button class="btn-lg btn-primary btn-block" id="bt_login_validate" style="margin-top: 10px;"><i class="fa fa-sign-in"></i> {{Connexion}}</button>
        </div>
    </center>
</div>
<br/>
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