<?php

namespace Tests\Jeedom\Core\Configuration;

use Jeedom\Core\Configuration\Configuration;
use Jeedom\Core\Configuration\InMemoryConfiguration;
use PHPUnit\Framework\TestCase;

class InMemoryConfigurationTest extends TestCase
{
    public function testInMemoryConfigurationIsConfiguration()
    {
        $configuration = new InMemoryConfiguration();
        $this->assertInstanceOf(Configuration::class, $configuration);
    }

    public function testNoValue()
    {
        $configuration = new InMemoryConfiguration();
        $this->assertNull($configuration->get('foo'));
    }

    public function testDefaultValue()
    {
        $configuration = new InMemoryConfiguration();
        $this->assertSame('default', $configuration->get('foo', 'default'));
    }

    public function testRetrieveValue()
    {
        $configuration = new InMemoryConfiguration();
        $this->assertSame($configuration, $configuration->set('foo', 'bar'));
        $this->assertSame('bar', $configuration->get('foo'));
    }

    public function testRemoveValue()
    {
        $configuration = new InMemoryConfiguration();
        $this->assertSame($configuration, $configuration->set('foo', 'bar'));
        $this->assertSame($configuration, $configuration->remove('foo'));
        $this->assertNull($configuration->get('foo'));
    }

    public function testNoDefaultMultiGet()
    {
        $configuration = new InMemoryConfiguration();
        $data = $configuration->multiGet(['a', 'b']);
        $this->assertEquals(['a' => null, 'b' => null], $data);
    }

    public function testDefaultMultiGet()
    {
        $configuration = new InMemoryConfiguration();
        $data = $configuration->multiGet(['a', 'b'], true);
        $this->assertEquals(['a' => true, 'b' => true], $data);
    }

    public function testArrayDefaultMultiGet()
    {
        $configuration = new InMemoryConfiguration();
        $data = $configuration->multiGet(['a', 'b'], ['a' => 'c', 'b' => 'd']);
        $this->assertEquals(['a' => 'c', 'b' => 'd'], $data);
    }

    public function testArrayDefaultMultiGetWithSettedValue()
    {
        $configuration = new InMemoryConfiguration();
        $configuration->set('b', 'e');
        $data = $configuration->multiGet(['a', 'b', 'c'], ['a' => 'c', 'b' => 'd']);
        $this->assertEquals(['a' => 'c', 'b' => 'e', 'c' => null], $data);
    }

    public function testSearchValueWithoutData()
    {
        $configuration = new InMemoryConfiguration();
        $this->assertEquals([], $configuration->search('ba'));
    }

    public function testSearchValueWithPattern()
    {
        $configuration = new InMemoryConfiguration();
        $configuration->set('foo', 1);
        $configuration->set('bar', 2);
        $configuration->set('baz', 3);
        $this->assertEquals(['bar' => 2, 'baz' => 3], $configuration->search('a'));
    }
}
