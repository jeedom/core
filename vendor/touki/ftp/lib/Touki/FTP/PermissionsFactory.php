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

use Touki\FTP\Model\Permissions;

/**
 * Factory class for Permissions model
 * Transforms input like
 *   rw-
 * Into a Permission model
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class PermissionsFactory
{
    /**
     * Builds a Permissions object
     *
     * @param  string      $input Permissions string
     * @return Permissions
     */
    public function build($input)
    {
        if (strlen($input) != 3) {
            throw new \InvalidArgumentException(sprintf("%s is not a valid permission input", $input));
        }

        $perms = 0;

        if ('r' === substr($input, 0, 1)) {
            $perms |= Permissions::READABLE;
        }

        if ('w' === substr($input, 1, 1)) {
            $perms |= Permissions::WRITABLE;
        }

        if ('x' === substr($input, 2, 1)) {
            $perms |= Permissions::EXECUTABLE;
        }

        $permissions = new Permissions;
        $permissions->setFlags($perms);

        return $permissions;
    }
}
