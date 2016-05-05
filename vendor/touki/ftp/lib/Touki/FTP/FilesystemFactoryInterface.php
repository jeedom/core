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
 * Base interface for any filesystem factory
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
interface FilesystemFactoryInterface
{
    /**
     * Builds a file from a given input line
     *
     * @param  string     $input Input string
     * @return Filesystem Newly created File object
     */
    public function build($input, $prefix = '');
}
