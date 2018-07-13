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
	<style type="text/css">
		/*.bodyLogin{
			background: linear-gradient(180deg, rgba(147,204,1,0.6), rgba(147,204,1,1));
			transition: all .5s;
			padding: 1px;
		}*/
		body{
			background: linear-gradient(180deg, rgba(147,204,1,0.6), rgba(147,204,1,1));
			transition: all .5s;
		}
		.veen{
			width: 70%;
			margin: 100px auto;
			background: rgba(250,250,250,1);
			min-height: 400px;
			display:table;
			position: relative;
			box-shadow: 0 0 4px rgba(0,0,0,.14), 0 4px 8px rgba(0,0,0,.28);
		}
		.veen > div {
			display: table-cell;
			vertical-align: middle;
			text-align: center;
			color: #fff;
		}
		.veen button{
			//background: transparent;
			background-image: linear-gradient(90deg, rgba(147,204,1,0.6), rgba(147,204,1,1));
			display: inline-block;
			padding: 10px 30px;
			border: 3px solid #fff;
			border-radius: 50px;
			background-clip: padding-box;
			position: relative;
			color: #FFF;
			//box-shadow: 0 0 4px rgba(0,0,0,.14), 0 4px 8px rgba(0,0,0,.28);
			transition: all .25s;
		}
		.veen button.dark{
			border-color: #93cc01;
			background: #93cc01;
		}
		.veen .move button.dark{
			border-color: #e0b722;
			background: #e0b722;
		}
		.veen .splits p{
			font-size: 15px;
		}
		.veen button:active{
			box-shadow: none;
		}
		.veen button:focus{
			outline: none;
		}
		.veen > .wrapper {
			position: absolute;
			width: 40%;
			height: 120%;
			top: -10%;
			left: 5%;
			background: #fff;
			box-shadow: 0 0 4px rgba(0,0,0,.14), 0 4px 8px rgba(0,0,0,.28);
			transition: all .5s;
			color: #303030;
			overflow: hidden;
		}
		.veen .wrapper > .form-group{
			padding: 15px 30px 30px;
			width: 100%;
			transition: all .5s;
			background: #fff;
		}
.veen .wrapper > .form-group:focus{
  outline: none;
}
		.veen .wrapper #login{
			padding-top: 20%;
      visibility: visible;
		}
		.veen .wrapper #register{
			transform: translateY(-80%) translateX(100%);
      visibility: hidden;
		}
		.veen .wrapper.move #register{
			transform: translateY(-80%) translateX(0%);
      visibility: visible;
		}
		.veen .wrapper.move #login{
			transform: translateX(-100%);
      visibility: hidden;
		}
		.veen .wrapper .checkbox{
			width: 100%;
			height: 10px;
		}
		.veen .wrapper > .form-group > div {
			position: relative;
			margin-bottom: 15px;
		}
		.veen .wrapper label{
			position: absolute;
			top: -7px;
			font-size: 12px;
			white-space: nowrap;
			background: #fff;
			text-align: left;
			left: 15px;
			padding: 0 5px;
			color: #999;
			pointer-events: none;
		}
		.veen .wrapper h3{
			margin-bottom: 25px;
		}
		.veen .wrapper input:not([type='checkbox']){
			height: 40px;
			padding: 5px 15px;
			width: 100%;
			border: solid 1px #999;
		}
.veen .wrapper input{
			height: 40px
			padding: 5px 15px;
			width: 100%;
			border: solid 1px #999;
		}
		.veen .wrapper input:focus{
			outline: none;
			border-color: #ff4931;
		}
		.veen > .wrapper.move{
			left: 45%;
		}
		.veen > .wrapper.move input:focus{
			border-color: #e0b722;
		}
		@media (max-width: 767px){
			.veen{
				padding: 5px;
				margin: 0;
				width: 100%;
				display: block;
			}
			.veen > .wrapper{
				position: relative;
				height: auto;
				top: 0;
				left: 0;
				width: 100%;
			}
			.veen > div{
				display: inline-block;
			}
			.splits{
				width: 50%;
				background: #fff;
				float: left;
			}
			.splits button{
				width: 100%;
				border-radius: 0;
				background: #505050;
				border: 0;
				opacity: .7;
			}
			.splits button.active{
				opacity: 1;
			}
			.splits button.active{
				opacity: 1;
				background: #ff4931;
			}
			.splits.rgstr-btn button.active{
				background: #e0b722;
			}
			.splits p{
				display: none;
			}
			.veen > .wrapper.move{
				left: 0%;
			}
		}

input:-webkit-autofill, textarea:-webkit-autofill, select:-webkit-autofill{
  box-shadow: inset 0 0 0 50px #fff
}
	</style>





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
            	if($('#in_login_username').val() == $('#in_login_password').val()){
            		$('#phrase_login_btn').html('{{Votre mot de passe doit être changé.<br/>Pour plus de sécurité.}}');
            		$('#titre_login_btn').html('{{Information importante :}}');
	            	$('.veen .wrapper').addClass('move');
					$('.body').css('background','linear-gradient(360deg, rgba(147,204,1,0.6), rgba(147,204,1,1))');
					$('.login-btn').css('color','#000000');
					$(".veen .login-btn button").removeClass('active');
					$(this).addClass('active');
            	}else{
                	window.location.href = 'index.php?v=d';
                }
            }
        });
    });

    $('#bt_change_validate').on('click', function() {
    	if($('#in_change_password').val() == $('#in_change_passwordToo').val()){
    		jeedom.user.get({
	    		error: function (data) {
		    		$('#div_alert').showAlert({message: error.message, level: 'danger'});
	    		},
	    		success: function (data){
		    		var user = data;
		    		user.password = $('#in_change_password').val();
					jeedom.user.saveProfils({
						profils: user,
						error: function (error) {
							$('#div_alert').showAlert({message: error.message, level: 'danger'});
						},
						success : function (){
							window.location.href = 'index.php?v=d';
						}
					})
	    		}
    		});
    	}else{
	    	$('#div_alert').showAlert({message: 'Les deux mots de passe ne sont pas identiques', level: 'danger'});
    	}
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
	$('#in_change_passwordToo').keypress(function(e) {
      if(e.which == 13) {
        $('#bt_change_validate').trigger('click');
	  }
	 });


// ADD //

$(document).ready(function(){
	$(".veen .login-btn button").click(function(){
		$('.veen .wrapper').removeClass('move');
		$('.body').css('background','#ff4931');
		$(".veen .rgstr-btn button").removeClass('active');
		$(this).addClass('active');
	});
});
</script>
