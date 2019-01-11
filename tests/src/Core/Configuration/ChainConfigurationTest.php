<?php

namespace Tests\Jeedom\Core\Configuration;

use Jeedom\Core\Configuration\ChainConfiguration;
use Jeedom\Core\Configuration\InMemoryConfiguration;
use Jeedom\Core\Configuration\ReadOnlyConfigurationException;
use Jeedom\Core\Configuration\ReadOnlyIniFileConfiguration;
use PHPUnit\Framework\TestCase;

class ChainConfigurationTest extends TestCase
{
    public function testGetTheFirstConfiguration()
    {
        $configuration1 = new InMemoryConfiguration();
        $configuration1->set('foo', 'bar');

        $configuration2 = new InMemoryConfiguration();
        $configuration2->set('foo', 'baz');

        $configuration = new ChainConfiguration([$configuration1, $configuration2]);
        $this->assertEquals('bar', $configuration->get('foo'));
    }

    public function testSetAllConfigurations()
    {
        $configuration1 = new InMemoryConfiguration();
        $configuration2 = new InMemoryConfiguration();

        $configuration = new ChainConfiguration([$configuration1, $configuration2]);
        $configuration->set('bar', 'baz');
        $this->assertEquals('baz', $configuration1->get('bar'));
        $this->assertEquals('baz', $configuration2->get('bar'));
    }

    public function testNoValue()
    {
        $configuration1 = new InMemoryConfiguration();
        $configuration2 = new InMemoryConfiguration();

        $configuration = new ChainConfiguration([$configuration1, $configuration2]);
        $this->assertNull($configuration->get('foo'));
    }

    public function testDefaultValue()
    {
        $configuration1 = new InMemoryConfiguration();
        $configuration2 = new InMemoryConfiguration();

        $configuration = new ChainConfiguration([$configuration1, $configuration2]);
        $this->assertSame('default', $configuration->get('foo', 'default'));
    }

    public function testSetThrowsReadOnlyExceptionWhenFirstConfigurationsIsReadonly()
    {
        $configuration1 = new ReadOnlyIniFileConfiguration(__DIR__.'/Fixtures/configuration.ini', 'core');
        $configuration2 = new InMemoryConfiguration();
        $configuration = new ChainConfiguration([$configuration1, $configuration2]);

        $this->expectException(ReadOnlyConfigurationException::class);
        $configuration->set('foo', 'bar');
    }

    public function testRemoveThrowsReadOnlyExceptionWhenFirstConfigurationsIsReadonly()
    {
        $configuration1 = new ReadOnlyIniFileConfiguration(__DIR__.'/Fixtures/configuration.ini', 'core');
        $configuration2 = new InMemoryConfiguration();
        $configuration = new ChainConfiguration([$configuration1, $configuration2]);

        $this->expectException(ReadOnlyConfigurationException::class);
        $configuration->remove('foo', 'bar');
    }

    public function testSetOverloadReadOnlyConfigurations()
    {
        $configuration1 = new InMemoryConfiguration();
        $configuration2 = new ReadOnlyIniFileConfiguration(__DIR__.'/Fixtures/configuration.ini', 'core');
        $configuration = new ChainConfiguration([$configuration1, $configuration2]);

        $this->assertSame('bar', $configuration->get('value'));
        $configuration->set('value', 'yep');
        $this->assertSame('yep', $configuration->get('value'));
    }

    public function testRemoveIsOverloadedReadOnlyConfigurations()
    {
        $configuration1 = new InMemoryConfiguration();
        $configuration2 = new ReadOnlyIniFileConfiguration(__DIR__.'/Fixtures/configuration.ini', 'core');
        $configuration = new ChainConfiguration([$configuration1, $configuration2]);

        $configuration->set('value', 'yep');
        $this->assertSame('yep', $configuration->get('value'));
        $configuration->remove('value');
        $this->assertSame('bar', $configuration->get('value'));
    }

    public function testMultiGetCombineValues()
    {
        $configuration1 = new InMemoryConfiguration();
        $configuration1->set('foo', 'bar');
        $configuration1->set('baz', 'youpi');

        $configuration2 = new InMemoryConfiguration();
        $configuration2->set('foo', 'baz');
        $configuration2->set('bar', 'baz');

        $configuration = new ChainConfiguration([$configuration1, $configuration2]);
        $configuration->addPostConfiguration(new InMemoryConfiguration());

        $result = $configuration->multiGet(['foo', 'bar', 'baz', 'value'], 'default');
        $this->assertEquals([
            'foo' => 'bar',
            'bar' => 'baz',
            'baz' => 'youpi',
            'value' => 'default',
        ], $result);
    }

    public function testMultiGetRetrieveAllAtLastConfiguration()
    {
        $configuration1 = new InMemoryConfiguration();
        $configuration1->set('foo', 'baz');
        $configuration1->set('bar', 'baz');

        $configuration = new ChainConfiguration([new InMemoryConfiguration(), new InMemoryConfiguration()]);
        $configuration->addPostConfiguration($configuration1);
        var_dump($configuration);

        $result = $configuration->multiGet(['foo', 'bar'], 'default');
        $this->assertEquals([
            'foo' => 'baz',
            'bar' => 'baz',
        ], $result);
    }

    public function testSearchCombineValues()
    {
        $configuration1 = new InMemoryConfiguration();
        $configuration1->set('foo', 'hello');
        $configuration1->set('baz', 'youpi');

        $configuration2 = new InMemoryConfiguration();
        $configuration2->set('baz', 'foo');
        $configuration2->set('bar', 'baz');
        $configuration2->set('foo', 'bye');

        $configuration = new ChainConfiguration([$configuration1, $configuration2]);

        $result = $configuration->search('a');
        $this->assertEquals([
            'bar' => 'baz',
            'baz' => 'youpi',
        ], $result);
    }
}
