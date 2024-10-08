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
    tableDevices: null,
    deviceDataTable: null,
    init: function() {
      window.jeeP = this
      this.tableDevices = document.getElementById('tableDevices')
      this.deviceDataTable = new DataTable(this.tableDevices, {
        columns: [
          { select: 3, sort: "desc" }
        ],
        paging: false,
        searchable: true,
      })

      jeeP.printUsers()
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
              userTR += '<div class="input-group pull-right" style="display:inline-flex">'
              userTR += '<span class="input-group-btn">'

              if (jeeFrontEnd.ldapEnable != '1') {
                userTR += '<a class="btn btn-xs btn-danger bt_del_user roundedRight"><i class="far fa-trash-alt"></i><span class="hidden-1280"> {{Supprimer}}</span></a>'
                userTR += '<a class="btn btn-xs btn-warning bt_change_mdp_user"><i class="fas fa-pencil-alt"></i><span class="hidden-1280"> {{Mot de passe}}</span></a>'
              }
              userTR += '<a class="cursor bt_changeHash btn btn-warning btn-xs" title="{{Renouveler la clef API}}"><i class="fas fa-sync"></i><span class="hidden-1280"> {{Régénérer API}}</span></a>'
              if (data[i].profils == 'restrict') {
                userTR += '<a class="btn btn-xs btn-default bt_copy_user_rights"><i class="fas fa-copy"></i><span class="hidden-1280"> {{Copier les droits}}</span></a>'
              }

              userTR += '<a class="btn btn-xs btn-warning bt_manage_restrict_rights"><i class="fas fa-align-right"></i><span class="hidden-1280"> {{Droits}}</span></a>'
              if (data[i].profils != 'restrict') {
                userTR = userTR.replace('bt_manage_restrict_rights', 'bt_manage_restrict_rights disabled')
              }
              userTR += '<a class="btn btn-xs btn-default bt_manage_profils roundedLeft"><i class="fas fa-briefcase"></i><span class="hidden-1280"> {{Profils}}</span></a>'

              userTR += '</span></div>'
            }
            userTR += '</td>'
            userTR += '</tr>'

            newRow.innerHTML = userTR
            newRow.setJeeValues(data[i], '.userAttr')
          }

          jeeFrontEnd.user.deviceDataTable.refresh()
          jeeFrontEnd.modifyWithoutSave = false
        }
      })
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
    saveUsers: function() {
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

//Register events on top of page container:
document.registerEvent('keydown', function(event) {
  if (jeedomUtils.getOpenedModal()) return
  if ((event.ctrlKey || event.metaKey) && event.which == 83) { //s
    event.preventDefault()
    jeeP.saveUsers()
  }
})



function handleSupportAccess(enable) {
  jeedom.user.supportAccess({
      enable: enable,
      error: function(error) {
          jeedomUtils.showAlert({
              message: error.message,
              level: 'danger'
          });
      },
      success: function(data) {
          jeeFrontEnd.modifyWithoutSave = false;
          jeedomUtils.loadPage('index.php?v=d&p=user');
      }
  });
}


/*Events delegations
*/
//div_administration
document.getElementById('div_administration').addEventListener('click', function(event) {
  var _target = null
  if (event.target.matches('.userAttr')) {
    jeeFrontEnd.modifyWithoutSave = true
  }

  if (_target = event.target.closest('#bt_addUser')) {
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
    return
  }

  if (_target = event.target.closest('#bt_saveUser')) {
    jeeP.saveUsers()
    return
  }

  if (_target = event.target.closest('#bt_supportAccess')) {
    var enable = _target.getAttribute('data-enable');
    if (enable == '1') {
        bootbox.confirm({
            message: "{{En activant l\'accès support, vous autorisez un technicien du support Jeedom à accéder à votre installation. Continuez ?}}",
            buttons: {
                confirm: {
                    label: "Oui",
                    className: "btn-success"
                },
                cancel: {
                    label: "Non",
                    className: "btn-danger"
                }
            },
            callback: function(result) {
                if (result) {
                    handleSupportAccess(enable);
                }
            }
        });
    } else {
        handleSupportAccess(enable);
    }
}


  if (_target = event.target.closest('#table_user .bt_del_user')) {
    jeedomUtils.hideAlert();
    var user = {
      id: _target.closest('tr').querySelector('.userAttr[data-l1key="id"]').innerHTML
    }
    var userName = _target.closest('tr').querySelector('input[data-l1key="login"]').value
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
    return
  }

  if (_target = event.target.closest('#table_user .bt_change_mdp_user')) {
    jeedomUtils.hideAlert()
    var user = {
      id: _target.closest('tr').querySelector('.userAttr[data-l1key="id"]').innerHTML,
      login: _target.closest('tr').querySelector('input[data-l1key="login"]').value
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
    return
  }

  if (_target = event.target.closest('#table_user .bt_changeHash')) {
    jeedomUtils.hideAlert()
    var user = {
      id: _target.closest('tr').querySelector('.userAttr[data-l1key="id"]').innerHTML
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
    return
  }

  if (_target = event.target.closest('#table_user .bt_manage_restrict_rights')) {
    jeeDialog.dialog({
      id: 'jee_modal',
      title: "{{Gestion des droits (Utilisateur limité)}}",
      contentUrl: 'index.php?v=d&modal=user.rights&id=' + _target.closest('tr').querySelector('.userAttr[data-l1key="id"]').innerHTML
    })
    return
  }

  if (_target = event.target.closest('#table_user .bt_manage_profils')) {
    let login = _target.closest('tr').querySelector('input[data-l1key="login"]')?.value
    jeeDialog.dialog({
      id: 'jee_modal',
      title: '{{Gestion du profil}} (' + login + ')',
      contentUrl: 'index.php?v=d&p=profils&ajax=1&user_id=' + _target.closest('tr').querySelector('.userAttr[data-l1key="id"]').innerHTML
    })
    return
  }

  if (_target = event.target.closest('#table_user .bt_disableTwoFactorAuthentification')) {
    jeedom.user.removeTwoFactorCode({
      id: _target.closest('tr').querySelector('.userAttr[data-l1key="id"]').innerHTML,
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
    return
  }

  if (_target = event.target.closest('#table_user .bt_copy_user_rights')) {
    let from = _target.closest('tr').querySelector('.userAttr[data-l1key="id"]').innerHTML
    let select_list = []
    document.querySelectorAll('#table_user tbody tr').forEach(_tr => {
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
      title: "{{Vous voulez copier les droit de }}<strong> " + _target.closest('tr').querySelector('.userAttr[data-l1key="login"]').value + "</strong> {{vers}} ?",
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
    })
    return
  }
})

document.getElementById('div_administration').addEventListener('change', function(event) {
  var _target = null
  if (_target = event.target.closest('select[data-l1key="profils"]')) {
    if (_target.value != 'restrict') {
      _target.closest('tr').querySelector('a.bt_manage_restrict_rights')?.addClass('disabled')
    } else {
      _target.closest('tr').querySelector('a.bt_manage_restrict_rights')?.removeClass('disabled')
    }
    return
  }

  if (_target = event.target.closest('.userAttr[data-l1key="options"][data-l2key="api::mode"]')) {
    if (_target.value == 'disable') {
      _target.closest('tr').querySelector('.userAttr[data-l1key="hash"]').unseen()
    } else {
      _target.closest('tr').querySelector('.userAttr[data-l1key="hash"]').seen()
    }
    return
  }
})


//tableSessions
document.getElementById('tableSessions').addEventListener('click', function(event) {
  var _target = null
  if (_target = event.target.closest('.bt_deleteSession')) {
    var id = _target.closest('tr').getAttribute('data-id')
    jeeFrontEnd.user.deleteSession(_target.closest('tr').getAttribute('data-id'))
    return
  }
})

//div_Devices
document.getElementById('div_Devices').addEventListener('click', function(event) {
  var _target = null
  if (_target = event.target.closest('#bt_removeAllRegisterDevice')) {
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
    return
  }

  if (_target = event.target.closest('.bt_removeRegisterDevice')) {
    var key = event.target.closest('tr').getAttribute('data-key')
    var user_id = event.target.closest('tr').getAttribute('data-user_id')
    jeeFrontEnd.user.removeRegisterDevice(key, user_id)
    return
  }
})

