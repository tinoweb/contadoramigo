<?
include "conect.php";

session_start();

$ID = $_SESSION["id_empresaSecao"];

switch($_REQUEST["hddCategoria_livro_caixa"]){
	case "Estagiários":
	
		$Valor = str_replace(",",".",str_replace(".","",$_REQUEST["ValorBruto"]));
		if(isset($_REQUEST["hddCategoria_livro_caixa"]) && $_REQUEST["hddCategoria_livro_caixa"] != ""){
			$categoria = ", categoria = '" . mysql_real_escape_string($_REQUEST["hddCategoria_livro_caixa"]) . "'";
		}
		$Descricao = mysql_real_escape_string("" . $_REQUEST["nome"]);
		$dia = substr($_REQUEST["DataPgto"], 0, 2);
		$mes = substr($_REQUEST["DataPgto"], 3, 2);
		$ano = substr($_REQUEST["DataPgto"], 6, 4);
		$Data = date('Y-m-d',mktime(0,0,0,$mes,$dia,$ano));
	
	break;
	
	case "Pró-Labore":
	
		$Valor = str_replace(",",".",str_replace(".","",$_REQUEST["ValorLiquido"]));
		if(isset($_REQUEST["hddCategoria_livro_caixa"]) && $_REQUEST["hddCategoria_livro_caixa"] != ""){
			$categoria = ", categoria = '" . mysql_real_escape_string($_REQUEST["hddCategoria_livro_caixa"]) . "'";
		}
		$Descricao = mysql_real_escape_string("" . $_REQUEST["nome"]);
		$dia = substr($_REQUEST["DataPgto"], 0, 2);
		$mes = substr($_REQUEST["DataPgto"], 3, 2);
		$ano = substr($_REQUEST["DataPgto"], 6, 4);
		$Data = date('Y-m-d',mktime(0,0,0,$mes,$dia,$ano));
	
	break;
	
	case "Distribuição de lucros":
	
		$Valor = str_replace(",",".",str_replace(".","",$_REQUEST["ValorLiquido"]));
		if(isset($_REQUEST["hddCategoria_livro_caixa"]) && $_REQUEST["hddCategoria_livro_caixa"] != ""){
			$categoria = ", categoria = '" . mysql_real_escape_string($_REQUEST["hddCategoria_livro_caixa"]) . "'";
		}
		$Descricao = mysql_real_escape_string($_REQUEST["nome"]);
		$dia = substr($_REQUEST["DataPgto"], 0, 2);
		$mes = substr($_REQUEST["DataPgto"], 3, 2);
		$ano = substr($_REQUEST["DataPgto"], 6, 4);
		$Data = date('Y-m-d',mktime(0,0,0,$mes,$dia,$ano));
	
	break;

	
	case "Pgto. a autônomos e fornecedores":
		
		if(isset($_REQUEST["hddCategoria_livro_caixa"]) && $_REQUEST["hddCategoria_livro_caixa"] != ""){
			$categoria = ", categoria = '" . mysql_real_escape_string($_REQUEST["hddCategoria_livro_caixa"]) . "'";
		}
		if($_REQUEST['tipo'] == "PJ"){
			$Descricao = mysql_real_escape_string("" . $_REQUEST["nome"]);
			$Valor = str_replace(",",".",str_replace(".","",$_REQUEST["Liquido"]));
		}else{
			$Descricao = mysql_real_escape_string("" . $_REQUEST["nome"]);
			$Valor = str_replace(",",".",str_replace(".","",$_REQUEST["ValorLiquido"]));
		}
		$dia = substr($_REQUEST["DataPgto"], 0, 2);
		$mes = substr($_REQUEST["DataPgto"], 3, 2);
		$ano = substr($_REQUEST["DataPgto"], 6, 4);
		$Data = date('Y-m-d',mktime(0,0,0,$mes,$dia,$ano));
	
	break;
	
	
}


$sql = "INSERT INTO user_" . $ID . "_livro_caixa SET
	data = '$Data'
	, saida = '$Valor'
	, descricao = '$Descricao'
	" . $categoria . "
	";

$resultado = mysql_query($sql)
or die (mysql_error());

// ATUALIZANDO A TABELA DE PAGAMENTOS COM O ID DO REGISTRO DO LIVRO CAIXA PARA POSER EXCLUIR E FAZER VALIDAÇÕES NECESSÁRIAS
mysql_query("UPDATE dados_pagamentos SET idLivroCaixa = " . mysql_insert_id() . " WHERE id_pagto = " . $_REQUEST['idPagto'] . "");

echo 1;
?>