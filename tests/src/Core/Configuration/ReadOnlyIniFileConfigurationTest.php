<?php

namespace Tests\Jeedom\Core\Configuration;

use Jeedom\Core\Configuration\ReadOnlyConfigurationException;
use Jeedom\Core\Configuration\ReadOnlyIniFileConfiguration;
use PHPUnit\Framework\TestCase;

class ReadOnlyIniFileConfigurationTest extends TestCase
{
    public function testFileDataAreSetted()
    {
        $file = __DIR__ . '/Fixtures/configuration.ini';
        $configuration = new ReadOnlyIniFileConfiguration($file, 'core');
        $this->assertSame('foo', $configuration->get('test::value'));
        $this->assertSame('bar', $configuration->get('value'));
        $this->assertSame('baz', $configuration->get('foo::bar'));
        $this->assertSame('1', $configuration->get('integer'));
        $this->assertSame('2.5', $configuration->get('float'));
        $this->assertNull($configuration->get('non_exists'));
    }

    public function testSetThrowReadOnlyConfigurationException()
    {
        $file = __DIR__ . '/Fixtures/configuration.ini';
        $configuration = new ReadOnlyIniFileConfiguration($file, 'core');

        $this->expectException(ReadOnlyConfigurationException::class);
        $configuration->set('foo', 'bar');
    }

    public function testRemoveThrowReadOnlyConfigurationException()
    {
        $file = __DIR__ . '/Fixtures/configuration.ini';
        $configuration = new ReadOnlyIniFileConfiguration($file, 'core');

        $this->expectException(ReadOnlyConfigurationException::class);
        $configuration->remove('foo');
    }
}
