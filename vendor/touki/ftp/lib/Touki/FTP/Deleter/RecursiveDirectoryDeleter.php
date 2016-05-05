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
use Touki\FTP\Model\Directory;
use Touki\FTP\Manager\FTPFilesystemManager;

/**
 * Recursive Directory Deleter
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class RecursiveDirectoryDeleter implements DeleterInterface, DeleterVotableInterface
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
    public function delete(Filesystem $remote, array $options = array())
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

        $this->deleteFiles($remote);
        $this->deleteDirectories($remote, $options);

        $this->wrapper->rmdir($remote->getRealpath());

        return true;
    }

    /**
     * Deletes files in a directory
     *
     * @param string $path /remote/path/
     */
    private function deleteFiles(Directory $path)
    {
        foreach ($this->manager->findFiles($path) as $file) {
            $this->wrapper->delete($file->getRealpath());
        }
    }

    /**
     * Deletes directories in a directory
     *
     * @param Directory $path    /remote/path/
     * @param array     $options Deleter options
     */
    private function deleteDirectories(Directory $path, array $options = array())
    {
        foreach ($this->manager->findDirectories($path) as $dir) {
            $this->delete($dir, $options);
        }
    }
}
