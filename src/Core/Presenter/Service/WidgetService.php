<?php

namespace Jeedom\Core\Presenter\Service;

interface WidgetService
{
    /**
     * @param string $type
     * @param string $viewer ('desktop', 'mobile')
     *
     * @return string[][][]
     */
    public function getAvailables($type, $viewer);
}
