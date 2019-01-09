<?php

namespace Tests\Jeedom\Core\Configuration;

use Jeedom\Core\Configuration\Configuration;
use Jeedom\Core\Configuration\InMemoryCacheConfigurationDecorator;
use PHPUnit\Framework\TestCase;

class InMemoryCacheConfigurationDecoratorTest extends TestCase
{
    public function testCallSubConfigurationOnGet()
    {
        $configuration = $this->createMock(Configuration::class);
        $configuration->expects($this->once())->method('get')->with('foo')->willReturn('bar');

        $cache = new InMemoryCacheConfigurationDecorator($configuration);
        $this->assertSame('bar', $cache->get('foo')); // Mock is called only here
        $this->assertSame('bar', $cache->get('foo'));
        $this->assertSame('bar', $cache->get('foo'));
    }

    public function testCallSubConfigurationOnSet()
    {
        $configuration = $this->createMock(Configuration::class);
        $configuration->expects($this->once())->method('set')->with('foo', 'bar');
        $configuration->expects($this->never())->method('get');

        $cache = new InMemoryCacheConfigurationDecorator($configuration);
        $cache->set('foo', 'bar');  // Mock is called only here
        $this->assertSame('bar', $cache->get('foo'));
        $this->assertSame('bar', $cache->get('foo'));
    }

    public function testCallSubConfigurationOnRemove()
    {
        $configuration = $this->createMock(Configuration::class);
        $configuration->expects($this->once())->method('set')->with('foo', 'bar');
        $configuration->expects($this->once())->method('remove')->with('foo');
        $configuration->expects($this->once())->method('get')->with('foo');

        $cache = new InMemoryCacheConfigurationDecorator($configuration);
        $cache->set('foo', 'bar');  // Called
        $this->assertSame('bar', $cache->get('foo'));
        $this->assertSame('bar', $cache->get('foo'));
        $cache->remove('foo'); // Called
        $this->assertNull($cache->get('foo')); // Called (not cached)
        $this->assertNull($cache->get('foo')); // Called (not cached)
    }

    public function testCallSubConfigurationOnMultiGet()
    {
        $configuration = $this->createMock(Configuration::class);
        $configuration->expects($this->once())->method('set')->with('foo', 'bar');
        $configuration->expects($this->once())->method('multiGet')->with(['other'])->willReturn(['other' => null]);
        $configuration->expects($this->never())->method('get');

        $cache = new InMemoryCacheConfigurationDecorator($configuration);
        $cache->set('foo', 'bar');  // Called
        $this->assertEquals(['foo' => 'bar', 'other' => 'default'], $cache->multiGet(['foo', 'other'], 'default')); // Called
        $this->assertEquals('bang', $cache->get('other', 'bang'));
    }

    public function testCallAlwaysSubConfigurationOnSearch()
    {
        $configuration = $this->createMock(Configuration::class);
        $configuration->expects($this->once())->method('search')->with('o')->willReturn(['foo' => 'bar']);
        $configuration->expects($this->never())->method('get');

        $cache = new InMemoryCacheConfigurationDecorator($configuration);
        $cache->set('foo', 'bar');  // Called
        $this->assertEquals(['foo' => 'bar'], $cache->search('o'));
        $this->assertEquals('bar', $cache->get('foo'));
    }
}
