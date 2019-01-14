<?php

namespace Jeedom\Core\Renderer;

class JsonRenderer implements Renderer
{
    public function render($template, array $data = [])
    {
        return json_encode($data);
    }
}
