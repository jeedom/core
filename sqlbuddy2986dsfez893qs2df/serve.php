<?php
/*

SQL Buddy - Web based MySQL administration
http://www.sqlbuddy.com/

serve.php
- serves files in a compressed manner

MIT license

2008 Calvin Lough <http://calv.in>

*/

include "config.php";

function compressCSS($input) {
	// remove comments
	$input = preg_replace("/\/\*.*\*\//Us", "", $input);
	
	// remove unnecessary characters
	$input = str_replace(":0px", ":0", $input);
	$input = str_replace(":0em", ":0", $input);
	$input = str_replace(" 0px", " 0", $input);
	$input = str_replace(" 0em", " 0", $input);
	$input = str_replace(";}", "}", $input);
	
	// remove spaces, etc
	$input = preg_replace('/\s\s+/', ' ', $input);
	$input = str_replace(" {", "{", $input);
	$input = str_replace("{ ", "{", $input);
	$input = str_replace("\n{", "{", $input);
	$input = str_replace("{\n", "{", $input);
	$input = str_replace(" }", "}", $input);
	$input = str_replace("} ", "}", $input);
	$input = str_replace(": ", ":", $input);
	$input = str_replace(" :", ":", $input);
	$input = str_replace(";\n", ";", $input);
	$input = str_replace(" ;", ";", $input);
	$input = str_replace("; ", ";", $input);
	$input = str_replace(", ", ",", $input);
	
	return trim($input);
}

function compressJS($input) {
	
	// remove comments
	$input = preg_replace("/\/\/.*\n/Us", "", $input);
	$input = preg_replace("/\/\*.*\*\//Us", "", $input);
	
	// remove spaces, etc
	$input = preg_replace("/\t/", "", $input);
	$input = preg_replace("/\n\n+/m", "\n", $input);
	$input = str_replace(";\n", ";", $input);
	$input = str_replace(" = ", "=", $input);
	$input = str_replace(" == ", "==", $input);
	$input = str_replace(" || ", "||", $input);
	$input = str_replace(" && ", "&&", $input);
	$input = str_replace(")\n{", "){", $input);
	$input = str_replace("if (", "if(", $input);
	
	return trim($input);
}

if (isset($_GET['file'])) {
	
	$filename = $_GET['file'];
	
	if (!(strpos($filename, "css/") === 0 || strpos($filename, "themes/") === 0 || strpos($filename, "js/") === 0))
		exit;
	
	if (strpos($filename, "..") !== false)
		exit;
	
	if (file_exists($filename)) {
		if (extension_loaded('zlib') && ((isset($sbconfig['EnableGzip']) && $sbconfig['EnableGzip'] == true) || !isset($sbconfig['EnableGzip']))) {
			ob_start("ob_gzhandler");
		} else {
			ob_start();
		}
		
		$last_modified_time = filemtime($filename);
		$etag = md5_file($filename);
		
		header("Last-Modified: " . gmdate("D, d M Y H:i:s", $last_modified_time) . " GMT");
		header("Expires: " . gmdate("D, d M Y H:i:s", time()+24*60*60*60) . " GMT");
		header("Etag: $etag");
		
		if ((array_key_exists('HTTP_IF_MODIFIED_SINCE', $_SERVER) && @strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $last_modified_time) || (array_key_exists('HTTP_IF_NONE_MATCH', $_SERVER) && trim($_SERVER['HTTP_IF_NONE_MATCH']) == $etag)) {
			header("HTTP/1.1 304 Not Modified");
			exit;
		}
		
		$contents = file_get_contents($filename);
		
		if (substr($filename, -4) == ".css") {
			header("Content-Type: text/css; charset=utf-8");
			$contents = compressCSS($contents);
		} else if (substr($filename, -3) == ".js" && strpos($filename, "mootools") === false) {
			header("Content-Type: application/x-javascript; charset=utf-8");
			$contents = compressJS($contents);
		} else if (substr($filename, -3) == ".js") {
			header("Content-Type: application/x-javascript; charset=utf-8");
		}
		
		echo $contents;
		
		ob_end_flush();
	} else {
		echo "File doesn't exist!";
	}
}

?>