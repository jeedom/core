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

use DateTime;

/**
 * Base class for Directories, files...
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
abstract class Filesystem
{
    /**
     * Real path to file
     * @var string
     */
    protected $realpath;

    /**
     * Owner Permissions
     * @var Permissions
     */
    protected $ownerPermissions;

    /**
     * Group Permissions
     * @var Permissions
     */
    protected $groupPermissions;

    /**
     * Guest Permissions
     * @var Permissions
     */
    protected $guestPermissions;

    /**
     * Owner name
     * @var string
     */
    protected $owner;

    /**
     * Group name
     * @var string
     */
    protected $group;

    /**
     * Size
     * @var integer
     */
    protected $size;

    /**
     * Last modified time
     * @var DateTime
     */
    protected $mtime;

    /**
     * Constructor
     *
     * @param string $realpath Full path to filesystem
     */
    public function __construct($realpath = null)
    {
        $this->realpath = $realpath;
    }

    /**
     * Get Realpath
     *
     * @return string Full path to filesystem
     */
    public function getRealpath()
    {
        return $this->realpath;
    }

    /**
     * Set Realpath
     *
     * @param  string     $realpath Full path to filesystem
     * @return Filesystem
     */
    public function setRealpath($realpath)
    {
        $this->realpath = $realpath;

        return $this;
    }

    /**
     * Get OwnerPermissions
     *
     * @return Permissions Owner Permissions
     */
    public function getOwnerPermissions()
    {
        return $this->ownerPermissions;
    }

    /**
     * Set OwnerPermissions
     *
     * @param  Permissions $ownerPermissions Owner Permissions
     * @return Filesystem
     */
    public function setOwnerPermissions(Permissions $ownerPermissions)
    {
        $this->ownerPermissions = $ownerPermissions;

        return $this;
    }

    /**
     * Get GroupPermissions
     *
     * @return Permissions Group Permissions
     */
    public function getGroupPermissions()
    {
        return $this->groupPermissions;
    }

    /**
     * Set GroupPermissions
     *
     * @param  Permissions $groupPermissions Group Permissions
     * @return Filesystem
     */
    public function setGroupPermissions(Permissions $groupPermissions)
    {
        $this->groupPermissions = $groupPermissions;

        return $this;
    }

    /**
     * Get GuestPermissions
     *
     * @return Permissions Guest Permissions
     */
    public function getGuestPermissions()
    {
        return $this->guestPermissions;
    }

    /**
     * Set GuestPermissions
     *
     * @param  Permissions $guestPermissions Guest Permissions
     * @return Filesystem
     */
    public function setGuestPermissions(Permissions $guestPermissions)
    {
        $this->guestPermissions = $guestPermissions;

        return $this;
    }

    /**
     * Get Owner
     *
     * @return string Owner name
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set Owner
     *
     * @param  string     $owner Owner name
     * @return Filesystem
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get Group
     *
     * @return string Group name
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * Set Group
     *
     * @param  string     $group Group name
     * @return Filesystem
     */
    public function setGroup($group)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * Get Size
     *
     * @return integer Size
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set Size
     *
     * @param  integer    $size Size
     * @return Filesystem
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get Mtime
     *
     * @return DateTime Last Modified time
     */
    public function getMtime()
    {
        return $this->mtime;
    }

    /**
     * Set Mtime
     *
     * @param  DateTime   $mtime Last Modified time
     * @return Filesystem
     */
    public function setMtime(DateTime $mtime)
    {
        $this->mtime = $mtime;

        return $this;
    }
}
