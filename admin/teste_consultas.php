<?php 
include '../conect.php';

include '../session.php';


$sql = "SELECT * FROM tbl_TESTES WHERE MONTH(campo1) = '8' AND YEAR(campo1) = '2015'";

if(mysql_num_rows(mysql_query($sql)) > 0){
	echo "executa";
}

?>