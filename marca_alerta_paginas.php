<?php
include "conect.php";

$id_login = $_POST['id_login'];
$nome_pagina = $_POST['nome_pagina'];
$status = $_POST['status'];

$checa = mysql_query("SELECT id FROM alertas_usuarios_paginas WHERE id_login = " . $id_login . " AND nome_pagina = '" . $nome_pagina . "'");

if(mysql_num_rows($checa) <= 0){
	mysql_query("INSERT INTO alertas_usuarios_paginas SET id_login = " . $id_login . ", nome_pagina = '" . $nome_pagina . "', status_alerta = " . $status);
}else{
	mysql_query("UPDATE alertas_usuarios_paginas SET status_alerta = " . $status . " WHERE id_login = " . $id_login . " AND nome_pagina = '" . $nome_pagina . "'");
}

?>