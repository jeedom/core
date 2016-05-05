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
use Touki\FTP\DownloaderVoter;
use Touki\FTP\Model\File;

/**
 * Downloader voter test case for FTP downloaders
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class DownloaderVoterFTPTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();

        $wrapper = $this->getMockBuilder('Touki\FTP\FTPWrapper')
            ->disableOriginalConstructor()
            ->getMock();
        $this->voter = new DownloaderVoter;
        $this->voter->addDefaultFTPDownloaders($wrapper);
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Could not resolve a downloader with the given options
     */
    public function testVoteNoElection()
    {
        $local = __FILE__;
        $file  = new File('/foo');

        $this->voter->vote($local, $file);
    }

    public function testVoteElectFileDownloader()
    {
        $local   = __FILE__;
        $file    = new File('/foo');
        $options = array(
            FTP::NON_BLOCKING => false
        );
        $downloader = $this->voter->vote($local, $file, $options);

        $this->assertInstanceOf('Touki\FTP\Downloader\FileDownloader', $downloader);
    }

    public function testVoteElectResourceDownloader()
    {
        $local   = fopen(__FILE__, 'r');
        $file    = new File('/foo');
        $options = array(
            FTP::NON_BLOCKING => false
        );
        $downloader = $this->voter->vote($local, $file, $options);

        $this->assertInstanceOf('Touki\FTP\Downloader\ResourceDownloader', $downloader);

        fclose($local);
    }

    public function testVoteElectNbFileDownloader()
    {
        $local   = __FILE__;
        $file    = new File('/foo');
        $options = array(
            FTP::NON_BLOCKING => true
        );
        $downloader = $this->voter->vote($local, $file, $options);

        $this->assertInstanceOf('Touki\FTP\Downloader\NbFileDownloader', $downloader);
    }

    public function testVoteElectNbResourceDownloader()
    {
        $local   = fopen(__FILE__, 'r');
        $file    = new File('/foo');
        $options = array(
            FTP::NON_BLOCKING => true
        );
        $downloader = $this->voter->vote($local, $file, $options);

        $this->assertInstanceOf('Touki\FTP\Downloader\NbResourceDownloader', $downloader);

        fclose($local);
    }

    public function testVoteAppendAnotherFileDownloader()
    {
        $local   = __FILE__;
        $file    = new File('/foo');
        $options = array(
            FTP::NON_BLOCKING => false
        );
        $mock = $this->getMock('Touki\FTP\DownloaderVotableInterface');
        $mock
            ->expects($this->any())
            ->method('vote')
            ->will($this->returnValue(true))
        ;
        $this->voter->addVotable($mock, $prepend = false);

        $downloader = $this->voter->vote($local, $file, $options);

        $this->assertInstanceOf('Touki\FTP\Downloader\FileDownloader', $downloader);
    }

    public function testVotePrependAnotherFileDownloader()
    {
        $local   = __FILE__;
        $file    = new File('/foo');
        $options = array(
            FTP::NON_BLOCKING => false
        );
        $mock = $this->getMock('Touki\FTP\DownloaderVotableInterface');
        $mock
            ->expects($this->once())
            ->method('vote')
            ->will($this->returnValue(true))
        ;
        $this->voter->addVotable($mock, $prepend = true);

        $downloader = $this->voter->vote($local, $file, $options);

        $this->assertSame($mock, $downloader);
    }
}
