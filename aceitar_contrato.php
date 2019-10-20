<?php 

	session_start();

	include 'conect.php';

	if( isset( $_SESSION["id_userSecaoMultiplo"] ) ):
		
		$user = $_SESSION["id_userSecaoMultiplo"];
		$consulta = mysql_query("INSERT INTO `contratos_aceitos`(`user`, `aceito`, `data`, `contratoId`) VALUES ('".$user."','1',NOW(),'1' )");

	endif;

	header("Location: index_restrita.php");	

?>