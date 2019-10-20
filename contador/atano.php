<?php

	ini_set('display_errors',1);
	ini_set('display_startup_erros',1);
	error_reporting(E_ALL);	
	
	session_start();
	
	echo "<pre>";
		print_r($_SESSION);
	echo "</pre>";
	
	echo "<pre>";
		print_r($_COOKIE);
	echo "</pre>";

?>