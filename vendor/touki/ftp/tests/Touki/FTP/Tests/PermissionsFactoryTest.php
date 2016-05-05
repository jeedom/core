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

use Touki\FTP\PermissionsFactory;

/**
 * Permissions Factory Test Case
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class PermissionsFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->factory = new PermissionsFactory;
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage 1234 is not a valid permission input
     */
    public function testBuildInvalidInput()
    {
        $this->factory->build('1234');
    }

    public function testBuildNoPermissions()
    {
        $input = '---';
        $permissions = $this->factory->build($input);

        $this->assertInstanceOf('Touki\FTP\Model\Permissions', $permissions);
        $this->assertFalse($permissions->isReadable());
        $this->assertFalse($permissions->isWritable());
        $this->assertFalse($permissions->isExecutable());
    }

    public function testBuildOnlyReadable()
    {
        $input = 'r--';
        $permissions = $this->factory->build($input);

        $this->assertInstanceOf('Touki\FTP\Model\Permissions', $permissions);
        $this->assertTrue($permissions->isReadable());
        $this->assertFalse($permissions->isWritable());
        $this->assertFalse($permissions->isExecutable());
    }

    public function testBuildOnlyWritable()
    {
        $input = '-w-';
        $permissions = $this->factory->build($input);

        $this->assertInstanceOf('Touki\FTP\Model\Permissions', $permissions);
        $this->assertFalse($permissions->isReadable());
        $this->assertTrue($permissions->isWritable());
        $this->assertFalse($permissions->isExecutable());
    }

    public function testBuildOnlyExecutable()
    {
        $input = '--x';
        $permissions = $this->factory->build($input);

        $this->assertInstanceOf('Touki\FTP\Model\Permissions', $permissions);
        $this->assertFalse($permissions->isReadable());
        $this->assertFalse($permissions->isWritable());
        $this->assertTrue($permissions->isExecutable());
    }

    public function testBuildReadableWritable()
    {
        $input = 'rw-';
        $permissions = $this->factory->build($input);

        $this->assertInstanceOf('Touki\FTP\Model\Permissions', $permissions);
        $this->assertTrue($permissions->isReadable());
        $this->assertTrue($permissions->isWritable());
        $this->assertFalse($permissions->isExecutable());
    }

    public function testBuildReadableExecutable()
    {
        $input = 'r-x';
        $permissions = $this->factory->build($input);

        $this->assertInstanceOf('Touki\FTP\Model\Permissions', $permissions);
        $this->assertTrue($permissions->isReadable());
        $this->assertFalse($permissions->isWritable());
        $this->assertTrue($permissions->isExecutable());
    }

    public function testBuildWritableExecutable()
    {
        $input = '-wx';
        $permissions = $this->factory->build($input);

        $this->assertInstanceOf('Touki\FTP\Model\Permissions', $permissions);
        $this->assertFalse($permissions->isReadable());
        $this->assertTrue($permissions->isWritable());
        $this->assertTrue($permissions->isExecutable());
    }

    public function testBuildAllPermissions()
    {
        $input = 'rwx';
        $permissions = $this->factory->build($input);

        $this->assertInstanceOf('Touki\FTP\Model\Permissions', $permissions);
        $this->assertTrue($permissions->isReadable());
        $this->assertTrue($permissions->isWritable());
        $this->assertTrue($permissions->isExecutable());
    }
}
