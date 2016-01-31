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
  <div id="div_rowSystemCommand" class="row">
  	<div class="col-lg-2 col-md-3 col-sm-4" style="overflow-y:auto;overflow-x:hidden;">
  		<div class="bs-sidebar">
  			<ul class="nav nav-list bs-sidenav list-group">
  				<li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
  				<li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command="sudo dmesg 2>&1">dmesg</a></li>
  				<li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command="sudo ifconfig 2>&1">ifconfig</a></li>
  				<li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command="sudo lsusb 2>&1">lsusb</a></li>
  				<li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command="sudo ls -la /dev/ttyUSB* 2>&1">ls -la /dev/ttyUSB*</a></li>
  				<li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command="sudo free -m 2>&1">free -m</a></li>
  				<li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command="sudo ps ax 2>&1">ps ax</a></li>
  				<li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command="sudo cat /var/log/mysql/error.log 2>&1">MySQL log</a></li>
  			</ul>
  		</div>
  	</div>
  	<div class="col-lg-10 col-md-9 col-sm-8" style="border-left: solid 1px #EEE; padding-left: 25px;overflow-y:hidden;overflow-x:hidden;">
  		<h3 id="h3_executeCommand">{{Cliquez sur une commande à droite}}</h3>
  		<pre id="pre_commandResult" style="width:100%"></pre>
  	</div>
  </div>

  <script>
  	var hWindow = $('#div_rowSystemCommand').parent().outerHeight() - 30;
  	$('#div_rowSystemCommand > div').height(hWindow);
  	$('#pre_commandResult').height(hWindow - 80);
  	$('.bt_systemCommand').off('click').on('click',function(){
  		var command = $(this).attr('data-command');
  		$('#pre_commandResult').empty();
  		if($(this).parent().hasClass('list-group-item-danger')){
  			bootbox.confirm('{{Etes-vous sûr de vouloir éxécuter cette commande : }}<strong>'+command+'</strong> ? {{Celle-ci est classé en dangereuse}}', function (result) {
  				if (result) {
  					jeedom.ssh({
  						command : command,
  						success : function(log){
  							$('#h3_executeCommand').empty().append('{{Commande : }}'+command);
  							$('#pre_commandResult').append(log);
  						}
  					})
  				}
  			});
  		}else{
  			jeedom.ssh({
  				command : command,
  				success : function(log){
  					$('#h3_executeCommand').empty().append('{{Commande : }}'+command);
  					$('#pre_commandResult').append(log);
  				}
  			})
  		}
  	});
  </script>
