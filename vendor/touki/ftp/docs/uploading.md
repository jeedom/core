# Uploading

**Note:** All along this file we are assuming:

 * `$ftp` is an instance of `Touki\FTP\FTP`
 * `Directory` is an alias of `Touki\FTP\Model\Directory`
 * `File` is an alias of `Touki\FTP\Model\File`

**Note:** Only file uploading is currently supported

## Uploading a file

```php
<?php

// From a file
$ftp->upload(new File('newfile.txt'), '/path/to/upload/file1.txt');

// From an handle
$handle = fopen('/path/to/upload/file1.txt', 'w+');
$ftp->upload(new File('newfile.txt'), $handle);

?>
```

You can also specify options passed to it

```php
<?php

$options = array(
    FTP::NON_BLOCKING  => false,     // Whether to deal with a callback while uploading
    FTP::NON_BLOCKING_CALLBACK => function() { }, // Callback to execute
    FTP::START_POS     => 0,         // File pointer to start uploading from
    FTP::TRANSFER_MODE => FTP_BINARY // Transfer Mode
);

$ftp->download('/path/to/upload/file1.txt', $file, $options);

?>
```
