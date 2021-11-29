<?php

require '../../core/php/authentification.php';
if (!isConnect('admin')) {
  throw new Exception('{{401 - Accès non autorisé}}');
}

if (init('type') == '') {
  $rootPaths = [''];
} else if (init('type') == 'widget') {
  $rootPaths = ['data/customTemplates'];
} else if (init('type') == 'custom') {
  $rootPaths = ['desktop/custom', 'mobile/custom'];
} else {
  throw new Exception(__('Invalide type', __FILE__));
}

// // To Enable(true) handling of PostScript files by ImageMagick
// // It is disabled by default as a countermeasure
// // of Ghostscript multiple -dSAFER sandbox bypass vulnerabilities
// // see https://www.kb.cert.org/vuls/id/332928
// define('ELFINDER_IMAGEMAGICK_PS', true);
// ===============================================

// load composer autoload before load elFinder autoload If you need composer
//require './vendor/autoload.php';

// elFinder autoload
require '../../3rdparty/elfinder/php/autoload.php';

/**
 * Simple function to demonstrate how to control file access using "accessControl" callback.
 * This method will disable accessing files/folders starting from '.' (dot)
 *
 * @param  string    $attr    attribute name (read|write|locked|hidden)
 * @param  string    $path    absolute file path
 * @param  string    $data    value of volume option `accessControlData`
 * @param  object    $volume  elFinder volume driver object
 * @param  bool|null $isDir   path is directory (true: directory, false: file, null: unknown)
 * @param  string    $relpath file path relative to volume root directory started with directory separator
 * @return bool|null
 **/

function access($attr, $path, $data, $volume, $isDir, $relpath) {
  $basename = basename($path);
  return $basename[0] === '.'                  // if file/folder begins with '.' (dot)
    && strlen($relpath) !== 1           // but with out volume root
    ? !($attr == 'read' || $attr == 'write') // set read+write to false, other (locked+hidden) set to true
    :  null;                                 // else elFinder decide it itself
}

// Documentation for connector options:
// https://github.com/Studio-42/elFinder/wiki/Connector-configuration-options
$opts = array(
  'roots' => array()
);
foreach ($rootPaths as $rootPath) {
  $root = array(
    //'id'            => '1',
    'driver'        => 'LocalFileSystem',   // driver for accessing file system (REQUIRED)
    'path'          => dirname(__FILE__) . '/../../' . $rootPath . '/',         // path to files (REQUIRED)
    'URL'           => dirname($_SERVER['PHP_SELF']) . '/../../' . $rootPath . '/', // URL to files (REQUIRED)
    'accessControl' => 'access',             // disable and hide dot starting files (OPTIONAL)
    'tmpPath'       => dirname(__FILE__) . '/../../data/editorTemp',
    'utf8fix'       => true,
    'alias'         => $rootPath
  );
  array_push($opts['roots'], $root);
}


// run elFinder
$connector = new elFinderConnector(new elFinder($opts));
$connector->run();
