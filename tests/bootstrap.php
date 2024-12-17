<?php

echo "
 ____  _   _ ____  _   _ _   _ ___ _____       _               _                   _____         _   ____        _ _       
|  _ \| | | |  _ \| | | | \ | |_ _|_   _|     | | ___  ___  __| | ___  _ __ ___   |_   _|__  ___| |_/ ___| _   _(_) |_ ___ 
| |_) | |_| | |_) | | | |  \| || |  | |    _  | |/ _ \/ _ \/ _` |/ _ \| '_ ` _ \    | |/ _ \/ __| __\___ \| | | | | __/ _ \
|  __/|  _  |  __/| |_| | |\  || |  | |   | |_| |  __/  __/ (_| | (_) | | | | | |   | |  __/\__ \ |_ ___) | |_| | | ||  __/
|_|   |_| |_|_|    \___/|_| \_|___| |_|    \___/ \___|\___|\__,_|\___/|_| |_| |_|   |_|\___||___/\__|____/ \__,_|_|\__\___|

";

$filename = str_replace("..", substr(__DIR__, 0, strrpos(__DIR__, "/")), "../core/config/common.config.php");
if( file_exists($filename)) {
	echo "Integration tests avec $filename : include Jeedom Core & autoloader...\n";
	require_once __DIR__ . "/../core/php/core.inc.php";
	
} else {

	// default useless configuration for standalone phpunits
	file_put_contents($filename, "<?php
define('DEBUG', 1);

global \$CONFIG;
\$CONFIG = array(
	'db' => array(
		'host' => 'localhost',
		'port' => '3306',
		'dbname' => 'jeedom',
		'username' => 'jeedom',
		'password' => 'dummy',
	),
);
");

	// add jeedom custom autoloader
	require_once __DIR__ . '/../core/php/utils.inc.php';

	function jeedomAutoload($_classname) {
		/* core class always in /core/class : */
		$path = __DIR__ . "/../core/class/$_classname.class.php";
		if (file_exists($path)) {
			include_file('core', $_classname, 'class');
		} else if (substr($_classname, 0, 4) === 'com_') {
			/* class com_$1 in /core/com/$1.com.php */
			include_file('core', substr($_classname, 4), 'com');
		} else if (substr($_classname, 0, 5) === 'repo_') {
			/* class repo_$1 in /core/repo/$1.repo.php */
			include_file('core', substr($_classname, 5), 'repo');
		}
	}

	spl_autoload_register('jeedomAutoload', true, true);

	// mock DB class
	class DB {
		public const FETCH_TYPE_ROW = 0;
		public const FETCH_TYPE_ALL = 1;
		public static function Prepare() {}
		public static function buildField(){}
	}
	function __() {}

	echo "Standalone phpunits: genetated default $filename\n";
}

echo "End of Jeedom Core Includes\n";