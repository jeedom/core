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
use Touki\FTP\Model\File;
use Touki\FTP\Model\Directory;

/**
 * Base FTP Interface
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
interface FTPInterface
{
    /**
     * Finds remote filesystems in the given remote directory
     *
     * @param  Directory $directory A Directory instance
     * @return array     An array of Filesystem
     */
    public function findFilesystems(Directory $directory);

    /**
     * Finds files in the given remote directory
     *
     * @param  Directory $directory A Directory instance
     * @return array     An array of File
     */
    public function findFiles(Directory $directory);

    /**
     * Finds directories in the given remote directory
     *
     * @param  Directory $directory A Directory instance
     * @return array     An array of Directory
     */
    public function findDirectories(Directory $directory);

    /**
     * Checks whether a remote filesystem exists
     *
     * @param  Filesystem $fs A Filesystem instance
     * @return boolean    TRUE if it exists, FALSE if not
     */
    public function filesystemExists(Filesystem $fs);

    /**
     * Checks whether a remote file exists
     *
     * @param  File    $file A File instance
     * @return boolean TRUE if it exists, FALSE if not
     */
    public function fileExists(File $file);

    /**
     * Checks whether a remote directory exists
     *
     * @param  Directory $directory A Directory instance
     * @return boolean   TRUE if it exists, FALSE if not
     */
    public function directoryExists(Directory $directory);

    /**
     * Finds a remote Filesystem by its name
     *
     * @param  string     $filename Filesystem name
     * @return Filesystem A Filesystem instance, NULL if it doesn't exists
     */
    public function findFilesystemByName($filename, Directory $inDirectory = null);

    /**
     * Finds a remote File by its name
     *
     * @param  string $filename File name
     * @return File   A File instance, NULL if it doesn't exists
     */
    public function findFileByName($filename, Directory $inDirectory = null);

    /**
     * Finds a directory by its name
     *
     * @param  string    $directory Directory name
     * @return Directory A Directory instance, NULL if it doesn't exists
     */
    public function findDirectoryByName($directory, Directory $inDirectory = null);

    /**
     * Returns the current working directory
     *
     * @return Directory A Directory instance
     */
    public function getCwd();

    /**
     * Downloads a remote Filesystem into the given local
     *
     * @param  mixed      $local   Local file, resource
     * @param  Filesystem $remote  The remote Filesystem
     * @param  array      $options Downloader's options
     * @return boolean    TRUE on success, FALSE on failure
     */
    public function download($local, Filesystem $remote, array $options = array());

    /**
     * Uploads to a remote Filesystem from a given local
     *
     * @param  Filesystem $remote  The remote Filesystem
     * @param  mixed      $local   Local file, resource
     * @param  array      $options Uploader's options
     * @return boolean    TRUE on success, FALSE on failure
     */
    public function upload(Filesystem $remote, $local, array $options = array());

    /**
     * Creates a Filesystem on remote server
     *
     * @param  Filesystem $filesystem Filesystem to create
     * @param  array      $options    Creator's options
     * @return boolean    TRUE on success, FALSE on failure
     */
    public function create(Filesystem $filesystem, array $options = array());
}
