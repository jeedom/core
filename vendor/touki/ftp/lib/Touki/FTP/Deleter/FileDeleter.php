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

namespace Touki\FTP\Deleter;

use Touki\FTP\FTP;
use Touki\FTP\FTPWrapper;
use Touki\FTP\DeleterInterface;
use Touki\FTP\DeleterVotableInterface;
use Touki\FTP\Model\Filesystem;
use Touki\FTP\Model\File;
use Touki\FTP\Manager\FTPFilesystemManager;
use Touki\FTP\Exception\DirectoryException;

/**
 * File Deleter
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class FileDeleter implements DeleterInterface, DeleterVotableInterface
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
            ($remote instanceof File)
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function delete(Filesystem $remote, array $options = array())
    {
        if (!($remote instanceof File)) {
            throw new \InvalidArgumentException(sprintf(
                "Invalid filesystem given, expected instance of File got %s",
                get_class($remote)
            ));
        }

        if (null === $this->manager->findFileByFile($remote)) {
            throw new DirectoryException(sprintf("Could not locate file %s", $remote->getRealpath()));
        }

        $this->wrapper->delete($remote->getRealpath());

        return true;
    }
}
