# Downloading

**Note:** All along this file we are assuming:

 * `$ftp` is an instance of `Touki\FTP\FTP`
 * `Directory` is an alias of `Touki\FTP\Model\Directory`
 * `File` is an alias of `Touki\FTP\Model\File`

**Note:** Only file downloading is currently supported

## Downloading a file

```php
<?php

$file = $ftp->findFileByName('file1.txt');

if (null === $file) {
    return;
}

// To a file
$ftp->download('/path/to/download/file1.txt', $file);

// To an handle
$handle = fopen('/path/to/download/file1.txt', 'w+');
$ftp->download($handle, $file);

?>
```

You can also specify options passed to it

```php
<?php

$options = array(
    FTP::NON_BLOCKING  => false,     // Whether to deal with a callback while downloading
    FTP::NON_BLOCKING_CALLBACK => function() { }, // Callback to execute
    FTP::START_POS     => 0,         // File pointer to start downloading from
    FTP::TRANSFER_MODE => FTP_BINARY // Transfer Mode 
);

$ftp->download('/path/to/download/file1.txt', $file, $options);

?>
```
