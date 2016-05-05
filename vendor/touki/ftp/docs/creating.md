# Creating

**Note:** All along this file we are assuming:

 * `$ftp` is an instance of `Touki\FTP\FTP`
 * `Directory` is an alias of `Touki\FTP\Model\Directory`
 * `File` is an alias of `Touki\FTP\Model\File`

## Creating directories

```php
<?php

$path = '/remote/path/to/create';

$ftp->create(new Directory($path));

?>
```

Will create all the non existing directories following the path.

You can also specify options passed to it.

```php
<?php

$options = array(
    FTP::RECURSIVE => true
);

$ftp->create(new Directory($path), $options);

?>
```

