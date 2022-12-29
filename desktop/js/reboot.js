var rebooti = '0';
var testjeedom = '0';
var redirect = 25;
var ip = window.location.protocol+'//'+window.location.hostname;
if(window.location.port != ''){
    ip = ip+':'+window.location.port;
  }

jeeDialog.confirm('{{Êtes-vous sûr de vouloir redémarrer le système ?}}', function (result) {
  if (result) {
    jeedom.rebootSystem();
    setTimeout(reboot_jeedom, 15000);
    $('#progressbar_reboot').width('5%');
  } else {
    jeedomUtils.loadPage(ip+'/index.php?v=d&p=dashboard');
  }
});

function redirectIP(){
      redirect++;
      new ping(ip, function (status, e) {
          console.log(status);
          if(redirect == 100){
            $('#div_reboot_jeedom_texte').empty().html('<h6><?php echo config::byKey("product_name"); ?> {{Impossible de trouver la box suite au redémarrage ...}}</h6>');
          }else{
            if(status == 'timeout'){
              $('#progressbar_reboot').width(redirect+'%');
              setTimeout(function () {
                redirectIP();
              }, 2000);
            }else if(status == 'responded'){
              $('#div_reboot_jeedom_texte').empty().html('<h6><?php echo config::byKey("product_name"); ?> {{est de nouveau opérationnel. Vous allez être redirigé vers votre dashboard. Cela peut prendre environ 30 secondes.}}</h6>');
              $('#progressbar_reboot').addClass('progress-bar-success').removeClass('progress-bar-danger');
              $('#progressbar_reboot').width('75%');
              setTimeout("$('#progressbar_reboot').width('100%');", 29500);
              setTimeout(returnToJeedom, 30000);
            }
          }
      });
}

function returnToJeedom(){
  top.location.href=ip+'/index.php?v=d&p=dashboard';
}

function reboot_jeedom(){
  $('#div_reboot_jeedom_texte').empty().html('<h6>{{Merci de patienter...}}<br /><?php echo config::byKey("product_name"); ?> {{est en cours de redémarrage.}}</h6>');
  $('#progressbar_reboot').width('25%');
  redirectIP();
}

function ping(ip, callback) {
      if (!this.inUse) {
          this.status = 'unchecked';
          this.inUse = true;
          this.callback = callback;
          this.ip = ip;
          var _that = this;
          this.img = new Image();
          this.img.onload = function () {
              _that.inUse = false;
              _that.callback('responded');

          };
          this.img.onerror = function (e) {
              if (_that.inUse) {
                  _that.inUse = false;
                  _that.callback('responded', e);
              }

          };
          this.start = new Date().getTime();
          this.img.src = ip;
          this.timer = setTimeout(function () {
              if (_that.inUse) {
                  _that.inUse = false;
                  _that.callback('timeout');
              }
          }, 1500);
      }
    }
