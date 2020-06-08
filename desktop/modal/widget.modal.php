<?php
if (!isConnect('admin')) {
  throw new Exception('{{401 - Accès non autorisé}}');
}

$data = init('data');
$type = init('type');
?>
<div class="form-group">
<?php
if ($type=='image'){
	$imageData = base64_encode(file_get_contents($data));
	$src = 'data: '.mime_content_type($data).';base64,'.$imageData;
	echo '<img src="' . $src . '" alt="" style="height: 100%; width: 100%; object-fit: contain">';
}
?>
</div>

