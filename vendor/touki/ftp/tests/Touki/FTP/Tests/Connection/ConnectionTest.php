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

namespace Touki\FTP\Tests\Connection;

use Touki\FTP\Connection\Connection;

/**
 * Connection TestCase
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class ConnectionTest extends \PHPUnit_Framework_TestCase
{
    protected $host;
    protected $username;
    protected $password;
    protected $port;

    public function setUp()
    {
        $this->host     = getenv("FTP_HOST");
        $this->username = getenv("FTP_USERNAME");
        $this->password = getenv("FTP_PASSWORD");
        $this->port     = getenv("FTP_PORT");
    }

    /**
     * We work on a clone of connection and then, pass it to the depending tests
     */
    public function testOpenSuccessful()
    {
        $connection = new Connection(getenv("FTP_HOST"), getenv("FTP_USERNAME"), getenv("FTP_PASSWORD"), getenv("FTP_PORT"));

        try {
            $connection->open();
            $this->assertTrue($connection->isConnected());

            return $connection;
        } catch (\Exception $e) {
            $this->markTestSkipped();
        }
    }

    /**
     * @expectedException        Touki\FTP\Exception\ConnectionException
     * @expectedExceptionMessage Could not connect to server foobar.google.com:21
     */
    public function testOpenOnInvalidHostGiven()
    {
        $connection = new Connection("foobar.google.com");
        $connection->open();
    }

    /**
     * @expectedException        Touki\FTP\Exception\ConnectionException
     * @expectedExceptionMessage Could not login using combination of username (foo) and password (***)
     * @depends                  testOpenSuccessful
     */
    public function testOpenOnInvalidCredentialsGiven()
    {
        $connection = new Connection($this->host, "foo", "baz");
        $connection->open();
    }

    /**
     * @expectedException Touki\FTP\Exception\ConnectionEstablishedException
     * @depends           testOpenSuccessful
     */
    public function testOpenConnectionOnAlreadyOpen($connection)
    {
        $connection->open();
    }

    /**
     * @expectedException        Touki\FTP\Exception\ConnectionUnestablishedException
     * @expectedExceptionMessage Tried to close an unitialized connection
     */
    public function testCloseNoConnection()
    {
        $connection = new Connection("foobar.google.com");
        $connection->close();
    }

    /**
     * @expectedException        Touki\FTP\Exception\ConnectionUnestablishedException
     * @expectedExceptionMessage Cannot get stream context. Connection is not established
     */
    public function testGetStreamNoConnection()
    {
        $connection = new Connection("foobar.google.com");
        $connection->getStream();
    }

    /**
     * @expectedException        Touki\FTP\Exception\ConnectionEstablishedException
     * @expectedExceptionMessage Cannot set new host. Connection is established
     * @depends                  testOpenSuccessful
     */
    public function testSetHostWhenConnectionIsEstablished($connection)
    {
        $connection->setHost("foo");
    }

    /**
     * @expectedException        Touki\FTP\Exception\ConnectionEstablishedException
     * @expectedExceptionMessage Cannot set new username. Connection is established
     * @depends                  testOpenSuccessful
     */
    public function testSetUsernameWhenConnectionIsEstablished($connection)
    {
        $connection->setUsername("foo");
    }

    /**
     * @expectedException        Touki\FTP\Exception\ConnectionEstablishedException
     * @expectedExceptionMessage Cannot set new password. Connection is established
     * @depends                  testOpenSuccessful
     */
    public function testSetPasswordWhenConnectionIsEstablished($connection)
    {
        $connection->setPassword("foo");
    }

    /**
     * @expectedException        Touki\FTP\Exception\ConnectionEstablishedException
     * @expectedExceptionMessage Cannot set new port. Connection is established
     * @depends                  testOpenSuccessful
     */
    public function testSetPortWhenConnectionIsEstablished($connection)
    {
        $connection->setPort(22);
    }

    /**
     * @expectedException        Touki\FTP\Exception\ConnectionEstablishedException
     * @expectedExceptionMessage Cannot set new timeout. Connection is established
     * @depends                  testOpenSuccessful
     */
    public function testSetTimeoutWhenConnectionIsEstablished($connection)
    {
        $connection->setTimeout(90);
    }

    /**
     * @depends testOpenSuccessful
     */
    public function testUnmodifiedParametersAfterSetTries($connection)
    {
        $this->assertSame($connection->getHost(), $this->host);
        $this->assertSame($connection->getUsername(), $this->username);
        $this->assertSame($connection->getPassword(), $this->password);
        $this->assertSame($connection->getPort(), $this->port);
    }

    /**
     * @depends testOpenSuccessful
     */
    public function testCloseSuccessful($connection)
    {
        $this->assertTrue($connection->close());
        $this->assertFalse($connection->isConnected());

        return $connection;
    }

    /**
     * @depends testCloseSuccessful
     */
    public function testModifyParametersAfterClose($connection)
    {
        $connection->setHost("foo");
        $connection->setUsername("bar");
        $connection->setPassword("baz");
        $connection->setPort(22);
        $connection->setTimeout(27);

        $this->assertEquals("foo", $connection->getHost());
        $this->assertEquals("bar", $connection->getUsername());
        $this->assertEquals("baz", $connection->getPassword());
        $this->assertEquals(22,    $connection->getPort());
        $this->assertEquals(27,    $connection->getTimeout());
    }
}
