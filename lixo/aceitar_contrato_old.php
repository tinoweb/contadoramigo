<?php 

	session_start();

	include 'conect.php';

	if( isset( $_SESSION["id_userSecaoMultiplo"] ) ):
		
		$user = $_SESSION["id_userSecaoMultiplo"];
		$consulta = mysql_query("INSERT INTO `contratos_aceitos`(`id`, `user`, `aceito`, `data`) VALUES ( '','".$user."','1','".date("Y-m-d H:m:s")."' )");

	endif;

	header("Location: index_restrita.php");	

?>