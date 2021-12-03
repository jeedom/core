<?php
if (!isConnect('admin')) {
  throw new Exception('{{401 - Accès non autorisé}}');
}
?>

<div id="div_rowSystemCommand" class="row row-overflow">
  <div class="col-lg-2 col-md-3 col-sm-4" style="overflow: hidden auto;">
    <div class="bs-sidebar">
      <ul class="nav nav-list bs-sidenav list-group" id='ul_listSystemHistory'></ul>
      <ul class="nav nav-list bs-sidenav list-group">
        <li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%" /></li>
        <?php if (jeedom::isCapable('sudo')) { ?>
          <li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command="sudo rm -f /var/lib/dpkg/updates/*">Fix dpkg</a></li>
          <li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command='echo "yes | sudo apt -f install" | sudo at now'>Fix install</a></li>
          <li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command='echo "sudo dpkg --configure -a --force-confdef" | sudo at now'>Dpkg configure</a></li>
          <li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command="sudo ../../health.sh">health.sh</a></li>
        <?php } ?>
        <li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command="ps -eo size,pid,user,command --sort -size">Memory Usage</a></li>
        <li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command="ps -eo pid,ppid,cmd,%mem,%cpu --sort=-%cpu">CPU Usage</a></li>
        <li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command="sudo dmesg">dmesg</a></li>
        <li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command="sudo ip addr">ip addr</a></li>
        <li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command="sudo lsusb">lsusb</a></li>
        <li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command="sudo ls -la /dev/ttyUSB*">ls -la /dev/ttyUSB*</a></li>
        <li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command="free -m">free -m</a></li>
        <li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command="sudo ps ax">ps ax</a></li>
        <?php if (jeedom::isCapable('sudo')) { ?>
          <li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command="sudo cat /var/log/mysql/error.log">MySQL log</a></li>
          <li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command='sudo fdtput -t s /media/boot/multiboot/meson64_odroidc2.dtb.linux /i2c@c1108500/pcf8563@51 status "okay"'>RTC Jeedom Pro</a></li>
        <?php } ?>
        <li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command="df -h">df -h</a></li>
        <li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command="w">w</a></li>
        <li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command="dpkg -l">dpkg -l</a></li>
        <li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command="netstat -tupln"> netstat -tupln</a></li>
        <li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command='cp /var/www/html/install/apache_security_unsecure /etc/apache2/conf-available/security.conf;echo "systemctl restart apache2" | sudo at now'> {{Apache non securisé}}</a></li>
        <li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command='cp /var/www/html/install/apache_security /etc/apache2/conf-available/security.conf;echo "systemctl restart apache2" | sudo at now'> {{Apache sécurisé}}</a></li>
      </ul>
    </div>
  </div>
  <div class="col-lg-10 col-md-9 col-sm-8" style="overflow: hidden auto">
    <div class="alert alert-info" id="h3_executeCommand">{{Cliquez sur une commande à gauche ou tapez une commande personnalisée ci-dessous}}</div>
    <div class="input-group">
      <input id="in_specificCommand" class="form-control roundedLeft" />
      <div class="input-group-btn">
        <a id="bt_validateSpecifiCommand" class="btn btn-warning roundedRight"><i class="fas fa-check"></i> {{OK}}</a>
      </div>
    </div>
    <pre id="pre_commandResult" style="height: calc(100% - 110px); width: 100%; margin-top: 5px;"></pre>
  </div>
</div>

<?php include_file("desktop", "system", "js"); ?>