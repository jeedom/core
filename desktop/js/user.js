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

printUsers()

document.onkeydown = function(event) {
  if (jeedomUtils.getOpenedModal()) return

  if ((event.ctrlKey || event.metaKey) && event.which == 83) { //s
    event.preventDefault()
    $('#bt_saveUser').click()
  }
}

var $tableDevices = $('#tableDevices')
$(function() {
  jeedomUtils.initTableSorter()
  $tableDevices[0].config.widgetOptions.resizable_widths = ['', '250px', '180px', '180px', '80px']
  $tableDevices.trigger('applyWidgets')
    .trigger('resizableReset')
    .trigger('sorton', [
      [
        [3, 1]
      ]
    ])
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
  $.hideAlert()
  $('#in_newUserLogin').value('')
  $('#in_newUserMdp').value('')
  $('#md_newUser').modal('show')
})

$("#bt_newUserSave").on('click', function(event) {
  $.hideAlert()
  var user = [{
    login: $('#in_newUserLogin').value(),
    password: $('#in_newUserMdp').value()
  }]
  jeedom.user.save({
    users: user,
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function() {
      printUsers()
      $.fn.showAlert({
        message: '{{Sauvegarde effectuée}}',
        level: 'success'
      })
      modifyWithoutSave = false
      $('#md_newUser').modal('hide')
    }
  })
})

$("#bt_saveUser").on('click', function(event) {
  var users =  $('#table_user tbody tr').getValues('.userAttr')

  if (!checkUsersLogins(users)) return

  jeedom.user.save({
    users: users,
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function() {
      printUsers()
      $.fn.showAlert({
        message: '{{Sauvegarde effectuée}}',
        level: 'success'
      })
      modifyWithoutSave = false
    }
  })
})

function checkUsersLogins(_users) {
  _users = _users.map(a => a.login)
  if (_users.includes('')) {
    $.fn.showAlert({
      message: '{{Le login d\'un utilisateur ne peut être vide !}}',
      level: 'danger'
    })
    return false
  }
  if (new Set(_users).size !== _users.length) {
    $.fn.showAlert({
    message: '{{Deux utilisateurs ne peuvent avoir le même login !}}',
      level: 'danger'
    })
    return false
  }
  return true
}

$("#table_user").on('click', ".bt_del_user", function(event) {
  $.hideAlert();
  var user = {
    id: $(this).closest('tr').find('.userAttr[data-l1key=id]').value()
  }
  var userName = $(this).closest('tr').find('span[data-l1key="login"]').text()
  bootbox.confirm('{{Vous allez supprimer l\'utilisateur :}}' + ' ' + userName, function(result) {
    if (result) {
      jeedom.user.remove({
        id: user.id,
        error: function(error) {
          $.fn.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function() {
          printUsers()
          $.fn.showAlert({
            message: '{{L\'utilisateur a bien été supprimé}}',
            level: 'success'
          })
        }
      })
    }
  })
})

$("#table_user").on('click', ".bt_change_mdp_user", function(event) {
  $.hideAlert()
  var user = {
    id: $(this).closest('tr').find('.userAttr[data-l1key=id]').value(),
    login: $(this).closest('tr').find('.userAttr[data-l1key=login]').value()
  }
  bootbox.prompt("{{Quel est le nouveau mot de passe ?}}", function(result) {
    if (result !== null) {
      user.password = result
      jeedom.user.save({
        users: [user],
        error: function(error) {
          $.fn.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function() {
          printUsers()
          $.fn.showAlert({
            message: '{{Sauvegarde effectuée}}',
            level: 'success'
          })
          modifyWithoutSave = false
        }
      })
    }
  })
})

$("#table_user").on('click', ".bt_changeHash", function(event) {
  $.hideAlert()
  var user = {
    id: $(this).closest('tr').find('.userAttr[data-l1key=id]').value()
  }
  bootbox.confirm("{{Êtes-vous sûr de vouloir changer la clef API de l\'utilisateur ?}}", function(result) {
    if (result) {
      user.hash = ''
      jeedom.user.save({
        users: [user],
        error: function(error) {
          $.fn.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function() {
          printUsers()
          $.fn.showAlert({
            message: '{{Modification effectuée}}',
            level: 'success'
          })
          modifyWithoutSave = false
        }
      })
    }
  })
})

$('#div_pageContainer').off('change', '.userAttr').on('change', '.userAttr:visible', function() {
  modifyWithoutSave = true
})

$('#div_pageContainer').off('change', '.configKey').on('change', '.configKey:visible', function() {
  modifyWithoutSave = true
})

$('#bt_supportAccess').on('click', function() {
  jeedom.user.supportAccess({
    enable: $(this).attr('data-enable'),
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(data) {
      modifyWithoutSave = false
      jeedomUtils.loadPage('index.php?v=d&p=user')
    }
  })
})

$('#table_user').off('change','.userAttr[data-l1key=options][data-l2key="api::mode"]').on('change','.userAttr[data-l1key=options][data-l2key="api::mode"]',function(){
  var tr = $(this).closest('tr');
  if($(this).value() == 'disable'){
    tr.find('.userAttr[data-l1key=hash]').hide()
  }else{
    tr.find('.userAttr[data-l1key=hash]').show()
  }
})

function printUsers() {
  $.showLoading()
  jeedom.user.all({
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(data) {
      $('#table_user tbody').empty()
      var tr = []
      var disable, userTR, result
      for (var i in data) {
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
        userTR += '<input class="userAttr form-control input-sm" data-l1key="hash" disabled />'
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

          if (ldapEnable != '1') {
            userTR += '<a class="btn btn-xs btn-danger pull-right bt_del_user roundedRight"><i class="far fa-trash-alt"></i> {{Supprimer}}</a>'
            userTR += '<a class="btn btn-xs btn-warning pull-right bt_change_mdp_user"><i class="fas fa-pencil-alt"></i> {{Mot de passe}}</a>'
          }
          userTR += '<a class="cursor bt_changeHash btn btn-warning btn-xs pull-right" title="{{Renouveler la clef API}}"><i class="fas fa-sync"></i> {{Régénérer API}}</a>'
          userTR += '<a class="btn btn-xs btn-warning pull-right bt_manage_restrict_rights"><i class="fas fa-align-right"></i> {{Droits}}</a>'
          if (data[i].profils != 'restrict') {
            userTR = userTR.replace('bt_manage_restrict_rights', 'bt_manage_restrict_rights disabled')
          }
          userTR += '<a class="btn btn-xs btn-default pull-right bt_manage_profils roundedLeft"><i class="fas fa-briefcase"></i> {{Profils}}</a>'

          userTR += '</span></div>'
        }
        userTR += '</td>'
        userTR += '</tr>'
        result = $(userTR)
        result.setValues(data[i], '.userAttr')
        tr.push(result)
      }
      $('#table_user tbody').append(tr)
      $('#table_user tbody .userAttr[data-l1key=options][data-l2key="api::mode"]').trigger('change')
      modifyWithoutSave = false
      $.hideLoading()
    }
  })
}

$('#table_user').on('click', '.bt_manage_restrict_rights', function() {
  $('#md_modal').dialog({
    title: "{{Gestion des droits (Utilisateur limité)}}"
  }).load('index.php?v=d&modal=user.rights&id=' + $(this).closest('tr').find('.userAttr[data-l1key=id]').value()).dialog('open')
})

$('#table_user').on('click', '.bt_manage_profils', function() {
  $('#md_modal').dialog({
    title: "{{Gestion du profils}}"
  }).load('index.php?v=d&p=profils&ajax=1&user_id=' + $(this).closest('tr').find('.userAttr[data-l1key=id]').value()).dialog('open')
})

$('#table_user').on('click', '.bt_disableTwoFactorAuthentification', function() {
  jeedom.user.removeTwoFactorCode({
    id: $(this).closest('tr').find('.userAttr[data-l1key=id]').value(),
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(data) {
      printUsers()
    }
  })
})

$('.bt_deleteSession').on('click', function() {
  var id = $(this).closest('tr').attr('data-id')
  jeedom.user.deleteSession({
    id: id,
    error: function(error) {
      $.fn.showAlert({
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
      $.fn.showAlert({
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
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(data) {
      modifyWithoutSave = false
      jeedomUtils.loadPage('index.php?v=d&p=user')
    }
  })
})