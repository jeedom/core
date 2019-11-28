<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\ExpressionLanguage\Tests\Node;

use Symfony\Component\ExpressionLanguage\Node\ConstantNode;

class ConstantNodeTest extends AbstractNodeTest
{
    public function getEvaluateData()
    {
        return array(
            array(false, new ConstantNode(false)),
            array(true, new ConstantNode(true)),
            array(null, new ConstantNode(null)),
            array(3, new ConstantNode(3)),
            array(3.3, new ConstantNode(3.3)),
            array('foo', new ConstantNode('foo')),
            array(array(1, 'b' => 'a'), new ConstantNode(array(1, 'b' => 'a'))),
        );
    }

    public function getCompileData()
    {
        return array(
            array('false', new ConstantNode(false)),
            array('true', new ConstantNode(true)),
            array('null', new ConstantNode(null)),
            array('3', new ConstantNode(3)),
            array('3.3', new ConstantNode(3.3)),
            array('"foo"', new ConstantNode('foo')),
            array('array(0 => 1, "b" => "a")', new ConstantNode(array(1, 'b' => 'a'))),
        );
    }

    public function getDumpData()
    {
        return array(
            array('false', new ConstantNode(false)),
            array('true', new ConstantNode(true)),
            array('null', new ConstantNode(null)),
            array('3', new ConstantNode(3)),
            array('3.3', new ConstantNode(3.3)),
            array('"foo"', new ConstantNode('foo')),
            array('foo', new ConstantNode('foo', true)),
            array('{0: 1, "b": "a", 1: true}', new ConstantNode(array(1, 'b' => 'a', true))),
            array('{"a\\"b": "c", "a\\\\b": "d"}', new ConstantNode(array('a"b' => 'c', 'a\\b' => 'd'))),
            array('["c", "d"]', new ConstantNode(array('c', 'd'))),
            array('{"a": ["b"]}', new ConstantNode(array('a' => array('b')))),
        );
    }
}
