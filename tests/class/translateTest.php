<?php

class translateTest extends \PHPUnit_Framework_TestCase
{
    public function testExec()
    {
        translate::setLanguage('en_US');
        $this->assertEquals('en_US', translate::getLanguage());
        $this->assertEquals('Synonyms for objects', translate::exec('{{Synonymes pour les objets}}', 'desktop/php/administration.php'));
        $this->assertEquals('Whitelist', translate::exec('{{IP "blanche"}}', 'desktop/php/administration.php'));
        $this->assertEquals('Synonyms for objects', translate::sentence('Synonymes pour les objets', 'desktop/php/administration.php'));
        $this->assertEquals('Whitelist', translate::sentence('IP "blanche"', 'desktop/php/administration.php'));
    }
}
