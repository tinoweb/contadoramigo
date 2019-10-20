<?php 
include "conect.php";

session_start();

$sql = "SELECT e.id FROM dados_da_empresa e INNER JOIN login l ON e.id = l.id WHERE l.idUsuarioPai = " . $_SESSION['id_userSecaoMultiplo'] . "";

$resultado = mysql_query($sql) or die (mysql_error());

echo (mysql_num_rows($resultado));

?>