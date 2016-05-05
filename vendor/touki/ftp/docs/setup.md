# Setting up the connection

```php
<?php

use Touki\FTP\Connection\Connection;
use Touki\FTP\Connection\AnonymousConnection;
use Touki\FTP\Connection\SSLConnection;

$connection = new Connection('host', 'username', 'password', $port = 21, $timeout = 90, $passive = false);
$connection = new AnonymousConnection('host', $port = 21, $timeout = 90, $passive = false);
$connection = new SSLConnection('host', 'username', 'password', $port = 21, $timeout = 90, $passive = false);

$connection->open();

?>
```

# Setting up with the helper

The easiest way to instanciate the main FTP helper is to use its factory

```php
<?php

use Touki\FTP\FTPFactory;

$factory = new FTPFactory;
$ftp = $factory->build($connection);

/**
 * Note: you can access the created components via getX() method
 */
$wrapper = $factory->getWrapper();
$manager = $factory->getManager();
$dlVoter = $factory->getDownloaderVoter();
$ulVoter = $factory->getUploaderVoter();
$clVoter = $factory->getCreatorVoter();
$dlVoter = $factory->getDeleterVoter();

?>
```

You can also give your own instances by using `setX()` methods  
This allows you to easily use your own instances, **WindowsFilesystemFactory** for example, if you're using a Windows server

```php
<?php

use Touki\FTP\FTPFactory;
use Touki\FTP\WindowsFilesystemFactory;
use Acme\Foo\MyDownloaderVoter;

$factory   = new FTPFactory;
$fsFactory = new WindowsFilesystemFactory;
$dlVoter   = new MyDownloaderVoter;

$factory->setFilesystemFactory($fsFactory);
$factory->setDownloaderVoter($dlVoter);

$ftp = $factory->build($connection);

?>
```

# Using the simple wrapper

If you just plan to use the simple wrapper, you can instanciate it this way

```php
<?php

use Touki\FTP\Connection\Connection;
use Touki\FTP\FTPWrapper;

$connection = new Connection('host', 'user', 'password');
$connection->open();

$wrapper = new FTPWrapper($connection);

$wrapper->chdir("/folder");
$wrapper->cdup();
$wrapper->get(__DIR__.'/foofile.txt', '/folder/foofile.txt');

$connection->close();

?>
```

# Whole package

You can instanciate the whole dependency pack to use its components the way you want

```php
<?php

use Touki\FTP\FTP;
use Touki\FTP\FTPWrapper;
use Touki\FTP\PermissionsFactory;
use Touki\FTP\FilesystemFactory;
use Touki\FTP\WindowsFilesystemFactory;
use Touki\FTP\DownloaderVoter;
use Touki\FTP\UploaderVoter;
use Touki\FTP\CreatorVoter;
use Touki\FTP\DeleterVoter;
use Touki\FTP\Manager\FTPFilesystemManager;

/**
 * The wrapper is a simple class which wraps the base PHP ftp_* functions
 * It needs a Connection instance to get the related stream
 */
$wrapper = new FTPWrapper($connection);

/**
 * This factory creates Permissions models from a given permission string (rw-)
 */
$permFactory = new PermissionsFactory;

/**
 * This factory creates Filesystem models from a given string, ex:
 *     drwxr-x---   3 vincent  vincent      4096 Jul 12 12:16 public_ftp
 *
 * It needs the PermissionsFactory so as to instanciate the given permissions in
 * its model
 */
$fsFactory = new FilesystemFactory($permFactory);

/**
 * If your server runs on WINDOWS, you can use a Windows filesystem factory instead
 */
$fsFactory = new WindowsFilesystemFactory;

/**
 * This manager focuses on operations on remote files and directories
 * It needs the FTPWrapper so as to do operations on the serveri
 * It needs the FilesystemFfactory so as to create models
 */
$manager = new FTPFilesystemManager($wrapper, $fsFactory);


/**
 * This is the downloader voter. It loads multiple DownloaderVotable class and
 * checks which one is needed on given options
 */
$dlVoter = new DownloaderVoter;

/**
 * Loads up default FTP Downloaders
 * It needs the FTPWrapper to be able to share them with the downloaders
 */
$dlVoter->addDefaultFTPDownloaders($wrapper);

/**
 * This is the uploader voter. It loads multiple UploaderVotable class and
 * checks which one is needed on given options
 */
$ulVoter = new UploaderVoter;

/**
 * Loads up default FTP Uploaders
 * It needs the FTPWrapper to be able to share them with the uploaders
 */
$ulVoter->addDefaultFTPUploaders($wrapper);

/**
 * This is the creator voter. It loads multiple CreatorVotable class and
 * checks which one is needed on the given options
 */
$crVoter = new CreatorVoter;

/**
 * Loads up the default FTP creators.
 * It needs the FTPWrapper and the FTPFilesystemManager to be able to share
 * them whith the creators
 */
$crVoter->addDefaultFTPCreators($wrapper, $manager);

/**
 * This is the deleter voter. It loads multiple DeleterVotable classes and
 * checks which one is needed on the given options
 */
$deVoter = new DeleterVoter;

/**
 * Loads up the default FTP deleters.
 * It needs the FTPWrapper and the FTPFilesystemManager to be able to share
 * them with the deleters
 */
$deVoter->addDefaultFTPDeleters($wrapper, $manager);

/**
 * Finally creates the main FTP
 * It needs the manager to do operations on files
 * It needs the download voter to pick-up the right downloader on ->download
 * It needs the upload voter to pick-up the right uploader on ->upload
 * It needs the creator voter to pick-up the right creator on ->create
 * It needs the deleter voter to pick-up the right deleter on ->delete
 */
return new FTP($manager, $dlVoter, $ulVoter, $crVoter, $deVoter);

?>
```

You can now read about

 * [How to fetch files and directories][1]
 * [How to download][2]
 * [How to upload][3]
 * [How to create files and directories][4]
 * [How to delete files and directories][5]

[1]: https://github.com/touki653/php-ftp-wrapper/blob/master/docs/fetching_files_and_directories.md
[2]: https://github.com/touki653/php-ftp-wrapper/blob/master/docs/downloading.md
[3]: https://github.com/touki653/php-ftp-wrapper/blob/master/docs/uploading.md
[4]: https://github.com/touki653/php-ftp-wrapper/blob/master/docs/creating.md
[5]: https://github.com/touki653/php-ftp-wrapper/blob/master/docs/deleting.md
