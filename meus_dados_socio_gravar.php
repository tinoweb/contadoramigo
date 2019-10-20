<?php
include "conect.php";
include "session.php";

$acao 					= $_POST["acao"];

$ID = $_SESSION["id_empresaSecao"];
$SocioID = $_POST["hidSocioID"];
$Nome = mysql_real_escape_string($_POST["txtNome"]);
$Sexo = $_POST["radSexo"];
$DataAdmissao = $_POST["txtDataAdmissao"];
$Nacionalidade = $_POST["txtNacionalidade"];
$Naturalidade = $_POST["txtNaturalidade"];
$EstadoCivil = $_POST["selEstadoCivil"];
$Profissao = mysql_real_escape_string($_POST["txtProfissao"]);
$CPF = $_POST["txtCPF"];
$RG = $_POST["txtRG"];
$RNE = $_POST["txtRNE"];
$DataEmissao = $_POST["txtDataEmissao"];
$OrgaoExpedidor = $_POST["txtOrgaoExpedidor"];
$DataNascimento = $_POST["txtDataNascimento"];
$Endereco = mysql_real_escape_string($_POST["txtEndereco"]);
$Bairro = mysql_real_escape_string($_POST["txtBairro"]);
$CEP = $_POST["txtCEP"];
$Cidade = mysql_real_escape_string($_POST["txtCidade"]);

// alterado em 27/11/2014 - adequando todos os cadastros para combo de cidades
$arrEstado = explode(';',$_POST['selEstado']);
$Estado = $arrEstado[1];
//$Estado = $_POST["selEstado"];


$PrefixoTelefone = $_POST["txtPrefixoTelefone"];
$Telefone = $_POST["txtTelefone"];
$Email = $_POST['txtEmail'];
$CodigoCBO = $_POST["txtCodigoCBO"];
$funcao = $_POST["txtFuncao"];

// PERDEU A FUNÇÃO POIS OS DADOS REFERENTES A PRO LABORE ESTAO NA PARTE DE PAGAMENTOS
/*
if(isset($_POST["cheRetiraProLabore"])){
	$retiraProLabore = "não";
	$ProLabore = "";
}else{
	$retiraProLabore = "sim";
	$ProLabore = str_replace(",",".",str_replace(".","",$_POST["txtProLabore"]));
}
*/
$Nit = $_POST["txtNit"];
$SocioResponsavelID = $_POST["radResponsavel"];

if($SocioResponsavelID != ''){

	$sqlSocioResponsavel = ",responsavel='1'";

}else{

	$sqlSocioResponsavel = ",responsavel='0'";

}

	//INICIO ALTERAÇÂO 04/05/2016 - SELECIONAR APENAS UM SOCIO RESPONSÁVEL - ARQUIVOS: meus_dados_socio.php e meus_dados_socio_gravar.php

	#Caso venha selecionado como socio responsável deverá setar todos os outros socios cadastrados com o id do usuario como socios normais

	if( $SocioResponsavelID == '1' ){
		
		$consulta = mysql_query("UPDATE dados_do_responsavel set responsavel = '0' WHERE id='".$_SESSION["id_empresaSecao"]."' ");
		
	}

	//FIM ALTERAÇÕES 04/05/2016


// NOVOS CAMPOS INSERIDOS EM 2013-05-27 PARA PODER USAR O PAGAMENTO PARA OS SOCIOS
$dependentes = $_POST["txtNumeroDep"];
$pensao = ($_POST["pensao"] == '1' ? '1' : '0');
$perc_pensao = $_POST["PercentPensao"];

//Formatar CPF
$CPFFormatado = $CPF;//substr($CPF,0,3) . "." . substr($CPF,3,3) . "." . substr($CPF,6,3) . "-" . substr($CPF,9,2);

//Formatar RG
$RGFormatado = $RG;//number_format($RG / 10,1,"-",".");

//Formatar CEP
//$CEP = str_replace(array("/","-","."),"",$CEP);
$CEPFormatado = $CEP;//substr($CEP,0,5) . "-" . substr($CEP,5,3);

//Formatar CBO
//$CodigoCBO = str_replace(array("/","-","."),"",$CodigoCBO);
$CodigoCBOFormatado = $CodigoCBO;//substr($CodigoCBO,0,4) . "-" . substr($CodigoCBO,4,2);

//Formatar NIT
$Nit = str_replace(array("/","-","."),"",$Nit);
$NITFormatado = $Nit;//substr($Nit,0,1) . "." . substr($Nit,1,3) . "." . substr($Nit,4,3) . "." . substr($Nit,7,3) . "-" . substr($Nit,10,1);

$tipo					= $_POST["tipo"];

$tipo_conta				= $_POST["tipo_conta"] != "" ? "'".$_POST["tipo_conta"]."'" : "null";
$id_banco					= $_POST["id_banco"] != "" ? "".$_POST["id_banco"]."" : "0";
$num_agencia			= $_POST["agencia"] != "" ? "'".$_POST["agencia"]."'" : "null";
$dig_agencia			= $_POST["dig_agencia"] != "" ? "'".$_POST["dig_agencia"]."'" : "null";
$num_conta				= $_POST["conta"] != "" ? "'".$_POST["conta"]."'" : "null";
$dig_conta				= $_POST["dig_conta"] != "" ? "'".$_POST["dig_conta"]."'" : "null";


if($acao == 'inserir'){
	$sql = "INSERT INTO dados_do_responsavel SET 
						id='$ID'
						, nome='$Nome'
						, sexo='$Sexo'
						, data_admissao='$DataAdmissao'
						, nacionalidade='$Nacionalidade'
						, naturalidade='$Naturalidade'
						, estado_civil='$EstadoCivil'
						, profissao='$Profissao'
						, cpf='$CPFFormatado'
						, rg='$RGFormatado'
						, rne='$RNE'
						, data_de_emissao='$DataEmissao'
						, orgao_expeditor='$OrgaoExpedidor'
						, data_de_nascimento='$DataNascimento'
						, endereco='$Endereco'
						, bairro='$Bairro'
						, cep='$CEPFormatado'
						, cidade='$Cidade'
						, estado='$Estado'
						, pref_telefone='$PrefixoTelefone'
						, telefone='$Telefone'
						, email_socio='$Email'
						, codigo_cbo='$CodigoCBOFormatado'
						, funcao='$funcao'
						, nit='$Nit'
						, dependentes='$dependentes'
						, pensao='$pensao'
						, perc_pensao='$perc_pensao'
						, tipo_conta=$tipo_conta
						, id_banco=$id_banco
						, agencia=$num_agencia
						, dig_agencia=$dig_agencia
						, tipo='$tipo'
						, conta=$num_conta
						, dig_conta=$dig_conta
						" . $sqlSocioResponsavel . "
						";
	$last_id = mysql_insert_id();
}else{
	
	//Atualizar dados em dados da empresa.
	$sql = "UPDATE dados_do_responsavel SET 
						nome='$Nome'
						, sexo='$Sexo'
						, data_admissao='$DataAdmissao'
						, nacionalidade='$Nacionalidade'
						, naturalidade='$Naturalidade'
						, estado_civil='$EstadoCivil'
						, profissao='$Profissao'
						, cpf='$CPFFormatado'
						, rg='$RGFormatado'
						, rne='$RNE'
						, data_de_emissao='$DataEmissao'
						, orgao_expeditor='$OrgaoExpedidor'
						, data_de_nascimento='$DataNascimento'
						, endereco='$Endereco'
						, bairro='$Bairro'
						, cep='$CEPFormatado'
						, cidade='$Cidade'
						, estado='$Estado'
						, pref_telefone='$PrefixoTelefone'
						, telefone='$Telefone'
						, email_socio='$Email'
						, codigo_cbo='$CodigoCBOFormatado'
						, funcao='$funcao'
						, nit='$Nit'
						, dependentes='$dependentes'
						, pensao='$pensao'
						, perc_pensao='$perc_pensao'
						, tipo_conta=$tipo_conta
						, id_banco=$id_banco
						, agencia=$num_agencia
						, dig_agencia=$dig_agencia
						, tipo='$tipo'
						, conta=$num_conta
						, dig_conta=$dig_conta
						" . $sqlSocioResponsavel . "						
					WHERE idSocio='$SocioID'";

	$last_id = $SocioID;
}

//echo $sql;
//exit;

$resultado = mysql_query($sql)
or die (mysql_error());
/*
if($SocioResponsavelID != ''){

	$sql = "UPDATE dados_do_responsavel SET responsavel='0' WHERE id='$ID'";
	$resultado = mysql_query($sql)
	or die (mysql_error());
	
	$sql = "UPDATE dados_do_responsavel SET responsavel='1' WHERE idSocio='$SocioResponsavelID'";
	$resultado = mysql_query($sql)
	or die (mysql_error());
	
}
*/
header('Location: meus_dados_socio.php?editar='.$last_id.' ' );
?>