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
 * File factory that parses input like
 *   drwxr-x---   3 vincent  vincent      4096 Jul 12 12:16 public_ftp
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class FilesystemFactory implements FilesystemFactoryInterface
{
    /**
     * A Permissions Factory
     * @var PermissionsFactory
     */
    protected $permissionsFactory;

    /**
     * Constructor
     *
     * @param PermissionsFactory $permissionsFactory A Permissions Factory
     */
    public function __construct(PermissionsFactory $permissionsFactory)
    {
        $this->permissionsFactory = $permissionsFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function build($input, $prefix = '')
    {
        $prefix     = rtrim($prefix, '/');
        $parts      = preg_split("/\s+/", $input);
        $type       = $parts[0][0];
        $filesystem = $this->resolveFile($type);
        $permParts  = str_split(substr($parts[0], 1, 9), 3);
        $hours      = sscanf($parts[7], "%d:%d");
        $name       = implode(' ', array_slice($parts, 8));

        if (null === $hours[1]) {
            $year  = $hours[0];
            $hours = array('00', '00');
        } else {
            $year = date('Y');
        }

        $date = new \DateTime(sprintf("%s-%s-%s %s:%s", $year, $parts[5], $parts[6], $hours[0], $hours[1]));

        $filesystem
            ->setRealpath(sprintf("%s/%s", $prefix, $name))
            ->setOwnerPermissions($this->permissionsFactory->build($permParts[0]))
            ->setGroupPermissions($this->permissionsFactory->build($permParts[1]))
            ->setGuestPermissions($this->permissionsFactory->build($permParts[2]))
            ->setOwner($parts[2])
            ->setGroup($parts[3])
            ->setSize($parts[4])
            ->setMtime($date)
        ;

        return $filesystem;
    }

    /**
     * Resolves the file type
     *
     * @param  string     $type File letter
     * @return Filesystem
     */
    private function resolveFile($type)
    {
        if ($type == 'd') {
            return new Directory;
        } else {
            return new File;
        }
    }
}
