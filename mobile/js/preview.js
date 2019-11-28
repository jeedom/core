$('body').attr('data-page', 'preview')
function initPreview() {
  jeedom.object.all({
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'})
    },
    success: function (objects) {
      $('#objectPreviewContainer').empty()
      var summaries = []
       for (var i in objects) {
        if (objects[i].isVisible == 1) {
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
              div += '<span class="name">'+_this.name+'</span>'
            div += '</div>'
            div += '<div class="bottomPreview bottomCorner">'
              div += '<div class="resume">'
              div += '<span class="objectSummary'+_this.id+'" data-version="mobile"></span>'
              div += '</div>'
            div += '</div>'
          div += '</div>'

          $('#objectPreviewContainer').append(div)
          summaries.push({object_id : _this.id})
        }
      }
      jeedom.object.summaryUpdate(summaries)

      $('.objectPreview').off('click').on('click', function (event) {
        if (event.target !== this) return
        modal(false)
        panel(false)
        page('equipment', $(this).data('title'), $(this).data('option').toString())
      })
    }
  })
}
