<?php

namespace Jeedom\Core\Renderer;

interface Renderer
{
    /**
     * @param $template
     * @param array $data
     *
     * @return string
     */
    public function render($template, array $data = []);
}
