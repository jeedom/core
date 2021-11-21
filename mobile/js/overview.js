"use strict"

$('body').attr('data-page', 'overview')

function initOverview() {
  jeedom.object.all({
    error: function(error) {
      $.fn.showAlert({message: error.message, level: 'danger'})
    },
    success: function(objects) {
      $.clearDivContent('objectOverviewContainer')
      var summaries = []
      var _this, icon, _backUrl, div
      for (var i in objects) {
        if (objects[i].isVisible == 1 && objects[i].configuration.hideOnOverview != 1) {
          _this = objects[i]
          icon = ''
          if (isset(_this.display) && isset(_this.display.icon)) {
            icon = _this.display.icon
          }
          _backUrl = _this.img
          if (_backUrl == '') {
            _backUrl = 'core/img/background/jeedom_abstract_04_light.jpg'
          }
          div = '<div class="objectPreview cursor shadowed fullCorner" style="background:url('+_backUrl+')" data-option="'+_this.id+'" data-page="equipment" data-title="' + icon.replace(/\"/g, "\'") + ' ' + _this.name.replace(/\"/g, "\'") + '">'
            div += '<div class="topPreview topCorner">'
              div += '<span class="name">'+icon +' '+_this.name+'</span>'
            div += '</div>'
            div += '<div class="bottomPreview bottomCorner">'
              div += '<div class="resume" style="display:none;">'
              div += '<span class="objectSummary'+_this.id+'" data-version="mobile"></span>'
              div += '</div>'
            div += '</div>'
          div += '</div>'

          $('#objectOverviewContainer').append(div)
          summaries.push({object_id : _this.id})
        }
      }
      jeedom.object.summaryUpdate(summaries)

      setTimeout(function() {
        //move to top summary:
        var parent
        $('.objectPreview').each(function() {
          parent = $(this).find('.topPreview')
          $(this).find('.objectSummaryParent[data-summary="temperature"], .objectSummaryParent[data-summary="motion"], .objectSummaryParent[data-summary="security"], .objectSummaryParent[data-summary="humidity"]').each(function() {
            $(this).detach().appendTo(parent)
          })

          $(this).find('.objectSummaryParent[data-summary="security"], .objectSummaryParent[data-summary="motion"]').last().addClass('last')

          if ($(this).find('.objectSummaryParent[data-summary="temperature"]').length == 0 && $(this).find('.objectSummaryParent[data-summary^=temp]').length > 0) {
            $(this).find('.objectSummaryParent[data-summary^=temp]').first().detach().appendTo(parent)
          }

          //get bigger if too much summaries:
          if ($(this).find('.bottomPreview .objectSummaryParent').length > 18) $(this).addClass('big')
        })

        $('.resume').show()
        createSummaryObserver()
      }, 500)

      $('.objectPreview').off('click').on('click', function(event) {
        if (event.target !== this) return
      if ($(event.target).hasClass('topPreview') || $(event.target).hasClass('name')) return
        jeedomUtils.loadModal(false)
        jeedomUtils.loadPanel(false)
        jeedomUtils.loadPage('equipment', $(this).data('title'), $(this).data('option').toString())
      })

      $('.objectPreview .name').off('click').on('click', function(event) {
        event.preventDefault()
        jeedomUtils.loadModal(false)
        jeedomUtils.loadPanel(false)
        jeedomUtils.loadPage('equipment', '<i class=\'fas fa-globe\'></i> {{Tous}}', 'all')
      })
    }
  })
}

function createSummaryObserver() {
  var _SummaryObserver_ = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
      if (mutation.type == 'childList' && mutation.target.className == 'resume') {
        try {
          updateSummary(mutation.addedNodes[0].className)
        } catch {}
      }
    })
  })

  var observerConfig = {
    attributes: true,
    childList: true,
    characterData: true,
    subtree: true
  }

  var targetNode = document.getElementById('objectOverviewContainer')
  if (targetNode) _SummaryObserver_.observe(targetNode, observerConfig)
}

function updateSummary(_className) {
  _className = _className.replace('objectSummaryContainer ', '')
  var parent = $('.'+_className).closest('.objectPreview')
  var pResume = parent.find('.resume')
  parent.find('.topPreview').find('.objectSummaryParent').remove()
  pResume.find('.objectSummaryParent[data-summary="temperature"], .objectSummaryParent[data-summary="motion"], .objectSummaryParent[data-summary="security"], .objectSummaryParent[data-summary="humidity"]').each(function() {
    $(this).detach().appendTo(parent.find('.topPreview'))
  })
  parent.find('.objectSummaryParent[data-summary="security"], .objectSummaryParent[data-summary="motion"]').last().addClass('last')
  if (pResume.find('.objectSummaryParent[data-summary="temperature"]').length == 0 && pResume.find('.objectSummaryParent[data-summary^=temp]').length > 0) {
    pResume.find('.objectSummaryParent[data-summary^=temp]').first().detach().appendTo(parent.find('.topPreview'))
  }
}