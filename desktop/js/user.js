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
          var table = document.getElementById('table_user').querySelector('tbody')
          table.empty()
          var fragment = document.createDocumentFragment()
          var disable, userTR, node
          for (var i in data) {
            let newRow = table.insertRow(i)
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
                userTR += '<a class="btn btn-xs btn-danger pull-right bt_del_user roundedRight"><i class="far fa-trash-alt"></i> {{Supprimer}}</a>'
                userTR += '<a class="btn btn-xs btn-warning pull-right bt_change_mdp_user"><i class="fas fa-pencil-alt"></i> {{Mot de passe}}</a>'
              }
              userTR += '<a class="cursor bt_changeHash btn btn-warning btn-xs pull-right" title="{{Renouveler la clef API}}"><i class="fas fa-sync"></i> {{Régénérer API}}</a>'
              if (data[i].profils == 'restrict') {
                userTR += '<a class="btn btn-xs btn-default pull-right bt_copy_user_rights"><i class="fas fa-copy"></i> {{Copier les droits}}</a>'
              }
              userTR += '<a class="btn btn-xs btn-warning pull-right bt_manage_restrict_rights"><i class="fas fa-align-right"></i> {{Droits}}</a>'
              if (data[i].profils != 'restrict') {
                userTR = userTR.replace('bt_manage_restrict_rights', 'bt_manage_restrict_rights disabled')
              }
              userTR += '<a class="btn btn-xs btn-default pull-right bt_manage_profils roundedLeft"><i class="fas fa-briefcase"></i> {{Profils}}</a>'

              userTR += '</span></div>'
            }
            userTR += '</td>'
            userTR += '</tr>'

            newRow.innerHTML = userTR
            newRow.setJeeValues(data[i], '.userAttr')
          }
          document.querySelector('#table_user tbody').appendChild(fragment)

          var $tableDevices = $('#tableDevices')
          jeedomUtils.initTableSorter()
          $tableDevices[0].config.widgetOptions.resizable_widths = ['', '250px', '180px', '180px', '80px']
          $tableDevices.trigger('applyWidgets')
            .trigger('resizableReset')
            .trigger('sorton', [
              [
                [3, 1]
              ]
            ])

          jeeFrontEnd.modifyWithoutSave = false
          domUtils.hideLoading()
        }
      })
    },
  }
}

jeeFrontEnd.user.init()
jeeP.printUsers()

document.registerEvent('keydown', function(event) {
  if (jeedomUtils.getOpenedModal()) return
  if ((event.ctrlKey || event.metaKey) && event.which == 83) { //s
    event.preventDefault()
    $('#bt_saveUser').click()
  }
})


$('#div_administration').on({
  'change': function(event) {
    if ($(this).val() != 'restrict') {
      $(this).closest('tr').find('a.bt_manage_restrict_rights').addClass('disabled')
    } else {
      $(this).closest('tr').find('a.bt_manage_restrict_rights').removeClass('disabled')
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
})

$("#table_user").on('click', ".bt_del_user", function(event) {
  jeedomUtils.hideAlert();
  var user = {
    id: this.closest('tr').querySelector('.userAttr[data-l1key="id"]').innerHTML
  }
  var userName = this.closest('tr').querySelector('input[data-l1key="login"]').value
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
  var user = {
    id: this.closest('tr').querySelector('.userAttr[data-l1key="id"]').innerHTML,
    login: this.closest('tr').querySelector('input[data-l1key="login"]').value
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
  var user = {
    id: this.closest('tr').querySelector('.userAttr[data-l1key="id"]').innerHTML
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

$('#div_pageContainer').off('change', '.userAttr').on('change', '.userAttr:visible', function() {
  jeeFrontEnd.modifyWithoutSave = true
})

$('#div_pageContainer').off('change', '.configKey').on('change', '.configKey:visible', function() {
  jeeFrontEnd.modifyWithoutSave = true
})

$('#bt_supportAccess').on('click', function() {
  jeedom.user.supportAccess({
    enable: $(this).attr('data-enable'),
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

$('#table_user').off('change','.userAttr[data-l1key=options][data-l2key="api::mode"]').on('change','.userAttr[data-l1key=options][data-l2key="api::mode"]',function() {
  var tr = this.closest('tr')
  if (this.value == 'disable') {
    tr.querySelector('.userAttr[data-l1key=hash]').unseen()
  } else {
    tr.querySelector('.userAttr[data-l1key=hash]').seen()
  }
})

$('#table_user').on('click', '.bt_manage_restrict_rights', function() {
  jeeDialog.dialog({
    id: 'jee_modal',
    title: "{{Gestion des droits (Utilisateur limité)}}",
    contentUrl: 'index.php?v=d&modal=user.rights&id=' + this.closest('tr').querySelector('.userAttr[data-l1key="id"]').innerHTML
  })
})

$('#table_user').on('click', '.bt_manage_profils', function() {
  jeeDialog.dialog({
    id: 'jee_modal',
    title: "{{Gestion du profils}}",
    contentUrl: 'index.php?v=d&p=profils&ajax=1&user_id=' + this.closest('tr').querySelector('.userAttr[data-l1key="id"]').innerHTML
  })
})

$('#table_user').on('click', '.bt_disableTwoFactorAuthentification', function() {
  jeedom.user.removeTwoFactorCode({
    id: this.closest('tr').querySelector('.userAttr[data-l1key="id"]').innerHTML,
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

$('#table_user').on('click', '.bt_copy_user_rights', function() {
  let from = this.closest('tr').querySelector('.userAttr[data-l1key="id"]').innerHTML
  let select_list = []
  $('#table_user tbody tr').each(function() {
    if (this.querySelector('.userAttr[data-l1key="login"]').value == 'internal_report') {
      return;
    }
    if (this.querySelector('.userAttr[data-l1key="profils"]').value != 'restrict') {
      return;
    }
    if (this.querySelector('.userAttr[data-l1key="id"]').innerHTML == from) {
      return;
    }
    select_list.push({
      value: this.querySelector('.userAttr[data-l1key="id"]').innerHTML,
      text: this.querySelector('.userAttr[data-l1key="login"]').value
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
    title: "{{Vous voulez copier les droit de }}<strong> " + this.closest('tr').querySelector('.userAttr[data-l1key="login"]').value + "</strong> {{vers}} ?",
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



$('.bt_deleteSession').on('click', function() {
  var id = $(this).closest('tr').attr('data-id')
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

$('.bt_removeRegisterDevice').on('click', function() {
  var key = $(this).closest('tr').attr('data-key')
  var user_id = $(this).closest('tr').attr('data-user_id')
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

$('#bt_removeAllRegisterDevice').on('click', function() {
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

//Manage events outside parents delegations:

//Specials

/*Events delegations
*/