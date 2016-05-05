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

use Touki\FTP\WindowsFilesystemFactory;

/**
 * Windows File factory test case
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class WindowsFileFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->factory = new WindowsFilesystemFactory;
    }

    public function testBuildDirectory()
    {
        $input = "12-22-11  08:21PM       <DIR>          dummydir";
        $dir = $this->factory->build($input, '/');

        $this->assertInstanceOf('Touki\FTP\Model\Directory', $dir);
        $this->assertEquals($dir->getRealpath(), '/dummydir');
        $this->assertEquals($dir->getSize(), 0);
        $this->assertEquals($dir->getMtime(), \DateTime::createFromFormat("Y-d-m H:i:s", "2011-22-12 20:21:00"));
    }

    public function testBuildFile()
    {
        $input = "07-25-13  05:49AM             17919077 dummyfile";
        $file = $this->factory->build($input, '/folder');

        $this->assertInstanceOf('Touki\FTP\Model\File', $file);
        $this->assertEquals($file->getRealpath(), '/folder/dummyfile');
        $this->assertEquals($file->getSize(), 17919077);
        $this->assertEquals($file->getMtime(), \DateTime::createFromFormat("Y-d-m H:i:s", "2013-25-07 05:49:00"));
    }

    public function testBuildFileWithSpaces()
    {
        $input = "07-25-13  05:49AM             17919077 dummy spaced filename";
        $file = $this->factory->build($input, '/folder');

        $this->assertInstanceOf('Touki\FTP\Model\File', $file);
        $this->assertEquals($file->getRealpath(), '/folder/dummy spaced filename');
        $this->assertEquals($file->getSize(), 17919077);
        $this->assertEquals($file->getMtime(), \DateTime::createFromFormat("Y-d-m H:i:s", "2013-25-07 05:49:00"));
    }
}
