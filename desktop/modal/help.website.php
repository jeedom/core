<?php

if (!isConnect()) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
$url = 'https://jeedom.fr/' . init('page');

$ch = curl_init();
curl_setopt_array($ch, array
    (
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => TRUE,
    CURLOPT_HEADER => FALSE,
    CURLOPT_FOLLOWLOCATION => TRUE,
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 10
));
$response = curl_exec($ch);

if (curl_errno($ch) || strpos($response, '404 Not Found') !== false) {
    curl_close($ch);
    echo '<div class="alert alert-warning">{{Aucune aide n\'existe pour le moment sur cette page}}</div>';
} else {
    curl_close($ch);
    echo str_replace('<img src="', '<img src="https://jeedom.fr/', $response);
}
?>


