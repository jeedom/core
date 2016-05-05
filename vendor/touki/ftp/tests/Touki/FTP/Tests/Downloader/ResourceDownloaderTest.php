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

namespace Touki\FTP\Tests\Downloader;

use Touki\FTP\Tests\ConnectionAwareTestCase;
use Touki\FTP\Downloader\ResourceDownloader;
use Touki\FTP\Model\File;
use Touki\FTP\Model\Directory;
use Touki\FTP\FTP;
use Touki\FTP\FTPWrapper;

/**
 * Resource downloader test
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class ResourceDownloaderTest extends ConnectionAwareTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->downloader = new ResourceDownloader(new FTPWrapper(self::$connection));
        $this->file       = tempnam(sys_get_temp_dir(), 'resourcedownloader');
        $this->local      = fopen($this->file, 'w+');
        $this->remote     = new File('file1.txt');
        $this->options    = array(
            FTP::NON_BLOCKING => false
        );
    }

    public function tearDown()
    {
        fclose($this->local);
    }

    public function testVote()
    {
        $this->assertTrue($this->downloader->vote($this->local, $this->remote, $this->options));
    }

    public function testDownload()
    {
        $this->assertTrue($this->downloader->download($this->local, $this->remote, $this->options));
        $this->assertFileExists($this->file);
        $this->assertEquals(file_get_contents($this->file), 'file1');

        unlink($this->file);
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Invalid remote file given, expected instance of File, got Touki\FTP\Model\Directory
     */
    public function testDownloadWrongFilesystemInstance()
    {
        $remote = new Directory('/');

        $this->downloader->download($this->local, $remote, $this->options);
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Invalid local file given. Expected resource, got string
     */
    public function testDownloadFilenameGiven()
    {
        $local = $this->file;

        $this->downloader->download($local, $this->remote, $this->options);
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Invalid option given. Expected false as FTP::NON_BLOCKING parameter
     */
    public function testDownloadNoOptionNonBlocking()
    {
        $this->downloader->download($this->local, $this->remote);
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Invalid option given. Expected false as FTP::NON_BLOCKING parameter
     */
    public function testDownloadWrongOptionNonBlocking()
    {
        $this->downloader->download($this->local, $this->remote, array(
            FTP::NON_BLOCKING => true
        ));
    }
}
