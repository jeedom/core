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
use Touki\FTP\Model\Directory;
use Touki\FTP\Model\File;
use Touki\FTP\Manager\FTPFilesystemManager;
use Touki\FTP\Exception\DirectoryException;

/**
 * FTP Class which implements standard behaviours of FTP
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class FTP implements FTPInterface
{
    const NON_BLOCKING          = 1;
    const NON_BLOCKING_CALLBACK = 2;
    const TRANSFER_MODE         = 3;
    const START_POS             = 4;
    const RECURSIVE             = 5;

    /**
     * Filesystem manager
     * @var FTPFilesystemManager
     */
    protected $manager;

    /**
     * Downloader Voter
     * @var DownloaderVoterInterface
     */
    protected $dlVoter;

    /**
     * Uploader Voter
     * @var UploaderVoterInterface
     */
    protected $ulVoter;

    /**
     * Creator Voter
     * @var CreatorVoter
     */
    protected $creatorVoter;

    /**
     * Deleter Voter
     * @var DeleterVoter
     */
    protected $deleterVoter;

    /**
     * Constructor
     *
     * @param FTPFilesystemManager $manager Directory manager
     */
    public function __construct(
        FTPFilesystemManager $manager,
        DownloaderVoterInterface $dlVoter,
        UploaderVoterInterface $ulVoter,
        CreatorVoter $creatorVoter,
        DeleterVoter $deleterVoter
    ) {
        $this->manager      = $manager;
        $this->dlVoter      = $dlVoter;
        $this->ulVoter      = $ulVoter;
        $this->creatorVoter = $creatorVoter;
        $this->deleterVoter = $deleterVoter;
    }

    /**
     * Get Manager
     *
     * @return FTPFilesystemManager Filesystem manager
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * {@inheritDoc}
     */
    public function findFilesystems(Directory $directory)
    {
        return $this->manager->findAll($directory);
    }

    /**
     * {@inheritDoc}
     */
    public function findFiles(Directory $directory)
    {
        return $this->manager->findFiles($directory);
    }

    /**
     * {@inheritDoc}
     */
    public function findDirectories(Directory $directory)
    {
        return $this->manager->findDirectories($directory);
    }

    /**
     * {@inheritDoc}
     */
    public function filesystemExists(Filesystem $filesystem)
    {
        try {
            return null !== $this->manager->findFilesystemByFilesystem($filesystem);
        } catch (DirectoryException $e) {
            return false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function fileExists(File $file)
    {
        try {
            return null !== $this->manager->findFileByFile($file);
        } catch (DirectoryException $e) {
            return false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function directoryExists(Directory $directory)
    {
        try {
            return null !== $this->manager->findDirectoryByDirectory($directory);
        } catch (DirectoryException $e) {
            return false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function findFilesystemByName($filename, Directory $inDirectory = null)
    {
        return $this->manager->findFilesystemByName($filename, $inDirectory);
    }

    /**
     * {@inheritDoc}
     */
    public function findFileByName($filename, Directory $inDirectory = null)
    {
        return $this->manager->findFileByName($filename, $inDirectory);
    }

    /**
     * {@inheritDoc}
     */
    public function findDirectoryByName($directory, Directory $inDirectory = null)
    {
        return $this->manager->findDirectoryByName($directory, $inDirectory);
    }

    /**
     * {@inheritDoc}
     */
    public function getCwd()
    {
        return $this->manager->getCwd();
    }

    /**
     * {@inheritDoc}
     */
    public function download($local, Filesystem $remote, array $options = array())
    {
        if (!$this->filesystemExists($remote)) {
            throw new DirectoryException(sprintf(
                "Remote filesystem %s of type %s does not exists",
                $remote->getRealpath(),
                get_class($remote)
            ));
        }

        $options = $options + array(
            FTP::NON_BLOCKING => false
        );
        $downloader = $this->dlVoter->vote($local, $remote, $options);

        return $downloader->download($local, $remote, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function upload(Filesystem $remote, $local, array $options = array())
    {
        $options = $options + array(
            FTP::NON_BLOCKING => false
        );
        $uploader = $this->ulVoter->vote($remote, $local, $options);

        return $uploader->upload($remote, $local, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function create(Filesystem $filesystem, array $options = array())
    {
        $options = $options + array(
            FTP::RECURSIVE => true
        );
        $creator = $this->creatorVoter->vote($filesystem, $options);

        return $creator->create($filesystem, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function delete(Filesystem $filesystem, array $options = array())
    {
        $options = $options + array(
            FTP::RECURSIVE => true
        );
        $deleter = $this->deleterVoter->vote($filesystem, $options);

        return $deleter->delete($filesystem, $options);
    }
}
