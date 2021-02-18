<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}

?>

<legend id="modalTitle"></legend>
<div id="modalDisplay" style="margin-top:15px;"></div>

<?php include_file('desktop', 'modaldisplay', 'js');?>