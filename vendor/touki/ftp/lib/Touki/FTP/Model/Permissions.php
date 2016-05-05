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

namespace Touki\FTP\Model;

/**
 * Model class for permissions
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class Permissions
{
    const READABLE   = 4;
    const WRITABLE   = 2;
    const EXECUTABLE = 1;

    /**
     * Flags
     * @var integer
     */
    protected $flags;

    /**
     * Constructor
     *
     * @param integer $flags Integer representation of flags
     */
    public function __construct($flags = 0)
    {
        $this->setFlags($flags);
    }

    /**
     * Get Flags
     *
     * @return integer Integer representation of flags
     */
    public function getFlags()
    {
        return $this->flags;
    }

    /**
     * Set Flags
     *
     * @param integer $flags Integer representation of flags
     */
    public function setFlags($flags = 0)
    {
        $this->flags = $flags;
    }

    /**
     * Is Readable
     *
     * @return boolean
     */
    public function isReadable()
    {
        return !!($this->flags & self::READABLE);
    }

    /**
     * Is Writable
     *
     * @return boolean
     */
    public function isWritable()
    {
        return !!($this->flags & self::WRITABLE);
    }

    /**
     * Is Executable
     *
     * @return boolean
     */
    public function isExecutable()
    {
        return !!($this->flags & self::EXECUTABLE);
    }
}
