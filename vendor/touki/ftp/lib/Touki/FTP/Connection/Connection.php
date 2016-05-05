<?php

/**
 * This file is a part of the FTP Wrapper package
 *
 * For the full informations, please read the README file
 * distributed with this source code
 *
 * @package FTP Wrapper
 * @version 1.1.1
 * @author  Touki <g.vincendon@vithemis.com>
 */

namespace Touki\FTP\Connection;

use Touki\FTP\ConnectionInterface;
use Touki\FTP\Exception\ConnectionException;
use Touki\FTP\Exception\ConnectionEstablishedException;
use Touki\FTP\Exception\ConnectionUnestablishedException;

/**
 * Standard connection
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class Connection implements ConnectionInterface
{
    /**
     * FTP server address
     * @var string
     */
    protected $host;

    /**
     * Port
     * @var integer
     */
    protected $port;

    /**
     * Timeout
     * @var integer
     */
    protected $timeout;

    /**
     * Username
     * @var string
     */
    protected $username;

    /**
     * Password
     * @var string
     */
    protected $password;

    /**
     * FTP Stream
     * @var resource
     */
    protected $stream;

    /**
     * Whether stream is already open
     * @var boolean
     */
    protected $connected = false;

    /**
     * Whether it's a passive connection
     * @var boolean
     */
    protected $passive = false;

    /**
     * Constructor
     *
     * @param string  $host     The FTP server address
     * @param string  $username Username to login with
     * @param string  $password Password to login with
     * @param integer $port     Specify the port to connect to
     * @param integer $timeout  Specify the default timeout
     * @param boolean $passive  Setting to true will call ftp_pasv on connection open
     */
    public function __construct($host, $username = 'anonymous', $password = 'guest', $port = 21, $timeout = 90, $passive = false)
    {
        $this->host     = $host;
        $this->username = $username;
        $this->password = $password;
        $this->port     = $port;
        $this->timeout  = $timeout;
        $this->passive  = $passive;
    }

    /**
     * Opens the connection
     *
     * @return boolean TRUE when connection suceeded
     *
     * @throws ConnectionEstablishedException When connection is already running
     * @throws ConnectionException            When connection to server failed
     * @throws ConnectionException            When loging-in to server failed
     */
    public function open()
    {
        if ($this->isConnected()) {
            throw new ConnectionEstablishedException;
        }

        $stream = $this->doConnect();

        if (false === $stream) {
            throw new ConnectionException(sprintf("Could not connect to server %s:%s", $this->getHost(), $this->getPort()));
        }

        if (!@ftp_login($stream, $this->getUsername(), $this->getPassword())) {
            throw new ConnectionException(sprintf(
                "Could not login using combination of username (%s) and password (%s)",
                $this->getUsername(),
                preg_replace("/./", "*", $this->getPassword())
            ));
        }

        if (true === $this->passive) {
            if (false === ftp_pasv($stream, true)) {
                throw new ConnectionException("Cold not turn on passive mode");
            }
        }

        $this->connected = true;
        $this->stream    = $stream;

        return true;
    }

    /**
     * Calls the connector
     *
     * @return boolean TRUE on success, FALSE on failure
     */
    protected function doConnect()
    {
        return @ftp_connect($this->getHost(), $this->getPort(), $this->getTimeout());
    }

    /**
     * Closes the connection
     *
     * @return boolean TRUE when closing connection suceeded
     *
     * @throws ConnectionUnestablishedException When connection is not established
     */
    public function close()
    {
        if (!$this->isConnected()) {
            throw new ConnectionUnestablishedException("Tried to close an unitialized connection");
        }

        ftp_close($this->stream);

        $this->connected = false;

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function isConnected()
    {
        return $this->connected;
    }

    /**
     * {@inheritDoc}
     *
     * @throws ConnectionUnestablishedException When connection is not established
     */
    public function getStream()
    {
        if (!$this->isConnected()) {
            throw new ConnectionUnestablishedException("Cannot get stream context. Connection is not established");
        }

        return $this->stream;
    }

    /**
     * Get Host
     *
     * @return string FTP server address
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Set Host
     *
     * @param string $host FTP server address
     *
     * @throws ConnectionEstablishedException When the connection is established
     */
    public function setHost($host)
    {
        if ($this->isConnected()) {
            throw new ConnectionEstablishedException("Cannot set new host. Connection is established");
        }

        $this->host = $host;
    }

    /**
     * Get Username
     *
     * @return string Username to login with
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set Username
     *
     * @param string $username Username to login with
     *
     * @throws ConnectionEstablishedException When the connection is established
     */
    public function setUsername($username)
    {
        if ($this->isConnected()) {
            throw new ConnectionEstablishedException("Cannot set new username. Connection is established");
        }

        $this->username = $username;
    }

    /**
     * Get Password
     *
     * @return string Password to login with
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set Password
     *
     * @param string $password Password to login with
     *
     * @throws ConnectionEstablishedException When the connection is established
     */
    public function setPassword($password)
    {
        if ($this->isConnected()) {
            throw new ConnectionEstablishedException("Cannot set new password. Connection is established");
        }

        $this->password = $password;
    }

    /**
     * Get Port
     *
     * @return integer Port to connect to
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Set Port
     *
     * @param integer $port Port to connect to
     *
     * @throws ConnectionEstablishedException When the connection is established
     */
    public function setPort($port)
    {
        if ($this->isConnected()) {
            throw new ConnectionEstablishedException("Cannot set new port. Connection is established");
        }

        $this->port = $port;
    }

    /**
     * Get Timeout
     *
     * @return integer Default timeout
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * Set Timeout
     *
     * @param integer $timeout Default timeout
     *
     * @throws ConnectionEstablishedException When the connection is established
     */
    public function setTimeout($timeout)
    {
        if ($this->isConnected()) {
            throw new ConnectionEstablishedException("Cannot set new timeout. Connection is established");
        }

        $this->timeout = $timeout;
    }

    /**
     * Forces connection to close on object destruction
     */
    public function __destruct()
    {
        if ($this->isConnected()) {
            $this->close();
        }
    }

    /**
     * Clones The object
     * We need this magic method since we have to pass the stream reference
     */
    public function __clone()
    {
        $this->stream = &$this->stream;
    }
}
