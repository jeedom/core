# Installation with composer

Using [composer](http://getcomposer.org) is the easiest way to use this library.  
Just add the following lines in your `composer.json`

```json
{
    "require": {
        "touki/ftp": "1.1.*"
    }
}
```

Then run

```sh
$ composer install touki/ftp
```

# Installation with git

To install this library through git you follow these steps

Clone the repository

```sh
$ pwd
/path/to/app
$ git clone git@github.com:touki653/php-ftp-wrapper.git
Cloning into 'php-ftp-wrapper'...
```

This library uses the [PSR-0] class-autoloading mechanism.  
You need to include [SplClassLoader] or Symfony's [UniversalClassLoader]

```php
<?php
// /path/to/app/autoload.php

/**
 * With SplClassLoader
 */
require __DIR__.'/SplClassLoader.php';

$loader = new SplClassLoader('Touki\\FTP', __DIR__.'/php-ftp-wrapper/src');
$loader->register();

/**
 * With UniversalClassLoader
 */
require __DIR__.'/UniversalClassLoader.php';

$loader = new UniversalClassLoader;
$loader->registerNamespace('Touki\\FTP', __DIR__.'/php-ftp-wrapper/src');
$loader->register();

?>
```

Then in your application, include your autoloader

```php
<?php
// /path/to/app/index.php
require __DIR__.'/autoload.php';

?>
```

Next step: [Setup]

[Setup]: https://github.com/touki653/php-ftp-wrapper/blob/master/docs/setup.md
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
[SplClassLoader]: https://gist.github.com/jwage/221634
[UniversalClassLoader]: https://github.com/symfony/symfony/blob/master/src/Symfony/Component/ClassLoader/UniversalClassLoader.php