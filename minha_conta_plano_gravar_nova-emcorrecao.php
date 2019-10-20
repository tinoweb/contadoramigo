<?php
include "conect.php";

$ID = $_POST["hidID"];
$plano = $_POST['radPlano'];

//Atualizar dados em dados de cobrança.
$sql = "UPDATE dados_cobranca SET plano='$plano' WHERE id='$ID'";
$resultado = mysql_query($sql)
or die (mysql_error());

header('Location: minha_conta.php' );
?>