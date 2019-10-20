<?php
session_start();
if (!isset($_SESSION["id_userSecao"])){
	
	if(!isset($_COOKIE["contadoramigoHTTPS"]) || $_COOKIE["contadoramigoHTTPS"]==""){

		header('Location: '.$dominio.'assinatura_pagina_restrita.php' );
		exit;
	
	}
	
}

include "conect.php";


//var_dump($_POST);

//echo $_SESSION['id_userSecaoMultiplo'];
//
//
//echo $_POST["hidID"] . "<BR>";
//echo $_POST["acao"] . "<BR>";
//exit;

$acao = $_POST['acao'];

$ID = $_POST["hidID"];
$RazaoSocial = mysql_real_escape_string($_POST["txtRazaoSocial"]);
$NomeFantasia = mysql_real_escape_string($_POST["txtNomeFantasia"]);
$CNPJ = $_POST["txtCNPJ"];
$InscricaoCCM = $_POST["txtInscricaoCCM"];
$InscricaoEstadual = $_POST["txtInscricaoEstadual"];
$Endereco = mysql_real_escape_string($_POST["txtEndereco"]);
$Bairro = mysql_real_escape_string($_POST["txtBairro"]);
$CEP = $_POST["txtCEP"];
$Cidade = mysql_real_escape_string($_POST["txtCidade"]);

// alterado em 27/11/2014 - adequando todos os cadastros para combo de cidades
$arrEstado = explode(';',$_POST['selEstado']);
$Estado = $arrEstado[1];
//$Estado = $_POST["selEstado"];



$PrefixoTelefone = preg_replace("/\W+/","",$_POST["txtPrefixoTelefone"]);
$Telefone = preg_replace("/\W+/","",$_POST["txtTelefone"]);
$RamoAtividade = $_POST["selRamoAtividade"];
$CodigoAtividadePrefeitura = $_POST["txtCodigoAtividadePrefeitura"];
$RegimeTributacao = $_POST["selRegimeTributacao"];
$InscritaComo = $_POST["selInscritaComo"];
$RegistroNire = $_POST["txtRegistroNire"];
$NumCartorio = $_POST["txtNumCartorio"];
$RegistroCartorio = $_POST["txtRegistroCartorio"];
$DataCriacao = substr($_POST["txtDataCriacao"],6,4) . "-" . substr($_POST["txtDataCriacao"],3,2) . "-" . substr($_POST["txtDataCriacao"],0,2);

$Recolhe_cprb = $_POST['txt_recolhe_cprb'];
$registrado_em = $_POST['registrado_em'];
//Formatar CNPJ
//$CNPJFormatado = $CNPJ;//substr($CNPJ,0,2) . "." . substr($CNPJ,2,3) . "." . substr($CNPJ,5,3) . "/" . substr($CNPJ,8,4) . "-" . substr($CNPJ,12,2);

//Formatar CEP
$CEPFormatado = $CEP;//substr($CEP,0,5) . "-" . substr($CEP,5,3);

//Formatar NIRE
$RegistroNireFormatado = $RegistroNire;//substr($RegistroNire,0,10) . "-" . substr($RegistroNire,10,1);

if($acao == 'editar'){
	//Apaga Códigos já existentes
	$sql = "DELETE FROM dados_da_empresa_codigos WHERE id='$ID'";
	$resultado = mysql_query($sql) or die (mysql_error());
}



//Insere os códigos novos
$CodigoCNAE = $_POST["txtCNAE_Principal"];


if($acao == "inserir"){
		
	
	// CHECANDO SE É A PRIMEIRA EMPRESA CADASTRADA
//	$sqlCount = "SELECT count(*) total FROM dados_da_empresa WHERE id='$ID'";
//	$resultadoCount = mysql_query($sqlCount) or die (mysql_error());
//	$rsCountEmpresas = mysql_fetch_array($resultadoCount);
	

	
	//Gravar dados em login.
	$sql = "INSERT INTO login (nome, assinante, email, senha, status, id_plano, info_preliminar, idUsuarioPai) SELECT nome, assinante, email, senha, status, id_plano, info_preliminar, idUsuarioPai FROM login WHERE id = " . $_SESSION['id_userSecaoMultiplo'];
	$resultado = mysql_query($sql) or die (mysql_error());

	$ID = mysql_insert_id();
}





if ($CodigoCNAE != "") {
	$ConsultaCNAE = str_replace(array("/","-","."),"",$CodigoCNAE);

	$sql = "SELECT * FROM cnae WHERE REPLACE(REPLACE(REPLACE(cnae,'.',''),'-',''),'/','')='$ConsultaCNAE' LIMIT 0,1";
	$resultado = mysql_query($sql)
	or die (mysql_error());
	$linha=mysql_fetch_array($resultado);

	$sql = "INSERT INTO dados_da_empresa_codigos (id, cnae, tipo) VALUES ('$ID', '" . $linha["cnae"] . "', '1')";
	$resultado = mysql_query($sql)
	or die (mysql_error());
}

$count = $_POST["skill_count"] - 1;
$campo = 1;

for($i=0;$i<=$count;$i++) {
	$CodigoCNAE = $_POST["txtCodigoCNAE".$campo];
	$ConsultaCNAE = str_replace(array("/","-","."),"",$CodigoCNAE);
	
	
	if ($CodigoCNAE!=""){
		//Consultar CNAE com grafia correta
		$sql = "SELECT * FROM cnae WHERE REPLACE(REPLACE(REPLACE(cnae,'.',''),'-',''),'/','')='$ConsultaCNAE' LIMIT 0,1";
		$resultado = mysql_query($sql)
		or die (mysql_error());
		$linha=mysql_fetch_array($resultado);
		
		$sql = "INSERT INTO dados_da_empresa_codigos (id, cnae, tipo) VALUES ('$ID', '" . $linha["cnae"] . "', '2')";
		$resultado = mysql_query($sql)
		or die (mysql_error());
	}
	$campo = $campo + 1;
}



if($acao == 'editar'){
	$sql_comando = "UPDATE ";
	$sql_CNPJ = "";
	$sql_ID = " WHERE id='".$ID."'";
}else{
	$sql_comando = "INSERT INTO ";
	$sql_CNPJ = ", cnpj = '" . $CNPJ . "'";
	$sql_ID = ", id='".$ID."'";
}



$sql = $sql_comando . " dados_da_empresa SET razao_social='$RazaoSocial', nome_fantasia='$NomeFantasia'" . $sql_CNPJ . ", inscricao_no_ccm='$InscricaoCCM', inscricao_estadual='$InscricaoEstadual', endereco='$Endereco', bairro='$Bairro', cep='$CEPFormatado', cidade='$Cidade', estado='$Estado', pref_telefone='$PrefixoTelefone', telefone='$Telefone', ramo_de_atividade='$RamoAtividade', codigo_de_atividade_prefeitura='$CodigoAtividadePrefeitura', regime_de_tributacao='$RegimeTributacao', inscrita_como='$InscritaComo', registro_nire='$RegistroNireFormatado', data_de_criacao='$DataCriacao', recolhe_cprb='$Recolhe_cprb', registrado_em='$registrado_em'" . $sql_ID;


$resultado = mysql_query($sql)
or die (mysql_error());


if($acao == 'editar'){
	//Apaga códigos da prefeitura já existentes
	$sql = "DELETE FROM dados_da_empresa_codigo_prefeitura WHERE id='$ID'";
	$resultado = mysql_query($sql)
	or die (mysql_error());
}
	

if($InscritaComo == 'Sociedade Simples'){
	$sql = "UPDATE dados_da_empresa SET registro_nire='', numero_cartorio='$NumCartorio', registro_cartorio='$RegistroCartorio' WHERE id='$ID'";
	$resultado = mysql_query($sql)
	or die (mysql_error());
} else {
	$sql = "UPDATE dados_da_empresa SET registro_nire='$RegistroNireFormatado', numero_cartorio='', registro_cartorio='' WHERE id='$ID'";
	$resultado = mysql_query($sql)
	or die (mysql_error());
}

//Atualizar nome no login.
$sql = "UPDATE login SET nome='$RazaoSocial' WHERE id='$ID'";
$resultado = mysql_query($sql)
or die (mysql_error());



if($acao == "inserir"){
	//Gravar dados em dados do responsavel.
	//$sql = "INSERT INTO dados_do_responsavel (id) VALUES ('$ID')";
	//$resultado = mysql_query($sql)
	//or die (mysql_error());
	
	//Criar tabela para o Livro Caixa.
	$sql = "CREATE TABLE user_" . $ID . "_livro_caixa (id int(30) NOT NULL AUTO_INCREMENT UNIQUE, data date NOT NULL, entrada decimal(50,2) NOT NULL, saida decimal(50,2) NOT NULL, documento_numero varchar(20) NOT NULL, descricao varchar(200) NOT NULL, categoria varchar(125), PRIMARY KEY (id))";
	$resultado = mysql_query($sql)
	or die (mysql_error());
}



///session_start();


//$rsTotalEmpresas = mysql_fetch_array(mysql_query("SELECT count(*) totalEmpresas FROM login WHERE idUsuarioPai = '" . $_SESSION['id_userSecaoMultiplo'] . "'"));

///unset($_SESSION["nome_userSecao"]);
///$_SESSION["nome_userSecao"]=$RazaoSocial;

if($acao == "inserir"){
	$_SESSION['n_empresasVinculadas']++;
}

//echo "1";

echo json_encode(array('status'=>true, 'IDEmpresa'=>$ID, 'qtdEmpresas'=>$_SESSION['n_empresasVinculadas']));

//header('Location: meus_dados_empresa.php' )
?>