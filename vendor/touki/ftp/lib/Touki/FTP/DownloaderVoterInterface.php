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
 * Interface for the downloader voter
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
interface DownloaderVoterInterface
{
    /**
     * Returns true if given informations matches with the downloader
     *
     * @param  mixed                      $local   The local component
     * @param  Filesystem                 $remote  The remote component
     * @param  array                      $options Downloader's options
     * @return DownloaderVotableInterface Instance of the voted downloader
     */
    public function vote($local, Filesystem $remote, array $options = array());
}
