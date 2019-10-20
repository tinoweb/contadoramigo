<?php
include "../conect.php";
$ID = $_POST["hidID"];
$Status = $_POST["selStatus"];
/*
echo $ID . "-" . $Status;
exit*/;

//Atualizar o Premio para o usuario
$sql = "UPDATE dados_indicacoes SET premiada='$Status' WHERE id='$ID'";
/*echo $sql;
exit;
*/
$resultado = mysql_query($sql);
if(mysql_affected_rows() > 0){
	echo "Status alterado com sucesso!";	
}else{
	echo "Ocorreu um erro na atualização do prêmio!";	
}

?>