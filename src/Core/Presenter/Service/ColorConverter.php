<?php

namespace Jeedom\Core\Presenter;

interface ColorConverter
{
    /**
     * @param string $color
     *
     * @return string
     */
    public function convert($color);
}
