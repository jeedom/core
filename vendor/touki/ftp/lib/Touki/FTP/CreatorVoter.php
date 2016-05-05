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

use Touki\FTP\Creator as FTPCreator;
use Touki\FTP\Model\Filesystem;
use Touki\FTP\Manager\FTPFilesystemManager;

/**
 * Voter class for a given set of options
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class CreatorVoter implements CreatorVoterInterface
{
    /**
     * An array of votable Creator
     * @var array
     */
    protected $votables = array();

    /**
     * Adds a votable Creator
     *
     * @param CreatorVotableInterface $votable A votable Creator
     * @param boolean                 $prepend Whether to prepend the votable
     */
    public function addVotable(CreatorVotableInterface $votable, $prepend = false)
    {
        if ($prepend) {
            array_unshift($this->votables, $votable);
        } else {
            $this->votables[] = $votable;
        }
    }

    /**
     * Adds the default creators
     *
     * @param FTPWrapper           $wrapper The FTPWrapper instance
     * @param FTPFilesystemManager $manager The Filesystem manager
     */
    public function addDefaultFTPCreators(FTPWrapper $wrapper, FTPFilesystemManager $manager)
    {
        $this->addVotable(new FTPCreator\RecursiveDirectoryCreator($wrapper, $manager));
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

        throw new \InvalidArgumentException("Could not resolve a creator with the given options");
    }
}
