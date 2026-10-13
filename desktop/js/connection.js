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
      document.getElementById('jeedom_theme_currentcss').href = 'core/themes/core2019_Light/desktop/core2019_Light.css?v=' + jeeFrontEnd.jeedomVersion

      window.jeeP = this
      this.container = document.getElementById('div_connection')
      this.resetPasswordToken = new URLSearchParams(window.location.search).get('rpk') || ''
      const url = window.location.href
      this.deepUrl = (url.includes('index.php?v=d') && !url.includes('logout') && this.resetPasswordToken == '') ? url : 'index.php?v=d'
      if (this.resetPasswordToken != '') {
        window.history.replaceState({}, document.title, 'index.php?v=d')
      }

      this.animateCss(this.container.seen(), 'zoomIn')
      this.showView((this.resetPasswordToken == '') ? 'login' : 'resetPassword')
    },
    currentView: function() {
      return this.container.querySelector('.connectionView:not(.hidden)')
    },
    showView: function(_id) {
      const previous = this.currentView()
      const view = document.getElementById(_id)
      jeedomUtils.hideAlert()
      if (!previous) {
        this.animateCss(view.seen(), 'bounceInUp', () => view.querySelector('input')?.focus())
        return
      }
      this.animateCss(previous, 'fadeOutLeft faster', () => {
        previous.unseen()
        previous.querySelectorAll('input:not([type=checkbox])').forEach(input => input.value = '')
        this.animateCss(view.seen(), 'fadeInLeft faster', () => view.querySelector('input')?.focus())
      })
    },
    goToIndex: function() {
      this.animateCss(this.container, 'bounceOut', () => {
        this.container.unseen()
        window.location.href = this.deepUrl
      })
    },
    showError: function(_message) {
      const current = this.currentView()
      jeedomUtils.showAlert({
        message: _message,
        level: 'danger',
        emptyBefore: true,
        attachTo: current
      })
      this.animateCss(current, 'shakeX')
    },
    checkInputs: function(_ids) {
      const values = {}
      if (_ids.username) {
        values.username = document.getElementById(_ids.username).value
        if (values.username.trim() == '') {
          this.showError("{{Veuillez saisir un nom d'utilisateur}}")
          return false
        }
      }
      if (_ids.password) {
        values.password = document.getElementById(_ids.password).value
        if (values.password == '') {
          this.showError('{{Veuillez saisir un mot de passe}}')
          return false
        }
      }
      if (_ids.confirmation && values.password != document.getElementById(_ids.confirmation).value) {
        this.showError('{{Les mots de passe ne correspondent pas}}')
        return false
      }
      if (_ids.twoFactorCode) {
        values.twoFactorCode = document.getElementById(_ids.twoFactorCode).value.trim()
        if (values.twoFactorCode == '') {
          this.showError('{{Veuillez saisir le code de double authentification}}')
          return false
        }
      }
      return values
    },
    loginValidate: function() {
      const ids = { username: 'in_loginUsername', password: 'in_loginPassword' }
      if (!document.getElementById('div_twoFactorCode').isHidden()) {
        ids.twoFactorCode = 'in_twoFactorCode'
      }
      const inputs = this.checkInputs(ids)
      if (!inputs) {
        return
      }
      jeedom.user.login({
        username: inputs.username,
        password: inputs.password,
        twoFactorCode: inputs.twoFactorCode,
        storeConnection: (document.getElementById('cb_storeConnection').checked) ? 1 : 0,
        error: function(error) {
          if (error.code == -32012) {
            document.getElementById('div_twoFactorCode').seen()
            jeedomUtils.showAlert({
              message: "{{Saisissez le code de votre application d'authentification}}",
              level: 'info',
              emptyBefore: true,
              attachTo: jeeP.currentView()
            })
            document.getElementById('in_twoFactorCode').focus()
            return
          }
          jeeP.showError(error.message)
        },
        success: function() {
          if (inputs.username == inputs.password) {
            jeeP.showView('changePassword')
          } else {
            jeeP.goToIndex()
          }
        }
      })
    },
    lostPasswordValidate: function() {
      const inputs = this.checkInputs({ username: 'in_lostPasswordUsername' })
      if (!inputs) {
        return
      }
      jeedom.user.sendResetPasswordLink({
        username: inputs.username,
        error: function(error) {
          jeeP.showError(error.message)
        },
        success: function() {
          jeeP.showView('login')
          jeedomUtils.showAlert({
            message: "{{Si ce compte existe et dispose d'une commande de notification, un lien de réinitialisation vient de lui être envoyé}}",
            level: 'success',
            attachTo: document.getElementById('login')
          })
        }
      })
    },
    resetPasswordValidate: function() {
      const inputs = this.checkInputs({ password: 'in_resetPassword', confirmation: 'in_resetPasswordConfirmation' })
      if (!inputs) {
        return
      }
      jeedom.user.resetPasswordFromToken({
        token: this.resetPasswordToken,
        newPassword: inputs.password,
        error: function(error) {
          jeeP.showError(error.message)
        },
        success: function(result) {
          if (result !== true) {
            jeeP.showError('{{Ce lien de réinitialisation est invalide ou a expiré}}')
            return
          }
          jeeP.showView('login')
          jeedomUtils.showAlert({
            message: '{{Votre mot de passe a bien été changé}}',
            level: 'success',
            attachTo: document.getElementById('login')
          })
        }
      })
    },
    changePasswordValidate: function() {
      const inputs = this.checkInputs({ password: 'in_changePassword', confirmation: 'in_changePasswordConfirmation' })
      if (!inputs) {
        return
      }
      jeedom.user.get({
        error: function(error) {
          jeeP.showError(error.message)
        },
        success: function(data) {
          var user = data
          user.password = inputs.password
          user.hash = ''
          jeedom.user.saveProfils({
            profils: user,
            error: function(error) {
              jeeP.showError(error.message)
            },
            success: function() {
              const market = document.getElementById('market')
              if (!market) {
                jeeP.goToIndex()
                return
              }
              jeedom.config.load({
                configuration: 'market::username',
                error: function(error) {
                  jeeP.showError(error.message)
                },
                success: function(data) {
                  if (data == '' || data == null) {
                    jeeP.showView('market')
                    if (market.dataset.logo) {
                      document.getElementById('img_product').setAttribute('src', market.dataset.logo)
                    }
                  } else {
                    jeeP.goToIndex()
                  }
                }
              })
            }
          })
        }
      })
    },
    marketValidate: function() {
      const inputs = this.checkInputs({ username: 'in_marketUsername', password: 'in_marketPassword' })
      if (!inputs) {
        return
      }
      jeedom.config.save({
        configuration: {
          'market::username': inputs.username,
          'market::password': inputs.password
        },
        error: function(error) {
          jeeP.showError(error.message)
        },
        success: function() {
          jeedom.repo.test({
            repo: 'market',
            error: function(error) {
              jeeP.showError(error.message)
            },
            success: function() {
              jeeP.goToIndex()
            }
          })
        }
      })
    },
    animateCss: function(_element, _animation, _callback) {
      _element.addClass('animated ' + _animation)
      _element.addEventListener('animationend', function onEnd(event) {
        if (event.target !== _element) {
          return
        }
        _element.removeEventListener('animationend', onEnd)
        _element.removeClass('animated ' + _animation)
        if (typeof _callback === 'function') {
          _callback()
        }
      })
      return _element
    }
  }
}

jeeFrontEnd.connection.init()

//events delegation:
jeeP.container.addEventListener('click', function(event) {
  if (event.target.closest('#bt_login_validate')) {
    jeeP.loginValidate()
    return
  }

  if (event.target.closest('.bt_goToLostPassword')) {
    jeeP.showView('lostPassword')
    return
  }

  if (event.target.closest('#bt_sendResetLink')) {
    jeeP.lostPasswordValidate()
    return
  }

  if (event.target.closest('.bt_goToLogin')) {
    jeeP.showView('login')
    return
  }

  if (event.target.closest('#bt_resetPassword')) {
    jeeP.resetPasswordValidate()
    return
  }

  if (event.target.closest('#bt_changePassword')) {
    jeeP.changePasswordValidate()
    return
  }

  if (event.target.closest('#bt_validateMarket')) {
    jeeP.marketValidate()
    return
  }

  if (event.target.closest('#bt_ignoreMarket')) {
    jeeP.goToIndex()
    return
  }

  if (event.target.closest('a.bt_showPassConnection')) {
    const button = event.target.closest('a.bt_showPassConnection')
    const input = button.closest('.input-group').querySelector('input')
    input.type = (input.type == 'password') ? 'text' : 'password'
    button.querySelector('.fas').classList.toggle('fa-eye')
    button.querySelector('.fas').classList.toggle('fa-eye-slash')
    return
  }
})
