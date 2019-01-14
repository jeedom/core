<?php

namespace Tests\Jeedom\Core\Renderer;

use Jeedom\Core\Renderer\JsonRenderer;
use PHPUnit\Framework\TestCase;

class JsonRendererTest extends TestCase
{
    public function testGlobalNamespaceResolver()
    {
        $renderer = new JsonRenderer();
        $this->assertEquals('{"bar":"yes"}', $renderer->render('foo', ['bar' => 'yes']));
    }
}
