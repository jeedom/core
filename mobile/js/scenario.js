function initScenario() {
  jeedom.scenario.toHtml({
    id: 'all',
    version: 'mobile',
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function (htmls) {
      $('#div_displayScenario').empty().html(htmls).trigger('create');
      setTileSize('.scenario');
      $('#div_displayScenario').packery({gutter : 0});
    }
  });
  
  $(window).on("resize", function (event) {
    setTileSize('.scenario');
    $('#div_displayScenario').packery({gutter : 0});
  });
  
  $('#in_searchWidget').off('keyup').on('keyup',function(){
    var search = $(this).value();
    if(search == ''){
      $('.scenario-widget').show();
      $('#div_displayScenario').packery();
      return;
    }
    $('.scenario-widget').each(function(){
      var match = false;
      if(match || $(this).find('.widget-name').text().toLowerCase().indexOf(search.toLowerCase()) >= 0){
        match = true;
      }
      if(match){
        $(this).show();
      }else{
        $(this).hide();
      }
    });
    $('#div_displayScenario').packery();
  });
  
  
  $('#bt_eraseSearchInput').off('click').on('click',function(){
    $('#in_searchWidget').val('');
    $('#in_searchWidget').keyup();
  })
}
