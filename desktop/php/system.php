<?php
if (!isConnect('admin')) {
  throw new Exception('{{401 - Accès non autorisé}}');
}
?>
<style>
.bs-sidenav .list-group-item{
  padding : 2px 2px 2px 2px;
}
</style>
<div id="div_rowSystemCommand" class="row row-overflow">
  <div class="col-lg-2 col-md-3 col-sm-4" style="overflow-y:auto;overflow-x:hidden;">
    <a class="btn btn-warning" style="width:100%;margin-bottom:4px" id="bt_consitency"><i class="fas fa-check"></i> {{Consistance}}</a>
    <a class="btn btn-warning" style="width:100%" id="bt_cleanFileSystemRight"><i class="fas fa-check"></i> {{Droit}}</a>
    <div class="bs-sidebar">
      <ul class="nav nav-list bs-sidenav list-group" id='ul_listSystemHistory'></ul>
      <ul class="nav nav-list bs-sidenav list-group">
        <li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
        <?php if (jeedom::isCapable('sudo')) {?>
          <li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command="sudo rm -f /var/lib/dpkg/updates/*">Fix dpkg</a></li>
                    <li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command='echo "yes | sudo apt -f install" | sudo at now'>Fix install</a></li>
          <li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command='echo "yes | sudo dpkg --configure -a" | sudo at now'>Dpkg configure</a></li>  
        <li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command="sudo ../../health.sh">health.sh</a></li>
        <?php }?>
        <li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command="ps -eo size,pid,user,command --sort -size">Memory Usage</a></li>
        <li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command="ps -eo pid,ppid,cmd,%mem,%cpu --sort=-%cpu">CPU Usage</a></li>
        <li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command="dmesg">dmesg</a></li>
        <li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command="ifconfig">ifconfig</a></li>
        <li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command="lsusb">lsusb</a></li>
        <li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command="ls -la /dev/ttyUSB*">ls -la /dev/ttyUSB*</a></li>
        <li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command="free -m">free -m</a></li>
        <li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command="ps ax">ps ax</a></li>
        <?php if (jeedom::isCapable('sudo')) {?>
          <li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command="sudo cat /var/log/mysql/error.log">MySQL log</a></li>
          <li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command='sudo fdtput -t s /media/boot/multiboot/meson64_odroidc2.dtb.linux /i2c@c1108500/pcf8563@51 status "okay"'>RTC Jeedom Pro</a></li>
        <?php }?>
        <li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command="df -h">df -h</a></li>
        <li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command="w">w</a></li>
        <li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command="dpkg -l">dpkg -l</a></li>
        <li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command="netstat -tupln"> netstat -tupln</a></li>
      </ul>
    </div>
  </div>
  <div class="col-lg-10 col-md-9 col-sm-8" style="border-left: solid 1px #EEE; padding-left: 25px;overflow-y:hidden;overflow-x:hidden;">
    
    <h3 id="h3_executeCommand">{{Cliquez sur une commande à droite ou tapez une commande personnalisée ci-dessous}}</h3>
    <input id="in_specificCommand" class="form-control" style="width:90%;display:inline-block;" /> <a id="bt_validateSpecifiCommand" class="btn btn-warning" style="position:relative;top:-2px;"><i class="fas fa-check"></i> {{OK}}</a>
    <pre id="pre_commandResult" style="height : calc(100% - 110px);width:100%;margin-top:5px;"></pre>
  </div>
</div>
<?php include_file("desktop", "system", "js");?>
