<?php
include "conect.php";
include "session.php";

if($_SESSION['status_userSecao'] != 'demoInativo') {
	$sql = "DELETE FROM historico_cobranca WHERE id='" . $_SESSION["id_userSecao"] . "' AND status_pagamento='pendente'";
	$resultado = mysql_query($sql)
	or die (mysql_error());
	
	$sql = "UPDATE historico_cobranca SET tipo_cobranca='', status_pagamento='pendente' WHERE id='" . $_SESSION["id_userSecao"] . "' AND status_pagamento='pago'";
	$resultado = mysql_query($sql)
	or die (mysql_error());
	
	$sql = "UPDATE login SET status='demoInativo' WHERE idUsuarioPai='" . $_SESSION["id_userSecao"] . "'";
	$resultado = mysql_query($sql)
	or die (mysql_error());
	$_SESSION['status_userSecao'] = 'demoInativo';
}

header('Location: minha_conta.php');
?>