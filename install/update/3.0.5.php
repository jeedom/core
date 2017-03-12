<?php
try {
	shell_exec('sudo apt-get -y install xvfb cutycapt');
} catch (Exception $e) {
	echo $e->getMessage();
}
?>