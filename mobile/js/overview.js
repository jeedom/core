var _SummaryObserver_ = null

$('body').attr('data-page', 'overview')

function initOverview() {
  jeedom.object.all({
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'})
    },
    success: function (objects) {
      $('#objectOverviewContainer').empty()
      var summaries = []
      for (var i in objects) {
        if (objects[i].isVisible == 1 && objects[i].configuration.hideOnOverview != 1) {
          var _this = objects[i]
          var icon = ''
          if (isset(_this.display) && isset(_this.display.icon)) {
            icon = _this.display.icon
          }
          var _backUrl = _this.img
          if (_backUrl == '') {
            _backUrl = 'core/img/background/jeedom_abstract_04_light.jpg'
          }
          var div = '<div class="objectPreview cursor shadowed fullCorner" style="background:url('+_backUrl+')" data-option="'+_this.id+'" data-page="equipment" data-title="' + icon.replace(/\"/g, "\'") + ' ' + _this.name.replace(/\"/g, "\'") + '">'
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
        $('.objectPreview').each(function() {
          var parent = $(this).find('.topPreview')
          $(this).find('.objectSummaryParent[data-summary="temperature"], .objectSummaryParent[data-summary="motion"], .objectSummaryParent[data-summary="security"], .objectSummaryParent[data-summary="humidity"]').each(function() {
            $(this).detach().appendTo(parent)
          })
          $(this).find('.resume').find('.objectSummaryParent').eq(-7).after("<br />")
        })

        $('.resume').show()
        colorizeSummary()
        createSummaryObserver()
      }, 500)

      $('.objectPreview').off('click').on('click', function (event) {
        if (event.target !== this) return
        modal(false)
        panel(false)
        page('equipment', $(this).data('title'), $(this).data('option').toString())
      })
    }
  })
}

function colorizeSummary() {
  $('.objectPreview .objectSummarysecurity, .objectPreview .objectSummarymotion').each(function() {
    var value = $(this).html()
    if (value == 0) {
      $(this).closest('.objectSummaryParent').addClass('success')
    } else {
      $(this).closest('.objectSummaryParent').addClass('danger')
    }
  })
}

function createSummaryObserver() {
  var _SummaryObserver_ = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
      if ( mutation.type == 'childList' && mutation.target.className == 'resume') {
        updateSummary(mutation.addedNodes[0].className)
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
  _SummaryObserver_.observe(targetNode, observerConfig)
}

function updateSummary(_className) {
  var parent = $('.'+_className).closest('.objectPreview')
  parent.find('.topPreview').find('.objectSummaryParent').remove()
  parent.find('.resume').find('.objectSummaryParent[data-summary="temperature"], .objectSummaryParent[data-summary="motion"], .objectSummaryParent[data-summary="security"], .objectSummaryParent[data-summary="humidity"]').each(function() {
      $(this).detach().appendTo(parent.find('.topPreview'))
    })
  parent.find('.resume').find('.objectSummaryParent').eq(-7).after("<br />")
  colorizeSummary()
}