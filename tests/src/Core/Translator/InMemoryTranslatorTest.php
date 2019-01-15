<?php

namespace Tests\Jeedom\Core\Translator;

use Jeedom\Core\Translator\InMemoryTranslator;

class InMemoryTranslatorTest extends \PHPUnit\Framework\TestCase
{
    public function testTranslate()
    {
        $translator = new InMemoryTranslator(['{{Bonjour}}' => 'Hello']);
        $this->assertEquals('Hello Michel', $translator->translate('{{bonjour}} Michel'));
    }
}
