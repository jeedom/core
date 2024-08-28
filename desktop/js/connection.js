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

      document.getElementById('jeedom_theme_currentcss').href = 'core/themes/core2019_Light/desktop/core2019_Light.css'
      document.title = JEEDOM_PRODUCT_NAME + ' - Login'
    },
    askMarket: function() {
      document.querySelectorAll('.veen .wrapper').removeClass('move')
      document.getElementById('login').unseen()
      document.getElementById('market').seen()
      document.querySelector('.img-responsive').setAttribute('src', 'https://market.jeedom.com/core/img/logo-MARKET.svg')
      document.querySelector('.img-responsive').style.width = '100%'
    },
    goToIndex: function() {
      jeeFrontEnd.connection.animateCss(document.querySelector('.veen'), 'bounceOut', function() {
        document.querySelectorAll('.veen').hide()
        window.location.href = 'index.php?v=d'
      })
    },
    loginValidate: function(_event) {
      jeedom.user.login({
        username: document.getElementById('in_login_username').value,
        password: document.getElementById('in_login_password').value,
        twoFactorCode: document.getElementById('in_twoFactorCode').value,
        storeConnection: (document.getElementById('cb_storeConnection').checked) ? 1 : 0,
        error: function(error) {
          if (error.code == -32012) {
            document.getElementById('div_twoFactorCode').seen()
            return
          }
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
          jeeFrontEnd.connection.animateCss(document.querySelector('.veen'), 'shake')
        },
        success: function() {
          if (document.getElementById('in_login_username').value == document.getElementById('in_login_password').value) {
            document.getElementById('phrase_login_btn').innerHTML = '{{Alerte de sécurité}} :<br>{{Votre mot de passe doit être changé.}}'
            document.getElementById('titre_login_btn').innerHTML = '{{Information importante}} :'
            document.querySelectorAll('.veen .wrapper').addClass('move')
            document.body.style.background = 'linear-gradient(360deg, rgba(147,204,1,0.6), rgba(147,204,1,1))'
            document.querySelector('.login-btn').style.color = '#000000'
            document.querySelectorAll('.veen .login-btn button').removeClass('active')
            _event.target.addClass('active')
          } else {
            jeeFrontEnd.connection.animateCss(document.querySelector('.veen'), 'bounceOut', function() {
              document.querySelectorAll('.veen').unseen()
              if (isset(jeeP.deepUrl) && jeeP.deepUrl.includes('index.php?v=d')) {
                window.location.href = jeeP.deepUrl
              } else {
                window.location.href = 'index.php?v=d'
              }
            })
          }
        }
      })
    },
    changeValidate: function(_event) {
      if (document.getElementById('in_change_password').value != '' && document.getElementById('in_change_password').value == document.getElementById('in_change_passwordToo').value) {
        jeedom.user.get({
          error: function(error) {
            jeedomUtils.showAlert({
              message: error.message,
              level: 'danger'
            })
          },
          success: function(data) {
            var user = data
            user.password = document.getElementById('in_change_password').value
            user.hash = ''
            jeedom.user.saveProfils({
              profils: user,
              error: function(error) {
                jeedomUtils.showAlert({
                  message: error.message,
                  level: 'danger'
                })
                jeeFrontEnd.connection.animateCss(document.querySelector('.veen'), 'shake')
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
                      jeeFrontEnd.connection.askMarket()
                    } else {
                      jeeFrontEnd.connection.goToIndex()
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
    },
    animateCss: function(element, animationName, callback) {
      // console.log('animateCss:', element, animationName, callback)
      var animationEnd = (function(el) {
        var animations = {
          animation: 'animationend',
          OAnimation: 'oAnimationEnd',
          MozAnimation: 'mozAnimationEnd',
          WebkitAnimation: 'webkitAnimationEnd',
        }

        for (var t in animations) {
          if (el.style[t] !== undefined) {
            return animations[t]
          }
        }
      })(document.createElement('div'))

      element.addClass('animated ' + animationName)
      element.addEventListener(animationEnd, () => {
        element.removeClass('animated ' + animationName)
        if (typeof callback === 'function') callback()
      }, {
        once: true,
      })
      return element
    },
  }
}

jeeFrontEnd.connection.init()

//events delegation:
document.getElementById('wrap')?.addEventListener('click', function(event) {
  var _target = null
  if (_target = event.target.closest('#bt_login_validate')) {
    jeeP.loginValidate(event)
    return
  }

  if (_target = event.target.closest('#bt_change_validate')) {
    jeeP.changeValidate(event)
    return
  }

  if (_target = event.target.closest('#bt_login_validate_market')) {
    var username = document.getElementById('in_login_username_market').value
    var password = document.getElementById('in_login_password_market').value
    var adress = 'https://market.jeedom.com'
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
            jeeFrontEnd.connection.animateCss(document.querySelector('.veen'), 'shake')
          },
          success: function(data) {
            jeedom.repo.test({
              repo: 'market',
              error: function(error) {
                jeedomUtils.showAlert({
                  message: error.message,
                  level: 'danger'
                })
                jeeFrontEnd.connection.animateCss(document.querySelector('.veen'), 'shake')
              },
              success: function(data) {
                jeeFrontEnd.connection.goToIndex()
              }
            })
          }
        })
      }
    })
    return
  }

  if (_target = event.target.closest('#bt_ignore_market')) {
    window.location.reload()
    return
  }

  if (_target = event.target.closest('#bt_compte_market')) {
    window.open('https://market.jeedom.com/index.php?v=d&p=register', '_blank')
    return
  }

  if (_target = event.target.closest('a.bt_showPassConnection')) {
    event.stopPropagation();
    var _el = event.target.matches('a.bt_showPassConnection') ? event.target : event.target.parentNode;
    var input = _el.closest('.input-group').querySelector('input');
    
    if (input.getAttribute('type') === 'password') {
        input.setAttribute('type', 'text');
    } else {
        input.setAttribute('type', 'password');
    }

    var icon = _el.querySelector('.fas');
    if (icon.classList.contains('fa-eye-slash')) {
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    } else {
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    }
    return;
  }
})

document.getElementById('wrap').addEventListener('keypress', function(event) {
  if (event.which != 13) return
  var _target = null
  if (_target = event.target.closest('input')) {
    jeeFrontEnd.connection.loginValidate(event)
    return
  }

  if (_target = event.target.closest('#in_twoFactorCode')) {
    jeeFrontEnd.connection.loginValidate(event)
    return
  }

  if (_target = event.target.closest('#in_change_passwordToo')) {
    jeeFrontEnd.connection.changeValidate(event)
    return
  }
})


window.setTimeout(function() {
  document.querySelector('.veen').removeClass('zoomIn')
  document.querySelector('.btn_help').removeClass('bounceInUp')
}, 5000)

window.setTimeout(function() {
  window.setInterval(function() {
    jeeFrontEnd.connection.animateCss(document.querySelector('.btn_help'), 'shake')
    window.setTimeout(function() {
      document.querySelector('.btn_help').removeClass('shake')
    }, 3000)
  }, 5000)
}, 10000)
