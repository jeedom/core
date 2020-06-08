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

use PHPUnit\Framework\TestCase;
use Symfony\Component\ExpressionLanguage\Lexer;
use Symfony\Component\ExpressionLanguage\Token;
use Symfony\Component\ExpressionLanguage\TokenStream;

class LexerTest extends TestCase
{
    /**
     * @var Lexer
     */
    private $lexer;

    protected function setUp()
    {
        $this->lexer = new Lexer();
    }

    /**
     * @dataProvider getTokenizeData
     */
    public function testTokenize($tokens, $expression)
    {
        $tokens[] = new Token('end of expression', null, strlen($expression) + 1);
        $this->assertEquals(new TokenStream($tokens, $expression), $this->lexer->tokenize($expression));
    }

    /**
     * @expectedException \Symfony\Component\ExpressionLanguage\SyntaxError
     * @expectedExceptionMessage Unexpected character "'" around position 33 for expression `service(faulty.expression.example').dummyMethod()`.
     */
    public function testTokenizeThrowsErrorWithMessage()
    {
        $expression = "service(faulty.expression.example').dummyMethod()";
        $this->lexer->tokenize($expression);
    }

    /**
     * @expectedException \Symfony\Component\ExpressionLanguage\SyntaxError
     * @expectedExceptionMessage Unclosed "(" around position 7 for expression `service(unclosed.expression.dummyMethod()`.
     */
    public function testTokenizeThrowsErrorOnUnclosedBrace()
    {
        $expression = 'service(unclosed.expression.dummyMethod()';
        $this->lexer->tokenize($expression);
    }

    public function getTokenizeData()
    {
        return array(
            array(
                array(new Token('name', 'a', 3)),
                '  a  ',
            ),
            array(
                array(new Token('name', 'a', 1)),
                'a',
            ),
            array(
                array(new Token('string', 'foo', 1)),
                '"foo"',
            ),
            array(
                array(new Token('number', '3', 1)),
                '3',
            ),
            array(
                array(new Token('operator', '+', 1)),
                '+',
            ),
            array(
                array(new Token('punctuation', '.', 1)),
                '.',
            ),
            array(
                array(
                    new Token('punctuation', '(', 1),
                    new Token('number', '3', 2),
                    new Token('operator', '+', 4),
                    new Token('number', '5', 6),
                    new Token('punctuation', ')', 7),
                    new Token('operator', '~', 9),
                    new Token('name', 'foo', 11),
                    new Token('punctuation', '(', 14),
                    new Token('string', 'bar', 15),
                    new Token('punctuation', ')', 20),
                    new Token('punctuation', '.', 21),
                    new Token('name', 'baz', 22),
                    new Token('punctuation', '[', 25),
                    new Token('number', '4', 26),
                    new Token('punctuation', ']', 27),
                ),
                '(3 + 5) ~ foo("bar").baz[4]',
            ),
            array(
                array(new Token('operator', '..', 1)),
                '..',
            ),
            array(
                array(new Token('string', '#foo', 1)),
                "'#foo'",
            ),
            array(
                array(new Token('string', '#foo', 1)),
                '"#foo"',
            ),
        );
    }
}
