<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\ExpressionLanguage\Tests;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\ExpressionLanguage\Tests\Fixtures\TestProvider;

class ExpressionLanguageTest extends \PHPUnit_Framework_TestCase
{
    public function testCachedParse()
    {
        $cacheMock = $this->getMock('Symfony\Component\ExpressionLanguage\ParserCache\ParserCacheInterface');
        $savedParsedExpression = null;
        $expressionLanguage = new ExpressionLanguage($cacheMock);

        $cacheMock
            ->expects($this->exactly(2))
            ->method('fetch')
            ->with('1 + 1//')
            ->will($this->returnCallback(function () use (&$savedParsedExpression) {
                return $savedParsedExpression;
            }))
        ;
        $cacheMock
            ->expects($this->exactly(1))
            ->method('save')
            ->with('1 + 1//', $this->isInstanceOf('Symfony\Component\ExpressionLanguage\ParsedExpression'))
            ->will($this->returnCallback(function ($key, $expression) use (&$savedParsedExpression) {
                $savedParsedExpression = $expression;
            }))
        ;

        $parsedExpression = $expressionLanguage->parse('1 + 1', array());
        $this->assertSame($savedParsedExpression, $parsedExpression);

        $parsedExpression = $expressionLanguage->parse('1 + 1', array());
        $this->assertSame($savedParsedExpression, $parsedExpression);
    }

    public function testConstantFunction()
    {
        $expressionLanguage = new ExpressionLanguage();
        $this->assertEquals(PHP_VERSION, $expressionLanguage->evaluate('constant("PHP_VERSION")'));

        $expressionLanguage = new ExpressionLanguage();
        $this->assertEquals('constant("PHP_VERSION")', $expressionLanguage->compile('constant("PHP_VERSION")'));
    }

    public function testProviders()
    {
        $expressionLanguage = new ExpressionLanguage(null, array(new TestProvider()));
        $this->assertEquals('foo', $expressionLanguage->evaluate('identity("foo")'));
        $this->assertEquals('"foo"', $expressionLanguage->compile('identity("foo")'));
    }

    /**
     * @dataProvider shortCircuitProviderEvaluate
     */
    public function testShortCircuitOperatorsEvaluate($expression, array $values, $expected)
    {
        $expressionLanguage = new ExpressionLanguage();
        $this->assertEquals($expected, $expressionLanguage->evaluate($expression, $values));
    }

    /**
     * @dataProvider shortCircuitProviderCompile
     */
    public function testShortCircuitOperatorsCompile($expression, array $names, $expected)
    {
        $result = null;
        $expressionLanguage = new ExpressionLanguage();
        eval(sprintf('$result = %s;', $expressionLanguage->compile($expression, $names)));
        $this->assertSame($expected, $result);
    }

    public function shortCircuitProviderEvaluate()
    {
        $object = $this->getMockBuilder('stdClass')->setMethods(array('foo'))->getMock();
        $object->expects($this->never())->method('foo');

        return array(
            array('false and object.foo()', array('object' => $object), false),
            array('false && object.foo()', array('object' => $object), false),
            array('true || object.foo()', array('object' => $object), true),
            array('true or object.foo()', array('object' => $object), true),
        );
    }

    public function shortCircuitProviderCompile()
    {
        return array(
            array('false and foo', array('foo' => 'foo'), false),
            array('false && foo', array('foo' => 'foo'), false),
            array('true || foo', array('foo' => 'foo'), true),
            array('true or foo', array('foo' => 'foo'), true),
        );
    }

    public function testCachingForOverriddenVariableNames()
    {
        $expressionLanguage = new ExpressionLanguage();
        $expression = 'a + b';
        $expressionLanguage->evaluate($expression, array('a' => 1, 'b' => 1));
        $result = $expressionLanguage->compile($expression, array('a', 'B' => 'b'));
        $this->assertSame('($a + $B)', $result);
    }

    public function testCachingWithDifferentNamesOrder()
    {
        $cacheMock = $this->getMock('Symfony\Component\ExpressionLanguage\ParserCache\ParserCacheInterface');
        $expressionLanguage = new ExpressionLanguage($cacheMock);
        $savedParsedExpressions = array();
        $cacheMock
            ->expects($this->exactly(2))
            ->method('fetch')
            ->will($this->returnCallback(function ($key) use (&$savedParsedExpressions) {
                return isset($savedParsedExpressions[$key]) ? $savedParsedExpressions[$key] : null;
            }))
        ;
        $cacheMock
            ->expects($this->exactly(1))
            ->method('save')
            ->will($this->returnCallback(function ($key, $expression) use (&$savedParsedExpressions) {
                $savedParsedExpressions[$key] = $expression;
            }))
        ;

        $expression = 'a + b';
        $expressionLanguage->compile($expression, array('a', 'B' => 'b'));
        $expressionLanguage->compile($expression, array('B' => 'b', 'a'));
    }
}
