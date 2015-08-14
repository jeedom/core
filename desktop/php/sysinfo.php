<?php
if (!hasRight('sysinfo', true)) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
?>
<iframe id="frame_sysinfo" src="<?php echo jeedom::getCurrentSysInfoFolder()?>/index.php" style="width : 100%;height : 1200px;border : none;"></iframe>

<script>
  var hWindow = $(window).height() - $('header').height() - $('footer').height() - 50;
  $('#frame_sysinfo').height(hWindow);
</script>