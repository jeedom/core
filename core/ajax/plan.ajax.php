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

    if (init('action') == 'save') {
        $plans = json_decode(init('plans'), true);
        foreach ($plans as $plan_ajax) {
            @$plan = plan::byId($plan_ajax['id']);
            if (!is_object($plan)) {
                $plan = plan::byLinkTypeLinkIdPlanHedaerId($plan_ajax['link_type'], $plan_ajax['link_id'], $plan_ajax['planHeader_id']);
                if (!is_object($plan)) {
                    $plan = new plan();
                }
            }
            utils::a2o($plan, $plan_ajax);
            $plan->save();
        }
        ajax::success();
    }

    if (init('action') == 'planHeader') {
        $return = array();
        foreach (plan::byPlanHeaderId(init('planHeader_id')) as $plan) {
            if ($plan->getLink_type() == 'eqLogic' || $plan->getLink_type() == 'scenario') {
                $link = $plan->getLink();
                if (!is_object($link)) {
                    continue;
                }
                $return[] = array(
                    'plan' => utils::o2a($plan),
                    'html' => $link->toHtml(init('version', 'dashboard'))
                );
            } else if ($plan->getLink_type() == 'plan') {
                $plan_link = planHeader::byId($plan->getLink_id());
                if (!is_object($plan_link)) {
                    continue;
                }
                $link = 'index.php?v=d&p=plan&plan_id=' . $plan_link->getId();
                $html = '<span class="plan-link-widget label label-success" data-link_id="' . $plan_link->getId() . '" data-offsetX="' . $plan->getDisplay('offsetX') . '" data-offsetY="' . $plan->getDisplay('offsetY') . '">';
                $html .= '<a href="' . $link . '" style="color:' . $plan->getCss('color', 'white') . ';text-decoration:none;font-size : 1.5em;">';
                if ($plan->getDisplay('name') != '' || $plan->getDisplay('icon') != '') {
                    $html .=$plan->getDisplay('icon') . ' ' . $plan->getDisplay('name');
                } else {
                    $html .= $plan_link->getName();
                }
                $html .= '</a>';
                $html .= '</span>';
                $return[] = array(
                    'plan' => utils::o2a($plan),
                    'html' => $html
                );
            } else if ($plan->getLink_type() == 'view') {
                $view = view::byId($plan->getLink_id());
                if (!is_object($view)) {
                    continue;
                }
                $link = 'index.php?v=d&p=view&view_id=' . $view->getId();
                $html = '<span href="' . $link . '" class="view-link-widget label label-primary" data-link_id="' . $view->getId() . '" >';
                $html .= '<a href="' . $link . '" style="color:' . $plan->getCss('color', 'white') . ';text-decoration:none;font-size : 1.5em;">';
                if ($plan->getDisplay('name') != '' || $plan->getDisplay('icon') != '') {
                    $html .= $plan->getDisplay('icon') . ' ' . $plan->getDisplay('name');
                } else {
                    $html .= $view->getName();
                }
                $html .= '</a>';
                $html .= '</span>';
                $return[] = array(
                    'plan' => utils::o2a($plan),
                    'html' => $html
                );
            } else if ($plan->getLink_type() == 'graph') {
                $return[] = array(
                    'plan' => utils::o2a($plan),
                    'html' => ''
                );
            } else if ($plan->getLink_type() == 'text') {
                $html = '<div class="text-widget" data-text_id="' . $plan->getLink_id() . '" style="color:' . $plan->getCss('color', 'black') . ';">';
                if ($plan->getDisplay('name') != '' || $plan->getDisplay('icon') != '') {
                    $html .= $plan->getDisplay('icon') . ' ' . $plan->getDisplay('text');
                } else {
                    $html .= $plan->getDisplay('text', 'Texte à insérer ici');
                }
                $html .= '</div>';
                $return[] = array(
                    'plan' => utils::o2a($plan),
                    'html' => $html
                );
            }
        }
        ajax::success($return);
    }

    if (init('action') == 'get') {
        $plan = plan::byId(init('id'));
        if (is_object($plan)) {
            ajax::success(utils::o2a($plan));
        }
        $plan = plan::byLinkTypeLinkIdObjectId(init('link_type'), init('link_id'), init('object_id'));
        if (is_object($plan)) {
            ajax::success(utils::o2a($plan));
        }
        throw new Exception(__('Aucun plan correspondant'));
    }

    if (init('action') == 'remove') {
        $plan = plan::byId(init('id'));
        if (is_object($plan)) {
            ajax::success($plan->remove());
        }
        $plan = plan::byLinkTypeLinkIdPlanHedaerId(init('link_type'), init('link_id'), init('object_id'));
        if (is_object($plan)) {
            ajax::success($plan->remove());
        }
        throw new Exception(__('Aucun plan correspondant'));
    }

    if (init('action') == 'removePlanHeader') {
        if (!isConnect('admin')) {
            throw new Exception(__('401 - Accès non autorisé', __FILE__));
        }
        $planHeader = planHeader::byId(init('id'));
        if (!is_object($planHeader)) {
            throw new Exception(__('Objet inconnu verifié l\'id', __FILE__));
        }
        $planHeader->remove();
        ajax::success();
    }

    if (init('action') == 'allHeader') {
        $planHeaders = planHeader::all();
        $return = array();
        foreach ($planHeaders as $planHeader) {
            $info_planHeader = utils::o2a($planHeader);
            unset($info_planHeader['image']);
            $return[] = $info_planHeader;
        }
        ajax::success($return);
    }

    if (init('action') == 'getPlanHeader') {
        $planHeader = planHeader::byId(init('id'));
        if (!is_object($planHeader)) {
            throw new Exception(__('Plan header inconnu verifié l\'id : ', __FILE__) . init('id'));
        }
        $return = utils::o2a($planHeader);
        $return['image'] = $planHeader->displayImage();
        ajax::success($return);
    }

    if (init('action') == 'savePlanHeader') {
        $planHeader_ajax = json_decode(init('planHeader'), true);
        $planHeader = planHeader::byId($planHeader_ajax['id']);
        if (!is_object($planHeader)) {
            $planHeader = new planHeader();
        }
        utils::a2o($planHeader, $planHeader_ajax);
        $planHeader->save();
        ajax::success(utils::o2a($planHeader));
    }

    if (init('action') == 'copyPlanHeader') {
        $planHeader = planHeader::byId(init('id'));
        if (!is_object($planHeader)) {
            throw new Exception(__('Plan header inconnu verifié l\'id : ', __FILE__) . init('id'));
        }
        ajax::success(utils::o2a($planHeader->copy(init('name'))));
    }

    if (init('action') == 'uploadImage') {
        $planHeader = planHeader::byId(init('id'));
        if (!is_object($planHeader)) {
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
            throw new Exception(__('Le fichier est trop gros (maximum 5mo)', __FILE__));
        }
        $img_size = getimagesize($_FILES['file']['tmp_name']);
        $planHeader->setImage('type', str_replace('.', '', $extension));
        $planHeader->setImage('size', $img_size);
        $planHeader->setImage('data', base64_encode(file_get_contents($_FILES['file']['tmp_name'])));
        $planHeader->setImage('sha1', sha1($planHeader->getImage('data')));
        $planHeader->setConfiguration('desktopSizeX', $img_size[0]);
        $planHeader->setConfiguration('desktopSizeY', $img_size[1]);
        $planHeader->save();
        @rrmdir(dirname(__FILE__) . '/../../core/img/plan');
        ajax::success();
    }

    throw new Exception(__('Aucune methode correspondante à : ', __FILE__) . init('action'));
    /*     * *********Catch exeption*************** */
} catch (Exception $e) {
    ajax::error(displayExeption($e), $e->getCode());
}
?>
