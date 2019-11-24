<?php

/** @entrypoint */
/** @ajax */

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

require_once __DIR__ . '/ajax.handler.inc.php';

ajaxHandle(function ()
{
  ajax::checkAccess('admin');

  if (init('action') == 'all') {
    return utils::o2a(widgets::all());
  }
  
  if (init('action') == 'byId') {
    $widget = widgets::byId(init('id'));
    $result = utils::o2a($widget);
    $result['usedBy'] = array();
    $usedBy = $widget->getUsedBy();
    if(is_array($usedBy) && count($usedBy) > 0){
      foreach ($usedBy as $cmd) {
        $result['usedBy'][$cmd->getId()] = $cmd->getHumanName();
      }
    }
    return $result;
  }
  
  if (init('action') == 'remove') {
    $widgets = widgets::byId(init('id'));
    if(!is_object($widgets)){
      throw new Exception(__('Widgets inconnue - Vérifiez l\'id', __FILE__).init('id'));
    }
    $widgets->remove();
    return '';
  }
  
  if (init('action') == 'save') {
    unautorizedInDemo();
    $widgets_json = json_decode(init('widgets'), true);
    if (isset($widgets_json['id'])) {
      $widgets = widgets::byId($widgets_json['id']);
    }
    if (!isset($widgets) || !is_object($widgets)) {
      $widgets = new widgets();
    }
    utils::a2o($widgets, $widgets_json);
    $widgets->save();
    return utils::o2a($widgets);
  }
  
  if (init('action') == 'getTemplateConfiguration') {
    return widgets::getTemplateConfiguration(init('template'));
  }
  
  if (init('action') == 'getPreview') {
    $widget = widgets::byId(init('id'));
    $usedBy = $widget->getUsedBy();
    if(!is_array($usedBy) || count($usedBy) == 0){
      return array('html' => '<div class="alert alert-warning">'.__('Aucune commande affectée au widget, prévisualisation impossible',__FILE__).'</div>');
    }
    return array('html' =>$usedBy[0]->getEqLogic()->toHtml('dashboard'));
  }
  
  if (init('action') == 'replacement') {
    return widgets::replacement(init('version'),init('replace'),init('by'));
  }
  
  throw new Exception(__('Aucune méthode correspondante à : ', __FILE__) . init('action'));
  
  /*     * *********Catch exeption*************** */
});
