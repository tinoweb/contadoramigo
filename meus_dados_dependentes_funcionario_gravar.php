<?php
include "conect.php";
include "session.php";

$acao 					= $_POST["acao"];

$ID = $_SESSION["id_userSecao"];

$idFuncionario = $_POST['hidFuncioanrioID'];
if($idFuncionario == '') $idFuncionario = $_POST['idFuncionario'];

$idDependente = $_POST['hidDependenteID'];
$nome = mysql_real_escape_string($_POST['txtNomeDependente']);
$vinculo = mysql_real_escape_string($_POST['selVinculoDependente']);
$sexo = $_POST['radSexoDependente'];
$cpf = $_POST['txtCPFDependente'];
$rg = $_POST['txtRGDependente'];
$dataEmissao = $_POST['txtDataEmissaoDependente'];
$orgaoExpedidor = mysql_real_escape_string($_POST['txtOrgaoExpedidorDependente']);
$dataNascimento = $_POST['txtDataNascimentoDependente'];
$endereco = mysql_real_escape_string($_POST['txtEnderecoDependente']);
$bairro = mysql_real_escape_string($_POST['txtBairroDependente']);
$cep = $_POST['txtCEPDependente'];
$cidade = mysql_real_escape_string($_POST['txtCidadeDependente']);
$estado = $_POST['selEstadoDependente'];

$invalidez = $_POST['radInvalidezDependente'];
//$tipoInvalidez = $_POST['radTempoInvalidezDependente'];
//$cid = $_POST['txtCIDInvalidezDependente'];

//Formatar CPF
$CPFFormatado = $cpf;//substr($CPF,0,3) . "." . substr($CPF,3,3) . "." . substr($CPF,6,3) . "-" . substr($CPF,9,2);

//Formatar RG
$RGFormatado = $rg;//number_format($RG / 10,1,"-",".");

//Formatar CEP
//$CEP = str_replace(array("/","-","."),"",$CEP);
$CEPFormatado = $cep;//substr($CEP,0,5) . "-" . substr($CEP,5,3);

if($acao == 'inserir'){
	$sql = "INSERT INTO dados_dependentes_funcionario SET 
						idFuncionario='" . $idFuncionario . "'
						, nome='" . $nome . "'
						, sexo='" . $sexo . "'
						, vinculo='" . $vinculo . "'
						, cpf='" . $CPFFormatado . "'
						, rg='" . $RGFormatado . "'
						, data_emissao='" . $dataEmissao . "'
						, orgao_expeditor='" . $orgaoExpedidor . "'
						, data_de_nascimento='" . $dataNascimento . "'
						, endereco='" . $endereco . "'
						, bairro='" . $bairro . "'
						, cep='" . $CEPFormatado . "'
						, cidade='" . $cidade . "'
						, estado='" . $estado . "'
						, invalidez='" . $invalidez . "'
						";
	
}else{
	
	//Atualizar dados em dados da empresa.
	$sql = "UPDATE dados_dependentes_funcionario SET 
						nome='" . $nome  . "'
						, sexo='" . $sexo  . "'
						, vinculo='" . $vinculo . "'
						, cpf='" . $cpf  . "'
						, rg='" . $rg  . "'
						, data_emissao='" . $dataEmissao  . "'
						, orgao_expeditor='" . $orgaoExpedidor  . "'
						, data_de_nascimento='" . $dataNascimento  . "'
						, endereco='" . $endereco  . "'
						, bairro='" . $bairro  . "'
						, cep='" . $cep  . "'
						, cidade='" . $cidade  . "'
						, estado='" . $estado  . "'
						, invalidez='" . $invalidez  . "'
					WHERE 
						idDependente = " . $idDependente . "";

}

//echo $sql;
//exit;

$resultado = mysql_query($sql)
or die (mysql_error());

if(strlen($_POST['pgOrigem']) > 0){

	$proximo_passo = $_POST['pgOrigem'];

}else{

	$proximo_passo = "meus_dados_funcionario.php";
	
}
//$rsValeTransporte = mysql_fetch_array(mysql_query("SELECT vale_transporte FROM dados_do_funcionario WHERE idFuncionario = " . $idFuncionario));
//if($rsValeTransporte['vale_transporte'] == '1'){
//	$proximo_passo = "meus_dados_transporte_funcionario.php";
//}

if(isset($_POST['idFuncionario']) && $_POST['idFuncionario'] != ''){
	echo '1';
}else{
	header('Location: ' . $proximo_passo );
}


?>