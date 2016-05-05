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

namespace Touki\FTP\Tests\Uploader;

use Touki\FTP\Tests\ConnectionAwareTestCase;
use Touki\FTP\Uploader\ResourceUploader;
use Touki\FTP\Model\File;
use Touki\FTP\Model\Directory;
use Touki\FTP\FTP;

/**
 * Resource uploader test
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class ResourceUploaderTest extends ConnectionAwareTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->wrapper    = self::$wrapper;
        $this->uploader   = new ResourceUploader($this->wrapper);
        $this->file       = __FILE__;
        $this->local      = fopen($this->file, 'r');
        $this->remote     = new File(basename(__FILE__));
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
        $this->assertTrue($this->uploader->vote($this->remote, $this->local, $this->options));
    }

    public function testUpload()
    {
        $this->assertTrue($this->uploader->upload($this->remote, $this->local, $this->options));
        $this->assertNotEquals(-1, $this->wrapper->size($this->remote->getRealpath()));

        $this->wrapper->delete($this->remote->getRealpath());
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Invalid remote file given, expected instance of File, got Touki\FTP\Model\Directory
     */
    public function testUploadWrongFilesystemInstance()
    {
        $remote = new Directory('/');

        $this->uploader->upload($remote, $this->local, $this->options);
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Invalid local file given. Expected resource, got string
     */
    public function testUploadFileGiven()
    {
        $local = $this->file;

        $this->uploader->upload($this->remote, $local, $this->options);
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Invalid option given. Expected false as FTP::NON_BLOCKING parameter
     */
    public function testUploadNoOptionNonBlocking()
    {
        $this->uploader->upload($this->remote, $this->local);
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Invalid option given. Expected false as FTP::NON_BLOCKING parameter
     */
    public function testUploadWrongOptionNonBlocking()
    {
        $this->uploader->upload($this->remote, $this->local, array(
            FTP::NON_BLOCKING => true
        ));
    }
}
