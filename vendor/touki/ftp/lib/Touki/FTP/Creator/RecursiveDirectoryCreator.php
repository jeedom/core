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

namespace Touki\FTP\Creator;

use Touki\FTP\FTP;
use Touki\FTP\FTPWrapper;
use Touki\FTP\CreatorInterface;
use Touki\FTP\CreatorVotableInterface;
use Touki\FTP\Model\Filesystem;
use Touki\FTP\Model\Directory;
use Touki\FTP\Manager\FTPFilesystemManager;
use Touki\FTP\Exception\DirectoryException;

/**
 * Recursive Directory Creator
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class RecursiveDirectoryCreator implements CreatorInterface, CreatorVotableInterface
{
    /**
     * FTP Wrapper
     * @var FTPWrapper
     */
    protected $wrapper;

    /**
     * Filesystem Manager
     * @var FTPFilesystemManager
     */
    protected $manager;

    /**
     * Constructor
     *
     * @param FTPWrapper           $wrapper A FTP Wrapper
     * @param FTPFilesystemManager $manager A Manager instance
     */
    public function __construct(FTPWrapper $wrapper, FTPFilesystemManager $manager)
    {
        $this->wrapper = $wrapper;
        $this->manager = $manager;
    }

    /**
     * {@inheritDoc}
     */
    public function vote(Filesystem $remote, array $options = array())
    {
        return
            ($remote instanceof Directory)
            && isset($options[FTP::RECURSIVE])
            && $options[FTP::RECURSIVE] === true
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function create(Filesystem $remote, array $options = array())
    {
        if (!($remote instanceof Directory)) {
            throw new \InvalidArgumentException(sprintf(
                "Invalid filesystem given, expected instance of Directory got %s",
                get_class($remote)
            ));
        }

        if (!isset($options[FTP::RECURSIVE]) || true !== $options[FTP::RECURSIVE]) {
            throw new \InvalidArgumentException("Invalid option given. Expected true as FTP::RECURSIVE parameter");
        }

        $full  = ltrim($remote->getRealpath(), '/');
        $parts = explode('/', $full);
        $path  = '';

        foreach ($parts as $part) {
            $path = sprintf("%s/%s", $path, $part);

            if (null === $this->manager->findDirectoryByName($path) && !$this->wrapper->mkdir($path)) {
                throw new DirectoryException(sprintf("Could not create directory %s", $path));
            }
        }

        return true;
    }
}
