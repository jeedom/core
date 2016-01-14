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
    require_once(dirname(__FILE__) . '/../../core/php/core.inc.php');
    include_file('core', 'authentification', 'php');

    if (!isConnect()) {
        throw new Exception(__('401 - Accès non autorisé', __FILE__));
    }

    if (init('action') == 'remove') {
        if (!isConnect('admin')) {
            throw new Exception(__('401 - Accès non autorisé', __FILE__));
        }
        $object = object::byId(init('id'));
        if (!is_object($object)) {
            throw new Exception(__('Objet inconnu verifié l\'id', __FILE__));
        }
        $object->remove();
        ajax::success();
    }

    if (init('action') == 'byId') {
        $object = object::byId(init('id'));
        if (!is_object($object)) {
            throw new Exception(__('Objet inconnu verifié l\'id : ', __FILE__) . init('id'));
        }
        ajax::success(utils::o2a($object));
    }

    if (init('action') == 'all') {
        ajax::success(utils::o2a(object::buildTree()));
    }

    if (init('action') == 'save') {
        if (!isConnect('admin')) {
            throw new Exception(__('401 - Accès non autorisé', __FILE__));
        }
        $object_json = json_decode(init('object'), true);
        if (isset($object_json['id'])) {
            $object = object::byId($object_json['id']);
        }
        if (!isset($object) || !is_object($object)) {
            $object = new object();
        }
        utils::a2o($object, $object_json);
        $object->save();
        ajax::success(utils::o2a($object));
    }

    if (init('action') == 'uploadImage') {
        $object = object::byId(init('id'));
        if (!is_object($object)) {
            throw new Exception(__('Objet inconnu verifié l\'id', __FILE__));
        }
        if (!isset($_FILES['file'])) {
            throw new Exception(__('Aucun fichier trouvé. Vérifié parametre PHP (post size limit)', __FILE__));
        }
        $extension = strtolower(strrchr($_FILES['file']['name'], '.'));
        if (!in_array($extension, array('.jpg', '.png'))) {
            throw new Exception('Extension du fichier non valide (autorisé .jpg .png) : ' . $extension);
        }
        if (filesize($_FILES['file']['tmp_name']) > 5000000) {
            throw new Exception(__('Le fichier est trop gros (miximum 5mo)', __FILE__));
        }
        $object->setImage('type', str_replace('.', '', $extension));
        $object->setImage('size', getimagesize($_FILES['file']['tmp_name']));
        $object->setImage('data', base64_encode(file_get_contents($_FILES['file']['tmp_name'])));
        $object->save();
        ajax::success();
    }

    if (init('action') == 'getChild') {
        $object = object::byId(init('id'));
        if (!is_object($object)) {
            throw new Exception(__('Objet inconnu verifié l\'id', __FILE__));
        }
        $return = utils::o2a($object->getChild());
        ajax::success($return);
    }

    if (init('action') == 'toHtml') {
        if (init('id') == 'all' || is_json(init('id'))) {
            if (is_json(init('id'))) {
                $object_ajax = json_decode(init('id'), true);
                $objects = array();
                foreach ($object_ajax as $id) {
                    $objects[] = object::byId($id);
                }
            } else {
                $objects = object::all();
            }
            $return = array();
            foreach ($objects as $object) {
                if (is_object($object) && $object->getIsVisible() == 1) {
                    $html = '';
                    foreach ($object->getEqLogic() as $eqLogic) {
                        if ($eqLogic->getIsVisible() == '1') {
                            $html .= $eqLogic->toHtml(init('version'));
                        }
                    }
                    $return[$object->getId()] = $html;
                }
            }
            ajax::success($return);
        } else {
            $object = object::byId(init('id'));
            if (!is_object($object)) {
                throw new Exception(__('Objet inconnu verifié l\'id', __FILE__));
            }
            $html = '';
            foreach ($object->getEqLogic() as $eqLogic) {
                if ($eqLogic->getIsVisible() == '1') {
                    $html .= $eqLogic->toHtml(init('version'));
                }
            }
            ajax::success($html);
        }
    }

    if (init('action') == 'setOrder') {
        if (!isConnect('admin')) {
            throw new Exception(__('401 - Accès non autorisé', __FILE__));
        }
        $position = 1;
        foreach (json_decode(init('objects'), true) as $id) {
            $object = object::byId($id);
            if (is_object($object)) {
                $object->setPosition($position);
                $object->save();
                $position++;
            }
        }
        ajax::success();
    }

    throw new Exception(__('Aucune methode correspondante à : ', __FILE__) . init('action'));
    /*     * *********Catch exeption*************** */
} catch (Exception $e) {
    ajax::error(displayExeption($e), $e->getCode());
}
?>
