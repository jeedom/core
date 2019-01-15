<?php

namespace Tests\Jeedom\Core\Renderer;

use Jeedom\Core\Renderer\PHPRenderer;
use PHPUnit\Framework\TestCase;

class PHPRendererTest extends TestCase
{
    public function testGlobalNamespaceResolver()
    {
        $renderer = new PHPRenderer(__DIR__ . '/templates');
        $this->assertEquals('yes', $renderer->render('foo.php', ['bar' => 'yes']));
    }

    public function testSpecificNamespaceResolver()
    {
        $renderer = new PHPRenderer(__DIR__ . '/templates');
        $renderer->addNamespace('MyPlugin', __DIR__ . '/specific_templates');
        $this->assertEquals('ok', $renderer->render('@MyPlugin/bar.php', ['baz' => 'ok']));
    }
}
