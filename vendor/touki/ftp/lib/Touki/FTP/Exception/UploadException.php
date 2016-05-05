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

namespace Touki\FTP\Exception;

/**
 * Exception to throw when an error occured while uploading
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class UploadException extends FTPException
{
    /**
     * Overrides the default to String
     * @return string
     */
    public function __toString()
    {
        return sprintf('[Upload Error] %s', $this->getMessage());
    }
}
