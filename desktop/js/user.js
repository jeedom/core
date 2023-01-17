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

if (!jeeFrontEnd.user) {
  jeeFrontEnd.user = {
    init: function() {
      window.jeeP = this
      jeeP.printUsers()

      jeedomUtils.initTableSorter()
      var tableDevices = document.getElementById('tableDevices')

      tableDevices.config.widgetOptions.resizable_widths = ['', '250px', '180px', '180px', '80px']
      tableDevices.triggerEvent('resizableReset')
      setTimeout(() => {
        tableDevices.querySelector('thead tr').children[3].triggerEvent('sort')
        tableDevices.querySelector('thead tr').children[3].triggerEvent('sort')
      }, 200)

      jeeFrontEnd.modifyWithoutSave = false
      domUtils.hideLoading()
    },
    checkUsersLogins: function(_users) {
      _users = _users.map(a => a.login)
      if (_users.includes('')) {
        jeedomUtils.showAlert({
          message: '{{Le login d\'un utilisateur ne peut être vide !}}',
          level: 'danger'
        })
        return false
      }
      if (new Set(_users).size !== _users.length) {
        jeedomUtils.showAlert({
        message: '{{Deux utilisateurs ne peuvent avoir le même login !}}',
          level: 'danger'
        })
        return false
      }
      return true
    },
    printUsers: function() {
      domUtils.showLoading()
      jeedom.user.all({
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          var table = document.getElementById('table_user')
          table.tBodies[0].empty()
          var disable, userTR, node
          for (var i in data) {
            let newRow = table.tBodies[0].insertRow(i)
            disable = ''
            if (data[i].login == 'internal_report' || data[i].login == 'jeedom_support') {
              disable = 'disabled'
            }
            userTR = '<tr><td class="login">'
            userTR += '<span class="userAttr" data-l1key="id" style="display:none;"/></span>'
            userTR += '<span><input class="' + disable + ' userAttr" data-l1key="login" /></span>'
            userTR += '</td>'
            userTR += '<td>'
            userTR += '<span><input type="checkbox" class="userAttr" data-l1key="enable" ' + disable + ' />{{Actif}}</span><br/>'
            userTR += '<span><input type="checkbox" class="userAttr" data-l1key="options" data-l2key="localOnly" ' + disable + ' />{{Local}}</span>'
            if (data[i].profils == 'admin') {
              userTR += '<br/><span><input type="checkbox" class="userAttr" data-l1key="options" data-l2key="doNotRotateHash" ' + disable + ' />{{Ne pas faire de rotation clef api}}</span>'
            }
            userTR += '</td>'
            userTR += '<td>'
            userTR += '<select class="userAttr form-control input-sm" data-l1key="profils" ' + disable + '>'
            userTR += '<option value="admin">{{Administrateur}}</option>'
            userTR += '<option value="user">{{Utilisateur}}</option>'
            userTR += '<option value="restrict">{{Utilisateur limité}}</option>'
            userTR += '</select>'
            userTR += '</td>'
            userTR += '<td>'
            if(disable != 'disabled'){
              userTR += '<select class="userAttr form-control input-sm" data-l1key="options" data-l2key="api::mode">'
              userTR += '<option value="enable">{{Activé}}</option>'
              userTR += '<option value="disable">{{Désactivé}}</option>'
              userTR += '</select>'
            }
            userTR += '<input class="userAttr form-control input-sm" data-l1key="hash" disabled  style="margin-top:4px;"/>'
            userTR += '</td>'
            userTR += '<td>'
            if (isset(data[i].options) && isset(data[i].options.twoFactorAuthentification) && data[i].options.twoFactorAuthentification == 1 && isset(data[i].options.twoFactorAuthentificationSecret) && data[i].options.twoFactorAuthentificationSecret != '') {
              userTR += '<span class="label label-success">{{OK}}</span>'
              userTR += ' <a class="btn btn-danger btn-xs bt_disableTwoFactorAuthentification"><i class="fas fa-times"></i> {{Désactiver}}</span>'
            } else {
              userTR += '<span class="label label-warning">{{NOK}}</span>'
            }
            userTR += '</td>'
            userTR += '<td>'
            userTR += '<span class="userAttr" data-l1key="options" data-l2key="lastConnection"></span>'
            userTR += '</td>'
            userTR += '<td>'
            if (disable == '') {
              userTR += '<div class="input-group pull-right">'
              userTR += '<span class="input-group-btn">'

              if (jeeFrontEnd.ldapEnable != '1') {
                userTR += '<a class="btn btn-xs btn-danger pull-right bt_del_user roundedRight"><i class="far fa-trash-alt"></i><span class="hidden-1280"> {{Supprimer}}</span></a>'
                userTR += '<a class="btn btn-xs btn-warning pull-right bt_change_mdp_user"><i class="fas fa-pencil-alt"></i><span class="hidden-1280"> {{Mot de passe}}</span></a>'
              }
              userTR += '<a class="cursor bt_changeHash btn btn-warning btn-xs pull-right" title="{{Renouveler la clef API}}"><i class="fas fa-sync"></i><span class="hidden-1280"> {{Régénérer API}}</span></a>'
              if (data[i].profils == 'restrict') {
                userTR += '<a class="btn btn-xs btn-default pull-right bt_copy_user_rights"><i class="fas fa-copy"></i><span class="hidden-1280"> {{Copier les droits}}</span></a>'
              }

              userTR += '<a class="btn btn-xs btn-warning pull-right bt_manage_restrict_rights"><i class="fas fa-align-right"></i><span class="hidden-1280"> {{Droits}}</span></a>'
              if (data[i].profils != 'restrict') {
                userTR = userTR.replace('bt_manage_restrict_rights', 'bt_manage_restrict_rights disabled')
              }
              userTR += '<a class="btn btn-xs btn-default pull-right bt_manage_profils roundedLeft"><i class="fas fa-briefcase"></i><span class="hidden-1280"> {{Profils}}</span></a>'

              userTR += '</span></div>'
            }
            userTR += '</td>'
            userTR += '</tr>'

            newRow.innerHTML = userTR
            newRow.setJeeValues(data[i], '.userAttr')
          }

          jeeFrontEnd.modifyWithoutSave = false
        }
      })
    },
    saveUser: function() {
      var users = document.getElementById('table_user').querySelectorAll('tbody tr').getJeeValues('.userAttr')
      if (!jeeP.checkUsersLogins(users)) return
      jeedom.user.save({
        users: users,
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function() {
          jeeP.printUsers()
          jeedomUtils.showAlert({
            message: '{{Sauvegarde effectuée}}',
            level: 'success'
          })
          jeeFrontEnd.modifyWithoutSave = false
        }
      })
    },
  }
}

jeeFrontEnd.user.init()

$('#div_administration').on({
  'change': function(event) {
    let me = event.target.closest('select')
    if (me.value != 'restrict') {
      me.closest('tr').querySelector('a.bt_manage_restrict_rights')?.addClass('disabled')
    } else {
      me.closest('tr').querySelector('a.bt_manage_restrict_rights')?.removeClass('disabled')
    }
  }
}, 'select[data-l1key="profils"]')

$("#bt_addUser").on('click', function(event) {
  jeedomUtils.hideAlert()
  let content = '<input class="promptAttr" data-l1key="newUserLogin" autocomplete="off" type="text" placeholder="{{Identifiant}}">'
  content += '<input class="promptAttr" data-l1key="newUserMdp" autocomplete="off" type="text" placeholder="{{Mot de passe}}">'
  jeeDialog.prompt({
    title: "{{Ajouter un utilisateur}}",
    message: content,
    inputType: false,
    callback: function(result) {
      if (result) {
        var user = [{
          login: result.newUserLogin,
          password: result.newUserMdp
        }]
        console.log('user: ', user)
        jeedom.user.save({
          users: user,
          error: function(error) {
            jeedomUtils.showAlert({
              message: error.message,
              level: 'danger'
            })
          },
          success: function() {
            jeeP.printUsers()
            jeedomUtils.showAlert({
              message: '{{Sauvegarde effectuée}}',
              level: 'success'
            })
            jeeFrontEnd.modifyWithoutSave = false
          }
        })
      }
    }
  })
})

$("#bt_saveUser").on('click', function(event) {
  jeeP.saveUser()
})

$("#table_user").on('click', ".bt_del_user", function(event) {
  let me = event.target.closest('.bt_del_user')
  jeedomUtils.hideAlert();
  var user = {
    id: me.closest('tr').querySelector('.userAttr[data-l1key="id"]').innerHTML
  }
  var userName = me.closest('tr').querySelector('input[data-l1key="login"]').value
  jeeDialog.confirm('{{Vous allez supprimer l\'utilisateur :}}' + ' ' + userName, function(result) {
    if (result) {
      jeedom.user.remove({
        id: user.id,
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function() {
          jeeP.printUsers()
          jeedomUtils.showAlert({
            message: '{{L\'utilisateur a bien été supprimé}}',
            level: 'success'
          })
        }
      })
    }
  })
})

$("#table_user").on('click', ".bt_change_mdp_user", function(event) {
  jeedomUtils.hideAlert()
  let me = event.target.closest('.bt_change_mdp_user')
  var user = {
    id: me.closest('tr').querySelector('.userAttr[data-l1key="id"]').innerHTML,
    login: me.closest('tr').querySelector('input[data-l1key="login"]').value
  }
  jeeDialog.prompt("{{Quel est le nouveau mot de passe ?}}", function(result) {
    if (result !== null) {
      user.password = result
      jeedom.user.save({
        users: [user],
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function() {
          jeeP.printUsers()
          jeedomUtils.showAlert({
            message: '{{Sauvegarde effectuée}}',
            level: 'success'
          })
          jeeFrontEnd.modifyWithoutSave = false
        }
      })
    }
  })
})

$("#table_user").on('click', ".bt_changeHash", function(event) {
  jeedomUtils.hideAlert()
  let me = event.target.closest('.bt_changeHash')
  var user = {
    id: me.closest('tr').querySelector('.userAttr[data-l1key="id"]').innerHTML
  }
  jeeDialog.confirm("{{Êtes-vous sûr de vouloir changer la clef API de l\'utilisateur ?}}", function(result) {
    if (result) {
      user.hash = ''
      jeedom.user.save({
        users: [user],
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function() {
          jeeP.printUsers()
          jeedomUtils.showAlert({
            message: '{{Modification effectuée}}',
            level: 'success'
          })
          jeeFrontEnd.modifyWithoutSave = false
        }
      })
    }
  })
})

$('#div_pageContainer').off('change', '.userAttr').on('change', '.userAttr:visible', function(event) {
  jeeFrontEnd.modifyWithoutSave = true
})

$('#div_pageContainer').off('change', '.configKey').on('change', '.configKey:visible', function(event) {
  jeeFrontEnd.modifyWithoutSave = true
})

$('#bt_supportAccess').on('click', function(event) {
  jeedom.user.supportAccess({
    enable: event.target.getAttribute('data-enable'),
    error: function(error) {
      jeedomUtils.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(data) {
      jeeFrontEnd.modifyWithoutSave = false
      jeedomUtils.loadPage('index.php?v=d&p=user')
    }
  })
})

$('#table_user').on('change', '.userAttr[data-l1key="options"][data-l2key="api::mode"]', function(event) {
  let me = event.target.closest('[data-l2key="api::mode"]')
  var tr = me.closest('tr')
  if (me.value == 'disable') {
    tr.querySelector('.userAttr[data-l1key="hash"]').unseen()
  } else {
    tr.querySelector('.userAttr[data-l1key="hash"]').seen()
  }
})

$('#table_user').on('click', '.bt_manage_restrict_rights', function(event) {
  let me = event.target.closest('.bt_manage_restrict_rights')
  jeeDialog.dialog({
    id: 'jee_modal',
    title: "{{Gestion des droits (Utilisateur limité)}}",
    contentUrl: 'index.php?v=d&modal=user.rights&id=' + me.closest('tr').querySelector('.userAttr[data-l1key="id"]').innerHTML
  })
})

$('#table_user').on('click', '.bt_manage_profils', function(event) {
  let me = event.target.closest('.bt_manage_profils')
  jeeDialog.dialog({
    id: 'jee_modal',
    title: "{{Gestion du profils}}",
    contentUrl: 'index.php?v=d&p=profils&ajax=1&user_id=' + me.closest('tr').querySelector('.userAttr[data-l1key="id"]').innerHTML
  })
})

$('#table_user').on('click', '.bt_disableTwoFactorAuthentification', function(event) {
  let me = event.target.closest('.bt_disableTwoFactorAuthentification')
  jeedom.user.removeTwoFactorCode({
    id: me.closest('tr').querySelector('.userAttr[data-l1key="id"]').innerHTML,
    error: function(error) {
      jeedomUtils.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(data) {
      jeeP.printUsers()
    }
  })
})

$('#table_user').on('click', '.bt_copy_user_rights', function(event) {
  let me = event.target.closest('.bt_copy_user_rights')
  let from = me.closest('tr').querySelector('.userAttr[data-l1key="id"]').innerHTML
  let select_list = []
  document.querySelectorAll('table_user tbody tr').forEach(_tr => {
    if (_tr.querySelector('.userAttr[data-l1key="login"]').value == 'internal_report') {
      return
    }
    if (_tr.querySelector('.userAttr[data-l1key="profils"]').value != 'restrict') {
      return
    }
    if (_tr.querySelector('.userAttr[data-l1key="id"]').innerHTML == from) {
      return
    }
    select_list.push({
      value: _tr.querySelector('.userAttr[data-l1key="id"]').innerHTML,
      text: _tr.querySelector('.userAttr[data-l1key="login"]').value
    })
  })
  if (select_list.length == 0) {
    jeedomUtils.showAlert({
      message: '{{Vous n\'avez aucun autre utilisateur à profil limité}}',
      level: 'warning'
    })
    return
  }
  jeeDialog.prompt({
    title: "{{Vous voulez copier les droit de }}<strong> " + me.closest('tr').querySelector('.userAttr[data-l1key="login"]').value + "</strong> {{vers}} ?",
    value: select_list[0].value,
    inputType: 'select',
    inputOptions: select_list,
    callback: function(to) {
      if (to == null) return
      jeedom.user.copyRights({
        to: to,
        from: from,
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          jeedomUtils.showAlert({
            message: '{{Droits copié avec succes}}',
            level: 'success'
          })
        }
      })
    }
  });
})

$('.bt_deleteSession').on('click', function(event) {
  var id = event.target.closest('tr').getAttribute('data-id')
  jeedom.user.deleteSession({
    id: id,
    error: function(error) {
      jeedomUtils.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(data) {
      jeedomUtils.loadPage('index.php?v=d&p=user')
    }
  })
})

$('.bt_removeRegisterDevice').on('click', function(event) {
  var key = event.target.closest('tr').getAttribute('data-key')
  var user_id = event.target.closest('tr').getAttribute('data-user_id')
  jeedom.user.removeRegisterDevice({
    key: key,
    user_id: user_id,
    error: function(error) {
      jeedomUtils.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(data) {
      jeedomUtils.loadPage('index.php?v=d&p=user')
    }
  })
})

$('#bt_removeAllRegisterDevice').on('click', function(event) {
  jeedom.user.removeRegisterDevice({
    error: function(error) {
      jeedomUtils.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(data) {
      jeeFrontEnd.modifyWithoutSave = false
      jeedomUtils.loadPage('index.php?v=d&p=user')
    }
  })
})

//Register events on top of page container:
document.registerEvent('keydown', function(event) {
  if (jeedomUtils.getOpenedModal()) return
  if ((event.ctrlKey || event.metaKey) && event.which == 83) { //s
    event.preventDefault()
    jeeP.saveUser()
  }
})

//Manage events outside parents delegations:

//Specials

/*Events delegations
*/