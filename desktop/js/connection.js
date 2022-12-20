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

"use strict"

if (!jeeFrontEnd.connection) {
  jeeFrontEnd.connection = {
    init: function() {
      this.deepUrl = window.location.href
      if (this.deepUrl.includes('logout')) this.deepUrl = ''
      window.jeeP = this
    },
  }
}

jeeFrontEnd.connection.init()

$('#bt_login_validate').on('click', function() {
  jeedom.user.login({
    username: document.getElementById('in_login_username').value,
    password: document.getElementById('in_login_password').value,
    twoFactorCode: document.getElementById('in_twoFactorCode').value,
    storeConnection: (document.getElementById('cb_storeConnection').checked) ? 1 : 0,
    error: function(error) {
      if (error.code == -32012) {
        $('#div_twoFactorCode').show()
        return
      }
      jeedomUtils.showAlert({
        message: error.message,
        level: 'danger'
      })
      $('.veen').animateCss('shake')
    },
    success: function() {
      if ($('#in_login_username').val() == $('#in_login_password').val()) {
        $('#phrase_login_btn').html('{{Alerte de sécurité}} :<br>{{Votre mot de passe doit être changé.}}')
        $('#titre_login_btn').html('{{Information importante}} :')
        $('.veen .wrapper').addClass('move')
        $('.body').css('background', 'linear-gradient(360deg, rgba(147,204,1,0.6), rgba(147,204,1,1))')
        $('.login-btn').css('color', '#000000')
        $(".veen .login-btn button").removeClass('active')
        $(this).addClass('active')
      } else {
        $('.veen').animateCss('bounceOut', function() {
          $('.veen').hide()
          if (isset(jeeP.deepUrl) && jeeP.deepUrl.includes('index.php?v=d')) {
            window.location.href = jeeP.deepUrl
          } else {
            window.location.href = 'index.php?v=d'
          }
        })
      }
    }
  })
})

$('#bt_change_validate').on('click', function() {
  if ($('#in_change_password').val() != '' && $('#in_change_password').val() == $('#in_change_passwordToo').val()) {
    jeedom.user.get({
      error: function() {
        jeedomUtils.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function(data) {
        var user = data
        user.password = $('#in_change_password').val()
        user.hash = ''
        jeedom.user.saveProfils({
          profils: user,
          error: function(error) {
            jeedomUtils.showAlert({
              message: error.message,
              level: 'danger'
            })
            $('.veen').animateCss('shake')
          },
          success: function() {
            jeedom.config.load({
              configuration: 'market::username',
              error: function(error) {
                jeedomUtils.showAlert({
                  message: error.message,
                  level: 'danger'
                })
              },
              success: function(data) {
                if (data == '' || data == null) {
                  marketdemande()
                } else {
                  goToIndex()
                }
              }
            })
          }
        })
      }
    })
  } else {
    jeedomUtils.showAlert({
      message: '{{Le mot de passe est vide ou ne correspond pas à la confirmation.}}',
      level: 'danger'
    })
  }
})

$('#bt_login_validate_market').on('click', function() {
  var username = $('#in_login_username_market').val()
  var password = $('#in_login_password_market').val()
  var adress = 'https://jeedom.com/market'
  jeedom.config.save({
    configuration: {
      'market::username': username
    },
    error: function(error) {
      jeedomUtils.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(data) {
      jeedom.config.save({
        configuration: {
          'market::password': password
        },
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
          $('.veen').animateCss('shake')
        },
        success: function(data) {
          jeedom.repo.test({
            repo: 'market',
            error: function(error) {
              jeedomUtils.showAlert({
                message: error.message,
                level: 'danger'
              })
              $('.veen').animateCss('shake')
            },
            success: function(data) {
              goToIndex()
            }
          })
        }
      })
    }
  })
})

$('#bt_ignore_market').on('click', function() {
  window.location.reload()
})

$('#bt_compte_market').on('click', function() {
  window.open(
    'https://www.jeedom.com/market/index.php?v=d&p=register',
    '_blank'
  )
})

$('#in_login_password').keypress(function(event) {
  if (event.which == 13) {
    $('#bt_login_validate').trigger('click')
  }
})

$('#in_twoFactorCode').keypress(function(event) {
  if (event.which == 13) {
    $('#bt_login_validate').trigger('click')
  }
})

$('#in_change_passwordToo').keypress(function(event) {
  if (event.which == 13) {
    $('#bt_change_validate').trigger('click')
  }
})

document.getElementById('jeedom_theme_currentcss').href = 'core/themes/core2019_Light/desktop/core2019_Light.css'

document.title = JEEDOM_PRODUCT_NAME + ' - Login'

$(".veen .login-btn button").click(function() {
  $('.veen .wrapper').removeClass('move')
  $('.body').css('background', '#ff4931')
  $(".veen .rgstr-btn button").removeClass('active')
  $(this).addClass('active')
})

window.setTimeout(function() {
  $('.veen').removeClass('zoomIn')
  $('.btn_help').removeClass('bounceInUp')
}, 5000)

window.setTimeout(function() {
  window.setInterval(function() {
    $('.btn_help').animateCss('shake')
    window.setTimeout(function() {
      $('.btn_help').removeClass('shake')
    }, 3000)
  }, 5000)
}, 10000)

var marketdemande = function() {
  $('.veen .wrapper').removeClass('move')
  $('#login').hide()
  $('#market').show()
  $('.img-responsive').attr('src', 'https://www.jeedom.com/market/core/img/logo-MARKET.svg')
  $('.img-responsive').width('100%')
}

var goToIndex = function() {
  $('.veen').animateCss('bounceOut', function() {
    $('.veen').hide()
    window.location.href = 'index.php?v=d'
  })
}
