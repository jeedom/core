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
 * Interface for the creator voter
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
interface CreatorVoterInterface
{
    /**
     * Picks up a creator voter on given options
     *
     * @param Filesystem $remote  A Filesystem instance
     * @param array      $options An array of options
     *
     * @return CreatorVotableInterface
     */
    public function vote(Filesystem $remote, array $options = array());
}
