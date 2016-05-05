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

/**
 * Simple wrapper FTP class
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class FTPWrapper
{
    const ASCII       = FTP_ASCII;
    const TEXT        = FTP_TEXT;
    const BINARY      = FTP_BINARY;
    const IMAGE       = FTP_IMAGE;
    const TIMEOUT_SEC = FTP_TIMEOUT_SEC;
    const AUTOSEEK    = FTP_AUTOSEEK;
    const AUTORESUME  = FTP_AUTORESUME;
    const FAILED      = FTP_FAILED;
    const FINISHED    = FTP_FINISHED;
    const MOREDATA    = FTP_MOREDATA;

    /**
     * FTP Connection
     * @var ConnectionInterface
     */
    protected $connection;

    /**
     * Constructor
     *
     * @param ConnectionInterface $connection A ConnectionInterface instance
     */
    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Get Connection
     *
     * @return ConnectionInterface
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Set Connection
     *
     * @param ConnectionInterface $connection A ConnectionInterface instance
     */
    public function setConnection(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Downloads a remote file
     * @link http://php.net/ftp_get
     *
     * @param  string  $localFile  The local file path
     * @param  string  $remoteFile The remote file path
     * @param  integer $mode       The transfer mode (FTPWrapper::ASCII, FTPWrapper::BINARY)
     * @param  integer $resumepos  The position in the remote file to start downloading from
     * @return boolean TRUE on success, FALSE on failure
     */
    public function get($localFile, $remoteFile, $mode = self::BINARY, $resumepos = 0)
    {
        return ftp_get($this->connection->getStream(), $localFile, $remoteFile, $mode, $resumepos);
    }

    /**
     * Retrieves a file from the server and writes it to a local file
     * @link http://php.net/ftp_nb_get
     *
     * @param  string  $localFile  The local file path
     * @param  string  $remoteFile The remote file path
     * @param  integer $mode       The transfer mode (FTPWrapper::ASCII, FTPWrapper::BINARY)
     * @param  integer $resumepos  The position in the remote file to start downloading from
     * @return integer FTPWrapper::FAILED, FTPWrapper::FINISHED or FTPWrapper::MOREDATA
     */
    public function getNb($localFile, $remoteFile, $mode = self::BINARY, $resumepos = 0)
    {
        return ftp_nb_get($this->connection->getStream(), $localFile, $remoteFile, $mode, $resumepos);
    }

    /**
     * Downloads a remote file and saves to an open file
     * @link http://php.net/ftp_fget
     *
     * @param  resource $handle     An open file pointer in which we store the data
     * @param  string   $remoteFile The remote file path
     * @param  integer  $mode       The transfer mode (FTPWrapper::ASCII or FTPWrapper::BINARY)
     * @param  integer  $resumepos  The position in the remote file to start downloading from
     * @return boolean  TRUE on success, FALSE on failure
     */
    public function fget($handle, $remoteFile, $mode = self::BINARY, $resumepos = 0)
    {
        return ftp_fget($this->connection->getStream(), $handle, $remoteFile, $mode, $resumepos);
    }

    /**
     * Retrieves a remote file and writes it ton an open file (non-blocking)
     * @link http://php.net/ftp_nb_fget
     *
     * @param  resource $handle     An open file pointer in which we store the data
     * @param  string   $remoteFile The remote file path
     * @param  integer  $mode       The transfer mode (FTPWrapper::ASCII or FTPWrapper::BINARY)
     * @param  integer  $resumepos  The position in the remote file to start downloading from
     * @return integer  FTPWrapper::FAILED, FTPWrapper::FINISHED or FTPWrapper::MOREDATA
     */
    public function fgetNb($handle, $remoteFile, $mode = self::BINARY, $resumepos = 0)
    {
        return ftp_nb_fget($this->connection->getStream(), $handle, $remoteFile, $mode, $resumepos);
    }

    /**
     * Continues retrieving/sending a file (non-blocking)
     * @link http://php.net/ftp_nb_continue
     *
     * @return integer FTPWrapper::FAILED, FTPWrapper::FINISHED or FTPWrapper::MOREDATA
     */
    public function nbContinue()
    {
        return ftp_nb_continue($this->connection->getStream());
    }

    /**
     * Stores a file on the server
     * @link http://php.net/ftp_put
     *
     * @param  string  $remoteFile The remote file path
     * @param  string  $localFile  The local file path
     * @param  integer $mode       The transfer mode (FTPWrapper::ASCII or FTPWrapper::BINARY)
     * @param  integer $startpos   The position in the remote file to start downloading from
     * @return boolean TRUE on success, FALSE on failure
     */
    public function put($remoteFile, $localFile, $mode = self::BINARY, $startpos = 0)
    {
        return ftp_put($this->connection->getStream(), $remoteFile, $localFile, $mode, $startpos);
    }

    /**
     * Stores a file on the server (non blocking)
     * @link http://php.net/ftp_nb_put
     *
     * @param  string  $remoteFile The remote file path
     * @param  string  $localFile  The local file path
     * @param  integer $mode       The transfer mode (FTPWrapper::ASCII or FTPWrapper::BINARY)
     * @param  integer $startpos   The position in the remote file to start downloading from
     * @return integer FTPWrapper::FAILED, FTPWrapper::FINISHED or FTPWrapper::MOREDATA
     */
    public function putNb($remoteFile, $localFile, $mode = self::BINARY, $startpos = 0)
    {
        return ftp_nb_put($this->connection->getStream(), $remoteFile, $localFile, $mode, $startpos);
    }

    /**
     * Uploads from an open file
     * @link http://php.net/ftp_fput
     *
     * @param  string   $remoteFile The remote file path
     * @param  resource $handle     An open file pointer on the local file
     * @param  integer  $mode       The transfer mode (FTPWrapper::ASCII or FTPWrapper::BINARY)
     * @param  integer  $startpos   The position in the remote file to start uploading to
     * @return boolean  TRUE on success, FALSE on failure
     */
    public function fput($remoteFile, $handle, $mode = self::BINARY, $startpos = 0)
    {
        return ftp_fput($this->connection->getStream(), $remoteFile, $handle, $mode, $startpos);
    }

    /**
     * Stores a file from an open file to the FTP server (non-blocking)
     * @link http://php.net/ftp_nb_fput
     *
     * @param  string   $remoteFile The remote file path
     * @param  resource $handle     An open file pointer on the local file
     * @param  integer  $mode       The transfer mode (FTPWrapper::ASCII or FTPWrapper::BINARY)
     * @param  integer  $startpos   The position in the remote file to start uploading to
     * @return integer  FTPWrapper::FAILED, FTPWrapper::FINISHED or FTPWrapper::MOREDATA
     */
    public function fputNb($remoteFile, $handle, $mode, $startpos = 0)
    {
        return ftp_nb_fput($this->connection->getStream(), $remoteFile, $handle, $mode, $startpos);
    }

    /**
     * Retrieves various runtime behaviour of the current stream
     * @link http://php.net/ftp_get_option
     *
     * @param  integer $option Runtime option. (FTPWrapper::TIMEOUT_SEC, FTPWrapper::AUTOSEEK)
     * @return mixed   Value on success, FALSE on error
     */
    public function getOption($option)
    {
        return ftp_get_option($this->connection->getStream(), $option);
    }

    /**
     * Set various runtime FTP options
     * @link http://php.net/ftp_set_option
     *
     * @param  integer $option Runtime option (FTPWrapper::TIMEOUT_SEC, FTPWrapper::AUTOSEEK)
     * @param  mixed   $value  Option's value
     * @return boolean TRUE on success, FALSE on error
     */
    public function setOption($option, $value)
    {
        return ftp_set_option($this->connection->getStream(), $option, $value);
    }

    /**
     * Changes to the parent directory
     * @link http://php.net/ftp_cdup
     *
     * @return boolean TRUE on success FALSE on failure
     */
    public function cdup()
    {
        return ftp_cdup($this->connection->getStream());
    }

    /**
     * Changes the current directory
     * @link http://php.net/ftp_chdir
     *
     * @param  string  $directory The target directory
     * @return boolean
     */
    public function chdir($directory)
    {
        return ftp_chdir($this->connection->getStream(), $directory);
    }

    /**
     * Creates a directory
     * @link http://php.net/ftp_mkdir
     *
     * @param  string $directory The name of the directory to create
     * @return string The new directory name on success, FALSE on error
     */
    public function mkdir($directory)
    {
        return ftp_mkdir($this->connection->getStream(), $directory);
    }

    /**
     * Removes a directory
     * @link http://php.net/php_rmdir
     *
     * @param  string  $directory The directory to delete
     * @return boolean TRUE on success, FALSE on failure
     */
    public function rmdir($directory)
    {
        return ftp_rmdir($this->connection->getStream(), $directory);
    }

    /**
     * Returns a list of files in the given directory
     * @link http://php.net/ftp_nlist
     *
     * @param  string $directory The directory to be listed
     * @return array  An array of filenames from the specified directory, FALSE on error
     */
    public function nlist($directory)
    {
        return ftp_nlist($this->connection->getStream(), $directory);
    }

    /**
     * Returns a detailed list of files in the given directory
     * @link http://php.net/ftp_rawlist
     *
     * @param  string  $directory The directory path
     * @param  boolean $recursive If set to TRUE, the issued command will be LIST -R
     * @return array   Each elements corresponds to one line of text
     */
    public function rawlist($directory, $recursive = false)
    {
        return ftp_rawlist($this->connection->getStream(), $directory, $recursive);
    }

    /**
     * Returns the current directory name
     * @link http://php.net/ftp_pwd
     *
     * @return string The directory name, FALSE on error
     */
    public function pwd()
    {
        return ftp_pwd($this->connection->getStream());
    }

    /**
     * Sends an arbitrary command to the server
     * @link http://php.net/ftp_raw
     *
     * @param  string $command The command to execute
     * @return array  Server's response as an array of strings
     */
    public function raw($command)
    {
        return ftp_raw($this->connection->getStream(), $command);
    }

    /**
     * Requests execution of a command
     * @link http://php.net/ftp_exec
     *
     * @param  string  $command The comman to execute
     * @return boolean TRUE on success, FALSE on failure
     */
    public function exec($command)
    {
        return ftp_exec($this->connection->getStream(), $command);
    }

    /**
     * Allocates space for a file to be uploaded
     * @link http://php.net/ftp_alloc
     *
     * @param  integer $filesize The number of bytes to allocate
     * @param  string  &$result  A textual representation of the servers response
     * @return boolean TRUE on success, FALSE on failure
     */
    public function alloc($filesize, &$result = null)
    {
        return ftp_alloc($this->connection->getStream(), $filesize, $result);
    }

    /**
     * Set permissions on a file
     * @link http://php.net/ftp_chmod
     *
     * @param  integer $mode     The new permissions given as an octal value
     * @param  string  $filename The remote file
     * @return integer New permissions or FALSE on error
     */
    public function chmod($mode, $filename)
    {
        return ftp_chmod($this->connection->getStream(), $mode, $filename);
    }

    /**
     * Renames a file or a directory
     * @link http://php.net/ftp_rename
     *
     * @param  string  $oldname The old file/directory name
     * @param  string  $newname the new name
     * @return boolean TRUE on success, FALSE on failure
     */
    public function rename($oldname, $newname)
    {
        return ftp_rename($this->connection->getStream(), $oldname, $newname);
    }

    /**
     * Deletes a remote file
     * @link http://php.net/ftp_delete
     *
     * @param  string  $path The file to delete
     * @return boolean TRUE on success, FALSE on failure
     */
    public function delete($path)
    {
        return ftp_delete($this->connection->getStream(), $path);
    }

    /**
     * Returns the last modified time of the given file
     * @link http://php.net/ftp_mdtm
     *
     * @param  string  $remoteFile The file from which to extract the last modification time
     * @return integer Unix timestamp on success, -1 on error
     */
    public function mdtm($remoteFile)
    {
        return ftp_mdtm($this->connection->getStream(), $remoteFile);
    }

    /**
     * Turns passive mode on or off
     * @link http://php.net/php_pasv
     *
     * @param  boolean $pasv If TRUE, the passive mode is turned on, else it's turned off
     * @return boolean TRUE on success, FALSE on failure
     */
    public function pasv($pasv)
    {
        return ftp_pasv($this->connection->getStream(), $pasv);
    }

    /**
     * Returns the size of a given file
     * @link http://php.net/php_size
     *
     * @param  string  $remoteFile The remote file
     * @return integer The file size on success, -1 on error
     */
    public function size($remoteFile)
    {
        return ftp_size($this->connection->getStream(), $remoteFile);
    }

    /**
     * Returns the system type identifier
     * @link http://php.net/php_systype
     *
     * @return string The remote system type, FALSE on error
     */
    public function systype()
    {
        return ftp_systype($this->connection->getStream());
    }
}
