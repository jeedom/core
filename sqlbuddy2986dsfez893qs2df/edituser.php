<?php
/*

SQL Buddy - Web based MySQL administration
http://www.sqlbuddy.com/

edituser.php
- change permissions, password

MIT license

2008 Calvin Lough <http://calv.in>

*/

include "functions.php";

loginCheck();

$conn->selectDB("mysql");

if (isset($_POST['editParts'])) {
	$editParts = $_POST['editParts'];
	
	$editParts = explode("; ", $editParts);
	
	$totalParts = count($editParts);
	$counter = 0;
	
	$firstField = true;
	
	foreach ($editParts as $part) {
		$part = trim($part);
		
		if ($part != "" && $part != ";") {
			
			list($user, $host) = explode("@", $part);
			
			$userSQL = $conn->query("SELECT * FROM `user` WHERE `User`='" . $user . "' AND `Host`='" . $host . "'");
			$dbuserSQL = $conn->query("SELECT * FROM `db` WHERE `User`='" . $user . "' AND `Host`='" . $host . "'");
			
			if ($conn->isResultSet($userSQL)) {
				
				$allPrivs = true;
				
				$dbShowList = array();
				
				if ($conn->isResultSet($dbuserSQL)) {
					
					$accessLevel = "LIMITED";
					
					while ($dbuserRow = $conn->fetchAssoc($dbuserSQL)) {
						$selectedPrivs = array();
						
						$dbShowList[] = $dbuserRow['Db'];
									
						foreach ($dbuserRow as $key=>$value) {
							if (substr($key, -5) == "_priv" && $key != "Grant_priv" && $value == "N") {
								$allPrivs = false;
							}
							
							if ($value == "N")
								$selectedPrivs[$key] = $value;
						}
						
						if (isset($thePrivList)) {
							$thePrivList = array_merge($thePrivList, $selectedPrivs);
						} else {
							$thePrivList = $dbuserRow;
						}
					}
				} else {
					$accessLevel = "GLOBAL";
					
					$userRow = $conn->fetchAssoc($userSQL);
					
					foreach ($userRow as $key=>$value) {
						if (substr($key, -5) == "_priv" && $key != "Grant_priv" && $value == "N") {
							$allPrivs = false;
						}
					}
					
					$thePrivList = $userRow;
				}
				
				echo '<form id="editform' . $counter . '" querypart="' . $part . '" onsubmit="saveUserEdit(\'editform' . $counter . '\'); return false;">';
				echo '<div class="edituser inputbox">';
				echo '<div class="errormessage" style="margin: 0 7px 13px; display: none"></div>';
				echo '<table class="edit" cellspacing="0" cellpadding="0">';
				
				?>
				
				<tr>
					<td class="secondaryheader"><?php echo __("User"); ?>:</td>
					<td><strong><?php echo $part; ?></strong></td>
				</tr>
				<tr>
					<td class="secondaryheader"><?php echo __("Change password"); ?>:</td>
					<td><input type="password" class="text" name="NEWPASS" /></td>
				</tr>
				<?php
				
				$dbList = $conn->listDatabases();
				
				if ($conn->isResultSet($dbList)) {
				
				?>
				<tr>
					<td class="secondaryheader"><?php echo __("Allow access to"); ?>:</td>
					<td>
					<label><input type="radio" name="ACCESSLEVEL" value="GLOBAL" id="ACCESSGLOBAL<?php echo $counter; ?>" onchange="updatePane('ACCESSSELECTED<?php echo $counter; ?>', 'dbaccesspane<?php echo $counter; ?>'); updatePane('ACCESSGLOBAL<?php echo $counter; ?>', 'adminprivlist<?php echo $counter; ?>')" onclick="updatePane('ACCESSSELECTED<?php echo $counter; ?>', 'dbaccesspane<?php echo $counter; ?>'); updatePane('ACCESSGLOBAL<?php echo $counter; ?>', 'adminprivlist<?php echo $counter; ?>')"<?php if ($accessLevel == "GLOBAL") echo ' checked="checked"'; ?> /><?php echo __("All databases"); ?></label><br />
					<label><input type="radio" name="ACCESSLEVEL" value="LIMITED" id="ACCESSSELECTED<?php echo $counter; ?>" onchange="updatePane('ACCESSSELECTED<?php echo $counter; ?>', 'dbaccesspane<?php echo $counter; ?>'); updatePane('ACCESSGLOBA<?php echo $counter; ?>L', 'adminprivlist<?php echo $counter; ?>')" onclick="updatePane('ACCESSSELECTED<?php echo $counter; ?>', 'dbaccesspane<?php echo $counter; ?>'); updatePane('ACCESSGLOBAL<?php echo $counter; ?>', 'adminprivlist<?php echo $counter; ?>')"<?php if ($accessLevel == "LIMITED") echo ' checked="checked"'; ?> /><?php echo __("Selected databases"); ?></label>
					
					<div id="dbaccesspane<?php echo $counter; ?>"<?php if ($accessLevel == "GLOBAL") echo ' style="display: none"'; ?> class="privpane">
						<table cellpadding="0">
						<?php
						
						while ($dbRow = $conn->fetchArray($dbList)) {
							echo '<tr>';
							echo '<td>';
							echo '<label><input type="checkbox" name="DBLIST[]" value="' . $dbRow[0] . '"';
							
							if (in_array($dbRow[0], $dbShowList))
								echo ' checked="checked"';
							
							echo ' />' . $dbRow[0] . '</label>';
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
					<label><input type="radio" name="CHOICE" value="ALL" onchange="updatePane('EDITPRIVSELECTED<?php echo $counter; ?>', 'editprivilegepane<?php echo $counter; ?>')" onclick="updatePane('EDITPRIVSELECTED<?php echo $counter; ?>', 'editprivilegepane<?php echo $counter; ?>')" <?php if ($allPrivs) echo 'checked="checked"'; ?> /><?php echo __("All privileges"); ?></label><br />
					<label><input type="radio" name="CHOICE" value="SELECTED" id="EDITPRIVSELECTED<?php echo $counter; ?>" onchange="updatePane('EDITPRIVSELECTED<?php echo $counter; ?>', 'editprivilegepane<?php echo $counter; ?>')" onclick="updatePane('EDITPRIVSELECTED<?php echo $counter; ?>', 'editprivilegepane<?php echo $counter; ?>')" <?php if (!$allPrivs) echo 'checked="checked"'; ?> /><?php echo __("Selected privileges"); ?></label>
					
					<div id="editprivilegepane<?php echo $counter; ?>" class="privpane" <?php if ($allPrivs) echo 'style="display: none"'; ?>>
					<div class="paneheader">
					<?php echo __("User privileges"); ?>
					</div>
					<table cellpadding="0" id="edituserprivs<?php echo $counter; ?>">
					<tr>
						<td width="50%">
						<label><input type="checkbox" name="PRIVILEGES[]" value="SELECT" <?php if (isset($thePrivList['Select_priv']) && $thePrivList['Select_priv'] == "Y") echo 'checked="checked"'; ?> /><?php echo __("Select"); ?></label>
						</td>
						<td width="50%">
						<label><input type="checkbox" name="PRIVILEGES[]" value="INSERT" <?php if (isset($thePrivList['Insert_priv']) && $thePrivList['Insert_priv'] == "Y") echo 'checked="checked"'; ?> /><?php echo __("Insert"); ?></label>
						</td>
					</tr>
					<tr>
						<td>
						<label><input type="checkbox" name="PRIVILEGES[]" value="UPDATE" <?php if (isset($thePrivList['Update_priv']) && $thePrivList['Update_priv'] == "Y") echo 'checked="checked"'; ?> /><?php echo __("Update"); ?></label>
						</td>
						<td>
						<label><input type="checkbox" name="PRIVILEGES[]" value="DELETE" <?php if (isset($thePrivList['Delete_priv']) && $thePrivList['Delete_priv'] == "Y") echo 'checked="checked"'; ?> /><?php echo __("Delete"); ?></label>
						</td>
					</tr>
					<tr>
						<td>
						<label><input type="checkbox" name="PRIVILEGES[]" value="INDEX" <?php if (isset($thePrivList['Index_priv']) && $thePrivList['Index_priv'] == "Y") echo 'checked="checked"'; ?> /><?php echo __("Index"); ?></label>
						</td>
						<td>
						<label><input type="checkbox" name="PRIVILEGES[]" value="ALTER" <?php if (isset($thePrivList['Alter_priv']) && $thePrivList['Alter_priv'] == "Y") echo 'checked="checked"'; ?> /><?php echo __("Alter"); ?></label>
						</td>
					</tr>
					<tr>
						<td>
						<label><input type="checkbox" name="PRIVILEGES[]" value="CREATE" <?php if (isset($thePrivList['Create_priv']) && $thePrivList['Create_priv'] == "Y") echo 'checked="checked"'; ?> /><?php echo __("Create"); ?></label>
						</td>
						<td>
						<label><input type="checkbox" name="PRIVILEGES[]" value="DROP" <?php if (isset($thePrivList['Drop_priv']) && $thePrivList['Drop_priv'] == "Y") echo 'checked="checked"'; ?> /><?php echo __("Drop"); ?></label>
						</td>
					</tr>
					<tr>
						<td colspan="2">
						<label><input type="checkbox" name="PRIVILEGES[]" value="CREATE TEMPORARY TABLES" <?php if (isset($thePrivList['Create_tmp_table_priv']) && $thePrivList['Create_tmp_table_priv'] == "Y") echo 'checked="checked"'; ?> /><?php echo __("Temp tables"); ?></label>
						</td>
					</tr>
					</table>
					<div id="adminprivlist<?php echo $counter; ?>">
					<div class="paneheader">
					<?php echo __("Administrator privileges"); ?>
					</div>
					<table cellpadding="0" id="editadminprivs<?php echo $counter; ?>">
					<tr>
						<td width="50%">
						<label><input type="checkbox" name="PRIVILEGES[]" value="FILE" <?php if (isset($thePrivList['File_priv']) && $thePrivList['File_priv'] == "Y") echo 'checked="checked"'; ?> /><?php echo __("File"); ?></label>
						</td>
						<td width="50%">
						<label><input type="checkbox" name="PRIVILEGES[]" value="PROCESS" <?php if (isset($thePrivList['Process_priv']) && $thePrivList['Process_priv'] == "Y") echo 'checked="checked"'; ?> /><?php echo __("Process"); ?></label>
						</td>
					</tr>
					<tr>
						<td>
						<label><input type="checkbox" name="PRIVILEGES[]" value="RELOAD" <?php if (isset($thePrivList['Reload_priv']) && $thePrivList['Reload_priv'] == "Y") echo 'checked="checked"'; ?> /><?php echo __("Reload"); ?></label>
						</td>
						<td>
						<label><input type="checkbox" name="PRIVILEGES[]" value="SHUTDOWN" <?php if (isset($thePrivList['Shutdown_priv']) && $thePrivList['Shutdown_priv'] == "Y") echo 'checked="checked"'; ?> /><?php echo __("Shutdown"); ?></label>
						</td>
					</tr>
					<tr>
						<td>
						<label><input type="checkbox" name="PRIVILEGES[]" value="SUPER" <?php if (isset($thePrivList['Super_priv']) && $thePrivList['Super_priv'] == "Y") echo 'checked="checked"'; ?> /><?php echo __("Super"); ?></label>
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
					<label><input type="checkbox" name="GRANTOPTION" value="true" <?php if ($thePrivList['Grant_priv'] == "Y") echo 'checked="checked"'; ?> /><?php echo __("Grant option"); ?></label>
					</td>
				</tr>
				</table>
				
				<div style="margin-top: 10px; height: 22px; padding: 4px 0">
					<input type="submit" class="inputbutton" value="<?php echo __("Submit"); ?>" />&nbsp;&nbsp;<a onclick="cancelEdit('editform<?php echo $counter; ?>')"><?php echo __("Cancel"); ?></a>
				</div>
				</div>
				</form>
			
			<?php
			
			} else {
				echo __("User not found!");
			}
			
			$counter++;
		}
	}
	
}

?>