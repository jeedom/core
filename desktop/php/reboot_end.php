<?php
if($_GET['shut == 1']){
	jeedom::haltSystem();
}else{
	jeedom::rebootSystem();
}
?>