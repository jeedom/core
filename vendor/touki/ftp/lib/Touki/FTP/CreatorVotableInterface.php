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
 * Creator votable Interface
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
interface CreatorVotableInterface extends CreatorInterface
{
    /**
     * Returns true if given informations matches with the creator
     *
     * @param  Filesystem $remote  Filesystem to create
     * @param  array      $options Creator options
     * @return boolean    TRUE if it matches, FALSE otherwise
     */
    public function vote(Filesystem $remote, array $options = array());
}
