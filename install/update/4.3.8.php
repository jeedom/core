<?php
//
// FOR MAJ JEEDOM LUNA
// 
//
require_once __DIR__ . '/../../core/php/core.inc.php';

$hostname = shell_exec('cat /etc/hostname');

if (strpos($hostname, 'Luna') !== false) {
    echo 'Mise à jour pour la Jeedom Luna Uniquement \n';
    config::save('hardware_name', "Luna");

    echo 'Vérification si Jeeasy est bien versionné \n';
    $update = update::byLogicalId('jeeasy');
    if(is_object($update)){
        if(strpos($update->getLocalVersion(), '202') == false){
            echo 'Mise à jour de Jeeasy avant de continuer \n';
            $update->doUpdate();
        }else{
            echo 'Tout est ok sur le versionning du plugin \n';
        }
    }else{
        echo 'Jeeasy n\'est pas installé sur cette box Luna \n';
    }
    echo "Fin de la mise a niveau de Jeeasy pour la Luna \n";
}
?>
