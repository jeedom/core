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

if (!jeeFrontEnd.plan) {
  jeeFrontEnd.plan = {
    contextMenu: null,
    planHeaderContextMenu: {},
    resizeObservers: [],
    cssStyleString: '',
    draggables: [],
    isFullScreen: false,
    clickedOpen: false,
    init: function() {
      window.jeeP = this
      this.pageContainer = document.getElementById('div_pageContainer')
      this.planContainer = document.querySelector('.div_displayObject')
      this.planHeaderContextMenu = {}
      jeedomUtils.userDevice = getDeviceType()
      if (typeof jeeFrontEnd.planEditOption === 'undefined' || jeedomUtils.userDevice.type != 'desktop') {
        jeeFrontEnd.planEditOption = {
          state: false,
          snap: false,
          grid: false,
          gridSize: false,
          highlight: true
        }
      } else if (jeedomUtils.userDevice.type == 'desktop') {
        if (getCookie('plan_gridSize') != '') jeeFrontEnd.planEditOption.gridSize = getCookie('plan_gridSize').split(',')
        if (getCookie('plan_grid') != '') jeeFrontEnd.planEditOption.grid = (getCookie('plan_grid') == '1')
        if (getCookie('plan_highlight') != '') jeeFrontEnd.planEditOption.highlight = (getCookie('plan_highlight') == '1')
      }
      jeedom.getInfoApplication({
        version: 'dashboard',
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          jeedom.appMobile.postToApp('initSummary', data.summary)
        }
      })
    },
    postInit: function() {
      for (var i in jeephp2js.planHeader) {
        jeeP.planHeaderContextMenu[jeephp2js.planHeader[i].id] = {
          name: jeephp2js.planHeader[i].name,
          callback: function(key, opt) {
            jeephp2js.planHeader_id = key
            jeeP.displayPlan()
          }
        }
      }

      //Shortcuts:
      if (jeedomUtils.userDevice.type == 'desktop' && user_isAdmin == 1) {
        document.registerEvent('keydown', function(event) {
          if (jeedomUtils.getOpenedModal()) return

          if ((event.ctrlKey || event.metaKey) && event.which == 83) { //s
            event.preventDefault()
            jeeP.savePlan()
            return
          }

          if ((event.ctrlKey || event.metaKey) && event.shiftKey && event.which == 69) { //e
            event.preventDefault()
            //jeeFrontEnd.planEditOption.state = !jeeFrontEnd.planEditOption.state
            jeeP.pageContainer.dataset.planEditState = !jeeFrontEnd.planEditOption.state
            jeeP.initEditOption(!jeeFrontEnd.planEditOption.state)
          }
        })
      }
    },
    createNewDesign: function() {
      jeeDialog.prompt("{{Nom du design ?}}", function(result) {
        if (result !== null) {
          jeedom.plan.saveHeader({
            planHeader: {
              name: result
            },
            error: function(error) {
              jeedomUtils.showAlert({
                message: error.message,
                level: 'danger'
              })
            },
            success: function(data) {
              window.location = 'index.php?v=d&p=plan&plan_id=' + data.id
            }
          })
        }
      })
    },
    fullScreen: function(_mode) {
      _mode = getBool(_mode)
      if (_mode) {
        document.body.addClass('fullscreen')
        jeeFrontEnd.plan.isFullScreen = true
      } else {
        document.body.removeClass('fullscreen')
        jeeFrontEnd.plan.isFullScreen = false
      }
    },
    addObject: function(_plan) {
      _plan.planHeader_id = jeephp2js.planHeader_id
      jeedom.plan.create({
        plan: _plan,
        version: 'dashboard',
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          jeeP.displayObject(data.plan, data.html, false)
          jeeFrontEnd.modifyWithoutSave = true
        }
      })
    },
    displayPlan: function(_code) {
      if (jeephp2js.planHeader_id == -1) return
      if (typeof _code == "undefined") {
        _code = null
      }
      if (getUrlVars('fullscreen') == 1) {
        jeeP.fullScreen(true)
      }
      jeedom.plan.getHeader({
        id: jeephp2js.planHeader_id,
        code: _code,
        error: function(error) {
          if (error.code == -32005) {
            var result = prompt("{{Veuillez indiquer le code ?}}", "")
            if (result == null) {
              jeedomUtils.showAlert({
                message: error.message,
                level: 'danger'
              })
              return
            }
            jeeP.displayPlan(result)
          } else {
            jeephp2js.planHeader_id = -1
            jeedomUtils.showAlert({
              message: error.message,
              level: 'danger'
            })
          }
        },
        success: function(data) {
          jeedom.cmd.resetUpdateFunction()
          jeeFrontEnd.plan.planContainer.empty().insertAdjacentHTML('beforeend', '<div id="div_grid" class="container-fluid" style="display:none;"></div>')
          document.querySelectorAll('.style_plan_specific').remove();
          Object.assign(jeeFrontEnd.plan.planContainer.style, {height:"auto", width:"auto"})
          //general design configuration:
          if (isset(data.image)) {
            jeeFrontEnd.plan.planContainer.insertAdjacentHTML('beforeend', data.image)
          }
          if (isset(data.configuration.backgroundTransparent) && data.configuration.backgroundTransparent == 1) {
            jeeFrontEnd.plan.planContainer.style.backgroundColor = 'transparent'
          } else if (isset(data.configuration.backgroundColor)) {
            jeeFrontEnd.plan.planContainer.style.backgroundColor = data.configuration.backgroundColor
          } else {
            jeeFrontEnd.plan.planContainer.style.backgroundColor = 'rgb(--bg-color)'
          }
          if (data.configuration != null && init(data.configuration.desktopSizeX) != '' && init(data.configuration.desktopSizeY) != '') {
            var style = {
              height: data.configuration.desktopSizeY + 'px',
              width: data.configuration.desktopSizeX + 'px'
            }
            Object.assign(jeeFrontEnd.plan.planContainer.style, style)
            var img = document.querySelector('.div_displayObject img') ? Object.assign(img.style, style) : null
          } else {
            var img = document.querySelector('.div_displayObject img')
            if (img != null) {
              var style = {
                height: img.getAttribute('data-sixe_x') + 'px',
                width: img.getAttribute('data-sixe_x') + 'px'
              }
              Object.assign(jeeFrontEnd.plan.planContainer.style, style)
              Object.assign(img.style, style)
            }
          }

          if (window.offsetHeight > jeeFrontEnd.plan.planContainer.offsetHeight) {
            document.querySelector('.div_backgroundPlan').style = window.offsetHeight
          } else {
            document.querySelector('.div_backgroundPlan').style = jeeFrontEnd.plan.planContainer.offsetHeight
          }

          document.getElementById('div_grid').style.width = jeeFrontEnd.plan.planContainer.offsetWidth + 'px'
          document.getElementById('div_grid').style.height = jeeFrontEnd.plan.planContainer.offsetHeight + 'px'

          if (jeedomUtils.userDevice.type != 'desktop') {
            document.querySelector('meta[name="viewport"]').setAttribute("content", 'width=' + jeeFrontEnd.plan.planContainer.offsetWidth + 'px, height=' + jeeFrontEnd.plan.planContainer.offsetHeight + 'px')
            jeeP.fullScreen(true)
            window.registerEvent('popstate', function(event) {
              window.location.href = 'index.php?v=m'
            })
          }

          //display design components:
          var selector = '.eqLogic-widget, .div_displayObject > .cmd-widget, .scenario-widget'
          selector += ',.plan-link-widget, .view-link-widget, .graph-widget, .text-widget, .image-widget, .zone-widget, .summary-widget'
          document.querySelectorAll(selector).remove()
          jeeFrontEnd.modifyWithoutSave = false
          jeedom.plan.byPlanHeader({
            id: jeephp2js.planHeader_id,
            error: function(error) {
              jeedomUtils.showAlert({
                message: error.message,
                level: 'danger'
              })
            },
            success: function(plans) {
              try {
                var object
                for (var i in plans) {
                  object = jeeP.displayObject(plans[i].plan, plans[i].html, true)
                  if (object != undefined) {
                    jeeFrontEnd.plan.planContainer.appendChild(object)
                    if (jeeFrontEnd.plan.cssStyleString != '') {
                      jeeFrontEnd.plan.pageContainer.insertAdjacentHTML('beforeend', jeeFrontEnd.plan.cssStyleString)
                      jeeFrontEnd.plan.cssStyleString = ''
                    }
                  }
                }
              } catch (e) { console.error(e) }

              jeedomUtils.addOrUpdateUrl('plan_id', jeephp2js.planHeader_id, data.name + ' - ' + JEEDOM_PRODUCT_NAME)
              jeeP.initEditOption(jeeFrontEnd.planEditOption.state)
              jeedomUtils.initReportMode()
              jeedomUtils.initTooltips()
              window.scrollTo({top: 0, behavior: "smooth"})
              jeeFrontEnd.plan.setGraphResizes()
              jeeFrontEnd.modifyWithoutSave = false
            }
          })
        },
      })
    },
    displayObject: function(_plan, _html, _noRender) { //Construct element node and seperated inline style to inject in dom (_noRender bool)
      //Deleted or inactive equipment
      if (_html == '') return

      _plan = init(_plan, {})
      _plan.position = init(_plan.position, {})
      _plan.css = init(_plan.css, {})
      var css_selector = ''
      var another_css = ''

      //get css selector:
      if (['eqLogic', 'scenario', 'text', 'image', 'zone', 'summary'].includes(_plan.link_type)) {
        css_selector = '.div_displayObject .' + _plan.link_type + '-widget[data-' + _plan.link_type + '_id="' + _plan.link_id + '"]'
        document.querySelector(css_selector)?.remove()
      } else if (['view', 'plan'].includes(_plan.link_type)) {
        css_selector = '.div_displayObject .' + _plan.link_type + '-link-widget[data-id="' + _plan.id + '"]'
        document.querySelector(css_selector)?.remove()
      } else if (_plan.link_type == 'cmd') {
        css_selector = '.div_displayObject > .cmd-widget[data-cmd_id="' + _plan.link_id + '"]'
        document.querySelector(css_selector)?.remove()
      } else if (_plan.link_type == 'graph') {
        if (jeedom.history.chart['div_designGraph' + _plan.link_id]) {
          delete jeedom.history.chart['div_designGraph' + _plan.link_id]
        }
        css_selector = '.div_displayObject .graph-widget[data-graph_id="' + _plan.link_id + '"]'
        document.querySelector(css_selector)?.remove()
        if (init(_plan.display.transparentBackground, 0) == 1) {
          _html = _html.replace('class="graph-widget"', 'class="graph-widget transparent"')
        }
      }
      var node = domUtils.DOMparseHTML(_html)
      node.setAttribute('data-plan_id', _plan.id)
      node.setAttribute('data-zoom', init(_plan.css.zoom, 1))
      node.addClass('jeedomAlreadyPosition', 'noResize')

      //set widget style:
      var style = {}
      style['z-index'] = '1000'
      style['position'] = 'absolute'
      style['top'] = init(_plan.position.top, '10') + 'px'
      style['left'] = init(_plan.position.left, '10') + 'px'
      if (init(_plan.css.zoom, 1) != 1) {
        style['scale'] = init(_plan.css.zoom, 1)
      }
      style['transform-origin'] = '0 0'

      if (_plan.link_type != 'cmd') {
        if (isset(_plan.display) && isset(_plan.display.width)) {
          style['width'] = init(_plan.display.width, 50) + 'px'
          node.style.width = style['width']
        }
        if (isset(_plan.display) && isset(_plan.display.height)) {
          style['height'] = init(_plan.display.height, 50) + 'px'
          node.style.height = style['height']
        }
      }

      for (var key in _plan.css) {
        if (_plan.css[key] === '' || key == 'zoom' || key == 'rotate') continue
        if (key == 'z-index' && _plan.css[key] < 999) continue

        if (key == 'background-color') {
          if (isset(_plan.display) && (!isset(_plan.display['background-defaut']) || _plan.display['background-defaut'] != 1)) {
            if (isset(_plan.display['background-transparent']) && _plan.display['background-transparent'] == 1) {
              style['background-color'] = 'transparent'
              style['border-radius'] = '0px'
              style['box-shadow'] = 'none'
              if (_plan.link_type == 'eqLogic') {
                another_css += css_selector + ' .widget-name{background-color : transparent !important;border:none!important;}'
                if (_plan.display['color-defaut'] == 0 && isset(_plan.css.color)) {
                  another_css += css_selector + ' .widget-name a{color : ' + _plan.css.color + ' !important;}'
                  another_css += css_selector + ' .state{color : ' + _plan.css.color + ' !important;}'
                }
              } else if (_plan.link_type == 'cmd') {
                if (_plan.display['color-defaut'] == 0 && isset(_plan.css.color)) {
                  another_css += css_selector + ' .widget-name a{color : ' + _plan.css.color + ' !important;}'
                  another_css += css_selector + ' .state{color : ' + _plan.css.color + ' !important;}'
                }
              }
            } else {
              style[key] = _plan.css[key]
            }
          }
          continue
        } else if (key == 'color') {
          if (!isset(_plan.display) || !isset(_plan.display['color-defaut']) || _plan.display['color-defaut'] != 1) {
            style[key] = _plan.css[key]
            if (_plan.link_type == 'eqLogic' || _plan.link_type == 'cmd' || _plan.link_type == 'summary') {
              another_css += css_selector + ' * {\n' + key + ' : ' + _plan.css[key] + ' !important;}'
              another_css += css_selector + ' .state {\n' + key + ' : ' + _plan.css[key] + ' !important;}'
            }
          }
          continue
        }
        if (key == 'opacity') continue
        if (key == 'font-size' && _plan.link_type == 'summary') {
          another_css += css_selector + ' .objectSummaryParent{\n' + key + ' : ' + _plan.css[key] + ' !important;\n}'
          continue
        }
        style[key] = _plan.css[key]
      }

      if (_plan.css['opacity'] && _plan.css['opacity'] !== '' && style['background-color'] && style['background-color'] != 'transparent') {
        if (style['background-color'].indexOf('#') != -1) {
          var rgb = jeedomUtils.hexToRgb(style['background-color'])
          style['background-color'] = 'rgba(' + rgb.r + ',' + rgb.g + ',' + rgb.b + ',' + _plan.css['opacity'] + ')'
        } else {
          style['background-color'] = style['background-color'].replace(')', ',' + _plan.css['opacity'] + ')').replace('rgb', 'rgba')
        }
      }

      if (_plan.link_type == 'eqLogic') {
        if (isset(_plan.display.hideName) && _plan.display.hideName == 1) {
          node.addClass('hideEqLogicName')
          another_css += css_selector + ' .verticalAlign{height: 100% !important;\n}'
        }
        if (isset(_plan.display.showObjectName) && _plan.display.showObjectName == 1) {
          node.addClass('displayObjectName')
        }
        if (isset(_plan.display.cmdHide)) {
          for (var i in _plan.display.cmdHide) {
            if (_plan.display.cmdHide[i] == 0) {
              continue
            }
            another_css += css_selector + ' .cmd[data-cmd_id="' + i + '"]{display : none !important;}'
          }
        }
        if (isset(_plan.display.cmdHideName)) {
          for (var i in _plan.display.cmdHideName) {
            if (_plan.display.cmdHideName[i] == 0) {
              continue
            }
            another_css += css_selector + ' .cmd[data-cmd_id="' + i + '"] .cmdName{display : none !important;}'
            another_css += css_selector + ' .cmd[data-cmd_id="' + i + '"] .title{display : none !important;}'
          }
        }
        if (isset(_plan.display.cmdTransparentBackground)) {
          for (var i in _plan.display.cmdTransparentBackground) {
            if (_plan.display.cmdTransparentBackground[i] == 0) {
              continue
            }
            another_css += css_selector + ' .cmd[data-cmd_id="' + i + '"]{'
            another_css += 'background-color: transparent !important'
            another_css += 'border-radius: 0px !important'
            another_css += 'box-shadow: none !important'
            another_css += '\n}'
          }
        }
        style['min-width'] = '0px'
        style['min-height'] = '0px'
        another_css += css_selector + ' *:not([class^="content"]:not(.cmd-widget) {'
        another_css += 'min-width:0px !important;'
        another_css += 'min-height:0px !important;'
        another_css += '\n}'
      }

      if (_plan.link_type == 'cmd') {
        var center = document.createElement('center')
        center.append(...node.childNodes)
        node.appendChild(center)

        delete style['height']
        style['min-height'] = '0px'
        delete style['width']
        style['min-width'] = '0px'
        node.style.width = ''
        node.style.height = ''
        if (isset(_plan.display.hideName) && _plan.display.hideName == 1) {
          another_css += css_selector + ' .cmdName{\ndisplay : none !important;\n}'
          another_css += css_selector + ' .title{\ndisplay : none !important;\n}'
        }
      }

      if (_plan.link_type == 'image') {
        if (isset(_plan.display.allowZoom) && _plan.display.allowZoom == 1) {
          node.querySelector('.directDisplay')?.addClass('zoom cursor')
        }
      }

      document.querySelector('#style_' + _plan.link_type + '_' + _plan.id)?.remove()
      var style_el = '<style class="style_plan_specific" id="style_' + _plan.link_type + '_' + _plan.id + '">'
      if (_plan.display.css && _plan.display.css != '') {
        if (_plan.display.cssApplyOn && _plan.display.cssApplyOn != '') {
          var cssApplyOn = _plan.display.cssApplyOn.split(',')
          for (var i in cssApplyOn) {
            style_el += css_selector + ' ' + cssApplyOn[i] + '{' + _plan.display.css + '}'
          }
        } else {
          style_el += css_selector + ' ' + '{' + _plan.display.css + '}'
        }
      }
      style_el += css_selector + '{'


      for (var i in style) {
        if (['left', 'top', 'bottom', 'right', 'height', 'width', 'box-shadow'].includes(i)) {
          style_el += i + ':' + style[i] + ';'
        } else {
          style_el += i + ':' + style[i] + ' !important;'
        }
      }
      style_el += '}\n' + another_css + '</style>'

      if (_plan.link_type == 'graph') {
        jeeFrontEnd.plan.pageContainer.insertAdjacentHTML('beforeend', style_el)
        jeeFrontEnd.plan.planContainer.appendChild(node)
        if (isset(_plan.display) && isset(_plan.display.graph)) {
          var done = 0
          for (var i in _plan.display.graph) {
            if (init(_plan.display.graph[i].link_id) != '') {
              done += 1
              jeedom.history.drawChart({
                cmd_id: _plan.display.graph[i].link_id,
                el: 'div_designGraph' + _plan.link_id,
                showLegend: init(_plan.display.showLegend, true),
                showTimeSelector: init(_plan.display.showTimeSelector, false),
                showScrollbar: init(_plan.display.showScrollbar, true),
                dateRange: init(_plan.display.dateRange, '7 days'),
                option: init(_plan.display.graph[i].configuration, {}),
                transparentBackground: init(_plan.display.transparentBackground, false),
                showNavigator: init(_plan.display.showNavigator, true),
                enableExport: false,
                global: false,
                yAxisScaling: init(_plan.display.yAxisScaling, false),
                yAxisByUnit: init(_plan.display.yAxisByUnit, false),
                success: function() {
                  done -= 1
                  if (done == 0) {
                    if (init(_plan.display.transparentBackground, true) == 1) {
                      document.getElementById('div_designGraph' + _plan.link_id).querySelector('.highcharts-background').style.fillOpacity = '0'
                    }
                  }
                }
              })
            }
          }
        }
        this.initEditOption(jeeFrontEnd.planEditOption.state)
        return
      }

      if (init(_noRender, false)) {
        this.cssStyleString += style_el
        return node
      }

      this.pageContainer.insertAdjacentHTML('beforeend', style_el)
      this.planContainer.appendChild(node)

      this.initEditOption(jeeFrontEnd.planEditOption.state)
    },
    getElementInfo: function(_element) {
      if (_element.length) _element = _element[0]
      if (_element.hasClass('eqLogic-widget')) { return {type: 'eqLogic', id: _element.getAttribute('data-eqLogic_id')} }
      if (_element.hasClass('cmd-widget')) { return {type: 'cmd', id: _element.getAttribute('data-cmd_id')} }
      if (_element.hasClass('scenario-widget')) { return {type: 'scenario', id: _element.getAttribute('data-scenario_id')} }
      if (_element.hasClass('plan-link-widget')) { return {type: 'plan', id: _element.getAttribute('data-link_id')} }
      if (_element.hasClass('view-link-widget')) { return {type: 'view', id: _element.getAttribute('data-link_id')} }
      if (_element.hasClass('graph-widget')) { return {type: 'graph', id: _element.getAttribute('data-graph_id')} }
      if (_element.hasClass('text-widget')) { return {type: 'text', id: _element.getAttribute('data-text_id')} }
      if (_element.hasClass('image-widget')) { return {type: 'image', id: _element.getAttribute('data-image_id')} }
      if (_element.hasClass('zone-widget')) { return {type: 'zone', id: _element.getAttribute('data-zone_id')} }
      if (_element.hasClass('summary-widget')) { return {type: 'summary', id: _element.getAttribute('data-summary_id')} }
    },
    //Events setter
    setGraphResizes: function () {
      for (var obs of this.resizeObservers) {
        obs.disconnect()
      }
      this.resizeObservers = []
      document.querySelectorAll('.graph-widget').forEach(function(_graph) {
        var obs = new ResizeObserver(entries => {
          var graphWidget = entries[0].target
          if (isset(jeedom.history.chart['div_designGraph' + graphWidget.getAttribute('data-graph_id')])) {
            jeedom.history.chart['div_designGraph' + graphWidget.getAttribute('data-graph_id')].chart.reflow()
          }
        })
        obs.observe(_graph)
        jeeFrontEnd.plan.resizeObservers.push(obs)
      })
    },
    //Edit mode:
    initEditOption: function(_state) {
      var editSelector = '.plan-link-widget, .view-link-widget, .graph-widget, .div_displayObject >.eqLogic-widget'
      editSelector += ', .div_displayObject > .cmd-widget, .scenario-widget, .text-widget, .image-widget, .zone-widget,.summary-widget'
      var editItems = document.querySelectorAll(editSelector)

      if (_state) { //Enter Edit mode
        jeeFrontEnd.planEditOption.state = true
        jeeP.pageContainer.dataset.planEditState = true

        editItems.forEach(item => {
          item.setAttribute('data-lock', '0')
        })

        jeedomUtils.disableTooltips()
        this.planContainer.addClass('editingMode')
        jeedom.cmd.disableExecute = true

        //Set draggies:
        editItems.forEach(item => {
          var draggie = new Draggabilly(item, {
            containment: 'div.div_displayObject',
          })
          jeeFrontEnd.plan.draggables.push(draggie)

          draggie.on('dragStart', function(event, pointer) {

            //Is locked:
            if (this.element.hasClass('locked')) this.dragEnd()

            //Handle zoom:
            this.containementBrect = document.querySelector('div.div_displayObject').getBoundingClientRect()
            this.zoomScale = parseFloat(this.element.getAttribute('data-zoom'))
            if (this.zoomScale != 1) {
              this.options.containment = false
            }

            //Handle grid snap
            if (jeeFrontEnd.planEditOption.grid == 1) {
              this.dragStep = jeeFrontEnd.planEditOption.gridSize[0] / this.zoomScale
              this.element.style.left = getNearestMultiple(this.element.offsetLeft, this.dragStep) + 'px'
              this.element.style.top = getNearestMultiple(this.element.offsetTop, this.dragStep) + 'px'
              this.options.grid = [this.dragStep, this.dragStep]
            } else {
              this.dragStep = false
              this.options.grid = undefined
            }
          })

          draggie.on('dragMove', function(event, pointer, moveVector) {
            //Handle zoom move / containement:
            if (this.zoomScale != 1) {

              //Fix zoomed move:
              this.dragPoint.x = moveVector.x / this.zoomScale
              this.dragPoint.y = moveVector.y / this.zoomScale

              //Check zoomed containement:
              /*Doesn't work
              var eRect = this.element.getBoundingClientRect()
              console.log('eRect:',eRect)

              console.log('position:', this.position)
              console.log(this.dragPoint)

              if (eRect.left < this.containementBrect.left) {
                console.log('>>>>>>> OUT LEFT')
                this.setPosition(this.position.x + this.dragPoint.x, this.position.y + this.dragPoint.y)
                this.pointerDone()
                this.dragEnd()
              }
              */
            }

            /*
            if (this.zoomScale != 1) {
              var matrix = window.getComputedStyle(this.element).getPropertyValue('transform')
              var matrixValues = matrix.match(/matrix.*\((.+)\)/)[1].split(', ')
              var tx = matrixValues[4]
              var ty = matrixValues[5]
              var txFactor = tx * this.zoomScale
              var tyFactor = ty * this.zoomScale
              var deltaX = this.element.offsetLeft - (tx * this.zoomScale)
              if ((this.element.offsetLeft + txFactor) < 0) this.dragEnd()
              if ((this.element.offsetTop + tyFactor) < 0) this.dragEnd()

              var realWidth = this.element.clientWidth * this.zoomScale
              var realRight = this.element.getBoundingClientRect().left + realWidth

              console.log('dragMove realRight:', realRight, 'realWidth:', realWidth, this.containementBrect)

              //Test ok, not solution--
              if (realRight >= this.containementBrect.right) {
                this.dragEnd()
              }
            }
            */

          })

          draggie.on('dragEnd', function(event, pointer) {
            jeeFrontEnd.modifyWithoutSave = true
            //jeeP.savePlan(false, false)
          })
        })

        if (jeeFrontEnd.planEditOption.highlight) {
          editItems.addClass('editingMode')
        } else {
          editItems.removeClass('editingMode', 'contextMenu_select')
        }

        if (jeeFrontEnd.planEditOption.gridSize) {
          document.getElementById('div_grid').seen().style.backgroundSize = jeeFrontEnd.planEditOption.gridSize[0] + 'px ' + jeeFrontEnd.planEditOption.gridSize[1] + 'px'
        } else {
          document.getElementById('div_grid').unseen()
        }

        //Set Resizing:
        let selector = '.plan-link-widget, .view-link-widget, .graph-widget, .div_displayObject >.eqLogic-widget, .scenario-widget, .text-widget, .image-widget, .zone-widget, .summary-widget'
        new jeeResize(selector, {
          cancel: 'locked',
          containment: document.querySelector('.div_displayObject'),
          start: function(event, element) {
            this.zoomScale = parseFloat(element.getAttribute('data-zoom'))

            if (this.zoomScale != 1) {
              element._jeeResize.options.containment = false
            } else {
              element._jeeResize.options.containment = document.querySelector('.div_displayObject')
            }

            //Handle snap:
            if (jeeFrontEnd.planEditOption.grid == 1) {
              this.dragStep = jeeFrontEnd.planEditOption.gridSize[0]
              this.dragStep = this.dragStep / this.zoomScale
            } else {
              this.dragStep = false
            }
          },
          resize: function(event, element) {
            if (this.zoomScale != 1) {
              if (!jeeFrontEnd.plan.isFullScreen && this.hasClass('top')) {
                element.style.top = element.offsetTop - 50 + 'px'
              }
              if (this.hasClass('left')) {
                element.style.left = element.offsetLeft - jeeFrontEnd.plan.planContainer.getBoundingClientRect().left + 'px'
              }
            }
            //Handle snap:
            if (this.dragStep) {
              element.style.width = getNearestMultiple(element.offsetWidth,  this.dragStep) + 'px'
              element.style.height = getNearestMultiple(element.offsetHeight,  this.dragStep) + 'px'
              element.style.top = getNearestMultiple(element.offsetTop,  this.dragStep) + 'px'
              element.style.left = getNearestMultiple(element.offsetLeft,  this.dragStep) + 'px'
            }

            element.querySelector('.camera')?.triggerEvent('resize')
          },
          stop: function(event, ui) {
            jeeFrontEnd.modifyWithoutSave = true
            //jeeP.savePlan(false, false)
          },
        })

        jeeP.elementContexMenu.enable()
      } else { //Leave Edit mode
        if(jeeFrontEnd.planEditOption.state === true){
          jeeP.savePlan(false, false)
        }
        if (jeeP.elementContexMenu) {
          jeeP.elementContexMenu.disable()
        }
        jeeFrontEnd.planEditOption.state = false
        jeeP.pageContainer.dataset.planEditState = false
        jeedom.cmd.disableExecute = false
        this.planContainer.removeClass('editingMode')
        editItems.forEach(item => {
          item.removeAttribute('data-lock')
          item.removeClass('editingMode')
          if (item._jeeResize) item._jeeResize.destroy()
        })
        jeeFrontEnd.plan.draggables.forEach(draggie => {
          let left = draggie.element.style.left
          let top = draggie.element.style.top
          draggie.destroy()
          draggie.element.style.left = left
          draggie.element.style.top = top
        })
        jeeFrontEnd.plan.draggables = []
        try {
          jeedomUtils.enableTooltips()
        } catch (e) {}
        document.getElementById('div_grid').unseen()
      }
    },
    //save Options
    saveOptions: function(_option, _value) {
      setCookie('plan_' + _option, _value, 7)
    },
    //save
    savePlan: function(_refreshDisplay, _async) {
      if (jeephp2js.planHeader_id == -1) return
      domUtils.showLoading()
      var plans = []
      var info, plan, position

      var selector = '.div_displayObject >.eqLogic-widget, .div_displayObject > .cmd-widget, .scenario-widget'
      selector += ', .plan-link-widget, .view-link-widget, .graph-widget, .text-widget, .image-widget, .zone-widget, .summary-widget'
      document.querySelectorAll(selector).forEach(function(_element) {
        info = jeeP.getElementInfo(_element)
        plan = {}
        plan.position = {}
        plan.display = {}
        plan.id = _element.getAttribute('data-plan_id')
        plan.link_type = info.type
        plan.link_id = info.id
        plan.planHeader_id = jeeP.planHeader_id
        plan.display.height = _element.offsetHeight
        plan.display.width = _element.offsetWidth
        if (info.type == 'graph') {
          plan.display.graph = json_decode(_element.querySelector('.graphOptions').jeeValue())
        }

        if (!_element.isVisible()) {
          _element.seen()
          plan.position.top = _element.offsetTop
          plan.position.left = _element.offsetLeft
          _element.unseen()
        } else {
          plan.position.top = _element.offsetTop
          plan.position.left = _element.offsetLeft
        }

        plans.push(plan)
      })
      jeedom.plan.save({
        plans: plans,
        async: _async || true,
        global: false,
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function() {
          if (init(_refreshDisplay, false)) {
            jeeP.displayPlan()
          }
          domUtils.hideLoading()
          jeeFrontEnd.modifyWithoutSave = false
        },
      })
    },
  }
}

jeeFrontEnd.plan.init()

jeeFrontEnd.plan.displayPlan()

jeeFrontEnd.plan.postInit()

//Context menu:
if (jeedomUtils.userDevice.type == 'desktop' && user_isAdmin == 1) {
  //Object context menu

  jeeP.elementContexMenu = new jeeCtxMenu({
    selector: '.div_displayObject > .eqLogic-widget,.div_displayObject > .cmd-widget,.scenario-widget,.plan-link-widget,.text-widget,.view-link-widget,.graph-widget,.image-widget,.zone-widget,.summary-widget',
    zIndex: 9999,
    appendTo: 'div#div_pageContainer',
    isDisable: true,
    events: {
      show: function(opt) {
        opt.setInputValues(opt.realTrigger, opt.realTrigger.dataset)
        if (jeeFrontEnd.planEditOption.highlight) {
          this.ctxMenu.removeClass('editingMode').addClass('contextMenu_select')
        }
      },
      hide: function(opt) {
        opt.getInputValues(opt.realTrigger, opt.realTrigger.dataset)
        if (jeeFrontEnd.planEditOption.highlight) {
          this.ctxMenu.removeClass('contextMenu_select').addClass('editingMode')
        }
      }
    },
    items: {
      title : {
        name: '{{Menu}}',
        icon: 'fas fa-bars',
        disabled:true
      },
      parameter: {
        name: '{{Paramètres d\'affichage}}',
        icon: 'fas fa-cogs',
        callback: function(key, opt) {
          jeeP.savePlan(false, false)
          jeeDialog.dialog({
            id: 'jee_modal',
            title: '{{Configuration du composant}}',
            contentUrl: 'index.php?v=d&modal=plan.configure&id=' + this.getAttribute('data-plan_id')
          })
        }
      },
      configuration: {
        name: '{{Configuration avancée}}',
        icon: 'fas fa-cog',
        disabled: function(key, opt) {
          var info = jeeP.getElementInfo(this)
          return !(info.type == 'eqLogic' || info.type == 'cmd' || info.type == 'graph')
        },
        callback: function(key, opt) {
          var info = jeeP.getElementInfo(this)
          if (info.type == 'graph') {
            var dom_el = this
            if (dom_el.length) dom_el = dom_el[0]
            jeeDialog.dialog({
              id: 'jee_modalGraph',
              title: '{{Configuration avancée}}',
              buttons: {
                confirm: {
                  label: '{{Valider}}',
                  className: 'success',
                  callback: {
                    click: function(event) {
                      var options = jeeFrontEnd.md_cmdGraphSelect.getOptions()

                      dom_el.querySelector('.graphOptions').empty().append(JSON.stringify(options))
                      jeeP.savePlan(true)
                      jeeFrontEnd.plan.setGraphResizes()
                      event.target.closest('#jee_modalGraph')._jeeDialog.destroy()
                    }
                  }
                },
                cancel: {
                  label: '{{Annuler}}',
                  className: 'warning',
                  callback: {
                    click: function(event) {
                      event.target.closest('#jee_modalGraph')._jeeDialog.destroy()
                    }
                  }
                }
              },
              contentUrl: 'index.php?v=d&modal=cmd.graph.select',
              callback: function() {
                document.querySelectorAll('#table_addViewData tbody tr .enable').forEach(_check => { _check.checked = false})
                var options = json_decode(dom_el.querySelector('.graphOptions').jeeValue())
                jeeFrontEnd.md_cmdGraphSelect.displayOptions(options)
              }
            })
          } else {
            jeeDialog.dialog({
              id: 'jee_modal',
              title: '{{Configuration avancée}}',
              contentUrl: 'index.php?v=d&modal=' + info.type + '.configure&' + info.type + '_id=' + info.id
            })
          }
        }
      },
      remove: {
        name: '{{Supprimer}}',
        icon: 'fas fa-trash',
        callback: function(key, opt) {
          jeeP.savePlan(false, false)
          jeedom.plan.remove({
            id: this.getAttribute('data-plan_id'),
            error: function(error) {
              jeedomUtils.showAlert({
                message: error.message,
                level: 'danger'
              })
            },
            success: function() {
              jeeP.displayPlan()
            },
          })
        }
      },
      duplicate: {
        name: '{{Dupliquer}}',
        icon: 'far fa-copy',
        disabled: function(key, opt) {
          var info = jeeP.getElementInfo(this)
          return !(info.type == 'text' || info.type == 'graph' || info.type == 'zone')
        },
        callback: function(key, opt) {
          var info = jeeP.getElementInfo(this)
          jeedom.plan.copy({
            id: this.getAttribute('data-plan_id'),
            version: 'dashboard',
            error: function(error) {
              jeedomUtils.showAlert({
                message: error.message,
                level: 'danger'
              })
            },
            success: function(data) {
              jeeP.displayObject(data.plan, data.html)
            }
          })
        }
      },
      lock: {
        name: "{{Verrouiller}}",
        type: 'checkbox',
        events: {
          click: function(key, opt) {
            if (this.jeeValue() == 1) {
              opt.realTrigger.addClass('locked')
            } else {
              opt.realTrigger.removeClass('locked')
            }
            return true
          }
        }
      },
    }
  })


  //Global context menu
  jeeP.globalContextMenu = new jeeCtxMenu({
    selector: '#div_pageContainer',
    appendTo: 'div#div_pageContainer',
    zIndex: 9999,
    events: {
      show: function(opt) {
        opt.setInputValues(opt.ctxMenu, this.ctxMenu.dataset)
      },
      hide: function(opt) {
        opt.getInputValues(opt.ctxMenu, this.ctxMenu.dataset)
      }
    },
    items: {
      title : {
        name: '{{Menu}}',
        icon: 'fas fa-bars',
        disabled:true
      },
      fold1: {
        name: "{{Designs}}",
        icon: 'far fa-image',
        items: jeeP.planHeaderContextMenu
      },
      edit: {
        name: "{{Edition}}",
        icon: 'fas fa-pencil-alt',
        callback: function(key, opt) {
          //jeeFrontEnd.planEditOption.state = !jeeFrontEnd.planEditOption.state
          this.setAttribute('data-jeeFrontEnd.planEditOption.state', !jeeFrontEnd.planEditOption.state)
          jeeP.initEditOption(!jeeFrontEnd.planEditOption.state)
        }
      },
      fullscreen: {
        name: "{{Plein écran}}",
        icon: 'fas fa-desktop',
        callback: function(key, opt) {
          if (this.getAttribute('data-fullscreen') == null || this.getAttribute('data-fullscreen') == 'false') {
            this.setAttribute('data-fullscreen', 'true')
            jeeP.fullScreen(true)
          } else {
            this.setAttribute('data-fullscreen', 'false')
            jeeP.fullScreen(false)
          }
        }
      },
      sep1: "---------",
      addGraph: {
        name: "{{Ajouter un Graphique}}",
        icon: 'fas fa-chart-line',
        disabled: function(key, opt) {
          return !getBool(this.getAttribute('data-jeeFrontEnd.planEditOption.state'))
        },
        callback: function(key, opt) {
          jeeP.addObject({
            link_type: 'graph',
            link_id: Math.round(Math.random() * 99999999) + 9999
          })
        }
      },
      addText: {
        name: "{{Ajouter du texte/html}}",
        icon: 'fas fa-align-center',
        disabled: function(key, opt) {
          return !getBool(this.getAttribute('data-jeeFrontEnd.planEditOption.state'))
        },
        callback: function(key, opt) {
          jeeP.addObject({
            link_type: 'text',
            link_id: Math.round(Math.random() * 99999999) + 9999,
            display: {
              text: 'Texte à insérer ici'
            }
          })
        }
      },
      addScenario: {
        name: "{{Ajouter un scénario}}",
        icon: 'fas fa-plus-circle',
        disabled: function(key, opt) {
          return !getBool(this.getAttribute('data-jeeFrontEnd.planEditOption.state'))
        },
        callback: function(key, opt) {
          jeedom.scenario.getSelectModal({}, function(data) {
            jeeP.addObject({
              link_type: 'scenario',
              link_id: data.id
            })
          })
        }
      },
      fold4: {
        name: "{{Ajouter un lien}}",
        icon: 'fas fa-link',
        disabled: function(key, opt) {
          return !getBool(this.getAttribute('data-jeeFrontEnd.planEditOption.state'))
        },
        items: {
          addViewLink: {
            name: "{{Vers une vue}}",
            icon: 'fas fa-link',
            disabled: function(key, opt) {
              return !getBool(this.getAttribute('data-jeeFrontEnd.planEditOption.state'))
            },
            callback: function(key, opt) {
              jeeP.addObject({
                link_type: 'view',
                link_id: -(Math.round(Math.random() * 99999999) + 9999),
                display: {
                  name: 'A configurer'
                }
              })
            }
          },
          addPlanLink: {
            name: "{{Vers un design}}",
            icon: 'fas fa-link',
            disabled: function(key, opt) {
              return !getBool(this.getAttribute('data-jeeFrontEnd.planEditOption.state'))
            },
            callback: function(key, opt) {
              jeeP.addObject({
                link_type: 'plan',
                link_id: -(Math.round(Math.random() * 99999999) + 9999),
                display: {
                  name: 'A configurer'
                }
              })
            }
          },
        }
      },
      addEqLogic: {
        name: "{{Ajouter un équipement}}",
        icon: 'fas fa-plus-circle',
        disabled: function(key, opt) {
          return !getBool(this.getAttribute('data-jeeFrontEnd.planEditOption.state'))
        },
        callback: function(key, opt) {
          jeedom.eqLogic.getSelectModal({}, function(data) {
            jeeP.addObject({
              link_type: 'eqLogic',
              link_id: data.id
            })
          })
        }
      },
      addCommand: {
        name: "{{Ajouter une commande}}",
        icon: 'fas fa-plus-circle',
        disabled: function(key, opt) {
          return !getBool(this.getAttribute('data-jeeFrontEnd.planEditOption.state'))
        },
        callback: function(key, opt) {
          jeedom.cmd.getSelectModal({}, function(data) {
            jeeP.addObject({
              link_type: 'cmd',
              link_id: data.cmd.id
            })
          })
        }
      },
      addImage: {
        name: "{{Ajouter une image/caméra}}",
        icon: 'fas fa-plus-circle',
        disabled: function(key, opt) {
          return !getBool(this.getAttribute('data-jeeFrontEnd.planEditOption.state'))
        },
        callback: function(key, opt) {
          jeeP.addObject({
            link_type: 'image',
            link_id: Math.round(Math.random() * 99999999) + 9999
          })
        }
      },
      addZone: {
        name: "{{Ajouter une zone}}",
        icon: 'fas fa-plus-circle',
        disabled: function(key, opt) {
          return !getBool(this.getAttribute('data-jeeFrontEnd.planEditOption.state'))
        },
        callback: function(key, opt) {
          jeeP.addObject({
            link_type: 'zone',
            link_id: Math.round(Math.random() * 99999999) + 9999
          })
        }
      },
      addSummary: {
        name: "{{Ajouter un résumé}}",
        icon: 'fas fa-plus-circle',
        disabled: function(key, opt) {
          return !getBool(this.getAttribute('data-jeeFrontEnd.planEditOption.state'))
        },
        callback: function(key, opt) {
          jeeP.addObject({
            link_type: 'summary',
            link_id: -1
          })
        }
      },
      sep2: "---------",
      fold2: {
        name: "{{Affichage}}",
        icon: 'fas fa-th',
        disabled: function(key, opt) {
          return !getBool(this.getAttribute('data-jeeFrontEnd.planEditOption.state'))
        },
        items: {
          grid_none: {
            name: "{{Aucune}}",
            type: 'radio',
            radio: 'radio',
            value: '0',
            selected: (jeeFrontEnd.planEditOption.gridSize == false) ? true : false,
            events: {
              click: function(e) {
                jeeFrontEnd.planEditOption.gridSize = false
                jeeP.saveOptions('gridSize', '')
                jeeP.initEditOption(1)
                return false
              }
            }
          },
          grid_10x10: {
            name: "10x10",
            type: 'radio',
            radio: 'radio',
            value: '10',
            selected: (jeeFrontEnd.planEditOption.gridSize && jeeFrontEnd.planEditOption.gridSize.includes('10')),
            events: {
              click: function(e) {
                jeeFrontEnd.planEditOption.gridSize = [10, 10]
                jeeP.saveOptions('gridSize', [10, 10])
                jeeP.initEditOption(1)
                return false
              }
            }
          },
          grid_15x15: {
            name: "15x15",
            type: 'radio',
            radio: 'radio',
            value: '15',
            selected: (jeeFrontEnd.planEditOption.gridSize && jeeFrontEnd.planEditOption.gridSize.includes('15')),
            events: {
              click: function(e) {
                jeeFrontEnd.planEditOption.gridSize = [15, 15]
                jeeP.saveOptions('gridSize', [15, 15])
                jeeP.initEditOption(1)
                return false
              }
            }
          },
          grid_20x20: {
            name: "20x20",
            type: 'radio',
            radio: 'radio',
            value: '20',
            selected: (jeeFrontEnd.planEditOption.gridSize && jeeFrontEnd.planEditOption.gridSize.includes('20')),
            events: {
              click: function(e) {
                jeeFrontEnd.planEditOption.gridSize = [20, 20]
                jeeP.saveOptions('gridSize', [20, 20])
                jeeP.initEditOption(1)
                return false
              }
            }
          },
          sep4: "---------",
          snapGrid: {
            name: "{{Aimanter à la grille}}",
            type: 'checkbox',
            radio: 'radio',
            selected: jeeFrontEnd.planEditOption.grid,
            events: {
              click: function(e) {
                jeeFrontEnd.planEditOption.grid = this.jeeValue()
                jeeP.saveOptions('grid', this.jeeValue())
                jeeP.initEditOption(1)
                return false
              }
            }
          },
          highlightWidget: {
            name: "{{Masquer surbrillance des éléments}}",
            type: 'checkbox',
            radio: 'radio',
            selected: !jeeFrontEnd.planEditOption.highlight,
            events: {
              click: function(e) {
                jeeFrontEnd.planEditOption.highlight = (this.jeeValue() == 1) ? false : true
                jeeP.saveOptions('highlight', ((this.jeeValue() == 1) ? 0 : 1))
                jeeP.initEditOption(1)
                return false
              }
            }
          },
        }
      },
      removePlan: {
        name: "{{Supprimer le design}}",
        icon: 'fas fa-trash',
        disabled: function(key, opt) {
          return !getBool(this.getAttribute('data-jeeFrontEnd.planEditOption.state'))
        },
        callback: function(key, opt) {
          jeeDialog.confirm('{{Êtes-vous sûr de vouloir supprimer ce design ?}}', function(result) {
            if (result) {
              jeedom.plan.removeHeader({
                id: jeephp2js.planHeader_id,
                error: function(error) {
                  jeedomUtils.showAlert({
                    message: error.message,
                    level: 'danger'
                  })
                },
                success: function() {
                  jeedomUtils.showAlert({
                    message: 'Design supprimé',
                    level: 'success'
                  })
                  window.location.reload()
                },
              })
            }
          })
        }
      },
      addPlan: {
        name: "{{Créer un design}}",
        icon: 'fas fa-plus-circle',
        disabled: function(key, opt) {
          return !getBool(this.getAttribute('data-jeeFrontEnd.planEditOption.state'))
        },
        callback: function(key, opt) {
          jeeP.createNewDesign()
        }
      },
      duplicatePlan: {
        name: "{{Dupliquer le design}}",
        icon: 'far fa-copy',
        disabled: function(key, opt) {
          return !getBool(this.getAttribute('data-jeeFrontEnd.planEditOption.state'))
        },
        callback: function(key, opt) {
          let name = "";
          for(let i in jeephp2js.planHeader){
            if(jeephp2js.planHeader[i].id == jeephp2js.planHeader_id){
              name = jeephp2js.planHeader[i].name+ " copie";
            }
          }
          jeeDialog.prompt({
            title : "{{Nom de la copie du design ?}}",
            value : name,
          }, function(result) {
            if (result !== null) {
              jeeP.savePlan(false, false)
              jeedom.plan.copyHeader({
                name: result,
                id: jeephp2js.planHeader_id,
                error: function(error) {
                  jeedomUtils.showAlert({
                    message: error.message,
                    level: 'danger'
                  })
                },
                success: function(data) {
                  jeephp2js.planHeader_id = data.id
                  jeedomUtils.loadPage('index.php?v=d&p=plan&plan_id=' + data.id)
                },
              })
            }
          })
        }
      },
      configurePlan: {
        name: "{{Configurer le design}}",
        icon: 'fas fa-cogs',
        disabled: function(key, opt) {
          return !getBool(this.getAttribute('data-jeeFrontEnd.planEditOption.state'))
        },
        callback: function(key, opt) {
          jeeP.savePlan(false, false)
          jeeDialog.dialog({
            id: 'jee_modal',
            title: '{{Configuration du design}}',
            contentUrl: 'index.php?v=d&modal=planHeader.configure&planHeader_id=' + jeephp2js.planHeader_id
          })
        }
      },
      sep3: "---------",
      save: {
        name: "{{Sauvegarder}}",
        icon: 'fas fa-save',
        callback: function(key, opt) {
          jeeP.savePlan()
        }
      },
    }
  })
}

jeedomUI.setEqSignals()
jeedomUI.setHistoryModalHandler()

//Handle zones:
document.body.registerEvent('click', function (event) {
  if (!jeeFrontEnd.planEditOption.state) {
    if ((!event.target.hasClass('.zone-widget.zoneEqLogic') && event.target.closest('.zone-widget.zoneEqLogic') == null) && (!event.target.hasClass('.zone-widget.zoneEqLogicOnFly') && event.target.closest('.zone-widget.zoneEqLogicOnFly') == null)) {
      document.querySelectorAll('.zone-widget.zoneEqLogic').forEach(function(_zone) {
        if (_zone.hasClass('zoneEqLogicOnClic') || _zone.hasClass('zoneEqLogicOnFly')) {
          _zone.empty()
          jeeP.clickedOpen = false
        }
      })
    }
  }
})

//div_pageContainer events delegation:
document.getElementById('div_pageContainer').addEventListener('mousedown', function(event) {
  if (jeeFrontEnd.planEditOption.state === true && event.target.closest('div.jeeCtxMenu') == null) {
    event.preventDefault()
    return
  }
})
document.getElementById('div_pageContainer').addEventListener('click', function(event) {
  if (jeeFrontEnd.planEditOption.state === true && event.target.closest('div.jeeCtxMenu') == null) {
    event.preventDefault()
    return
  }
  var _target = null
  if (_target = event.target.closest('#bt_createNewDesign')) {
    jeeP.createNewDesign()
    return
  }

  if (_target = event.target.closest('.view-link-widget')) {
    var link = _target.querySelector('a')
    link.click()
    return
  }

  if (_target = event.target.closest('.plan-link-widget')) {
    if (!jeeFrontEnd.planEditOption.state) {
      var linkId = _target.getAttribute('data-link_id')
      if (linkId == undefined) return
      jeephp2js.planHeader_id = linkId
      jeeP.displayPlan()
    }
    return
  }

  if (_target = event.target.closest('.zone-widget:not(.zoneEqLogic)')) {
    if (!jeeFrontEnd.planEditOption.state) {
      _target.insertAdjacentHTML('beforeend', '<center class="loading"><i class="fas fa-spinner fa-spin fa-4x"></i></center>')
      jeedom.plan.execute({
        id: _target.getAttribute('data-plan_id'),
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
          _target.empty().insertAdjacentHTML('beforeend', '<center class="loading"><i class="fas fa-times fa-4x"></i></center>')
          setTimeout(function() {
            _target.empty()
            jeeP.clickedOpen = false
          }, 3000)
        },
        success: function() {
          _target.empty()
          jeeP.clickedOpen = false
        },
      })
    }
    return
  }

  if (_target = event.target.closest('.zone-widget.zoneEqLogic.zoneEqLogicOnClic')) {
    if (!jeeFrontEnd.planEditOption.state && !jeeP.clickedOpen) {
      jeeP.clickedOpen = true
      jeedom.eqLogic.toHtml({
        id: _target.getAttribute('data-eqLogic_id'),
        version: 'dashboard',
        global: false,
        success: function(data) {
          if (data.html) {
            _target.html(data.html, true)
            let inserted = _target.childNodes[0]
            inserted.style = inserted.style.cssText + _target.getAttribute('data-position')
            inserted.style.position = 'absolute'
            jeedomUtils.positionEqLogic(_target.getAttribute('data-eqLogic_id'), false)
            jeedomUtils.initTooltips(inserted)
          }
        }
      })
    }
  }

}, {buble: true})

document.querySelector('.div_displayObject').addEventListener('mouseenter', function(event) {
  if (event.target.matches('.zone-widget.zoneEqLogic.zoneEqLogicOnFly')) {
    if (!jeeFrontEnd.planEditOption.state && event.target.getAttribute('data-flying') != '1') {
      var el = event.target
      el.setAttribute('data-flying', '1')
      jeeP.clickedOpen = true
      jeedom.eqLogic.toHtml({
        id: el.getAttribute('data-eqLogic_id'),
        version: 'dashboard',
        global: false,
        success: function(data) {
          if (data.html) {
            el.html(data.html, true)
            let inserted = el.childNodes[0]
            inserted.style = inserted.style.cssText + el.getAttribute('data-position')
            inserted.style.position = 'absolute'
            jeedomUtils.positionEqLogic(el.getAttribute('data-eqLogic_id'), false)
            jeedomUtils.initTooltips(inserted)
          }
        }
      })
    }
    return
  }
}, {capture: true})

document.querySelector('.div_displayObject').addEventListener('mouseleave', function(event) {
  if (event.target.matches('.zone-widget.zoneEqLogic.zoneEqLogicOnFly')) {
    if (event.target.getAttribute('data-flying') == '1') {
      event.target.setAttribute('data-flying', '0')
      event.target.empty()
      jeeP.clickedOpen = false
    }
    return
  }
}, {capture: true})

//Event for App Mobile:
document.body.addEventListener('jeeObject::summary::update', function(_event) {
  for (var i in _event.detail) {
    if(isset(_event.detail[i].force) && _event.detail[i].force == 1) continue
    if(_event.detail[i].object_id == 'global') {
      /* SEND UPDATE SUMMARY TO APP */
      jeedom.appMobile.postToApp('updateSummary', _event.detail[i].keys)
    }
  }
})

//back to mobile home with three fingers on mobile:
if (user_isAdmin == 1 && jeedomUtils.userDevice.type != 'desktop') {
  document.body.registerEvent('touchstart', function (event) {
    if (event.touches.length == 3) {
      event.preventDefault()
      event.stopPropagation()
      window.location.href = 'index.php?v=m'
    }
  })
}
