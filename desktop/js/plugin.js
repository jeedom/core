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


/*
This page show plugin list and can show each plugin configuration.
Can also be called in modale, triggering plugin button click for direct access to plugin configuration
*/

"use strict"

if (!jeeFrontEnd.plugin) {
  jeeFrontEnd.plugin = {
    init: function() {
      window.jeeP = this
    },
    displayPlugin: function(_pluginId) {
      jeedomUtils.hideAlert()
      if ($('#md_modal').is(':visible')) {
        var $container = $('#md_modal #div_confPlugin')
        var dom_container = document.querySelector('#md_modal #div_confPlugin')
        $('#md_modal #bt_returnToThumbnailDisplay').hide()
        $('#md_modal #div_resumePluginList').hide()
        $('#md_modal .li_plugin').removeClass('active')
        $('#md_modal .li_plugin[data-plugin_id=' + _pluginId + ']').addClass('active')
        $('#md_modal #div_confPlugin').show()
      } else {
        var $container = $('#div_confPlugin')
        var dom_container = document.getElementById('div_confPlugin')
        $('#bt_returnToThumbnailDisplay').show()
        $('#div_resumePluginList').hide()
        $('.li_plugin').removeClass('active')
        $('.li_plugin[data-plugin_id=' + _pluginId + ']').addClass('active')
        $('#div_confPlugin').show()
      }
      domUtils.showLoading()
      jeedom.plugin.get({
        id: _pluginId,
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          $container.find('#span_plugin_id').html(data.id)
          $container.find('#span_plugin_name').html(data.name)

          if (isset(data.update) && isset(data.update.localVersion)) {
            var localVer = data.update.localVersion
            if (localVer.length > 20) localVer = localVer.substring(0, 20) + '...'
            $container.find('#span_plugin_install_date').html(localVer)
          } else {
            $container.find('#span_plugin_install_date').html('')
          }

          $container.find('#span_plugin_license').html(data.license)
          if (data.installation.trim() == '' || data.installation.trim() == 'Aucune') {
            $container.find('#span_plugin_installation').closest('.panel').hide()
          } else {
            $container.find('#span_plugin_installation').html(data.installation).closest('.panel').show()
          }

          if (isset(data.update) && isset(data.update.configuration) && isset(data.update.configuration.version)) {
            $container.find('#span_plugin_install_version').html(data.update.configuration.version)
          } else {
            $container.find('#span_plugin_install_version').html('')
          }

          if (isset(data.author)) {
            $container.find('#span_plugin_author').html('<a href="https://market.jeedom.com/index.php?v=d&p=market&author=' + data.author + '">' + data.author + '</a>')
          } else {
            $container.find('#span_plugin_author').html('')
          }

          if (isset(data.category) && isset(jeephp2js.pluginCategories[data.category])) {
            $container.find('#span_plugin_category').html(jeephp2js.pluginCategories[data.category].name)
          } else {
            $container.find('#span_plugin_category').html('')
          }

          if (isset(data.source)) {
            $container.find('#span_plugin_source').html(data.source)
          } else {
            $container.find('#span_plugin_source').html('')
          }

          $container.find('#div_state .openPluginPage').attr("data-plugin_id", data.id)

          if (data.checkVersion != -1) {
            if (data.require <= jeeFrontEnd.jeedomVersion) {
              $container.find('#span_plugin_require').html('<span class="label label-success">' + data.require + '</span>')
            } else {
              $container.find('#span_plugin_require').html('<span class="label label-warning">' + data.require + '</span>')
            }
          } else {
            $container.find('#span_plugin_require').html('<span class="label label-danger">' + data.require + '</span>')
          }

          //dependencies and daemon divs:
          var $divPluginDependancy = $container.find('#div_plugin_dependancy')
          var $divPluginDeamon = $container.find('#div_plugin_deamon')
          $divPluginDependancy.closest('.panel').parent().addClass('col-md-6')
          $divPluginDeamon.closest('.panel').parent().addClass('col-md-6')
          if (data.hasDependency == 0 || data.activate != 1) {
            $divPluginDependancy.closest('.panel').hide()
            $divPluginDeamon.closest('.panel').parent().removeClass('col-md-6')
          } else {
            $divPluginDependancy.load('index.php?v=d&modal=plugin.dependancy&plugin_id=' + data.id).closest('.panel').show()
          }

          if (data.hasOwnDeamon == 0 || data.activate != 1) {
            $divPluginDeamon.closest('.panel').hide()
            $divPluginDependancy.closest('.panel').parent().removeClass('col-md-6')
          } else {
            $divPluginDeamon.load('index.php?v=d&modal=plugin.deamon&plugin_id=' + data.id).closest('.panel').show()
          }

          if ((data.hasDependency == 0 || data.activate != 1) && (data.hasOwnDeamon == 0 || data.activate != 1)) {
            $divPluginDependancy.closest('.panel').parent().hide()
            $divPluginDeamon.closest('.panel').parent().hide()
          } else {
            $divPluginDependancy.closest('.panel').parent().show()
            $divPluginDeamon.closest('.panel').parent().show()
          }

          //top right buttons:
          var $spanRightButton = $container.find('#span_right_button')


          $spanRightButton.empty().append('<a class="btn btn-sm roundedLeft bt_refreshPluginInfo"><i class="fas fa-sync"></i> {{Rafraichir}}</a>')
          if(jeedom.theme.mbState == 0) {
          if (data.update.configuration && data.update.configuration.version == 'beta') {
            if (isset(data.documentation_beta) && data.documentation_beta != '') {
              $spanRightButton.append('<a class="btn btn-primary btn-sm" target="_blank" href="' + data.documentation_beta + '"><i class="fas fa-book"></i> {{Documentation}}</a>')
            }
            if (isset(data.changelog_beta) && data.changelog_beta != '') {
              $spanRightButton.append('<a class="btn btn-primary btn-sm" target="_blank" href="' + data.changelog_beta + '"><i class="fas fa-book"></i> {{Changelog}}</a>')
            }
          } else {
            if (isset(data.documentation) && data.documentation != '') {
              $spanRightButton.append('<a class="btn btn-primary btn-sm" target="_blank" href="' + data.documentation + '"><i class="fas fa-book"></i> {{Documentation}}</a>')
            }
            if (isset(data.changelog) && data.changelog != '') {
              $spanRightButton.append('<a class="btn btn-primary btn-sm" target="_blank" href="' + data.changelog + '"><i class="fas fa-book"></i> {{Changelog}}</a>')
            }
          }

          if (isset(data.info.display) && data.info.display != '') {
            $spanRightButton.append('<a class="btn btn-primary btn-sm" target="_blank" href="' + data.info.display + '"><i class="fas fa-book"></i> {{Détails}}</a>')
          }
        }
          $spanRightButton.append('<a class="btn btn-danger btn-sm removePlugin roundedRight" data-market_logicalId="' + data.id + '"><i class="fas fa-trash"></i> {{Supprimer}}</a>');

          $container.find('#div_configPanel').hide()

          $container.find('#div_plugin_panel').empty()
          if (isset(data.display) && data.display != '') {
            var config_panel_html = '<div class="form-group">'
            config_panel_html += '<label class="col-lg-4 col-md-4 col-sm-4 col-xs-6 control-label">{{Afficher le panneau desktop}}</label>'
            config_panel_html += '<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">'
            config_panel_html += '<input type="checkbox" class="configKey tooltips" data-l1key="displayDesktopPanel" />'
            config_panel_html += '</div>'
            config_panel_html += '</div>'
            $container.find('#div_configPanel').show()
            $container.find('#div_plugin_panel').append(config_panel_html)
          }

          if (isset(data.mobile) && data.mobile != '') {
            var config_panel_html = '<div class="form-group">'
            config_panel_html += '<label class="col-lg-4 col-md-4 col-sm-4 col-xs-6 control-label">{{Afficher le panneau mobile}}</label>'
            config_panel_html += '<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">'
            config_panel_html += '<input type="checkbox" class="configKey tooltips" data-l1key="displayMobilePanel" />'
            config_panel_html += '</div>'
            config_panel_html += '</div>'
            $container.find('#div_configPanel').show()
            $container.find('#div_plugin_panel').append(config_panel_html)
          }

          $container.find('#div_plugin_functionality').empty()
          count = 0
          var config_panel_html = '<div class="row">'
          config_panel_html += '<div class="col-sm-6">'
          for (var i in data.functionality) {
            config_panel_html += '<div class="form-group">'
            config_panel_html += '<label class="col-lg-3 col-md-4 col-sm-4 col-xs-6 control-label">' + i + '</label>'
            config_panel_html += '<label class="col-lg-2 col-md-2 col-sm-3 col-xs-6">'
            if (data.functionality[i].exists) {
              config_panel_html += '<span class="label label-success">{{Oui}}</span>'
              config_panel_html += '</label>'
              if (data.functionality[i].controlable) {
                config_panel_html += '<label class="col-lg-3 col-md-3 col-sm-3 col-xs-6 control-label">{{Activer}}</label>'
                config_panel_html += '<label class="col-lg-2 col-md-2 col-sm-2 col-xs-6">'
                config_panel_html += '<input type="checkbox" class="configKey tooltips" data-l1key="functionality::' + i + '::enable" checked/>'
                config_panel_html += '</label>'
              }
            } else {
              config_panel_html += '<span class="label label-danger">{{Non}}</span>'
              config_panel_html += '</label>'
            }
            config_panel_html += '</div>'
            count++
            if (count == 5) {
              config_panel_html += '</div>'
              config_panel_html += '<div class="col-sm-6">'
            }
          }
          config_panel_html += '</div>'
          config_panel_html += '</div>'
          $container.find('#div_plugin_functionality').append(config_panel_html)

          $container.find('#div_plugin_toggleState').empty()
          if (data.checkVersion != -1) {
            var html = '<form class="form-horizontal"><fieldset>'
            html += '<div class="form-group">'
            html += '<label class="col-sm-2 control-label">{{Statut}}</label>'
            html += '<div class="col-sm-4">'
            if (data.activate == 1) {
              $container.find('#div_plugin_toggleState').closest('.panel').removeClass('panel-default panel-danger').addClass('panel-success')
              html += '<span class="label label-success">{{Actif}}</span>'
            } else {
              $container.find('#div_plugin_toggleState').closest('.panel').removeClass('panel-default panel-success').addClass('panel-danger')
              html += '<span class="label label-danger">{{Inactif}}</span>'
            }
            html += '</div>'
            html += '<label class="col-sm-2 control-label">{{Action}}</label>'
            html += '<div class="col-sm-4">'
            if (data.activate == 1) {
              html += '<a class="btn btn-danger btn-xs togglePlugin" data-state="0" data-plugin_id="' + data.id + '" style="position:relative;top:-2px;"><i class="fas fa-times"></i> {{Désactiver}}</a>'
            } else {
              html += '<a class="btn btn-success btn-xs togglePlugin" data-state="1" data-plugin_id="' + data.id + '" style="position:relative;top:-2px;"><i class="fas fa-check"></i> {{Activer}}</a>'
            }
            html += '</div>'
            html += '</div>'
            html += '</fieldset></form>'
            $container.find('#div_plugin_toggleState').html(html)
          } else {
            $container.find('#div_plugin_toggleState').closest('.panel').removeClass('panel-default panel-success').addClass('panel-danger')
            $container.find('#div_plugin_toggleState').html('{{Votre version de}} ' + JEEDOM_PRODUCT_NAME + ' {{ne permet pas d\'activer ce plugin}}')
          }
          var log_conf = ''
          for (var i in data.logs) {
            log_conf = '<form class="form-horizontal">'
            log_conf += '<div class="form-group">'
            log_conf += '<label class="col-sm-3 control-label">{{Niveau log}}</label>'
            log_conf += '<div class="col-sm-9">'
            log_conf += '<label class="radio-inline"><input type="radio" name="rd_logupdate' + data.id + '" class="configKey" data-l1key="log::level::' + data.id + '" data-l2key="1000" /> {{Aucun}}</label>'
            log_conf += '<label class="radio-inline"><input type="radio" name="rd_logupdate' + data.id + '" class="configKey" data-l1key="log::level::' + data.id + '" data-l2key="default" /> {{Defaut}}</label>'
            log_conf += '<label class="radio-inline"><input type="radio" name="rd_logupdate' + data.id + '" class="configKey" data-l1key="log::level::' + data.id + '" data-l2key="100" /> {{Debug}}</label>'
            log_conf += '<label class="radio-inline"><input type="radio" name="rd_logupdate' + data.id + '" class="configKey" data-l1key="log::level::' + data.id + '" data-l2key="200" /> {{Info}}</label>'
            log_conf += '<label class="radio-inline"><input type="radio" name="rd_logupdate' + data.id + '" class="configKey" data-l1key="log::level::' + data.id + '" data-l2key="300" /> {{Warning}}</label>'
            log_conf += '<label class="radio-inline"><input type="radio" name="rd_logupdate' + data.id + '" class="configKey" data-l1key="log::level::' + data.id + '" data-l2key="400" /> {{Error}}</label>'
            log_conf += '</div>'
            log_conf += '</div>'
            log_conf += '<div class="form-group">'
            log_conf += '<label class="col-sm-3 control-label">{{Logs}}</label>'
            log_conf += '<div class="col-sm-9">'
            for (var j in data.logs[i].log) {
              log_conf += '<a class="btn btn-info bt_plugin_conf_view_log" data-slaveId="' + data.logs[i].id + '" data-log="' + data.logs[i].log[j] + '"><i class="fas fa-paperclip"></i>  ' + data.logs[i].log[j].charAt(0).toUpperCase() + data.logs[i].log[j].slice(1) + '</a> '
            }
            log_conf += '</div>'
            log_conf += '</div>'
            log_conf += '</form>'
          }

          log_conf += '<form class="form-horizontal">'
          log_conf += '<div class="form-group">'
          log_conf += '<label class="col-sm-3 control-label">{{Heartbeat (min)}}</label>'
          log_conf += '<div class="col-sm-2">'
          log_conf += '<input class="configKey form-control" data-l1key="heartbeat::delay::' + data.id + '" />'
          log_conf += '</div>'
          if (data.hasOwnDeamon) {
            log_conf += '<label class="col-sm-3 control-label">{{Redémarrer démon}}</label>'
            log_conf += '<div class="col-sm-2">'
            log_conf += '<input type="checkbox" class="configKey" data-l1key="heartbeat::restartDeamon::' + data.id + '" />'
            log_conf += '</div>'
          }
          log_conf += '</div>'
          log_conf += '</form>'

          $container.find('#div_plugin_log').empty()
          $container.find('#div_plugin_log').append(log_conf)

          var $divPluginConfiguration = $container.find('#div_plugin_configuration')
          var dom_divPluginConfiguration = dom_container.querySelector('#div_plugin_configuration')
          $divPluginConfiguration.empty()
          if (data.checkVersion != -1) {
            if (data.configurationPath != '' && data.activate == 1) {
              $divPluginConfiguration.load('index.php?v=d&plugin=' + data.id + '&configure=1', function() {
                if ($divPluginConfiguration.html().trim() == '') {
                  $divPluginConfiguration.closest('.panel').hide()
                  return
                } else {
                  $divPluginConfiguration.closest('.panel').show()
                }
                jeedom.config.load({
                  configuration: dom_divPluginConfiguration.getJeeValues('.configKey')[0],
                  plugin: $container.find('#span_plugin_id').text(),
                  error: function(error) {
                    jeedomUtils.showAlert({
                      message: error.message,
                      level: 'danger'
                    })
                  },
                  success: function(data) {
                    dom_divPluginConfiguration.setJeeValues(data, '.configKey')
                    dom_divPluginConfiguration.parentNode.seen()
                    jeeFrontEnd.modifyWithoutSave = false
                  }
                })
              })
            } else {
              dom_divPluginConfiguration.closest('.panel').unseen()
            }
            jeedom.config.load({
              configuration: dom_container.querySelector('#div_plugin_panel').getJeeValues('.configKey')[0],
              plugin: dom_container.querySelector('#span_plugin_id').innerHTML,
              error: function(error) {
                jeedomUtils.showAlert({
                  message: error.message,
                  level: 'danger'
                })
              },
              success: function(data) {
                dom_container.querySelector('#div_plugin_panel').setJeeValues(data, '.configKey')
                jeeFrontEnd.modifyWithoutSave = false
              }
            })
            jeedom.config.load({
              configuration: dom_container.querySelector('#div_plugin_functionality').getJeeValues('.configKey')[0],
              plugin: dom_container.querySelector('#span_plugin_id').innerHTML,
              error: function(error) {
                jeedomUtils.showAlert({
                  message: error.message,
                  level: 'danger'
                })
              },
              success: function(data) {
                dom_container.querySelector('#div_plugin_functionality').setJeeValues(data, '.configKey')
                jeeFrontEnd.modifyWithoutSave = false
              }
            })
            jeedom.config.load({
              configuration: dom_container.querySelector('#div_plugin_log').getJeeValues('.configKey')[0],
              error: function(error) {
                jeedomUtils.showAlert({
                  message: error.message,
                  level: 'danger'
                })
              },
              success: function(data) {
                dom_container.querySelector('#div_plugin_log').setJeeValues(data, '.configKey')
                jeeFrontEnd.modifyWithoutSave = false
              }
            })
          } else {
            dom_container.querySelector('#div_plugin_configuration').closest('.alert').unseen()
          }
          try {dom_container.querySelector('#div_confPlugin').seen()} catch(e) {}
          jeeFrontEnd.modifyWithoutSave = false
          if (!$('#md_modal').is(':visible')) {
            jeedomUtils.addOrUpdateUrl('id', $container.find('#span_plugin_id').text(), data.name + ' - ' + JEEDOM_PRODUCT_NAME)
          }
          setTimeout(function() {
            jeedomUtils.initTooltips(document.getElementById("div_confPlugin"))
          }, 500)
        }
      })
    },
    savePluginConfig: function(_param) {
      jeedom.config.save({
        configuration: document.getElementById('div_plugin_configuration').getJeeValues('.configKey')[0],
        plugin: document.getElementById('span_plugin_id').innerHTML,
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          });
        },
        success: function() {
          if (!isset(_param)) {
            _param = {}
          }
          jeedomUtils.showAlert({
            message: '{{Sauvegarde effectuée}}',
            level: 'success'
          })
          jeeFrontEnd.modifyWithoutSave = false;
          var postSave = document.getElementById('span_plugin_id').innerHTML + '_postSaveConfiguration'
          if (typeof window[postSave] == 'function') {
            window[postSave]()
          }
          if (typeof _param.success == 'function') {
            _param.success(0)
          }
          if ($('#div_plugin_configuration .saveParam[data-l1key=relaunchDeamon]').html() != undefined) {
            _param.relaunchDeamon = document.querySelector('#div_plugin_configuration .saveParam[data-l1key=relaunchDeamon]').jeeValue()
          }
        }
      })
    },
  }
}

jeeFrontEnd.plugin.init()

$('sub.itemsNumber').html('(' + $('.pluginDisplayCard').length + ')')

document.registerEvent('keydown', function(event) {
  if (jeedomUtils.getOpenedModal()) return

  if ((event.ctrlKey || event.metaKey) && event.which == 83) { //s
    event.preventDefault()
    $("#bt_savePluginConfig").click()
  }
})

//searching:
$('#in_searchPlugin').off('keyup').keyup(function() {
  var search = this.value
  if (search == '') {
    $('.pluginDisplayCard').show()
    return
  }
  search = jeedomUtils.normTextLower(search)

  $('.pluginDisplayCard').hide()
  var text
  $('.pluginDisplayCard .name').each(function() {
    text = jeedomUtils.normTextLower($(this).text())
    if (text.indexOf(search) >= 0) {
      $(this).closest('.pluginDisplayCard').show()
    }
  })
})
$('#bt_resetPluginSearch').on('click', function() {
  $('#in_searchPlugin').val('').keyup()
})

//displayAsTable or conf open plugin:
$('div.pluginDisplayCard .bt_openPluginPage, #div_confPlugin .openPluginPage').off('click').on('click', function(event) {
  event.stopPropagation()
  if (event.target.closest('.pluginDisplayCard')?.hasClass('inactive') || event.target.closest('#div_state')?.querySelector('a.togglePlugin')?.getAttribute('data-state') == '1') {
    jeeDialog.alert('{{Vous devez activer ce plugin pour y accéder.}}')
    return false
  }
  if (event.target.closest('.pluginDisplayCard') != null) {
    var pluginId = event.target.closest('.pluginDisplayCard').getAttribute('data-plugin_id')
  } else {
    var pluginId = event.target.getAttribute('data-plugin_id')
  }
  var url = '/index.php?v=d&m=' + pluginId + '&p=' + pluginId
  if (event.ctrlKey || event.metaKey) {
    window.open(url).focus()
  } else {
    jeedomUtils.loadPage(url)
  }
  return false
})
$('div.pluginDisplayCard .bt_openPluginPage, #div_confPlugin .openPluginPage').off('mouseup').on('mouseup', function(event) {
  if (event.which == 2) {
    event.stopPropagation()
    event.preventDefault()
    if (event.target.closest('.pluginDisplayCard') != null) {
      var pluginId = event.target.closest('.pluginDisplayCard').getAttribute('data-plugin_id')
      $('.pluginDisplayCard[data-plugin_id="' + pluginId + '"] .bt_openPluginPage').trigger(jQuery.Event('click', {
        ctrlKey: true,
        pluginId: pluginId
      }))
    } else {
      $('#div_confPlugin .openPluginPage').trigger(jQuery.Event('click', {
        ctrlKey: true,
        pluginId: pluginId
      }))
    }
  }
})

//Plugin page:
$('.pullInstall').on('click', function() {
  jeedom.repo.pullInstall({
    repo: $(this).attr('data-repo'),
    error: function(error) {
      jeedomUtils.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(data) {
      if (data.number > 0) {
        jeedomUtils.reloadPagePrompt('{{De nouveaux plugins ont été installés}} (' + data.number + ').')
      } else {
        jeedomUtils.showAlert({
          message: '{{Synchronisation réussi. Aucun nouveau plugin installé.}}',
          level: 'success'
        })
      }
    }
  })
})

$('.gotoUrlStore').on('click', function() {
  window.open($(this).attr('data-href'), '_blank');
})

$('.displayStore').on('click', function() {
  jeeDialog.dialog({
    id: 'jee_modal',
    title: "{{Market}}",
    contentUrl: 'index.php?v=d&modal=update.list&type=plugin&repo=' + this.getAttribute('data-repo')
  })
})

$('#bt_addPluginFromOtherSource').on('click', function() {
  jeeDialog.dialog({
    id: 'jee_modal',
    title: "{{Ajouter un plugin}}",
    contentUrl: 'index.php?v=d&modal=update.add'
  })
})

$('.pluginDisplayCard').off('click').on('click', function(event) {
  var pluginId = $(this).attr('data-plugin_id')
  if (event.ctrlKey || event.metaKey) {
    var url = '/index.php?v=d&p=plugin&id=' + pluginId
    window.open(url).focus()
  } else {
    jeeP.displayPlugin(pluginId)
  }
  return false
})
$('div.pluginDisplayCard').off('mouseup').on('mouseup', function(event) {
  event.stopPropagation()
  event.preventDefault()
  if (event.which == 2) {
    event.preventDefault()
    var pluginId = $(this).attr('data-plugin_id')
    $('.pluginDisplayCard[data-plugin_id="' + pluginId + '"]').trigger(jQuery.Event('click', {
      ctrlKey: true,
      pluginId: pluginId
    }))
  }
})

//configuration page:
$('#bt_returnToThumbnailDisplay').last().on('click', function() {
  setTimeout(function() {
    $('.nav li.active').removeClass('active')
    $('a[href="#' + $('.tab-pane.active').attr('id') + '"]').closest('li').addClass('active')
  }, 500)
  if (jeedomUtils.checkPageModified()) return
  $('#div_resumePluginList').show()
  $('#div_confPlugin').hide()
  jeedomUtils.addOrUpdateUrl('id', null, '{{Gestion Plugins}} - ' + JEEDOM_PRODUCT_NAME)
})

$('body').off('click', '.bt_refreshPluginInfo').on('click', '.bt_refreshPluginInfo', function() {
  $('.pluginDisplayCard[data-plugin_id=' + $('#span_plugin_id').text() + ']').click()
})

$('#span_right_button').on({
  'click': function(event) {
    var _el = $(this)
    jeeDialog.confirm('{{Êtes-vous sûr de vouloir supprimer le plugin}} <span style="font-weight: bold ;">' + $('#span_plugin_name').text() + '</span> ?', function(result) {
      if (result) {
        jeedomUtils.hideAlert()
        jeedom.update.remove({
          id: _el.attr('data-market_logicalId'),
          error: function(error) {
            jeedomUtils.showAlert({
              message: error.message,
              level: 'danger'
            })
          },
          success: function() {
            jeedomUtils.loadPage('index.php?v=d&p=plugin')
          }
        })
      }
    })
  }
}, '.removePlugin')

$('#div_plugin_toggleState').on({
  'click': function(event) {
    var _el = $(this)
    jeedom.plugin.toggle({
      id: _el.attr('data-plugin_id'),
      state: _el.attr('data-state'),
      error: function(error) {
        jeedomUtils.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function() {
        if (document.getElementById('jee_modal')?.isVisible()) {
          jeeDialog.dialog({
            id: 'jee_modal',
            title: '{{Configuration du plugin}}',
            height: '85%',
            contentUrl: 'index.php?v=d&p=plugin&ajax=1&id=' + _el.attr('data-plugin_id')
          })
        } else {
          window.location.href = 'index.php?v=d&p=plugin&id=' + _el.attr('data-plugin_id')
        }
      }
    })
  }
}, '.togglePlugin')

$("#bt_savePluginConfig").on('click', function(event) {
  jeeFrontEnd.plugin.savePluginConfig()
  return false
})

$('#div_resumePluginList').off('change', '.configKey').on('change', '.configKey:visible', function() {
  jeeFrontEnd.modifyWithoutSave = true
})

$('#bt_savePluginPanelConfig').off('click').on('click', function() {
  jeedom.config.save({
    configuration: document.getElementById('div_plugin_panel').getJeeValues('.configKey')[0],
    plugin: $('#span_plugin_id').text(),
    error: function(error) {
      jeedomUtils.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function() {
      jeedomUtils.showAlert({
        message: '{{Sauvegarde de la configuration des panneaux effectuée}}',
        level: 'success'
      })
      jeeFrontEnd.modifyWithoutSave = false
    }
  })
})

$('#bt_savePluginFunctionalityConfig').off('click').on('click', function() {
  jeedom.config.save({
    configuration: document.getElementById('div_plugin_functionality').getJeeValues('.configKey')[0],
    plugin: $('#span_plugin_id').text(),
    error: function(error) {
      jeedomUtils.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function() {
      jeedomUtils.showAlert({
        message: '{{Sauvegarde des fonctionalités effectuée}}',
        level: 'success'
      })
      jeeFrontEnd.modifyWithoutSave = false
    }
  })
})

$('#bt_savePluginLogConfig').off('click').on('click', function() {
  jeedom.config.save({
    configuration: document.getElementById('div_plugin_log').getJeeValues('.configKey')[0],
    error: function(error) {
      jeedomUtils.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function() {
      jeedomUtils.showAlert({
        message: '{{Sauvegarde de la configuration des logs effectuée}}',
        level: 'success'
      })
      jeeFrontEnd.modifyWithoutSave = false
    }
  })
})

$('#div_configLog').on({
  'click': function(event) {
    let mId = document.getElementById('jee_modal')?.isVisible() ? 'jee_modal2' : 'jee_modal'
    jeeDialog.dialog({
      id: mId,
      title: "{{Log du plugin}}" + ' ' + this.getAttribute('data-log'),
      contentUrl: 'index.php?v=d&modal=log.display&log=' + escape(this.getAttribute('data-log'))
    })
  }
}, '.bt_plugin_conf_view_log')

//is plugin id in url to go to configuration:
if (typeof(jeephp2js.selPluginId) !== "undefined" && jeephp2js.selPluginId != -1) {
  if ($('#md_modal').is(':visible')) {
    var $container = $('#md_modal #div_resumePluginList')
  } else {
    var $container = $('#div_resumePluginList')
  }
  if ($container.find('.pluginDisplayCard[data-plugin_id=' + jeephp2js.selPluginId + ']').length != 0) {
    $container.find('.pluginDisplayCard[data-plugin_id=' + jeephp2js.selPluginId + ']').click()
  } else {
    $container.find('.pluginDisplayCard').first().click()
  }
  jeedomUtils.initTooltips()
}