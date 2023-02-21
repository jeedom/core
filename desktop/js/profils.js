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

if (!jeeFrontEnd.profils) {
  jeeFrontEnd.profils = {
    tableDevices: null,
    deviceDataTable: null,
    init: function() {
      /* Not used, also loaded as modal!
      window.jeeP = this
      */
      this.tableDevices = document.getElementById('securitytab')?.querySelector('#tableDevices')
      if (this.tableDevices != null) { //Not modal!
        this.deviceDataTable = new DataTable(this.tableDevices, {
          columns: [
            { select: 2, sort: "desc" }
          ],
          paging: false,
          searchable: true,
        })
      }
    },
    removeRegisterDevice: function(_key, _userId) {
      if (!isset(_userId)) _userId = ''
      jeedom.user.removeRegisterDevice({
        key: _key,
        user_id: _userId,
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          jeeFrontEnd.modifyWithoutSave = false
          window.location.reload()
        }
      })
    },
    deleteSession: function(_id) {
      jeedom.user.deleteSession({
        id: _id,
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          window.location.reload()
        }
      })
    },
  }
}

jeeFrontEnd.profils.init()

document.registerEvent('keydown', function(event) {
  if ((event.ctrlKey || event.metaKey) && event.which == 83) { //s
    event.preventDefault()
    document.getElementById("bt_saveProfils").click()
  }
})

jeedom.user.get({
  id: jeephp2js.profils_user_id,
  error: function(error) {
    jeedomUtils.showAlert({
      message: error.message,
      level: 'danger'
    })
  },
  success: function(data) {
    document.getElementById('div_userProfils').setJeeValues(data, '.userAttr')
    let pass = document.getElementById('in_passwordCheck')
    if (pass) pass.value = data.password
    jeeFrontEnd.modifyWithoutSave = false
  }
})


//Manage events outside parents delegations:
if (jeephp2js.profils_user_id == -1) {
  document.getElementById('bt_genUserKeyAPI')?.addEventListener('click', function(event) {
    var profil = document.getElementById('div_userProfils').getJeeValues('.userAttr')[0]
    profil.hash = ''
    jeedom.user.saveProfils({
      profils: profil,
      error: function(error) {
        jeedomUtils.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function() {
        jeedomUtils.showAlert({
          message: "{{Opération effectuée}}",
          level: 'success'
        })
        jeedom.user.get({
          error: function(error) {
            jeedomUtils.showAlert({
              message: error.message,
              level: 'danger'
            })
          },
          success: function(data) {
            document.getElementById('div_userProfils').setJeeValues(data, '.userAttr')
            jeeFrontEnd.modifyWithoutSave = false
          }
        })
      }
    })
  })

  document.getElementById('bt_removeAllRegisterDevice')?.addEventListener('click', function(event) {
    jeedom.user.removeRegisterDevice({
      key: '',
      error: function(error) {
        jeedomUtils.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function(data) {
        jeeFrontEnd.modifyWithoutSave = false
        window.location.reload()
      }
    })
  })
}

document.getElementById('bt_saveProfils')?.addEventListener('click', function(event) {
  jeedomUtils.hideAlert()
  var profil = document.getElementById('div_userProfils').getJeeValues('.userAttr')[0]
  if (jeephp2js.profils_user_id == -1) {
    if (profil.password != document.getElementById('in_passwordCheck').value) {
      jeedomUtils.showAlert({
        message: "{{Les deux mots de passe ne sont pas identiques}}",
        level: 'danger'
      })
      return
    }
    jeedom.user.saveProfils({
      profils: profil,
      error: function(error) {
        jeedomUtils.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function() {
        jeedomUtils.showAlert({
          message: "{{Sauvegarde effectuée}}",
          level: 'success'
        })
        jeedom.user.get({
          error: function(error) {
            jeedomUtils.showAlert({
              message: error.message,
              level: 'danger'
            })
          },
          success: function(data) {
            document.getElementById('div_userProfils').setJeeValues(data, '.userAttr')
            jeeFrontEnd.modifyWithoutSave = false
          }
        })
      }
    })
  } else {
    profil.id = jeephp2js.profils_user_id;
    jeedom.user.save({
      users: [profil],
      error: function(error) {
        jeedomUtils.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function() {
        jeedomUtils.showAlert({
          message: "{{Sauvegarde effectuée}}",
          level: 'success'
        })
        jeedom.user.get({
          error: function(error) {
            jeedomUtils.showAlert({
              message: error.message,
              level: 'danger'
            })
          },
          success: function(data) {
            jeeFrontEnd.modifyWithoutSave = false
          }
        })
      }
    })
  }
  return false
})

document.getElementById('bt_configureTwoFactorAuthentification')?.addEventListener('click', function(event) {
  var profil = document.getElementById('div_userProfils').getJeeValues('.userAttr')[0]
  jeeDialog.dialog({
    id: 'jee_modal',
    title: "{{Authentification 2 étapes}}",
    contentUrl: 'index.php?v=d&modal=twoFactor.authentification'
  })
})

/*Events delegations
*/
document.getElementById('div_pageContainer').addEventListener('click', function(event) {
  var _target = null
  if (jeephp2js.profils_user_id == -1) {
    if (_target = event.target.closest('.bt_removeRegisterDevice')) {
      jeeFrontEnd.profils.removeRegisterDevice(_target.closest('tr').getAttribute('data-key'))
      return
    }

    if (_target = event.target.closest('.bt_deleteSession')) {
      jeeFrontEnd.profils.deleteSession(_target.closest('tr').getAttribute('data-id'))
      return
    }
  }
})

document.getElementById('interfacetab').addEventListener('click', function(event) {
  var _target = null
  if (_target = event.target.closest('.bt_selectWarnMeCmd')) {
    jeedom.cmd.getSelectModal({
      cmd: {
        type: 'action',
        subType: 'message'
      }
    }, function(result) {
      document.querySelector('.userAttr[data-l1key="options"][data-l2key="notification::cmd"]').jeeValue(result.human)
      jeeFrontEnd.modifyWithoutSave = true
    })
    return
  }
})

document.getElementById('div_pageContainer').addEventListener('change', function(event) {
  if (event.target.matches('.userAttr')) {
    jeeFrontEnd.modifyWithoutSave = true
  }
})