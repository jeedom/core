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
use Touki\FTP\FTPFactory;
use Touki\FTP\Model\Directory;
use Touki\FTP\Model\File;

/**
 * FTP API Test case
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class FTPTest extends ConnectionAwareTestCase
{
    public function setUp()
    {
        parent::setUp();

        $factory = new FTPFactory;
        $this->ftp = $factory->build(self::$connection);
        $this->manager = $factory->getManager();
        $this->wrapper = self::$wrapper;
    }

    public function testFindFilesystems()
    {
        $list = $this->ftp->findFilesystems(new Directory('/'));
        $this->assertCount(3, $list);

        foreach ($list as $item) {
            $this->assertInstanceOf('Touki\FTP\Model\Filesystem', $item);
        }
    }

    public function testFindFilesystemsDeep()
    {
        $list = $this->ftp->findFilesystems(new Directory('folder'));
        $this->assertCount(2, $list);

        foreach ($list as $item) {
            $this->assertInstanceOf('Touki\FTP\Model\Filesystem', $item);
        }
    }

    /**
     * @expectedException        Touki\FTP\Exception\DirectoryException
     * @expectedExceptionMessage Directory /foo not found
     */
    public function testFindFilesystemsUnknownFolder()
    {
        $this->ftp->findFilesystems(new Directory('foo'));
    }

    public function testFindFiles()
    {
        $list = $this->ftp->findFiles(new Directory('/'));
        $this->assertCount(2, $list);

        foreach ($list as $item) {
            $this->assertInstanceOf('Touki\FTP\Model\File', $item);
        }
    }

    public function testFindFilesDeep()
    {
        $list = $this->ftp->findFiles(new Directory('folder'));

        $this->assertCount(1, $list);
        $this->assertInstanceOf('Touki\FTP\Model\File', $list[0]);
        $this->assertEquals('/folder/file3.txt', $list[0]->getRealpath());
    }

    /**
     * @expectedException        Touki\FTP\Exception\DirectoryException
     * @expectedExceptionMessage Directory /foo not found
     */
    public function testFindFilesUnknownFolder()
    {
        $this->ftp->findFiles(new Directory('/foo'));
    }

    public function testFindDirectories()
    {
        $list = $this->ftp->findDirectories(new Directory('/'));

        $this->assertCount(1, $list);
        $this->assertInstanceOf('Touki\FTP\Model\Directory', $list[0]);
        $this->assertEquals('/folder', $list[0]->getRealpath());
    }

    public function testFindDirectoriesDeep()
    {
        $list = $this->ftp->findDirectories(new Directory('/folder'));

        $this->assertCount(1, $list);
        $this->assertInstanceOf('Touki\FTP\Model\Directory', $list[0]);
        $this->assertEquals('/folder/subfolder', $list[0]->getRealpath());
    }

    /**
     * @expectedException        Touki\FTP\Exception\DirectoryException
     * @expectedExceptionMessage Directory /foo not found
     */
    public function testFindDirectoriesUnknownFolder()
    {
        $this->ftp->findDirectories(new Directory('foo'));
    }

    public function testFileExists()
    {
        $this->assertTrue($this->ftp->fileExists(new File('file1.txt')));
        $this->assertTrue($this->ftp->fileExists(new File('/file2.txt')));
        $this->assertTrue($this->ftp->fileExists(new File('folder/file3.txt')));
        $this->assertTrue($this->ftp->fileExists(new File('/folder/file3.txt')));
    }

    public function testFileExistsNonExistant()
    {
        $this->assertFalse($this->ftp->fileExists(new File('/foo.txt')));
        $this->assertFalse($this->ftp->fileExists(new File('foo.txt')));
        $this->assertFalse($this->ftp->fileExists(new File('folder/foo.txt')));
        $this->assertFalse($this->ftp->fileExists(new File('/unknown/folder/foo.txt')));
    }

    public function testDirectoryExists()
    {
        $this->assertTrue($this->ftp->directoryExists(new Directory('folder')));
        $this->assertTrue($this->ftp->directoryExists(new Directory('/folder')));
        $this->assertTrue($this->ftp->directoryExists(new Directory('/folder/subfolder')));
    }

    public function testDirectoryExistsNonExistant()
    {
        $this->assertFalse($this->ftp->directoryExists(new Directory('foo')));
        $this->assertFalse($this->ftp->directoryExists(new Directory('/unknown/folder')));
    }

    public function testFindFileByName()
    {
        $file = $this->ftp->findFileByName('file1.txt');

        $this->assertInstanceOf('Touki\FTP\Model\File', $file);
        $this->assertEquals('/file1.txt', $file->getRealpath());
    }

    public function testFindFileByNameDeep()
    {
        $file = $this->ftp->findFileByName('folder/file3.txt');

        $this->assertInstanceOf('Touki\FTP\Model\File', $file);
        $this->assertEquals('/folder/file3.txt', $file->getRealpath());
    }

    /**
     * @expectedException        Touki\FTP\Exception\DirectoryException
     * @expectedExceptionMessage Directory /foo not found
     */
    public function testFindFileByNameUnknownFolder()
    {
        $this->ftp->findFileByName('foo/bar');
    }

    public function testFindFileByNameNotFound()
    {
        $this->assertNull($this->ftp->findFileByName('bar'));
        $this->assertNull($this->ftp->findFileByName('folder/baz'));
    }

    public function testFindDirectoryByName()
    {
        $dir = $this->ftp->findDirectoryByName('folder');

        $this->assertInstanceOf('Touki\FTP\Model\Directory', $dir);
        $this->assertEquals('/folder', $dir->getRealpath());
    }

    public function testFindDirectoryByNameDeep()
    {
        $dir = $this->ftp->findDirectoryByName('folder/subfolder');

        $this->assertInstanceOf('Touki\FTP\Model\Directory', $dir);
        $this->assertEquals('/folder/subfolder', $dir->getRealpath());
    }

    /**
     * @expectedException        Touki\FTP\Exception\DirectoryException
     * @expectedExceptionMessage Directory /foo not found
     */
    public function testFindDirectoryByNameUnknownFolder()
    {
        $this->ftp->findDirectoryByName('foo/bar');
    }

    public function testFindDirectoryByNameNotFound()
    {
        $this->assertNull($this->ftp->findDirectoryByName('bar'));
        $this->assertNull($this->ftp->findDirectoryByName('folder/baz'));
    }

    /**
     * @expectedException        Touki\FTP\Exception\DirectoryException
     * @expectedExceptionMessage Remote filesystem foo of type Touki\FTP\Model\File does not exists
     */
    public function testDownloadNonExistantRemote()
    {
        $local  = tempnam(sys_get_temp_dir(), 'ftpdownload');
        $remote = new File('foo');

        $this->ftp->download($local, $remote);
    }

    public function testDownload()
    {
        $local  = tempnam(sys_get_temp_dir(), 'ftpdownload');
        $remote = $this->ftp->findFileByName('file1.txt');

        $this->assertTrue($this->ftp->download($local, $remote));
        $this->assertFileExists($local);

        unlink($local);
    }

    public function testFindFileByNameFromCwd()
    {
        $file = $this->ftp->findFileByName('file1.txt', $this->ftp->getCwd());

        $this->assertInstanceOf('Touki\FTP\Model\File', $file);
        $this->assertEquals('/file1.txt', $file->getRealpath());
    }

    public function testCreateRecursiveDirectoryByDefault()
    {
        $creation = new Directory('folder/tmpdir/tmpdirdeep');

        $this->assertTrue($this->ftp->create($creation));

        $dir = $this->manager->findDirectoryByDirectory($creation);

        $this->assertInstanceOf('Touki\FTP\Model\Directory', $dir);
        $this->assertEquals('/folder/tmpdir/tmpdirdeep', $dir->getRealpath());

        $this->wrapper->rmdir('/folder/tmpdir/tmpdirdeep');
        $this->wrapper->rmdir('/folder/tmpdir');
    }
}
