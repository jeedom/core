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
 * Interface for downloader
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
interface DownloaderInterface
{
    /**
     * Processes the download
     *
     * @param  mixed      $local   Local file, resource, directory
     * @param  Filesystem $remote  Remote File, directory
     * @param  array      $options Downloader options
     * @return boolean    TRUE on success
     */
    public function download($local, Filesystem $remote, array $options = array());
}
