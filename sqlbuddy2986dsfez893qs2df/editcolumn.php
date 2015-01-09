<?php
/*

SQL Buddy - Web based MySQL administration
http://www.sqlbuddy.com/

editcolumn.php
- edit database table columns

MIT license

2008 Calvin Lough <http://calv.in>

*/

include "functions.php";

loginCheck();

requireDatabaseAndTableBeDefined();

if (isset($db))
	$conn->selectDB($db);

if (isset($db))
	$structureSql = $conn->query("SHOW FULL FIELDS FROM `$table`");

if (isset($_POST['editParts']) && $conn->isResultSet($structureSql)) {
	
	$editParts = $_POST['editParts'];
	
	$editParts = explode("; ", $editParts);
	
	$totalParts = count($editParts);
	$counter = 0;
	
	$firstField = true;
	
	?>
	<script type="text/javascript" authkey="<?php echo $requestKey; ?>">
	
	if ($('EDITCOLUMNFIRSTFIELD')) {
		$('EDITCOLUMNFIRSTFIELD').focus();
	}
	
	</script>
	<?php
	
	while ($structureRow = $conn->fetchAssoc($structureSql)) {
		if (in_array($structureRow['Field'], $editParts)) {
			echo '<form id="editform' . $counter . '" querypart="' . $structureRow['Field'] . '" onsubmit="saveColumnEdit(\'editform' . $counter . '\'); return false;">';
			echo '<div class="editcolumn">';
			echo '<div class="errormessage" style="margin: 0 7px 13px; display: none"></div>';
			echo '<table class="edit" cellspacing="0" cellpadding="0">';
			
			preg_match("/^([a-z]+)(.([0-9]+).)?(.*)?$/", $structureRow['Type'], $matches);
			
			$curtype = $matches[1];
			$cursizeQuotes = $matches[2];
			$cursize = $matches[3];
			$curextra = $matches[4];
			
			?>
			
			<tr>
			<td class="secondaryheader">
			<?php echo __("Name:"); ?>
			</td>
			<td class="inputarea">
			<input type="text" class="text" name="NAME"<?php if ($firstField) echo ' id="EDITCOLUMNFIRSTFIELD"'; ?> value="<?php echo $structureRow['Field']; ?>" style="width: 125px" />
			</td>
			<td class="secondaryheader">
			<?php echo __("Type:"); ?>
			</td>
			<td class="inputarea">
			<select name="TYPE" onchange="toggleValuesLine(this, 'editform<?php echo $counter; ?>')" style="width: 125px">
			<?php
			
			foreach ($typeList as $type) {
				echo '<option value="' . $type . '"';
				
				if ($type == $curtype)
					echo ' selected';
				
				echo '>' . $type . '</option>';
			}
			
			?>
			</select>
			</td>
			</tr>
			<?php
			
			echo '<tr class="valueline inputarea"';
			
			if (!($curtype == "enum" || $curtype == "set"))
				echo ' style="display: none"';
			
			echo '>';
			
			?>
			<td class="secondaryheader">
			<?php echo __("Values:"); ?>
			</td>
			<td class="inputarea">
			<input type="text" class="text" name="VALUES" value="<?php if (substr($curextra, 0, 1) == "(" && substr($curextra, -1) == ")") echo $curextra; ?>" style="width: 125px" />
			</td>
			<td colspan="2">
			</td>
			</tr>
			<tr>
			<td class="secondaryheader">
			<?php echo __("Size:"); ?>
			</td>
			<td class="inputarea">
			<input type="text" class="text" name="SIZE" value="<?php echo $cursize; ?>" style="width: 125px" />
			</td>
			<td class="secondaryheader">
			<?php echo __("Default:"); ?>
			</td>
			<td class="inputarea">
			<input type="text" class="text" name="DEFAULT" value="<?php echo $structureRow['Default']; ?>" style="width: 125px" />
			</td>
			</tr>
			<?php
			
			if (isset($charsetList) && isset($collationList)) {
				echo "<tr>";
				echo "<td class=\"secondaryheader\">";
				echo __("Charset:");
				echo "</td>";
				echo "<td class=\"inputarea\" colspan=\"3\">";
				echo "<select name=\"CHARSET\" style=\"width: 125px\">";
				echo "<option></option>";
				
				if ($structureRow['Collation'] != "NULL" && isset($structureRow['Collation'])) {
					$currentCharset = $collationList[$structureRow['Collation']];
				}
				
				foreach ($charsetList as $charset) {
					echo "<option value=\"" . $charset . "\"";
					
					if (isset($currentCharset) && $charset == $currentCharset) {
						echo ' selected="selected"';
					}
					
					echo ">" . $charset . "</option>";
				}
				echo "</select>";
				echo "</td>";
				echo "</tr>";
			}
			
			?>
			<tr>
			<td class="secondaryheader">
			<?php echo __("Other:"); ?>
			</td>
			<td colspan="3" class="inputarea">
			<label><input type="checkbox" name="UNSIGN"<?php if ($curextra == " unsigned") echo " checked"; ?>><?php echo __("Unsigned"); ?></label>
			<label><input type="checkbox" name="BINARY"<?php if ($curextra == " binary") echo " checked"; ?>><?php echo __("Binary"); ?></label>
			<label><input type="checkbox" name="NOTNULL"<?php if ($structureRow['Null'] != "YES") echo " checked"; ?>><?php echo __("Not Null"); ?></label>
			</td>
			</tr>
			
			<?php
			
			$firstField = false;
			
			?>
			
			<tr>
			<td style="padding: 5px 0 15px" colspan="4">
			<input type="submit" class="inputbutton" value="<?php echo __("Submit"); ?>" />&nbsp;&nbsp;<a onclick="cancelEdit('editform<?php echo $counter; ?>')"><?php echo __("Cancel"); ?></a>
			</td>
			</tr>
			</table>
			</div>
			</form>
			
			<?php
			
			$counter++;
		}
	}
	
}

?>