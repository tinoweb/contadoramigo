<?php include '../conect.php';

$arquivo = mysql_fetch_object(mysql_query('SELECT nome FROM rps WHERE id = "' . $_GET['download'] . '"'));

mysql_query('UPDATE rps SET baixado = 1 WHERE id = "' . $_GET['download'] . '"');

$nome_pasta = '../RPS/COBRANCA';

$file_name = $nome_pasta . "/" . $arquivo->nome;

header("Content-Disposition: attachment; filename=\"".$arquivo->nome."\""); 
header("Content-type: document/text");

readfile($file_name);
?>