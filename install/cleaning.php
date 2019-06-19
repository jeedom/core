<?php

/* This file is part of Jeedom.
*
* Jeedom is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Jeedom is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
*/

if (php_sapi_name() != 'cli' || isset($_SERVER['REQUEST_METHOD']) || !isset($_SERVER['argc'])) {
  header("Statut: 404 Page non trouvée");
  header('HTTP/1.0 404 Not Found');
  $_SERVER['REDIRECT_STATUS'] = 404;
  echo "<h1>404 Non trouvé</h1>";
  echo "La page que vous demandez ne peut être trouvée.";
  exit();
}
set_time_limit(1800);

echo "[START CLEANING]\n";
if (isset($argv)) {
  foreach ($argv as $arg) {
    $argList = explode('=', $arg);
    if (isset($argList[0]) && isset($argList[1])) {
      $_GET[$argList[0]] = $argList[1];
    }
  }
}
try {
  require_once __DIR__ . '/../core/php/core.inc.php';
  
  $cmdClean = array(
    'display' => array(
      'showOnmobile',
      'showOnview',
      'showOnplan',
      'showOndashboard',
      'showNameOnplan',
      'showNameOnview',
      'showIconAndNameplan',
      'showIconAndNameview',
      'generic_type'
    )
  );
  
  $eqLogicClean = array(
    'display' => array(
      'showObjectNameOnview',
      'showObjectNameOndview',
      'showObjectNameOnmview'
    )
  );
  
  $removeFolders = array(
    __DIR__ . '/../3rdparty/font-awesome',
    __DIR__ . '/../manifest.json',
    __DIR__ . '/../manifest.webmanifest',
    __DIR__ . '/../core/css/core.css',
    __DIR__ . '/../js/plugin.ajax.js',
    __DIR__ . '/../core/themes/amber',
    __DIR__ . '/../core/themes/blue',
    __DIR__ . '/../core/themes/brown',
    __DIR__ . '/../core/themes/cyan',
    __DIR__ . '/../core/themes/darksobre',
    __DIR__ . '/../core/themes/deep_orange',
    __DIR__ . '/../core/themes/deep_purple',
    __DIR__ . '/../core/themes/green',
    __DIR__ . '/../core/themes/grey',
    __DIR__ . '/../core/themes/light_blue',
    __DIR__ . '/../core/themes/light_green',
    __DIR__ . '/../core/themes/lime',
    __DIR__ . '/../core/themes/orange',
    __DIR__ . '/../core/themes/pink',
    __DIR__ . '/../core/themes/purple',
    __DIR__ . '/../core/themes/red',
    __DIR__ . '/../core/themes/teal',
    __DIR__ . '/../core/themes/yellow',
    __DIR__ . '/../desktop/css/commun.css',
    __DIR__ . '/../desktop/css/dashboard.css',
    __DIR__ . '/../desktop/css/futur.css',
    __DIR__ . '/../desktop/modal/eqLogic.displayWidget.php',
    __DIR__ . '/../desktop/modal/remove.history.php',
    __DIR__ . '/../mobile/css/commun.css',
  );
  
  $nb_cleaning = 0;
  foreach (cmd::all() as $cmd) {
    if(!is_object($cmd->getEqLogic())){
      echo 'Remove cmd because no eqLogic found : '.$cmd->getHumanName()."\n";
      $cmd->remove(true);
      continue;
    }
    echo 'Cleaning cmd : '.$cmd->getHumanName()."\n";
    $displays = $cmd->getDisplay();
    foreach ($displays as $key => $value) {
      if($value === ''){
        $cmd->setDisplay($key,null);
        $nb_cleaning++;
        continue;
      }
      if(is_array($value) && count($value) == 0){
        $cmd->setDisplay($key,null);
        continue;
      }
      if(in_array($key,$cmdClean['display'])){
        $cmd->setDisplay($key,null);
        $nb_cleaning++;
        continue;
      }
    }
    
    $configurations = $cmd->getConfiguration();
    foreach ($configurations as $key => $value) {
      if($value === ''){
        $cmd->setConfiguration($key,null);
        continue;
      }
      if(is_array($value) && count($value) == 0){
        $cmd->setConfiguration($key,null);
        continue;
      }
    }
    $cmd->save(true);
  }
  
  foreach (eqLogic::all() as $eqLogic) {
    echo 'Cleaning eqLogic : '.$eqLogic->getHumanName()."\n";
    $displays = $eqLogic->getDisplay();
    foreach ($displays as $key => $value) {
      if($value === ''){
        $eqLogic->setDisplay($key,null);
        continue;
      }
      if(is_array($value) && count($value) == 0){
        $eqLogic->setDisplay($key,null);
        continue;
      }
      if(in_array($key,$eqLogicClean['display'])){
        $eqLogic->setDisplay($key,null);
        $nb_cleaning++;
        continue;
      }
      if(strpos($key,'layout::mobile') !== false){
        $eqLogic->setDisplay($key,null);
        $nb_cleaning++;
        continue;
      }
    }
    
    if($eqLogic->getDisplay('layout::dashboard') != 'table'){
      $displays = $eqLogic->getDisplay();
      foreach ($displays as $key => $value) {
        if(strpos($key,'layout::') === 0){
          $eqLogic->setDisplay($key,null);
          $nb_cleaning++;
          continue;
        }
      }
    }
    
    $configurations = $eqLogic->getConfiguration();
    foreach ($configurations as $key => $value) {
      if($value === ''){
        $eqLogic->setConfiguration($key,null);
        continue;
      }
      if(is_array($value) && count($value) == 0){
        $eqLogic->setConfiguration($key,null);
        continue;
      }
    }
    $eqLogic->save(true);
  }
  
  foreach ($removeFolders as $folder) {
    if(file_exists($folder)){
      rrmdir($folder);
    }
  }
  
  
}catch (Exception $e) {
  echo "\nError : ";
  echo $e->getMessage();
}

echo "[END CLEANING]\n";
