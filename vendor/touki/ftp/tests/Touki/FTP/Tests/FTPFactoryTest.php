<?php

namespace Touki\FTP\Tests;

use Touki\FTP\FTPFactory;
use Touki\FTP\FTPWrapper;
use Touki\FTP\DeleterVoter;
use Touki\FTP\CreatorVoter;
use Touki\FTP\DownloaderVoter;
use Touki\FTP\UploaderVoter;
use Touki\FTP\Manager\FTPFilesystemManager;
use Touki\FTP\WindowsFilesystemFactory;

/**
 * FTP Factory Test Case
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class FTPFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $mock = $this->getMock('Touki\FTP\ConnectionInterface');
        $mock
            ->expects($this->any())
            ->method('isConnected')
            ->will($this->returnValue(true))
        ;

        $this->connection = $mock;
        $this->factory    = new FTPFactory;
    }

    public function testNoConfigurationGiven()
    {
        $ftp = $this->factory->build($this->connection);

        $this->assertInstanceOf('Touki\FTP\FTP', $ftp);
        $this->assertInstanceOf('Touki\FTP\FTPWrapper', $this->factory->getWrapper());
        $this->assertSame($this->connection, $this->factory->getWrapper()->getConnection());
        $this->assertInstanceOf('Touki\FTP\Manager\FTPFilesystemManager', $this->factory->getManager());
        $this->assertInstanceOf('Touki\FTP\CreatorVoter', $this->factory->getCreatorVoter());
        $this->assertInstanceOf('Touki\FTP\DeleterVoter', $this->factory->getDeleterVoter());
        $this->assertInstanceOf('Touki\FTP\DownloaderVoter', $this->factory->getDownloaderVoter());
        $this->assertInstanceOf('Touki\FTP\UploaderVoter', $this->factory->getUploaderVoter());
    }

    public function testFTPWrapperGiven()
    {
        $wrapper = new FTPWrapper($this->connection);
        $this->factory->setWrapper($wrapper);

        $ftp = $this->factory->build($this->connection);

        $this->assertInstanceOf('Touki\FTP\FTP', $ftp);
        $this->assertSame($wrapper, $this->factory->getWrapper());
        $this->assertInstanceOf('Touki\FTP\Manager\FTPFilesystemManager', $this->factory->getManager());
        $this->assertInstanceOf('Touki\FTP\CreatorVoter', $this->factory->getCreatorVoter());
        $this->assertInstanceOf('Touki\FTP\DeleterVoter', $this->factory->getDeleterVoter());
        $this->assertInstanceOf('Touki\FTP\DownloaderVoter', $this->factory->getDownloaderVoter());
        $this->assertInstanceOf('Touki\FTP\UploaderVoter', $this->factory->getUploaderVoter());
    }

    public function testManagerGiven()
    {
        $manager = new FTPFilesystemManager(new FTPWrapper($this->connection), new WindowsFilesystemFactory);
        $this->factory->setManager($manager);

        $ftp = $this->factory->build($this->connection);

        $this->assertInstanceOf('Touki\FTP\FTP', $ftp);
        $this->assertInstanceOf('Touki\FTP\FTPWrapper', $this->factory->getWrapper());
        $this->assertSame($this->connection, $this->factory->getWrapper()->getConnection());
        $this->assertSame($manager, $this->factory->getManager());
        $this->assertInstanceOf('Touki\FTP\CreatorVoter', $this->factory->getCreatorVoter());
        $this->assertInstanceOf('Touki\FTP\DeleterVoter', $this->factory->getDeleterVoter());
        $this->assertInstanceOf('Touki\FTP\DownloaderVoter', $this->factory->getDownloaderVoter());
        $this->assertInstanceOf('Touki\FTP\UploaderVoter', $this->factory->getUploaderVoter());
    }

    public function testCreatorVoterGiven()
    {
        $crVoter = new CreatorVoter;
        $this->factory->setCreatorVoter($crVoter);
        $ftp = $this->factory->build($this->connection);

        $this->assertInstanceOf('Touki\FTP\FTP', $ftp);
        $this->assertInstanceOf('Touki\FTP\FTPWrapper', $this->factory->getWrapper());
        $this->assertSame($this->connection, $this->factory->getWrapper()->getConnection());
        $this->assertInstanceOf('Touki\FTP\Manager\FTPFilesystemManager', $this->factory->getManager());
        $this->assertSame($crVoter, $this->factory->getCreatorVoter());
        $this->assertInstanceOf('Touki\FTP\DeleterVoter', $this->factory->getDeleterVoter());
        $this->assertInstanceOf('Touki\FTP\DownloaderVoter', $this->factory->getDownloaderVoter());
        $this->assertInstanceOf('Touki\FTP\UploaderVoter', $this->factory->getUploaderVoter());
    }

    public function testDeleterVoterGiven()
    {
        $deVoter = new DeleterVoter;
        $this->factory->setDeleterVoter($deVoter);
        $ftp = $this->factory->build($this->connection);

        $this->assertInstanceOf('Touki\FTP\FTP', $ftp);
        $this->assertInstanceOf('Touki\FTP\FTPWrapper', $this->factory->getWrapper());
        $this->assertSame($this->connection, $this->factory->getWrapper()->getConnection());
        $this->assertInstanceOf('Touki\FTP\Manager\FTPFilesystemManager', $this->factory->getManager());
        $this->assertInstanceOf('Touki\FTP\CreatorVoter', $this->factory->getCreatorVoter());
        $this->assertSame($deVoter, $this->factory->getDeleterVoter());
        $this->assertInstanceOf('Touki\FTP\DownloaderVoter', $this->factory->getDownloaderVoter());
        $this->assertInstanceOf('Touki\FTP\UploaderVoter', $this->factory->getUploaderVoter());
    }

    public function testDownloaderVoterGiven()
    {
        $dlVoter = new DownloaderVoter;
        $this->factory->setDownloaderVoter($dlVoter);
        $ftp = $this->factory->build($this->connection);

        $this->assertInstanceOf('Touki\FTP\FTP', $ftp);
        $this->assertInstanceOf('Touki\FTP\FTPWrapper', $this->factory->getWrapper());
        $this->assertSame($this->connection, $this->factory->getWrapper()->getConnection());
        $this->assertInstanceOf('Touki\FTP\Manager\FTPFilesystemManager', $this->factory->getManager());
        $this->assertInstanceOf('Touki\FTP\CreatorVoter', $this->factory->getCreatorVoter());
        $this->assertInstanceOf('Touki\FTP\DeleterVoter', $this->factory->getDeleterVoter());
        $this->assertSame($dlVoter, $this->factory->getDownloaderVoter());
        $this->assertInstanceOf('Touki\FTP\UploaderVoter', $this->factory->getUploaderVoter());
    }

    public function testUploaderVoterGiven()
    {
        $ulVoter = new UploaderVoter;
        $this->factory->setUploaderVoter($ulVoter);
        $ftp = $this->factory->build($this->connection);

        $this->assertInstanceOf('Touki\FTP\FTP', $ftp);
        $this->assertInstanceOf('Touki\FTP\FTPWrapper', $this->factory->getWrapper());
        $this->assertSame($this->connection, $this->factory->getWrapper()->getConnection());
        $this->assertInstanceOf('Touki\FTP\Manager\FTPFilesystemManager', $this->factory->getManager());
        $this->assertInstanceOf('Touki\FTP\CreatorVoter', $this->factory->getCreatorVoter());
        $this->assertInstanceOf('Touki\FTP\DeleterVoter', $this->factory->getDeleterVoter());
        $this->assertInstanceOf('Touki\FTP\DownloaderVoter', $this->factory->getDownloaderVoter());
        $this->assertSame($ulVoter, $this->factory->getUploaderVoter());
    }

    public function testFilesystemFactoryGiven()
    {
        $fsFactory = new WindowsFilesystemFactory;
        $this->factory->setFilesystemFactory($fsFactory);
        $ftp = $this->factory->build($this->connection);

        $this->assertInstanceOf('Touki\FTP\FTP', $ftp);
        $this->assertInstanceOf('Touki\FTP\FTPWrapper', $this->factory->getWrapper());
        $this->assertSame($this->connection, $this->factory->getWrapper()->getConnection());
        $this->assertInstanceOf('Touki\FTP\Manager\FTPFilesystemManager', $this->factory->getManager());
        $this->assertInstanceOf('Touki\FTP\CreatorVoter', $this->factory->getCreatorVoter());
        $this->assertInstanceOf('Touki\FTP\DeleterVoter', $this->factory->getDeleterVoter());
        $this->assertInstanceOf('Touki\FTP\DownloaderVoter', $this->factory->getDownloaderVoter());
        $this->assertInstanceOf('Touki\FTP\UploaderVoter', $this->factory->getUploaderVoter());
    }
}
