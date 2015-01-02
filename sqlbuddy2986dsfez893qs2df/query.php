<?php
/*

SQL Buddy - Web based MySQL administration
http://www.sqlbuddy.com/

query.php
- query the database

MIT license

2008 Calvin Lough <http://calv.in>

*/

include "functions.php";

loginCheck();

if (isset($db))
	$conn->selectDB($db);

if (isset($_POST['query']))
	$query = $_POST['query'];

echo '<div style="padding-left: 5px">';

if (isset($db)) {
	echo '<span style="color: rgb(135, 135, 135)">' . sprintf(__("Run a query on the %s database"), $db) . '.</span>';
}

if (isset($query)) {
	$displayQuery = $query;
} else if (isset($db) && isset($table) && $conn->getAdapter() == "mysql") {
	$displayQuery = "SELECT * FROM `$table` LIMIT 100";
} else if (isset($db) && isset($table) && $conn->getAdapter() == "sqlite") {
	$displayQuery = "SELECT * FROM '$table' LIMIT 100";
}

?>

<form onsubmit="executeQuery(); return false;">
<table cellpadding="0" cellspacing="0" style="margin: 2px 0px">
<tr>
	<td>
	<textarea name="QUERY" id="QUERY"><?php
	
	if (isset($displayQuery))
		echo htmlentities($displayQuery, ENT_QUOTES, 'UTF-8');
	
	?></textarea>
	</td>
	<td valign="bottom" style="padding-left: 7px">
	<input type="submit" class="inputbutton" value="<?php echo __("Submit"); ?>" />
	</td>
</tr>
</table>
</form>

</div>

<?php

if (isset($query)) {
	
	echo '<div style="margin-top: 10px">';
	
	require "includes/browse.php";
	
	echo '</div>';
}

?>
<script type="text/javascript" authkey="<?php echo $requestKey; ?>">

$('QUERY').focus();

</script>