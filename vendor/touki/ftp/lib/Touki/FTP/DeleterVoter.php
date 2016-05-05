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

use Touki\FTP\Deleter as FTPDeleter;
use Touki\FTP\Model\Filesystem;
use Touki\FTP\Manager\FTPFilesystemManager;

/**
 * Deleter voter class for a given set of options
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class DeleterVoter implements DeleterVoterInterface
{
    /**
     * An array of votable Deleter
     * @var array
     */
    protected $votables = array();

    /**
     * Adds a votable Deleter
     *
     * @param DeleterVotableInterface $votable A votable Deleter
     * @param boolean                 $prepend Whether to prepend the votable
     */
    public function addVotable(DeleterVotableInterface $votable, $prepend = false)
    {
        if ($prepend) {
            array_unshift($this->votables, $votable);
        } else {
            $this->votables[] = $votable;
        }
    }

    /**
     * Adds the default deleters
     *
     * @param FTPWrapper           $wrapper The FTPWrapper instance
     * @param FTPFilesystemManager $manager The Filesystem manager
     */
    public function addDefaultFTPDeleters(FTPWrapper $wrapper, FTPFilesystemManager $manager)
    {
        $this->addVotable(new FTPDeleter\RecursiveDirectoryDeleter($wrapper, $manager));
        $this->addVotable(new FTPDeleter\FileDeleter($wrapper, $manager));
    }

    /**
     * {@inheritDoc}
     */
    public function vote(Filesystem $remote, array $options = array())
    {
        foreach ($this->votables as $votable) {
            if (true === $votable->vote($remote, $options)) {
                return $votable;
            }
        }

        throw new \InvalidArgumentException("Could not resolve a deleter with the given options");
    }
}
