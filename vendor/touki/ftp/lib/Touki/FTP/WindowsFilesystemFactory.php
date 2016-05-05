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

use Touki\FTP\Model\File;
use Touki\FTP\Model\Directory;

/**
 * Filesystem factory that parses inputs like
 *     12-22-11  08:21PM       <DIR>          dummydir
 *     07-25-13  05:49AM             17919077 dummyfile
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class WindowsFilesystemFactory implements FilesystemFactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function build($input, $prefix = '')
    {
        $prefix    = rtrim($prefix, '/');
        $parts     = preg_split("/\s+/", $input);
        $inputdate = sprintf("%s %s", $parts[0], $parts[1]);
        $date      = \DateTime::createFromFormat('m-d-y H:iA', $inputdate);
        $name      = implode(' ', array_slice($parts, 3));

        if ($parts[2] == '<DIR>') {
            $size = 0;
            $file = new Directory;
        } else {
            $size = $parts[2];
            $file = new File;
        }

        $file
            ->setRealpath(sprintf("%s/%s", $prefix, $name))
            ->setSize($size)
            ->setMtime($date)
        ;

        return $file;
    }
}
