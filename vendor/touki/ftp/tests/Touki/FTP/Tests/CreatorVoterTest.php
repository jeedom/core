<?php

/**
 * This file is a part of the FTP Wrapper package
 *
 * For the full informations, please read the README file
 * distributed with this source code
 *
 * @package FTP Wrapper
 * @version 1.1.0
 * @author  Touki <g.vincendon@vithemis.com>
 */

namespace Touki\FTP\Tests;

use Touki\FTP\FTP;
use Touki\FTP\CreatorVoter;
use Touki\FTP\Model\File;

/**
 * Creator voter test case
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class CreatorVoterTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $mock = $this->getMock('Touki\FTP\CreatorVotableInterface');
        $mock
            ->expects($this->once())
            ->method('vote')
            ->will($this->returnValue(false))
        ;
        $this->voter = new CreatorVoter;
        $this->voter->addVotable($mock);
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Could not resolve a creator with the given options
     */
    public function testVoteNoEligibleVoters()
    {
        $this->voter->vote(new File('bar'), array('baz'));
    }

    public function testVoteEligibleVoter()
    {
        $mock = $this->getMock('Touki\FTP\CreatorVotableInterface');
        $mock
            ->expects($this->once())
            ->method('vote')
            ->will($this->returnValue(true))
        ;

        $this->voter->addVotable($mock);
        $votable = $this->voter->vote(new File('bar'), array('baz'));

        $this->assertSame($mock, $votable);
    }
}
