<?
//Caso seja necessário modificar os códigos abaixo, modificar também em 'session.php'
//$conexao = mysql_connect("186.202.13.2", "contadoramigo", "ttq231kz");
$conexao = mysql_connect("177.153.16.160", "contadoramigo", "ttq231kz");
//mysql01.contadoramigo.siteprofissional.com
//mysql01.contadoramigo.com.br
$db = mysql_select_db("contadoramigo");
mysql_query("SET NAMES 'utf8'");
mysql_query('SET character_set_connection=utf8');
mysql_query('SET character_set_client=utf8');
mysql_query('SET character_set_results=utf8');
?>
