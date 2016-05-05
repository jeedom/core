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
 * Interface class to allow a uploader to be chosen
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
interface UploaderVotableInterface extends UploaderInterface
{
    /**
     * Returns true if given informations matches with the uploader
     *
     * @param  Filesystem $remote  Remote file/directory
     * @param  mixed      $local   The local resource/file/directory
     * @param  array      $options Options
     * @return boolean    TRUE if it matches the requirements, FALSE otherwise
     */
    public function vote(Filesystem $remote, $local, array $options = array());
}
