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

namespace Touki\FTP;

/**
 * Connection interface
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
interface ConnectionInterface
{
    /**
     * Returns the connection stream
     *
     * @return resource FTP Connection stream
     */
    public function getStream();

    /**
     * Tells wether the connection is established
     *
     * @return boolean TRUE if connected, FALSE if not
     */
    public function isConnected();
}
