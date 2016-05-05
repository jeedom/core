# Deleting

**Note:** All along this file we are assuming:

 * `$ftp` is an instance of `Touki\FTP\FTP`
 * `Directory` is an alias of `Touki\FTP\Model\Directory`
 * `File` is an alias of `Touki\FTP\Model\File`

## Deleting directories

This sample will delete all files and directories given in the remote Directory

```php
<?php

$dir = $ftp->findDirectoryByName('/folder');

$ftp->delete($dir);

?>
```

You can also specify options passed to it.

```php
<?php

$options = array(
    FTP::RECURSIVE => true
);

$ftp->delete(new Directory($path), $options);

?>
```

## Deleting files

This will delete a given remote file

```php
<?php

$filename = '/remote/path/to/file';
$file = $ftp->findFileByName($filename);

$ftp->delete($file);

?>
```
