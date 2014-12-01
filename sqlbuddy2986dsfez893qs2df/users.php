<?php
/*

SQL Buddy - Web based MySQL administration
http://www.sqlbuddy.com/

users.php
- manage users page

MIT license

2008 Calvin Lough <http://calv.in>

*/

include "functions.php";

loginCheck();

function removeAdminPrivs($priv) {
	if ($priv == "FILE" || $priv == "PROCESS" ||  $priv == "RELOAD" ||  $priv == "SHUTDOWN" ||  $priv == "SUPER")
		return false;
	else
		return true;
}

if ($_POST) {
	
	if (isset($_POST['NEWHOST']))
		$newHost = $_POST['NEWHOST'];
	else
		$newHost = "localhost";
	
	if (isset($_POST['NEWNAME']))
		$newName = $_POST['NEWNAME'];
	
	if (isset($_POST['NEWPASS']))
		$newPass = $_POST['NEWPASS'];
	
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
	
	if (isset($_POST['NEWCHOICE']))
		$newChoice = $_POST['NEWCHOICE'];
	
	if (isset($_POST['NEWPRIVILEGES']))
		$newPrivileges = $_POST['NEWPRIVILEGES'];
	
	if (isset($newName) && ($accessLevel == "GLOBAL" || ($accessLevel == "LIMITED" && sizeof($dbList) > 0))) {
		
		if ($newChoice == "ALL") {
			$privList = "ALL";
		} else {
			
			if (sizeof($newPrivileges) > 0) {
				if ($accessLevel == "LIMITED") {
					$newPrivileges = array_filter($newPrivileges, "removeAdminPrivs");
				}
				
				$privList = implode(", ", $newPrivileges);
				
			} else {
				$privList = "USAGE";
			}
		}
		
		if ($accessLevel == "LIMITED") {
			foreach ($dbList as $theDb) {
				$newQuery = "GRANT " . $privList;
				
				$newQuery .= " ON `$theDb`.*";
				
				$newQuery .= " TO '" . $newName . "'@'" . $newHost . "'";
				
				if ($newPass)
					$newQuery .= " IDENTIFIED BY '" . $newPass . "'";
				
				if (isset($_POST['GRANTOPTION']))
					$newQuery .= " WITH GRANT OPTION";
				
				$conn->query($newQuery) or ($dbError = $conn->error());
			}
		} else {
			$newQuery = "GRANT " . $privList;
			
			$newQuery .= " ON *.*";
			
			$newQuery .= " TO '" . $newName . "'@'" . $newHost . "'";
			
			if ($newPass)
				$newQuery .= " IDENTIFIED BY '" . $newPass . "'";
			
			if (isset($_POST['GRANTOPTION']))
				$newQuery .= " WITH GRANT OPTION";
			
			$conn->query($newQuery) or ($dbError = $conn->error());
		}
		
		$conn->query("FLUSH PRIVILEGES") or ($dbError = $conn->error());
		
	}
}

$connected = $conn->selectDB("mysql");

// delete users
if (isset($_POST['deleteUsers']) && $connected) {
	$deleteUsers = $_POST['deleteUsers'];
	
	// boom!
	$userList = explode(";", $deleteUsers);
	
	foreach ($userList as $each) {
		$split = explode("@", $each, 2);
		
		if (isset($split[0]))
			$user = trim($split[0]);
		
		if (isset($split[1]))
			$host = trim($split[1]);
		
		if (isset($user) && isset($host)) {
			$conn->query("REVOKE ALL PRIVILEGES ON *.* FROM '$user'@'$host'");
			$conn->query("REVOKE GRANT OPTION ON *.* FROM '$user'@'$host'");
			$conn->query("DELETE FROM `user` WHERE `User`='$user' AND `Host`='$host'");
			$conn->query("DELETE FROM `db` WHERE `User`='$user' AND `Host`='$host'");
			$conn->query("DELETE FROM `tables_priv` WHERE `User`='$user' AND `Host`='$host'");
			$conn->query("DELETE FROM `columns_priv` WHERE `User`='$user' AND `Host`='$host'");
		}
	}
	$conn->query("FLUSH PRIVILEGES");
}

if (isset($dbError)) {
	echo '<div class="errormessage" style="margin: 6px 12px 10px 7px; width: 550px">';
	echo '<strong>' . __("Error performing operation") . '</strong><p>' . $dbError . '</p>';
	echo '</div>';
}

?>

<div class="users">

<?php

if ($connected) {
	
	$userSql = $conn->query("SELECT * FROM `user`");
	
	if ($conn->isResultSet($userSql)) {
		
		?>
		
		<table class="browsenav">
		<tr>
		<td class="options">
		<?php
		
		echo __("Select") . ':&nbsp;&nbsp;<a onclick="checkAll()">' . __("All") . '</a>&nbsp;&nbsp;<a onclick="checkNone()">' . __("None") . '</a>';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . __("With selected") . ':&nbsp;&nbsp;<a onclick="editSelectedRows()">' . __("Edit") . '</a>&nbsp;&nbsp;<a onclick="deleteSelectedUsers()">' . __("Delete") . '</a>';
		
		?>
		
		</td>
		</tr>
		</table>
		
		<?php
		
		echo '<div class="grid">';
		
		echo '<div class="emptyvoid">&nbsp;</div>';
		
		echo '<div class="gridheader impotent">';
			echo '<div class="gridheaderinner">';
			echo '<table cellpadding="0" cellspacing="0">';
			echo '<tr>';
			echo '<td><div column="1" class="headertitle column1">' . __("Host") . '</div></td>';
			echo '<td><div class="columnresizer"></div></td>';
			echo '<td><div column="2" class="headertitle column2">' . __("User") . '</div></td>';
			echo '<td><div class="columnresizer"></div></td>';
			echo '</tr>';
			echo '</table>';
			echo '</div>';
		echo '</div>';
		
		echo '<div class="leftchecks" style="max-height: 400px">';
		
		$m = 0;
		
		while ($userRow = $conn->fetchAssoc($userSql)) {
			$queryBuilder = $userRow['User'] . "@" . $userRow['Host'];
			echo '<dl class="manip';
			
			if ($m % 2 == 1)
				echo ' alternator';
			else 
				echo ' alternator2';
			
			echo '"><dt><input type="checkbox" class="check' . $m . '" onclick="rowClicked(' . $m++ . ')" querybuilder="' . $queryBuilder . '" /></dt></dl>';
		}
		
		echo '</div>';
		
		$userSql = $conn->query("SELECT * FROM `user`");
		
		echo '<div class="gridscroll withchecks" style="overflow-x: hidden; max-height: 400px">';
		
		if ($conn->isResultSet($userSql)) {
			$m = 0;
			
			while ($userRow = $conn->fetchAssoc($userSql)) {
				
				echo '<div class="row' . $m . ' browse';
				
				if ($m % 2 == 1) { echo ' alternator'; }
				else 
				{ echo ' alternator2'; }
				
				echo '">';
				echo '<table cellspacing="0" cellpadding="0">';
				echo '<tr>';
				echo '<td><div class="item column1">' . $userRow['Host'] . '</div></td>';
				echo '<td><div class="item column2">' . $userRow['User'] . '</div></td>';
				echo '</tr>';
				echo '</table>';
				echo '</div>';
				
				$m++;
			}
		}
		
		echo '</div>';
		echo '</div>';
	
	}
	
	$hasPermissions = false;
	
	// check to see if this user has proper permissions to manage users
	$checkSql = $conn->query("SELECT `Grant_priv` FROM `user` WHERE `Host`='" . $conn->getOptionValue("host") . "' AND `User`='" . $_SESSION['SB_LOGIN_USER'] . "' LIMIT 1");
	
	if ($conn->isResultSet($checkSql)) {
		$grantValue = $conn->result($checkSql, 0, "Grant_priv");
		
		if ($grantValue == "Y") {
			$hasPermissions = true;
		}
	}
	
	if ($hasPermissions) {
	
	?>
	
	<div class="inputbox" style="margin-top: 15px">
		<h4><?php echo __("Add a new user"); ?></h4>
			
		<form id="NEWUSERFORM" onsubmit="submitForm('NEWUSERFORM'); return false">
		<table cellpadding="0">
		<tr>
			<td class="secondaryheader"><?php echo __("Host"); ?>:</td>
			<td><input type="text" class="text" name="NEWHOST" value="localhost" /></td>
		</tr>
		<tr>
			<td class="secondaryheader"><?php echo __("Name"); ?>:</td>
			<td><input type="text" class="text" name="NEWNAME" /></td>
		</tr>
		<tr>
			<td class="secondaryheader"><?php echo __("Password"); ?>:</td>
			<td><input type="password" class="text" name="NEWPASS" /></td>
		</tr>
		<?php
		
		$dbList = $conn->listDatabases();
		
		if ($conn->isResultSet($dbList)) {
		
		?>
		<tr>
			<td class="secondaryheader"><?php echo __("Allow access to"); ?>:</td>
			<td>
			<label><input type="radio" name="ACCESSLEVEL" value="GLOBAL" id="ACCESSGLOBAL" onchange="updatePane('ACCESSSELECTED', 'dbaccesspane'); updatePane('ACCESSGLOBAL', 'adminprivlist')" onclick="updatePane('ACCESSSELECTED', 'dbaccesspane'); updatePane('ACCESSGLOBAL', 'adminprivlist')" checked="checked" /><?php echo __("All databases"); ?></label><br />
			<label><input type="radio" name="ACCESSLEVEL" value="LIMITED" id="ACCESSSELECTED" onchange="updatePane('ACCESSSELECTED', 'dbaccesspane'); updatePane('ACCESSGLOBAL', 'adminprivlist')" onclick="updatePane('ACCESSSELECTED', 'dbaccesspane'); updatePane('ACCESSGLOBAL', 'adminprivlist')" /><?php echo __("Selected databases"); ?></label>
			
			<div id="dbaccesspane" style="display: none"  class="privpane">
				<table cellpadding="0">
				<?php
				
				while ($dbRow = $conn->fetchArray($dbList)) {
					echo '<tr>';
					echo '<td>';
					echo '<label><input type="checkbox" name="DBLIST[]" value="' . $dbRow[0] . '" />' . $dbRow[0] . '</label>';
					echo '</td>';
					echo '</tr>';
				}
				
				?>
				</table>
			</div>
			
			</td>
		</tr>
		<?php
		
		}
		
		?>
		<tr>
			<td class="secondaryheader"><?php echo __("Give user"); ?>:</td>
			<td>
			<label><input type="radio" name="NEWCHOICE" value="ALL" onchange="updatePane('PRIVSELECTED', 'privilegepane')" onclick="updatePane('PRIVSELECTED', 'privilegepane')" checked="checked" /><?php echo __("All privileges"); ?></label><br />
			<label><input type="radio" name="NEWCHOICE" value="SELECTED" id="PRIVSELECTED" onchange="updatePane('PRIVSELECTED', 'privilegepane')" onclick="updatePane('PRIVSELECTED', 'privilegepane')" /><?php echo __("Selected privileges"); ?></label>
			
			<div id="privilegepane" style="display: none"  class="privpane">
				<div class="paneheader">
				<?php echo __("User privileges"); ?>
				</div>
				<table cellpadding="0" id="userprivs">
				<tr>
					<td width="50%">
					<label><input type="checkbox" name="NEWPRIVILEGES[]" value="SELECT" /><?php echo __("Select"); ?></label>
					</td>
					<td width="50%">
					<label><input type="checkbox" name="NEWPRIVILEGES[]" value="INSERT" /><?php echo __("Insert"); ?></label>
					</td>
				</tr>
				<tr>
					<td>
					<label><input type="checkbox" name="NEWPRIVILEGES[]" value="UPDATE" /><?php echo __("Update"); ?></label>
					</td>
					<td>
					<label><input type="checkbox" name="NEWPRIVILEGES[]" value="DELETE" /><?php echo __("Delete"); ?></label>
					</td>
				</tr>
				<tr>
					<td>
					<label><input type="checkbox" name="NEWPRIVILEGES[]" value="INDEX" /><?php echo __("Index"); ?></label>
					</td>
					<td>
					<label><input type="checkbox" name="NEWPRIVILEGES[]" value="ALTER" /><?php echo __("Alter"); ?></label>
					</td>
				</tr>
				<tr>
					<td>
					<label><input type="checkbox" name="NEWPRIVILEGES[]" value="CREATE" /><?php echo __("Create"); ?></label>
					</td>
					<td>
					<label><input type="checkbox" name="NEWPRIVILEGES[]" value="DROP" /><?php echo __("Drop"); ?></label>
					</td>
				</tr>
				<tr>
					<td colspan="2">
					<label><input type="checkbox" name="NEWPRIVILEGES[]" value="CREATE TEMPORARY TABLES" /><?php echo __("Temp tables"); ?></label>
					</td>
				</tr>
				</table>
				<div id="adminprivlist">
				<div class="paneheader">
				<?php echo __("Administrator privileges"); ?>
				</div>
				<table cellpadding="0" id="adminprivs">
				<tr>
					<td width="50%">
					<label><input type="checkbox" name="NEWPRIVILEGES[]" value="FILE" /><?php echo __("File"); ?></label>
					</td>
					<td width="50%">
					<label><input type="checkbox" name="NEWPRIVILEGES[]" value="PROCESS" /><?php echo __("Process"); ?></label>
					</td>
				</tr>
				<tr>
					<td>
					<label><input type="checkbox" name="NEWPRIVILEGES[]" value="RELOAD" /><?php echo __("Reload"); ?></label>
					</td>
					<td>
					<label><input type="checkbox" name="NEWPRIVILEGES[]" value="SHUTDOWN" /><?php echo __("Shutdown"); ?></label>
					</td>
				</tr>
				<tr>
					<td>
					<label><input type="checkbox" name="NEWPRIVILEGES[]" value="SUPER" /><?php echo __("Super"); ?></label>
					</td>
					<td>
					</td>
				</tr>
				</table>
				</div>
			</div>
			
			</td>
		</tr>
		</table>
		
		<table cellpadding="0">
		<tr>
			<td class="secondaryheader"><?php echo __("Options"); ?>:</td>
			<td>
			<label><input type="checkbox" name="GRANTOPTION" value="true" /><?php echo __("Grant option"); ?></label>
			</td>
		</tr>
		</table>
		
		<div style="margin-top: 10px; height: 22px; padding: 4px 0">
			<input type="submit" class="inputbutton" value="<?php echo __("Submit"); ?>" />
		</div>
		</form>
	</div>
	<?php
	
	} else {
		?>
		<h4 style="margin-top: 20px"><?php echo __("Error"); ?></h4>
		<p><?php echo __("You do not have enough permissions to create new users."); ?></p>
		<?php
	}

} else {
	?>
	<div class="errorpage">
	<h4><?php echo __("Error"); ?></h4>
	<p><?php echo __("You do not have enough permissions to view or manage users."); ?></p>
	</div>
	<?php
}

?>
</div>

<script type="text/javascript" authkey="<?php echo $requestKey; ?>">
setTimeout(function(){ startGrid(); }, 1);
</script>