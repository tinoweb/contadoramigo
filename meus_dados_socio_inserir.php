<?php
include "conect.php";

$id = $_GET["id"];

$sql = "INSERT INTO dados_do_responsavel (id, responsavel, sexo, estado, retira_pro_labore) VALUES ('$id', '0', 'Masculino', 'SP', 'sim')";
$resultado = mysql_query($sql)
or die (mysql_error());

header('Location: meus_dados_socio.php' );
?>