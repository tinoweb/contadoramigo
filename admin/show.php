<?php 	

session_start();

	echo "Admin <br/>";

	echo "<pre>_COOKIE";
		print_r($_COOKIE);
	echo "</pre>";
	
	echo "<pre>_SESSION";
		print_r($_SESSION);
	echo "</pre>";
?>