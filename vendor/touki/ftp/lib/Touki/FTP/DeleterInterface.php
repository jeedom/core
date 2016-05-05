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

use Touki\FTP\Model\Filesystem;

/**
 * Deleter Interface
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
interface DeleterInterface
{
    /**
     * Creates a remote filesystem
     *
     * @param  Filesystem $remote  Filesystem to create
     * @param  array      $options Deleter options
     * @return boolean    TRUE on success
     */
    public function delete(Filesystem $remote, array $options = array());
}
