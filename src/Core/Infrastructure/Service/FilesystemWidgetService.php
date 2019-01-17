<?php

namespace Jeedom\Core\Infrastructure\Service;

use Jeedom\Core\Presenter\Service\WidgetService;

class FilesystemWidgetService implements WidgetService
{
    /**
     * @param string $type
     * @param string $viewer ('desktop', 'mobile')
     *
     * @return string[][][]
     */
    public function getAvailables($type, $viewer)
    {
        $path = diname(__DIR__, 4) . '/template/' . $viewer;
        $files = ls($path, $type.'.*', false, array('files', 'quiet'));
        $return = array();
        foreach ($files as $file) {
            $informations = explode('.', $file);
            if (!isset($return[$informations[1]][$informations[2]])) {
                $return[$informations[1]][$informations[2]] = array();
            }
            if (isset($informations[3])) {
                $return[$informations[1]][$informations[2]][$informations[3]] = array('name' => $informations[3], 'location' => 'core');
            }
        }
        $path = dirname(__DIR__, 4) . '/plugins/widget/core/template/' . $viewer;
        if (file_exists($path)) {
            $files = ls($path, $type.'.*', false, array('files', 'quiet'));
            foreach ($files as $file) {
                $informations = explode('.', $file);
                if ((count($informations) > 3)
                    && !isset($return[$informations[1]][$informations[2]][$informations[3]])) {
                    $return[$informations[1]][$informations[2]][$informations[3]] = array('name' => $informations[3], 'location' => 'widget');
                }
            }
        }
        return $return;
    }
}
