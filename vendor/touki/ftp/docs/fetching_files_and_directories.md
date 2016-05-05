# Fetching files and directories

**Note:** All along this file we are assuming:

 * `$ftp` is an instance of `Touki\FTP\FTP`
 * `Directory` is an alias of `Touki\FTP\Model\Directory`
 * `File` is an alias of `Touki\FTP\Model\File`

## Find filesystem existance

```php
<?php

$ftp->fileExists(new File('/foo'));
$ftp->fileExists(new File('/non/existant/file'))
$ftp->directoryExists(new Directory('/folder'))
$ftp->directoryExists(new Directory('/bar'))

?>
```

## Fetching all filesystems

```php
<?php

$list = $ftp->findFilesystems(new Directory("/"));
var_dump($list);

?>
```

Will output:

```
array(3) {
  [0] => object (Touki\FTP\Model\File) {
    protected $realpath => string(10) "/file1.txt"
    protected $ownerPermissions => object (Touki\FTP\Model\Permissions) {
      protected $flags => int(6)
    }
    protected $groupPermissions => object (Touki\FTP\Model\Permissions) {
      protected $flags => int(6)
    }
    protected $guestPermissions => object (Touki\FTP\Model\Permissions) {
      protected $flags => int(4)
    }
    protected $owner => string(9) "ftp-tests"
    protected $group => string(9) "guillaume"
    protected $size => string(1) "5"
    protected $mtime => object (DateTime) {
      public $date => string(19) "2013-07-15 09:17:00"
      public $timezone_type => int(3)
      public $timezone => string(13) "Europe/Berlin"
    }
  }
  [1] => object (Touki\FTP\Model\File) {
    protected $realpath => string(10) "/file2.txt"
    protected $ownerPermissions => object (Touki\FTP\Model\Permissions) {
      protected $flags => int(6)
    }
    protected $groupPermissions => object (Touki\FTP\Model\Permissions) {
      protected $flags => int(6)
    }
    protected $guestPermissions => object (Touki\FTP\Model\Permissions) {
      protected $flags => int(4)
    }
    protected $owner => string(9) "ftp-tests"
    protected $group => string(9) "guillaume"
    protected $size => string(1) "5"
    protected $mtime => object (DateTime) {
      public $date => string(19) "2013-07-15 09:11:00"
      public $timezone_type => int(3)
      public $timezone => string(13) "Europe/Berlin"
    }
  }
  [2] => object (Touki\FTP\Model\Directory) {
    protected $realpath => string(7) "/folder"
    protected $ownerPermissions => object (Touki\FTP\Model\Permissions) {
      protected $flags => int(7)
    }
    protected $groupPermissions => object (Touki\FTP\Model\Permissions) {
      protected $flags => int(7)
    }
    protected $guestPermissions => object (Touki\FTP\Model\Permissions) {
      protected $flags => int(5)
    }
    protected $owner => string(9) "ftp-tests"
    protected $group => string(9) "guillaume"
    protected $size => string(4) "4096"
    protected $mtime => object (DateTime) {
      public $date => string(19) "2013-07-17 13:18:00"
      public $timezone_type => int(3)
      public $timezone => string(13) "Europe/Berlin"
    }
  }
}

?>
```

## Fetching a single file

```php
<?php

$file  = $ftp->findFileByName('file1.txt');
$file2 = $ftp->findFileByName('nonexistant');
$file3 = $ftp->findFileByName('file3.txt', $inDirectory = new Directory('folder'));

var_dump($file);
var_dump($file2);
var_dump($file3);

?>
```

Will output

```
object (Touki\FTP\Model\File) {
  protected $realpath => string(10) "/file1.txt"
  protected $ownerPermissions => object (Touki\FTP\Model\Permissions) {
    protected $flags => int(6)
  }
  protected $groupPermissions => object (Touki\FTP\Model\Permissions) {
    protected $flags => int(6)
  }
  protected $guestPermissions => object (Touki\FTP\Model\Permissions) {
    protected $flags => int(4)
  }
  protected $owner => string(9) "ftp-tests"
  protected $group => string(9) "guillaume"
  protected $size => string(1) "5"
  protected $mtime => object (DateTime) {
    public $date => string(19) "2013-07-15 09:17:00"
    public $timezone_type => int(3)
    public $timezone => string(13) "Europe/Berlin"
  }
}

NULL

object (Touki\FTP\Model\File) {
  protected $realpath => string(17) "/folder/file3.txt"
  protected $ownerPermissions => object (Touki\FTP\Model\Permissions) {
    protected $flags => int(6)
  }
  protected $groupPermissions => object (Touki\FTP\Model\Permissions) {
    protected $flags => int(6)
  }
  protected $guestPermissions => object (Touki\FTP\Model\Permissions) {
    protected $flags => int(4)
  }
  protected $owner => string(9) "ftp-tests"
  protected $group => string(9) "guillaume"
  protected $size => string(1) "5"
  protected $mtime => object (DateTime) {
    public $date => string(19) "2013-07-15 09:11:00"
    public $timezone_type => int(3)
    public $timezone => string(13) "Europe/Berlin"
  }
}
```

## Fetching a directory

```php
<?php

$dir = $ftp->findDirectoryByName('/folder');
var_dump($dir);
$dir = $ftp->findDirectoryByName('subfolder', $inDirectory = new Directory('folder'));

?>
```

Will output

```
object (Touki\FTP\Model\Directory) {
  protected $realpath => string(7) "/folder"
  protected $ownerPermissions => object (Touki\FTP\Model\Permissions) {
    protected $flags => int(7)
  }
  protected $groupPermissions => object (Touki\FTP\Model\Permissions) {
    protected $flags => int(7)
  }
  protected $guestPermissions => object (Touki\FTP\Model\Permissions) {
    protected $flags => int(5)
  }
  protected $owner => string(9) "ftp-tests"
  protected $group => string(9) "guillaume"
  protected $size => string(4) "4096"
  protected $mtime => object (DateTime) {
    public $date => string(19) "2013-07-17 13:18:00"
    public $timezone_type => int(3)
    public $timezone => string(13) "Europe/Berlin"
  }
}
```

## Fetching the current working directory

```php
<?php
$dir = $ftp->getCwd();
?>
```
