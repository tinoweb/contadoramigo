<?php 

	include("app_server.php");

	if ($_POST['update'] == "update"){
		
		$count = 0;
		foreach ($_POST['item'] as $value) {
		$count ++;
			$query = "UPDATE dbo_biogen_usuario_config SET listorder = " . $count . " WHERE id_config = " . $value;
			mysql_query($query) or die('Error, insert query failed');
				
		}
		
		print utf8_decode('<img src="images/ajax-loader.gif" /> Configura&ccedil;&atilde;o Salva.');
		
		
	}
?>