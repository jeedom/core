<?php

//
// jQuery File Tree PHP Connector
//
// Version 1.01
//
// Cory S.N. LaViska
// A Beautiful Site (http://abeautifulsite.net/)
// 24 March 2008
//
// History:
//
// 1.01 - updated to work with foreign characters in directory/file names (12 April 2008)
// 1.00 - released (24 March 2008)
//
// Output a list of files for jQuery File Tree
//

require_once dirname(__FILE__) . "/../../core/php/core.inc.php";
include_file('core', 'authentification', 'php');
if (!isConnect('admin')) {
    throw new Exception('401 - Accès non autorisé');
}
$dir = urldecode($_GET['dir']) . '/';
$root = '';
if (file_exists($root . $dir)) {

    $files = scandir($root . $dir);
    natcasesort($files);
    if (count($files) > 2) { /* The 2 accounts for . and .. */
        echo "<ul class=\"jqueryFileTree\" style=\"display: none;\">";
        // All dirs
        foreach ($files as $file) {
            if (file_exists($root . $dir . $file) && $file != '.' && $file != '..' && is_dir($root . $dir . $file)) {
                echo "<li class=\"directory collapsed\"><a href=\"#\" rel=\"" . htmlentities($dir . $file) . "/\">" . htmlentities($file) . "</a></li>";
            }
        }
        // All files
        foreach ($files as $file) {
            if (file_exists($root . $dir . $file) && $file != '.' && $file != '..' && !is_dir($root . $dir . $file)) {
                $ext = preg_replace('/^.*\./', '', $file);
                echo "<li class=\"file ext_$ext\"><a href=\"#\" rel=\"" . htmlentities($dir . $file) . "\">" . htmlentities($file) . "</a></li>";
            }
        }
        echo "</ul>";
    } else {
        echo 'Aucun fichiers trouvés';
    }
}
?>