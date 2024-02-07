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
      this.modal = null
      this.dom_container = null
      document.querySelector('sub.itemsNumber').innerHTML = '(' + document.querySelectorAll('.pluginDisplayCard').length + ')'
    },
    postInit: function() {
      //is plugin id in url to go to configuration:
      if (typeof (jeephp2js.selPluginId) !== "undefined" && jeephp2js.selPluginId != -1) {
        let modal = jeeDialog.get('#div_confPlugin', 'dialog')
        let dom_container = null
        if (modal != null) {
          dom_container = modal.querySelector('#div_resumePluginList')
        } else {
          dom_container = document.getElementById('div_pageContainer').querySelector('#div_resumePluginList')
        }
        let plugin = dom_container.querySelector('.pluginDisplayCard[data-plugin_id="' + jeephp2js.selPluginId + '"]')
        if (plugin != null) {
          plugin.click()
          jeedomUtils.initTooltips()
        }
      }
    },
    displayPlugin: function(_pluginId) {
      jeedomUtils.hideAlert()
      let self = this
      //Is plugin page displayed inside modal from _pluginId page or Core plugin management page:
      this.modal = jeeDialog.get('#div_confPlugin', 'dialog')
      if (this.modal != null) {
        this.dom_container = this.modal.querySelector('#div_confPlugin')
        document.getElementById('bt_returnToThumbnailDisplay').unseen()
        document.getElementById('div_resumePluginList').unseen()
        this.dom_container.seen()
      } else {
        this.dom_container = document.getElementById('div_pageContainer').querySelector('#div_confPlugin')
        document.getElementById('bt_returnToThumbnailDisplay').seen()
        document.getElementById('div_resumePluginList').unseen()
        this.dom_container.seen()
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
          self.dom_container.querySelector('#span_plugin_id').innerHTML = data.id
          self.dom_container.querySelector('#span_plugin_name').innerHTML = data.name

          if (isset(data.update) && isset(data.update.localVersion)) {
            var localVer = data.update.localVersion
            if (localVer.length > 20) localVer = localVer.substring(0, 20) + '...'
            self.dom_container.querySelector('#span_plugin_install_date').innerHTML = localVer
          } else {
            self.dom_container.querySelector('#span_plugin_install_date').innerHTML = ''
          }

          self.dom_container.querySelector('#span_plugin_license').innerHTML = data.license
          if (data.installation.trim() == '' || data.installation.trim() == 'Aucune') {
            self.dom_container.querySelector('#span_plugin_installation').closest('.panel').unseen()
          } else {
            self.dom_container.querySelector('#span_plugin_installation').innerHTML = data.installation
            self.dom_container.querySelector('#span_plugin_installation').closest('.panel').seen()
          }

          if (isset(data.update) && isset(data.update.configuration) && isset(data.update.configuration.version)) {
            self.dom_container.querySelector('#span_plugin_install_version').innerHTML = data.update.configuration.version
          } else {
            self.dom_container.querySelector('#span_plugin_install_version').innerHTML = ''
          }

          if (isset(data.author)) {
            self.dom_container.querySelector('#span_plugin_author').innerHTML = '<a href="https://market.jeedom.com/index.php?v=d&p=market&author=' + data.author + '">' + data.author + '</a>'
          } else {
            self.dom_container.querySelector('#span_plugin_author').innerHTML = ''
          }

          if (isset(data.category) && isset(jeephp2js.pluginCategories[data.category])) {
            self.dom_container.querySelector('#span_plugin_category').innerHTML = jeephp2js.pluginCategories[data.category].name
          } else {
            self.dom_container.querySelector('#span_plugin_category').innerHTML = ''
          }
          if (isset(data.source)) {
            if (isset(data.update.configuration.user)){
                self.dom_container.querySelector('#span_plugin_source').innerHTML = data.source +' - '+data.update.configuration.user
            } else {
                self.dom_container.querySelector('#span_plugin_source').innerHTML = data.source
            }
          } else {
            self.dom_container.querySelector('#span_plugin_source').innerHTML = ''
          }

          self.dom_container.querySelector('#div_state .bt_openPluginPage').setAttribute('data-plugin_id', data.id)

          if (data.checkVersion != -1) {
            if (data.require <= jeeFrontEnd.jeedomVersion) {
              self.dom_container.querySelector('#span_plugin_require').innerHTML = '<span class="label label-success">' + data.require + '</span>'
            } else {
              self.dom_container.querySelector('#span_plugin_require').innerHTML = '<span class="label label-warning">' + data.require + '</span>'
            }
          } else {
            self.dom_container.querySelector('#span_plugin_require').innerHTML = '<span class="label label-danger">' + data.require + '</span>'
          }

          //dependencies and daemon divs:
          var divPluginDependancy = self.dom_container.querySelector('#div_plugin_dependancy')
          var divPluginDeamon = self.dom_container.querySelector('#div_plugin_deamon')
          divPluginDependancy.closest('.panel').parentNode.addClass('col-md-6')
          divPluginDeamon.closest('.panel').parentNode.addClass('col-md-6')
          if (data.hasDependency == 0 || data.activate != 1) {
            divPluginDependancy.closest('.panel').unseen()
            divPluginDeamon.closest('.panel').parentNode.removeClass('col-md-6')
          } else {
            divPluginDependancy.load('index.php?v=d&modal=plugin.dependancy&plugin_id=' + data.id, function(_div) {
              _div.closest('.panel').seen()
            })
          }

          if (data.hasOwnDeamon == 0 || data.activate != 1) {
            divPluginDeamon.closest('.panel').unseen()
            divPluginDependancy.closest('.panel').parentNode.removeClass('col-md-6')
          } else {
            divPluginDeamon.load('index.php?v=d&modal=plugin.deamon&plugin_id=' + data.id, function(_div) {
              _div.closest('.panel').seen()
            })
          }

          if ((data.hasDependency == 0 || data.activate != 1) && (data.hasOwnDeamon == 0 || data.activate != 1)) {
            divPluginDependancy.closest('.panel').parentNode.unseen()
            divPluginDeamon.closest('.panel').parentNode.unseen()
          } else {
            divPluginDependancy.closest('.panel').parentNode.seen()
            divPluginDeamon.closest('.panel').parentNode.seen()
          }

          //top right buttons:
          var spanRightButton = self.dom_container.querySelector('#span_right_button')
          let title = '{{Rafraichir la page}}'
          let button = '<a class="btn btn-sm roundedLeft bt_refreshPluginInfo" title="' + title + '"><i class="fas fa-sync"></i><span class="hidden-768"> {{Rafraichir}}</span></a>'
          spanRightButton.empty().insertAdjacentHTML('beforeend', button)
          if (jeedom.theme.mbState == 0) {
            if (isset(data.info.display) && data.info.display != '') {
              title = '{{Voir sur le market}}'
              button = '<a class="btn btn-sm" target="_blank" href="' + data.info.display + '" title="' + title + '"><i class="fas fa-search"></i><span class="hidden-768"> {{Détails}}</span></a>'
              spanRightButton.insertAdjacentHTML('beforeend', button)
            }
            if (data.update.configuration) {
              title = '{{Accéder à la documentation du plugin}}'
              if (isset(data.documentation_beta) && data.documentation_beta != '' && data.update.configuration.version == 'beta') {
                button = '<a class="btn btn-primary btn-sm" target="_blank" href="' + data.documentation_beta + '" title="' + title + '"><i class="fas fa-book"></i> {{Documentation}}</a>'
                spanRightButton.insertAdjacentHTML('beforeend', button)
              }
              else if (isset(data.documentation) && data.documentation != '') {
                button = '<a class="btn btn-primary btn-sm" target="_blank" href="' + data.documentation + '" title="' + title + '"><i class="fas fa-book"></i> {{Documentation}}</a>'
                spanRightButton.insertAdjacentHTML('beforeend', button)
              }
              title = '{{Consulter le journal des modifications du plugin}}'
              if (isset(data.changelog_beta) && data.changelog_beta != '' && data.update.configuration.version == 'beta') {
                button = '<a class="btn btn-info btn-sm" target="_blank" href="' + data.changelog_beta + '" title="' + title + '"><i class="fas fa-file-code"></i> {{Changelog}}</a>'
                spanRightButton.insertAdjacentHTML('beforeend', button)
              }
              else if (isset(data.changelog) && data.changelog != '') {
                button = '<a class="btn btn-info btn-sm" target="_blank" href="' + data.changelog + '" title="' + title + '"><i class="fas fa-file-code"></i> {{Changelog}}</a>'
                spanRightButton.insertAdjacentHTML('beforeend', button)
              }
            }
            title = '{{Ouvrir une demande d\'aide sur le forum communautaire}}'
            button = '<a class="btn btn-warning btn-sm" id="createCommunityPost" data-plugin_id="' + data.id + '" title="' + title + '"><i class="fas fa-ambulance"></i><span class="hidden-768"> {{Assistance}}</span></a>'
            spanRightButton.insertAdjacentHTML('beforeend', button)
          }
          title = '{{Supprimer le plugin}}'
          button = '<a class="btn btn-danger btn-sm removePlugin roundedRight" data-market_logicalId="' + data.id + '" title="' + title + '"><i class="fas fa-trash"></i><span class="hidden-768"> {{Supprimer}}</span></a>'
          spanRightButton.insertAdjacentHTML('beforeend', button)

          self.dom_container.querySelector('#div_configPanel').unseen()
          self.dom_container.querySelector('#div_plugin_panel').empty()
          if (isset(data.display) && data.display != '') {
            var config_panel_html = '<div class="form-group">'
            config_panel_html += '<label class="col-lg-4 col-md-4 col-sm-4 col-xs-6 control-label">{{Afficher le panneau desktop}}</label>'
            config_panel_html += '<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">'
            config_panel_html += '<input type="checkbox" class="configKey tooltips" data-l1key="displayDesktopPanel" />'
            config_panel_html += '</div>'
            config_panel_html += '</div>'
            self.dom_container.querySelector('#div_configPanel').seen()
            self.dom_container.querySelector('#div_plugin_panel').insertAdjacentHTML('beforeend', config_panel_html)
          }

          if (isset(data.mobile) && data.mobile != '') {
            var config_panel_html = '<div class="form-group">'
            config_panel_html += '<label class="col-lg-4 col-md-4 col-sm-4 col-xs-6 control-label">{{Afficher le panneau mobile}}</label>'
            config_panel_html += '<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">'
            config_panel_html += '<input type="checkbox" class="configKey tooltips" data-l1key="displayMobilePanel" />'
            config_panel_html += '</div>'
            config_panel_html += '</div>'
            self.dom_container.querySelector('#div_configPanel').seen()
            self.dom_container.querySelector('#div_plugin_panel').insertAdjacentHTML('beforeend', config_panel_html)
          }

          self.dom_container.querySelector('#div_plugin_functionality').empty()
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
          self.dom_container.querySelector('#div_plugin_functionality').insertAdjacentHTML('beforeend', config_panel_html)

          self.dom_container.querySelector('#div_plugin_toggleState').empty()
          if (data.checkVersion != -1) {
            var html = '<form class="form-horizontal"><fieldset>'
            html += '<div class="form-group">'
            html += '<label class="col-sm-2 col-xs-6 control-label">{{Statut}}</label>'
            html += '<div class="col-sm-4 col-xs-6">'
            if (data.activate == 1) {
              self.dom_container.querySelector('#div_plugin_toggleState').closest('.panel').removeClass('panel-default', 'panel-danger').addClass('panel-success')
              html += '<span class="label label-success">{{Actif}}</span>'
            } else {
              self.dom_container.querySelector('#div_plugin_toggleState').closest('.panel').removeClass('panel-default', 'panel-success').addClass('panel-danger')
              html += '<span class="label label-danger">{{Inactif}}</span>'
            }
            html += '</div>'
            html += '<label class="col-sm-2 col-xs-6 control-label">{{Action}}</label>'
            html += '<div class="col-sm-4 col-xs-6">'
            if (data.activate == 1) {
              html += '<a class="btn btn-danger btn-xs togglePlugin" data-state="0" data-plugin_id="' + data.id + '" style="position:relative;top:-2px;"><i class="fas fa-times"></i> {{Désactiver}}</a>'
            } else {
              html += '<a class="btn btn-success btn-xs togglePlugin" data-state="1" data-plugin_id="' + data.id + '" style="position:relative;top:-2px;"><i class="fas fa-check"></i> {{Activer}}</a>'
            }
            html += '</div>'
            html += '</div>'
            html += '</fieldset></form>'
            self.dom_container.querySelector('#div_plugin_toggleState').insertAdjacentHTML('beforeend', html)
          } else {
            self.dom_container.querySelector('#div_plugin_toggleState').closest('.panel').removeClass('panel-default', 'panel-success').addClass('panel-danger')
            self.dom_container.querySelector('#div_plugin_toggleState').insertAdjacentHTML('beforeend', '{{Votre version de}} ' + JEEDOM_PRODUCT_NAME + ' {{ne permet pas d\'activer ce plugin}}')
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
              log_conf += '<a class="btn btn-info btn-sm bt_plugin_conf_view_log" data-slaveId="' + data.logs[i].id + '" data-log="' + data.logs[i].log[j] + '"><i class="fas fa-paperclip"></i>  ' + data.logs[i].log[j].charAt(0).toUpperCase() + data.logs[i].log[j].slice(1) + '</a> '
            }
            log_conf += '</div>'
            log_conf += '</div>'
            log_conf += '</form>'
          }

          log_conf += '<form class="form-horizontal">'
          log_conf += '<div class="form-group">'
          log_conf += '<label class="col-sm-3 control-label">{{Heartbeat (min)}}</label>'
          log_conf += '<div class="col-sm-2">'
          log_conf += '<input class="configKey form-control input-sm" data-l1key="heartbeat::delay::' + data.id + '" />'
          log_conf += '</div>'
          if (data.hasOwnDeamon) {
            log_conf += '<label class="col-sm-3 control-label">{{Redémarrer démon}}</label>'
            log_conf += '<div class="col-sm-2">'
            log_conf += '<input type="checkbox" class="configKey" data-l1key="heartbeat::restartDeamon::' + data.id + '" />'
            log_conf += '</div>'
          }
          log_conf += '</div>'
          log_conf += '</form>'

          self.dom_container.querySelector('#div_plugin_log').empty().insertAdjacentHTML('beforeend', log_conf)
          var dom_divPluginConfiguration = self.dom_container.querySelector('#div_plugin_configuration')
          dom_divPluginConfiguration.empty()
          if (data.checkVersion != -1) {
            if (data.configurationPath != '' && data.activate == '1') {
              dom_divPluginConfiguration.load('index.php?v=d&plugin=' + data.id + '&configure=1', function() {
                if (dom_divPluginConfiguration.innerHTML.trim() == '') {
                  dom_divPluginConfiguration.closest('.panel').unseen()
                  return
                } else {
                  dom_divPluginConfiguration.closest('.panel').seen()
                }
                jeedom.config.load({
                  configuration: dom_divPluginConfiguration.getJeeValues('.configKey')[0],
                  plugin: data.id,
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
              configuration: self.dom_container.querySelector('#div_plugin_panel').getJeeValues('.configKey')[0],
              plugin: data.id,
              error: function(error) {
                jeedomUtils.showAlert({
                  message: error.message,
                  level: 'danger'
                })
              },
              success: function(data) {
                self.dom_container.querySelector('#div_plugin_panel').setJeeValues(data, '.configKey')
                jeeFrontEnd.modifyWithoutSave = false
              }
            })

            jeedom.config.load({
              configuration: self.dom_container.querySelector('#div_plugin_functionality').getJeeValues('.configKey')[0],
              plugin: data.id,
              error: function(error) {
                jeedomUtils.showAlert({
                  message: error.message,
                  level: 'danger'
                })
              },
              success: function(data) {
                self.dom_container.querySelector('#div_plugin_functionality').setJeeValues(data, '.configKey')
                jeeFrontEnd.modifyWithoutSave = false
              }
            })

            jeedom.config.load({
              configuration: self.dom_container.querySelector('#div_plugin_log').getJeeValues('.configKey')[0],
              error: function(error) {
                jeedomUtils.showAlert({
                  message: error.message,
                  level: 'danger'
                })
              },
              success: function(data) {
                self.dom_container.querySelector('#div_plugin_log').setJeeValues(data, '.configKey')
                jeeFrontEnd.modifyWithoutSave = false
              }
            })
          } else {
            self.dom_container.querySelector('#div_plugin_configuration').closest('.alert').unseen()
          }
          self.dom_container.seen()
          jeeFrontEnd.modifyWithoutSave = false

          if (this.modal == null) {
            jeedomUtils.addOrUpdateUrl('id', self.dom_container.querySelector('#span_plugin_id').textContent, data.name + ' - ' + JEEDOM_PRODUCT_NAME)
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
          })
        },
        success: function() {
          if (!isset(_param)) {
            _param = {}
          }
          jeedomUtils.showAlert({
            message: '{{Sauvegarde effectuée}}',
            level: 'success'
          })
          jeeFrontEnd.modifyWithoutSave = false
          var postSave = document.getElementById('span_plugin_id').innerHTML + '_postSaveConfiguration'
          if (typeof window[postSave] == 'function') {
            window[postSave]()
          }
          if (typeof _param.success == 'function') {
            _param.success(0)
          }
          let relaunchDeamon = document.querySelector('#div_plugin_configuration .saveParam[data-l1key="relaunchDeamon"]')
          if (relaunchDeamon != null) {
            _param.relaunchDeamon = relaunchDeamon.jeeValue()
          }
        }
      })
    },
    openPluginPage: function(_event, _aux) {
      if (!isset(_aux)) {
        if (_event.ctrlKey || _event.metaKey) {
          _aux = true
        } else {
          _aux = false
        }
      }
      if (event.target.closest('.pluginDisplayCard')?.hasClass('inactive') || event.target.closest('#div_state')?.querySelector('a.togglePlugin')?.getAttribute('data-state') == '1') {
        jeeDialog.alert('{{Vous devez activer ce plugin pour y accéder.}}')
        return
      }

      if (_event.target.closest('.pluginDisplayCard') != null) {
        var pluginId = _event.target.closest('.pluginDisplayCard').getAttribute('data-plugin_id')
      } else {
        var pluginId = _event.target.getAttribute('data-plugin_id')
      }

      var url = '/index.php?v=d&m=' + pluginId + '&p=' + pluginId
      if (_aux) {
        window.open(url).focus()
      } else {
        jeedomUtils.loadPage(url)
      }
    }
  }
}

jeeFrontEnd.plugin.init()

//searching:
document.getElementById('in_searchPlugin')?.addEventListener('keyup', function(event) {
  var search = event.target.value
  if (search == '') {
    document.querySelectorAll('.pluginDisplayCard').seen()
    return
  }
  search = jeedomUtils.normTextLower(search)

  document.querySelectorAll('.pluginDisplayCard').unseen()
  var text
  document.querySelectorAll('.pluginDisplayCard .name').forEach(_name => {
    text = jeedomUtils.normTextLower(_name.textContent)
    if (text.includes(search)) {
      _name.closest('.pluginDisplayCard').seen()
    }
  })
})
document.getElementById('bt_resetPluginSearch')?.addEventListener('click', function(event) {
  document.getElementById('in_searchPlugin').jeeValue('').triggerEvent('keyup')
})


//Register events on top of page container:
document.registerEvent('keydown', function(event) {
  if (jeedomUtils.getOpenedModal()) return
  if ((event.ctrlKey || event.metaKey) && event.which == 83) { //s
    event.preventDefault()
    jeeFrontEnd.plugin.savePluginConfig()
  }
})

/*Events delegations
*/
//Plugin list page:
document.getElementById('div_resumePluginList')?.addEventListener('click', function(event) {
  var _target = null
  if (_target = event.target.closest('.pullInstall')) {
    jeedom.repo.pullInstall({
      repo: _target.getAttribute('data-repo'),
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
            message: '{{Synchronisation réussie. Aucun nouveau plugin installé.}}',
            level: 'success'
          })
        }
      }
    })
    return
  }

  if (_target = event.target.closest('.gotoUrlStore')) {
    window.open(_target.getAttribute('data-href'), '_blank')
    return
  }

  if (_target = event.target.closest('.displayStore')) {
    jeeDialog.dialog({
      id: 'jee_modal',
      title: "{{Market}}",
      contentUrl: 'index.php?v=d&modal=update.list&type=plugin&repo=' + _target.getAttribute('data-repo')
    })
    return
  }

  if (_target = event.target.closest('#bt_addPluginFromOtherSource')) {
    jeeDialog.dialog({
      id: 'jee_modal',
      title: "{{Ajouter un plugin}}",
      contentUrl: 'index.php?v=d&modal=update.add'
    })
    return
  }

  if (_target = event.target.closest('div.pluginDisplayCard')) {
    let pluginId = _target.getAttribute('data-plugin_id')
    if ((isset(event.detail) && event.detail.ctrlKey) || event.ctrlKey || event.metaKey) {
      window.open('/index.php?v=d&p=plugin&id=' + pluginId).focus()
    } else {
      jeeFrontEnd.plugin.displayPlugin(pluginId)
    }
    return
  }

  if (_target = event.target.closest('.bt_openPluginPage')) {
    event.stopPropagation()
    jeeFrontEnd.plugin.openPluginPage(event)
    return
  }
})

document.getElementById('div_resumePluginList')?.addEventListener('mouseup', function(event) {
  var _target = null
  if (_target = event.target.closest('div.pluginDisplayCard')) {
    event.stopPropagation()
    if (event.which == 2) {
      event.preventDefault()
      var pluginId = _target.getAttribute('data-plugin_id')
      document.querySelector('.pluginDisplayCard[data-plugin_id="' + pluginId + '"]').triggerEvent('click', { detail: { ctrlKey: true } })
    }
    return
  }

  if (_target = event.target.closest('.bt_openPluginPage')) {
    if (event.which == 2) {
      event.stopPropagation()
      event.preventDefault()
      jeeFrontEnd.plugin.openPluginPage(event, true)
    }
    return
  }
})


//Plugin configuration, page or modale:
document.getElementById('div_confPlugin')?.addEventListener('click', function(event) {
  var _target = null
  if (_target = event.target.closest('#bt_returnToThumbnailDisplay')) {
    setTimeout(function() {
      document.querySelectorAll('.nav li.active').removeClass('active')
      let tab, active = document.querySelector('.tab-pane.active')
      if (active) tab = document.querySelector('a[href="#' + active.getAttribute('id') + '"]')
      if (tab) tab.closest('li').addClass('active')
    }, 500)
    if (jeedomUtils.checkPageModified()) return
    document.getElementById('div_resumePluginList')?.seen()
    document.getElementById('div_confPlugin')?.unseen()
    jeedomUtils.addOrUpdateUrl('id', null, '{{Gestion Plugins}} - ' + JEEDOM_PRODUCT_NAME)
    return
  }

  if (_target = event.target.closest('.bt_refreshPluginInfo')) {
    document.querySelector('.pluginDisplayCard[data-plugin_id="' + document.getElementById('span_plugin_id').textContent + '"]').click()
    return
  }

  if (_target = event.target.closest('.removePlugin')) {
    jeeDialog.confirm('{{Êtes-vous sûr de vouloir supprimer le plugin}} <span style="font-weight: bold ;">' + document.getElementById('span_plugin_name').textContent + '</span> ?', function(result) {
      if (result) {
        jeedomUtils.hideAlert()
        jeedom.update.remove({
          id: _target.getAttribute('data-market_logicalId'),
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
    return
  }

  if (_target = event.target.closest('.togglePlugin')) {
    jeedom.plugin.toggle({
      id: _target.getAttribute('data-plugin_id'),
      state: _target.getAttribute('data-state'),
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
            contentUrl: 'index.php?v=d&p=plugin&ajax=1&id=' + _target.getAttribute('data-plugin_id')
          })
        } else {
          window.location.href = 'index.php?v=d&p=plugin&id=' + _target.getAttribute('data-plugin_id')
        }
      }
    })
    return
  }

  if (_target = event.target.closest('#bt_savePluginConfig')) {
    jeeFrontEnd.plugin.savePluginConfig()
    return
  }

  if (_target = event.target.closest('#bt_savePluginPanelConfig')) {
    jeedom.config.save({
      configuration: document.getElementById('div_plugin_panel').getJeeValues('.configKey')[0],
      plugin: document.getElementById('span_plugin_id').textContent,
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
    return
  }

  if (_target = event.target.closest('#bt_savePluginFunctionalityConfig')) {
    jeedom.config.save({
      configuration: document.getElementById('div_plugin_functionality').getJeeValues('.configKey')[0],
      plugin: document.getElementById('span_plugin_id').textContent,
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
    return
  }

  if (_target = event.target.closest('#bt_savePluginLogConfig')) {
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
    return
  }

  if (_target = event.target.closest('.bt_plugin_conf_view_log')) {
    let mId = document.getElementById('jee_modal')?.isVisible() ? 'jee_modal2' : 'jee_modal'
    jeeDialog.dialog({
      id: mId,
      title: "{{Log du plugin}}" + ' ' + event.target.closest('.bt_plugin_conf_view_log').getAttribute('data-log'),
      contentUrl: 'index.php?v=d&modal=log.display&log=' + escape(event.target.closest('.bt_plugin_conf_view_log').getAttribute('data-log'))
    })
    return
  }

  if (_target = event.target.closest('.bt_openPluginPage')) {
    event.stopPropagation()
    jeeFrontEnd.plugin.openPluginPage(event)
    return
  }

  if (_target = event.target.closest('#createCommunityPost')) {
    jeedom.plugin.createCommunityPost({
      type: _target.getAttribute('data-plugin_id'),
      error: function(error) {
        domUtils.hideLoading()
        jeedomUtils.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function(data) {
        let element = document.createElement('a')
        element.setAttribute('href', data.url)
        element.setAttribute('target', '_blank')
        element.style.display = 'none'
        document.body.appendChild(element)
        element.click()
        document.body.removeChild(element)
      }
    })
    return
  }
})

document.getElementById('div_confPlugin')?.addEventListener('mouseup', function(event) {
  var _target = null
  if (_target = event.target.closest('.bt_openPluginPage')) {
    if (event.which == 2) {
      event.stopPropagation()
      event.preventDefault()
      jeeFrontEnd.plugin.openPluginPage(event, true)
    }
    return
  }
})

document.getElementById('div_confPlugin')?.addEventListener('change', function(event) {
  var _target = null
  if (_target = event.target.closest('#div_resumePluginList .configKey')) {
    if (_target.isVisible()) jeeFrontEnd.modifyWithoutSave = true
    return
  }
})

jeeFrontEnd.plugin.postInit()
