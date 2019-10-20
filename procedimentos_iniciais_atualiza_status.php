<?php
include "conect.php";

session_start();

if(isset($_REQUEST['atualiza'])){
	$sql = "UPDATE login SET info_preliminar='1' WHERE id='" . $_SESSION['id_userSecaoMultiplo'] . "'";
	$resultado = mysql_query($sql) or die (mysql_error());

	echo json_encode(array('status'=>true));
}

else {
	
	echo json_encode(array('status'=>false));
	
}
?>