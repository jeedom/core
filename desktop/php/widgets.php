<?php
if (!isConnect('admin')) {
  throw new Exception('{{401 - Accès non autorisé}}');
}
?>






<?php include_file("desktop", "widgets", "js");?>
