<?php
/*

SQL Buddy - Web based MySQL administration
http://www.sqlbuddy.com/

ajaxsavecolumnedit.php
- saves the details of a table column

MIT license

2008 Calvin Lough <http://calv.in>

*/

include "functions.php";

loginCheck();

$conn->selectDB("mysql");

function removeAdminPrivs($priv) {
	if ($priv == "FILE" || $priv == "PROCESS" ||  $priv == "RELOAD" ||  $priv == "SHUTDOWN" ||  $priv == "SUPER")
		return false;
	else
		return true;
}

if (isset($_GET['user']))
	$user = $_GET['user'];

if (isset($_POST['NEWPASS']))
	$newPass = $_POST['NEWPASS'];

if (isset($_POST['CHOICE']))
	$choice = $_POST['CHOICE'];

if (isset($_POST['ACCESSLEVEL']))
	$accessLevel = $_POST['ACCESSLEVEL'];
else
	$accessLevel = "GLOBAL";

if ($accessLevel != "LIMITED")
	$accessLevel = "GLOBAL";

if (isset($_POST['DBLIST']))
	$dbList = $_POST['DBLIST'];
else
	$dbList = array();

if (isset($_POST['PRIVILEGES']))
	$privileges = $_POST['PRIVILEGES'];
else
	$privileges = array();

if (isset($_POST['GRANTOPTION']))
	$grantOption = $_POST['GRANTOPTION'];

if (isset($user) && ($accessLevel == "GLOBAL" || ($accessLevel == "LIMITED" && sizeof($dbList) > 0))) {
	
	if ($choice == "ALL") {
		$privList = "ALL";
	} else {
		if (isset($privileges) && count($privileges) > 0)
			$privList = implode(", ", $privileges);
		else
			$privList = "USAGE";
			
		if (sizeof($privileges) > 0) {
			if ($accessLevel == "LIMITED") {
				$privileges = array_filter($privileges, "removeAdminPrivs");
			}
			
			$privList = implode(", ", $privileges);
		} else {
			$privList = "USAGE";
		}
		
	}
	
	$split = explode("@", $user);
	
	if (isset($split[0]))
		$name = $split[0];
	
	if (isset($split[1]))
		$host = $split[1];
	
	if (isset($name) && isset($host)) {
		$user = "'" . $name . "'@'" . $host . "'";
		
		if ($accessLevel == "LIMITED") {
			$conn->query("DELETE FROM `db` WHERE `User`='$name' AND `Host`='$host'");
			
			foreach ($dbList as $theDb) {	
				$query = "GRANT " . $privList . " ON `$theDb`.* TO " . $user;
				
				if (isset($grantOption))
					$query .= " WITH GRANT OPTION";
				
				$conn->query($query) or ($dbError = $conn->error());
			}
		} else {
			$conn->query("REVOKE ALL PRIVILEGES ON *.* FROM " . $user);
			$conn->query("REVOKE GRANT OPTION ON *.* FROM " . $user);
			
			$query = "GRANT " . $privList . " ON *.* TO " . $user;
		
			if (isset($grantOption))
				$query .= " WITH GRANT OPTION";
			
			$conn->query($query) or ($dbError = $conn->error());
		}
		
		if (isset($newPass))
			$conn->query("SET PASSWORD FOR '$name'@'$host' = PASSWORD('$newPass')") or ($dbError = $conn->error());
		
		$conn->query("FLUSH PRIVILEGES") or ($dbError = $conn->error());
		
		echo "{\n";
		echo "    \"formupdate\": \"" . $_GET['form'] . "\",\n";
		echo "    \"errormess\": \"";
		if (isset($dbError))
			echo $dbError;
		echo "\"\n";
		echo '}';
	}
}

?>