<?php

/*
  Uploadify v3.1.0
  Copyright (c) 2012 Reactive Apps, Ronnie Garcia
  Released under the MIT License <http://www.opensource.org/licenses/mit-license.php>
 */
die();
require_once dirname(__FILE__) . "/../../core/php/core.inc.php";
if (!empty($_FILES) && isset($_GET['id']) && isset($_GET['type'])) {
    $fileTypes = array('jpg', 'jpeg', 'gif', 'png'); // File extensions
    $fileParts = pathinfo($_FILES['Filedata']['name']);
    if (in_array(strtolower($fileParts['extension']), $fileTypes)) {
        switch ($_GET['type']) {
            case "eqLogic" :
                $eqLogic = eqLogic::byId($_GET['id']);
                if (!is_object($eqLogic)) {
                    error_log('EqLogic inconnu verifié l\'id');
                    return false;
                }
                $eqLogic->saveImage(getUploadedImage(200, 150));
                break;
            case "object" :
                $object = object::byId($_GET['id']);
                if (!is_object($object)) {
                    error_log('Object inconnu verifié l\'id');
                    return false;
                }
                $object->saveImage(getUploadedImage());
                break;
            default :
                echo 'Erreur code 03';
                break;
        }
        echo '1';
    } else {
        echo 'Type de fichier invalide.';
    }
}

function getUploadedImage($width = null, $height = null) {
    $ret = false;
    $img_taille = 0;
    $img_nom = '';
    $taille_max = 20000000;
    $ret = is_uploaded_file($_FILES['Filedata']['tmp_name']);
    if (!$ret) {
        error_log('Problème de transfert (Erreur php : ' . $_FILES['Filedata'] . ')');
        return false;
    } else {
        // Le fichier a bien été reçu
        $img_taille = $_FILES['Filedata']['size'];
        if ($img_taille > $taille_max) {
            error_log("Trop gros !");
            return false;
        }
        $img_nom = $_FILES['Filedata']['name'];
    }
    // Redimensionnement de l'image
    // Hauteur image destination
    $source_file = $_FILES['Filedata']['tmp_name']; // Fichier d'origine
    // Cacul des nouvelles dimensions

    $fileParts = pathinfo($_FILES['Filedata']['name']);
    if ((substr_count(strtolower($fileParts['extension']), "jpg") > 0) || (substr_count($fileParts['extension'], "jpeg") > 0)) {
        $source_image = imagecreatefromjpeg($source_file);
    } else if (substr_count(strtolower($fileParts['extension']), "png") > 0) {
        $source_image = imagecreatefrompng($source_file);
    } else if (substr_count(strtolower($fileParts['extension']), "gif") > 0) {
        $source_image = imagecreatefromgif($source_file);
    } else {
        return false;
    }

    if ($width == null && $height == null) {
        ob_start();
        imagejpeg($source_image);
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;
    } else {
        return resizeImage($source_image, $width, $height);
    }
}

?>