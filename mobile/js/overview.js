"use strict"

$('body').attr('data-page', 'overview')

function initOverview() {
  jeedom.object.all({
    error: function(error) {
      $.fn.showAlert({message: error.message, level: 'danger'})
    },
    success: function(objects) {
      $('#objectOverviewContainer').empty()
      let summaries = []
      let _this, icon, _backUrl, div, synthAction, dataPage, dataOption, dataTitle
      for (let i in objects) {
        if (objects[i].isVisible == 1 && objects[i].configuration.hideOnOverview != 1) {
          _this = objects[i]
          icon = ''
          if (isset(_this.display) && isset(_this.display.icon)) {
            icon = _this.display.icon
          }
          _backUrl = _this.img
          if (_backUrl == '') {
            jeedom.config.load({
              configuration: 'product_synthese_image',
              success: function(data) {
                if(data) {
                  var elements = document.getElementsByClassName("objectPreview")
                  for (var i = 0; i < elements.length; i++) {
                    elements[i].style.background='url('+data+')';
                  }
                }
              }
            });
            _backUrl = 'core/img/background/jeedom_abstract_04_light.jpg'
          }

          synthAction = _this.configuration.synthToAction
          let name = (_this.configuration.display_name && _this.configuration.display_name != '') ? _this.configuration.display_name : _this.name;
          if (synthAction != undefined && synthAction != "-1" && synthAction != 'synthToDashboard') {
            if (synthAction == 'synthToView') {
              dataPage = 'view'
              dataOption = _this.configuration.synthToView
              dataTitle = '{{Vue}}'
            }
            if (synthAction == 'synthToPlan') {
              dataPage = 'index.php?v=d&p=plan&plan_id=' + _this.configuration.synthToPlan
              dataOption = ''
              dataTitle = '{{Design}}'
            }
            if (synthAction == 'synthToPlan3d') {
              dataPage = 'index.php?v=d&p=plan3d&plan3d_id=' + _this.configuration.synthToPlan3d
              dataOption = ''
              dataTitle = '{{Design 3D}}'
            }
          } else {
            dataPage = 'equipment';
            dataOption = _this.id
            dataTitle = icon.replace(/\"/g, "\'") + ' ' + name.replace(/\"/g, "\'")
          }
          div = '<div class="objectPreview cursor shadowed fullCorner" style="background:url('+_backUrl+')" data-object_id="'+dataOption+'" data-page="' + dataPage + '" data-option="'+dataOption+'" data-page="equipment" data-title="' + dataTitle + '">'
            div += '<div class="topPreview topCorner">'
              div += '<span class="name">'+icon +' '+name+'</span>'
            div += '</div>'
            div += '<div class="bottomPreview bottomCorner">'
              div += '<div class="resume" style="display:none;">'
              div += '<span class="objectSummaryContainer objectSummary'+_this.id+'" data-version="mobile"></span>'
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
        document.querySelectorAll('.objectPreview').forEach(function(element) {
          var parent = element.querySelector('.topPreview')
          element.querySelectorAll('.objectSummaryParent[data-summary="temperature"], .objectSummaryParent[data-summary="motion"], .objectSummaryParent[data-summary="security"], .objectSummaryParent[data-summary="humidity"]').forEach(function(el) {
            parent.appendChild(el)
          })
          element.querySelectorAll('.objectSummaryParent[data-summary="security"], .objectSummaryParent[data-summary="motion"]')?.last()?.addClass('last')
          if (element.querySelector('.objectSummaryParent[data-summary="temperature"]') != null && element.querySelector('.objectSummaryParent[data-summary^="temp"]') != null) {
            parent.appendChild(element.querySelector('.objectSummaryParent[data-summary^="temp"]'))
          }
        })
        document.querySelectorAll('.resume').seen()
        createSummaryObserver()
      }, 250)

      $('.objectPreview').off('click').on('click', function(event) {
        if (event.target !== this) return
        if ($(event.target).hasClass('topPreview') || $(event.target).hasClass('name')) return
        jeedomUtils.loadModal(false)
        jeedomUtils.loadPanel(false)
        if ($(this).data('option').toString() == '') {
          window.location.href = $(this).data('page')
        } else {
          jeedomUtils.loadPage($(this).data('page'), $(this).data('title'), $(this).data('option').toString())
        }
      })

      $('.objectPreview .name').off('click').on('click', function(event) {
        event.preventDefault()
        jeedomUtils.loadModal(false)
        jeedomUtils.loadPanel(false)
        jeedomUtils.loadPage('equipment', '<i class=\'fas fa-globe\'></i> {{Tous}}', 'all')
      })
      jeedomUtils.hideLoading()
    }
  })

  if (plugins.length > 0) {
    let nb_plugin = 0;
    for (var i in plugins) {
      if (plugins[i].mobile == '') continue
      if (plugins[i].displayMobilePanel == 0) continue
      nb_plugin++;
    }
    var panel = '<center>'
    for (var i in plugins) {
      if (plugins[i].mobile == '') continue
      if (plugins[i].displayMobilePanel == 0) continue
      panel += '<a href="#" class="link ui-btn ui-btn-inline ui-btn-raised ui-mini" data-page="' + plugins[i].mobile + '" data-plugin="' + plugins[i].id + '" data-title="' + plugins[i].name + '">'
      if(nb_plugin < 4){
        panel +=  plugins[i].name
      }else{
        panel += '<img src="plugins/'+plugins[i].id +'/plugin_info/'+plugins[i].id +'_icon.png" onerror=\'this.style.display = "none"\' style="height:25px" /> '
      }
      panel +=  '</a> '
    }
    panel +=  '</center>'
    $('#panelOverviewContainer').empty().html(panel);
  }
}

function createSummaryObserver() {
  var _SummaryObserver_ = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
      if (mutation.type == 'childList' && mutation.target.hasClass('objectSummaryContainer')) {
        try {
          updateSummary(mutation.addedNodes[0].className)
        } catch {}
      }
    })
  })

  let observerConfig = {
    attributes: false,
    childList: true,
    characterData: false,
    subtree: true
  }

  let targetNode = document.getElementById('objectOverviewContainer')
  if (targetNode) _SummaryObserver_.observe(targetNode, observerConfig)
}

function updateSummary(_className) {
  _className = _className.replace('objectSummaryContainer ', '')
  var parent = document.querySelector('.' + _className).closest('.objectPreview')
  if (parent == null) return
  parent.querySelector('.topPreview')?.querySelectorAll('.objectSummaryParent')?.remove()
  var pResume = parent.querySelector('.resume')
  if (pResume == null) return
  pResume.querySelectorAll('.objectSummaryParent[data-summary="temperature"], .objectSummaryParent[data-summary="motion"], .objectSummaryParent[data-summary="security"], .objectSummaryParent[data-summary="humidity"]').forEach(function(element) {
    parent.querySelector('.topPreview').appendChild(element)
  })
  parent.querySelectorAll('.objectSummaryParent[data-summary="security"], .objectSummaryParent[data-summary="motion"]')?.last()?.addClass('last')
  if (pResume.querySelector('.objectSummaryParent[data-summary="temperature"]') != null && pResume.querySelector('.objectSummaryParent[data-summary^="temp"]') != null ) {
    parent.find('.topPreview').appendChild(pResume.querySelector('.objectSummaryParent[data-summary^="temp"]'))
  }
}
