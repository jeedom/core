<?php

if (!isConnect()) {
  throw new Exception('{{401 - Accès non autorisé}}');
}

$scenario = scenario::byId(init('scenario_id'));
if (!is_object($scenario)) {
  throw new Exception('{{Scénario introuvable}}');
}
?>
<a class="btn btn-success pull-right bt_downloadScenario"><i class="fa fa-download"></i> {{Télécharger}}</a>
<a class="btn btn-success pull-right bt_copyScenario"><i class="fa fa-copy"></i> {{Copier}}</a>
<br><br>
<?php
echo '<textarea id="scExport" style="height:calc(100% - 40px);width:100%">' . $scenario->export() . '</textarea>';
?>

<script>
$('.bt_downloadScenario').on('click',function(){
  var content = $('#scExport').text()
  content = content.replace(/\n/g, "\r\n")
  
  dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(content)
  downloadAnchorNode = document.createElement('a')
  downloadAnchorNode.setAttribute("href",     dataStr)
  downloadAnchorNode.setAttribute("target", "_blank")
  downloadAnchorNode.setAttribute("download", 'scenario.txt')
  document.body.appendChild(downloadAnchorNode)
  downloadAnchorNode.click()
  downloadAnchorNode.remove()
})

$('.bt_copyScenario').on('click',function(){
  $('#scExport').select()
  document.execCommand("copy")
  $('#scExport').blur()
})
</script>
