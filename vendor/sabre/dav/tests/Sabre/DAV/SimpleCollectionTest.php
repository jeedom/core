<?php

namespace Sabre\DAV;

class SimpleCollectionTest extends \PHPUnit\Framework\TestCase
{
    public function testConstructNode()
    {
        $s = new SimpleCollection('foo', [new SimpleFile('bar.txt', 'hi')]);
        $this->assertEquals('foo', $s->getName());
        $this->assertEquals('bar.txt', $s->getChild('bar.txt')->getName());
    }

    public function testConstructNodeArray()
    {
        $s = new SimpleCollection('foo', [
            'bar' => [],
            'baz.txt' => 'hi',
            'gir' => [
                'zim' => 'world',
            ],
        ]);
        $this->assertEquals('foo', $s->getName());
        $this->assertEquals('bar', $s->getChild('bar')->getName());
        $this->assertEquals('hi', $s->getChild('baz.txt')->get());
        $this->assertEquals('world', $s->getChild('gir')->getChild('zim')->get());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConstructBadParam()
    {
        new SimpleCollection('foo', [new \StdClass()]);
    }

    public function testGetChildren()
    {
        $child = new SimpleFile('bar.txt', 'hi');

        $s = new SimpleCollection('foo', [$child]);
        $this->assertEquals([$child], $s->getChildren());
    }

    /**
     * @expectedException \Sabre\DAV\Exception\NotFound
     */
    public function testGetChild404()
    {
        $s = new SimpleCollection('foo', []);
        $this->assertEquals($s->getChild('404'));
    }
}
