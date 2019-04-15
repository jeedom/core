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

try {
  
  require_once __DIR__ . '/../../core/php/core.inc.php';
  include_file('core', 'authentification', 'php');
  
  if (!isConnect('admin')) {
    throw new Exception(__('401 - Accès non autorisé', __FILE__), -1234);
  }
  
  ajax::init(true);
  
  if (init('action') == 'all') {
    ajax::success(utils::o2a(widgets::all()));
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
    ajax::success($result);
  }
  
  if (init('action') == 'remove') {
    $widgets = widgets::byId(init('id'));
    if(!is_object($widgets)){
      throw new Exception(__('Widgets inconnue - Vérifiez l\'id', __FILE__).init('id'));
    }
    $widgets->remove();
    ajax::success();
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
    ajax::success(utils::o2a($widgets));
  }
  
  if (init('action') == 'getTemplateConfiguration') {
    ajax::success(widgets::getTemplateConfiguration(init('template')));
  }
  
  /*     * *********Catch exeption*************** */
} catch (Exception $e) {
  ajax::error(displayException($e), $e->getCode());
}
