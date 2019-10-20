<?php
include "../conect.php";

if(isset($_POST["hidID"]) && $_POST["hidID"] != ''){
	
	mysql_query("UPDATE faq SET pergunta = '" . mysql_real_escape_string($_POST['txtPergunta']) . "', pergunta_ordem = '" . mysql_real_escape_string($_POST['hidPerguntaTxt']) . "', resposta = '" . mysql_real_escape_string($_POST['txtResposta']) . "' WHERE id_faq = " . $_POST["hidID"])	or die (mysql_error());
	
}else{

	mysql_query("INSERT INTO faq SET pergunta = '" . mysql_real_escape_string($_POST['txtPergunta']) . "', pergunta_ordem = '" . mysql_real_escape_string($_POST['hidPerguntaTxt']) . "', resposta = '" . mysql_real_escape_string($_POST['txtResposta']) . "'")	or die (mysql_error());
	
}

header('Location: faq_lista.php' );

echo $Data;
?>