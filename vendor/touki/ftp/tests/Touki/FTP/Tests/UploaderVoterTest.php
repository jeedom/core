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
use Touki\FTP\UploaderVoter;
use Touki\FTP\Model\File;

/**
 * Uploader voter test case
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class UploaderVoterTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $mock = $this->getMock('Touki\FTP\UploaderVotableInterface');
        $mock
            ->expects($this->once())
            ->method('vote')
            ->will($this->returnValue(false))
        ;
        $this->voter = new UploaderVoter;
        $this->voter->addVotable($mock);
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Could not resolve an uploader with the given options
     */
    public function testVoteNoEligibleVoters()
    {
        $this->voter->vote(new File('bar'), 'foo', array('baz'));
    }

    public function testVoteEligibleVoter()
    {
        $mock = $this->getMock('Touki\FTP\UploaderVotableInterface');
        $mock
            ->expects($this->once())
            ->method('vote')
            ->will($this->returnValue(true))
        ;

        $this->voter->addVotable($mock);
        $votable = $this->voter->vote(new File('bar'), 'foo', array('baz'));

        $this->assertSame($mock, $votable);
    }
}
