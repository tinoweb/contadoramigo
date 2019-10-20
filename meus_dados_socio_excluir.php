<?php 
include 'conect.php';
include 'session.php';
include 'check_login.php';

$socio = $_GET["socio"];

// pegando ID da empresa do sócio selecionado
$sql = "SELECT id,responsavel FROM dados_do_responsavel WHERE idSocio='" . $socio . "' LIMIT 0,1";
$resultado = mysql_query($sql)
or die (mysql_error());

$id=mysql_fetch_array($resultado);
$_SESSION["aviso"]= "";

// CHECANDO SE O SOCIO EXCLUIDO É O RESPONSAVEL
if($id["responsavel"] == '1'){
	// COLOCANDO O PRIMEIRO SOCIO COMO RESPONSAVEL
	$novo_responsavel = mysql_fetch_array(mysql_query("SELECT nome, idSocio FROM dados_do_responsavel WHERE idSocio = (SELECT min(idSocio) FROM dados_do_responsavel WHERE id = " . $id["id"] . " AND responsavel = 0)"));
	
	$nome_novo_responsavel = $novo_responsavel['nome'];
	
	mysql_query("UPDATE dados_do_responsavel SET responsavel = 1 WHERE idSocio = ".$novo_responsavel['idSocio']."");
	$_SESSION["aviso2"] = "O novo Sócio Responsável é " . $nome_novo_responsavel . ".";
}


// CHECANDO SE HÁ PAGAMENTOS PARA O SOCIO A SER EXCLUIDO
$pagamentos = mysql_fetch_array(mysql_query("SELECT id_pagto FROM dados_pagamentos WHERE id_socio = '" . $socio . "'"));
if(is_array($pagamentos)){

	mysql_query("UPDATE dados_do_responsavel SET responsavel = 0, status = 2 WHERE idSocio = ".$socio."");
	$_SESSION["aviso1"] = "Este sócio não pôde ser removido pois há pagamentos de pró-labore registrados em nome dele. Ao invés disso, seu status foi alterado para: \"ex-sócio\"." . '\r';

}else{
	
//	if ($_SESSION["id_empresaSecao"] == $id["id"]){
		$sql = "SELECT * FROM dados_do_responsavel WHERE id='" . $_SESSION["id_empresaSecao"] . "'";
		$resultado = mysql_query($sql)
		or die (mysql_error());
		
		$totalSocio = mysql_num_rows($resultado);
		
		if ($totalSocio != 1) {
			$sql = "DELETE FROM dados_do_responsavel WHERE idSocio='" . $socio . "'";
			$resultado = mysql_query($sql)
			or die (mysql_error());
		}
//	}
}

$_SESSION['aviso'] = $_SESSION['aviso1'] . $_SESSION['aviso2'];

header('Location: meus_dados_socio.php' );
?>