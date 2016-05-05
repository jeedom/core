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

namespace Touki\FTP\Tests\Creator;

use Touki\FTP\Creator\RecursiveDirectoryCreator;
use Touki\FTP\Manager\FTPFilesystemManager;
use Touki\FTP\FilesystemFactory;
use Touki\FTP\PermissionsFactory;
use Touki\FTP\Tests\ConnectionAwareTestCase;
use Touki\FTP\Model\Directory;
use Touki\FTP\Model\File;
use Touki\FTP\FTP;

/**
 * Recursive Directory Creator Test Case
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class RecursiveDirectoryCreatorTest extends ConnectionAwareTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->wrapper = self::$wrapper;
        $this->manager = new FTPFilesystemManager(self::$wrapper, new FilesystemFactory(new PermissionsFactory));
        $this->creator = new RecursiveDirectoryCreator(self::$wrapper, $this->manager);
    }

    public function testVote()
    {
        $this->assertTrue($this->creator->vote(new Directory('/foo'), array(FTP::RECURSIVE => true)));
    }

    public function testCreate()
    {
        $creation = new Directory('tmpdir');
        $this->assertTrue($this->creator->create($creation, array(FTP::RECURSIVE => true)));

        $dir = $this->manager->findDirectoryByDirectory($creation);

        $this->assertInstanceOf('Touki\FTP\Model\Directory', $dir);
        $this->assertEquals('/tmpdir', $dir->getRealpath());

        $this->wrapper->rmdir('tmpdir');
    }

    public function testCreateInDeepFolder()
    {
        $creation = new Directory('folder/subfolder/tmpdir');

        $this->assertTrue($this->creator->create($creation, array(FTP::RECURSIVE => true)));

        $dir = $this->manager->findDirectoryByDirectory($creation);

        $this->assertInstanceOf('Touki\FTP\Model\Directory', $dir);
        $this->assertEquals('/folder/subfolder/tmpdir', $dir->getRealpath());

        $this->wrapper->rmdir('/folder/subfolder/tmpdir');
    }

    public function testCreateDeepFolder()
    {
        $creation = new Directory('folder/tmpdir/tmpdirdeep');

        $this->assertTrue($this->creator->create($creation, array(FTP::RECURSIVE => true)));

        $dir = $this->manager->findDirectoryByDirectory($creation);

        $this->assertInstanceOf('Touki\FTP\Model\Directory', $dir);
        $this->assertEquals('/folder/tmpdir/tmpdirdeep', $dir->getRealpath());

        $this->wrapper->rmdir('/folder/tmpdir/tmpdirdeep');
        $this->wrapper->rmdir('/folder/tmpdir');
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Invalid filesystem given, expected instance of Directory got Touki\FTP\Model\File
     */
    public function testCreateInvalidInstanceGiven()
    {
        $this->creator->create(new File('foo'));
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Invalid option given. Expected true as FTP::RECURSIVE parameter
     */
    public function testCreateNoOptionsGiven()
    {
        $this->creator->create(new Directory('foo'));
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Invalid option given. Expected true as FTP::RECURSIVE parameter
     */
    public function testCreateNonRecursiveOptionGiven()
    {
        $this->creator->create(new Directory('foo'), array(FTP::RECURSIVE => false));
    }
}
