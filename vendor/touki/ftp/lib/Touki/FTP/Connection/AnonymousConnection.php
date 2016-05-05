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

/**
 * Anonymous connection
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class AnonymousConnection extends Connection
{
    /**
     * Constructor
     *
     * @param string  $host    FTP Server adress
     * @param integer $port    Port to connect to
     * @param integer $timeout Default timeout
     * @param boolean $passive Setting to true will call ftp_pasv on connection open
     */
    public function __construct($host, $port = 21, $timeout = 90, $passive = false)
    {
        parent::__construct($host, 'anonymous', 'guest', $port, $timeout, $passive);
    }
}
