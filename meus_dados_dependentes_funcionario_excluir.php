<?php
include "conect.php";
include "session.php";

$ID = $_GET["dep"];

$sql="DELETE FROM dados_dependentes_funcionario WHERE idDependente = " . $ID . "";

$resultado = mysql_query($sql)
or die (mysql_error());

//$_SESSION['aviso'] = 'Dados excluídos com sucesso!';


//header('Location: ' . basename($_SERVER['HTTP_REFERER']) );
echo "1";
?>