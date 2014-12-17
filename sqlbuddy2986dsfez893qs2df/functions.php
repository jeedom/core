<?php
/*

SQL Buddy - Web based MySQL administration
http://www.sqlbuddy.com/

functions.php
- gets the page setup with the variables it needs

MIT license

2008 Calvin Lough <http://calv.in>

*/

error_reporting(E_ALL);

if (function_exists('date_default_timezone_set'))
	date_default_timezone_set('Greenwich');

if (!session_id())
	session_start();

define("MAIN_DIR", dirname(__FILE__) . "/");
define("INCLUDES_DIR", MAIN_DIR . "includes/");

include MAIN_DIR . "config.php";
include INCLUDES_DIR . "types.php";
include INCLUDES_DIR . "class/GetTextReader.php";

if (version_compare(PHP_VERSION, "5.0.0", "<"))
	include INCLUDES_DIR . "class/Sql-php4.php";
else
	include INCLUDES_DIR . "class/Sql.php";

define("VERSION_NUMBER", "1.3.3");
define("PREVIEW_CHAR_SIZE", 75);

$adapterList[] = "mysql";

if (function_exists("sqlite_open") || (class_exists("PDO") && in_array("sqlite", PDO::getAvailableDrivers()))) {
	$adapterList[] = "sqlite";
}

$cookieLength = time() + (60*24*60*60);

$langList['id_ID'] = "Bahasa Indonesia";
$langList['ms_ID'] = "Bahasa Melayu";
$langList['ca_AD'] = "Català";
$langList['cs_CZ'] = "Čeština";
$langList['sr_RS'] = "Српски ћирилица";
$langList['da_DK'] = "Dansk";
$langList['de_DE'] = "Deutsch";
$langList['et_EE'] = "Eesti keel";
$langList['en_US'] = "English";
$langList['es_ES'] = "Español";
$langList['es_AR'] = "Español (Argentina)";
$langList['eo_EO'] = "Esperanto";
$langList['fr_FR'] = "Français";
$langList['gl_ES'] = "Galego";
$langList['hr_HR'] = "Hrvatski";
$langList['it_IT'] = "Italiano";
$langList['ko_KR'] = "한국어";
$langList['lo_LA'] = "Lao";
$langList['lv_LV'] = "Latviešu";
$langList['hu_HU'] = "Magyar";
$langList['nl_NL'] = "Nederlands";
$langList['no_NO'] = "Norsk";
$langList['pl_PL'] = "Polski";
$langList['pt_BR'] = "Português (Brasil)";
$langList['pt_PT'] = "Português (Portugal)";
$langList['ru_RU'] = "Русский";
$langList['ro_RO'] = "Română";
$langList['sq_AL'] = "Shqip";
$langList['sk_SK'] = "Slovenčina";
$langList['sl_SL'] = "Slovenščina";
$langList['sp_RS'] = "Srpski";
$langList['fi_FI'] = "Suomi";
$langList['sv_SE'] = "Svenska";
$langList['tl_PH'] = "Tagalog";
$langList['vi_VN'] = "Tiếng Việt";
$langList['tr_TR'] = "Türkçe";
$langList['uk_UA'] = "Українська";
$langList['ar_DZ'] = "العربية";
$langList['fa_IR'] = "فارسی";
$langList['he_IL'] = "עִבְרִית";
$langList['bg_BG'] = "български език";
$langList['bn_BD'] = "বাংলা";
$langList['el_GR'] = "ελληνικά";
$langList['th_TH'] = "ภาษาไทย";
$langList['zh_CN'] = "中文 (简体)";
$langList['zh_TW'] = "中文 (繁體)";
$langList['ja_JP'] = "日本語";

if (isset($_COOKIE['sb_lang']) && array_key_exists($_COOKIE['sb_lang'], $langList)) {
	$lang = preg_replace("/[^a-z0-9_]/i", "", $_COOKIE['sb_lang']);
} else {
	$lang = "en_US";
}

if ($lang != "en_US") {
	// extend the cookie length
	setcookie("sb_lang", $lang, $cookieLength);
} else if (isset($_COOKIE['sb_lang'])) {
	// cookie not needed for en_US
	setcookie("sb_lang", "", time() - 10000);
}

$themeList["classic"] = "Classic";
$themeList["bittersweet"] = "Bittersweet";

if (isset($_COOKIE['sb_theme'])) {
	$currentTheme = preg_replace("/[^a-z0-9_]/i", "", $_COOKIE['sb_theme']);

	if (array_key_exists($currentTheme, $themeList)) {
		$theme = $currentTheme;

		// extend the cookie length
		setcookie("sb_theme", $theme, $cookieLength);
	} else {
		$theme = "bittersweet";
		setcookie("sb_theme", "", time() - 10000);
	}
} else {
	$theme = "bittersweet";
}

$gt = new GetTextReader($lang . ".pot");

if (isset($_SESSION['SB_LOGIN_STRING'])) {
	$user = (isset($_SESSION['SB_LOGIN_USER'])) ? $_SESSION['SB_LOGIN_USER'] : "";
	$pass = (isset($_SESSION['SB_LOGIN_PASS'])) ? $_SESSION['SB_LOGIN_PASS'] : "";
	$conn = new SQL($_SESSION['SB_LOGIN_STRING'], $user, $pass);
}

// unique identifer for this session, to validate ajax requests.
// document root is included because it is likely a difficult value
// for potential attackers to guess
$requestKey = substr(md5(session_id() . $_SERVER["DOCUMENT_ROOT"]), 0, 16);

if (isset($conn) && $conn->isConnected()) {
	if (isset($_GET['db']))
		$db = $conn->escapeString($_GET['db']);

	if (isset($_GET['table']))
		$table = $conn->escapeString($_GET['table']);
	
	if ($conn->hasCharsetSupport()) {
		
		$charsetSql = $conn->listCharset();
		if ($conn->isResultSet($charsetSql)) {
			while ($charsetRow = $conn->fetchAssoc($charsetSql)) {
				$charsetList[] = $charsetRow['Charset'];
			}
		}
	
		$collationSql = $conn->listCollation();
		if ($conn->isResultSet($collationSql)) {
			while ($collationRow = $conn->fetchAssoc($collationSql)) {
				$collationList[$collationRow['Collation']] = $collationRow['Charset'];
			}
		}
	}
}

// undo magic quotes, if necessary
if (get_magic_quotes_gpc()) {
	$_GET = stripslashesFromArray($_GET);
	$_POST = stripslashesFromArray($_POST);
	$_COOKIE = stripslashesFromArray($_COOKIE);
	$_REQUEST = stripslashesFromArray($_REQUEST);
}

function stripslashesFromArray($value) {
    $value = is_array($value) ?
                array_map('stripslashesFromArray', $value) :
                stripslashes($value);

    return $value;
}

function loginCheck($validateReq = true) {
	if (!isset($_SESSION['SB_LOGIN'])){
		if (isset($_GET['ajaxRequest']))
			redirect("login.php?timeout=1");
		else
			redirect("login.php");
		exit;
	}
	if ($validateReq) {
		if (!validateRequest()) {
			exit;
		}
	}

	startOutput();
}

function redirect($url) {
	if (isset($_GET['ajaxRequest']) || headers_sent()) {
		global $requestKey;
		?>
		<script type="text/javascript" authkey="<?php echo $_GET['requestKey']; ?>">

		document.location = "<?php echo $url; ?>" + window.location.hash;

		</script>
		<?php
	} else {
		header("Location: $url");
	}
	exit;
}

function validateRequest() {
	global $requestKey;
	if (isset($_GET['requestKey']) && $_GET['requestKey'] != $requestKey) {
		return false;
	}
	return true;
}

function startOutput() {
	global $sbconfig;
	
	if (!headers_sent()) {
		if (extension_loaded("zlib") && ((isset($sbconfig['EnableGzip']) && $sbconfig['EnableGzip'] == true) || !isset($sbconfig['EnableGzip'])) && !ini_get("zlib.output_compression") && ini_get("output_handler") != "ob_gzhandler") {
			ob_start("ob_gzhandler");
		} else {
			ob_start();
		}
		
		register_shutdown_function("finishOutput");
	}
}

function finishOutput() {	
	global $conn;
	
	ob_end_flush();
	
	if (isset($conn) && $conn->isConnected()) {
		$conn->disconnect();
		unset($conn);
	}
}

function outputPage() {

global $requestKey;
global $sbconfig;
global $conn;
global $lang;

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/REC-html40/strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" version="-//W3C//DTD XHTML 1.1//EN" xml:lang="en">
	<head>
		<title>SQL Buddy</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<link type="text/css" rel="stylesheet" href="<?php echo smartCaching("css/common.css"); ?>" media="all" />
		<link type="text/css" rel="stylesheet" href="<?php echo smartCaching("css/navigation.css"); ?>" media="all" />
		<link type="text/css" rel="stylesheet" href="<?php echo smartCaching("css/print.css"); ?>" media="print" />
		<link type="text/css" rel="stylesheet" href="<?php echo themeFile("css/main.css"); ?>" media="all" />
		<!--[if lte IE 7]>
    		<link type="text/css" rel="stylesheet" href="<?php echo themeFile("css/ie.css"); ?>" media="all" />
		<![endif]-->
		<script type="text/javascript" src="<?php echo smartCaching("js/mootools-1.2-core.js"); ?>"></script>
		<script type="text/javascript" src="<?php echo smartCaching("js/helpers.js"); ?>"></script>
		<script type="text/javascript" src="<?php echo smartCaching("js/core.js"); ?>"></script>
		<script type="text/javascript" src="<?php echo smartCaching("js/movement.js"); ?>"></script>
	</head>
	<body>
	<div id="container">
	<div id="header">
		<div id="headerlogo">
		<a href="#page=home" onclick="sideMainClick('home.php', 0); return false;"><img src="images/logo.png" /></a>
		</div>
		<div id="toptabs"><ul></ul></div>
		<div id="headerinfo">
		<span id="load" style="display: none"><?php echo __("Loading..."); ?></span>
		<?php

		// if set to auto login, providing a link to logout wouldnt be much good
		if (!((isset($sbconfig['DefaultPass']) && $conn->getAdapter() == "mysql") || (isset($sbconfig['DefaultDatabase']) && $conn->getAdapter() == "sqlite")))
			echo '<a href="logout.php">' . __("Logout") . '</a>';

		?>
		</div>
		<div class="clearer"></div>
	</div>

	<div id="bottom">

	<div id="leftside">
		<div id="sidemenu">
		<div class="dblist"><ul>
		<?php
		
		if ($conn->getAdapter() != "sqlite") {
		
		?>
			<li id="sidehome"><a href="#page=home" onclick="sideMainClick('home.php', 0); return false;"><div class="menuicon">&gt;</div><div class="menutext"><?php echo __("Home"); ?></div></a></li>
			<li id="sideusers"><a href="#page=users&topTab=1" onclick="sideMainClick('users.php', 1); return false;"><div class="menuicon">&gt;</div><div class="menutext"><?php echo __("Users"); ?></div></a></li>
			<li id="sidequery"><a href="#page=query&topTab=2" onclick="sideMainClick('query.php', 2); return false;"><div class="menuicon">&gt;</div><div class="menutext"><?php echo __("Query"); ?></div></a></li>
			<li id="sideimport"><a href="#page=import&topTab=3" onclick="sideMainClick('import.php', 3); return false;"><div class="menuicon">&gt;</div><div class="menutext"><?php echo __("Import"); ?></div></a></li>
			<li id="sideexport"><a href="#page=export&topTab=4" onclick="sideMainClick('export.php', 4); return false;"><div class="menuicon">&gt;</div><div class="menutext"><?php echo __("Export"); ?></div></a></li>
		<?php
		
		} else {
		
		?>
			<li id="sidehome"><a href="#page=home" onclick="sideMainClick('home.php', 0); return false;"><div class="menuicon">&gt;</div><div class="menutext"><?php echo __("Home"); ?></div></a></li>
			<li id="sidequery"><a href="#page=query&topTab=1" onclick="sideMainClick('query.php', 1); return false;"><div class="menuicon">&gt;</div><div class="menutext"><?php echo __("Query"); ?></div></a></li>
			<li id="sideimport"><a href="#page=import&topTab=2" onclick="sideMainClick('import.php', 2); return false;"><div class="menuicon">&gt;</div><div class="menutext"><?php echo __("Import"); ?></div></a></li>
			<li id="sideexport"><a href="#page=export&topTab=3" onclick="sideMainClick('export.php', 3); return false;"><div class="menuicon">&gt;</div><div class="menutext"><?php echo __("Export"); ?></div></a></li>
		<?php
		
		}
		
		?>
		</ul></div>
		
		<div class="dblistheader"><?php echo __("Databases"); ?></div>
		<div class="dblist" id="databaselist"><ul></ul></div>
		</div>
	</div>
	<div id="rightside">

		<div id="content">
			<div class="corners"><div class="tl"></div><div class="tr"></div></div>
			<div id="innercontent"></div>
			<div class="corners"><div class="bl"></div><div class="br"></div></div>
		</div>

		</div>

	</div>
	</div>

	</body>
	<script type="text/javascript">
	<!--
	
	<?php
	
	if ($conn->getAdapter() == "sqlite") {
		echo "var showUsersMenu = false;\n";
	} else {
		echo "var showUsersMenu = true;\n";
	}
	
	echo "var adapter = \"" . $conn->getAdapter() . "\";\n";
	
	if (isset($requestKey)) {
		echo 'var requestKey = "' . $requestKey . '";';
		echo "\n";
	}
	
	// javascript translation strings
	echo "\t\tvar getTextArr = {";
	
	if ($lang != "en_US") {
		
		echo '"Home":"' . __("Home") . '", ';
		echo '"Users":"' . __("Users") . '", ';
		echo '"Query":"' . __("Query") . '", ';
		echo '"Import":"' . __("Import") . '", ';
		echo '"Export":"' . __("Export") . '", ';
	
		echo '"Overview":"' . __("Overview") . '", ';
	
		echo '"Browse":"' . __("Browse") . '", ';
		echo '"Structure":"' . __("Structure") . '", ';
		echo '"Insert":"' . __("Insert") . '", ';
	
		echo '"Your changes were saved to the database.":"' . __("Your changes were saved to the database.") . '", ';
	
		echo '"delete this row":"' . __("delete this row") . '", ';
		echo '"delete these rows":"' . __("delete these rows") . '", ';
		echo '"empty this table":"' . __("empty this table") . '", ';
		echo '"empty these tables":"' . __("empty these tables") . '", ';
		echo '"drop this table":"' . __("drop this table") . '", ';
		echo '"drop these tables":"' . __("drop these tables") . '", ';
		echo '"delete this column":"' . __("delete this column") . '", ';
		echo '"delete these columns":"' . __("delete these columns") . '", ';
		echo '"delete this index":"' . __("delete this index") . '", ';
		echo '"delete these indexes":"' . __("delete these indexes") . '", ';
		echo '"delete this user":"' . __("delete this user") . '", ';
		echo '"delete these users":"' . __("delete these users") . '", ';
		echo '"Are you sure you want to":"' . __("Are you sure you want to") . '", ';
	
		echo '"The following query will be run:":"' . __("The following query will be run:") . '", ';
		echo '"The following queries will be run:":"' . __("The following queries will be run:") . '", ';
	
		echo '"Confirm":"' . __("Confirm") . '", ';
		echo '"Are you sure you want to empty the \'%s\' table? This will delete all the data inside of it. The following query will be run:":"' . __("Are you sure you want to empty the '%s' table? This will delete all the data inside of it. The following query will be run:") . '", ';
		echo '"Are you sure you want to drop the \'%s\' table? This will delete the table and all data inside of it. The following query will be run:":"' . __("Are you sure you want to drop the '%s' table? This will delete the table and all data inside of it. The following query will be run:") . '", ';
		echo '"Are you sure you want to drop the database \'%s\'? This will delete the database, the tables inside the database, and all data inside of the tables. The following query will be run:":"' . __("Are you sure you want to drop the database '%s'? This will delete the database, the tables inside the database, and all data inside of the tables. The following query will be run:") . '", ';
	
		echo '"Successfully saved changes":"' . __("Successfully saved changes") . '", ';
	
		echo '"New field":"' . __("New field") . '", ';
	
		echo '"Full Text":"' . __("Full Text") . '", ';
	
		echo '"Loading...":"' . __("Loading...") . '", ';
		echo '"Redirecting...":"' . __("Redirecting...") . '", ';
	
		echo '"Okay":"' . __("Okay") . '", ';
		echo '"Cancel":"' . __("Cancel") . '", ';
	
		echo '"Error":"' . __("Error") . '", ';
		echo '"There was an error receiving data from the server":"' . __("There was an error receiving data from the server") . '"';
		
	}
	
	echo '};';

	echo "\n";


	echo 'var menujson = {"menu": [';
	echo $conn->getMetadata();
	echo ']};';
	
	?>
	//-->
	</script>
</html>
<?php
}

function requireDatabaseAndTableBeDefined() {
	global $db, $table;

	if (!isset($db)) {
		?>

		<div class="errorpage">
		<h4><?php echo __("Oops"); ?></h4>
		<p><?php echo __("For some reason, the database parameter was not included with your request."); ?></p>
		</div>

		<?php
		exit;
	}

	if (!isset($table)) {
		?>

		<div class="errorpage">
		<h4><?php echo __("Oops"); ?></h4>
		<p><?php echo __("For some reason, the table parameter was not included with your request."); ?></p>
		</div>

		<?php
		exit;
	}

}

function formatForOutput($text) {
	$text = nl2br(htmlentities($text, ENT_QUOTES, 'UTF-8'));
	if (utf8_strlen($text) > PREVIEW_CHAR_SIZE) {
		$text = utf8_substr($text, 0, PREVIEW_CHAR_SIZE) . " <span class=\"toBeContinued\">[...]</span>";
	}
	return $text;
}

function formatDataForCSV($text) {
	$text = str_replace('"', '""', $text);
	return $text;
}

function splitQueryText($query) {
	// the regex needs a trailing semicolon
	$query = trim($query);

	if (substr($query, -1) != ";")
		$query .= ";";

	// i spent 3 days figuring out this line
	preg_match_all("/(?>[^;']|(''|(?>'([^']|\\')*[^\\\]')))+;/ixU", $query, $matches, PREG_SET_ORDER);

	$querySplit = "";

	foreach ($matches as $match) {
		// get rid of the trailing semicolon
		$querySplit[] = substr($match[0], 0, -1);
	}

	return $querySplit;
}

function memoryFormat($bytes) {
	if ($bytes < 1024)
		$dataString = $bytes . " B";
	else if ($bytes < (1024 * 1024))
		$dataString = round($bytes / 1024) . " KB";
	else if ($bytes < (1024 * 1024 * 1024))
		$dataString = round($bytes / (1024 * 1024)) . " MB";
	else
		$dataString = round($bytes / (1024 * 1024 * 1024)) . " GB";

	return $dataString;
}

function themeFile($filename) {
	global $theme;
	return smartCaching("themes/" . $theme . "/" . $filename);
}

function smartCaching($filename) {
	return $filename . "?ver=" . str_replace(".", "_", VERSION_NUMBER);
}

function __($t) {
	global $gt;
	return $gt->getTranslation($t);
}

function __p($singular, $plural, $count) {
	global $gt;
	if ($count == 1) {
		return $gt->getTranslation($singular);
	} else {
		return $gt->getTranslation($plural);
	}
}

function utf8_substr($str, $from, $len) {
# utf8 substr
# www.yeap.lv
  return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$from.'}'.
                       '((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$len.'}).*#s',
                       '$1',$str);
}

function utf8_strlen($str) {
    $i = 0;
    $count = 0;
    $len = strlen ($str);
    while ($i < $len) {
    $chr = ord ($str[$i]);
    $count++;
    $i++;
    if ($i >= $len)
        break;

    if ($chr & 0x80) {
        $chr <<= 1;
        while ($chr & 0x80) {
        $i++;
        $chr <<= 1;
        }
    }
    }
    return $count;
}

function microtime_float() {
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

?>