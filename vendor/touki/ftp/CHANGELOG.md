1.1.3
-----

 * Fixed bug #7 - [dirname](http://php.net/dirname) for windows server returns `\` instead of `/`
 * Fixed bug #11 - Invalid getCwd on root directory

1.1.2
-----

 * Removed Deep FTP Folder for Creators, Deleters, Downloaders and Uploaders

   > Example: From `Touki\FTP\Downloader\FTP\FileDownloader` to `Touki\FTP\Downloader\FileDownloader`

 * Removed hard call to passive mode when uploading/downloading: Issue [#4](https://github.com/touki653/php-ftp-wrapper/issues/4)
 * Fixed bug in `FileDeleter`

1.1.1
-----

 * Voters now extend their base Interfaces
 * Added missing interfaces for `CreatorVoter` and `DeleterVoter`
 * FTPFactory now accepts user classes

1.1.0
-----

 * Added `$connection->open()` to docs
 * Added changelog
 * Moved Manager dependency from `FilesystemFactory` to `FilesystemFactoryInterface`
 * Added `WindowsFilesystemFactory`
 * Introduced creators
 * Introduced deleters
 * Added Getters to FTPFactory

1.0.1
-----

 * Added `getCwd()` command to handle issue [#2](https://github.com/touki653/php-ftp-wrapper/issues/2)

1.0.0
-----

 * Initial release
