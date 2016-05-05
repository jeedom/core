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
 * SSL Connection
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class SSLConnection extends Connection
{
    /**
     * {@inheritDoc}
     */
    protected function doConnect()
    {
        return @ftp_ssl_connect($this->getHost(), $this->getPort(), $this->getTimeout());
    }
}
