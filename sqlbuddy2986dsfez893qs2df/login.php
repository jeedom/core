<?php
/*

SQL Buddy - Web based MySQL administration
http://www.sqlbuddy.com/

login.php
- login to sql buddy

MIT license

2008 Calvin Lough <http://calv.in>

*/

include "functions.php";

$adapter = (isset($sbconfig['DefaultAdapter'])) ? $sbconfig['DefaultAdapter'] : "mysql";
$host = (isset($sbconfig['DefaultHost'])) ? $sbconfig['DefaultHost'] : "localhost";
$user = (isset($sbconfig['DefaultUser'])) ? $sbconfig['DefaultUser'] : "root";
$pass = (isset($sbconfig['DefaultPass'])) ? $sbconfig['DefaultPass'] : "";	

// SQLite only
$database = (isset($sbconfig['DefaultDatabase'])) ? $sbconfig['DefaultDatabase'] : "";	

if ($_POST) {
	if (isset($_POST['ADAPTER']))
		$adapter = $_POST['ADAPTER'];
	
	if (isset($_POST['HOST']))
		$host = $_POST['HOST'];
		
	if (isset($_POST['USER']))
		$user = $_POST['USER'];
		
	if (isset($_POST['PASS']))
		$pass = $_POST['PASS'];
	
	if (isset($_POST['DATABASE']))
		$database = $_POST['DATABASE'];
}

if (!in_array($adapter, $adapterList)) {
	$adapter = "mysql";
}

if (($adapter != "sqlite" && $host && $user && ($pass || $_POST)) || ($adapter == "sqlite" && $database)) {
	
	if ($adapter == "sqlite") {
		$connString = "sqlite:database=$database";
		$connCheck = new SQL($connString);
		$user = "";
		$pass = "";
	} else {
		$connString = "$adapter:host=$host";
		$connCheck = new SQL($connString, $user, $pass);
	}
	
	if ($connCheck->isConnected()) {
		$_SESSION['SB_LOGIN'] = true;
		$_SESSION['SB_LOGIN_STRING'] = $connString;
		$_SESSION['SB_LOGIN_USER'] = $user;
		$_SESSION['SB_LOGIN_PASS'] = $pass;
		
		$path = $_SERVER["SCRIPT_NAME"];
		$pathSplit = explode("/", $path);
		
		$redirect = "";
		
		for ($i=0; $i<count($pathSplit)-1; $i++) {
			if (trim($pathSplit[$i]) != "")
				$redirect .= "/" . $pathSplit[$i];
		}
		
		if (array_key_exists("HTTPS", $_SERVER) && $_SERVER['HTTPS'] == "on") {
			$protocol = "https://";
		} else {
			$protocol = "http://";
		}
		
		$redirect = $protocol . $_SERVER["HTTP_HOST"] . $redirect . "/";
		
		redirect($redirect);
		exit;
	} else {
		$error = __("There was a problem logging you in.");
	}
}

startOutput();

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/REC-html40/strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" version="-//W3C//DTD XHTML 1.1//EN" xml:lang="en">
	<head>
		<title>SQL Buddy</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<link type="text/css" rel="stylesheet" href="<?php echo smartCaching("css/common.css"); ?>" />
		<link type="text/css" rel="stylesheet" href="<?php echo smartCaching("css/navigation.css"); ?>" />
		<link type="text/css" rel="stylesheet" href="<?php echo smartCaching("css/print.css"); ?>" media="print" />
		<link type="text/css" rel="stylesheet" href="<?php echo themeFile("css/main.css"); ?>" />
		<!--[if lte IE 7]>
    		<link type="text/css" rel="stylesheet" href="<?php echo themeFile("css/ie.css"); ?>" />
		<![endif]-->
		<script type="text/javascript" src="<?php echo smartCaching("js/mootools-1.2-core.js"); ?>"></script>
		<script type="text/javascript" src="<?php echo smartCaching("js/helpers.js"); ?>"></script>
		<script type="text/javascript" src="<?php echo smartCaching("js/movement.js"); ?>"></script>
	</head>
	<body style="background: none">
	<div id="container">
	<div id="loginform">
		<form name="loginform" method="post">
		<div class="loginspacer">
		<?php
		
		// make sure they aren't using IE below version 7
		
		$ua = $_SERVER['HTTP_USER_AGENT'];
		
		$ie = strstr($ua, 'MSIE') ? true : false;
		$ieVer = $ie ? preg_split('/msie/i', $ua) : false;
		$ieVer = $ieVer ? floatval($ieVer[1]) : false;
		
		// turn into whole number
		$ieVer = (int)($ieVer);
		
		if ($ua && $ie && $ieVer < 7) {
			
			?>
			<table cellpadding="0" id="tb">
			<tr>
			<td class="loginheader"><h3><?php echo __("Unsupported browser"); ?></h3><a href="http://www.sqlbuddy.com/help/" title="Help"><?php echo __("Help!"); ?></a></td>
			</tr>
			<tr>
			<td><?php echo __("We're sorry, but currently only Internet Explorer 7 is supported. It is available as a free download on Microsoft's website. Other free browsers are also supported, including Firefox, Safari, and Opera."); ?></td>
			</tr>
			</table>
			<?php
			
		} else {
			
			?>
			<table cellpadding="0" id="tb">
			<tr>
			<td colspan="2"><div class="loginheader"><h3><strong><?php echo __("Login"); ?></strong></h3><a href="http://www.sqlbuddy.com/help/" title="Help"><?php echo __("Help!"); ?></a></div></td>
			</tr>
			<?php
			if (isset($error)) {
				echo '<tr><td colspan="2"><div class="errormess">' . $error . '</div></td></tr>';
			}
			if (isset($_GET['timeout'])) {
				echo '<tr><td colspan="2"><div class="errormess">' . __("Your session has timed out. Please login again.") . '</div></td></tr>';
			}
			
			if (sizeof($adapterList) > 1) {
			
			?>
			<tr>
			<td class="field"></td>
			<td>
			<select name="ADAPTER" id="ADAPTER" onchange="adapterChange()">
			<?php
			
			if (in_array("mysql", $adapterList)) {
				?>
				<option value="mysql"<?php if ($adapter == "mysql") echo " selected"; ?>><?php echo __("MySQL"); ?></option>
				<?php
			}
			
			if (in_array("sqlite", $adapterList)) {
				?>
				<option value="sqlite"<?php if ($adapter == "sqlite") echo " selected"; ?>><?php echo __("SQLite"); ?></option>
				<?php
			}
			
			?>
			</select>
			</td>
			</tr>
			<?php
			
			}
			
			?>
			</table>
			<table cellpadding="0" id="REGOPTIONS"<?php if ($adapter == "sqlite") echo ' style="display: none"'; ?>>
			<tr>
			<td class="field"><?php echo __("Host"); ?>:</td>
			<td><input type="text" class="text" name="HOST" value="<?php echo $host; ?>" /></td>
			</tr>
			<tr>
			<td class="field"><?php echo __("Username"); ?>:</td>
			<td><input type="text" class="text" name="USER" value="<?php echo $user; ?>" /></td>
			</tr>
			<tr>
			<td class="field"><?php echo __("Password"); ?>:</td>
			<td><input type="password" class="text" name="PASS" id="PASS" /></td>
			</tr>
			</table>
			<table cellpadding="0" id="LITEOPTIONS"<?php if ($adapter == "mysql") echo ' style="display: none"'; ?>>
			<tr>
			<td class="field"><?php echo __("Database"); ?>:</td>
			<td><input type="text" class="text" name="DATABASE" id="DATABASE" value="<?php echo $database; ?>" /></td>
			</tr>
			</table>
			<table cellpadding="0">
			<tr>
			<td class="field"></td>
			<td><input type="submit" class="inputbutton" value="<?php echo __("Submit"); ?>" /></td>
			</tr>
			</table>
			<?php
			
		}
		
		?>
		</div>
		</form>
	</div>
	</div>
	<script type="text/javascript">
	<!--
	
	<?php
	
	if ($adapter != "sqlite") {
		echo "$('PASS').focus();";
	} else {
		echo "$('DATABASE').focus();";
	}
	
	?>
	
	if (!navigator.cookieEnabled) {
		var tb = $('tb');
		var newTr = new Element('tr');
		var newTd = new Element('td');
		newTd.setAttribute("colspan", 2);
		var newDiv = new Element('div');
		newDiv.className = "errormess";
		newDiv.set('text', "<?php echo __("You don't appear to have cookies enabled. For sessions to work, most php installations require cookies."); ?>");
		newTd.appendChild(newDiv);
		newTr.appendChild(newTd);
		tb.appendChild(newTr);
	}
	
	function adapterChange() {
		var adapter = $('ADAPTER');
		var currentAdapter = adapter.options[adapter.selectedIndex].value;
		
		if (currentAdapter == "sqlite") {
			$('REGOPTIONS').style.display = 'none';
			$('LITEOPTIONS').style.display = '';
			$('DATABASE').focus();
		} else {
			$('REGOPTIONS').style.display = '';
			$('LITEOPTIONS').style.display = 'none';
			$('PASS').focus();
		}
		
	}
	
	// -->
	</script>
</body>
</html>