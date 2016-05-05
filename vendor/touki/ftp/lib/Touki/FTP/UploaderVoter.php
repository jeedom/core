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

use Touki\FTP\Uploader as FTPUploader;
use Touki\FTP\Model\Filesystem;

/**
 * Voter class for a given set of options
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class UploaderVoter implements UploaderVoterInterface
{
    /**
     * An array of votable uploader
     * @var array
     */
    protected $votables = array();

    /**
     * Adds a votable uploader
     *
     * @param UploaderVotableInterface $votable A votable uploader
     * @param boolean                  $prepend Whether to prepend the votable
     */
    public function addVotable(UploaderVotableInterface $votable, $prepend = false)
    {
        if ($prepend) {
            array_unshift($this->votables, $votable);
        } else {
            $this->votables[] = $votable;
        }
    }

    /**
     * Adds the default uploaders
     *
     * @param FTPWrapper $wrapper An FTP Wrapper
     */
    public function addDefaultFTPUploaders(FTPWrapper $wrapper)
    {
        $this->addVotable(new FTPUploader\FileUploader($wrapper));
        $this->addVotable(new FTPUploader\ResourceUploader($wrapper));
        $this->addVotable(new FTPUploader\NbFileUploader($wrapper));
        $this->addVotable(new FTPUploader\NbResourceUploader($wrapper));
    }

    /**
     * {@inheritDoc}
     */
    public function vote(Filesystem $remote, $local, array $options = array())
    {
        foreach ($this->votables as $votable) {
            if (true === $votable->vote($remote, $local, $options)) {
                return $votable;
            }
        }

        throw new \InvalidArgumentException("Could not resolve an uploader with the given options");
    }
}
