<?php

/** @entrypoint */
/** @console */

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

require_once dirname(__DIR__).'/core/php/console.php';
set_time_limit(1800);

echo "[START CLEANING]\n";

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
  
  
  $sql = 'select cmd_id from history group by cmd_id';
  $results1 = DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL);
  $sql = 'select cmd_id from historyArch group by cmd_id';
  $results2 = DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL);
  $cmd_histories = array_flip(array_column($results1,'cmd_id')) + array_flip(array_column($results2,'cmd_id'));
  foreach ($cmd_histories as $id => $value) {
    $cmd = cmd::byId($id);
    if(is_object($cmd) && $cmd->getIsHistorized() == 1){
      continue;
    }
    $values = array('cmd_id' => $id);
    echo 'Remove history for cmd : '.$id."\n";
    $sql = 'delete from history where cmd_id=:cmd_id';
    DB::Prepare($sql,$values, DB::FETCH_TYPE_ROW);
    $sql = 'delete from historyArch where cmd_id=:cmd_id';
    DB::Prepare($sql,$values, DB::FETCH_TYPE_ROW);
  }
  
  
}catch (Exception $e) {
  echo "\nError : ";
  echo $e->getMessage();
}

echo "[END CLEANING]\n";
