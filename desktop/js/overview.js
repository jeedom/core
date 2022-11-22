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

if (!jeeFrontEnd.overview) {
  jeeFrontEnd.overview = {
    $summaryContainer: null,
    summaryObjEqs: [],
    _SummaryObserver_: null,
    observerConfig: {
      attributes: true,
      childList: true,
      characterData: true,
      subtree: true
    },
    init: function() {
      window.jeeP = this
    },
    postInit: function() {
      this.$summaryContainer = $('#summaryEqlogics')
      this.modal = this.$summaryContainer.parents('.ui-dialog.ui-resizable')
      this.modalContent = this.modal.find('.ui-dialog-content.ui-widget-content')
      this.checkResumeEmpty()
      this.createSummaryObserver()
    },
    createSummaryObserver: function() {
      this._SummaryObserver_ = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
          if (mutation.type == 'childList' && mutation.target.className == 'resume') {
            try {
              jeeP.updateSummary(mutation.addedNodes[0].className)
            } catch {}
          }
        })
      })

      var targetNode = document.getElementById('objectOverviewContainer')
      if (targetNode) this._SummaryObserver_.observe(targetNode, this.observerConfig)
    },
    checkResumeEmpty: function() {
      var button
      $('.objectPreview ').each(function() {
        if (!$(this).find('.objectSummaryParent').length) {
          button = '<span class="bt_config"><i class="fas fa-cogs"></i></span>'
          $(this).find('.bt_config').remove()
          $(this).find('.topPreview').append(button)
        }
      })
    },
    updateSummary: function(_className) {
      _className = _className.replace('objectSummaryContainer ', '')
      var parent = $('.' + _className).closest('.objectPreview')
      var pResume = parent.find('.resume')
      parent.find('.topPreview').find('.objectSummaryParent').remove()
      pResume.find('.objectSummaryParent[data-summary="temperature"], .objectSummaryParent[data-summary="motion"], .objectSummaryParent[data-summary="security"], .objectSummaryParent[data-summary="humidity"]').each(function() {
        $(this).detach().appendTo(parent.find('.topPreview'))
      })
      parent.find('.objectSummaryParent[data-summary="security"], .objectSummaryParent[data-summary="motion"]').last().addClass('last')
      if (pResume.find('.objectSummaryParent[data-summary="temperature"]').length == 0 && pResume.find('.objectSummaryParent[data-summary^=temp]').length > 0) {
        pResume.find('.objectSummaryParent[data-summary^=temp]').first().detach().appendTo(parent.find('.topPreview'))
      }
      this.checkResumeEmpty()
    },
    getSummaryHtml: function(_object_id, _summary, _title) {
      this.summaryObjEqs[_object_id] = []
      self = this
      jeedom.object.getEqLogicsFromSummary({
        id: _object_id,
        onlyEnable: '1',
        onlyVisible: '0',
        version: 'dashboard',
        summary: _summary,
        error: function(error) {
          $.fn.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          try {
            self.$summaryContainer.empty().packery('destroy')
          } catch (e) {}

          _title = $.parseHTML('<span>' + _title + '</span>')
          jeeP.$modal.parent('.ui-dialog').find('span.ui-dialog-title').empty().append(_title)
          jeeP.$modal.dialog('open')

          var nbEqs = data.length
          for (var i = 0; i < nbEqs; i++) {
            if (self.summaryObjEqs[_object_id].includes(data[i].id)) {
              nbEqs--
              return
            }
            self.summaryObjEqs[_object_id].push(data[i].id)

            jeedom.eqLogic.toHtml({
              id: data[i].id,
              version: 'dashboard',
              error: function(error) {
                $.fn.showAlert({
                  message: error.message,
                  level: 'danger'
                })
              },
              success: function(html) {
                if (html.html != '') {
                  self.$summaryContainer.append(html.html)
                }
                nbEqs--

                //is last ajax:
                if (nbEqs == 0) {
                  //adapt modal size:
                  var brwSize = {
                    width: window.innerWidth || document.body.clientWidth,
                    height: window.innerHeight || document.body.clientHeight
                  }
                  var fullWidth = 0
                  var fullHeight = 0
                  var thisWidth = 0
                  var thisHeight = 0

                  $('#md_overviewSummary div.eqLogic-widget').each(function(index) {
                    thisWidth = $(this).outerWidth(true)
                    thisHeight = $(this).outerHeight(true)
                    if (fullHeight == 0 || fullHeight < thisHeight + 5) fullHeight = thisHeight + 5
                    if ((fullWidth + thisWidth + 150) < brwSize.width) {
                      fullWidth += thisWidth + 7
                    } else {
                      fullHeight += thisHeight + 5
                    }
                  })

                  if (fullWidth == 0) {
                    fullWidth = 120
                    fullHeight = 120
                  }

                  fullWidth += 26
                  fullHeight += 51
                  jeeP.$modal.dialog({
                    width: fullWidth,
                    height: fullHeight
                  })

                  self.$summaryContainer.packery({
                    gutter: parseInt(jeedom.theme['widget::margin']) * 2,
                    isLayoutInstant: true
                  })

                  //check is inside screen:
                  var modalLeft = parseInt(self.modal[0].style['left'])
                  if (modalLeft + fullWidth + 26 > brwSize.width || modalLeft < 5) {
                    self.modal.css('left', brwSize.width - fullWidth - 50)
                  }

                  jeedomUtils.initTooltips($('#md_overviewSummary'))
                }
              }
            })
          }
        }
      })
    },
  }
}

jeeFrontEnd.overview.init()

$(function() {
  //infos/actions tile signals:
  jeedomUI.isEditing = false
  jeedomUI.setEqSignals()

  //move to top summary:
  $('.objectPreview').each(function() {
    var parent = $(this).find('.topPreview')
    $(this).find('.objectSummaryParent[data-summary="temperature"], .objectSummaryParent[data-summary="motion"], .objectSummaryParent[data-summary="security"], .objectSummaryParent[data-summary="humidity"]').each(function() {
      $(this).detach().appendTo(parent)
    })

    $(this).find('.objectSummaryParent[data-summary="security"], .objectSummaryParent[data-summary="motion"]').last().addClass('last')

    if ($(this).find('.objectSummaryParent[data-summary="temperature"]').length == 0 && $(this).find('.objectSummaryParent[data-summary^=temp]').length > 0) {
      $(this).find('.objectSummaryParent[data-summary^=temp]').first().detach().appendTo(parent)
    }
  })

  jeeFrontEnd.overview.postInit()
  $('.resume').show()

  //summary modal events:
  jeeP.$summaryContainer.packery()
  jeeP.modal.resize(function() {
    jeeP.$summaryContainer.packery()
  })
  jeeP.modalContent.off().on('click', function(event) {
    if (!$(event.target).parents('.eqLogic-widget').length) {
      jeeP.$modal.dialog('close')
    }
  })
  //history in summary modal:
  jeeP.modalContent.on({
    'click': function(event) {
      event.stopImmediatePropagation()
      event.stopPropagation()
      if (event.ctrlKey || event.metaKey) {
        var cmdIds = []
        $(this).closest('div.eqLogic-widget').find('.history[data-cmd_id]').each(function() {
          cmdIds.push($(this).data('cmd_id'))
        })
        cmdIds = cmdIds.join('-')
      } else {
        var cmdIds = $(this).closest('.history[data-cmd_id]').data('cmd_id')
      }
      $('#md_modal2').dialog({
        title: "{{Historique}}"
      }).load('index.php?v=d&modal=cmd.history&id=' + cmdIds).dialog('open')
    }
  }, 'div.eqLogic-widget .history')
})

//buttons:
$('#div_pageContainer').on({
  'click': function(event) {
    var objectId = $(this).closest('.objectPreview').data('object_id')
    var url = 'index.php?v=d&p=object&id=' + objectId + '#summarytab'
    jeedomUtils.loadPage(url)
  }
}, '.objectPreview .bt_config')

$('#objectOverviewContainer').on({
  'click': function(event) {
    //action summary:
    if (event.ctrlKey) return

    event.stopPropagation()
    event.preventDefault()
    var objectId = $(this).closest('.objectPreview').attr('data-object_id')
    var summaryType = $(this).attr('data-summary')
    var icon = $(this).get(0).firstChild.outerHTML
    if (icon) {
      var title = icon + ' ' + $(this).closest('.objectPreview').find('.topPreview .name').text()
    } else {
      var title = $(this).closest('.objectPreview').find('.topPreview .name').text()
    }
    jeeP.getSummaryHtml(objectId, summaryType, title)
  }
}, '.objectSummaryParent')


//Tile click or center-click
$('.objectPreview').off('click').on('click', function(event) {
  if (event.target !== this && !$(event.target).hasClass('bottomPreview')) return
  var url = $(this).attr('data-url')
  if (event.ctrlKey || event.metaKey) {
    window.open(url).focus()
  } else {
    jeedomUtils.loadPage(url)
  }
  return false
})
$('.objectPreview').off('mouseup').on('mouseup', function(event) {
  if (event.which == 2) {
    var target = event.target
    if ($(target).hasClass('topPreview') || $(target).hasClass('name')) return
    if (target !== this && !$(target).hasClass('bottomPreview')) {
      target = $(target).closest('.objectSummaryParent')
      var url = 'index.php?v=d&p=dashboard&summary=' + target.data('summary') + '&object_id=' + $(this).data('object_id') + '&childs=0'
      window.open(url).focus()
    } else {
      event.preventDefault()
      var id = $(this).attr('data-object_id')
      $('.objectPreview[data-object_id="' + id + '"]').trigger(jQuery.Event('click', {
        ctrlKey: true
      }))
    }
  }
})

//Tile name click or center-click
$('.objectPreview .name').off('click').on('click', function(event) {
  var url = 'index.php?v=d&p=dashboard&object_id=' + $(this).closest('.objectPreview').attr('data-object_id')
  if (event.ctrlKey || event.metaKey) {
    window.open(url).focus()
  } else {
    jeedomUtils.loadPage(url)
  }
  return false
})
$('.objectPreview .name').off('mouseup').on('mouseup', function(event) {
  if (event.which == 2) {
    event.preventDefault()
    var id = $(this).closest('.objectPreview').attr('data-object_id')
    $('.objectPreview[data-object_id="' + id + '"] .name').trigger(jQuery.Event('click', {
      ctrlKey: true
    }))
  }
})

//Dialog summary opening:
jeeP.$modal = $("#md_overviewSummary")
jeeP.$modal.dialog({
  closeText: '',
  autoOpen: false,
  modal: true,
  width: 500,
  height: 200,
  position: {
    my: 'center top',
    at: 'center center-100',
    of: $('#div_pageContainer')
  },
  open: function() {
    $('.ui-widget-overlay.ui-front').css('display', 'none')
    //catch infos updates by main mutationobserver (jeedomUtils.loadPage disconnect/reconnect it):
    if (jeedomUtils.OBSERVER) {
      var summaryModal = document.getElementById('summaryEqlogics')
      jeedomUtils.OBSERVER.observe(summaryModal, jeedomUtils.observerConfig)
    }
  },
  beforeClose: function(event, ui) {
    $('.ui-widget-overlay.ui-front').css('display')
  }
})