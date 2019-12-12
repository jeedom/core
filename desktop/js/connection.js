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
                $('.veen').animateCss('shake');
            },
            success: function (data) {
            	if($('#in_login_username').val() == $('#in_login_password').val()){
            		$('#phrase_login_btn').html('{{Alerte de sécurité :<br/>Votre mot de passe doit être changé.}}');
            		$('#titre_login_btn').html('{{Information importante :}}');
	            	$('.veen .wrapper').addClass('move');
					$('.body').css('background','linear-gradient(360deg, rgba(147,204,1,0.6), rgba(147,204,1,1))');
					$('.login-btn').css('color','#000000');
					$(".veen .login-btn button").removeClass('active');
					$(this).addClass('active');
                }else{
	                $('.veen').animateCss('bounceOut', function(){
						$('.veen').hide();
						window.location.href = 'index.php?v=d';
					});
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
				user.hash = '';
					jeedom.user.saveProfils({
						profils: user,
						error: function (error) {
							$('#div_alert').showAlert({message: error.message, level: 'danger'});
							$('.veen').animateCss('shake');
						},
						success : function (){
							jeedom.config.load({
								configuration: 'market::username',
								error: function (error) {
									$('#div_alert').showAlert({message: error.message, level: 'danger'});
								},
								success: function (data) {
									if(data == '' || data == null){
										marketdemande();
									}else{
										goToIndex();
									}
								}
							});
						}
					});
	    		}
    		});
    	}else{
	    	$('#div_alert').showAlert({message: 'Les deux mots de passe ne sont pas identiques', level: 'danger'});
    	}
    });

    $('#bt_login_validate_market').on('click', function() {
    	var username = $('#in_login_username_market').val();
        var password = $('#in_login_password_market').val();
        var adress = 'https://jeedom.com/market';
    	jeedom.config.save({
	    	configuration: {'market::username': username},
	    	error: function (error) {
				$('#div_alert').showAlert({message: error.message, level: 'danger'});
			},
			success: function (data) {
				jeedom.config.save({
			    	configuration: {'market::password': password},
			    	error: function (error) {
						$('#div_alert').showAlert({message: error.message, level: 'danger'});
						$('.veen').animateCss('shake');
					},
					success: function (data) {
						jeedom.repo.test({
							repo: 'market',
							error: function (error) {
								$('#div_alert').showAlert({message: error.message, level: 'danger'});
								$('.veen').animateCss('shake');
							},
							success: function (data) {
								goToIndex();
							}
						});
					}
		    	});
			}
    	});
    });
    $('#bt_compte_market').on('click', function() {
    	window.open(
			'https://www.jeedom.com/market/index.php?v=d&p=register',
			'_blank'
		);
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
	var theme = 'core/themes/core2019_Light/desktop/core2019_Light.css';
	$('#bootstrap_theme_css').attr('href', theme);

	$(".veen .login-btn button").click(function(){
		$('.veen .wrapper').removeClass('move');
		$('.body').css('background','#ff4931');
		$(".veen .rgstr-btn button").removeClass('active');
		$(this).addClass('active');
	});
	window.setTimeout(function(){
		//$('.veen').removeClass('animated');
		$('.veen').removeClass('zoomIn');
		//$('.btn_help').removeClass('animated');
		$('.btn_help').removeClass('bounceInUp');
	}, 5000);
	window.setTimeout(function(){
		window.setInterval(function(){
			$('.btn_help').animateCss('shake');
			window.setTimeout(function(){
				$('.btn_help').removeClass('shake');
			},3000);
			},5000);
	}, 10000);

});

// Function //

var marketdemande = function(){
	$('.veen .wrapper').removeClass('move');
	$('#login').hide();
	$('#market').show();
	$('.img-responsive').attr('src', 'https://www.jeedom.com/market/core/img/logo-MARKET.svg');
	$('.img-responsive').width('100%');
}

var goToIndex = function(){
	$('.veen').animateCss('bounceOut', function(){
		$('.veen').hide();
		window.location.href = 'index.php?v=d';
	});
}
