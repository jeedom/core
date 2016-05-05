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

namespace Touki\FTP\Downloader;

use Touki\FTP\FTP;
use Touki\FTP\FTPWrapper;
use Touki\FTP\DownloaderInterface;
use Touki\FTP\DownloaderVotableInterface;
use Touki\FTP\Model\Filesystem;
use Touki\FTP\Model\File;

/**
 * FTP Non blocking File downloader
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class NbFileDownloader implements DownloaderInterface, DownloaderVotableInterface
{
    /**
     * FTP Wrapper
     * @var FTPWrapper
     */
    protected $wrapper;

    /**
     * Constructor
     *
     * @param FTPWrapper $wrapper An FTPWrapper instance
     */
    public function __construct(FTPWrapper $wrapper)
    {
        $this->wrapper = $wrapper;
    }

    /**
     * {@inheritDoc}
     */
    public function vote($local, Filesystem $remote, array $options = array())
    {
        return
            ($remote instanceof File)
            && false === is_resource($local)
            && false === is_dir($local)
            && isset($options[ FTP::NON_BLOCKING ])
            && true === $options[ FTP::NON_BLOCKING ]
        ;
    }

    /**
     * {@inheritDoc}
     *
     * @throws InvalidArgumentException When argument(s) is(are) incorrect
     */
    public function download($local, Filesystem $remote, array $options = array())
    {
        if (!($remote instanceof File)) {
            throw new \InvalidArgumentException(sprintf(
                "Invalid remote file given, expected instance of File, got %s",
                get_class($remote)
            ));
        }

        if (false !== is_resource($local)) {
            throw new \InvalidArgumentException("Invalid local file given. Expected filename, got resource");
        }

        if (false !== is_dir($local)) {
            throw new \InvalidArgumentException("Invalid local file given. Expected filename, got directory");
        }

        if (!isset($options[ FTP::NON_BLOCKING ]) || true !== $options[ FTP::NON_BLOCKING ]) {
            throw new \InvalidArgumentException("Invalid option given. Expected true as FTP::NON_BLOCKING parameter");
        }

        $defaults = array(
            FTP::NON_BLOCKING_CALLBACK => function() { },
            FTP::TRANSFER_MODE => FTPWrapper::BINARY,
            FTP::START_POS     => 0
        );
        $options  = $options + $defaults;
        $callback = $options[ FTP::NON_BLOCKING_CALLBACK ];

        $state = $this->wrapper->getNb($local, $remote->getRealpath(), $options[ FTP::TRANSFER_MODE ], $options[ FTP::START_POS ]);
        call_user_func_array($callback, array());

        while ($state == FTPWrapper::MOREDATA) {
            $state = $this->wrapper->nbContinue();

            call_user_func_array($callback, array());
        }

        return $state === FTPWrapper::FINISHED;
    }
}
