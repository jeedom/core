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

namespace Touki\FTP\Tests\Deleter\FTP;

use Touki\FTP\Deleter\RecursiveDirectoryDeleter;
use Touki\FTP\Manager\FTPFilesystemManager;
use Touki\FTP\FilesystemFactory;
use Touki\FTP\PermissionsFactory;
use Touki\FTP\Tests\ConnectionAwareTestCase;
use Touki\FTP\Model\Directory;
use Touki\FTP\Model\File;
use Touki\FTP\FTP;

/**
 * Recursive Directory Deleter Test Case
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class RecursiveDirectoryDeleterTest extends ConnectionAwareTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->wrapper = self::$wrapper;
        $this->manager = new FTPFilesystemManager(self::$wrapper, new FilesystemFactory(new PermissionsFactory));
        $this->deleter = new RecursiveDirectoryDeleter(self::$wrapper, $this->manager);
    }

    public function testVote()
    {
        $this->assertTrue($this->deleter->vote(new Directory('/foo'), array(FTP::RECURSIVE => true)));
    }

    public function testVoteInvalidFilesystemGiven()
    {
        $this->assertFalse($this->deleter->vote(new File));
    }

    public function testVoteInvalidOptionsGiven()
    {
        $this->assertFalse($this->deleter->vote(new Directory));
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Invalid filesystem given, expected instance of Directory got Touki\FTP\Model\File
     */
    public function testDeleteInvalidFilesystem()
    {
        $this->deleter->delete(new File);
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Invalid option given. Expected true as FTP::RECURSIVE parameter
     */
    public function testDeleteInvalidOptions()
    {
        $this->deleter->delete(new Directory('/bar'));
    }
}
