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
 * Interface for uploader
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
interface UploaderInterface
{
    /**
     * Processes the upload
     *
     * @param  Filesystem $remote  Remote File, directory
     * @param  mixed      $local   Local file, resource, directory
     * @param  array      $options Uploader options
     * @return boolean    TRUE on success
     */
    public function upload(Filesystem $remote, $local, array $options = array());
}
